<?php

namespace App\Http\Controllers;

use App\Models\Estimate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    /**
     * Display the user's estimation history.
     */
    public function index(Request $request)
    {
        $query = Estimate::where('user_id', Auth::id())
            ->with(['project', 'project.client']);

        // Apply filters if provided
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->whereHas('project', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('client', function ($clientQuery) use ($search) {
                      $clientQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->filled('confidence')) {
            $confidenceFilter = $request->get('confidence');
            switch ($confidenceFilter) {
                case 'high':
                    $query->where('confidence_score', '>=', 80);
                    break;
                case 'medium':
                    $query->whereBetween('confidence_score', [50, 79]);
                    break;
                case 'low':
                    $query->where('confidence_score', '<', 50);
                    break;
            }
        }

        $estimates = $query->latest()->paginate(15);

        return view('history.index', [
            'estimates' => $estimates,
            'filters' => $request->only(['search', 'status', 'confidence']),
        ]);
    }

    /**
     * Display a specific estimate from history.
     */
    public function show(Estimate $estimate)
    {
        // Ensure the user can only view their own estimates
        if ($estimate->user_id !== Auth::id()) {
            abort(403);
        }

        $estimate->load(['project', 'project.client']);

        return view('estimates.show', compact('estimate'));
    }
}
