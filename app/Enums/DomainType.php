<?php

namespace App\Enums;

enum DomainType: string
{
    case Saas = 'saas';
    case Ecommerce = 'ecommerce';
    case Fintech = 'fintech';
    case Healthcare = 'healthcare';
    case Education = 'education';
    case InternalTool = 'internal_tool';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Saas => 'SaaS',
            self::Ecommerce => 'E-commerce',
            self::Fintech => 'FinTech',
            self::Healthcare => 'Healthcare',
            self::Education => 'Education',
            self::InternalTool => 'Internal Tool',
            self::Other => 'Other',
        };
    }
}
