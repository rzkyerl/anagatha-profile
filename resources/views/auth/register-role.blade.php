@extends('layouts.auth')

@section('title', 'Choose Role - Anagata Executive')
@section('body_class', 'page register-page register-role-page')

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

        {{-- Right Section: Role Selection --}}
        <div class="register-right-section">
            <div class="register-form-wrapper">
                <h1 class="register-title">Choose Your Role</h1>
                <p class="register-subtitle">Select how you want to use Anagata Executive</p>

                <form method="GET" action="{{ route('register') }}" class="register-form">
                    <input type="hidden" name="role" id="selected_role" value="">

                    <div class="role-selection-cards">
                        <div class="role-card-option" data-role="employee">
                            <span class="role-card-option__label">Employee / Jobseeker</span>
                            <div class="role-card-option__radio">
                                <div class="radio-outer"></div>
                                <div class="radio-inner"></div>
                            </div>
                        </div>

                        <div class="role-card-option" data-role="recruiter">
                            <span class="role-card-option__label">Recruiter</span>
                            <div class="role-card-option__radio">
                                <div class="radio-outer"></div>
                                <div class="radio-inner"></div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="register-submit-btn" id="continue-btn" disabled>
                        Continue
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
<script nonce="{{ $cspNonce ?? '' }}">
    (function() {
        'use strict';
        
        function initRoleSelection() {
            const roleCards = document.querySelectorAll('.role-card-option');
            const selectedRoleInput = document.getElementById('selected_role');
            const continueBtn = document.getElementById('continue-btn');

            if (!roleCards.length || !selectedRoleInput || !continueBtn) {
                return;
            }

            // Handle card clicks
            roleCards.forEach(function(card) {
                card.addEventListener('click', function() {
                    const role = this.getAttribute('data-role');
                    
                    // Remove active class from all cards
                    roleCards.forEach(function(c) {
                        c.classList.remove('active');
                    });

                    // Add active class to selected card
                    this.classList.add('active');

                    // Set the selected role
                    selectedRoleInput.value = role;

                    // Enable continue button
                    continueBtn.disabled = false;
                });
            });
        }
        
        // Initialize when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initRoleSelection);
        } else {
            // DOM is already ready
            initRoleSelection();
        }
    })();
</script>
@endpush
@endsection



