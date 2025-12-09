<?php

namespace App\DTO;

use App\Enums\ConfidenceLevel;

readonly class EstimationResultDTO
{
    /**
     * @param  ModuleBreakdownDTO[]  $moduleBreakdown
     * @param  RecommendedRoleDTO[]  $recommendedTeam
     * @param  string[]  $risks
     * @param  string[]  $assumptions
     * @param  string[]  $questionsToClarify
     */
    public function __construct(
        public int $totalHoursMin,
        public int $totalHoursMax,
        public int $durationWeeksMin,
        public int $durationWeeksMax,
        public int $costCentsMin,
        public int $costCentsTypical,
        public int $costCentsMax,
        public ConfidenceLevel $confidence,
        public array $moduleBreakdown,
        public array $recommendedTeam,
        public array $risks = [],
        public array $assumptions = [],
        public array $questionsToClarify = [],
    ) {}

    public function getTotalHoursRange(): string
    {
        return $this->totalHoursMin === $this->totalHoursMax
            ? number_format($this->totalHoursMin)
            : number_format($this->totalHoursMin).'-'.number_format($this->totalHoursMax);
    }

    public function getDurationWeeksRange(): string
    {
        return $this->durationWeeksMin === $this->durationWeeksMax
            ? (string) $this->durationWeeksMin
            : "{$this->durationWeeksMin}-{$this->durationWeeksMax}";
    }

    public function getCostRangeFormatted(string $currency = 'USD'): string
    {
        $min = $this->formatCurrency($this->costCentsMin, $currency);
        $max = $this->formatCurrency($this->costCentsMax, $currency);
        $typical = $this->formatCurrency($this->costCentsTypical, $currency);

        return "{$min} - {$max} (typical: {$typical})";
    }

    public function getTotalModules(): int
    {
        return count($this->moduleBreakdown);
    }

    public function getTotalFeatures(): int
    {
        return array_sum(array_map(
            fn (ModuleBreakdownDTO $module) => $module->getFeatureCount(),
            $this->moduleBreakdown
        ));
    }

    public function getTotalTeamMembers(): int
    {
        return array_sum(array_map(
            fn (RecommendedRoleDTO $role) => $role->count,
            $this->recommendedTeam
        ));
    }

    public function getAverageHours(): float
    {
        return ($this->totalHoursMin + $this->totalHoursMax) / 2;
    }

    public function getAverageDurationWeeks(): float
    {
        return ($this->durationWeeksMin + $this->durationWeeksMax) / 2;
    }

    private function formatCurrency(int $cents, string $currency): string
    {
        return match ($currency) {
            'USD' => '$'.number_format($cents / 100, 0),
            'EUR' => '€'.number_format($cents / 100, 0),
            'GBP' => '£'.number_format($cents / 100, 0),
            default => number_format($cents / 100, 0).' '.$currency,
        };
    }

    public function toArray(): array
    {
        return [
            'total_hours_min' => $this->totalHoursMin,
            'total_hours_max' => $this->totalHoursMax,
            'duration_weeks_min' => $this->durationWeeksMin,
            'duration_weeks_max' => $this->durationWeeksMax,
            'cost_cents_min' => $this->costCentsMin,
            'cost_cents_typical' => $this->costCentsTypical,
            'cost_cents_max' => $this->costCentsMax,
            'confidence' => $this->confidence->value,
            'module_breakdown' => array_map(fn (ModuleBreakdownDTO $module) => $module->toArray(), $this->moduleBreakdown),
            'recommended_team' => array_map(fn (RecommendedRoleDTO $role) => $role->toArray(), $this->recommendedTeam),
            'risks' => $this->risks,
            'assumptions' => $this->assumptions,
            'questions_to_clarify' => $this->questionsToClarify,
            'total_modules' => $this->getTotalModules(),
            'total_features' => $this->getTotalFeatures(),
            'total_team_members' => $this->getTotalTeamMembers(),
            'average_hours' => $this->getAverageHours(),
            'average_duration_weeks' => $this->getAverageDurationWeeks(),
        ];
    }

    public static function fromArray(array $data): self
    {
        $moduleBreakdown = array_map(
            fn (array $moduleData) => ModuleBreakdownDTO::fromArray($moduleData),
            $data['module_breakdown'] ?? []
        );

        $recommendedTeam = array_map(
            fn (array $roleData) => RecommendedRoleDTO::fromArray($roleData),
            $data['recommended_team'] ?? []
        );

        return new self(
            totalHoursMin: $data['total_hours_min'],
            totalHoursMax: $data['total_hours_max'],
            durationWeeksMin: $data['duration_weeks_min'],
            durationWeeksMax: $data['duration_weeks_max'],
            costCentsMin: $data['cost_cents_min'],
            costCentsTypical: $data['cost_cents_typical'],
            costCentsMax: $data['cost_cents_max'],
            confidence: ConfidenceLevel::from($data['confidence']),
            moduleBreakdown: $moduleBreakdown,
            recommendedTeam: $recommendedTeam,
            risks: $data['risks'] ?? [],
            assumptions: $data['assumptions'] ?? [],
            questionsToClarify: $data['questions_to_clarify'] ?? [],
        );
    }
}
