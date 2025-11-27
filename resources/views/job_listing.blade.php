@extends('layouts.app')

@section('title', 'Find Jobs | ' . __('app.meta.title'))
@section('body_class', 'page job-listing-page')

@section('content')
    <section class="job-search-hero">
        <div class="container">
            <div class="job-search-hero__content" data-aos="fade-up">
                <div class="job-search-hero__logo">
                    <div class="job-search-hero__logo-text">
                        <span class="job-search-hero__logo-name">Anagata Executive</span>
                        <span class="job-search-hero__logo-tagline">Where Data Meet Talent</span>
                    </div>
                </div>
                <h1 class="job-search-hero__title">Find Jobs</h1>
                <p class="job-search-hero__tagline">Search and apply for jobs in one click</p>
                
                <div class="job-search-card">
                    <div class="job-search-bar">
                        <div class="job-search-bar__input-group">
                        <div class="input-with-icon">
                            <i class="fa-solid fa-magnifying-glass input-icon" aria-hidden="true"></i>
                            <input type="text" placeholder="Search by Job or Company" class="job-search-input">
                        </div>
                    </div>
                    <div class="job-search-bar__input-group">
                        <div class="input-with-icon">
                            <i class="fa-solid fa-location-dot input-icon" aria-hidden="true"></i>
                            <input type="text" placeholder="Location" class="job-search-input">
                        </div>
                    </div>
                    <button type="button" class="job-search-bar__button cta-primary cta-primary--brand">
                        Search
                    </button>
                    </div>
                </div>

                <div class="job-filters" data-aos="fade-up" data-aos-delay="100">
                    <div class="job-filters__dropdowns">
                        <select class="job-filter-select">
                            <option value="">Work Preference</option>
                            <option value="wfo">WFO</option>
                            <option value="wfh">WFH</option>
                            <option value="hybrid">Hybrid</option>
                        </select>
                        <select class="job-filter-select">
                            <option value="">Salary</option>
                            <option value="0-5">IDR 0 - 5M</option>
                            <option value="5-10">IDR 5M - 10M</option>
                            <option value="10-20">IDR 10M - 20M</option>
                            <option value="20+">IDR 20M+</option>
                        </select>
                        <select class="job-filter-select">
                            <option value="">Industry</option>
                            <option value="tech">Technology</option>
                            <option value="finance">Finance</option>
                            <option value="retail">Retail</option>
                            <option value="manufacturing">Manufacturing</option>
                        </select>
                        <select class="job-filter-select">
                            <option value="">Years of Experience</option>
                            <option value="entry">Entry Level</option>
                            <option value="1-3">1-3 Years</option>
                            <option value="3-5">3-5 Years</option>
                            <option value="5+">5+ Years</option>
                        </select>
                    </div>
                    <div class="job-filters__actions">
                        <button type="button" class="job-filter-button" id="openFilterModal">
                            <i class="fa-solid fa-sliders" aria-hidden="true"></i>
                            All Filters
                        </button>
                        <a href="#" class="job-filter-clear" id="clearAllFilters">Clear All</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="job-listings-section">
        <div class="container">
            <div class="job-listings-section__header" data-aos="fade-up">
                <h2 class="job-listings-section__title">Hot Jobs This Month</h2>
            </div>
            <div class="job-cards-grid job-cards-grid--listing" id="jobCardsGrid">
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
                            'recruiter' => ['name' => 'Wahyu P • Recruitment', 'avatar' => 'WP'],
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
                        [
                            'logo' => '/assets/hero-sec.png',
                            'title' => 'Marketing Manager',
                            'company' => 'Tech Solutions Inc',
                            'verified' => true,
                            'salary' => 'IDR 15,000,000 - IDR 25,000,000',
                            'tags' => ['WFO', 'Full Time', 'Senior'],
                            'location' => 'Jakarta Pusat, Indonesia',
                            'posted' => 'about 12 hours ago',
                            'recruiter' => ['name' => 'Sarah • HR', 'avatar' => 'SA'],
                        ],
                        [
                            'logo' => '/assets/hero-sec.png',
                            'title' => 'Software Engineer',
                            'company' => 'Digital Innovations',
                            'verified' => true,
                            'salary' => 'IDR 20,000,000 - IDR 35,000,000',
                            'tags' => ['Hybrid', 'Full Time', 'Mid Level'],
                            'location' => 'Bandung, Indonesia',
                            'posted' => 'about 1 day ago',
                            'recruiter' => ['name' => 'Ahmad • HR', 'avatar' => 'AH'],
                        ],
                        [
                            'logo' => '/assets/hero-sec.png',
                            'title' => 'HR Specialist',
                            'company' => 'People First Co',
                            'verified' => true,
                            'salary' => 'IDR 12,000,000 - IDR 18,000,000',
                            'tags' => ['WFO', 'Full Time', 'Mid Level'],
                            'location' => 'Surabaya, Indonesia',
                            'posted' => 'about 2 days ago',
                            'recruiter' => ['name' => 'Lisa • HR', 'avatar' => 'LI'],
                        ],
                        [
                            'logo' => '/assets/hero-sec.png',
                            'title' => 'Product Manager',
                            'company' => 'Innovate Tech',
                            'verified' => true,
                            'salary' => 'IDR 30,000,000 - IDR 45,000,000',
                            'tags' => ['Hybrid', 'Full Time', 'Senior'],
                            'location' => 'Jakarta Selatan, Indonesia',
                            'posted' => 'about 2 days ago',
                            'recruiter' => ['name' => 'David • HR', 'avatar' => 'DA'],
                        ],
                        [
                            'logo' => '/assets/hero-sec.png',
                            'title' => 'Data Analyst',
                            'company' => 'Data Insights Co',
                            'verified' => true,
                            'salary' => 'IDR 18,000,000 - IDR 28,000,000',
                            'tags' => ['WFO', 'Full Time', 'Mid Level'],
                            'location' => 'Jakarta Pusat, Indonesia',
                            'posted' => 'about 3 days ago',
                            'recruiter' => ['name' => 'Rina • HR', 'avatar' => 'RI'],
                        ],
                        [
                            'logo' => '/assets/hero-sec.png',
                            'title' => 'UX Designer',
                            'company' => 'Creative Studio',
                            'verified' => true,
                            'salary' => 'IDR 22,000,000 - IDR 32,000,000',
                            'tags' => ['WFO', 'Full Time', 'Mid Level'],
                            'location' => 'Jakarta Barat, Indonesia',
                            'posted' => 'about 3 days ago',
                            'recruiter' => ['name' => 'Budi • HR', 'avatar' => 'BU'],
                        ],
                        [
                            'logo' => '/assets/hero-sec.png',
                            'title' => 'Account Executive',
                            'company' => 'Sales Force Inc',
                            'verified' => true,
                            'salary' => 'IDR 20,000,000 - IDR 30,000,000',
                            'tags' => ['WFO', 'Full Time', 'Mid Level'],
                            'location' => 'Jakarta Utara, Indonesia',
                            'posted' => 'about 4 days ago',
                            'recruiter' => ['name' => 'Sari • HR', 'avatar' => 'SR'],
                        ],
                        [
                            'logo' => '/assets/hero-sec.png',
                            'title' => 'Operations Manager',
                            'company' => 'Logistics Pro',
                            'verified' => true,
                            'salary' => 'IDR 28,000,000 - IDR 40,000,000',
                            'tags' => ['WFO', 'Full Time', 'Senior'],
                            'location' => 'Tangerang, Indonesia',
                            'posted' => 'about 4 days ago',
                            'recruiter' => ['name' => 'Andi • HR', 'avatar' => 'AN'],
                        ],
                        [
                            'logo' => '/assets/hero-sec.png',
                            'title' => 'Content Writer',
                            'company' => 'Media Group',
                            'verified' => true,
                            'salary' => 'IDR 10,000,000 - IDR 15,000,000',
                            'tags' => ['WFH', 'Full Time', 'Entry'],
                            'location' => 'Jakarta Selatan, Indonesia',
                            'posted' => 'about 5 days ago',
                            'recruiter' => ['name' => 'Maya • HR', 'avatar' => 'MY'],
                        ],
                        [
                            'logo' => '/assets/hero-sec.png',
                            'title' => 'Financial Analyst',
                            'company' => 'Finance Corp',
                            'verified' => true,
                            'salary' => 'IDR 25,000,000 - IDR 35,000,000',
                            'tags' => ['WFO', 'Full Time', 'Mid Level'],
                            'location' => 'Jakarta Pusat, Indonesia',
                            'posted' => 'about 5 days ago',
                            'recruiter' => ['name' => 'Eko • HR', 'avatar' => 'EK'],
                        ],
                        [
                            'logo' => '/assets/hero-sec.png',
                            'title' => 'Customer Success Manager',
                            'company' => 'Service Excellence',
                            'verified' => true,
                            'salary' => 'IDR 23,000,000 - IDR 33,000,000',
                            'tags' => ['Hybrid', 'Full Time', 'Mid Level'],
                            'location' => 'Jakarta Timur, Indonesia',
                            'posted' => 'about 6 days ago',
                            'recruiter' => ['name' => 'Dewi • HR', 'avatar' => 'DW'],
                        ],
                        [
                            'logo' => '/assets/hero-sec.png',
                            'title' => 'Quality Assurance Engineer',
                            'company' => 'Tech Quality',
                            'verified' => true,
                            'salary' => 'IDR 19,000,000 - IDR 27,000,000',
                            'tags' => ['WFO', 'Full Time', 'Mid Level'],
                            'location' => 'Bandung, Indonesia',
                            'posted' => 'about 6 days ago',
                            'recruiter' => ['name' => 'Fajar • HR', 'avatar' => 'FJ'],
                        ],
                        [
                            'logo' => '/assets/hero-sec.png',
                            'title' => 'Business Analyst',
                            'company' => 'Strategy Consulting',
                            'verified' => true,
                            'salary' => 'IDR 27,000,000 - IDR 38,000,000',
                            'tags' => ['WFO', 'Full Time', 'Senior'],
                            'location' => 'Jakarta Selatan, Indonesia',
                            'posted' => 'about 1 week ago',
                            'recruiter' => ['name' => 'Gita • HR', 'avatar' => 'GI'],
                        ],
                        [
                            'logo' => '/assets/hero-sec.png',
                            'title' => 'Graphic Designer',
                            'company' => 'Design Studio',
                            'verified' => true,
                            'salary' => 'IDR 14,000,000 - IDR 22,000,000',
                            'tags' => ['WFO', 'Full Time', 'Mid Level'],
                            'location' => 'Jakarta Barat, Indonesia',
                            'posted' => 'about 1 week ago',
                            'recruiter' => ['name' => 'Hadi • HR', 'avatar' => 'HD'],
                        ],
                        [
                            'logo' => '/assets/hero-sec.png',
                            'title' => 'Supply Chain Manager',
                            'company' => 'Supply Chain Solutions',
                            'verified' => true,
                            'salary' => 'IDR 32,000,000 - IDR 45,000,000',
                            'tags' => ['WFO', 'Full Time', 'Senior'],
                            'location' => 'Jakarta Utara, Indonesia',
                            'posted' => 'about 1 week ago',
                            'recruiter' => ['name' => 'Indra • HR', 'avatar' => 'IN'],
                        ],
                        [
                            'logo' => '/assets/hero-sec.png',
                            'title' => 'Frontend Developer',
                            'company' => 'Web Solutions',
                            'verified' => true,
                            'salary' => 'IDR 21,000,000 - IDR 31,000,000',
                            'tags' => ['Hybrid', 'Full Time', 'Mid Level'],
                            'location' => 'Jakarta Pusat, Indonesia',
                            'posted' => 'about 1 week ago',
                            'recruiter' => ['name' => 'Joko • HR', 'avatar' => 'JK'],
                        ],
                        [
                            'logo' => '/assets/hero-sec.png',
                            'title' => 'Backend Developer',
                            'company' => 'Server Tech',
                            'verified' => true,
                            'salary' => 'IDR 24,000,000 - IDR 34,000,000',
                            'tags' => ['WFO', 'Full Time', 'Mid Level'],
                            'location' => 'Jakarta Selatan, Indonesia',
                            'posted' => 'about 1 week ago',
                            'recruiter' => ['name' => 'Kiki • HR', 'avatar' => 'KI'],
                        ],
                        [
                            'logo' => '/assets/hero-sec.png',
                            'title' => 'DevOps Engineer',
                            'company' => 'Cloud Infrastructure',
                            'verified' => true,
                            'salary' => 'IDR 26,000,000 - IDR 36,000,000',
                            'tags' => ['Hybrid', 'Full Time', 'Senior'],
                            'location' => 'Jakarta Timur, Indonesia',
                            'posted' => 'about 1 week ago',
                            'recruiter' => ['name' => 'Lina • HR', 'avatar' => 'LN'],
                        ],
                        [
                            'logo' => '/assets/hero-sec.png',
                            'title' => 'Sales Manager',
                            'company' => 'Sales Pro',
                            'verified' => true,
                            'salary' => 'IDR 29,000,000 - IDR 42,000,000',
                            'tags' => ['WFO', 'Full Time', 'Senior'],
                            'location' => 'Jakarta Pusat, Indonesia',
                            'posted' => 'about 1 week ago',
                            'recruiter' => ['name' => 'Mario • HR', 'avatar' => 'MR'],
                        ],
                        [
                            'logo' => '/assets/hero-sec.png',
                            'title' => 'Project Coordinator',
                            'company' => 'Project Management Co',
                            'verified' => true,
                            'salary' => 'IDR 16,000,000 - IDR 24,000,000',
                            'tags' => ['WFO', 'Full Time', 'Mid Level'],
                            'location' => 'Jakarta Barat, Indonesia',
                            'posted' => 'about 1 week ago',
                            'recruiter' => ['name' => 'Nina • HR', 'avatar' => 'NN'],
                        ],
                        [
                            'logo' => '/assets/hero-sec.png',
                            'title' => 'IT Support Specialist',
                            'company' => 'IT Services',
                            'verified' => true,
                            'salary' => 'IDR 13,000,000 - IDR 20,000,000',
                            'tags' => ['WFO', 'Full Time', 'Entry'],
                            'location' => 'Jakarta Selatan, Indonesia',
                            'posted' => 'about 1 week ago',
                            'recruiter' => ['name' => 'Omar • HR', 'avatar' => 'OM'],
                        ],
                        [
                            'logo' => '/assets/hero-sec.png',
                            'title' => 'Social Media Manager',
                            'company' => 'Digital Marketing',
                            'verified' => true,
                            'salary' => 'IDR 17,000,000 - IDR 25,000,000',
                            'tags' => ['WFH', 'Full Time', 'Mid Level'],
                            'location' => 'Jakarta Pusat, Indonesia',
                            'posted' => 'about 1 week ago',
                            'recruiter' => ['name' => 'Putri • HR', 'avatar' => 'PT'],
                        ],
                        [
                            'logo' => '/assets/hero-sec.png',
                            'title' => 'Legal Counsel',
                            'company' => 'Legal Services',
                            'verified' => true,
                            'salary' => 'IDR 35,000,000 - IDR 50,000,000',
                            'tags' => ['WFO', 'Full Time', 'Senior'],
                            'location' => 'Jakarta Selatan, Indonesia',
                            'posted' => 'about 2 weeks ago',
                            'recruiter' => ['name' => 'Qori • HR', 'avatar' => 'QR'],
                        ],
                        [
                            'logo' => '/assets/hero-sec.png',
                            'title' => 'Research Analyst',
                            'company' => 'Research Institute',
                            'verified' => true,
                            'salary' => 'IDR 15,000,000 - IDR 23,000,000',
                            'tags' => ['WFO', 'Full Time', 'Mid Level'],
                            'location' => 'Jakarta Timur, Indonesia',
                            'posted' => 'about 2 weeks ago',
                            'recruiter' => ['name' => 'Rudi • HR', 'avatar' => 'RU'],
                        ],
                        [
                            'logo' => '/assets/hero-sec.png',
                            'title' => 'Training Specialist',
                            'company' => 'Learning Academy',
                            'verified' => true,
                            'salary' => 'IDR 18,000,000 - IDR 26,000,000',
                            'tags' => ['WFO', 'Full Time', 'Mid Level'],
                            'location' => 'Jakarta Utara, Indonesia',
                            'posted' => 'about 2 weeks ago',
                            'recruiter' => ['name' => 'Sinta • HR', 'avatar' => 'ST'],
                        ],
                        [
                            'logo' => '/assets/hero-sec.png',
                            'title' => 'Warehouse Manager',
                            'company' => 'Logistics Hub',
                            'verified' => true,
                            'salary' => 'IDR 20,000,000 - IDR 30,000,000',
                            'tags' => ['WFO', 'Full Time', 'Mid Level'],
                            'location' => 'Tangerang, Indonesia',
                            'posted' => 'about 2 weeks ago',
                            'recruiter' => ['name' => 'Tono • HR', 'avatar' => 'TN'],
                        ],
                        [
                            'logo' => '/assets/hero-sec.png',
                            'title' => 'Event Coordinator',
                            'company' => 'Event Management',
                            'verified' => true,
                            'salary' => 'IDR 12,000,000 - IDR 18,000,000',
                            'tags' => ['WFO', 'Full Time', 'Entry'],
                            'location' => 'Jakarta Pusat, Indonesia',
                            'posted' => 'about 2 weeks ago',
                            'recruiter' => ['name' => 'Umi • HR', 'avatar' => 'UM'],
                        ],
                    ];
                @endphp
                @foreach($jobs as $index => $job)
                    @php
                        $workPreference = '';
                        $experienceLevel = '';
                        foreach($job['tags'] as $tag) {
                            if (in_array(strtolower($tag), ['wfo', 'wfh', 'hybrid'])) {
                                $workPreference = strtolower($tag);
                            }
                            if (stripos($tag, 'entry') !== false) {
                                $experienceLevel = 'entry';
                            } elseif (stripos($tag, 'senior') !== false) {
                                $experienceLevel = '5+';
                            } elseif (preg_match('/\d+-\d+/', $tag)) {
                                $experienceLevel = preg_match('/1-3/', $tag) ? '1-3' : '3-5';
                            }
                        }
                        // Extract salary range for filtering
                        $salaryValue = 0;
                        if (preg_match('/IDR\s*([\d,]+)/', $job['salary'], $matches)) {
                            $salaryValue = (int)str_replace(',', '', $matches[1]);
                        }
                        $salaryRange = '';
                        if ($salaryValue > 0) {
                            if ($salaryValue < 5000000) {
                                $salaryRange = '0-5';
                            } elseif ($salaryValue < 10000000) {
                                $salaryRange = '5-10';
                            } elseif ($salaryValue < 20000000) {
                                $salaryRange = '10-20';
                            } else {
                                $salaryRange = '20+';
                            }
                        }
                    @endphp
                    <article class="job-card" 
                        data-job-index="{{ $index }}" 
                        data-job-title="{{ strtolower($job['title']) }}"
                        data-job-company="{{ strtolower($job['company']) }}"
                        data-job-location="{{ strtolower($job['location']) }}"
                        data-work-preference="{{ $workPreference }}"
                        data-salary-range="{{ $salaryRange }}"
                        data-experience-level="{{ $experienceLevel }}"
                        data-aos="fade-up" 
                        data-aos-delay="{{ ($index % 3) * 100 }}">
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
                                    <span class="job-card__tag">
                                        @if($tag === 'WFO')
                                            <i class="fa-solid fa-building" aria-hidden="true"></i>
                                        @elseif($tag === 'Contract' || strpos($tag, 'Time') !== false)
                                            <i class="fa-solid fa-file-contract" aria-hidden="true"></i>
                                        @else
                                            <i class="fa-solid fa-rocket" aria-hidden="true"></i>
                                        @endif
                                        {{ $tag }}
                                    </span>
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
                                <div class="job-card__recruiter-avatar job-card__recruiter-avatar--brand">{{ $job['recruiter']['avatar'] }}</div>
                                <span class="job-card__recruiter-name">{{ $job['recruiter']['name'] }}</span>
                            </div>
                        </div>
                        </a>
                    </article>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="job-pagination" id="jobPagination" style="display: none;">
                <button type="button" class="job-pagination__button job-pagination__button--prev" id="paginationPrev" aria-label="Previous page">
                    <i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
                </button>
                <div class="job-pagination__pages" id="paginationPages">
                    <!-- Pages will be generated by JavaScript -->
                </div>
                <button type="button" class="job-pagination__button job-pagination__button--next" id="paginationNext" aria-label="Next page">
                    <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                </button>
            </div>
        </div>
    </section>

    <!-- Filter Modal -->
    <div class="filter-modal" id="filterModal" hidden aria-hidden="true">
        <div class="filter-modal__backdrop" data-filter-modal-close></div>
        <div class="filter-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="filter-modal-title" tabindex="-1">
            <div class="filter-modal__header">
                <h3 class="filter-modal__title" id="filter-modal-title">All Filters</h3>
                <button type="button" class="filter-modal__close" aria-label="Close filters" data-filter-modal-close>
                    <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                </button>
            </div>
            <div class="filter-modal__body">
                <div class="filter-modal__content">
                    <div class="filter-modal__group">
                        <label class="filter-modal__label">Work Preference</label>
                        <select class="filter-modal__select" id="filterWorkPreference">
                            <option value="">All Work Preferences</option>
                            <option value="wfo">WFO</option>
                            <option value="wfh">WFH</option>
                            <option value="hybrid">Hybrid</option>
                        </select>
                    </div>
                    <div class="filter-modal__group">
                        <label class="filter-modal__label">Salary Range</label>
                        <select class="filter-modal__select" id="filterSalary">
                            <option value="">All Salary Ranges</option>
                            <option value="0-5">IDR 0 - 5M</option>
                            <option value="5-10">IDR 5M - 10M</option>
                            <option value="10-20">IDR 10M - 20M</option>
                            <option value="20+">IDR 20M+</option>
                        </select>
                    </div>
                    <div class="filter-modal__group">
                        <label class="filter-modal__label">Industry</label>
                        <select class="filter-modal__select" id="filterIndustry">
                            <option value="">All Industries</option>
                            <option value="tech">Technology</option>
                            <option value="finance">Finance</option>
                            <option value="retail">Retail</option>
                            <option value="manufacturing">Manufacturing</option>
                        </select>
                    </div>
                    <div class="filter-modal__group">
                        <label class="filter-modal__label">Years of Experience</label>
                        <select class="filter-modal__select" id="filterExperience">
                            <option value="">All Experience Levels</option>
                            <option value="entry">Entry Level</option>
                            <option value="1-3">1-3 Years</option>
                            <option value="3-5">3-5 Years</option>
                            <option value="5+">5+ Years</option>
                        </select>
                    </div>
                    <div class="filter-modal__group">
                        <label class="filter-modal__label">Minimum Degree</label>
                        <select class="filter-modal__select" id="filterDegree">
                            <option value="">All Degrees</option>
                            <option value="senior-high-school">Senior High School</option>
                            <option value="diploma">Diploma</option>
                            <option value="bachelor">Bachelor</option>
                            <option value="master">Master</option>
                            <option value="mba">MBA</option>
                            <option value="phd">Ph.D</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="filter-modal__footer">
                <button type="button" class="filter-modal__clear" id="filterModalClear">Clear All</button>
                <button type="button" class="cta-primary cta-primary--brand filter-modal__apply" id="filterModalApply">Apply Filters</button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="/js/custom-dropdown.js"></script>
        <script src="/js/job-pagination.js"></script>
        <script src="/js/job-search-filter.js"></script>
        <script src="/js/filter-modal.js"></script>
    @endpush
@endsection

