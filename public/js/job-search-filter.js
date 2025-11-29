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
        if (state.salaryRange) {
            const salaryRange = card.dataset.salaryRange || '';
            const salaryMin = parseFloat(card.dataset.salaryMin || 0);
            const salaryMax = parseFloat(card.dataset.salaryMax || 0);
            
            // If job has no salary data, exclude it when filter is active
            if (salaryMin === 0 && salaryMax === 0) {
            return false;
            }
            
            // Define job's actual salary range in millions
            const jobMin = salaryMin;
            const jobMax = salaryMax > 0 ? salaryMax : 999999; // Use large number if no max (for "X+" format)
            
            // Define selected filter range boundaries
            let filterMin = 0;
            let filterMax = 0;
            
            switch(state.salaryRange) {
                case '0-5':
                    filterMin = 0;
                    filterMax = 5;
                    break;
                case '5-10':
                    filterMin = 5;
                    filterMax = 10;
                    break;
                case '10-20':
                    filterMin = 10;
                    filterMax = 20;
                    break;
                case '20+':
                    filterMin = 20;
                    filterMax = 999999; // No upper limit
                    break;
            }
            
            // Check if job salary range overlaps with filter range
            // Two ranges overlap if: jobMin < filterMax AND jobMax > filterMin
            const overlaps = jobMin < filterMax && jobMax > filterMin;
            
            if (!overlaps) {
                return false;
            }
        }

        // Experience level filter
        if (state.experience) {
            if (state.experience === 'entry' && experience !== 'entry') {
                return false;
            } else if (state.experience === '1-3' && experience !== '1-3') {
                return false;
            } else if (state.experience === '3-5' && experience !== '3-5') {
                return false;
            } else if (state.experience === '5+' && experience !== '5+') {
                return false;
            }
        }

        // Industry filter
        if (state.industry) {
            const cardIndustry = card.dataset.industry || '';
            if (!cardIndustry || cardIndustry !== state.industry) {
                return false;
            }
        }

        // Degree filter
        if (state.degree) {
            const cardDegree = card.dataset.degree || '';
            // If filter is set, only show cards that match (ignore cards without degree data)
            if (!cardDegree || cardDegree !== state.degree) {
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
            // Build active filters list
            const activeFilters = [];
            if (searchState.jobQuery) {
                activeFilters.push(`Job: "${searchState.jobQuery}"`);
            }
            if (searchState.locationQuery) {
                activeFilters.push(`Location: "${searchState.locationQuery}"`);
            }
            if (searchState.workPreference) {
                const workPrefLabels = {
                    'wfo': 'WFO',
                    'wfh': 'WFH',
                    'hybrid': 'Hybrid'
                };
                activeFilters.push(`Work: ${workPrefLabels[searchState.workPreference] || searchState.workPreference}`);
            }
            if (searchState.salaryRange) {
                const salaryLabels = {
                    '0-5': 'IDR 0 - 5M',
                    '5-10': 'IDR 5M - 10M',
                    '10-20': 'IDR 10M - 20M',
                    '20+': 'IDR 20M+'
                };
                activeFilters.push(`Salary: ${salaryLabels[searchState.salaryRange] || searchState.salaryRange}`);
            }
            if (searchState.industry) {
                const industryLabels = {
                    'tech': 'Technology',
                    'finance': 'Finance',
                    'retail': 'Retail',
                    'manufacturing': 'Manufacturing'
                };
                activeFilters.push(`Industry: ${industryLabels[searchState.industry] || searchState.industry}`);
            }
            if (searchState.experience) {
                const expLabels = {
                    'entry': 'Entry Level',
                    '1-3': '1-3 Years',
                    '3-5': '3-5 Years',
                    '5+': '5+ Years'
                };
                activeFilters.push(`Experience: ${expLabels[searchState.experience] || searchState.experience}`);
            }
            if (searchState.degree) {
                activeFilters.push(`Degree: ${searchState.degree}`);
            }

            const filtersText = activeFilters.length > 0 
                ? `<div class="no-results-message__filters">
                    <p class="no-results-message__filters-label">Active filters:</p>
                    <div class="no-results-message__filters-list">${activeFilters.join(', ')}</div>
                   </div>` 
                : '';

            messageEl = document.createElement('div');
            messageEl.id = 'noResultsMessage';
            messageEl.className = 'no-results-message';
            messageEl.innerHTML = `
                <div class="no-results-message__content">
                    <div class="no-results-message__icon">
                    <i class="fa-solid fa-search" aria-hidden="true"></i>
                    </div>
                    <h3 class="no-results-message__title">No jobs found</h3>
                    <p class="no-results-message__text">We couldn't find any jobs matching your search criteria.</p>
                    ${filtersText}
                    <div class="no-results-message__actions">
                        <button type="button" class="no-results-message__button" id="clearFiltersFromNoResults">
                            <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                            Clear All Filters
                        </button>
                    </div>
                </div>
            `;
            
            // Insert after job cards grid
            jobCardsGrid.parentNode.insertBefore(messageEl, jobCardsGrid.nextSibling);
            
            // Add event listener to clear button
            const clearButton = messageEl.querySelector('#clearFiltersFromNoResults');
            if (clearButton && window.clearAllJobFilters) {
                clearButton.addEventListener('click', () => {
                    window.clearAllJobFilters();
                });
            }
        } else if (show && messageEl) {
            // Update existing message with current filters
            const activeFilters = [];
            if (searchState.jobQuery) activeFilters.push(`Job: "${searchState.jobQuery}"`);
            if (searchState.locationQuery) activeFilters.push(`Location: "${searchState.locationQuery}"`);
            if (searchState.workPreference) {
                const workPrefLabels = { 'wfo': 'WFO', 'wfh': 'WFH', 'hybrid': 'Hybrid' };
                activeFilters.push(`Work: ${workPrefLabels[searchState.workPreference] || searchState.workPreference}`);
            }
            if (searchState.salaryRange) {
                const salaryLabels = { '0-5': 'IDR 0 - 5M', '5-10': 'IDR 5M - 10M', '10-20': 'IDR 10M - 20M', '20+': 'IDR 20M+' };
                activeFilters.push(`Salary: ${salaryLabels[searchState.salaryRange] || searchState.salaryRange}`);
            }
            if (searchState.industry) {
                const industryLabels = { 'tech': 'Technology', 'finance': 'Finance', 'retail': 'Retail', 'manufacturing': 'Manufacturing' };
                activeFilters.push(`Industry: ${industryLabels[searchState.industry] || searchState.industry}`);
            }
            if (searchState.experience) {
                const expLabels = { 'entry': 'Entry Level', '1-3': '1-3 Years', '3-5': '3-5 Years', '5+': '5+ Years' };
                activeFilters.push(`Experience: ${expLabels[searchState.experience] || searchState.experience}`);
            }
            if (searchState.degree) activeFilters.push(`Degree: ${searchState.degree}`);

            const filtersText = activeFilters.length > 0 
                ? `<div class="no-results-message__filters">
                    <p class="no-results-message__filters-label">Active filters:</p>
                    <div class="no-results-message__filters-list">${activeFilters.join(', ')}</div>
                   </div>` 
                : '';

            const contentEl = messageEl.querySelector('.no-results-message__content');
            if (contentEl) {
                contentEl.innerHTML = `
                    <div class="no-results-message__icon">
                        <i class="fa-solid fa-search" aria-hidden="true"></i>
                    </div>
                    <h3 class="no-results-message__title">No jobs found</h3>
                    <p class="no-results-message__text">We couldn't find any jobs matching your search criteria.</p>
                    ${filtersText}
                    <div class="no-results-message__actions">
                        <button type="button" class="no-results-message__button" id="clearFiltersFromNoResults">
                            <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                            Clear All Filters
                        </button>
                    </div>
                `;
                
                // Reattach event listener
                const clearButton = contentEl.querySelector('#clearFiltersFromNoResults');
                if (clearButton && window.clearAllJobFilters) {
                    clearButton.addEventListener('click', () => {
                        window.clearAllJobFilters();
                    });
                }
            }
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

