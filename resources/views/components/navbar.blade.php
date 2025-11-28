@php
    $currentLocale = app()->getLocale();
    $isAuthenticated = auth()->check();
    $user = auth()->user();
    $isLandingPage = request()->routeIs('landing');
@endphp

<header class="site-header" role="banner">
    <div class="container nav">
        <a href="{{ route('landing') }}" class="logo">
            <span>Anagata Executive</span>
            <span>Where Data Meet Talent</span>
        </a>

        @unless($isLandingPage)
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
        @endunless

        <div class="nav-actions">
            @if($isAuthenticated)
                {{-- User Dropdown --}}
                <div class="user-dropdown" data-user-dropdown>
                    <button type="button" 
                        class="user-dropdown__trigger"
                        aria-expanded="false"
                        aria-haspopup="true"
                        data-user-dropdown-toggle>
                        <div class="user-dropdown__avatar">
                            @if($user->avatar ?? null)
                                <img src="{{ $user->avatar }}" alt="{{ $user->name }}" />
                            @else
                                <span>{{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}</span>
                            @endif
                        </div>
                        <span class="user-dropdown__name">{{ $user->name ?? 'User' }}</span>
                        <i class="fa-solid fa-chevron-down user-dropdown__icon" aria-hidden="true"></i>
                    </button>
                    <div class="user-dropdown__menu" role="menu">
                        <a href="{{ route('profile.test') }}" class="user-dropdown__item" role="menuitem">
                            <i class="fa-solid fa-user" aria-hidden="true"></i>
                            <span>Profile</span>
                        </a>
                        <a href="{{ route('history.test') }}" class="user-dropdown__item" role="menuitem">
                            <i class="fa-solid fa-clock-rotate-left" aria-hidden="true"></i>
                            <span>History</span>
                        </a>

                        {{-- Language Submenu --}}
                        <div class="user-dropdown__submenu" data-language-submenu>
                            <button type="button" class="user-dropdown__item user-dropdown__item--submenu" role="menuitem">
                                <i class="fa-solid fa-language" aria-hidden="true"></i>
                                <span>Language</span>
                                <i class="fa-solid fa-chevron-right user-dropdown__submenu-icon" aria-hidden="true"></i>
                            </button>
                            <div class="user-dropdown__submenu-menu" role="menu">
                                <button type="button"
                                    class="user-dropdown__submenu-item {{ $currentLocale === 'en' ? 'is-active' : '' }}"
                                    data-language="en" 
                                    data-language-url="{{ route('lang.switch', 'en') }}"
                                    role="menuitem">
                                    English
                                </button>
                                <button type="button"
                                    class="user-dropdown__submenu-item {{ $currentLocale === 'id' ? 'is-active' : '' }}"
                                    data-language="id" 
                                    data-language-url="{{ route('lang.switch', 'id') }}"
                                    role="menuitem">
                                    Bahasa Indonesia
                                </button>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('logout') }}" class="user-dropdown__logout-form">
                            @csrf
                            <button type="submit" class="user-dropdown__item user-dropdown__item--logout" role="menuitem">
                                <i class="fa-solid fa-sign-out-alt" aria-hidden="true"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            @else
                @if($isLandingPage)
                    {{-- Language Switcher (only on landing page) --}}
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

                    {{-- Login & Register Buttons (only on landing page) --}}
                    <div class="auth-buttons">
                        <a href="{{ route('login') }}" class="btn-login">
                            <span>Login</span>
                        </a>
                        <a href="{{ route('register') }}" class="btn-register">
                            <span>Register</span>
                        </a>
                    </div>
                @else
                    {{-- Testing User Dropdown (for non-landing pages) --}}
                    <div class="user-dropdown" data-user-dropdown>
                        <button type="button" 
                            class="user-dropdown__trigger"
                            aria-expanded="false"
                            aria-haspopup="true"
                            data-user-dropdown-toggle>
                            <div class="user-dropdown__avatar">
                                <i class="fa-solid fa-user" aria-hidden="true"></i>
                            </div>
                            <span class="user-dropdown__name">Testing Web</span>
                            <i class="fa-solid fa-chevron-down user-dropdown__icon" aria-hidden="true"></i>
                        </button>
                        <div class="user-dropdown__menu" role="menu">
                            <a href="{{ route('profile.test') }}" class="user-dropdown__item" role="menuitem">
                                <i class="fa-solid fa-user" aria-hidden="true"></i>
                                <span>Profile</span>
                            </a>
                            <a href="{{ route('history.test') }}" class="user-dropdown__item" role="menuitem">
                                <i class="fa-solid fa-clock-rotate-left" aria-hidden="true"></i>
                                <span>History</span>
                            </a>
                            
                            {{-- Language Submenu --}}
                            <div class="user-dropdown__submenu" data-language-submenu>
                                <button type="button" class="user-dropdown__item user-dropdown__item--submenu" role="menuitem">
                                    <i class="fa-solid fa-language" aria-hidden="true"></i>
                                    <span>Language</span>
                                    <i class="fa-solid fa-chevron-right user-dropdown__submenu-icon" aria-hidden="true"></i>
                                </button>
                                <div class="user-dropdown__submenu-menu" role="menu">
                                    <button type="button"
                                        class="user-dropdown__submenu-item {{ $currentLocale === 'en' ? 'is-active' : '' }}"
                                        data-language="en" 
                                        data-language-url="{{ route('lang.switch', 'en') }}"
                                        role="menuitem">
                                        English
                                    </button>
                                    <button type="button"
                                        class="user-dropdown__submenu-item {{ $currentLocale === 'id' ? 'is-active' : '' }}"
                                        data-language="id" 
                                        data-language-url="{{ route('lang.switch', 'id') }}"
                                        role="menuitem">
                                        Bahasa Indonesia
                                    </button>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('logout') }}" class="user-dropdown__logout-form">
                                @csrf
                                <button type="submit" class="user-dropdown__item user-dropdown__item--logout" role="menuitem">
                                    <i class="fa-solid fa-sign-out-alt" aria-hidden="true"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Mobile Menu Toggle --}}
                    <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="primary-navigation"
                        data-nav-toggle>
                        <span class="sr-only">Toggle navigation</span>
                        <i class="nav-toggle__icon nav-toggle__icon--menu fa-solid fa-bars" aria-hidden="true"></i>
                        <i class="nav-toggle__icon nav-toggle__icon--close fa-solid fa-x" aria-hidden="true"></i>
                    </button>
                @endif
            @endif
        </div>
    </div>
</header>

@push('scripts')
    <script src="/js/navbar.js"></script>
@endpush
