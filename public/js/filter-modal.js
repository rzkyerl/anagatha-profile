/**
 * Filter Modal
 * Handles opening/closing filter modal and applying filters
 */

(function() {
    'use strict';

    const modal = document.getElementById('filterModal');
    const openButton = document.getElementById('openFilterModal');
    const closeButtons = document.querySelectorAll('[data-filter-modal-close]');
    const applyButton = document.getElementById('filterModalApply');
    const clearButton = document.getElementById('filterModalClear');
    const clearAllButton = document.getElementById('clearAllFilters');

    // Filter select elements
    const filterSelects = {
        workPreference: document.getElementById('filterWorkPreference'),
        salary: document.getElementById('filterSalary'),
        industry: document.getElementById('filterIndustry'),
        experience: document.getElementById('filterExperience'),
        event: document.getElementById('filterEvent')
    };

    // Main filter selects (in the hero section)
    const mainFilterSelects = document.querySelectorAll('.job-filter-select');

    let lastFocusedElement = null;
    let hideTimeout = null;

    if (!modal || !openButton) return;

    /**
     * Lock scroll when modal is open
     */
    function lockScroll() {
        const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
        document.documentElement.style.setProperty('--scrollbar-compensation', `${scrollbarWidth}px`);
        document.body.classList.add('modal-open');
        document.documentElement.classList.add('modal-open');
    }

    /**
     * Unlock scroll when modal is closed
     */
    function unlockScroll() {
        document.body.classList.remove('modal-open');
        document.documentElement.classList.remove('modal-open');
        document.documentElement.style.setProperty('--scrollbar-compensation', '0px');
    }

    /**
     * Open modal
     */
    function openModal() {
        if (!modal.hasAttribute('hidden')) {
            return;
        }

        // Store last focused element
        lastFocusedElement = document.activeElement;

        // Remove hidden attribute
        modal.removeAttribute('hidden');

        // Sync filter values from main selects to modal selects
        syncFiltersToModal();

        // Trigger reflow
        void modal.offsetWidth;

        // Show modal
        modal.classList.add('is-active');
        modal.setAttribute('aria-hidden', 'false');

        // Lock scroll
        lockScroll();

        // Focus on first filter or close button
        const firstSelect = filterSelects.workPreference;
        if (firstSelect) {
            setTimeout(() => firstSelect.focus(), 100);
        }
    }

    /**
     * Close modal
     */
    function closeModal() {
        if (modal.hasAttribute('hidden')) {
            return;
        }

        modal.classList.remove('is-active');
        modal.setAttribute('aria-hidden', 'true');
        unlockScroll();

        hideTimeout = window.setTimeout(() => {
            modal.setAttribute('hidden', 'hidden');
        }, 220);

        // Return focus to last focused element
        if (lastFocusedElement && typeof lastFocusedElement.focus === 'function') {
            lastFocusedElement.focus();
        }
    }

    /**
     * Sync filter values from main selects to modal selects
     */
    function syncFiltersToModal() {
        if (mainFilterSelects.length >= 5) {
            if (filterSelects.workPreference) {
                filterSelects.workPreference.value = mainFilterSelects[0].value || '';
            }
            if (filterSelects.salary) {
                filterSelects.salary.value = mainFilterSelects[1].value || '';
            }
            if (filterSelects.industry) {
                filterSelects.industry.value = mainFilterSelects[2].value || '';
            }
            if (filterSelects.experience) {
                filterSelects.experience.value = mainFilterSelects[3].value || '';
            }
            if (filterSelects.event) {
                filterSelects.event.value = mainFilterSelects[4].value || '';
            }
        }
    }

    /**
     * Sync filter values from modal selects to main selects
     */
    function syncFiltersFromModal() {
        if (mainFilterSelects.length >= 5) {
            if (filterSelects.workPreference) {
                const newValue = filterSelects.workPreference.value || '';
                if (mainFilterSelects[0].value !== newValue) {
                    mainFilterSelects[0].value = newValue;
                    // Update custom dropdown if it exists
                    if (mainFilterSelects[0]._customDropdown) {
                        mainFilterSelects[0]._customDropdown.updateButtonText();
                    }
                    // Trigger change event
                    const changeEvent = new Event('change', { bubbles: true });
                    mainFilterSelects[0].dispatchEvent(changeEvent);
                }
            }
            if (filterSelects.salary) {
                const newValue = filterSelects.salary.value || '';
                if (mainFilterSelects[1].value !== newValue) {
                    mainFilterSelects[1].value = newValue;
                    if (mainFilterSelects[1]._customDropdown) {
                        mainFilterSelects[1]._customDropdown.updateButtonText();
                    }
                    const changeEvent = new Event('change', { bubbles: true });
                    mainFilterSelects[1].dispatchEvent(changeEvent);
                }
            }
            if (filterSelects.industry) {
                const newValue = filterSelects.industry.value || '';
                if (mainFilterSelects[2].value !== newValue) {
                    mainFilterSelects[2].value = newValue;
                    if (mainFilterSelects[2]._customDropdown) {
                        mainFilterSelects[2]._customDropdown.updateButtonText();
                    }
                    const changeEvent = new Event('change', { bubbles: true });
                    mainFilterSelects[2].dispatchEvent(changeEvent);
                }
            }
            if (filterSelects.experience) {
                const newValue = filterSelects.experience.value || '';
                if (mainFilterSelects[3].value !== newValue) {
                    mainFilterSelects[3].value = newValue;
                    if (mainFilterSelects[3]._customDropdown) {
                        mainFilterSelects[3]._customDropdown.updateButtonText();
                    }
                    const changeEvent = new Event('change', { bubbles: true });
                    mainFilterSelects[3].dispatchEvent(changeEvent);
                }
            }
            if (filterSelects.event) {
                const newValue = filterSelects.event.value || '';
                if (mainFilterSelects[4].value !== newValue) {
                    mainFilterSelects[4].value = newValue;
                    if (mainFilterSelects[4]._customDropdown) {
                        mainFilterSelects[4]._customDropdown.updateButtonText();
                    }
                    const changeEvent = new Event('change', { bubbles: true });
                    mainFilterSelects[4].dispatchEvent(changeEvent);
                }
            }
        }
    }

    /**
     * Clear all filters
     */
    function clearAllFilters() {
        // Clear modal selects
        Object.values(filterSelects).forEach(select => {
            if (select) select.value = '';
        });

        // Clear main selects and update custom dropdowns
        mainFilterSelects.forEach(select => {
            select.value = '';
            // Update custom dropdown if it exists
            if (select._customDropdown) {
                select._customDropdown.updateButtonText();
            }
        });

        // Apply filters (which will trigger search/filter logic)
        applyFilters();
    }

    /**
     * Apply filters
     */
    function applyFilters() {
        // Sync from modal to main selects
        syncFiltersFromModal();

        // Close modal
        closeModal();

        // Here you can add logic to filter jobs based on selected filters
        // For now, we'll just log the filter values
        const filters = {
            workPreference: filterSelects.workPreference?.value || '',
            salary: filterSelects.salary?.value || '',
            industry: filterSelects.industry?.value || '',
            experience: filterSelects.experience?.value || '',
            event: filterSelects.event?.value || ''
        };

        console.log('Applied filters:', filters);

        // TODO: Add actual filtering logic here
        // You can filter the job cards based on these filter values
        // and update the pagination accordingly
    }

    // Event listeners
    if (openButton) {
        openButton.addEventListener('click', (e) => {
            e.preventDefault();
            openModal();
        });
    }

    closeButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            closeModal();
        });
    });

    if (applyButton) {
        applyButton.addEventListener('click', (e) => {
            e.preventDefault();
            applyFilters();
        });
    }

    if (clearButton) {
        clearButton.addEventListener('click', (e) => {
            e.preventDefault();
            clearAllFilters();
        });
    }

    if (clearAllButton) {
        clearAllButton.addEventListener('click', (e) => {
            e.preventDefault();
            clearAllFilters();
        });
    }

    // Close on Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !modal.hasAttribute('hidden')) {
            closeModal();
        }
    });

    // Close on backdrop click
    const backdrop = modal.querySelector('.filter-modal__backdrop');
    if (backdrop) {
        backdrop.addEventListener('click', (e) => {
            if (e.target === backdrop) {
                closeModal();
            }
        });
    }

})();

