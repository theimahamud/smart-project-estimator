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
        // PrismPHP uses facades, no additional bindings needed
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}