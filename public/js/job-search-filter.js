/**
 * Job Search and Filter
 * Handles search and filtering functionality for job listings
 */

(function() {
    'use strict';

    // Elements
    const jobCardsGrid = document.getElementById('jobCardsGrid');
    const searchInputs = document.querySelectorAll('.job-search-input');
    const searchButton = document.querySelector('.job-search-bar__button');
    const filterSelects = document.querySelectorAll('.job-filter-select');
    
    // Search state
    let searchState = {
        jobQuery: '',
        locationQuery: '',
        workPreference: '',
        salaryRange: '',
        industry: '',
        experience: '',
        degree: ''
    };

    if (!jobCardsGrid) return;

    /**
     * Get all job cards
     */
    function getAllJobCards() {
        return Array.from(jobCardsGrid.querySelectorAll('.job-card'));
    }

    /**
     * Check if a job card matches the search criteria
     */
    function matchesSearch(card, state) {
        const title = card.dataset.jobTitle || '';
        const company = card.dataset.jobCompany || '';
        const location = card.dataset.jobLocation || '';
        const workPref = card.dataset.workPreference || '';
        const salary = card.dataset.salaryRange || '';
        const experience = card.dataset.experienceLevel || '';

        // Search query (job title or company)
        if (state.jobQuery) {
            const query = state.jobQuery.toLowerCase();
            const matchesTitle = title.includes(query);
            const matchesCompany = company.includes(query);
            if (!matchesTitle && !matchesCompany) {
                return false;
            }
        }

        // Location search
        if (state.locationQuery) {
            const locationQuery = state.locationQuery.toLowerCase();
            if (!location.includes(locationQuery)) {
                return false;
            }
        }

        // Work preference filter
        if (state.workPreference && workPref !== state.workPreference) {
            return false;
        }

        // Salary range filter
        if (state.salaryRange && salary !== state.salaryRange) {
            return false;
        }

        // Experience level filter
        if (state.experience) {
            if (state.experience === 'entry' && experience !== 'entry') {
                return false;
            } else if (state.experience === '1-3' && !['1-3'].includes(experience)) {
                return false;
            } else if (state.experience === '3-5' && !['3-5'].includes(experience)) {
                return false;
            } else if (state.experience === '5+' && experience !== '5+') {
                return false;
            }
        }

        // Industry filter (not implemented in data yet, but ready for future)
        if (state.industry) {
            // Can be extended when industry data is available
        }

        // Degree filter (not implemented in data yet, but ready for future)
        if (state.degree) {
            // Can be extended when degree data is available
            // For now, we'll check if job card has degree data attribute
            const degree = card.dataset.degree || '';
            if (degree && degree !== state.degree) {
                return false;
            }
        }

        return true;
    }

    /**
     * Filter and display job cards
     */
    function filterJobs() {
        const allCards = getAllJobCards();
        let visibleCount = 0;
        const filteredCards = []; // Store cards that match filters

        allCards.forEach(card => {
            if (matchesSearch(card, searchState)) {
                card.style.display = 'grid';
                filteredCards.push(card); // Add to filtered array
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Update pagination with filtered results and filtered cards array
        updatePagination(visibleCount, filteredCards);

        // Show message if no results
        showNoResultsMessage(visibleCount === 0);

        return visibleCount;
    }

    /**
     * Show/hide no results message
     */
    function showNoResultsMessage(show) {
        let messageEl = document.getElementById('noResultsMessage');
        
        if (show && !messageEl) {
            messageEl = document.createElement('div');
            messageEl.id = 'noResultsMessage';
            messageEl.className = 'no-results-message';
            messageEl.innerHTML = `
                <div class="no-results-message__content">
                    <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                    <h3>No jobs found</h3>
                    <p>Try adjusting your search or filter criteria</p>
                </div>
            `;
            jobCardsGrid.parentNode.insertBefore(messageEl, jobCardsGrid.nextSibling);
        } else if (!show && messageEl) {
            messageEl.remove();
        }
    }

    /**
     * Update pagination with new visible count and filtered cards
     */
    function updatePagination(visibleCount, filteredCards) {
        // Dispatch custom event to trigger pagination update
        window.dispatchEvent(new CustomEvent('jobFilterUpdated', { 
            detail: { 
                visibleCount,
                filteredCards: filteredCards || []
            } 
        }));

        // Also try direct update if available
        if (window.jobPagination && typeof window.jobPagination.update === 'function') {
            window.jobPagination.update(visibleCount, filteredCards);
        }
    }

    /**
     * Update search state from inputs
     */
    function updateSearchState() {
        if (searchInputs.length >= 2) {
            searchState.jobQuery = searchInputs[0].value.trim();
            searchState.locationQuery = searchInputs[1].value.trim();
        }
    }

    /**
     * Update filter state from selects
     */
    function updateFilterState() {
        // Update from main filter selects (filter bar)
        if (filterSelects.length >= 4) {
            searchState.workPreference = filterSelects[0].value || '';
            searchState.salaryRange = filterSelects[1].value || '';
            searchState.industry = filterSelects[2].value || '';
            searchState.experience = filterSelects[3].value || '';
        }
        
        // Update degree from modal filter (only exists in modal)
        const degreeSelect = document.getElementById('filterDegree');
        if (degreeSelect) {
            searchState.degree = degreeSelect.value || '';
        }
    }

    /**
     * Perform search
     */
    function performSearch() {
        updateSearchState();
        updateFilterState();
        filterJobs();
    }

    /**
     * Handle search button click
     */
    if (searchButton) {
        searchButton.addEventListener('click', (e) => {
            e.preventDefault();
            performSearch();
        });
    }

    /**
     * Handle search input Enter key
     */
    searchInputs.forEach(input => {
        input.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                performSearch();
            }
        });

        // Real-time search (optional - can be enabled for instant filtering)
        // input.addEventListener('input', debounce(performSearch, 300));
    });

    /**
     * Handle filter select changes
     */
    filterSelects.forEach((select, index) => {
        select.addEventListener('change', () => {
            updateFilterState();
            filterJobs();
        });
    });


    /**
     * Clear all filters and search
     */
    function clearAll() {
        // Clear search inputs
        searchInputs.forEach(input => {
            input.value = '';
        });

        // Clear filter selects
        filterSelects.forEach(select => {
            select.value = '';
            // Update custom dropdown if it exists
            if (select._customDropdown) {
                select._customDropdown.updateButtonText();
            }
        });

        // Reset state
        searchState = {
            jobQuery: '',
            locationQuery: '',
            workPreference: '',
            salaryRange: '',
            industry: '',
            experience: '',
            degree: ''
        };

        // Show all jobs
        performSearch();
    }

    // Expose clearAll function globally for filter modal
    window.clearAllJobFilters = clearAll;

    /**
     * Initialize - apply any URL parameters or saved state
     */
    function init() {
        // Check URL parameters
        const urlParams = new URLSearchParams(window.location.search);
        const searchParam = urlParams.get('search');
        const locationParam = urlParams.get('location');

        if (searchParam && searchInputs.length > 0) {
            searchInputs[0].value = searchParam;
        }
        if (locationParam && searchInputs.length > 1) {
            searchInputs[1].value = locationParam;
        }

        // Wait a bit for pagination to initialize first
        setTimeout(() => {
            // Initial filter
            performSearch();
        }, 100);
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Listen for filter modal apply event
    document.addEventListener('filtersApplied', () => {
        updateFilterState();
        filterJobs();
    });

    // Listen for degree filter change in modal (if it exists)
    const degreeSelect = document.getElementById('filterDegree');
    if (degreeSelect) {
        degreeSelect.addEventListener('change', () => {
            updateFilterState();
            filterJobs();
        });
    }

    // Expose filterJobs function for external use
    window.jobSearchFilter = {
        filterJobs,
        performSearch,
        clearAll,
        getState: () => ({ ...searchState })
    };

})();

