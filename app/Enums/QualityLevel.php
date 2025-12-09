<?php

namespace App\Enums;

enum QualityLevel: string
{
    case Mvp = 'mvp';
    case Production = 'production';
    case Enterprise = 'enterprise';

    public function label(): string
    {
        return match ($this) {
            self::Mvp => 'MVP',
            self::Production => 'Production',
            self::Enterprise => 'Enterprise',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::Mvp => 'Minimum viable product with basic features',
            self::Production => 'Production-ready with comprehensive testing',
            self::Enterprise => 'Enterprise-grade with full compliance and security',
        };
    }
}
