@extends('user.layouts.app')

@push('page_styles')
<style>
    .payment-request-summary {
        display: grid;
        gap: 12px;
        padding: 18px;
        border-radius: 22px;
        border: 1px solid rgba(15, 23, 42, 0.08);
        background:
            linear-gradient(135deg, rgba(249, 115, 22, 0.08), rgba(37, 99, 235, 0.06)),
            #ffffff;
    }

    .payment-request-summary[hidden] {
        display: none;
    }

    .payment-request-summary__grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .payment-request-summary__item {
        border-radius: 16px;
        border: 1px solid rgba(148, 163, 184, 0.14);
        background: rgba(255, 255, 255, 0.92);
        padding: 14px;
    }

    .payment-request-summary__item span {
        display: block;
        font-size: 0.8rem;
        color: #64748b;
        margin-bottom: 6px;
    }

    .payment-request-summary__item strong {
        display: block;
        color: #0f172a;
        line-height: 1.5;
        word-break: break-word;
    }

    .payment-request-summary__total {
        font-size: 1.9rem;
        line-height: 1.1;
        letter-spacing: -0.04em;
    }

    .payment-inline-note {
        padding: 12px 14px;
        border-radius: 16px;
        background: rgba(15, 23, 42, 0.04);
        border: 1px solid rgba(148, 163, 184, 0.14);
        color: #475569;
        line-height: 1.55;
    }

    .payment-inline-note--warning {
        background: rgba(245, 158, 11, 0.12);
        color: #92400e;
        border-color: rgba(161, 98, 7, 0.18);
    }

    @media (max-width: 640px) {
        .payment-request-summary__grid {
            grid-template-columns: 1fr;
        }

        .payment-request-summary__total {
            font-size: 1.6rem;
        }
    }
</style>
@endpush

@section('content')
<section class="panel hero-panel hero-panel--compact">
    <div>
        <p class="hero-panel__eyebrow">Pagos y métodos</p>
        <h2>Registra tu pago con el importe final de la asistencia, sin volver a capturarlo.</h2>
        <p>
            El sistema toma automáticamente el total de la asistencia completada. Por ahora puedes registrar
            pagos en efectivo o por transferencia de forma simple y ordenada.
        </p>
    </div>
</section>

<section class="panel">
    <div class="section-head">
        <h3>Cómo funciona esta sección</h3>
        <span class="section-pill">Resumen</span>
    </div>

    <div class="stack-list">
        <article class="card-row">
            <h4 class="card-row__title">Monto definido automáticamente</h4>
            <p class="card-row__meta">
                Ya no necesitas escribir el total manualmente. El sistema usa el importe final de la asistencia completada.
            </p>
        </article>

        <article class="card-row">
            <h4 class="card-row__title">Efectivo</h4>
            <p class="card-row__meta">
                Se registra como pago completado en el momento.
            </p>
        </article>

        <article class="card-row">
            <h4 class="card-row__title">Transferencia</h4>
            <p class="card-row__meta">
                Se registra con referencia y queda pendiente de validación administrativa.
            </p>
        </article>
    </div>
</section>

<section class="grid-two">
    <article class="panel">
        <div class="section-head">
            <h3>Métodos guardados</h3>
            <span class="section-pill">Cuenta</span>
        </div>

        <div class="helper-note">
            Actualmente puedes registrar pagos en efectivo o por transferencia.
        </div>

        <div id="paymentMethodsList" class="stack-list">
            <article class="empty-state">Cargando métodos de pago...</article>
        </div>
    </article>

    <article class="panel">
        <div class="section-head">
            <h3>Pagos registrados</h3>
            <span class="section-pill">Historial</span>
        </div>

        <div class="helper-note">
            Aquí se muestran pagos completados y pagos enviados a validación.
        </div>

        <div id="paymentsList" class="stack-list">
            <article class="empty-state">Cargando pagos...</article>
        </div>
    </article>
</section>

<section class="grid-two">
    <article class="panel">
        <div class="section-head">
            <h3>Agregar método de pago</h3>
            <span class="section-pill">Nuevo</span>
        </div>

        <form id="paymentMethodForm" class="form-grid" autocomplete="off">
            <label class="form-field form-field--full">
                <span>Tipo de método</span>
                <select id="paymentMethodType" name="method_name" required>
                    <option value="">Cargando tipos...</option>
                </select>
            </label>

            <label class="form-field form-field--full">
                <span>Detalle</span>
                <input
                    type="text"
                    name="method_details"
                    id="paymentMethodDetails"
                    placeholder="Ej. Efectivo contra entrega o BBVA cuenta personal"
                    required
                >
            </label>

            <div class="form-actions form-field--full">
                <button type="submit" class="button button--primary">Guardar método</button>
            </div>
        </form>
    </article>

    <article class="panel">
        <div class="section-head">
            <h3>Registrar pago</h3>
            <span class="section-pill">Solicitud concluida</span>
        </div>

        <div class="helper-note">
            Selecciona una asistencia completada y el sistema mostrará automáticamente el importe correspondiente.
        </div>

        <form id="paymentRegisterForm" class="form-grid" autocomplete="off">
            <label class="form-field form-field--full">
                <span>Solicitud completada</span>
                <select id="paymentRequestId" name="assistance_request_id" required>
                    <option value="">Cargando solicitudes elegibles...</option>
                </select>
            </label>

            <div id="paymentRequestSummary" class="payment-request-summary">
                <div class="payment-inline-note">
                    Selecciona una solicitud para ver el monto que el sistema tomará automáticamente.
                </div>

                <div class="payment-request-summary__grid">
                    <article class="payment-request-summary__item">
                        <span>Folio</span>
                        <strong id="paymentSummaryPublicId">Pendiente</strong>
                    </article>

                    <article class="payment-request-summary__item">
                        <span>Servicio</span>
                        <strong id="paymentSummaryService">Pendiente</strong>
                    </article>

                    <article class="payment-request-summary__item">
                        <span>Vehículo</span>
                        <strong id="paymentSummaryVehicle">Pendiente</strong>
                    </article>

                    <article class="payment-request-summary__item">
                        <span>Estatus financiero actual</span>
                        <strong id="paymentSummaryStatus">Pendiente</strong>
                    </article>

                    <article class="payment-request-summary__item" style="grid-column: 1 / -1;">
                        <span>Total a registrar</span>
                        <strong id="paymentSummaryAmount" class="payment-request-summary__total">—</strong>
                    </article>
                </div>
            </div>

            <label class="form-field">
                <span>Método de pago</span>
                <select id="paymentRegisterMethod" name="payment_method" required>
                    <option value="">Selecciona método</option>
                </select>
            </label>

            <label class="form-field">
                <span>Referencia</span>
                <input
                    type="text"
                    name="reference"
                    id="paymentReference"
                    placeholder="Obligatoria para transferencia"
                >
            </label>

            <label class="form-field form-field--full">
                <span>Notas</span>
                <textarea
                    name="notes"
                    id="paymentNotes"
                    rows="4"
                    placeholder="Ej. Pago liquidado en efectivo con el proveedor, transferencia realizada desde BBVA, etc."
                ></textarea>
            </label>

            <div id="paymentMethodNote" class="payment-inline-note payment-inline-note--warning">
                Si eliges transferencia, debes capturar una referencia válida. Ese pago quedará pendiente de validación.
            </div>

            <div class="form-actions form-field--full">
                <button type="submit" id="paymentRegisterSubmit" class="button button--primary">
                    Registrar pago
                </button>
            </div>
        </form>
    </article>
</section>
@endsection

@push('page_scripts')
<script>
(function () {
    function boot() {
        const form = document.getElementById('paymentRegisterForm');
        const requestSelect = document.getElementById('paymentRequestId');
        const methodSelect = document.getElementById('paymentRegisterMethod');
        const referenceInput = document.getElementById('paymentReference');
        const submitButton = document.getElementById('paymentRegisterSubmit');

        if (!form || !requestSelect || !methodSelect || !referenceInput || !submitButton) {
            return;
        }

        const app = window.ZYGA_CLIENT_APP || {};
        const requestMap = new Map();

        function normalize(value) {
            return String(value || '').trim().toLowerCase();
        }

        function statusLabel(value) {
            const map = {
                pending: 'Pendiente',
                pending_validation: 'Pendiente de validación',
                paid: 'Pagado',
                completed: 'Completado',
                failed: 'Fallido',
                rejected: 'Rechazado',
            };

            const key = normalize(value);
            return map[key] || (value || 'Sin estado');
        }

        function vehicleLabel(vehicle) {
            if (!vehicle || typeof vehicle !== 'object') {
                return 'Vehículo no identificado';
            }

            const brand = String(vehicle.brand || '').trim();
            const model = String(vehicle.model || '').trim();
            const plate = String(vehicle.plate || '').trim();
            const base = (brand + ' ' + model).trim();

            return plate ? (base ? (base + ' · ' + plate) : plate) : (base || 'Vehículo no identificado');
        }

        function money(value) {
            const amount = Number(value || 0);

            if (!Number.isFinite(amount)) {
                return '—';
            }

            return new Intl.NumberFormat('es-MX', {
                style: 'currency',
                currency: 'MXN',
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            }).format(amount);
        }

        async function api(path) {
            const response = await fetch(app.apiBaseUrl + path, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': 'Bearer ' + app.token,
                },
            });

            const payload = await response.json().catch(function () {
                return {};
            });

            if (!response.ok) {
                throw new Error(payload.message || 'No fue posible cargar la información de pagos.');
            }

            return payload;
        }

        function setSummary(request) {
            const publicId = document.getElementById('paymentSummaryPublicId');
            const service = document.getElementById('paymentSummaryService');
            const vehicle = document.getElementById('paymentSummaryVehicle');
            const status = document.getElementById('paymentSummaryStatus');
            const amount = document.getElementById('paymentSummaryAmount');

            if (!request) {
                if (publicId) publicId.textContent = 'Pendiente';
                if (service) service.textContent = 'Pendiente';
                if (vehicle) vehicle.textContent = 'Pendiente';
                if (status) status.textContent = 'Pendiente';
                if (amount) amount.textContent = '—';
                return;
            }

            if (publicId) publicId.textContent = request.public_id || ('#' + request.id);
            if (service) service.textContent = request.service && request.service.name ? request.service.name : 'Servicio';
            if (vehicle) vehicle.textContent = vehicleLabel(request.vehicle || {});
            if (status) status.textContent = statusLabel(request.payment_status || 'pending');
            if (amount) amount.textContent = money(request.final_amount || request.quoted_amount || 0);
        }

        function fillEligibleRequests(requests, payments) {
            requestMap.clear();

            const blockedIds = new Set(
                (payments || [])
                    .filter(function (payment) {
                        return ['pending', 'pending_validation', 'completed'].includes(normalize(payment.status));
                    })
                    .map(function (payment) {
                        return String(
                            payment.assistance_request_id ||
                            (payment.assistanceRequest && payment.assistanceRequest.id) ||
                            (payment.assistance_request && payment.assistance_request.id) ||
                            ''
                        );
                    })
            );

            const eligible = (requests || []).filter(function (item) {
                return normalize(item.status) === 'completed' && !blockedIds.has(String(item.id));
            });

            requestSelect.innerHTML = '<option value="">' + (eligible.length ? 'Selecciona solicitud' : 'No hay solicitudes pendientes de pago') + '</option>';

            eligible.forEach(function (item) {
                requestMap.set(String(item.id), item);

                const option = document.createElement('option');
                option.value = String(item.id);
                option.textContent = (item.public_id || ('#' + item.id)) + ' · ' + ((item.service && item.service.name) ? item.service.name : 'Servicio');
                requestSelect.appendChild(option);
            });

            submitButton.disabled = eligible.length === 0;
            setSummary(null);
        }

        function syncTransferRule() {
            const isTransfer = normalize(methodSelect.value) === 'transfer';
            referenceInput.required = isTransfer;
        }

        async function refreshEligibleRequests() {
            if (!app.apiBaseUrl || !app.token) {
                return;
            }

            try {
                const [requestsPayload, paymentsPayload] = await Promise.all([
                    api('/api/v1/client/assistance-requests'),
                    api('/api/v1/client/payments'),
                ]);

                const requests = Array.isArray(requestsPayload.data) ? requestsPayload.data : [];
                const payments = Array.isArray(paymentsPayload.data) ? paymentsPayload.data : [];

                fillEligibleRequests(requests, payments);
            } catch (error) {
                console.error(error);
            }
        }

        requestSelect.addEventListener('change', function () {
            const selected = requestMap.get(String(requestSelect.value || ''));
            setSummary(selected || null);
        });

        methodSelect.addEventListener('change', syncTransferRule);
        syncTransferRule();

        refreshEligibleRequests();
        setTimeout(refreshEligibleRequests, 900);
    }

    window.addEventListener('load', boot, { once: true });
})();
</script>
@endpush
