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
        const userDropdown = document.querySelector('[data-user-dropdown]');
        const isAuthenticated = !!userDropdown;

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

        // Initialize navigation dropdown
        const initNavDropdown = () => {
            const navDropdown = document.querySelector('[data-nav-dropdown]');
            if (!navDropdown) {
                return;
            }

            const dropdownButton = navDropdown.querySelector('.nav-links__link--dropdown');
            const dropdownMenu = navDropdown.querySelector('.nav-links__dropdown-menu');

            if (!dropdownButton || !dropdownMenu) {
                return;
            }

            const toggleDropdown = (event) => {
                event.preventDefault();
                event.stopPropagation();
                const isExpanded = navDropdown.getAttribute('aria-expanded') === 'true';
                const newState = !isExpanded;
                navDropdown.setAttribute('aria-expanded', String(newState));
                
                // Close other dropdowns if any
                document.querySelectorAll('[data-nav-dropdown]').forEach((dropdown) => {
                    if (dropdown !== navDropdown) {
                        dropdown.setAttribute('aria-expanded', 'false');
                    }
                });
                
                // Prevent closing mobile menu when toggling dropdown
                if (navLinks && navLinks.classList.contains('is-open')) {
                    event.stopPropagation();
                }
            };

            const closeDropdown = () => {
                navDropdown.setAttribute('aria-expanded', 'false');
            };

            dropdownButton.addEventListener('click', toggleDropdown);

            // Close dropdown when clicking outside
            document.addEventListener('click', (event) => {
                if (!navDropdown.contains(event.target)) {
                    closeDropdown();
                }
            });

            // Close dropdown on Escape key
            document.addEventListener('keyup', (event) => {
                if (event.key === 'Escape' && navDropdown.getAttribute('aria-expanded') === 'true') {
                    closeDropdown();
                    dropdownButton.focus();
                }
            });

            // Close dropdown when clicking on a dropdown item
            dropdownMenu.addEventListener('click', (event) => {
                const clickedItem = event.target.closest('.nav-links__dropdown-item');
                if (clickedItem) {
                    // Don't prevent default - allow navigation
                    // Close dropdown after a short delay to allow navigation
                    setTimeout(() => {
                        closeDropdown();
                    }, 100);
                }
            });
        };

        initNavDropdown();

        // Initialize User Dropdown
        const initUserDropdown = () => {
            if (!userDropdown) {
                return;
            }

            const dropdownTrigger = userDropdown.querySelector('[data-user-dropdown-toggle]');
            const dropdownMenu = userDropdown.querySelector('.user-dropdown__menu');
            const languageSubmenu = userDropdown.querySelector('[data-language-submenu]');

            if (!dropdownTrigger || !dropdownMenu) {
                return;
            }

            const toggleDropdown = (event) => {
                event.preventDefault();
                event.stopPropagation();
                const isExpanded = dropdownTrigger.getAttribute('aria-expanded') === 'true';
                
                // Close nav menu if open
                if (navLinks && navLinks.classList.contains('is-open')) {
                    navLinks.classList.remove('is-open');
                    if (navToggle) {
                        navToggle.setAttribute('aria-expanded', 'false');
                        navToggle.classList.remove('is-active');
                    }
                    document.body.classList.remove('nav-open');
                }
                
                // Toggle main dropdown
                dropdownTrigger.setAttribute('aria-expanded', !isExpanded);
                userDropdown.classList.toggle('is-open', !isExpanded);
            };

            const closeDropdown = () => {
                dropdownTrigger.setAttribute('aria-expanded', 'false');
                userDropdown.classList.remove('is-open');
                if (languageSubmenu) {
                    languageSubmenu.classList.remove('is-open');
                }
            };

            dropdownTrigger.addEventListener('click', toggleDropdown);

            // Handle language submenu
            if (languageSubmenu) {
                const submenuTrigger = languageSubmenu.querySelector('.user-dropdown__item--submenu');
                const submenuMenu = languageSubmenu.querySelector('.user-dropdown__submenu-menu');
                const languageButtons = submenuMenu?.querySelectorAll('[data-language]');

                if (submenuTrigger && submenuMenu) {
                    submenuTrigger.addEventListener('click', (e) => {
                        e.stopPropagation();
                        languageSubmenu.classList.toggle('is-open');
                    });

                    // Handle language selection
                    languageButtons?.forEach((button) => {
                        button.addEventListener('click', () => {
                            const targetUrl = button.dataset.languageUrl;
                            if (targetUrl) {
                                window.location.href = targetUrl;
                            }
                        });
                    });
                }
            }

            // Close dropdown when clicking outside
            document.addEventListener('click', (event) => {
                if (!userDropdown.contains(event.target)) {
                    closeDropdown();
                }
            });

            // Close dropdown on Escape key
            document.addEventListener('keyup', (event) => {
                if (event.key === 'Escape' && userDropdown.classList.contains('is-open')) {
                    closeDropdown();
                    dropdownTrigger.focus();
                }
            });
        };

        initUserDropdown();

        // Mobile menu toggle (only when nav toggle button exists)
        if (navToggle && navLinks) {
            const closeMenu = () => {
                if (!navLinks.classList.contains('is-open')) {
                    return;
                }
                
                // Close Services dropdown immediately if open (before closing nav menu)
                const navDropdown = document.querySelector('[data-nav-dropdown]');
                if (navDropdown && navDropdown.getAttribute('aria-expanded') === 'true') {
                    navDropdown.setAttribute('aria-expanded', 'false');
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
                
                // Close user dropdown if open
                if (userDropdown && userDropdown.classList.contains('is-open')) {
                    const dropdownTrigger = userDropdown.querySelector('[data-user-dropdown-toggle]');
                    if (dropdownTrigger) {
                        dropdownTrigger.setAttribute('aria-expanded', 'false');
                    }
                    userDropdown.classList.remove('is-open');
                    const languageSubmenu = userDropdown.querySelector('[data-language-submenu]');
                    if (languageSubmenu) {
                        languageSubmenu.classList.remove('is-open');
                    }
                }
                
                // Close Services dropdown if closing nav menu
                if (!isOpen) {
                    const navDropdown = document.querySelector('[data-nav-dropdown]');
                    if (navDropdown && navDropdown.getAttribute('aria-expanded') === 'true') {
                        navDropdown.setAttribute('aria-expanded', 'false');
                    }
                }
                
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
        }

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
