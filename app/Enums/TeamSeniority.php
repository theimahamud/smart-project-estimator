<?php

namespace App\Enums;

enum TeamSeniority: string
{
    case Junior = 'junior';
    case Mid = 'mid';
    case Senior = 'senior';
    case Blended = 'blended';

    public function label(): string
    {
        return match ($this) {
            self::Junior => 'Junior',
            self::Mid => 'Mid-Level',
            self::Senior => 'Senior',
            self::Blended => 'Blended',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::Junior => '0-2 years of experience',
            self::Mid => '3-5 years of experience',
            self::Senior => '6+ years of experience',
            self::Blended => 'Mix of junior, mid, and senior developers',
        };
    }
}
