<?php

namespace App\Services\AI;

use App\DTO\EstimationContextDTO;
use App\DTO\ProjectBasicsDTO;
use App\DTO\RequirementsDTO;
use App\Exceptions\AiEstimationFailedException;
use App\Exceptions\InvalidEstimationResponseException;
use Illuminate\Support\Facades\Log;

class EstimationAiClient implements EstimationAiClientInterface
{
    public function __construct(
        private readonly mixed $prismClient // Will be injected via service provider
    ) {}

    public function generateEstimatePayload(
        ProjectBasicsDTO $projectBasics,
        RequirementsDTO $requirements,
        EstimationContextDTO $context
    ): array {
        try {
            $prompt = $this->buildPrompt($projectBasics, $requirements, $context);

            Log::info('Sending AI estimation request', [
                'project_type' => $projectBasics->projectType->value,
                'domain_type' => $projectBasics->domainType->value,
                'requirements_length' => $requirements->getCharacterCount(),
                'tech_stack' => $context->getTechStackString(),
            ]);

            // Call PrismPHP client (will be configured for DeepSeek)
            $response = $this->prismClient->generateResponse($prompt, [
                'model' => config('ai.model', 'deepseek-coder'),
                'max_tokens' => 4000,
                'temperature' => 0.1, // Low temperature for consistent estimates
            ]);

            if (empty($response)) {
                throw new AiEstimationFailedException(
                    'Empty response from AI service',
                    ['prompt_length' => strlen($prompt)]
                );
            }

            return $this->parseAiResponse(['content' => $response]);

        } catch (InvalidEstimationResponseException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('AI estimation request failed', [
                'error' => $e->getMessage(),
                'project_type' => $projectBasics->projectType->value,
            ]);

            throw new AiEstimationFailedException(
                'AI estimation request failed: '.$e->getMessage(),
                [
                    'project_name' => $projectBasics->name,
                    'error_type' => get_class($e),
                ],
                0,
                $e
            );
        }
    }

    public function buildPrompt(
        ProjectBasicsDTO $projectBasics,
        RequirementsDTO $requirements,
        EstimationContextDTO $context
    ): string {
        $hasDeadline = $context->hasFixedDeadline();
        $deadlineText = $hasDeadline ? ($context->fixedDeadline?->format('Y-m-d') ?? 'Invalid') : 'No';
        $complianceText = $context->isHighCompliance ? 'Yes' : 'No';

        return <<<PROMPT
You are an expert software project estimator. Generate a detailed project estimation based on the following information.

**Project Details:**
- Name: {$projectBasics->name}
- Type: {$projectBasics->projectType->label()}
- Domain: {$projectBasics->domainType->label()}
- Quality Level: {$projectBasics->qualityLevel->label()}
- Client: {$projectBasics->clientName}
- Description: {$projectBasics->shortDescription}

**Requirements:**
{$requirements->rawText}

**Context:**
- Country/Region: {$context->workforceCountryCode}
- Team Seniority: {$context->teamSeniority->label()}
- Tech Stack: {$context->getTechStackString()}
- High Compliance: {$complianceText}
- Fixed Deadline: {$deadlineText}
- Fixed Budget: {$context->getFixedBudgetFormatted()}

**Instructions:**
Provide a detailed estimation in the following JSON format. Be realistic and consider:
- Project complexity and scope
- Team experience level
- Technology choices
- Quality requirements
- Regional factors

```json
{
    "total_hours_min": 100,
    "total_hours_max": 150,
    "confidence": "medium",
    "modules": [
        {
            "name": "User Authentication",
            "description": "User registration, login, password reset",
            "total_hours_min": 20,
            "total_hours_max": 30,
            "priority": "high",
            "features": [
                {
                    "name": "User Registration",
                    "description": "Registration form with validation",
                    "hours_min": 8,
                    "hours_max": 12,
                    "complexity": "medium",
                    "dependencies": []
                }
            ]
        }
    ],
    "recommended_team": [
        {
            "role": "backend_developer",
            "count": 1,
            "total_hours": 60,
            "description": "API development and database design"
        }
    ],
    "assumptions": [
        "Using standard authentication libraries",
        "Database is MySQL or PostgreSQL"
    ],
    "risks": [
        "Third-party integration complexity",
        "Changing requirements during development"
    ],
    "questions_to_clarify": [
        "What is the expected user load?",
        "Are there specific security requirements?"
    ]
}
```

**Important Guidelines:**
1. Break down into logical modules with features
2. Use realistic hour estimates based on complexity
3. Consider the specified tech stack and team seniority
4. Include proper assumptions and risk factors
5. Suggest clarifying questions for better estimation
6. Ensure JSON is valid and follows the exact schema
7. Use only these role types: backend_developer, frontend_developer, fullstack_developer, devops_engineer, ui_ux_designer, project_manager, qa_engineer, business_analyst, data_scientist, mobile_developer
8. Use only these complexity levels: low, medium, high
9. Use only these priority levels: low, medium, high
10. Use only these confidence levels: low, medium, high

Respond with ONLY the JSON, no additional text or explanation.
PROMPT;
    }

    public function parseAiResponse(array $response): array
    {
        $content = $response['content'] ?? '';

        if (empty($content)) {
            throw new InvalidEstimationResponseException(
                'Empty content in AI response',
                $response
            );
        }

        // Extract JSON from response (in case there's extra text)
        $jsonMatch = null;
        if (preg_match('/\{.*\}/s', $content, $jsonMatch)) {
            $content = $jsonMatch[0];
        }

        $decoded = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidEstimationResponseException(
                'Invalid JSON in AI response: '.json_last_error_msg(),
                ['raw_response' => $content],
                json_last_error_msg()
            );
        }

        // Validate required fields
        $requiredFields = [
            'total_hours_min',
            'total_hours_max',
            'confidence',
            'modules',
            'recommended_team',
        ];

        foreach ($requiredFields as $field) {
            if (! isset($decoded[$field])) {
                throw new InvalidEstimationResponseException(
                    "Missing required field: {$field}",
                    $decoded,
                    "Missing field: {$field}"
                );
            }
        }

        // Validate data types and ranges
        $this->validateEstimationData($decoded);

        // Set defaults for optional fields
        $decoded['assumptions'] = $decoded['assumptions'] ?? [];
        $decoded['risks'] = $decoded['risks'] ?? [];
        $decoded['questions_to_clarify'] = $decoded['questions_to_clarify'] ?? [];

        return $decoded;
    }

    private function validateEstimationData(array $data): void
    {
        // Validate hours
        if ($data['total_hours_min'] < 1 || $data['total_hours_max'] < 1) {
            throw new InvalidEstimationResponseException(
                'Invalid hour estimates: hours must be positive',
                $data,
                'Hours must be positive numbers'
            );
        }

        if ($data['total_hours_min'] > $data['total_hours_max']) {
            throw new InvalidEstimationResponseException(
                'Invalid hour estimates: min hours cannot be greater than max hours',
                $data,
                'Min hours > max hours'
            );
        }

        // Validate confidence level
        $validConfidences = ['low', 'medium', 'high'];
        if (! in_array($data['confidence'], $validConfidences)) {
            throw new InvalidEstimationResponseException(
                'Invalid confidence level',
                $data,
                'Confidence must be: '.implode(', ', $validConfidences)
            );
        }

        // Validate modules
        if (! is_array($data['modules']) || empty($data['modules'])) {
            throw new InvalidEstimationResponseException(
                'Modules must be a non-empty array',
                $data,
                'Invalid modules structure'
            );
        }

        foreach ($data['modules'] as $index => $module) {
            $this->validateModule($module, $index);
        }

        // Validate team
        if (! is_array($data['recommended_team']) || empty($data['recommended_team'])) {
            throw new InvalidEstimationResponseException(
                'Recommended team must be a non-empty array',
                $data,
                'Invalid team structure'
            );
        }

        foreach ($data['recommended_team'] as $index => $role) {
            $this->validateTeamRole($role, $index);
        }
    }

    private function validateModule(array $module, int $index): void
    {
        $required = ['name', 'description', 'total_hours_min', 'total_hours_max', 'priority', 'features'];

        foreach ($required as $field) {
            if (! isset($module[$field])) {
                throw new InvalidEstimationResponseException(
                    "Missing field '{$field}' in module {$index}",
                    ['module' => $module],
                    "Missing module field: {$field}"
                );
            }
        }

        // Validate features
        if (! is_array($module['features'])) {
            throw new InvalidEstimationResponseException(
                "Features must be an array in module {$index}",
                ['module' => $module],
                'Features must be array'
            );
        }
    }

    private function validateTeamRole(array $role, int $index): void
    {
        $required = ['role', 'count', 'total_hours'];

        foreach ($required as $field) {
            if (! isset($role[$field])) {
                throw new InvalidEstimationResponseException(
                    "Missing field '{$field}' in team role {$index}",
                    ['role' => $role],
                    "Missing team field: {$field}"
                );
            }
        }

        if ($role['count'] < 1) {
            throw new InvalidEstimationResponseException(
                "Team role count must be positive in role {$index}",
                ['role' => $role],
                'Invalid team count'
            );
        }
    }
}
