<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'FlashDev') }}</title>

        <!-- Fonts & Material Symbols -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Same private-key theme system as the main layout (see layouts/app.blade.php) -->
        <script>
            function flashdevApplyTheme() {
                const stored = localStorage.getItem('flashdev-theme');
                const isDark = stored === 'dark' || (!stored && window.matchMedia('(prefers-color-scheme: dark)').matches);
                document.documentElement.classList.toggle('dark', isDark);
            }
            flashdevApplyTheme();
            document.addEventListener('livewire:navigated', flashdevApplyTheme);
        </script>
    </head>
    <body class="font-sans text-on-surface antialiased bg-surface">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-8 sm:pt-0 bg-surface px-4">
            <div>
                <a href="/" wire:navigate class="flex items-center space-x-2">
                    <img src="{{ asset('flash.png') }}" alt="FlashDev Logo" class="h-10 w-auto">
                    <span class="text-2xl font-bold tracking-tight text-on-surface">FLASH<span class="text-primary">DEV</span></span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-8 py-7 bg-surface-container-lowest border border-outline-variant shadow-xs sm:rounded-2xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
