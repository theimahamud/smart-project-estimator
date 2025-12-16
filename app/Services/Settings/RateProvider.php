<?php

namespace App\Services\Settings;

use App\Enums\TeamSeniority;
use App\Models\HourlyRate;

class RateProvider
{
    private array $defaultRates = [
        'backend_developer' => [
            'junior' => 50.00,
            'mid' => 75.00,
            'senior' => 110.00,
            'blended' => 80.00,
        ],
        'frontend_developer' => [
            'junior' => 45.00,
            'mid' => 70.00,
            'senior' => 100.00,
            'blended' => 75.00,
        ],
        'fullstack_developer' => [
            'junior' => 55.00,
            'mid' => 80.00,
            'senior' => 120.00,
            'blended' => 85.00,
        ],
        'devops_engineer' => [
            'junior' => 60.00,
            'mid' => 85.00,
            'senior' => 130.00,
            'blended' => 95.00,
        ],
        'ui_ux_designer' => [
            'junior' => 40.00,
            'mid' => 65.00,
            'senior' => 95.00,
            'blended' => 70.00,
        ],
        'project_manager' => [
            'junior' => 55.00,
            'mid' => 80.00,
            'senior' => 120.00,
            'blended' => 85.00,
        ],
        'qa_engineer' => [
            'junior' => 35.00,
            'mid' => 55.00,
            'senior' => 80.00,
            'blended' => 60.00,
        ],
        'business_analyst' => [
            'junior' => 45.00,
            'mid' => 70.00,
            'senior' => 100.00,
            'blended' => 75.00,
        ],
        'data_scientist' => [
            'junior' => 70.00,
            'mid' => 100.00,
            'senior' => 150.00,
            'blended' => 110.00,
        ],
        'mobile_developer' => [
            'junior' => 50.00,
            'mid' => 75.00,
            'senior' => 115.00,
            'blended' => 80.00,
        ],
    ];

    public function getHourlyRate(int $userId, string $role, TeamSeniority $seniority, ?array $customRates = null): float
    {
        // Check for custom rates first (from form)
        if ($customRates && isset($customRates[$role])) {
            return (float) $customRates[$role];
        }

        // Try to get user-specific rate
        $userRate = $this->getUserRate($userId, $role, $seniority);
        if ($userRate !== null) {
            return $userRate;
        }

        // Fall back to default rates
        return $this->getDefaultRate($role, $seniority);
    }

    public function getUserRate(int $userId, string $role, TeamSeniority $seniority): ?float
    {
        $hourlyRate = HourlyRate::where('user_id', $userId)
            ->where('role', $role)
            ->where('seniority', $seniority->value)
            ->where('is_active', true)
            ->first();

        return $hourlyRate?->rate;
    }

    public function getDefaultRate(string $role, TeamSeniority $seniority): float
    {
        $normalizedRole = $this->normalizeRole($role);
        $normalizedSeniority = strtolower($seniority->value);

        if (isset($this->defaultRates[$normalizedRole][$normalizedSeniority])) {
            return $this->defaultRates[$normalizedRole][$normalizedSeniority];
        }

        // Fallback to fullstack developer rates if role not found
        return $this->defaultRates['fullstack_developer'][$normalizedSeniority] ?? 80.00;
    }

    public function setUserRate(
        int $userId,
        string $role,
        TeamSeniority $seniority,
        float $rate,
        string $currency = 'USD'
    ): HourlyRate {
        return HourlyRate::updateOrCreate(
            [
                'user_id' => $userId,
                'role' => $role,
                'seniority' => $seniority->value,
            ],
            [
                'rate' => $rate,
                'currency' => $currency,
                'is_active' => true,
            ]
        );
    }

    public function getUserRates(int $userId): array
    {
        return HourlyRate::where('user_id', $userId)
            ->where('is_active', true)
            ->get()
            ->groupBy('role')
            ->map(fn ($rates) => $rates->keyBy('seniority'))
            ->toArray();
    }

    public function getDefaultRates(): array
    {
        return $this->defaultRates;
    }

    public function getAllRolesForUser(int $userId): array
    {
        $userRoles = HourlyRate::where('user_id', $userId)
            ->where('is_active', true)
            ->pluck('role')
            ->unique()
            ->toArray();

        $defaultRoles = array_keys($this->defaultRates);

        return array_unique(array_merge($userRoles, $defaultRoles));
    }

    public function estimateTeamCost(array $teamComposition, int $userId, ?array $customRates = null): array
    {
        $totalCost = 0;
        $breakdown = [];

        foreach ($teamComposition as $roleData) {
            $role = $roleData['role'];
            $count = $roleData['count'];
            $hours = $roleData['hours'];
            $seniority = TeamSeniority::from($roleData['seniority'] ?? 'mid');

            $hourlyRate = $this->getHourlyRate($userId, $role, $seniority, $customRates);
            $roleCost = $hours * $hourlyRate;
            $totalCost += $roleCost;

            $breakdown[$role] = [
                'count' => $count,
                'hours' => $hours,
                'hourly_rate' => $hourlyRate,
                'total_cost' => $roleCost,
                'seniority' => $seniority->value,
            ];
        }

        return [
            'total_cost' => $totalCost,
            'breakdown' => $breakdown,
        ];
    }

    private function normalizeRole(string $role): string
    {
        $role = strtolower(trim($role));

        // Map common variations to standard role names
        $roleMapping = [
            'backend' => 'backend_developer',
            'back-end' => 'backend_developer',
            'backend dev' => 'backend_developer',
            'frontend' => 'frontend_developer',
            'front-end' => 'frontend_developer',
            'frontend dev' => 'frontend_developer',
            'fullstack' => 'fullstack_developer',
            'full-stack' => 'fullstack_developer',
            'full stack' => 'fullstack_developer',
            'devops' => 'devops_engineer',
            'dev-ops' => 'devops_engineer',
            'designer' => 'ui_ux_designer',
            'ui designer' => 'ui_ux_designer',
            'ux designer' => 'ui_ux_designer',
            'pm' => 'project_manager',
            'product manager' => 'project_manager',
            'qa' => 'qa_engineer',
            'tester' => 'qa_engineer',
            'quality assurance' => 'qa_engineer',
            'analyst' => 'business_analyst',
            'ba' => 'business_analyst',
            'data engineer' => 'data_scientist',
            'ml engineer' => 'data_scientist',
            'mobile' => 'mobile_developer',
            'ios developer' => 'mobile_developer',
            'android developer' => 'mobile_developer',
        ];

        return $roleMapping[$role] ?? $role;
    }
}
