(() => {
    const onReady = (callback) => {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', callback);
        } else {
            callback();
        }
    };

    onReady(() => {
        const modal = document.getElementById('security-modal');
        if (!modal) return;

        const dialog = modal.querySelector('[data-security-modal-dialog]');
        const openTriggers = document.querySelectorAll('[data-security-modal-open]');
        const closeTriggers = modal.querySelectorAll('[data-security-modal-close]');
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
        const backdrop = modal.querySelector('.security-modal__backdrop');
        if (backdrop) {
            backdrop.addEventListener('click', (e) => {
                if (e.target === backdrop) {
                    closeModal();
                }
            });
        }
    });
})();

