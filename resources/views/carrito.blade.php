<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Mi Cesta - Pañol') }}</title>
    
    <!-- Bootstrap para la barra superior -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
    body {
        --page-background: #f5f5f5;
        --surface: #fff;
        --text: #222;
        --muted: #777;
        --border: #eee;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: var(--page-background);
        color: var(--text);
        margin: 0;
        padding: 30px;
        transition: background-color 0.2s, color 0.2s;
    }
    body[data-theme="dark"] {
        --page-background: #121212;
        --surface: #1e1e1e;
        --text: #f5f5f5;
        --muted: #bdbdbd;
        --border: #3b3b3b;
        color-scheme: dark;
    }
    
    
    .container-cesta { display: flex; gap: 15px; max-width: 1050px; margin: 0 auto; }
    .cesta { flex: 2; background: var(--surface); padding: 18px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); font-size: 0.95em; }
    .item-card { display: flex; gap: 12px; border-bottom: 1px solid var(--border); padding: 15px 0; align-items: center; }
    .item-card:last-child { border-bottom: none; }
    .item-info { flex: 1; }
    .item-title { font-weight: bold; font-size: 1.05em; color: var(--text); margin-bottom: 4px; }
    .stock-badge-green { background: #e8f5e9; color: #2e7d32; padding: 3px 8px; border-radius: 10px; font-size: 0.8em; font-weight: bold; margin-right: 5px; }
    .stock-badge-red { background: #ffebee; color: #c62828; padding: 3px 8px; border-radius: 10px; font-size: 0.8em; font-weight: bold; }
    
    /* Columna Derecha resumen y formulario */
    .resumen { flex: 1; background: var(--surface); padding: 18px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); height: fit-content; font-size: 0.95em; }
    
        /* Cuadros de formulario */
        .form-group { margin-bottom: 5px; }
        .form-group label { display: block; margin-bottom: 4px; font-weight: bold; color: var(--text); font-size: 0.85em; }
        .form-control { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box; font-family: inherit; font-size: 0.9em; background-color: var(--surface); color: var(--text); }
        .datetime-picker { position: relative; }
        .datetime-picker-control { padding-right: 42px; cursor: pointer; }
        .datetime-picker-icon {
            position: absolute;
            top: 50%;
            right: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            padding: 0;
            border: 0;
            background: transparent;
            color: var(--text);
            transform: translateY(-50%);
            cursor: pointer;
        }
        .datetime-picker-panel {
            position: absolute;
            z-index: 20;
            top: calc(100% + 6px);
            right: 0;
            left: 0;
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
            gap: 10px;
            padding: 12px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: var(--surface);
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.18);
        }
        .datetime-picker-panel[hidden] { display: none; }
        .datetime-picker-panel label { font-size: 0.8em; }
        .datetime-picker-panel .form-control { min-width: 0; }
        .datetime-picker-date { grid-column: 1 / -1; }
        .calendar-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 8px;
        }
        .calendar-month { font-weight: 600; text-transform: capitalize; }
        .calendar-navigation {
            display: flex;
            gap: 4px;
        }
        .calendar-navigation button {
            width: 30px;
            height: 30px;
            border: 1px solid var(--border);
            border-radius: 5px;
            background: var(--surface);
            color: var(--text);
            cursor: pointer;
        }
        .calendar-navigation button:disabled { opacity: 0.45; cursor: default; }
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, minmax(0, 1fr));
            gap: 3px;
        }
        .calendar-weekday,
        .calendar-day {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 0;
            min-height: 30px;
            font-size: 0.8em;
        }
        .calendar-weekday { color: var(--muted); font-weight: 600; }
        .calendar-day {
            border: 0;
            border-radius: 5px;
            background: transparent;
            color: var(--text);
            cursor: pointer;
        }
        .calendar-day:hover:not(:disabled),
        .calendar-day[aria-pressed="true"] { background: #e50027; color: #fff; }
        .calendar-day:disabled { color: var(--muted); opacity: 0.4; cursor: not-allowed; }
        .form-control::placeholder { color: var(--muted); }
        body[data-theme="dark"] .form-control { border-color: #555; }
        select[name="carrera_id"] { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' fill='%23212529' viewBox='0 0 16 16'%3E%3Cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 12px center; background-size: 14px; padding-right: 32px; }
        body[data-theme="dark"] select[name="carrera_id"] { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' fill='white' viewBox='0 0 16 16'%3E%3Cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E"); }
        
            /* Botón de confirmar reversa */
            .btn-continuar { width: 101%; background: #e50027; color: white; border: none; padding: 10px; border-radius: 20px; font-weight: bold; font-size: 0.95em; cursor: pointer; transition: 0.2s; }
            .btn-continuar:hover { background: #cc0022; }

    /* Cesta de Materiales y Detalles del Ramo */
    .cesta h2, .resumen h2 { font-size: 1.35em !important; margin-top: 0; border-bottom: 2px solid #f5f5f5; padding-bottom: 10px !important; margin-bottom: 15px; }
    body[data-theme="dark"] .cesta h2,
    body[data-theme="dark"] .resumen h2 { border-bottom-color: var(--border) !important; }
    body[data-theme="dark"] .site-navbar { background-color: #1e1e1e !important; }
    body[data-theme="dark"] .dropdown-menu { background-color: #2a2a2a; }
    body[data-theme="dark"] .dropdown-item { color: #f5f5f5 !important; }
    body[data-theme="dark"] .dropdown-item:hover { background-color: #3b3b3b; }
    body[data-theme="dark"] .photo-placeholder { background: #333 !important; color: #ccc !important; }
    body[data-theme="dark"] .item-meta,
    body[data-theme="dark"] .quantity-label { color: var(--muted) !important; }
    .theme-menu-toggle { cursor: pointer; }
    .theme-icon-hidden { display: none; }
    .language-menu-form { padding: 4px 16px; }
    .language-menu-form .language-select {
        width: 100%;
        border: 0;
        background: transparent;
        color: inherit;
        font: inherit;
    }
    body[data-theme="dark"] .language-menu-form .language-select { color: #f5f5f5; }
    .language-menu-form option { color: #222; }
    @media (max-width: 767px) {
        body { padding: 16px; }
        .container-cesta { flex-direction: column; }
    }
</style>
</head>
<body style="padding-top: 0;">

    <!-- Barra de Navegación Superior -->
    <nav class="navbar fixed-top shadow-sm site-navbar" style="background-color: #e60028; padding: 15px 30px;">
        <div class="container-fluid p-0 d-flex justify-content-between align-items-center">
            
            <!-- Lado Izquierdo de la pagina Logo UCSC en negro -->
            <a href="{{ url('/') }}" class="text-decoration-none">
                <img src="{{ asset('img/logo-ucsc.png') }}" alt="Logo UCSC" height="35">
            </a>

            <!-- Lado Derecho de la pagina Icono Gris -> Nombre -> Flecha -->
            <div class="dropdown">
                <a class="d-flex align-items-center text-white text-decoration-none" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="outline: none;">
                    
                    <div class="rounded-circle d-flex justify-content-center align-items-center me-2" style="background-color: #e9ecef; width: 32px; height: 32px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#adb5bd" class="bi bi-person-fill" viewBox="0 0 16 16">
                            <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3Zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                        </svg>
                    </div>

                    <span class="fw-semibold me-2">{{ Auth::user()->name }}</span>
                    
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-chevron-down" viewBox="0 0 16 16">
                      <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/>
                    </svg>
                </a>
                
                <!-- Menú Desplegable con cerrar sesion -->
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                    <li>
                        @include('components.language-selector', ['variant' => 'menu'])
                    </li>
                    <li>
                        @include('components.theme-toggle', ['variant' => 'menu'])
                    </li>
                    <li>
                        <form action="{{ url('/logout') }}" method="POST" class="m-0 p-0">
                            @csrf
                            <button type="submit" class="dropdown-item text-secondary d-flex align-items-center fw-semibold">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-right me-2" viewBox="0 0 16 16">
                                  <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/>
                                  <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
                                </svg>
                                {{ __('Cerrar sesión') }}
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Espacio para empujar el contenido abajo de la barra fija -->
    <div style="margin-top: 100px;"></div>

    <div class="container-cesta">
        
        <!-- Items -->
        <div class="cesta">
            <h2 style="margin-top: 0; border-bottom: 2px solid #f5f5f5; padding-bottom: 15px;">{{ __('Cesta de Materiales') }}</h2>
            
            @if(isset($items) && count($items) > 0)
                <!-- Tomar 3 ítems de prueba de tu base de datos para simular -->
                @foreach($items->take(3) as $item)
                <div class="item-card">
                    <div class="photo-placeholder" style="width: 90px; height: 90px; background: #f0f0f0; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #aaa; font-size: 0.8em;">{{ __('Sin Foto') }}</div>
                    <div class="item-info">
                        <div class="item-title">{{ $item->nombre }}</div>
                        <div class="item-meta" style="color: #888; font-size: 0.85em; margin-bottom: 10px;">{{ __('SKU: :sku | Ubicación: :location', ['sku' => $item->sku, 'location' => $item->ubicacion ?? 'N/A']) }}</div>
                        
                        <!-- Indicadores de Stock -->
                        <div>
                            <span class="stock-badge-green">{{ __('Disponible: :count', ['count' => $item->cantidad_disponible]) }}</span>
                            <span class="stock-badge-red">{{ __('Ocupado: :count', ['count' => 2]) }}</span>
                        </div>
                    </div>
                    <div>
                        <label class="quantity-label" style="display: block; font-size: 0.8em; color: #666; text-align: center; margin-bottom: 5px;">{{ __('Cantidad') }}</label>
                        <input type="number" name="items[{{ $item->id }}]" min="1" max="10" value="1" style="width: 50px; padding: 8px; text-align: center; border: 1px solid #ccc; border-radius: 6px;">
                    </div>
                </div>
                @endforeach
            @else
                <p>{{ __('No hay items en la cesta.') }}</p>
            @endif
        </div>

        <!-- Formulario de reserva -->
        <div class="resumen">
            <h2 style="margin-top: 0; border-bottom: 2px solid #f5f5f5; padding-bottom: 15px;">{{ __('Detalles del Ramo') }}</h2>
            
            <form action="#" method="POST">
                
                <div class="form-group">
                    <label>{{ __('Carrera de destino:') }}</label>
                    <select name="carrera_id" class="form-control" required>
                        <option value="">{{ __('-- Selecciona la carrera --') }}</option>
                        @if(isset($carreras))
                            @foreach($carreras as $carrera)
                                <option value="{{ $carrera->id }}">{{ $carrera->nombre }}</option>
                            @endforeach
                        @endif
                        
                        <option value="998">Ingeniería de Ejecución en Informática</option>
                        <option value="999">Técnico Universitario en Construcción</option>
                        <option value="999">Técnico Universitario en Electricidad Industrial</option>
                        <option value="999">Técnico Universitario en Electromecánica</option>
                        <option value="999">Técnico Universitario en Refrigeración y Climatización Industrial</option>
                    </select>
                    <div style="font-size: 0.75em; color: #e50027; margin-top: 5px;">{{ __('* Límite de 10 objetos totales por carrera.') }}</div>
                </div>

                <div class="form-group">
                    <label>{{ __('Nombre del Ramo:') }}</label>
                    <input type="text" name="ramo" class="form-control" placeholder="{{ __('Ej: Taller de Hardware') }}" required>
                </div>

                <div class="form-group">
                    <label>{{ __('¿Para cuándo lo necesitas?') }}</label>
                    <div class="datetime-picker" data-datetime-picker>
                        <input
                            type="datetime-local"
                            id="fecha-necesitada"
                            name="fecha_necesitada"
                            class="form-control datetime-picker-control"
                            step="60"
                            placeholder="{{ __('Selecciona fecha y hora') }}"
                            aria-label="{{ __('¿Para cuándo lo necesitas?') }}"
                                data-message-past="{{ __('Selecciona una fecha y hora desde este momento en adelante.') }}"
                                data-message-sunday="{{ __('El domingo el instituto está cerrado. Selecciona de lunes a sábado.') }}"
                                data-message-hours="{{ __('Horario disponible para este día: :opening a :closing.') }}"
                                data-message-closed="{{ __('Ya no quedan horarios disponibles para hoy. Selecciona otra fecha.') }}"
                                readonly
                        >
                        <button
                            type="button"
                            class="datetime-picker-icon"
                            data-picker-toggle
                            aria-label="{{ __('Abrir calendario y horarios disponibles') }}"
                            aria-expanded="false"
                            aria-controls="selector-fecha-hora"
                            title="{{ __('Horario disponible: lunes a jueves de 10:00 a 19:00, viernes de 10:00 a 18:00 y sábado de 10:00 a 14:00. Domingo cerrado.') }}"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h.5A1.5 1.5 0 0 1 15 2.5v12a1.5 1.5 0 0 1-1.5 1.5h-11A1.5 1.5 0 0 1 1 14.5v-12A1.5 1.5 0 0 1 2.5 1H3V.5a.5.5 0 0 1 .5-.5ZM2.5 2a.5.5 0 0 0-.5.5V4h12V2.5a.5.5 0 0 0-.5-.5h-11ZM14 5H2v9.5a.5.5 0 0 0 .5.5h11a.5.5 0 0 0 .5-.5V5Z"/>
                            </svg>
                        </button>
                        <div class="datetime-picker-panel" id="selector-fecha-hora" data-picker-panel hidden>
                            <div class="datetime-picker-date">
                                <span class="form-label">{{ __('Fecha') }}</span>
                                <div class="calendar-heading">
                                    <span class="calendar-month" data-calendar-month></span>
                                    <div class="calendar-navigation">
                                        <button type="button" data-calendar-previous aria-label="{{ __('Mes anterior') }}" disabled>‹</button>
                                        <button type="button" data-calendar-next aria-label="{{ __('Mes siguiente') }}">›</button>
                                    </div>
                                </div>
                                <div class="calendar-grid" data-calendar-weekdays></div>
                                <div class="calendar-grid" data-calendar-days role="grid"></div>
                                <input type="hidden" data-picker-date>
                            </div>
                            <div class="datetime-picker-hour">
                                <label for="hora-seleccionada">{{ __('Hora') }}</label>
                                <select id="hora-seleccionada" class="form-control" data-picker-hour disabled>
                                    <option value="">{{ __('Selecciona una hora') }}</option>
                                </select>
                            </div>
                            <div class="datetime-picker-minute">
                                <label for="minuto-seleccionado">{{ __('Minutos') }}</label>
                                <select id="minuto-seleccionado" class="form-control" data-picker-minute disabled>
                                    <option value="">{{ __('Minutos') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="margin-top: 30px;">
                    <button type="submit" class="btn-continuar">{{ __('Confirmar Reserva') }}</button>
                </div>
            </form>
        </div>

    </div>

    <!-- Menú Desplegable -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (() => {
            const dateInput = document.querySelector('#fecha-necesitada');
            const picker = document.querySelector('[data-datetime-picker]');
            const toggle = picker.querySelector('[data-picker-toggle]');
            const panel = picker.querySelector('[data-picker-panel]');
            const selectedDateInput = picker.querySelector('[data-picker-date]');
            const calendarMonth = picker.querySelector('[data-calendar-month]');
            const calendarWeekdays = picker.querySelector('[data-calendar-weekdays]');
            const calendarDays = picker.querySelector('[data-calendar-days]');
            const previousMonthButton = picker.querySelector('[data-calendar-previous]');
            const nextMonthButton = picker.querySelector('[data-calendar-next]');
            const hourInput = picker.querySelector('[data-picker-hour]');
            const minuteInput = picker.querySelector('[data-picker-minute]');
            const reservationForm = dateInput.form;
            const pad = (value) => String(value).padStart(2, '0');
            const formatDate = (date) => `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}`;
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            let minimumDate = formatDate(today);
            let visibleMonth = new Date(today.getFullYear(), today.getMonth(), 1, 12);
            const createOption = (value, label) => {
                const option = document.createElement('option');
                option.value = value;
                option.textContent = label;
                return option;
            };

            const formatBusinessTime = (hour, minute = 0) => {
                if (dateInput.dataset.locale !== 'en') {
                    return `${pad(hour)}:${pad(minute)}`;
                }

                return `${hour % 12 || 12}:${pad(minute)} ${hour >= 12 ? 'PM' : 'AM'}`;
            };

            const updateMinimumDate = () => {
                const currentDate = new Date();
                currentDate.setHours(0, 0, 0, 0);
                minimumDate = formatDate(currentDate);
                today.setTime(currentDate.getTime());
            };

            const renderCalendar = () => {
                const locale = dateInput.dataset.locale === 'en' ? 'en-US' : 'es-CL';
                const firstOfMonth = new Date(visibleMonth.getFullYear(), visibleMonth.getMonth(), 1, 12);
                const daysInMonth = new Date(visibleMonth.getFullYear(), visibleMonth.getMonth() + 1, 0).getDate();
                const offset = (firstOfMonth.getDay() + 6) % 7;

                calendarMonth.textContent = new Intl.DateTimeFormat(locale, {
                    month: 'long',
                    year: 'numeric',
                }).format(visibleMonth);
                calendarWeekdays.replaceChildren();
                for (let weekday = 0; weekday < 7; weekday++) {
                    const date = new Date(2024, 0, 1 + weekday, 12);
                    const header = document.createElement('span');
                    header.className = 'calendar-weekday';
                    header.textContent = new Intl.DateTimeFormat(locale, { weekday: 'short' }).format(date);
                    calendarWeekdays.append(header);
                }

                calendarDays.replaceChildren();
                for (let emptyDay = 0; emptyDay < offset; emptyDay++) {
                    const spacer = document.createElement('span');
                    spacer.setAttribute('aria-hidden', 'true');
                    calendarDays.append(spacer);
                }

                for (let dayNumber = 1; dayNumber <= daysInMonth; dayNumber++) {
                    const date = new Date(visibleMonth.getFullYear(), visibleMonth.getMonth(), dayNumber, 12);
                    const value = formatDate(date);
                    const isSunday = date.getDay() === 0;
                    const isPast = value < minimumDate;
                    const dayButton = document.createElement('button');
                    dayButton.type = 'button';
                    dayButton.className = 'calendar-day';
                    dayButton.textContent = String(dayNumber);
                    dayButton.disabled = isSunday || isPast;
                    dayButton.setAttribute('role', 'gridcell');
                    dayButton.setAttribute('aria-label', new Intl.DateTimeFormat(locale, {
                        dateStyle: 'full',
                    }).format(date));
                    dayButton.setAttribute('aria-pressed', String(value === selectedDateInput.value));
                    dayButton.dataset.date = value;
                    if (isSunday) {
                        dayButton.title = '{{ __('Cerrado los domingos') }}';
                    }
                    calendarDays.append(dayButton);
                }

                const currentMonth = new Date(today.getFullYear(), today.getMonth(), 1, 12);
                previousMonthButton.disabled = visibleMonth <= currentMonth;
            };

            const updateMinutes = () => {
                const previousMinute = minuteInput.value;
                const selectedDate = new Date(`${selectedDateInput.value}T12:00:00`);
                const day = selectedDate.getDay();
                const closingHour = day === 6 ? 14 : day === 5 ? 18 : 19;
                const selectedHour = Number(hourInput.value);
                const now = new Date();
                const isToday = selectedDateInput.value === formatDate(now);
                let firstMinute = 0;
                let lastMinute = selectedHour === closingHour ? 0 : 59;

                if (isToday && selectedHour === now.getHours()) {
                    firstMinute = now.getMinutes() + (now.getSeconds() > 0 || now.getMilliseconds() > 0 ? 1 : 0);
                }

                minuteInput.replaceChildren(createOption('', '{{ __('Minutos') }}'));
                for (let minute = firstMinute; minute <= lastMinute; minute++) {
                    const value = pad(minute);
                    minuteInput.append(createOption(value, value));
                }

                minuteInput.disabled = minuteInput.options.length <= 1;
                minuteInput.value = [...minuteInput.options].some((option) => option.value === previousMinute)
                    ? previousMinute
                    : '';
            };

            const updateHours = () => {
                const previousHour = hourInput.value;
                dateInput.setCustomValidity('');
                minuteInput.replaceChildren(createOption('', '{{ __('Minutos') }}'));
                minuteInput.disabled = true;
                hourInput.replaceChildren(createOption('', '{{ __('Selecciona una hora') }}'));
                hourInput.disabled = true;

                if (!selectedDateInput.value || selectedDateInput.value < minimumDate) {
                    return;
                }

                const selectedDate = new Date(`${selectedDateInput.value}T12:00:00`);
                const day = selectedDate.getDay();
                if (day === 0) {
                    dateInput.setCustomValidity(dateInput.dataset.messageSunday);
                    hourInput.replaceChildren(createOption('', '{{ __('Cerrado') }}'));
                    return;
                }

                const closingHour = day === 6 ? 14 : day === 5 ? 18 : 19;
                let openingHour = 10;
                const now = new Date();
                const isToday = selectedDateInput.value === formatDate(now);
                if (isToday && (
                    now.getHours() > closingHour ||
                    (now.getHours() === closingHour && (now.getMinutes() > 0 || now.getSeconds() > 0 || now.getMilliseconds() > 0))
                )) {
                    dateInput.setCustomValidity(dateInput.dataset.messageClosed);
                    hourInput.replaceChildren(createOption('', '{{ __('Sin horarios disponibles') }}'));
                    return;
                }

                if (isToday && now.getHours() >= openingHour) {
                    openingHour = now.getHours();
                    if (now.getMinutes() === 59 && (now.getSeconds() > 0 || now.getMilliseconds() > 0)) {
                        openingHour += 1;
                    }
                }

                if (openingHour > closingHour) {
                    dateInput.setCustomValidity(dateInput.dataset.messageClosed);
                    hourInput.replaceChildren(createOption('', '{{ __('Sin horarios disponibles') }}'));
                    return;
                }

                for (let hour = openingHour; hour <= closingHour; hour++) {
                    const value = pad(hour);
                    const label = dateInput.dataset.locale === 'en'
                        ? `${hour % 12 || 12}:00 ${hour >= 12 ? 'PM' : 'AM'}`
                        : `${value}:00`;
                    hourInput.append(createOption(value, label));
                }

                hourInput.disabled = false;
                hourInput.value = [...hourInput.options].some((option) => option.value === previousHour)
                    ? previousHour
                    : '';
                if (hourInput.value) {
                    updateMinutes();
                }
            };

            const updateDateTimeValue = () => {
                dateInput.setCustomValidity('');
                if (!selectedDateInput.value || !hourInput.value || !minuteInput.value) {
                    dateInput.value = '';
                    return false;
                }

                const selectedValue = `${selectedDateInput.value}T${hourInput.value}:${minuteInput.value}`;
                const selectedDateTime = new Date(selectedValue);
                const now = new Date();
                if (selectedDateTime < now) {
                    dateInput.value = '';
                    dateInput.setCustomValidity(dateInput.dataset.messagePast);
                    return false;
                }

                const day = selectedDateTime.getDay();
                if (day === 0) {
                    dateInput.value = '';
                    dateInput.setCustomValidity(dateInput.dataset.messageSunday);
                    return false;
                }

                const closingHour = day === 6 ? 14 : day === 5 ? 18 : 19;
                const hour = selectedDateTime.getHours();
                const minute = selectedDateTime.getMinutes();
                if (hour < 10 || hour > closingHour || (hour === closingHour && minute > 0)) {
                    dateInput.value = '';
                    dateInput.setCustomValidity(dateInput.dataset.messageHours
                        .replace(':opening', formatBusinessTime(10))
                        .replace(':closing', formatBusinessTime(closingHour)));
                    return false;
                }

                dateInput.value = selectedValue;
                return true;
            };

            const closePicker = () => {
                panel.hidden = true;
                toggle.setAttribute('aria-expanded', 'false');
            };

            const openPicker = () => {
                updateMinimumDate();
                const currentValue = dateInput.value;
                selectedDateInput.value = currentValue ? currentValue.slice(0, 10) : minimumDate;
                const selectedDate = new Date(`${selectedDateInput.value}T12:00:00`);
                visibleMonth = new Date(selectedDate.getFullYear(), selectedDate.getMonth(), 1, 12);
                renderCalendar();
                updateHours();
                if (currentValue) {
                    hourInput.value = currentValue.slice(11, 13);
                    updateMinutes();
                    minuteInput.value = currentValue.slice(14, 16);
                }
                panel.hidden = false;
                toggle.setAttribute('aria-expanded', 'true');
                calendarDays.querySelector(`[data-date="${selectedDateInput.value}"]:not(:disabled)`)?.focus();
            };

            toggle.addEventListener('click', () => {
                if (panel.hidden) {
                    openPicker();
                } else {
                    closePicker();
                }
            });
            dateInput.addEventListener('click', openPicker);
            dateInput.addEventListener('keydown', (event) => {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    openPicker();
                }
            });
            dateInput.addEventListener('invalid', (event) => {
                event.preventDefault();
                openPicker();
            });
            previousMonthButton.addEventListener('click', () => {
                visibleMonth = new Date(visibleMonth.getFullYear(), visibleMonth.getMonth() - 1, 1, 12);
                renderCalendar();
            });
            nextMonthButton.addEventListener('click', () => {
                visibleMonth = new Date(visibleMonth.getFullYear(), visibleMonth.getMonth() + 1, 1, 12);
                renderCalendar();
            });
            calendarDays.addEventListener('click', (event) => {
                const dayButton = event.target.closest('[data-date]');
                if (!dayButton || dayButton.disabled) {
                    return;
                }

                selectedDateInput.value = dayButton.dataset.date;
                dateInput.value = '';
                hourInput.value = '';
                minuteInput.value = '';
                renderCalendar();
                updateHours();
                hourInput.focus();
            });
            hourInput.addEventListener('change', updateMinutes);
            minuteInput.addEventListener('change', () => {
                if (updateDateTimeValue()) {
                    closePicker();
                }
            });
            reservationForm.addEventListener('submit', (event) => {
                if (!dateInput.value || !updateDateTimeValue()) {
                    event.preventDefault();
                    if (dateInput.validationMessage) {
                        dateInput.reportValidity();
                    } else {
                        openPicker();
                    }
                }
            });
            document.addEventListener('pointerdown', (event) => {
                if (!picker.contains(event.target)) {
                    closePicker();
                }
            });

            updateMinimumDate();
            renderCalendar();
        })();
    </script>
</body>
</html>