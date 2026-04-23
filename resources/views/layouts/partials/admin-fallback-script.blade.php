<script>
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

        if (overlay) {
            overlay.addEventListener('click', () => setSidebarState(false));
        }

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

            if (chevron) {
                chevron.classList.toggle('rotate-180', shouldOpen);
            }
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
    });
</script>
