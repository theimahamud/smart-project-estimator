<?php

namespace App\DTO;

use App\Enums\DomainType;
use App\Enums\ProjectType;
use App\Enums\QualityLevel;

readonly class ProjectBasicsDTO
{
    public function __construct(
        public string $name,
        public ProjectType $projectType,
        public DomainType $domainType,
        public QualityLevel $qualityLevel,
        public ?string $clientName = null,
        public ?string $shortDescription = null,
    ) {}

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'project_type' => $this->projectType->value,
            'domain_type' => $this->domainType->value,
            'quality_level' => $this->qualityLevel->value,
            'client_name' => $this->clientName,
            'short_description' => $this->shortDescription,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            projectType: ProjectType::from($data['project_type']),
            domainType: DomainType::from($data['domain_type']),
            qualityLevel: QualityLevel::from($data['quality_level']),
            clientName: $data['client_name'] ?? null,
            shortDescription: $data['short_description'] ?? null,
        );
    }
}
