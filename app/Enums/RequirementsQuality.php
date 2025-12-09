<?php

namespace App\Enums;

enum RequirementsQuality: string
{
    case Final = 'final';
    case Draft = 'draft';
    case RoughIdea = 'rough_idea';

    public function label(): string
    {
        return match ($this) {
            self::Final => 'Final',
            self::Draft => 'Draft',
            self::RoughIdea => 'Rough Idea',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::Final => 'Complete and detailed requirements',
            self::Draft => 'Work in progress requirements',
            self::RoughIdea => 'High-level concept only',
        };
    }
}
