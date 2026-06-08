document.addEventListener('DOMContentLoaded', () => {
    const root = document.getElementById('pimpinanApp');
    if (!root) {
        return;
    }

    const sidebar = document.getElementById('pimpinanSidebar');
    const navScroll = document.getElementById('pimpinanSidebarNav');
    const overlay = document.getElementById('pimpinanSidebarOverlay');
    const openBtn = document.getElementById('pimpinanSidebarOpen');
    const closeBtn = document.getElementById('pimpinanSidebarClose');

    const setSidebarOpen = (open) => {
        if (!sidebar || !overlay) {
            return;
        }

        sidebar.classList.toggle('-translate-x-full', !open);
        overlay.classList.toggle('pointer-events-none', !open);
        overlay.classList.toggle('opacity-0', !open);
        overlay.classList.toggle('opacity-100', open);
        document.body.classList.toggle('overflow-hidden', open && window.innerWidth < 1024);
    };

    const alignActiveNavItem = () => {
        if (!navScroll) {
            return;
        }

        const active = navScroll.querySelector('[data-nav-active]');
        if (!active) {
            navScroll.scrollTop = 0;
            return;
        }

        if (active.parentElement === navScroll) {
            navScroll.scrollTop = 0;
            return;
        }

        const padding = 8;
        const navRect = navScroll.getBoundingClientRect();
        const activeRect = active.getBoundingClientRect();

        if (activeRect.top < navRect.top + padding) {
            navScroll.scrollTop -= navRect.top - activeRect.top + padding;
        } else if (activeRect.bottom > navRect.bottom - padding) {
            navScroll.scrollTop += activeRect.bottom - navRect.bottom + padding;
        }
    };

    openBtn?.addEventListener('click', () => setSidebarOpen(true));
    closeBtn?.addEventListener('click', () => setSidebarOpen(false));
    overlay?.addEventListener('click', () => setSidebarOpen(false));

    window.addEventListener('resize', () => {
        if (window.innerWidth >= 1024) {
            document.body.classList.remove('overflow-hidden');
            overlay?.classList.add('pointer-events-none', 'opacity-0');
            overlay?.classList.remove('opacity-100');
            sidebar?.classList.remove('-translate-x-full');
        } else if (!overlay?.classList.contains('opacity-100')) {
            sidebar?.classList.add('-translate-x-full');
        }
    });

    root.querySelectorAll('[data-submenu-toggle]').forEach((button) => {
        const targetId = button.getAttribute('aria-controls');
        const panel = targetId ? document.getElementById(targetId) : null;
        const chevron = button.querySelector('[data-submenu-chevron]');

        if (!panel) {
            return;
        }

        button.addEventListener('click', () => {
            const expanded = button.getAttribute('aria-expanded') === 'true';
            const nextExpanded = !expanded;

            button.setAttribute('aria-expanded', nextExpanded ? 'true' : 'false');
            panel.classList.toggle('hidden', !nextExpanded);
            chevron?.classList.toggle('rotate-180', nextExpanded);
        });
    });

    alignActiveNavItem();

    const logoutModal = document.getElementById('pimpinanLogoutModal');
    const logoutBackdrop = document.getElementById('pimpinanLogoutModalBackdrop');
    const logoutPanel = document.getElementById('pimpinanLogoutModalPanel');
    const logoutOpenBtn = document.getElementById('pimpinanLogoutOpen');
    const logoutCancelBtn = document.getElementById('pimpinanLogoutCancel');
    const logoutConfirmBtn = document.getElementById('pimpinanLogoutConfirm');
    const logoutForm = document.getElementById('pimpinanLogoutForm');

    const setLogoutModalOpen = (open) => {
        if (!logoutModal || !logoutBackdrop || !logoutPanel) {
            return;
        }

        logoutModal.classList.toggle('hidden', !open);
        logoutModal.classList.toggle('flex', open);
        logoutModal.setAttribute('aria-hidden', open ? 'false' : 'true');
        document.body.classList.toggle('overflow-hidden', open);

        if (open) {
            requestAnimationFrame(() => {
                logoutBackdrop.classList.remove('opacity-0');
                logoutBackdrop.classList.add('opacity-100');
                logoutPanel.classList.remove('scale-95', 'opacity-0');
                logoutPanel.classList.add('scale-100', 'opacity-100');
            });
            logoutCancelBtn?.focus();
            return;
        }

        logoutBackdrop.classList.add('opacity-0');
        logoutBackdrop.classList.remove('opacity-100');
        logoutPanel.classList.add('scale-95', 'opacity-0');
        logoutPanel.classList.remove('scale-100', 'opacity-100');
    };

    logoutOpenBtn?.addEventListener('click', () => setLogoutModalOpen(true));
    logoutCancelBtn?.addEventListener('click', () => setLogoutModalOpen(false));
    logoutBackdrop?.addEventListener('click', () => setLogoutModalOpen(false));

    logoutConfirmBtn?.addEventListener('click', () => {
        logoutForm?.submit();
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && logoutModal && !logoutModal.classList.contains('hidden')) {
            setLogoutModalOpen(false);
        }
    });
});
