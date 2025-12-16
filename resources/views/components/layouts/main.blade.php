<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>{{ $title ?? 'SmartEstimate AI' }}</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#327be2",
                        "background-light": "#f6f7f8",
                        "background-dark": "#111821",
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .material-symbols-outlined.fill {
            font-variation-settings: 'FILL' 1;
        }
    </style>
</head>
<body class="font-display bg-background-light dark:bg-background-dark">
    <div class="relative flex h-auto min-h-screen w-full flex-col group/design-root overflow-x-hidden">
        <div class="flex min-h-screen">
            <!-- Sidebar -->
            <aside class="w-64 flex-shrink-0 bg-white dark:bg-black/20 p-4 border-r border-gray-200 dark:border-gray-800 flex flex-col justify-between">
                <div class="flex flex-col gap-8">
                    <!-- Logo -->
                    <div class="flex items-center gap-3 px-3">
                        <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-8" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDQdQYuoVthAL1UMl3rlV850U_EcZn2lRNQA0fKWMXRIUq6o8KH5QkiwBHjMx9B42GPNNnxTcj-lVPyTMBOHm3a_uih3fnA7LLyJ_LV_VQCZ6LGK6Y0_tS43yA9u04KejTCaZGw7mrhSa0tFBWf-JkUGRWPi9gTruMwocKPjCJyx23axucp8JDoz2KVxLYqzSUOEEZUwilxLvKxpV3RPDv4bw4PH8lDD7F8fFy35V99oSK9YXskbYiXrezAj9toQEFS_kHnP2Kfjrk");'></div>
                        <h1 class="text-gray-900 dark:text-white text-lg font-bold leading-normal">SmartEstimate AI</h1>
                    </div>
                    
                    <!-- Navigation -->
                    <nav class="flex flex-col gap-2">
                        <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-primary/10 text-primary dark:bg-primary/20' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/10' }}" href="{{ route('dashboard') }}">
                            <span class="material-symbols-outlined {{ request()->routeIs('dashboard') ? 'fill text-primary' : '' }}">dashboard</span>
                            <p class="text-sm font-medium leading-normal">Dashboard</p>
                        </a>
                        <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('estimates.*') ? 'bg-primary/10 text-primary dark:bg-primary/20' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/10' }}" href="{{ route('estimates.create') }}">
                            <span class="material-symbols-outlined {{ request()->routeIs('estimates.create') ? 'fill text-primary' : '' }}">add_circle</span>
                            <p class="text-sm font-medium leading-normal">New Estimate</p>
                        </a>
                        <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('estimates.index') ? 'bg-primary/10 text-primary dark:bg-primary/20' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/10' }}" href="{{ route('estimates.index') }}">
                            <span class="material-symbols-outlined">history</span>
                            <p class="text-sm font-medium leading-normal">History</p>
                        </a>
                        <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('clients.*') ? 'bg-primary/10 text-primary dark:bg-primary/20' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/10' }}" href="{{ route('clients.index') }}">
                            <span class="material-symbols-outlined {{ request()->routeIs('clients.*') ? 'fill text-primary' : '' }}">groups</span>
                            <p class="text-sm font-medium leading-normal">Clients</p>
                        </a>
                        <a class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/10" href="{{ route('settings.profile.edit') }}">
                            <span class="material-symbols-outlined">settings</span>
                            <p class="text-sm font-medium leading-normal">Settings</p>
                        </a>
                    </nav>
                </div>
            </aside>
            
            <!-- Main Content -->
            <div class="flex-1 flex flex-col">
                <!-- Header -->
                <header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-gray-200 dark:border-gray-800 px-10 py-3 bg-white/50 dark:bg-black/20 backdrop-blur-sm sticky top-0">
                    <div class="flex items-center gap-4 text-gray-900 dark:text-white">
                        <div class="size-5 text-primary">
                            <svg fill="currentColor" viewbox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                                <path d="M24 45.8096C19.6865 45.8096 15.4698 44.5305 11.8832 42.134C8.29667 39.7376 5.50128 36.3314 3.85056 32.3462C2.19985 28.361 1.76794 23.9758 2.60947 19.7452C3.451 15.5145 5.52816 11.6284 8.57829 8.5783C11.6284 5.52817 15.5145 3.45101 19.7452 2.60948C23.9758 1.76795 28.361 2.19986 32.3462 3.85057C36.3314 5.50129 39.7376 8.29668 42.134 11.8833C44.5305 15.4698 45.8096 19.6865 45.8096 24L24 24L24 45.8096Z"></path>
                            </svg>
                        </div>
                        <h2 class="text-lg font-bold leading-tight tracking-[-0.015em]">{{ $workspace ?? 'My Agency Workspace' }}</h2>
                    </div>
                    <div class="flex flex-1 justify-end gap-6 items-center">
                        <a href="{{ route('estimates.create') }}" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em]">
                            <span class="truncate">New Estimate</span>
                        </a>
                        <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuA6TL1__MVsAko3_DOJD6fpuAa2ZryuDi1JLcaBN-kZIh4wnPN4ZnO2lwSiB-xMLXfB-Y19Re7qTyzsmaC-qj_axhEO9qrZIJyoHxF3DUnge_tl6MlwCKKCsJ02KhyOPTov5dDRpYVENviXAlje8AlKhXWlISRGbz65oJJwQZpwNdmYxHc7FhqfnwXijj4p1lrate-gyxb-27HuATkbboVT6l1x9IlOOk18PIQWGcbxcwOYkG2tspjo8lEohJo76UIqlnuPPObLeFI");'></div>
                    </div>
                </header>
                
                <!-- Main Content Area -->
                <main class="flex-1 p-10">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </div>
</body>
</html>