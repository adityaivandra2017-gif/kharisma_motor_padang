function initNotificationDropdown(prefix) {
    const root = document.getElementById(`${prefix}NotificationRoot`);
    const toggle = document.getElementById(`${prefix}NotificationToggle`);
    const panel = document.getElementById(`${prefix}NotificationPanel`);

    if (!root || !toggle || !panel) {
        return;
    }

    const setOpen = (open) => {
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');

        if (open) {
            panel.hidden = false;
            requestAnimationFrame(() => {
                panel.classList.remove('pointer-events-none', 'scale-95', 'opacity-0');
                panel.classList.add('pointer-events-auto', 'scale-100', 'opacity-100', 'stock-notification-panel--open');
            });
            return;
        }

        panel.classList.add('pointer-events-none', 'scale-95', 'opacity-0');
        panel.classList.remove('pointer-events-auto', 'scale-100', 'opacity-100', 'stock-notification-panel--open');
        window.setTimeout(() => {
            if (toggle.getAttribute('aria-expanded') === 'false') {
                panel.hidden = true;
            }
        }, 200);
    };

    toggle.addEventListener('click', (event) => {
        event.stopPropagation();
        const open = toggle.getAttribute('aria-expanded') === 'true';
        setOpen(!open);
    });

    document.addEventListener('click', (event) => {
        if (!root.contains(event.target)) {
            setOpen(false);
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            setOpen(false);
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('adminApp')) {
        initNotificationDropdown('admin');
    }

    if (document.getElementById('pimpinanApp')) {
        initNotificationDropdown('pimpinan');
    }
});
