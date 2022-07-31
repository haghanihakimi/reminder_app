<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>
        <link rel="canonical" href="{{ url()->current() }}">
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia

        {{-- @env ('local')
            <script src="http://localhost:8080/js/bundle.js"></script>
        @endenv --}}
        
        <!-- Icons -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        {{-- <link rel="stylesheet" href="{{ mix('css/app.css') }}"> --}}
        <link rel="stylesheet" href="{{ mix('css/_defaults.css') }}">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
        <link rel="stylesheet" href="{{ mix('css/MobileNavigation.css') }}">
        <link rel="stylesheet" href="{{ mix('css/HomeGuest.css') }}">

        <!-- Scripts -->
        @routes
            <script src="{{ mix('js/manifest.js') }}" defer></script>
            <script src="{{ mix('js/vendor.js') }}" defer></script>
            <script src="{{ mix('js/app.js') }}" defer></script>
        @inertiaHead
    </body>
</html>
