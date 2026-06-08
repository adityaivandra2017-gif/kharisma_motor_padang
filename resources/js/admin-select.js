function buildMenuFromNativeSelect(nativeSelect, menu) {
    menu.innerHTML = '';

    Array.from(nativeSelect.options).forEach((option) => {
        const item = document.createElement('li');
        const button = document.createElement('button');

        button.type = 'button';
        button.role = 'option';
        button.setAttribute('data-admin-select-option', '');
        button.setAttribute('data-value', option.value);
        button.textContent = option.textContent?.trim() ?? '';
        button.className = 'admin-filter-select__option';
        button.setAttribute('aria-selected', option.selected ? 'true' : 'false');

        if (option.selected) {
            button.classList.add('is-selected');
        }

        if (option.disabled) {
            button.disabled = true;
        }

        item.appendChild(button);
        menu.appendChild(item);
    });
}

function syncSelectedState(root) {
    const nativeSelect = root.querySelector('[data-admin-select-native]');
    const hiddenInput = root.querySelector('[data-admin-select-input]');
    const label = root.querySelector('[data-admin-select-label]');
    const options = root.querySelectorAll('[data-admin-select-option]');

    const selectedValue = nativeSelect
        ? nativeSelect.value
        : (hiddenInput?.value ?? '');

    options.forEach((option) => {
        const isSelected = option.getAttribute('data-value') === selectedValue;
        option.classList.toggle('is-selected', isSelected);
        option.setAttribute('aria-selected', isSelected ? 'true' : 'false');
    });

    if (!label) {
        return;
    }

    if (nativeSelect) {
        const selectedOption = nativeSelect.options[nativeSelect.selectedIndex];
        label.textContent = selectedOption?.textContent?.trim() ?? '';
        return;
    }

    const selectedButton = Array.from(options).find(
        (option) => option.getAttribute('data-value') === selectedValue,
    );
    label.textContent = selectedButton?.textContent?.trim() ?? '';
}

function initAdminSelects() {
    document.querySelectorAll('[data-admin-select]').forEach((root) => {
        const trigger = root.querySelector('[data-admin-select-trigger]');
        const menu = root.querySelector('[data-admin-select-menu]');
        const nativeSelect = root.querySelector('[data-admin-select-native]');
        const hiddenInput = root.querySelector('[data-admin-select-input]');

        if (!trigger || !menu) {
            return;
        }

        if (nativeSelect && menu.children.length === 0) {
            buildMenuFromNativeSelect(nativeSelect, menu);
        }

        syncSelectedState(root);

        const close = () => {
            menu.classList.add('hidden');
            trigger.setAttribute('aria-expanded', 'false');
            root.removeAttribute('data-admin-select-open');
        };

        const open = () => {
            document.querySelectorAll('[data-admin-select]').forEach((other) => {
                if (other === root) {
                    return;
                }

                other.querySelector('[data-admin-select-menu]')?.classList.add('hidden');
                other.querySelector('[data-admin-select-trigger]')?.setAttribute('aria-expanded', 'false');
                other.removeAttribute('data-admin-select-open');
            });

            menu.classList.remove('hidden');
            trigger.setAttribute('aria-expanded', 'true');
            root.setAttribute('data-admin-select-open', '');
        };

        trigger.addEventListener('click', (event) => {
            event.stopPropagation();

            if (menu.classList.contains('hidden')) {
                open();
            } else {
                close();
            }
        });

        menu.addEventListener('click', (event) => {
            event.stopPropagation();
        });

        menu.querySelectorAll('[data-admin-select-option]').forEach((option) => {
            option.addEventListener('click', () => {
                const value = option.getAttribute('data-value') ?? '';

                if (nativeSelect) {
                    nativeSelect.value = value;
                    nativeSelect.dispatchEvent(new Event('change', { bubbles: true }));
                }

                if (hiddenInput) {
                    hiddenInput.value = value;
                    hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
                }

                syncSelectedState(root);
                close();
            });
        });

        nativeSelect?.addEventListener('change', () => {
            syncSelectedState(root);
        });
    });

    document.addEventListener('click', () => {
        document.querySelectorAll('[data-admin-select]').forEach((root) => {
            root.querySelector('[data-admin-select-menu]')?.classList.add('hidden');
            root.querySelector('[data-admin-select-trigger]')?.setAttribute('aria-expanded', 'false');
            root.removeAttribute('data-admin-select-open');
        });
    });
}

document.addEventListener('DOMContentLoaded', initAdminSelects);
