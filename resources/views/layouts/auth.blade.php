<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'Anagata Executive')</title>
    <meta name="description" content="{{ __('app.meta.description') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- Preconnect to external domains for faster DNS resolution --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    
    {{-- Preload critical image --}}
    <link rel="preload" as="image" href="/assets/hero-sec.png" fetchpriority="high">
    
    {{-- Load fonts with display=swap for better performance --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    {{-- Load Font Awesome - critical for icons, load synchronously --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    
    {{-- Load base variables first (required for CSS custom properties) --}}
    @php
        $variablesVersion = file_exists(public_path('styles/base/variables.css')) ? filemtime(public_path('styles/base/variables.css')) : time();
        $authVersion = file_exists(public_path('styles/auth/auth.css')) ? filemtime(public_path('styles/auth/auth.css')) : time();
    @endphp
    <link rel="stylesheet" href="/styles/base/variables.css?v={{ $variablesVersion }}" media="all">
    
    {{-- Load auth CSS - optimized for auth pages only --}}
    <link rel="stylesheet" href="/styles/auth/auth.css?v={{ $authVersion }}" media="all">
    
    <link rel="icon" type="image/x-icon" href="/assets/hero-sec.png">
    @stack('head')
</head>

<body class="@yield('body_class', 'page')">
    <main id="main-content" class="@yield('main_class', 'page-main')" tabindex="-1">
        @yield('content')
    </main>

    @stack('scripts')
</body>

</html>

