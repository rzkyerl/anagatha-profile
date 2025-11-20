(() => {
    const onReady = (callback) => {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', callback, { once: true });
        } else {
            callback();
        }
    };

    onReady(() => {
        const serviceData = window.serviceCardData || {};
        const modal = document.querySelector('[data-service-modal]');
        const cards = document.querySelectorAll('[data-service-card]');
        const root = document.documentElement;

        if (!modal || !cards.length || !Object.keys(serviceData).length) {
            return;
        }

        const dialog = modal.querySelector('[data-service-modal-dialog]');
        const titleEl = modal.querySelector('[data-service-modal-title]');
        const summaryEl = modal.querySelector('[data-service-modal-summary]');
        const listEl = modal.querySelector('[data-service-modal-list]');
        const imageEl = modal.querySelector('[data-service-modal-image]');
        const ctaEl = modal.querySelector('[data-service-modal-cta]');
        const closeEls = modal.querySelectorAll('[data-service-modal-close]');
        const subtitleEl = modal.querySelector('[data-service-modal-subtitle]');
        const modalCopy = window.serviceModalCopy || {};

        if (subtitleEl && modalCopy.subtitle) {
            subtitleEl.textContent = modalCopy.subtitle;
        }

        let lastFocusedElement = null;
        let hideTimeout;
        let activeCard = null;
        let scrollPosition = 0;
        let previousBodyPaddingRight = '';

        const hoverClass = 'service-card--hover';
        const activeClass = 'service-card--active';

        const focusableSelectors = [
            'a[href]',
            'button:not([disabled])',
            '[tabindex]:not([tabindex="-1"])',
        ];

        const ensureTabStop = (card) => {
            if (!card.hasAttribute('tabindex')) {
                card.setAttribute('tabindex', '0');
            }
        };

        const setActiveCard = (card) => {
            if (activeCard && activeCard !== card) {
                activeCard.classList.remove(activeClass);
            }
            activeCard = card;
            card?.classList.add(activeClass);
        };

        const clearActiveCard = () => {
            if (activeCard) {
                activeCard.classList.remove(activeClass);
                activeCard = null;
            }
        };

        const bindHoverState = (card) => {
            const addHover = () => card.classList.add(hoverClass);
            const removeHover = () => card.classList.remove(hoverClass);

            card.addEventListener('mouseenter', addHover);
            card.addEventListener('mouseleave', removeHover);
            card.addEventListener('focus', addHover);
            card.addEventListener('blur', removeHover);
        };

        const getFocusableNodes = () => dialog
            ? Array.from(dialog.querySelectorAll(focusableSelectors.join(','))).filter(
                (node) => node.offsetParent !== null,
            )
            : [];

        const handleFocusTrap = (event) => {
            if (event.key !== 'Tab' || modal.getAttribute('aria-hidden') === 'true') {
                return;
            }

            const focusableNodes = getFocusableNodes();
            if (!focusableNodes.length) {
                event.preventDefault();
                return;
            }

            const firstNode = focusableNodes[0];
            const lastNode = focusableNodes[focusableNodes.length - 1];

            if (event.shiftKey && document.activeElement === firstNode) {
                event.preventDefault();
                lastNode.focus();
            } else if (!event.shiftKey && document.activeElement === lastNode) {
                event.preventDefault();
                firstNode.focus();
            }
        };

        const lockScroll = () => {
            scrollPosition = window.scrollY || window.pageYOffset || 0;
            const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
            previousBodyPaddingRight = document.body.style.paddingRight || '';

            if (scrollbarWidth > 0) {
                document.documentElement.style.setProperty('--scrollbar-compensation', `${scrollbarWidth}px`);
            } else {
                document.documentElement.style.setProperty('--scrollbar-compensation', '0px');
            }

            document.body.classList.add('modal-open');
            root.classList.add('modal-open');
        };

        const unlockScroll = () => {
            document.body.classList.remove('modal-open');
            root.classList.remove('modal-open');
            document.documentElement.style.setProperty('--scrollbar-compensation', '0px');
            previousBodyPaddingRight = '';
            window.scrollTo({ top: scrollPosition, behavior: 'auto' });
        };

        const openModal = (key) => {
            const service = serviceData[key];
            if (!service || !titleEl) {
                return;
            }

            clearTimeout(hideTimeout);
            lastFocusedElement = document.activeElement;

            titleEl.textContent = service.title || '';
            if (summaryEl && service.summary) {
                // Format summary with proper paragraph breaks
                let formattedSummary = service.summary.trim();
                
                // Split by double newlines or lines that start special keywords
                // First, normalize: replace multiple newlines with double newline
                formattedSummary = formattedSummary.replace(/\n{3,}/g, '\n\n');
                
                // Split by double newlines to get main paragraphs
                const paragraphs = formattedSummary
                    .split(/\n\s*\n/)
                    .map(para => para.replace(/\n/g, ' ').trim())
                    .filter(para => para.length > 0);
                
                // Process each paragraph
                const processedParagraphs = paragraphs.map(para => {
                    // Check if this paragraph starts with Visi/Misi
                    const visiMisiMatch = para.match(/^(Visi kami|Misi kami|Our vision|Our mission)\s+(adalah|is)\s+(.+)$/i);
                    if (visiMisiMatch) {
                        const title = visiMisiMatch[1];
                        const connector = visiMisiMatch[2];
                        let content = visiMisiMatch[3];
                        // Make "Anagata Executive" bold in content
                        content = content.replace(/(Anagata Executive)/gi, '<strong>$1</strong>');
                        return `<p><strong>${title} ${connector}</strong> ${content}</p>`;
                    }
                    
                    // Check if this paragraph is "Jenis Training" heading
                    const jenisTrainingMatch = para.match(/^(Jenis Training yang Kami Berikan|Types of Training We Provide)[:：]?\s*$/i);
                    if (jenisTrainingMatch) {
                        return `<p><strong>${jenisTrainingMatch[1]}:</strong></p>`;
                    }
                    
                    // Make "Anagata Executive" bold everywhere in regular paragraphs
                    para = para.replace(/(Anagata Executive)/gi, '<strong>$1</strong>');
                    
                    // Regular paragraph - wrap in <p>
                    return `<p>${para}</p>`;
                });
                
                formattedSummary = processedParagraphs.join('');
                summaryEl.innerHTML = formattedSummary;
            }

            if (imageEl) {
                imageEl.src = service.image || '';
                imageEl.alt = service.imageAlt || service.title || '';
                imageEl.classList.toggle('is-hidden', !service.image);
            }

            if (listEl) {
                listEl.innerHTML = '';
                if (Array.isArray(service.details) && service.details.length) {
                    listEl.removeAttribute('hidden');
                    service.details.forEach((detail) => {
                        const li = document.createElement('li');
                        li.textContent = detail;
                        listEl.appendChild(li);
                    });
                } else {
                    listEl.setAttribute('hidden', 'hidden');
                }
            }

            if (ctaEl) {
                ctaEl.href = service.cta || '#contact';
                if (modalCopy.cta) {
                    ctaEl.textContent = modalCopy.cta;
                }
            }

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
            clearActiveCard();

            hideTimeout = window.setTimeout(() => {
                modal.setAttribute('hidden', 'hidden');
            }, 220);

            if (lastFocusedElement && typeof lastFocusedElement.focus === 'function') {
                lastFocusedElement.focus();
            }
        };

        cards.forEach((card) => {
            const key = card.getAttribute('data-service-key');
            if (!key) {
                return;
            }

            ensureTabStop(card);
            bindHoverState(card);

            card.addEventListener('click', () => {
                setActiveCard(card);
                openModal(key);
            });
            card.addEventListener('keydown', (event) => {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    setActiveCard(card);
                    openModal(key);
                }
            });
        });

        closeEls.forEach((element) => {
            element.addEventListener('click', closeModal);
        });

        if (ctaEl) {
            ctaEl.addEventListener('click', () => {
                closeModal();
            });
        }

        modal.addEventListener('keydown', handleFocusTrap);

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && modal.getAttribute('aria-hidden') === 'false') {
                closeModal();
            }
        });
    });
})();

