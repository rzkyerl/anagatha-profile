<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Anagata Executive')</title>
    <meta name="description" content="Anagata Executive - Where Data Meet Talent. Headhunting, training, and outsourcing solutions powered by data-driven insights.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('styles/style.css') }}">
    @stack('head')
</head>
<body class="@yield('body_class', 'page')">
    <a class="skip-link" href="#main-content">Skip to content</a>

    <x-navbar />

    <main id="main-content" class="@yield('main_class', 'page-main')" tabindex="-1">
        @yield('content')
    </main>

    <x-footer />

    @stack('scripts')
    @stack('body_end')
</body>
</html>
