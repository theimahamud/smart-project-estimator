<x-layouts.main title="New Estimate - SmartEstimate AI">
    <!-- Page Heading -->
    <div class="max-w-4xl mx-auto">
        <div class="flex flex-wrap justify-between gap-3 mb-4">
            <p class="text-gray-900 dark:text-white text-3xl font-black leading-tight tracking-tight min-w-72">New Estimate</p>
        </div>
        
        <!-- Form Card -->
        <div class="bg-white dark:bg-gray-900/50 p-6 md:p-8 rounded-lg border border-gray-200 dark:border-gray-800">
            <!-- Headline -->
            <h3 class="text-gray-900 dark:text-white text-xl font-bold leading-tight mb-6">Project Details</h3>
            
            <form method="POST" action="{{ route('estimates.store') }}" class="space-y-6">
                @csrf
                
                <!-- Project Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5" for="name">Project Name</label>
                    <input 
                        class="block w-full rounded-md border-gray-300 dark:border-gray-700 shadow-sm focus:border-primary focus:ring-primary sm:text-sm bg-background-light dark:bg-gray-800 text-gray-900 dark:text-white" 
                        id="name" 
                        name="name" 
                        placeholder="e.g., E-commerce Platform Redesign" 
                        type="text"
                        value="{{ old('name') }}"
                        required
                    />
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Project Description -->
                <div>
                    <div class="flex justify-between">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5" for="description">Project Description</label>
                    </div>
                    <textarea 
                        class="block w-full rounded-md border-gray-300 dark:border-gray-700 shadow-sm focus:border-primary focus:ring-primary sm:text-sm bg-background-light dark:bg-gray-800 text-gray-900 dark:text-white" 
                        id="description" 
                        name="description" 
                        placeholder="Describe the main goal and overview of your project..." 
                        rows="4"
                        required
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-6">
                    <!-- Project Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5" for="project_type">Project Type</label>
                        <select 
                            class="block w-full rounded-md border-gray-300 dark:border-gray-700 shadow-sm focus:border-primary focus:ring-primary sm:text-sm bg-background-light dark:bg-gray-800 text-gray-900 dark:text-white" 
                            id="project_type" 
                            name="project_type"
                            required
                        >
                            <option value="">Select project type</option>
                            @foreach($projectTypes as $type)
                                <option value="{{ $type->value }}" {{ old('project_type') === $type->value ? 'selected' : '' }}>
                                    {{ $type->label() }}
                                </option>
                            @endforeach
                        </select>
                        @error('project_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Domain Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5" for="domain_type">Domain</label>
                        <select 
                            class="block w-full rounded-md border-gray-300 dark:border-gray-700 shadow-sm focus:border-primary focus:ring-primary sm:text-sm bg-background-light dark:bg-gray-800 text-gray-900 dark:text-white" 
                            id="domain_type" 
                            name="domain_type"
                            required
                        >
                            <option value="">Select domain</option>
                            @foreach($domainTypes as $domain)
                                <option value="{{ $domain->value }}" {{ old('domain_type') === $domain->value ? 'selected' : '' }}>
                                    {{ $domain->label() }}
                                </option>
                            @endforeach
                        </select>
                        @error('domain_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Quality Level -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Quality Level</label>
                        <div class="grid sm:grid-cols-3 gap-4">
                            @foreach($qualityLevels as $quality)
                                <div class="relative">
                                    <input 
                                        class="peer absolute top-4 left-4 size-4 text-primary focus:ring-primary border-gray-300 dark:border-gray-600" 
                                        id="quality-{{ strtolower($quality->value) }}" 
                                        name="desired_quality_level" 
                                        type="radio"
                                        value="{{ $quality->value }}"
                                        {{ old('desired_quality_level') === $quality->value ? 'checked' : '' }}
                                        {{ old('desired_quality_level') === null && $quality->value === 'production' ? 'checked' : '' }}
                                    />
                                    <label class="block p-4 rounded-lg border border-gray-300 dark:border-gray-700 peer-checked:border-primary peer-checked:ring-1 peer-checked:ring-primary cursor-pointer" for="quality-{{ strtolower($quality->value) }}">
                                        <p class="font-medium text-gray-900 dark:text-white ml-6">{{ $quality->label() }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 ml-6 mt-1">{{ $quality->description() }}</p>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('desired_quality_level')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Requirements Section -->
                <div class="border-t border-gray-200 dark:border-gray-800 pt-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Requirements</h4>
                    
                    <!-- Functional Requirements -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5" for="functional_requirements">Functional Requirements</label>
                        <textarea 
                            class="block w-full rounded-md border-gray-300 dark:border-gray-700 shadow-sm focus:border-primary focus:ring-primary sm:text-sm bg-background-light dark:bg-gray-800 text-gray-900 dark:text-white min-h-32" 
                            id="functional_requirements" 
                            name="functional_requirements" 
                            placeholder="Describe what the system should do - features, user stories, business logic..."
                            required
                        >{{ old('functional_requirements') }}</textarea>
                        @error('functional_requirements')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Technical Requirements -->
                    <div class="mb-6">
                        <div class="flex justify-between">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5" for="technical_requirements">Technical Requirements</label>
                            <span class="text-sm text-gray-500 dark:text-gray-400">Optional</span>
                        </div>
                        <textarea 
                            class="block w-full rounded-md border-gray-300 dark:border-gray-700 shadow-sm focus:border-primary focus:ring-primary sm:text-sm bg-background-light dark:bg-gray-800 text-gray-900 dark:text-white" 
                            id="technical_requirements" 
                            name="technical_requirements" 
                            placeholder="Tech stack preferences, integration requirements, performance needs..."
                            rows="3"
                        >{{ old('technical_requirements') }}</textarea>
                        @error('technical_requirements')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Requirements Quality -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Requirements Quality</label>
                        <div class="flex rounded-lg bg-gray-200/50 dark:bg-gray-800 p-1 w-full sm:w-auto">
                            @foreach($requirementsQualities as $reqQuality)
                                <button 
                                    type="button"
                                    class="px-4 py-1.5 text-sm font-medium rounded-md flex-1 sm:flex-none requirements-quality-btn {{ old('requirements_quality') === $reqQuality->value || (old('requirements_quality') === null && $reqQuality->value === 'draft') ? 'bg-white dark:bg-gray-900 text-primary shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-700' }}"
                                    data-value="{{ $reqQuality->value }}"
                                >
                                    {{ $reqQuality->label() }}
                                </button>
                            @endforeach
                        </div>
                        <input type="hidden" name="requirements_quality" id="requirements_quality" value="{{ old('requirements_quality', 'draft') }}">
                        @error('requirements_quality')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Context Section -->
                <div class="border-t border-gray-200 dark:border-gray-800 pt-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Project Context</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Team Seniority -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5" for="team_seniority">Team Seniority</label>
                            <select 
                                class="block w-full rounded-md border-gray-300 dark:border-gray-700 shadow-sm focus:border-primary focus:ring-primary sm:text-sm bg-background-light dark:bg-gray-800 text-gray-900 dark:text-white" 
                                id="team_seniority" 
                                name="team_seniority"
                                required
                            >
                                <option value="">Select team seniority</option>
                                @foreach($teamSeniorities as $seniority)
                                    <option value="{{ $seniority->value }}" {{ old('team_seniority') === $seniority->value || (old('team_seniority') === null && $seniority->value === 'mid') ? 'selected' : '' }}>
                                        {{ $seniority->label() }} - {{ $seniority->description() }}
                                    </option>
                                @endforeach
                            </select>
                            @error('team_seniority')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Client Selection -->
                        <div>
                            <div class="flex justify-between">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5" for="client_id">Client</label>
                                <span class="text-sm text-gray-500 dark:text-gray-400">Optional</span>
                            </div>
                            <select 
                                class="block w-full rounded-md border-gray-300 dark:border-gray-700 shadow-sm focus:border-primary focus:ring-primary sm:text-sm bg-background-light dark:bg-gray-800 text-gray-900 dark:text-white" 
                                id="client_id" 
                                name="client_id"
                            >
                                <option value="">Select client</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                        {{ $client->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('client_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Additional Context -->
                        <div class="md:col-span-2">
                            <div class="flex justify-between">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5" for="additional_context">Additional Context</label>
                                <span class="text-sm text-gray-500 dark:text-gray-400">Optional</span>
                            </div>
                            <textarea 
                                class="block w-full rounded-md border-gray-300 dark:border-gray-700 shadow-sm focus:border-primary focus:ring-primary sm:text-sm bg-background-light dark:bg-gray-800 text-gray-900 dark:text-white" 
                                id="additional_context" 
                                name="additional_context" 
                                placeholder="Any additional context, constraints, or special requirements..."
                                rows="3"
                            >{{ old('additional_context') }}</textarea>
                            @error('additional_context')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-800">
                    <a href="{{ route('estimates.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary/50 dark:focus:ring-offset-background-dark">
                        Cancel
                    </a>
                    <button type="submit" class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary dark:focus:ring-offset-background-dark">
                        <span class="material-symbols-outlined text-xl">auto_awesome</span>
                        Generate Estimate
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        // Requirements Quality Button Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.requirements-quality-btn');
            const hiddenInput = document.getElementById('requirements_quality');
            
            buttons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons
                    buttons.forEach(btn => {
                        btn.classList.remove('bg-white', 'dark:bg-gray-900', 'text-primary', 'shadow-sm');
                        btn.classList.add('text-gray-500', 'dark:text-gray-400', 'hover:bg-white', 'dark:hover:bg-gray-700');
                    });
                    
                    // Add active class to clicked button
                    this.classList.remove('text-gray-500', 'dark:text-gray-400', 'hover:bg-white', 'dark:hover:bg-gray-700');
                    this.classList.add('bg-white', 'dark:bg-gray-900', 'text-primary', 'shadow-sm');
                    
                    // Update hidden input
                    hiddenInput.value = this.dataset.value;
                });
            });
        });
    </script>
</x-layouts.main>