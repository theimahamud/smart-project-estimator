<x-layouts.main title="{{ $client->name }} - SmartEstimate AI">
    <!-- Page Heading -->
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-wrap justify-between gap-3 mb-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('clients.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                    <span class="material-symbols-outlined text-xl">arrow_back</span>
                </a>
                <p class="text-gray-900 dark:text-white text-3xl font-black leading-tight tracking-tight min-w-72">
                    {{ $client->name }}
                </p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('clients.edit', $client) }}" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700">
                    <span class="material-symbols-outlined text-lg">edit</span>
                    Edit
                </a>
                <a href="{{ route('estimates.create', ['client_id' => $client->id]) }}" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary/90">
                    <span class="material-symbols-outlined text-lg">auto_awesome</span>
                    New Estimate
                </a>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <!-- Client Information -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-800 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Contact Information</h3>
                    
                    <div class="space-y-4">
                        @if($client->company)
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Company</label>
                                <p class="text-gray-900 dark:text-white">{{ $client->company }}</p>
                            </div>
                        @endif
                        
                        @if($client->email)
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</label>
                                <p class="text-gray-900 dark:text-white">
                                    <a href="mailto:{{ $client->email }}" class="text-primary hover:text-primary/80">
                                        {{ $client->email }}
                                    </a>
                                </p>
                            </div>
                        @endif
                        
                        @if($client->phone)
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone</label>
                                <p class="text-gray-900 dark:text-white">
                                    <a href="tel:{{ $client->phone }}" class="text-primary hover:text-primary/80">
                                        {{ $client->phone }}
                                    </a>
                                </p>
                            </div>
                        @endif
                        
                        @if($client->address)
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Address</label>
                                <p class="text-gray-900 dark:text-white whitespace-pre-line">{{ $client->address }}</p>
                            </div>
                        @endif
                        
                        @if($client->notes)
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Notes</label>
                                <p class="text-gray-900 dark:text-white whitespace-pre-line">{{ $client->notes }}</p>
                            </div>
                        @endif
                        
                        <div>
                            <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Added</label>
                            <p class="text-gray-900 dark:text-white">{{ $client->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="bg-white dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-800 p-6 mt-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Statistics</h3>
                    
                    <div class="grid grid-cols-2 gap-4 text-center">
                        <div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ $client->projects->count() }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Total Projects</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-primary">
                                {{ $client->projects->sum(fn($p) => $p->estimates->where('status', 'completed')->count()) }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Estimates</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Projects -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-800 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Projects</h3>
                        <a href="{{ route('estimates.create', ['client_id' => $client->id]) }}" class="text-sm text-primary hover:text-primary/80 font-medium">
                            + Add Project
                        </a>
                    </div>

                    @if($client->projects->isEmpty())
                        <!-- Empty State -->
                        <div class="text-center py-12">
                            <div class="mx-auto w-12 h-12 text-gray-400 mb-4">
                                <span class="material-symbols-outlined text-5xl">folder_open</span>
                            </div>
                            <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No projects yet</h4>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">Create your first project estimate for this client.</p>
                            <a href="{{ route('estimates.create', ['client_id' => $client->id]) }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary/90">
                                <span class="material-symbols-outlined text-lg">auto_awesome</span>
                                Create First Project
                            </a>
                        </div>
                    @else
                        <!-- Projects List -->
                        <div class="space-y-4">
                            @foreach($client->projects as $project)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-lg font-medium text-gray-900 dark:text-white truncate">
                                                {{ $project->name }}
                                            </h4>
                                            @if($project->description)
                                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                                    {{ Str::limit($project->description, 150) }}
                                                </p>
                                            @endif
                                            <div class="flex items-center gap-4 mt-2 text-sm text-gray-500 dark:text-gray-400">
                                                <span class="flex items-center gap-1">
                                                    <span class="material-symbols-outlined text-lg">category</span>
                                                    {{ $project->project_type->label() }}
                                                </span>
                                                <span class="flex items-center gap-1">
                                                    <span class="material-symbols-outlined text-lg">domain</span>
                                                    {{ $project->domain_type->label() }}
                                                </span>
                                                <span class="flex items-center gap-1">
                                                    <span class="material-symbols-outlined text-lg">schedule</span>
                                                    {{ $project->created_at->format('M d, Y') }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4 flex-shrink-0">
                                            @if($project->estimates->count() > 0)
                                                @php($latestEstimate = $project->estimates->first())
                                                <a href="{{ route('estimates.show', $latestEstimate) }}" class="inline-flex items-center gap-1 px-3 py-1 text-sm font-medium text-primary bg-primary/10 rounded-lg hover:bg-primary/20">
                                                    <span class="material-symbols-outlined text-lg">visibility</span>
                                                    View Estimate
                                                </a>
                                            @else
                                                <a href="{{ route('estimates.create', ['client_id' => $client->id, 'project_name' => $project->name]) }}" class="inline-flex items-center gap-1 px-3 py-1 text-sm font-medium text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600">
                                                    <span class="material-symbols-outlined text-lg">auto_awesome</span>
                                                    Create Estimate
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.main>