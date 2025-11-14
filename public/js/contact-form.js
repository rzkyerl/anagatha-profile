/**
 * Contact Form JavaScript
 * Handles auto-hide alerts and WhatsApp redirect
 */

(function() {
    'use strict';


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

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            initAutoHideAlerts();
            initWhatsAppRedirect();
        });
    } else {
        // DOM is already ready
        initAutoHideAlerts();
        initWhatsAppRedirect();
    }
})();

