@extends('admin.admin_layouts.app')

@section('title', 'Edit User')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="javascript: void(0);">Anagata Executive</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.users.index') }}">Users</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.users.show', $user->id) }}">{{ $user->first_name }} {{ $user->last_name }}</a>
    </li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 mx-auto">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h4 class="card-title mb-1">Edit User</h4>
                            <p class="card-title-desc mb-0">Update user information</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-info">
                                <i class="ri-eye-line align-middle me-1"></i> View
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
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

                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('first_name') is-invalid @enderror" 
                                           id="first_name" 
                                           name="first_name" 
                                           value="{{ old('first_name', $user->first_name) }}" 
                                           placeholder="Enter first name" 
                                           required>
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('last_name') is-invalid @enderror" 
                                           id="last_name" 
                                           name="last_name" 
                                           value="{{ old('last_name', $user->last_name) }}" 
                                           placeholder="Enter last name" 
                                           @optional>
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" 
                                    class="form-control @error('email') is-invalid @enderror" 
                                    id="email" 
                                    name="email" 
                                    value="{{ old('email', $user->email) }}" 
                                    placeholder="Enter email address" 
                                    required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                                    <select class="form-select @error('role') is-invalid @enderror" 
                                        id="role" 
                                        name="role" 
                                        required>
                                        <option value="">Select role</option>
                                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                                        <option value="recruiter" {{ old('role', $user->role) == 'recruiter' ? 'selected' : '' }}>Recruiter</option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password <span class="text-muted">(Leave blank to keep current password)</span></label>
                                    <input type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           placeholder="Enter new password (min. 8 characters)" 
                                           minlength="8">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Leave blank if you don't want to change the password.</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                                    <input type="password" 
                                           class="form-control @error('password_confirmation') is-invalid @enderror" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           placeholder="Confirm new password" 
                                           minlength="8">
                                    @error('password_confirmation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title mb-3">Account Information</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Status:</strong></p>
                                            <p class="mb-3">
                                                @if ($user->email_verified_at)
                                                    <span class="badge bg-success">
                                                        <i class="ri-checkbox-circle-line me-1"></i>Verified
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning">
                                                        <i class="ri-time-line me-1"></i>Unverified
                                                    </span>
                                                @endif
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Joined:</strong></p>
                                            <p class="mb-0">{{ $user->created_at->setTimezone('Asia/Jakarta')->format('d M, Y H:i:s') }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Last Updated:</strong></p>
                                            <p class="mb-0">{{ $user->updated_at->setTimezone('Asia/Jakarta')->format('d M, Y H:i:s') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-0">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary waves-effect waves-light">
                                    <i class="ri-save-line align-middle me-1"></i> Update User
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

        // Password confirmation validation (only if password is filled)
        var passwordField = document.getElementById('password');
        var passwordConfirmationField = document.getElementById('password_confirmation');

        function validatePasswordMatch() {
            var password = passwordField.value;
            var passwordConfirmation = passwordConfirmationField.value;
            
            // Only validate if password is filled
            if (password && passwordConfirmation) {
                if (password !== passwordConfirmation) {
                    passwordConfirmationField.setCustomValidity('Passwords do not match');
                } else {
                    passwordConfirmationField.setCustomValidity('');
                }
            } else if (!password && passwordConfirmation) {
                // If password is empty but confirmation is filled, clear confirmation
                passwordConfirmationField.setCustomValidity('');
            } else {
                passwordConfirmationField.setCustomValidity('');
            }
        }

        passwordField.addEventListener('input', function() {
            validatePasswordMatch();
            // If password is filled, make confirmation required
            if (this.value) {
                passwordConfirmationField.setAttribute('required', 'required');
            } else {
                passwordConfirmationField.removeAttribute('required');
            }
        });

        passwordConfirmationField.addEventListener('input', function() {
            validatePasswordMatch();
        });
    </script>
@endpush
