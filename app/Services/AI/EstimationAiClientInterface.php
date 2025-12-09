<?php

namespace App\Services\AI;

use App\DTO\EstimationContextDTO;
use App\DTO\ProjectBasicsDTO;
use App\DTO\RequirementsDTO;
use App\Exceptions\AiEstimationFailedException;
use App\Exceptions\InvalidEstimationResponseException;

interface EstimationAiClientInterface
{
    /**
     * Generate estimation payload from AI
     *
     * @throws AiEstimationFailedException
     * @throws InvalidEstimationResponseException
     */
    public function generateEstimatePayload(
        ProjectBasicsDTO $projectBasics,
        RequirementsDTO $requirements,
        EstimationContextDTO $context
    ): array;

    /**
     * Build the prompt for AI estimation
     */
    public function buildPrompt(
        ProjectBasicsDTO $projectBasics,
        RequirementsDTO $requirements,
        EstimationContextDTO $context
    ): string;

    /**
     * Validate and parse AI response
     *
     * @throws InvalidEstimationResponseException
     */
    public function parseAiResponse(array $response): array;
}
