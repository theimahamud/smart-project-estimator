<?php

namespace App\Services\Estimation;

use App\DTO\EstimationContextDTO;
use App\DTO\EstimationResultDTO;
use App\DTO\FeatureBreakdownDTO;
use App\DTO\ModuleBreakdownDTO;
use App\DTO\ProjectBasicsDTO;
use App\DTO\RecommendedRoleDTO;
use App\DTO\RequirementsDTO;
use App\Enums\ConfidenceLevel;
use App\Enums\EstimateStatus;
use App\Enums\QualityLevel;
use App\Exceptions\AiEstimationFailedException;
use App\Exceptions\InvalidEstimationResponseException;
use App\Models\Estimate;
use App\Models\Project;
use App\Services\AI\EstimationAiClientInterface;
use App\Services\Requirements\RequirementsPreprocessor;
use App\Services\Settings\RateProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EstimationService implements EstimationServiceInterface
{
    public function __construct(
        private readonly EstimationAiClientInterface $aiClient,
        private readonly RequirementsPreprocessor $requirementsPreprocessor,
        private readonly CostCalculator $costCalculator,
        private readonly RateProvider $rateProvider
    ) {}

    public function estimate(
        ProjectBasicsDTO $projectBasics,
        RequirementsDTO $requirements,
        EstimationContextDTO $context,
        int $userId
    ): EstimationResultDTO {
        try {
            Log::info('Starting estimation process', [
                'user_id' => $userId,
                'project_name' => $projectBasics->name,
                'project_type' => $projectBasics->projectType->value,
                'requirements_length' => $requirements->getCharacterCount(),
            ]);

            // Step 1: Preprocess requirements
            $processedRequirements = $this->requirementsPreprocessor->process($requirements);

            // Step 2: Get AI estimation payload
            $aiPayload = $this->aiClient->generateEstimatePayload(
                $projectBasics,
                $processedRequirements,
                $context
            );

            // Step 3: Parse AI response into structured data
            $parsedData = $this->aiClient->parseAiResponse($aiPayload);

            // Step 4: Build DTOs from parsed data
            $estimationResult = $this->buildEstimationResultDTO(
                $parsedData,
                $context,
                $userId
            );

            Log::info('Estimation completed successfully', [
                'user_id' => $userId,
                'total_hours_range' => "{$estimationResult->totalHoursMin}-{$estimationResult->totalHoursMax}",
                'confidence' => $estimationResult->confidence->value,
                'module_count' => count($estimationResult->moduleBreakdown),
            ]);

            return $estimationResult;

        } catch (AiEstimationFailedException|InvalidEstimationResponseException $e) {
            Log::error('Estimation failed', [
                'user_id' => $userId,
                'error' => $e->getMessage(),
                'context' => method_exists($e, 'getLoggableContext') ? $e->getLoggableContext() : [],
            ]);
            throw $e;
        } catch (\Exception $e) {
            Log::error('Unexpected estimation error', [
                'user_id' => $userId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw new AiEstimationFailedException(
                'Unexpected error during estimation: '.$e->getMessage(),
                ['user_id' => $userId],
                0,
                $e
            );
        }
    }

    public function estimateAndPersist(
        ProjectBasicsDTO $projectBasics,
        RequirementsDTO $requirements,
        EstimationContextDTO $context,
        int $userId,
        ?int $clientId = null
    ): Estimate {
        return DB::transaction(function () use ($projectBasics, $requirements, $context, $userId, $clientId) {
            // Generate estimation
            $estimationResult = $this->estimate($projectBasics, $requirements, $context, $userId);

            // Create project
            $project = $this->createProject($projectBasics, $context, $userId, $clientId);

            // Create estimate
            $estimate = $this->createEstimate(
                $project,
                $requirements,
                $context,
                $estimationResult
            );

            Log::info('Estimation persisted successfully', [
                'user_id' => $userId,
                'project_id' => $project->id,
                'estimate_id' => $estimate->id,
            ]);

            return $estimate;
        });
    }

    public function reestimate(
        Project $project,
        RequirementsDTO $requirements,
        EstimationContextDTO $context,
        string $version
    ): Estimate {
        return DB::transaction(function () use ($project, $requirements, $context, $version) {
            // Build project basics from existing project
            $projectBasics = new ProjectBasicsDTO(
                name: $project->name,
                projectType: $project->project_type,
                domainType: $project->domain_type,
                qualityLevel: QualityLevel::Production, // Default for re-estimation
                clientName: $project->client?->name,
                shortDescription: $project->description
            );

            // Generate new estimation
            $estimationResult = $this->estimate($projectBasics, $requirements, $context, $project->user_id);

            // Create new estimate version
            $estimate = $this->createEstimate(
                $project,
                $requirements,
                $context,
                $estimationResult,
                $version
            );

            Log::info('Re-estimation completed', [
                'project_id' => $project->id,
                'estimate_id' => $estimate->id,
                'version' => $version,
            ]);

            return $estimate;
        });
    }

    private function buildEstimationResultDTO(
        array $parsedData,
        EstimationContextDTO $context,
        int $userId
    ): EstimationResultDTO {
        // Build module breakdown
        $modules = [];
        foreach ($parsedData['modules'] ?? [] as $moduleData) {
            $features = [];
            foreach ($moduleData['features'] ?? [] as $featureData) {
                $features[] = new FeatureBreakdownDTO(
                    name: $featureData['name'],
                    description: $featureData['description'],
                    hoursMin: (int) $featureData['hours_min'],
                    hoursMax: (int) $featureData['hours_max'],
                    complexity: $featureData['complexity'],
                    techStack: $context->techStackSlugs,
                    notes: $featureData['notes'] ?? null,
                    dependencies: $featureData['dependencies'] ?? []
                );
            }

            $modules[] = new ModuleBreakdownDTO(
                name: $moduleData['name'],
                description: $moduleData['description'],
                features: $features,
                totalHoursMin: (int) $moduleData['total_hours_min'],
                totalHoursMax: (int) $moduleData['total_hours_max'],
                priority: $moduleData['priority'],
                notes: $moduleData['notes'] ?? null
            );
        }

        // Get hourly rates and build recommended team
        $recommendedTeam = [];
        foreach ($parsedData['recommended_team'] ?? [] as $roleData) {
            $hourlyRate = $this->rateProvider->getHourlyRate(
                $userId,
                $roleData['role'],
                $context->teamSeniority
            );

            $recommendedTeam[] = new RecommendedRoleDTO(
                role: $roleData['role'],
                count: (int) $roleData['count'],
                totalHours: (int) $roleData['total_hours'],
                hourlyRate: $hourlyRate,
                seniority: $context->teamSeniority->value,
                description: $roleData['description'] ?? null
            );
        }

        // Calculate costs using cost calculator
        $costCalculation = $this->costCalculator->calculateCosts(
            $modules,
            $recommendedTeam,
            $context
        );

        return new EstimationResultDTO(
            totalHoursMin: (int) $parsedData['total_hours_min'],
            totalHoursMax: (int) $parsedData['total_hours_max'],
            durationWeeksMin: $costCalculation['duration_weeks_min'],
            durationWeeksMax: $costCalculation['duration_weeks_max'],
            costCentsMin: $costCalculation['cost_cents_min'],
            costCentsTypical: $costCalculation['cost_cents_typical'],
            costCentsMax: $costCalculation['cost_cents_max'],
            confidence: ConfidenceLevel::from($parsedData['confidence']),
            moduleBreakdown: $modules,
            recommendedTeam: $recommendedTeam,
            risks: $parsedData['risks'] ?? [],
            assumptions: $parsedData['assumptions'] ?? [],
            questionsToClarify: $parsedData['questions_to_clarify'] ?? []
        );
    }

    private function createProject(
        ProjectBasicsDTO $projectBasics,
        EstimationContextDTO $context,
        int $userId,
        ?int $clientId
    ): Project {
        return Project::create([
            'user_id' => $userId,
            'client_id' => $clientId,
            'name' => $projectBasics->name,
            'description' => $projectBasics->shortDescription,
            'project_type' => $projectBasics->projectType,
            'domain_type' => $projectBasics->domainType,
            'country' => $context->workforceCountryCode,
            'tech_stack' => $context->techStackSlugs,
        ]);
    }

    private function createEstimate(
        Project $project,
        RequirementsDTO $requirements,
        EstimationContextDTO $context,
        EstimationResultDTO $result,
        string $version = '1.0'
    ): Estimate {
        return Estimate::create([
            'project_id' => $project->id,
            'version' => $version,
            'status' => EstimateStatus::Completed,
            'requirements' => $requirements->rawText,
            'requirements_quality' => $requirements->quality,
            'quality_level' => $project->quality_level ?? QualityLevel::Production,
            'team_seniority' => $context->teamSeniority,
            'confidence_level' => $result->confidence,
            'total_hours' => $result->getAverageHours(),
            'min_hours' => $result->totalHoursMin,
            'max_hours' => $result->totalHoursMax,
            'total_cost' => $result->costCentsTypical / 100,
            'min_cost' => $result->costCentsMin / 100,
            'max_cost' => $result->costCentsMax / 100,
            'breakdown' => $result->toArray(),
            'assumptions' => $result->assumptions,
            'risks' => $result->risks,
            'recommendations' => $result->questionsToClarify,
        ]);
    }
}
