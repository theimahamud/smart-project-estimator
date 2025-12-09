<?php

namespace App\DTO;

readonly class ModuleBreakdownDTO
{
    /**
     * @param  FeatureBreakdownDTO[]  $features
     */
    public function __construct(
        public string $name,
        public string $description,
        public array $features,
        public int $totalHoursMin,
        public int $totalHoursMax,
        public string $priority,
        public ?string $notes = null,
    ) {}

    public function getFeatureCount(): int
    {
        return count($this->features);
    }

    public function getAverageHours(): float
    {
        return ($this->totalHoursMin + $this->totalHoursMax) / 2;
    }

    public function getHoursRange(): string
    {
        return $this->totalHoursMin === $this->totalHoursMax
            ? (string) $this->totalHoursMin
            : "{$this->totalHoursMin}-{$this->totalHoursMax}";
    }

    public function getComplexityDistribution(): array
    {
        $distribution = ['low' => 0, 'medium' => 0, 'high' => 0];

        foreach ($this->features as $feature) {
            $complexity = strtolower($feature->complexity);
            if (isset($distribution[$complexity])) {
                $distribution[$complexity]++;
            }
        }

        return $distribution;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'features' => array_map(fn (FeatureBreakdownDTO $feature) => $feature->toArray(), $this->features),
            'total_hours_min' => $this->totalHoursMin,
            'total_hours_max' => $this->totalHoursMax,
            'priority' => $this->priority,
            'notes' => $this->notes,
            'feature_count' => $this->getFeatureCount(),
            'average_hours' => $this->getAverageHours(),
            'hours_range' => $this->getHoursRange(),
            'complexity_distribution' => $this->getComplexityDistribution(),
        ];
    }

    public static function fromArray(array $data): self
    {
        $features = array_map(
            fn (array $featureData) => FeatureBreakdownDTO::fromArray($featureData),
            $data['features'] ?? []
        );

        return new self(
            name: $data['name'],
            description: $data['description'],
            features: $features,
            totalHoursMin: $data['total_hours_min'],
            totalHoursMax: $data['total_hours_max'],
            priority: $data['priority'],
            notes: $data['notes'] ?? null,
        );
    }
}
