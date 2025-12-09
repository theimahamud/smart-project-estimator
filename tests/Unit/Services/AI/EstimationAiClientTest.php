<?php

namespace Tests\Unit\Services\AI;

use App\DTO\EstimationContextDTO;
use App\DTO\ProjectBasicsDTO;
use App\DTO\RequirementsDTO;
use App\Enums\DomainType;
use App\Enums\ProjectType;
use App\Enums\QualityLevel;
use App\Enums\RequirementsQuality;
use App\Enums\TeamSeniority;
use App\Exceptions\AiEstimationFailedException;
use App\Exceptions\InvalidEstimationResponseException;
use App\Services\AI\EstimationAiClient;
use PHPUnit\Framework\TestCase;

class EstimationAiClientTest extends TestCase
{
    private EstimationAiClient $aiClient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->aiClient = new EstimationAiClient($this->createMockPrismClient());
    }

    public function test_generate_estimate_payload_with_valid_data(): void
    {
        // Arrange
        $projectBasics = new ProjectBasicsDTO(
            name: 'Test Project',
            projectType: ProjectType::WebApp,
            domainType: DomainType::Saas,
            qualityLevel: QualityLevel::Production,
            clientName: 'Test Client',
            shortDescription: 'A test project for unit testing'
        );

        $requirements = new RequirementsDTO(
            rawText: 'Build a web application with user authentication and dashboard.',
            quality: RequirementsQuality::Draft
        );

        $context = new EstimationContextDTO(
            workforceCountryCode: 'US',
            teamSeniority: TeamSeniority::Mid,
            techStackSlugs: ['laravel', 'vue', 'tailwind'],
            isHighCompliance: false
        );

        // Act
        $result = $this->aiClient->generateEstimatePayload($projectBasics, $requirements, $context);

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('total_hours_min', $result);
        $this->assertArrayHasKey('total_hours_max', $result);
        $this->assertArrayHasKey('confidence', $result);
        $this->assertArrayHasKey('modules', $result);
        $this->assertArrayHasKey('recommended_team', $result);
        $this->assertArrayHasKey('assumptions', $result);
        $this->assertArrayHasKey('risks', $result);
        $this->assertArrayHasKey('questions_to_clarify', $result);
    }

    public function test_generate_estimate_payload_with_ai_failure(): void
    {
        // Arrange
        $projectBasics = new ProjectBasicsDTO(
            name: 'Test Project',
            projectType: ProjectType::WebApp,
            domainType: DomainType::Saas,
            qualityLevel: QualityLevel::Production
        );

        $requirements = new RequirementsDTO(
            rawText: 'Build a web application',
            quality: RequirementsQuality::Draft
        );

        $context = new EstimationContextDTO(
            workforceCountryCode: 'US',
            teamSeniority: TeamSeniority::Mid,
            techStackSlugs: ['laravel']
        );

        // Mock Prism client to throw exception
        $mockClient = $this->createMockPrismClientThatThrows();
        $aiClient = new EstimationAiClient($mockClient);

        // Act & Assert
        $this->expectException(AiEstimationFailedException::class);
        $this->expectExceptionMessage('AI estimation request failed: Network error');

        $aiClient->generateEstimatePayload($projectBasics, $requirements, $context);
    }

    public function test_parse_ai_response_with_invalid_json(): void
    {
        // Arrange
        $invalidResponse = ['content' => 'This is not valid JSON'];
        $mockClient = $this->createMockPrismClientWithResponse('This is not valid JSON');
        $aiClient = new EstimationAiClient($mockClient);

        // Act & Assert
        $this->expectException(InvalidEstimationResponseException::class);
        $this->expectExceptionMessage('Invalid JSON in AI response: Syntax error');

        $aiClient->parseAiResponse($invalidResponse);
    }

    public function test_parse_ai_response_with_missing_fields(): void
    {
        // Arrange
        $incompleteResponse = [
            'content' => json_encode([
                'total_hours_min' => 100,
                'total_hours_max' => 150,
                // Missing 'confidence' field
                'modules' => [],
                'recommended_team' => [],
            ]),
        ];

        $mockClient = $this->createMockPrismClientWithResponse(json_encode($incompleteResponse['content']));
        $aiClient = new EstimationAiClient($mockClient);

        // Act & Assert
        $this->expectException(InvalidEstimationResponseException::class);
        $this->expectExceptionMessage('Missing required field: confidence');

        $aiClient->parseAiResponse($incompleteResponse);
    }

    public function test_build_prompt_includes_all_required_information(): void
    {
        // Arrange
        $projectBasics = new ProjectBasicsDTO(
            name: 'E-commerce Platform',
            projectType: ProjectType::WebApp,
            domainType: DomainType::Ecommerce,
            qualityLevel: QualityLevel::Enterprise,
            clientName: 'Acme Corp',
            shortDescription: 'Online shopping platform with payment processing'
        );

        $requirements = new RequirementsDTO(
            rawText: 'User registration, product catalog, shopping cart, checkout, payment integration',
            quality: RequirementsQuality::Final
        );

        $context = new EstimationContextDTO(
            workforceCountryCode: 'CA',
            teamSeniority: TeamSeniority::Senior,
            techStackSlugs: ['laravel', 'react', 'stripe'],
            isHighCompliance: true,
            fixedDeadline: new \Carbon\Carbon('2024-06-30'),
            fixedBudgetCents: 500000 // $5,000
        );

        // Act
        $prompt = $this->aiClient->buildPrompt($projectBasics, $requirements, $context);

        // Assert
        $this->assertStringContainsString('E-commerce Platform', $prompt);
        $this->assertStringContainsString('Online shopping platform', $prompt);
        $this->assertStringContainsString('User registration, product catalog', $prompt);
        $this->assertStringContainsString('Canada', $prompt);
        $this->assertStringContainsString('Senior', $prompt);
        $this->assertStringContainsString('laravel, react, stripe', $prompt);
        $this->assertStringContainsString('Yes', $prompt);
        $this->assertStringContainsString('2024-06-30', $prompt);
        $this->assertStringContainsString('$5,000.00', $prompt);
        $this->assertStringContainsString('backend_developer', $prompt);
        $this->assertStringContainsString('low, medium, high', $prompt);
    }

    public function test_validate_estimation_data_with_invalid_hours(): void
    {
        // Arrange
        $invalidData = [
            'total_hours_min' => -10, // Invalid: negative hours
            'total_hours_max' => 150,
            'confidence' => 'medium',
            'modules' => [],
            'recommended_team' => [],
        ];

        // Act & Assert
        $this->expectException(InvalidEstimationResponseException::class);
        $this->expectExceptionMessage('Invalid hour estimates: hours must be positive');

        $this->invokePrivateMethod($this->aiClient, 'validateEstimationData', $invalidData);
    }

    public function test_validate_estimation_data_with_invalid_confidence(): void
    {
        // Arrange
        $invalidData = [
            'total_hours_min' => 100,
            'total_hours_max' => 150,
            'confidence' => 'invalid_confidence', // Invalid confidence level
            'modules' => [],
            'recommended_team' => [],
        ];

        // Act & Assert
        $this->expectException(InvalidEstimationResponseException::class);
        $this->expectExceptionMessage('Confidence must be: low, medium, high');

        $this->invokePrivateMethod($this->aiClient, 'validateEstimationData', $invalidData);
    }

    public function test_is_non_retryable_error_with_authentication_error(): void
    {
        // Arrange
        $authError = new \Exception('Authentication failed: invalid API key');

        // Act
        $result = $this->invokePrivateMethod($this->aiClient, 'isNonRetryableError', $authError);

        // Assert
        $this->assertTrue($result, 'Authentication errors should not be retryable');
    }

    public function test_is_non_retryable_error_with_rate_limit_error(): void
    {
        // Arrange
        $rateLimitError = new \Exception('Rate limit exceeded: too many requests');

        // Act
        $result = $this->invokePrivateMethod($this->aiClient, 'isNonRetryableError', $rateLimitError);

        // Assert
        $this->assertTrue($result, 'Rate limit errors should not be retryable');
    }

    public function test_is_retryable_error_with_network_error(): void
    {
        // Arrange
        $networkError = new \Exception('Connection timeout');

        // Act
        $result = $this->invokePrivateMethod($this->aiClient, 'isNonRetryableError', $networkError);

        // Assert
        $this->assertFalse($result, 'Network errors should be retryable');
    }

    private function createMockPrismClient(): object
    {
        return new class
        {
            public function generateResponse(string $prompt, array $options = []): string
            {
                // Return a valid mock response
                return json_encode([
                    'total_hours_min' => 120,
                    'total_hours_max' => 180,
                    'confidence' => 'medium',
                    'modules' => [
                        [
                            'name' => 'User Authentication',
                            'description' => 'Login, registration, password reset',
                            'total_hours_min' => 25,
                            'total_hours_max' => 35,
                            'priority' => 'high',
                            'features' => [
                                [
                                    'name' => 'User Login',
                                    'description' => 'Login form with validation',
                                    'hours_min' => 10,
                                    'hours_max' => 15,
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
                            'total_hours' => 80,
                            'description' => 'API and database development',
                        ],
                        [
                            'role' => 'frontend_developer',
                            'count' => 1,
                            'total_hours' => 40,
                            'description' => 'UI development',
                        ],
                    ],
                    'assumptions' => [
                        'Using Laravel authentication',
                        'MySQL database',
                        'Standard deployment environment',
                    ],
                    'risks' => [
                        'Third-party integration complexity',
                        'Scope changes during development',
                    ],
                    'questions_to_clarify' => [
                        'Expected user load?',
                        'Payment gateway requirements?',
                        'Multi-tenancy needed?',
                    ],
                ]);
            }
        };
    }

    private function createMockPrismClientThatThrows(): object
    {
        return new class
        {
            public function generateResponse(string $prompt, array $options = []): string
            {
                throw new \Exception('Network error');
            }
        };
    }

    private function createMockPrismClientWithResponse(string $response): object
    {
        return new class($response)
        {
            public function __construct(private readonly string $response) {}

            public function generateResponse(string $prompt, array $options = []): string
            {
                return $this->response;
            }
        };
    }

    private function invokePrivateMethod(object $object, string $methodName, array $parameters = []): mixed
    {
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}
