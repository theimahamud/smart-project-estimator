<?php

namespace App\DTO;

use App\Enums\TeamSeniority;
use Carbon\Carbon;

readonly class EstimationContextDTO
{
    public function __construct(
        public string $workforceCountryCode,
        public TeamSeniority $teamSeniority,
        public array $techStackSlugs,
        public bool $isHighCompliance = false,
        public ?Carbon $fixedDeadline = null,
        public ?int $fixedBudgetCents = null,
        public int $availableTeamSize = 3,
        public int $workHoursPerDay = 8,
        public ?int $targetBudgetCents = null,
        public ?array $customRates = null,
    ) {}

    public function hasFixedDeadline(): bool
    {
        return $this->fixedDeadline !== null;
    }

    public function hasFixedBudget(): bool
    {
        return $this->fixedBudgetCents !== null;
    }

    public function hasTargetBudget(): bool
    {
        return $this->targetBudgetCents !== null;
    }

    public function hasCustomRates(): bool
    {
        return $this->customRates !== null && count($this->customRates) > 0;
    }

    public function getFixedBudgetFormatted(string $currency = 'USD'): ?string
    {
        if ($this->fixedBudgetCents === null) {
            return null;
        }

        return match ($currency) {
            'USD' => '$'.number_format($this->fixedBudgetCents / 100, 2),
            'EUR' => '€'.number_format($this->fixedBudgetCents / 100, 2),
            'GBP' => '£'.number_format($this->fixedBudgetCents / 100, 2),
            default => number_format($this->fixedBudgetCents / 100, 2).' '.$currency,
        };
    }

    public function getTechStackString(): string
    {
        return implode(', ', $this->techStackSlugs);
    }

    public function toArray(): array
    {
        return [
            'workforce_country_code' => $this->workforceCountryCode,
            'team_seniority' => $this->teamSeniority->value,
            'tech_stack_slugs' => $this->techStackSlugs,
            'is_high_compliance' => $this->isHighCompliance,
            'fixed_deadline' => $this->fixedDeadline?->toISOString(),
            'fixed_budget_cents' => $this->fixedBudgetCents,
            'available_team_size' => $this->availableTeamSize,
            'work_hours_per_day' => $this->workHoursPerDay,
            'target_budget_cents' => $this->targetBudgetCents,
            'custom_rates' => $this->customRates,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            workforceCountryCode: $data['workforce_country_code'],
            teamSeniority: TeamSeniority::from($data['team_seniority']),
            techStackSlugs: $data['tech_stack_slugs'],
            isHighCompliance: $data['is_high_compliance'] ?? false,
            fixedDeadline: isset($data['fixed_deadline']) ? Carbon::parse($data['fixed_deadline']) : null,
            fixedBudgetCents: $data['fixed_budget_cents'] ?? null,
            availableTeamSize: $data['available_team_size'] ?? 3,
            workHoursPerDay: $data['work_hours_per_day'] ?? 8,
            targetBudgetCents: $data['target_budget_cents'] ?? null,
            customRates: $data['custom_rates'] ?? null,
        );
    }
}
