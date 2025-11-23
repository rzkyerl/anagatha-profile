@extends('layouts.app')

@section('title', __('app.meta.title'))
@section('body_class', 'page home-page')

@section('content')
    @if (session('status'))
        <div class="toast-stack" data-toast>
            <div class="toast toast--{{ session('toast_type', 'success') }}" role="status" aria-live="polite">
                <div class="toast__icon">
                    @if (session('toast_type', 'success') === 'success')
                        <i class="fa-solid fa-circle-check" aria-hidden="true"></i>
                    @elseif (session('toast_type') === 'warning')
                        <i class="fa-solid fa-triangle-exclamation" aria-hidden="true"></i>
                    @else
                        <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
                    @endif
                </div>
                <div class="toast__body">
                    <p class="toast__title">
                        @if (session('toast_type', 'success') === 'success')
                            {{ __('app.toast.success') }}
                        @elseif (session('toast_type') === 'warning')
                            {{ __('app.toast.warning') }}
                        @else
                            {{ __('app.toast.error') }}
                        @endif
                    </p>
                    <p class="toast__message">{{ session('status') }}</p>
                </div>
                <button type="button" class="toast__close" aria-label="{{ __('app.aria.close_toast') }}">
                    <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                </button>
            </div>
        </div>
    @endif

    <section id="hero" class="hero home-hero">
        <div class="container hero__layout">
            <div class="hero-text" data-aos="fade-right">
                <span class="hero-eyebrow">{{ __('app.hero.eyebrow') }}</span>
                <h1>{{ __('app.hero.headline') }}</h1>
                <p class="text-lead">{{ __('app.hero.description') }}</p>
                <a class="cta-primary" href="{{ url('/#contact') }}">{{ __('app.hero.cta') }}</a>
            </div>
            <div class="hero-visual" data-aos="fade-left">
                <img class="hero-visual__image" src="/assets/hero-sec.png"
                    alt="Ilustrasi tim Anagata Executive yang berkolaborasi"
                    loading="eager" decoding="async" fetchpriority="high">
            </div>
        </div>
    </section>

    <section id="about">
        <div class="container section-shell section-shell--stack">
            <div class="section-heading section-heading--left" data-aos="fade-up">
                <h2 class="section-heading__title">{{ __('app.about.title') }}</h2>
                <p class="section-heading__text">{{ __('app.about.description') }}</p>
            </div>
        </div>
    </section>

    <section aria-labelledby="vision-mission-heading">
        <div class="container vision-mission-container">
            <div class="section-heading section-heading--left" data-aos="fade-up">
                <h2 id="vision-mission-heading" class="section-heading__title">{{ __('app.vision_mission.title') }}</h2>
                <p class="section-heading__text">{{ __('app.vision_mission.subtitle') }}</p>
            </div>

            <div class="vision-mission-grid">
                <article class="vision-mission-card vision-mission-card--vision" data-aos="fade-up" data-aos-delay="50">
                    <div class="vision-mission-card__header">
                        <div class="vision-mission-card__icon">
                            <i class="fa-solid fa-eye"></i>
                        </div>
                        <h3 class="vision-mission-card__title">{{ __('app.vision_mission.vision_title') }}</h3>
                    </div>
                    <p class="vision-mission-card__text">{{ __('app.vision_mission.vision_body') }}</p>
                </article>

                <article class="vision-mission-card vision-mission-card--mission" data-aos="fade-up" data-aos-delay="150">
                    <div class="vision-mission-card__header">
                        <div class="vision-mission-card__icon">
                            <i class="fa-solid fa-bullseye"></i>
                        </div>
                        <h3 class="vision-mission-card__title">{{ __('app.vision_mission.mission_title') }}</h3>
                    </div>
                    <p class="vision-mission-card__text">{{ __('app.vision_mission.mission_body') }}</p>
                </article>
            </div>
        </div>
    </section>


    <section id="services" aria-labelledby="services-title">
        <div class="container section-shell section-shell--stack">
            <div class="section-heading" data-aos="fade-up">
                <h2 id="services-title" class="section-heading__title">{{ __('app.services.title') }}</h2>
                <p class="section-heading__text">{{ __('app.services.description') }}</p>
            </div>

            <div class="grid-cards grid-cards--wide">
                <article class="card card--service" data-service-card data-service-key="executive" role="button"
                    tabindex="0" aria-haspopup="dialog" aria-controls="service-modal" data-aos="fade-up" data-aos-delay="50">
                    <div class="card--service__content">
                        <div class="card--service__text">
                            <h3>{{ __('app.services.cards.executive') }}</h3>
                            <button type="button" class="card--service__button" data-service-card-trigger>
                                {{ __('app.services.cards.view_details') }}
                            </button>
                        </div>
                        <div class="card--service__divider"></div>
                        <div class="card--service__icon">
                            <img src="/assets/scope.svg" alt="Executive Search & Leadership Placement" srcset="">
                        </div>
                    </div>
                </article>
                <article class="card card--service" data-service-card data-service-key="culture_fit" role="button"
                    tabindex="0" aria-haspopup="dialog" aria-controls="service-modal" data-aos="fade-up" data-aos-delay="150">
                    <div class="card--service__content">
                        <div class="card--service__text">
                            <h3>{{ __('app.services.cards.culture_fit') }}</h3>
                            <button type="button" class="card--service__button" data-service-card-trigger>
                                {{ __('app.services.cards.view_details') }}
                            </button>
                        </div>
                        <div class="card--service__divider"></div>
                        <div class="card--service__icon">
                            <img src="/assets/data-up.svg" alt="Culture Fit Recruitment for Growing Startups" srcset="">
                        </div>
                    </div>
                </article>
                <article class="card card--service" data-service-card data-service-key="pipeline" role="button"
                    tabindex="0" aria-haspopup="dialog" aria-controls="service-modal" data-aos="fade-up" data-aos-delay="250">
                    <div class="card--service__content">
                        <div class="card--service__text">
                            <h3>{{ __('app.services.cards.pipeline') }}</h3>
                            <button type="button" class="card--service__button" data-service-card-trigger>
                                {{ __('app.services.cards.view_details') }}
                            </button>
                        </div>
                        <div class="card--service__divider"></div>
                        <div class="card--service__icon">
                            <img src="/assets/analytic-chart.svg" alt="Talent Pipeline Development for Specialized Roles" srcset="">
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <div class="service-modal" id="service-modal" data-service-modal hidden aria-hidden="true">
        <div class="service-modal__backdrop" data-service-modal-close></div>
        <div class="service-modal__dialog" data-service-modal-dialog role="dialog" aria-modal="true"
            aria-labelledby="service-modal-title" tabindex="-1">
            <div class="service-modal__header">
                <div class="service-modal__header-left">
                    <p class="service-modal__eyebrow" data-service-modal-subtitle>{{ __('app.services.modal_copy.subtitle') }}</p>
                    <div class="service-modal__title-wrapper">
                        <h3 class="service-modal__title" id="service-modal-title" data-service-modal-title></h3>
                        <div class="service-modal__divider"></div>
                        <div class="service-modal__icon">
                            <img src="" alt="" class="service-modal__header-icon is-hidden" data-service-modal-image>
                        </div>
                    </div>
                </div>
                <button type="button" class="service-modal__close" aria-label="{{ __('app.aria.close_toast') }}"
                    data-service-modal-close>
                    <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                </button>
            </div>
            <div class="service-modal__body">
                <div class="service-modal__content">
                    <p class="service-modal__summary" data-service-modal-summary></p>
                    <ul class="service-modal__list" data-service-modal-list hidden></ul>
                </div>
            </div>
            <div class="service-modal__footer">
                <a class="cta-primary service-modal__cta" href="#contact" data-service-modal-cta>
                    {{ __('app.services.modal_copy.cta') }}
                </a>
            </div>
        </div>
    </div>


    <section id="why-us">
        <div class="container section-shell section-shell--stack">
            <div class="section-heading section-heading--left" data-aos="fade-up">
                <h2 class="section-heading__title">{{ __('app.why_us.title') }}</h2>
                <p class="section-heading__text">{{ __('app.why_us.description') }}</p>
            </div>
            <div class="why-us-grid">
                <article class="why-us-card" data-aos="fade-up" data-aos-delay="0">
                    <div class="why-us-card__icon">
                        <i class="fa-solid fa-bolt"></i>
                    </div>
                    <h3 class="why-us-card__title">{{ __('app.why_us.cards.fast') }}</h3>
                </article>
                <article class="why-us-card" data-aos="fade-up" data-aos-delay="50">
                    <div class="why-us-card__icon">
                        <i class="fa-solid fa-star"></i>
                    </div>
                    <h3 class="why-us-card__title">{{ __('app.why_us.cards.quality') }}</h3>
                </article>
                <article class="why-us-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="why-us-card__icon">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                    <h3 class="why-us-card__title">{{ __('app.why_us.cards.data') }}</h3>
                </article>
                <article class="why-us-card" data-aos="fade-up" data-aos-delay="150">
                    <div class="why-us-card__icon">
                        <i class="fa-solid fa-network-wired"></i>
                    </div>
                    <h3 class="why-us-card__title">{{ __('app.why_us.cards.network') }}</h3>
                </article>
                <article class="why-us-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="why-us-card__icon">
                        <i class="fa-solid fa-handshake"></i>
                    </div>
                    <h3 class="why-us-card__title">{{ __('app.why_us.cards.culture') }}</h3>
                </article>
                <article class="why-us-card" data-aos="fade-up" data-aos-delay="250">
                    <div class="why-us-card__icon">
                        <i class="fa-solid fa-eye"></i>
                    </div>
                    <h3 class="why-us-card__title">{{ __('app.why_us.cards.transparent') }}</h3>
                </article>
                <article class="why-us-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="why-us-card__icon">
                        <i class="fa-solid fa-lightbulb"></i>
                    </div>
                    <h3 class="why-us-card__title">{{ __('app.why_us.cards.insights') }}</h3>
                </article>
                <article class="why-us-card" data-aos="fade-up" data-aos-delay="350">
                    <div class="why-us-card__icon">
                        <i class="fa-solid fa-plug"></i>
                    </div>
                    <h3 class="why-us-card__title">{{ __('app.why_us.cards.integration') }}</h3>
                </article>
                <article class="why-us-card" data-aos="fade-up" data-aos-delay="400">
                    <div class="why-us-card__icon">
                        <i class="fa-solid fa-coins"></i>
                    </div>
                    <h3 class="why-us-card__title">{{ __('app.why_us.cards.cost') }}</h3>
                </article>
                <article class="why-us-card" data-aos="fade-up" data-aos-delay="450">
                    <div class="why-us-card__icon">
                        <i class="fa-solid fa-arrow-trend-up"></i>
                    </div>
                    <h3 class="why-us-card__title">{{ __('app.why_us.cards.improvement') }}</h3>
                </article>
            </div>
        </div>
    </section>



    <section id="contact">
        <div class="container section-shell contact-section">
            <div class="section-heading contact-section__heading" data-aos="fade-up">
                <h2 class="section-heading__title">{{ __('app.contact.title') }}</h2>
                <p class="section-heading__text">{{ __('app.contact.subtitle') }}</p>
            </div>
            <div class="contact-section__grid">
                <div class="card card--form" data-aos="fade-up" data-aos-delay="50">
                    <form action="{{ url('/contact') }}" method="POST" novalidate>
                        @csrf
                        {{-- Honeypot field for spam bots --}}
                        <div class="sr-only" aria-hidden="true">
                            <label for="company">{{ __('app.contact.form.honeypot_label') }}</label>
                            <input id="company" type="text" name="company" tabindex="-1" autocomplete="off">
                        </div>
                        <div class="form-grid">
                            <div class="form-field">
                                <label for="first_name">{{ __('app.contact.form.first_name_label') }}</label>
                                <div class="input-with-icon">
                                    <span class="input-icon" aria-hidden="true">
                                        <i class="fa-solid fa-user"></i>
                                    </span>
                                    <input id="first_name" type="text" name="first_name"
                                        value="{{ old('first_name') }}" placeholder="{{ __('app.contact.form.first_name_placeholder') }}" required
                                        autocomplete="given-name">
                                </div>
                                @error('first_name')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-field">
                                <label for="last_name">{{ __('app.contact.form.last_name_label') }}</label>
                                <div class="input-with-icon">
                                    <span class="input-icon" aria-hidden="true">
                                        <i class="fa-solid fa-user"></i>
                                    </span>
                                    <input id="last_name" type="text" name="last_name"
                                        value="{{ old('last_name') }}" placeholder="{{ __('app.contact.form.last_name_placeholder') }}"
                                        autocomplete="family-name">
                                </div>
                                @error('last_name')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="form-grid">
                            <div class="form-field">
                                <label for="email">{{ __('app.contact.form.email_label') }}</label>
                                <div class="input-with-icon">
                                    <span class="input-icon" aria-hidden="true">
                                        <i class="fa-solid fa-envelope"></i>
                                    </span>
                                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                                        placeholder="{{ __('app.contact.form.email_placeholder') }}" required autocomplete="email">
                                </div>
                                @error('email')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-field">
                                <label for="phone">{{ __('app.contact.form.phone_label') }}</label>
                                <div class="input-with-icon">
                                    <span class="input-icon" aria-hidden="true">
                                        <i class="fa-solid fa-phone"></i>
                                    </span>
                                    <input id="phone" type="text" name="phone" value="{{ old('phone') }}"
                                        placeholder="{{ __('app.contact.form.phone_placeholder') }}" autocomplete="tel">
                                </div>
                                @error('phone')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="form-field">
                            <label for="message">{{ __('app.contact.form.message_label') }}</label>
                            <div class="input-with-icon textarea-with-icon">
                                <span class="input-icon" aria-hidden="true">
                                <i class="fa-solid fa-comment-dots"></i>
                            </span>
                                <textarea id="message" name="message" placeholder="{{ __('app.contact.form.message_placeholder') }}" required rows="4">{{ old('message') }}</textarea>
                            </div>
                            @error('message')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="cta-primary">{{ __('app.contact.form.submit') }}</button>
                    </form>
                </div>

            </div>
        </div>
    </section>

    <section id="supported_by">
        <div class="container section-shell section-shell--stack">
            <div class="section-heading section-heading--left" data-aos="fade-up">
                <h2 class="section-heading__title">{{ __('app.supported_by.title') }}</h2>
            </div>
            <div class="supported-by-logos" data-aos="fade-up" data-aos-delay="100">
                <img src="/assets/logo-google-ai.png" alt="{{ __('app.logos.google_ai') }}" loading="lazy" decoding="async" fetchpriority="low">
                <img src="/assets/logo-kemnaker.png" alt="{{ __('app.logos.kemnaker') }}" loading="lazy" decoding="async" fetchpriority="low">
                <img src="/assets/logo-bnsp.png" alt="{{ __('app.logos.bnsp') }}" loading="lazy" decoding="async" fetchpriority="low">
                <img src="/assets/logo-akulita.png" alt="{{ __('app.logos.akulita') }}" loading="lazy" decoding="async" fetchpriority="low">
            </div>
        </div>
    </section>

    @push('scripts')
        @php
            $contactValidationMessages = __('app.contact.validation');
            $serviceModalCopy = [
                'subtitle' => __('app.services.modal_copy.subtitle'),
                'cta' => __('app.services.modal_copy.cta'),
            ];
            $serviceModalData = [
                'executive' => [
                    'title' => __('app.services.cards.executive'),
                    'summary' => __('app.services.modal.executive.summary'),
                    'details' => __('app.services.modal.executive.details'),
                    'image' => '/assets/scope.svg',
                    'imageAlt' => __('app.services.cards.executive'),
                    'cta' => '#contact',
                ],
                'culture_fit' => [
                    'title' => __('app.services.cards.culture_fit'),
                    'summary' => __('app.services.modal.culture_fit.summary'),
                    'details' => __('app.services.modal.culture_fit.details'),
                    'image' => '/assets/data-up.svg',
                    'imageAlt' => __('app.services.cards.culture_fit'),
                    'cta' => '#contact',
                ],
                'pipeline' => [
                    'title' => __('app.services.cards.pipeline'),
                    'summary' => __('app.services.modal.pipeline.summary'),
                    'details' => __('app.services.modal.pipeline.details'),
                    'image' => '/assets/analytic-chart.svg',
                    'imageAlt' => __('app.services.cards.pipeline'),
                    'cta' => '#contact',
                ],
            ];
        @endphp
        <script nonce="{{ $cspNonce ?? '' }}">
            window.contactFormMessages = @json($contactValidationMessages);
            window.serviceModalCopy = @json($serviceModalCopy);
            window.serviceCardData = @json($serviceModalData);
        </script>
        <script src="/js/services-modal.js"></script>
        <script src="/js/contact-form.js"></script>
    @endpush
@endsection
