<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SmartEstimate AI - Intelligent Project Estimation</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&family=Noto+Sans:wght@400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    <!-- Vite CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
<body class="bg-background-light dark:bg-background-dark font-display text-slate-900 dark:text-white overflow-x-hidden antialiased selection:bg-primary selection:text-black">
<div class="relative flex h-auto min-h-screen w-full flex-col">
    <!-- Navigation -->
    <header class="fixed top-0 left-0 right-0 z-50 flex items-center justify-center px-4 py-4 md:px-10 bg-background-light/80 dark:bg-background-dark/80 backdrop-blur-md border-b border-border-dark/50">
        <div class="flex w-full max-w-[1280px] items-center justify-between">
            <div class="flex items-center gap-3 text-slate-900 dark:text-white cursor-pointer group">
                <div class="size-8 text-primary transition-transform group-hover:rotate-90">
                    <svg class="h-full w-full" fill="none" viewbox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                        <path clip-rule="evenodd" d="M47.2426 24L24 47.2426L0.757355 24L24 0.757355L47.2426 24ZM12.2426 21H35.7574L24 9.24264L12.2426 21Z" fill="currentColor" fill-rule="evenodd"></path>
                    </svg>
                </div>
                <h2 class="text-lg font-bold leading-tight tracking-tight">SmartEstimate AI</h2>
            </div>
            <nav class="hidden md:flex flex-1 justify-center gap-8">
                <a class="text-sm font-medium hover:text-primary transition-colors" href="#features">Features</a>
                <a class="text-sm font-medium hover:text-primary transition-colors" href="#how-it-works">How It Works</a>
                @guest
                    <a class="text-sm font-medium hover:text-primary transition-colors" href="#get-started">Get Started</a>
                @endguest
            </nav>
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="hidden sm:flex h-10 px-4 md:px-6 items-center justify-center rounded-full bg-surface-dark border border-border-dark text-white text-sm font-bold hover:bg-border-dark transition-all">
                        Dashboard
                    </a>
                    <a href="{{ route('estimates.create') }}" class="h-10 px-4 md:px-6 flex items-center justify-center rounded-full bg-primary text-black text-sm font-bold hover:bg-[#65e040] hover:scale-105 active:scale-95 transition-all shadow-[0_0_20px_-5px_rgba(83,210,45,0.4)]">
                        <span class="hidden sm:inline">Create Estimate</span>
                        <span class="sm:hidden">Create</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="hidden sm:flex h-10 px-4 md:px-6 items-center justify-center rounded-full bg-surface-dark border border-border-dark text-white text-sm font-bold hover:bg-border-dark transition-all">
                        Log in
                    </a>
                    <a href="{{ route('register') }}" class="h-10 px-4 md:px-6 flex items-center justify-center rounded-full bg-primary text-black text-sm font-bold hover:bg-[#65e040] hover:scale-105 active:scale-95 transition-all shadow-[0_0_20px_-5px_rgba(83,210,45,0.4)]">
                        <span class="hidden sm:inline">Get Started Free</span>
                        <span class="sm:hidden">Sign Up</span>
                    </a>
                @endauth

                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="md:hidden h-10 w-10 flex items-center justify-center rounded-full bg-surface-dark border border-border-dark text-white hover:bg-border-dark transition-all">
                    <span class="material-symbols-outlined text-xl">menu</span>
                </button>
            </div>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div id="mobile-menu" class="hidden md:hidden absolute top-full left-0 right-0 bg-background-dark/95 backdrop-blur-md border-b border-border-dark shadow-lg">
            <div class="px-4 py-4 space-y-3">
                <a class="block text-sm font-medium hover:text-primary transition-colors py-2" href="#features">Features</a>
                <a class="block text-sm font-medium hover:text-primary transition-colors py-2" href="#how-it-works">How It Works</a>
                @guest
                    <a class="block text-sm font-medium hover:text-primary transition-colors py-2" href="#get-started">Get Started</a>
                    <div class="pt-3 border-t border-border-dark">
                        <a href="{{ route('login') }}" class="block w-full h-10 px-4 mb-2 flex items-center justify-center rounded-full bg-surface-dark border border-border-dark text-white text-sm font-bold hover:bg-border-dark transition-all">
                            Log in
                        </a>
                    </div>
                @else
                    <div class="pt-3 border-t border-border-dark">
                        <a href="{{ route('dashboard') }}" class="block w-full h-10 px-4 mb-2 flex items-center justify-center rounded-full bg-surface-dark border border-border-dark text-white text-sm font-bold hover:bg-border-dark transition-all">
                            Dashboard
                        </a>
                    </div>
                @endguest
            </div>
        </div>
    </header>
    <main class="flex-grow pt-24">
        <!-- Hero Section -->
        <section class="relative px-4 pb-20 pt-10 md:px-10 lg:pt-20">
            <div class="mx-auto max-w-[1280px] flex flex-col items-center">
                <div class="relative z-10 flex flex-col items-center gap-8 text-center max-w-4xl">
                    <div class="inline-flex items-center rounded-full border border-primary/20 bg-primary/10 px-3 py-1 text-sm font-medium text-primary">
                        <span class="mr-2">✨</span> Now 100% Free for All Users
                    </div>
                    <h1 class="text-5xl font-extrabold leading-[1.1] tracking-tight md:text-7xl lg:text-8xl bg-clip-text text-transparent bg-gradient-to-b from-white to-white/60">
                        Intelligent Project <span class="text-primary">Estimation</span>
                    </h1>
                    <p class="max-w-2xl text-lg font-normal text-slate-400 md:text-xl leading-relaxed">
                        Stop guessing. Start shipping. Upload your requirements and let AI calculate time, cost, and resources in seconds with 98% accuracy.
                    </p>
                    <!-- Interactive Input Teaser -->
                    <div class="mt-8 w-full max-w-xl group">
                        @auth
                            <form action="{{ route('estimates.create') }}" method="GET" class="relative flex w-full items-stretch rounded-full p-1 bg-surface-dark border border-border-dark focus-within:border-primary transition-all shadow-2xl shadow-black/50">
                                <div class="flex items-center pl-4 text-slate-400">
                                    <span class="material-symbols-outlined">description</span>
                                </div>
                                <input name="quick_description" class="h-14 w-full bg-transparent border-none px-4 text-base text-white placeholder:text-slate-500 focus:ring-0" placeholder="Describe your project or drop a PDF..." type="text"/>
                                <button type="submit" class="h-14 px-8 rounded-full bg-primary text-black font-bold text-sm md:text-base hover:bg-[#65e040] transition-colors whitespace-nowrap">
                                    Create Estimate
                                </button>
                            </form>
                        @else
                            <div class="relative flex w-full items-stretch rounded-full p-1 bg-surface-dark border border-border-dark focus-within:border-primary transition-all shadow-2xl shadow-black/50">
                                <div class="flex items-center pl-4 text-slate-400">
                                    <span class="material-symbols-outlined">description</span>
                                </div>
                                <input class="h-14 w-full bg-transparent border-none px-4 text-base text-white placeholder:text-slate-500 focus:ring-0" placeholder="Describe your project or drop a PDF..." type="text" readonly/>
                                <a href="{{ route('register') }}" class="h-14 px-8 rounded-full bg-primary text-black font-bold text-sm md:text-base hover:bg-[#65e040] transition-colors whitespace-nowrap flex items-center">
                                    Get Started Free
                                </a>
                            </div>
                        @endauth
                        <p class="mt-3 text-xs text-slate-500 flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-[14px]">check_circle</span> 100% Free - No Credit Card Required
                        </p>
                    </div>
                </div>
                <!-- Abstract Dashboard Preview -->
                <div class="mt-20 relative w-full max-w-5xl rounded-xl border border-border-dark bg-surface-dark/50 backdrop-blur-sm p-2 md:p-4 shadow-2xl">
                    <div class="absolute -top-20 -left-20 w-72 h-72 bg-primary/20 rounded-full blur-[100px] pointer-events-none"></div>
                    <div class="absolute -bottom-20 -right-20 w-72 h-72 bg-blue-500/10 rounded-full blur-[100px] pointer-events-none"></div>
                    <div class="relative overflow-hidden rounded-lg aspect-[16/9] md:aspect-[21/9] bg-surface-darker flex flex-col">
                        <!-- Fake UI Header -->
                        <div class="h-12 border-b border-border-dark flex items-center px-4 justify-between bg-surface-dark">
                            <div class="flex gap-2">
                                <div class="w-3 h-3 rounded-full bg-red-500/20"></div>
                                <div class="w-3 h-3 rounded-full bg-yellow-500/20"></div>
                                <div class="w-3 h-3 rounded-full bg-green-500/20"></div>
                            </div>
                            <div class="h-2 w-32 bg-border-dark rounded-full"></div>
                        </div>
                        <!-- Fake UI Body -->
                        <div class="flex-1 p-6 grid grid-cols-12 gap-6">
                            <div class="col-span-12 md:col-span-3 space-y-4">
                                <div class="h-8 w-3/4 bg-border-dark rounded animate-pulse"></div>
                                <div class="h-4 w-full bg-border-dark/50 rounded"></div>
                                <div class="h-4 w-5/6 bg-border-dark/50 rounded"></div>
                                <div class="mt-8 h-32 w-full bg-gradient-to-b from-border-dark/30 to-transparent rounded-lg border border-border-dark/50"></div>
                            </div>
                            <div class="col-span-12 md:col-span-9 space-y-6">
                                <div class="flex justify-between items-end">
                                    <div class="space-y-2">
                                        <div class="h-10 w-48 bg-border-dark rounded-lg"></div>
                                        <div class="h-4 w-32 bg-border-dark/50 rounded"></div>
                                    </div>
                                    <div class="h-10 w-32 bg-primary/20 border border-primary/30 rounded-lg"></div>
                                </div>
                                <!-- Charts Area -->
                                <div class="grid grid-cols-3 gap-4 h-48">
                                    <div class="bg-surface-dark border border-border-dark rounded-xl p-4 flex flex-col justify-end relative overflow-hidden group">
                                        <div class="absolute inset-0 bg-gradient-to-t from-primary/10 to-transparent opacity-50"></div>
                                        <div class="h-1/2 w-full bg-primary/20 rounded-t-lg border-t border-x border-primary/30"></div>
                                    </div>
                                    <div class="bg-surface-dark border border-border-dark rounded-xl p-4 flex flex-col justify-end">
                                        <div class="h-3/4 w-full bg-blue-500/10 rounded-t-lg border-t border-x border-blue-500/20"></div>
                                    </div>
                                    <div class="bg-surface-dark border border-border-dark rounded-xl p-4 flex flex-col justify-end">
                                        <div class="h-2/3 w-full bg-purple-500/10 rounded-t-lg border-t border-x border-purple-500/20"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Features Section -->
        <section id="features" class="px-4 py-20 bg-surface-darker border-y border-border-dark relative overflow-hidden">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-3xl h-1 bg-gradient-to-r from-transparent via-primary/50 to-transparent blur-sm"></div>
            <div class="mx-auto max-w-[1280px]">
                <div class="flex flex-col gap-4 mb-16 max-w-3xl">
                    <h2 class="text-3xl md:text-5xl font-extrabold tracking-tight text-white">
                        From Requirement to <span class="text-primary">Roadmap</span>
                    </h2>
                    <p class="text-slate-400 text-lg">
                        Our AI parses your raw documents and transforms them into actionable project plans, identifying bottlenecks before they happen.
                    </p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Feature 1 -->
                    <div class="group relative flex flex-col gap-6 rounded-[2rem] border border-border-dark bg-surface-dark p-8 transition-all hover:-translate-y-1 hover:border-primary/50 hover:shadow-[0_0_30px_-10px_rgba(83,210,45,0.15)]">
                        <div class="flex h-14 w-14 items-center justify-center rounded-full bg-background-dark border border-border-dark text-primary group-hover:bg-primary group-hover:text-black transition-colors">
                            <span class="material-symbols-outlined text-[28px]">upload_file</span>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white mb-2">PDF Upload &amp; Text Input</h3>
                            <p class="text-slate-400 leading-relaxed">Simply drag and drop your project requirement documents or paste messy meeting notes directly.</p>
                        </div>
                        <div class="mt-auto pt-6 border-t border-border-dark/50 flex justify-between items-center opacity-50 group-hover:opacity-100 transition-opacity">
                            <span class="text-xs font-bold uppercase tracking-wider text-primary">Input Source</span>
                            <span class="material-symbols-outlined text-primary text-sm">arrow_forward</span>
                        </div>
                    </div>
                    <!-- Feature 2 -->
                    <div class="group relative flex flex-col gap-6 rounded-[2rem] border border-border-dark bg-surface-dark p-8 transition-all hover:-translate-y-1 hover:border-primary/50 hover:shadow-[0_0_30px_-10px_rgba(83,210,45,0.15)]">
                        <div class="flex h-14 w-14 items-center justify-center rounded-full bg-background-dark border border-border-dark text-primary group-hover:bg-primary group-hover:text-black transition-colors">
                            <span class="material-symbols-outlined text-[28px]">psychology</span>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white mb-2">AI-Powered Analysis</h3>
                            <p class="text-slate-400 leading-relaxed">Our deep learning models break down complexity, identifying key tasks, dependencies, and potential risks.</p>
                        </div>
                        <div class="mt-auto pt-6 border-t border-border-dark/50 flex justify-between items-center opacity-50 group-hover:opacity-100 transition-opacity">
                            <span class="text-xs font-bold uppercase tracking-wider text-primary">Processing</span>
                            <span class="material-symbols-outlined text-primary text-sm">arrow_forward</span>
                        </div>
                    </div>
                    <!-- Feature 3 -->
                    <div class="group relative flex flex-col gap-6 rounded-[2rem] border border-border-dark bg-surface-dark p-8 transition-all hover:-translate-y-1 hover:border-primary/50 hover:shadow-[0_0_30px_-10px_rgba(83,210,45,0.15)]">
                        <div class="flex h-14 w-14 items-center justify-center rounded-full bg-background-dark border border-border-dark text-primary group-hover:bg-primary group-hover:text-black transition-colors">
                            <span class="material-symbols-outlined text-[28px]">monitoring</span>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white mb-2">Accurate Estimates</h3>
                            <p class="text-slate-400 leading-relaxed">Receive precise forecasts for budget, timeline, and resource allocation instantly based on historical data.</p>
                        </div>
                        <div class="mt-auto pt-6 border-t border-border-dark/50 flex justify-between items-center opacity-50 group-hover:opacity-100 transition-opacity">
                            <span class="text-xs font-bold uppercase tracking-wider text-primary">Output</span>
                            <span class="material-symbols-outlined text-primary text-sm">arrow_forward</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- How It Works Section -->
        <section id="how-it-works" class="px-4 py-24 md:px-10">
            <div class="mx-auto max-w-[1280px]">
                <div class="flex flex-col items-center text-center mb-16">
                    <span class="text-primary font-bold tracking-wider uppercase text-sm mb-3">Workflow</span>
                    <h2 class="text-3xl md:text-5xl font-extrabold tracking-tight text-white mb-6">How It Works</h2>
                    <p class="max-w-2xl text-slate-400">Transforming ambiguity into clarity in four simple steps.</p>
                </div>
                <div class="relative">
                    <!-- Connecting Line (Desktop) -->
                    <div class="absolute top-1/2 left-0 w-full h-1 bg-border-dark -translate-y-1/2 hidden md:block z-0"></div>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8 relative z-10">
                        <!-- Step 1 -->
                        <div class="flex flex-col items-center text-center group">
                            <div class="w-16 h-16 rounded-2xl bg-surface-dark border-2 border-border-dark flex items-center justify-center mb-6 shadow-lg group-hover:border-primary group-hover:scale-110 transition-all duration-300">
                                <span class="text-2xl font-black text-white group-hover:text-primary">1</span>
                            </div>
                            <h3 class="text-lg font-bold text-white mb-2">Upload Requirements</h3>
                            <p class="text-sm text-slate-400 leading-relaxed px-4">Drag in your PDF, Docx, or simply paste a text description.</p>
                        </div>
                        <!-- Step 2 -->
                        <div class="flex flex-col items-center text-center group">
                            <div class="w-16 h-16 rounded-2xl bg-surface-dark border-2 border-border-dark flex items-center justify-center mb-6 shadow-lg group-hover:border-primary group-hover:scale-110 transition-all duration-300 delay-75">
                                <span class="text-2xl font-black text-white group-hover:text-primary">2</span>
                            </div>
                            <h3 class="text-lg font-bold text-white mb-2">AI Analyzes Scope</h3>
                            <p class="text-sm text-slate-400 leading-relaxed px-4">Our NLP engine extracts tasks, features, and technical constraints.</p>
                        </div>
                        <!-- Step 3 -->
                        <div class="flex flex-col items-center text-center group">
                            <div class="w-16 h-16 rounded-2xl bg-surface-dark border-2 border-border-dark flex items-center justify-center mb-6 shadow-lg group-hover:border-primary group-hover:scale-110 transition-all duration-300 delay-150">
                                <span class="text-2xl font-black text-white group-hover:text-primary">3</span>
                            </div>
                            <h3 class="text-lg font-bold text-white mb-2">Review Parameters</h3>
                            <p class="text-sm text-slate-400 leading-relaxed px-4">Adjust team size, hourly rates, and risk buffers interactively.</p>
                        </div>
                        <!-- Step 4 -->
                        <div class="flex flex-col items-center text-center group">
                            <div class="w-16 h-16 rounded-2xl bg-surface-dark border-2 border-border-dark flex items-center justify-center mb-6 shadow-lg group-hover:border-primary group-hover:scale-110 transition-all duration-300 delay-200">
                                <span class="text-2xl font-black text-white group-hover:text-primary">4</span>
                            </div>
                            <h3 class="text-lg font-bold text-white mb-2">Get Your Estimate</h3>
                            <p class="text-sm text-slate-400 leading-relaxed px-4">View detailed breakdown with costs, timeline, and recommended team structure.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- CTA Section -->
        <section id="get-started" class="px-4 py-20 bg-primary relative overflow-hidden text-background-darker">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#000 1px, transparent 1px); background-size: 32px 32px;"></div>
            <div class="mx-auto max-w-4xl text-center relative z-10">
                <h2 class="text-4xl md:text-6xl font-black tracking-tighter mb-6 leading-tight">Ready to stop guessing?</h2>
                <p class="text-lg md:text-xl font-medium mb-10 max-w-2xl mx-auto opacity-80">Join thousands of project managers who trust SmartEstimate AI for their project planning.</p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    @auth
                        <a href="{{ route('estimates.create') }}" class="w-full sm:w-auto px-8 h-14 rounded-full bg-black text-white font-bold text-lg hover:bg-white hover:text-black transition-all shadow-xl flex items-center justify-center">
                            Create Your First Estimate
                        </a>
                        <a href="{{ route('dashboard') }}" class="w-full sm:w-auto px-8 h-14 rounded-full bg-transparent border-2 border-black text-black font-bold text-lg hover:bg-black/10 transition-all flex items-center justify-center">
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 h-14 rounded-full bg-black text-white font-bold text-lg hover:bg-white hover:text-black transition-all shadow-xl flex items-center justify-center">
                            Start Estimating Free
                        </a>
                        <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 h-14 rounded-full bg-transparent border-2 border-black text-black font-bold text-lg hover:bg-black/10 transition-all flex items-center justify-center">
                            Sign In
                        </a>
                    @endauth
                </div>
            </div>
        </section>
    </main>
    <footer class="bg-background-dark py-12 px-4 border-t border-border-dark">
        <div class="mx-auto max-w-[1280px] flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-2 text-white">
                <div class="size-6 text-primary">
                    <svg class="h-full w-full" fill="none" viewbox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                        <path clip-rule="evenodd" d="M47.2426 24L24 47.2426L0.757355 24L24 0.757355L47.2426 24ZM12.2426 21H35.7574L24 9.24264L12.2426 21Z" fill="currentColor" fill-rule="evenodd"></path>
                    </svg>
                </div>
                <span class="font-bold text-lg tracking-tight">SmartEstimate AI</span>
            </div>
            <div class="flex gap-8 text-sm text-slate-400">
                <a class="hover:text-white" href="#">Privacy</a>
                <a class="hover:text-white" href="#">Terms</a>
                <a class="hover:text-white" href="#">Twitter</a>
                <a class="hover:text-white" href="#">LinkedIn</a>
            </div>
            <div class="text-sm text-slate-600">
                © 2025 SmartEstimate AI Inc.
            </div>
        </div>
    </footer>
</div>

<!-- Enhanced interactivity -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');

    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!mobileMenuBtn.contains(e.target) && !mobileMenu.contains(e.target)) {
                mobileMenu.classList.add('hidden');
            }
        });
    }

    // Smooth scrolling for navigation links
    const navLinks = document.querySelectorAll('a[href^="#"]');
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
                // Close mobile menu after clicking a link
                if (mobileMenu) mobileMenu.classList.add('hidden');
            }
        });
    });
});
</script>

@guest
<!-- Guest user interactions -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const heroInput = document.querySelector('input[placeholder*="Describe your project"]');

    if (heroInput) {
        heroInput.addEventListener('focus', function() {
            this.parentElement.classList.add('ring-2', 'ring-primary/50');
        });

        heroInput.addEventListener('blur', function() {
            this.parentElement.classList.remove('ring-2', 'ring-primary/50');
        });

        heroInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                window.location.href = '{{ route("register") }}';
            }
        });
    }
});
</script>
@endguest
</body>
</html>
