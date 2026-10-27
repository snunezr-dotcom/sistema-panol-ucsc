@php
    $variant = $variant ?? 'footer';
@endphp

<button
    type="button"
    class="{{ $variant === 'menu' ? 'dropdown-item d-flex align-items-center fw-semibold theme-menu-toggle' : 'theme-toggle' }}"
    data-theme-toggle
    aria-label="{{ __('Cambiar a modo oscuro') }}"
    data-label-light="{{ __('Tema claro') }}"
    data-label-dark="{{ __('Tema oscuro') }}"
    data-action-light="{{ __('Cambiar a modo claro') }}"
    data-action-dark="{{ __('Cambiar a modo oscuro') }}"
    title="{{ __('Cambiar tema') }}"
>
    <svg data-theme-icon="light" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
        <path d="M8 4.5a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7ZM8 0a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-1 0v-1A.5.5 0 0 1 8 0Zm0 14a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-1 0v-1A.5.5 0 0 1 8 14Zm8-6a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1 0-1h1a.5.5 0 0 1 .5.5ZM2 8a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1 0-1h1A.5.5 0 0 1 2 8Zm11.657-5.657a.5.5 0 0 1 0 .707l-.707.707a.5.5 0 1 1-.707-.707l.707-.707a.5.5 0 0 1 .707 0Zm-9.9 9.9a.5.5 0 0 1 0 .707l-.707.707a.5.5 0 1 1-.707-.707l.707-.707a.5.5 0 0 1 .707 0Zm9.9 1.414a.5.5 0 0 1-.707 0l-.707-.707a.5.5 0 1 1 .707-.707l.707.707a.5.5 0 0 1 0 .707Zm-9.9-9.9a.5.5 0 0 1-.707 0l-.707-.707a.5.5 0 0 1 .707-.707l.707.707a.5.5 0 0 1 0 .707Z"/>
    </svg>
    <svg class="theme-icon-hidden" data-theme-icon="dark" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
        <path d="M6 .278a.77.77 0 0 1 .08.858 7.2 7.2 0 0 0-.878 3.46c0 4.021 3.278 7.28 7.318 7.28.597 0 1.177-.071 1.73-.204a.77.77 0 0 1 .775.327.73.73 0 0 1-.074.868A8.2 8.2 0 0 1 8.344 16C3.732 16 0 12.29 0 7.713 0 4.21 2.198 1.218 5.29.074A.75.75 0 0 1 6 .278Z"/>
    </svg>
    <span @class(['ms-2' => $variant === 'menu']) data-theme-label>{{ __('Tema claro') }}</span>
</button>

<script>
    (() => {
        const storageKey = 'panol-theme';
        const toggle = document.querySelector('[data-theme-toggle]');

        const applyTheme = (theme) => {
            document.documentElement.dataset.theme = theme;
            document.body.dataset.theme = theme;
            toggle.querySelectorAll('[data-theme-icon]').forEach((icon) => {
                icon.classList.toggle('theme-icon-hidden', icon.dataset.themeIcon !== theme);
            });

            const label = toggle.querySelector('[data-theme-label]');
            const themeLabel = theme === 'dark' ? toggle.dataset.labelDark : toggle.dataset.labelLight;
            const actionLabel = theme === 'dark' ? toggle.dataset.actionLight : toggle.dataset.actionDark;
            toggle.setAttribute('aria-label', actionLabel);
            if (label) {
                label.textContent = themeLabel;
            }
        };

        applyTheme(localStorage.getItem(storageKey) === 'dark' ? 'dark' : 'light');

        toggle.addEventListener('click', () => {
            const nextTheme = document.body.dataset.theme === 'dark' ? 'light' : 'dark';
            localStorage.setItem(storageKey, nextTheme);
            applyTheme(nextTheme);
        });
    })();
</script>
