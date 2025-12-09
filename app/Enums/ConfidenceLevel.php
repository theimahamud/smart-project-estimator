<?php

namespace App\Enums;

enum ConfidenceLevel: string
{
    case Low = 'low';
    case Medium = 'medium';
    case High = 'high';

    public function label(): string
    {
        return match ($this) {
            self::Low => 'Low',
            self::Medium => 'Medium',
            self::High => 'High',
        };
    }

    public function percentage(): string
    {
        return match ($this) {
            self::Low => '50-70%',
            self::Medium => '70-85%',
            self::High => '85-95%',
        };
    }
}
