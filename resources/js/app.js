document.addEventListener('DOMContentLoaded', () => {
    const body = document.body;
    const sidebar = document.querySelector('[data-admin-sidebar]');
    const overlay = document.querySelector('[data-admin-overlay]');
    const openButtons = document.querySelectorAll('[data-admin-sidebar-toggle]');
    const closeButtons = document.querySelectorAll('[data-admin-sidebar-close]');
    const desktopMediaQuery = window.matchMedia('(min-width: 1024px)');

    const setSidebarState = (isOpen) => {
        if (! sidebar || ! overlay) {
            return;
        }

        if (desktopMediaQuery.matches) {
            sidebar.classList.remove('-translate-x-full', 'translate-x-0');
            overlay.classList.add('hidden');
            body.classList.remove('overflow-hidden');

            return;
        }

        sidebar.classList.toggle('-translate-x-full', ! isOpen);
        sidebar.classList.toggle('translate-x-0', isOpen);
        overlay.classList.toggle('hidden', ! isOpen);
        body.classList.toggle('overflow-hidden', isOpen);
    };

    openButtons.forEach((button) => {
        button.addEventListener('click', () => setSidebarState(true));
    });

    closeButtons.forEach((button) => {
        button.addEventListener('click', () => setSidebarState(false));
    });

    overlay?.addEventListener('click', () => setSidebarState(false));

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            setSidebarState(false);
        }
    });

    desktopMediaQuery.addEventListener('change', () => setSidebarState(false));
    setSidebarState(false);

    const sections = [...document.querySelectorAll('[data-admin-accordion-section]')];

    const syncSection = (section, shouldOpen) => {
        const trigger = section.querySelector('[data-admin-accordion-trigger]');
        const panel = section.querySelector('[data-admin-accordion-panel]');
        const chevron = section.querySelector('[data-admin-chevron]');

        if (! trigger || ! panel) {
            return;
        }

        trigger.setAttribute('aria-expanded', shouldOpen ? 'true' : 'false');
        panel.classList.toggle('hidden', ! shouldOpen);
        chevron?.classList.toggle('rotate-180', shouldOpen);
    };

    sections.forEach((section, index) => {
        const trigger = section.querySelector('[data-admin-accordion-trigger]');

        if (! trigger) {
            return;
        }

        if (! sections.some((item) => item.querySelector('[data-admin-accordion-trigger]')?.getAttribute('aria-expanded') === 'true') && index === 0) {
            syncSection(section, true);
        }

        trigger.addEventListener('click', () => {
            const shouldOpen = trigger.getAttribute('aria-expanded') !== 'true';

            sections.forEach((item) => syncSection(item, false));
            syncSection(section, shouldOpen);
        });
    });

    const siteNavToggle = document.querySelector('[data-site-nav-toggle]');
    const siteNavPanel = document.querySelector('[data-site-nav-panel]');
    const siteNavLinks = siteNavPanel?.querySelectorAll('a') ?? [];
    const siteDesktopMediaQuery = window.matchMedia('(min-width: 1024px)');

    const setSiteNavState = (isOpen) => {
        if (! siteNavPanel || ! siteNavToggle) {
            return;
        }

        if (siteDesktopMediaQuery.matches) {
            siteNavPanel.classList.add('hidden');
            siteNavToggle.setAttribute('aria-expanded', 'false');
            body.classList.remove('overflow-hidden');

            return;
        }

        siteNavPanel.classList.toggle('hidden', ! isOpen);
        siteNavToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        body.classList.toggle('overflow-hidden', isOpen);
    };

    siteNavToggle?.addEventListener('click', () => {
        const shouldOpen = siteNavToggle.getAttribute('aria-expanded') !== 'true';

        setSiteNavState(shouldOpen);
    });

    siteNavLinks.forEach((link) => {
        link.addEventListener('click', () => setSiteNavState(false));
    });

    siteDesktopMediaQuery.addEventListener('change', () => setSiteNavState(false));
    setSiteNavState(false);

    const siteSliders = [...document.querySelectorAll('[data-site-slider]')];

    siteSliders.forEach((slider) => {
        const slides = [...slider.querySelectorAll('[data-site-slide]')];
        const indicators = [...slider.querySelectorAll('[data-site-indicator]')];
        const previousButton = slider.querySelector('[data-site-prev]');
        const nextButton = slider.querySelector('[data-site-next]');

        if (slides.length <= 1) {
            return;
        }

        let currentIndex = Math.max(0, slides.findIndex((slide) => slide.dataset.active === 'true'));
        let autoplayHandle = null;

        const setSlide = (index) => {
            currentIndex = (index + slides.length) % slides.length;

            slides.forEach((slide, slideIndex) => {
                slide.dataset.active = slideIndex === currentIndex ? 'true' : 'false';
            });

            indicators.forEach((indicator, indicatorIndex) => {
                indicator.dataset.active = indicatorIndex === currentIndex ? 'true' : 'false';
            });
        };

        const startAutoplay = () => {
            if (autoplayHandle !== null) {
                return;
            }

            autoplayHandle = window.setInterval(() => {
                setSlide(currentIndex + 1);
            }, 6500);
        };

        const stopAutoplay = () => {
            if (autoplayHandle === null) {
                return;
            }

            window.clearInterval(autoplayHandle);
            autoplayHandle = null;
        };

        previousButton?.addEventListener('click', () => {
            setSlide(currentIndex - 1);
            stopAutoplay();
            startAutoplay();
        });

        nextButton?.addEventListener('click', () => {
            setSlide(currentIndex + 1);
            stopAutoplay();
            startAutoplay();
        });

        indicators.forEach((indicator) => {
            indicator.addEventListener('click', () => {
                const nextIndex = Number.parseInt(indicator.dataset.siteIndicator ?? '0', 10);

                if (Number.isNaN(nextIndex)) {
                    return;
                }

                setSlide(nextIndex);
                stopAutoplay();
                startAutoplay();
            });
        });

        slider.addEventListener('mouseenter', stopAutoplay);
        slider.addEventListener('mouseleave', startAutoplay);
        slider.addEventListener('focusin', stopAutoplay);
        slider.addEventListener('focusout', startAutoplay);

        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                stopAutoplay();

                return;
            }

            startAutoplay();
        });

        setSlide(currentIndex);
        startAutoplay();
    });
});
