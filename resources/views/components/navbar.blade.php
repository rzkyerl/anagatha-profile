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
            <a href="{{ url('/#hero') }}">Home</a>
            <a href="{{ url('/#about') }}">About</a>
            <a href="{{ url('/#services') }}">Services</a>
            <a href="{{ url('/#why-us') }}">Why Us</a>
            <a href="{{ url('/#contact') }}">Contact</a>
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
    });
</script>
@endpush