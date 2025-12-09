<?php

namespace App\DTO;

readonly class FeatureBreakdownDTO
{
    public function __construct(
        public string $name,
        public string $description,
        public int $hoursMin,
        public int $hoursMax,
        public string $complexity,
        public array $techStack,
        public ?string $notes = null,
        public array $dependencies = [],
    ) {}

    public function getAverageHours(): float
    {
        return ($this->hoursMin + $this->hoursMax) / 2;
    }

    public function getHoursRange(): string
    {
        return $this->hoursMin === $this->hoursMax
            ? (string) $this->hoursMin
            : "{$this->hoursMin}-{$this->hoursMax}";
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'hours_min' => $this->hoursMin,
            'hours_max' => $this->hoursMax,
            'complexity' => $this->complexity,
            'tech_stack' => $this->techStack,
            'notes' => $this->notes,
            'dependencies' => $this->dependencies,
            'average_hours' => $this->getAverageHours(),
            'hours_range' => $this->getHoursRange(),
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            description: $data['description'],
            hoursMin: $data['hours_min'],
            hoursMax: $data['hours_max'],
            complexity: $data['complexity'],
            techStack: $data['tech_stack'],
            notes: $data['notes'] ?? null,
            dependencies: $data['dependencies'] ?? [],
        );
    }
}
