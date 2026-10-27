@php
    $variant = $variant ?? 'footer';
@endphp

<form
    action="{{ route('language.update') }}"
    method="POST"
    class="{{ $variant === 'menu' ? 'dropdown-item-text language-menu-form' : 'language-form' }}"
>
    @csrf
    <label class="visually-hidden" for="language-select-{{ $variant }}">{{ __('Idioma') }}</label>
    <select
        id="language-select-{{ $variant }}"
        class="language-select"
        name="locale"
        aria-label="{{ __('Idioma') }}"
        onchange="this.form.submit()"
    >
        <option value="es" @selected(app()->getLocale() === 'es')>{{ __('Español - Internacional (es)') }}</option>
        <option value="en" @selected(app()->getLocale() === 'en')>{{ __('English (en)') }}</option>
    </select>
</form>
