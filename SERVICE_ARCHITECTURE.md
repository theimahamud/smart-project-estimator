# Service Layer Architecture - SmartEstimate AI

## Overview

The service layer implements a clean, testable architecture with clear separation of concerns. Controllers remain thin and orchestrate data flow, while services contain all business logic.

## Architecture Diagram

```
Controller Layer
      ↓
EstimationService (Main Orchestrator)
      ↓
┌─────────────────┬─────────────────┬─────────────────┐
│ RequirementsPreprocessor │ EstimationAiClient │ CostCalculator │
└─────────────────┴─────────────────┴─────────────────┘
                            ↓
                      RateProvider
```

## Core Services

### 1. EstimationService (Main Pipeline)

**File:** `app/Services/Estimation/EstimationService.php`  
**Interface:** `app/Services/Estimation/EstimationServiceInterface.php`

**Main Methods:**

- `estimate()` - Generate estimation from DTOs
- `estimateAndPersist()` - Generate and save to database
- `reestimate()` - Re-run estimation for existing project

**Pipeline Flow:**

1. Preprocess requirements text
2. Call AI service for estimation payload
3. Parse and validate AI response
4. Build EstimationResultDTO with cost calculations
5. Persist to database (if requested)

### 2. EstimationAiClient (AI Integration)

**File:** `app/Services/AI/EstimationAiClient.php`  
**Interface:** `app/Services/AI/EstimationAiClientInterface.php`

**Responsibilities:**

- Build structured prompts for AI
- Call PrismPHP client (configured for DeepSeek)
- Parse and validate AI JSON responses
- Handle AI-specific errors and retries

**Key Features:**

- Comprehensive prompt engineering
- JSON schema validation
- Error handling with logging
- Mock responses for development

### 3. RequirementsPreprocessor (Text Processing)

**File:** `app/Services/Requirements/RequirementsPreprocessor.php`

**Responsibilities:**

- Clean and normalize requirements text
- Truncate oversized content intelligently
- Enhance structure (add bullets, formatting)
- Assess and adjust quality levels
- Extract project keywords

**Features:**

- PDF artifact removal
- Sentence boundary truncation
- Quality level auto-adjustment
- Structure enhancement

### 4. CostCalculator (Financial Calculations)

**File:** `app/Services/Estimation/CostCalculator.php`

**Responsibilities:**

- Convert effort hours to cost ranges
- Calculate project duration estimates
- Apply regional and context adjustments
- Factor in team efficiency and compliance

**Adjustment Factors:**

- Country/region multipliers
- Team seniority impacts
- High compliance overhead
- Fixed deadline pressure

### 5. RateProvider (Rate Management)

**File:** `app/Services/Settings/RateProvider.php`

**Responsibilities:**

- Resolve hourly rates per role/seniority
- Manage user-specific rate overrides
- Provide default rate fallbacks
- Handle rate CRUD operations

**Default Roles Supported:**

- backend_developer, frontend_developer, fullstack_developer
- devops_engineer, ui_ux_designer, project_manager
- qa_engineer, business_analyst, data_scientist, mobile_developer

## Error Handling Strategy

### Custom Exceptions

**AiEstimationFailedException:** `app/Exceptions/AiEstimationFailedException.php`

- AI service unavailable or timeout
- Invalid API responses
- Network errors

**InvalidEstimationResponseException:** `app/Exceptions/InvalidEstimationResponseException.php`

- Malformed JSON from AI
- Missing required fields
- Invalid data types or ranges

### Logging Strategy

- **Info Level:** Successful estimations with context
- **Warning Level:** Validation issues, quality downgrades
- **Error Level:** Service failures, AI errors
- **Sensitive Data:** Automatically filtered from logs

## Dependency Injection

### Service Provider

**File:** `app/Providers/EstimationServiceProvider.php`

**Bindings:**

- `EstimationServiceInterface` → `EstimationService`
- `EstimationAiClientInterface` → `EstimationAiClient`
- PrismPHP client configuration with DeepSeek

### Container Resolution Example

```php
// In controller constructor
public function __construct(
    private readonly EstimationServiceInterface $estimationService
) {}

// The container automatically resolves:
// EstimationService
//   ├── EstimationAiClient (with PrismPHP)
//   ├── RequirementsPreprocessor
//   ├── CostCalculator
//   └── RateProvider
```

## Configuration

### AI Configuration

**File:** `config/ai.php`

**Key Settings:**

- DeepSeek API configuration
- Request limits and timeouts
- Quality thresholds and indicators
- Estimation-specific settings

**Environment Variables:**

```env
DEEPSEEK_API_KEY=your_api_key_here
DEEPSEEK_MODEL=deepseek-coder
AI_MAX_TOKENS=4000
AI_TEMPERATURE=0.1
AI_REQUESTS_PER_MINUTE=10
```

## Usage Examples

### Basic Estimation

```php
// In controller
$result = $this->estimationService->estimate(
    $projectBasics,   // ProjectBasicsDTO
    $requirements,    // RequirementsDTO
    $context,         // EstimationContextDTO
    $userId
);

// Returns EstimationResultDTO with:
// - Hours, cost, duration ranges
// - Module/feature breakdown
// - Team recommendations
// - Risks, assumptions, questions
```

### Estimation with Persistence

```php
$estimate = $this->estimationService->estimateAndPersist(
    $projectBasics,
    $requirements,
    $context,
    $userId,
    $clientId
);

// Creates Project and Estimate models
// Returns Estimate with full JSON data
```

## Testing Strategy

### Service Mocking

```php
// Mock AI client for tests
$this->mock(EstimationAiClientInterface::class, function ($mock) {
    $mock->shouldReceive('generateEstimatePayload')
         ->andReturn($mockResponse);
});
```

### Integration Testing

- Test full pipeline with real data
- Validate DTO conversions
- Verify database persistence
- Check error handling paths

## Performance Considerations

### Caching Strategy

- Cache similar estimation requests (1 hour)
- Cache user rate configurations
- Cache country multipliers

### Optimization

- Lazy load AI client for non-estimation requests
- Batch rate lookups for large teams
- Async AI calls for multiple estimates

## Security

### Data Protection

- API keys secured in environment variables
- Sensitive context filtered from logs
- Input validation at service boundaries
- Rate limiting on AI requests

### Input Sanitization

- Requirements text cleaning
- JSON response validation
- SQL injection protection via Eloquent
- XSS prevention in text processing

---

The service layer provides a robust, maintainable foundation for SmartEstimate AI with clear contracts, comprehensive error handling, and full testability.
