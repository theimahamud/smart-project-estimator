<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Get Started Free - SmartEstimate AI</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&family=Noto+Sans:wght@400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#53d22d",
                        "background-light": "#f6f8f6",
                        "background-dark": "#152012",
                        "surface-dark": "#1f251d",
                        "surface-darker": "#1a2118",
                        "border-dark": "#2d372a",
                    },
                    fontFamily: {
                        "display": ["Manrope", "Noto Sans", "sans-serif"]
                    },
                    borderRadius: {"DEFAULT": "1rem", "lg": "2rem", "xl": "3rem", "full": "9999px"},
                },
            },
        }
    </script>
    <style>
        /* Custom scrollbar for webkit */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #152012;
        }
        ::-webkit-scrollbar-thumb {
            background: #2d372a;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #53d22d;
        }
    </style>
</head>
<body class="bg-background-dark font-display text-white overflow-x-hidden antialiased selection:bg-primary selection:text-black">

<div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8 relative overflow-hidden">
    <!-- Background Elements -->
    <div class="absolute top-0 right-1/4 w-72 h-72 bg-primary/10 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute bottom-0 left-1/4 w-96 h-96 bg-blue-500/5 rounded-full blur-[120px] pointer-events-none"></div>

    <!-- Header -->
    <div class="sm:mx-auto sm:w-full sm:max-w-md mb-8">
        <div class="flex justify-center">
            <a href="{{ route('home') }}" class="flex items-center gap-3 text-white group">
                <div class="size-10 text-primary transition-transform group-hover:rotate-90">
                    <svg class="h-full w-full" fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                        <path clip-rule="evenodd" d="M47.2426 24L24 47.2426L0.757355 24L24 0.757355L47.2426 24ZM12.2426 21H35.7574L24 9.24264L12.2426 21Z" fill="currentColor" fill-rule="evenodd"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-bold leading-tight tracking-tight">SmartEstimate AI</h2>
            </a>
        </div>
        <h1 class="mt-6 text-center text-3xl font-extrabold text-white">
            Get Started Free
        </h1>
        <p class="mt-2 text-center text-sm text-slate-400">
            Create your account and start estimating projects in seconds
        </p>
        <div class="mt-3 flex items-center justify-center">
            <div class="inline-flex items-center rounded-full border border-primary/20 bg-primary/10 px-3 py-1 text-xs font-medium text-primary">
                <span class="mr-1">✨</span> 100% Free - No Credit Card Required
            </div>
        </div>
    </div>

    <!-- Register Card -->
    <div class="sm:mx-auto sm:w-full sm:max-w-md relative z-10">
        <div class="bg-surface-dark border border-border-dark rounded-2xl shadow-2xl py-8 px-6 sm:px-8">
            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <!-- Full Name Input -->
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-300 mb-2">
                        Full Name
                    </label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        class="block w-full h-12 px-4 rounded-xl border border-border-dark bg-surface-darker text-white placeholder-slate-500 focus:border-primary focus:ring-primary focus:ring-1 transition-colors sm:text-sm"
                        placeholder="Enter your full name"
                    />
                    @error('name')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-300 mb-2">
                        Email address
                    </label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        required
                        class="block w-full h-12 px-4 rounded-xl border border-border-dark bg-surface-darker text-white placeholder-slate-500 focus:border-primary focus:ring-primary focus:ring-1 transition-colors sm:text-sm"
                        placeholder="your@email.com"
                    />
                    @error('email')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Input -->
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-300 mb-2">
                        Password
                    </label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        required
                        class="block w-full h-12 px-4 rounded-xl border border-border-dark bg-surface-darker text-white placeholder-slate-500 focus:border-primary focus:ring-primary focus:ring-1 transition-colors sm:text-sm"
                        placeholder="Create a secure password"
                    />
                    @error('password')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password Input -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-300 mb-2">
                        Confirm Password
                    </label>
                    <input
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        required
                        class="block w-full h-12 px-4 rounded-xl border border-border-dark bg-surface-darker text-white placeholder-slate-500 focus:border-primary focus:ring-primary focus:ring-1 transition-colors sm:text-sm"
                        placeholder="Confirm your password"
                    />
                    @error('password_confirmation')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Create Account Button -->
                <div>
                    <button type="submit" class="group relative w-full flex items-center justify-center gap-2
                               h-12 px-6 border border-transparent text-sm font-bold
                               rounded-xl text-black bg-primary hover:bg-[#65e040]
                               focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary
                               transition-all duration-200
                               transform hover:scale-[1.02] active:scale-[0.98]
                               shadow-[0_0_20px_-5px_rgba(83,210,45,0.4)]">
                        <span class="material-symbols-outlined text-lg">
                            person_add
                        </span>
                        <span>
                            Create Free Account
                        </span>
                    </button>
                </div>


                <!-- Terms Notice -->
                <div class="text-center">
                    <p class="text-xs text-slate-400">
                        By creating an account, you agree to our
                        <a href="#" class="text-primary hover:text-[#65e040] font-medium">Terms of Service</a> and
                        <a href="#" class="text-primary hover:text-[#65e040] font-medium">Privacy Policy</a>
                    </p>
                </div>
            </form>

            <!-- Sign In Link -->
            <div class="mt-8">
                <div class="space-y-4">
                    <a href="{{ route('login') }}" class="group relative w-full flex justify-center h-12 px-4 border-2 border-slate-600/40 text-sm font-bold rounded-xl text-slate-300 bg-slate-800/30 hover:bg-slate-700/50 hover:border-slate-500/60 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary focus:ring-offset-surface-dark transition-all duration-200 items-center gap-2 overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-r from-slate-700/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-200"></div>
                        <span class="material-symbols-outlined text-lg relative z-10">login</span>
                        <span class="relative z-10">Sign in to your account</span>
                    </a>

                    <div class="text-center">
                        <p class="text-xs text-slate-500">
                            Secure sign-in with your existing credentials
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Back to Home Link -->
    <div class="sm:mx-auto sm:w-full sm:max-w-md mt-8">
        <div class="text-center">
            <a href="{{ route('home') }}" class="group inline-flex items-center justify-center gap-2 text-sm text-slate-400 hover:text-primary font-medium transition-all duration-200 px-4 py-2 rounded-lg hover:bg-primary/5">
                <span class="material-symbols-outlined text-sm group-hover:-translate-x-0.5 transition-transform duration-200">arrow_back</span>
                <span>Back to home</span>
            </a>
        </div>
    </div>
</div>

</body>
</html>
