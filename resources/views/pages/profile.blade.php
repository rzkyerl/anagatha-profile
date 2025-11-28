@extends('layouts.app')

@section('title', 'Profile - Anagata Executive')
@section('body_class', 'page profile-page')

@section('content')
<div class="profile-container">
    <div class="profile-card">
        {{-- Header Section --}}
        <div class="profile-header">
            <h1 class="profile-title">PROFILE</h1>
        </div>

        <form method="POST" action="{{ route('profile.update') }}" class="profile-form" id="profileForm" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="profile-content">
                {{-- Left Section: Avatar & Social Media --}}
                <div class="profile-left">
                    {{-- Avatar Picture Section --}}
                    <div class="profile-avatar-section">
                        <h2 class="profile-section-title">Avatar picture</h2>
                        <div class="avatar-upload-wrapper">
                            <input 
                                type="file" 
                                id="avatar" 
                                name="avatar" 
                                class="avatar-upload-input" 
                                accept="image/*"
                            />
                            <label for="avatar" class="avatar-preview">
                                <div class="avatar-placeholder">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                                <span class="avatar-upload-text">Upload Picture</span>
                            </label>
                            <div class="avatar-preview-image" id="avatarPreview"></div>
                        </div>
                    </div>

                    {{-- Social Media Section --}}
                    <div class="profile-social-section">
                        <h2 class="profile-section-title">Social Media</h2>
                        <div class="social-media-list">
                            <div class="social-media-item">
                                <div class="social-media-icon social-media-icon--github">
                                    <i class="fa-brands fa-github"></i>
                                </div>
                                <div class="social-media-input-wrapper">
                                    <input 
                                        type="url" 
                                        id="github" 
                                        name="github" 
                                        class="social-media-input @error('github') is-invalid @enderror" 
                                        placeholder="Add Github"
                                        value="{{ old('github', auth()->user()?->github ?? '') }}"
                                    />
                                </div>
                            </div>

                            <div class="social-media-item">
                                <div class="social-media-icon social-media-icon--linkedin">
                                    <i class="fa-brands fa-linkedin"></i>
                                </div>
                                <div class="social-media-input-wrapper">
                                    <input 
                                        type="url" 
                                        id="linkedin" 
                                        name="linkedin" 
                                        class="social-media-input @error('linkedin') is-invalid @enderror" 
                                        placeholder="Add Linkedin"
                                        value="{{ old('linkedin', auth()->user()?->linkedin ?? '') }}"
                                    />
                                </div>
                            </div>

                            <div class="social-media-item">
                                <div class="social-media-icon social-media-icon--x">
                                    <i class="fa-brands fa-x-twitter"></i>
                                </div>
                                <div class="social-media-input-wrapper">
                                    <input 
                                        type="url" 
                                        id="x" 
                                        name="x" 
                                        class="social-media-input @error('x') is-invalid @enderror" 
                                        placeholder="Add X"
                                        value="{{ old('x', auth()->user()?->x ?? '') }}"
                                    />
                                </div>
                            </div>

                            <div class="social-media-item">
                                <div class="social-media-icon social-media-icon--instagram">
                                    <i class="fa-brands fa-instagram"></i>
                                </div>
                                <div class="social-media-input-wrapper">
                                    <input 
                                        type="url" 
                                        id="instagram" 
                                        name="instagram" 
                                        class="social-media-input @error('instagram') is-invalid @enderror" 
                                        placeholder="Add Instagram"
                                        value="{{ old('instagram', auth()->user()?->instagram ?? '') }}"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Section: Form Fields --}}
                <div class="profile-right">
                    <div class="profile-form-fields">
                        <div class="profile-form-group">
                            <label for="name" class="profile-form-label">
                                Name:
                            </label>
                            <div class="profile-form-input-wrapper">
                                <input 
                                    type="text" 
                                    id="name" 
                                    name="name" 
                                    class="profile-form-input @error('name') is-invalid @enderror" 
                                    placeholder="Enter your name"
                                    value="{{ old('name', auth()->user()?->name ?? '') }}"
                                    required
                                />
                                <i class="fa-solid fa-circle-info profile-form-info-icon"></i>
                            </div>
                            @error('name')
                                <span class="profile-form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="profile-form-group">
                            <label for="email" class="profile-form-label">
                                E-mail:
                            </label>
                            <div class="profile-form-input-wrapper">
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    class="profile-form-input @error('email') is-invalid @enderror" 
                                    placeholder="your.email@example.com"
                                    value="{{ old('email', auth()->user()?->email ?? '') }}"
                                    required
                                />
                                <i class="fa-solid fa-circle-info profile-form-info-icon"></i>
                            </div>
                            @error('email')
                                <span class="profile-form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="profile-form-group">
                            <label for="phone" class="profile-form-label">
                                Number Phone:
                            </label>
                            <div class="profile-form-input-wrapper">
                                <input 
                                    type="tel" 
                                    id="phone" 
                                    name="phone" 
                                    class="profile-form-input @error('phone') is-invalid @enderror" 
                                    placeholder="+62 812-3456-7890"
                                    value="{{ old('phone', auth()->user()?->phone ?? '') }}"
                                    required
                                />
                                <i class="fa-solid fa-circle-info profile-form-info-icon"></i>
                            </div>
                            @error('phone')
                                <span class="profile-form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="profile-form-group">
                            <label for="password" class="profile-form-label">
                                Password:
                            </label>
                            <div class="profile-form-input-wrapper">
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password" 
                                    class="profile-form-input @error('password') is-invalid @enderror" 
                                    placeholder="Enter new password"
                                />
                                <i class="fa-solid fa-circle-info profile-form-info-icon"></i>
                            </div>
                            @error('password')
                                <span class="profile-form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="profile-form-group">
                            <label for="password_confirmation" class="profile-form-label">
                                Repeat Password:
                            </label>
                            <div class="profile-form-input-wrapper">
                                <input 
                                    type="password" 
                                    id="password_confirmation" 
                                    name="password_confirmation" 
                                    class="profile-form-input @error('password_confirmation') is-invalid @enderror" 
                                    placeholder="Repeat new password"
                                />
                                <i class="fa-solid fa-circle-info profile-form-info-icon"></i>
                            </div>
                            @error('password_confirmation')
                                <span class="profile-form-error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Submit Button --}}
            <div class="profile-actions">
                <button type="submit" class="profile-update-btn" id="updateBtn">
                    Update Information
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script nonce="{{ $cspNonce ?? '' }}">
    document.addEventListener('DOMContentLoaded', function() {
        // Avatar preview functionality
        const avatarInput = document.getElementById('avatar');
        const avatarPreview = document.getElementById('avatarPreview');
        const avatarPlaceholder = document.querySelector('.avatar-placeholder');
        const avatarUploadText = document.querySelector('.avatar-upload-text');

        if (avatarInput) {
            avatarInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        avatarPreview.innerHTML = `<img src="${e.target.result}" alt="Avatar Preview" />`;
                        avatarPreview.style.display = 'block';
                        avatarPlaceholder.style.display = 'none';
                        avatarUploadText.textContent = 'Change Picture';
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        // Form validation
        const form = document.getElementById('profileForm');
        const updateBtn = document.getElementById('updateBtn');

        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                let isValid = true;
                const errors = [];

                // Validate required fields
                const requiredFields = form.querySelectorAll('input[required], textarea[required]');
                requiredFields.forEach(field => {
                    const value = field.value ? field.value.trim() : '';
                    if (!value) {
                        isValid = false;
                        field.classList.add('is-invalid');
                        errors.push(`${field.previousElementSibling?.textContent || field.name} is required`);
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });

                // Validate password match
                const password = document.getElementById('password');
                const passwordConfirmation = document.getElementById('password_confirmation');
                
                if (password && passwordConfirmation && password.value) {
                    if (password.value !== passwordConfirmation.value) {
                        isValid = false;
                        password.classList.add('is-invalid');
                        passwordConfirmation.classList.add('is-invalid');
                        errors.push('Passwords do not match');
                    }
                }

                // Validate email format
                const emailField = document.getElementById('email');
                if (emailField && emailField.value) {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(emailField.value)) {
                        isValid = false;
                        emailField.classList.add('is-invalid');
                        errors.push('Please enter a valid email address');
                    }
                }

                if (!isValid) {
                    alert('Please fill in all required fields correctly.\n\n' + errors.join('\n'));
                    const firstError = form.querySelector('.is-invalid');
                    if (firstError) {
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                    return false;
                }

                // If validation passes, submit the form
                form.submit();
            });
        }
    });
</script>
@endpush
@endsection

