<?php

namespace App\Providers;

use App\Services\AI\EstimationAiClient;
use App\Services\AI\EstimationAiClientInterface;
use App\Services\Estimation\EstimationService;
use App\Services\Estimation\EstimationServiceInterface;
use Illuminate\Support\ServiceProvider;

class EstimationServiceProvider extends ServiceProvider
{
    /**
     * All of the container singletons that should be registered.
     */
    public array $singletons = [
        EstimationServiceInterface::class => EstimationService::class,
        EstimationAiClientInterface::class => EstimationAiClient::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        // Register PrismPHP client for AI integration
        $this->app->singleton('prism.client', function ($app) {
            // This will be configured once PrismPHP package is installed
            // For now, return a mock or basic HTTP client
            return new class
            {
                public function generateResponse(string $prompt, array $options = []): string
                {
                    // Mock response for development
                    return json_encode([
                        'total_hours_min' => 100,
                        'total_hours_max' => 150,
                        'confidence' => 'medium',
                        'modules' => [
                            [
                                'name' => 'User Authentication',
                                'description' => 'User registration, login, password reset',
                                'total_hours_min' => 20,
                                'total_hours_max' => 30,
                                'priority' => 'high',
                                'features' => [
                                    [
                                        'name' => 'User Registration',
                                        'description' => 'Registration form with validation',
                                        'hours_min' => 8,
                                        'hours_max' => 12,
                                        'complexity' => 'medium',
                                        'dependencies' => [],
                                    ],
                                ],
                            ],
                        ],
                        'recommended_team' => [
                            [
                                'role' => 'backend_developer',
                                'count' => 1,
                                'total_hours' => 60,
                                'description' => 'API development and database design',
                            ],
                            [
                                'role' => 'frontend_developer',
                                'count' => 1,
                                'total_hours' => 40,
                                'description' => 'UI implementation and integration',
                            ],
                        ],
                        'assumptions' => [
                            'Using standard authentication libraries',
                            'Database is MySQL or PostgreSQL',
                        ],
                        'risks' => [
                            'Third-party integration complexity',
                            'Changing requirements during development',
                        ],
                        'questions_to_clarify' => [
                            'What is the expected user load?',
                            'Are there specific security requirements?',
                        ],
                    ]);
                }
            };
        });

        // Bind the PrismPHP client to AI client
        $this->app->when(EstimationAiClient::class)
            ->needs('$prismClient')
            ->give(function ($app) {
                return $app['prism.client'];
            });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
