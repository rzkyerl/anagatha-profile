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
