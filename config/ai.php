<?php

return [

    /*
    |--------------------------------------------------------------------------
    | AI Service Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for AI services used in SmartEstimate AI.
    | Currently configured for DeepSeek via PrismPHP.
    |
    */

    'default' => env('AI_SERVICE', 'deepseek'),

    'services' => [

        'deepseek' => [
            'driver' => 'prism',
            'api_key' => env('DEEPSEEK_API_KEY'),
            'base_url' => env('DEEPSEEK_BASE_URL', 'https://api.deepseek.com'),
            'model' => env('DEEPSEEK_MODEL', 'deepseek-coder'),
            'max_tokens' => env('AI_MAX_TOKENS', 4000),
            'temperature' => env('AI_TEMPERATURE', 0.1),
            'timeout' => env('AI_TIMEOUT', 180),
        ],

        // Fallback for development/testing
        'mock' => [
            'driver' => 'mock',
            'enabled' => env('AI_MOCK_ENABLED', false),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Estimation Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration specific to project estimation features.
    |
    */

    'estimation' => [

        // Maximum allowed requirements text length (characters)
        'max_requirements_length' => 10000,

        // Minimum requirements length for quality assessment
        'min_requirements_length' => 50,

        // Default confidence level for estimates
        'default_confidence' => 'medium',

        // Retry configuration for failed AI calls
        'retry_attempts' => 3,
        'retry_delay' => 1000, // milliseconds

        // Cache duration for similar estimation requests
        'cache_duration' => 3600, // seconds (1 hour)

    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limits
    |--------------------------------------------------------------------------
    |
    | API rate limiting configuration for AI services.
    |
    */

    'rate_limits' => [

        'requests_per_minute' => env('AI_REQUESTS_PER_MINUTE', 10),
        'requests_per_hour' => env('AI_REQUESTS_PER_HOUR', 100),
        'requests_per_day' => env('AI_REQUESTS_PER_DAY', 500),

    ],

    /*
    |--------------------------------------------------------------------------
    | Quality Thresholds
    |--------------------------------------------------------------------------
    |
    | Thresholds for automatic quality assessment and validation.
    |
    */

    'quality' => [

        // Minimum word count for different quality levels
        'word_count_thresholds' => [
            'rough_idea' => 10,
            'draft' => 50,
            'final' => 100,
        ],

        // Keywords that indicate higher quality requirements
        'quality_indicators' => [
            'acceptance criteria',
            'user story',
            'business logic',
            'integration',
            'authentication',
            'authorization',
            'database',
            'api',
            'security',
            'performance',
            'scalability',
        ],

    ],

];
