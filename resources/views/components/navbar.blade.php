<header class="site-header" role="banner">
    <div class="container nav">
        <a href="{{ url('/') }}" class="logo">
            <span>Anagata Executive</span>
            <span>Where Data Meet Talent</span>
        </a>

        <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="primary-navigation" data-nav-toggle>
            <span class="sr-only">Toggle navigation</span>
            <span class="nav-toggle__bar nav-toggle__bar--top" aria-hidden="true"></span>
            <span class="nav-toggle__bar nav-toggle__bar--middle" aria-hidden="true"></span>
            <span class="nav-toggle__bar nav-toggle__bar--bottom" aria-hidden="true"></span>
        </button>

        <nav id="primary-navigation" class="nav-links" aria-label="Navigasi utama" data-nav-links>
            <a href="{{ url('/#hero') }}" data-nav-link="hero">Home</a>
            <a href="{{ url('/#about') }}" data-nav-link="about">About</a>
            <a href="{{ url('/#services') }}" data-nav-link="services">Services</a>
            <a href="{{ url('/#why-us') }}" data-nav-link="why-us">Why Us</a>
            <a href="{{ url('/#contact') }}" data-nav-link="contact">Contact</a>
        </nav>
    </div>
</header>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const navToggle = document.querySelector('[data-nav-toggle]');
        const navLinks = document.querySelector('[data-nav-links]');

        if (!navToggle || !navLinks) {
            return;
        }

        const closeMenu = () => {
            if (!navLinks.classList.contains('is-open')) {
                return;
            }
            navLinks.classList.remove('is-open');
            navToggle.setAttribute('aria-expanded', 'false');
            navToggle.classList.remove('is-active');
            document.body.classList.remove('nav-open');
        };

        const closeOnLinkClick = (event) => {
            if (event.target.matches('a')) {
                closeMenu();
            }
        };

        navToggle.addEventListener('click', () => {
            const isOpen = navLinks.classList.toggle('is-open');
            navToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            navToggle.classList.toggle('is-active', isOpen);
            document.body.classList.toggle('nav-open', isOpen);
            if (isOpen) {
                navLinks.querySelector('a')?.focus();
            } else {
                navToggle.focus();
            }
        });

        document.addEventListener('click', (event) => {
            if (!navLinks.contains(event.target) && !navToggle.contains(event.target)) {
                closeMenu();
            }
        });

        document.addEventListener('keyup', (event) => {
            if (event.key === 'Escape') {
                closeMenu();
                navToggle.focus();
            }
        });

        navLinks.addEventListener('click', closeOnLinkClick);

        // Active link detection for desktop only (width > 768px)
        const updateActiveLink = () => {
            // Only run on desktop
            if (window.innerWidth <= 768) {
                // Remove all active states on mobile/tablet
                navLinks.querySelectorAll('a.is-active').forEach(link => {
                    link.classList.remove('is-active');
                });
                return;
            }

            const sections = document.querySelectorAll('section[id]');
            const navLinksList = navLinks.querySelectorAll('a[data-nav-link]');
            
            if (sections.length === 0 || navLinksList.length === 0) {
                return;
            }

            let currentSection = '';
            const scrollPosition = window.scrollY + 150; // Offset for better UX

            sections.forEach((section) => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.offsetHeight;
                const sectionId = section.getAttribute('id');

                if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                    currentSection = sectionId;
                }
            });

            // If at the top, make hero/home active
            if (window.scrollY < 100) {
                currentSection = 'hero';
            }

            // Update active state
            navLinksList.forEach((link) => {
                const linkSection = link.getAttribute('data-nav-link');
                if (linkSection === currentSection) {
                    link.classList.add('is-active');
                } else {
                    link.classList.remove('is-active');
                }
            });
        };

        // Update on scroll (throttled for performance)
        let scrollTimeout;
        window.addEventListener('scroll', () => {
            if (scrollTimeout) {
                window.cancelAnimationFrame(scrollTimeout);
            }
            scrollTimeout = window.requestAnimationFrame(updateActiveLink);
        });

        // Update on resize
        let resizeTimeout;
        window.addEventListener('resize', () => {
            if (resizeTimeout) {
                clearTimeout(resizeTimeout);
            }
            resizeTimeout = setTimeout(updateActiveLink, 150);
        });

        // Initial update
        updateActiveLink();
    });
</script>
@endpush