@extends('user.layouts.app')

@push('page_styles')
<link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
/>

<style>
    .tracking-hero {
        display: grid;
        gap: 14px;
    }

    .tracking-hero__copy {
        display: grid;
        gap: 6px;
    }

    .tracking-hero__copy h2 {
        margin: 0;
        color: #0f172a;
        line-height: 1.08;
        font-size: clamp(1.35rem, 2vw, 1.9rem);
    }

    .tracking-hero__copy p {
        margin: 0;
        color: #475569;
        line-height: 1.55;
        font-size: 0.95rem;
    }

    .tracking-status-card {
        display: grid;
        gap: 14px;
        padding: 18px;
        border-radius: 24px;
        border: 1px solid rgba(15, 23, 42, 0.08);
        background:
            radial-gradient(circle at top right, rgba(249, 115, 22, 0.14), transparent 40%),
            radial-gradient(circle at bottom left, rgba(37, 99, 235, 0.12), transparent 45%),
            #ffffff;
    }

    .tracking-status-card__top {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: center;
        justify-content: space-between;
    }

    .tracking-status-card__title {
        display: grid;
        gap: 4px;
    }

    .tracking-status-card__title h3 {
        margin: 0;
        color: #0f172a;
        line-height: 1.2;
    }

    .tracking-status-card__title p {
        margin: 0;
        color: #64748b;
        font-size: 0.9rem;
    }

    .tracking-status-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 12px;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 800;
        white-space: nowrap;
    }

    .tracking-status-pill--info {
        background: rgba(37, 99, 235, 0.10);
        color: #1d4ed8;
    }

    .tracking-status-pill--success {
        background: rgba(22, 163, 74, 0.10);
        color: #15803d;
    }

    .tracking-status-pill--warning {
        background: rgba(245, 158, 11, 0.12);
        color: #b45309;
    }

    .tracking-status-pill--danger {
        background: rgba(239, 68, 68, 0.10);
        color: #b91c1c;
    }

    .tracking-status-message {
        padding: 14px 16px;
        border-radius: 18px;
        background: rgba(15, 23, 42, 0.04);
        border: 1px solid rgba(148, 163, 184, 0.16);
        color: #475569;
        line-height: 1.55;
    }

    .tracking-summary-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .tracking-summary-item {
        padding: 14px 16px;
        border-radius: 18px;
        border: 1px solid rgba(148, 163, 184, 0.16);
        background: #f8fafc;
        display: grid;
        gap: 6px;
    }

    .tracking-summary-item span {
        color: #64748b;
        font-size: 0.8rem;
    }

    .tracking-summary-item strong {
        color: #0f172a;
        line-height: 1.45;
        word-break: break-word;
        font-size: 0.96rem;
    }

    .tracking-map-shell {
        position: relative;
        border-radius: 24px;
        overflow: hidden;
        border: 1px solid rgba(15, 23, 42, 0.08);
        background: #e2e8f0;
    }

    .tracking-map {
        width: 100%;
        min-height: 400px;
    }

    .tracking-map-overlay {
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

    .tracking-map-marker-wrapper {
        background: transparent;
        border: 0;
    }

    .tracking-map-marker {
        display: block;
        width: 20px;
        height: 20px;
        border-radius: 999px;
        border: 3px solid #fff;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.24);
    }

    .tracking-map-marker--me {
        background: #2563eb;
    }

    .tracking-map-marker--provider {
        background: #f97316;
    }

    .tracking-feed {
        display: grid;
        gap: 12px;
    }

    .tracking-feed-item {
        padding: 15px;
        border-radius: 18px;
        border: 1px solid rgba(148, 163, 184, 0.16);
        background: #ffffff;
        display: grid;
        gap: 6px;
    }

    .tracking-feed-item__top {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        align-items: center;
    }

    .tracking-feed-item__title {
        margin: 0;
        color: #0f172a;
        font-size: 0.94rem;
        line-height: 1.35;
    }

    .tracking-feed-item__time {
        color: #64748b;
        font-size: 0.78rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .tracking-feed-item__body {
        margin: 0;
        color: #475569;
        line-height: 1.5;
        font-size: 0.9rem;
    }

    .tracking-empty {
        padding: 18px;
        border-radius: 18px;
        border: 1px dashed rgba(148, 163, 184, 0.26);
        background: rgba(248, 250, 252, 0.9);
        color: #64748b;
        text-align: center;
        line-height: 1.55;
    }

    .tracking-cancel-card {
        display: grid;
        gap: 14px;
        padding: 18px;
        border-radius: 22px;
        border: 1px solid rgba(239, 68, 68, 0.16);
        background: rgba(239, 68, 68, 0.04);
    }

    .tracking-cancel-card[hidden] {
        display: none;
    }

    .tracking-cancel-card__title {
        margin: 0;
        color: #991b1b;
    }

    .tracking-cancel-card__text {
        margin: 0;
        color: #7f1d1d;
        line-height: 1.55;
        font-size: 0.92rem;
    }

    @media (max-width: 720px) {
        .tracking-summary-grid {
            grid-template-columns: 1fr;
        }

        .tracking-map {
            min-height: 320px;
        }
    }

    @media (max-width: 560px) {
        .tracking-status-card,
        .tracking-cancel-card {
            padding: 15px;
        }

        .tracking-summary-item,
        .tracking-feed-item {
            padding: 13px;
        }

        .tracking-status-card__top,
        .tracking-feed-item__top {
            align-items: flex-start;
        }

        .tracking-hero__copy h2 {
            font-size: 1.25rem;
        }

        .tracking-hero__copy p,
        .tracking-feed-item__body {
            font-size: 0.88rem;
        }
    }
</style>
@endpush

@section('content')
<section class="panel tracking-hero">
    <div class="tracking-hero__copy">
        <p class="hero-panel__eyebrow">Seguimiento</p>
        <h2 id="trackingHeroTitle">Revisando tu servicio…</h2>
        <p id="trackingHeroText">En esta pantalla puedes ver cómo va tu ayuda y los cambios más recientes.</p>
    </div>

    <div class="actions-inline">
        <button type="button" id="trackingRefreshButton" class="button button--primary">Actualizar</button>
        <a href="{{ route('user.historial') }}" class="button button--ghost">Historial</a>
        <a href="{{ route('user.pagos') }}" class="button button--secondary">Pagos</a>
    </div>
</section>

<section class="panel">
    <div class="section-head">
        <h3>Estado actual</h3>
        <span class="section-pill">En tiempo real</span>
    </div>

    <div id="trackingStatusCard" class="tracking-status-card">
        <div class="tracking-status-card__top">
            <div class="tracking-status-card__title">
                <h3 id="trackingServiceName">Buscando tu servicio…</h3>
                <p id="trackingFolio">Folio: pendiente</p>
            </div>

            <span id="trackingStatusPill" class="tracking-status-pill tracking-status-pill--info">
                Cargando
            </span>
        </div>

        <div id="trackingStatusMessage" class="tracking-status-message">
            Estamos consultando la información de tu servicio.
        </div>

        <div id="trackingSummaryGrid" class="tracking-summary-grid">
            <article class="tracking-summary-item">
                <span>Dirección</span>
                <strong>Pendiente</strong>
            </article>
            <article class="tracking-summary-item">
                <span>Referencia</span>
                <strong>Pendiente</strong>
            </article>
            <article class="tracking-summary-item">
                <span>Proveedor</span>
                <strong>Pendiente</strong>
            </article>
            <article class="tracking-summary-item">
                <span>Pago</span>
                <strong>Pendiente</strong>
            </article>
        </div>
    </div>
</section>

<section class="panel">
    <div class="section-head">
        <h3>Ubicación</h3>
        <span class="section-pill">Mapa</span>
    </div>

    <div class="tracking-map-shell">
        <div id="trackingMap" class="tracking-map"></div>
        <div id="trackingMapOverlay" class="tracking-map-overlay">
            Estamos preparando el mapa de tu servicio.
        </div>
    </div>
</section>

<section class="grid-two">
    <article class="panel">
        <div class="section-head">
            <h3>Movimientos recientes</h3>
            <span class="section-pill">Actualizaciones</span>
        </div>

        <div id="trackingFeed" class="tracking-feed">
            <article class="tracking-empty">Cargando movimientos de tu servicio…</article>
        </div>
    </article>

    <article class="panel">
        <div class="section-head">
            <h3>Lo que necesitas saber</h3>
            <span class="section-pill">Ayuda rápida</span>
        </div>

        <div class="tracking-feed">
            <article class="tracking-feed-item">
                <div class="tracking-feed-item__top">
                    <h4 class="tracking-feed-item__title">Tu ubicación</h4>
                </div>
                <p class="tracking-feed-item__body">
                    El punto azul es la ubicación de tu solicitud. El punto naranja aparecerá cuando el proveedor comparta su ubicación.
                </p>
            </article>

            <article class="tracking-feed-item">
                <div class="tracking-feed-item__top">
                    <h4 class="tracking-feed-item__title">Actualización automática</h4>
                </div>
                <p class="tracking-feed-item__body">
                    Esta vista se actualiza sola cada pocos segundos. También puedes usar el botón “Actualizar”.
                </p>
            </article>

            <article class="tracking-feed-item">
                <div class="tracking-feed-item__top">
                    <h4 class="tracking-feed-item__title">Pago</h4>
                </div>
                <p class="tracking-feed-item__body">
                    Aquí verás si tu pago sigue pendiente, está en revisión o ya fue confirmado.
                </p>
            </article>
        </div>
    </article>
</section>

<section id="trackingCancelCard" class="tracking-cancel-card" hidden>
    <h3 class="tracking-cancel-card__title">Cancelar servicio</h3>
    <p class="tracking-cancel-card__text">
        Si ya no necesitas la ayuda, puedes cancelar mientras el servicio aún lo permita.
    </p>

    <form id="trackingCancelForm" class="form-grid" autocomplete="off">
        <label class="form-field form-field--full">
            <span>Motivo</span>
            <textarea
                id="trackingCancelReason"
                name="cancel_reason"
                rows="3"
                placeholder="Ej. Ya no necesito el servicio"
            ></textarea>
        </label>

        <div class="form-actions form-field--full">
            <button type="submit" id="trackingCancelSubmit" class="button button--danger">
                Cancelar servicio
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
    let myMarker = null;
    let providerMarker = null;
    let refreshTimer = null;
    let currentRequest = null;
    let loading = false;

    function boot() {
        const app = window.ZYGA_CLIENT_APP || {};

        if ((app.page || '') !== 'active') {
            return;
        }

        bindEvents();
        loadTracking();
        startPolling();

        document.addEventListener('visibilitychange', handleVisibilityChange);
        window.addEventListener('beforeunload', stopPolling, { once: true });
    }

    function bindEvents() {
        const refreshButton = document.getElementById('trackingRefreshButton');
        const cancelForm = document.getElementById('trackingCancelForm');

        if (refreshButton) {
            refreshButton.addEventListener('click', function () {
                loadTracking(true);
            });
        }

        if (cancelForm) {
            cancelForm.addEventListener('submit', handleCancelSubmit);
        }
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

    async function loadTracking(showLoadingMessage) {
        const app = window.ZYGA_CLIENT_APP || {};

        if (!app.apiBaseUrl || !app.token) {
            setHero(
                'No pudimos abrir tu seguimiento',
                'Vuelve a iniciar sesión para consultar el estado de tu servicio.'
            );
            setStatusCard('No disponible', 'danger', 'No fue posible identificar tu sesión.');
            setMapOverlay('No se pudo abrir el mapa porque la sesión no está lista.');
            renderEmptyFeed('No se pudieron cargar los movimientos.');
            hideCancelCard();
            return;
        }

        if (loading) {
            return;
        }

        loading = true;

        if (showLoadingMessage) {
            setStatusCard('Actualizando', 'info', 'Estamos consultando los datos más recientes de tu servicio.');
        }

        try {
            const requestsPayload = await api(app, '/api/v1/client/assistance-requests');
            const requests = extractList(requestsPayload && requestsPayload.data, ['requests', 'items']);
            const activeRequest = findActiveRequest(requests);

            if (!activeRequest) {
                currentRequest = null;
                setHero(
                    'No tienes un servicio en proceso',
                    'Cuando envíes una solicitud, aquí podrás ver el estado y la ubicación de tu ayuda.'
                );
                setStatusCard('Sin servicio activo', 'warning', 'En este momento no encontramos una solicitud abierta.');
                setSummaryGrid([
                    ['Dirección', 'Sin servicio activo'],
                    ['Referencia', 'Sin servicio activo'],
                    ['Proveedor', 'Sin servicio activo'],
                    ['Pago', 'Sin servicio activo'],
                ]);
                setMapOverlay('Cuando tengas un servicio activo, aquí verás tu ubicación y la del proveedor.');
                resetProviderMarker();
                resetMyMarker();
                renderEmptyFeed('Todavía no hay movimientos para mostrar.');
                hideCancelCard();
                return;
            }

            currentRequest = activeRequest;

            const [trackingPayload, timelinePayload] = await Promise.all([
                api(app, '/api/v1/client/assistance-requests/' + activeRequest.id + '/tracking'),
                api(app, '/api/v1/client/assistance-requests/' + activeRequest.id + '/timeline'),
            ]);

            renderTracking(trackingPayload ? trackingPayload.data : null, timelinePayload ? timelinePayload.data : null, activeRequest);
        } catch (error) {
            setHero(
                'No pudimos actualizar tu seguimiento',
                'Intenta nuevamente en unos segundos. Si el problema sigue, recarga la página.'
            );
            setStatusCard('Error', 'danger', error.message || 'No fue posible consultar tu servicio.');
            setMapOverlay(error.message || 'No fue posible cargar el mapa en este momento.');
            renderEmptyFeed('No se pudieron cargar los movimientos de tu servicio.');
            hideCancelCard();
        } finally {
            loading = false;
        }
    }

    function renderTracking(trackingData, timelineData, fallbackRequest) {
        const request = (trackingData && trackingData.request) || fallbackRequest || {};
        const provider = trackingData && trackingData.provider ? trackingData.provider : null;
        const providerLocation = trackingData && trackingData.provider_location ? trackingData.provider_location : null;

        const requestLat = toNumber(request.lat);
        const requestLng = toNumber(request.lng);
        const providerLat = toNumber(providerLocation && providerLocation.lat);
        const providerLng = toNumber(providerLocation && providerLocation.lng);

        const readableStatus = statusLabel(request.status);
        const serviceName = (request.service && request.service.name) ? request.service.name : 'Servicio en proceso';
        const folio = request.public_id || ('#' + (request.id || '—'));
        const paymentState = paymentStatusLabel(fallbackRequest.payment_status || request.payment_status || 'pending');
        const providerName = provider && provider.display_name ? provider.display_name : 'Aún sin proveedor asignado';

        setHero(
            serviceName,
            statusMessageForUser(request.status, !!provider, !!providerLocation)
        );

        setStatusCard(readableStatus, statusTone(request.status, !!provider, !!providerLocation), statusMessageForUser(request.status, !!provider, !!providerLocation));
        setServiceHeader(serviceName, folio);
        setSummaryGrid([
            ['Dirección', request.pickup_address || 'Sin dirección registrada'],
            ['Referencia', request.pickup_reference || 'Sin referencia'],
            ['Proveedor', providerName],
            ['Pago', paymentState],
        ]);

        renderTimeline(timelineData);

        if (requestLat === null || requestLng === null) {
            setMapOverlay('No pudimos ubicar el punto de tu solicitud en el mapa.');
            hideCancelCard();
            return;
        }

        ensureMap([requestLat, requestLng]);
        updateMyMarker(requestLat, requestLng, request.pickup_address, request.pickup_reference);

        if (provider && providerLat !== null && providerLng !== null) {
            updateProviderMarker(providerLat, providerLng, provider);
            fitToBothMarkers(requestLat, requestLng, providerLat, providerLng);
            clearMapOverlay();
        } else {
            resetProviderMarker();

            if (provider) {
                if (map) {
                    map.setView([requestLat, requestLng], 15);
                }

                setMapOverlay('Tu proveedor ya fue asignado. En cuanto comparta su ubicación, la verás aquí.');
            } else {
                if (map) {
                    map.setView([requestLat, requestLng], 15);
                }

                setMapOverlay('Todavía estamos buscando quién atienda tu servicio.');
            }
        }

        if (canCancel(request.status)) {
            showCancelCard();
        } else {
            hideCancelCard();
        }
    }

    function renderTimeline(timelineData) {
        const feed = document.getElementById('trackingFeed');

        if (!feed) {
            return;
        }

        const history = Array.isArray(timelineData && timelineData.history) ? timelineData.history : [];
        const events = Array.isArray(timelineData && timelineData.events) ? timelineData.events : [];

        const merged = [];

        history.forEach(function (item) {
            merged.push({
                type: 'history',
                title: statusLabel(item.status),
                text: historyMessage(item.status),
                time: item.created_at || item.updated_at || null,
                sort: item.created_at || item.updated_at || '',
            });
        });

        events.forEach(function (item) {
            merged.push({
                type: 'event',
                title: eventTitle(item.event_type),
                text: eventMessage(item.event_type, item.event_data || {}),
                time: item.created_at || item.updated_at || null,
                sort: item.created_at || item.updated_at || '',
            });
        });

        merged.sort(function (a, b) {
            return String(b.sort || '').localeCompare(String(a.sort || ''));
        });

        if (!merged.length) {
            renderEmptyFeed('Todavía no hay movimientos para mostrar.');
            return;
        }

        feed.innerHTML = merged.map(function (item) {
            return `
                <article class="tracking-feed-item">
                    <div class="tracking-feed-item__top">
                        <h4 class="tracking-feed-item__title">${escapeHtml(item.title)}</h4>
                        <span class="tracking-feed-item__time">${escapeHtml(formatDateTime(item.time))}</span>
                    </div>
                    <p class="tracking-feed-item__body">${escapeHtml(item.text)}</p>
                </article>
            `;
        }).join('');
    }

    function renderEmptyFeed(message) {
        const feed = document.getElementById('trackingFeed');

        if (!feed) {
            return;
        }

        feed.innerHTML = `<article class="tracking-empty">${escapeHtml(message)}</article>`;
    }

    function setHero(title, text) {
        const titleNode = document.getElementById('trackingHeroTitle');
        const textNode = document.getElementById('trackingHeroText');

        if (titleNode) {
            titleNode.textContent = title;
        }

        if (textNode) {
            textNode.textContent = text;
        }
    }

    function setServiceHeader(serviceName, folio) {
        const serviceNode = document.getElementById('trackingServiceName');
        const folioNode = document.getElementById('trackingFolio');

        if (serviceNode) {
            serviceNode.textContent = serviceName;
        }

        if (folioNode) {
            folioNode.textContent = 'Folio ' + folio;
        }
    }

    function setStatusCard(label, tone, message) {
        const pill = document.getElementById('trackingStatusPill');
        const msg = document.getElementById('trackingStatusMessage');

        if (pill) {
            pill.className = 'tracking-status-pill tracking-status-pill--' + (tone || 'info');
            pill.textContent = label;
        }

        if (msg) {
            msg.textContent = message;
        }
    }

    function setSummaryGrid(items) {
        const grid = document.getElementById('trackingSummaryGrid');

        if (!grid) {
            return;
        }

        grid.innerHTML = (items || []).map(function (item) {
            return `
                <article class="tracking-summary-item">
                    <span>${escapeHtml(item[0])}</span>
                    <strong>${escapeHtml(item[1] || 'No disponible')}</strong>
                </article>
            `;
        }).join('');
    }

    function ensureMap(center) {
        const mapElement = document.getElementById('trackingMap');

        if (!mapElement) {
            return;
        }

        if (!map) {
            map = L.map(mapElement).setView(center, 15);

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);
        }

        setTimeout(function () {
            if (map) {
                map.invalidateSize();
            }
        }, 220);
    }

    function updateMyMarker(lat, lng, address, reference) {
        if (!map) {
            return;
        }

        const popup = `
            <div>
                <strong>Tu ubicación</strong><br>
                <span>${escapeHtml(address || 'Sin dirección registrada')}</span><br>
                <small>${escapeHtml(reference || 'Sin referencia')}</small>
            </div>
        `;

        if (!myMarker) {
            myMarker = L.marker([lat, lng], {
                icon: markerIcon('me')
            }).addTo(map);
        } else {
            myMarker.setLatLng([lat, lng]);
        }

        myMarker.bindPopup(popup);
    }

    function updateProviderMarker(lat, lng, provider) {
        if (!map) {
            return;
        }

        const popup = `
            <div>
                <strong>${escapeHtml((provider && provider.display_name) || 'Proveedor')}</strong><br>
                <span>Última ubicación registrada</span>
            </div>
        `;

        if (!providerMarker) {
            providerMarker = L.marker([lat, lng], {
                icon: markerIcon('provider')
            }).addTo(map);
        } else {
            providerMarker.setLatLng([lat, lng]);
        }

        providerMarker.bindPopup(popup);
    }

    function resetMyMarker() {
        if (map && myMarker) {
            map.removeLayer(myMarker);
            myMarker = null;
        }
    }

    function resetProviderMarker() {
        if (map && providerMarker) {
            map.removeLayer(providerMarker);
            providerMarker = null;
        }
    }

    function fitToBothMarkers(requestLat, requestLng, providerLat, providerLng) {
        if (!map) {
            return;
        }

        const bounds = L.latLngBounds([
            [requestLat, requestLng],
            [providerLat, providerLng]
        ]);

        map.fitBounds(bounds, {
            padding: [34, 34],
            maxZoom: 16
        });
    }

    function markerIcon(type) {
        return L.divIcon({
            className: 'tracking-map-marker-wrapper',
            html: '<span class="tracking-map-marker tracking-map-marker--' + type + '"></span>',
            iconSize: [20, 20],
            iconAnchor: [10, 10],
            popupAnchor: [0, -10]
        });
    }

    function setMapOverlay(message) {
        const overlay = document.getElementById('trackingMapOverlay');

        if (!overlay) {
            return;
        }

        overlay.hidden = false;
        overlay.textContent = message;
    }

    function clearMapOverlay() {
        const overlay = document.getElementById('trackingMapOverlay');

        if (!overlay) {
            return;
        }

        overlay.hidden = true;
        overlay.textContent = '';
    }

    function canCancel(status) {
        const value = normalize(status);
        return ['created', 'accepted', 'assigned'].includes(value);
    }

    function showCancelCard() {
        const card = document.getElementById('trackingCancelCard');
        if (card) {
            card.hidden = false;
        }
    }

    function hideCancelCard() {
        const card = document.getElementById('trackingCancelCard');
        if (card) {
            card.hidden = true;
        }
    }

    async function handleCancelSubmit(event) {
        event.preventDefault();

        const app = window.ZYGA_CLIENT_APP || {};
        const reasonInput = document.getElementById('trackingCancelReason');
        const submitButton = document.getElementById('trackingCancelSubmit');
        const reason = String(reasonInput && reasonInput.value ? reasonInput.value : '').trim();

        if (!currentRequest || !currentRequest.id) {
            alert('No encontramos un servicio activo para cancelar.');
            return;
        }

        if (!reason) {
            alert('Escribe un motivo breve para cancelar el servicio.');
            return;
        }

        if (!confirm('¿Seguro que quieres cancelar este servicio?')) {
            return;
        }

        if (submitButton) {
            submitButton.disabled = true;
        }

        try {
            await api(app, '/api/v1/client/assistance-requests/' + currentRequest.id + '/cancel', {
                method: 'PATCH',
                body: JSON.stringify({
                    cancel_reason: reason
                })
            });

            if (reasonInput) {
                reasonInput.value = '';
            }

            loadTracking(true);
            alert('Tu servicio fue cancelado correctamente.');
        } catch (error) {
            alert(error.message || 'No fue posible cancelar el servicio.');
        } finally {
            if (submitButton) {
                submitButton.disabled = false;
            }
        }
    }

    async function api(app, path, options = {}) {
        const response = await fetch(app.apiBaseUrl + path, {
            method: options.method || 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + app.token
            },
            body: options.body || null
        });

        const payload = await response.json().catch(function () {
            return {};
        });

        if (!response.ok) {
            throw new Error(readableApiMessage(payload.message || 'Ocurrió un problema.'));
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
            const value = normalize(item && item.status);
            return value !== 'completed' && value !== 'cancelled';
        }) || null;
    }

    function normalize(value) {
        return String(value || '').trim().toLowerCase();
    }

    function statusLabel(status) {
        const value = normalize(status);

        return {
            created: 'Solicitud enviada',
            accepted: 'Solicitud aceptada',
            assigned: 'Proveedor asignado',
            in_progress: 'En camino',
            arrived: 'Llegó al punto',
            completed: 'Servicio completado',
            cancelled: 'Servicio cancelado',
        }[value] || 'En proceso';
    }

    function paymentStatusLabel(status) {
        const value = normalize(status);

        return {
            pending: 'Pendiente',
            pending_validation: 'En revisión',
            paid: 'Confirmado',
            failed: 'Fallido',
            rejected: 'Rechazado',
        }[value] || 'Pendiente';
    }

    function statusTone(status, hasProvider, hasLocation) {
        const value = normalize(status);

        if (value === 'completed') return 'success';
        if (value === 'cancelled') return 'danger';
        if (value === 'arrived') return 'success';
        if (value === 'in_progress') return 'success';
        if (value === 'accepted' || value === 'assigned') {
            return hasLocation ? 'success' : 'warning';
        }

        return 'info';
    }

    function statusMessageForUser(status, hasProvider, hasLocation) {
        const value = normalize(status);

        if (value === 'created') {
            return 'Ya recibimos tu solicitud. Estamos buscando quién pueda ayudarte.';
        }

        if (value === 'accepted' || value === 'assigned') {
            if (hasLocation) {
                return 'Tu ayuda ya va en camino. Puedes seguir su ubicación en el mapa.';
            }

            return 'Tu servicio ya fue tomado. En cuanto el proveedor comparta su ubicación, la verás aquí.';
        }

        if (value === 'in_progress') {
            return 'Tu ayuda está en camino. Revisa el mapa y los movimientos recientes.';
        }

        if (value === 'arrived') {
            return 'Tu ayuda ya llegó al punto indicado.';
        }

        if (value === 'completed') {
            return 'Tu servicio fue completado.';
        }

        if (value === 'cancelled') {
            return 'Tu servicio fue cancelado.';
        }

        if (hasProvider && hasLocation) {
            return 'Tu ayuda está en camino. Revisa el mapa para ver su última ubicación.';
        }

        return 'Estamos revisando la información más reciente de tu servicio.';
    }

    function historyMessage(status) {
        const value = normalize(status);

        return {
            created: 'Tu solicitud fue enviada.',
            accepted: 'Tu solicitud fue aceptada.',
            assigned: 'Ya hay un proveedor asignado.',
            in_progress: 'Tu ayuda va en camino.',
            arrived: 'El proveedor llegó al punto.',
            completed: 'El servicio fue completado.',
            cancelled: 'El servicio fue cancelado.',
        }[value] || 'Hubo una actualización en tu servicio.';
    }

    function eventTitle(type) {
        const value = normalize(type);

        return {
            request_created: 'Solicitud enviada',
            request_cancelled_by_client: 'Servicio cancelado',
            payment_registered: 'Pago registrado',
            payment_submitted_for_validation: 'Pago enviado a revisión',
        }[value] || 'Actualización';
    }

    function eventMessage(type, data) {
        const value = normalize(type);

        if (value === 'request_created') {
            return 'Recibimos tu solicitud y ya la estamos procesando.';
        }

        if (value === 'request_cancelled_by_client') {
            return data && data.cancel_reason
                ? 'Cancelaste el servicio. Motivo: ' + data.cancel_reason
                : 'El servicio fue cancelado.';
        }

        if (value === 'payment_registered') {
            return 'Tu pago fue registrado correctamente.';
        }

        if (value === 'payment_submitted_for_validation') {
            return 'Tu pago fue enviado a revisión.';
        }

        return 'Hubo una actualización en tu servicio.';
    }

    function readableApiMessage(message) {
        const text = String(message || '').trim();

        if (!text) {
            return 'Ocurrió un problema al consultar tu servicio.';
        }

        const normalized = text.toLowerCase();

        if (normalized.includes('no encontrada')) {
            return 'No encontramos la información del servicio.';
        }

        if (normalized.includes('no puede cancelarse')) {
            return 'Este servicio ya no se puede cancelar desde la app.';
        }

        if (normalized.includes('una solicitud activa')) {
            return 'Ya tienes un servicio en proceso.';
        }

        return text;
    }

    function formatDateTime(value) {
        if (!value) {
            return 'Ahora';
        }

        const date = new Date(value);

        if (Number.isNaN(date.getTime())) {
            return 'Ahora';
        }

        return new Intl.DateTimeFormat('es-MX', {
            day: '2-digit',
            month: '2-digit',
            hour: '2-digit',
            minute: '2-digit'
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

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot, { once: true });
    } else {
        boot();
    }
})();
</script>
@endpush
