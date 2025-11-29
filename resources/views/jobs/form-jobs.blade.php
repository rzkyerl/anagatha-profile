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
            <h1 class="job-application-title">Job Application Form</h1>
            @if(isset($job) && isset($job['title']))
                <div class="job-application-job-info">
                    <p class="job-application-job-title">Applying for: <strong>{{ $job['title'] }}</strong></p>
                    @if(isset($job['company']))
                        <p class="job-application-job-company">at {{ $job['company'] }}</p>
                    @endif
                </div>
            @endif
            <p class="job-application-subtitle">Please fill out all required fields to complete your application</p>
        </div>

        {{-- Already Applied Warning --}}
        @if(isset($hasApplied) && $hasApplied)
            <div class="job-application-already-applied" style="padding: 20px; margin: 20px; background-color: #fef3c7; border: 2px solid #f59e0b; border-radius: 8px; text-align: center;">
                <div style="font-size: 48px; color: #f59e0b; margin-bottom: 10px;">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <h2 style="color: #92400e; margin-bottom: 10px; font-size: 24px;">You Have Already Applied!</h2>
                <p style="color: #78350f; margin-bottom: 15px; font-size: 16px;">
                    You have already submitted an application for this job position. Please check your application history to view the status.
                </p>
                @if(isset($existingApplication))
                    <p style="color: #78350f; margin-bottom: 15px; font-size: 14px;">
                        <strong>Application Status:</strong> 
                        <span style="text-transform: capitalize;">{{ $existingApplication->status }}</span>
                    </p>
                    <p style="color: #78350f; margin-bottom: 20px; font-size: 14px;">
                        <strong>Applied Date:</strong> 
                        {{ $existingApplication->applied_at ? \Carbon\Carbon::parse($existingApplication->applied_at)->format('F d, Y') : 'N/A' }}
                    </p>
                @endif
                <div style="display: flex; gap: 10px; justify-content: center; flex-wrap: wrap;">
                    <a href="{{ route('history') }}" style="display: inline-block; padding: 12px 24px; background-color: #f59e0b; color: white; text-decoration: none; border-radius: 6px; font-weight: 600; transition: background-color 0.3s;">
                        <i class="fa-solid fa-history"></i> View Application History
                    </a>
                    <a href="{{ route('jobs') }}" style="display: inline-block; padding: 12px 24px; background-color: #6b7280; color: white; text-decoration: none; border-radius: 6px; font-weight: 600; transition: background-color 0.3s;">
                        <i class="fa-solid fa-briefcase"></i> Browse Other Jobs
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
                    Personal Information
                </h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="full_name" class="form-label">
                            Full Name <span class="required">*</span>
                        </label>
                        <div class="form-input-wrapper">
                            <i class="form-input-icon fa-solid fa-user"></i>
                            <input 
                                type="text" 
                                id="full_name" 
                                name="full_name" 
                                class="form-input @error('full_name') is-invalid @enderror" 
                                placeholder="Enter your full name"
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
                            Email Address <span class="required">*</span>
                        </label>
                        <div class="form-input-wrapper">
                            <i class="form-input-icon fa-solid fa-envelope"></i>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                class="form-input @error('email') is-invalid @enderror" 
                                placeholder="your.email@example.com"
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
                            Phone Number <span class="required">*</span>
                        </label>
                        <div class="form-input-wrapper">
                            <i class="form-input-icon fa-solid fa-phone"></i>
                            <input 
                                type="tel" 
                                id="phone" 
                                name="phone" 
                                class="form-input @error('phone') is-invalid @enderror" 
                                placeholder="+62 812-3456-7890"
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
                            Address / Location <span class="required">*</span>
                        </label>
                        <div class="form-input-wrapper">
                            <i class="form-input-icon fa-solid fa-location-dot"></i>
                            <input 
                                type="text" 
                                id="address" 
                                name="address" 
                                class="form-input @error('address') is-invalid @enderror" 
                                placeholder="City, Province, Country"
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
                    Professional Information
                </h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="current_salary" class="form-label">
                            Current Salary
                        </label>
                        <div class="form-input-wrapper">
                            <i class="form-input-icon fa-solid fa-dollar-sign"></i>
                            <input 
                                type="text" 
                                id="current_salary" 
                                name="current_salary" 
                                class="form-input @error('current_salary') is-invalid @enderror" 
                                placeholder="e.g., 5,000,000 IDR"
                                value="{{ old('current_salary') }}"
                            />
                        </div>
                        @error('current_salary')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="expected_salary" class="form-label">
                            Expected Salary <span class="required">*</span>
                        </label>
                        <div class="form-input-wrapper">
                            <i class="form-input-icon fa-solid fa-dollar-sign"></i>
                            <input 
                                type="text" 
                                id="expected_salary" 
                                name="expected_salary" 
                                class="form-input @error('expected_salary') is-invalid @enderror" 
                                placeholder="e.g., 7,000,000 IDR"
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
                            Availability <span class="required">*</span>
                        </label>
                        <div class="form-input-wrapper">
                            <i class="form-input-icon fa-solid fa-calendar-check"></i>
                            <select 
                                id="availability" 
                                name="availability" 
                                class="form-input @error('availability') is-invalid @enderror" 
                                required
                            >
                                <option value="">Select availability</option>
                                <option value="immediate" {{ old('availability') == 'immediate' ? 'selected' : '' }}>Immediate</option>
                                <option value="2-weeks" {{ old('availability') == '2-weeks' ? 'selected' : '' }}>Within 2 weeks</option>
                                <option value="1-month" {{ old('availability') == '1-month' ? 'selected' : '' }}>Within 1 month</option>
                                <option value="2-months" {{ old('availability') == '2-months' ? 'selected' : '' }}>Within 2 months</option>
                                <option value="negotiable" {{ old('availability') == 'negotiable' ? 'selected' : '' }}>Negotiable</option>
                            </select>
                        </div>
                        @error('availability')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="relocation" class="form-label">
                            Relocation Readiness <span class="required">*</span>
                        </label>
                        <div class="form-input-wrapper">
                            <i class="form-input-icon fa-solid fa-route"></i>
                            <select 
                                id="relocation" 
                                name="relocation" 
                                class="form-input @error('relocation') is-invalid @enderror" 
                                required
                            >
                                <option value="">Select option</option>
                                <option value="yes" {{ old('relocation') == 'yes' ? 'selected' : '' }}>Yes, willing to relocate</option>
                                <option value="no" {{ old('relocation') == 'no' ? 'selected' : '' }}>No, not willing to relocate</option>
                                <option value="maybe" {{ old('relocation') == 'maybe' ? 'selected' : '' }}>Maybe, depending on location</option>
                            </select>
                        </div>
                        @error('relocation')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Professional Profiles Section --}}
            <div class="form-section">
                <h2 class="form-section-title">
                    <i class="fa-solid fa-link form-section-icon"></i>
                    Professional Profiles
                </h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="linkedin" class="form-label">
                            LinkedIn Profile
                        </label>
                        <div class="form-input-wrapper">
                            <i class="form-input-icon fa-brands fa-linkedin"></i>
                            <input 
                                type="url" 
                                id="linkedin" 
                                name="linkedin" 
                                class="form-input @error('linkedin') is-invalid @enderror" 
                                placeholder="https://linkedin.com/in/yourprofile"
                                value="{{ old('linkedin') }}"
                            />
                        </div>
                        @error('linkedin')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="github" class="form-label">
                            GitHub Profile
                        </label>
                        <div class="form-input-wrapper">
                            <i class="form-input-icon fa-brands fa-github"></i>
                            <input 
                                type="url" 
                                id="github" 
                                name="github" 
                                class="form-input @error('github') is-invalid @enderror" 
                                placeholder="https://github.com/yourusername"
                                value="{{ old('github') }}"
                            />
                        </div>
                        @error('github')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="social_media" class="form-label">
                            Social Media 
                        </label>
                        <div class="form-input-wrapper">
                            <i class="form-input-icon fa-solid fa-share-nodes"></i>
                            <input 
                                type="url" 
                                id="social_media" 
                                name="social_media" 
                                class="form-input @error('social_media') is-invalid @enderror" 
                                placeholder="https://your-social-media-profile.com"
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
                    Documents
                </h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="cv" class="form-label">
                            CV / Resume <span class="required">*</span>
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
                                <span class="file-upload-text">Choose file or drag it here</span>
                                <span class="file-upload-hint">PDF, DOC, DOCX (Max 5MB)</span>
                            </label>
                            <div class="file-upload-preview"></div>
                        </div>
                        @error('cv')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="portfolio_file" class="form-label">
                            Portfolio File
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
                                <span class="file-upload-text">Choose file or drag it here</span>
                                <span class="file-upload-hint">PDF, ZIP, RAR (Max 10MB)</span>
                            </label>
                            <div class="file-upload-preview"></div>
                        </div>
                        @error('portfolio_file')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group form-group--full">
                        <label for="cover_letter" class="form-label">
                            Cover Letter
                        </label>
                        <div class="form-input-wrapper form-input-wrapper--textarea">
                            <i class="form-input-icon fa-solid fa-file-lines"></i>
                            <textarea 
                                id="cover_letter" 
                                name="cover_letter" 
                                class="form-input form-input--textarea @error('cover_letter') is-invalid @enderror" 
                                placeholder="Write your cover letter here..."
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
                    Additional Information
                </h2>
                <div class="form-grid">
                    <div class="form-group form-group--full">
                        <label for="reason_applying" class="form-label">
                            Reason for Applying <span class="required">*</span>
                        </label>
                        <div class="form-input-wrapper form-input-wrapper--textarea">
                            <i class="form-input-icon fa-solid fa-question-circle"></i>
                            <textarea 
                                id="reason_applying" 
                                name="reason_applying" 
                                class="form-input form-input--textarea @error('reason_applying') is-invalid @enderror" 
                                placeholder="Tell us why you want to join our team..."
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
                            Relevant Experience
                        </label>
                        <div class="form-input-wrapper form-input-wrapper--textarea">
                            <i class="form-input-icon fa-solid fa-briefcase"></i>
                            <textarea 
                                id="relevant_experience" 
                                name="relevant_experience" 
                                class="form-input form-input--textarea @error('relevant_experience') is-invalid @enderror" 
                                placeholder="Describe your relevant work experience and achievements..."
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
                        Already Applied
                    </button>
                    <p class="form-note" style="color: #f59e0b;">
                        <i class="fa-solid fa-info-circle"></i>
                        You have already submitted an application for this job. Check your application history for status updates.
                    </p>
                @else
                <button type="submit" class="job-application-submit-btn" id="submitBtn">
                    <i class="fa-solid fa-paper-plane"></i>
                    Submit Application
                </button>
                <p class="form-note">
                    <i class="fa-solid fa-info-circle"></i>
                    By submitting this form, you agree to our terms and conditions. All fields marked with <span class="required">*</span> are required.
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
                <h2 class="job-application-error-modal__title" id="error-modal-title">Validation Error</h2>
                <button type="button" class="job-application-error-modal__close" aria-label="Close modal" data-error-modal-close>
                    <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                </button>
            </div>
            <div class="job-application-error-modal__body">
                <p class="job-application-error-modal__message">Please fill in all required fields correctly.</p>
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
                    OK, I'll fix it
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
            <h2 class="job-application-success-modal__title" id="success-modal-title">Application Submitted Successfully!</h2>
            <p class="job-application-success-modal__message">
                Your job application has been successfully submitted. We will review your application and get back to you soon.
            </p>
            <p class="job-application-success-modal__info">
                <i class="fa-solid fa-info-circle"></i>
                You can track the status and view the continuation of your application in the History
            </p>
            <div class="job-application-success-modal__actions">
                <a href="{{ route('history') }}" class="job-application-success-modal__btn job-application-success-modal__btn--primary">
                    View History
                </a>
                <a href="{{ route('jobs') }}" class="job-application-success-modal__btn job-application-success-modal__btn--secondary">
                    Close
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script nonce="{{ $cspNonce ?? '' }}">
    document.addEventListener('DOMContentLoaded', function() {
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
        
        fileInputs.forEach(input => {
            const preview = input.nextElementSibling.querySelector('.file-upload-preview');
            const label = input.nextElementSibling;
            
            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const fileName = file.name;
                    const fileSize = (file.size / 1024 / 1024).toFixed(2);
                    
                    if (!preview.innerHTML) {
                        preview.innerHTML = `
                            <div class="file-preview-item">
                                <i class="fa-solid fa-file"></i>
                                <div class="file-preview-info">
                                    <span class="file-preview-name">${fileName}</span>
                                    <span class="file-preview-size">${fileSize} MB</span>
                                </div>
                                <button type="button" class="file-preview-remove" aria-label="Remove file">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                        `;
                        
                        // Remove file functionality
                        const removeBtn = preview.querySelector('.file-preview-remove');
                        removeBtn.addEventListener('click', function() {
                            input.value = '';
                            preview.innerHTML = '';
                            label.classList.remove('has-file');
                        });
                        
                        label.classList.add('has-file');
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
                const requiredFields = form.querySelectorAll('input[required]:not([type="file"]):not([type="url"]), textarea[required], select[required]');
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
        
        // Check if application was successful (from session) - for backend mode
        @if(session('application_success'))
            if (successModal) {
                // Show modal after a short delay for better UX
                setTimeout(function() {
                    showSuccessModal();
                }, 300);
            }
        @endif
        
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

