# DTO Usage Examples for SmartEstimate AI

## Overview

These examples show how the DTOs are used throughout the application flow from controllers to services to models.

## 1. Controller Layer - Creating DTOs from Requests

```php
<?php
// In EstimationController

public function store(EstimationRequest $request)
{
    // Create DTOs from validated request data
    $projectBasics = new ProjectBasicsDTO(
        name: $request->validated('name'),
        projectType: ProjectType::from($request->validated('project_type')),
        domainType: DomainType::from($request->validated('domain_type')),
        qualityLevel: QualityLevel::from($request->validated('quality_level')),
        clientName: $request->validated('client_name'),
        shortDescription: $request->validated('description')
    );

    $requirements = new RequirementsDTO(
        rawText: $request->validated('requirements'),
        quality: RequirementsQuality::from($request->validated('requirements_quality'))
    );

    $context = new EstimationContextDTO(
        workforceCountryCode: $request->validated('country', 'US'),
        teamSeniority: TeamSeniority::from($request->validated('team_seniority')),
        techStackSlugs: $request->validated('tech_stack', []),
        isHighCompliance: $request->validated('high_compliance', false),
        fixedDeadline: $request->validated('deadline') ? Carbon::parse($request->validated('deadline')) : null,
        fixedBudgetCents: $request->validated('budget_cents')
    );

    // Pass DTOs to service
    $estimation = $this->estimationService->generateEstimation($projectBasics, $requirements, $context);

    return redirect()->route('projects.estimates.show', $estimation);
}
```

## 2. Service Layer - Processing DTOs

```php
<?php
// In EstimationService

public function generateEstimation(
    ProjectBasicsDTO $projectBasics,
    RequirementsDTO $requirements,
    EstimationContextDTO $context
): Estimate {
    // Use DTOs in business logic
    $aiPrompt = $this->buildAiPrompt($projectBasics, $requirements, $context);

    // Call AI service with DTOs
    $estimationResult = $this->aiService->generateEstimation($aiPrompt, $context);

    // Create project and estimate models
    $project = $this->createProject($projectBasics, $context);
    $estimate = $this->createEstimate($project, $requirements, $context, $estimationResult);

    return $estimate;
}

private function createProject(ProjectBasicsDTO $projectBasics, EstimationContextDTO $context): Project
{
    return Project::create([
        'user_id' => auth()->id(),
        'name' => $projectBasics->name,
        'description' => $projectBasics->shortDescription,
        'project_type' => $projectBasics->projectType,
        'domain_type' => $projectBasics->domainType,
        'country' => $context->workforceCountryCode,
        'tech_stack' => $context->techStackSlugs,
    ]);
}

private function createEstimate(
    Project $project,
    RequirementsDTO $requirements,
    EstimationContextDTO $context,
    EstimationResultDTO $result
): Estimate {
    return Estimate::create([
        'project_id' => $project->id,
        'status' => EstimateStatus::Completed,
        'requirements' => $requirements->rawText,
        'requirements_quality' => $requirements->quality,
        'quality_level' => $project->qualityLevel, // from project basics
        'team_seniority' => $context->teamSeniority,
        'confidence_level' => $result->confidence,
        'total_hours' => $result->getAverageHours(),
        'min_hours' => $result->totalHoursMin,
        'max_hours' => $result->totalHoursMax,
        'total_cost' => $result->costCentsTypical / 100,
        'min_cost' => $result->costCentsMin / 100,
        'max_cost' => $result->costCentsMax / 100,
        'breakdown' => $result->toArray(), // JSON casting handles this
        'assumptions' => $result->assumptions,
        'risks' => $result->risks,
        'recommendations' => $result->questionsToClarify,
    ]);
}
```

## 3. AI Service - Working with DTOs

```php
<?php
// In AIEstimationService

public function generateEstimation(string $prompt, EstimationContextDTO $context): EstimationResultDTO
{
    try {
        $response = $this->prismClient->generateResponse($prompt);
        $parsed = $this->parseAiResponse($response);

        return $this->buildEstimationResult($parsed, $context);
    } catch (Exception $e) {
        Log::error('AI estimation failed', [
            'context' => $context->toArray(),
            'error' => $e->getMessage()
        ]);
        throw new EstimationException('Failed to generate estimation', 0, $e);
    }
}

private function buildEstimationResult(array $aiData, EstimationContextDTO $context): EstimationResultDTO
{
    // Build module breakdowns from AI response
    $modules = [];
    foreach ($aiData['modules'] as $moduleData) {
        $features = [];
        foreach ($moduleData['features'] as $featureData) {
            $features[] = new FeatureBreakdownDTO(
                name: $featureData['name'],
                description: $featureData['description'],
                hoursMin: $featureData['hours_min'],
                hoursMax: $featureData['hours_max'],
                complexity: $featureData['complexity'],
                techStack: $context->techStackSlugs,
                dependencies: $featureData['dependencies'] ?? []
            );
        }

        $modules[] = new ModuleBreakdownDTO(
            name: $moduleData['name'],
            description: $moduleData['description'],
            features: $features,
            totalHoursMin: $moduleData['total_hours_min'],
            totalHoursMax: $moduleData['total_hours_max'],
            priority: $moduleData['priority']
        );
    }

    // Build recommended team
    $team = [];
    foreach ($aiData['team'] as $roleData) {
        $team[] = new RecommendedRoleDTO(
            role: $roleData['role'],
            count: $roleData['count'],
            totalHours: $roleData['total_hours'],
            hourlyRate: $this->getHourlyRate($roleData['role'], $context->teamSeniority),
            seniority: $context->teamSeniority->value,
            description: $roleData['description'] ?? null
        );
    }

    return new EstimationResultDTO(
        totalHoursMin: $aiData['total_hours_min'],
        totalHoursMax: $aiData['total_hours_max'],
        durationWeeksMin: $aiData['duration_weeks_min'],
        durationWeeksMax: $aiData['duration_weeks_max'],
        costCentsMin: $aiData['cost_cents_min'],
        costCentsTypical: $aiData['cost_cents_typical'],
        costCentsMax: $aiData['cost_cents_max'],
        confidence: ConfidenceLevel::from($aiData['confidence']),
        moduleBreakdown: $modules,
        recommendedTeam: $team,
        risks: $aiData['risks'] ?? [],
        assumptions: $aiData['assumptions'] ?? [],
        questionsToClarify: $aiData['questions'] ?? []
    );
}
```

## 4. Model Integration - Reading DTOs from Database

```php
<?php
// In Estimate model or service

public function getEstimationResult(): EstimationResultDTO
{
    // The breakdown column is JSON-casted and contains the full DTO data
    return EstimationResultDTO::fromArray($this->breakdown);
}

public function getProjectBasics(): ProjectBasicsDTO
{
    return new ProjectBasicsDTO(
        name: $this->project->name,
        projectType: $this->project->project_type,
        domainType: $this->project->domain_type,
        qualityLevel: $this->quality_level,
        clientName: $this->project->client?->name,
        shortDescription: $this->project->description
    );
}

public function getRequirements(): RequirementsDTO
{
    return new RequirementsDTO(
        rawText: $this->requirements,
        quality: $this->requirements_quality
    );
}
```

## 5. View Integration - Using DTOs in Blade

```php
<?php
// In controller passing to view

public function show(Estimate $estimate)
{
    $estimationResult = $estimate->getEstimationResult();
    $projectBasics = $estimate->getProjectBasics();

    return view('estimates.show', compact('estimate', 'estimationResult', 'projectBasics'));
}
```

```html
<!-- In Blade template -->
<div class="estimation-summary">
    <h2>{{ $projectBasics->name }}</h2>
    <p>Type: {{ $projectBasics->projectType->label() }}</p>
    <p>Domain: {{ $projectBasics->domainType->label() }}</p>

    <div class="hours">
        <strong>Total Hours:</strong> {{ $estimationResult->getTotalHoursRange()
        }}
    </div>

    <div class="cost">
        <strong>Cost Range:</strong> {{
        $estimationResult->getCostRangeFormatted() }}
    </div>

    <div class="confidence">
        <span class="badge badge-{{ $estimationResult->confidence->value }}">
            {{ $estimationResult->confidence->label() }} Confidence ({{
            $estimationResult->confidence->percentage() }})
        </span>
    </div>
</div>

<div class="modules">
    <h3>
        Module Breakdown ({{ $estimationResult->getTotalModules() }} modules)
    </h3>
    @foreach ($estimationResult->moduleBreakdown as $module)
    <div class="module">
        <h4>{{ $module->name }}</h4>
        <p>{{ $module->description }}</p>
        <p><strong>Hours:</strong> {{ $module->getHoursRange() }}</p>
        <p><strong>Features:</strong> {{ $module->getFeatureCount() }}</p>

        @foreach ($module->features as $feature)
        <div class="feature">
            <h5>{{ $feature->name }}</h5>
            <p>{{ $feature->description }}</p>
            <span class="complexity complexity-{{ $feature->complexity }}">
                {{ ucfirst($feature->complexity) }}
            </span>
        </div>
        @endforeach
    </div>
    @endforeach
</div>
```

## Key Benefits

1. **Type Safety**: All properties are strictly typed
2. **Immutability**: DTOs cannot be modified after creation
3. **Serialization**: Easy conversion to/from arrays for JSON storage
4. **Validation**: Data is validated at the DTO boundary
5. **Helper Methods**: Business logic methods on DTOs for formatting and calculations
6. **Clear Data Flow**: Explicit data contracts between layers
