@extends('layouts.app')

@section('title', 'History - Anagata Executive')
@section('body_class', 'page history-page')

@section('content')
<div class="history-container">
    <div class="history-card">
        {{-- Header Section --}}
        <div class="history-header">
            <h1 class="history-title">{{ __('app.history.title') }}</h1>
            <p class="history-subtitle">{{ __('app.history.subtitle') }}</p>
        </div>

        {{-- Success Message Alert --}}
        @if(session('application_success'))
        <div class="history-alert history-alert--success" id="historySuccessAlert">
            <div class="history-alert__icon">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div class="history-alert__content">
                <h3 class="history-alert__title">{{ __('app.history.application_submitted') }}</h3>
                <p class="history-alert__message">{{ session('status', __('app.history.application_submitted')) }}</p>
            </div>
            <button type="button" class="history-alert__close" aria-label="Close alert" onclick="document.getElementById('historySuccessAlert').style.display='none'">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        @endif

        @if(session('status') && !session('application_success'))
        <div class="history-alert history-alert--info">
            <div class="history-alert__icon">
                <i class="fa-solid fa-info-circle"></i>
            </div>
            <div class="history-alert__content">
                <p class="history-alert__message">{{ session('status') }}</p>
            </div>
        </div>
        @endif

        <div class="history-content">
            {{-- My Applications Section --}}
            <div class="history-section">
                <div class="history-section-header">
                    <h2 class="history-section-title">
                        <i class="fa-solid fa-file-lines history-section-icon"></i>
                        {{ __('app.history.my_applications') }}
                    </h2>
                    <span class="history-section-count" id="applicationsCount">{{ count($applications ?? []) }}</span>
                </div>

                <div class="history-list" id="applicationsList">
                    @forelse($applications ?? [] as $index => $application)
                    <div class="history-item history-item--application" data-aos="fade-up" data-aos-delay="{{ $index * 50 }}">
                        <div class="history-item__header">
                            <div class="history-item__logo">
                                <img src="{{ $application['companyLogo'] }}" alt="{{ $application['company'] }} Logo" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="history-item__logo-placeholder" style="display: none;">{{ $application['companyLogoPlaceholder'] }}</div>
                            </div>
                            <div class="history-item__status history-item__status--{{ $application['status'] }}">
                                <i class="fa-solid {{ $application['statusIcon'] }}"></i>
                                <span>{{ $application['statusText'] }}</span>
                            </div>
                        </div>
                        <div class="history-item__body">
                            <h3 class="history-item__title">{{ $application['jobTitle'] }}</h3>
                            <div class="history-item__company">
                                <i class="fa-solid fa-building"></i>
                                <span>{{ $application['company'] }}</span>
                            </div>
                            <div class="history-item__meta">
                                <div class="history-item__meta-item">
                                    <i class="fa-solid fa-calendar"></i>
                                    <span>{{ __('app.history.applied_on') }} {{ $application['appliedDate'] }}</span>
                                </div>
                                <div class="history-item__meta-item">
                                    <i class="fa-solid fa-location-dot"></i>
                                    <span>{{ $application['location'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="history-item__footer">
                            <button type="button" class="history-item__link" data-application-modal-open data-application-id="{{ $application['id'] }}">
                                {{ __('app.history.view_details') }}
                                <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                    @empty
                    @endforelse
                </div>

                {{-- Empty State for Applications --}}
                @if(empty($applications) || count($applications) === 0)
                <div class="history-empty" id="applicationsEmpty" style="display: block;">
                    <div class="history-empty__icon">
                        <i class="fa-solid fa-file-lines"></i>
                    </div>
                    <h3 class="history-empty__title">{{ __('app.history.no_applications') }}</h3>
                    <p class="history-empty__message">{{ __('app.history.no_applications_message') }}</p>
                    <a href="{{ route('jobs') }}" class="history-empty__action cta-primary cta-primary--brand">
                        {{ __('app.history.browse_jobs') }}
                    </a>
                </div>
                @else
                <div class="history-empty" id="applicationsEmpty" style="display: none;">
                    <div class="history-empty__icon">
                        <i class="fa-solid fa-file-lines"></i>
                    </div>
                    <h3 class="history-empty__title">{{ __('app.history.no_applications') }}</h3>
                    <p class="history-empty__message">{{ __('app.history.no_applications_message') }}</p>
                    <a href="{{ route('jobs') }}" class="history-empty__action cta-primary cta-primary--brand">
                        {{ __('app.history.browse_jobs') }}
                    </a>
                </div>
                @endif
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
                <h2 class="application-detail-modal__title" id="application-modal-title">{{ __('app.history.application_details') }}</h2>
                <button type="button" class="application-detail-modal__close" data-application-modal-close aria-label="{{ __('app.history.close') }}">
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
                        {{ __('app.history.application_information') }}
                    </h4>
                    <div class="application-detail__info-grid">
                        <div class="application-detail__info-item">
                            <div class="application-detail__info-label">
                                <i class="fa-solid fa-calendar"></i>
                                <span>{{ __('app.history.applied_date') }}</span>
                            </div>
                            <div class="application-detail__info-value" id="modal-applied-date">January 15, 2024</div>
                        </div>
                        <div class="application-detail__info-item">
                            <div class="application-detail__info-label">
                                <i class="fa-solid fa-location-dot"></i>
                                <span>{{ __('app.history.location') }}</span>
                            </div>
                            <div class="application-detail__info-value" id="modal-location">Jakarta, Indonesia</div>
                        </div>
                        <div class="application-detail__info-item">
                            <div class="application-detail__info-label">
                                <i class="fa-solid fa-briefcase"></i>
                                <span>{{ __('app.history.position') }}</span>
                            </div>
                            <div class="application-detail__info-value" id="modal-position">Senior Data Analyst</div>
                        </div>
                        <div class="application-detail__info-item">
                            <div class="application-detail__info-label">
                                <i class="fa-solid fa-building"></i>
                                <span>{{ __('app.history.company') }}</span>
                            </div>
                            <div class="application-detail__info-value" id="modal-company-name">Anagata Executive</div>
                        </div>
                    </div>
                </div>

                <div class="application-detail__section">
                    <h4 class="application-detail__section-title">
                        <i class="fa-solid fa-file-lines"></i>
                        {{ __('app.history.application_status') }}
                    </h4>
                    <div class="application-detail__status-info">
                        <p class="application-detail__status-message" id="modal-status-message">
                            {{ __('app.history.status_message') }}
                        </p>
                    </div>
                </div>

                {{-- Recruiter Notes Section --}}
                {{-- Note: This section is NOT bilingual because it's data from recruiters --}}
                <div class="application-detail__section" id="modal-notes-section" style="display: none;">
                    <h4 class="application-detail__section-title">
                        <i class="fa-solid fa-comment-dots"></i>
                        {{ __('app.history.notes_from_recruiter') }}
                    </h4>
                    <div class="application-detail__notes-info">
                        <div class="p-3 bg-light rounded" id="modal-notes-content">
                            <!-- Notes will be populated here -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="application-detail-modal__footer">
                <a href="#" class="application-detail-modal__btn application-detail-modal__btn--primary" id="modal-view-job-link" target="_blank">
                    <i class="fa-solid fa-external-link"></i>
                    {{ __('app.history.view_job_posting') }}
                </a>
                <button type="button" class="application-detail-modal__btn application-detail-modal__btn--secondary" data-application-modal-close>
                    {{ __('app.history.close') }}
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

        // Application data from backend
        const applicationsData = @json(collect($applications ?? [])->keyBy('id')->toArray());

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

            // Update notes section
            const notesSection = document.getElementById('modal-notes-section');
            const notesContent = document.getElementById('modal-notes-content');
            if (data.notes && data.notes.trim() !== '') {
                if (notesSection) notesSection.style.display = 'block';
                if (notesContent) {
                    // Convert line breaks to <br> tags
                    notesContent.innerHTML = data.notes.replace(/\n/g, '<br>');
                }
            } else {
                if (notesSection) notesSection.style.display = 'none';
                if (notesContent) notesContent.innerHTML = '';
            }

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

