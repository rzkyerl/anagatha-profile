@extends('admin.admin_layouts.app')

@section('title', 'Create New Job Listing')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="javascript: void(0);">Anagata Executive</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.job-listings.index') }}">Job Listings</a>
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
                            <h4 class="card-title mb-1">Create New Job Listing</h4>
                            <p class="card-title-desc mb-0">Add a new job listing to the system</p>
                        </div>
                        <a href="{{ route('admin.job-listings.index') }}" class="btn btn-secondary">
                            <i class="ri-arrow-left-line align-middle me-1"></i> Back to List
                        </a>
                    </div>

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="ri-error-warning-line me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.job-listings.store') }}" method="POST" class="needs-validation" enctype="multipart/form-data" novalidate>
                        @csrf

                        <!-- Basic Information -->
                        <h5 class="mb-3 text-decoration-underline">Basic Information</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Job Title <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('title') is-invalid @enderror" 
                                           id="title" 
                                           name="title" 
                                           value="{{ old('title') }}" 
                                           placeholder="e.g., Senior Software Engineer" 
                                           required 
                                           maxlength="255">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="company" class="form-label">Company Name <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('company') is-invalid @enderror" 
                                           id="company" 
                                           name="company" 
                                           value="{{ old('company') }}" 
                                           placeholder="Enter company name" 
                                           required 
                                           maxlength="255">
                                    @error('company')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="company_logo" class="form-label">Company Logo</label>
                                    <input type="file" 
                                           class="form-control @error('company_logo') is-invalid @enderror" 
                                           id="company_logo" 
                                           name="company_logo" 
                                           accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                                    @error('company_logo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Upload company logo image (JPEG, PNG, JPG, GIF, WebP, max 2MB)</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('location') is-invalid @enderror" 
                                           id="location" 
                                           name="location" 
                                           value="{{ old('location') }}" 
                                           placeholder="e.g., Jakarta, Indonesia" 
                                           required 
                                           maxlength="255">
                                    @error('location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Job Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="5" 
                                      placeholder="Enter job description...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <!-- Salary Information -->
                        <h5 class="mb-3 text-decoration-underline">Salary Information</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="salary_min" class="form-label">Minimum Salary</label>
                                    <input type="number" 
                                           class="form-control @error('salary_min') is-invalid @enderror" 
                                           id="salary_min" 
                                           name="salary_min" 
                                           value="{{ old('salary_min') }}" 
                                           placeholder="0" 
                                           min="0" 
                                           step="0.01">
                                    @error('salary_min')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="salary_max" class="form-label">Maximum Salary</label>
                                    <input type="number" 
                                           class="form-control @error('salary_max') is-invalid @enderror" 
                                           id="salary_max" 
                                           name="salary_max" 
                                           value="{{ old('salary_max') }}" 
                                           placeholder="0" 
                                           min="0" 
                                           step="0.01">
                                    @error('salary_max')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="salary_display" class="form-label">Salary Display <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('salary_display') is-invalid @enderror" 
                                           id="salary_display" 
                                           name="salary_display" 
                                           value="{{ old('salary_display', 'Not Disclose') }}" 
                                           placeholder="e.g., IDR 25,000,000 - IDR 35,000,000" 
                                           required 
                                           maxlength="255">
                                    @error('salary_display')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">How the salary will be displayed publicly</div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Job Details -->
                        <h5 class="mb-3 text-decoration-underline">Job Details</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="work_preference" class="form-label">Work Preference <span class="text-danger">*</span></label>
                                    <select class="form-select @error('work_preference') is-invalid @enderror" 
                                            id="work_preference" 
                                            name="work_preference" 
                                            required>
                                        <option value="">Select work preference</option>
                                        <option value="wfo" {{ old('work_preference') == 'wfo' ? 'selected' : '' }}>Work From Office</option>
                                        <option value="wfh" {{ old('work_preference') == 'wfh' ? 'selected' : '' }}>Work From Home</option>
                                        <option value="hybrid" {{ old('work_preference') == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                                    </select>
                                    @error('work_preference')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="contract_type" class="form-label">Contract Type <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('contract_type') is-invalid @enderror" 
                                           id="contract_type" 
                                           name="contract_type" 
                                           value="{{ old('contract_type', 'Full Time') }}" 
                                           placeholder="e.g., Full Time, Contract, Part Time" 
                                           required 
                                           maxlength="255">
                                    @error('contract_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="experience_level" class="form-label">Experience Level</label>
                                    <select class="form-select @error('experience_level') is-invalid @enderror" 
                                            id="experience_level" 
                                            name="experience_level">
                                        <option value="">Select experience level</option>
                                        <option value="Entry" {{ old('experience_level') == 'Entry' ? 'selected' : '' }}>Entry</option>
                                        <option value="1-3 Years" {{ old('experience_level') == '1-3 Years' ? 'selected' : '' }}>1-3 Years</option>
                                        <option value="3-5 Years" {{ old('experience_level') == '3-5 Years' ? 'selected' : '' }}>3-5 Years</option>
                                        <option value="5+ Years" {{ old('experience_level') == '5+ Years' ? 'selected' : '' }}>5+ Years</option>
                                        <option value="Senior" {{ old('experience_level') == 'Senior' ? 'selected' : '' }}>Senior</option>
                                        <option value="Mid Level" {{ old('experience_level') == 'Mid Level' ? 'selected' : '' }}>Mid Level</option>
                                    </select>
                                    @error('experience_level')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="industry" class="form-label">Industry</label>
                                    <input type="text" 
                                           class="form-control @error('industry') is-invalid @enderror" 
                                           id="industry" 
                                           name="industry" 
                                           value="{{ old('industry') }}" 
                                           placeholder="e.g., Technology, Finance, Healthcare" 
                                           maxlength="255">
                                    @error('industry')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="minimum_degree" class="form-label">Minimum Degree</label>
                                    <select class="form-select @error('minimum_degree') is-invalid @enderror" 
                                            id="minimum_degree" 
                                            name="minimum_degree">
                                        <option value="">Select minimum degree</option>
                                        <option value="Senior High School" {{ old('minimum_degree') == 'Senior High School' ? 'selected' : '' }}>Senior High School</option>
                                        <option value="Diploma" {{ old('minimum_degree') == 'Diploma' ? 'selected' : '' }}>Diploma</option>
                                        <option value="Bachelor" {{ old('minimum_degree') == 'Bachelor' ? 'selected' : '' }}>Bachelor</option>
                                        <option value="Master" {{ old('minimum_degree') == 'Master' ? 'selected' : '' }}>Master</option>
                                        <option value="MBA" {{ old('minimum_degree') == 'MBA' ? 'selected' : '' }}>MBA</option>
                                        <option value="Ph.D" {{ old('minimum_degree') == 'Ph.D' ? 'selected' : '' }}>Ph.D</option>
                                    </select>
                                    @error('minimum_degree')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Additional Information -->
                        <h5 class="mb-3 text-decoration-underline">Additional Information</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="recruiter_id" class="form-label">Recruiter <span class="text-danger">*</span></label>
                                    <select class="form-select @error('recruiter_id') is-invalid @enderror" 
                                            id="recruiter_id" 
                                            name="recruiter_id" 
                                            required>
                                        <option value="">Select recruiter</option>
                                        @foreach($recruiters ?? [] as $recruiter)
                                            <option value="{{ $recruiter->id }}" {{ old('recruiter_id') == $recruiter->id ? 'selected' : '' }}>
                                                {{ $recruiter->first_name }} {{ $recruiter->last_name }} ({{ $recruiter->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('recruiter_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror" 
                                            id="status" 
                                            name="status" 
                                            required>
                                        <option value="">Select status</option>
                                        <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="posted_at" class="form-label">Posted At</label>
                                    <input type="datetime-local" 
                                           class="form-control @error('posted_at') is-invalid @enderror" 
                                           id="posted_at" 
                                           name="posted_at" 
                                           value="{{ old('posted_at') }}">
                                    @error('posted_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Leave blank to auto-set when status is active</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check form-switch mt-4">
                                        <input class="form-check-input @error('verified') is-invalid @enderror" 
                                               type="checkbox" 
                                               id="verified" 
                                               name="verified" 
                                               value="1" 
                                               {{ old('verified') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="verified">
                                            Verified Job Listing
                                        </label>
                                    </div>
                                    @error('verified')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Mark this job listing as verified</div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-0 mt-4">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary waves-effect waves-light">
                                    <i class="ri-save-line align-middle me-1"></i> Create Job Listing
                                </button>
                                <a href="{{ route('admin.job-listings.index') }}" class="btn btn-secondary waves-effect">
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

        // Salary validation: max must be >= min
        document.getElementById('salary_max').addEventListener('input', function() {
            var salaryMin = parseFloat(document.getElementById('salary_min').value) || 0;
            var salaryMax = parseFloat(this.value) || 0;
            
            if (salaryMin > 0 && salaryMax > 0 && salaryMax < salaryMin) {
                this.setCustomValidity('Maximum salary must be greater than or equal to minimum salary');
            } else {
                this.setCustomValidity('');
            }
        });

        document.getElementById('salary_min').addEventListener('input', function() {
            var salaryMax = document.getElementById('salary_max');
            if (salaryMax.value) {
                salaryMax.dispatchEvent(new Event('input'));
            }
        });
    </script>
@endpush

