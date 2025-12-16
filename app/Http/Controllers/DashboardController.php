<?php

namespace App\Http\Controllers;

use App\Models\Estimate;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with recent estimates and statistics.
     */
    public function index()
    {
        $user = Auth::user();

        // Get recent estimates for the authenticated user through their projects
        $recentEstimates = Estimate::whereHas('project', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->with(['project', 'project.client'])
            ->latest()
            ->take(10)
            ->get();

        // Calculate dashboard statistics
        $totalEstimates = Estimate::whereHas('project', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->count();
        $totalProjects = $recentEstimates->groupBy('project_id')->count();
        // For now, set a placeholder confidence score since we need to determine how to calculate this
        $avgConfidence = 0;

        // Calculate total estimated value
        $totalValue = $recentEstimates->sum(function ($estimate) {
            $minHours = $estimate->min_hours ?? 0;
            $maxHours = $estimate->max_hours ?? 0;

            return ($minHours + $maxHours) / 2 * 100; // Assuming $100/hour average
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
