<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
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
        <main class="flex-1 overflow-y-auto">
            {{ $slot }}
        </main>

        @empty($hideNav)
            <x-bottom-nav />
        @endempty
    </div>
</body>

</html>