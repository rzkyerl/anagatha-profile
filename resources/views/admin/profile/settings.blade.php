@extends('admin.admin_layouts.app')

@section('title', 'Profile Settings')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="javascript: void(0);">Anagata Executive</a>
    </li>
    <li class="breadcrumb-item active">Profile Settings</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Profile Settings</h6>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="ri-checkbox-circle-line me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="ri-error-warning-line me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route($user->role === 'recruiter' ? 'recruiter.profile.update' : 'admin.profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            {{-- Avatar Section --}}
                            <div class="col-md-4 mb-4">
                                <div class="text-center">
                                    <div class="mb-3">
                                        @if($user->avatar)
                                            <img src="{{ route('admin.profile.avatar', $user->avatar) }}" 
                                                 alt="Avatar" 
                                                 class="img-thumbnail rounded-circle" 
                                                 style="width: 150px; height: 150px; object-fit: cover;">
                                        @else
                                            <img src="{{ asset('dashboard/images/users/profile-default.jpg') }}" 
                                                 alt="Avatar" 
                                                 class="img-thumbnail rounded-circle" 
                                                 style="width: 150px; height: 150px; object-fit: cover;">
                                        @endif
                                    </div>
                                    <div class="mb-3">
                                        <label for="avatar" class="form-label">Change Avatar</label>
                                        <input type="file" 
                                               class="form-control @error('avatar') is-invalid @enderror" 
                                               id="avatar" 
                                               name="avatar" 
                                               accept="image/*">
                                        @error('avatar')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Max size: 2MB. Supported formats: JPG, PNG, GIF</small>
                                    </div>
                                </div>
                            </div>

                            {{-- Profile Information --}}
                            <div class="col-md-8">
                                <h5 class="mb-3">Personal Information</h5>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('first_name') is-invalid @enderror" 
                                               id="first_name" 
                                               name="first_name" 
                                               value="{{ old('first_name', $user->first_name) }}" 
                                               required>
                                        @error('first_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="last_name" class="form-label">Last Name</label>
                                        <input type="text" 
                                               class="form-control @error('last_name') is-invalid @enderror" 
                                               id="last_name" 
                                               name="last_name" 
                                               value="{{ old('last_name', $user->last_name) }}">
                                        @error('last_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" 
                                               class="form-control @error('email') is-invalid @enderror" 
                                               id="email" 
                                               name="email" 
                                               value="{{ old('email', $user->email) }}" 
                                               required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="phone" class="form-label">Phone</label>
                                        <input type="text" 
                                               class="form-control @error('phone') is-invalid @enderror" 
                                               id="phone" 
                                               name="phone" 
                                               value="{{ old('phone', $user->phone) }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Recruiter Specific Fields --}}
                                @if($user->role === 'recruiter')
                                    <hr class="my-4">
                                    <h5 class="mb-3">Company Information</h5>
                                    
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="company_name" class="form-label">Company Name</label>
                                            <input type="text" 
                                                   class="form-control @error('company_name') is-invalid @enderror" 
                                                   id="company_name" 
                                                   name="company_name" 
                                                   value="{{ old('company_name', $user->company_name) }}">
                                            @error('company_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="job_title" class="form-label">Job Title</label>
                                            <select class="form-select @error('job_title') is-invalid @enderror" 
                                                    id="job_title" 
                                                    name="job_title">
                                                <option value="">Select job title</option>
                                                <option value="HR Manager" {{ old('job_title', $user->job_title) == 'HR Manager' ? 'selected' : '' }}>HR Manager</option>
                                                <option value="HR Business Partner" {{ old('job_title', $user->job_title) == 'HR Business Partner' ? 'selected' : '' }}>HR Business Partner</option>
                                                <option value="Talent Acquisition Specialist" {{ old('job_title', $user->job_title) == 'Talent Acquisition Specialist' ? 'selected' : '' }}>Talent Acquisition Specialist</option>
                                                <option value="Recruitment Manager" {{ old('job_title', $user->job_title) == 'Recruitment Manager' ? 'selected' : '' }}>Recruitment Manager</option>
                                                <option value="HR Director" {{ old('job_title', $user->job_title) == 'HR Director' ? 'selected' : '' }}>HR Director</option>
                                                <option value="HR Coordinator" {{ old('job_title', $user->job_title) == 'HR Coordinator' ? 'selected' : '' }}>HR Coordinator</option>
                                                <option value="Recruiter" {{ old('job_title', $user->job_title) == 'Recruiter' ? 'selected' : '' }}>Recruiter</option>
                                                <option value="Senior Recruiter" {{ old('job_title', $user->job_title) == 'Senior Recruiter' ? 'selected' : '' }}>Senior Recruiter</option>
                                                <option value="HR Generalist" {{ old('job_title', $user->job_title) == 'HR Generalist' ? 'selected' : '' }}>HR Generalist</option>
                                                <option value="People Operations Manager" {{ old('job_title', $user->job_title) == 'People Operations Manager' ? 'selected' : '' }}>People Operations Manager</option>
                                                <option value="Other" {{ old('job_title', $user->job_title) == 'Other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                            @error('job_title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div id="job_title_other_wrapper" class="mt-2" style="display: {{ old('job_title', $user->job_title) == 'Other' ? 'block' : 'none' }};">
                                                <input type="text" 
                                                       class="form-control @error('job_title_other') is-invalid @enderror" 
                                                       id="job_title_other" 
                                                       name="job_title_other" 
                                                       value="{{ old('job_title_other', $user->job_title_other) }}" 
                                                       placeholder="Please specify job title">
                                                @error('job_title_other')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                {{-- Minimum Degree Field (for Recruiter and Admin) --}}
                                @if($user->role === 'recruiter' || $user->role === 'admin')
                                    <hr class="my-4">
                                    <h5 class="mb-3">Education Information</h5>
                                    
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <label for="minimum_degree" class="form-label">Minimum Degree</label>
                                            <select class="form-select @error('minimum_degree') is-invalid @enderror" 
                                                    id="minimum_degree" 
                                                    name="minimum_degree"
                                                    onchange="toggleMinimumDegreeOtherField()">
                                                <option value="">Select minimum degree</option>
                                                <option value="Senior High School" {{ old('minimum_degree', $user->minimum_degree) == 'Senior High School' ? 'selected' : '' }}>Senior High School</option>
                                                <option value="Diploma" {{ old('minimum_degree', $user->minimum_degree) == 'Diploma' ? 'selected' : '' }}>Diploma</option>
                                                <option value="Bachelor" {{ old('minimum_degree', $user->minimum_degree) == 'Bachelor' ? 'selected' : '' }}>Bachelor</option>
                                                <option value="Master" {{ old('minimum_degree', $user->minimum_degree) == 'Master' ? 'selected' : '' }}>Master</option>
                                                <option value="MBA" {{ old('minimum_degree', $user->minimum_degree) == 'MBA' ? 'selected' : '' }}>MBA</option>
                                                <option value="Ph.D" {{ old('minimum_degree', $user->minimum_degree) == 'Ph.D' ? 'selected' : '' }}>Ph.D</option>
                                                <option value="Other" {{ old('minimum_degree', $user->minimum_degree) == 'Other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                            @error('minimum_degree')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div id="minimum_degree_other_wrapper" class="mt-2" style="display: {{ old('minimum_degree', $user->minimum_degree) == 'Other' ? 'block' : 'none' }};">
                                                <label for="minimum_degree_other" class="form-label">Custom Minimum Degree <span class="text-danger">*</span></label>
                                                <input type="text" 
                                                       class="form-control @error('minimum_degree_other') is-invalid @enderror" 
                                                       id="minimum_degree_other" 
                                                       name="minimum_degree_other" 
                                                       value="{{ old('minimum_degree_other', $user->minimum_degree_other) }}" 
                                                       placeholder="Please specify minimum degree"
                                                       {{ old('minimum_degree', $user->minimum_degree) == 'Other' ? 'required' : '' }}>
                                                @error('minimum_degree_other')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <hr class="my-4">
                                <h5 class="mb-3">Change Password</h5>
                                <p class="text-muted small">Leave blank if you don't want to change your password.</p>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="password" class="form-label">New Password</label>
                                        <input type="password" 
                                               class="form-control @error('password') is-invalid @enderror" 
                                               id="password" 
                                               name="password">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                        <input type="password" 
                                               class="form-control" 
                                               id="password_confirmation" 
                                               name="password_confirmation">
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ri-save-line me-1"></i> Update Profile
                                    </button>
                                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                                        <i class="ri-close-line me-1"></i> Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script nonce="{{ $cspNonce ?? '' }}">
        // Global function for minimum degree toggle (can be called from inline onchange)
        @if($user->role === 'recruiter' || $user->role === 'admin')
        function toggleMinimumDegreeOtherField() {
            const select = document.getElementById('minimum_degree');
            const wrapper = document.getElementById('minimum_degree_other_wrapper');
            const input = document.getElementById('minimum_degree_other');
            
            if (!select || !wrapper || !input) {
                return;
            }
            
            if (select.value === 'Other') {
                wrapper.style.display = 'block';
                input.required = true;
            } else {
                wrapper.style.display = 'none';
                input.required = false;
                input.value = '';
            }
        }
        @endif

        // Wait for DOM to be fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Preview avatar before upload
            const avatarInput = document.getElementById('avatar');
            if (avatarInput) {
                avatarInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const img = document.querySelector('.img-thumbnail');
                            if (img) {
                                img.src = e.target.result;
                            }
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            // Handle "Other" option for job_title (recruiter only)
            @if($user->role === 'recruiter')
            function toggleJobTitleOther() {
                const select = document.getElementById('job_title');
                const wrapper = document.getElementById('job_title_other_wrapper');
                const input = document.getElementById('job_title_other');
                
                if (select && wrapper && input) {
                    if (select.value === 'Other') {
                        wrapper.style.display = 'block';
                        input.required = true;
                    } else {
                        wrapper.style.display = 'none';
                        input.required = false;
                        input.value = '';
                    }
                }
            }

            const jobTitleSelect = document.getElementById('job_title');
            if (jobTitleSelect) {
                toggleJobTitleOther();
                jobTitleSelect.addEventListener('change', toggleJobTitleOther);
            }
            @endif

            // Initialize minimum degree field on page load
            @if($user->role === 'recruiter' || $user->role === 'admin')
            if (typeof toggleMinimumDegreeOtherField === 'function') {
                toggleMinimumDegreeOtherField();
            }
            @endif
        });
    </script>
@endsection

