const BULAN = [
    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
];

function pad(value) {
    return String(value).padStart(2, '0');
}

function toIso(year, month, day) {
    return `${year}-${pad(month + 1)}-${pad(day)}`;
}

function parseIso(value) {
    if (!value || !/^\d{4}-\d{2}-\d{2}$/.test(value)) {
        return null;
    }

    const [year, month, day] = value.split('-').map(Number);
    const date = new Date(year, month - 1, day);

    if (
        date.getFullYear() !== year
        || date.getMonth() !== month - 1
        || date.getDate() !== day
    ) {
        return null;
    }

    return date;
}

function formatDisplay(iso) {
    const date = parseIso(iso);
    if (!date) {
        return '';
    }

    return `${pad(date.getDate())}/${pad(date.getMonth() + 1)}/${date.getFullYear()}`;
}

function parseManualText(text) {
    const trimmed = text.trim();
    if (trimmed === '') {
        return '';
    }

    if (/^\d{4}-\d{2}-\d{2}$/.test(trimmed)) {
        return parseIso(trimmed) ? trimmed : null;
    }

    const match = trimmed.match(/^(\d{1,2})[/-](\d{1,2})[/-](\d{4})$/);
    if (!match) {
        return null;
    }

    const day = Number(match[1]);
    const month = Number(match[2]);
    const year = Number(match[3]);
    const iso = toIso(year, month - 1, day);

    return parseIso(iso) ? iso : null;
}

function getPanel(root) {
    return root._datePanel || root.querySelector('[data-admin-date-panel]');
}

function attachPanelToBody(root, panel) {
    if (!panel || panel.parentElement === document.body) {
        return;
    }

    document.body.appendChild(panel);
}

function restorePanelHome(root, panel) {
    if (!panel || panel.parentElement === root) {
        return;
    }

    root.appendChild(panel);
}

function resetPanelPosition(panel) {
    if (!panel) {
        return;
    }

    panel.style.position = '';
    panel.style.top = '';
    panel.style.left = '';
    panel.style.width = '';
    panel.style.visibility = '';
    panel.classList.remove('is-fixed', 'is-above', 'is-below');
}

function positionPanel(root) {
    const field = root.querySelector('.admin-date-picker__field');
    const panel = getPanel(root);

    if (!field || !panel || panel.classList.contains('hidden')) {
        return;
    }

    panel.classList.add('is-fixed');
    panel.style.position = 'fixed';
    panel.style.width = '18.5rem';
    panel.style.visibility = 'hidden';

    const panelHeight = panel.offsetHeight;
    const panelWidth = panel.offsetWidth;
    const rect = field.getBoundingClientRect();
    const gap = 8;
    const padding = 12;
    const spaceAbove = rect.top - padding;
    const spaceBelow = window.innerHeight - rect.bottom - padding;

    let openAbove = spaceAbove >= panelHeight + gap;

    if (!openAbove && spaceBelow < panelHeight + gap) {
        openAbove = spaceAbove >= spaceBelow;
    } else if (openAbove && spaceBelow >= panelHeight + gap && spaceBelow > spaceAbove + 40) {
        openAbove = false;
    }

    panel.classList.toggle('is-above', openAbove);
    panel.classList.toggle('is-below', !openAbove);

    let top = openAbove
        ? rect.top - panelHeight - gap
        : rect.bottom + gap;

    top = Math.max(padding, Math.min(top, window.innerHeight - panelHeight - padding));

    let left = rect.left;
    left = Math.max(padding, Math.min(left, window.innerWidth - panelWidth - padding));

    panel.style.top = `${top}px`;
    panel.style.left = `${left}px`;
    panel.style.visibility = '';
}

function closeAllDatePickers() {
    document.querySelectorAll('[data-admin-date-picker]').forEach((root) => {
        const panel = getPanel(root);
        panel?.classList.add('hidden');
        resetPanelPosition(panel);
        restorePanelHome(root, panel);
        root.querySelector('[data-admin-date-toggle]')?.setAttribute('aria-expanded', 'false');
        root.querySelector('.admin-date-picker__field')?.classList.remove('is-open');
        root.removeAttribute('data-admin-date-open');
    });
}

function syncDisplayFromHidden(root) {
    const input = root.querySelector('[data-admin-date-input]');
    const textInput = root.querySelector('[data-admin-date-text]');

    if (!input || !textInput) {
        return;
    }

    textInput.value = input.value === '' ? '' : formatDisplay(input.value);
    textInput.classList.remove('is-invalid');
}

function setHiddenValue(root, iso) {
    const input = root.querySelector('[data-admin-date-input]');
    if (!input) {
        return;
    }

    input.value = iso;
    input.dispatchEvent(new Event('change', { bubbles: true }));
    syncDisplayFromHidden(root);
}

function renderCalendar(root) {
    const panel = getPanel(root);
    const grid = panel?.querySelector('[data-admin-date-grid]');
    const monthLabel = panel?.querySelector('[data-admin-date-month-label]');
    const input = root.querySelector('[data-admin-date-input]');

    if (!panel || !grid || !monthLabel || !input) {
        return;
    }

    const viewYear = Number(root.dataset.viewYear);
    const viewMonth = Number(root.dataset.viewMonth);
    const selected = input.value;
    const today = new Date();
    const todayIso = toIso(today.getFullYear(), today.getMonth(), today.getDate());

    monthLabel.textContent = `${BULAN[viewMonth]} ${viewYear}`;
    grid.innerHTML = '';

    const firstDay = new Date(viewYear, viewMonth, 1);
    const startOffset = (firstDay.getDay() + 6) % 7;
    const daysInMonth = new Date(viewYear, viewMonth + 1, 0).getDate();

    for (let i = 0; i < startOffset; i += 1) {
        const empty = document.createElement('span');
        empty.className = 'admin-date-picker__day admin-date-picker__day--empty';
        grid.appendChild(empty);
    }

    for (let day = 1; day <= daysInMonth; day += 1) {
        const iso = toIso(viewYear, viewMonth, day);
        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'admin-date-picker__day';
        button.textContent = String(day);
        button.dataset.date = iso;

        if (iso === selected) {
            button.classList.add('is-selected');
        } else if (iso === todayIso) {
            button.classList.add('is-today');
        }

        button.addEventListener('click', () => {
            setHiddenValue(root, iso);
            closeAllDatePickers();
        });

        grid.appendChild(button);
    }

    if (root.hasAttribute('data-admin-date-open')) {
        requestAnimationFrame(() => positionPanel(root));
    }
}

function commitManualInput(root) {
    const textInput = root.querySelector('[data-admin-date-text]');
    const input = root.querySelector('[data-admin-date-input]');

    if (!textInput || !input) {
        return;
    }

    const parsed = parseManualText(textInput.value);

    if (parsed === '') {
        input.value = '';
        input.dispatchEvent(new Event('change', { bubbles: true }));
        textInput.classList.remove('is-invalid');
        return;
    }

    if (parsed === null) {
        textInput.classList.add('is-invalid');
        syncDisplayFromHidden(root);
        return;
    }

    input.value = parsed;
    input.dispatchEvent(new Event('change', { bubbles: true }));
    syncDisplayFromHidden(root);
}

function initDatePicker(root) {
    const toggle = root.querySelector('[data-admin-date-toggle]');
    const panel = root.querySelector('[data-admin-date-panel]');
    const input = root.querySelector('[data-admin-date-input]');
    const textInput = root.querySelector('[data-admin-date-text]');
    const field = root.querySelector('.admin-date-picker__field');

    if (!toggle || !panel || !input || !textInput) {
        return;
    }

    root._datePanel = panel;

    const prevBtn = panel.querySelector('[data-admin-date-prev]');
    const nextBtn = panel.querySelector('[data-admin-date-next]');
    const clearBtn = panel.querySelector('[data-admin-date-clear]');
    const todayBtn = panel.querySelector('[data-admin-date-today]');

    const setViewFromValue = () => {
        const parsed = parseIso(input.value) || new Date();
        root.dataset.viewYear = String(parsed.getFullYear());
        root.dataset.viewMonth = String(parsed.getMonth());
    };

    setViewFromValue();
    syncDisplayFromHidden(root);

    const open = () => {
        closeAllDatePickers();
        setViewFromValue();
        panel.classList.remove('hidden');
        attachPanelToBody(root, panel);
        renderCalendar(root);
        toggle.setAttribute('aria-expanded', 'true');
        field?.classList.add('is-open');
        root.setAttribute('data-admin-date-open', '');
        requestAnimationFrame(() => positionPanel(root));
    };

    toggle.addEventListener('click', (event) => {
        event.stopPropagation();
        if (panel.classList.contains('hidden')) {
            open();
        } else {
            closeAllDatePickers();
        }
    });

    field?.addEventListener('click', (event) => {
        event.stopPropagation();
    });

    panel.addEventListener('click', (event) => {
        event.stopPropagation();
    });

    textInput.addEventListener('keydown', (event) => {
        if (event.key === 'Enter') {
            event.preventDefault();
            commitManualInput(root);
            closeAllDatePickers();
        }
    });

    textInput.addEventListener('blur', () => {
        commitManualInput(root);
    });

    prevBtn?.addEventListener('click', () => {
        let year = Number(root.dataset.viewYear);
        let month = Number(root.dataset.viewMonth) - 1;
        if (month < 0) {
            month = 11;
            year -= 1;
        }
        root.dataset.viewYear = String(year);
        root.dataset.viewMonth = String(month);
        renderCalendar(root);
    });

    nextBtn?.addEventListener('click', () => {
        let year = Number(root.dataset.viewYear);
        let month = Number(root.dataset.viewMonth) + 1;
        if (month > 11) {
            month = 0;
            year += 1;
        }
        root.dataset.viewYear = String(year);
        root.dataset.viewMonth = String(month);
        renderCalendar(root);
    });

    clearBtn?.addEventListener('click', () => {
        setHiddenValue(root, '');
        closeAllDatePickers();
    });

    todayBtn?.addEventListener('click', () => {
        const now = new Date();
        setHiddenValue(root, toIso(now.getFullYear(), now.getMonth(), now.getDate()));
        closeAllDatePickers();
    });
}

function initAdminDatePickers() {
    document.querySelectorAll('[data-admin-date-picker]').forEach(initDatePicker);

    document.addEventListener('click', closeAllDatePickers);
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeAllDatePickers();
        }
    });

    document.querySelectorAll('form').forEach((form) => {
        form.addEventListener('submit', () => {
            form.querySelectorAll('[data-admin-date-picker]').forEach((root) => {
                commitManualInput(root);
            });
        });
    });

    const repositionOpenPickers = () => {
        document.querySelectorAll('[data-admin-date-open]').forEach((root) => {
            positionPanel(root);
        });
    };

    window.addEventListener('resize', repositionOpenPickers);
    window.addEventListener('scroll', repositionOpenPickers, true);
}

document.addEventListener('DOMContentLoaded', initAdminDatePickers);
