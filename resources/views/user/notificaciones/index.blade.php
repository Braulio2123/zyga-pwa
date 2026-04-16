@extends('user.layouts.app')

@push('page_styles')
<style>
    .notifications-hero {
        display: grid;
        gap: 14px;
    }

    .notifications-hero__copy {
        display: grid;
        gap: 6px;
    }

    .notifications-hero__copy h2 {
        margin: 0;
        color: #0f172a;
        line-height: 1.08;
        font-size: clamp(1.35rem, 2vw, 1.9rem);
    }

    .notifications-hero__copy p {
        margin: 0;
        color: #475569;
        line-height: 1.55;
        font-size: 0.95rem;
    }

    .notifications-stats {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
    }

    .notifications-list {
        display: grid;
        gap: 12px;
    }

    .notification-card {
        padding: 16px;
        border-radius: 20px;
        border: 1px solid rgba(148, 163, 184, 0.16);
        background: #ffffff;
        display: grid;
        gap: 8px;
    }

    .notification-card.is-unread {
        border-color: rgba(37, 99, 235, 0.18);
        background: rgba(37, 99, 235, 0.05);
    }

    .notification-card__top {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        align-items: center;
        flex-wrap: wrap;
    }

    .notification-card__title {
        margin: 0;
        color: #0f172a;
        font-size: 0.98rem;
        line-height: 1.3;
    }

    .notification-card__state {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 800;
        white-space: nowrap;
        background: rgba(15, 23, 42, 0.06);
        color: #334155;
    }

    .notification-card__message {
        margin: 0;
        color: #475569;
        line-height: 1.55;
        font-size: 0.92rem;
    }

    .notification-card__meta {
        color: #64748b;
        font-size: 0.8rem;
        line-height: 1.45;
    }

    .notifications-empty,
    .notifications-loading {
        padding: 18px;
        border-radius: 18px;
        text-align: center;
        line-height: 1.55;
    }

    .notifications-empty {
        border: 1px dashed rgba(148, 163, 184, 0.24);
        background: rgba(248, 250, 252, 0.92);
        color: #64748b;
    }

    .notifications-loading {
        background: rgba(37, 99, 235, 0.06);
        color: #1d4ed8;
    }

    @media (max-width: 900px) {
        .notifications-stats {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 560px) {
        .notification-card {
            padding: 14px;
        }

        .notifications-hero__copy h2 {
            font-size: 1.25rem;
        }

        .notifications-hero__copy p,
        .notification-card__message {
            font-size: 0.88rem;
        }
    }
</style>
@endpush

@section('content')
<section class="panel notifications-hero">
    <div class="notifications-hero__copy">
        <p class="hero-panel__eyebrow">Notificaciones</p>
        <h2>Tus avisos recientes</h2>
        <p>Aquí puedes revisar cambios de tu solicitud, del servicio y de tus pagos.</p>
    </div>

    <div class="actions-inline">
        <button type="button" id="notificationsRefreshButton" class="button button--primary">Actualizar</button>
        <button type="button" id="notificationsMarkAllButton" class="button button--ghost">Marcar como leídas</button>
    </div>
</section>

<section class="notifications-stats">
    <article class="stat-card">
        <span class="stat-card__label">Total</span>
        <strong id="notificationsTotalCount">—</strong>
    </article>

    <article class="stat-card">
        <span class="stat-card__label">Nuevas</span>
        <strong id="notificationsUnreadCount">—</strong>
    </article>

    <article class="stat-card">
        <span class="stat-card__label">Leídas</span>
        <strong id="notificationsReadCount">—</strong>
    </article>
</section>

<section class="panel">
    <div class="section-head">
        <h3>Lista de notificaciones</h3>
        <span class="section-pill">Avisos</span>
    </div>

    <div id="notificationsList" class="notifications-list">
        <article class="notifications-loading">Cargando tus notificaciones…</article>
    </div>
</section>
@endsection

@push('page_scripts')
<script>
(function () {
    function boot() {
        const app = window.ZYGA_CLIENT_APP || {};

        if ((app.page || '') !== 'notifications') {
            return;
        }

        bindEvents();
        loadNotifications();
    }

    function bindEvents() {
        const refreshButton = document.getElementById('notificationsRefreshButton');
        const markAllButton = document.getElementById('notificationsMarkAllButton');

        if (refreshButton) {
            refreshButton.addEventListener('click', loadNotifications);
        }

        if (markAllButton) {
            markAllButton.addEventListener('click', markAllAsRead);
        }
    }

    async function loadNotifications() {
        const app = window.ZYGA_CLIENT_APP || {};
        const container = document.getElementById('notificationsList');

        if (!app.apiBaseUrl || !app.token) {
            renderError('No pudimos abrir tus notificaciones. Vuelve a iniciar sesión.');
            return;
        }

        if (container) {
            container.innerHTML = '<article class="notifications-loading">Cargando tus notificaciones…</article>';
        }

        try {
            const payload = await api(app, '/api/v1/notifications');
            const items = extractList(payload && payload.data, ['notifications', 'items']);

            const sorted = items.sort(function (a, b) {
                return String(b.created_at || b.updated_at || '').localeCompare(String(a.created_at || a.updated_at || ''));
            });

            const unread = sorted.filter(function (item) {
                return !Boolean(item && item.is_read);
            });

            const read = sorted.filter(function (item) {
                return Boolean(item && item.is_read);
            });

            setText('notificationsTotalCount', String(sorted.length));
            setText('notificationsUnreadCount', String(unread.length));
            setText('notificationsReadCount', String(read.length));

            renderList(sorted);
        } catch (error) {
            renderError(error.message || 'No pudimos cargar tus notificaciones.');
        }
    }

    function renderList(items) {
        const container = document.getElementById('notificationsList');

        if (!container) return;

        if (!items.length) {
            container.innerHTML = '<article class="notifications-empty">No tienes notificaciones para mostrar.</article>';
            return;
        }

        container.innerHTML = items.map(function (item) {
            return `
                <article class="notification-card ${!item.is_read ? 'is-unread' : ''}">
                    <div class="notification-card__top">
                        <h4 class="notification-card__title">${escapeHtml(notificationTypeLabel(item.type || ''))}</h4>
                        <span class="notification-card__state">${escapeHtml(item.is_read ? 'Leída' : 'Nueva')}</span>
                    </div>

                    <p class="notification-card__message">${escapeHtml(item.message || 'Sin mensaje disponible.')}</p>

                    <div class="notification-card__meta">
                        ${escapeHtml(formatDateTime(item.created_at || item.updated_at))}
                    </div>
                </article>
            `;
        }).join('');
    }

    async function markAllAsRead() {
        const app = window.ZYGA_CLIENT_APP || {};

        if (!app.apiBaseUrl || !app.token) {
            alert('No pudimos realizar la acción. Vuelve a iniciar sesión.');
            return;
        }

        try {
            await api(app, '/api/v1/notifications/read-all', {
                method: 'PATCH'
            });

            loadNotifications();
            alert('Tus notificaciones fueron marcadas como leídas.');
        } catch (error) {
            alert(error.message || 'No pudimos marcar tus notificaciones como leídas.');
        }
    }

    function renderError(message) {
        const container = document.getElementById('notificationsList');

        if (container) {
            container.innerHTML = '<article class="notifications-empty">' + escapeHtml(message) + '</article>';
        }

        setText('notificationsTotalCount', '0');
        setText('notificationsUnreadCount', '0');
        setText('notificationsReadCount', '0');
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
            throw new Error(readableApiMessage(payload.message || 'Ocurrió un problema con tus notificaciones.'));
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

        for (const key of ['items', 'notifications', 'data']) {
            if (Array.isArray(data[key])) {
                return data[key];
            }
        }

        return [];
    }

    function notificationTypeLabel(value) {
        return {
            assistance_request: 'Solicitud de ayuda',
            payment: 'Pago',
            provider: 'Proveedor',
            system: 'Aviso'
        }[normalize(value)] || 'Aviso';
    }

    function normalize(value) {
        return String(value || '').trim().toLowerCase();
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
            return 'No pudimos cargar tus notificaciones.';
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
