<?php

namespace App\DTO;

readonly class RecommendedRoleDTO
{
    public function __construct(
        public string $role,
        public int $count,
        public int $totalHours,
        public float $hourlyRate,
        public string $seniority,
        public ?string $description = null,
    ) {}

    public function getTotalCost(): float
    {
        return $this->totalHours * $this->hourlyRate;
    }

    public function getAverageHoursPerPerson(): float
    {
        return $this->count > 0 ? $this->totalHours / $this->count : 0;
    }

    public function toArray(): array
    {
        return [
            'role' => $this->role,
            'count' => $this->count,
            'total_hours' => $this->totalHours,
            'hourly_rate' => $this->hourlyRate,
            'seniority' => $this->seniority,
            'description' => $this->description,
            'total_cost' => $this->getTotalCost(),
            'average_hours_per_person' => $this->getAverageHoursPerPerson(),
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            role: $data['role'],
            count: $data['count'],
            totalHours: $data['total_hours'],
            hourlyRate: $data['hourly_rate'],
            seniority: $data['seniority'],
            description: $data['description'] ?? null,
        );
    }
}
