<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', __('app.meta.title'))</title>
    <meta name="description" content="{{ __('app.meta.description') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
    @php
    $isAuthPage = request()->routeIs('login', 'register', 'register.role', 'register.recruiter');
    @endphp
    
    @unless($isAuthPage)
        <x-navbar />
    @endunless

    <main id="main-content" class="@yield('main_class', 'page-main')" tabindex="-1">
        @yield('content')
    </main>

    @php
        $isLandingPage = request()->routeIs('landing');
    @endphp
    
    @unless($isAuthPage || $isLandingPage)
        <x-footer />
    @endunless

    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script nonce="{{ $cspNonce ?? '' }}">
        // Optimized AOS initialization for better scroll performance
        (function() {
            let initAttempts = 0;
            const maxAttempts = 50; // Increased attempts
            const fallbackTimeout = 3000; // Show content after 3 seconds if AOS fails
            let showContentFallback = null;
            
            function initAOS() {
                if (typeof AOS !== 'undefined') {
                    // Clear fallback if AOS loads successfully
                    if (showContentFallback) {
                        clearTimeout(showContentFallback);
                        showContentFallback = null;
                    }
                    
                    try {
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
                        document.body.classList.add('aos-enabled');
                        console.log('AOS initialized successfully');
                    } catch (e) {
                        console.error('AOS initialization error:', e);
                        // If init fails, show content anyway
                        showAllContent();
                    }
                } else if (initAttempts < maxAttempts) {
                    initAttempts++;
                    setTimeout(initAOS, 100); // Check every 100ms
                } else {
                    // AOS failed to load after max attempts
                    console.warn('AOS failed to load, showing content without animation');
                    showAllContent();
                }
            }
            
            function showAllContent() {
                const aosElements = document.querySelectorAll('[data-aos]');
                aosElements.forEach(function(el) {
                    el.style.opacity = '1';
                    el.style.visibility = 'visible';
                    el.style.transform = 'none';
                });
            }
            
            // Set fallback timeout
            showContentFallback = setTimeout(function() {
                const aosElements = document.querySelectorAll('[data-aos]');
                const hasAosInit = Array.from(aosElements).some(function(el) {
                    return el.classList.contains('aos-init') || el.classList.contains('aos-animate');
                });
                
                // Only show content if AOS hasn't initialized any elements
                if (!hasAosInit && typeof AOS === 'undefined') {
                    console.warn('AOS timeout - showing content without animation');
                    showAllContent();
                }
            }, fallbackTimeout);

            // Wait for DOM and scripts to be ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', function() {
                    // Wait a bit longer for defer scripts to load
                    setTimeout(initAOS, 200);
                });
            } else {
                // DOM already loaded, wait for scripts
                setTimeout(initAOS, 200);
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
