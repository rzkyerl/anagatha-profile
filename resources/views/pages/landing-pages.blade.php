@extends('layouts.app')

@section('title', 'Welcome - Anagata Executive')
@section('body_class', 'page landing-page')

@section('content')
<section id="hero" class="hero landing-hero">
    <div class="container hero__layout">
        <div class="hero-text" data-aos="fade-right">
            <span class="hero-eyebrow">Your Career Journey Starts Here</span>
            <h1>Find Your Dream Job with Anagata Executive</h1>
            <p class="text-lead">Search and find your dream job is now easier than ever. Just browse a job and apply if you need to. Connect with top employers and discover opportunities that match your skills.</p>
            <div class="hero-cta-group">
                <a class="cta-primary cta-primary--glow" href="{{ route('login') }}">
                    Get Started
                </a>
            </div>
        </div>
        <div class="hero-visual" data-aos="fade-left">
            <img class="hero-visual__image" src="/assets/hero-sec.png"
                alt="Ilustrasi tim Anagata Executive yang berkolaborasi"
                loading="eager" decoding="async" fetchpriority="high">
        </div>
    </div>
</section>

<section id="features" class="page-section">
    <div class="container section-shell section-shell--stack">
        <div class="section-heading section-heading--center" data-aos="fade-up">
            <span class="section-heading__eyebrow">Why Choose Us</span>
            <h2 class="section-heading__title">Everything You Need to Succeed</h2>
        </div>
        <div class="features-grid">
            <div class="feature-card" data-aos="fade-up" data-aos-delay="0">
                <div class="feature-card__icon">
                    <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                </div>
                <h3 class="feature-card__title">Easy Job Search</h3>
                <p class="feature-card__description">Browse through thousands of job listings and find the perfect match for your skills and career goals.</p>
            </div>
            <div class="feature-card" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-card__icon">
                    <i class="fa-solid fa-briefcase" aria-hidden="true"></i>
                </div>
                <h3 class="feature-card__title">Top Companies</h3>
                <p class="feature-card__description">Connect with verified employers and trusted companies looking for talented professionals like you.</p>
            </div>
            <div class="feature-card" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-card__icon">
                    <i class="fa-solid fa-rocket" aria-hidden="true"></i>
                </div>
                <h3 class="feature-card__title">Quick Apply</h3>
                <p class="feature-card__description">Apply to multiple positions with just a few clicks. Save time and focus on what matters most.</p>
            </div>
            <div class="feature-card" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-card__icon">
                    <i class="fa-solid fa-shield-halved" aria-hidden="true"></i>
                </div>
                <h3 class="feature-card__title">Secure & Trusted</h3>
                <p class="feature-card__description">Your data is safe with us. We prioritize your privacy and security throughout your job search journey.</p>
            </div>
        </div>
    </div>
</section>

<section id="cta-section" class="page-section cta-section">
    <div class="container">
        <div class="cta-banner" data-aos="fade-up">
            <div class="cta-banner__content">
                <h2 class="cta-banner__title">Ready to Start Your Career Journey?</h2>
                <p class="cta-banner__description">Join thousands of professionals who found their dream jobs through Anagata Executive</p>
                <div class="cta-banner__actions">
                    <a class="cta-primary cta-primary--glow" href="{{ route('login') }}">
                        Login Now
                    </a>
                    <a class="cta-primary cta-primary--outline" href="{{ route('register.role') }}">
                        Register Free
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

