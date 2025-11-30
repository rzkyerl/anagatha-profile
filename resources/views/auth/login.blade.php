@extends('layouts.app')

@section('title', 'Login - Anagata Executive')
@section('body_class', 'page login-page')

@section('content')
<div class="register-container">
    <div class="register-card">
        {{-- Left Section: Login Form --}}
        <div class="register-right-section">
            <div class="register-form-wrapper">
                <h1 class="register-title">Welcome Back!</h1>
                <p class="register-subtitle">Pick up your career journey again</p>

                {{-- Social Login --}}
                <div class="social-login social-login-top">
                    <div class="social-login-buttons">
                        <a href="#" class="social-btn social-btn--facebook" aria-label="Sign in with Facebook">
                            <i class="fa-brands fa-facebook-f" aria-hidden="true"></i>
                        </a>
                        <a href="#" class="social-btn social-btn--google" aria-label="Sign in with Google">
                            <svg width="20" height="20" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                            </svg>
                        </a>
                        <a href="#" class="social-btn social-btn--linkedin" aria-label="Sign in with LinkedIn">
                            <i class="fa-brands fa-linkedin-in" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>

                {{-- Separator Text --}}
                <div class="register-separator">
                    <span>or use your email for login:</span>
                </div>

                <form method="POST" action="{{ route('login') }}" class="register-form" id="loginForm">
                    @csrf

                    {{-- Error Messages --}}
                    @if($errors->any())
                        <div class="alert alert-danger" role="alert" style="margin-bottom: 1.5rem; padding: 0.75rem 1rem; background-color: #fee; border: 1px solid #fcc; border-radius: 0.5rem; color: #c33;">
                            <div style="font-weight: 600; margin-bottom: 0.5rem;">Login Failed</div>
                            <ul style="margin: 0; padding-left: 1.5rem;">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Success Message (if redirected from registration) --}}
                    @if(session('status'))
                        <div class="alert alert-success" role="alert" style="margin-bottom: 1.5rem; padding: 0.75rem 1rem; background-color: #dfd; border: 1px solid #cfc; border-radius: 0.5rem; color: #3c3;">
                            {{ session('status') }}
                        </div>
                    @endif

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
                                autofocus
                                autocomplete="email"
                            />
                        </div>
                        @error('email')
                            <span class="form-error" style="display: block; margin-top: 0.5rem; color: #c33; font-size: 0.875rem;">{{ $message }}</span>
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
                                autocomplete="current-password"
                            />
                            <button type="button" class="password-toggle" aria-label="Toggle password visibility">
                                <i class="fa-solid fa-eye" aria-hidden="true"></i>
                            </button>
                        </div>
                        @error('password')
                            <span class="form-error" style="display: block; margin-top: 0.5rem; color: #c33; font-size: 0.875rem;">{{ $message }}</span>
                        @enderror
                        {{-- Forgot Password Link --}}
                        <div class="form-forgot-password">
                            <a href="{{ route('password.request') }}" class="forgot-password-link">Forgot Password?</a>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" class="register-submit-btn">
                        Login
                    </button>
                </form>

                {{-- Register Link --}}
                <div class="register-footer">
                    <p>Don't have an account? <a href="{{ route('register.role') }}" class="register-link">Register</a></p>
                </div>
            </div>
        </div>

        {{-- Right Section: Gradient Background with Text --}}
        <div class="register-left-section">
            <div class="register-header-row">
                <h2 class="register-signup-label">Login</h2>
                <div class="register-logo">
                    <img src="/assets/hero-sec.png" alt="Anagata Executive Logo" />
                </div>
            </div>
            <div class="register-text-content">
                <p class="register-text-small">You can easily</p>
                <h2 class="register-text-large">Search and find your dream job is now easier than ever. Just browse a job and apply if you need to.</h2>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script nonce="{{ $cspNonce ?? '' }}">
    // Password toggle functionality with auto-hide
    (function() {
        'use strict';
        
        function initPasswordToggle() {
            const passwordToggles = document.querySelectorAll('.password-toggle');
            const AUTO_HIDE_DURATION = 3000; // 3 seconds
            
            if (passwordToggles.length === 0) {
                return;
            }
            
            passwordToggles.forEach(function(toggle) {
                let hideTimer = null;
                
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Find the input field - look for input in the same form-input-wrapper
                    const inputWrapper = this.closest('.form-input-wrapper');
                    if (!inputWrapper) {
                        return;
                    }
                    
                    // Find the input field - get the first input that is not a button
                    const input = inputWrapper.querySelector('input[type="password"], input[type="text"]');
                    
                    if (!input || input.tagName.toLowerCase() !== 'input') {
                        return;
                    }
                    
                    // Clear any existing timer
                    if (hideTimer) {
                        clearTimeout(hideTimer);
                        hideTimer = null;
                    }
                    
                    // Toggle password visibility
                    const isPassword = input.type === 'password';
                    const icon = this.querySelector('i');
                    
                    if (isPassword) {
                        // Show password
                        input.type = 'text';
                        if (icon) {
                            icon.classList.remove('fa-eye');
                            icon.classList.add('fa-eye-slash');
                        }
                        
                        // Auto-hide after duration
                        const self = this;
                        const inputRef = input;
                        hideTimer = setTimeout(function() {
                            inputRef.type = 'password';
                            const currentIcon = self.querySelector('i');
                            if (currentIcon) {
                                currentIcon.classList.remove('fa-eye-slash');
                                currentIcon.classList.add('fa-eye');
                            }
                            hideTimer = null;
                        }, AUTO_HIDE_DURATION);
                    } else {
                        // Hide password immediately
                        input.type = 'password';
                        if (icon) {
                            icon.classList.remove('fa-eye-slash');
                            icon.classList.add('fa-eye');
                        }
                    }
                });
            });
        }
        
        // Initialize when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initPasswordToggle);
        } else {
            // DOM is already ready
            initPasswordToggle();
        }
    })();

    // Form validation
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;

            // Clear previous invalid states
            loginForm.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            loginForm.querySelectorAll('.form-error').forEach(el => el.remove());

            let isValid = true;

            // Validate email
            if (!email) {
                isValid = false;
                const emailInput = document.getElementById('email');
                emailInput.classList.add('is-invalid');
                const errorSpan = document.createElement('span');
                errorSpan.classList.add('form-error');
                errorSpan.style.cssText = 'display: block; margin-top: 0.5rem; color: #c33; font-size: 0.875rem;';
                errorSpan.textContent = 'Email is required.';
                emailInput.closest('.form-group').appendChild(errorSpan);
            } else {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    isValid = false;
                    const emailInput = document.getElementById('email');
                    emailInput.classList.add('is-invalid');
                    const errorSpan = document.createElement('span');
                    errorSpan.classList.add('form-error');
                    errorSpan.style.cssText = 'display: block; margin-top: 0.5rem; color: #c33; font-size: 0.875rem;';
                    errorSpan.textContent = 'Please enter a valid email address.';
                    emailInput.closest('.form-group').appendChild(errorSpan);
                }
            }

            // Validate password
            if (!password) {
                isValid = false;
                const passwordInput = document.getElementById('password');
                passwordInput.classList.add('is-invalid');
                const errorSpan = document.createElement('span');
                errorSpan.classList.add('form-error');
                errorSpan.style.cssText = 'display: block; margin-top: 0.5rem; color: #c33; font-size: 0.875rem;';
                errorSpan.textContent = 'Password is required.';
                passwordInput.closest('.form-group').appendChild(errorSpan);
            }

            if (!isValid) {
                e.preventDefault();
                const firstError = loginForm.querySelector('.is-invalid');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstError.focus();
                }
                return false;
            }

            // If validation passes, form will submit naturally
        });
    }
</script>
@endpush
@endsection
