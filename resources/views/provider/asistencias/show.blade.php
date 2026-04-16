@extends('provider.layouts.app')

@section('title', 'ZYGA | Detalle de asistencia')
@section('page-title', 'Seguimiento táctico de la asistencia')
@section('page-copy', 'Consulta detalle, progreso, mapa operativo, navegación y tracking GPS de la solicitud.')
@section('page-key', 'assistance-show')

@push('page_styles')
<link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
/>

<style>
    .tracking-card {
        display: grid;
        gap: 16px;
    }

    .tracking-status {
        padding: 16px 18px;
        border-radius: 18px;
        border: 1px solid rgba(15, 23, 42, 0.08);
        background: rgba(37, 99, 235, 0.08);
        color: #1d4ed8;
        font-weight: 700;
        line-height: 1.5;
    }

    .tracking-status--success {
        background: rgba(22, 163, 74, 0.08);
        color: #15803d;
    }

    .tracking-status--warning {
        background: rgba(245, 158, 11, 0.12);
        color: #b45309;
    }

    .tracking-status--danger {
        background: rgba(239, 68, 68, 0.10);
        color: #b91c1c;
    }

    .tracking-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
    }

    .tracking-box {
        padding: 16px;
        border-radius: 18px;
        background: var(--shell-soft);
        border: 1px solid #e5eaf2;
        display: grid;
        gap: 6px;
    }

    .tracking-box span {
        font-size: .82rem;
        color: var(--muted);
    }

    .tracking-box strong {
        font-size: .98rem;
        line-height: 1.5;
        color: var(--text);
        word-break: break-word;
    }

    .tracking-note {
        padding: 14px 16px;
        border-radius: 18px;
        background: #fff7ec;
        border: 1px solid #ffe1b2;
        color: #9a5b00;
        line-height: 1.6;
    }

    .tracking-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .tracking-log {
        padding: 16px;
        border-radius: 18px;
        border: 1px dashed #d4dce8;
        background: #fbfcfe;
        color: var(--muted);
        line-height: 1.6;
        min-height: 68px;
    }

    .provider-map-layout {
        display: grid;
        grid-template-columns: minmax(0, 1.3fr) minmax(320px, 0.9fr);
        gap: 18px;
        align-items: start;
    }

    .provider-map-shell {
        position: relative;
        border-radius: 24px;
        overflow: hidden;
        border: 1px solid rgba(15, 23, 42, 0.08);
        background: #e2e8f0;
    }

    .provider-map {
        width: 100%;
        min-height: 420px;
    }

    .provider-map-overlay {
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

    .provider-map-panel {
        display: grid;
        gap: 14px;
    }

    .provider-map-panel-card {
        padding: 16px;
        border-radius: 18px;
        background: var(--shell-soft);
        border: 1px solid #e5eaf2;
        display: grid;
        gap: 8px;
    }

    .provider-map-panel-card span {
        font-size: .82rem;
        color: var(--muted);
    }

    .provider-map-panel-card strong {
        font-size: .98rem;
        line-height: 1.5;
        color: var(--text);
        word-break: break-word;
    }

    .provider-nav-actions {
        display: grid;
        gap: 10px;
    }

    .provider-nav-links {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .provider-map-marker-wrapper {
        background: transparent;
        border: 0;
    }

    .provider-map-marker {
        display: block;
        width: 20px;
        height: 20px;
        border-radius: 999px;
        border: 3px solid #fff;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.24);
    }

    .provider-map-marker--target {
        background: #2563eb;
    }

    .provider-map-marker--provider {
        background: #f97316;
    }

    @media (max-width: 960px) {
        .tracking-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .provider-map-layout {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 640px) {
        .tracking-grid {
            grid-template-columns: 1fr;
        }

        .provider-map {
            min-height: 320px;
        }
    }
</style>
@endpush

@section('content')
    @php($r = $context['readiness'])
    @php($trackingAllowed = $r['portal_ready'] && in_array(($requestItem['status'] ?? null), ['assigned', 'in_progress'], true))
    @php($hasTargetCoordinates = ($requestItem['lat'] ?? null) !== null && ($requestItem['lng'] ?? null) !== null)

    <section class="hero">
        <p class="eyebrow">Solicitud operativa</p>
        <h2 style="margin:0 0 12px; font-size:2rem;">{{ $requestItem['service_name'] }}</h2>
        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <span class="chip {{ $requestItem['status_tone'] ?? 'info' }}">{{ $requestItem['status_label'] ?? ($requestItem['status'] ?? 'Sin estado') }}</span>
            @if($requestItem['public_id'])
                <span class="chip info">{{ $requestItem['public_id'] }}</span>
            @endif
            <span class="chip {{ $r['portal_ready'] ? 'success' : 'warning' }}">Portal {{ $r['portal_ready'] ? 'habilitado' : 'bloqueado' }}</span>
            <span class="chip {{ $trackingAllowed ? 'success' : 'warning' }}">
                Tracking {{ $trackingAllowed ? 'disponible' : 'no disponible' }}
            </span>
        </div>
        <p class="muted" style="margin:16px 0 0; line-height:1.6;">
            Vista sustentada por el endpoint real <strong>/api/v1/provider/assistance-requests/{id}</strong>.
            Desde aquí el proveedor puede confirmar el punto del cliente, revisar la referencia manual,
            iniciar navegación y compartir su ubicación mientras la asistencia esté asignada o en proceso.
        </p>
    </section>

    <section class="two-col">
        <section class="card">
            <div class="section-head">
                <div>
                    <p class="eyebrow">Datos clave</p>
                    <h3>Resumen de la solicitud</h3>
                </div>
            </div>
            <div class="meta-grid">
                <div class="meta-box"><span>Dirección base</span><strong>{{ $requestItem['pickup_address'] }}</strong></div>
                <div class="meta-box"><span>Cliente</span><strong>{{ $requestItem['client_email'] ?: 'Sin correo visible' }}</strong></div>
                <div class="meta-box"><span>Vehículo</span><strong>{{ $requestItem['vehicle'] }}</strong></div>
                <div class="meta-box"><span>Ubicación objetivo</span><strong>{{ $requestItem['lat'] !== null && $requestItem['lng'] !== null ? $requestItem['lat'] . ', ' . $requestItem['lng'] : 'Sin coordenadas' }}</strong></div>
                <div class="meta-box" style="grid-column: 1 / -1;">
                    <span>Referencia manual del cliente</span>
                    <strong>{{ $requestItem['pickup_reference'] ?: 'El cliente no registró una referencia adicional.' }}</strong>
                </div>
            </div>

            <div class="inline-form" style="margin-top:16px;">
                <a href="{{ route('provider.asistencias') }}" class="btn-outline">Volver</a>
                @if(($requestItem['status'] ?? null) === 'created' && $r['portal_ready'])
                    <form method="POST" action="{{ route('provider.asistencias.accept', $requestItem['id']) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn">Aceptar solicitud</button>
                    </form>
                @endif
            </div>
        </section>

        <section class="card">
            <div class="section-head">
                <div>
                    <p class="eyebrow">Acciones válidas</p>
                    <h3>Transiciones permitidas</h3>
                </div>
            </div>

            @if(!$r['portal_ready'])
                <div class="empty">
                    <h4>Portal bloqueado</h4>
                    <p>Mientras el provider no esté listo, no se exponen acciones operativas aquí.</p>
                </div>
            @elseif(empty($allowedStatusOptions))
                <div class="empty">
                    <h4>Sin transición manual disponible</h4>
                    <p>Este estado ya no admite cambios manuales desde el portal o todavía requiere aceptación previa.</p>
                </div>
            @else
                <form method="POST" action="{{ route('provider.asistencias.status', $requestItem['id']) }}" class="form-grid">
                    @csrf
                    @method('PATCH')
                    <div class="field full">
                        <label class="label" for="status">Nuevo estado</label>
                        <select id="status" name="status" required>
                            <option value="">Selecciona una transición válida</option>
                            @foreach($allowedStatusOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field full">
                        <button type="submit" class="btn full">Actualizar estado</button>
                    </div>
                </form>
            @endif
        </section>
    </section>

    <section class="card">
        <div class="section-head">
            <div>
                <p class="eyebrow">Mapa táctico</p>
                <h3>Destino del cliente y navegación</h3>
            </div>
        </div>

        @if(!$hasTargetCoordinates)
            <div class="tracking-note">
                Esta solicitud no tiene latitud y longitud válidas, así que no se puede mostrar el mapa ni generar navegación externa.
            </div>
        @else
            <div class="provider-map-layout">
                <div class="provider-map-shell">
                    <div id="providerTacticalMap" class="provider-map"></div>
                    <div id="providerTacticalMapOverlay" class="provider-map-overlay">
                        El punto azul representa al cliente. Cuando el GPS del proveedor se actualice, verás tu posición en naranja.
                    </div>
                </div>

                <div class="provider-map-panel">
                    <article class="provider-map-panel-card">
                        <span>Destino de la asistencia</span>
                        <strong id="providerTargetAddress">{{ $requestItem['pickup_address'] ?: 'Sin dirección disponible' }}</strong>
                    </article>

                    <article class="provider-map-panel-card">
                        <span>Referencia operativa</span>
                        <strong id="providerTargetReference">{{ $requestItem['pickup_reference'] ?: 'Sin referencia manual adicional' }}</strong>
                    </article>

                    <article class="provider-map-panel-card">
                        <span>Coordenadas del cliente</span>
                        <strong id="providerTargetCoordinates">
                            {{ $requestItem['lat'] }}, {{ $requestItem['lng'] }}
                        </strong>
                    </article>

                    <article class="provider-map-panel-card">
                        <span>Tu posición actual</span>
                        <strong id="providerCurrentCoordinates">Pendiente de GPS</strong>
                    </article>

                    <article class="provider-map-panel-card">
                        <span>Navegación externa</span>
                        <div class="provider-nav-actions">
                            <div class="provider-nav-links">
                                <a
                                    id="providerGoogleMapsLink"
                                    href="#"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="btn"
                                >
                                    Abrir en Google Maps
                                </a>

                                <a
                                    id="providerWazeLink"
                                    href="#"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="btn-outline"
                                >
                                    Abrir en Waze
                                </a>
                            </div>
                            <small class="muted">
                                La dirección base y la referencia manual deben revisarse juntas antes de iniciar navegación.
                            </small>
                        </div>
                    </article>
                </div>
            </div>
        @endif
    </section>

    <section class="card tracking-card">
        <div class="section-head">
            <div>
                <p class="eyebrow">Tracking del proveedor</p>
                <h3>Envío de ubicación en tiempo real</h3>
            </div>
        </div>

        @if(!$r['portal_ready'])
            <div class="tracking-note">
                El tracking no puede iniciar porque el portal del provider todavía no está listo para operar.
                Completa validación, servicios, horarios y documentos.
            </div>
        @elseif(!in_array(($requestItem['status'] ?? null), ['assigned', 'in_progress'], true))
            <div class="tracking-note">
                El tracking solo se activa cuando la asistencia está en estado <strong>Asignada</strong> o <strong>En proceso</strong>.
                Estado actual: <strong>{{ $requestItem['status_label'] ?? ($requestItem['status'] ?? 'Sin estado') }}</strong>.
            </div>
        @else
            <div id="providerTrackingStatus" class="tracking-status">
                Preparando captura de GPS del proveedor...
            </div>

            <div class="tracking-grid">
                <article class="tracking-box">
                    <span>Estado operativo</span>
                    <strong id="providerTrackingState">Inicializando</strong>
                </article>

                <article class="tracking-box">
                    <span>Última coordenada enviada</span>
                    <strong id="providerTrackingCoordinates">Pendiente</strong>
                </article>

                <article class="tracking-box">
                    <span>Última sincronización</span>
                    <strong id="providerTrackingLastSync">Pendiente</strong>
                </article>

                <article class="tracking-box">
                    <span>Precisión reportada</span>
                    <strong id="providerTrackingAccuracy">Pendiente</strong>
                </article>
            </div>

            <div class="tracking-actions">
                <button type="button" id="providerTrackingRetryButton" class="btn-outline">
                    Reintentar GPS
                </button>

                <button type="button" id="providerTrackingToggleButton" class="btn-ghost">
                    Pausar tracking
                </button>
            </div>

            <div id="providerTrackingLog" class="tracking-log">
                El sistema intentará compartir tu ubicación automáticamente mientras esta asistencia siga asignada o en proceso.
            </div>
        @endif
    </section>

    <section class="card">
        <div class="section-head">
            <div>
                <p class="eyebrow">Timeline técnico</p>
                <h3>Eventos e historial registrados</h3>
            </div>
        </div>

        <div class="two-col">
            <div>
                <h4 style="margin-top:0;">Request history</h4>
                @if(empty($requestRaw['history']))
                    <div class="empty"><h4>Sin historial</h4><p>Aún no hay movimientos registrados para esta solicitud.</p></div>
                @else
                    <div class="timeline">
                        @foreach($requestRaw['history'] as $history)
                            <div class="timeline-entry">
                                <strong>{{ $history['status'] ?? 'Sin estado' }}</strong>
                                <p class="muted">{{ $history['event_type'] ?? ($history['notes'] ?? 'Sin detalle') }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            <div>
                <h4 style="margin-top:0;">Request events</h4>
                @if(empty($requestRaw['events']))
                    <div class="empty"><h4>Sin eventos</h4><p>Aún no hay eventos disponibles para esta solicitud.</p></div>
                @else
                    <div class="timeline">
                        @foreach($requestRaw['events'] as $event)
                            <div class="timeline-entry">
                                <strong>{{ $event['event_type'] ?? 'Evento' }}</strong>
                                <p class="muted">{{ $event['status'] ?? ($event['created_at'] ?? 'Sin fecha') }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

@push('page_scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
(function () {
    const app = window.ZYGA_PROVIDER_APP || {};
    const trackingConfig = {
        requestId: @json($requestItem['id'] ?? null),
        status: @json($requestItem['status'] ?? null),
        portalReady: @json((bool) $r['portal_ready']),
        trackingAllowed: @json($trackingAllowed),
        targetLat: @json(($requestItem['lat'] ?? null) !== null ? (float) $requestItem['lat'] : null),
        targetLng: @json(($requestItem['lng'] ?? null) !== null ? (float) $requestItem['lng'] : null),
        targetAddress: @json($requestItem['pickup_address'] ?? ''),
        targetReference: @json($requestItem['pickup_reference'] ?? ''),
        publicId: @json($requestItem['public_id'] ?? null),
    };

    const ALLOWED_STATUSES = ['assigned', 'in_progress'];
    const SEND_INTERVAL_MS = 8000;

    let watchId = null;
    let trackingEnabled = true;
    let sending = false;
    let lastSentAt = 0;
    let map = null;
    let targetMarker = null;
    let providerMarker = null;
    let providerAccuracyCircle = null;
    let latestProviderPosition = null;

    function boot() {
        if (app.page !== 'assistance-show') {
            return;
        }

        initMap();
        updateNavigationLinks();

        if (!trackingConfig.portalReady || !trackingConfig.trackingAllowed) {
            return;
        }

        if (!navigator.geolocation) {
            setTrackingStatus('Este dispositivo o navegador no soporta geolocalización.', 'danger');
            setStateText('GPS no disponible');
            appendLog('No se pudo iniciar el tracking porque el navegador no soporta geolocalización.');
            return;
        }

        bindButtons();
        startTracking();

        document.addEventListener('visibilitychange', handleVisibilityChange);
        window.addEventListener('beforeunload', stopTracking, { once: true });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot, { once: true });
    } else {
        boot();
    }

    function initMap() {
        if (!window.L) {
            return;
        }

        if (!isFiniteNumber(trackingConfig.targetLat) || !isFiniteNumber(trackingConfig.targetLng)) {
            return;
        }

        const mapElement = document.getElementById('providerTacticalMap');

        if (!mapElement) {
            return;
        }

        map = L.map(mapElement).setView([trackingConfig.targetLat, trackingConfig.targetLng], 15);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        targetMarker = L.marker([trackingConfig.targetLat, trackingConfig.targetLng], {
            icon: createMarkerIcon('target')
        }).addTo(map);

        targetMarker.bindPopup(
            '<strong>Punto del cliente</strong><br>' +
            escapeHtml(trackingConfig.targetAddress || 'Sin dirección') +
            '<br><small>' + escapeHtml(trackingConfig.targetReference || 'Sin referencia adicional') + '</small>'
        );

        setTimeout(function () {
            if (map) {
                map.invalidateSize();
            }
        }, 250);
    }

    function bindButtons() {
        const retryButton = document.getElementById('providerTrackingRetryButton');
        const toggleButton = document.getElementById('providerTrackingToggleButton');

        if (retryButton && retryButton.dataset.bound !== '1') {
            retryButton.dataset.bound = '1';
            retryButton.addEventListener('click', function () {
                appendLog('Reintentando captura de GPS...');
                restartTracking();
            });
        }

        if (toggleButton && toggleButton.dataset.bound !== '1') {
            toggleButton.dataset.bound = '1';
            toggleButton.addEventListener('click', function () {
                trackingEnabled = !trackingEnabled;

                if (trackingEnabled) {
                    toggleButton.textContent = 'Pausar tracking';
                    appendLog('Tracking reactivado manualmente.');
                    startTracking();
                } else {
                    toggleButton.textContent = 'Reanudar tracking';
                    appendLog('Tracking pausado manualmente.');
                    stopTracking();
                    setTrackingStatus('Tracking pausado manualmente por el proveedor.', 'warning');
                    setStateText('Pausado');
                }
            });
        }
    }

    function handleVisibilityChange() {
        if (!trackingConfig.trackingAllowed || !trackingEnabled) {
            return;
        }

        if (document.visibilityState === 'visible') {
            appendLog('La vista volvió a estar visible. Reanudando tracking.');
            startTracking();
        } else {
            appendLog('La vista dejó de estar visible. Tracking en pausa para ahorrar recursos.');
            stopTracking();
            setTrackingStatus('Tracking en pausa porque la vista no está visible.', 'warning');
            setStateText('En pausa');
        }
    }

    function restartTracking() {
        stopTracking();
        startTracking();
    }

    function startTracking() {
        if (!trackingEnabled) {
            return;
        }

        if (!trackingConfig.requestId || !ALLOWED_STATUSES.includes(String(trackingConfig.status || '').toLowerCase())) {
            setTrackingStatus('La asistencia actual no permite tracking en este estado.', 'warning');
            setStateText('No permitido');
            return;
        }

        if (watchId !== null) {
            return;
        }

        setTrackingStatus('Solicitando permiso de ubicación y esperando coordenadas del dispositivo...', 'info');
        setStateText('Esperando GPS');

        watchId = navigator.geolocation.watchPosition(
            handlePosition,
            handlePositionError,
            {
                enableHighAccuracy: true,
                timeout: 15000,
                maximumAge: 0,
            }
        );
    }

    function stopTracking() {
        if (watchId !== null) {
            navigator.geolocation.clearWatch(watchId);
            watchId = null;
        }
    }

    function handlePosition(position) {
        if (!trackingEnabled) {
            return;
        }

        const coords = position.coords || {};
        const lat = Number(coords.latitude);
        const lng = Number(coords.longitude);
        const accuracy = isFiniteNumber(coords.accuracy) ? Number(coords.accuracy) : null;

        latestProviderPosition = {
            lat: lat,
            lng: lng,
            accuracy: accuracy,
            recordedAt: new Date(position.timestamp || Date.now()).toISOString(),
        };

        updateProviderVisuals(lat, lng, accuracy);
        updateNavigationLinks();
        setAccuracyText(accuracy);
        setCoordinatesText(lat, lng);
        setCurrentCoordinatesPanel(lat, lng);

        const now = Date.now();

        if (now - lastSentAt < SEND_INTERVAL_MS) {
            setStateText('GPS activo');
            appendLog('GPS recibido. Esperando siguiente ventana de envío para evitar saturación.');
            return;
        }

        sendLocation(position);
    }

    async function sendLocation(position) {
        if (sending) {
            return;
        }

        const apiBaseUrl = String(app.apiBaseUrl || '').trim();
        const token = String(app.token || '').trim();

        if (!apiBaseUrl) {
            setTrackingStatus('No existe URL base de API configurada para el portal del provider.', 'danger');
            setStateText('Error de configuración');
            appendLog('Tracking detenido porque falta apiBaseUrl.');
            return;
        }

        if (!token) {
            setTrackingStatus('No existe token de sesión para enviar ubicación a la API.', 'danger');
            setStateText('Sin token');
            appendLog('Tracking detenido porque falta token de autenticación.');
            return;
        }

        sending = true;
        setTrackingStatus('Enviando ubicación actual del proveedor a la API...', 'info');
        setStateText('Sincronizando');

        const payload = {
            assistance_request_id: trackingConfig.requestId,
            lat: Number(position.coords.latitude),
            lng: Number(position.coords.longitude),
            accuracy: isFiniteNumber(position.coords.accuracy) ? Number(position.coords.accuracy) : null,
            heading: isFiniteNumber(position.coords.heading) ? Number(position.coords.heading) : null,
            speed: isFiniteNumber(position.coords.speed) ? Number(position.coords.speed) : null,
            recorded_at: new Date(position.timestamp || Date.now()).toISOString(),
        };

        try {
            const response = await fetch(apiBaseUrl + '/api/v1/provider/tracking', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + token,
                },
                body: JSON.stringify(payload),
            });

            const result = await response.json().catch(function () {
                return {};
            });

            if (!response.ok) {
                throw new Error(result.message || 'La API rechazó la actualización de ubicación.');
            }

            lastSentAt = Date.now();

            setTrackingStatus('Ubicación enviada correctamente. El cliente ya puede consultar esta posición.', 'success');
            setStateText('Activo');
            setCoordinatesText(payload.lat, payload.lng);
            setAccuracyText(payload.accuracy);
            setLastSyncText(payload.recorded_at);
            setCurrentCoordinatesPanel(payload.lat, payload.lng);
            appendLog('Ubicación sincronizada con éxito para la asistencia #' + trackingConfig.requestId + '.');
        } catch (error) {
            setTrackingStatus(error.message || 'No fue posible enviar la ubicación a la API.', 'danger');
            setStateText('Error al sincronizar');
            appendLog(error.message || 'Falló el envío de coordenadas.');
        } finally {
            sending = false;
        }
    }

    function handlePositionError(error) {
        let message = 'No fue posible obtener la ubicación del dispositivo.';

        if (error && typeof error.code !== 'undefined') {
            switch (error.code) {
                case 1:
                    message = 'El proveedor negó el permiso de ubicación.';
                    break;
                case 2:
                    message = 'La ubicación no está disponible en este momento.';
                    break;
                case 3:
                    message = 'Se agotó el tiempo al intentar obtener la ubicación.';
                    break;
            }
        }

        setTrackingStatus(message, 'danger');
        setStateText('GPS con error');
        appendLog(message);
    }

    function updateProviderVisuals(lat, lng, accuracy) {
        if (!map || !isFiniteNumber(lat) || !isFiniteNumber(lng)) {
            return;
        }

        if (!providerMarker) {
            providerMarker = L.marker([lat, lng], {
                icon: createMarkerIcon('provider')
            }).addTo(map);
        } else {
            providerMarker.setLatLng([lat, lng]);
        }

        providerMarker.bindPopup(
            '<strong>Tu posición actual</strong><br>' +
            escapeHtml(formatCoord(lat) + ', ' + formatCoord(lng))
        );

        if (isFiniteNumber(accuracy)) {
            if (!providerAccuracyCircle) {
                providerAccuracyCircle = L.circle([lat, lng], {
                    radius: accuracy,
                    weight: 1,
                    opacity: 0.55,
                    fillOpacity: 0.08,
                }).addTo(map);
            } else {
                providerAccuracyCircle.setLatLng([lat, lng]);
                providerAccuracyCircle.setRadius(accuracy);
            }
        }

        fitMapToMarkers();
        clearMapOverlay();
    }

    function fitMapToMarkers() {
        if (!map || !targetMarker || !providerMarker) {
            return;
        }

        const bounds = L.latLngBounds([
            targetMarker.getLatLng(),
            providerMarker.getLatLng()
        ]);

        map.fitBounds(bounds, {
            padding: [40, 40],
            maxZoom: 16
        });
    }

    function updateNavigationLinks() {
        const googleLink = document.getElementById('providerGoogleMapsLink');
        const wazeLink = document.getElementById('providerWazeLink');

        if (!googleLink || !wazeLink) {
            return;
        }

        if (!isFiniteNumber(trackingConfig.targetLat) || !isFiniteNumber(trackingConfig.targetLng)) {
            googleLink.href = '#';
            wazeLink.href = '#';
            googleLink.setAttribute('aria-disabled', 'true');
            wazeLink.setAttribute('aria-disabled', 'true');
            return;
        }

        const destination = trackingConfig.targetLat + ',' + trackingConfig.targetLng;

        let googleUrl = 'https://www.google.com/maps/dir/?api=1&destination=' + encodeURIComponent(destination) + '&travelmode=driving';

        if (latestProviderPosition && isFiniteNumber(latestProviderPosition.lat) && isFiniteNumber(latestProviderPosition.lng)) {
            const origin = latestProviderPosition.lat + ',' + latestProviderPosition.lng;
            googleUrl += '&origin=' + encodeURIComponent(origin);
        }

        const wazeUrl = 'https://waze.com/ul?ll=' +
            encodeURIComponent(destination) +
            '&navigate=yes';

        googleLink.href = googleUrl;
        wazeLink.href = wazeUrl;
        googleLink.removeAttribute('aria-disabled');
        wazeLink.removeAttribute('aria-disabled');
    }

    function createMarkerIcon(type) {
        return L.divIcon({
            className: 'provider-map-marker-wrapper',
            html: '<span class="provider-map-marker provider-map-marker--' + type + '"></span>',
            iconSize: [20, 20],
            iconAnchor: [10, 10],
            popupAnchor: [0, -10]
        });
    }

    function clearMapOverlay() {
        const overlay = document.getElementById('providerTacticalMapOverlay');

        if (!overlay) {
            return;
        }

        overlay.hidden = true;
        overlay.textContent = '';
    }

    function setTrackingStatus(message, tone) {
        const target = document.getElementById('providerTrackingStatus');

        if (!target) {
            return;
        }

        target.className = 'tracking-status';

        if (tone === 'success') {
            target.classList.add('tracking-status--success');
        } else if (tone === 'warning') {
            target.classList.add('tracking-status--warning');
        } else if (tone === 'danger') {
            target.classList.add('tracking-status--danger');
        }

        target.textContent = message;
    }

    function setStateText(value) {
        const target = document.getElementById('providerTrackingState');
        if (target) target.textContent = value || 'Pendiente';
    }

    function setCoordinatesText(lat, lng) {
        const target = document.getElementById('providerTrackingCoordinates');
        if (target) target.textContent = [formatCoord(lat), formatCoord(lng)].join(', ');
    }

    function setCurrentCoordinatesPanel(lat, lng) {
        const target = document.getElementById('providerCurrentCoordinates');
        if (target) target.textContent = [formatCoord(lat), formatCoord(lng)].join(', ');
    }

    function setLastSyncText(value) {
        const target = document.getElementById('providerTrackingLastSync');
        if (!target) {
            return;
        }

        if (!value) {
            target.textContent = 'Pendiente';
            return;
        }

        const date = new Date(value);

        if (Number.isNaN(date.getTime())) {
            target.textContent = 'Fecha inválida';
            return;
        }

        target.textContent = new Intl.DateTimeFormat('es-MX', {
            dateStyle: 'medium',
            timeStyle: 'short',
        }).format(date);
    }

    function setAccuracyText(value) {
        const target = document.getElementById('providerTrackingAccuracy');
        if (!target) {
            return;
        }

        if (!isFiniteNumber(value)) {
            target.textContent = 'No reportada';
            return;
        }

        target.textContent = Number(value).toFixed(2) + ' m';
    }

    function appendLog(message) {
        const target = document.getElementById('providerTrackingLog');

        if (!target) {
            return;
        }

        const prefix = new Intl.DateTimeFormat('es-MX', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
        }).format(new Date());

        target.textContent = '[' + prefix + '] ' + message;
    }

    function isFiniteNumber(value) {
        return typeof value === 'number' && Number.isFinite(value);
    }

    function formatCoord(value) {
        const number = Number(value);
        return Number.isFinite(number) ? number.toFixed(6) : 'N/D';
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
