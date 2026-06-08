document.addEventListener('DOMContentLoaded', () => {
    const modal = document.querySelector('[data-delete-modal]');
    if (!modal) {
        return;
    }

    const backdrop = modal.querySelector('[data-delete-backdrop]');
    const panel = modal.querySelector('[data-delete-panel]');
    const titleEl = modal.querySelector('[data-delete-title]');
    const subtitleEl = modal.querySelector('[data-delete-subtitle]');
    const messageEl = modal.querySelector('[data-delete-message]');
    const itemEl = modal.querySelector('[data-delete-item]');
    const itemWrap = modal.querySelector('[data-delete-item-wrap]');
    const cancelBtn = modal.querySelector('[data-delete-cancel]');
    const confirmBtn = modal.querySelector('[data-delete-confirm]');

    let pendingForm = null;

    const setPanelVisible = (visible) => {
        if (!panel) {
            return;
        }

        panel.classList.toggle('scale-95', !visible);
        panel.classList.toggle('opacity-0', !visible);
        panel.classList.toggle('scale-100', visible);
        panel.classList.toggle('opacity-100', visible);
    };

    const setModalOpen = (open) => {
        modal.classList.toggle('hidden', !open);
        modal.classList.toggle('flex', open);
        modal.setAttribute('aria-hidden', open ? 'false' : 'true');
        document.body.classList.toggle('overflow-hidden', open);

        if (!open) {
            setPanelVisible(false);
            pendingForm = null;
            return;
        }

        requestAnimationFrame(() => setPanelVisible(true));
        cancelBtn?.focus();
    };

    document.querySelectorAll('[data-delete-trigger]').forEach((trigger) => {
        trigger.addEventListener('click', () => {
            const formId = trigger.dataset.deleteForm;
            const form = formId ? document.getElementById(formId) : null;

            if (!form) {
                return;
            }

            pendingForm = form;

            if (titleEl) {
                titleEl.textContent = trigger.dataset.deleteTitle || 'Konfirmasi Hapus';
            }
            if (messageEl) {
                messageEl.textContent =
                    trigger.dataset.deleteMessage || 'Apakah Anda yakin ingin menghapus data ini?';
            }
            if (subtitleEl) {
                const subtitle = trigger.dataset.deleteSubtitle || '';
                subtitleEl.textContent = subtitle;
                subtitleEl.classList.toggle('hidden', subtitle === '');
            }
            if (itemEl && itemWrap) {
                const item = trigger.dataset.deleteItem || '';
                itemEl.textContent = item;
                itemWrap.classList.toggle('hidden', item === '');
            }

            setModalOpen(true);
        });
    });

    cancelBtn?.addEventListener('click', () => setModalOpen(false));
    backdrop?.addEventListener('click', () => setModalOpen(false));

    confirmBtn?.addEventListener('click', () => {
        pendingForm?.submit();
        setModalOpen(false);
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
            setModalOpen(false);
        }
    });
});
