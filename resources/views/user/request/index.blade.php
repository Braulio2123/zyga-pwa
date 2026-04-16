@extends('user.layouts.app')

@push('page_styles')
<link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
/>

<style>
    .request-hero {
        display: grid;
        gap: 14px;
    }

    .request-hero__copy {
        display: grid;
        gap: 6px;
    }

    .request-hero__copy h2 {
        margin: 0;
        color: #0f172a;
        line-height: 1.08;
        font-size: clamp(1.35rem, 2vw, 1.9rem);
    }

    .request-hero__copy p {
        margin: 0;
        color: #475569;
        line-height: 1.55;
        font-size: 0.95rem;
    }

    .request-step-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
    }

    .request-step-card {
        padding: 14px;
        border-radius: 18px;
        border: 1px solid rgba(148, 163, 184, 0.16);
        background: #ffffff;
        display: grid;
        gap: 6px;
    }

    .request-step-card span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 999px;
        background: rgba(249, 115, 22, 0.12);
        color: #ea580c;
        font-size: 0.8rem;
        font-weight: 800;
    }

    .request-step-card strong {
        color: #0f172a;
        line-height: 1.35;
        font-size: 0.95rem;
    }

    .request-step-card p {
        margin: 0;
        color: #64748b;
        line-height: 1.5;
        font-size: 0.86rem;
    }

    .request-state-card {
        display: grid;
        gap: 14px;
        padding: 18px;
        border-radius: 22px;
        border: 1px solid rgba(15, 23, 42, 0.08);
        background:
            radial-gradient(circle at top right, rgba(249, 115, 22, 0.12), transparent 40%),
            radial-gradient(circle at bottom left, rgba(37, 99, 235, 0.10), transparent 45%),
            #ffffff;
    }

    .request-state-card__top {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 12px;
        align-items: center;
    }

    .request-state-card__title {
        display: grid;
        gap: 4px;
    }

    .request-state-card__title h3 {
        margin: 0;
        color: #0f172a;
        line-height: 1.2;
    }

    .request-state-card__title p {
        margin: 0;
        color: #64748b;
        font-size: 0.9rem;
        line-height: 1.45;
    }

    .request-state-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 12px;
        border-radius: 999px;
        background: rgba(245, 158, 11, 0.12);
        color: #b45309;
        font-size: 0.78rem;
        font-weight: 800;
        white-space: nowrap;
    }

    .request-state-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .request-state-item {
        padding: 13px 14px;
        border-radius: 16px;
        border: 1px solid rgba(148, 163, 184, 0.14);
        background: #f8fafc;
        display: grid;
        gap: 5px;
    }

    .request-state-item span {
        color: #64748b;
        font-size: 0.78rem;
    }

    .request-state-item strong {
        color: #0f172a;
        line-height: 1.45;
        word-break: break-word;
        font-size: 0.94rem;
    }

    .request-form-layout {
        display: grid;
        grid-template-columns: minmax(0, 1.1fr) minmax(320px, 0.9fr);
        gap: 20px;
        align-items: start;
    }

    .request-form-stack {
        display: grid;
        gap: 18px;
    }

    .request-quote-card {
        display: grid;
        gap: 14px;
        padding: 18px;
        border-radius: 22px;
        border: 1px solid rgba(15, 23, 42, 0.08);
        background:
            linear-gradient(135deg, rgba(249, 115, 22, 0.08), rgba(37, 99, 235, 0.06)),
            #ffffff;
    }

    .request-quote-card[hidden] {
        display: none;
    }

    .request-quote-state {
        padding: 12px 14px;
        border-radius: 16px;
        background: rgba(15, 23, 42, 0.05);
        color: #334155;
        line-height: 1.5;
        font-weight: 700;
    }

    .request-quote-state--loading {
        background: rgba(37, 99, 235, 0.10);
        color: #1d4ed8;
    }

    .request-quote-state--success {
        background: rgba(22, 163, 74, 0.10);
        color: #15803d;
    }

    .request-quote-state--warning {
        background: rgba(245, 158, 11, 0.12);
        color: #b45309;
    }

    .request-quote-state--danger {
        background: rgba(239, 68, 68, 0.10);
        color: #b91c1c;
    }

    .request-quote-total {
        padding: 16px;
        border-radius: 18px;
        background: rgba(255, 255, 255, 0.92);
        border: 1px solid rgba(148, 163, 184, 0.14);
        display: grid;
        gap: 6px;
    }

    .request-quote-total span {
        color: #64748b;
        font-size: 0.8rem;
    }

    .request-quote-total strong {
        color: #0f172a;
        line-height: 1.05;
        font-size: 2rem;
        letter-spacing: -0.04em;
    }

    .request-quote-total small {
        color: #475569;
        line-height: 1.45;
        font-size: 0.86rem;
    }

    .request-quote-list {
        display: grid;
        gap: 10px;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .request-quote-list li {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        padding: 12px 14px;
        border-radius: 16px;
        background: #ffffff;
        border: 1px solid rgba(148, 163, 184, 0.14);
        color: #0f172a;
        line-height: 1.45;
    }

    .request-quote-list li span:last-child {
        font-weight: 800;
        white-space: nowrap;
    }

    .request-quote-note {
        padding: 12px 14px;
        border-radius: 16px;
        background: rgba(15, 23, 42, 0.04);
        border: 1px solid rgba(148, 163, 184, 0.14);
        color: #475569;
        line-height: 1.5;
        font-size: 0.88rem;
    }

    .request-search-form {
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        gap: 10px;
    }

    .request-search-results {
        display: grid;
        gap: 8px;
        margin-top: 12px;
    }

    .request-search-result {
        width: 100%;
        border: 1px solid rgba(148, 163, 184, 0.22);
        background: #fff;
        color: #0f172a;
        border-radius: 16px;
        padding: 12px 14px;
        text-align: left;
        cursor: pointer;
        transition: border-color 0.2s ease, transform 0.2s ease;
    }

    .request-search-result:hover {
        border-color: rgba(37, 99, 235, 0.24);
        transform: translateY(-1px);
    }

    .request-search-result strong {
        display: block;
        font-size: 0.92rem;
        margin-bottom: 4px;
    }

    .request-search-result span {
        display: block;
        font-size: 0.84rem;
        color: #64748b;
        line-height: 1.45;
    }

    .request-map-shell {
        position: relative;
        border-radius: 24px;
        overflow: hidden;
        border: 1px solid rgba(15, 23, 42, 0.08);
        background: #e2e8f0;
    }

    .request-map {
        width: 100%;
        min-height: 420px;
    }

    .request-map-overlay {
        position: absolute;
        inset: auto 14px 14px 14px;
        z-index: 500;
        background: rgba(15, 23, 42, 0.78);
        color: #fff;
        padding: 12px 14px;
        border-radius: 16px;
        font-size: 0.9rem;
        line-height: 1.5;
        backdrop-filter: blur(8px);
    }

    .request-map-pin-wrapper {
        background: transparent;
        border: 0;
    }

    .request-map-pin {
        display: block;
        width: 22px;
        height: 22px;
        border-radius: 999px;
        background: #f97316;
        border: 3px solid #fff;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.24);
    }

    .request-location-box {
        display: grid;
        gap: 12px;
    }

    .request-location-status {
        padding: 14px 16px;
        border-radius: 18px;
        border: 1px solid rgba(15, 23, 42, 0.08);
        background: rgba(37, 99, 235, 0.08);
        color: #1d4ed8;
        font-weight: 700;
        line-height: 1.5;
    }

    .request-location-status--warning {
        background: rgba(245, 158, 11, 0.12);
        color: #b45309;
    }

    .request-location-status--danger {
        background: rgba(239, 68, 68, 0.10);
        color: #b91c1c;
    }

    .request-location-status--success {
        background: rgba(22, 163, 74, 0.08);
        color: #15803d;
    }

    .request-location-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .request-location-item {
        padding: 14px 16px;
        border-radius: 18px;
        border: 1px solid rgba(148, 163, 184, 0.16);
        background: #f8fafc;
        display: grid;
        gap: 6px;
    }

    .request-location-item span {
        color: #64748b;
        font-size: 0.8rem;
    }

    .request-location-item strong {
        color: #0f172a;
        line-height: 1.45;
        word-break: break-word;
        font-size: 0.94rem;
    }

    .request-hidden-coordinates {
        display: none;
    }

    .request-readonly-field {
        background: #f8fafc;
    }

    @media (max-width: 1024px) {
        .request-step-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .request-form-layout {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 720px) {
        .request-state-grid,
        .request-location-grid {
            grid-template-columns: 1fr;
        }

        .request-search-form {
            grid-template-columns: 1fr;
        }

        .request-map {
            min-height: 340px;
        }
    }

    @media (max-width: 560px) {
        .request-step-grid {
            grid-template-columns: 1fr;
        }

        .request-hero__copy h2 {
            font-size: 1.25rem;
        }

        .request-hero__copy p,
        .request-step-card p,
        .request-quote-note {
            font-size: 0.88rem;
        }

        .request-state-card,
        .request-quote-card {
            padding: 15px;
        }

        .request-quote-total strong {
            font-size: 1.7rem;
        }

        .request-quote-list li,
        .request-location-item,
        .request-state-item {
            padding: 12px;
        }
    }
</style>
@endpush

@section('content')
@php
    $initialServices = is_array($requestServices ?? null) ? $requestServices : [];
    $initialVehicles = is_array($requestVehicles ?? null) ? $requestVehicles : [];
    $activeRequest = is_array($requestActiveRequest ?? null) ? $requestActiveRequest : [];
    $apiErrors = is_array($requestApiErrors ?? null) ? $requestApiErrors : [];
    $requestCanCreate = (bool) ($requestCanCreate ?? false);

    $statusLabel = function (?string $status): string {
        $map = [
            'created' => 'Solicitud enviada',
            'accepted' => 'Solicitud aceptada',
            'assigned' => 'Proveedor asignado',
            'in_progress' => 'En camino',
            'arrived' => 'Llegó al punto',
            'completed' => 'Servicio completado',
            'cancelled' => 'Servicio cancelado',
            'pending' => 'Pendiente',
            'pending_validation' => 'Pago en revisión',
            'paid' => 'Pago confirmado',
        ];

        $key = strtolower(trim((string) $status));

        return $map[$key] ?? ($status ?: 'En proceso');
    };

    $vehicleLabel = function (array $vehicle): string {
        $brand = trim((string) ($vehicle['brand'] ?? ''));
        $model = trim((string) ($vehicle['model'] ?? ''));
        $plate = trim((string) ($vehicle['plate'] ?? ''));

        $base = trim($brand . ' ' . $model);

        return $plate !== ''
            ? trim($base . ' · ' . $plate)
            : ($base !== '' ? $base : 'Vehículo sin datos');
    };

    $activeServiceName = data_get($activeRequest, 'service.name', 'Servicio');
    $activeVehicleName = !empty($activeRequest['vehicle']) && is_array($activeRequest['vehicle'])
        ? $vehicleLabel($activeRequest['vehicle'])
        : 'Vehículo no identificado';
    $activePublicId = data_get($activeRequest, 'public_id')
        ?: (!empty($activeRequest['id']) ? '#' . $activeRequest['id'] : 'Sin folio');

    $blockingMessages = [];

    foreach ($apiErrors as $error) {
        $blockingMessages[] = [
            'tone' => 'danger',
            'message' => $error,
        ];
    }

    if (empty($initialVehicles)) {
        $blockingMessages[] = [
            'tone' => 'warning',
            'message' => 'Antes de pedir ayuda, agrega al menos un vehículo en tu perfil.',
        ];
    }

    if (empty($initialServices)) {
        $blockingMessages[] = [
            'tone' => 'warning',
            'message' => 'En este momento no hay servicios disponibles.',
        ];
    }

    if (!empty($activeRequest)) {
        $blockingMessages[] = [
            'tone' => 'warning',
            'message' => 'Ya tienes una solicitud en proceso. Primero revisa su seguimiento.',
        ];
    }
@endphp

<section class="panel request-hero">
    <div class="request-hero__copy">
        <p class="hero-panel__eyebrow">Pedir ayuda</p>
        <h2>Solicita apoyo para tu vehículo</h2>
        <p>Elige el servicio, revisa el costo y marca el punto donde necesitas ayuda.</p>
    </div>

    <div class="actions-inline">
        @if(!empty($activeRequest))
            <a href="{{ route('user.activo') }}" class="button button--primary">Ver seguimiento</a>
        @else
            <a href="{{ route('user.cuenta') }}" class="button button--secondary">Mis vehículos</a>
        @endif

        <a href="{{ route('user.historial') }}" class="button button--ghost">Historial</a>
    </div>
</section>

<section class="request-step-grid">
    <article class="request-step-card">
        <span>1</span>
        <strong>Elige el servicio</strong>
        <p>Selecciona el tipo de ayuda que necesitas.</p>
    </article>

    <article class="request-step-card">
        <span>2</span>
        <strong>Elige tu vehículo</strong>
        <p>Usa el vehículo correcto para obtener el costo.</p>
    </article>

    <article class="request-step-card">
        <span>3</span>
        <strong>Revisa el costo</strong>
        <p>El sistema calcula el monto automáticamente.</p>
    </article>

    <article class="request-step-card">
        <span>4</span>
        <strong>Marca tu ubicación</strong>
        <p>Ubica el punto exacto donde necesitas ayuda.</p>
    </article>
</section>

@if(!empty($blockingMessages))
    <section class="stack-list">
        @foreach($blockingMessages as $message)
            @php
                $isDanger = ($message['tone'] ?? 'warning') === 'danger';
            @endphp

            <article
                class="notice-card"
                style="border-color: {{ $isDanger ? 'rgba(220,38,38,0.18)' : 'rgba(161,98,7,0.18)' }}; background: {{ $isDanger ? 'rgba(220,38,38,0.05)' : 'rgba(161,98,7,0.06)' }}; color: {{ $isDanger ? '#b91c1c' : '#92400e' }};"
            >
                {{ $message['message'] }}
            </article>
        @endforeach
    </section>
@endif

@if(!empty($activeRequest))
    <section class="panel">
        <div class="section-head">
            <h3>Tienes una solicitud en proceso</h3>
            <span class="section-pill">Activa</span>
        </div>

        <div class="request-state-card">
            <div class="request-state-card__top">
                <div class="request-state-card__title">
                    <h3>{{ $activeServiceName }}</h3>
                    <p>Folio {{ $activePublicId }}</p>
                </div>

                <span class="request-state-pill">
                    {{ $statusLabel(data_get($activeRequest, 'status')) }}
                </span>
            </div>

            <div class="request-state-grid">
                <article class="request-state-item">
                    <span>Vehículo</span>
                    <strong>{{ $activeVehicleName }}</strong>
                </article>

                <article class="request-state-item">
                    <span>Dirección</span>
                    <strong>{{ data_get($activeRequest, 'pickup_address', 'Sin dirección registrada') }}</strong>
                </article>

                <article class="request-state-item">
                    <span>Pago</span>
                    <strong>{{ $statusLabel(data_get($activeRequest, 'payment_status', 'pending')) }}</strong>
                </article>

                <article class="request-state-item">
                    <span>Monto</span>
                    <strong>${{ number_format((float) data_get($activeRequest, 'final_amount', data_get($activeRequest, 'quoted_amount', 0)), 2) }}</strong>
                </article>
            </div>

            <div class="actions-inline">
                <a href="{{ route('user.activo') }}" class="button button--primary">Abrir seguimiento</a>
            </div>
        </div>
    </section>
@endif

<section class="panel">
    <div class="section-head">
        <h3>Nueva solicitud</h3>
        <span class="section-pill">Formulario</span>
    </div>

    <form id="assistanceRequestForm" class="form-grid" autocomplete="off">
        <div class="request-form-layout">
            <div class="request-form-stack">
                <label class="form-field form-field--full">
                    <span>Servicio</span>
                    <select id="requestServiceId" name="service_id" required @disabled(!$requestCanCreate)>
                        <option value="">
                            {{ count($initialServices) ? 'Selecciona un servicio' : 'No hay servicios disponibles' }}
                        </option>
                        @foreach($initialServices as $service)
                            <option value="{{ $service['id'] }}" @selected(old('service_id') == ($service['id'] ?? null))>
                                {{ $service['name'] ?? 'Servicio' }}
                            </option>
                        @endforeach
                    </select>
                </label>

                <label class="form-field form-field--full">
                    <span>Vehículo</span>
                    <select id="requestVehicleId" name="vehicle_id" required @disabled(!$requestCanCreate)>
                        <option value="">
                            {{ count($initialVehicles) ? 'Selecciona un vehículo' : 'No hay vehículos disponibles' }}
                        </option>
                        @foreach($initialVehicles as $vehicle)
                            <option value="{{ $vehicle['id'] }}" @selected(old('vehicle_id') == ($vehicle['id'] ?? null))>
                                {{ $vehicleLabel($vehicle) }}
                            </option>
                        @endforeach
                    </select>
                </label>

                <div id="requestQuoteCard" class="request-quote-card" @if(!$requestCanCreate) hidden @endif>
                    <div id="requestQuoteState" class="request-quote-state">
                        Selecciona un servicio y un vehículo para ver el costo.
                    </div>

                    <div class="request-quote-total">
                        <span>Total</span>
                        <strong id="requestQuoteAmount">—</strong>
                        <small id="requestQuoteCurrencyNote">El costo se calcula automáticamente.</small>
                    </div>

                    <ul id="requestQuoteBreakdown" class="request-quote-list">
                        <li>
                            <span>Sin datos todavía</span>
                            <span>—</span>
                        </li>
                    </ul>

                    <div id="requestQuoteNotes">
                        <div class="request-quote-note">
                            El costo final se volverá a confirmar cuando envíes tu solicitud.
                        </div>
                    </div>
                </div>

                <label class="form-field form-field--full">
                    <span>Buscar dirección</span>

                    <div class="request-search-form">
                        <input
                            type="text"
                            id="requestSearchQuery"
                            placeholder="Ej. Av. Juárez 123, Guadalajara"
                            @disabled(!$requestCanCreate)
                        >

                        <button
                            type="button"
                            id="requestSearchButton"
                            class="button button--ghost"
                            @disabled(!$requestCanCreate)
                        >
                            Buscar
                        </button>
                    </div>

                    <div id="requestSearchResults" class="request-search-results"></div>
                </label>

                <label class="form-field form-field--full">
                    <span>Dirección</span>
                    <textarea
                        id="requestPickupAddress"
                        name="pickup_address"
                        rows="3"
                        class="request-readonly-field"
                        placeholder="El mapa llenará esta dirección automáticamente."
                        required
                        readonly
                        @disabled(!$requestCanCreate)
                    >{{ old('pickup_address') }}</textarea>
                </label>

                <label class="form-field form-field--full">
                    <span>Referencia</span>
                    <textarea
                        id="requestPickupReference"
                        name="pickup_reference"
                        rows="4"
                        placeholder="Ej. Frente a la farmacia, al lado del portón gris"
                        @disabled(!$requestCanCreate)
                    >{{ old('pickup_reference') }}</textarea>
                </label>

                <div class="request-hidden-coordinates">
                    <input
                        type="hidden"
                        id="requestLat"
                        name="lat"
                        value="{{ old('lat') }}"
                        required
                    >

                    <input
                        type="hidden"
                        id="requestLng"
                        name="lng"
                        value="{{ old('lng') }}"
                        required
                    >
                </div>

                <div class="form-actions form-field--full">
                    <button
                        type="submit"
                        id="requestSubmitButton"
                        class="button button--primary"
                        @disabled(!$requestCanCreate)
                    >
                        Enviar solicitud
                    </button>
                </div>
            </div>

            <div class="request-form-stack">
                <div class="request-location-box">
                    <div id="requestLocationStatus" class="request-location-status">
                        Marca el punto exacto donde necesitas ayuda.
                    </div>

                    <div class="actions-inline">
                        <button
                            type="button"
                            id="requestMapLocateButton"
                            class="button button--secondary"
                            @disabled(!$requestCanCreate)
                        >
                            Usar mi ubicación
                        </button>
                    </div>

                    <div class="request-map-shell">
                        <div id="requestMap" class="request-map"></div>
                        <div id="requestMapOverlay" class="request-map-overlay">
                            Toca el mapa para marcar tu ubicación o usa el botón de ubicación actual.
                        </div>
                    </div>

                    <div class="request-location-grid">
                        <article class="request-location-item">
                            <span>Latitud</span>
                            <strong id="requestLatPreview">{{ old('lat') ?: 'Pendiente' }}</strong>
                        </article>

                        <article class="request-location-item">
                            <span>Longitud</span>
                            <strong id="requestLngPreview">{{ old('lng') ?: 'Pendiente' }}</strong>
                        </article>

                        <article class="request-location-item" style="grid-column: 1 / -1;">
                            <span>Ubicación detectada</span>
                            <strong id="requestDetectedAddress">{{ old('pickup_address') ?: 'Pendiente de seleccionar ubicación' }}</strong>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>
@endsection

@push('page_scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
(function () {
    const DEFAULT_CENTER = [20.6736, -103.3440];
    const DEFAULT_ZOOM = 15;

    let map = null;
    let marker = null;
    let latestDetectedAddress = '';
    let searchAbortController = null;
    let quoteAbortController = null;
    let latestQuote = null;

    function boot() {
        const app = window.ZYGA_CLIENT_APP || {};

        if ((app.page || '') !== 'request') {
            return;
        }

        if (!window.L) {
            updateLocationStatus('No pudimos cargar el mapa.', 'danger');
            return;
        }

        initializeMap();
        bindUi();
        hydrateInitialState();
        hydrateQuoteState();
        bindFormSubmit();
    }

    function bindUi() {
        const locateButton = document.getElementById('requestMapLocateButton');
        const searchButton = document.getElementById('requestSearchButton');
        const searchInput = document.getElementById('requestSearchQuery');
        const serviceSelect = document.getElementById('requestServiceId');
        const vehicleSelect = document.getElementById('requestVehicleId');

        if (locateButton) {
            locateButton.addEventListener('click', useCurrentLocation);
        }

        if (searchButton) {
            searchButton.addEventListener('click', runSearch);
        }

        if (searchInput) {
            searchInput.addEventListener('keydown', function (event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    runSearch();
                }
            });
        }

        if (serviceSelect) {
            serviceSelect.addEventListener('change', hydrateQuoteState);
        }

        if (vehicleSelect) {
            vehicleSelect.addEventListener('change', hydrateQuoteState);
        }
    }

    function bindFormSubmit() {
        const form = document.getElementById('assistanceRequestForm');

        if (!form) {
            return;
        }

        form.addEventListener('submit', async function (event) {
            event.preventDefault();

            const app = window.ZYGA_CLIENT_APP || {};
            const submitButton = document.getElementById('requestSubmitButton');
            const serviceId = getValue('requestServiceId');
            const vehicleId = getValue('requestVehicleId');
            const lat = getValue('requestLat');
            const lng = getValue('requestLng');
            const pickupAddress = getValue('requestPickupAddress');
            const pickupReference = getValue('requestPickupReference');

            if (!app.apiBaseUrl || !app.token) {
                alert('Tu sesión no está lista. Vuelve a iniciar sesión.');
                return;
            }

            if (!serviceId || !vehicleId) {
                alert('Primero elige el servicio y tu vehículo.');
                return;
            }

            if (!latestQuote) {
                alert('Espera a que se calcule el costo antes de continuar.');
                return;
            }

            if (!lat || !lng || !pickupAddress) {
                alert('Marca tu ubicación en el mapa antes de enviar la solicitud.');
                return;
            }

            if (submitButton) {
                submitButton.disabled = true;
                submitButton.textContent = 'Enviando...';
            }

            try {
                const response = await fetch(app.apiBaseUrl + '/api/v1/client/assistance-requests', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + app.token
                    },
                    body: JSON.stringify({
                        service_id: Number(serviceId),
                        vehicle_id: Number(vehicleId),
                        lat: Number(lat),
                        lng: Number(lng),
                        pickup_address: pickupAddress,
                        pickup_reference: pickupReference || null
                    })
                });

                const payload = await response.json().catch(function () {
                    return {};
                });

                if (!response.ok) {
                    throw new Error(readableApiMessage(payload.message || 'No pudimos enviar tu solicitud.'));
                }

                alert('Tu solicitud fue enviada correctamente.');
                window.location.href = (app.routes && app.routes.active) ? app.routes.active : '/user/activo';
            } catch (error) {
                alert(error.message || 'No pudimos enviar tu solicitud.');
            } finally {
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.textContent = 'Enviar solicitud';
                }
            }
        });
    }

    function initializeMap() {
        const mapElement = document.getElementById('requestMap');

        if (!mapElement) {
            return;
        }

        map = L.map(mapElement).setView(DEFAULT_CENTER, DEFAULT_ZOOM);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        map.on('click', function (event) {
            setSelectedPoint(event.latlng.lat, event.latlng.lng, {
                recenter: false,
                reverse: true
            });
        });

        setTimeout(function () {
            if (map) {
                map.invalidateSize();
            }
        }, 250);
    }

    function hydrateInitialState() {
        const lat = toNumber(getValue('requestLat'));
        const lng = toNumber(getValue('requestLng'));
        const pickupAddress = getValue('requestPickupAddress');

        if (pickupAddress) {
            latestDetectedAddress = pickupAddress;
            setDetectedAddress(pickupAddress);
            setPickupAddressField(pickupAddress);
        }

        if (lat !== null && lng !== null) {
            setSelectedPoint(lat, lng, {
                recenter: true,
                reverse: pickupAddress === ''
            });
            return;
        }

        updateLocationStatus('Marca el punto exacto donde necesitas ayuda.', 'warning');
    }

    function hydrateQuoteState() {
        const card = document.getElementById('requestQuoteCard');
        const submitButton = document.getElementById('requestSubmitButton');
        const serviceId = getValue('requestServiceId');
        const vehicleId = getValue('requestVehicleId');

        if (!card) {
            return;
        }

        latestQuote = null;
        card.hidden = false;

        if (submitButton) {
            submitButton.disabled = true;
        }

        if (!serviceId || !vehicleId) {
            renderQuoteIdle('Selecciona un servicio y un vehículo para ver el costo.');
            return;
        }

        fetchQuote(serviceId, vehicleId);
    }

    async function fetchQuote(serviceId, vehicleId) {
        const app = window.ZYGA_CLIENT_APP || {};

        if (!app.apiBaseUrl || !app.token) {
            renderQuoteError('Tu sesión no está lista para calcular el costo.');
            return;
        }

        renderQuoteLoading();

        if (quoteAbortController) {
            quoteAbortController.abort();
        }

        quoteAbortController = new AbortController();

        try {
            const response = await fetch(app.apiBaseUrl + '/api/v1/client/assistance-requests/quote', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + app.token
                },
                body: JSON.stringify({
                    service_id: Number(serviceId),
                    vehicle_id: Number(vehicleId)
                }),
                signal: quoteAbortController.signal
            });

            const payload = await response.json().catch(function () {
                return {};
            });

            if (!response.ok) {
                throw new Error(readableApiMessage(payload.message || 'No pudimos calcular el costo.'));
            }

            latestQuote = payload && payload.data ? payload.data : null;

            if (!latestQuote) {
                throw new Error('No pudimos calcular el costo en este momento.');
            }

            renderQuoteSuccess(latestQuote);
        } catch (error) {
            if (error.name === 'AbortError') {
                return;
            }

            latestQuote = null;
            renderQuoteError(error.message || 'No pudimos calcular el costo.');
        }
    }

    function renderQuoteIdle(message) {
        setQuoteState(message, 'default');
        setQuoteAmount('—');
        setQuoteCurrencyNote('El costo aparecerá aquí.');
        setQuoteBreakdown([
            ['Costo base', '—'],
            ['Recargo nocturno', '—'],
            ['Recargo fin de semana', '—'],
        ]);
        setQuoteNotes([
            'El costo se calcula automáticamente cuando eliges el servicio y el vehículo.',
        ]);
    }

    function renderQuoteLoading() {
        setQuoteState('Calculando costo...', 'loading');
        setQuoteAmount('Calculando...');
        setQuoteCurrencyNote('Espera un momento.');
        setQuoteBreakdown([
            ['Costo base', '...'],
            ['Recargo nocturno', '...'],
            ['Recargo fin de semana', '...'],
        ]);
        setQuoteNotes([
            'Estamos revisando la tarifa disponible para tu solicitud.',
        ]);
    }

    function renderQuoteSuccess(quote) {
        const submitButton = document.getElementById('requestSubmitButton');
        const currency = String(quote.currency || 'MXN').toUpperCase();

        setQuoteState('Costo listo. Ya puedes continuar con tu ubicación.', 'success');
        setQuoteAmount(formatMoney(quote.quoted_amount, currency));
        setQuoteCurrencyNote(currency + ' · calculado automáticamente');
        setQuoteBreakdown([
            ['Costo base', formatMoney(quote.base_amount, currency)],
            ['Recargo nocturno', formatMoney(quote.night_surcharge, currency)],
            ['Recargo fin de semana', formatMoney(quote.weekend_surcharge, currency)],
        ]);

        const notes = [];

        if (quote.conditions && quote.conditions.is_night) {
            notes.push('Se aplicó recargo nocturno.');
        } else {
            notes.push('No se aplicó recargo nocturno.');
        }

        if (quote.conditions && quote.conditions.is_weekend) {
            notes.push('Se aplicó recargo de fin de semana.');
        } else {
            notes.push('No se aplicó recargo de fin de semana.');
        }

        setQuoteNotes(notes);

        if (submitButton) {
            submitButton.disabled = false;
        }
    }

    function renderQuoteError(message) {
        const submitButton = document.getElementById('requestSubmitButton');

        setQuoteState(message, 'danger');
        setQuoteAmount('No disponible');
        setQuoteCurrencyNote('No fue posible calcular el costo.');
        setQuoteBreakdown([
            ['Costo base', '—'],
            ['Recargo nocturno', '—'],
            ['Recargo fin de semana', '—'],
        ]);
        setQuoteNotes([
            'Verifica tu servicio, tu vehículo o intenta nuevamente en unos momentos.',
        ]);

        if (submitButton) {
            submitButton.disabled = true;
        }
    }

    function setQuoteState(message, tone) {
        const target = document.getElementById('requestQuoteState');

        if (!target) return;

        target.className = 'request-quote-state';

        if (tone === 'loading') target.classList.add('request-quote-state--loading');
        if (tone === 'success') target.classList.add('request-quote-state--success');
        if (tone === 'warning') target.classList.add('request-quote-state--warning');
        if (tone === 'danger') target.classList.add('request-quote-state--danger');

        target.textContent = message;
    }

    function setQuoteAmount(value) {
        const target = document.getElementById('requestQuoteAmount');
        if (target) target.textContent = value;
    }

    function setQuoteCurrencyNote(value) {
        const target = document.getElementById('requestQuoteCurrencyNote');
        if (target) target.textContent = value;
    }

    function setQuoteBreakdown(items) {
        const container = document.getElementById('requestQuoteBreakdown');

        if (!container) return;

        container.innerHTML = (items || []).map(function (item) {
            return '<li><span>' + escapeHtml(item[0]) + '</span><span>' + escapeHtml(item[1]) + '</span></li>';
        }).join('');
    }

    function setQuoteNotes(items) {
        const container = document.getElementById('requestQuoteNotes');

        if (!container) return;

        container.innerHTML = (items || []).map(function (item) {
            return '<div class="request-quote-note">' + escapeHtml(item) + '</div>';
        }).join('');
    }

    async function useCurrentLocation() {
        if (!navigator.geolocation) {
            updateLocationStatus('Tu celular no permite obtener la ubicación desde aquí.', 'danger');
            return;
        }

        updateLocationStatus('Buscando tu ubicación...', 'info');

        navigator.geolocation.getCurrentPosition(
            function (position) {
                setSelectedPoint(position.coords.latitude, position.coords.longitude, {
                    recenter: true,
                    reverse: true
                });
            },
            function () {
                updateLocationStatus('No pudimos obtener tu ubicación.', 'danger');
            },
            {
                enableHighAccuracy: true,
                timeout: 12000,
                maximumAge: 0
            }
        );
    }

    async function runSearch() {
        const query = String(getValue('requestSearchQuery') || '').trim();

        if (!query) {
            updateLocationStatus('Escribe una dirección para buscarla.', 'warning');
            return;
        }

        updateLocationStatus('Buscando dirección...', 'info');
        renderSearchResults([]);

        if (searchAbortController) {
            searchAbortController.abort();
        }

        searchAbortController = new AbortController();

        try {
            const params = new URLSearchParams({
                q: query,
                format: 'jsonv2',
                limit: '5',
                countrycodes: 'mx',
                addressdetails: '1'
            });

            const response = await fetch('https://nominatim.openstreetmap.org/search?' + params.toString(), {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                },
                signal: searchAbortController.signal
            });

            const results = await response.json();

            if (!Array.isArray(results) || !results.length) {
                updateLocationStatus('No encontramos resultados para esa dirección.', 'warning');
                return;
            }

            renderSearchResults(results);
            updateLocationStatus('Selecciona una opción para marcar la ubicación.', 'success');
        } catch (error) {
            if (error.name === 'AbortError') {
                return;
            }

            updateLocationStatus('No pudimos buscar la dirección.', 'danger');
        }
    }

    function renderSearchResults(results) {
        const container = document.getElementById('requestSearchResults');

        if (!container) return;

        if (!results.length) {
            container.innerHTML = '';
            return;
        }

        container.innerHTML = results.map(function (item, index) {
            const title = escapeHtml(item.display_name || ('Resultado ' + (index + 1)));

            return `
                <button type="button" class="request-search-result" data-lat="${escapeHtml(item.lat)}" data-lng="${escapeHtml(item.lon)}" data-address="${title}">
                    <strong>Resultado ${index + 1}</strong>
                    <span>${title}</span>
                </button>
            `;
        }).join('');

        Array.from(container.querySelectorAll('.request-search-result')).forEach(function (button) {
            button.addEventListener('click', function () {
                const lat = Number(button.dataset.lat);
                const lng = Number(button.dataset.lng);
                const address = button.dataset.address || '';

                setSelectedPoint(lat, lng, {
                    recenter: true,
                    reverse: false
                });

                latestDetectedAddress = address;
                setDetectedAddress(address);
                setPickupAddressField(address);
                updateLocationStatus('Ubicación guardada correctamente.', 'success');
                container.innerHTML = '';
            });
        });
    }

    function setSelectedPoint(lat, lng, options) {
        if (!map) return;

        if (!marker) {
            marker = L.marker([lat, lng], {
                draggable: true,
                icon: L.divIcon({
                    className: 'request-map-pin-wrapper',
                    html: '<span class="request-map-pin"></span>',
                    iconSize: [22, 22],
                    iconAnchor: [11, 11]
                })
            }).addTo(map);

            marker.on('dragend', function () {
                const position = marker.getLatLng();

                setSelectedPoint(position.lat, position.lng, {
                    recenter: false,
                    reverse: true
                });
            });
        } else {
            marker.setLatLng([lat, lng]);
        }

        if (!options || options.recenter !== false) {
            map.setView([lat, lng], Math.max(map.getZoom(), DEFAULT_ZOOM));
        }

        setCoordinateFields(lat, lng);

        if (!options || options.reverse !== false) {
            reverseGeocode(lat, lng);
        } else {
            updateLocationStatus('Ubicación guardada correctamente.', 'success');
        }

        hideMapOverlay();
    }

    async function reverseGeocode(lat, lng) {
        updateLocationStatus('Obteniendo dirección…', 'info');

        try {
            const params = new URLSearchParams({
                lat: String(lat),
                lon: String(lng),
                format: 'jsonv2',
                zoom: '18',
                addressdetails: '1'
            });

            const response = await fetch('https://nominatim.openstreetmap.org/reverse?' + params.toString(), {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();
            const address = (data && data.display_name ? data.display_name : '').trim();

            if (!address) {
                updateLocationStatus('La ubicación fue guardada, pero no pudimos obtener la dirección.', 'warning');
                return;
            }

            latestDetectedAddress = address;
            setDetectedAddress(address);
            setPickupAddressField(address);
            updateLocationStatus('Ubicación guardada correctamente.', 'success');
        } catch (error) {
            updateLocationStatus('La ubicación fue guardada, pero no pudimos obtener la dirección.', 'warning');
        }
    }

    function setCoordinateFields(lat, lng) {
        const latValue = Number(lat).toFixed(6);
        const lngValue = Number(lng).toFixed(6);

        setInputValue('requestLat', latValue);
        setInputValue('requestLng', lngValue);
        setText('requestLatPreview', latValue);
        setText('requestLngPreview', lngValue);
    }

    function setDetectedAddress(address) {
        setText('requestDetectedAddress', address || 'Pendiente de seleccionar ubicación');
    }

    function setPickupAddressField(address) {
        setInputValue('requestPickupAddress', address || '');
    }

    function updateLocationStatus(message, tone) {
        const target = document.getElementById('requestLocationStatus');

        if (!target) return;

        target.className = 'request-location-status';

        if (tone === 'warning') target.classList.add('request-location-status--warning');
        if (tone === 'danger') target.classList.add('request-location-status--danger');
        if (tone === 'success') target.classList.add('request-location-status--success');

        target.textContent = message;
    }

    function hideMapOverlay() {
        const overlay = document.getElementById('requestMapOverlay');

        if (!overlay) return;

        overlay.hidden = true;
        overlay.textContent = '';
    }

    function getValue(id) {
        const node = document.getElementById(id);
        return node ? String(node.value || '').trim() : '';
    }

    function setInputValue(id, value) {
        const node = document.getElementById(id);
        if (node) node.value = value;
    }

    function setText(id, value) {
        const node = document.getElementById(id);
        if (node) node.textContent = value;
    }

    function readableApiMessage(message) {
        const text = String(message || '').trim();
        const normalized = text.toLowerCase();

        if (!text) {
            return 'Ocurrió un problema. Intenta nuevamente.';
        }

        if (normalized.includes('ya existe una solicitud activa')) {
            return 'Ya tienes una solicitud en proceso. Abre el seguimiento para continuar.';
        }

        if (normalized.includes('vehículo seleccionado no pertenece')) {
            return 'No pudimos usar ese vehículo. Intenta nuevamente desde tu cuenta.';
        }

        if (normalized.includes('no existe una tarifa activa')) {
            return 'No hay costo disponible para esa combinación de servicio y vehículo.';
        }

        return text;
    }

    function formatMoney(value, currency) {
        const amount = Number(value);

        if (!Number.isFinite(amount)) {
            return '—';
        }

        try {
            return new Intl.NumberFormat('es-MX', {
                style: 'currency',
                currency: currency || 'MXN',
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(amount);
        } catch (error) {
            return '$' + amount.toFixed(2);
        }
    }

    function toNumber(value) {
        const number = Number(value);
        return Number.isFinite(number) ? number : null;
    }

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot, { once: true });
    } else {
        boot();
    }
})();
</script>
@endpush

