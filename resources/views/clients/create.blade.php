<x-layouts.main title="{{ isset($client) ? 'Edit Client' : 'Add Client' }} - SmartEstimate AI">
    <!-- Page Heading -->
    <div class="max-w-2xl mx-auto">
        <div class="flex flex-wrap justify-between gap-3 mb-4">
            <p class="text-gray-900 dark:text-white text-3xl font-black leading-tight tracking-tight min-w-72">
                {{ isset($client) ? 'Edit Client' : 'Add Client' }}
            </p>
        </div>
        
        <!-- Form Card -->
        <div class="bg-white dark:bg-gray-900/50 p-6 md:p-8 rounded-lg border border-gray-200 dark:border-gray-800">
            <form method="POST" action="{{ isset($client) ? route('clients.update', $client) : route('clients.store') }}" class="space-y-6">
                @csrf
                @if(isset($client))
                    @method('PUT')
                @endif
                
                <!-- Client Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5" for="name">Client Name</label>
                    <input 
                        class="block w-full rounded-md border-gray-300 dark:border-gray-700 shadow-sm focus:border-primary focus:ring-primary sm:text-sm bg-background-light dark:bg-gray-800 text-gray-900 dark:text-white" 
                        id="name" 
                        name="name" 
                        placeholder="e.g., John Smith or ABC Corporation" 
                        type="text"
                        value="{{ old('name', $client->name ?? '') }}"
                        required
                    />
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Company -->
                <div>
                    <div class="flex justify-between">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5" for="company">Company</label>
                        <span class="text-sm text-gray-500 dark:text-gray-400">Optional</span>
                    </div>
                    <input 
                        class="block w-full rounded-md border-gray-300 dark:border-gray-700 shadow-sm focus:border-primary focus:ring-primary sm:text-sm bg-background-light dark:bg-gray-800 text-gray-900 dark:text-white" 
                        id="company" 
                        name="company" 
                        placeholder="e.g., ABC Corporation" 
                        type="text"
                        value="{{ old('company', $client->company ?? '') }}"
                    />
                    @error('company')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Email -->
                    <div>
                        <div class="flex justify-between">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5" for="email">Email</label>
                            <span class="text-sm text-gray-500 dark:text-gray-400">Optional</span>
                        </div>
                        <input 
                            class="block w-full rounded-md border-gray-300 dark:border-gray-700 shadow-sm focus:border-primary focus:ring-primary sm:text-sm bg-background-light dark:bg-gray-800 text-gray-900 dark:text-white" 
                            id="email" 
                            name="email" 
                            placeholder="client@example.com" 
                            type="email"
                            value="{{ old('email', $client->email ?? '') }}"
                        />
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <div class="flex justify-between">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5" for="phone">Phone</label>
                            <span class="text-sm text-gray-500 dark:text-gray-400">Optional</span>
                        </div>
                        <input 
                            class="block w-full rounded-md border-gray-300 dark:border-gray-700 shadow-sm focus:border-primary focus:ring-primary sm:text-sm bg-background-light dark:bg-gray-800 text-gray-900 dark:text-white" 
                            id="phone" 
                            name="phone" 
                            placeholder="+1 (555) 123-4567" 
                            type="tel"
                            value="{{ old('phone', $client->phone ?? '') }}"
                        />
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Address -->
                <div>
                    <div class="flex justify-between">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5" for="address">Address</label>
                        <span class="text-sm text-gray-500 dark:text-gray-400">Optional</span>
                    </div>
                    <textarea 
                        class="block w-full rounded-md border-gray-300 dark:border-gray-700 shadow-sm focus:border-primary focus:ring-primary sm:text-sm bg-background-light dark:bg-gray-800 text-gray-900 dark:text-white" 
                        id="address" 
                        name="address" 
                        placeholder="Street address, city, state, zip code"
                        rows="3"
                    >{{ old('address', $client->address ?? '') }}</textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div>
                    <div class="flex justify-between">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5" for="notes">Notes</label>
                        <span class="text-sm text-gray-500 dark:text-gray-400">Optional</span>
                    </div>
                    <textarea 
                        class="block w-full rounded-md border-gray-300 dark:border-gray-700 shadow-sm focus:border-primary focus:ring-primary sm:text-sm bg-background-light dark:bg-gray-800 text-gray-900 dark:text-white" 
                        id="notes" 
                        name="notes" 
                        placeholder="Any additional information about this client..."
                        rows="3"
                    >{{ old('notes', $client->notes ?? '') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-800">
                    <a href="{{ route('clients.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary/50 dark:focus:ring-offset-background-dark">
                        Cancel
                    </a>
                    <button type="submit" class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary dark:focus:ring-offset-background-dark">
                        <span class="material-symbols-outlined text-xl">{{ isset($client) ? 'save' : 'person_add' }}</span>
                        {{ isset($client) ? 'Update Client' : 'Add Client' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.main>