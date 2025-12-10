<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\HourlyRate;
use App\Models\UserSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get user settings or create default ones
        $userSettings = UserSetting::firstOrCreate(
            ['user_id' => $user->id],
            [
                'default_country' => 'US',
                'default_tech_stack' => ['laravel', 'vue'],
                'default_quality_level' => 'production',
            ]
        );

        // Get hourly rates or create defaults
        $hourlyRates = HourlyRate::where('user_id', $user->id)->get()->keyBy('role');
        
        // Default rates if none exist
        $defaultRates = [
            'backend_developer' => 120,
            'frontend_developer' => 110,
            'qa_engineer' => 90,
            'project_manager' => 130,
            'ui_ux_designer' => 100,
        ];

        return view('settings.index', [
            'userSettings' => $userSettings,
            'hourlyRates' => $hourlyRates,
            'defaultRates' => $defaultRates,
        ]);
    }

    /**
     * Update the user's settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'default_country' => ['required', 'string', 'max:2'],
            'default_tech_stack' => ['required', 'array'],
            'default_tech_stack.*' => ['required', 'string'],
            'default_quality_level' => ['required', Rule::in(['mvp', 'production', 'enterprise'])],
            'hourly_rates' => ['required', 'array'],
            'hourly_rates.*' => ['required', 'numeric', 'min:0', 'max:1000'],
        ]);

        $user = Auth::user();

        // Update user settings
        UserSetting::updateOrCreate(
            ['user_id' => $user->id],
            [
                'default_country' => $validated['default_country'],
                'default_tech_stack' => $validated['default_tech_stack'],
                'default_quality_level' => $validated['default_quality_level'],
            ]
        );

        // Update hourly rates
        foreach ($validated['hourly_rates'] as $role => $rate) {
            HourlyRate::updateOrCreate(
                ['user_id' => $user->id, 'role' => $role],
                ['rate_cents' => $rate * 100] // Convert to cents
            );
        }

        return redirect()->route('settings.index')
            ->with('success', 'Settings updated successfully!');
    }

    /**
     * Reset settings to recommended defaults.
     */
    public function reset()
    {
        $user = Auth::user();

        // Reset user settings to defaults
        UserSetting::updateOrCreate(
            ['user_id' => $user->id],
            [
                'default_country' => 'US',
                'default_tech_stack' => ['laravel', 'vue'],
                'default_quality_level' => 'production',
            ]
        );

        // Reset hourly rates to defaults
        $defaultRates = [
            'backend_developer' => 12000, // $120.00 in cents
            'frontend_developer' => 11000, // $110.00 in cents
            'qa_engineer' => 9000, // $90.00 in cents
            'project_manager' => 13000, // $130.00 in cents
            'ui_ux_designer' => 10000, // $100.00 in cents
        ];

        foreach ($defaultRates as $role => $rateCents) {
            HourlyRate::updateOrCreate(
                ['user_id' => $user->id, 'role' => $role],
                ['rate_cents' => $rateCents]
            );
        }

        return redirect()->route('settings.index')
            ->with('success', 'Settings reset to recommended defaults!');
    }
}
