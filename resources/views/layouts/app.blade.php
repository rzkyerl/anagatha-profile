<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Anagata Executive')</title>
    <meta name="description"
        content="Anagata Executive - Where Data Meet Talent. Headhunting, training, and outsourcing solutions powered by data-driven insights.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('styles/style.css') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/hero-sec.png') }}">
    @stack('head')
</head>

<body class="@yield('body_class', 'page')">
    <x-navbar />

    <main id="main-content" class="@yield('main_class', 'page-main')" tabindex="-1">
        @yield('content')
    </main>

    <x-footer />

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            offset: 100
        });
    </script>
    @stack('scripts')
    @stack('body_end')
</body>

</html>
