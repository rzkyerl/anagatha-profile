<header class="site-header" role="banner">
    <div class="container nav">
        <a href="{{ url('/') }}" class="logo">
            <span>Anagata Executive</span>
            <span>Where Data Meet Talent</span>
        </a>

        <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="primary-navigation" data-nav-toggle>
            <span class="sr-only">Toggle navigation</span>
            <i class="nav-toggle__icon nav-toggle__icon--menu fa-solid fa-bars" aria-hidden="true"></i>
            <i class="nav-toggle__icon nav-toggle__icon--close fa-solid fa-x" aria-hidden="true"></i>
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
    <script src="{{ asset('js/navbar.js') }}"></script>
@endpush
