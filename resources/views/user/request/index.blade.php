@extends('user.layouts.app')

@push('page_styles')
<link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
/>

<style>
    .request-map-layout {
        display: grid;
        grid-template-columns: minmax(0, 1.35fr) minmax(320px, 0.9fr);
        gap: 20px;
        align-items: start;
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
        min-height: 460px;
    }

    .request-map-overlay {
        position: absolute;
        inset: auto 16px 16px 16px;
        z-index: 500;
        background: rgba(15, 23, 42, 0.78);
        color: #fff;
        padding: 12px 14px;
        border-radius: 16px;
        font-size: 0.92rem;
        line-height: 1.5;
        backdrop-filter: blur(8px);
    }

    .request-location-panel {
        display: grid;
        gap: 16px;
    }

    .request-map-toolbar {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
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
        font-size: 0.95rem;
        margin-bottom: 4px;
    }

    .request-search-result span {
        display: block;
        font-size: 0.85rem;
        color: #64748b;
        line-height: 1.45;
    }

    .request-location-summary {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .request-location-chip {
        padding: 14px 16px;
        border-radius: 18px;
        background: #f8fafc;
        border: 1px solid rgba(148, 163, 184, 0.18);
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .request-location-chip span {
        font-size: 0.8rem;
        color: #64748b;
    }

    .request-location-chip strong {
        color: #0f172a;
        line-height: 1.5;
        font-size: 0.96rem;
        word-break: break-word;
    }

    .request-location-status {
        padding: 14px 16px;
        border-radius: 18px;
        border: 1px solid rgba(15, 23, 42, 0.08);
        background: rgba(37, 99, 235, 0.08);
        color: #1d4ed8;
        font-weight: 600;
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

    .request-map-pin {
        display: block;
        width: 22px;
        height: 22px;
        border-radius: 999px;
        background: #f97316;
        border: 3px solid #fff;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.24);
    }

    .request-map-pin-wrapper {
        background: transparent;
        border: 0;
    }

    .request-hidden-coordinates {
        display: none;
    }

    @media (max-width: 980px) {
        .request-map-layout {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 640px) {
        .request-map {
            min-height: 360px;
        }

        .request-search-form {
            grid-template-columns: 1fr;
        }

        .request-location-summary {
            grid-template-columns: 1fr;
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
            'created' => 'Creada',
            'accepted' => 'Aceptada',
            'assigned' => 'Asignada',
            'in_progress' => 'En progreso',
            'arrived' => 'En sitio',
            'completed' => 'Completada',
            'cancelled' => 'Cancelada',
            'quoted' => 'Cotizada',
            'pending' => 'Pendiente',
        ];

        $key = strtolower(trim((string) $status));

        return $map[$key] ?? ($status ?: 'Sin estado');
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

    $activeServiceName = data_get($activeRequest, 'service.name', 'Servicio no identificado');
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
            'message' => 'No tienes vehículos disponibles. Debes registrar al menos uno antes de solicitar asistencia.',
        ];
    }

    if (empty($initialServices)) {
        $blockingMessages[] = [
            'tone' => 'warning',
            'message' => 'No hay servicios activos disponibles para generar una solicitud en este momento.',
        ];
    }

    if (!empty($activeRequest)) {
        $blockingMessages[] = [
            'tone' => 'warning',
            'message' => 'Ya existe una solicitud activa para este cliente. Debes darle seguimiento o cancelarla antes de crear otra.',
        ];
    }

    $formHelperMessage = $requestCanCreate
        ? 'Selecciona la ubicación en el mapa, usa tu ubicación actual o busca una dirección. El sistema guardará latitud, longitud y una dirección base para la solicitud.'
        : 'El formulario está bloqueado hasta que el flujo cumpla las condiciones mínimas de operación.';
@endphp

<section class="panel hero-panel hero-panel--compact">
    <div>
        <p class="hero-panel__eyebrow">Nueva asistencia</p>
        <h2>Selecciona el punto exacto donde necesitas apoyo.</h2>
        <p>
            Elige el servicio, el vehículo involucrado y marca la ubicación en el mapa.
            La solicitud guardará las coordenadas y la dirección base del punto seleccionado.
        </p>
    </div>
</section>

<section id="requestBlockingState" class="stack-list">
    @forelse($blockingMessages as $message)
        @php
            $isDanger = ($message['tone'] ?? 'warning') === 'danger';
        @endphp

        <article
            class="notice-card"
            style="border-color: {{ $isDanger ? 'rgba(220,38,38,0.18)' : 'rgba(161,98,7,0.18)' }}; background: {{ $isDanger ? 'rgba(220,38,38,0.05)' : 'rgba(161,98,7,0.06)' }}; color: {{ $isDanger ? '#b91c1c' : '#92400e' }};"
        >
            {{ $message['message'] }}
        </article>
    @empty
        <article
            class="notice-card"
            style="border-color: rgba(22,163,74,0.18); background: rgba(22,163,74,0.05); color: #166534;"
        >
            El flujo está listo para crear una nueva asistencia.
        </article>
    @endforelse
</section>

<section class="grid-two">
    <article class="panel">
        <div class="section-head">
            <h3>Antes de solicitar</h3>
            <span class="section-pill">Revisión</span>
        </div>

        <div class="stack-list">
            <article class="card-row">
                <h4 class="card-row__title">1. Verifica tu vehículo</h4>
                <p class="card-row__meta">
                    Debes tener al menos un vehículo registrado en tu cuenta para poder continuar.
                </p>
            </article>

            <article class="card-row">
                <h4 class="card-row__title">2. Marca el punto real</h4>
                <p class="card-row__meta">
                    Usa el mapa para ubicar el lugar exacto de atención. Puedes buscar una dirección, tocar el mapa o usar tu ubicación actual.
                </p>
            </article>

            <article class="card-row">
                <h4 class="card-row__title">3. Complementa con referencia</h4>
                <p class="card-row__meta">
                    La dirección base se llenará automáticamente. Puedes agregar detalles como “frente a la gasolinera” o “portón gris”.
                </p>
            </article>
        </div>

        <div class="actions-inline" style="margin-top: 1rem;">
            @if(empty($initialVehicles))
                <a href="{{ route('user.cuenta') }}" class="button button--secondary">Ir a Cuenta</a>
            @endif

            @if(!empty($activeRequest))
                <a href="{{ route('user.activo') }}" class="button button--primary">Abrir servicio activo</a>
            @endif
        </div>
    </article>

    <article class="panel">
        <div class="section-head">
            <h3>Estado del flujo</h3>
            <span class="section-pill">MVP</span>
        </div>

        @if(!empty($activeRequest))
            <div class="stack-list">
                <article class="card-row">
                    <h4 class="card-row__title">Solicitud activa detectada</h4>
                    <p class="card-row__meta">Folio: {{ $activePublicId }}</p>
                    <p class="card-row__meta">Servicio: {{ $activeServiceName }}</p>
                    <p class="card-row__meta">Vehículo: {{ $activeVehicleName }}</p>
                    <p class="card-row__meta">Estado: {{ $statusLabel(data_get($activeRequest, 'status')) }}</p>
                </article>
            </div>
        @else
            <div class="helper-note">
                Si ya tienes una solicitud activa, el sistema bloqueará la creación de otra hasta que la actual
                termine o sea cancelada.
            </div>

            <div class="stack-list">
                <article class="card-row">
                    <h4 class="card-row__title">Solicitud única activa</h4>
                    <p class="card-row__meta">
                        Esto evita duplicidades y mantiene ordenado el proceso entre cliente, proveedor y administración.
                    </p>
                </article>

                <article class="card-row">
                    <h4 class="card-row__title">Seguimiento posterior</h4>
                    <p class="card-row__meta">
                        Después de crear la asistencia, el control principal pasa a la vista de servicio activo.
                    </p>
                </article>

                <article class="card-row">
                    <h4 class="card-row__title">Servicios disponibles</h4>
                    <p class="card-row__meta">
                        {{ count($initialServices) }} servicio(s) activos detectados desde la API.
                    </p>
                </article>

                <article class="card-row">
                    <h4 class="card-row__title">Vehículos disponibles</h4>
                    <p class="card-row__meta">
                        {{ count($initialVehicles) }} vehículo(s) detectados para este cliente.
                    </p>
                </article>
            </div>
        @endif
    </article>
</section>

<section class="panel">
    <div class="section-head">
        <h3>Formulario de solicitud</h3>
        <span class="section-pill">Ubicación</span>
    </div>

    <div class="helper-note">
        {{ $formHelperMessage }}
    </div>

    <form id="assistanceRequestForm" class="form-grid" autocomplete="off">
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

        <div class="form-field form-field--full">
            <span>Buscar ubicación</span>

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
        </div>

        <div class="form-field form-field--full">
            <span>Selecciona el punto en el mapa</span>

            <div class="request-map-layout">
                <div class="request-map-shell">
                    <div id="requestMap" class="request-map"></div>
                    <div id="requestMapOverlay" class="request-map-overlay">
                        Toca el mapa o usa “Mi ubicación” para fijar el punto exacto donde necesitas apoyo.
                    </div>
                </div>

                <div class="request-location-panel">
                    <div id="requestLocationStatus" class="request-location-status">
                        Aún no se ha fijado una ubicación para la solicitud.
                    </div>

                    <div class="request-map-toolbar">
                        <button
                            type="button"
                            id="requestMapLocateButton"
                            class="button button--secondary"
                            @disabled(!$requestCanCreate)
                        >
                            Usar mi ubicación
                        </button>

                        <button
                            type="button"
                            id="requestMapResetAddressButton"
                            class="button button--ghost"
                            @disabled(!$requestCanCreate)
                        >
                            Usar dirección detectada
                        </button>
                    </div>

                    <div class="request-location-summary">
                        <article class="request-location-chip">
                            <span>Latitud</span>
                            <strong id="requestLatPreview">{{ old('lat') ?: 'Pendiente' }}</strong>
                        </article>

                        <article class="request-location-chip">
                            <span>Longitud</span>
                            <strong id="requestLngPreview">{{ old('lng') ?: 'Pendiente' }}</strong>
                        </article>

                        <article class="request-location-chip" style="grid-column: 1 / -1;">
                            <span>Dirección detectada desde el mapa</span>
                            <strong id="requestDetectedAddress">{{ old('pickup_address') ?: 'Pendiente de seleccionar ubicación' }}</strong>
                        </article>
                    </div>
                </div>
            </div>
        </div>

        <label class="form-field form-field--full">
            <span>Dirección y referencia final</span>
            <textarea
                id="requestPickupAddress"
                name="pickup_address"
                rows="4"
                placeholder="La dirección detectada se llenará aquí. Puedes agregar detalles como fachada, negocio cercano o punto exacto."
                required
                @disabled(!$requestCanCreate)
            >{{ old('pickup_address') }}</textarea>
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
                Crear asistencia
            </button>
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

    function boot() {
        const app = window.ZYGA_CLIENT_APP || {};

        if ((app.page || '') !== 'request') {
            return;
        }

        if (!window.L) {
            updateStatus('No fue posible cargar el mapa.', 'danger');
            return;
        }

        initializeMap();
        bindUi();
        hydrateInitialState();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot, { once: true });
    } else {
        boot();
    }

    function bindUi() {
        const locateButton = document.getElementById('requestMapLocateButton');
        const searchButton = document.getElementById('requestSearchButton');
        const searchInput = document.getElementById('requestSearchQuery');
        const resetAddressButton = document.getElementById('requestMapResetAddressButton');
        const pickupAddress = document.getElementById('requestPickupAddress');

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

        if (resetAddressButton) {
            resetAddressButton.addEventListener('click', function () {
                if (!latestDetectedAddress) {
                    updateStatus('Primero selecciona un punto válido en el mapa.', 'warning');
                    return;
                }

                if (pickupAddress) {
                    pickupAddress.value = latestDetectedAddress;
                    pickupAddress.dataset.autofilled = '1';
                }

                updateStatus('La dirección detectada fue copiada al campo final.', 'success');
            });
        }

        if (pickupAddress) {
            pickupAddress.addEventListener('input', function () {
                pickupAddress.dataset.autofilled = '0';
            });
        }
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
                source: 'map',
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
        const latInput = document.getElementById('requestLat');
        const lngInput = document.getElementById('requestLng');
        const pickupAddress = document.getElementById('requestPickupAddress');

        const lat = toNumber(latInput && latInput.value);
        const lng = toNumber(lngInput && lngInput.value);

        if (pickupAddress && pickupAddress.value.trim() !== '') {
            latestDetectedAddress = pickupAddress.value.trim();
            setDetectedAddress(latestDetectedAddress);
        }

        if (lat !== null && lng !== null) {
            setSelectedPoint(lat, lng, {
                source: 'initial',
                recenter: true,
                reverse: pickupAddress ? pickupAddress.value.trim() === '' : true
            });
            return;
        }

        updateStatus('Selecciona un punto en el mapa, busca una dirección o usa tu ubicación actual.', 'warning');
    }

    function createMarker(lat, lng) {
        return L.marker([lat, lng], {
            draggable: true,
            icon: L.divIcon({
                className: 'request-map-pin-wrapper',
                html: '<span class="request-map-pin"></span>',
                iconSize: [22, 22],
                iconAnchor: [11, 11]
            })
        }).addTo(map);
    }

    function setSelectedPoint(lat, lng, options = {}) {
        if (!map) {
            return;
        }

        if (!marker) {
            marker = createMarker(lat, lng);

            marker.on('dragend', function () {
                const position = marker.getLatLng();

                setSelectedPoint(position.lat, position.lng, {
                    source: 'drag',
                    recenter: false,
                    reverse: true
                });
            });
        } else {
            marker.setLatLng([lat, lng]);
        }

        if (options.recenter !== false) {
            map.setView([lat, lng], Math.max(map.getZoom(), DEFAULT_ZOOM));
        }

        setCoordinateFields(lat, lng);

        if (options.reverse !== false) {
            reverseGeocode(lat, lng);
        } else {
            updateStatus('Ubicación cargada correctamente.', 'success');
        }

        hideMapOverlay();
    }

    function setCoordinateFields(lat, lng) {
        const latValue = Number(lat).toFixed(6);
        const lngValue = Number(lng).toFixed(6);

        const latInput = document.getElementById('requestLat');
        const lngInput = document.getElementById('requestLng');
        const latPreview = document.getElementById('requestLatPreview');
        const lngPreview = document.getElementById('requestLngPreview');

        if (latInput) latInput.value = latValue;
        if (lngInput) lngInput.value = lngValue;
        if (latPreview) latPreview.textContent = latValue;
        if (lngPreview) lngPreview.textContent = lngValue;
    }

    async function useCurrentLocation() {
        if (!navigator.geolocation) {
            updateStatus('Tu navegador no soporta geolocalización.', 'danger');
            return;
        }

        updateStatus('Obteniendo ubicación actual del dispositivo...', 'info');

        navigator.geolocation.getCurrentPosition(
            function (position) {
                setSelectedPoint(position.coords.latitude, position.coords.longitude, {
                    source: 'geolocation',
                    recenter: true,
                    reverse: true
                });
            },
            function () {
                updateStatus('No fue posible obtener la ubicación del dispositivo.', 'danger');
            },
            {
                enableHighAccuracy: true,
                timeout: 12000,
                maximumAge: 0
            }
        );
    }

    async function runSearch() {
        const queryInput = document.getElementById('requestSearchQuery');
        const query = (queryInput && queryInput.value ? queryInput.value : '').trim();

        if (!query) {
            updateStatus('Escribe una dirección o referencia para buscarla en el mapa.', 'warning');
            return;
        }

        updateStatus('Buscando dirección en el mapa...', 'info');
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
                updateStatus('No se encontraron resultados para esa búsqueda.', 'warning');
                return;
            }

            renderSearchResults(results);
            updateStatus('Selecciona uno de los resultados para fijar la ubicación.', 'success');
        } catch (error) {
            if (error.name === 'AbortError') {
                return;
            }

            updateStatus('No fue posible buscar la dirección en este momento.', 'danger');
        }
    }

    function renderSearchResults(results) {
        const container = document.getElementById('requestSearchResults');

        if (!container) {
            return;
        }

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
                    source: 'search',
                    recenter: true,
                    reverse: false
                });

                latestDetectedAddress = address;
                setDetectedAddress(address);
                autofillFinalAddress(address);
                updateStatus('Ubicación fijada desde la búsqueda.', 'success');
                container.innerHTML = '';
            });
        });
    }

    async function reverseGeocode(lat, lng) {
        updateStatus('Obteniendo dirección del punto seleccionado...', 'info');

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
                updateStatus('Se fijó la ubicación, pero no se pudo resolver una dirección útil.', 'warning');
                return;
            }

            latestDetectedAddress = address;
            setDetectedAddress(address);
            autofillFinalAddress(address);
            updateStatus('Ubicación fijada correctamente.', 'success');
        } catch (error) {
            updateStatus('Se fijó la ubicación, pero falló la obtención de la dirección.', 'warning');
        }
    }

    function setDetectedAddress(address) {
        const target = document.getElementById('requestDetectedAddress');

        if (target) {
            target.textContent = address || 'Pendiente de seleccionar ubicación';
        }
    }

    function autofillFinalAddress(address) {
        const pickupAddress = document.getElementById('requestPickupAddress');

        if (!pickupAddress) {
            return;
        }

        const currentValue = pickupAddress.value.trim();
        const isAutofilled = pickupAddress.dataset.autofilled === '1';

        if (currentValue === '' || isAutofilled) {
            pickupAddress.value = address;
            pickupAddress.dataset.autofilled = '1';
        }
    }

    function updateStatus(message, tone) {
        const target = document.getElementById('requestLocationStatus');

        if (!target) {
            return;
        }

        target.className = 'request-location-status';

        if (tone === 'warning') {
            target.classList.add('request-location-status--warning');
        } else if (tone === 'danger') {
            target.classList.add('request-location-status--danger');
        } else if (tone === 'success') {
            target.classList.add('request-location-status--success');
        }

        target.textContent = message;
    }

    function hideMapOverlay() {
        const overlay = document.getElementById('requestMapOverlay');

        if (!overlay) {
            return;
        }

        overlay.hidden = true;
        overlay.textContent = '';
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
})();
</script>
@endpush
