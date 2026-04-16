@extends('user.layouts.app')

@push('page_styles')
<style>
    .history-hero {
        display: grid;
        gap: 14px;
    }

    .history-hero__copy {
        display: grid;
        gap: 6px;
    }

    .history-hero__copy h2 {
        margin: 0;
        color: #0f172a;
        line-height: 1.08;
        font-size: clamp(1.35rem, 2vw, 1.9rem);
    }

    .history-hero__copy p {
        margin: 0;
        color: #475569;
        line-height: 1.55;
        font-size: 0.95rem;
    }

    .history-stats {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
    }

    .history-card {
        padding: 18px;
        border-radius: 22px;
        border: 1px solid rgba(15, 23, 42, 0.08);
        background: #ffffff;
        display: grid;
        gap: 14px;
    }

    .history-card__top {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        align-items: center;
        flex-wrap: wrap;
    }

    .history-card__title {
        margin: 0;
        color: #0f172a;
        font-size: 1rem;
        line-height: 1.3;
    }

    .history-card__meta {
        margin: 0;
        color: #64748b;
        font-size: 0.86rem;
        line-height: 1.45;
    }

    .history-status {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 7px 10px;
        border-radius: 999px;
        font-size: 0.76rem;
        font-weight: 800;
        white-space: nowrap;
    }

    .history-status--success {
        background: rgba(22, 163, 74, 0.10);
        color: #15803d;
    }

    .history-status--warning {
        background: rgba(245, 158, 11, 0.12);
        color: #b45309;
    }

    .history-status--danger {
        background: rgba(239, 68, 68, 0.10);
        color: #b91c1c;
    }

    .history-status--info {
        background: rgba(37, 99, 235, 0.10);
        color: #1d4ed8;
    }

    .history-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .history-grid__item {
        padding: 13px 14px;
        border-radius: 16px;
        background: #f8fafc;
        border: 1px solid rgba(148, 163, 184, 0.14);
        display: grid;
        gap: 5px;
    }

    .history-grid__item span {
        color: #64748b;
        font-size: 0.78rem;
    }

    .history-grid__item strong {
        color: #0f172a;
        line-height: 1.45;
        word-break: break-word;
        font-size: 0.94rem;
    }

    .history-list {
        display: grid;
        gap: 12px;
    }

    .history-empty {
        padding: 18px;
        border-radius: 18px;
        border: 1px dashed rgba(148, 163, 184, 0.24);
        background: rgba(248, 250, 252, 0.92);
        color: #64748b;
        text-align: center;
        line-height: 1.55;
    }

    .history-loading {
        padding: 18px;
        border-radius: 18px;
        background: rgba(37, 99, 235, 0.06);
        color: #1d4ed8;
        text-align: center;
        line-height: 1.55;
    }

    @media (max-width: 900px) {
        .history-stats,
        .history-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 560px) {
        .history-card {
            padding: 15px;
        }

        .history-grid__item {
            padding: 12px;
        }

        .history-hero__copy h2 {
            font-size: 1.25rem;
        }

        .history-hero__copy p,
        .history-card__meta {
            font-size: 0.88rem;
        }
    }
</style>
@endpush

@section('content')
<section class="panel history-hero">
    <div class="history-hero__copy">
        <p class="hero-panel__eyebrow">Historial</p>
        <h2>Tus servicios y pagos anteriores</h2>
        <p>Aquí puedes revisar servicios terminados o cancelados y ver cómo quedó cada pago.</p>
    </div>

    <div class="actions-inline">
        <a href="{{ route('user.pagos') }}" class="button button--secondary">Ver pagos</a>
        <a href="{{ route('user.notificaciones') }}" class="button button--ghost">Notificaciones</a>
    </div>
</section>

<section class="history-stats">
    <article class="stat-card">
        <span class="stat-card__label">Servicios cerrados</span>
        <strong id="historyClosedCount">—</strong>
    </article>

    <article class="stat-card">
        <span class="stat-card__label">Servicios completados</span>
        <strong id="historyCompletedCount">—</strong>
    </article>

    <article class="stat-card">
        <span class="stat-card__label">Pagos registrados</span>
        <strong id="historyPaymentsCount">—</strong>
    </article>
</section>

<section class="grid-two">
    <article class="panel">
        <div class="section-head">
            <h3>Servicios anteriores</h3>
            <span class="section-pill">Cerrados</span>
        </div>

        <div id="historyRequestsList" class="history-list">
            <article class="history-loading">Cargando tus servicios…</article>
        </div>
    </article>

    <article class="panel">
        <div class="section-head">
            <h3>Pagos</h3>
            <span class="section-pill">Resumen</span>
        </div>

        <div id="historyPaymentsList" class="history-list">
            <article class="history-loading">Cargando tus pagos…</article>
        </div>
    </article>
</section>
@endsection

@push('page_scripts')
<script>
(function () {
    function boot() {
        const app = window.ZYGA_CLIENT_APP || {};

        if ((app.page || '') !== 'history') {
            return;
        }

        loadHistory();
    }

    async function loadHistory() {
        const app = window.ZYGA_CLIENT_APP || {};
        const requestsContainer = document.getElementById('historyRequestsList');
        const paymentsContainer = document.getElementById('historyPaymentsList');

        if (!app.apiBaseUrl || !app.token) {
            renderErrorState('No pudimos abrir tu historial. Vuelve a iniciar sesión.');
            return;
        }

        try {
            const [requestsPayload, paymentsPayload] = await Promise.all([
                api(app, '/api/v1/client/assistance-requests'),
                api(app, '/api/v1/client/payments'),
            ]);

            const allRequests = extractList(requestsPayload && requestsPayload.data, ['requests', 'items']);
            const allPayments = extractList(paymentsPayload && paymentsPayload.data, ['payments', 'items']);

            const closedRequests = allRequests.filter(function (item) {
                const status = normalize(item && item.status);
                return status === 'completed' || status === 'cancelled';
            });

            const completedRequests = closedRequests.filter(function (item) {
                return normalize(item && item.status) === 'completed';
            });

            const sortedRequests = closedRequests.sort(function (a, b) {
                return String(b.created_at || b.updated_at || '').localeCompare(String(a.created_at || a.updated_at || ''));
            });

            const sortedPayments = allPayments.sort(function (a, b) {
                return String(b.created_at || '').localeCompare(String(a.created_at || ''));
            });

            setText('historyClosedCount', String(closedRequests.length));
            setText('historyCompletedCount', String(completedRequests.length));
            setText('historyPaymentsCount', String(sortedPayments.length));

            renderRequests(sortedRequests, requestsContainer);
            renderPayments(sortedPayments, paymentsContainer);
        } catch (error) {
            renderErrorState(error.message || 'No pudimos cargar tu historial.');
        }
    }

    function renderRequests(items, container) {
        if (!container) return;

        if (!items.length) {
            container.innerHTML = '<article class="history-empty">Aún no tienes servicios cerrados para mostrar.</article>';
            return;
        }

        container.innerHTML = items.map(function (item) {
            const status = normalize(item.status);
            const serviceName = (item.service && item.service.name) ? item.service.name : 'Servicio';
            const folio = item.public_id || ('#' + (item.id || '—'));

            return `
                <article class="history-card">
                    <div class="history-card__top">
                        <div>
                            <h4 class="history-card__title">${escapeHtml(serviceName)}</h4>
                            <p class="history-card__meta">Folio ${escapeHtml(folio)}</p>
                        </div>

                        <span class="history-status ${statusClass(status)}">
                            ${escapeHtml(statusLabel(status))}
                        </span>
                    </div>

                    <div class="history-grid">
                        <article class="history-grid__item">
                            <span>Dirección</span>
                            <strong>${escapeHtml(item.pickup_address || 'Sin dirección registrada')}</strong>
                        </article>

                        <article class="history-grid__item">
                            <span>Referencia</span>
                            <strong>${escapeHtml(item.pickup_reference || 'Sin referencia')}</strong>
                        </article>

                        <article class="history-grid__item">
                            <span>Monto</span>
                            <strong>${escapeHtml(formatMoney(item.final_amount || item.quoted_amount || 0))}</strong>
                        </article>

                        <article class="history-grid__item">
                            <span>Pago</span>
                            <strong>${escapeHtml(paymentStatusLabel(item.payment_status || 'pending'))}</strong>
                        </article>
                    </div>
                </article>
            `;
        }).join('');
    }

    function renderPayments(items, container) {
        if (!container) return;

        if (!items.length) {
            container.innerHTML = '<article class="history-empty">Aún no tienes pagos registrados.</article>';
            return;
        }

        container.innerHTML = items.map(function (item) {
            const request = item.assistanceRequest || item.assistance_request || {};
            const serviceName = (request.service && request.service.name) ? request.service.name : 'Servicio';
            const paymentStatus = normalize(item.status);

            return `
                <article class="history-card">
                    <div class="history-card__top">
                        <div>
                            <h4 class="history-card__title">${escapeHtml(serviceName)}</h4>
                            <p class="history-card__meta">${escapeHtml((request.public_id || ('#' + (request.id || '—'))))}</p>
                        </div>

                        <span class="history-status ${statusClass(paymentStatus)}">
                            ${escapeHtml(paymentStatusLabel(paymentStatus))}
                        </span>
                    </div>

                    <div class="history-grid">
                        <article class="history-grid__item">
                            <span>Monto</span>
                            <strong>${escapeHtml(formatMoney(item.amount || 0))}</strong>
                        </article>

                        <article class="history-grid__item">
                            <span>Método</span>
                            <strong>${escapeHtml(paymentMethodLabel(item.payment_method || ''))}</strong>
                        </article>

                        <article class="history-grid__item">
                            <span>Referencia</span>
                            <strong>${escapeHtml(item.reference || 'Sin referencia')}</strong>
                        </article>

                        <article class="history-grid__item">
                            <span>Fecha</span>
                            <strong>${escapeHtml(formatDateTime(item.created_at))}</strong>
                        </article>
                    </div>
                </article>
            `;
        }).join('');
    }

    function renderErrorState(message) {
        const requestsContainer = document.getElementById('historyRequestsList');
        const paymentsContainer = document.getElementById('historyPaymentsList');

        if (requestsContainer) {
            requestsContainer.innerHTML = '<article class="history-empty">' + escapeHtml(message) + '</article>';
        }

        if (paymentsContainer) {
            paymentsContainer.innerHTML = '<article class="history-empty">' + escapeHtml(message) + '</article>';
        }

        setText('historyClosedCount', '0');
        setText('historyCompletedCount', '0');
        setText('historyPaymentsCount', '0');
    }

    async function api(app, path) {
        const response = await fetch(app.apiBaseUrl + path, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Authorization': 'Bearer ' + app.token
            }
        });

        const payload = await response.json().catch(function () {
            return {};
        });

        if (!response.ok) {
            throw new Error(readableApiMessage(payload.message || 'No pudimos consultar tu historial.'));
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

        for (const key of ['items', 'requests', 'payments', 'data']) {
            if (Array.isArray(data[key])) {
                return data[key];
            }
        }

        return [];
    }

    function normalize(value) {
        return String(value || '').trim().toLowerCase();
    }

    function statusLabel(status) {
        return {
            completed: 'Completado',
            cancelled: 'Cancelado',
            pending: 'Pendiente',
            pending_validation: 'En revisión',
            paid: 'Confirmado',
            failed: 'Fallido',
            rejected: 'Rechazado'
        }[normalize(status)] || 'En proceso';
    }

    function paymentStatusLabel(status) {
        return {
            pending: 'Pendiente',
            pending_validation: 'En revisión',
            paid: 'Confirmado',
            completed: 'Confirmado',
            failed: 'Fallido',
            rejected: 'Rechazado'
        }[normalize(status)] || 'Pendiente';
    }

    function paymentMethodLabel(value) {
        return {
            cash: 'Efectivo',
            transfer: 'Transferencia'
        }[normalize(value)] || (value ? String(value) : 'No definido');
    }

    function statusClass(status) {
        const value = normalize(status);

        if (value === 'completed' || value === 'paid') return 'history-status--success';
        if (value === 'pending' || value === 'pending_validation') return 'history-status--warning';
        if (value === 'cancelled' || value === 'failed' || value === 'rejected') return 'history-status--danger';
        return 'history-status--info';
    }

    function formatMoney(value) {
        const amount = Number(value || 0);

        if (!Number.isFinite(amount)) {
            return '—';
        }

        return new Intl.NumberFormat('es-MX', {
            style: 'currency',
            currency: 'MXN',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(amount);
    }

    function formatDateTime(value) {
        if (!value) return 'Sin fecha';

        const date = new Date(value);

        if (Number.isNaN(date.getTime())) {
            return 'Sin fecha';
        }

        return new Intl.DateTimeFormat('es-MX', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        }).format(date);
    }

    function readableApiMessage(message) {
        const text = String(message || '').trim();

        if (!text) {
            return 'No pudimos cargar tu historial.';
        }

        if (text.toLowerCase().includes('token')) {
            return 'Tu sesión ya no es válida. Vuelve a iniciar sesión.';
        }

        return text;
    }

    function setText(id, value) {
        const node = document.getElementById(id);
        if (node) node.textContent = value;
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
