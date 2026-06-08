document.addEventListener('DOMContentLoaded', () => {
    const popovers = Array.from(document.querySelectorAll('details[data-info-popover]'));
    if (popovers.length === 0) {
        return;
    }

    const closeOthers = (activePopover) => {
        popovers.forEach((popover) => {
            if (popover !== activePopover) {
                popover.removeAttribute('open');
            }
        });
    };

    popovers.forEach((popover) => {
        popover.addEventListener('toggle', () => {
            if (popover.open) {
                closeOthers(popover);
                requestAnimationFrame(() => adjustPanelPosition(popover));
            }
        });
    });

    const adjustPanelPosition = (popover) => {
        const panel = popover.querySelector('[data-info-panel]');
        if (!(panel instanceof HTMLElement)) {
            return;
        }

        panel.style.left = '';
        panel.style.right = '';
        panel.style.transform = '';

        const panelRect = panel.getBoundingClientRect();
        const margin = 12;
        let shiftX = 0;

        if (panelRect.left < margin) {
            shiftX = margin - panelRect.left;
        } else if (panelRect.right > window.innerWidth - margin) {
            shiftX = window.innerWidth - margin - panelRect.right;
        }

        if (shiftX !== 0) {
            panel.style.transform = `translateX(calc(-50% + ${shiftX}px))`;
        } else {
            panel.style.transform = 'translateX(-50%)';
        }
    };

    document.addEventListener('click', (event) => {
        const target = event.target;
        if (!(target instanceof Node)) {
            return;
        }

        const clickedInsidePopover = popovers.some((popover) => popover.contains(target));
        if (!clickedInsidePopover) {
            popovers.forEach((popover) => popover.removeAttribute('open'));
        }
    });
});
