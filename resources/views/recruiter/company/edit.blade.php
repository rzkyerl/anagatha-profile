@extends('admin.admin_layouts.app')

@section('title', 'Edit Company Information')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="javascript:void(0)">Anagata Executive</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('recruiter.company.show') }}">My Company</a>
    </li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 mx-auto">
            {{-- Toast Alert --}}
            @if (session('status'))
                <div class="alert alert-{{ session('toast_type') === 'success' ? 'success' : (session('toast_type') === 'error' ? 'danger' : 'info') }} alert-dismissible fade show" role="alert">
                    <i class="ri-{{ session('toast_type') === 'success' ? 'checkbox-circle-line' : (session('toast_type') === 'error' ? 'error-warning-line' : 'information-line') }} me-2"></i>
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="ri-error-warning-line me-2"></i>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold mb-1">Edit Your Company</h4>
                    <p class="text-muted mb-0">Update your company details and information</p>
                </div>
                <a href="{{ route('recruiter.company.show') }}" class="btn btn-secondary">
                    <i class="ri-arrow-left-line me-1"></i> Back
                </a>
            </div>

            <form action="{{ route('recruiter.company.update') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                @csrf
                @method('PUT')

                {{-- Company Logo Upload --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold mb-2">
                        <i class="ri-image-line me-1"></i> Company Logo
                    </label>
                    <div class="logo-upload-area border rounded p-4 text-center" style="background: #f8f9fa; min-height: 200px; display: flex; align-items: center; justify-content: center; border-radius: 12px;">
                        <div id="logoPreview" class="logo-preview">
                            @if($user->company_logo)
                                <img src="{{ route('recruiter.company.logo', $user->company_logo) }}" 
                                     class="img-fluid" 
                                     style="max-height: 180px; border-radius: 10px;"
                                     id="currentLogo">
                            @else
                                <i class="ri-image-add-line" style="font-size: 3rem; color: #adb5bd;"></i>
                                <p class="text-muted mt-2 mb-0">No logo selected</p>
                            @endif
                        </div>
                    </div>
                    <div class="mt-3">
                        <input type="file" 
                               class="form-control @error('company_logo') is-invalid @enderror" 
                               id="company_logo" 
                               name="company_logo" 
                               accept="image/*"
                               onchange="previewLogo(this)">
                        <small class="form-text text-muted">Leave empty to keep current logo. Recommended: Square image, max 2MB. Formats: JPG, PNG, GIF, WEBP</small>
                        @error('company_logo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Company Name & Phone --}}
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="company_name" class="form-label fw-semibold mb-2">
                        <i class="ri-building-line"></i> Company Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('company_name') is-invalid @enderror" 
                               id="company_name" 
                               name="company_name" 
                               value="{{ old('company_name', $user->company_name) }}" 
                               placeholder="Enter company name" 
                               required>
                        @error('company_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="phone" class="form-label fw-semibold mb-2">
                            <i class="ri-phone-line me-1"></i> Phone / WhatsApp
                        </label>
                        <input type="tel" 
                               class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone', $user->phone) }}" 
                               placeholder="Enter phone number">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Job Title & Industry --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="job_title" class="form-label fw-semibold mb-2">
                            <i class="ri-user-settings-line"></i> Job Title / Position <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('job_title') is-invalid @enderror" 
                                id="job_title" 
                                name="job_title" 
                                required>
                            <option value="">Select Job Title</option>
                            @php
                                $jobTitles = ['HR Manager', 'HR Business Partner', 'Talent Acquisition Specialist', 'Recruitment Manager', 'HR Director', 'HR Coordinator', 'Recruiter', 'Senior Recruiter', 'HR Generalist', 'People Operations Manager', 'Other'];
                            @endphp
                            @foreach($jobTitles as $jt)
                                <option value="{{ $jt }}" {{ old('job_title', $user->job_title) == $jt ? 'selected' : '' }}>
                                    {{ $jt }}
                                </option>
                            @endforeach
                        </select>
                        @error('job_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        {{-- Other Job Title Field (shown when "Other" is selected) --}}
                        <div class="mt-3 job-title-other-group {{ (old('job_title', $user->job_title) == 'Other') ? 'd-block' : 'd-none' }}" id="job_title_other_container" style="{{ (old('job_title', $user->job_title) == 'Other') ? 'display: block !important;' : 'display: none !important;' }}">
                            <label for="job_title_other" class="form-label fw-semibold mb-2">
                                <i class="ri-briefcase-line me-1"></i> Custom Job Title <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('job_title_other') is-invalid @enderror" 
                                   id="job_title_other" 
                                   name="job_title_other" 
                                   value="{{ old('job_title_other', $user->job_title_other) }}" 
                                   placeholder="Enter custom job title"
                                   {{ (old('job_title', $user->job_title) == 'Other') ? 'required' : '' }}>
                            @error('job_title_other')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="industry" class="form-label fw-semibold mb-2">
                            <i class="ri-building-2-line"></i> Industry <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('industry') is-invalid @enderror" 
                                id="industry" 
                                name="industry" 
                                required>
                            <option value="">Select Industry</option>
                            @php
                                $industries = ['Technology', 'Healthcare', 'Finance', 'Education', 'Manufacturing', 'Retail', 'Real Estate', 'Hospitality', 'Transportation & Logistics', 'Energy', 'Telecommunications', 'Media & Entertainment', 'Consulting', 'Legal', 'Construction', 'Agriculture', 'Food & Beverage', 'Automotive', 'Aerospace', 'Pharmaceuticals', 'Other'];
                            @endphp
                            @foreach($industries as $industry)
                                <option value="{{ $industry }}" {{ old('industry', $user->industry) == $industry ? 'selected' : '' }}>
                                    {{ $industry }}
                                </option>
                            @endforeach
                        </select>
                        @error('industry')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        {{-- Other Industry Field (shown when "Other" is selected) --}}
                        <div class="mt-3 industry-other-group {{ (old('industry', $user->industry) == 'Other') ? 'd-block' : 'd-none' }}" id="industry_other_container" style="{{ (old('industry', $user->industry) == 'Other') ? 'display: block !important;' : 'display: none !important;' }}">
                            <label for="industry_other" class="form-label fw-semibold mb-2">
                                <i class="ri-building-line me-1"></i> Custom Industry <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('industry_other') is-invalid @enderror" 
                                   id="industry_other" 
                                   name="industry_other" 
                                   value="{{ old('industry_other', $user->industry_other ?? '') }}" 
                                   placeholder="Enter custom industry"
                                   {{ (old('industry', $user->industry) == 'Other') ? 'required' : '' }}>
                            @error('industry_other')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                {{-- Location --}}
                <div class="mb-4">
                    <label for="location" class="form-label fw-semibold mb-2">
                        <i class="ri-map-pin-line"></i> Company Location
                    </label>
                    <input type="text" 
                           class="form-control @error('location') is-invalid @enderror" 
                           id="location" 
                           name="location" 
                           value="{{ old('location', $user->company?->location ?? '') }}" 
                           placeholder="e.g., Jakarta, Indonesia"
                           maxlength="255">
                    @error('location')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Enter the primary location of your company.</div>
                </div>

                {{-- Action Buttons --}}
                <div class="d-flex gap-2 justify-content-end pt-3 border-top">
                    <a href="{{ route('recruiter.company.show') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-save-line me-1"></i> Update Company
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script nonce="{{ $cspNonce ?? '' }}">
        // Logo Preview Function
        function previewLogo(input) {
            const preview = document.getElementById('logoPreview');
            if (!preview) {
                console.error('Logo preview element not found');
                return;
            }

            if (input && input.files && input.files[0]) {
                // Validate file type
                const file = input.files[0];
                if (!file.type.match('image.*')) {
                    alert('Please select a valid image file');
                    input.value = '';
                    return;
                }

                // Validate file size (max 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Image size must be less than 2MB');
                    input.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = '<img src="' + e.target.result + '" class="img-fluid" style="max-height: 180px; border-radius: 10px;" id="currentLogo">';
                };
                reader.onerror = function() {
                    alert('Error reading image file');
                    input.value = '';
                };
                reader.readAsDataURL(file);
            } else {
                // Reset to original logo or placeholder
                @if($user->company_logo)
                    preview.innerHTML = '<img src="{{ route('recruiter.company.logo', $user->company_logo) }}" class="img-fluid" style="max-height: 180px; border-radius: 10px;" id="currentLogo">';
                @else
                    preview.innerHTML = '<i class="ri-image-add-line" style="font-size: 3rem; color: #adb5bd;"></i><p class="text-muted mt-2 mb-0">No logo selected</p>';
                @endif
            }
        }

        // Handle "Other" option for enum fields - make it globally accessible
        window.toggleOtherField = function(selectId, wrapperId) {
            console.log('toggleOtherField called:', selectId, wrapperId);
            const select = document.getElementById(selectId);
            const wrapper = document.getElementById(wrapperId);
            
            if (!select) {
                console.error('Select element not found:', selectId);
                return;
            }
            
            if (!wrapper) {
                console.error('Wrapper element not found:', wrapperId);
                return;
            }
            
            const input = wrapper.querySelector('input');
            
            console.log('Current select value:', select.value);
            
            if (select.value === 'Other') {
                // Show the field - use multiple methods to ensure visibility
                wrapper.classList.remove('d-none');
                wrapper.style.display = 'block';
                wrapper.style.visibility = 'visible';
                wrapper.style.opacity = '1';
                wrapper.style.height = 'auto';
                wrapper.style.minHeight = 'auto';
                wrapper.style.marginTop = '1rem';
                wrapper.style.overflow = 'visible';
                wrapper.removeAttribute('hidden');
                
                // Force show with important
                wrapper.setAttribute('style', 'display: block !important; visibility: visible !important; opacity: 1 !important; height: auto !important; min-height: auto !important; margin-top: 1rem !important; overflow: visible !important;');
                
                if (input) {
                    input.required = true;
                    input.removeAttribute('disabled');
                    input.removeAttribute('readonly');
                    input.style.display = 'block';
                }
                
                console.log('Field shown:', wrapperId, 'Display:', window.getComputedStyle(wrapper).display);
            } else {
                // Hide the field
                wrapper.classList.add('d-none');
                wrapper.style.display = 'none';
                if (input) {
                    input.required = false;
                    input.value = '';
                }
                console.log('Field hidden:', wrapperId);
            }
        };

        // Initialize function
        function initOtherFields() {
            // Get select elements
            const jobTitleSelect = document.getElementById('job_title');
            const industrySelect = document.getElementById('industry');
            const jobTitleOtherContainer = document.getElementById('job_title_other_container');
            const industryOtherContainer = document.getElementById('industry_other_container');
            
            console.log('Initializing other fields...', {
                jobTitleSelect: !!jobTitleSelect,
                industrySelect: !!industrySelect,
                jobTitleOtherContainer: !!jobTitleOtherContainer,
                industryOtherContainer: !!industryOtherContainer
            });
            
            // Initialize field visibility on page load
            if (jobTitleSelect && jobTitleOtherContainer) {
                // Check current value and show/hide accordingly
                toggleOtherField('job_title', 'job_title_other_container');
                
                // Add event listener - remove existing first to avoid duplicates
                jobTitleSelect.removeEventListener('change', handleJobTitleChange);
                jobTitleSelect.addEventListener('change', handleJobTitleChange);
            }
            
            if (industrySelect && industryOtherContainer) {
                // Check current value and show/hide accordingly
                toggleOtherField('industry', 'industry_other_container');
                
                // Add event listener - remove existing first to avoid duplicates
                industrySelect.removeEventListener('change', handleIndustryChange);
                industrySelect.addEventListener('change', handleIndustryChange);
            }
        }
        
        // Separate handler functions for better debugging
        function handleJobTitleChange() {
            console.log('Job title changed to:', this.value);
            toggleOtherField('job_title', 'job_title_other_container');
        }
        
        function handleIndustryChange() {
            console.log('Industry changed to:', this.value);
            toggleOtherField('industry', 'industry_other_container');
        }

        // Try multiple ways to ensure script runs
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initOtherFields);
        } else {
            // DOM is already ready
            initOtherFields();
        }
        
        // Also run after a short delay as fallback
        setTimeout(function() {
            initOtherFields();
        }, 200);
        
        // Run on window load as final fallback
        window.addEventListener('load', function() {
            setTimeout(initOtherFields, 100);
        });
    </script>
    @endpush

    @push('styles')
    <style>
        .logo-upload-area {
            transition: all 0.3s ease;
        }
        .logo-upload-area:hover {
            background: #f0f0f0 !important;
        }
        .form-label {
            color: #495057;
        }
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        /* Job Title Other field - ensure it has space and visibility */
        .job-title-other-group {
            transition: opacity 0.3s ease;
            margin-top: 1rem !important;
            min-height: auto;
            width: 100% !important;
            position: relative !important;
            z-index: 1 !important;
        }

        .job-title-other-group.d-none {
            display: none !important;
            height: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
            overflow: hidden !important;
            visibility: hidden !important;
        }

        .job-title-other-group.d-block,
        .job-title-other-group:not(.d-none) {
            display: block !important;
            opacity: 1 !important;
            visibility: visible !important;
            height: auto !important;
            min-height: auto !important;
            max-height: none !important;
            overflow: visible !important;
            margin-top: 1rem !important;
        }
        
        #job_title_other_container {
            display: block !important;
        }
        
        #job_title_other_container.d-none {
            display: none !important;
        }

        /* Industry Other field - ensure it has space and visibility */
        .industry-other-group {
            transition: opacity 0.3s ease;
            margin-top: 1rem !important;
            min-height: auto;
            width: 100% !important;
            position: relative !important;
            z-index: 1 !important;
        }

        .industry-other-group.d-none {
            display: none !important;
            height: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
            overflow: hidden !important;
            visibility: hidden !important;
        }

        .industry-other-group.d-block,
        .industry-other-group:not(.d-none) {
            display: block !important;
            opacity: 1 !important;
            visibility: visible !important;
            height: auto !important;
            min-height: auto !important;
            max-height: none !important;
            overflow: visible !important;
            margin-top: 1rem !important;
        }
        
        #industry_other_container {
            display: block !important;
        }
        
        #industry_other_container.d-none {
            display: none !important;
        }

        /* Ensure parent columns and row don't clip content */
        .row.g-3 {
            overflow: visible !important;
            min-height: auto !important;
        }
        
        .row.g-3 .col-md-6 {
            overflow: visible !important;
            min-height: auto !important;
            height: auto !important;
        }
        
        /* Ensure form has enough space */
        form.needs-validation {
            overflow: visible !important;
        }
        
        /* Make sure the container divs don't restrict height */
        #job_title_other_container,
        #industry_other_container {
            box-sizing: border-box;
        }
    </style>
    @endpush
@endsection
