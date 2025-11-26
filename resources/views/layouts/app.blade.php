<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', __('app.meta.title'))</title>
    <meta name="description" content="{{ __('app.meta.description') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="image" href="/assets/hero-sec.png" fetchpriority="high">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/hugeicons@latest/css/hugeicons.css" />
    <link rel="stylesheet" href="/styles/style.css">
    <link rel="icon" type="image/x-icon" href="/assets/hero-sec.png">
    @stack('head')
</head>

<body class="@yield('body_class', 'page')">
    <x-navbar />

    <main id="main-content" class="@yield('main_class', 'page-main')" tabindex="-1">
        @yield('content')
    </main>

    <x-footer />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js" defer></script>
    <script nonce="{{ $cspNonce ?? '' }}">
        // Optimized AOS initialization for better scroll performance
        (function() {
            let initAttempts = 0;
            const maxAttempts = 20;
            
            function initAOS() {
                if (typeof AOS !== 'undefined') {
                    AOS.init({
                        duration: 500,
                        easing: 'ease-out-cubic',
                        once: true,
                        offset: 80,
                        mirror: false,
                        anchorPlacement: 'top-bottom',
                        disableMutationObserver: true,
                        throttleDelay: 99,
                        debounceDelay: 50
                    });
                } else if (initAttempts < maxAttempts) {
                    initAttempts++;
                    setTimeout(initAOS, 50);
                }
            }

            // Wait for DOM and scripts to be ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', function() {
                    setTimeout(initAOS, 100);
                });
            } else {
                setTimeout(initAOS, 100);
            }

            // Optimized resize handler with debounce
            let resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    if (typeof AOS !== 'undefined') {
                        AOS.refresh();
                    }
                }, 250);
            }, { passive: true });
        })();
    </script>
    @stack('scripts')
    @stack('body_end')
</body>

</html>
