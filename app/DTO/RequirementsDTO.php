<?php

namespace App\DTO;

use App\Enums\RequirementsQuality;

readonly class RequirementsDTO
{
    public function __construct(
        public string $rawText,
        public RequirementsQuality $quality,
    ) {}

    public function isEmpty(): bool
    {
        return empty(trim($this->rawText));
    }

    public function getWordCount(): int
    {
        return str_word_count($this->rawText);
    }

    public function getCharacterCount(): int
    {
        return mb_strlen($this->rawText);
    }

    public function toArray(): array
    {
        return [
            'raw_text' => $this->rawText,
            'quality' => $this->quality->value,
            'word_count' => $this->getWordCount(),
            'character_count' => $this->getCharacterCount(),
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            rawText: $data['raw_text'],
            quality: RequirementsQuality::from($data['quality']),
        );
    }
}
