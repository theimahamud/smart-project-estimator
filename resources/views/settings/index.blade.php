<x-layouts.main>
    <x-slot:title>Settings - SmartEstimate AI</x-slot:title>

    <div class="max-w-4xl mx-auto">
        <!-- Page Heading -->
        <div class="flex flex-wrap justify-between gap-3 mb-8">
            <h1 class="text-text-light dark:text-text-dark text-4xl font-black leading-tight tracking-[-0.033em]">Settings</h1>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-100 dark:bg-green-900/50 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('settings.update') }}" method="POST" class="flex flex-col gap-8">
            @csrf
            @method('PUT')

            <!-- Defaults Card -->
            <div class="bg-card-light dark:bg-card-dark rounded-xl border border-border-light dark:border-border-dark shadow-sm">
                <div class="p-6 border-b border-border-light dark:border-border-dark">
                    <h2 class="text-text-light dark:text-text-dark text-[22px] font-bold leading-tight tracking-[-0.015em]">Defaults</h2>
                    <p class="text-subtext-light dark:text-subtext-dark text-sm mt-1">Set your default parameters for new project estimations.</p>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Default Country -->
                    <label class="flex flex-col">
                        <p class="text-text-light dark:text-text-dark text-sm font-medium leading-normal pb-2">Default Country</p>
                        <select name="default_country" class="form-select w-full rounded-lg text-text-light dark:text-text-dark focus:outline-none focus:ring-2 focus:ring-primary border border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark focus:border-primary h-12 placeholder:text-subtext-light dark:placeholder:text-subtext-dark px-3 text-base font-normal leading-normal">
                            <option value="US" {{ $userSettings->default_country === 'US' ? 'selected' : '' }}>United States</option>
                            <option value="CA" {{ $userSettings->default_country === 'CA' ? 'selected' : '' }}>Canada</option>
                            <option value="GB" {{ $userSettings->default_country === 'GB' ? 'selected' : '' }}>United Kingdom</option>
                            <option value="AU" {{ $userSettings->default_country === 'AU' ? 'selected' : '' }}>Australia</option>
                            <option value="DE" {{ $userSettings->default_country === 'DE' ? 'selected' : '' }}>Germany</option>
                        </select>
                        @error('default_country')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </label>

                    <!-- Default Tech Stack -->
                    <label class="flex flex-col">
                        <p class="text-text-light dark:text-text-dark text-sm font-medium leading-normal pb-2">Default Tech Stack</p>
                        <select name="default_tech_stack[]" multiple class="form-select w-full rounded-lg text-text-light dark:text-text-dark focus:outline-none focus:ring-2 focus:ring-primary border border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark focus:border-primary h-12 placeholder:text-subtext-light dark:placeholder:text-subtext-dark px-3 text-base font-normal leading-normal">
                            @php
                                $selectedStack = $userSettings->default_tech_stack ?? [];
                            @endphp
                            <option value="laravel" {{ in_array('laravel', $selectedStack) ? 'selected' : '' }}>Laravel</option>
                            <option value="react" {{ in_array('react', $selectedStack) ? 'selected' : '' }}>React</option>
                            <option value="vue" {{ in_array('vue', $selectedStack) ? 'selected' : '' }}>Vue.js</option>
                            <option value="nodejs" {{ in_array('nodejs', $selectedStack) ? 'selected' : '' }}>Node.js</option>
                            <option value="python" {{ in_array('python', $selectedStack) ? 'selected' : '' }}>Python</option>
                        </select>
                        @error('default_tech_stack')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </label>

                    <!-- Default Quality Level -->
                    <label class="flex flex-col">
                        <p class="text-text-light dark:text-text-dark text-sm font-medium leading-normal pb-2">Default Quality Level</p>
                        <select name="default_quality_level" class="form-select w-full rounded-lg text-text-light dark:text-text-dark focus:outline-none focus:ring-2 focus:ring-primary border border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark focus:border-primary h-12 placeholder:text-subtext-light dark:placeholder:text-subtext-dark px-3 text-base font-normal leading-normal">
                            <option value="mvp" {{ $userSettings->default_quality_level === 'mvp' ? 'selected' : '' }}>MVP</option>
                            <option value="production" {{ $userSettings->default_quality_level === 'production' ? 'selected' : '' }}>Production</option>
                            <option value="enterprise" {{ $userSettings->default_quality_level === 'enterprise' ? 'selected' : '' }}>Enterprise</option>
                        </select>
                        @error('default_quality_level')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </label>
                </div>
            </div>

            <!-- Hourly Rates Card -->
            <div class="bg-card-light dark:bg-card-dark rounded-xl border border-border-light dark:border-border-dark shadow-sm">
                <div class="p-6 border-b border-border-light dark:border-border-dark">
                    <h2 class="text-text-light dark:text-text-dark text-[22px] font-bold leading-tight tracking-[-0.015em]">Hourly rates</h2>
                    <p class="text-subtext-light dark:text-subtext-dark text-sm mt-1">Define the hourly rates for different roles on your team.</p>
                </div>
                <div class="p-2 sm:p-6">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr>
                                    <th class="p-4 text-sm font-semibold text-subtext-light dark:text-subtext-dark">Role</th>
                                    <th class="p-4 text-sm font-semibold text-subtext-light dark:text-subtext-dark">Rate (USD/hr)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $roles = [
                                        'backend_developer' => 'Backend Developer',
                                        'frontend_developer' => 'Frontend Developer',
                                        'qa_engineer' => 'QA Engineer',
                                        'project_manager' => 'Project Manager',
                                        'ui_ux_designer' => 'UI/UX Designer',
                                    ];
                                @endphp
                                @foreach($roles as $roleKey => $roleLabel)
                                    <tr class="border-t border-border-light dark:border-border-dark">
                                        <td class="p-4 text-sm font-medium text-text-light dark:text-text-dark">{{ $roleLabel }}</td>
                                        <td class="p-4 w-48">
                                            <div class="relative">
                                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-subtext-light dark:text-subtext-dark">$</span>
                                                <input 
                                                    name="hourly_rates[{{ $roleKey }}]"
                                                    class="form-input pl-7 w-full rounded-lg text-text-light dark:text-text-dark focus:outline-none focus:ring-2 focus:ring-primary border border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark focus:border-primary h-12 placeholder:text-subtext-light dark:placeholder:text-subtext-dark text-base font-normal leading-normal" 
                                                    type="number" 
                                                    value="{{ $hourlyRates->get($roleKey)->rate_cents ?? $defaultRates[$roleKey] }}"
                                                    min="0"
                                                    max="1000"
                                                    step="1"
                                                />
                                            </div>
                                            @error("hourly_rates.{$roleKey}")
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row justify-end items-center gap-4 pt-4">
                <button 
                    type="button"
                    onclick="document.getElementById('reset-form').submit();"
                    class="w-full sm:w-auto flex items-center justify-center rounded-lg h-12 px-6 text-sm font-bold bg-background-light dark:bg-card-dark border border-border-light dark:border-border-dark text-text-light dark:text-text-dark hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                >
                    Reset to recommended defaults
                </button>
                <button 
                    type="submit"
                    class="w-full sm:w-auto flex items-center justify-center rounded-lg h-12 px-6 text-sm font-bold bg-primary text-white hover:bg-primary/90 transition-colors"
                >
                    Save changes
                </button>
            </div>
        </form>

        <!-- Hidden form for reset functionality -->
        <form id="reset-form" action="{{ route('settings.reset') }}" method="POST" class="hidden">
            @csrf
        </form>
    </div>
</x-layouts.main>