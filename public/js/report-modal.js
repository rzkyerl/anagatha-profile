(() => {
    const onReady = (callback) => {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', callback);
        } else {
            callback();
        }
    };

    onReady(() => {
        const modal = document.getElementById('report-modal');
        if (!modal) return;

        const dialog = modal.querySelector('[data-report-modal-dialog]');
        const openTriggers = document.querySelectorAll('[data-report-modal-open]');
        const closeTriggers = modal.querySelectorAll('[data-report-modal-close]');
        const form = document.getElementById('report-form');
        let lastFocusedElement = null;
        let hideTimeout = null;

        const lockScroll = () => {
            const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
            document.documentElement.style.setProperty('--scrollbar-compensation', `${scrollbarWidth}px`);
            document.documentElement.classList.add('modal-open');
            document.body.classList.add('modal-open');
        };

        const unlockScroll = () => {
            document.documentElement.classList.remove('modal-open');
            document.body.classList.remove('modal-open');
            document.documentElement.style.removeProperty('--scrollbar-compensation');
        };

        const openModal = () => {
            if (!modal.hasAttribute('hidden')) {
                return;
            }

            lastFocusedElement = document.activeElement;
            modal.removeAttribute('hidden');
            modal.setAttribute('aria-hidden', 'false');

            requestAnimationFrame(() => {
                modal.classList.add('is-active');
                lockScroll();
                if (dialog && typeof dialog.focus === 'function') {
                    dialog.focus({ preventScroll: true });
                }
            });
        };

        const closeModal = () => {
            if (modal.hasAttribute('hidden')) {
                return;
            }

            modal.classList.remove('is-active');
            modal.setAttribute('aria-hidden', 'true');
            unlockScroll();

            hideTimeout = window.setTimeout(() => {
                modal.setAttribute('hidden', 'hidden');
                if (form) {
                    form.reset();
                }
            }, 220);

            if (lastFocusedElement && typeof lastFocusedElement.focus === 'function') {
                lastFocusedElement.focus();
            }
        };

        openTriggers.forEach((trigger) => {
            trigger.addEventListener('click', (e) => {
                e.preventDefault();
                openModal();
            });
        });

        closeTriggers.forEach((trigger) => {
            trigger.addEventListener('click', (e) => {
                e.preventDefault();
                closeModal();
            });
        });

        // Close on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !modal.hasAttribute('hidden')) {
                closeModal();
            }
        });

        // Close on backdrop click
        const backdrop = modal.querySelector('.report-modal__backdrop');
        if (backdrop) {
            backdrop.addEventListener('click', (e) => {
                if (e.target === backdrop) {
                    closeModal();
                }
            });
        }

        // Handle form submission
        if (form) {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                
                // Here you can add form submission logic
                // For now, just show an alert and close modal
                alert('Thank you for your report. We will review it shortly.');
                closeModal();
            });
        }

        // Initialize custom dropdown when modal opens
        const initCustomDropdown = () => {
            const select = document.getElementById('report-category');
            if (select && typeof window.CustomDropdown !== 'undefined') {
                // Trigger custom dropdown initialization
                setTimeout(() => {
                    const selects = document.querySelectorAll('#report-modal .job-filter-select');
                    selects.forEach(sel => {
                        if (sel.dataset.dropdownInitialized !== 'true') {
                            // Re-initialize custom dropdowns
                            if (typeof initCustomDropdowns === 'function') {
                                initCustomDropdowns();
                            } else {
                                // Fallback: trigger the custom dropdown script
                                const event = new Event('DOMContentLoaded');
                                document.dispatchEvent(event);
                            }
                        }
                    });
                }, 100);
            }
        };

        // Re-initialize custom dropdowns when modal opens
        openTriggers.forEach((trigger) => {
            trigger.addEventListener('click', (e) => {
                e.preventDefault();
                openModal();
                // Initialize custom dropdown after modal opens
                setTimeout(initCustomDropdown, 200);
            });
        });
    });
})();

