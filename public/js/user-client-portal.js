(function () {
    const app = window.ZYGA_CLIENT_APP || {};
    const toast = document.getElementById('appToast');
    const listenerRegistry = new WeakMap();

    document.addEventListener('DOMContentLoaded', init, { once: true });

    function init() {
        if (!app.apiBaseUrl) {
            showToast('La URL base de la API no está configurada.');
            return;
        }

        if (!app.token) {
            showToast('La sesión del cliente no tiene token de API. Vuelve a iniciar sesión.');
        }

        switch (app.page) {
            case 'dashboard':
                loadDashboard();
                break;
            case 'request':
                loadRequestPage();
                break;
            case 'active':
                loadActivePage();
                break;
            case 'history':
                loadHistoryPage();
                break;
            case 'payments':
                loadPaymentsPage();
                break;
            case 'account':
                loadAccountPage();
                break;
            default:
                break;
        }
    }

    async function loadDashboard() {
        try {
            const [services, vehicles, requests] = await Promise.all([
                fetchServices(),
                fetchVehicles(),
                fetchAssistanceRequests(),
            ]);

            const active = findActiveRequest(requests);

            setText('dashboardServicesCount', String(services.length));
            setText('dashboardVehiclesCount', String(vehicles.length));
            setText('dashboardActiveCount', active ? '1' : '0');

            renderServicesPreview('dashboardServicesList', services);
            renderDashboardVehicleState('dashboardVehicleState', vehicles);
            renderDashboardActiveState('dashboardActiveRequest', active);
            renderDashboardBlockers('dashboardBlockers', vehicles, active);
        } catch (error) {
            showToast(error.message || 'No fue posible cargar el panel principal.');
        }
    }

    async function loadRequestPage() {
        try {
            const [services, vehicles, requests] = await Promise.all([
                fetchServices(),
                fetchVehicles(),
                fetchAssistanceRequests(),
            ]);

            const active = findActiveRequest(requests);
            const form = document.getElementById('assistanceRequestForm');
            const submitButton = document.getElementById('requestSubmitButton');
            const geoButton = document.getElementById('requestGeoButton');

            fillSelect(
                'requestServiceId',
                services,
                services.length ? 'Selecciona un servicio' : 'No hay servicios disponibles',
                (item) => ({
                    value: item.id,
                    label: item.name || `Servicio #${item.id}`,
                })
            );

            fillVehicleSelect('requestVehicleId', vehicles);
            renderRequestBlockingState(active, vehicles.length === 0);

            bindElementEvent(geoButton, 'request:geo', 'click', hydrateCurrentLocation);

            if (active || vehicles.length === 0) {
                if (submitButton) {
                    submitButton.disabled = true;
                }

                if (form) {
                    Array.from(form.elements).forEach((element) => {
                        if (element.id === 'requestGeoButton') {
                            element.disabled = vehicles.length === 0;
                            return;
                        }

                        if (element.name) {
                            element.disabled = true;
                        }
                    });
                }

                return;
            }

            if (form) {
                Array.from(form.elements).forEach((element) => {
                    if (element.name || element.id === 'requestGeoButton') {
                        element.disabled = false;
                    }
                });
            }

            bindElementEvent(form, 'request:create', 'submit', async (event) => {
                event.preventDefault();

                if (submitButton) {
                    submitButton.disabled = true;
                }

                try {
                    const payload = formToObject(form);

                    await api('/api/v1/client/assistance-requests', {
                        method: 'POST',
                        body: JSON.stringify(payload),
                    });

                    showToast('Solicitud creada correctamente.');
                    window.location.href = app.routes.active;
                } catch (error) {
                    showToast(error.message || 'No fue posible crear la solicitud.');
                } finally {
                    if (submitButton) {
                        submitButton.disabled = false;
                    }
                }
            });
        } catch (error) {
            showToast(error.message || 'No fue posible cargar la sección de solicitud.');
        }
    }

    async function loadActivePage() {
        const reloadButton = document.getElementById('activeReloadButton');

        bindElementEvent(reloadButton, 'active:reload', 'click', () => {
            loadActivePage();
        });

        try {
            const requests = await fetchAssistanceRequests();
            const active = findActiveRequest(requests);

            if (!active) {
                setHtml('activeRequestSummary', emptyCard('No tienes solicitudes activas. Cuando crees una asistencia verás aquí el seguimiento real.'));
                setHtml('activeTimeline', emptyCard('Sin timeline disponible.'));
                disableCancelForm(true);
                return;
            }

            const [statusPayload, timelinePayload] = await Promise.all([
                api(`/api/v1/client/assistance-requests/${active.id}/status`).catch(() => null),
                api(`/api/v1/client/assistance-requests/${active.id}/timeline`).catch(() => null),
            ]);

            renderActiveSummary('activeRequestSummary', active, statusPayload?.data || null);
            renderTimeline('activeTimeline', timelinePayload?.data || null, active.status);
            bindCancelForm(active, statusPayload?.data || null);
        } catch (error) {
            showToast(error.message || 'No fue posible cargar el servicio activo.');
        }
    }

    async function loadHistoryPage() {
        try {
            const [requests, payments] = await Promise.all([
                fetchAssistanceRequests(),
                fetchPayments(),
            ]);

            const closedRequests = requests.filter((item) =>
                ['completed', 'cancelled'].includes(normalizeStatus(item.status))
            );

            renderRequestsHistory('historyRequestsList', closedRequests);
            renderPayments('historyPaymentsList', payments, true);
        } catch (error) {
            showToast(error.message || 'No fue posible cargar el historial.');
        }
    }

    async function loadPaymentsPage() {
        try {
            const [paymentTypes, paymentMethods, payments, requests] = await Promise.all([
                fetchPaymentMethodTypes(),
                fetchPaymentMethods(),
                fetchPayments(),
                fetchAssistanceRequests(),
            ]);

            const paymentMethodForm = document.getElementById('paymentMethodForm');
            const paymentRegisterForm = document.getElementById('paymentRegisterForm');

            renderPaymentMethods('paymentMethodsList', paymentMethods, paymentTypes);
            renderPayments('paymentsList', payments, false);

            fillSelect(
                'paymentMethodType',
                paymentTypes,
                paymentTypes.length ? 'Selecciona tipo' : 'No hay tipos disponibles',
                (item) => ({
                    value: item.code,
                    label: item.name || item.code,
                })
            );

            fillSelect(
                'paymentRegisterMethod',
                paymentTypes,
                paymentTypes.length ? 'Selecciona método' : 'No hay métodos disponibles',
                (item) => ({
                    value: item.code,
                    label: item.name || item.code,
                })
            );

            fillCompletedRequestSelect('paymentRequestId', requests, payments);

            bindElementEvent(paymentMethodForm, 'payments:create-method', 'submit', async (event) => {
                event.preventDefault();

                try {
                    const payload = formToObject(paymentMethodForm);

                    await api('/api/v1/client/payment-methods', {
                        method: 'POST',
                        body: JSON.stringify(payload),
                    });

                    paymentMethodForm.reset();
                    showToast('Método de pago guardado correctamente.');
                    await loadPaymentsPage();
                } catch (error) {
                    showToast(error.message || 'No fue posible guardar el método de pago.');
                }
            });

            bindElementEvent(paymentRegisterForm, 'payments:create-payment', 'submit', async (event) => {
                event.preventDefault();

                try {
                    const payload = formToObject(paymentRegisterForm);

                    await api('/api/v1/client/payments', {
                        method: 'POST',
                        body: JSON.stringify(payload),
                    });

                    paymentRegisterForm.reset();
                    showToast('Pago registrado correctamente.');
                    await loadPaymentsPage();
                } catch (error) {
                    showToast(error.message || 'No fue posible registrar el pago.');
                }
            });
        } catch (error) {
            showToast(error.message || 'No fue posible cargar la sección de pagos.');
        }
    }

    async function loadAccountPage() {
        try {
            hydrateVehicleTypeOptions();

            const [profilePayload, vehicles] = await Promise.all([
                api('/api/v1/me').catch(() => null),
                fetchVehicles(),
            ]);

            renderAccountProfile('accountProfileCard', profilePayload?.data || null);
            renderAccountVehicles('accountVehiclesList', vehicles);
            populateAccountForms(profilePayload?.data || null);

            bindAccountForms();
            bindVehicleForm(vehicles);
        } catch (error) {
            showToast(error.message || 'No fue posible cargar la cuenta.');
        }
    }

    function bindAccountForms() {
        const emailForm = document.getElementById('accountEmailForm');
        const passwordForm = document.getElementById('accountPasswordForm');

        bindElementEvent(emailForm, 'account:update-email', 'submit', async (event) => {
            event.preventDefault();

            try {
                const payload = formToObject(emailForm);

                await api('/api/v1/me', {
                    method: 'PUT',
                    body: JSON.stringify(payload),
                });

                showToast('Correo actualizado correctamente.');
                await loadAccountPage();
            } catch (error) {
                showToast(error.message || 'No fue posible actualizar el correo.');
            }
        });

        bindElementEvent(passwordForm, 'account:update-password', 'submit', async (event) => {
            event.preventDefault();

            try {
                const payload = formToObject(passwordForm);

                await api('/api/v1/me', {
                    method: 'PATCH',
                    body: JSON.stringify(payload),
                });

                passwordForm.reset();
                showToast('Contraseña actualizada correctamente.');
            } catch (error) {
                showToast(error.message || 'No fue posible actualizar la contraseña.');
            }
        });
    }

    function bindVehicleForm(vehicles) {
        const form = document.getElementById('vehicleForm');
        const resetButton = document.getElementById('vehicleFormReset');

        bindElementEvent(resetButton, 'vehicle:reset', 'click', () => {
            resetVehicleForm();
        });

        bindElementEvent(form, 'vehicle:submit', 'submit', async (event) => {
            event.preventDefault();

            try {
                const payload = formToObject(form);
                const vehicleId = payload.vehicle_id;

                delete payload.vehicle_id;

                if (vehicleId) {
                    await api(`/api/v1/client/vehicles/${vehicleId}`, {
                        method: 'PATCH',
                        body: JSON.stringify(payload),
                    });

                    showToast('Vehículo actualizado correctamente.');
                } else {
                    await api('/api/v1/client/vehicles', {
                        method: 'POST',
                        body: JSON.stringify(payload),
                    });

                    showToast('Vehículo registrado correctamente.');
                }

                resetVehicleForm();
                await loadAccountPage();
            } catch (error) {
                showToast(error.message || 'No fue posible guardar el vehículo.');
            }
        });

        document.querySelectorAll('[data-edit-vehicle]').forEach((button) => {
            bindElementEvent(button, 'vehicle:edit', 'click', () => {
                const vehicle = vehicles.find((item) => String(item.id) === String(button.dataset.editVehicle));
                if (!vehicle) {
                    return;
                }

                const vehicleIdInput = document.getElementById('vehicleIdInput');
                const vehicleTypeInput = document.getElementById('vehicleTypeInput');
                const vehiclePlateInput = document.getElementById('vehiclePlateInput');
                const vehicleBrandInput = document.getElementById('vehicleBrandInput');
                const vehicleModelInput = document.getElementById('vehicleModelInput');
                const vehicleYearInput = document.getElementById('vehicleYearInput');
                const vehicleSubmitButton = document.getElementById('vehicleSubmitButton');

                if (vehicleIdInput) vehicleIdInput.value = vehicle.id || '';
                if (vehicleTypeInput) vehicleTypeInput.value = vehicle.vehicle_type_id || '';
                if (vehiclePlateInput) vehiclePlateInput.value = vehicle.plate || '';
                if (vehicleBrandInput) vehicleBrandInput.value = vehicle.brand || '';
                if (vehicleModelInput) vehicleModelInput.value = vehicle.model || '';
                if (vehicleYearInput) vehicleYearInput.value = vehicle.year || '';
                if (vehicleSubmitButton) vehicleSubmitButton.textContent = 'Actualizar vehículo';

                form?.scrollIntoView({ behavior: 'smooth', block: 'center' });
            });
        });

        document.querySelectorAll('[data-delete-vehicle]').forEach((button) => {
            bindElementEvent(button, 'vehicle:delete', 'click', async () => {
                const vehicleId = button.dataset.deleteVehicle;

                if (!vehicleId) {
                    return;
                }

                if (!window.confirm('¿Deseas eliminar este vehículo?')) {
                    return;
                }

                try {
                    await api(`/api/v1/client/vehicles/${vehicleId}`, {
                        method: 'DELETE',
                    });

                    showToast('Vehículo eliminado correctamente.');
                    await loadAccountPage();
                } catch (error) {
                    showToast(error.message || 'No fue posible eliminar el vehículo.');
                }
            });
        });
    }

    async function fetchServices() {
        const payload = await api('/api/v1/services').catch(() => ({ data: [] }));
        return Array.isArray(payload?.data) ? payload.data : [];
    }

    async function fetchVehicles() {
        const payload = await api('/api/v1/client/vehicles').catch(() => ({ data: [] }));
        return Array.isArray(payload?.data) ? payload.data : [];
    }

    async function fetchAssistanceRequests() {
        const payload = await api('/api/v1/client/assistance-requests').catch(() => ({ data: [] }));
        return Array.isArray(payload?.data) ? payload.data : [];
    }

    async function fetchPayments() {
        const payload = await api('/api/v1/client/payments').catch(() => ({ data: [] }));
        return Array.isArray(payload?.data) ? payload.data : [];
    }

    async function fetchPaymentMethods() {
        const payload = await api('/api/v1/client/payment-methods').catch(() => ({ data: [] }));
        return Array.isArray(payload?.data) ? payload.data : [];
    }

    async function fetchPaymentMethodTypes() {
        const payload = await api('/api/v1/payment-method-types').catch(() => ({ data: [] }));
        return Array.isArray(payload?.data) ? payload.data : [];
    }

    function api(path, options = {}) {
        if (!path) {
            return Promise.reject(new Error('Ruta de API no definida.'));
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

        if (app.csrfToken) {
            headers['X-CSRF-TOKEN'] = app.csrfToken;
        }

        return fetch(`${String(app.apiBaseUrl).replace(/\/$/, '')}${path}`, {
            ...options,
            headers,
        }).then(async (response) => {
            const payload = await safeJson(response);

            if (!response.ok) {
                throw new Error(payload?.message || firstValidationError(payload) || 'La API respondió con error.');
            }

            return payload;
        });
    }

    function safeJson(response) {
        return response.json().catch(() => null);
    }

    function firstValidationError(payload) {
        if (!payload?.errors) {
            return null;
        }

        const firstKey = Object.keys(payload.errors)[0];
        return firstKey && Array.isArray(payload.errors[firstKey])
            ? payload.errors[firstKey][0]
            : null;
    }

    function findActiveRequest(requests) {
        return requests.find((item) => !['completed', 'cancelled'].includes(normalizeStatus(item.status))) || null;
    }

    function normalizeStatus(status) {
        return `${status || ''}`.trim().toLowerCase();
    }

    function statusLabel(status) {
        const map = {
            created: 'Creada',
            accepted: 'Aceptada',
            assigned: 'Asignada',
            in_progress: 'En progreso',
            arrived: 'En sitio',
            completed: 'Completada',
            cancelled: 'Cancelada',
            quoted: 'Cotizada',
            pending: 'Pendiente',
        };

        return map[normalizeStatus(status)] || (status || 'Sin estado');
    }

    function statusTone(status) {
        const value = normalizeStatus(status);

        if (['completed', 'assigned', 'accepted', 'in_progress', 'arrived'].includes(value)) {
            return 'success';
        }

        if (value === 'cancelled') {
            return 'danger';
        }

        return 'warning';
    }

    function renderServicesPreview(targetId, services) {
        if (!services.length) {
            setHtml(targetId, emptyCard('No hay servicios activos publicados por la API.'));
            return;
        }

        setHtml(
            targetId,
            services.slice(0, 6).map((service) => `
                <article class="card-row">
                    <h4 class="card-row__title">${escapeHtml(service.name || 'Servicio')}</h4>
                    <p class="card-row__meta">${escapeHtml(service.description || 'Servicio activo disponible para solicitudes del cliente.')}</p>
                </article>
            `).join('')
        );
    }

    function renderDashboardVehicleState(targetId, vehicles) {
        if (!vehicles.length) {
            setHtml(targetId, emptyCard('Aún no registras vehículos. Ve a Cuenta para cargar al menos uno antes de solicitar asistencia.'));
            return;
        }

        const primary = vehicles[0];

        setHtml(targetId, `
            <article class="vehicle-card">
                <h4 class="vehicle-card__title">${escapeHtml(vehicleLabel(primary))}</h4>
                <p class="card-row__meta">Tipo: ${escapeHtml(primary.vehicleType?.name || typeLabel(primary.vehicle_type_id))}</p>
                <p class="card-row__meta">Total registrados: ${vehicles.length}</p>
            </article>
        `);
    }

    function renderDashboardActiveState(targetId, active) {
        if (!active) {
            setHtml(targetId, emptyCard('No tienes una solicitud activa. Cuando generes una asistencia, verás aquí el folio, servicio y dirección.'));
            return;
        }

        setHtml(targetId, requestCard(active, {
            includeActions: `<div class="actions-inline"><a class="button button--secondary" href="${app.routes.active}">Abrir timeline</a></div>`,
        }));
    }

    function renderDashboardBlockers(targetId, vehicles, active) {
        const blocks = [];

        if (!vehicles.length) {
            blocks.push(noticeCard('warning', 'No puedes crear una asistencia real hasta registrar al menos un vehículo.'));
        }

        if (active) {
            blocks.push(noticeCard('warning', 'Ya existe una solicitud activa. El backend impide crear otra mientras siga abierta.'));
        }

        setHtml(targetId, blocks.join(''));
    }

    function renderRequestBlockingState(active, noVehicles) {
        const blocks = [];

        if (noVehicles) {
            blocks.push(noticeCard(
                'warning',
                `No tienes vehículos disponibles. <a class="text-link" href="${app.routes.account}">Regístralo en Cuenta</a> antes de continuar.`
            ));
        }

        if (active) {
            blocks.push(noticeCard(
                'warning',
                `Ya tienes una solicitud activa (${escapeHtml(active.public_id || `#${active.id}`)}). <a class="text-link" href="${app.routes.active}">Abre el servicio activo</a>.`
            ));
        }

        setHtml('requestBlockingState', blocks.join(''));
    }

    function renderActiveSummary(targetId, active, statusPayload) {
        const summary = statusPayload || {};

        setHtml(targetId, requestCard(active, {
            includeActions: `
                <div class="actions-inline">
                    <span class="badge badge--${statusTone(summary.status || active.status)}">${escapeHtml(statusLabel(summary.status || active.status))}</span>
                    ${summary.cancel_reason ? `<span class="badge badge--danger">Motivo: ${escapeHtml(summary.cancel_reason)}</span>` : ''}
                </div>
            `,
        }));
    }

    function renderTimeline(targetId, timelineData, fallbackStatus) {
        if (!timelineData) {
            setHtml(targetId, emptyCard('No fue posible cargar el timeline.'));
            return;
        }

        const history = Array.isArray(timelineData.history) ? timelineData.history : [];
        const events = Array.isArray(timelineData.events) ? timelineData.events : [];
        const merged = [...history, ...events].sort((a, b) => {
            return new Date(a.created_at || a.updated_at || 0) - new Date(b.created_at || b.updated_at || 0);
        });

        if (!merged.length) {
            setHtml(targetId, `
                <article class="timeline-item is-${statusTone(fallbackStatus)}">
                    <strong>${escapeHtml(statusLabel(fallbackStatus))}</strong>
                    <p class="timeline-item__meta">La API aún no tiene más movimientos para esta solicitud.</p>
                </article>
            `);
            return;
        }

        setHtml(targetId, merged.map((item) => {
            const itemStatus = item.status || item.event_code || fallbackStatus;
            const metadata = item.metadata || {};
            const description = metadata.message || metadata.description || item.event_code || 'Movimiento registrado.';

            return `
                <article class="timeline-item is-${statusTone(itemStatus)}">
                    <strong>${escapeHtml(statusLabel(itemStatus))}</strong>
                    <p class="timeline-item__meta">${escapeHtml(description)}</p>
                    <p class="timeline-item__meta">${escapeHtml(formatDateTime(item.created_at || item.updated_at))}</p>
                </article>
            `;
        }).join(''));
    }

    function bindCancelForm(active, statusPayload) {
        const form = document.getElementById('cancelRequestForm');
        const button = document.getElementById('cancelSubmitButton');

        if (!form || !button) {
            return;
        }

        const normalizedStatus = normalizeStatus(statusPayload?.status || active?.status);
        const canCancel = ['created', 'accepted', 'assigned'].includes(normalizedStatus);

        disableCancelForm(!canCancel);

        if (!canCancel || !active) {
            return;
        }

        bindElementEvent(form, 'active:cancel', 'submit', async (event) => {
            event.preventDefault();
            button.disabled = true;

            try {
                const payload = formToObject(form);

                await api(`/api/v1/client/assistance-requests/${active.id}/cancel`, {
                    method: 'PATCH',
                    body: JSON.stringify(payload),
                });

                showToast('Solicitud cancelada correctamente.');
                await loadActivePage();
            } catch (error) {
                showToast(error.message || 'No fue posible cancelar la solicitud.');
            } finally {
                button.disabled = false;
            }
        });
    }

    function disableCancelForm(disabled) {
        const form = document.getElementById('cancelRequestForm');

        if (!form) {
            return;
        }

        Array.from(form.elements).forEach((element) => {
            element.disabled = disabled;
        });
    }

    function renderRequestsHistory(targetId, requests) {
        if (!requests.length) {
            setHtml(targetId, emptyCard('No existen solicitudes cerradas todavía.'));
            return;
        }

        setHtml(targetId, requests.map((item) => requestCard(item)).join(''));
    }

    function renderPayments(targetId, payments, compact) {
        if (!payments.length) {
            setHtml(targetId, emptyCard('No hay pagos registrados todavía.'));
            return;
        }

        setHtml(targetId, payments.map((payment) => {
            const request = payment.assistance_request || payment.assistanceRequest || {};

            return `
                <article class="payment-card">
                    <h4 class="card-row__title">Pago ${escapeHtml(payment.transaction_id || `#${payment.id}`)}</h4>
                    <p class="card-row__meta">Solicitud: ${escapeHtml(request.public_id || 'N/D')}</p>
                    <p class="card-row__meta">Método: ${escapeHtml(payment.payment_method || 'N/D')} · Estatus: ${escapeHtml(statusLabel(payment.status || 'completed'))}</p>
                    <p class="card-row__meta">Monto: ${escapeHtml(currency(payment.amount || 0))}</p>
                    ${compact ? '' : `<p class="card-row__meta">Emitido: ${escapeHtml(formatDateTime(payment.created_at || payment.updated_at))}</p>`}
                </article>
            `;
        }).join(''));
    }

    function renderPaymentMethods(targetId, methods, paymentTypes) {
        if (!methods.length) {
            setHtml(targetId, emptyCard('No has guardado métodos de pago.'));
            return;
        }

        const map = new Map(paymentTypes.map((item) => [item.code, item.name]));

        setHtml(targetId, methods.map((method) => `
            <article class="card-row">
                <h4 class="card-row__title">${escapeHtml(map.get(method.method_name) || method.method_name || 'Método')}</h4>
                <p class="card-row__meta">${escapeHtml(method.method_details || 'Sin detalle')}</p>
            </article>
        `).join(''));
    }

    function fillCompletedRequestSelect(targetId, requests, payments) {
        const paidIds = new Set(
            payments
                .filter((payment) => normalizeStatus(payment.status) === 'completed')
                .map((payment) => String(
                    payment.assistance_request_id ||
                    payment.assistanceRequest?.id ||
                    payment.assistance_request?.id ||
                    ''
                ))
        );

        const eligible = requests.filter((item) => {
            return normalizeStatus(item.status) === 'completed' && !paidIds.has(String(item.id));
        });

        fillSelect(
            targetId,
            eligible,
            eligible.length ? 'Selecciona solicitud' : 'No hay solicitudes pendientes de pago',
            (item) => ({
                value: item.id,
                label: `${item.public_id || `#${item.id}`} · ${item.service?.name || 'Servicio'}`,
            })
        );
    }

    function renderAccountProfile(targetId, payload) {
        if (!payload) {
            setHtml(targetId, emptyCard('No fue posible cargar el perfil del cliente.'));
            return;
        }

        const user = payload.user || {};
        const roles = Array.isArray(payload.roles)
            ? payload.roles.map((role) => role.name || role.code).join(', ')
            : 'Cliente';

        setHtml(targetId, `
            <article class="profile-card">
                <h4 class="card-row__title">${escapeHtml(user.email || 'Sin correo')}</h4>
                <p class="card-row__meta">Roles: ${escapeHtml(roles || 'Cliente')}</p>
                <p class="card-row__meta">ID de usuario: ${escapeHtml(String(user.id || 'N/D'))}</p>
            </article>
        `);
    }

    function renderAccountVehicles(targetId, vehicles) {
        if (!vehicles.length) {
            setHtml(targetId, emptyCard('Aún no registras vehículos.'));
            return;
        }

        setHtml(targetId, vehicles.map((vehicle) => `
            <article class="vehicle-card">
                <h4 class="vehicle-card__title">${escapeHtml(vehicleLabel(vehicle))}</h4>
                <p class="card-row__meta">Tipo: ${escapeHtml(vehicle.vehicleType?.name || typeLabel(vehicle.vehicle_type_id))}</p>
                <p class="card-row__meta">Año: ${escapeHtml(String(vehicle.year || 'N/D'))}</p>
                <div class="actions-inline">
                    <button type="button" class="button button--ghost" data-edit-vehicle="${vehicle.id}">Editar</button>
                    <button type="button" class="button button--danger" data-delete-vehicle="${vehicle.id}">Eliminar</button>
                </div>
            </article>
        `).join(''));
    }

    function populateAccountForms(profilePayload) {
        const user = profilePayload?.user || {};
        const emailInput = document.getElementById('accountEmailInput');

        if (emailInput) {
            emailInput.value = user.email || '';
        }
    }

    function hydrateVehicleTypeOptions() {
        const options = Array.isArray(app.vehicleTypeOptions) ? app.vehicleTypeOptions : [];

        fillSelect(
            'vehicleTypeInput',
            options,
            options.length ? 'Selecciona tipo' : 'Catálogo no disponible',
            (item) => ({
                value: item.id,
                label: item.name,
            })
        );
    }

    function resetVehicleForm() {
        const form = document.getElementById('vehicleForm');
        const vehicleIdInput = document.getElementById('vehicleIdInput');
        const vehicleSubmitButton = document.getElementById('vehicleSubmitButton');

        if (form) {
            form.reset();
        }

        if (vehicleIdInput) {
            vehicleIdInput.value = '';
        }

        if (vehicleSubmitButton) {
            vehicleSubmitButton.textContent = 'Guardar vehículo';
        }
    }

    function requestCard(item, options = {}) {
        return `
            <article class="request-card">
                <h4 class="request-card__title">${escapeHtml(item.service?.name || 'Asistencia')} · ${escapeHtml(item.public_id || `#${item.id}`)}</h4>
                <p class="request-card__meta">Estatus: ${escapeHtml(statusLabel(item.status))}</p>
                <p class="request-card__meta">Dirección: ${escapeHtml(item.pickup_address || 'Sin dirección')}</p>
                <p class="request-card__meta">Vehículo: ${escapeHtml(vehicleLabel(item.vehicle || {}))}</p>
                <p class="request-card__meta">Creada: ${escapeHtml(formatDateTime(item.created_at))}</p>
                ${options.includeActions || ''}
            </article>
        `;
    }

    function noticeCard(tone, message) {
        const isDanger = tone === 'danger';

        return `
            <article class="notice-card" style="border-color: ${isDanger ? 'rgba(220,38,38,0.18)' : 'rgba(161,98,7,0.18)'}; background: ${isDanger ? 'rgba(220,38,38,0.05)' : 'rgba(161,98,7,0.06)'}; color: ${isDanger ? '#b91c1c' : '#92400e'};">
                ${message}
            </article>
        `;
    }

    function emptyCard(message) {
        return `<article class="empty-state">${message}</article>`;
    }

    function formToObject(form) {
        const data = new FormData(form);
        return Object.fromEntries(data.entries());
    }

    function fillSelect(targetId, items, placeholder, transformer) {
        const select = document.getElementById(targetId);

        if (!select) {
            return;
        }

        const options = items.map((item) => {
            const { value, label } = transformer(item);
            return `<option value="${escapeHtml(String(value))}">${escapeHtml(label)}</option>`;
        }).join('');

        select.innerHTML = `<option value="">${escapeHtml(placeholder)}</option>${options}`;
    }

    function fillVehicleSelect(targetId, vehicles) {
        fillSelect(
            targetId,
            vehicles,
            vehicles.length ? 'Selecciona un vehículo' : 'No hay vehículos disponibles',
            (item) => ({
                value: item.id,
                label: vehicleLabel(item),
            })
        );
    }

    function vehicleLabel(vehicle) {
        const brand = vehicle.brand || 'Vehículo';
        const model = vehicle.model || '';
        const plate = vehicle.plate || 'sin placas';

        return `${brand} ${model}`.trim() + ` · ${plate}`;
    }

    function typeLabel(typeId) {
        const match = (app.vehicleTypeOptions || []).find((item) => String(item.id) === String(typeId));
        return match?.name || 'Tipo no identificado';
    }

    function hydrateCurrentLocation() {
        if (!navigator.geolocation) {
            showToast('Tu navegador no soporta geolocalización.');
            return;
        }

        navigator.geolocation.getCurrentPosition(
            (position) => {
                const lat = document.getElementById('requestLat');
                const lng = document.getElementById('requestLng');

                if (lat) {
                    lat.value = position.coords.latitude.toFixed(6);
                }

                if (lng) {
                    lng.value = position.coords.longitude.toFixed(6);
                }

                showToast('Ubicación cargada correctamente.');
            },
            () => {
                showToast('No fue posible obtener la ubicación del dispositivo.');
            }
        );
    }

    function bindElementEvent(element, key, eventName, handler) {
        if (!element) {
            return;
        }

        let registry = listenerRegistry.get(element);

        if (!registry) {
            registry = {};
            listenerRegistry.set(element, registry);
        }

        const previous = registry[key];

        if (previous) {
            element.removeEventListener(eventName, previous);
        }

        registry[key] = handler;
        element.addEventListener(eventName, handler);
    }

    function setText(id, value) {
        const element = document.getElementById(id);
        if (element) {
            element.textContent = value;
        }
    }

    function setHtml(id, value) {
        const element = document.getElementById(id);
        if (element) {
            element.innerHTML = value;
        }
    }

    function formatDateTime(value) {
        if (!value) {
            return 'Sin fecha';
        }

        const date = new Date(value);

        if (Number.isNaN(date.getTime())) {
            return value;
        }

        return new Intl.DateTimeFormat('es-MX', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
        }).format(date);
    }

    function currency(value) {
        return new Intl.NumberFormat('es-MX', {
            style: 'currency',
            currency: 'MXN',
        }).format(Number(value || 0));
    }

    function escapeHtml(value) {
        return `${value ?? ''}`
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function showToast(message) {
        if (!toast) {
            return;
        }

        toast.textContent = message;
        toast.classList.add('is-visible');

        clearTimeout(showToast.timer);

        showToast.timer = setTimeout(() => {
            toast.classList.remove('is-visible');
        }, 3200);
    }
})();
