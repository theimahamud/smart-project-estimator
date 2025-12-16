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
            
            <form method="POST" action="{{ route('estimates.store') }}" enctype="multipart/form-data" class="space-y-6">
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
                    
                    <!-- Input Method Selection -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">How would you like to provide requirements?</label>
                        <div class="flex rounded-lg bg-gray-200/50 dark:bg-gray-800 p-1 w-full sm:w-auto">
                            <button 
                                type="button"
                                class="px-4 py-2 text-sm font-medium rounded-md flex-1 sm:flex-none input-method-btn bg-white dark:bg-gray-900 text-primary shadow-sm"
                                data-method="text"
                                id="text-method-btn"
                            >
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Text Input
                                </span>
                            </button>
                            <button 
                                type="button"
                                class="px-4 py-2 text-sm font-medium rounded-md flex-1 sm:flex-none input-method-btn text-gray-500 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-700"
                                data-method="pdf"
                                id="pdf-method-btn"
                            >
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                    PDF Upload
                                </span>
                            </button>
                        </div>
                        <input type="hidden" name="input_method" id="input_method" value="text">
                    </div>
                    
                    <!-- PDF Upload Section (Hidden by default) -->
                    <div id="pdf-upload-section" class="mb-6 hidden">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Upload Requirements Document</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-md hover:border-primary dark:hover:border-primary transition-colors">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                    <label for="requirements_pdf" class="relative cursor-pointer bg-white dark:bg-gray-900 rounded-md font-medium text-primary hover:text-primary/80 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary">
                                        <span>Upload a file</span>
                                        <input id="requirements_pdf" name="requirements_pdf" type="file" accept=".pdf" class="sr-only">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">PDF up to 10MB</p>
                            </div>
                        </div>
                        <div id="pdf-file-info" class="hidden mt-2 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-md">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                                </svg>
                                <span id="pdf-file-name" class="text-sm text-blue-700 dark:text-blue-300 font-medium"></span>
                                <button type="button" id="remove-pdf" class="ml-auto text-red-600 hover:text-red-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        @error('requirements_pdf')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Text Input Section (Visible by default) -->
                    <div id="text-input-section">
                        <!-- Functional Requirements -->
                        <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5" for="functional_requirements">Functional Requirements</label>
                        <textarea 
                            class="block w-full rounded-md border-gray-300 dark:border-gray-700 shadow-sm focus:border-primary focus:ring-primary sm:text-sm bg-background-light dark:bg-gray-800 text-gray-900 dark:text-white min-h-32" 
                            id="functional_requirements" 
                            name="functional_requirements" 
                            placeholder="Describe what the system should do - features, user stories, business logic..."
                            required
                        >{{ old('functional_requirements', $quickDescription ?? '') }}</textarea>
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
                </div>
                
                <!-- Team Configuration Section -->
                <div class="border-t border-gray-200 dark:border-gray-800 pt-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Team Configuration</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Available Team Size -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5" for="available_team_size">Available Team Size</label>
                            <input 
                                class="block w-full rounded-md border-gray-300 dark:border-gray-700 shadow-sm focus:border-primary focus:ring-primary sm:text-sm bg-background-light dark:bg-gray-800 text-gray-900 dark:text-white" 
                                id="available_team_size" 
                                name="available_team_size" 
                                type="number"
                                min="1"
                                max="50"
                                placeholder="e.g., 5"
                                value="{{ old('available_team_size', 3) }}"
                                required
                            />
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Total number of developers available for the project</p>
                            @error('available_team_size')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Work Hours Per Day -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5" for="work_hours_per_day">Work Hours Per Day</label>
                            <select 
                                class="block w-full rounded-md border-gray-300 dark:border-gray-700 shadow-sm focus:border-primary focus:ring-primary sm:text-sm bg-background-light dark:bg-gray-800 text-gray-900 dark:text-white" 
                                id="work_hours_per_day" 
                                name="work_hours_per_day"
                                required
                            >
                                <option value="4" {{ old('work_hours_per_day') == '4' ? 'selected' : '' }}>4 hours (Part-time)</option>
                                <option value="6" {{ old('work_hours_per_day') == '6' ? 'selected' : '' }}>6 hours (Reduced)</option>
                                <option value="8" {{ old('work_hours_per_day', '8') == '8' ? 'selected' : '' }}>8 hours (Standard)</option>
                                <option value="10" {{ old('work_hours_per_day') == '10' ? 'selected' : '' }}>10 hours (Extended)</option>
                            </select>
                            @error('work_hours_per_day')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Team Seniority -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5" for="team_seniority">Overall Team Seniority</label>
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
                        
                        <!-- Budget Range -->
                        <div>
                            <div class="flex justify-between">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5" for="target_budget">Target Budget (USD)</label>
                                <span class="text-sm text-gray-500 dark:text-gray-400">Optional</span>
                            </div>
                            <input 
                                class="block w-full rounded-md border-gray-300 dark:border-gray-700 shadow-sm focus:border-primary focus:ring-primary sm:text-sm bg-background-light dark:bg-gray-800 text-gray-900 dark:text-white" 
                                id="target_budget" 
                                name="target_budget" 
                                type="number"
                                min="1000"
                                max="10000000"
                                step="1000"
                                placeholder="e.g., 50000"
                                value="{{ old('target_budget') }}"
                            />
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">If you have a budget constraint, we'll optimize the estimate accordingly</p>
                            @error('target_budget')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Custom Hourly Rates (Optional Advanced Section) -->
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 bg-gray-50 dark:bg-gray-800/50">
                        <div class="flex items-center justify-between mb-3">
                            <h5 class="text-sm font-semibold text-gray-900 dark:text-white">Custom Hourly Rates</h5>
                            <button type="button" id="toggle-custom-rates" class="text-sm text-primary hover:text-primary/80 font-medium">
                                <span class="flex items-center gap-1">
                                    <span id="toggle-text">Show Advanced</span>
                                    <svg id="toggle-icon" class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </span>
                            </button>
                        </div>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-4">Override default rates for specific roles (leave empty to use defaults)</p>
                        
                        <div id="custom-rates-section" class="hidden">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1" for="rate_backend_developer">Backend Developer ($/hr)</label>
                                    <input 
                                        class="block w-full text-sm rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-primary focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-white" 
                                        id="rate_backend_developer" 
                                        name="custom_rates[backend_developer]" 
                                        type="number"
                                        min="10"
                                        max="500"
                                        step="5"
                                        placeholder="Default: $75"
                                        value="{{ old('custom_rates.backend_developer') }}"
                                    />
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1" for="rate_frontend_developer">Frontend Developer ($/hr)</label>
                                    <input 
                                        class="block w-full text-sm rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-primary focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-white" 
                                        id="rate_frontend_developer" 
                                        name="custom_rates[frontend_developer]" 
                                        type="number"
                                        min="10"
                                        max="500"
                                        step="5"
                                        placeholder="Default: $70"
                                        value="{{ old('custom_rates.frontend_developer') }}"
                                    />
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1" for="rate_fullstack_developer">Fullstack Developer ($/hr)</label>
                                    <input 
                                        class="block w-full text-sm rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-primary focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-white" 
                                        id="rate_fullstack_developer" 
                                        name="custom_rates[fullstack_developer]" 
                                        type="number"
                                        min="10"
                                        max="500"
                                        step="5"
                                        placeholder="Default: $80"
                                        value="{{ old('custom_rates.fullstack_developer') }}"
                                    />
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1" for="rate_ui_ux_designer">UI/UX Designer ($/hr)</label>
                                    <input 
                                        class="block w-full text-sm rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-primary focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-white" 
                                        id="rate_ui_ux_designer" 
                                        name="custom_rates[ui_ux_designer]" 
                                        type="number"
                                        min="10"
                                        max="500"
                                        step="5"
                                        placeholder="Default: $65"
                                        value="{{ old('custom_rates.ui_ux_designer') }}"
                                    />
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1" for="rate_project_manager">Project Manager ($/hr)</label>
                                    <input 
                                        class="block w-full text-sm rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-primary focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-white" 
                                        id="rate_project_manager" 
                                        name="custom_rates[project_manager]" 
                                        type="number"
                                        min="10"
                                        max="500"
                                        step="5"
                                        placeholder="Default: $80"
                                        value="{{ old('custom_rates.project_manager') }}"
                                    />
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1" for="rate_qa_engineer">QA Engineer ($/hr)</label>
                                    <input 
                                        class="block w-full text-sm rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-primary focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-white" 
                                        id="rate_qa_engineer" 
                                        name="custom_rates[qa_engineer]" 
                                        type="number"
                                        min="10"
                                        max="500"
                                        step="5"
                                        placeholder="Default: $55"
                                        value="{{ old('custom_rates.qa_engineer') }}"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Project Context Section -->
                <div class="border-t border-gray-200 dark:border-gray-800 pt-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Project Context</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
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
        document.addEventListener('DOMContentLoaded', function() {
            // Requirements Quality Button Toggle
            const qualityButtons = document.querySelectorAll('.requirements-quality-btn');
            const hiddenInput = document.getElementById('requirements_quality');
            
            qualityButtons.forEach(button => {
                button.addEventListener('click', function() {
                    qualityButtons.forEach(btn => {
                        btn.classList.remove('bg-white', 'dark:bg-gray-900', 'text-primary', 'shadow-sm');
                        btn.classList.add('text-gray-500', 'dark:text-gray-400', 'hover:bg-white', 'dark:hover:bg-gray-700');
                    });
                    
                    this.classList.remove('text-gray-500', 'dark:text-gray-400', 'hover:bg-white', 'dark:hover:bg-gray-700');
                    this.classList.add('bg-white', 'dark:bg-gray-900', 'text-primary', 'shadow-sm');
                    
                    hiddenInput.value = this.dataset.value;
                });
            });
            
            // Input Method Toggle
            const inputMethodButtons = document.querySelectorAll('.input-method-btn');
            const inputMethodHidden = document.getElementById('input_method');
            const pdfSection = document.getElementById('pdf-upload-section');
            const textSection = document.getElementById('text-input-section');
            
            inputMethodButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const method = this.dataset.method;
                    
                    // Update button styles
                    inputMethodButtons.forEach(btn => {
                        btn.classList.remove('bg-white', 'dark:bg-gray-900', 'text-primary', 'shadow-sm');
                        btn.classList.add('text-gray-500', 'dark:text-gray-400', 'hover:bg-white', 'dark:hover:bg-gray-700');
                    });
                    
                    this.classList.remove('text-gray-500', 'dark:text-gray-400', 'hover:bg-white', 'dark:hover:bg-gray-700');
                    this.classList.add('bg-white', 'dark:bg-gray-900', 'text-primary', 'shadow-sm');
                    
                    // Update hidden input
                    inputMethodHidden.value = method;
                    
                    // Toggle sections
                    if (method === 'pdf') {
                        pdfSection.classList.remove('hidden');
                        textSection.classList.add('hidden');
                        // Make PDF required, text fields optional
                        document.getElementById('functional_requirements').required = false;
                    } else {
                        pdfSection.classList.add('hidden');
                        textSection.classList.remove('hidden');
                        // Make text fields required, PDF optional
                        document.getElementById('functional_requirements').required = true;
                        document.getElementById('requirements_pdf').value = '';
                        document.getElementById('pdf-file-info').classList.add('hidden');
                    }
                });
            });
            
            // PDF Upload Handler
            const pdfInput = document.getElementById('requirements_pdf');
            const pdfFileInfo = document.getElementById('pdf-file-info');
            const pdfFileName = document.getElementById('pdf-file-name');
            const removePdfBtn = document.getElementById('remove-pdf');
            
            pdfInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    if (file.type === 'application/pdf') {
                        pdfFileName.textContent = file.name;
                        pdfFileInfo.classList.remove('hidden');
                    } else {
                        alert('Please select a PDF file.');
                        this.value = '';
                    }
                }
            });
            
            removePdfBtn.addEventListener('click', function() {
                pdfInput.value = '';
                pdfFileInfo.classList.add('hidden');
            });
            
            // Drag and Drop for PDF
            const pdfUploadArea = pdfSection.querySelector('.border-dashed');
            
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                pdfUploadArea.addEventListener(eventName, preventDefaults, false);
            });
            
            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }
            
            ['dragenter', 'dragover'].forEach(eventName => {
                pdfUploadArea.addEventListener(eventName, highlight, false);
            });
            
            ['dragleave', 'drop'].forEach(eventName => {
                pdfUploadArea.addEventListener(eventName, unhighlight, false);
            });
            
            function highlight(e) {
                pdfUploadArea.classList.add('border-primary', 'bg-primary/5');
            }
            
            function unhighlight(e) {
                pdfUploadArea.classList.remove('border-primary', 'bg-primary/5');
            }
            
            pdfUploadArea.addEventListener('drop', handleDrop, false);
            
            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                
                if (files.length > 0) {
                    const file = files[0];
                    if (file.type === 'application/pdf') {
                        pdfInput.files = files;
                        pdfFileName.textContent = file.name;
                        pdfFileInfo.classList.remove('hidden');
                    } else {
                        alert('Please drop a PDF file.');
                    }
                }
            }
            
            // Custom Rates Toggle
            const toggleBtn = document.getElementById('toggle-custom-rates');
            const customRatesSection = document.getElementById('custom-rates-section');
            const toggleText = document.getElementById('toggle-text');
            const toggleIcon = document.getElementById('toggle-icon');
            
            toggleBtn.addEventListener('click', function() {
                if (customRatesSection.classList.contains('hidden')) {
                    customRatesSection.classList.remove('hidden');
                    toggleText.textContent = 'Hide Advanced';
                    toggleIcon.classList.add('rotate-180');
                } else {
                    customRatesSection.classList.add('hidden');
                    toggleText.textContent = 'Show Advanced';
                    toggleIcon.classList.remove('rotate-180');
                }
            });
        });
    </script>
</x-layouts.main>