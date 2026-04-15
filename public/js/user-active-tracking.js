(function () {
    const app = window.ZYGA_CLIENT_APP || {};
    const POLL_INTERVAL_MS = 8000;

    let map = null;
    let clientMarker = null;
    let providerMarker = null;
    let providerAccuracyCircle = null;
    let refreshTimer = null;
    let requestInFlight = false;

    document.addEventListener('DOMContentLoaded', init, { once: true });

    function init() {
        if (app.page !== 'active') {
            return;
        }

        if (!window.L) {
            renderTrackingStatus('No fue posible cargar la librería del mapa.', 'danger');
            renderTrackingMeta([]);
            return;
        }

        bindReloadButton();
        renderTrackingStatus('Preparando seguimiento en tiempo real...', 'info');
        renderTrackingMeta([]);
        loadTracking();
        startPolling();

        document.addEventListener('visibilitychange', handleVisibilityChange);
        window.addEventListener('beforeunload', stopPolling, { once: true });
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
            return;
        }

        stopPolling();
    }

    async function loadTracking() {
        if (requestInFlight) {
            return;
        }

        requestInFlight = true;

        try {
            const requestsPayload = await api('/api/v1/client/assistance-requests');
            const requests = extractList(requestsPayload?.data, ['requests', 'items']);
            const activeRequest = findActiveRequest(requests);

            if (!activeRequest) {
                renderNoActiveRequest();
                return;
            }

            const trackingPayload = await api(`/api/v1/client/assistance-requests/${activeRequest.id}/tracking`);
            renderTracking(trackingPayload?.data || null, activeRequest);
        } catch (error) {
            renderTrackingStatus(error.message || 'No fue posible cargar el seguimiento del servicio.', 'danger');
            renderTrackingMeta([]);
        } finally {
            requestInFlight = false;
        }
    }

    function renderNoActiveRequest() {
        renderTrackingStatus('No hay una asistencia activa para mostrar en el mapa.', 'warning');
        renderTrackingMeta([]);
        setMapOverlay('Crea o recupera una solicitud activa para habilitar el seguimiento.');
        resetProviderMarker();
    }

    function renderTracking(data, fallbackRequest) {
        const request = data?.request || fallbackRequest || {};
        const provider = data?.provider || null;
        const providerLocation = data?.provider_location || null;
        const requestLat = toNumber(request.lat);
        const requestLng = toNumber(request.lng);
        const providerLat = toNumber(providerLocation?.lat);
        const providerLng = toNumber(providerLocation?.lng);

        const metaItems = [
            {
                label: 'Estado',
                value: statusLabel(request.status),
            },
            {
                label: 'Folio',
                value: request.public_id || `#${request.id || 'N/D'}`,
            },
            {
                label: 'Proveedor',
                value: provider?.display_name || 'Aún sin proveedor asignado',
            },
            {
                label: 'Última actualización',
                value: providerLocation?.recorded_at
                    ? formatDateTime(providerLocation.recorded_at)
                    : 'Sin ubicación del proveedor todavía',
            },
            {
                label: 'Dirección',
                value: request.pickup_address || 'Sin dirección capturada',
            },
        ];

        renderTrackingMeta(metaItems);

        if (requestLat === null || requestLng === null) {
            renderTrackingStatus('La asistencia existe, pero no tiene coordenadas válidas del cliente.', 'danger');
            setMapOverlay('La solicitud no cuenta con latitud y longitud válidas.');
            resetProviderMarker();
            return;
        }

        ensureMap([requestLat, requestLng]);
        updateClientMarker(requestLat, requestLng, request.pickup_address);

        if (provider && providerLat !== null && providerLng !== null) {
            updateProviderMarker(providerLat, providerLng, provider, providerLocation);
            fitMapToMarkers(requestLat, requestLng, providerLat, providerLng);

            const freshnessText = freshnessLabel(providerLocation?.recorded_at);
            renderTrackingStatus(
                `Proveedor asignado: ${provider.display_name || 'Proveedor'} · ${freshnessText}`,
                'success'
            );
            clearMapOverlay();
            return;
        }

        resetProviderMarker();
        map.setView([requestLat, requestLng], 15);

        if (provider) {
            renderTrackingStatus(
                `Proveedor asignado: ${provider.display_name || 'Proveedor'} · aún no comparte ubicación.`,
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
            map = L.map(mapElement, {
                zoomControl: true,
                attributionControl: true,
            }).setView(center, 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors',
            }).addTo(map);
        } else {
            map.invalidateSize();
        }
    }

    function updateClientMarker(lat, lng, address) {
        if (!map) {
            return;
        }

        const popup = `
            <div>
                <strong>Tu ubicación registrada</strong><br>
                <span>${escapeHtml(address || 'Sin dirección capturada')}</span><br>
                <small>${escapeHtml(`${lat.toFixed(6)}, ${lng.toFixed(6)}`)}</small>
            </div>
        `;

        if (!clientMarker) {
            clientMarker = L.marker([lat, lng], {
                icon: createMarkerIcon('client'),
            }).addTo(map);
        } else {
            clientMarker.setLatLng([lat, lng]);
        }

        clientMarker.bindPopup(popup);
    }

    function updateProviderMarker(lat, lng, provider, providerLocation) {
        if (!map) {
            return;
        }

        const popup = `
            <div>
                <strong>${escapeHtml(provider?.display_name || 'Proveedor')}</strong><br>
                <span>Ubicación más reciente del proveedor</span><br>
                <small>${escapeHtml(formatDateTime(providerLocation?.recorded_at || providerLocation?.created_at))}</small>
            </div>
        `;

        if (!providerMarker) {
            providerMarker = L.marker([lat, lng], {
                icon: createMarkerIcon('provider'),
            }).addTo(map);
        } else {
            providerMarker.setLatLng([lat, lng]);
        }

        providerMarker.bindPopup(popup);

        const accuracy = toNumber(providerLocation?.accuracy);

        if (accuracy !== null) {
            if (!providerAccuracyCircle) {
                providerAccuracyCircle = L.circle([lat, lng], {
                    radius: accuracy,
                    weight: 1,
                    opacity: 0.65,
                    fillOpacity: 0.08,
                }).addTo(map);
            } else {
                providerAccuracyCircle.setLatLng([lat, lng]);
                providerAccuracyCircle.setRadius(accuracy);
            }
        } else if (providerAccuracyCircle) {
            map.removeLayer(providerAccuracyCircle);
            providerAccuracyCircle = null;
        }
    }

    function resetProviderMarker() {
        if (map && providerMarker) {
            map.removeLayer(providerMarker);
            providerMarker = null;
        }

        if (map && providerAccuracyCircle) {
            map.removeLayer(providerAccuracyCircle);
            providerAccuracyCircle = null;
        }
    }

    function fitMapToMarkers(requestLat, requestLng, providerLat, providerLng) {
        if (!map) {
            return;
        }

        const bounds = L.latLngBounds([
            [requestLat, requestLng],
            [providerLat, providerLng],
        ]);

        map.fitBounds(bounds, {
            padding: [40, 40],
            maxZoom: 16,
        });
    }

    function createMarkerIcon(type) {
        return L.divIcon({
            className: 'active-tracking-marker-wrapper',
            html: `<span class="active-tracking-marker active-tracking-marker--${type}"></span>`,
            iconSize: [20, 20],
            iconAnchor: [10, 10],
            popupAnchor: [0, -10],
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

        target.className = `active-tracking-status active-tracking-status--${tone || 'info'}`;
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

    async function api(path, options = {}) {
        const response = await fetch(`${app.apiBaseUrl}${path}`, {
            method: options.method || 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'Authorization': app.token ? `Bearer ${app.token}` : '',
                ...(options.headers || {}),
            },
            body: options.body,
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
            const status = normalizeStatus(item?.status);
            return status !== 'completed' && status !== 'cancelled';
        }) || null;
    }

    function normalizeStatus(status) {
        return `${status || ''}`.trim().toLowerCase();
    }

    function statusLabel(status) {
        const value = normalizeStatus(status);

        const labels = {
            created: 'Nueva',
            assigned: 'Asignada',
            in_progress: 'En proceso',
            completed: 'Completada',
            cancelled: 'Cancelada',
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
            return `última ubicación hace ${diffSeconds} s`;
        }

        const diffMinutes = Math.round(diffSeconds / 60);

        if (diffMinutes < 60) {
            return `última ubicación hace ${diffMinutes} min`;
        }

        const diffHours = Math.round(diffMinutes / 60);
        return `última ubicación hace ${diffHours} h`;
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
            timeStyle: 'short',
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
