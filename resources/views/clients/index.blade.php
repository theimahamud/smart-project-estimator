<x-layouts.main title="Clients - SmartEstimate AI">
    <!-- Page Heading -->
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-wrap justify-between gap-3 mb-4">
            <p class="text-gray-900 dark:text-white text-3xl font-black leading-tight tracking-tight min-w-72">Clients</p>
            <div class="flex gap-2">
                <a href="{{ route('clients.create') }}" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary dark:focus:ring-offset-background-dark">
                    <span class="material-symbols-outlined text-lg">person_add</span>
                    Add Client
                </a>
            </div>
        </div>

        @if($clients->isEmpty())
            <!-- Empty State -->
            <div class="bg-white dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-800 p-12 text-center">
                <div class="mx-auto w-16 h-16 text-gray-400 mb-4">
                    <span class="material-symbols-outlined text-6xl">groups</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No clients yet</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6 max-w-md mx-auto">Start organizing your projects by adding clients. Track their information and manage all their projects in one place.</p>
                <a href="{{ route('clients.create') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary/90">
                    <span class="material-symbols-outlined text-lg">person_add</span>
                    Add Your First Client
                </a>
            </div>
        @else
            <!-- Clients Grid -->
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                @foreach($clients as $client)
                    <div class="bg-white dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-800 p-6 hover:shadow-lg transition-shadow">
                        <!-- Client Header -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white truncate">
                                    {{ $client->name }}
                                </h3>
                                @if($client->company)
                                    <p class="text-sm text-gray-600 dark:text-gray-400 truncate">
                                        {{ $client->company }}
                                    </p>
                                @endif
                            </div>
                            <div class="ml-2 flex-shrink-0">
                                <div class="relative">
                                    <button type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" onclick="toggleDropdown('client-{{ $client->id }}')">
                                        <span class="material-symbols-outlined text-xl">more_vert</span>
                                    </button>
                                    <div id="client-{{ $client->id }}" class="hidden absolute right-0 z-10 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5">
                                        <div class="py-1">
                                            <a href="{{ route('clients.show', $client) }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                <span class="material-symbols-outlined text-lg">visibility</span>
                                                View Details
                                            </a>
                                            <a href="{{ route('clients.edit', $client) }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                <span class="material-symbols-outlined text-lg">edit</span>
                                                Edit
                                            </a>
                                            @if($client->projects_count === 0)
                                                <form method="POST" action="{{ route('clients.destroy', $client) }}" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Are you sure you want to delete this client?')" class="flex items-center gap-2 px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700 w-full text-left">
                                                        <span class="material-symbols-outlined text-lg">delete</span>
                                                        Delete
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Client Info -->
                        <div class="space-y-2 mb-4">
                            @if($client->email)
                                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                    <span class="material-symbols-outlined text-lg">mail</span>
                                    <a href="mailto:{{ $client->email }}" class="hover:text-primary truncate">
                                        {{ $client->email }}
                                    </a>
                                </div>
                            @endif
                            @if($client->phone)
                                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                    <span class="material-symbols-outlined text-lg">phone</span>
                                    <a href="tel:{{ $client->phone }}" class="hover:text-primary">
                                        {{ $client->phone }}
                                    </a>
                                </div>
                            @endif
                        </div>

                        <!-- Stats -->
                        <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                            <div class="text-center">
                                <div class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $client->projects_count }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Projects</div>
                            </div>
                            <div class="text-center">
                                <div class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $client->active_projects_count }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Active</div>
                            </div>
                            <a href="{{ route('clients.show', $client) }}" class="text-primary hover:text-primary/80 font-medium text-sm">
                                View →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($clients->hasPages())
                <div class="mt-6">
                    {{ $clients->links() }}
                </div>
            @endif
        @endif
    </div>

    <script>
        function toggleDropdown(id) {
            const dropdown = document.getElementById(id);
            // Hide all other dropdowns
            document.querySelectorAll('[id^="client-"]').forEach(el => {
                if (el.id !== id) el.classList.add('hidden');
            });
            // Toggle current dropdown
            dropdown.classList.toggle('hidden');
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('button') && !event.target.closest('[id^="client-"]')) {
                document.querySelectorAll('[id^="client-"]').forEach(el => {
                    el.classList.add('hidden');
                });
            }
        });
    </script>
</x-layouts.main>