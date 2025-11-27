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
                <a class="cta-primary" href="{{ route('jobs') }}">Find a Job</a>
            </div>
            <div class="hero-visual" data-aos="fade-left">
                <img class="hero-visual__image" src="/assets/hero-sec.png"
                    alt="Ilustrasi tim Anagata Executive yang berkolaborasi"
                    loading="eager" decoding="async" fetchpriority="high">
            </div>
        </div>
    </section>

    <section id="job_listings" class="page-section">
        <div class="container section-shell section-shell--stack">
            <div class="section-heading section-heading--center" data-aos="fade-up">
                <h2 class="section-heading__title">Grow Faster With the Right Talent</h2>
            </div>
            <div class="job-cards-grid">
                @php
                    $jobs = [
                        [
                            'logo' => '/assets/hero-sec.png',
                            'title' => 'Brand Representative',
                            'company' => 'Indomobil AION',
                            'verified' => true,
                            'salary' => 'IDR 25,000,000 - IDR 35,000,000',
                            'tags' => ['WFO', 'Contract', 'Specialist/Supervisor'],
                            'location' => 'Jakarta Timur, Indonesia',
                            'posted' => 'about 6 hours ago',
                            'recruiter' => ['name' => 'Wahyu P. Recruitment', 'avatar' => 'WP'],
                        ],
                        [
                            'logo' => '/assets/hero-sec.png',
                            'title' => 'Sales Supervisor - Modern Trade',
                            'company' => 'Sukanda Djaya',
                            'verified' => true,
                            'salary' => 'Not Disclose',
                            'tags' => ['WFO', 'Full Time', 'Specialist/Supervisor'],
                            'location' => 'Jakarta, Indonesia',
                            'posted' => 'about 6 hours ago',
                            'recruiter' => ['name' => 'Maytri • HR', 'avatar' => 'MD'],
                        ],
                        [
                            'logo' => '/assets/hero-sec.png',
                            'title' => 'Telesales - Credit Consultant',
                            'company' => 'Dolpheen Indonesia',
                            'verified' => true,
                            'salary' => 'Not Disclose',
                            'tags' => ['WFO', 'Full Time', 'Entry'],
                            'location' => 'Jakarta Selatan, Indonesia',
                            'posted' => 'about 6 hours ago',
                            'recruiter' => ['name' => 'Abraham • HR', 'avatar' => 'AB'],
                        ],
                        [
                            'logo' => '/assets/hero-sec.png',
                            'title' => 'Public Relations',
                            'company' => 'BlueFocus',
                            'verified' => true,
                            'salary' => 'Not Disclose',
                            'tags' => ['WFO', 'Full Time', 'Senior'],
                            'location' => 'Jakarta Selatan, Indonesia',
                            'posted' => 'about 8 hours ago',
                            'recruiter' => ['name' => 'Chunyan • HR', 'avatar' => 'CY'],
                        ],
                        [
                            'logo' => '/assets/hero-sec.png',
                            'title' => 'Business Development',
                            'company' => 'BlueFocus',
                            'verified' => true,
                            'salary' => 'Not Disclose',
                            'tags' => ['WFO', 'Full Time', 'Senior'],
                            'location' => 'Jakarta Selatan, Indonesia',
                            'posted' => 'about 8 hours ago',
                            'recruiter' => ['name' => 'Chunyan • HR', 'avatar' => 'CY'],
                        ],
                        [
                            'logo' => '/assets/hero-sec.png',
                            'title' => 'Advanced Indonesian/English Editor',
                            'company' => 'PT Dali Foods Indonesia',
                            'verified' => true,
                            'salary' => 'CNY 3,000 - CNY 5,000',
                            'tags' => ['WFO', 'Full Time', 'Entry'],
                            'location' => 'Kabupaten Karawang, Indonesia',
                            'posted' => 'about 8 hours ago',
                            'recruiter' => ['name' => 'ZHENGSIKAI Human Resources', 'avatar' => 'ZH'],
                        ],
                    ];
                @endphp
                @foreach($jobs as $index => $job)
                    <article class="job-card" data-aos="fade-up" data-aos-delay="{{ ($index % 3) * 100 }}">
                        <div class="job-card__header">
                            <div class="job-card__logo">
                                <img src="{{ $job['logo'] }}" alt="{{ $job['company'] }}" loading="lazy" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="job-card__logo-placeholder" style="display: none;">
                                    {{ substr($job['company'], 0, 2) }}
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('job.detail', ['id' => $index + 1]) }}" class="job-card__link">
                        <div class="job-card__body">
                            <h3 class="job-card__title">{{ $job['title'] }}</h3>
                            <div class="job-card__company">
                                <span class="job-card__company-name">{{ $job['company'] }}</span>
                                @if($job['verified'])
                                    <i class="fa-solid fa-circle-check job-card__verified" aria-hidden="true"></i>
                                @endif
                            </div>
                            <div class="job-card__salary">{{ $job['salary'] }}</div>
                            <div class="job-card__tags">
                                @foreach(array_slice($job['tags'], 0, 3) as $tag)
                                    <span class="job-card__tag">{{ $tag }}</span>
                                @endforeach
                                @if(count($job['tags']) > 3)
                                    <span class="job-card__tag job-card__tag--more">+{{ count($job['tags']) - 3 }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="job-card__footer">
                            <div class="job-card__meta">
                                <div class="job-card__meta-item">
                                    <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                                    <span>{{ $job['location'] }}</span>
                                </div>
                                <div class="job-card__meta-item">
                                    <i class="fa-regular fa-clock" aria-hidden="true"></i>
                                    <span>{{ $job['posted'] }}</span>
                                </div>
                            </div>
                            <div class="job-card__recruiter">
                                <div class="job-card__recruiter-avatar">{{ $job['recruiter']['avatar'] }}</div>
                                <span class="job-card__recruiter-name">{{ $job['recruiter']['name'] }}</span>
                            </div>
                        </div>
                        </a>
                    </article>
                @endforeach
            </div>
            <div class="job-listings__cta" data-aos="fade-up" data-aos-delay="300">
                <a href="{{ route('jobs') }}" class="cta-primary cta-primary--orange">Explore Jobs</a>
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
