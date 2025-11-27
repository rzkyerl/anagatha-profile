@php
    $currentLocale = app()->getLocale();
@endphp

<header class="site-header" role="banner">
    <div class="container nav">
        <a href="{{ url('/') }}" class="logo">
            <span>Anagata Executive</span>
            <span>Where Data Meet Talent</span>
        </a>

        <nav id="primary-navigation" class="nav-links" aria-label="Navigasi utama" data-nav-links>
            <a href="{{ route('home') }}"
                class="nav-links__link {{ request()->routeIs('home') ? 'is-active' : '' }}"
                data-nav-link="hero">{{ __('app.nav.home') }}</a>
            <a href="{{ route('about') }}"
                class="nav-links__link {{ request()->routeIs('about') ? 'is-active' : '' }}"
                data-nav-link="about">{{ __('app.nav.about') }}</a>
            <div class="nav-links__dropdown" data-nav-dropdown>
                <button type="button" 
                    class="nav-links__link nav-links__link--dropdown {{ request()->routeIs('services', 'jobs') ? 'is-active' : '' }}"
                    data-nav-link="services"
                    aria-expanded="false"
                    aria-haspopup="true">
                    {{ __('app.nav.services') }}
                    <i class="fa-solid fa-chevron-down nav-links__dropdown-icon" aria-hidden="true"></i>
                </button>
                <ul class="nav-links__dropdown-menu" role="menu">
                    <li role="none">
                        <a href="{{ route('services') }}" 
                           class="nav-links__dropdown-item {{ request()->routeIs('services') ? 'is-active' : '' }}"
                           role="menuitem">{{ __('app.nav.services') }}</a>
                    </li>
                    <li role="none">
                        <a href="{{ route('jobs') }}" 
                           class="nav-links__dropdown-item {{ request()->routeIs('jobs', 'job.detail') ? 'is-active' : '' }}"
                           role="menuitem">Job Listing</a>
                    </li>
                </ul>
            </div>
            <a href="{{ route('why-us') }}"
                class="nav-links__link {{ request()->routeIs('why-us') ? 'is-active' : '' }}"
                data-nav-link="why-us">{{ __('app.nav.why_us') }}</a>
            <a href="{{ route('contact') }}"
                class="nav-links__link nav-links__link--cta {{ request()->routeIs('contact') ? 'is-active' : '' }}"
                data-nav-link="contact">{{ __('app.nav.contact') }}</a>
        </nav>

        <div class="nav-actions">
            <div class="language-switcher" data-language-switcher data-default-language="{{ $currentLocale }}"
                role="group" aria-label="{{ __('app.language.switcher_label') }}">
                <button type="button"
                    class="language-switcher__btn {{ $currentLocale === 'en' ? 'is-active' : '' }}"
                    data-language="en" data-language-url="{{ route('lang.switch', 'en') }}"
                    aria-pressed="{{ $currentLocale === 'en' ? 'true' : 'false' }}">EN</button>
                <button type="button"
                    class="language-switcher__btn {{ $currentLocale === 'id' ? 'is-active' : '' }}"
                    data-language="id" data-language-url="{{ route('lang.switch', 'id') }}"
                    aria-pressed="{{ $currentLocale === 'id' ? 'true' : 'false' }}">ID</button>
            </div>

            <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="primary-navigation"
                data-nav-toggle>
                <span class="sr-only">Toggle navigation</span>
                <i class="nav-toggle__icon nav-toggle__icon--menu fa-solid fa-bars" aria-hidden="true"></i>
                <i class="nav-toggle__icon nav-toggle__icon--close fa-solid fa-x" aria-hidden="true"></i>
            </button>
        </div>
    </div>
</header>

@push('scripts')
    <script src="/js/navbar.js"></script>
@endpush
