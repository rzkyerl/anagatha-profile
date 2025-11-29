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
                    $jobs = [];
                    if (isset($jobListings) && $jobListings->count() > 0) {
                        foreach ($jobListings as $listing) {
                            // Build tags array
                            $tags = [];
                            if ($listing->work_preference) {
                                $tags[] = strtoupper($listing->work_preference);
                            }
                            if ($listing->contract_type) {
                                $tags[] = $listing->contract_type;
                            }
                            if ($listing->experience_level) {
                                $tags[] = $listing->experience_level;
                            }
                            
                            // Format salary
                            $salary = $listing->salary_display ?? 'Not Disclose';
                            if ($listing->salary_min && $listing->salary_max) {
                                $salary = 'IDR ' . number_format($listing->salary_min, 0, ',', ',') . ' - IDR ' . number_format($listing->salary_max, 0, ',', ',');
                            } elseif ($listing->salary_min) {
                                $salary = 'IDR ' . number_format($listing->salary_min, 0, ',', ',') . '+';
                            }
                            
                            // Format posted date
                            $posted = 'Just now';
                            if ($listing->posted_at) {
                                $posted = $listing->posted_at->setTimezone('Asia/Jakarta')->diffForHumans();
                            } elseif ($listing->created_at) {
                                $posted = $listing->created_at->setTimezone('Asia/Jakarta')->diffForHumans();
                            }
                            
                            // Recruiter info
                            $recruiterName = 'Admin';
                            $recruiterAvatar = 'AD';
                            if ($listing->recruiter) {
                                $recruiterName = $listing->recruiter->first_name . ' ' . $listing->recruiter->last_name;
                                $recruiterAvatar = strtoupper(substr($listing->recruiter->first_name, 0, 1) . substr($listing->recruiter->last_name, 0, 1));
                            }
                            
                            $jobs[] = [
                                'id' => $listing->id,
                                'logo' => $listing->company_logo ?? '/assets/hero-sec.png',
                                'title' => $listing->title,
                                'company' => $listing->company,
                                'verified' => $listing->verified ?? false,
                                'salary' => $salary,
                                'tags' => $tags,
                                'location' => $listing->location,
                                'posted' => $posted,
                                'recruiter' => [
                                    'name' => $recruiterName,
                                    'avatar' => $recruiterAvatar
                                ],
                                'work_preference' => $listing->work_preference,
                                'experience_level' => $listing->experience_level,
                                'salary_min' => $listing->salary_min,
                                'salary_max' => $listing->salary_max,
                                'industry' => $listing->industry,
                                'minimum_degree' => $listing->minimum_degree,
                            ];
                        }
                    }
                    
                    // Fallback to empty array if no jobs in database
                    if (empty($jobs)) {
                        $jobs = [];
                    }
                @endphp
                @foreach($jobs as $index => $job)
                    @php
                        // Get work preference from job data or extract from tags
                        $workPreference = $job['work_preference'] ?? '';
                        if (empty($workPreference)) {
                            foreach($job['tags'] ?? [] as $tag) {
                            if (in_array(strtolower($tag), ['wfo', 'wfh', 'hybrid'])) {
                                $workPreference = strtolower($tag);
                                    break;
                                }
                            }
                        }
                        
                        // Get experience level from job data and normalize it for filtering
                        $experienceLevel = $job['experience_level'] ?? '';
                        $experienceLevelFilter = '';
                        
                        if (!empty($experienceLevel)) {
                            // Normalize experience level to match filter values
                            $expLower = strtolower($experienceLevel);
                            if (stripos($expLower, 'entry') !== false) {
                                $experienceLevelFilter = 'entry';
                            } elseif (preg_match('/\b(1|2|3)\s*(years?|year)\b/i', $expLower) || preg_match('/^1-3/i', $expLower)) {
                                $experienceLevelFilter = '1-3';
                            } elseif (preg_match('/\b(4|5)\s*(years?|year)\b/i', $expLower) || preg_match('/^3-5/i', $expLower) || preg_match('/^4-5/i', $expLower)) {
                                $experienceLevelFilter = '3-5';
                            } elseif (preg_match('/\b(6|7|8|9|\d{2,})\s*(years?|year|plus|\+)\b/i', $expLower) || stripos($expLower, 'senior') !== false || preg_match('/^5\+/i', $expLower)) {
                                $experienceLevelFilter = '5+';
                            }
                        }
                        
                        // If still empty, try to extract from tags
                        if (empty($experienceLevelFilter)) {
                            foreach($job['tags'] ?? [] as $tag) {
                                $tagLower = strtolower($tag);
                                if (stripos($tagLower, 'entry') !== false) {
                                    $experienceLevelFilter = 'entry';
                                    break;
                                } elseif (stripos($tagLower, 'senior') !== false) {
                                    $experienceLevelFilter = '5+';
                                    break;
                                } elseif (preg_match('/1-3/i', $tag)) {
                                    $experienceLevelFilter = '1-3';
                                    break;
                                } elseif (preg_match('/3-5|4-5/i', $tag)) {
                                    $experienceLevelFilter = '3-5';
                                    break;
                                }
                            }
                        }
                        
                        // Use normalized value for filtering
                        $experienceLevel = $experienceLevelFilter;
                        
                        // Extract salary range for filtering - use actual min/max values
                        $salaryMin = $job['salary_min'] ?? null;
                        $salaryMax = $job['salary_max'] ?? null;
                        
                        // Convert to millions for easier comparison
                        $salaryMinM = $salaryMin ? ($salaryMin / 1000000) : 0;
                        $salaryMaxM = $salaryMax ? ($salaryMax / 1000000) : 0;
                        
                        // Determine primary salary range based on min (or average if both exist)
                        $salaryRange = '';
                        if ($salaryMinM > 0) {
                            // Use average if both min and max exist, otherwise use min
                            $avgSalary = $salaryMaxM > 0 ? (($salaryMinM + $salaryMaxM) / 2) : $salaryMinM;
                            
                            if ($avgSalary < 5) {
                                $salaryRange = '0-5';
                            } elseif ($avgSalary < 10) {
                                $salaryRange = '5-10';
                            } elseif ($avgSalary < 20) {
                                $salaryRange = '10-20';
                            } else {
                                $salaryRange = '20+';
                            }
                        }
                        // Map industry to filter values (normalize industry names)
                        $industryFilter = '';
                        $industry = strtolower($job['industry'] ?? '');
                        if (!empty($industry)) {
                            // Map database industry values to filter dropdown values
                            if (stripos($industry, 'technology') !== false || stripos($industry, 'tech') !== false) {
                                $industryFilter = 'tech';
                            } elseif (stripos($industry, 'finance') !== false || stripos($industry, 'financial') !== false) {
                                $industryFilter = 'finance';
                            } elseif (stripos($industry, 'retail') !== false) {
                                $industryFilter = 'retail';
                            } elseif (stripos($industry, 'manufacturing') !== false || stripos($industry, 'automotive') !== false) {
                                $industryFilter = 'manufacturing';
                            }
                        }
                        
                        // Get minimum degree for filtering
                        $minimumDegree = strtolower($job['minimum_degree'] ?? '');
                    @endphp
                    <article class="job-card" 
                        data-job-index="{{ $index }}" 
                        data-job-title="{{ strtolower($job['title']) }}"
                        data-job-company="{{ strtolower($job['company']) }}"
                        data-job-location="{{ strtolower($job['location']) }}"
                        data-work-preference="{{ $workPreference }}"
                        data-salary-range="{{ $salaryRange }}"
                        data-salary-min="{{ $salaryMinM }}"
                        data-salary-max="{{ $salaryMaxM }}"
                        data-experience-level="{{ $experienceLevel }}"
                        data-industry="{{ $industryFilter }}"
                        data-degree="{{ $minimumDegree }}"
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
                        <a href="{{ route('job.detail', ['id' => $job['id'] ?? ($index + 1)]) }}" class="job-card__link">
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
                
                @if(empty($jobs))
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">No job listings available at the moment. Please check back later.</p>
                    </div>
                @endif
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

