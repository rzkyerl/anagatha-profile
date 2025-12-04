@extends('admin.admin_layouts.app')

@section('title', 'Edit Job Application')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="javascript: void(0);">Anagata Executive</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.job-apply.index') }}">Job Applications</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.job-apply.show', $jobApply->id) }}">{{ $jobApply->full_name }}</a>
    </li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h4 class="card-title mb-1">
                                @if(isset($isRecruiter) && $isRecruiter)
                                    Review Job Application
                                @else
                                    Edit Job Application
                                @endif
                            </h4>
                            <p class="card-title-desc mb-0">
                                @if(isset($isRecruiter) && $isRecruiter)
                                    Update application status and add notes for the applicant
                                @else
                                    Update job application information
                                @endif
                            </p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.job-apply.show', $jobApply->id) }}" class="btn btn-info">
                                <i class="ri-eye-line align-middle me-1"></i> View
                            </a>
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

                    <form action="{{ route('admin.job-apply.update', $jobApply->id) }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')

                        {{-- Debug: isRecruiter = {{ isset($isRecruiter) ? ($isRecruiter ? 'true' : 'false') : 'not set' }} --}}
                        @if(isset($isRecruiter) && $isRecruiter)
                        {{-- Recruiter View: Read-only information display --}}
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-info mb-4">
                                    <i class="ri-information-line me-2"></i>
                                    <strong>Information:</strong> As a recruiter, you can only update the application status and add notes for the applicant. All other information is read-only.
                                </div>
                            </div>
                        </div>

                        {{-- Read-only Applicant Information --}}
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5 class="mb-3">Applicant Information</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <th style="width: 200px;">Job Position:</th>
                                                <td>{{ $jobApply->jobListing ? $jobApply->jobListing->title : 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Full Name:</th>
                                                <td>{{ $jobApply->full_name }}</td>
                                            </tr>
                                            <tr>
                                                <th>Email:</th>
                                                <td>{{ $jobApply->email }}</td>
                                            </tr>
                                            <tr>
                                                <th>Phone:</th>
                                                <td>{{ $jobApply->phone }}</td>
                                            </tr>
                                            <tr>
                                                <th>Address:</th>
                                                <td>{{ $jobApply->address }}</td>
                                            </tr>
                                            <tr>
                                                <th>Current Salary:</th>
                                                <td>{{ $jobApply->current_salary ?? 'Not specified' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Expected Salary:</th>
                                                <td>{{ $jobApply->expected_salary }}</td>
                                            </tr>
                                            <tr>
                                                <th>Availability:</th>
                                                <td>{{ $jobApply->availability }}</td>
                                            </tr>
                                            <tr>
                                                <th>Relocation:</th>
                                                <td>{{ $jobApply->relocation }}@if($jobApply->relocation === 'Other' && $jobApply->relocation_other) - {{ $jobApply->relocation_other }}@endif</td>
                                            </tr>
                                            @if($jobApply->linkedin)
                                            <tr>
                                                <th>LinkedIn:</th>
                                                <td><a href="{{ $jobApply->linkedin }}" target="_blank">{{ $jobApply->linkedin }}</a></td>
                                            </tr>
                                            @endif
                                            @if($jobApply->github)
                                            <tr>
                                                <th>GitHub:</th>
                                                <td><a href="{{ $jobApply->github }}" target="_blank">{{ $jobApply->github }}</a></td>
                                            </tr>
                                            @endif
                                            @if($jobApply->social_media)
                                            <tr>
                                                <th>Social Media:</th>
                                                <td><a href="{{ $jobApply->social_media }}" target="_blank">{{ $jobApply->social_media }}</a></td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5 class="mb-3">Application Details</h5>
                                @if($jobApply->cover_letter)
                                <div class="mb-3">
                                    <strong>Cover Letter:</strong>
                                    <div class="p-3 bg-light rounded mt-2">{{ $jobApply->cover_letter }}</div>
                                </div>
                                @endif
                                <div class="mb-3">
                                    <strong>Reason for Applying:</strong>
                                    <div class="p-3 bg-light rounded mt-2">{{ $jobApply->reason_applying }}</div>
                                </div>
                                @if($jobApply->relevant_experience)
                                <div class="mb-3">
                                    <strong>Relevant Experience:</strong>
                                    <div class="p-3 bg-light rounded mt-2">{{ $jobApply->relevant_experience }}</div>
                                </div>
                                @endif
                                @if($jobApply->cv)
                                <div class="mb-3">
                                    <strong>CV / Resume:</strong>
                                    <div class="mt-2">
                                        <a href="{{ route('admin.job-apply.download.cv', $jobApply->id) }}" class="btn btn-sm btn-primary" target="_blank">
                                            <i class="ri-file-pdf-line me-1"></i> Download CV
                                        </a>
                                    </div>
                                </div>
                                @endif
                                @if($jobApply->portfolio_file)
                                <div class="mb-3">
                                    <strong>Portfolio:</strong>
                                    <div class="mt-2">
                                        <a href="{{ route('admin.job-apply.download.portfolio', $jobApply->id) }}" class="btn btn-sm btn-primary" target="_blank">
                                            <i class="ri-folder-line me-1"></i> Download Portfolio
                                        </a>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- Separator --}}
                        <div class="row mt-4 mb-3">
                            <div class="col-12">
                                <hr>
                                <h5 class="mt-4 mb-3">
                                    <i class="ri-edit-line me-2"></i> Update Application Status & Notes
                                </h5>
                            </div>
                        </div>

                        {{-- Editable Section: Status and Notes --}}
                        <div class="card" id="recruiter-status-notes-section" style="display: block !important; visibility: visible !important;">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <div style="display: block !important;">
                                                <label for="status" class="form-label fw-semibold mb-2" style="display: block !important; width: 100%;">
                                                    Application Status <span class="text-danger">*</span>
                                                </label>
                                            </div>
                                            <div style="display: block !important; width: 100%;">
                                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required style="cursor: pointer; display: block !important; visibility: visible !important; opacity: 1 !important; width: 100%; position: static !important; left: auto !important; right: auto !important; top: auto !important; transform: none !important; margin: 0 !important;">
                                                    <option value="">Select Status</option>
                                                    <option value="pending" {{ old('status', $jobApply->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="hired" {{ old('status', $jobApply->status) == 'hired' ? 'selected' : '' }}>Accepted</option>
                                                    <option value="rejected" {{ old('status', $jobApply->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                                </select>
                                            </div>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted d-block mt-2">
                                                <i class="ri-information-line me-1"></i>
                                                You can only change the status to: <strong>Pending</strong>, <strong>Accepted</strong>, or <strong>Rejected</strong>
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-0">
                                            <label for="notes" class="form-label fw-semibold mb-2">Notes for Applicant</label>
                                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="5" placeholder="Add notes for the applicant... (these notes will be visible to the applicant on their history page)">{{ old('notes', $jobApply->notes) }}</textarea>
                                            @error('notes')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted d-block mt-2">
                                                <i class="ri-information-line me-1"></i> 
                                                These notes will be displayed to the applicant on their history page
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent border-top pt-3" id="recruiter-action-buttons" style="display: block !important; visibility: visible !important;">
                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="{{ route('admin.job-apply.show', $jobApply->id) }}" class="btn btn-secondary" style="display: inline-block !important; visibility: visible !important;">
                                        <i class="ri-close-line align-middle me-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary waves-effect waves-light" id="submit-status-notes-btn" style="display: inline-block !important; visibility: visible !important;">
                                        <i class="ri-save-line align-middle me-1"></i> Update Status & Notes
                                    </button>
                                </div>
                            </div>
                        </div>
                        @else
                        {{-- Admin View: Full editable form --}}
                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-6">
                                <h5 class="mb-3">Applicant Information</h5>

                                <div class="mb-3">
                                    <label for="job_listing_id" class="form-label">Job Position</label>
                                    <select class="form-select @error('job_listing_id') is-invalid @enderror" id="job_listing_id" name="job_listing_id">
                                        <option value="">Select Job Position</option>
                                        @foreach($jobListings ?? [] as $listing)
                                            <option value="{{ $listing->id }}" {{ old('job_listing_id', $jobApply->job_listing_id) == $listing->id ? 'selected' : '' }}>
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
                                           value="{{ old('full_name', $jobApply->full_name) }}" 
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
                                           value="{{ old('email', $jobApply->email) }}" 
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
                                           value="{{ old('phone', $jobApply->phone) }}" 
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
                                              required>{{ old('address', $jobApply->address) }}</textarea>
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
                                                   value="{{ old('current_salary', $jobApply->current_salary) }}" 
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
                                                   value="{{ old('expected_salary', $jobApply->expected_salary) }}" 
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
                                           value="{{ old('availability', $jobApply->availability) }}" 
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
                                        <option value="Yes" {{ old('relocation', $jobApply->relocation) == 'Yes' ? 'selected' : '' }}>Yes</option>
                                        <option value="No" {{ old('relocation', $jobApply->relocation) == 'No' ? 'selected' : '' }}>No</option>
                                        <option value="Other" {{ old('relocation', $jobApply->relocation) == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('relocation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div id="relocation_other_wrapper" class="mt-2" style="display: {{ old('relocation', $jobApply->relocation) == 'Other' ? 'block' : 'none' }};">
                                        <input type="text" 
                                               class="form-control @error('relocation_other') is-invalid @enderror" 
                                               id="relocation_other" 
                                               name="relocation_other" 
                                               value="{{ old('relocation_other', $jobApply->relocation_other) }}" 
                                               placeholder="Please specify relocation preference">
                                        @error('relocation_other')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
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
                                           value="{{ old('linkedin', $jobApply->linkedin) }}" 
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
                                           value="{{ old('github', $jobApply->github) }}" 
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
                                           value="{{ old('social_media', $jobApply->social_media) }}" 
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
                                    @if($jobApply->cv)
                                        <div class="form-text">
                                            Current file: <a href="{{ route('admin.job-apply.download.cv', $jobApply->id) }}" target="_blank">Download CV</a>
                                        </div>
                                    @endif
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
                                    @if($jobApply->portfolio_file)
                                        <div class="form-text">
                                            Current file: <a href="{{ route('admin.job-apply.download.portfolio', $jobApply->id) }}" target="_blank">Download Portfolio</a>
                                        </div>
                                    @endif
                                    <div class="form-text">Accepted formats: PDF, DOC, DOCX, ZIP, RAR (Max: 10MB)</div>
                                </div>

                                <h5 class="mb-3 mt-4">Application Details</h5>

                                <div class="mb-3">
                                    <label for="cover_letter" class="form-label">Cover Letter</label>
                                    <textarea class="form-control @error('cover_letter') is-invalid @enderror" 
                                              id="cover_letter" 
                                              name="cover_letter" 
                                              rows="4" 
                                              placeholder="Enter cover letter">{{ old('cover_letter', $jobApply->cover_letter) }}</textarea>
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
                                              required>{{ old('reason_applying', $jobApply->reason_applying) }}</textarea>
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
                                              placeholder="Describe your relevant experience">{{ old('relevant_experience', $jobApply->relevant_experience) }}</textarea>
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
                                        <option value="pending" {{ old('status', $jobApply->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="shortlisted" {{ old('status', $jobApply->status) == 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                                        <option value="interview" {{ old('status', $jobApply->status) == 'interview' ? 'selected' : '' }}>Interview</option>
                                        <option value="hired" {{ old('status', $jobApply->status) == 'hired' ? 'selected' : '' }}>Hired</option>
                                        <option value="rejected" {{ old('status', $jobApply->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
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
                                              placeholder="Internal notes about this application...">{{ old('notes', $jobApply->notes) }}</textarea>
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
                                    <i class="ri-save-line align-middle me-1"></i> Update Application
                                </button>
                                <a href="{{ route('admin.job-apply.show', $jobApply->id) }}" class="btn btn-secondary">
                                    <i class="ri-close-line align-middle me-1"></i> Cancel
                                </a>
                            </div>
                        </div>
                        @endif
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

        // Ensure recruiter status section is always visible
        function ensureRecruiterSectionVisible() {
            const statusSection = document.getElementById('recruiter-status-notes-section');
            const statusSelect = document.getElementById('status');
            const actionButtons = document.getElementById('recruiter-action-buttons');
            const submitBtn = document.getElementById('submit-status-notes-btn');
            
            @if(isset($isRecruiter) && $isRecruiter)
            // Only run if this is recruiter view
            if (statusSection) {
                statusSection.style.setProperty('display', 'block', 'important');
                statusSection.style.setProperty('visibility', 'visible', 'important');
                statusSection.style.setProperty('opacity', '1', 'important');
                statusSection.classList.remove('d-none');
            }
            
            if (statusSelect) {
                statusSelect.style.setProperty('display', 'block', 'important');
                statusSelect.style.setProperty('visibility', 'visible', 'important');
                statusSelect.style.setProperty('opacity', '1', 'important');
                statusSelect.style.setProperty('width', '100%', 'important');
            }
            
            if (actionButtons) {
                actionButtons.style.setProperty('display', 'block', 'important');
                actionButtons.style.setProperty('visibility', 'visible', 'important');
            }
            
            if (submitBtn) {
                submitBtn.style.setProperty('display', 'inline-block', 'important');
                submitBtn.style.setProperty('visibility', 'visible', 'important');
            }
            
            // Check if elements are actually visible
            if (statusSection && (statusSection.offsetParent === null || window.getComputedStyle(statusSection).display === 'none')) {
                console.warn('Recruiter status section is hidden, forcing visibility');
                statusSection.removeAttribute('style');
                statusSection.style.cssText = 'display: block !important; visibility: visible !important; opacity: 1 !important;';
            }
            @endif
        }

        // Run after DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            ensureRecruiterSectionVisible();
            
            // Keep checking to ensure elements remain visible
            setTimeout(ensureRecruiterSectionVisible, 100);
            setTimeout(ensureRecruiterSectionVisible, 500);
            setTimeout(ensureRecruiterSectionVisible, 1000);
            
            // Continuous check every 500ms for first 5 seconds
            let checkCount = 0;
            const maxChecks = 10;
            const checkInterval = setInterval(function() {
                ensureRecruiterSectionVisible();
                checkCount++;
                if (checkCount >= maxChecks) {
                    clearInterval(checkInterval);
                }
            }, 500);
        });

        // Also run after all resources are loaded
        window.addEventListener('load', function() {
            ensureRecruiterSectionVisible();
            setTimeout(ensureRecruiterSectionVisible, 100);
            setTimeout(ensureRecruiterSectionVisible, 500);
        });
        
        // Run immediately if DOM is already loaded
        if (document.readyState === 'loading') {
            // DOM is still loading, wait for DOMContentLoaded
        } else {
            // DOM is already loaded, run immediately
            ensureRecruiterSectionVisible();
        }

        // Use MutationObserver to watch for changes that might hide the elements
        if (typeof MutationObserver !== 'undefined') {
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                        const target = mutation.target;
                        if (target.id === 'recruiter-status-notes-section' || 
                            target.id === 'status' || 
                            target.id === 'recruiter-action-buttons' ||
                            target.id === 'submit-status-notes-btn') {
                            ensureRecruiterSectionVisible();
                        }
                    }
                    if (mutation.type === 'childList') {
                        ensureRecruiterSectionVisible();
                    }
                });
            });

            document.addEventListener('DOMContentLoaded', function() {
                const statusSection = document.getElementById('recruiter-status-notes-section');
                if (statusSection) {
                    observer.observe(statusSection, {
                        attributes: true,
                        attributeFilter: ['style', 'class'],
                        childList: true,
                        subtree: true
                    });
                }
            });
        }

        // Handle "Other" option for relocation (only for admin view)
        const relocationSelect = document.getElementById('relocation');
        if (relocationSelect && !document.getElementById('recruiter-status-notes-section')) {
            function toggleRelocationOther() {
                const select = document.getElementById('relocation');
                const wrapper = document.getElementById('relocation_other_wrapper');
                const input = document.getElementById('relocation_other');
                
                if (select && select.value === 'Other') {
                    if (wrapper) wrapper.style.display = 'block';
                    if (input) input.required = true;
                } else {
                    if (wrapper) wrapper.style.display = 'none';
                    if (input) {
                        input.required = false;
                        input.value = '';
                    }
                }
            }

            // Initialize on page load
            document.addEventListener('DOMContentLoaded', function() {
                toggleRelocationOther();
            });

            // Add event listener
            relocationSelect.addEventListener('change', toggleRelocationOther);
        }
    </script>
@endpush

@push('styles')
<style>
    /* Ensure Application Status label and select are on separate lines */
    #recruiter-status-notes-section .mb-4 > div:first-child,
    #recruiter-status-notes-section .mb-4 > label[for="status"] {
        display: block !important;
        width: 100% !important;
        margin-bottom: 0.5rem !important;
    }
    
    #recruiter-status-notes-section .mb-4 > div:last-child,
    #recruiter-status-notes-section .mb-4 > select#status {
        display: block !important;
        width: 100% !important;
        margin-top: 0 !important;
    }
    
    #recruiter-status-notes-section .form-label[for="status"] {
        display: block !important;
        width: 100% !important;
    }
    
    /* Override preloader CSS for #status select element */
    #recruiter-status-notes-section select#status,
    #recruiter-status-notes-section #status.form-select {
        position: static !important;
        left: auto !important;
        right: auto !important;
        top: auto !important;
        bottom: auto !important;
        transform: none !important;
        -webkit-transform: none !important;
        margin: 0 !important;
        display: block !important;
        width: 100% !important;
    }
</style>
@endpush

