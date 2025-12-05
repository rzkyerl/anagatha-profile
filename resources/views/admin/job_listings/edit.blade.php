@extends('admin.admin_layouts.app')

@section('title', 'Edit Job Listing')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="javascript: void(0);">Anagata Executive</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.job-listings.index') }}">Job Listings</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.job-listings.show', $jobListing->id) }}">{{ $jobListing->title }}</a>
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
                            <h4 class="card-title mb-1">Edit Job Listing</h4>
                            <p class="card-title-desc mb-0">Update job listing information</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.job-listings.show', $jobListing->id) }}" class="btn btn-info">
                                <i class="ri-eye-line align-middle me-1"></i> View
                            </a>
                            <a href="{{ route('admin.job-listings.index') }}" class="btn btn-secondary">
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

                    <form action="{{ route('admin.job-listings.update', $jobListing->id) }}" method="POST" class="needs-validation" enctype="multipart/form-data" novalidate>
                        @csrf
                        @method('PUT')

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
                                           value="{{ old('title', $jobListing->title) }}" 
                                           placeholder="e.g., Senior Software Engineer" 
                                           required 
                                           maxlength="255">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Company Information</label>
                                    <div class="alert alert-info mb-2" style="font-size: 0.875rem;">
                                        <i class="ri-information-line me-1"></i>
                                        Company name, logo, location, and industry are taken from the recruiter's company profile. 
                                        @if($jobListing->recruiter)
                                            <a href="{{ route('admin.users.show', $jobListing->recruiter->id) }}" target="_blank" class="alert-link">View recruiter profile</a> to update company information.
                                        @endif
                                    </div>
                                    @if($jobListing->company_logo)
                                        <div class="mb-2">
                                            <strong>Current Company Logo:</strong>
                                            <div class="mt-2">
                                                <img src="{{ route('company.logo', $jobListing->company_logo) }}" 
                                                     alt="{{ $jobListing->company }} Logo" 
                                                     style="max-width: 150px; max-height: 150px; object-fit: contain; border: 1px solid #ddd; border-radius: 4px; padding: 5px;">
                                            </div>
                                        </div>
                                    @endif
                                    <div class="text-muted small">
                                        <strong>Company:</strong> {{ $jobListing->company }}<br>
                                        <strong>Location:</strong> {{ $jobListing->location }}<br>
                                        <strong>Industry:</strong> {{ $jobListing->industry ?? 'Not specified' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Job Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="5" 
                                      placeholder="Enter job description...">{{ old('description', $jobListing->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @php
                            // Get existing data or old input, fallback to empty array
                            $oldResponsibilities = old('responsibilities', is_array($jobListing->responsibilities) && !empty($jobListing->responsibilities) ? $jobListing->responsibilities : ['']);
                            $oldRequirements = old('requirements', is_array($jobListing->requirements) && !empty($jobListing->requirements) ? $jobListing->requirements : ['']);
                            $oldKeySkills = old('key_skills', is_array($jobListing->key_skills) && !empty($jobListing->key_skills) ? $jobListing->key_skills : ['']);
                            $oldBenefits = old('benefits', is_array($jobListing->benefits) && !empty($jobListing->benefits) ? $jobListing->benefits : ['']);
                        @endphp

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="responsibilities" class="form-label">Responsibilities</label>
                                    <div id="responsibilitiesContainer">
                                        @foreach($oldResponsibilities as $index => $value)
                                            <div class="input-group mb-2">
                                                <input type="text" 
                                                       class="form-control responsibilities-input @error('responsibilities.' . $index) is-invalid @enderror" 
                                                       name="responsibilities[]" 
                                                       value="{{ $value }}"
                                                       placeholder="Enter responsibility...">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-danger removeResponsibility" style="{{ count($oldResponsibilities) > 1 ? 'display: block;' : 'display: none;' }}">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    @error('responsibilities.*')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                    <button type="button" class="btn btn-sm btn-secondary" id="addResponsibility">
                                        <i class="fas fa-plus"></i> Add Responsibility
                                    </button>
                                    <small class="form-text text-muted">Click button to add responsibility</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="requirements" class="form-label">Requirements</label>
                                    <div id="requirementsContainer">
                                        @foreach($oldRequirements as $index => $value)
                                            <div class="input-group mb-2">
                                                <input type="text" 
                                                       class="form-control requirements-input @error('requirements.' . $index) is-invalid @enderror" 
                                                       name="requirements[]" 
                                                       value="{{ $value }}"
                                                       placeholder="Enter requirement...">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-danger removeRequirement" style="{{ count($oldRequirements) > 1 ? 'display: block;' : 'display: none;' }}">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    @error('requirements.*')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                    <button type="button" class="btn btn-sm btn-secondary" id="addRequirement">
                                        <i class="fas fa-plus"></i> Add Requirement
                                    </button>
                                    <small class="form-text text-muted">Click button to add requirement</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="key_skills" class="form-label">Key Skills</label>
                                    <div id="keySkillsContainer">
                                        @foreach($oldKeySkills as $index => $value)
                                            <div class="input-group mb-2">
                                                <input type="text" 
                                                       class="form-control key-skills-input @error('key_skills.' . $index) is-invalid @enderror" 
                                                       name="key_skills[]" 
                                                       value="{{ $value }}"
                                                       placeholder="Enter skill...">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-danger removeKeySkill" style="{{ count($oldKeySkills) > 1 ? 'display: block;' : 'display: none;' }}">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    @error('key_skills.*')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                    <button type="button" class="btn btn-sm btn-secondary" id="addKeySkill">
                                        <i class="fas fa-plus"></i> Add Skill
                                    </button>
                                    <small class="form-text text-muted">Click button to add skill</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="benefits" class="form-label">Benefits</label>
                                    <div id="benefitsContainer">
                                        @foreach($oldBenefits as $index => $value)
                                            <div class="input-group mb-2">
                                                <input type="text" 
                                                       class="form-control benefits-input @error('benefits.' . $index) is-invalid @enderror" 
                                                       name="benefits[]" 
                                                       value="{{ $value }}"
                                                       placeholder="Enter benefit...">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-danger removeBenefit" style="{{ count($oldBenefits) > 1 ? 'display: block;' : 'display: none;' }}">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    @error('benefits.*')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                    <button type="button" class="btn btn-sm btn-secondary" id="addBenefit">
                                        <i class="fas fa-plus"></i> Add Benefit
                                    </button>
                                    <small class="form-text text-muted">Click button to add benefit</small>
                                </div>
                            </div>
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
                                           value="{{ old('salary_min', $jobListing->salary_min) }}" 
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
                                           value="{{ old('salary_max', $jobListing->salary_max) }}" 
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
                                           value="{{ old('salary_display', $jobListing->salary_display) }}" 
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
                                        <option value="wfo" {{ old('work_preference', $jobListing->work_preference) == 'wfo' ? 'selected' : '' }}>Work From Office</option>
                                        <option value="wfh" {{ old('work_preference', $jobListing->work_preference) == 'wfh' ? 'selected' : '' }}>Work From Home</option>
                                        <option value="hybrid" {{ old('work_preference', $jobListing->work_preference) == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                                    </select>
                                    @error('work_preference')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="contract_type" class="form-label">Contract Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('contract_type') is-invalid @enderror" 
                                           id="contract_type" 
                                           name="contract_type" 
                                            required>
                                        <option value="">Select contract type</option>
                                        <option value="Full Time" {{ old('contract_type', $jobListing->contract_type) == 'Full Time' ? 'selected' : '' }}>Full Time</option>
                                        <option value="Contract" {{ old('contract_type', $jobListing->contract_type) == 'Contract' ? 'selected' : '' }}>Contract</option>
                                        <option value="Part Time" {{ old('contract_type', $jobListing->contract_type) == 'Part Time' ? 'selected' : '' }}>Part Time</option>
                                        <option value="Other" {{ old('contract_type', $jobListing->contract_type) == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('contract_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div id="contract_type_other_wrapper" class="mt-2" style="display: {{ old('contract_type', $jobListing->contract_type) == 'Other' ? 'block' : 'none' }};">
                                        <input type="text" 
                                               class="form-control @error('contract_type_other') is-invalid @enderror" 
                                               id="contract_type_other" 
                                               name="contract_type_other" 
                                               value="{{ old('contract_type_other', $jobListing->contract_type_other) }}" 
                                               placeholder="Please specify contract type">
                                        @error('contract_type_other')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="experience_level" class="form-label">Experience Level</label>
                                    <select class="form-select @error('experience_level') is-invalid @enderror" 
                                            id="experience_level" 
                                            name="experience_level">
                                        <option value="">Select experience level</option>
                                        <option value="Entry" {{ old('experience_level', $jobListing->experience_level) == 'Entry' ? 'selected' : '' }}>Entry</option>
                                        <option value="1-3 Years" {{ old('experience_level', $jobListing->experience_level) == '1-3 Years' ? 'selected' : '' }}>1-3 Years</option>
                                        <option value="3-5 Years" {{ old('experience_level', $jobListing->experience_level) == '3-5 Years' ? 'selected' : '' }}>3-5 Years</option>
                                        <option value="5+ Years" {{ old('experience_level', $jobListing->experience_level) == '5+ Years' ? 'selected' : '' }}>5+ Years</option>
                                        <option value="Senior" {{ old('experience_level', $jobListing->experience_level) == 'Senior' ? 'selected' : '' }}>Senior</option>
                                        <option value="Mid Level" {{ old('experience_level', $jobListing->experience_level) == 'Mid Level' ? 'selected' : '' }}>Mid Level</option>
                                        <option value="Other" {{ old('experience_level', $jobListing->experience_level) == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('experience_level')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div id="experience_level_other_wrapper" class="mt-2" style="display: {{ old('experience_level', $jobListing->experience_level) == 'Other' ? 'block' : 'none' }};">
                                        <input type="text" 
                                               class="form-control @error('experience_level_other') is-invalid @enderror" 
                                               id="experience_level_other" 
                                               name="experience_level_other" 
                                               value="{{ old('experience_level_other', $jobListing->experience_level_other) }}" 
                                               placeholder="Please specify experience level">
                                        @error('experience_level_other')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="minimum_degree" class="form-label">Minimum Degree</label>
                                    <select class="form-select @error('minimum_degree') is-invalid @enderror" 
                                            id="minimum_degree" 
                                            name="minimum_degree"
                                            onchange="toggleOtherField('minimum_degree', 'minimum_degree_other_wrapper')">
                                        <option value="">Select minimum degree</option>
                                        <option value="Senior High School" {{ old('minimum_degree', $jobListing->minimum_degree) == 'Senior High School' ? 'selected' : '' }}>Senior High School</option>
                                        <option value="Diploma" {{ old('minimum_degree', $jobListing->minimum_degree) == 'Diploma' ? 'selected' : '' }}>Diploma</option>
                                        <option value="Bachelor" {{ old('minimum_degree', $jobListing->minimum_degree) == 'Bachelor' ? 'selected' : '' }}>Bachelor</option>
                                        <option value="Master" {{ old('minimum_degree', $jobListing->minimum_degree) == 'Master' ? 'selected' : '' }}>Master</option>
                                        <option value="MBA" {{ old('minimum_degree', $jobListing->minimum_degree) == 'MBA' ? 'selected' : '' }}>MBA</option>
                                        <option value="Ph.D" {{ old('minimum_degree', $jobListing->minimum_degree) == 'Ph.D' ? 'selected' : '' }}>Ph.D</option>
                                        <option value="Other" {{ old('minimum_degree', $jobListing->minimum_degree) == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('minimum_degree')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div id="minimum_degree_other_wrapper" class="mt-2" style="display: {{ old('minimum_degree', $jobListing->minimum_degree) == 'Other' ? 'block' : 'none' }};">
                                        <label for="minimum_degree_other" class="form-label">Custom Minimum Degree <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('minimum_degree_other') is-invalid @enderror" 
                                               id="minimum_degree_other" 
                                               name="minimum_degree_other" 
                                               value="{{ old('minimum_degree_other', $jobListing->minimum_degree_other) }}" 
                                               placeholder="Please specify minimum degree"
                                               {{ old('minimum_degree', $jobListing->minimum_degree) == 'Other' ? 'required' : '' }}>
                                        @error('minimum_degree_other')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
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
                                            <option value="{{ $recruiter->id }}" {{ old('recruiter_id', $jobListing->recruiter_id) == $recruiter->id ? 'selected' : '' }}>
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
                                        <option value="draft" {{ old('status', $jobListing->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="active" {{ old('status', $jobListing->status) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status', $jobListing->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="closed" {{ old('status', $jobListing->status) == 'closed' ? 'selected' : '' }}>Closed</option>
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
                                           value="{{ old('posted_at', $jobListing->posted_at ? $jobListing->posted_at->setTimezone('Asia/Jakarta')->format('Y-m-d\TH:i') : '') }}">
                                    @error('posted_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Will auto-set when status changes to active (if not already set)</div>
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
                                               {{ old('verified', $jobListing->verified) ? 'checked' : '' }}>
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

                        <div class="mb-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title mb-3">Job Listing Information</h6>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <p class="mb-1"><strong>Created:</strong></p>
                                            <p class="mb-3">{{ $jobListing->created_at->setTimezone('Asia/Jakarta')->format('d M, Y H:i:s') }}</p>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="mb-1"><strong>Last Updated:</strong></p>
                                            <p class="mb-3">{{ $jobListing->updated_at->setTimezone('Asia/Jakarta')->format('d M, Y H:i:s') }}</p>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="mb-1"><strong>Posted At:</strong></p>
                                            <p class="mb-0">
                                                @if($jobListing->posted_at)
                                                    {{ $jobListing->posted_at->setTimezone('Asia/Jakarta')->format('d M, Y H:i:s') }}
                                                @else
                                                    <span class="text-muted">Not posted yet</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-0 mt-4">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary waves-effect waves-light">
                                    <i class="ri-save-line align-middle me-1"></i> Update Job Listing
                                </button>
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

        // Handle "Other" option for enum fields (global function for inline handlers)
        window.toggleOtherField = function(selectId, wrapperId) {
            const select = document.getElementById(selectId);
            const wrapper = document.getElementById(wrapperId);
            
            if (!select || !wrapper) {
                console.error('Element not found:', selectId, wrapperId);
                return;
            }
            
            const input = wrapper.querySelector('input');
            
            if (select.value === 'Other') {
                wrapper.style.display = 'block';
                if (input) input.required = true;
            } else {
                wrapper.style.display = 'none';
                if (input) {
                    input.required = false;
                    input.value = '';
                }
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleOtherField('contract_type', 'contract_type_other_wrapper');
            toggleOtherField('experience_level', 'experience_level_other_wrapper');
            toggleOtherField('industry', 'industry_other_wrapper');
            toggleOtherField('minimum_degree', 'minimum_degree_other_wrapper');
        });

        // Add event listeners
        const contractTypeSelect = document.getElementById('contract_type');
        if (contractTypeSelect) {
            contractTypeSelect.addEventListener('change', function() {
                toggleOtherField('contract_type', 'contract_type_other_wrapper');
            });
        }

        const experienceLevelSelect = document.getElementById('experience_level');
        if (experienceLevelSelect) {
            experienceLevelSelect.addEventListener('change', function() {
                toggleOtherField('experience_level', 'experience_level_other_wrapper');
            });
        }

        const industrySelect = document.getElementById('industry');
        if (industrySelect) {
            industrySelect.addEventListener('change', function() {
                toggleOtherField('industry', 'industry_other_wrapper');
            });
        }

        const minimumDegreeSelect = document.getElementById('minimum_degree');
        if (minimumDegreeSelect) {
            minimumDegreeSelect.addEventListener('change', function() {
                toggleOtherField('minimum_degree', 'minimum_degree_other_wrapper');
            });
        }

        // Dynamic input fields for Responsibilities, Requirements, Key Skills, and Benefits
        function addDynamicField(containerId, inputName, placeholder, removeButtonClass) {
            const container = document.getElementById(containerId);
            
            // Create new input group
            const newInputGroup = document.createElement('div');
            newInputGroup.className = 'input-group mb-2';
            
            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.className = 'btn btn-danger ' + removeButtonClass;
            removeButton.style.display = 'none';
            removeButton.innerHTML = '<i class="fas fa-times"></i>';
            
            const inputGroupAppend = document.createElement('div');
            inputGroupAppend.className = 'input-group-append';
            inputGroupAppend.appendChild(removeButton);
            
            const input = document.createElement('input');
            input.type = 'text';
            input.className = 'form-control';
            input.name = inputName + '[]';
            input.placeholder = placeholder;
            
            newInputGroup.appendChild(input);
            newInputGroup.appendChild(inputGroupAppend);
            
            container.appendChild(newInputGroup);
            
            // Show remove buttons if more than 1 input
            updateRemoveButtons(container, removeButtonClass);
            
            // Focus on new input
            input.focus();
        }

        function removeDynamicField(button, containerId, removeButtonClass) {
            const container = document.getElementById(containerId);
            const inputGroup = button.closest('.input-group');
            const inputGroups = container.querySelectorAll('.input-group');
            
            // Don't remove if it's the last one
            if (inputGroups.length > 1) {
                inputGroup.remove();
                updateRemoveButtons(container, removeButtonClass);
            }
        }

        function updateRemoveButtons(container, removeButtonClass) {
            const inputGroups = container.querySelectorAll('.input-group');
            const removeButtons = container.querySelectorAll('.' + removeButtonClass);
            
            // Show remove button if more than 1 input, hide if only 1
            removeButtons.forEach(btn => {
                if (inputGroups.length > 1) {
                    btn.style.display = 'block';
                } else {
                    btn.style.display = 'none';
                }
            });
        }

        // Responsibilities
        document.getElementById('addResponsibility').addEventListener('click', function() {
            addDynamicField('responsibilitiesContainer', 'responsibilities', 'Enter responsibility...', 'removeResponsibility');
        });

        document.addEventListener('click', function(e) {
            if (e.target.closest('.removeResponsibility')) {
                removeDynamicField(e.target.closest('.removeResponsibility'), 'responsibilitiesContainer', 'removeResponsibility');
            }
        });

        // Requirements
        document.getElementById('addRequirement').addEventListener('click', function() {
            addDynamicField('requirementsContainer', 'requirements', 'Enter requirement...', 'removeRequirement');
        });

        document.addEventListener('click', function(e) {
            if (e.target.closest('.removeRequirement')) {
                removeDynamicField(e.target.closest('.removeRequirement'), 'requirementsContainer', 'removeRequirement');
            }
        });

        // Key Skills
        document.getElementById('addKeySkill').addEventListener('click', function() {
            addDynamicField('keySkillsContainer', 'key_skills', 'Enter skill...', 'removeKeySkill');
        });

        document.addEventListener('click', function(e) {
            if (e.target.closest('.removeKeySkill')) {
                removeDynamicField(e.target.closest('.removeKeySkill'), 'keySkillsContainer', 'removeKeySkill');
            }
        });

        // Benefits
        document.getElementById('addBenefit').addEventListener('click', function() {
            addDynamicField('benefitsContainer', 'benefits', 'Enter benefit...', 'removeBenefit');
        });

        document.addEventListener('click', function(e) {
            if (e.target.closest('.removeBenefit')) {
                removeDynamicField(e.target.closest('.removeBenefit'), 'benefitsContainer', 'removeBenefit');
            }
        });

        // Initialize remove buttons on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateRemoveButtons(document.getElementById('responsibilitiesContainer'), 'removeResponsibility');
            updateRemoveButtons(document.getElementById('requirementsContainer'), 'removeRequirement');
            updateRemoveButtons(document.getElementById('keySkillsContainer'), 'removeKeySkill');
            updateRemoveButtons(document.getElementById('benefitsContainer'), 'removeBenefit');
        });
    </script>
@endpush

