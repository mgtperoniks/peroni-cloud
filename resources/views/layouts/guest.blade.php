<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
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
            }
        </style>
    </head>
    <body class="bg-background-light dark:bg-background-dark min-h-screen flex flex-col font-display">
        <div class="relative flex h-auto min-h-screen w-full flex-col bg-background-light dark:bg-background-dark overflow-x-hidden">
            <!-- Top App Bar / Branding -->
            <div class="flex items-center bg-white dark:bg-slate-900 p-4 pb-2 justify-between border-b border-gray-200 dark:border-slate-800">
                <div class="text-primary flex size-12 shrink-0 items-center justify-center">
                    <span class="material-symbols-outlined text-3xl">factory</span>
                </div>
                <h2 class="text-[#111318] dark:text-white text-lg font-bold leading-tight tracking-[-0.015em] flex-1 text-center pr-12">Factory Log</h2>
            </div>
            
            <div class="flex-1 flex flex-col justify-center px-4 py-8">
                {{ $slot }}
            </div>

            <!-- Footer / Version Info -->
            <div class="p-6 text-center">
                <p class="text-[#616f89] dark:text-slate-500 text-xs font-medium uppercase tracking-widest">Internal Use Only • v2.4.0</p>
            </div>
        </div>
    </body>
</html>
