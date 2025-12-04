@extends('layouts.auth')

@section('title', 'Verify Your Email - Anagata Executive')
@section('body_class', 'page verify-email-page')

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
                <h2 class="register-signup-label">Verify Email</h2>
                <div class="register-logo">
                    <img src="/assets/hero-sec.png" alt="Anagata Executive Logo" />
                </div>
            </div>
            <div class="register-text-content">
                <p class="register-text-small">Email Verification</p>
                <h2 class="register-text-large">Please verify your email address to access your account and start using our platform.</h2>
            </div>
        </div>

        {{-- Right Section: Verification Content --}}
        <div class="register-right-section">
            <div class="register-form-wrapper">
                <div class="verify-email-content">
                    <div class="verify-email-icon">
                        <i class="fa-solid fa-envelope-circle-check"></i>
                    </div>
                    <h1 class="register-title">Verify Your Email Address</h1>
                    <p class="register-subtitle">
                        We've sent a verification link to <strong>{{ Auth::user()->email }}</strong>
                    </p>
                    <p class="verify-email-message">
                        Before proceeding, please check your email for a verification link. If you didn't receive the email, we can send you another one.
                    </p>

                    {{-- Resend Verification Email Form --}}
                    <form method="POST" action="{{ route('verification.send') }}" class="verify-email-form">
                        @csrf
                        <button type="submit" class="register-submit-btn">
                            <i class="fa-solid fa-paper-plane" style="margin-right: 8px;"></i>
                            Resend Verification Email
                        </button>
                    </form>

                    {{-- Logout Link --}}
                    <div class="register-footer" style="margin-top: 2rem;">
                        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="login-link" style="background: none; border: none; color: inherit; text-decoration: underline; cursor: pointer; padding: 0;">
                                Logout
                            </button>
                        </form>
                    </div>
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
</script>
@endpush
@endsection

