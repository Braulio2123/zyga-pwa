@extends('user.layouts.app')

@push('page_styles')
<link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
/>

<style>
    .active-tracking-status {
        margin: 0 0 16px;
        padding: 14px 16px;
        border-radius: 18px;
        border: 1px solid rgba(15, 23, 42, 0.08);
        font-weight: 600;
        line-height: 1.5;
    }

    .active-tracking-status--info {
        background: rgba(37, 99, 235, 0.08);
        color: #1d4ed8;
    }

    .active-tracking-status--success {
        background: rgba(22, 163, 74, 0.08);
        color: #15803d;
    }

    .active-tracking-status--warning {
        background: rgba(245, 158, 11, 0.12);
        color: #b45309;
    }

    .active-tracking-status--danger {
        background: rgba(239, 68, 68, 0.10);
        color: #b91c1c;
    }

    .active-tracking-meta-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 14px;
        margin-bottom: 18px;
    }

    .active-tracking-meta-card {
        padding: 16px;
        border-radius: 18px;
        background: #f8fafc;
        border: 1px solid rgba(148, 163, 184, 0.18);
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .active-tracking-meta-card span {
        font-size: 0.82rem;
        color: #64748b;
    }

    .active-tracking-meta-card strong {
        font-size: 0.98rem;
        color: #0f172a;
        line-height: 1.5;
    }

    .active-tracking-map-shell {
        position: relative;
        border-radius: 24px;
        overflow: hidden;
        border: 1px solid rgba(15, 23, 42, 0.08);
        background: #e2e8f0;
    }

    .active-tracking-map {
        width: 100%;
        min-height: 420px;
    }

    .active-tracking-map-overlay {
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

    .active-tracking-marker-wrapper {
        background: transparent;
        border: 0;
    }

    .active-tracking-marker {
        display: block;
        width: 20px;
        height: 20px;
        border-radius: 999px;
        border: 3px solid #fff;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.24);
    }

    .active-tracking-marker--client {
        background: #2563eb;
    }

    .active-tracking-marker--provider {
        background: #f97316;
    }

    @media (max-width: 768px) {
        .active-tracking-map {
            min-height: 320px;
        }
    }
</style>
@endpush

@section('content')
<section class="panel hero-panel hero-panel--compact">
    <div>
        <p class="hero-panel__eyebrow">Servicio activo</p>
        <h2>Da seguimiento a tu asistencia en tiempo real.</h2>
        <p>
            Aquí podrás consultar el estado actual de tu solicitud, revisar el timeline del servicio,
            ver el mapa de seguimiento y cancelar únicamente cuando el flujo todavía lo permita.
        </p>
    </div>
</section>

<section class="panel">
    <div class="section-head">
        <h3>Mapa de seguimiento</h3>
        <span class="section-pill">Tracking</span>
    </div>

    <div class="helper-note">
        Este bloque consulta la solicitud activa del cliente y usa el endpoint de tracking para mostrar
        la ubicación registrada de la asistencia y, cuando exista, la última posición enviada por el proveedor.
    </div>

    <div id="activeTrackingStatus" class="active-tracking-status active-tracking-status--info">
        Preparando seguimiento en tiempo real...
    </div>

    <div id="activeTrackingMeta" class="active-tracking-meta-grid">
        <article class="empty-state">Cargando datos operativos del seguimiento...</article>
    </div>

    <div class="active-tracking-map-shell">
        <div id="activeTrackingMap" class="active-tracking-map"></div>
        <div id="activeTrackingMapOverlay" class="active-tracking-map-overlay">
            Buscando la ubicación de tu solicitud...
        </div>
    </div>
</section>

<section class="grid-two">
    <article class="panel">
        <div class="section-head">
            <h3>Resumen del servicio</h3>
            <span class="section-pill">Seguimiento</span>
        </div>
        <div class="helper-note">
            Este bloque muestra la solicitud activa del cliente y su estado más reciente recuperado desde la API.
        </div>
        <div id="activeRequestSummary" class="stack-list">
            <article class="empty-state">Buscando tu solicitud activa...</article>
        </div>
    </article>

    <article class="panel">
        <div class="section-head">
            <h3>Consideraciones operativas</h3>
            <span class="section-pill">Flujo</span>
        </div>

        <div class="stack-list">
            <article class="card-row">
                <h4 class="card-row__title">Una solicitud activa a la vez</h4>
                <p class="card-row__meta">
                    Mientras esta asistencia siga abierta, el sistema no permitirá generar una nueva.
                </p>
            </article>

            <article class="card-row">
                <h4 class="card-row__title">Actualización periódica</h4>
                <p class="card-row__meta">
                    El mapa consulta cambios automáticamente cada pocos segundos y además puedes refrescar manualmente.
                </p>
            </article>

            <article class="card-row">
                <h4 class="card-row__title">Cancelación controlada</h4>
                <p class="card-row__meta">
                    La cancelación solo debe usarse cuando el estado actual de la solicitud todavía lo permita.
                </p>
            </article>
        </div>
    </article>
</section>

<section class="panel">
    <div class="section-head">
        <h3>Timeline del servicio</h3>
        <button type="button" id="activeReloadButton" class="button button--ghost">Actualizar</button>
    </div>

    <div class="helper-note">
        El timeline refleja los movimientos registrados por la operación: creación, aceptación, asignación,
        avance, llegada al sitio, finalización o cancelación.
    </div>

    <div id="activeTimeline" class="timeline-list">
        <article class="empty-state">Cargando movimientos del servicio...</article>
    </div>
</section>

<section class="panel">
    <div class="section-head">
        <h3>Cancelar solicitud</h3>
        <span class="section-pill">Gestión</span>
    </div>

    <div class="helper-note">
        Si decides cancelar, registra un motivo breve y claro. La API validará si la cancelación
        todavía está permitida según el estado actual de la asistencia.
    </div>

    <form id="cancelRequestForm" class="form-grid" autocomplete="off">
        <label class="form-field form-field--full">
            <span>Motivo de cancelación</span>
            <textarea
                id="cancelReason"
                name="cancel_reason"
                rows="3"
                placeholder="Ej. Ya no requiero el servicio o resolví la situación por otro medio"
            ></textarea>
        </label>

        <div class="form-actions form-field--full">
            <button type="submit" id="cancelSubmitButton" class="button button--danger">
                Cancelar solicitud
            </button>
        </div>
    </form>
</section>
@endsection

@push('page_scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
(function () {
    const POLL_INTERVAL_MS = 8000;
    let map = null;
    let clientMarker = null;
    let providerMarker = null;
    let refreshTimer = null;
    let requestInFlight = false;

    function boot() {
        const app = window.ZYGA_CLIENT_APP || {};

        if ((app.page || '') !== 'active') {
            return;
        }

        if (!app.apiBaseUrl) {
            renderTrackingStatus('La URL base de la API no está configurada en la sesión del cliente.', 'danger');
            renderTrackingMeta([]);
            setMapOverlay('No se pudo inicializar el seguimiento porque falta la URL base de la API.');
            return;
        }

        if (!window.L) {
            renderTrackingStatus('No fue posible cargar la librería del mapa.', 'danger');
            renderTrackingMeta([]);
            setMapOverlay('Leaflet no cargó correctamente en esta vista.');
            return;
        }

        bindReloadButton();
        renderTrackingStatus('Consultando datos del seguimiento...', 'info');
        renderTrackingMeta([]);
        loadTracking();
        startPolling();

        document.addEventListener('visibilitychange', handleVisibilityChange);
        window.addEventListener('beforeunload', stopPolling, { once: true });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot, { once: true });
    } else {
        boot();
    }

    function bindReloadButton() {
        const reloadButton = document.getElementById('activeReloadButton');

        if (!reloadButton || reloadButton.dataset.trackingBound === '1') {
            return;
        }

        reloadButton.dataset.trackingBound = '1';
        reloadButton.addEventListener('click', function () {
            loadTracking();
        });
    }

    function startPolling() {
        stopPolling();

        refreshTimer = window.setInterval(function () {
            if (document.visibilityState === 'visible') {
                loadTracking();
            }
        }, POLL_INTERVAL_MS);
    }

    function stopPolling() {
        if (refreshTimer) {
            window.clearInterval(refreshTimer);
            refreshTimer = null;
        }
    }

    function handleVisibilityChange() {
        if (document.visibilityState === 'visible') {
            loadTracking();
            startPolling();
        } else {
            stopPolling();
        }
    }

    async function loadTracking() {
        const app = window.ZYGA_CLIENT_APP || {};

        if (requestInFlight) {
            return;
        }

        requestInFlight = true;

        try {
            const requestsPayload = await api(app, '/api/v1/client/assistance-requests');
            const requests = extractList(requestsPayload && requestsPayload.data, ['requests', 'items']);
            const activeRequest = findActiveRequest(requests);

            if (!activeRequest) {
                renderTrackingStatus('No hay una asistencia activa para mostrar en el mapa.', 'warning');
                renderTrackingMeta([]);
                setMapOverlay('Crea o recupera una solicitud activa para habilitar el seguimiento.');
                return;
            }

            const trackingPayload = await api(app, '/api/v1/client/assistance-requests/' + activeRequest.id + '/tracking');
            renderTracking(trackingPayload ? trackingPayload.data : null, activeRequest);
        } catch (error) {
            renderTrackingStatus(error.message || 'No fue posible cargar el seguimiento del servicio.', 'danger');
            renderTrackingMeta([]);
            setMapOverlay(error.message || 'Ocurrió un error al consultar el seguimiento.');
        } finally {
            requestInFlight = false;
        }
    }

    function renderTracking(data, fallbackRequest) {
        const request = (data && data.request) || fallbackRequest || {};
        const provider = data && data.provider ? data.provider : null;
        const providerLocation = data && data.provider_location ? data.provider_location : null;

        const requestLat = toNumber(request.lat);
        const requestLng = toNumber(request.lng);
        const providerLat = toNumber(providerLocation && providerLocation.lat);
        const providerLng = toNumber(providerLocation && providerLocation.lng);

        renderTrackingMeta([
            { label: 'Estado', value: statusLabel(request.status) },
            { label: 'Folio', value: request.public_id || ('#' + (request.id || 'N/D')) },
            { label: 'Proveedor', value: provider && provider.display_name ? provider.display_name : 'Aún sin proveedor asignado' },
            {
                label: 'Última actualización',
                value: providerLocation && providerLocation.recorded_at
                    ? formatDateTime(providerLocation.recorded_at)
                    : 'Sin ubicación del proveedor todavía'
            },
            { label: 'Dirección', value: request.pickup_address || 'Sin dirección capturada' }
        ]);

        if (requestLat === null || requestLng === null) {
            renderTrackingStatus('La solicitud existe, pero no tiene coordenadas válidas del cliente.', 'danger');
            setMapOverlay('La asistencia activa no tiene latitud y longitud válidas.');
            return;
        }

        ensureMap([requestLat, requestLng]);
        updateClientMarker(requestLat, requestLng, request.pickup_address);

        if (provider && providerLat !== null && providerLng !== null) {
            updateProviderMarker(providerLat, providerLng, provider);
            fitMapToMarkers(requestLat, requestLng, providerLat, providerLng);
            renderTrackingStatus(
                'Proveedor asignado: ' + (provider.display_name || 'Proveedor') + ' · ' + freshnessLabel(providerLocation && providerLocation.recorded_at),
                'success'
            );
            clearMapOverlay();
            return;
        }

        if (map) {
            map.setView([requestLat, requestLng], 15);
        }

        if (provider) {
            renderTrackingStatus(
                'Proveedor asignado: ' + (provider.display_name || 'Proveedor') + ' · aún no comparte ubicación.',
                'warning'
            );
            setMapOverlay('El proveedor ya fue asignado, pero todavía no envía su ubicación en tiempo real.');
            return;
        }

        renderTrackingStatus('La solicitud sigue activa, pero aún no hay proveedor asignado.', 'info');
        setMapOverlay('Cuando un proveedor acepte la solicitud, aquí verás su posición actual.');
    }

    function ensureMap(center) {
        const mapElement = document.getElementById('activeTrackingMap');

        if (!mapElement) {
            return;
        }

        if (!map) {
            map = L.map(mapElement).setView(center, 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);
        }

        setTimeout(function () {
            if (map) {
                map.invalidateSize();
            }
        }, 250);
    }

    function updateClientMarker(lat, lng, address) {
        if (!map) {
            return;
        }

        const popup = `
            <div>
                <strong>Tu ubicación registrada</strong><br>
                <span>${escapeHtml(address || 'Sin dirección capturada')}</span><br>
                <small>${escapeHtml(lat.toFixed(6) + ', ' + lng.toFixed(6))}</small>
            </div>
        `;

        if (!clientMarker) {
            clientMarker = L.marker([lat, lng], {
                icon: createMarkerIcon('client')
            }).addTo(map);
        } else {
            clientMarker.setLatLng([lat, lng]);
        }

        clientMarker.bindPopup(popup);
    }

    function updateProviderMarker(lat, lng, provider) {
        if (!map) {
            return;
        }

        const popup = `
            <div>
                <strong>${escapeHtml((provider && provider.display_name) || 'Proveedor')}</strong><br>
                <span>Última ubicación registrada del proveedor</span><br>
                <small>${escapeHtml(lat.toFixed(6) + ', ' + lng.toFixed(6))}</small>
            </div>
        `;

        if (!providerMarker) {
            providerMarker = L.marker([lat, lng], {
                icon: createMarkerIcon('provider')
            }).addTo(map);
        } else {
            providerMarker.setLatLng([lat, lng]);
        }

        providerMarker.bindPopup(popup);
    }

    function fitMapToMarkers(requestLat, requestLng, providerLat, providerLng) {
        if (!map) {
            return;
        }

        const bounds = L.latLngBounds([
            [requestLat, requestLng],
            [providerLat, providerLng]
        ]);

        map.fitBounds(bounds, {
            padding: [40, 40],
            maxZoom: 16
        });
    }

    function createMarkerIcon(type) {
        return L.divIcon({
            className: 'active-tracking-marker-wrapper',
            html: '<span class="active-tracking-marker active-tracking-marker--' + type + '"></span>',
            iconSize: [20, 20],
            iconAnchor: [10, 10],
            popupAnchor: [0, -10]
        });
    }

    function renderTrackingMeta(items) {
        const target = document.getElementById('activeTrackingMeta');

        if (!target) {
            return;
        }

        if (!items.length) {
            target.innerHTML = `
                <article class="empty-state">
                    Aún no hay datos suficientes del seguimiento. En cuanto exista una solicitud activa, aquí verás su estado, folio y proveedor.
                </article>
            `;
            return;
        }

        target.innerHTML = items.map(function (item) {
            return `
                <article class="active-tracking-meta-card">
                    <span>${escapeHtml(item.label)}</span>
                    <strong>${escapeHtml(item.value || 'N/D')}</strong>
                </article>
            `;
        }).join('');
    }

    function renderTrackingStatus(message, tone) {
        const target = document.getElementById('activeTrackingStatus');

        if (!target) {
            return;
        }

        target.className = 'active-tracking-status active-tracking-status--' + (tone || 'info');
        target.textContent = message;
    }

    function setMapOverlay(message) {
        const overlay = document.getElementById('activeTrackingMapOverlay');

        if (!overlay) {
            return;
        }

        overlay.hidden = false;
        overlay.textContent = message;
    }

    function clearMapOverlay() {
        const overlay = document.getElementById('activeTrackingMapOverlay');

        if (!overlay) {
            return;
        }

        overlay.hidden = true;
        overlay.textContent = '';
    }

    async function api(app, path, options = {}) {
        const headers = {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        };

        if (app.token) {
            headers.Authorization = 'Bearer ' + app.token;
        }

        const response = await fetch(app.apiBaseUrl + path, {
            method: options.method || 'GET',
            headers: headers,
            body: options.body || null
        });

        const payload = await response.json().catch(function () {
            return {};
        });

        if (!response.ok) {
            throw new Error(
                payload.message ||
                payload.error ||
                'La API no respondió correctamente.'
            );
        }

        return payload;
    }

    function extractList(data, preferredKeys) {
        if (Array.isArray(data)) {
            return data;
        }

        if (!data || typeof data !== 'object') {
            return [];
        }

        for (const key of preferredKeys || []) {
            if (Array.isArray(data[key])) {
                return data[key];
            }
        }

        for (const key of ['requests', 'items', 'data']) {
            if (Array.isArray(data[key])) {
                return data[key];
            }
        }

        return [];
    }

    function findActiveRequest(requests) {
        return requests.find(function (item) {
            const status = normalizeStatus(item && item.status);
            return status !== 'completed' && status !== 'cancelled';
        }) || null;
    }

    function normalizeStatus(status) {
        return String(status || '').trim().toLowerCase();
    }

    function statusLabel(status) {
        const value = normalizeStatus(status);

        const labels = {
            created: 'Creada',
            assigned: 'Asignada',
            in_progress: 'En proceso',
            completed: 'Completada',
            cancelled: 'Cancelada'
        };

        return labels[value] || status || 'Sin estado';
    }

    function freshnessLabel(recordedAt) {
        if (!recordedAt) {
            return 'sin registro reciente';
        }

        const now = Date.now();
        const recorded = new Date(recordedAt).getTime();

        if (Number.isNaN(recorded)) {
            return 'última ubicación con fecha inválida';
        }

        const diffSeconds = Math.max(0, Math.round((now - recorded) / 1000));

        if (diffSeconds <= 15) {
            return 'ubicación actualizada hace unos segundos';
        }

        if (diffSeconds < 60) {
            return 'última ubicación hace ' + diffSeconds + ' s';
        }

        const diffMinutes = Math.round(diffSeconds / 60);

        if (diffMinutes < 60) {
            return 'última ubicación hace ' + diffMinutes + ' min';
        }

        const diffHours = Math.round(diffMinutes / 60);
        return 'última ubicación hace ' + diffHours + ' h';
    }

    function formatDateTime(value) {
        if (!value) {
            return 'Sin fecha';
        }

        const date = new Date(value);

        if (Number.isNaN(date.getTime())) {
            return 'Fecha inválida';
        }

        return new Intl.DateTimeFormat('es-MX', {
            dateStyle: 'medium',
            timeStyle: 'short'
        }).format(date);
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
