/**
 * Navbar JavaScript
 * Handles mobile menu toggle and active link detection
 */

(function() {
    'use strict';

    function initNavbar() {
        const navToggle = document.querySelector('[data-nav-toggle]');
        const navLinks = document.querySelector('[data-nav-links]');
        const languageSwitcher = document.querySelector('[data-language-switcher]');

        const initLanguageSwitcher = () => {
            if (!languageSwitcher) {
                return;
            }

            const buttons = languageSwitcher.querySelectorAll('.language-switcher__btn[data-language]');
            if (!buttons.length) {
                return;
            }

            const availableLanguages = Array.from(buttons).map((button) => button.dataset.language);
            const defaultLanguage = languageSwitcher.dataset.defaultLanguage || availableLanguages[0];

            const setActiveLanguage = (language) => {
                buttons.forEach((button) => {
                    const isActive = button.dataset.language === language;
                    button.classList.toggle('is-active', isActive);
                    button.setAttribute('aria-pressed', String(isActive));
                });
                languageSwitcher.dataset.selectedLanguage = language;
            };

            setActiveLanguage(defaultLanguage);

            buttons.forEach((button) => {
                button.addEventListener('click', () => {
                    const selectedLanguage = button.dataset.language;
                    const targetUrl = button.dataset.languageUrl;

                    if (!selectedLanguage || selectedLanguage === languageSwitcher.dataset.selectedLanguage) {
                        return;
                    }

                    setActiveLanguage(selectedLanguage);

                    if (targetUrl) {
                        window.location.href = targetUrl;
                        return;
                    }

                    languageSwitcher.dispatchEvent(new CustomEvent('languagechange', {
                        detail: { language: selectedLanguage },
                        bubbles: true
                    }));
                });
            });
        };

        initLanguageSwitcher();

        if (!navToggle || !navLinks) {
            return;
        }

        const closeMenu = () => {
            if (!navLinks.classList.contains('is-open')) {
                return;
            }
            navLinks.classList.remove('is-open');
            navToggle.setAttribute('aria-expanded', 'false');
            navToggle.classList.remove('is-active');
            document.body.classList.remove('nav-open');
        };

        const closeOnLinkClick = (event) => {
            if (event.target.matches('a')) {
                closeMenu();
            }
        };

        navToggle.addEventListener('click', () => {
            const isOpen = navLinks.classList.toggle('is-open');
            navToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            navToggle.classList.toggle('is-active', isOpen);
            document.body.classList.toggle('nav-open', isOpen);
            if (isOpen) {
                navLinks.querySelector('a')?.focus();
            } else {
                navToggle.focus();
            }
        });

        document.addEventListener('click', (event) => {
            if (!navLinks.contains(event.target) && !navToggle.contains(event.target)) {
                closeMenu();
            }
        });

        document.addEventListener('keyup', (event) => {
            if (event.key === 'Escape') {
                closeMenu();
                navToggle.focus();
            }
        });

        navLinks.addEventListener('click', closeOnLinkClick);

        // Active state now handled server-side per page, so no scroll tracking needed
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initNavbar);
    } else {
        // DOM is already ready
        initNavbar();
    }
})();

