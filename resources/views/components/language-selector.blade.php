@php
    $variant = $variant ?? 'footer';
@endphp

<form
    action="{{ route('language.update') }}"
    method="POST"
    class="{{ $variant === 'menu' ? 'dropdown-item-text language-menu-form' : 'language-form' }}"
    data-language-form
>
    @csrf
    <label class="visually-hidden" for="language-select-{{ $variant }}">{{ __('Idioma') }}</label>
    <select
        id="language-select-{{ $variant }}"
        class="language-select"
        name="locale"
        aria-label="{{ __('Idioma') }}"
        onchange="this.form.requestSubmit()"
    >
        <option value="es" @selected(app()->getLocale() === 'es')>{{ __('Español - Internacional (es)') }}</option>
        <option value="en" @selected(app()->getLocale() === 'en')>{{ __('English (en)') }}</option>
    </select>
    <span class="visually-hidden" data-language-status role="status" aria-live="polite"></span>
</form>

<script>
    (() => {
        if (window.panolLanguageSwitchInitialized) {
            return;
        }

        window.panolLanguageSwitchInitialized = true;

        const captureState = () => {
            const controls = [...document.querySelectorAll('input[name], select[name], textarea[name]')]
                .filter((control) => control.name !== '_token' && control.name !== 'locale')
                .map((control) => {
                    let selectionStart = null;
                    let selectionEnd = null;
                    try {
                        selectionStart = control.selectionStart;
                        selectionEnd = control.selectionEnd;
                    } catch {
                        // Some input types, such as number, do not expose text selection.
                    }

                    return {
                        name: control.name,
                        type: control.type,
                        value: control.value,
                        checked: control.checked,
                        selectedValues: control.multiple
                            ? [...control.selectedOptions].map((option) => option.value)
                            : null,
                        selectionStart,
                        selectionEnd,
                    };
                });
            const activeElement = document.activeElement;
            const errorMessage = document.querySelector('.error-msg')?.textContent;

            return {
                controls,
                focusId: activeElement?.id,
                focusName: activeElement?.name,
                dropdownOpen: Boolean(document.querySelector('.dropdown-menu.show')),
                datePicker: window.panolDatePicker?.snapshot() ?? null,
                errorMessage,
                scrollX: window.scrollX,
                scrollY: window.scrollY,
            };
        };

        const restoreControls = (controls) => {
            const used = new Map();
            controls.forEach((state) => {
                const matches = [...document.querySelectorAll('[name]')]
                    .filter((control) => control.name === state.name);
                const index = used.get(state.name) ?? 0;
                const control = matches[index];
                used.set(state.name, index + 1);
                if (!control) {
                    return;
                }

                if (state.type === 'checkbox' || state.type === 'radio') {
                    control.checked = state.checked;
                } else if (control.multiple && state.selectedValues) {
                    [...control.options].forEach((option) => {
                        option.selected = state.selectedValues.includes(option.value);
                    });
                } else {
                    control.value = state.value;
                }

                if (state.selectionStart !== null && state.selectionEnd !== null) {
                    control.setSelectionRange?.(state.selectionStart, state.selectionEnd);
                }
            });
        };

        const runScripts = async (container) => {
            for (const original of [...container.querySelectorAll('script')]) {
                if (original.src && original.src.includes('bootstrap.bundle') && window.bootstrap) {
                    original.remove();
                    continue;
                }

                const script = document.createElement('script');
                [...original.attributes].forEach((attribute) => {
                    script.setAttribute(attribute.name, attribute.value);
                });

                if (original.src) {
                    await new Promise((resolve, reject) => {
                        script.addEventListener('load', resolve, { once: true });
                        script.addEventListener('error', () => reject(new Error(`No se pudo cargar ${script.src}`)), { once: true });
                        original.replaceWith(script);
                    });
                } else {
                    script.textContent = original.textContent;
                    original.replaceWith(script);
                }
            }
        };

        const renderLocalizedPage = async (state) => {
            const pageResponse = await fetch(window.location.href, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                cache: 'no-store',
                credentials: 'same-origin',
            });
            if (!pageResponse.ok) {
                throw new Error(`No se pudo cargar la página traducida (${pageResponse.status}).`);
            }

            const responseDocument = new DOMParser().parseFromString(await pageResponse.text(), 'text/html');
            document.documentElement.lang = responseDocument.documentElement.lang;
            document.title = responseDocument.title;

            const nextBody = document.importNode(responseDocument.body, true);
            document.body.replaceWith(nextBody);
            await runScripts(nextBody);

            restoreControls(state.controls);
            window.panolDatePicker?.restore(state.datePicker);

            if (state.errorMessage) {
                const error = document.querySelector('.error-msg');
                if (error) {
                    error.textContent = state.errorMessage;
                }
            }

            if (state.dropdownOpen) {
                const trigger = document.querySelector('[data-bs-toggle="dropdown"]');
                if (trigger && window.bootstrap) {
                    window.bootstrap.Dropdown.getOrCreateInstance(trigger).show();
                }
            }

            const focusTarget = state.focusId
                ? document.getElementById(state.focusId)
                : [...document.querySelectorAll('[name]')].find((control) => control.name === state.focusName);
            focusTarget?.focus({ preventScroll: true });
            window.scrollTo(state.scrollX, state.scrollY);
        };

        document.addEventListener('submit', async (event) => {
            const form = event.target.closest('[data-language-form]');
            if (!form) {
                return;
            }

            event.preventDefault();
            const state = captureState();
            const select = form.querySelector('select[name="locale"]');
            const locale = select.value;
            const previousLocale = document.documentElement.lang;
            const formData = new FormData(form);
            select.disabled = true;

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        Accept: 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin',
                });
                if (!response.ok) {
                    throw new Error(`El servidor rechazó el cambio de idioma (${response.status}).`);
                }

                const result = await response.json();
                if (result.locale !== locale) {
                    throw new Error('El servidor no confirmó el idioma solicitado.');
                }
                await renderLocalizedPage(state);
            } catch (error) {
                console.error('Error al cambiar el idioma:', error);
                const currentSelect = document.querySelector('[data-language-form] select[name="locale"]');
                if (currentSelect) {
                    currentSelect.value = previousLocale;
                    currentSelect.disabled = false;
                }
                const currentStatus = document.querySelector('[data-language-form] [data-language-status]');
                if (currentStatus) {
                    currentStatus.textContent = '{{ __('No se pudo cambiar el idioma. Inténtalo nuevamente.') }}';
                }
            }
        });
    })();
</script>
