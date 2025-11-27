@extends('layouts.app')

@section('title', ($job['title'] ?? 'Job Detail') . ' | ' . __('app.meta.title'))
@section('body_class', 'page job-detail-page')

@section('content')
    @php
        // Default job data - in real app, this would come from database
        $job = $job ?? [
            'id' => 1,
            'logo' => '/assets/hero-sec.png',
            'title' => 'Brand Representative',
            'company' => 'Indomobil AION',
            'verified' => true,
            'salary' => 'IDR 25,000,000 - IDR 35,000,000',
            'tags' => [
                ['text' => 'WFO', 'icon' => 'fa-building'],
                ['text' => 'Contract', 'icon' => 'fa-file-contract'],
                ['text' => 'Specialist/Supervisor', 'icon' => 'fa-rocket'],
                ['text' => '4-5 years', 'icon' => 'fa-briefcase'],
                ['text' => 'Diploma', 'icon' => 'fa-graduation-cap'],
            ],
            'location' => 'Jakarta Timur, Indonesia',
            'posted' => 'about 9 hours ago',
            'recruiter' => ['name' => 'Wahyu P', 'role' => 'Recruitment', 'avatar' => 'WP'],
            'responsibilities' => [
                'Promote and represent our brand to customers and retail partners',
                'Achieve sales targets through active selling and product introduction',
                'Build strong relationships with clients and maintain regular follow-ups',
                'Ensure product visibility, availability, and good merchandising in stores',
                'Provide product knowledge, handle inquiries, and deliver excellent service',
                'Gather market insights and report competitor activities',
            ],
            'requirements' => [
                'Strong communication and persuasion skills',
                'Strong networking skills',
                'Passion for sales and customer engagement',
                'Willing to work in the field and visit clients daily',
                'Target-oriented, energetic, and proactive',
            ],
            'key_skills' => ['Business Communications', 'Business Representation'],
            'benefits' => ['Transport allowance', 'Meal Allowance', 'Medical Benefit'],
            'company_info' => [
                'industry' => 'Automotive Industries',
                'employees' => '11 to 50 employees',
                'description' => 'Anak perusahaan dari Indomobil Group, yang bergerak di bidang impor, distribusi mobil dan suku cadang di bawah manajemen Indomobil Group.',
                'address' => 'Indomobil Tower Lantai 15, Jl. MT Haryono Kav.11 Bidara Cina, Jatinegara',
            ],
        ];
    @endphp

    <section class="job-detail-section">
        <div class="container">
            <a href="{{ route('jobs') }}" class="job-detail__back-link" data-aos="fade-up">
                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                <span>Back to Job list</span>
            </a>

            <!-- Job Header Card -->
            <div class="job-detail-header" data-aos="fade-up" data-aos-delay="50">
                <div class="job-detail-header__actions">
                    <button type="button" class="job-detail-header__action" aria-label="Share job">
                        <i class="fa-solid fa-share-nodes" aria-hidden="true"></i>
                    </button>
                </div>
                <div class="job-detail-header__content">
                    <div class="job-detail-header__logo">
                        <img src="{{ $job['logo'] }}" alt="{{ $job['company'] }}" loading="lazy" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="job-detail-header__logo-placeholder" style="display: none;">
                            {{ substr($job['company'], 0, 2) }}
                        </div>
                    </div>
                    <div class="job-detail-header__info">
                        <div class="job-detail-header__company">
                            <span class="job-detail-header__company-name">{{ $job['company'] }}</span>
                            @if($job['verified'])
                                <i class="fa-solid fa-circle-check job-detail-header__verified" aria-hidden="true"></i>
                            @endif
                        </div>
                        <h1 class="job-detail-header__title">{{ $job['title'] }}</h1>
                        <div class="job-detail-header__salary">{{ $job['salary'] }}</div>
                        <div class="job-detail-header__location">
                            <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                            <span>{{ $job['location'] }}</span>
                        </div>
                        <div class="job-detail-header__tags">
                            @foreach($job['tags'] as $tag)
                                <span class="job-detail-header__tag">
                                    <i class="fa-solid {{ $tag['icon'] ?? 'fa-circle' }}" aria-hidden="true"></i>
                                    <span>{{ $tag['text'] ?? $tag }}</span>
                                </span>
                            @endforeach
                        </div>
                        <button type="button" class="job-detail-header__apply cta-primary cta-primary--orange">
                            Apply Now
                        </button>
                    </div>
                </div>
                <div class="job-detail-header__meta">
                    <i class="fa-regular fa-clock" aria-hidden="true"></i>
                    <span>Updated {{ $job['posted'] }}</span>
                </div>
            </div>

            <!-- Main Content Layout -->
            <div class="job-detail-content">
                <div class="job-detail-content__main">
                    <!-- Job Description -->
                    <div class="job-detail-section-card" data-aos="fade-up" data-aos-delay="100">
                        <h2 class="job-detail-section-card__title">Job Description</h2>
                        
                        <div class="job-detail-description">
                            <div class="job-detail-description__section">
                                <h3 class="job-detail-description__section-title">
                                    <a href="#responsibilities" class="job-detail-description__link">Responsibilities</a>
                                </h3>
                                <ol class="job-detail-description__list">
                                    @foreach($job['responsibilities'] as $responsibility)
                                        <li>{{ $responsibility }}</li>
                                    @endforeach
                                </ol>
                            </div>

                            <div class="job-detail-description__section">
                                <h3 class="job-detail-description__section-title">
                                    <a href="#requirements" class="job-detail-description__link">Requirements</a>
                                </h3>
                                <ol class="job-detail-description__list">
                                    @foreach($job['requirements'] as $requirement)
                                        <li>{{ $requirement }}</li>
                                    @endforeach
                                </ol>
                            </div>
                        </div>
                    </div>

                    <!-- Key Skills -->
                    <div class="job-detail-section-card" data-aos="fade-up" data-aos-delay="150">
                        <h2 class="job-detail-section-card__title">Key Skills</h2>
                        <div class="job-detail-skills">
                            @foreach($job['key_skills'] as $skill)
                                <span class="job-detail-skill-tag">{{ $skill }}</span>
                            @endforeach
                        </div>
                    </div>

                    <!-- Benefits -->
                    <div class="job-detail-section-card" data-aos="fade-up" data-aos-delay="200">
                        <h2 class="job-detail-section-card__title">Benefits</h2>
                        <div class="job-detail-skills">
                            @foreach($job['benefits'] as $benefit)
                                <span class="job-detail-skill-tag">{{ $benefit }}</span>
                            @endforeach
                        </div>
                    </div>

                    <!-- About the Company -->
                    <div class="job-detail-section-card" data-aos="fade-up" data-aos-delay="250">
                        <h2 class="job-detail-section-card__title">About the Company</h2>
                        <div class="job-detail-company">
                            <div class="job-detail-company__header">
                                <div class="job-detail-company__logo">
                                    <img src="{{ $job['logo'] }}" alt="{{ $job['company'] }}" loading="lazy" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="job-detail-company__logo-placeholder" style="display: none;">
                                        {{ substr($job['company'], 0, 2) }}
                                    </div>
                                </div>
                                <div class="job-detail-company__info">
                                    <h3 class="job-detail-company__name">{{ $job['company'] }}</h3>
                                    <div class="job-detail-company__meta">
                                        <span class="job-detail-company__meta-item">Industry: {{ $job['company_info']['industry'] }}</span>
                                        <span class="job-detail-company__meta-item">{{ $job['company_info']['employees'] }}</span>
                                    </div>
                                </div>
                            </div>
                            <p class="job-detail-company__description">{{ $job['company_info']['description'] }}</p>
                        </div>
                    </div>

                    <!-- Office Address -->
                    <div class="job-detail-section-card" data-aos="fade-up" data-aos-delay="300">
                        <h2 class="job-detail-section-card__title">Office Address</h2>
                        <p class="job-detail-address">{{ $job['company_info']['address'] }}</p>
                    </div>

                    <!-- Security Notice -->
                    <div class="job-detail-security-notice" data-aos="fade-up" data-aos-delay="350">
                        <div class="job-detail-security-notice__content">
                            <h3 class="job-detail-security-notice__title">Be Smart. Be Safe.</h3>
                            <p class="job-detail-security-notice__text">
                                Credible companies will never ask you to make payments, top-ups, or provide sensitive information such as credit card or bank details.
                            </p>
                            <a href="#" class="job-detail-security-notice__link" data-security-modal-open>Learn how to stay secure</a>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <aside class="job-detail-sidebar">
                    <!-- Meet The Hiring Team -->
                    <div class="job-detail-sidebar-card" data-aos="fade-up" data-aos-delay="100">
                        <h3 class="job-detail-sidebar-card__title">Meet The Hiring Team</h3>
                        <div class="job-detail-hiring-team">
                            <div class="job-detail-hiring-team__avatar">{{ $job['recruiter']['avatar'] }}</div>
                            <div class="job-detail-hiring-team__info">
                                <div class="job-detail-hiring-team__name">{{ $job['recruiter']['name'] }}</div>
                                <div class="job-detail-hiring-team__role">{{ $job['recruiter']['role'] }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Jobs from this Company -->
                    <div class="job-detail-sidebar-card" data-aos="fade-up" data-aos-delay="150">
                        <h3 class="job-detail-sidebar-card__title">
                            <i class="fa-solid fa-building" aria-hidden="true"></i>
                            Jobs from this Company
                        </h3>
                        <div class="job-detail-other-jobs">
                            <div class="job-detail-other-jobs__empty">
                                <i class="fa-solid fa-circle-info" aria-hidden="true"></i>
                                <span>There is no other job</span>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>

            <!-- Footer Bar -->
            <div class="job-detail-footer-bar" data-aos="fade-up" data-aos-delay="400">
                <button type="button" class="job-detail-footer-bar__report" data-report-modal-open>
                    <i class="fa-solid fa-folder" aria-hidden="true"></i>
                    <span>Report</span>
                </button>
                <div class="job-detail-footer-bar__job-info">
                    <div class="job-detail-footer-bar__logo">
                        <img src="{{ $job['logo'] }}" alt="{{ $job['company'] }}" loading="lazy" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="job-detail-footer-bar__logo-placeholder" style="display: none;">
                            {{ substr($job['company'], 0, 2) }}
                        </div>
                    </div>
                    <div class="job-detail-footer-bar__details">
                        <div class="job-detail-footer-bar__company">
                            <span>{{ $job['company'] }}</span>
                            @if($job['verified'])
                                <i class="fa-solid fa-circle-check" aria-hidden="true"></i>
                            @endif
                        </div>
                        <div class="job-detail-footer-bar__title">{{ $job['title'] }}</div>
                    </div>
                </div>
                <button type="button" class="job-detail-footer-bar__apply cta-primary cta-primary--orange">
                    Apply Now
                </button>
            </div>
        </div>
    </section>

    <!-- Security Center Modal -->
    <div class="security-modal" id="security-modal" data-security-modal hidden aria-hidden="true">
        <div class="security-modal__backdrop" data-security-modal-close></div>
        <div class="security-modal__dialog" data-security-modal-dialog role="dialog" aria-modal="true"
            aria-labelledby="security-modal-title" tabindex="-1">
            <div class="security-modal__header">
                <div class="security-modal__header-content">
                    <h2 class="security-modal__title" id="security-modal-title">Safety and Security Center</h2>
                </div>
                <button type="button" class="security-modal__close" aria-label="Close modal"
                    data-security-modal-close>
                    <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                </button>
            </div>
            <div class="security-modal__body">
                <!-- How to Protect Yourself Section -->
                <div class="security-modal__section">
                    <h3 class="security-modal__section-title">How to Protect Yourself</h3>
                    <div class="security-modal__steps">
                        <div class="security-modal__step">
                            <div class="security-modal__step-number">1</div>
                            <div class="security-modal__step-content">
                                <h4 class="security-modal__step-title">Recognize the Scams</h4>
                                <p class="security-modal__step-text">
                                    Knowing the signs is your first step in staying safe. Scammers are constantly innovating with new ways to deceive job seekers, so stay alert!
                                </p>
                            </div>
                        </div>
                        <div class="security-modal__step">
                            <div class="security-modal__step-number">2</div>
                            <div class="security-modal__step-content">
                                <h4 class="security-modal__step-title">Protect Yourself Online</h4>
                                <p class="security-modal__step-text">
                                    Once you're aware of the scams, it's time to take action. Start from creating a strong password and avoiding suspicious links. These small actions can go a long way in keeping you safe.
                                </p>
                            </div>
                        </div>
                        <div class="security-modal__step">
                            <div class="security-modal__step-number">3</div>
                            <div class="security-modal__step-content">
                                <h4 class="security-modal__step-title">Control Your Data</h4>
                                <p class="security-modal__step-text">
                                    Your privacy is in your hands. Only share information relevant to your job search and recruitment process. Check our privacy policy to understand how we use your data.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Scams Around You Section -->
                <div class="security-modal__section">
                    <h3 class="security-modal__section-title">Scams Around You</h3>
                    <div class="security-modal__scams">
                        <div class="security-modal__scam">
                            <div class="security-modal__scam-content">
                                <h4 class="security-modal__scam-title">WhatsApp Impersonation Scams</h4>
                                <p class="security-modal__scam-text">
                                    Scammers may contact you via WhatsApp, pretending to be a reputable company. Always verify the identity through official channels.
                                </p>
                            </div>
                        </div>
                        <div class="security-modal__scam">
                            <div class="security-modal__scam-content">
                                <h4 class="security-modal__scam-title">External Forms & Sensitive Data</h4>
                                <p class="security-modal__scam-text">
                                    Do not fill out forms outside our official website or share personal/financial info until the company and job offer are verified.
                                </p>
                            </div>
                        </div>
                        <div class="security-modal__scam">
                            <div class="security-modal__scam-content">
                                <h4 class="security-modal__scam-title">Payment Demands / Top-Ups</h4>
                                <p class="security-modal__scam-text">
                                    Legitimate employers will never ask for payments or top-ups as part of the hiring process. This is a major red flag.
                                </p>
                            </div>
                        </div>
                        <div class="security-modal__scam">
                            <div class="security-modal__scam-content">
                                <h4 class="security-modal__scam-title">Suspicious External Links</h4>
                                <p class="security-modal__scam-text">
                                    Don't download any unverified links and use our official communication channels to discuss recruitment matters for added security.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Job Modal -->
    <div class="report-modal" id="report-modal" data-report-modal hidden aria-hidden="true">
        <div class="report-modal__backdrop" data-report-modal-close></div>
        <div class="report-modal__dialog" data-report-modal-dialog role="dialog" aria-modal="true"
            aria-labelledby="report-modal-title" tabindex="-1">
            <div class="report-modal__header">
                <h2 class="report-modal__title" id="report-modal-title">Report This Job!</h2>
                <button type="button" class="report-modal__close" aria-label="Close modal"
                    data-report-modal-close>
                    <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                </button>
            </div>
            <div class="report-modal__body">
                <form class="report-form" id="report-form">
                    <div class="form-grid">
                        <div class="form-field">
                            <label for="report-first-name">First Name <span class="required">*</span></label>
                            <div class="input-with-icon">
                                <i class="fa-solid fa-user input-icon" aria-hidden="true"></i>
                                <input type="text" id="report-first-name" name="first_name" required>
                            </div>
                        </div>
                        <div class="form-field">
                            <label for="report-last-name">Last Name <span class="required">*</span></label>
                            <div class="input-with-icon">
                                <i class="fa-solid fa-user input-icon" aria-hidden="true"></i>
                                <input type="text" id="report-last-name" name="last_name" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-field">
                        <label for="report-email">Email <span class="required">*</span></label>
                        <div class="input-with-icon">
                            <i class="fa-solid fa-envelope input-icon" aria-hidden="true"></i>
                            <input type="email" id="report-email" name="email" required>
                        </div>
                    </div>
                    <div class="form-field">
                        <label for="report-category">Select Category</label>
                        <div class="input-with-icon">
                            <i class="fa-solid fa-list input-icon" aria-hidden="true"></i>
                            <select id="report-category" name="category" class="report-select">
                                <option value="">Select a category</option>
                                <option value="spam">Spam or Scam</option>
                                <option value="misleading">Misleading Information</option>
                                <option value="duplicate">Duplicate Job Posting</option>
                                <option value="inappropriate">Inappropriate Content</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-field">
                        <label for="report-description">Description <span class="required">*</span></label>
                        <div class="input-with-icon textarea-with-icon">
                            <i class="fa-solid fa-comment input-icon" aria-hidden="true"></i>
                            <textarea id="report-description" name="description" rows="5" required placeholder="Please describe the issue..."></textarea>
                        </div>
                    </div>
                    <div class="report-modal__footer">
                        <button type="submit" class="cta-primary cta-primary--orange">Report</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="/js/security-modal.js"></script>
        <script src="/js/report-modal.js"></script>
    @endpush
@endsection

