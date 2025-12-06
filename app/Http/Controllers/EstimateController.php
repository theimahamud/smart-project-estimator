<?php

namespace App\Http\Controllers;

use App\Models\Estimate;
use App\Models\Region;
use App\Models\Technology;
use App\Services\OpenAIService;
use App\Services\EstimationEngine;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EstimateController extends Controller
{
    public function __construct(
        private OpenAIService $openAIService,
        private EstimationEngine $estimationEngine
    ) {}

    public function index()
    {
        $estimates = auth()->user()->estimates()
            ->with(['region', 'requirements', 'breakdowns.teamRole'])
            ->latest()
            ->paginate(10);

        return Inertia::render('Estimates/Index', [
            'estimates' => $estimates,
        ]);
    }

    public function create()
    {
        $regions = Region::where('is_active', true)->get();
        $technologies = Technology::where('is_active', true)->get();

        return Inertia::render('Estimates/Create', [
            'regions' => $regions,
            'technologies' => $technologies,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_name' => 'required|string|max:255',
            'raw_requirements' => 'required|string|min:50',
            'region_id' => 'nullable|exists:regions,id',
            'selected_technologies' => 'nullable|array',
            'team_size' => 'integer|min:1|max:50',
        ]);

        $estimate = Estimate::create([
            'user_id' => auth()->id(),
            'project_name' => $validated['project_name'],
            'raw_requirements' => $validated['raw_requirements'],
            'region_id' => $validated['region_id'] ?? null,
            'selected_technologies' => $validated['selected_technologies'] ?? [],
            'team_size' => $validated['team_size'] ?? 3,
            'status' => 'processing',
        ]);

        try {
            $aiData = $this->openAIService->analyzeRequirements($validated['raw_requirements']);
            $this->estimationEngine->calculateEstimate($estimate, $aiData);
        } catch (\Exception $e) {
            $estimate->update(['status' => 'failed']);
            return back()->with('error', 'Failed to generate estimate. Please try again.');
        }

        return redirect()->route('estimates.show', $estimate)
            ->with('success', 'Estimate generated successfully!');
    }

    public function show(Estimate $estimate)
    {
       // $this->authorize('view', $estimate);

        $estimate->load(['region', 'requirements', 'breakdowns.teamRole']);

        return Inertia::render('Estimates/Show', [
            'estimate' => $estimate,
        ]);
    }

    public function destroy(Estimate $estimate)
    {
      //  $this->authorize('delete', $estimate);

        $estimate->delete();

        return redirect()->route('estimates.index')
            ->with('success', 'Estimate deleted successfully!');
    }
}
