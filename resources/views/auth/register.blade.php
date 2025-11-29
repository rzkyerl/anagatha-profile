@extends('layouts.app')

@section('title', 'Register - Anagata Executive')
@section('body_class', 'page register-page')

@section('content')
<div class="register-container">
    <div class="register-card">
        {{-- Left Section: Gradient Background with Text --}}
        <div class="register-left-section">
            <div class="register-header-row">
                <h2 class="register-signup-label">Register</h2>
                <div class="register-logo">
                    <img src="/assets/hero-sec.png" alt="Anagata Executive Logo" />
                </div>
            </div>
            <div class="register-text-content">
                <p class="register-text-small">You can easily</p>
                <h2 class="register-text-large">Search and find your dream job is now easier than ever. Just browse a job and apply if you need to.</h2>
            </div>
        </div>

        {{-- Right Section: Register Form --}}
        <div class="register-right-section">
            <div class="register-form-wrapper">
                <h1 class="register-title">Create Account</h1>
                <p class="register-subtitle">Start your journey to a better career</p>

                {{-- Social Login --}}
                <div class="social-login social-login-top">
                    <div class="social-login-buttons">
                        <a href="#" class="social-btn social-btn--facebook" aria-label="Sign up with Facebook">
                            <i class="fa-brands fa-facebook-f" aria-hidden="true"></i>
                        </a>
                        <a href="#" class="social-btn social-btn--google" aria-label="Sign up with Google">
                            <svg width="20" height="20" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                            </svg>
                        </a>
                        <a href="#" class="social-btn social-btn--linkedin" aria-label="Sign up with LinkedIn">
                            <i class="fa-brands fa-linkedin-in" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>

                {{-- Separator Text --}}
                <div class="register-separator">
                    <span>or use your email for registration:</span>
                </div>

                <form method="POST" action="{{ route('register') }}" class="register-form">
                    @csrf

                    {{-- First Name Field --}}
                    <div class="form-group">
                        <div class="form-input-wrapper">
                            <i class="form-input-icon fa-solid fa-user"></i>
                            <input 
                                type="text" 
                                id="first_name" 
                                name="first_name" 
                                class="form-input @error('first_name') is-invalid @enderror" 
                                placeholder="First Name"
                                value="{{ old('first_name') }}"
                                required 
                                autofocus
                            />
                        </div>
                        @error('first_name')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Last Name Field --}}
                    <div class="form-group">
                        <div class="form-input-wrapper">
                            <i class="form-input-icon fa-solid fa-user"></i>
                            <input 
                                type="text" 
                                id="last_name" 
                                name="last_name" 
                                class="form-input @error('last_name') is-invalid @enderror" 
                                placeholder="Last Name (Optional)"
                                value="{{ old('last_name') }}"
                                @optional
                            />
                        </div>
                        @error('last_name')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Email Field --}}
                    <div class="form-group">
                        <div class="form-input-wrapper">
                            <i class="form-input-icon fa-solid fa-envelope"></i>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                class="form-input @error('email') is-invalid @enderror" 
                                placeholder="Email"
                                value="{{ old('email') }}"
                                required
                            />
                        </div>
                        @error('email')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Password Field --}}
                    <div class="form-group">
                        <div class="form-input-wrapper">
                            <i class="form-input-icon fa-solid fa-lock"></i>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                class="form-input @error('password') is-invalid @enderror" 
                                placeholder="Password"
                                required
                            />
                            <button type="button" class="password-toggle" aria-label="Toggle password visibility">
                                <i class="fa-solid fa-eye" aria-hidden="true"></i>
                            </button>
                        </div>
                        @error('password')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Confirm Password Field --}}
                    <div class="form-group">
                        <div class="form-input-wrapper">
                            <i class="form-input-icon fa-solid fa-lock"></i>
                            <input 
                                type="password" 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                class="form-input" 
                                placeholder="Confirm Password"
                                required
                            />
                            <button type="button" class="password-toggle" aria-label="Toggle password visibility">
                                <i class="fa-solid fa-eye" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" class="register-submit-btn">
                        Register
                    </button>
                </form>

                {{-- Login Link --}}
                <div class="register-footer">
                    <p>Already have an account? <a href="{{ route('login') }}" class="login-link">Login</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Password toggle functionality
    document.addEventListener('DOMContentLoaded', function() {
        const passwordToggles = document.querySelectorAll('.password-toggle');
        
        passwordToggles.forEach(toggle => {
            toggle.addEventListener('click', function() {
                const input = this.parentElement.querySelector('input');
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                
                const icon = this.querySelector('i');
                if (type === 'password') {
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                } else {
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                }
            });
        });
    });
</script>
@endpush
@endsection

