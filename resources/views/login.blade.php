<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Acceso Pañol - UCSC') }}</title>
    <style>
        body {
            --page-background: #fff;
            --surface: #fff;
            --text: #333;
            --border: #b0b0b0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background-color: var(--page-background);
            color: var(--text);
            display: flex; 
            justify-content: center; 
            align-items: center; 
            min-height: 100vh;
            margin: 0;
            padding: 24px;
            box-sizing: border-box;
            transition: background-color 0.2s, color 0.2s;
        }
        body[data-theme="dark"] {
            --page-background: #121212;
            --surface: #1e1e1e;
            --text: #f5f5f5;
            --border: #555;
            color-scheme: dark;
        }
        .login-container { 
            background: transparent;
            padding: 0;
            width: 100%;
            max-width: 632px;
            text-align: center;
        }
        .logo { 
            max-width: 260px;
            margin-bottom: 14px;
        }
        .login-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-top: 26px;
            color: #e50027;
            font-size: 14px;
        }
        .language-select {
            border: 0;
            background: transparent;
            color: inherit;
            font: inherit;
            cursor: pointer;
            max-width: 230px;
            padding: 4px 0;
        }
        .theme-toggle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            width: auto;
            height: 32px;
            padding: 0 8px;
            border: 0;
            border-radius: 6px;
            background: transparent;
            color: inherit;
            font: inherit;
            cursor: pointer;
        }
        .theme-toggle:hover {
            background: rgba(229, 0, 39, 0.08);
        }
        .theme-toggle:focus-visible,
        .language-select:focus {
            outline: 2px solid currentColor;
            outline-offset: 2px;
        }
        body[data-theme="dark"] .language-select {
            color: #ff4965;
        }
        .theme-icon-hidden {
            display: none;
        }
        .form-group { 
            margin-bottom: 20px; 
        }
        .form-control { 
            width: 100%; 
            padding: 12px 15px; 
            border: 1px solid var(--border);
            border-radius: 6px; 
            box-sizing: border-box; 
            font-size: 16px; 
            color: var(--text);
            background-color: var(--surface);
        }
        
        .form-control::placeholder { 
            color: #7a7a7a; 
        }
        
        .form-control:focus {
            outline: none;
            border-color: #4a90e2;
        }
        .btn-login { 
            width: 100%; 
            background: #e50027; 
            color: white; 
            border: none; 
            padding: 14px; 
            border-radius: 6px; 
            font-weight: bold; 
            font-size: 18px; 
            cursor: pointer; 
            transition: 0.2s; 
            margin-top: 10px;
        }
        .btn-login:hover { 
            background: #cc0022; 
        }
        .error-msg { 
            background: #f8d7da; 
            color: #721c24; 
            padding: 10px; 
            border-radius: 6px; 
            margin-bottom: 20px; 
            font-size: 14px; 
        }
        @media (max-width: 520px) {
            .login-footer {
                align-items: flex-start;
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Logo oficial de la UCSC -->
        <img src="https://uvirtual2.ucsc.cl/pluginfile.php/1/core_admin/logo/0x200/1791279004/logoucsc.png" alt="Logo UCSC" class="logo">

        <!-- Mensaje de error por si la contraseña es incorrecta -->
        @if(session('error'))
            <div class="error-msg">{{ session('error') }}</div>
        @endif

        <form action="/login" method="POST">
            @csrf 
            
            <div class="form-group">
                <input type="text" name="rut" class="form-control" placeholder="{{ __('Ingrese su rut de profesor') }}" value="{{ old('rut') }}" required autocomplete="username">
            </div>
            
            <div class="form-group">
                <input type="password" name="password" class="form-control" placeholder="{{ __('Ingrese su contraseña') }}" required>
            </div>
            
            <button type="submit" class="btn-login">{{ __('Acceder') }}</button>
        </form>
        <div class="login-footer">
            @include('components.language-selector')
            @include('components.theme-toggle')
        </div>
    </div>
</body>
</html>
