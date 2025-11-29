@extends('admin.admin_layouts.app')

@section('title', 'Create Job Application')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="javascript: void(0);">Anagata Executive</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.job-apply.index') }}">Job Applications</a>
    </li>
    <li class="breadcrumb-item active">Create</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h4 class="card-title mb-1">Create New Job Application</h4>
                            <p class="card-title-desc mb-0">Add a new job application to the system</p>
                        </div>
                        <div>
                            <a href="{{ route('admin.job-apply.index') }}" class="btn btn-secondary">
                                <i class="ri-arrow-left-line align-middle me-1"></i> Back to List
                            </a>
                        </div>
                    </div>

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="ri-error-warning-line me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.job-apply.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf

                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-6">
                                <h5 class="mb-3">Applicant Information</h5>

                                <div class="mb-3">
                                    <label for="user_id" class="form-label">User Account (Optional)</label>
                                    <select class="form-select @error('user_id') is-invalid @enderror" id="user_id" name="user_id">
                                        <option value="">Select User (Optional)</option>
                                        @foreach($users ?? [] as $user)
                                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->first_name }} {{ $user->last_name }} ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Link this application to an existing user account (optional).</div>
                                </div>

                                <div class="mb-3">
                                    <label for="job_listing_id" class="form-label">Job Position</label>
                                    <select class="form-select @error('job_listing_id') is-invalid @enderror" id="job_listing_id" name="job_listing_id">
                                        <option value="">Select Job Position</option>
                                        @foreach($jobListings ?? [] as $listing)
                                            <option value="{{ $listing->id }}" {{ old('job_listing_id') == $listing->id ? 'selected' : '' }}>
                                                {{ $listing->title }} - {{ $listing->company }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('job_listing_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('full_name') is-invalid @enderror" 
                                           id="full_name" 
                                           name="full_name" 
                                           value="{{ old('full_name') }}" 
                                           placeholder="Enter full name" 
                                           required>
                                    @error('full_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           placeholder="Enter email address" 
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" 
                                           name="phone" 
                                           value="{{ old('phone') }}" 
                                           placeholder="Enter phone number" 
                                           required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" 
                                              id="address" 
                                              name="address" 
                                              rows="2" 
                                              placeholder="Enter address" 
                                              required>{{ old('address') }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="current_salary" class="form-label">Current Salary</label>
                                            <input type="text" 
                                                   class="form-control @error('current_salary') is-invalid @enderror" 
                                                   id="current_salary" 
                                                   name="current_salary" 
                                                   value="{{ old('current_salary') }}" 
                                                   placeholder="e.g., $50,000">
                                            @error('current_salary')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="expected_salary" class="form-label">Expected Salary <span class="text-danger">*</span></label>
                                            <input type="text" 
                                                   class="form-control @error('expected_salary') is-invalid @enderror" 
                                                   id="expected_salary" 
                                                   name="expected_salary" 
                                                   value="{{ old('expected_salary') }}" 
                                                   placeholder="e.g., $60,000" 
                                                   required>
                                            @error('expected_salary')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="availability" class="form-label">Availability <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('availability') is-invalid @enderror" 
                                           id="availability" 
                                           name="availability" 
                                           value="{{ old('availability') }}" 
                                           placeholder="e.g., Immediately, 2 weeks notice" 
                                           required>
                                    @error('availability')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="relocation" class="form-label">Relocation <span class="text-danger">*</span></label>
                                    <select class="form-select @error('relocation') is-invalid @enderror" id="relocation" name="relocation" required>
                                        <option value="">Select option</option>
                                        <option value="yes" {{ old('relocation') == 'yes' ? 'selected' : '' }}>Yes</option>
                                        <option value="no" {{ old('relocation') == 'no' ? 'selected' : '' }}>No</option>
                                        <option value="maybe" {{ old('relocation') == 'maybe' ? 'selected' : '' }}>Maybe</option>
                                    </select>
                                    @error('relocation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-md-6">
                                <h5 class="mb-3">Links & Files</h5>

                                <div class="mb-3">
                                    <label for="linkedin" class="form-label">LinkedIn URL</label>
                                    <input type="url" 
                                           class="form-control @error('linkedin') is-invalid @enderror" 
                                           id="linkedin" 
                                           name="linkedin" 
                                           value="{{ old('linkedin') }}" 
                                           placeholder="https://linkedin.com/in/...">
                                    @error('linkedin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="github" class="form-label">GitHub URL</label>
                                    <input type="url" 
                                           class="form-control @error('github') is-invalid @enderror" 
                                           id="github" 
                                           name="github" 
                                           value="{{ old('github') }}" 
                                           placeholder="https://github.com/...">
                                    @error('github')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="social_media" class="form-label">Social Media URL</label>
                                    <input type="url" 
                                           class="form-control @error('social_media') is-invalid @enderror" 
                                           id="social_media" 
                                           name="social_media" 
                                           value="{{ old('social_media') }}" 
                                           placeholder="https://...">
                                    @error('social_media')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="cv" class="form-label">CV / Resume</label>
                                    <input type="file" 
                                           class="form-control @error('cv') is-invalid @enderror" 
                                           id="cv" 
                                           name="cv" 
                                           accept=".pdf,.doc,.docx">
                                    @error('cv')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Accepted formats: PDF, DOC, DOCX (Max: 10MB)</div>
                                </div>

                                <div class="mb-3">
                                    <label for="portfolio_file" class="form-label">Portfolio File</label>
                                    <input type="file" 
                                           class="form-control @error('portfolio_file') is-invalid @enderror" 
                                           id="portfolio_file" 
                                           name="portfolio_file" 
                                           accept=".pdf,.doc,.docx,.zip,.rar">
                                    @error('portfolio_file')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Accepted formats: PDF, DOC, DOCX, ZIP, RAR (Max: 10MB)</div>
                                </div>

                                <h5 class="mb-3 mt-4">Application Details</h5>

                                <div class="mb-3">
                                    <label for="cover_letter" class="form-label">Cover Letter</label>
                                    <textarea class="form-control @error('cover_letter') is-invalid @enderror" 
                                              id="cover_letter" 
                                              name="cover_letter" 
                                              rows="4" 
                                              placeholder="Enter cover letter">{{ old('cover_letter') }}</textarea>
                                    @error('cover_letter')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="reason_applying" class="form-label">Reason for Applying <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('reason_applying') is-invalid @enderror" 
                                              id="reason_applying" 
                                              name="reason_applying" 
                                              rows="3" 
                                              placeholder="Why are you applying for this position?" 
                                              required>{{ old('reason_applying') }}</textarea>
                                    @error('reason_applying')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="relevant_experience" class="form-label">Relevant Experience</label>
                                    <textarea class="form-control @error('relevant_experience') is-invalid @enderror" 
                                              id="relevant_experience" 
                                              name="relevant_experience" 
                                              rows="3" 
                                              placeholder="Describe your relevant experience">{{ old('relevant_experience') }}</textarea>
                                    @error('relevant_experience')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="mb-3">Status & Notes</h5>

                                <div class="mb-3">
                                    <label for="status" class="form-label">Application Status <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                        <option value="">Select status</option>
                                        <option value="pending" {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="shortlisted" {{ old('status') == 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                                        <option value="interview" {{ old('status') == 'interview' ? 'selected' : '' }}>Interview</option>
                                        <option value="hired" {{ old('status') == 'hired' ? 'selected' : '' }}>Hired</option>
                                        <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="notes" class="form-label">Admin Notes</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                                              id="notes" 
                                              name="notes" 
                                              rows="5" 
                                              placeholder="Internal notes about this application...">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">These notes are only visible to administrators.</div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-0 mt-4">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary waves-effect waves-light">
                                    <i class="ri-save-line align-middle me-1"></i> Create Application
                                </button>
                                <a href="{{ route('admin.job-apply.index') }}" class="btn btn-secondary">
                                    <i class="ri-close-line align-middle me-1"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Bootstrap 5 form validation
        (function() {
            'use strict';
            var forms = document.querySelectorAll('.needs-validation');
            Array.prototype.slice.call(forms).forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>
@endpush

