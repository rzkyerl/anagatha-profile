/**
 * Custom Dropdown Component
 * Replaces native select elements with custom styled dropdowns
 */

(function() {
    'use strict';

    class CustomDropdown {
        constructor(selectElement) {
            this.select = selectElement;
            this.isOpen = false;
            this.isToggling = false; // Flag to prevent outside click during toggle
            this.init();
        }

        init() {
            // Create custom dropdown structure
            this.createDropdown();
            // Hide original select
            this.select.style.display = 'none';
            // Bind events
            this.bindEvents();
        }

        createDropdown() {
            const wrapper = document.createElement('div');
            wrapper.className = 'custom-dropdown';
            wrapper.setAttribute('data-dropdown', '');

            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'custom-dropdown__button';
            button.setAttribute('aria-haspopup', 'listbox');
            button.setAttribute('aria-expanded', 'false');

            const selectedText = this.select.options[this.select.selectedIndex]?.text || this.select.options[0]?.text || '';
            button.textContent = selectedText;

            const icon = document.createElement('i');
            icon.className = 'fa-solid fa-chevron-down custom-dropdown__icon';
            icon.setAttribute('aria-hidden', 'true');
            button.appendChild(icon);

            const menu = document.createElement('ul');
            menu.className = 'custom-dropdown__menu';
            menu.setAttribute('role', 'listbox');
            menu.setAttribute('aria-hidden', 'true');

            // Create options
            Array.from(this.select.options).forEach((option, index) => {
                const item = document.createElement('li');
                item.className = 'custom-dropdown__item';
                item.setAttribute('role', 'option');
                item.setAttribute('data-value', option.value);
                item.textContent = option.text;
                
                if (option.selected) {
                    item.classList.add('is-selected');
                }

                item.addEventListener('click', () => {
                    this.selectOption(option.value, option.text, index);
                });

                menu.appendChild(item);
            });

            wrapper.appendChild(button);
            wrapper.appendChild(menu);
            this.select.parentNode.insertBefore(wrapper, this.select);
            this.wrapper = wrapper;
            this.button = button;
            this.menu = menu;
        }

        bindEvents() {
            // Single click handler for toggle
            this.buttonClickHandler = (e) => {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                
                // Set flag to prevent outside click handler from interfering
                this.isToggling = true;
                
                // Toggle dropdown immediately
                this.toggle();
                
                // Reset flag after toggle completes (use requestAnimationFrame for next frame)
                requestAnimationFrame(() => {
                    setTimeout(() => {
                        this.isToggling = false;
                    }, 50);
                });
            };
            
            // Use click event with capture phase for priority
            this.button.addEventListener('click', this.buttonClickHandler, true);

            // Close on outside click - but not when clicking the button or toggling
            this.outsideClickHandler = (e) => {
                // Don't close if currently toggling
                if (this.isToggling) return;
                
                // Check if click is on button or inside dropdown
                const isButtonClick = e.target === this.button || this.button.contains(e.target);
                const isInsideDropdown = this.wrapper.contains(e.target);
                
                // Only close if clicking outside
                if (this.isOpen && !isButtonClick && !isInsideDropdown) {
                    this.close();
                }
            };
            
            // Add listener with delay to avoid conflicts
            setTimeout(() => {
                document.addEventListener('click', this.outsideClickHandler, true);
            }, 300);

            // Close on escape key
            this.escapeKeyHandler = (e) => {
                if (e.key === 'Escape' && this.isOpen) {
                    e.preventDefault();
                    this.close();
                }
            };
            document.addEventListener('keydown', this.escapeKeyHandler);

            // Keyboard navigation
            this.menu.addEventListener('keydown', (e) => {
                this.handleKeyboard(e);
            });
        }

        toggle() {
            if (this.isOpen) {
                this.close();
            } else {
                this.open();
            }
        }

        open() {
            // Close other open dropdowns first
            document.querySelectorAll('.custom-dropdown.is-open').forEach(dropdown => {
                if (dropdown !== this.wrapper) {
                    dropdown.classList.remove('is-open');
                    const button = dropdown.querySelector('.custom-dropdown__button');
                    const menu = dropdown.querySelector('.custom-dropdown__menu');
                    const icon = dropdown.querySelector('.custom-dropdown__icon');
                    if (button) button.setAttribute('aria-expanded', 'false');
                    if (menu) menu.setAttribute('aria-hidden', 'true');
                    if (icon) icon.classList.remove('is-rotated');
                }
            });

            // Set state immediately
            this.isOpen = true;
            
            // Update DOM state
            this.wrapper.classList.add('is-open');
            this.button.setAttribute('aria-expanded', 'true');
            this.menu.setAttribute('aria-hidden', 'false');
            
            const icon = this.button.querySelector('.custom-dropdown__icon');
            if (icon) {
                icon.classList.add('is-rotated');
            }
            
            // Reset any inline styles - let CSS handle positioning
            this.menu.style.position = '';
            this.menu.style.top = '';
            this.menu.style.left = '';
            this.menu.style.width = '';
            this.menu.style.minWidth = '';
            this.menu.style.maxWidth = '';
        }

        close() {
            if (!this.isOpen) return; // Already closed
            
            this.isOpen = false;
            this.wrapper.classList.remove('is-open');
            this.button.setAttribute('aria-expanded', 'false');
            this.menu.setAttribute('aria-hidden', 'true');
            
            const icon = this.button.querySelector('.custom-dropdown__icon');
            if (icon) {
                icon.classList.remove('is-rotated');
            }
        }

        selectOption(value, text, index) {
            // Update select element
            this.select.selectedIndex = index;
            this.select.value = value;

            // Update button text
            this.button.textContent = text;
            const icon = document.createElement('i');
            icon.className = 'fa-solid fa-chevron-down custom-dropdown__icon';
            icon.setAttribute('aria-hidden', 'true');
            this.button.appendChild(icon);

            // Update selected state in menu
            this.menu.querySelectorAll('.custom-dropdown__item').forEach((item, i) => {
                if (i === index) {
                    item.classList.add('is-selected');
                } else {
                    item.classList.remove('is-selected');
                }
            });

            // Trigger change event
            const changeEvent = new Event('change', { bubbles: true });
            this.select.dispatchEvent(changeEvent);

            // Close dropdown
            this.close();
        }

        /**
         * Update button text when select value changes programmatically
         */
        updateButtonText() {
            const selectedOption = this.select.options[this.select.selectedIndex];
            if (selectedOption) {
                // Update button text
                this.button.textContent = selectedOption.text;
                // Remove existing icon and add new one
                const existingIcon = this.button.querySelector('.custom-dropdown__icon');
                if (existingIcon) {
                    existingIcon.remove();
                }
                const icon = document.createElement('i');
                icon.className = 'fa-solid fa-chevron-down custom-dropdown__icon';
                icon.setAttribute('aria-hidden', 'true');
                this.button.appendChild(icon);

                // Update selected state in menu
                this.menu.querySelectorAll('.custom-dropdown__item').forEach((item, i) => {
                    if (i === this.select.selectedIndex) {
                        item.classList.add('is-selected');
                    } else {
                        item.classList.remove('is-selected');
                    }
                });
            }
        }

        handleKeyboard(e) {
            const items = Array.from(this.menu.querySelectorAll('.custom-dropdown__item'));
            const currentIndex = items.findIndex(item => item.classList.contains('is-selected'));
            let newIndex = currentIndex;

            switch(e.key) {
                case 'ArrowDown':
                    e.preventDefault();
                    newIndex = (currentIndex + 1) % items.length;
                    break;
                case 'ArrowUp':
                    e.preventDefault();
                    newIndex = currentIndex <= 0 ? items.length - 1 : currentIndex - 1;
                    break;
                case 'Enter':
                case ' ':
                    e.preventDefault();
                    if (currentIndex >= 0) {
                        const option = this.select.options[currentIndex];
                        this.selectOption(option.value, option.text, currentIndex);
                    }
                    return;
            }

            if (newIndex !== currentIndex && newIndex >= 0) {
                items.forEach((item, i) => {
                    if (i === newIndex) {
                        item.classList.add('is-selected');
                        item.scrollIntoView({ block: 'nearest' });
                    } else {
                        item.classList.remove('is-selected');
                    }
                });
            }
        }
    }

    // Initialize custom dropdowns when DOM is ready
    function initCustomDropdowns() {
        const selects = document.querySelectorAll('.job-filter-select');
        
        // Clear any existing dropdowns to prevent duplicates
        selects.forEach(select => {
            // Check if already initialized
            if (select.dataset.dropdownInitialized === 'true') {
                return;
            }
            
            // Check if select is inside a modal that's hidden
            const modal = select.closest('[hidden]');
            if (modal && modal.hasAttribute('hidden')) {
                return; // Skip hidden modals
            }
            
            try {
                const dropdown = new CustomDropdown(select);
                select.dataset.dropdownInitialized = 'true';
                // Store reference for cleanup if needed
                select._customDropdown = dropdown;
            } catch (error) {
                console.error('Error initializing custom dropdown:', error);
            }
        });
    }
    
    // Make function available globally
    window.initCustomDropdowns = initCustomDropdowns;

    // Initialize on DOMContentLoaded with delay to ensure all scripts are loaded
    function init() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                // Small delay to ensure other scripts have initialized
                setTimeout(initCustomDropdowns, 50);
            });
        } else {
            // DOM is already ready, but add small delay
            setTimeout(initCustomDropdowns, 50);
        }
    }

    // Also reinitialize on dynamic content changes
    const observer = new MutationObserver((mutations) => {
        let shouldReinit = false;
        mutations.forEach((mutation) => {
            if (mutation.addedNodes.length) {
                mutation.addedNodes.forEach((node) => {
                    if (node.nodeType === 1 && (node.classList.contains('job-filter-select') || node.querySelector('.job-filter-select'))) {
                        shouldReinit = true;
                    }
                });
            }
        });
        if (shouldReinit) {
            setTimeout(initCustomDropdowns, 100);
        }
    });

    // Start observing
    if (document.body) {
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }

    init();

})();

