@extends('layouts.auth')

@section('title', 'Reset Password - Anagata Executive')
@section('body_class', 'page reset-password-page')

@section('content')
    {{-- Toast Notifications --}}
    @if (session('status'))
        <div class="toast-stack" data-toast>
            <div class="toast toast--{{ session('toast_type', 'success') }}" role="status" aria-live="polite">
                <div class="toast__icon">
                    @if (session('toast_type', 'success') === 'success')
                        <i class="fa-solid fa-circle-check" aria-hidden="true"></i>
                    @elseif (session('toast_type') === 'warning')
                        <i class="fa-solid fa-triangle-exclamation" aria-hidden="true"></i>
                    @else
                        <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
                    @endif
                </div>
                <div class="toast__body">
                    <p class="toast__title">
                        @if (session('toast_type', 'success') === 'success')
                            Success
                        @elseif (session('toast_type') === 'warning')
                            Warning
                        @else
                            Error
                        @endif
                    </p>
                    <p class="toast__message">{{ session('status') }}</p>
                </div>
                <button type="button" class="toast__close" aria-label="Close toast">
                    <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                </button>
            </div>
        </div>
    @endif

    @if ($errors->any() && !session('status'))
        <div class="toast-stack" data-toast>
            <div class="toast toast--error" role="alert" aria-live="assertive">
                <div class="toast__icon">
                    <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
                </div>
                <div class="toast__body">
                    <p class="toast__title">Validation Error</p>
                    <p class="toast__message">
                        @foreach ($errors->all() as $error)
                            {{ $error }}
                        @endforeach
                    </p>
                </div>
                <button type="button" class="toast__close" aria-label="Close toast">
                    <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                </button>
            </div>
        </div>
    @endif

<div class="register-container">
    <div class="register-card">
        {{-- Left Section: Gradient Background with Text --}}
        <div class="register-left-section">
            <div class="register-header-row">
                <h2 class="register-signup-label">Reset Password</h2>
                <div class="register-logo">
                    <img src="/assets/hero-sec.png" alt="Anagata Executive Logo" />
                </div>
            </div>
            <div class="register-text-content">
                <p class="register-text-small">Create New Password</p>
                <h2 class="register-text-large">Enter your new password below. Make sure it's strong and secure.</h2>
            </div>
        </div>

        {{-- Right Section: Reset Password Form --}}
        <div class="register-right-section">
            <div class="register-form-wrapper">
                <h1 class="register-title">Reset Password</h1>
                <p class="register-subtitle">Enter your new password</p>

                <form method="POST" action="{{ route('password.update') }}" class="register-form">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ $email }}">

                    {{-- Email Field (readonly) --}}
                    <div class="form-group">
                        <div class="form-input-wrapper">
                            <i class="form-input-icon fa-solid fa-envelope"></i>
                            <input 
                                type="email" 
                                id="email" 
                                name="email_display" 
                                class="form-input" 
                                value="{{ $email }}"
                                readonly
                                style="background-color: #f5f5f5; cursor: not-allowed;"
                            />
                        </div>
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
                                placeholder="New Password"
                                required
                                autofocus
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
                                placeholder="Confirm New Password"
                                required
                            />
                            <button type="button" class="password-toggle" aria-label="Toggle password visibility">
                                <i class="fa-solid fa-eye" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" class="register-submit-btn">
                        <i class="fa-solid fa-key" style="margin-right: 8px;"></i>
                        RESET PASSWORD
                    </button>
                </form>

                {{-- Back to Login Link --}}
                <div class="register-footer">
                    <p>Remember your password? <a href="{{ route('login') }}" class="login-link">Sign in</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script nonce="{{ $cspNonce ?? '' }}">
    // Toast notification initialization
    function initToast() {
        const toastStack = document.querySelector('[data-toast]');
        if (!toastStack) return;

        const toast = toastStack.querySelector('.toast');
        if (!toast) return;

        // Show with small delay for smooth entrance
        requestAnimationFrame(function () {
            toast.classList.add('toast--visible');
        });

        const autoHideMs = 5000;
        const hide = function () {
            toast.classList.add('toast--hiding');
            setTimeout(function () {
                if (toast.parentNode) {
                    toast.remove();
                }
            }, 350);
        };

        // Auto hide
        const timer = setTimeout(hide, autoHideMs);

        // Close button
        const closeBtn = toast.querySelector('.toast__close');
        if (closeBtn) {
            closeBtn.addEventListener('click', function () {
                clearTimeout(timer);
                hide();
            });
        }
    }

    // Initialize toast when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            initToast();
            // Also check after a short delay for redirects
            setTimeout(function() {
                const toastStack = document.querySelector('[data-toast]');
                if (toastStack && !toastStack.querySelector('.toast--visible')) {
                    initToast();
                }
            }, 100);
        });
    } else {
        // DOM is already ready
        initToast();
        setTimeout(function() {
            const toastStack = document.querySelector('[data-toast]');
            if (toastStack && !toastStack.querySelector('.toast--visible')) {
                initToast();
            }
        }, 100);
    }

    // Also check on page show (for back/forward navigation)
    window.addEventListener('pageshow', function() {
        initToast();
    });

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
                    
                    const inputWrapper = this.closest('.form-input-wrapper');
                    if (!inputWrapper) {
                        return;
                    }
                    
                    const input = inputWrapper.querySelector('input[type="password"], input[type="text"]');
                    
                    if (!input || input.tagName.toLowerCase() !== 'input') {
                        return;
                    }
                    
                    if (hideTimer) {
                        clearTimeout(hideTimer);
                        hideTimer = null;
                    }
                    
                    const isPassword = input.type === 'password';
                    const icon = this.querySelector('i');
                    
                    if (isPassword) {
                        input.type = 'text';
                        if (icon) {
                            icon.classList.remove('fa-eye');
                            icon.classList.add('fa-eye-slash');
                        }
                        
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
                        input.type = 'password';
                        if (icon) {
                            icon.classList.remove('fa-eye-slash');
                            icon.classList.add('fa-eye');
                        }
                    }
                });
            });
        }
        
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initPasswordToggle);
        } else {
            initPasswordToggle();
        }
    })();
</script>
@endpush
@endsection

