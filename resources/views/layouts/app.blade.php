<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('logo.png') }}">

        @php
            $pageTitle = isset($title) && !empty($title) 
                ? (is_string($title) ? $title : trim(strip_tags((string)$title))) 
                : 'FlashDev - Agensi Pembuatan Software & Website Professional';
            
            $pageDescription = isset($description) && !empty($description) 
                ? (is_string($description) ? $description : trim(strip_tags((string)$description))) 
                : 'Agensi pengembang software & website modern. Kami membantu akselerasi transformasi digital bisnis Anda dengan solusi teknologi performa tinggi.';
            
            $pageImage = isset($image) && !empty($image) 
                ? (is_string($image) ? $image : trim(strip_tags((string)$image))) 
                : 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=1200&q=80';
            
            $pageUrl = request()->url();
        @endphp

        <title>{{ $pageTitle }}</title>
        <meta name="description" content="{{ $pageDescription }}">
        <meta name="robots" content="index, follow">

        <!-- Open Graph / Facebook / WhatsApp Preview -->
        <meta property="og:type" content="website">
        <meta property="og:site_name" content="FlashDev Agency">
        <meta property="og:url" content="{{ $pageUrl }}">
        <meta property="og:title" content="{{ $pageTitle }}">
        <meta property="og:description" content="{{ $pageDescription }}">
        <meta property="og:image" content="{{ $pageImage }}">

        <!-- Twitter Card Meta -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:url" content="{{ $pageUrl }}">
        <meta name="twitter:title" content="{{ $pageTitle }}">
        <meta name="twitter:description" content="{{ $pageDescription }}">
        <meta name="twitter:image" content="{{ $pageImage }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />

        <!-- Inline script to prevent FOUC for theme toggle -->
        <script>
            if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @fluxAppearance
    </head>
    <body class="font-sans antialiased bg-[#F8FAFC] dark:bg-[#0B0F17] text-slate-900 dark:text-white selection:bg-amber-400 selection:text-slate-950 transition-colors duration-300">
        <div class="min-h-screen bg-[#F8FAFC] dark:bg-[#0B0F17] text-slate-900 dark:text-white transition-colors duration-300">
            <livewire:layout.navigation />

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>

            <x-footer />
        </div>
        @fluxScripts
    </body>
</html>
