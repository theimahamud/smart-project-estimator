<?php

namespace App\Services\Estimation;

use App\DTO\EstimationContextDTO;
use App\DTO\EstimationResultDTO;
use App\DTO\ProjectBasicsDTO;
use App\DTO\RequirementsDTO;
use App\Exceptions\AiEstimationFailedException;
use App\Exceptions\InvalidEstimationResponseException;
use App\Models\Estimate;
use App\Models\Project;

interface EstimationServiceInterface
{
    /**
     * Generate a complete project estimation
     *
     * @throws AiEstimationFailedException
     * @throws InvalidEstimationResponseException
     */
    public function estimate(
        ProjectBasicsDTO $projectBasics,
        RequirementsDTO $requirements,
        EstimationContextDTO $context,
        int $userId
    ): EstimationResultDTO;

    /**
     * Create a project and persist an estimation result
     *
     * @throws AiEstimationFailedException
     * @throws InvalidEstimationResponseException
     */
    public function estimateAndPersist(
        ProjectBasicsDTO $projectBasics,
        RequirementsDTO $requirements,
        EstimationContextDTO $context,
        int $userId,
        ?int $clientId = null
    ): Estimate;

    /**
     * Re-run estimation for an existing project with different parameters
     *
     * @throws AiEstimationFailedException
     * @throws InvalidEstimationResponseException
     */
    public function reestimate(
        Project $project,
        RequirementsDTO $requirements,
        EstimationContextDTO $context,
        string $version
    ): Estimate;
}
