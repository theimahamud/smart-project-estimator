<?php

namespace App\Http\Controllers;

use App\Models\Estimate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with recent estimates and statistics.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get recent estimates for the authenticated user
        $recentEstimates = Estimate::where('user_id', $user->id)
            ->with(['project', 'project.client'])
            ->latest()
            ->take(10)
            ->get();

        // Calculate dashboard statistics
        $totalEstimates = Estimate::where('user_id', $user->id)->count();
        $totalProjects = $recentEstimates->groupBy('project_id')->count();
        $avgConfidence = $recentEstimates->avg('confidence_score') ?? 0;
        
        // Calculate total estimated value
        $totalValue = $recentEstimates->sum(function ($estimate) {
            return ($estimate->total_hours_min + $estimate->total_hours_max) / 2 * 100; // Assuming $100/hour average
        });

        return view('dashboard', [
            'recentEstimates' => $recentEstimates,
            'stats' => [
                'total_estimates' => $totalEstimates,
                'total_projects' => $totalProjects,
                'avg_confidence' => round($avgConfidence, 1),
                'total_value' => $totalValue,
            ],
        ]);
    }
}
