@extends('layouts.app')

@section('title', 'Anagata Executive | Where Data Meet Talent')
@section('body_class', 'page home-page')

@section('content')
<section id="hero" class="hero home-hero">
    <div class="container hero__layout">
        <div class="hero-text" data-aos="fade-right">
            <span class="hero-eyebrow">Human Resources Recruitment Agency</span>
            <h1>Where Talent Thrives 
            & Culture Elevates</h1>
            <p class="text-lead">Discover top talent with speed, precision, and data-driven intelligence. Anagata Executive delivers end-to-end recruitment powered by AI, insight, and human expertise.</p>
                <a class="cta-primary" href="{{ url('/#contact') }}">Find a Job</a>
        </div>
        <div class="hero-visual" data-aos="fade-left">
            <img class="hero-visual__image" src="/assets/hero-sec.png" alt="Ilustrasi tim Anagata Executive yang berkolaborasi">
        </div>
    </div>
</section>

<section id="about">
    <div class="container section-shell section-shell--stack">
        <div class="section-heading section-heading--left" data-aos="fade-up">
            <h2 class="section-heading__title">Excellence in Talent Acquisition</h2>
            <p class="section-heading__text">We deliver comprehensive recruitment solutions with a data-driven and technology enabled approach to ensure your organization's success. We are committed to finding the perfect fit for both our clients and candidates. By combining deep industry expertise, a personalized recruitment approach, and an extensive professional network, we identify and attract top talent with precision and care. Our process focuses not only on skills and experience, but also on cultural alignment and long-term potential, ensuring that every placement supports sustainable growth. Through this approach, we help companies build stronger teams while guiding candidates toward meaningful career opportunities.</p>
        </div>  
    </div>
</section>

<section aria-labelledby="vision-mission-heading">
    <div class="container vision-mission-container">
        <div class="section-heading section-heading--left" data-aos="fade-up">
            <h2 id="vision-mission-heading" class="section-heading__title">Our Vision & Mission</h2>
            <p class="section-heading__text">Guiding principles that shape our commitment to excellence in talent acquisition</p>
        </div>
        
        <div class="vision-mission-grid">
            <article class="vision-mission-card vision-mission-card--vision" data-aos="fade-up" data-aos-delay="100">
                <div class="vision-mission-card__header">
                    <div class="vision-mission-card__icon">
                        <i class="fa-solid fa-eye"></i>
                    </div>
                    <h3 class="vision-mission-card__title">Our Vision</h3>
                </div>
                <p class="vision-mission-card__text">To become the most trusted and strategic talent partner in the industry, recognized for our ability to deliver exceptional talent solutions that drive organizational growth and success across diverse sectors.</p>
            </article>
            
            <article class="vision-mission-card vision-mission-card--mission" data-aos="fade-up" data-aos-delay="200">
                <div class="vision-mission-card__header">
                    <div class="vision-mission-card__icon">
                        <i class="fa-solid fa-bullseye"></i>
                    </div>
                    <h3 class="vision-mission-card__title">Our Mission</h3>
                </div>
                <p class="vision-mission-card__text">To bridge the gap between exceptional talent and remarkable opportunities by leveraging cutting-edge technology, data-driven insights, and human expertise. We are committed to building lasting partnerships, delivering unparalleled value to our clients, and empowering candidates to achieve their career aspirations through meaningful placements that foster professional growth and organizational excellence.</p>
            </article>
        </div>
    </div>
</section>


<section id="services" aria-labelledby="services-title">
    <div class="container section-shell section-shell--stack">
        <div class="section-heading" data-aos="fade-up">
            <h2 id="services-title" class="section-heading__title">What We Offer</h2>
            <p class="section-heading__text">We provide comprehensive recruitment solutions tailored to your organization's unique needs. From executive leadership placements to specialized talent pipelines, our services combine data-driven insights with personalized expertise to deliver exceptional results that drive your business forward.</p>
        </div>

        <div class="grid-cards grid-cards--wide">
            <article class="card card--service" data-aos="fade-up" data-aos-delay="100">
                <img src="/assets/scope.svg" alt="Executive Search & Leadership Placement" srcset="">
                <h3>Executive Search & Leadership Placement</h3>
            </article>
            <article class="card card--service" data-aos="fade-up" data-aos-delay="200">
            <img src="/assets/data-up.svg" alt="Culture Fit Recruitment for Growing Startups" srcset="">
                <h3>Culture Fit Recruitment for Growing Startups</h3>
            </article>
            <article class="card card--service" data-aos="fade-up" data-aos-delay="300">
            <img src="/assets/analytic-chart.svg" alt="Talent Pipeline Development for Specialized Roles" srcset="">
                <h3>Talent Pipeline Development for Specialized Roles</h3>
            </article>
        </div>
    </div>
</section>


<section id="why-us">
    <div class="container section-shell section-shell--stack">
        <div class="section-heading section-heading--left" data-aos="fade-up">
            <h2 class="section-heading__title">Why Choose Us</h2>
            <p class="section-heading__text">We deliver comprehensive recruitment solutions with a data-driven and technology-enabled approach to ensure your organization's success.</p>
        </div>
        <div class="why-us-grid">
            <article class="why-us-card" data-aos="fade-up" data-aos-delay="50">
                <div class="why-us-card__icon">
                    <i class="fa-solid fa-bolt"></i>
                </div>
                <h3 class="why-us-card__title">Fast & Efficient Hiring Process</h3>
            </article>
            <article class="why-us-card" data-aos="fade-up" data-aos-delay="100">
                <div class="why-us-card__icon">
                    <i class="fa-solid fa-star"></i>
                </div>
                <h3 class="why-us-card__title">Higher Candidate Quality</h3>
            </article>
            <article class="why-us-card" data-aos="fade-up" data-aos-delay="150">
                <div class="why-us-card__icon">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
                <h3 class="why-us-card__title">Data-Driven Decision Making</h3>
            </article>
            <article class="why-us-card" data-aos="fade-up" data-aos-delay="200">
                <div class="why-us-card__icon">
                    <i class="fa-solid fa-network-wired"></i>
                </div>
                <h3 class="why-us-card__title">Wide Talent Network</h3>
            </article>
            <article class="why-us-card" data-aos="fade-up" data-aos-delay="250">
                <div class="why-us-card__icon">
                    <i class="fa-solid fa-handshake"></i>
                </div>
                <h3 class="why-us-card__title">Culture Fit Matching</h3>
            </article>
            <article class="why-us-card" data-aos="fade-up" data-aos-delay="300">
                <div class="why-us-card__icon">
                    <i class="fa-solid fa-eye"></i>
                </div>
                <h3 class="why-us-card__title">Transparent & Accurate</h3>
            </article>
            <article class="why-us-card" data-aos="fade-up" data-aos-delay="350">
                <div class="why-us-card__icon">
                    <i class="fa-solid fa-lightbulb"></i>
                </div>
                <h3 class="why-us-card__title">Labor Market Insights</h3>
            </article>
            <article class="why-us-card" data-aos="fade-up" data-aos-delay="400">
                <div class="why-us-card__icon">
                    <i class="fa-solid fa-plug"></i>
                </div>
                <h3 class="why-us-card__title">HR System Integration</h3>
            </article>
            <article class="why-us-card" data-aos="fade-up" data-aos-delay="450">
                <div class="why-us-card__icon">
                    <i class="fa-solid fa-coins"></i>
                </div>
                <h3 class="why-us-card__title">Cost Effective</h3>
            </article>
            <article class="why-us-card" data-aos="fade-up" data-aos-delay="500">
                <div class="why-us-card__icon">
                    <i class="fa-solid fa-arrow-trend-up"></i>
                </div>
                <h3 class="why-us-card__title">Continuous Improvement</h3>
            </article>
        </div>
    </div>
</section>



<section id="contact">
    <div class="container section-shell contact-section">
        <div class="section-heading contact-section__heading" data-aos="fade-up">
            <h2 class="section-heading__title">Talk to US</h2>
            <p class="section-heading__text">Let us help you find the people who will shape your success. Contact us today</p>
        </div>
        <div class="contact-section__grid">
            <div class="card card--form" data-aos="fade-up" data-aos-delay="100">
            @if (session('status'))
                <div class="alert alert--success" role="status">
                    {{ session('status') }}
                </div>
            @endif

            <form action="{{ url('/contact') }}" method="POST" novalidate>
                @csrf
                <div class="form-grid">
                    <div class="form-field">
                        <label for="first_name">First Name</label>
                        <div class="input-with-icon">
                            <span class="input-icon" aria-hidden="true">
                                <i class="fa-solid fa-user"></i>
                            </span>
                            <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}" placeholder="Enter your first name" required autocomplete="given-name">
                        </div>
                        @error('first_name')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-field">
                        <label for="last_name">Last Name</label>
                        <div class="input-with-icon">
                            <span class="input-icon" aria-hidden="true">
                                <i class="fa-solid fa-user"></i>
                            </span>
                            <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Enter your last name" required autocomplete="family-name">
                        </div>
                        @error('last_name')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="form-grid">
                    <div class="form-field">
                        <label for="email">Email Address</label>
                        <div class="input-with-icon">
                            <span class="input-icon" aria-hidden="true">
                                <i class="fa-solid fa-envelope"></i>
                            </span>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email address" required autocomplete="email">
                        </div>
                        @error('email')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-field">
                        <label for="phone">Phone Number</label>
                        <div class="input-with-icon">
                            <span class="input-icon" aria-hidden="true">
                                <i class="fa-solid fa-phone"></i>
                            </span>
                            <input id="phone" type="text" name="phone" value="{{ old('phone') }}" placeholder="Enter your phone number" autocomplete="tel">
                        </div>
                        @error('phone')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="form-field">
                    <label for="message">Message</label>
                    <div class="input-with-icon textarea-with-icon">
                        <!-- <span class="input-icon" aria-hidden="true">
                            <i class="fa-solid fa-comment-dots"></i>
                        </span> -->
                        <textarea id="message" name="message" placeholder="Tell us about your needs" required rows="4">{{ old('message') }}</textarea>
                    </div>
                    @error('message')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="cta-primary">Send Message</button>
            </form>
            </div>
            
        </div>
    </div>
</section>
@endsection