@extends('user.layouts.app')

@push('page_styles')
<style>
    .client-home-hero {
        display: grid;
        gap: 14px;
        align-items: center;
    }

    .client-home-hero__copy {
        display: grid;
        gap: 6px;
    }

    .client-home-hero__copy h2 {
        margin: 0;
        color: #0f172a;
        line-height: 1.08;
        font-size: clamp(1.35rem, 2vw, 1.9rem);
    }

    .client-home-hero__copy p {
        margin: 0;
        color: #475569;
        line-height: 1.55;
        font-size: 0.96rem;
    }

    .client-home-stats {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
    }

    .client-home-actions {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
    }

    .client-home-action {
        padding: 18px;
        border-radius: 22px;
        border: 1px solid rgba(15, 23, 42, 0.08);
        background:
            linear-gradient(135deg, rgba(249, 115, 22, 0.08), rgba(37, 99, 235, 0.05)),
            #ffffff;
        display: grid;
        gap: 10px;
    }

    .client-home-action__label {
        display: inline-flex;
        width: fit-content;
        padding: 6px 10px;
        border-radius: 999px;
        background: rgba(15, 23, 42, 0.06);
        color: #334155;
        font-size: 0.76rem;
        font-weight: 800;
    }

    .client-home-action h4 {
        margin: 0;
        color: #0f172a;
        line-height: 1.25;
        font-size: 1rem;
    }

    .client-home-action p {
        margin: 0;
        color: #475569;
        line-height: 1.5;
        font-size: 0.9rem;
    }

    .client-service-card {
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

    .client-service-card__top {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
        justify-content: space-between;
    }

    .client-service-card__status {
        display: inline-flex;
        align-items: center;
        padding: 8px 12px;
        border-radius: 999px;
        background: rgba(249, 115, 22, 0.12);
        color: #c2410c;
        font-size: 0.8rem;
        font-weight: 800;
    }

    .client-service-grid,
    .client-account-check {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .client-service-item,
    .client-check-item {
        padding: 14px 16px;
        border-radius: 18px;
        border: 1px solid rgba(148, 163, 184, 0.16);
        background: #f8fafc;
        display: grid;
        gap: 6px;
    }

    .client-service-item span,
    .client-check-item span {
        color: #64748b;
        font-size: 0.8rem;
    }

    .client-service-item strong,
    .client-check-item strong {
        color: #0f172a;
        line-height: 1.45;
        word-break: break-word;
        font-size: 0.95rem;
    }

    .client-check-item.is-ready {
        border-color: rgba(22, 163, 74, 0.18);
        background: rgba(22, 163, 74, 0.05);
    }

    .client-check-item.is-ready strong {
        color: #166534;
    }

    .client-feed {
        display: grid;
        gap: 12px;
    }

    .client-feed-item {
        padding: 15px;
        border-radius: 18px;
        border: 1px solid rgba(148, 163, 184, 0.16);
        background: #ffffff;
        display: grid;
        gap: 6px;
    }

    .client-feed-item.is-unread {
        border-color: rgba(37, 99, 235, 0.18);
        background: rgba(37, 99, 235, 0.05);
    }

    .client-feed-item__top {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        align-items: center;
    }

    .client-feed-item__title {
        margin: 0;
        color: #0f172a;
        font-size: 0.94rem;
        line-height: 1.35;
    }

    .client-feed-item__meta {
        color: #64748b;
        font-size: 0.78rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .client-feed-item__body {
        margin: 0;
        color: #475569;
        line-height: 1.5;
        font-size: 0.9rem;
    }

    .client-home-note {
        padding: 14px 16px;
        border-radius: 18px;
        background: rgba(15, 23, 42, 0.04);
        border: 1px solid rgba(148, 163, 184, 0.14);
        color: #475569;
        line-height: 1.55;
    }

    @media (max-width: 1024px) {
        .client-home-actions {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 720px) {
        .client-home-stats,
        .client-home-actions,
        .client-service-grid,
        .client-account-check {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 560px) {
        .client-home-hero__copy h2 {
            font-size: 1.25rem;
        }

        .client-home-hero__copy p {
            font-size: 0.9rem;
        }

        .client-service-card,
        .client-home-action {
            padding: 15px;
        }

        .client-service-item,
        .client-check-item,
        .client-feed-item {
            padding: 13px;
        }

        .client-service-card__top {
            align-items: flex-start;
        }

        .client-service-card__status {
            font-size: 0.76rem;
            padding: 7px 10px;
        }

        .client-home-action h4 {
            font-size: 0.96rem;
        }

        .client-home-action p,
        .client-feed-item__body {
            font-size: 0.86rem;
        }
    }
</style>
@endpush

@section('content')
@php
    $profile = is_array($dashboardProfile ?? null) ? $dashboardProfile : [];
    $services = is_array($dashboardServices ?? null) ? $dashboardServices : [];
    $vehicles = is_array($dashboardVehicles ?? null) ? $dashboardVehicles : [];
    $history = is_array($dashboardRequestHistory ?? null) ? $dashboardRequestHistory : [];
    $activeRequest = is_array($dashboardActiveRequest ?? null) ? $dashboardActiveRequest : [];
    $payments = is_array($dashboardPayments ?? null) ? $dashboardPayments : [];
    $notifications = is_array($dashboardNotifications ?? null) ? $dashboardNotifications : [];
    $unreadNotifications = is_array($dashboardUnreadNotifications ?? null) ? $dashboardUnreadNotifications : [];
    $pendingPayments = is_array($dashboardPendingPayments ?? null) ? $dashboardPendingPayments : [];
    $closedRequests = is_array($dashboardClosedRequests ?? null) ? $dashboardClosedRequests : [];
    $errors = is_array($dashboardApiErrors ?? null) ? $dashboardApiErrors : [];

    $showSetupBlock = (bool) ($dashboardNeedsSetup ?? false);
    $canRequest = (bool) ($dashboardCanRequest ?? false);
    $profileReady = (bool) ($dashboardProfileReady ?? false);

    $userData = is_array($profile['user'] ?? null) ? $profile['user'] : [];
    $firstName = trim((string) ($userData['name'] ?? ''));
    $firstName = $firstName !== '' ? explode(' ', $firstName)[0] : 'Cliente';

    $recentNotifications = array_slice($notifications, 0, 4);
    $recentClosedRequests = array_slice($closedRequests, 0, 3);

    $statusLabel = function (?string $status): string {
        $key = strtolower(trim((string) $status));

        return match ($key) {
            'created' => 'Solicitud enviada',
            'accepted' => 'Solicitud aceptada',
            'assigned' => 'Proveedor asignado',
            'in_progress' => 'En camino',
            'arrived' => 'Llegó al punto',
            'completed' => 'Servicio completado',
            'cancelled' => 'Servicio cancelado',
            'pending_validation' => 'Pago en revisión',
            'paid' => 'Pago confirmado',
            'failed' => 'Pago fallido',
            'rejected' => 'Pago rechazado',
            default => $status ?: 'En proceso',
        };
    };

    $notificationTypeLabel = function (mixed $value): string {
        $key = strtolower(trim((string) $value));

        return match ($key) {
            'assistance_request' => 'Solicitud de ayuda',
            'payment' => 'Pago',
            'provider' => 'Proveedor',
            default => $key !== '' ? ucfirst(str_replace('_', ' ', $key)) : 'Aviso',
        };
    };

    $activeServiceName = data_get($activeRequest, 'service.name', 'Servicio en curso');
    $activePublicId = data_get($activeRequest, 'public_id') ?: ('#' . data_get($activeRequest, 'id', '—'));
@endphp

@if(!empty($errors))
    <section class="stack-list">
        @foreach($errors as $error)
            <article class="notice-card" style="border-color: rgba(220,38,38,0.18); background: rgba(220,38,38,0.05); color: #b91c1c;">
                {{ $error }}
            </article>
        @endforeach
    </section>
@endif

<section class="panel client-home-hero">
    <div class="client-home-hero__copy">
        @if(!empty($activeRequest))
            <p class="hero-panel__eyebrow">Servicio en curso</p>
            <h2>Tienes ayuda en proceso</h2>
            <p>Abre el seguimiento para ver el estado de tu solicitud.</p>
        @elseif($canRequest)
            <p class="hero-panel__eyebrow">Inicio</p>
            <h2>¿Necesitas ayuda?</h2>
            <p>Pide apoyo en unos cuantos pasos.</p>
        @else
            <p class="hero-panel__eyebrow">Inicio</p>
            <h2>Deja tu cuenta lista</h2>
            <p>Completa tus datos y agrega un vehículo para continuar.</p>
        @endif
    </div>

    <div class="actions-inline">
        @if(!empty($activeRequest))
            <a href="{{ route('user.activo') }}" class="button button--primary">Ver seguimiento</a>
            <a href="{{ route('user.pagos') }}" class="button button--ghost">Ver pago</a>
        @elseif($canRequest)
            <a href="{{ route('user.solicitud') }}" class="button button--primary">Pedir ayuda</a>
            <a href="{{ route('user.cuenta') }}" class="button button--secondary">Mis vehículos</a>
        @else
            <a href="{{ route('user.cuenta') }}" class="button button--primary">Completar perfil</a>
            <a href="{{ route('user.historial') }}" class="button button--ghost">Historial</a>
        @endif
    </div>
</section>

<section class="client-home-stats">
    <article class="stat-card">
        <span class="stat-card__label">Vehículos</span>
        <strong>{{ count($vehicles) }}</strong>
    </article>

    <article class="stat-card">
        <span class="stat-card__label">Notificaciones</span>
        <strong>{{ count($unreadNotifications) }}</strong>
    </article>

    <article class="stat-card">
        <span class="stat-card__label">Pagos pendientes</span>
        <strong>{{ count($pendingPayments) }}</strong>
    </article>
</section>

@if(!empty($activeRequest))
    <section class="panel">
        <div class="section-head">
            <h3>Tu servicio ahora</h3>
            <span class="section-pill">En curso</span>
        </div>

        <div class="client-service-card">
            <div class="client-service-card__top">
                <div>
                    <h3 style="margin: 0 0 4px; color: #0f172a;">{{ $activeServiceName }}</h3>
                    <p style="margin: 0; color: #475569;">Folio {{ $activePublicId }}</p>
                </div>

                <span class="client-service-card__status">
                    {{ $statusLabel(data_get($activeRequest, 'status')) }}
                </span>
            </div>

            <div class="client-service-grid">
                <article class="client-service-item">
                    <span>Dirección</span>
                    <strong>{{ data_get($activeRequest, 'pickup_address', 'Sin dirección registrada') }}</strong>
                </article>

                <article class="client-service-item">
                    <span>Referencia</span>
                    <strong>{{ data_get($activeRequest, 'pickup_reference', 'Sin referencia') }}</strong>
                </article>

                <article class="client-service-item">
                    <span>Monto</span>
                    <strong>${{ number_format((float) data_get($activeRequest, 'final_amount', data_get($activeRequest, 'quoted_amount', 0)), 2) }}</strong>
                </article>

                <article class="client-service-item">
                    <span>Pago</span>
                    <strong>{{ $statusLabel(data_get($activeRequest, 'payment_status', 'pending')) }}</strong>
                </article>
            </div>

            <div class="actions-inline">
                <a href="{{ route('user.activo') }}" class="button button--primary">Abrir seguimiento</a>
                <a href="{{ route('user.historial') }}" class="button button--ghost">Ver historial</a>
            </div>
        </div>
    </section>
@endif

@if($showSetupBlock)
    <section class="panel">
        <div class="section-head">
            <h3>Antes de pedir ayuda</h3>
            <span class="section-pill">Configura tu cuenta</span>
        </div>

        <div class="client-account-check">
            <article class="client-check-item {{ $profileReady ? 'is-ready' : '' }}">
                <span>Perfil</span>
                <strong>{{ $profileReady ? 'Listo' : 'Revisa tus datos' }}</strong>
            </article>

            <article class="client-check-item {{ !empty($vehicles) ? 'is-ready' : '' }}">
                <span>Vehículo</span>
                <strong>{{ !empty($vehicles) ? 'Ya registrado' : 'Agrega tu vehículo' }}</strong>
            </article>

            <article class="client-check-item {{ !empty($services) ? 'is-ready' : '' }}">
                <span>Servicios</span>
                <strong>{{ !empty($services) ? 'Disponibles' : 'No disponibles por ahora' }}</strong>
            </article>

            <article class="client-check-item {{ !empty($history) ? 'is-ready' : '' }}">
                <span>Uso del sistema</span>
                <strong>{{ !empty($history) ? 'Ya usaste el sistema' : 'Aún no haces una solicitud' }}</strong>
            </article>
        </div>

        <div class="actions-inline" style="margin-top: 16px;">
            <a href="{{ route('user.cuenta') }}" class="button button--secondary">Ir a mi perfil</a>

            @if($canRequest)
                <a href="{{ route('user.solicitud') }}" class="button button--primary">Pedir ayuda</a>
            @endif
        </div>
    </section>
@endif

<section class="panel">
    <div class="section-head">
        <h3>Accesos rápidos</h3>
        <span class="section-pill">Lo más usado</span>
    </div>

    <div class="client-home-actions">
        <article class="client-home-action">
            <span class="client-home-action__label">Ayuda</span>
            <h4>Pedir ayuda</h4>
            <p>Inicia una solicitud con servicio, vehículo y ubicación.</p>
            <a href="{{ route('user.solicitud') }}" class="button button--primary button--compact">Abrir</a>
        </article>

        <article class="client-home-action">
            <span class="client-home-action__label">Seguimiento</span>
            <h4>Ver estado</h4>
            <p>Consulta si ya aceptaron tu solicitud y cómo va el servicio.</p>
            <a href="{{ route('user.activo') }}" class="button button--secondary button--compact">Abrir</a>
        </article>

        <article class="client-home-action">
            <span class="client-home-action__label">Perfil</span>
            <h4>Mis datos y vehículos</h4>
            <p>Administra tu cuenta y deja listo tu vehículo.</p>
            <a href="{{ route('user.cuenta') }}" class="button button--ghost button--compact">Abrir</a>
        </article>

        <article class="client-home-action">
            <span class="client-home-action__label">Historial</span>
            <h4>Servicios anteriores</h4>
            <p>Revisa solicitudes cerradas y pagos anteriores.</p>
            <a href="{{ route('user.historial') }}" class="button button--ghost button--compact">Abrir</a>
        </article>
    </div>
</section>

<section class="grid-two">
    <article class="panel">
        <div class="section-head">
            <h3>Notificaciones</h3>
            <span class="section-pill">Recientes</span>
        </div>

        <div class="client-feed">
            @forelse($recentNotifications as $notification)
                <article class="client-feed-item {{ empty($notification['is_read']) ? 'is-unread' : '' }}">
                    <div class="client-feed-item__top">
                        <h4 class="client-feed-item__title">
                            {{ $notificationTypeLabel($notification['type'] ?? null) }}
                        </h4>

                        <span class="client-feed-item__meta">
                            {{ empty($notification['is_read']) ? 'Nueva' : 'Leída' }}
                        </span>
                    </div>

                    <p class="client-feed-item__body">
                        {{ $notification['message'] ?? 'Sin mensaje disponible.' }}
                    </p>
                </article>
            @empty
                <article class="empty-state">
                    No tienes notificaciones recientes.
                </article>
            @endforelse
        </div>

        <div class="actions-inline" style="margin-top: 16px;">
            <a href="{{ route('user.notificaciones') }}" class="button button--ghost">Ver todas</a>
        </div>
    </article>

    <article class="panel">
        <div class="section-head">
            <h3>Últimos movimientos</h3>
            <span class="section-pill">Resumen</span>
        </div>

        @if(!empty($pendingPayments))
            <div class="client-home-note" style="margin-bottom: 14px;">
                Tienes {{ count($pendingPayments) }} pago(s) pendiente(s) de revisión o confirmación.
            </div>
        @endif

        <div class="client-feed">
            @forelse($recentClosedRequests as $request)
                <article class="client-feed-item">
                    <div class="client-feed-item__top">
                        <h4 class="client-feed-item__title">
                            {{ data_get($request, 'service.name', 'Servicio') }}
                        </h4>

                        <span class="client-feed-item__meta">
                            {{ $statusLabel(data_get($request, 'status')) }}
                        </span>
                    </div>

                    <p class="client-feed-item__body">
                        Folio {{ data_get($request, 'public_id') ?: ('#' . data_get($request, 'id', '—')) }}
                        · Pago {{ $statusLabel(data_get($request, 'payment_status', 'pending')) }}
                    </p>
                </article>
            @empty
                <article class="empty-state">
                    Aún no tienes movimientos recientes.
                </article>
            @endforelse
        </div>

        <div class="actions-inline" style="margin-top: 16px;">
            <a href="{{ route('user.historial') }}" class="button button--ghost">Ver historial</a>
            <a href="{{ route('user.pagos') }}" class="button button--secondary">Ver pagos</a>
        </div>
    </article>
</section>
@endsection
