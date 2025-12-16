<x-layouts.main title="Estimate Details - SmartEstimate AI">
    <!-- Page Heading -->
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
            <div class="flex flex-col gap-2">
                <h1 class="text-3xl font-black leading-tight tracking-[-0.033em] text-gray-900 dark:text-white">{{ $estimate->project->name }}</h1>
                <p class="text-base font-normal text-gray-500 dark:text-gray-400">Project estimate generated on {{ $estimate->created_at->format('M j, Y') }}</p>
            </div>
            <div class="flex gap-3">
                <form method="POST" action="{{ route('estimates.destroy', $estimate) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this estimate?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-red-600 hover:text-red-700 border border-red-300 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20">
                        Delete
                    </button>
                </form>
                <a href="{{ route('estimates.create') }}" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary/90">
                    <span class="material-symbols-outlined text-lg">add</span>
                    New Estimate
                </a>
            </div>
        </div>
        
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Total Cost Card -->
            <div class="bg-white dark:bg-gray-900/50 p-6 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Cost</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">${{ number_format($estimate->total_cost, 0) }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            @if(isset($estimate->breakdown['cost_cents_min']) && isset($estimate->breakdown['cost_cents_max']))
                                Range: ${{ number_format($estimate->breakdown['cost_cents_min'] / 100, 0) }} - ${{ number_format($estimate->breakdown['cost_cents_max'] / 100, 0) }}
                            @else
                                Based on {{ $estimate->total_hours }} hours
                            @endif
                        </p>
                    </div>
                    <div class="bg-green-100 dark:bg-green-900/50 p-3 rounded-full">
                        <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-2xl">attach_money</span>
                    </div>
                </div>
            </div>
            
            <!-- Duration Card -->
            <div class="bg-white dark:bg-gray-900/50 p-6 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Duration</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ ceil($estimate->total_hours / 40) }} weeks</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            {{ $estimate->total_hours }} total hours
                            @if(isset($estimate->breakdown['duration_weeks_min']) && isset($estimate->breakdown['duration_weeks_max']))
                                ({{ $estimate->breakdown['duration_weeks_min'] }}-{{ $estimate->breakdown['duration_weeks_max'] }} weeks)
                            @endif
                        </p>
                    </div>
                    <div class="bg-blue-100 dark:bg-blue-900/50 p-3 rounded-full">
                        <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-2xl">schedule</span>
                    </div>
                </div>
            </div>
            
            <!-- Confidence Card -->
            <div class="bg-white dark:bg-gray-900/50 p-6 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Confidence Level</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1 capitalize">{{ $estimate->confidence_level ?? 'Medium' }}</p>
                        @php
                            $confidence = $estimate->confidence_level ?? \App\Enums\ConfidenceLevel::Medium;
                            $badgeClass = match($confidence) {
                                \App\Enums\ConfidenceLevel::High => 'bg-teal-100 dark:bg-teal-900/50 text-teal-700 dark:text-teal-300',
                                \App\Enums\ConfidenceLevel::Medium => 'bg-amber-100 dark:bg-amber-900/50 text-amber-700 dark:text-amber-300',
                                \App\Enums\ConfidenceLevel::Low => 'bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-300',
                                default => 'bg-gray-100 dark:bg-gray-900/50 text-gray-700 dark:text-gray-300'
                            };
                        @endphp
                        <span class="inline-flex items-center rounded-full {{ $badgeClass }} px-2 py-1 text-xs font-medium mt-1">
                            {{ $confidence->label() }} Confidence
                        </span>
                    </div>
                    <div class="bg-purple-100 dark:bg-purple-900/50 p-3 rounded-full">
                        <span class="material-symbols-outlined text-purple-600 dark:text-purple-400 text-2xl">trending_up</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Project Details -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Project Information -->
            <div class="bg-white dark:bg-gray-900/50 p-6 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Project Information</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Project Type</p>
                        <p class="text-gray-900 dark:text-white">{{ $estimate->project->project_type->label() }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Domain</p>
                        <p class="text-gray-900 dark:text-white">{{ $estimate->project->domain_type->label() }}</p>
                    </div>
                    @if($estimate->project->description)
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Description</p>
                            <p class="text-gray-900 dark:text-white">{{ $estimate->project->description }}</p>
                        </div>
                    @endif
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Created</p>
                        <p class="text-gray-900 dark:text-white">{{ $estimate->created_at->format('F j, Y \a\t g:i A') }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Requirements Summary -->
            <div class="bg-white dark:bg-gray-900/50 p-6 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Requirements Summary</h3>
                @if($estimate->requirements ?? $estimate->project->functional_requirements)
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Requirements Quality</p>
                            <p class="text-gray-900 dark:text-white capitalize">{{ $estimate->requirements_quality ?? 'Draft' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Requirements Text</p>
                            <div class="bg-gray-50 dark:bg-gray-800 p-3 rounded-lg text-sm text-gray-700 dark:text-gray-300 max-h-32 overflow-y-auto">
                                {{ Str::limit($estimate->requirements ?? $estimate->project->functional_requirements, 300) }}
                            </div>
                        </div>
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400">No requirements available</p>
                @endif
            </div>
        </div>
        
        <!-- Detailed Breakdown -->
        @if(isset($estimate->breakdown['module_breakdown']) && is_array($estimate->breakdown['module_breakdown']))
            <div class="mt-8">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Module Breakdown</h3>
                <div class="space-y-4">
                    @foreach($estimate->breakdown['module_breakdown'] as $module)
                        <div class="bg-white dark:bg-gray-900/50 p-6 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $module['name'] ?? 'Module' }}</h4>
                                    @if(isset($module['description']))
                                        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $module['description'] }}</p>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold text-primary">
                                        @if(isset($module['total_hours_min']) && isset($module['total_hours_max']))
                                            {{ $module['total_hours_min'] }}-{{ $module['total_hours_max'] }}h
                                        @else
                                            {{ $module['hours'] ?? 'TBD' }}h
                                        @endif
                                    </p>
                                    @if(isset($module['priority']))
                                        <p class="text-sm text-gray-500 dark:text-gray-400 capitalize">{{ $module['priority'] }} Priority</p>
                                    @endif
                                </div>
                            </div>
                            
                            @if(isset($module['features']) && is_array($module['features']))
                                <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                                    <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Features:</h5>
                                    <ul class="space-y-2">
                                        @foreach($module['features'] as $feature)
                                            <li class="flex justify-between items-center text-sm">
                                                <span class="text-gray-600 dark:text-gray-400">{{ $feature['name'] ?? 'Feature' }}</span>
                                                <span class="text-gray-500 dark:text-gray-500">
                                                    @if(isset($feature['hours_min']) && isset($feature['hours_max']))
                                                        {{ $feature['hours_min'] }}-{{ $feature['hours_max'] }}h
                                                    @else
                                                        {{ $feature['hours'] ?? 'TBD' }}h
                                                    @endif
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        
        <!-- Recommendations & Risks -->
        <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Assumptions -->
            @if(isset($estimate->assumptions) && is_array($estimate->assumptions) && count($estimate->assumptions) > 0)
                <div class="bg-white dark:bg-gray-900/50 p-6 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Assumptions</h3>
                    <ul class="space-y-2">
                        @foreach($estimate->assumptions as $assumption)
                            <li class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-blue-500 dark:text-blue-400 text-sm mt-0.5">info</span>
                                <span class="text-gray-700 dark:text-gray-300 text-sm">{{ $assumption }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <!-- Risks -->
            @if(isset($estimate->risks) && is_array($estimate->risks) && count($estimate->risks) > 0)
                <div class="bg-white dark:bg-gray-900/50 p-6 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Risk Factors</h3>
                    <ul class="space-y-2">
                        @foreach($estimate->risks as $risk)
                            <li class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-amber-500 dark:text-amber-400 text-sm mt-0.5">warning</span>
                                <span class="text-gray-700 dark:text-gray-300 text-sm">{{ $risk }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <!-- Recommendations -->
            @if(isset($estimate->recommendations) && is_array($estimate->recommendations) && count($estimate->recommendations) > 0)
                <div class="bg-white dark:bg-gray-900/50 p-6 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm lg:col-span-2">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Recommendations</h3>
                    <ul class="space-y-2">
                        @foreach($estimate->recommendations as $recommendation)
                            <li class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-green-500 dark:text-green-400 text-sm mt-0.5">lightbulb</span>
                                <span class="text-gray-700 dark:text-gray-300 text-sm">{{ $recommendation }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
</x-layouts.main>