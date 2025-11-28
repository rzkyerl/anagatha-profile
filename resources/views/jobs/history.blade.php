@extends('layouts.app')

@section('title', 'History - Anagata Executive')
@section('body_class', 'page history-page')

@section('content')
<div class="history-container">
    <div class="history-card">
        {{-- Header Section --}}
        <div class="history-header">
            <h1 class="history-title">HISTORY</h1>
            <p class="history-subtitle">Track your job applications</p>
        </div>

        <div class="history-content">
            {{-- My Applications Section --}}
            <div class="history-section">
                <div class="history-section-header">
                    <h2 class="history-section-title">
                        <i class="fa-solid fa-file-lines history-section-icon"></i>
                        My Applications
                    </h2>
                    <span class="history-section-count" id="applicationsCount">3</span>
                </div>

                <div class="history-list" id="applicationsList">
                    {{-- Application Item 1 --}}
                    <div class="history-item history-item--application" data-aos="fade-up">
                        <div class="history-item__header">
                            <div class="history-item__logo">
                                <img src="/assets/hero-sec.png" alt="Company Logo" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="history-item__logo-placeholder" style="display: none;">AE</div>
                            </div>
                            <div class="history-item__status history-item__status--pending">
                                <i class="fa-solid fa-clock"></i>
                                <span>Pending</span>
                            </div>
                        </div>
                        <div class="history-item__body">
                            <h3 class="history-item__title">Senior Data Analyst</h3>
                            <div class="history-item__company">
                                <i class="fa-solid fa-building"></i>
                                <span>Anagata Executive</span>
                            </div>
                            <div class="history-item__meta">
                                <div class="history-item__meta-item">
                                    <i class="fa-solid fa-calendar"></i>
                                    <span>Applied on: January 15, 2024</span>
                                </div>
                                <div class="history-item__meta-item">
                                    <i class="fa-solid fa-location-dot"></i>
                                    <span>Jakarta, Indonesia</span>
                                </div>
                            </div>
                        </div>
                        <div class="history-item__footer">
                            <button type="button" class="history-item__link" data-application-modal-open data-application-id="1">
                                View Details
                                <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>  

                    {{-- Application Item 2 --}}
                    <div class="history-item history-item--application" data-aos="fade-up" data-aos-delay="50">
                        <div class="history-item__header">
                            <div class="history-item__logo">
                                <img src="/assets/hero-sec.png" alt="Company Logo" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="history-item__logo-placeholder" style="display: none;">ID</div>
                            </div>
                            <div class="history-item__status history-item__status--review">
                                <i class="fa-solid fa-eye"></i>
                                <span>Under Review</span>
                            </div>
                        </div>
                        <div class="history-item__body">
                            <h3 class="history-item__title">Brand Representative</h3>
                            <div class="history-item__company">
                                <i class="fa-solid fa-building"></i>
                                <span>Indomobil AION</span>
                            </div>
                            <div class="history-item__meta">
                                <div class="history-item__meta-item">
                                    <i class="fa-solid fa-calendar"></i>
                                    <span>Applied on: January 10, 2024</span>
                                </div>
                                <div class="history-item__meta-item">
                                    <i class="fa-solid fa-location-dot"></i>
                                    <span>Jakarta Timur, Indonesia</span>
                                </div>
                            </div>
                        </div>
                        <div class="history-item__footer">
                            <button type="button" class="history-item__link" data-application-modal-open data-application-id="2">
                                View Details
                                <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Application Item 3 --}}
                    <div class="history-item history-item--application" data-aos="fade-up" data-aos-delay="100">
                        <div class="history-item__header">
                            <div class="history-item__logo">
                                <img src="/assets/hero-sec.png" alt="Company Logo" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="history-item__logo-placeholder" style="display: none;">TC</div>
                            </div>
                            <div class="history-item__status history-item__status--accepted">
                                <i class="fa-solid fa-check-circle"></i>
                                <span>Accepted</span>
                            </div>
                        </div>
                        <div class="history-item__body">
                            <h3 class="history-item__title">Full Stack Developer</h3>
                            <div class="history-item__company">
                                <i class="fa-solid fa-building"></i>
                                <span>Tech Company</span>
                            </div>
                            <div class="history-item__meta">
                                <div class="history-item__meta-item">
                                    <i class="fa-solid fa-calendar"></i>
                                    <span>Applied on: December 28, 2023</span>
                                </div>
                                <div class="history-item__meta-item">
                                    <i class="fa-solid fa-location-dot"></i>
                                    <span>Remote</span>
                                </div>
                            </div>
                        </div>
                        <div class="history-item__footer">
                            <button type="button" class="history-item__link" data-application-modal-open data-application-id="3">
                                View Details
                                <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Empty State for Applications --}}
                <div class="history-empty" id="applicationsEmpty" style="display: none;">
                    <div class="history-empty__icon">
                        <i class="fa-solid fa-file-lines"></i>
                    </div>
                    <h3 class="history-empty__title">No Applications Yet</h3>
                    <p class="history-empty__message">You haven't applied to any jobs yet. Start exploring available positions!</p>
                    <a href="{{ route('jobs') }}" class="history-empty__action cta-primary cta-primary--brand">
                        Browse Jobs
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Application Detail Modal --}}
<div class="application-detail-modal" id="applicationDetailModal" hidden aria-hidden="true">
    <div class="application-detail-modal__backdrop" data-application-modal-close></div>
    <div class="application-detail-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="application-modal-title" tabindex="-1">
        <div class="application-detail-modal__content">
            <div class="application-detail-modal__header">
                <h2 class="application-detail-modal__title" id="application-modal-title">Application Details</h2>
                <button type="button" class="application-detail-modal__close" data-application-modal-close aria-label="Close modal">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <div class="application-detail-modal__body">
                <div class="application-detail__section">
                    <div class="application-detail__header">
                        <div class="application-detail__logo">
                            <img id="modal-company-logo" src="/assets/hero-sec.png" alt="Company Logo" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="application-detail__logo-placeholder" id="modal-company-logo-placeholder" style="display: none;">AE</div>
                        </div>
                        <div class="application-detail__status-wrapper">
                            <div class="application-detail__status" id="modal-status">
                                <i class="fa-solid fa-clock"></i>
                                <span>Pending</span>
                            </div>
                        </div>
                    </div>
                    <h3 class="application-detail__job-title" id="modal-job-title">Senior Data Analyst</h3>
                    <div class="application-detail__company" id="modal-company">
                        <i class="fa-solid fa-building"></i>
                        <span>Anagata Executive</span>
                    </div>
                </div>

                <div class="application-detail__section">
                    <h4 class="application-detail__section-title">
                        <i class="fa-solid fa-info-circle"></i>
                        Application Information
                    </h4>
                    <div class="application-detail__info-grid">
                        <div class="application-detail__info-item">
                            <div class="application-detail__info-label">
                                <i class="fa-solid fa-calendar"></i>
                                <span>Applied Date</span>
                            </div>
                            <div class="application-detail__info-value" id="modal-applied-date">January 15, 2024</div>
                        </div>
                        <div class="application-detail__info-item">
                            <div class="application-detail__info-label">
                                <i class="fa-solid fa-location-dot"></i>
                                <span>Location</span>
                            </div>
                            <div class="application-detail__info-value" id="modal-location">Jakarta, Indonesia</div>
                        </div>
                        <div class="application-detail__info-item">
                            <div class="application-detail__info-label">
                                <i class="fa-solid fa-briefcase"></i>
                                <span>Position</span>
                            </div>
                            <div class="application-detail__info-value" id="modal-position">Senior Data Analyst</div>
                        </div>
                        <div class="application-detail__info-item">
                            <div class="application-detail__info-label">
                                <i class="fa-solid fa-building"></i>
                                <span>Company</span>
                            </div>
                            <div class="application-detail__info-value" id="modal-company-name">Anagata Executive</div>
                        </div>
                    </div>
                </div>

                <div class="application-detail__section">
                    <h4 class="application-detail__section-title">
                        <i class="fa-solid fa-file-lines"></i>
                        Application Status
                    </h4>
                    <div class="application-detail__status-info">
                        <p class="application-detail__status-message" id="modal-status-message">
                            Your application is currently being reviewed by the hiring team. We will notify you once there's an update.
                        </p>
                    </div>
                </div>
            </div>

            <div class="application-detail-modal__footer">
                <a href="#" class="application-detail-modal__btn application-detail-modal__btn--primary" id="modal-view-job-link" target="_blank">
                    <i class="fa-solid fa-external-link"></i>
                    View Job Posting
                </a>
                <button type="button" class="application-detail-modal__btn application-detail-modal__btn--secondary" data-application-modal-close>
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script nonce="{{ $cspNonce ?? '' }}">
    document.addEventListener('DOMContentLoaded', function() {
        // Update counts dynamically (for when data is loaded from backend)
        const applicationsList = document.getElementById('applicationsList');
        const applicationsCount = document.getElementById('applicationsCount');
        const applicationsEmpty = document.getElementById('applicationsEmpty');

        // Check if lists are empty and show/hide empty states
        if (applicationsList) {
            const applicationItems = applicationsList.querySelectorAll('.history-item--application');
            if (applicationItems.length === 0) {
                applicationsList.style.display = 'none';
                if (applicationsEmpty) applicationsEmpty.style.display = 'block';
            } else {
                if (applicationsCount) applicationsCount.textContent = applicationItems.length;
            }
        }

        // Application Detail Modal
        const modal = document.getElementById('applicationDetailModal');
        const openButtons = document.querySelectorAll('[data-application-modal-open]');
        const closeButtons = document.querySelectorAll('[data-application-modal-close]');
        const backdrop = modal?.querySelector('.application-detail-modal__backdrop');

        // Application data (in real app, this would come from backend)
        const applicationsData = {
            1: {
                jobTitle: 'Senior Data Analyst',
                company: 'Anagata Executive',
                companyLogo: '/assets/hero-sec.png',
                companyLogoPlaceholder: 'AE',
                status: 'pending',
                statusText: 'Pending',
                statusIcon: 'fa-clock',
                statusMessage: 'Your application is currently being reviewed by the hiring team. We will notify you once there\'s an update.',
                appliedDate: 'January 15, 2024',
                location: 'Jakarta, Indonesia',
                jobLink: '{{ route("job.detail", ["id" => 1]) }}'
            },
            2: {
                jobTitle: 'Brand Representative',
                company: 'Indomobil AION',
                companyLogo: '/assets/hero-sec.png',
                companyLogoPlaceholder: 'ID',
                status: 'review',
                statusText: 'Under Review',
                statusIcon: 'fa-eye',
                statusMessage: 'Your application has been reviewed and is currently in the selection process. We will contact you soon.',
                appliedDate: 'January 10, 2024',
                location: 'Jakarta Timur, Indonesia',
                jobLink: '{{ route("job.detail", ["id" => 2]) }}'
            },
            3: {
                jobTitle: 'Full Stack Developer',
                company: 'Tech Company',
                companyLogo: '/assets/hero-sec.png',
                companyLogoPlaceholder: 'TC',
                status: 'accepted',
                statusText: 'Accepted',
                statusIcon: 'fa-check-circle',
                statusMessage: 'Congratulations! Your application has been accepted. The company will contact you for the next steps.',
                appliedDate: 'December 28, 2023',
                location: 'Remote',
                jobLink: '{{ route("job.detail", ["id" => 3]) }}'
            }
        };

        function openModal(applicationId) {
            const data = applicationsData[applicationId];
            if (!data || !modal) return;

            // Update modal content
            document.getElementById('modal-job-title').textContent = data.jobTitle;
            document.getElementById('modal-company-name').textContent = data.company;
            document.getElementById('modal-position').textContent = data.jobTitle;
            document.getElementById('modal-applied-date').textContent = data.appliedDate;
            document.getElementById('modal-location').textContent = data.location;
            document.getElementById('modal-status-message').textContent = data.statusMessage;
            document.getElementById('modal-view-job-link').href = data.jobLink;

            // Update company logo
            const logoImg = document.getElementById('modal-company-logo');
            const logoPlaceholder = document.getElementById('modal-company-logo-placeholder');
            if (logoImg && logoPlaceholder) {
                logoImg.src = data.companyLogo;
                logoImg.style.display = 'block';
                logoPlaceholder.style.display = 'none';
                logoPlaceholder.textContent = data.companyLogoPlaceholder;
            }

            // Update company name in header
            const companySpan = document.querySelector('#modal-company span');
            if (companySpan) {
                companySpan.textContent = data.company;
            }

            // Update status
            const statusEl = document.getElementById('modal-status');
            if (statusEl) {
                statusEl.className = `application-detail__status application-detail__status--${data.status}`;
                statusEl.innerHTML = `<i class="fa-solid ${data.statusIcon}"></i><span>${data.statusText}</span>`;
            }

            // Show modal
            modal.hidden = false;
            modal.setAttribute('aria-hidden', 'false');
            modal.classList.add('is-active');
            document.body.classList.add('modal-open');

            // Focus on modal dialog
            const dialog = modal.querySelector('.application-detail-modal__dialog');
            if (dialog) {
                setTimeout(() => {
                    dialog.focus();
                }, 100);
            }
        }

        function closeModal() {
            if (!modal) return;
            modal.classList.remove('is-active');
            document.body.classList.remove('modal-open');
            setTimeout(() => {
                modal.hidden = true;
                modal.setAttribute('aria-hidden', 'true');
            }, 300);
        }

        // Open modal handlers
        openButtons.forEach(button => {
            button.addEventListener('click', function() {
                const applicationId = parseInt(this.getAttribute('data-application-id'));
                openModal(applicationId);
            });
        });

        // Close modal handlers
        closeButtons.forEach(button => {
            button.addEventListener('click', closeModal);
        });

        // Close on backdrop click
        if (backdrop) {
            backdrop.addEventListener('click', function(e) {
                if (e.target === backdrop) {
                    closeModal();
                }
            });
        }

        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.classList.contains('is-active')) {
                closeModal();
            }
        });
    });
</script>
@endpush
@endsection

