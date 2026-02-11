<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preload" href="{{ asset('vendor/fonts/material-symbols/ms-400.ttf') }}" as="font" type="font/ttf"
        crossorigin>
    <link rel="stylesheet" href="{{ asset('vendor/fonts/inter.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/fonts/material-symbols.css') }}">

    <!-- Scripts -->
    <script src="{{ asset('vendor/tailwind/tailwind.min.js') }}"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#135bec",
                        "background-light": "#f6f6f8",
                        "background-dark": "#101622",
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    }
                },
            },
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            display: inline-block;
            vertical-align: middle;
        }

        .filled-icon {
            font-variation-settings: 'FILL' 1;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Safari Compatibility for glassmorphism */
        .backdrop-blur-md,
        .backdrop-blur-xl {
            -webkit-backdrop-filter: blur(12px);
        }
    </style>
</head>

<body x-data
    class="bg-background-light dark:bg-background-dark font-display text-[#111318] dark:text-white transition-colors duration-200 antialiased pb-20">
    <div
        class="relative flex min-h-screen w-full flex-col max-w-7xl mx-auto bg-white dark:bg-background-dark shadow-xl overflow-hidden md:my-4 md:rounded-3xl">
        <!-- TopAppBar -->
        <header
            class="sticky top-0 z-40 flex items-center bg-white/90 dark:bg-background-dark/90 backdrop-blur-md p-4 pb-2 justify-between border-b border-[#f0f2f4] dark:border-gray-800">
            <h1 class="text-[#111318] dark:text-white text-2xl font-bold leading-tight tracking-tight flex-1">
                {{ $header }}
            </h1>
            <div class="flex items-center justify-end gap-2">
                @isset($headerActions)
                    {{ $headerActions }}
                @endisset
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto relative">
            @if (session('status'))
                <div class="fixed top-20 left-1/2 -translate-x-1/2 z-[100] w-[calc(100%-2rem)] max-w-md">
                    <div
                        class="bg-primary/95 backdrop-blur-md text-white px-6 py-4 rounded-3xl shadow-2xl flex items-center gap-3 animate-in fade-in slide-in-from-top-4 duration-500">
                        <span class="material-symbols-outlined filled-icon">check_circle</span>
                        <p class="text-xs font-bold uppercase tracking-widest">{{ session('status') }}</p>
                    </div>
                </div>
            @endif

            @if (session('swal_error'))
                <div class="fixed top-20 left-1/2 -translate-x-1/2 z-[100] w-[calc(100%-2rem)] max-w-md">
                    <div
                        class="bg-red-500/95 backdrop-blur-md text-white px-6 py-4 rounded-3xl shadow-2xl flex items-center gap-3 animate-in fade-in slide-in-from-top-4 duration-500">
                        <span class="material-symbols-outlined filled-icon">error</span>
                        <p class="text-xs font-bold uppercase tracking-widest">{{ session('swal_error') }}</p>
                    </div>
                </div>
            @endif

            {{ $slot }}
        </main>

        @empty($hideNav)
            <x-bottom-nav />
        @endempty
    </div>
    @stack('scripts')
</body>

</html>