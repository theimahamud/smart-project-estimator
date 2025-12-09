<x-layouts.main title="Projects Dashboard">
    <x-slot name="workspace">My Agency Workspace</x-slot>
    
    <!-- Page Heading -->
    <div class="mb-8">
        <p class="text-gray-900 dark:text-white text-4xl font-black leading-tight tracking-[-0.033em]">Projects</p>
    </div>
    
    @if($estimates->count() > 0)
        <!-- Table -->
        <div class="px-0 py-3 @container">
            <div class="flex overflow-hidden rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-black/20">
                <table class="flex-1 w-full text-left">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-white/5">
                            <th class="px-6 py-4 text-left text-gray-800 dark:text-gray-300 w-1/4 text-sm font-medium leading-normal">Project name</th>
                            <th class="px-6 py-4 text-left text-gray-800 dark:text-gray-300 text-sm font-medium leading-normal">Domain</th>
                            <th class="px-6 py-4 text-left text-gray-800 dark:text-gray-300 text-sm font-medium leading-normal">Tech stack</th>
                            <th class="px-6 py-4 text-left text-gray-800 dark:text-gray-300 text-sm font-medium leading-normal">Country</th>
                            <th class="px-6 py-4 text-left text-gray-800 dark:text-gray-300 text-sm font-medium leading-normal">Estimated cost</th>
                            <th class="px-6 py-4 text-left text-gray-800 dark:text-gray-300 text-sm font-medium leading-normal">Duration</th>
                            <th class="px-6 py-4 text-left text-gray-800 dark:text-gray-300 w-28 text-sm font-medium leading-normal">Confidence</th>
                            <th class="px-6 py-4 text-left text-gray-500 w-28 text-sm font-medium leading-normal"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                        @foreach($estimates as $estimate)
                            <tr>
                                <td class="h-[72px] px-6 py-2 text-gray-900 dark:text-white text-sm font-normal leading-normal">
                                    {{ $estimate->project->name }}
                                </td>
                                <td class="h-[72px] px-6 py-2 text-gray-500 dark:text-gray-400 text-sm font-normal leading-normal">
                                    {{ $estimate->project->domain_type ? ucfirst(str_replace('_', ' ', $estimate->project->domain_type)) : 'N/A' }}
                                </td>
                                <td class="h-[72px] px-6 py-2 text-gray-500 dark:text-gray-400 text-sm font-normal leading-normal">
                                    Laravel, PHP
                                </td>
                                <td class="h-[72px] px-6 py-2 text-gray-500 dark:text-gray-400 text-sm font-normal leading-normal">
                                    {{ $estimate->project->country ?? 'USA' }}
                                </td>
                                <td class="h-[72px] px-6 py-2 text-gray-500 dark:text-gray-400 text-sm font-normal leading-normal">
                                    ${{ number_format($estimate->total_cost, 0) }}
                                </td>
                                <td class="h-[72px] px-6 py-2 text-gray-500 dark:text-gray-400 text-sm font-normal leading-normal">
                                    {{ ceil($estimate->total_hours / 40) }} weeks
                                </td>
                                <td class="h-[72px] px-6 py-2 text-sm font-normal leading-normal">
                                    @php
                                        $confidence = $estimate->confidence_level ?? 'medium';
                                        $badgeClass = match($confidence) {
                                            'high' => 'bg-teal-100 dark:bg-teal-900/50 text-teal-700 dark:text-teal-300',
                                            'medium' => 'bg-amber-100 dark:bg-amber-900/50 text-amber-700 dark:text-amber-300',
                                            'low' => 'bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-300',
                                            default => 'bg-gray-100 dark:bg-gray-900/50 text-gray-700 dark:text-gray-300'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center rounded-full {{ $badgeClass }} px-3 py-1 text-xs font-medium">
                                        {{ ucfirst($confidence) }}
                                    </span>
                                </td>
                                <td class="h-[72px] px-6 py-2 text-primary dark:text-primary/90 text-sm font-bold leading-normal tracking-[0.015em]">
                                    <a href="{{ route('estimates.show', $estimate) }}">View details</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Pagination -->
        @if($estimates->hasPages())
            <div class="mt-6">
                {{ $estimates->links() }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="flex flex-col items-center justify-center border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-xl p-12 mt-8 text-center bg-white dark:bg-black/20">
            <div class="flex flex-col items-center gap-6">
                <div class="bg-center bg-no-repeat aspect-video bg-contain w-full max-w-[280px]" style='background-image: url("https://img.freepik.com/free-vector/project-management-planning-workflow-organization-concept-flat-vector-illustration-with-characters_128772-763.jpg?w=1060&t=st=1716301327~exp=1716301927~hmac=63c8a5a6797a7a508f7eb7a5b3a4e9b97779de0989b537c3a2862c76a599cc63");'></div>
                <div class="flex max-w-[480px] flex-col items-center gap-2">
                    <p class="text-gray-900 dark:text-white text-lg font-bold leading-tight tracking-[-0.015em] max-w-[480px] text-center">No estimates yet.</p>
                    <p class="text-gray-600 dark:text-gray-400 text-sm font-normal leading-normal max-w-[480px] text-center">Get started by creating your first project estimate.</p>
                </div>
                <a href="{{ route('estimates.create') }}" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em]">
                    <span class="truncate">Create your first estimate</span>
                </a>
            </div>
        </div>
    @endif
</x-layouts.main>