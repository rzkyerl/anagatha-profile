/**
 * Contact Form JavaScript
 * Handles toast + auto-hide alerts
 */

(function() {
    'use strict';

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

    // Also check for toast after a short delay (for redirects)
    function checkForToast() {
        setTimeout(function () {
            const toastStack = document.querySelector('[data-toast]');
            if (toastStack && !toastStack.querySelector('.toast--visible')) {
                initToast();
            }
        }, 100);
    }

    function initFormGuard() {
        const form = document.querySelector('.card--form form');
        if (!form) return;

        const submitBtn = form.querySelector('button[type="submit"]');
        if (!submitBtn) return;

        const requiredFields = [
            form.querySelector('#first_name'),
            form.querySelector('#last_name'),
            form.querySelector('#email'),
            form.querySelector('#message')
        ];

        function isEmailValid(value) {
            if (!value) return false;
            const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return pattern.test(String(value).toLowerCase());
        }

        function updateSubmitState() {
            const [firstName, lastName, email, message] = requiredFields;

            const allFilled = requiredFields.every(function (el) {
                return el && el.value && el.value.trim().length > 0;
            });

            const emailOk = isEmailValid(email ? email.value : '');
            const isValid = allFilled && emailOk;

            submitBtn.disabled = !isValid;
            submitBtn.classList.toggle('is-disabled', !isValid);
        }

        // Initial state
        updateSubmitState();

        requiredFields.forEach(function (el) {
            if (!el) return;
            el.addEventListener('input', updateSubmitState);
            el.addEventListener('blur', updateSubmitState);
        });

        let isSubmitting = false;
        
        form.addEventListener('submit', function (e) {
            if (isSubmitting) {
                e.preventDefault();
                return false;
            }

            isSubmitting = true;
            submitBtn.disabled = true;
            submitBtn.classList.add('is-disabled');

            // Reset submitting flag after 3 seconds (in case of error)
            setTimeout(function () {
                isSubmitting = false;
            }, 3000);
        });
    }

    // Fallback: auto-hide legacy alerts if any
    function initAutoHideAlerts() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function (alert) {
            setTimeout(function () {
                alert.style.transition = 'opacity 0.5s ease-out';
                alert.style.opacity = '0';
                setTimeout(function () {
                    alert.remove();
                }, 500);
            }, 5000);
        });
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            initToast();
            checkForToast();
            initFormGuard();
            initAutoHideAlerts();
        });
    } else {
        // DOM is already ready
        initToast();
        checkForToast();
        initFormGuard();
        initAutoHideAlerts();
    }

    // Also check on page show (for back/forward navigation)
    window.addEventListener('pageshow', function() {
        initToast();
    });
})();

