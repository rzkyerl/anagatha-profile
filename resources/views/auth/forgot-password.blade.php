@extends('layouts.app')

@section('title', 'Forgot Password - Anagata Executive')
@section('body_class', 'page forgot-password-page')

@section('content')
<div class="register-container">
    <div class="register-card">
        {{-- Left Section: Gradient Background with Text --}}
        <div class="register-left-section">
            <div class="register-header-row">
                <h2 class="register-signup-label">Forgot Password</h2>
                <div class="register-logo">
                    <img src="/assets/hero-sec.png" alt="Anagata Executive Logo" />
                </div>
            </div>
            <div class="register-text-content">
                <p class="register-text-small">Don't worry</p>
                <h2 class="register-text-large">We'll send you a password reset link to your email address.</h2>
            </div>
        </div>

        {{-- Right Section: Forgot Password Form --}}
        <div class="register-right-section">
            <div class="register-form-wrapper">
                <h1 class="register-title">Forgot Password?</h1>
                <p class="register-subtitle">Enter your email address and we'll send you a reset link</p>

                <form method="POST" action="#" class="register-form" onsubmit="event.preventDefault(); return false;">
                    @csrf

                    {{-- Email Field --}}
                    <div class="form-group">
                        <div class="form-input-wrapper">
                            <i class="form-input-icon fa-solid fa-envelope"></i>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                class="form-input" 
                                placeholder="Email"
                                autofocus
                            />
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" class="register-submit-btn" disabled>
                        SEND RESET LINK
                    </button>
                </form>

                {{-- Back to Login Link --}}
                <div class="register-footer">
                    <p>Remember your password? <a href="{{ route('login') }}" class="register-link">Sign in</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

