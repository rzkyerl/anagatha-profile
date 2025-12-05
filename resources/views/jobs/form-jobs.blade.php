@extends('layouts.app')

@section('title', 'Job Application Form - Anagata Executive')
@section('body_class', 'page job-application-page')

@section('content')

<div class="job-application-container">
    <div class="job-application-card">
        {{-- Header Section --}}
        <div class="job-application-header">
            <div class="job-application-logo">
                <img src="/assets/hero-sec.png" alt="Anagata Executive Logo" />
            </div>
            <h1 class="job-application-title">{{ __('app.job_application.title') }}</h1>
            @if(isset($job) && isset($job['title']))
                <div class="job-application-job-info">
                    <p class="job-application-job-title">{{ __('app.job_application.applying_for') }} <strong>{{ $job['title'] }}</strong></p>
                    @if(isset($job['company']))
                        <p class="job-application-job-company">{{ __('app.job_application.at') }} {{ $job['company'] }}</p>
                    @endif
                </div>
            @endif
            <p class="job-application-subtitle">{{ __('app.job_application.subtitle') }}</p>
        </div>

        {{-- Already Applied Warning --}}
        @if(isset($hasApplied) && $hasApplied)
            <div class="job-application-already-applied" style="padding: 20px; margin: 20px; background-color: #fef3c7; border: 2px solid #f59e0b; border-radius: 8px; text-align: center;">
                <div style="font-size: 48px; color: #f59e0b; margin-bottom: 10px;">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <h2 style="color: #92400e; margin-bottom: 10px; font-size: 24px;">{{ __('app.job_application.already_applied') }}</h2>
                <p style="color: #78350f; margin-bottom: 15px; font-size: 16px;">
                    {{ __('app.job_application.already_applied_message') }}
                </p>
                @if(isset($existingApplication))
                    <p style="color: #78350f; margin-bottom: 15px; font-size: 14px;">
                        <strong>{{ __('app.job_application.application_status_label') }}</strong> 
                        <span style="text-transform: capitalize;">{{ $existingApplication->status }}</span>
                    </p>
                    <p style="color: #78350f; margin-bottom: 20px; font-size: 14px;">
                        <strong>{{ __('app.job_application.applied_date_label') }}</strong> 
                        {{ $existingApplication->applied_at ? \Carbon\Carbon::parse($existingApplication->applied_at)->format('F d, Y') : 'N/A' }}
                    </p>
                @endif
                <div style="display: flex; gap: 10px; justify-content: center; flex-wrap: wrap;">
                    <a href="{{ route('history') }}" style="display: inline-block; padding: 12px 24px; background-color: #f59e0b; color: white; text-decoration: none; border-radius: 6px; font-weight: 600; transition: background-color 0.3s;">
                        <i class="fa-solid fa-history"></i> {{ __('app.job_application.view_application_history') }}
                    </a>
                    <a href="{{ route('jobs') }}" style="display: inline-block; padding: 12px 24px; background-color: #6b7280; color: white; text-decoration: none; border-radius: 6px; font-weight: 600; transition: background-color 0.3s;">
                        <i class="fa-solid fa-briefcase"></i> {{ __('app.job_application.browse_other_jobs') }}
                    </a>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('job.application.store') }}" class="job-application-form" id="jobApplicationForm" enctype="multipart/form-data" @if(isset($hasApplied) && $hasApplied) style="pointer-events: none; opacity: 0.6;" @endif>
            @csrf
            
            {{-- Hidden field for job listing ID (required) --}}
            @if(isset($job) && isset($job['id']))
                <input type="hidden" name="job_listing_id" value="{{ $job['id'] }}" required>
            @elseif(request()->has('job_id'))
                <input type="hidden" name="job_listing_id" value="{{ request()->input('job_id') }}" required>
            @else
                {{-- If no job ID, redirect will happen in controller --}}
                <input type="hidden" name="job_listing_id" value="" required>
            @endif

            {{-- Personal Information Section --}}
            <div class="form-section">
                <h2 class="form-section-title">
                    <i class="fa-solid fa-user form-section-icon"></i>
                    {{ __('app.job_application.personal_information') }}
                </h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="full_name" class="form-label">
                            {{ __('app.job_application.full_name') }} <span class="required">*</span>
                        </label>
                        <div class="form-input-wrapper">
                            <i class="form-input-icon fa-solid fa-user"></i>
                            <input 
                                type="text" 
                                id="full_name" 
                                name="full_name" 
                                class="form-input @error('full_name') is-invalid @enderror" 
                                placeholder="{{ __('app.job_application.full_name_placeholder') }}"
                                value="{{ old('full_name') }}"
                                required 
                                autofocus
                            />
                        </div>
                        @error('full_name')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">
                            {{ __('app.job_application.email_address') }} <span class="required">*</span>
                        </label>
                        <div class="form-input-wrapper">
                            <i class="form-input-icon fa-solid fa-envelope"></i>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                class="form-input @error('email') is-invalid @enderror" 
                                placeholder="{{ __('app.job_application.email_placeholder') }}"
                                value="{{ old('email') }}"
                                required
                            />
                        </div>
                        @error('email')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone" class="form-label">
                            {{ __('app.job_application.phone_number') }} <span class="required">*</span>
                        </label>
                        <div class="form-input-wrapper">
                            <i class="form-input-icon fa-solid fa-phone"></i>
                            <input 
                                type="tel" 
                                id="phone" 
                                name="phone" 
                                class="form-input @error('phone') is-invalid @enderror" 
                                placeholder="{{ __('app.job_application.phone_placeholder') }}"
                                value="{{ old('phone') }}"
                                required
                            />
                        </div>
                        @error('phone')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="address" class="form-label">
                            {{ __('app.job_application.address_location') }} <span class="required">*</span>
                        </label>
                        <div class="form-input-wrapper">
                            <i class="form-input-icon fa-solid fa-location-dot"></i>
                            <input 
                                type="text" 
                                id="address" 
                                name="address" 
                                class="form-input @error('address') is-invalid @enderror" 
                                placeholder="{{ __('app.job_application.address_placeholder') }}"
                                value="{{ old('address') }}"
                                required
                            />
                        </div>
                        @error('address')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Professional Information Section --}}
            <div class="form-section">
                <h2 class="form-section-title">
                    <i class="fa-solid fa-briefcase form-section-icon"></i>
                    {{ __('app.job_application.professional_information') }}
                </h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="current_salary" class="form-label">
                            {{ __('app.job_application.current_salary') }}
                        </label>
                        <div class="form-input-wrapper">
                            <i class="form-input-icon fa-solid fa-dollar-sign"></i>
                            <input 
                                type="text" 
                                id="current_salary" 
                                name="current_salary" 
                                class="form-input @error('current_salary') is-invalid @enderror" 
                                placeholder="{{ __('app.job_application.current_salary_placeholder') }}"
                                value="{{ old('current_salary') }}"
                            />
                        </div>
                        @error('current_salary')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="expected_salary" class="form-label">
                            {{ __('app.job_application.expected_salary') }} <span class="required">*</span>
                        </label>
                        <div class="form-input-wrapper">
                            <i class="form-input-icon fa-solid fa-dollar-sign"></i>
                            <input 
                                type="text" 
                                id="expected_salary" 
                                name="expected_salary" 
                                class="form-input @error('expected_salary') is-invalid @enderror" 
                                placeholder="{{ __('app.job_application.expected_salary_placeholder') }}"
                                value="{{ old('expected_salary') }}"
                                required
                            />
                        </div>
                        @error('expected_salary')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="availability" class="form-label">
                            {{ __('app.job_application.availability') }} <span class="required">*</span>
                        </label>
                        <div class="form-input-wrapper">
                            <i class="form-input-icon fa-solid fa-calendar-check"></i>
                            <select 
                                id="availability" 
                                name="availability" 
                                class="form-input @error('availability') is-invalid @enderror" 
                                required
                            >
                                <option value="">{{ __('app.job_application.select_availability') }}</option>
                                <option value="immediate" {{ old('availability') == 'immediate' ? 'selected' : '' }}>{{ __('app.job_application.immediate') }}</option>
                                <option value="2-weeks" {{ old('availability') == '2-weeks' ? 'selected' : '' }}>{{ __('app.job_application.within_2_weeks') }}</option>
                                <option value="1-month" {{ old('availability') == '1-month' ? 'selected' : '' }}>{{ __('app.job_application.within_1_month') }}</option>
                                <option value="2-months" {{ old('availability') == '2-months' ? 'selected' : '' }}>{{ __('app.job_application.within_2_months') }}</option>
                                <option value="negotiable" {{ old('availability') == 'negotiable' ? 'selected' : '' }}>{{ __('app.job_application.negotiable') }}</option>
                            </select>
                        </div>
                        @error('availability')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="relocation" class="form-label">
                            {{ __('app.job_application.relocation_readiness') }} <span class="required">*</span>
                        </label>
                        <div class="form-input-wrapper">
                            <i class="form-input-icon fa-solid fa-route"></i>
                            <select 
                                id="relocation" 
                                name="relocation" 
                                class="form-input @error('relocation') is-invalid @enderror" 
                                required
                            >
                                <option value="">{{ __('app.job_application.select_option') }}</option>
                                <option value="Yes" {{ old('relocation') == 'Yes' ? 'selected' : '' }}>{{ __('app.job_application.yes') }}</option>
                                <option value="No" {{ old('relocation') == 'No' ? 'selected' : '' }}>{{ __('app.job_application.no') }}</option>
                                <option value="Other" {{ old('relocation') == 'Other' ? 'selected' : '' }}>{{ __('app.job_application.other') }}</option>
                            </select>
                        </div>
                        @error('relocation')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Other Relocation Field (shown when "Other" is selected) --}}
                    <div class="form-group relocation-other-group" id="relocation_other_container" style="display: {{ old('relocation') == 'Other' ? 'block' : 'none' }};">
                        <label for="relocation_other" class="form-label">
                            {{ __('app.job_application.other_relocation_option') }} <span class="required">*</span>
                        </label>
                        <div class="form-input-wrapper">
                            <i class="form-input-icon fa-solid fa-route"></i>
                            <input 
                                type="text" 
                                id="relocation_other" 
                                name="relocation_other" 
                                class="form-input @error('relocation_other') is-invalid @enderror" 
                                placeholder="{{ __('app.job_application.other_relocation_placeholder') }}"
                                value="{{ old('relocation_other') }}"
                            />
                        </div>
                        @error('relocation_other')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Professional Profiles Section --}}
            <div class="form-section">
                <h2 class="form-section-title">
                    <i class="fa-solid fa-link form-section-icon"></i>
                    {{ __('app.job_application.professional_profiles') }}
                </h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="linkedin" class="form-label">
                            {{ __('app.job_application.linkedin_profile') }}
                        </label>
                        <div class="form-input-wrapper">
                            <i class="form-input-icon fa-brands fa-linkedin"></i>
                            <input 
                                type="url" 
                                id="linkedin" 
                                name="linkedin" 
                                class="form-input @error('linkedin') is-invalid @enderror" 
                                placeholder="{{ __('app.job_application.linkedin_placeholder') }}"
                                value="{{ old('linkedin') }}"
                            />
                        </div>
                        @error('linkedin')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="github" class="form-label">
                            {{ __('app.job_application.github_profile') }}
                        </label>
                        <div class="form-input-wrapper">
                            <i class="form-input-icon fa-brands fa-github"></i>
                            <input 
                                type="url" 
                                id="github" 
                                name="github" 
                                class="form-input @error('github') is-invalid @enderror" 
                                placeholder="{{ __('app.job_application.github_placeholder') }}"
                                value="{{ old('github') }}"
                            />
                        </div>
                        @error('github')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="social_media" class="form-label">
                            {{ __('app.job_application.social_media') }}
                        </label>
                        <div class="form-input-wrapper">
                            <i class="form-input-icon fa-solid fa-share-nodes"></i>
                            <input 
                                type="url" 
                                id="social_media" 
                                name="social_media" 
                                class="form-input @error('social_media') is-invalid @enderror" 
                                placeholder="{{ __('app.job_application.social_media_placeholder') }}"
                                value="{{ old('social_media') }}"
                            />
                        </div>
                        @error('social_media')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Documents Section --}}
            <div class="form-section">
                <h2 class="form-section-title">
                    <i class="fa-solid fa-file-upload form-section-icon"></i>
                    {{ __('app.job_application.documents') }}
                </h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="cv" class="form-label">
                            {{ __('app.job_application.cv_resume') }} <span class="required">*</span>
                        </label>
                        <div class="file-upload-wrapper">
                            <input 
                                type="file" 
                                id="cv" 
                                name="cv" 
                                class="file-upload-input @error('cv') is-invalid @enderror" 
                                accept=".pdf,.doc,.docx"
                                required
                            />
                            <label for="cv" class="file-upload-label">
                                <i class="fa-solid fa-cloud-arrow-up file-upload-icon"></i>
                                <span class="file-upload-text">{{ __('app.job_application.choose_file') }}</span>
                                <span class="file-upload-hint">{{ __('app.job_application.file_hint_cv') }}</span>
                            </label>
                            <div class="file-upload-preview"></div>
                        </div>
                        @error('cv')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="portfolio_file" class="form-label">
                            {{ __('app.job_application.portfolio_file') }}
                        </label>
                        <div class="file-upload-wrapper">
                            <input 
                                type="file" 
                                id="portfolio_file" 
                                name="portfolio_file" 
                                class="file-upload-input @error('portfolio_file') is-invalid @enderror" 
                                accept=".pdf,.zip,.rar"
                            />
                            <label for="portfolio_file" class="file-upload-label">
                                <i class="fa-solid fa-cloud-arrow-up file-upload-icon"></i>
                                <span class="file-upload-text">{{ __('app.job_application.choose_file') }}</span>
                                <span class="file-upload-hint">{{ __('app.job_application.file_hint_portfolio') }}</span>
                            </label>
                            <div class="file-upload-preview"></div>
                        </div>
                        @error('portfolio_file')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group form-group--full">
                        <label for="cover_letter" class="form-label">
                            {{ __('app.job_application.cover_letter') }}
                        </label>
                        <div class="form-input-wrapper form-input-wrapper--textarea">
                            <i class="form-input-icon fa-solid fa-file-lines"></i>
                            <textarea 
                                id="cover_letter" 
                                name="cover_letter" 
                                class="form-input form-input--textarea @error('cover_letter') is-invalid @enderror" 
                                placeholder="{{ __('app.job_application.cover_letter_placeholder') }}"
                                rows="6"
                            >{{ old('cover_letter') }}</textarea>
                        </div>
                        @error('cover_letter')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Additional Information Section --}}
            <div class="form-section">
                <h2 class="form-section-title">
                    <i class="fa-solid fa-comment-dots form-section-icon"></i>
                    {{ __('app.job_application.additional_information') }}
                </h2>
                <div class="form-grid">
                    <div class="form-group form-group--full">
                        <label for="reason_applying" class="form-label">
                            {{ __('app.job_application.reason_applying') }} <span class="required">*</span>
                        </label>
                        <div class="form-input-wrapper form-input-wrapper--textarea">
                            <i class="form-input-icon fa-solid fa-question-circle"></i>
                            <textarea 
                                id="reason_applying" 
                                name="reason_applying" 
                                class="form-input form-input--textarea @error('reason_applying') is-invalid @enderror" 
                                placeholder="{{ __('app.job_application.reason_applying_placeholder') }}"
                                rows="4"
                                required
                            >{{ old('reason_applying') }}</textarea>
                        </div>
                        @error('reason_applying')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group form-group--full">
                        <label for="relevant_experience" class="form-label">
                            {{ __('app.job_application.relevant_experience') }}
                        </label>
                        <div class="form-input-wrapper form-input-wrapper--textarea">
                            <i class="form-input-icon fa-solid fa-briefcase"></i>
                            <textarea 
                                id="relevant_experience" 
                                name="relevant_experience" 
                                class="form-input form-input--textarea @error('relevant_experience') is-invalid @enderror" 
                                placeholder="{{ __('app.job_application.relevant_experience_placeholder') }}"
                                rows="5"
                            >{{ old('relevant_experience') }}</textarea>
                        </div>
                        @error('relevant_experience')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Submit Button --}}
            <div class="form-actions">
                @if(isset($hasApplied) && $hasApplied)
                    <button type="button" class="job-application-submit-btn" id="submitBtn" disabled style="opacity: 0.5; cursor: not-allowed;">
                        <i class="fa-solid fa-check-circle"></i>
                        {{ __('app.job_application.already_applied_btn') }}
                    </button>
                    <p class="form-note" style="color: #f59e0b;">
                        <i class="fa-solid fa-info-circle"></i>
                        {{ __('app.job_application.already_applied_message') }}
                    </p>
                @else
                <button type="submit" class="job-application-submit-btn" id="submitBtn">
                    <i class="fa-solid fa-paper-plane"></i>
                    {{ __('app.job_application.submit_application') }}
                </button>
                <p class="form-note">
                    <i class="fa-solid fa-info-circle"></i>
                    {{ __('app.job_application.form_note') }} <span class="required">*</span> {{ __('app.job_application.are_required') }}
                </p>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Validation Error Modal --}}
<div class="job-application-error-modal" id="jobApplicationErrorModal" hidden aria-hidden="true">
    <div class="job-application-error-modal__backdrop" data-error-modal-close></div>
    <div class="job-application-error-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="error-modal-title" tabindex="-1">
        <div class="job-application-error-modal__content">
            <div class="job-application-error-modal__header">
                <div class="job-application-error-modal__icon">
                    <i class="fa-solid fa-circle-exclamation"></i>
                </div>
                <h2 class="job-application-error-modal__title" id="error-modal-title">{{ __('app.job_application.validation_error') }}</h2>
                <button type="button" class="job-application-error-modal__close" aria-label="Close modal" data-error-modal-close>
                    <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                </button>
            </div>
            <div class="job-application-error-modal__body">
                <p class="job-application-error-modal__message">{{ __('app.job_application.fill_required_fields') }}</p>
                <ul class="job-application-error-modal__errors" id="errorModalErrorsList">
                    <!-- Errors will be populated here -->
                    @if($errors->any())
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    @endif
                </ul>
            </div>
            <div class="job-application-error-modal__actions">
                <button type="button" class="job-application-error-modal__btn job-application-error-modal__btn--primary" data-error-modal-close>
                    {{ __('app.job_application.ok_fix') }}
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Success Modal --}}
<div class="job-application-success-modal" id="jobApplicationSuccessModal" hidden aria-hidden="true">
    <div class="job-application-success-modal__backdrop" data-success-modal-close></div>
    <div class="job-application-success-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="success-modal-title" tabindex="-1">
        <div class="job-application-success-modal__content">
            <div class="job-application-success-modal__icon">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <h2 class="job-application-success-modal__title" id="success-modal-title">{{ __('app.job_application.application_submitted_success') }}</h2>
            <p class="job-application-success-modal__message">
                {{ __('app.job_application.application_submitted_message') }}
            </p>
            <p class="job-application-success-modal__info">
                <i class="fa-solid fa-info-circle"></i>
                {{ __('app.job_application.track_status_info') }}
            </p>
            <div class="job-application-success-modal__actions">
                <a href="{{ route('history') }}" class="job-application-success-modal__btn job-application-success-modal__btn--primary">
                    <i class="fa-solid fa-history"></i>
                    {{ __('app.job_application.view_history') }}
                </a>
                <button type="button" class="job-application-success-modal__btn job-application-success-modal__btn--secondary" data-success-modal-close>
                    <i class="fa-solid fa-xmark"></i>
                    {{ __('app.history.close') }}
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script nonce="{{ $cspNonce ?? '' }}">
    // Translation strings for JavaScript
    const translations = {
        chooseFile: @json(__('app.job_application.choose_file')),
        fileHintCv: @json(__('app.job_application.file_hint_cv')),
        fileHintPortfolio: @json(__('app.job_application.file_hint_portfolio')),
        fileUploadedSuccess: @json(__('app.job_application.file_uploaded_success')),
    };

    document.addEventListener('DOMContentLoaded', function() {
        // Check if application was successful (from session or URL parameter) - check early
        const urlParams = new URLSearchParams(window.location.search);
        const urlSuccess = urlParams.get('success') === '1';
        const sessionSuccess = @json(session('application_success', false) || session('show_success_modal', false));
        const applicationSuccess = sessionSuccess || urlSuccess;
        
        console.log('Application success check:', {
            urlSuccess: urlSuccess,
            sessionSuccess: sessionSuccess,
            applicationSuccess: applicationSuccess,
            urlParams: window.location.search
        });
        
        // Check if user has already applied
        const hasApplied = @json(isset($hasApplied) && $hasApplied);
        
        if (hasApplied) {
            // Disable all form inputs
            const form = document.getElementById('jobApplicationForm');
            if (form) {
                const inputs = form.querySelectorAll('input, textarea, select, button[type="submit"]');
                inputs.forEach(input => {
                    input.disabled = true;
                });
            }
            
            // Prevent form submission
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    alert('You have already applied for this job position. Please check your application history.');
                    return false;
                });
            }
        }
        
        // Relocation "Other" field toggle with smooth animation
        const relocationSelect = document.getElementById('relocation');
        const relocationOtherContainer = document.getElementById('relocation_other_container');
        const relocationOtherInput = document.getElementById('relocation_other');

        function toggleRelocationOther() {
            if (!relocationOtherContainer) return;
            
            if (relocationSelect && relocationSelect.value === 'Other') {
                // Show with smooth animation
                relocationOtherContainer.style.display = 'flex';
                relocationOtherContainer.style.flexDirection = 'column';
                relocationOtherContainer.style.opacity = '0';
                relocationOtherContainer.style.maxHeight = '0';
                relocationOtherContainer.style.overflow = 'hidden';
                relocationOtherContainer.style.marginTop = '0';
                relocationOtherContainer.style.marginBottom = '0';
                relocationOtherInput.setAttribute('required', 'required');
                
                // Trigger reflow for animation
                void relocationOtherContainer.offsetHeight;
                
                // Animate in
                requestAnimationFrame(function() {
                    relocationOtherContainer.style.transition = 'opacity 0.3s ease, max-height 0.3s ease, margin 0.3s ease';
                    relocationOtherContainer.style.opacity = '1';
                    relocationOtherContainer.style.maxHeight = '200px';
                });
            } else {
                // Hide with smooth animation
                if (relocationOtherContainer.style.display !== 'none' && relocationOtherContainer.style.display !== '') {
                    relocationOtherContainer.style.transition = 'opacity 0.3s ease, max-height 0.3s ease, margin 0.3s ease';
                    relocationOtherContainer.style.opacity = '0';
                    relocationOtherContainer.style.maxHeight = '0';
                    relocationOtherContainer.style.marginTop = '0';
                    relocationOtherContainer.style.marginBottom = '0';
                    
                    setTimeout(function() {
                        relocationOtherContainer.style.display = 'none';
                        relocationOtherInput.removeAttribute('required');
                        relocationOtherInput.value = ''; // Clear value when hidden
                    }, 300);
                }
            }
        }

        if (relocationSelect) {
            relocationSelect.addEventListener('change', toggleRelocationOther);
            toggleRelocationOther(); // Initial call to set state based on current value
        }

        // Helper function for URL validation
        function isValidUrl(string) {
            try {
                new URL(string);
                return true;
            } catch (_) {
                return false;
            }
        }

        // Function to show error modal
        function showErrorModal(errors) {
            const errorModal = document.getElementById('jobApplicationErrorModal');
            const errorsList = document.getElementById('errorModalErrorsList');
            
            if (errorModal && errorsList) {
                // Clear previous errors
                errorsList.innerHTML = '';
                
                // Populate errors list
                errors.forEach(error => {
                    const li = document.createElement('li');
                    li.textContent = error;
                    errorsList.appendChild(li);
                });
                
                // Show modal
                errorModal.hidden = false;
                errorModal.setAttribute('aria-hidden', 'false');
                errorModal.classList.add('is-active');
                document.body.classList.add('modal-open');
                
                // Focus on the modal dialog for accessibility
                const dialog = errorModal.querySelector('.job-application-error-modal__dialog');
                if (dialog) {
                    setTimeout(() => {
                        dialog.focus();
                    }, 100);
                }
            }
        }

        // Function to close error modal
        function closeErrorModal() {
            const errorModal = document.getElementById('jobApplicationErrorModal');
            if (errorModal) {
                errorModal.classList.remove('is-active');
                document.body.classList.remove('modal-open');
                setTimeout(function() {
                    errorModal.hidden = true;
                    errorModal.setAttribute('aria-hidden', 'true');
                }, 300);
            }
        }

        // Function to show success modal
        function showSuccessModal() {
            const successModal = document.getElementById('jobApplicationSuccessModal');
            if (successModal) {
                console.log('Showing success modal'); // Debug log
                successModal.hidden = false;
                successModal.setAttribute('aria-hidden', 'false');
                successModal.classList.add('is-active');
                document.body.classList.add('modal-open');
                
                // Focus on the modal dialog for accessibility
                const dialog = successModal.querySelector('.job-application-success-modal__dialog');
                if (dialog) {
                    setTimeout(() => {
                        dialog.focus();
                    }, 100);
                }
            } else {
                console.error('Success modal element not found!'); // Debug log
            }
        }

        // File upload preview functionality
        const fileInputs = document.querySelectorAll('.file-upload-input');
        
        // Function to get file icon based on type
        function getFileIcon(fileType) {
            const type = fileType.toLowerCase();
            if (type === 'application/pdf') {
                return 'fa-file-pdf';
            } else if (type === 'application/msword' || type === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') {
                return 'fa-file-word';
            } else if (type === 'application/zip' || type === 'application/x-rar-compressed' || type === 'application/vnd.rar') {
                return 'fa-file-zipper';
            }
            return 'fa-file';
        }
        
        // Function to get file type label
        function getFileTypeLabel(fileType, fileName) {
            const type = fileType.toLowerCase();
            const ext = fileName.split('.').pop().toLowerCase();
            
            if (type === 'application/pdf' || ext === 'pdf') {
                return 'PDF Document';
            } else if (type === 'application/msword' || ext === 'doc') {
                return 'Word Document (DOC)';
            } else if (type === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' || ext === 'docx') {
                return 'Word Document (DOCX)';
            } else if (type === 'application/zip' || ext === 'zip') {
                return 'ZIP Archive';
            } else if (type === 'application/x-rar-compressed' || type === 'application/vnd.rar' || ext === 'rar') {
                return 'RAR Archive';
            }
            return 'File';
        }
        
        fileInputs.forEach(input => {
            // Get the wrapper and related elements
            const wrapper = input.closest('.file-upload-wrapper');
            const preview = wrapper ? wrapper.querySelector('.file-upload-preview') : null;
            const label = wrapper ? wrapper.querySelector('.file-upload-label') : null;
            
            if (!wrapper || !preview || !label) return;
            
            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const fileName = file.name;
                    const fileSize = (file.size / 1024 / 1024).toFixed(2);
                    const fileType = file.type || '';
                    const fileIcon = getFileIcon(fileType);
                    const fileTypeLabel = getFileTypeLabel(fileType, fileName);
                    
                    // Update label to show file is uploaded
                    label.classList.add('has-file');
                    
                    // Update icon to show success
                    const uploadIcon = label.querySelector('.file-upload-icon');
                    if (uploadIcon) {
                        uploadIcon.className = 'fa-solid fa-circle-check file-upload-icon';
                    }
                    
                    // Update label text to show success
                    const uploadText = label.querySelector('.file-upload-text');
                    if (uploadText) {
                        uploadText.innerHTML = translations.fileUploadedSuccess || 'File uploaded successfully!';
                    }
                    
                    // Update hint to show filename
                    const uploadHint = label.querySelector('.file-upload-hint');
                    if (uploadHint) {
                        uploadHint.innerHTML = `${fileName} (${fileSize} MB)`;
                        uploadHint.style.color = '#10b981';
                        uploadHint.style.fontWeight = '600';
                    }
                    
                    // Clear previous preview
                    preview.innerHTML = '';
                    
                    // Show preview with file details
                    preview.innerHTML = `
                        <div class="file-preview-item">
                            <div class="file-preview-icon">
                                <i class="fa-solid ${fileIcon}"></i>
                            </div>
                            <div class="file-preview-info">
                                <span class="file-preview-name" title="${fileName}">${fileName}</span>
                                <div class="file-preview-meta">
                                    <span class="file-preview-type">${fileTypeLabel}</span>
                                    <span class="file-preview-separator">•</span>
                                    <span class="file-preview-size">${fileSize} MB</span>
                                </div>
                            </div>
                            <button type="button" class="file-preview-remove" aria-label="Remove file">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    `;
                    
                    // Remove file functionality
                    const removeBtn = preview.querySelector('.file-preview-remove');
                    if (removeBtn) {
                        removeBtn.addEventListener('click', function() {
                            input.value = '';
                            preview.innerHTML = '';
                            label.classList.remove('has-file');
                            
                            // Reset icon
                            const uploadIcon = label.querySelector('.file-upload-icon');
                            if (uploadIcon) {
                                uploadIcon.className = 'fa-solid fa-cloud-arrow-up file-upload-icon';
                            }
                            
                            // Reset label text
                            const uploadText = label.querySelector('.file-upload-text');
                            if (uploadText) {
                                uploadText.innerHTML = translations.chooseFile || 'Choose file or drag it here';
                            }
                            
                            // Reset hint
                            const uploadHint = label.querySelector('.file-upload-hint');
                            if (uploadHint) {
                                const fieldId = input.id;
                                if (fieldId === 'cv') {
                                    uploadHint.innerHTML = translations.fileHintCv || 'PDF, DOC, DOCX (Max 5MB)';
                                } else if (fieldId === 'portfolio_file') {
                                    uploadHint.innerHTML = translations.fileHintPortfolio || 'PDF, ZIP, RAR (Max 10MB)';
                                }
                                uploadHint.style.color = '';
                                uploadHint.style.fontWeight = '';
                            }
                        });
                    }
                } else {
                    preview.innerHTML = '';
                    label.classList.remove('has-file');
                    
                    // Reset icon
                    const uploadIcon = label.querySelector('.file-upload-icon');
                    if (uploadIcon) {
                        uploadIcon.className = 'fa-solid fa-cloud-arrow-up file-upload-icon';
                    }
                    
                    // Reset label text
                    const uploadText = label.querySelector('.file-upload-text');
                    if (uploadText) {
                        uploadText.innerHTML = translations.chooseFile || 'Choose file or drag it here';
                    }
                    
                    // Reset hint
                    const uploadHint = label.querySelector('.file-upload-hint');
                    if (uploadHint) {
                        const fieldId = input.id;
                        if (fieldId === 'cv') {
                            uploadHint.innerHTML = translations.fileHintCv || 'PDF, DOC, DOCX (Max 5MB)';
                        } else if (fieldId === 'portfolio_file') {
                            uploadHint.innerHTML = translations.fileHintPortfolio || 'PDF, ZIP, RAR (Max 10MB)';
                        }
                        uploadHint.style.color = '';
                        uploadHint.style.fontWeight = '';
                    }
                }
            });

            // Drag and drop functionality
            label.addEventListener('dragover', function(e) {
                e.preventDefault();
                label.classList.add('drag-over');
            });

            label.addEventListener('dragleave', function(e) {
                e.preventDefault();
                label.classList.remove('drag-over');
            });

            label.addEventListener('drop', function(e) {
                e.preventDefault();
                label.classList.remove('drag-over');
                
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    input.files = files;
                    input.dispatchEvent(new Event('change'));
                }
            });
        });

        // Form validation and submission (Frontend Testing Mode)
        const form = document.getElementById('jobApplicationForm');
        const submitBtn = document.getElementById('submitBtn');
        
        console.log('Initializing form handler...'); // Debug log
        console.log('Form element:', form); // Debug log
        console.log('Submit button:', submitBtn); // Debug log
        
        if (form) {
            console.log('Form found, adding submit listener'); // Debug log
            
            // Check if user has already applied - if so, prevent all form submissions
            if (hasApplied) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    alert('You have already applied for this job position. Please check your application history.');
                    return false;
                });
                return; // Exit early, don't process form validation
                }
                
            // Handle form submission
            function processFormSubmission(e) {
                console.log('Form submit event triggered'); // Debug log
                
                // Double check - prevent if already applied
                if (hasApplied) {
                    e.preventDefault();
                    alert('You have already applied for this job position. Please check your application history.');
                    return false;
                }
                
                let isValid = true;
                let errors = [];
                
                // Check required text fields
                const requiredFields = form.querySelectorAll('input[required]:not([type="file"]):not([type="url"]):not(#relocation_other), textarea[required], select[required]');
                console.log('Required fields found:', requiredFields.length); // Debug log
                
                requiredFields.forEach(field => {
                    const value = field.value ? field.value.trim() : '';
                    if (!value) {
                        isValid = false;
                        field.classList.add('is-invalid');
                        const wrapper = field.closest('.form-input-wrapper');
                        if (wrapper) {
                            wrapper.classList.add('is-invalid');
                        }
                        // Get label from form-group
                        const formGroup = field.closest('.form-group');
                        const label = formGroup ? formGroup.querySelector('.form-label') : null;
                        const fieldLabel = label ? label.textContent.replace(/\*$/, '').trim() : 'This field';
                        errors.push(`${fieldLabel} is required`);
                    } else {
                        field.classList.remove('is-invalid');
                        const wrapper = field.closest('.form-input-wrapper');
                        if (wrapper) {
                            wrapper.classList.remove('is-invalid');
                        }
                    }
                });
                
                // Check relocation_other if relocation is "Other"
                const relocationSelect = form.querySelector('#relocation');
                const relocationOtherInput = form.querySelector('#relocation_other');
                if (relocationSelect && relocationSelect.value === 'Other') {
                    const relocationOtherValue = relocationOtherInput ? relocationOtherInput.value.trim() : '';
                    if (!relocationOtherValue) {
                        isValid = false;
                        if (relocationOtherInput) {
                            relocationOtherInput.classList.add('is-invalid');
                            const wrapper = relocationOtherInput.closest('.form-input-wrapper');
                            if (wrapper) {
                                wrapper.classList.add('is-invalid');
                            }
                        }
                        errors.push('Other Relocation Option is required');
                    } else {
                        if (relocationOtherInput) {
                            relocationOtherInput.classList.remove('is-invalid');
                            const wrapper = relocationOtherInput.closest('.form-input-wrapper');
                            if (wrapper) {
                                wrapper.classList.remove('is-invalid');
                            }
                        }
                    }
                } else {
                    // Clear validation if relocation is not "Other"
                    if (relocationOtherInput) {
                        relocationOtherInput.classList.remove('is-invalid');
                        const wrapper = relocationOtherInput.closest('.form-input-wrapper');
                        if (wrapper) {
                            wrapper.classList.remove('is-invalid');
                        }
                    }
                }
                
                // Check required URL fields separately
                const requiredUrlFields = form.querySelectorAll('input[type="url"][required]');
                requiredUrlFields.forEach(field => {
                    const value = field.value ? field.value.trim() : '';
                    if (!value) {
                        isValid = false;
                        field.classList.add('is-invalid');
                        const formGroup = field.closest('.form-group');
                        const label = formGroup ? formGroup.querySelector('.form-label') : null;
                        const fieldLabel = label ? label.textContent.replace(/\*$/, '').trim() : 'This field';
                        errors.push(`${fieldLabel} is required`);
                    }
                });

                // Check required file uploads
                const requiredFileInputs = form.querySelectorAll('input[type="file"][required]');
                requiredFileInputs.forEach(input => {
                    if (!input.files || input.files.length === 0) {
                        isValid = false;
                        const labelWrapper = input.closest('.file-upload-wrapper');
                        if (labelWrapper) {
                            const label = labelWrapper.querySelector('.file-upload-label');
                            if (label) {
                                label.classList.add('is-invalid');
                                label.style.borderColor = '#ef4444';
                            }
                        }
                        const formGroup = input.closest('.form-group');
                        const formLabel = formGroup ? formGroup.querySelector('.form-label') : null;
                        const fieldLabel = formLabel ? formLabel.textContent.replace(/\*$/, '').trim() : 'File';
                        errors.push(`${fieldLabel} is required`);
                    } else {
                        const labelWrapper = input.closest('.file-upload-wrapper');
                        if (labelWrapper) {
                            const label = labelWrapper.querySelector('.file-upload-label');
                            if (label) {
                                label.classList.remove('is-invalid');
                                label.style.borderColor = '';
                            }
                        }
                    }
                });

                // Validate email format
                const emailField = form.querySelector('#email');
                if (emailField && emailField.value) {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(emailField.value)) {
                        isValid = false;
                        emailField.classList.add('is-invalid');
                        errors.push('Please enter a valid email address');
                    }
                }

                // Validate URLs
                const urlFields = form.querySelectorAll('input[type="url"]');
                urlFields.forEach(field => {
                    if (field.value && field.value.trim()) {
                        if (!isValidUrl(field.value)) {
                            isValid = false;
                            field.classList.add('is-invalid');
                            const formGroup = field.closest('.form-group');
                            const label = formGroup ? formGroup.querySelector('.form-label') : null;
                            const fieldLabel = label ? label.textContent.replace(/\*$/, '').trim() : 'URL';
                            errors.push(`Please enter a valid ${fieldLabel}`);
                        }
                    }
                });

                if (!isValid) {
                    console.log('Validation errors:', errors); // Debug log
                    showErrorModal(errors);
                    const firstError = form.querySelector('.is-invalid');
                    if (firstError) {
                        setTimeout(() => {
                            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }, 100);
                    }
                    // Re-enable submit button if validation fails
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> Submit Application';
                    }
                    return false;
                }

                console.log('All validations passed, submitting form to server'); // Debug log
                
                // Disable submit button to prevent double submission
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Submitting...';
                }
                
                // Allow form to submit normally to backend
                // Don't prevent default, let Laravel handle the submission
                return true;
            }
            
            // Add submit event listener to form
            form.addEventListener('submit', function(e) {
                // Only prevent default if validation fails
                const isValid = processFormSubmission(e);
                if (!isValid) {
                    e.preventDefault();
                    return false;
                }
                // If validation passes, let form submit normally (don't prevent default)
            });
        } else {
            console.error('Form element not found!'); // Debug log
        }

        // Success Modal Handler
        const successModal = document.getElementById('jobApplicationSuccessModal');
        const successModalBackdrop = document.querySelector('[data-success-modal-close]');
        
        // applicationSuccess is already defined above, just use it here
        if (applicationSuccess) {
            console.log('Application success detected - will show modal');
            
            // Re-enable submit button and reset form after successful submission
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> Submit Application';
            }
            
            // Reset form fields
            if (form) {
                form.reset();
                
                // Reset file upload previews
                const fileInputs = form.querySelectorAll('.file-upload-input');
                fileInputs.forEach(input => {
                    const wrapper = input.closest('.file-upload-wrapper');
                    const preview = wrapper ? wrapper.querySelector('.file-upload-preview') : null;
                    const label = wrapper ? wrapper.querySelector('.file-upload-label') : null;
                    
                    if (preview) preview.innerHTML = '';
                    if (label) {
                        label.classList.remove('has-file');
                        const uploadIcon = label.querySelector('.file-upload-icon');
                        if (uploadIcon) {
                            uploadIcon.className = 'fa-solid fa-cloud-arrow-up file-upload-icon';
                        }
                        const uploadText = label.querySelector('.file-upload-text');
                        if (uploadText) {
                            uploadText.innerHTML = translations.chooseFile || 'Choose file or drag it here';
                        }
                        const uploadHint = label.querySelector('.file-upload-hint');
                        if (uploadHint) {
                            const fieldId = input.id;
                            if (fieldId === 'cv') {
                                uploadHint.innerHTML = translations.fileHintCv || 'PDF, DOC, DOCX (Max 5MB)';
                            } else if (fieldId === 'portfolio_file') {
                                uploadHint.innerHTML = translations.fileHintPortfolio || 'PDF, ZIP, RAR (Max 10MB)';
                            }
                            uploadHint.style.color = '';
                            uploadHint.style.fontWeight = '';
                        }
                    }
                });
                
                // Clear validation classes
                const invalidFields = form.querySelectorAll('.is-invalid');
                invalidFields.forEach(field => {
                    field.classList.remove('is-invalid');
                });
            }
            
            // Show modal after page fully loads
            if (successModal) {
                console.log('Success modal element found, will show modal');
                
                // Remove success parameter from URL to prevent showing again on refresh
                if (urlSuccess && window.history && window.history.replaceState) {
                    const newUrl = window.location.pathname + (window.location.search.replace(/[?&]success=1/, '').replace(/^\?$/, '') || '');
                    window.history.replaceState({}, '', newUrl);
                }
                
                // Wait for page to fully load, then show modal
                setTimeout(function() {
                    console.log('Attempting to show success modal');
                    if (successModal) {
                        showSuccessModal();
                        // Scroll to top to show modal
                        setTimeout(() => {
                            window.scrollTo({ top: 0, behavior: 'smooth' });
                        }, 100);
                    } else {
                        console.error('Success modal not found when trying to show');
                    }
                }, 1000);
            } else {
                console.error('Success modal element not found!');
            }
        } else {
            console.log('Application success not detected');
        }
        
        // Check if there are validation errors from backend
        @if($errors->any())
            const backendErrors = [];
            @foreach($errors->all() as $error)
                backendErrors.push('{{ $error }}');
            @endforeach
            
            if (backendErrors.length > 0) {
                // Re-enable submit button if there are backend errors
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> Submit Application';
                }
                
                setTimeout(function() {
                    showErrorModal(backendErrors);
                    // Scroll to first error field if exists
                    const firstErrorField = form.querySelector('.is-invalid, .form-input.is-invalid, .file-upload-label.is-invalid');
                    if (firstErrorField) {
                        setTimeout(() => {
                            firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }, 300);
                    }
                }, 300);
            }
        @endif

        // Close modal functionality
        if (successModal) {
            function closeSuccessModal() {
                successModal.classList.remove('is-active');
                document.body.classList.remove('modal-open');
                setTimeout(function() {
                    successModal.hidden = true;
                    successModal.setAttribute('aria-hidden', 'true');
                }, 300);
            }

            // Close on backdrop click
            if (successModalBackdrop) {
                successModalBackdrop.addEventListener('click', function(e) {
                    if (e.target === successModalBackdrop) {
                        closeSuccessModal();
                    }
                });
            }

            // Close on close button click
            const closeButtons = successModal.querySelectorAll('[data-success-modal-close]');
            closeButtons.forEach(btn => {
                btn.addEventListener('click', closeSuccessModal);
            });

            // Close on Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && successModal.classList.contains('is-active')) {
                    closeSuccessModal();
                }
            });
        }

        // Error Modal Handler
        const errorModal = document.getElementById('jobApplicationErrorModal');
        const errorModalBackdrop = document.querySelector('[data-error-modal-close]');
        
        if (errorModal) {
            // Close on backdrop click
            if (errorModalBackdrop) {
                errorModalBackdrop.addEventListener('click', function(e) {
                    if (e.target === errorModalBackdrop) {
                        closeErrorModal();
                    }
                });
            }

            // Close on close button click
            const errorCloseButtons = errorModal.querySelectorAll('[data-error-modal-close]');
            errorCloseButtons.forEach(btn => {
                btn.addEventListener('click', closeErrorModal);
            });

            // Close on Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && errorModal.classList.contains('is-active')) {
                    closeErrorModal();
                }
            });
        }
    });
</script>
@endpush
@endsection

