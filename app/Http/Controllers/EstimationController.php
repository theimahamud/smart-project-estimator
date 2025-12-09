<?php

namespace App\Http\Controllers;

use App\DTO\EstimationContextDTO;
use App\DTO\ProjectBasicsDTO;
use App\DTO\RequirementsDTO;
use App\Enums\DomainType;
use App\Enums\ProjectType;
use App\Enums\QualityLevel;
use App\Enums\RequirementsQuality;
use App\Enums\TeamSeniority;
use App\Http\Requests\EstimationRequest;
use App\Models\Client;
use App\Models\Estimate;
use App\Services\Estimation\EstimationServiceInterface;
use App\Services\Settings\RateProvider;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class EstimationController extends Controller implements HasMiddleware
{
    use AuthorizesRequests;

    public function __construct(
        private readonly EstimationServiceInterface $estimationService,
        private readonly RateProvider $rateProvider,
    ) {}

    public static function middleware(): array
    {
        return [
            'auth',
        ];
    }

    public function index(): View
    {
        $estimates = Estimate::query()
            ->with(['project'])
            ->where('project_id', fn ($q) => $q->select('id')
                ->from('projects')
                ->where('user_id', Auth::id()))
            ->latest()
            ->paginate(10);

        return view('estimates.index', compact('estimates'));
    }

    public function create(): View
    {
        $clients = Client::query()
            ->where('user_id', Auth::id())
            ->orderBy('name')
            ->get();

        return view('estimates.create', [
            'clients' => $clients,
            'projectTypes' => ProjectType::cases(),
            'domainTypes' => DomainType::cases(),
            'qualityLevels' => QualityLevel::cases(),
            'teamSeniorities' => TeamSeniority::cases(),
            'requirementsQualities' => RequirementsQuality::cases(),
        ]);
    }

    public function store(EstimationRequest $request): RedirectResponse|JsonResponse
    {
        try {
            // Build all requirements text into single string
            $allRequirements = implode("\n\n", array_filter([
                "Functional Requirements:\n".$request->validated('functional_requirements'),
                $request->validated('technical_requirements')
                    ? "Technical Requirements:\n".$request->validated('technical_requirements')
                    : null,
                $request->validated('quality_requirements')
                    ? "Quality Requirements:\n".$request->validated('quality_requirements')
                    : null,
                $request->validated('constraints')
                    ? "Constraints:\n".$request->validated('constraints')
                    : null,
                $request->validated('assumptions')
                    ? "Assumptions:\n".$request->validated('assumptions')
                    : null,
                $request->validated('additional_context')
                    ? "Additional Context:\n".$request->validated('additional_context')
                    : null,
            ]));

            // Create DTOs with correct constructor parameters
            $projectBasics = new ProjectBasicsDTO(
                name: $request->validated('name'),
                projectType: ProjectType::from($request->validated('project_type')),
                domainType: DomainType::from($request->validated('domain_type')),
                qualityLevel: QualityLevel::from($request->validated('desired_quality_level')),
                clientName: $request->validated('client_id')
                    ? Client::find($request->validated('client_id'))?->name
                    : null,
                shortDescription: $request->validated('description')
            );

            $requirements = new RequirementsDTO(
                rawText: $allRequirements,
                quality: RequirementsQuality::from($request->validated('requirements_quality'))
            );

            $context = new EstimationContextDTO(
                workforceCountryCode: 'US', // Default to US, could be configurable
                teamSeniority: TeamSeniority::from($request->validated('team_seniority')),
                techStackSlugs: ['laravel', 'php', 'mysql'], // Default tech stack
                isHighCompliance: false,
                fixedDeadline: null,
                fixedBudgetCents: null
            );

            // Use the service's estimateAndPersist method which handles everything
            $estimate = $this->estimationService->estimateAndPersist(
                $projectBasics,
                $requirements,
                $context,
                Auth::id(),
                $request->validated('client_id')
            );

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'estimate_id' => $estimate->id,
                    'message' => 'Estimation completed successfully',
                ]);
            }

            return to_route('estimates.show', $estimate)
                ->with('status', 'Estimation completed successfully');

        } catch (\Exception $e) {
            Log::error('Estimation failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to generate estimation. Please try again.',
                ], 500);
            }

            return back()
                ->withInput()
                ->with('error', 'Failed to generate estimation. Please try again.');
        }
    }

    public function show(Estimate $estimate): View
    {
        // Simple authorization check
        if ($estimate->project->user_id !== Auth::id()) {
            abort(403);
        }

        $estimate->load(['project']);

        return view('estimates.show', compact('estimate'));
    }

    public function destroy(Estimate $estimate): RedirectResponse
    {
        // Simple authorization check
        if ($estimate->project->user_id !== Auth::id()) {
            abort(403);
        }

        $estimate->delete();

        return to_route('estimates.index')
            ->with('status', 'Estimate deleted successfully');
    }
}
