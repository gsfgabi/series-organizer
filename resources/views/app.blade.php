<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Series Organizer') }}</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        
        <!-- PDF.js -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
        
        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/embed-solutions.js'])
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        @include('components.header')

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="flash-message fixed top-4 right-4 bg-primary-600 text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-opacity duration-300">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="flash-message fixed top-4 right-4 bg-red-600 text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-opacity duration-300">
                {{ session('error') }}
            </div>
        @endif

        <!-- Page Content -->
        <main class="min-h-screen">
            @yield('content')
        </main>

        @include('components.footer')
        @livewireScripts
    </body>
</html>