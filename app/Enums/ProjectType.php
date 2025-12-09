<?php

namespace App\Enums;

enum ProjectType: string
{
    case WebApp = 'web_app';
    case MobileApp = 'mobile_app';
    case Api = 'api';
    case DataProject = 'data_project';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::WebApp => 'Web Application',
            self::MobileApp => 'Mobile Application',
            self::Api => 'API',
            self::DataProject => 'Data Project',
            self::Other => 'Other',
        };
    }
}
