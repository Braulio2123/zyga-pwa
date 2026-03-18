(function () {
    const app = window.ZYGA_USER_CONFIG || {};
    const toastElement = document.getElementById('zygaToast');

    const SERVICE_ICON_MAP = [
        { pattern: /grua|grúa|remolque/i, icon: 'bi-truck-front-fill' },
        { pattern: /llanta|neum[aá]tico/i, icon: 'bi-disc-fill' },
        { pattern: /combustible|gasolina|diesel/i, icon: 'bi-fuel-pump-fill' },
        { pattern: /bater[ií]a/i, icon: 'bi-battery-charging' },
        { pattern: /cerrajer/i, icon: 'bi-key-fill' },
        { pattern: /m[eé]dica|medica|salud/i, icon: 'bi-heart-pulse-fill' },
    ];

    document.addEventListener('DOMContentLoaded', init);

    function init() {
        bindDashboardEvents();

        switch (app.currentPage) {
            case 'dashboard':
                loadDashboard();
                break;
            case 'history':
                loadHistory();
                break;
            case 'wallet':
                loadWallet();
                break;
            case 'account':
                loadAccount();
                break;
            default:
                break;
        }
    }

    function bindDashboardEvents() {
        const modal = document.getElementById('requestModal');
        const closeTrigger = modal?.querySelector('[data-close-modal]');
        const geoButton = document.getElementById('geoButton');
        const form = document.getElementById('assistanceForm');
        const detailButton = document.getElementById('requestDetailsButton');
        const trackerReloadButton = document.getElementById('trackerReloadButton');

        closeTrigger?.addEventListener('click', closeRequestModal);
        modal?.addEventListener('click', (event) => {
            if (event.target === modal) {
                closeRequestModal();
            }
        });

        geoButton?.addEventListener('click', fillCurrentLocation);
        form?.addEventListener('submit', submitAssistanceRequest);

        detailButton?.addEventListener('click', () => {
            window.location.href = app.webRoutes.account;
        });

        trackerReloadButton?.addEventListener('click', loadAccount);
    }

    async function loadDashboard() {
        hydrateSessionIdentity('dashboardGreeting', 'dashboardAvatar');

        const [servicesResult, vehiclesResult, requestsResult] = await Promise.allSettled([
            api(app.endpoints.services),
            api(app.endpoints.vehicles),
            api(app.endpoints.assistanceRequests),
        ]);

        const services = getDataArray(servicesResult, []);
        const vehicles = getDataArray(vehiclesResult, []);
        const requests = getDataArray(requestsResult, []);

        renderServices(services);
        renderVehicleOptions(vehicles);
        renderActiveRequest(requests);
        showMissingTokenNotice(servicesResult, vehiclesResult, requestsResult);
    }

    async function loadHistory() {
        const container = document.getElementById('paymentHistoryList');
        if (!container) return;

        const [paymentsResult, requestsResult] = await Promise.allSettled([
            api(app.endpoints.payments),
            api(app.endpoints.assistanceRequests),
        ]);

        const payments = getDataArray(paymentsResult, []);
        const requests = getDataArray(requestsResult, []);

        if (!payments.length && !requests.length) {
            container.innerHTML = `<article class="empty-state">Aún no existen pagos ni solicitudes cerradas para mostrar en historial.</article>`;
            showMissingTokenNotice(paymentsResult, requestsResult);
            return;
        }

        if (payments.length) {
            container.innerHTML = payments.map(renderPaymentCard).join('');
        } else {
            container.innerHTML = requests.map(renderAssistanceHistoryCard).join('');
        }

        showMissingTokenNotice(paymentsResult, requestsResult);
    }

    async function loadWallet() {
        const balance = document.getElementById('walletBalance');
        if (!balance) return;

        const paymentsResult = await api(app.endpoints.payments).catch((error) => ({ message: error.message, data: [] }));
        const payments = Array.isArray(paymentsResult.data) ? paymentsResult.data : [];
        const total = payments
            .filter((payment) => Number(payment.amount) > 0 && `${payment.status || ''}`.toLowerCase() !== 'cancelled')
            .reduce((sum, payment) => sum + Number(payment.amount || 0), 0);

        balance.textContent = currency(total);

        if (!app.token) {
            showToast('La vista de billetera ya quedó lista, pero faltan los controladores de métodos de pago y suscripción.');
        }
    }

    async function loadAccount() {
        hydrateSessionIdentity('accountName', 'accountAvatar', true);

        const [profileResult, vehiclesResult, requestsResult] = await Promise.allSettled([
            api(app.endpoints.profile),
            api(app.endpoints.vehicles),
            api(app.endpoints.assistanceRequests),
        ]);

        const profilePayload = getObject(profileResult, null);
        const vehicles = getDataArray(vehiclesResult, []);
        const requests = getDataArray(requestsResult, []);

        renderAccountProfile(profilePayload);
        renderPrimaryVehicle(vehicles);
        await renderTracker(requests);
        showMissingTokenNotice(profileResult, vehiclesResult, requestsResult);
    }

    function renderServices(services) {
        const grid = document.getElementById('serviceGrid');
        if (!grid) return;

        const fallbackServices = [
            { id: 1, name: 'Servicio de grúa' },
            { id: 2, name: 'Cambio de llanta' },
            { id: 3, name: 'Combustible' },
            { id: 4, name: 'Batería' },
        ];

        const finalServices = services.length ? services.slice(0, 4) : fallbackServices;

        grid.innerHTML = finalServices.map((service) => {
            const icon = resolveServiceIcon(service.name);
            return `
                <button type="button" class="quick-action" data-service-id="${service.id}" data-service-name="${escapeHtml(service.name)}">
                    <i class="bi ${icon}"></i>
                    <span>${escapeHtml(service.name)}</span>
                </button>
            `;
        }).join('');

        grid.querySelectorAll('[data-service-id]').forEach((button) => {
            button.addEventListener('click', () => openRequestModal(button.dataset.serviceId, button.dataset.serviceName));
        });
    }

    function renderVehicleOptions(vehicles) {
        const select = document.getElementById('vehicleSelect');
        if (!select) return;

        if (!vehicles.length) {
            select.innerHTML = `<option value="">Primero registra un vehículo desde el API del cliente</option>`;
            return;
        }

        select.innerHTML = `<option value="">Selecciona un vehículo</option>` + vehicles.map((vehicle) => {
            const label = `${vehicle.brand || 'Vehículo'} ${vehicle.model || ''} · ${vehicle.plate || 'sin placa'}`.trim();
            return `<option value="${vehicle.id}">${escapeHtml(label)}</option>`;
        }).join('');
    }

    function renderActiveRequest(requests) {
        const title = document.getElementById('activeRequestTitle');
        const subtitle = document.getElementById('activeRequestSubtitle');
        if (!title || !subtitle) return;

        const activeRequest = requests.find((item) => !['completed', 'cancelled'].includes(`${item.status || ''}`.toLowerCase())) || requests[0];

        if (!activeRequest) {
            title.textContent = 'No hay solicitudes activas';
            subtitle.textContent = 'Cuando generes una asistencia, verás aquí su estado, folio y dirección.';
            return;
        }

        const serviceName = activeRequest.service?.name || 'Servicio vial';
        const vehicleName = [activeRequest.vehicle?.brand, activeRequest.vehicle?.model].filter(Boolean).join(' ');
        title.textContent = `${serviceName} · ${activeRequest.public_id || `#${activeRequest.id}`}`;

        subtitle.innerHTML = `
            <span class="badge-status">${formatStatus(activeRequest.status)}</span>
            <br>${escapeHtml(activeRequest.pickup_address || 'Sin dirección registrada')}
            ${vehicleName ? `<br>Vehículo: ${escapeHtml(vehicleName)}` : ''}
        `;
    }

    function renderPaymentCard(payment) {
        const serviceName = payment.assistance_request?.service?.name || payment.assistanceRequest?.service?.name || 'Servicio';
        const vehicle = payment.assistance_request?.vehicle || payment.assistanceRequest?.vehicle || {};
        const vehicleName = [vehicle.brand, vehicle.model].filter(Boolean).join(' ') || 'Vehículo no especificado';
        const createdAt = formatDate(payment.created_at || payment.updated_at);
        const transactionId = payment.transaction_id || `PAGO-${payment.id}`;

        return `
            <article class="history-card">
                <div class="history-card__folio">Folio ${escapeHtml(transactionId)}<br>${escapeHtml(createdAt)}</div>
                <div class="history-card__chip">${escapeHtml(serviceName)}</div>
                <div class="history-card__grid">
                    <div class="history-card__icon"><i class="bi ${resolveServiceIcon(serviceName)}"></i></div>
                    <div class="history-card__meta">
                        <ul>
                            <li>Vehículo: ${escapeHtml(vehicleName)}</li>
                            <li>Método de pago: ${escapeHtml(payment.payment_method || 'Pendiente')}</li>
                            <li>Estatus: ${escapeHtml(formatStatus(payment.status || 'pagado'))}</li>
                        </ul>
                        <strong>Método de pago:</strong><br>
                        ${escapeHtml(payment.payment_method || 'No definido')}
                    </div>
                    <div class="history-card__amount">
                        <span>Total pagado:</span>
                        <strong>${currency(payment.amount || 0)}</strong>
                    </div>
                </div>
            </article>
        `;
    }

    function renderAssistanceHistoryCard(request) {
        const serviceName = request.service?.name || 'Servicio de asistencia';
        const vehicleName = [request.vehicle?.brand, request.vehicle?.model].filter(Boolean).join(' ') || 'Vehículo no especificado';
        return `
            <article class="history-card">
                <div class="history-card__folio">Folio ${escapeHtml(request.public_id || `REQ-${request.id}`)}<br>${escapeHtml(formatDate(request.created_at))}</div>
                <div class="history-card__chip">${escapeHtml(serviceName)}</div>
                <div class="history-card__grid">
                    <div class="history-card__icon"><i class="bi ${resolveServiceIcon(serviceName)}"></i></div>
                    <div class="history-card__meta">
                        <ul>
                            <li>Vehículo: ${escapeHtml(vehicleName)}</li>
                            <li>Dirección: ${escapeHtml(request.pickup_address || 'Sin dirección')}</li>
                            <li>Estatus: ${escapeHtml(formatStatus(request.status || 'created'))}</li>
                        </ul>
                    </div>
                    <div class="history-card__amount">
                        <span>Seguimiento:</span>
                        <strong>${escapeHtml(formatStatus(request.status || 'created'))}</strong>
                    </div>
                </div>
            </article>
        `;
    }

    function renderAccountProfile(payload) {
        const accountName = document.getElementById('accountName');
        const accountEmail = document.getElementById('accountEmail');
        const avatar = document.getElementById('accountAvatar');

        const user = payload?.user || null;
        if (!user) {
            if (accountEmail && app.sessionUser?.email) {
                accountEmail.textContent = app.sessionUser.email;
            }
            return;
        }

        const displayName = user.name || user.email || 'Usuario ZYGA';
        if (accountName) accountName.textContent = displayName;
        if (accountEmail) accountEmail.textContent = user.email || 'Sin correo disponible';
        if (avatar) avatar.textContent = initials(displayName);
    }

    function renderPrimaryVehicle(vehicles) {
        const title = document.getElementById('primaryVehicleTitle');
        const meta = document.getElementById('primaryVehicleMeta');
        if (!title || !meta) return;

        const vehicle = vehicles[0];
        if (!vehicle) {
            title.textContent = 'Sin vehículo registrado';
            meta.textContent = 'Agrega un vehículo desde /api/v1/client/vehicles para verlo aquí.';
            return;
        }

        title.textContent = `${vehicle.brand || 'Vehículo'} ${vehicle.model || ''}`.trim();
        meta.textContent = `${vehicle.vehicle_type?.name || 'Tipo no disponible'} · ${vehicle.plate || 'sin placa'}${vehicle.year ? ` · ${vehicle.year}` : ''}`;
    }

    async function renderTracker(requests) {
        const trackerTitle = document.getElementById('trackerTitle');
        const trackerList = document.getElementById('trackerList');
        if (!trackerTitle || !trackerList) return;

        const latestRequest = requests[0];
        if (!latestRequest) {
            trackerTitle.textContent = 'Solicitud pendiente';
            trackerList.innerHTML = `
                <div class="tracker-item is-muted">
                    <span class="tracker-check"><i class="bi bi-circle"></i></span>
                    <span>Aún no has creado una solicitud de asistencia.</span>
                </div>
            `;
            return;
        }

        trackerTitle.textContent = `${latestRequest.service?.name || 'Asistencia'} · ${latestRequest.public_id || `REQ-${latestRequest.id}`}`;

        const timelinePayload = await api(`${app.endpoints.assistanceRequests}/${latestRequest.id}/timeline`).catch(() => null);
        const history = Array.isArray(timelinePayload?.data?.history) ? timelinePayload.data.history : [];

        if (!history.length) {
            trackerList.innerHTML = buildTrackerFromStatus(latestRequest.status);
            return;
        }

        trackerList.innerHTML = history.map((item) => {
            const stateClass = trackerClass(item.status);
            return `
                <div class="tracker-item ${stateClass}">
                    <span class="tracker-check"><i class="bi ${trackerIcon(item.status)}"></i></span>
                    <span>${escapeHtml(formatStatus(item.status))}</span>
                </div>
            `;
        }).join('');
    }

    function buildTrackerFromStatus(status) {
        const steps = ['created', 'assigned', 'in_progress', 'completed'];
        const currentIndex = Math.max(steps.indexOf(`${status || ''}`.toLowerCase()), 0);

        return steps.map((step, index) => {
            const stateClass = `${status}`.toLowerCase() === 'cancelled'
                ? (index === 0 ? 'is-done' : 'is-danger')
                : (index <= currentIndex ? 'is-done' : 'is-muted');

            return `
                <div class="tracker-item ${stateClass}">
                    <span class="tracker-check"><i class="bi ${index <= currentIndex ? 'bi-check-lg' : 'bi-circle'}"></i></span>
                    <span>${escapeHtml(formatStatus(step))}</span>
                </div>
            `;
        }).join('');
    }

    function openRequestModal(serviceId, serviceName) {
        const modal = document.getElementById('requestModal');
        const serviceIdInput = document.getElementById('serviceIdInput');
        const serviceNameInput = document.getElementById('serviceNameInput');

        if (!modal || !serviceIdInput || !serviceNameInput) return;

        serviceIdInput.value = serviceId || '';
        serviceNameInput.value = serviceName || '';
        modal.classList.add('is-open');
    }

    function closeRequestModal() {
        document.getElementById('requestModal')?.classList.remove('is-open');
    }

    function fillCurrentLocation() {
        if (!navigator.geolocation) {
            showToast('Tu navegador no soporta geolocalización.');
            return;
        }

        navigator.geolocation.getCurrentPosition(
            (position) => {
                const lat = document.getElementById('latInput');
                const lng = document.getElementById('lngInput');
                if (lat) lat.value = position.coords.latitude.toFixed(6);
                if (lng) lng.value = position.coords.longitude.toFixed(6);
                showToast('Ubicación cargada correctamente.');
            },
            () => showToast('No fue posible obtener la ubicación del dispositivo.')
        );
    }

    async function submitAssistanceRequest(event) {
        event.preventDefault();

        const form = event.currentTarget;
        const submitButton = form.querySelector('[type="submit"]');
        const payload = Object.fromEntries(new FormData(form).entries());

        if (!app.token) {
            showToast('Falta el token de API. Guarda el token de Sanctum en sesión para activar esta función.');
            return;
        }

        try {
            if (submitButton) submitButton.disabled = true;
            const response = await api(app.endpoints.assistanceRequests, {
                method: 'POST',
                body: JSON.stringify(payload),
            });

            showToast(response.message || 'Solicitud creada correctamente.');
            form.reset();
            closeRequestModal();
            loadDashboard();
        } catch (error) {
            showToast(error.message || 'No fue posible crear la solicitud.');
        } finally {
            if (submitButton) submitButton.disabled = false;
        }
    }

    function hydrateSessionIdentity(nameElementId, avatarElementId, keepOnlyName) {
        const nameElement = document.getElementById(nameElementId);
        const avatarElement = document.getElementById(avatarElementId);
        const user = app.sessionUser || {};
        const rawName = user.name || user.email || 'Usuario';

        if (nameElement) {
            nameElement.textContent = keepOnlyName ? rawName : `${rawName}`;
        }

        if (avatarElement) {
            avatarElement.textContent = initials(rawName);
        }
    }

    async function api(path, options = {}) {
        if (!path) {
            throw new Error('Ruta de API no definida.');
        }

        const headers = {
            Accept: 'application/json',
            ...(options.headers || {}),
        };

        if (!(options.body instanceof FormData)) {
            headers['Content-Type'] = 'application/json';
        }

        if (app.token) {
            headers.Authorization = `Bearer ${app.token}`;
        }

        const response = await fetch(`${app.apiBaseUrl}${path}`, {
            ...options,
            headers,
        });

        const payload = await safeJson(response);

        if (!response.ok) {
            const message = payload?.message || firstValidationError(payload) || 'Ocurrió un error al consultar la API.';
            throw new Error(message);
        }

        return payload;
    }

    async function safeJson(response) {
        try {
            return await response.json();
        } catch {
            return null;
        }
    }

    function firstValidationError(payload) {
        if (!payload?.errors) return null;
        const firstKey = Object.keys(payload.errors)[0];
        return firstKey ? payload.errors[firstKey][0] : null;
    }

    function getDataArray(result, fallback = []) {
        if (result.status !== 'fulfilled') {
            return fallback;
        }

        return Array.isArray(result.value?.data) ? result.value.data : fallback;
    }

    function getObject(result, fallback = null) {
        if (result.status !== 'fulfilled') {
            return fallback;
        }

        return result.value?.data || fallback;
    }

    function showMissingTokenNotice(...results) {
        const shouldWarn = !app.token || results.some((result) => result.status === 'rejected');
        if (shouldWarn) {
            showToast('La interfaz ya está conectada para API, pero necesitas token y rutas definitivas para datos reales.');
        }
    }

    function showToast(message) {
        if (!toastElement) return;
        toastElement.textContent = message;
        toastElement.classList.add('is-visible');
        clearTimeout(showToast.timeout);
        showToast.timeout = setTimeout(() => {
            toastElement.classList.remove('is-visible');
        }, 3000);
    }

    function resolveServiceIcon(name = '') {
        const match = SERVICE_ICON_MAP.find((item) => item.pattern.test(name));
        return match ? match.icon : 'bi-tools';
    }

    function formatStatus(status = '') {
        const value = `${status}`.trim().toLowerCase();
        const dictionary = {
            created: 'Solicitud creada',
            assigned: 'Apoyo asignado',
            accepted: 'Solicitud aceptada',
            in_progress: 'Apoyo en camino',
            arrived: 'Apoyo en el lugar',
            completed: 'Apoyo finalizado',
            cancelled: 'Solicitud cancelada',
            confirmed: 'Confirmado',
            quoted: 'Cotizado',
            paid: 'Pagado',
            pending: 'Pendiente',
        };
        return dictionary[value] || status || 'Sin estado';
    }

    function trackerClass(status = '') {
        const normalized = `${status}`.toLowerCase();
        if (normalized === 'cancelled') return 'is-danger';
        if (['quoted', 'pending'].includes(normalized)) return 'is-warning';
        return 'is-done';
    }

    function trackerIcon(status = '') {
        const normalized = `${status}`.toLowerCase();
        if (normalized === 'cancelled') return 'bi-x-lg';
        if (['quoted', 'pending'].includes(normalized)) return 'bi-exclamation-lg';
        return 'bi-check-lg';
    }

    function formatDate(value) {
        if (!value) return 'Sin fecha';
        const date = new Date(value);
        if (Number.isNaN(date.getTime())) return value;
        return new Intl.DateTimeFormat('es-MX', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
        }).format(date);
    }

    function currency(value) {
        return new Intl.NumberFormat('es-MX', {
            style: 'currency',
            currency: 'MXN',
            maximumFractionDigits: 2,
        }).format(Number(value || 0));
    }

    function initials(value = '') {
        const parts = `${value}`.trim().split(/\s+/).filter(Boolean);
        if (!parts.length) return 'U';
        return parts.slice(0, 2).map((part) => part[0]?.toUpperCase() || '').join('');
    }

    function escapeHtml(value = '') {
        return `${value}`
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }
})();
