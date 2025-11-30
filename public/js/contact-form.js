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
            if (toastStack) {
                const toast = toastStack.querySelector('.toast');
                if (toast && !toast.classList.contains('toast--visible')) {
                    initToast();
                }
            }
        }, 100);
    }
    
    // Additional check after page load (for redirects from form submission)
    function checkForToastOnLoad() {
        // Check immediately
        const toastStack = document.querySelector('[data-toast]');
        if (toastStack) {
            const toast = toastStack.querySelector('.toast');
            if (toast && !toast.classList.contains('toast--visible')) {
                initToast();
            }
        }
        
        // Also check after a delay to handle slow redirects
        setTimeout(function () {
            const toastStack = document.querySelector('[data-toast]');
            if (toastStack) {
                const toast = toastStack.querySelector('.toast');
                if (toast && !toast.classList.contains('toast--visible')) {
                    initToast();
                }
            }
        }, 300);
    }

    function initFormGuard() {
        const form = document.querySelector('.card--form form');
        if (!form) return;

        const submitBtn = form.querySelector('button[type="submit"]');
        if (!submitBtn) return;

        // Get all form fields
        const firstNameField = form.querySelector('#first_name');
        const lastNameField = form.querySelector('#last_name');
        const emailField = form.querySelector('#email');
        const phoneField = form.querySelector('#phone');
        const messageField = form.querySelector('#message');

        const allFields = [
            { el: firstNameField, name: 'first_name' },
            { el: lastNameField, name: 'last_name' },
            { el: emailField, name: 'email' },
            { el: phoneField, name: 'phone' },
            { el: messageField, name: 'message' }
        ].filter(function (field) {
            return field.el !== null;
        });

        const localizedMessages = (typeof window !== 'undefined' && window.contactFormMessages && typeof window.contactFormMessages === 'object')
            ? window.contactFormMessages
            : {};

        const buildMessages = function(fieldName, defaults) {
            const overrides = (localizedMessages && typeof localizedMessages[fieldName] === 'object')
                ? localizedMessages[fieldName]
                : {};
            return Object.assign({}, defaults, overrides);
        };

        // Validation rules
        const validationRules = {
            first_name: {
                required: true,
                min: 4,
                max: 60,
                regex: /^[^<>]*$/,
                message: buildMessages('first_name', {
                    required: 'First name is required',
                    min: 'First name must be at least 4 characters',
                    max: 'First name must not exceed 60 characters',
                    regex: 'First name contains invalid characters'
                })
            },
            last_name: {
                required: false,
                max: 60,
                regex: /^[^<>]*$/,
                message: buildMessages('last_name', {
                    max: 'Last name must not exceed 60 characters',
                    regex: 'Last name contains invalid characters'
                })
            },
            email: {
                required: true,
                max: 35,
                email: true,
                message: buildMessages('email', {
                    required: 'Email is required',
                    email: 'Please enter a valid email address (e.g. name@example.com)',
                    max: 'Email must not exceed 35 characters'
                })
            },
            phone: {
                required: true,
                min: 8,
                max: 15,
                regex: /^\(\+\d{1,2}\)\s?\d{8,15}$/,
                countryCodeRegex: /^\(\+\d{1,2}\)$/,
                message: buildMessages('phone', {
                    required: 'Phone number is required',
                    min: 'Phone number must be at least 8 digits after country code',
                    max: 'Phone number must not exceed 15 digits after country code',
                    countryCode: 'Please enter a valid country code in format (+X) or (+XX) before continuing',
                    regex: 'Phone number must be in format (+X) YYYYYYYY or (+XX) YYYYYYYY (e.g. (+62) 81234567890, (+1) 2345678900)'
                })
            },
            message: {
                required: true,
                min: 10,
                max: 2000,
                regex: /^[^<>]*$/,
                message: buildMessages('message', {
                    required: 'Message is required',
                    min: 'Message must be at least 10 characters',
                    max: 'Message must not exceed 2000 characters',
                    regex: 'Message contains invalid characters'
                })
            }
        };

        // Validation functions
        function isEmailValid(value) {
            if (!value) return false;
            const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return pattern.test(String(value).toLowerCase());
        }

        function validateField(fieldName, value) {
            const rules = validationRules[fieldName];
            if (!rules) return { valid: true, error: null };

            const trimmedValue = value ? value.trim() : '';

            // Check required
            if (rules.required && !trimmedValue) {
                return { valid: false, error: rules.message.required };
            }

            // Skip other validations if field is empty and not required
            if (!trimmedValue && !rules.required) {
                return { valid: true, error: null };
            }

            // Special validation for phone: check country code first
            if (fieldName === 'phone' && rules.countryCodeRegex) {
                // Extract country code part in format (+X) or (+XX)
                const countryCodeMatch = trimmedValue.match(/^(\(\+\d{1,2}\))\s?(.*)$/);
                
                if (!countryCodeMatch) {
                    // Check if user is still typing country code (without closing parenthesis)
                    const partialMatch = trimmedValue.match(/^\(\+\d{0,2}$/);
                    if (partialMatch) {
                        return { valid: false, error: rules.message.countryCode };
                    }
                    // No valid country code found
                    return { valid: false, error: rules.message.countryCode };
                }
                
                const countryCode = countryCodeMatch[1];
                const phoneNumberPart = countryCodeMatch[2] || '';
                
                // Check if country code is valid format (+X) or (+XX)
                if (!rules.countryCodeRegex.test(countryCode)) {
                    return { valid: false, error: rules.message.countryCode };
                }
                
                // If only country code is entered (no phone number yet)
                if (phoneNumberPart.trim().length === 0) {
                    return { valid: false, error: rules.message.countryCode };
                }
                
                // Extract only digits from phone number part (after country code)
                const phoneDigits = phoneNumberPart.replace(/\D/g, '');
                
                // Validate phone number digits length (not total string length)
                if (phoneDigits.length < rules.min) {
                    return { valid: false, error: rules.message.min };
                }
                
                if (phoneDigits.length > rules.max) {
                    return { valid: false, error: rules.message.max };
                }
                
                // Skip general min/max check for phone (we already validated digits)
                // Skip regex check for phone (we already validated format and digits)
                // Return early since all phone validations are complete
                return { valid: true, error: null };
            } else {
                // Check min length (for non-phone fields)
                if (rules.min && trimmedValue.length < rules.min) {
                    return { valid: false, error: rules.message.min };
                }

                // Check max length (for non-phone fields)
                if (rules.max && trimmedValue.length > rules.max) {
                    return { valid: false, error: rules.message.max };
                }
            }

            // Check regex pattern (skip for phone as it's already validated)
            if (fieldName !== 'phone' && rules.regex && !rules.regex.test(trimmedValue)) {
                return { valid: false, error: rules.message.regex };
            }

            // Check email format
            if (rules.email && !isEmailValid(trimmedValue)) {
                return { valid: false, error: rules.message.email };
            }

            return { valid: true, error: null };
        }

        function updateFieldValidation(field) {
            const value = field.el.value;
            const validation = validateField(field.name, value);
            const inputContainer = field.el.closest('.input-with-icon');

            if (inputContainer) {
                if (validation.valid) {
                    inputContainer.classList.remove('is-invalid');
                } else {
                    inputContainer.classList.add('is-invalid');
                }
            }

            // Show/hide error message with smooth transition
            const formField = field.el.closest('.form-field');
            let errorElement = formField ? formField.querySelector('.form-error-realtime') : null;
            
            if (!validation.valid) {
                if (!errorElement) {
                    errorElement = document.createElement('p');
                    errorElement.className = 'form-error form-error-realtime';
                    if (formField) {
                        formField.appendChild(errorElement);
                    }
                    // Trigger reflow for transition
                    void errorElement.offsetHeight;
                }
                errorElement.textContent = validation.error;
                // Add visible class after a tiny delay for smooth fade-in
                requestAnimationFrame(function() {
                    errorElement.classList.add('is-visible');
                });
            } else {
                if (errorElement) {
                    // Remove visible class first for fade-out
                    errorElement.classList.remove('is-visible');
                    // Remove element after transition completes
                    setTimeout(function() {
                        if (errorElement && errorElement.parentNode) {
                            errorElement.remove();
                        }
                    }, 300);
                }
            }

            return validation.valid;
        }

        function validateAllFields() {
            let allValid = true;
            allFields.forEach(function (field) {
                const isValid = updateFieldValidation(field);
                if (!isValid) {
                    allValid = false;
                }
            });
            return allValid;
        }

        function updateSubmitState() {
            const allValid = validateAllFields();
            submitBtn.disabled = !allValid;
            submitBtn.classList.toggle('is-disabled', !allValid);
        }

        // Initial validation
        updateSubmitState();

        // Special handling for phone field - ensure format: (+XX) YYYYYYYY
        function initPhoneFieldHandler() {
            if (!phoneField) return;

            // Prevent input if country code is not valid
            phoneField.addEventListener('keydown', function(e) {
                const value = phoneField.value;
                const cursorPos = phoneField.selectionStart;
                
                // Prevent deletion of opening parenthesis at the start
                if ((e.key === 'Backspace' || e.key === 'Delete') && cursorPos === 0 && value[0] === '(') {
                    e.preventDefault();
                    return;
                }

                // Allow: backspace, delete, arrow keys, tab, escape, enter, home, end
                if (['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown', 'Tab', 'Escape', 'Enter', 'Home', 'End'].includes(e.key)) {
                    return;
                }

                // Allow: Ctrl/Cmd + A, C, V, X, Z
                if (e.ctrlKey || e.metaKey) {
                    return;
                }

                // Extract current country code part
                const countryCodeMatch = value.match(/^\((\+\d{0,2})/);
                const currentCountryCode = countryCodeMatch ? countryCodeMatch[1] : '';
                
                // Check if country code is complete (has closing parenthesis)
                const completeCountryCodeMatch = value.match(/^(\(\+\d{1,2}\)\s?)/);
                const isAfterCountryCode = completeCountryCodeMatch && cursorPos >= completeCountryCodeMatch[1].length;
                
                // If we're still in country code area (inside parentheses), only allow digits
                if (value.startsWith('(') && cursorPos > 0 && cursorPos <= currentCountryCode.length + 1) {
                    // If user is typing in country code area (after + and before closing parenthesis)
                    if (cursorPos >= 2 && cursorPos <= currentCountryCode.length + 1) {
                        // Only allow digits
                        if (!/^\d$/.test(e.key)) {
                            e.preventDefault();
                            return;
                        }
                        
                        // Prevent typing more than 2 digits after +
                        if (currentCountryCode.length >= 4 && cursorPos <= 4) {
                            e.preventDefault();
                            return;
                        }
                    }
                }
                
                // If cursor is after country code (after ") "), only allow digits
                if (isAfterCountryCode) {
                    // Only allow digits, space is already handled by auto-formatting
                    if (!/^\d$/.test(e.key) && e.key !== ' ') {
                        e.preventDefault();
                        return;
                    }
                }
            });

            // Auto-format phone number
            phoneField.addEventListener('input', function(e) {
                let value = phoneField.value;
                const cursorPos = phoneField.selectionStart;
                let newCursorPos = cursorPos;
                
                // Auto-add opening parenthesis and + if user starts typing
                if (value.length === 0) {
                    return;
                }
                
                // If doesn't start with (, add it
                if (value[0] !== '(') {
                    value = '(' + value.replace(/^\(/, '');
                    newCursorPos++;
                }
                
                // If after ( there's no +, add it
                if (value.startsWith('(') && value.length > 1 && value[1] !== '+') {
                    value = '(' + '+' + value.substring(1).replace(/^\+/, '');
                    newCursorPos++;
                }
                
                // Auto-close parenthesis after country code (1-2 digits)
                const countryCodeMatch = value.match(/^\((\+\d{1,2})/);
                if (countryCodeMatch) {
                    const countryCode = countryCodeMatch[1];
                    const afterCountryCode = value.substring(countryCode.length + 1);
                    
                    // Don't auto-close parenthesis automatically
                    // Only close when user types a digit after the country code
                    // This allows user to type (+X) or (+XX) without premature closing
                    if (!afterCountryCode.startsWith(')')) {
                        // Check if user is typing a digit after the country code
                        if (afterCountryCode.length > 0 && /^\d/.test(afterCountryCode)) {
                            // User typed a digit after country code, close parenthesis before the digit
                            value = '(' + countryCode + ') ' + afterCountryCode;
                            newCursorPos = Math.min(newCursorPos + 2, value.length);
                        }
                        // If no digit after country code, don't close - let user continue typing
                    }
                }
                
                // Remove non-digit characters after country code (after ") ")
                const completeCountryCodeMatch = value.match(/^(\(\+\d{1,2}\)\s?)(.*)$/);
                if (completeCountryCodeMatch) {
                    const countryCodePart = completeCountryCodeMatch[1];
                    let phoneNumberPart = completeCountryCodeMatch[2];
                    
                    // Remove all non-digit characters from phone number part
                    phoneNumberPart = phoneNumberPart.replace(/\D/g, '');
                    
                    // Reconstruct value
                    value = countryCodePart + phoneNumberPart;
                    
                    // Adjust cursor position (subtract removed characters)
                    const removedChars = completeCountryCodeMatch[2].length - phoneNumberPart.length;
                    newCursorPos = Math.max(countryCodePart.length, cursorPos - removedChars);
                }
                
                // Update value and cursor position
                if (value !== phoneField.value) {
                    phoneField.value = value;
                    setTimeout(function() {
                        phoneField.setSelectionRange(newCursorPos, newCursorPos);
                    }, 0);
                }
            }, { passive: true });

            // Ensure format is present on focus if field is empty
            phoneField.addEventListener('focus', function() {
                const value = phoneField.value.trim();
                if (value.length === 0) {
                    phoneField.value = '(+';
                    // Move cursor after +
                    setTimeout(function() {
                        phoneField.setSelectionRange(2, 2);
                    }, 0);
                }
            });
        }

        // Add event listeners for real-time validation
        allFields.forEach(function (field) {
            if (!field.el) return;

            // Validate on input (real-time)
            field.el.addEventListener('input', function () {
                updateFieldValidation(field);
                updateSubmitState();
            });

            // Validate on blur (when user leaves field)
            field.el.addEventListener('blur', function () {
                updateFieldValidation(field);
                updateSubmitState();
            });
        });

        // Initialize phone field handler
        initPhoneFieldHandler();

        let isSubmitting = false;
        
        form.addEventListener('submit', function (e) {
            // Prevent submission if form is invalid
            if (!validateAllFields()) {
                e.preventDefault();
                // Focus on first invalid field
                const firstInvalid = allFields.find(function (field) {
                    return !validateField(field.name, field.el.value).valid;
                });
                if (firstInvalid && firstInvalid.el) {
                    firstInvalid.el.focus();
                }
                return false;
            }

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
                updateSubmitState();
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
            checkForToastOnLoad();
            checkForToast();
            initFormGuard();
            initAutoHideAlerts();
        });
    } else {
        // DOM is already ready
        checkForToastOnLoad();
        checkForToast();
        initFormGuard();
        initAutoHideAlerts();
    }

    // Also check on page show (for back/forward navigation and redirects)
    window.addEventListener('pageshow', function() {
        checkForToastOnLoad();
    });
    
    // Check when page becomes visible (for tab switching)
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
            setTimeout(function() {
                checkForToastOnLoad();
            }, 100);
        }
    });
})();

