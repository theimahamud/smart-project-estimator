<?php

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;

class OpenAIService
{
    public function analyzeRequirements(string $requirements): array
    {
        $prompt = $this->buildAnalysisPrompt($requirements);

        try {
            $response = OpenAI::chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => $this->getSystemPrompt()],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.7,
                'max_tokens' => 2000,
            ]);

            $content = $response->choices[0]->message->content;
            return $this->parseAIResponse($content);
        } catch (\Exception $e) {
            return $this->getFallbackEstimate($requirements);
        }
    }

    private function getSystemPrompt(): string
    {
        return "You are a project estimation expert. Analyze software project requirements and extract:
1. List of features (be specific and detailed)
2. Complexity level for each feature (simple/medium/complex/very_complex)
3. Estimated hours per feature
4. Recommended team composition
5. Technology stack suggestions
6. Potential risks or challenges

Return your response in valid JSON format with this structure:
{
    \"features\": [
        {\"name\": \"Feature Name\", \"complexity\": \"medium\", \"hours\": 40, \"description\": \"Brief description\"}
    ],
    \"team_composition\": {
        \"Frontend Developer\": 1,
        \"Backend Developer\": 1,
        \"UI/UX Designer\": 1
    },
    \"technologies\": [\"React\", \"Laravel\", \"MySQL\"],
    \"total_hours\": 200,
    \"complexity_level\": \"medium\",
    \"risks\": [\"Risk 1\", \"Risk 2\"]
}";
    }

    private function buildAnalysisPrompt(string $requirements): string
    {
        return "Analyze the following project requirements and provide a detailed breakdown:\n\n" . $requirements;
    }

    private function parseAIResponse(string $content): array
    {
        $jsonStart = strpos($content, '{');
        $jsonEnd = strrpos($content, '}');

        if ($jsonStart !== false && $jsonEnd !== false) {
            $jsonString = substr($content, $jsonStart, $jsonEnd - $jsonStart + 1);
            $decoded = json_decode($jsonString, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
            }
        }

        return $this->getFallbackEstimate('');
    }

    private function getFallbackEstimate(string $requirements): array
    {
        $wordCount = str_word_count($requirements);
        $estimatedHours = max(80, $wordCount * 2);

        return [
            'features' => [
                ['name' => 'Core Functionality', 'complexity' => 'medium', 'hours' => $estimatedHours * 0.6, 'description' => 'Main features'],
                ['name' => 'UI/UX Design', 'complexity' => 'medium', 'hours' => $estimatedHours * 0.2, 'description' => 'User interface'],
                ['name' => 'Testing & Deployment', 'complexity' => 'simple', 'hours' => $estimatedHours * 0.2, 'description' => 'QA and deployment'],
            ],
            'team_composition' => [
                'Frontend Developer' => 1,
                'Backend Developer' => 1,
            ],
            'technologies' => ['React', 'Laravel', 'MySQL'],
            'total_hours' => $estimatedHours,
            'complexity_level' => 'medium',
            'risks' => ['Timeline may vary based on requirement changes'],
        ];
    }
}

