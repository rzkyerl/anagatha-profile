/**
 * Contact Form JavaScript
 * Handles auto-hide alerts and WhatsApp redirect
 */

(function() {
    'use strict';

    // Generate WhatsApp URL from form data (fast - no server wait)
    function generateWhatsAppUrl(formData) {
        // Get WhatsApp phone from data attribute or use default
        const formCard = document.querySelector('.card--form');
        const phoneNumber = formCard ? (formCard.getAttribute('data-whatsapp-phone') || '6289684267761') : '6289684267761';
        
        const firstName = formData.get('first_name') || '';
        const lastName = formData.get('last_name') || '';
        const name = (firstName + ' ' + lastName).trim();
        const email = formData.get('email') || '';
        const phone = formData.get('phone') || '';
        const message = formData.get('message') || '';
        
        // Build contact info
        let contactInfo = name + ', ' + email;
        if (phone) {
            contactInfo += ', ' + phone;
        }
        
        // Format message (same format as backend)
        const whatsappMessage = 'Halo Anagatha, I\'m ' + contactInfo + '\n' + message;
        
        // Generate wa.me URL
        const encodedMessage = encodeURIComponent(whatsappMessage);
        return 'https://wa.me/' + phoneNumber + '?text=' + encodedMessage;
    }

    // Auto-hide alerts after 5 seconds
    function initAutoHideAlerts() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                alert.style.transition = 'opacity 0.5s ease-out';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            }, 5000); // Hide after 5 seconds
        });
    }

    // Open WhatsApp after successful form submission (from server session)
    function initWhatsAppRedirect() {
        // Check from data attribute first (more reliable)
        const formCard = document.querySelector('.card--form[data-whatsapp-url]');
        if (formCard) {
            const whatsappUrl = formCard.getAttribute('data-whatsapp-url');
            if (whatsappUrl) {
                // Open WhatsApp immediately
                window.open(whatsappUrl, '_blank');
                // Remove attribute to prevent reopening on refresh
                formCard.removeAttribute('data-whatsapp-url');
                return; // Exit early if found
            }
        }
        
        // Fallback: check from window variable (set by Blade)
        if (window.whatsappUrlFromSession) {
            const whatsappUrlFromSession = window.whatsappUrlFromSession;
            if (whatsappUrlFromSession) {
                window.open(whatsappUrlFromSession, '_blank');
                // Clear the variable to prevent reopening on refresh
                window.whatsappUrlFromSession = null;
            }
        }
    }

    // Handle form submission - open WhatsApp immediately (fast!)
    function initFormSubmission() {
        const form = document.querySelector('form[action*="/contact"]');
        if (form) {
            form.addEventListener('submit', function(e) {
                // Generate WhatsApp URL from form data immediately (no server wait)
                const formData = new FormData(form);
                const whatsappUrl = generateWhatsAppUrl(formData);
                
                // Open WhatsApp immediately (don't wait for server response)
                if (whatsappUrl) {
                    // Small delay to ensure form submits properly
                    setTimeout(function() {
                        window.open(whatsappUrl, '_blank');
                    }, 100);
                }
            });
        }
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            initAutoHideAlerts();
            initWhatsAppRedirect();
            initFormSubmission();
        });
    } else {
        // DOM is already ready
        initAutoHideAlerts();
        initWhatsAppRedirect();
        initFormSubmission();
    }
})();

