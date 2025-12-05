<?php

namespace App\Services;

use App\Models\Estimate;
use App\Models\Region;
use App\Models\TeamRole;
use App\Models\Requirement;
use App\Models\EstimateBreakdown;

class EstimationEngine
{
    private $complexityMultipliers = [
        'simple' => 1.0,
        'medium' => 1.5,
        'complex' => 2.5,
        'very_complex' => 4.0,
    ];

    public function calculateEstimate(Estimate $estimate, array $aiData): void
    {
        $region = $estimate->region;
        $features = $aiData['features'] ?? [];

        $totalHours = 0;
        $totalCost = 0;

        foreach ($features as $feature) {
            $requirement = Requirement::create([
                'estimate_id' => $estimate->id,
                'feature_name' => $feature['name'],
                'description' => $feature['description'] ?? '',
                'complexity_level' => $feature['complexity'],
                'estimated_hours' => (int)$feature['hours'],
            ]);

            $totalHours += $feature['hours'];
        }

        $teamComposition = $aiData['team_composition'] ?? ['Backend Developer' => 1];

        foreach ($teamComposition as $roleName => $count) {
            $role = TeamRole::where('name', $roleName)->first();

            if ($role) {
                $hours = (int)($totalHours / count($teamComposition) * $count);
                $hourlyRate = $this->getHourlyRate($role, $region);
                $cost = $hours * $hourlyRate;

                EstimateBreakdown::create([
                    'estimate_id' => $estimate->id,
                    'team_role_id' => $role->id,
                    'hours' => $hours,
                    'hourly_rate' => $hourlyRate,
                    'cost' => $cost,
                ]);

                $totalCost += $cost;
            }
        }

        $estimatedDays = $this->calculateDays($totalHours, $estimate->team_size);

        $estimate->update([
            'total_cost' => $totalCost,
            'estimated_hours' => $totalHours,
            'estimated_days' => $estimatedDays,
            'complexity_level' => $aiData['complexity_level'] ?? 'medium',
            'team_composition' => $teamComposition,
            'parsed_features' => $features,
            'ai_response' => $aiData,
            'status' => 'completed',
        ]);
    }

    private function getHourlyRate(TeamRole $role, ?Region $region): float
    {
        if ($region) {
            $pricingTier = $role->pricingTiers()
                ->where('region_id', $region->id)
                ->first();

            if ($pricingTier) {
                return $pricingTier->hourly_rate;
            }

            return $role->base_hourly_rate * $region->cost_multiplier;
        }

        return $role->base_hourly_rate;
    }

    private function calculateDays(int $totalHours, int $teamSize): int
    {
        $hoursPerDay = 8;
        $efficiencyFactor = 0.8;

        $effectiveHours = $totalHours * $efficiencyFactor;
        $days = ceil($effectiveHours / ($hoursPerDay * $teamSize));

        return (int)$days;
    }
}

