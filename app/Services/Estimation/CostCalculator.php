<?php

namespace App\Services\Estimation;

use App\DTO\EstimationContextDTO;
use App\DTO\ModuleBreakdownDTO;
use App\DTO\RecommendedRoleDTO;

class CostCalculator
{
    private const DEFAULT_HOURS_PER_WEEK = 40;

    private const TEAM_EFFICIENCY_FACTOR = 0.75; // Account for collaboration overhead

    /**
     * Calculate cost ranges and duration from modules and team
     *
     * @param  ModuleBreakdownDTO[]  $modules
     * @param  RecommendedRoleDTO[]  $team
     */
    public function calculateCosts(
        array $modules,
        array $team,
        EstimationContextDTO $context
    ): array {
        $totalHoursMin = array_sum(array_map(fn ($module) => $module->totalHoursMin, $modules));
        $totalHoursMax = array_sum(array_map(fn ($module) => $module->totalHoursMax, $modules));
        $averageHours = ($totalHoursMin + $totalHoursMax) / 2;

        // Calculate duration based on team configuration and efficiency
        $totalTeamMembers = array_sum(array_map(fn ($role) => $role->count, $team));

        // Use context-specific team size and work hours
        $availableTeamSize = $context->availableTeamSize;
        $hoursPerWeek = $context->workHoursPerDay * 5; // 5 working days per week
        $effectiveTeamSize = max(1, min($availableTeamSize, $totalTeamMembers) * self::TEAM_EFFICIENCY_FACTOR);

        $durationWeeksMin = max(1, ceil($totalHoursMin / ($effectiveTeamSize * $hoursPerWeek)));
        $durationWeeksMax = ceil($totalHoursMax / ($effectiveTeamSize * $hoursPerWeek));

        // Calculate costs
        $costCentsMin = $this->calculateTotalCost($team, $totalHoursMin);
        $costCentsMax = $this->calculateTotalCost($team, $totalHoursMax);
        $costCentsTypical = $this->calculateTotalCost($team, $averageHours);

        // Apply context adjustments
        $adjustmentFactors = $this->getAdjustmentFactors($context);

        $costCentsMin = (int) ($costCentsMin * $adjustmentFactors['cost_min']);
        $costCentsMax = (int) ($costCentsMax * $adjustmentFactors['cost_max']);
        $costCentsTypical = (int) ($costCentsTypical * $adjustmentFactors['cost_typical']);

        $durationWeeksMin = (int) ($durationWeeksMin * $adjustmentFactors['duration_min']);
        $durationWeeksMax = (int) ($durationWeeksMax * $adjustmentFactors['duration_max']);

        return [
            'cost_cents_min' => $costCentsMin,
            'cost_cents_typical' => $costCentsTypical,
            'cost_cents_max' => $costCentsMax,
            'duration_weeks_min' => $durationWeeksMin,
            'duration_weeks_max' => $durationWeeksMax,
        ];
    }

    /**
     * @param  RecommendedRoleDTO[]  $team
     */
    private function calculateTotalCost(array $team, float $totalHours): int
    {
        $totalCost = 0;
        $totalTeamHours = array_sum(array_map(fn ($role) => $role->totalHours, $team));

        // Distribute total hours proportionally across team roles
        foreach ($team as $role) {
            $roleHours = $totalTeamHours > 0 ? ($role->totalHours / $totalTeamHours) * $totalHours : 0;
            $roleCost = $roleHours * $role->hourlyRate;
            $totalCost += $roleCost;
        }

        return (int) ($totalCost * 100); // Convert to cents
    }

    private function getAdjustmentFactors(EstimationContextDTO $context): array
    {
        $factors = [
            'cost_min' => 0.8,
            'cost_typical' => 1.0,
            'cost_max' => 1.4,
            'duration_min' => 0.9,
            'duration_max' => 1.3,
        ];

        // Adjust for high compliance requirements
        if ($context->isHighCompliance) {
            $factors['cost_min'] *= 1.2;
            $factors['cost_typical'] *= 1.3;
            $factors['cost_max'] *= 1.5;
            $factors['duration_min'] *= 1.1;
            $factors['duration_max'] *= 1.4;
        }

        // Adjust for fixed deadline pressure
        if ($context->hasFixedDeadline()) {
            $factors['cost_min'] *= 1.1;
            $factors['cost_typical'] *= 1.15;
            $factors['cost_max'] *= 1.25;
        }

        // Adjust for team seniority
        $seniorityMultiplier = match ($context->teamSeniority) {
            \App\Enums\TeamSeniority::Junior => ['cost' => 0.7, 'duration' => 1.3],
            \App\Enums\TeamSeniority::Mid => ['cost' => 1.0, 'duration' => 1.0],
            \App\Enums\TeamSeniority::Senior => ['cost' => 1.3, 'duration' => 0.8],
            \App\Enums\TeamSeniority::Blended => ['cost' => 1.1, 'duration' => 0.9],
        };

        $factors['cost_min'] *= $seniorityMultiplier['cost'];
        $factors['cost_typical'] *= $seniorityMultiplier['cost'];
        $factors['cost_max'] *= $seniorityMultiplier['cost'];
        $factors['duration_min'] *= $seniorityMultiplier['duration'];
        $factors['duration_max'] *= $seniorityMultiplier['duration'];

        // Adjust for country/region
        $countryMultiplier = $this->getCountryMultiplier($context->workforceCountryCode);
        $factors['cost_min'] *= $countryMultiplier;
        $factors['cost_typical'] *= $countryMultiplier;
        $factors['cost_max'] *= $countryMultiplier;

        // Adjust for available team size (smaller teams may need more time)
        if ($context->availableTeamSize <= 2) {
            $factors['duration_min'] *= 1.2;
            $factors['duration_max'] *= 1.3;
        } elseif ($context->availableTeamSize >= 8) {
            // Larger teams have coordination overhead
            $factors['duration_min'] *= 1.1;
            $factors['duration_max'] *= 1.2;
        }

        // Adjust for work hours per day
        $hoursAdjustment = match ($context->workHoursPerDay) {
            4 => ['duration' => 2.0], // Part-time
            6 => ['duration' => 1.33], // Reduced hours
            8 => ['duration' => 1.0], // Standard
            10 => ['duration' => 0.8], // Extended hours
        };

        $factors['duration_min'] *= $hoursAdjustment['duration'];
        $factors['duration_max'] *= $hoursAdjustment['duration'];

        return $factors;
    }

    private function getCountryMultiplier(string $countryCode): float
    {
        // Cost multipliers by country (base: 1.0 for US)
        return match (strtoupper($countryCode)) {
            'US', 'USA' => 1.0,
            'CA', 'CAN' => 0.85,
            'GB', 'UK' => 0.9,
            'DE', 'DEU' => 0.8,
            'FR', 'FRA' => 0.8,
            'AU', 'AUS' => 0.95,
            'NL', 'NLD' => 0.85,
            'SE', 'SWE' => 0.9,
            'CH', 'CHE' => 1.1,
            'IN', 'IND' => 0.3,
            'PH', 'PHL' => 0.25,
            'UA', 'UKR' => 0.35,
            'PL', 'POL' => 0.4,
            'BR', 'BRA' => 0.35,
            'MX', 'MEX' => 0.4,
            'AR', 'ARG' => 0.3,
            default => 0.6, // Default for unlisted countries
        };
    }

    public function estimateProjectDuration(
        int $totalHours,
        int $teamSize,
        float $efficiency = self::TEAM_EFFICIENCY_FACTOR
    ): array {
        $effectiveTeamSize = max(1, $teamSize * $efficiency);
        $weeksMin = max(1, ceil($totalHours * 0.8 / ($effectiveTeamSize * self::DEFAULT_HOURS_PER_WEEK)));
        $weeksMax = ceil($totalHours * 1.2 / ($effectiveTeamSize * self::DEFAULT_HOURS_PER_WEEK));

        return [
            'weeks_min' => $weeksMin,
            'weeks_max' => $weeksMax,
            'months_min' => ceil($weeksMin / 4),
            'months_max' => ceil($weeksMax / 4),
        ];
    }
}
