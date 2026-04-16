@extends('provider.layouts.app')

@section('title', 'ZYGA | Inicio proveedor')
@section('page-key', 'dashboard')
@section('page-title', 'Inicio')
@section('page-copy', 'Consulta el estado de tu cuenta, revisa nuevas solicitudes y da seguimiento a tus servicios desde una vista más clara.')

@push('page_styles')
<style>
    .provider-dashboard-grid,
    .provider-dashboard-hero-grid,
    .provider-dashboard-summary-grid,
    .provider-dashboard-focus-grid,
    .provider-dashboard-kpi-grid,
    .provider-dashboard-lists-grid,
    .provider-dashboard-mobile-stack {
        display: grid;
        gap: 16px;
    }

    .provider-dashboard-grid,
    .provider-dashboard-hero-grid,
    .provider-dashboard-summary-grid,
    .provider-dashboard-focus-grid,
    .provider-dashboard-kpi-grid,
    .provider-dashboard-lists-grid {
        grid-template-columns: 1fr;
    }

    .provider-dashboard-badge-row,
    .provider-dashboard-actions,
    .provider-dashboard-meta-row {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .provider-dashboard-priority-card,
    .provider-dashboard-focus-card,
    .provider-dashboard-kpi-card,
    .provider-dashboard-mobile-section {
        display: grid;
        gap: 14px;
    }

    .provider-dashboard-priority-card {
        padding: 18px;
        border-radius: 24px;
        border: 1px solid rgba(255, 122, 0, 0.12);
        background: linear-gradient(180deg, #fff9f3 0%, #ffffff 100%);
        box-shadow: 0 12px 32px rgba(15, 23, 42, 0.05);
    }

    .provider-dashboard-priority-card__head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
    }

    .provider-dashboard-priority-card__head h3,
    .provider-dashboard-focus-card h3,
    .provider-dashboard-mobile-section h3 {
        margin: 0;
        color: var(--provider-text-dark);
    }

    .provider-dashboard-priority-card__head p,
    .provider-dashboard-focus-card p,
    .provider-dashboard-mobile-section p {
        margin: 6px 0 0;
        color: var(--provider-text-soft);
        line-height: 1.55;
    }

    .provider-dashboard-summary-card {
        padding: 16px;
        border-radius: 20px;
        background: rgba(255,255,255,.12);
        border: 1px solid rgba(255,255,255,.10);
    }

    .provider-dashboard-summary-card span {
        display: block;
        color: rgba(255,255,255,.74);
        font-size: .84rem;
    }

    .provider-dashboard-summary-card strong {
        display: block;
        margin-top: 8px;
        font-size: 1.75rem;
        color: #fff;
        line-height: 1.1;
        letter-spacing: -.03em;
        word-break: break-word;
    }

    .provider-dashboard-summary-card small {
        display: block;
        margin-top: 8px;
        color: rgba(255,255,255,.70);
        line-height: 1.45;
        font-size: .8rem;
    }

    .provider-dashboard-focus-grid {
        gap: 14px;
    }

    .provider-dashboard-focus-card {
        padding: 18px;
        border-radius: 22px;
        border: 1px solid #e7ebf3;
        background: linear-gradient(180deg, #ffffff 0%, #fafbfd 100%);
        box-shadow: 0 8px 22px rgba(15, 23, 42, 0.04);
        min-height: 182px;
    }

    .provider-dashboard-focus-card__value {
        font-size: clamp(1.9rem, 2.8vw, 2.45rem);
        line-height: 1;
        font-weight: 900;
        letter-spacing: -.04em;
        color: var(--provider-text-dark);
        word-break: break-word;
    }

    .provider-dashboard-kpi-card {
        padding: 18px;
        border-radius: 22px;
        border: 1px solid #e7ebf3;
        background: linear-gradient(180deg, #ffffff 0%, #fafbfd 100%);
        box-shadow: 0 8px 22px rgba(15, 23, 42, 0.04);
        min-height: 164px;
    }

    .provider-dashboard-kpi-card__top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
    }

    .provider-dashboard-kpi-card__label {
        margin: 0;
        color: var(--provider-text-soft);
        font-size: .9rem;
    }

    .provider-dashboard-kpi-card__value {
        margin: 0;
        font-size: clamp(1.7rem, 2.6vw, 2.25rem);
        line-height: 1;
        font-weight: 900;
        letter-spacing: -.04em;
        color: var(--provider-text-dark);
        word-break: break-word;
    }

    .provider-dashboard-kpi-card__hint {
        margin: 0;
        color: var(--provider-text-soft);
        line-height: 1.52;
        font-size: .92rem;
    }

    .provider-dashboard-list-card {
        display: grid;
        gap: 14px;
    }

    .provider-dashboard-list-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
    }

    .provider-dashboard-list-head h3 {
        margin: 0;
        color: var(--provider-text-dark);
    }

    .provider-dashboard-list-head p {
        margin: 6px 0 0;
        color: var(--provider-text-soft);
        line-height: 1.55;
    }

    .provider-dashboard-request-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 10px;
    }

    .provider-dashboard-request-meta span {
        display: inline-flex;
        align-items: center;
        padding: 8px 10px;
        border-radius: 999px;
        background: #f8fafc;
        color: var(--provider-text-soft);
        border: 1px solid #e4ebf5;
        font-size: .82rem;
        font-weight: 700;
    }

    .provider-dashboard-mobile-stack {
        gap: 14px;
    }

    .provider-dashboard-mobile-section {
        padding: 18px;
        border-radius: 22px;
        border: 1px solid #e7ebf3;
        background: linear-gradient(180deg, #ffffff 0%, #fafbfd 100%);
        box-shadow: 0 8px 22px rgba(15, 23, 42, 0.04);
    }

    .provider-dashboard-mobile-count {
        font-size: 2rem;
        line-height: 1;
        font-weight: 900;
        color: var(--provider-text-dark);
        letter-spacing: -.04em;
    }

    .provider-dashboard-next-step {
        padding: 16px;
        border-radius: 18px;
        background: linear-gradient(180deg, #fffaf2 0%, #fff 100%);
        border: 1px solid #ffe1b3;
    }

    .provider-dashboard-next-step h4 {
        margin: 0 0 8px;
        color: var(--provider-text-dark);
    }

    .provider-dashboard-next-step p {
        margin: 0;
        color: var(--provider-text-soft);
        line-height: 1.55;
    }

    @media (min-width: 768px) {
        .provider-dashboard-focus-grid,
        .provider-dashboard-kpi-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (min-width: 1024px) {
        .provider-dashboard-hero-grid {
            grid-template-columns: 1.15fr .95fr;
        }

        .provider-dashboard-summary-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .provider-dashboard-focus-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .provider-dashboard-kpi-grid {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }

        .provider-dashboard-lists-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
</style>
@endpush

@section('content')
    @php
        $r = $context['readiness'];

        $availableCount = count($availableRequests);
        $activeCount = count($activeRequests);
        $historyCount = count($historicalRequests);

        $statusBadgeClass = $r['portal_ready']
            ? 'success'
            : ($r['backend_can_operate'] ? 'info' : 'warning');

        $statusBadgeText = $r['portal_ready']
            ? 'Cuenta lista para trabajar'
            : ($r['backend_can_operate'] ? 'Casi lista para comenzar' : 'Todavía faltan pasos');

        $verificationText = $r['checks']['is_verified']
            ? 'Cuenta validada'
            : 'Validación pendiente';

        $accountStatusText = $r['checks']['status_active']
            ? 'Cuenta activa'
            : 'Cuenta pendiente de activación';

        $nextStepTitle = 'Completa tu configuración inicial';
        $nextStepDescription = 'Sigue estos pasos para dejar tu cuenta lista y comenzar a atender solicitudes.';
        $nextStepButtonText = 'Ir a mi perfil';
        $nextStepButtonRoute = route('provider.perfil');

        if (!$context['hasProfile']) {
            $nextStepTitle = 'Completa tu perfil';
            $nextStepDescription = 'Agrega tu información básica para continuar con la configuración de tu cuenta.';
            $nextStepButtonText = 'Completar perfil';
            $nextStepButtonRoute = route('provider.perfil');
        } elseif ($r['services_count'] === 0) {
            $nextStepTitle = 'Selecciona los servicios que ofreces';
            $nextStepDescription = 'Indica qué tipos de asistencia puedes atender para que el sistema te muestre solicitudes compatibles.';
            $nextStepButtonText = 'Configurar servicios';
            $nextStepButtonRoute = route('provider.servicios');
        } elseif ($r['active_schedules_count'] === 0) {
            $nextStepTitle = 'Configura tu disponibilidad';
            $nextStepDescription = 'Agrega al menos un horario activo para indicar cuándo puedes recibir solicitudes.';
            $nextStepButtonText = 'Configurar horarios';
            $nextStepButtonRoute = route('provider.horarios');
        } elseif (!$r['checks']['is_verified']) {
            $nextStepTitle = 'Espera la validación de tu cuenta';
            $nextStepDescription = 'Tu información ya está casi lista. Solo falta la revisión para habilitar tu operación.';
            $nextStepButtonText = 'Ver perfil';
            $nextStepButtonRoute = route('provider.perfil');
        } elseif (!$r['checks']['status_active']) {
            $nextStepTitle = 'Tu cuenta aún no está activa';
            $nextStepDescription = 'Tu perfil ya está configurado, pero todavía no aparece como activo para comenzar a trabajar.';
            $nextStepButtonText = 'Ver perfil';
            $nextStepButtonRoute = route('provider.perfil');
        } elseif ($r['portal_ready']) {
            $nextStepTitle = 'Tu cuenta está lista';
            $nextStepDescription = 'Ya puedes revisar solicitudes disponibles, aceptar servicios y dar seguimiento a tu operación.';
            $nextStepButtonText = 'Ver solicitudes';
            $nextStepButtonRoute = route('provider.asistencias');
        }

        $availableHeadlineTitle = $availableCount > 0
            ? 'Hay solicitudes esperando atención'
            : 'No hay solicitudes nuevas por ahora';

        $availableHeadlineText = $availableCount > 0
            ? 'Tienes ' . $availableCount . ' solicitud' . ($availableCount === 1 ? '' : 'es') . ' disponible' . ($availableCount === 1 ? '' : 's') . ' para revisar desde este momento.'
            : 'Cuando exista una nueva solicitud compatible con tus servicios aparecerá aquí automáticamente.';

        $requestStatusLabel = function (array $request): string {
            return $request['status_label']
                ?? match ($request['status'] ?? null) {
                    'created' => 'Nueva',
                    'assigned' => 'Asignada',
                    'in_progress' => 'En proceso',
                    'completed' => 'Completada',
                    'cancelled' => 'Cancelada',
                    default => 'Sin estado',
                };
        };

        $requestStatusTone = function (array $request): string {
            return $request['status_tone']
                ?? match ($request['status'] ?? null) {
                    'completed' => 'success',
                    'cancelled' => 'warning',
                    'assigned', 'in_progress' => 'info',
                    default => 'info',
                };
        };
    @endphp

    <section class="hero hero-split provider-dashboard-hero-grid">
        <div class="provider-dashboard-priority-card">
            <div class="provider-dashboard-priority-card__head">
                <div>
                    <p class="eyebrow">Resumen del día</p>
                    <h3>Tu operación de hoy</h3>
                    <p>
                        Desde aquí puedes ver si tu cuenta está lista, qué te falta por completar
                        y si tienes solicitudes nuevas para revisar.
                    </p>
                </div>

                <span class="chip {{ $statusBadgeClass }}">{{ $statusBadgeText }}</span>
            </div>

            <div class="provider-dashboard-badge-row">
                <span class="chip {{ $r['checks']['is_verified'] ? 'success' : 'warning' }}">{{ $verificationText }}</span>
                <span class="chip {{ $r['checks']['status_active'] ? 'success' : 'warning' }}">{{ $accountStatusText }}</span>
                <span class="chip {{ $availableCount > 0 ? 'success' : 'info' }}">
                    {{ $availableCount }} solicitud{{ $availableCount === 1 ? '' : 'es' }} nueva{{ $availableCount === 1 ? '' : 's' }}
                </span>
            </div>

            <div class="provider-dashboard-next-step">
                <h4>{{ $nextStepTitle }}</h4>
                <p>{{ $nextStepDescription }}</p>
            </div>

            <div class="provider-dashboard-actions">
                <a href="{{ $nextStepButtonRoute }}" class="btn">{{ $nextStepButtonText }}</a>
                <a href="{{ route('provider.asistencias') }}" class="btn-outline">Ver solicitudes</a>
            </div>
        </div>

        <div class="hero-panel provider-dashboard-summary-grid">
            <div class="provider-dashboard-summary-card">
                <span>Solicitudes disponibles</span>
                <strong>{{ $availableCount }}</strong>
                <small>Lo más importante para revisar ahora.</small>
            </div>

            <div class="provider-dashboard-summary-card">
                <span>Servicios en curso</span>
                <strong>{{ $activeCount }}</strong>
                <small>Casos que ya estás atendiendo.</small>
            </div>

            <div class="provider-dashboard-summary-card">
                <span>Servicios configurados</span>
                <strong>{{ $r['services_count'] }}</strong>
                <small>Tipos de asistencia que ofreces.</small>
            </div>

            <div class="provider-dashboard-summary-card">
                <span>Horarios activos</span>
                <strong>{{ $r['active_schedules_count'] }}</strong>
                <small>Tu disponibilidad registrada.</small>
            </div>
        </div>
    </section>

    @if(!$r['portal_ready'])
        <section class="lockbox">
            <div class="section-head">
                <div>
                    <p class="eyebrow">Antes de comenzar</p>
                    <h3>Tu cuenta todavía necesita algunos pasos</h3>
                </div>
            </div>

            <div class="checklist">
                @foreach($r['blockers'] as $blocker)
                    <div class="check">
                        <span>{{ $blocker }}</span>
                        <span class="chip warning">Pendiente</span>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <section class="provider-dashboard-focus-grid desktop-only">
        <article class="provider-dashboard-focus-card">
            <div>
                <p class="eyebrow">Atención inmediata</p>
                <h3>{{ $availableHeadlineTitle }}</h3>
                <p>{{ $availableHeadlineText }}</p>
            </div>

            <div class="provider-dashboard-focus-card__value">{{ $availableCount }}</div>

            <div class="provider-dashboard-actions">
                <a href="{{ route('provider.asistencias') }}" class="btn">Abrir solicitudes</a>
            </div>
        </article>

        <article class="provider-dashboard-focus-card">
            <div>
                <p class="eyebrow">Siguiente paso</p>
                <h3>{{ $nextStepTitle }}</h3>
                <p>{{ $nextStepDescription }}</p>
            </div>

            <div class="provider-dashboard-meta-row">
                <span class="chip {{ $context['hasProfile'] ? 'success' : 'warning' }}">
                    {{ $context['hasProfile'] ? 'Perfil listo' : 'Perfil pendiente' }}
                </span>
            </div>

            <div class="provider-dashboard-actions">
                <a href="{{ $nextStepButtonRoute }}" class="btn-outline">{{ $nextStepButtonText }}</a>
            </div>
        </article>

        <article class="provider-dashboard-focus-card">
            <div>
                <p class="eyebrow">Estado de cuenta</p>
                <h3>Resumen rápido</h3>
                <p>Consulta el estado general de tu cuenta sin entrar a otras pantallas.</p>
            </div>

            <div class="provider-dashboard-meta-row">
                <span class="chip {{ $r['checks']['is_verified'] ? 'success' : 'warning' }}">{{ $verificationText }}</span>
                <span class="chip {{ $r['checks']['status_active'] ? 'success' : 'warning' }}">{{ $accountStatusText }}</span>
            </div>

            <div class="provider-dashboard-meta-row">
                <span class="chip info">{{ $historyCount }} en historial</span>
            </div>
        </article>
    </section>

    <section class="provider-dashboard-mobile-stack mobile-only">
        <article class="provider-dashboard-mobile-section">
            <p class="eyebrow">Atención inmediata</p>
            <h3>{{ $availableHeadlineTitle }}</h3>
            <p>{{ $availableHeadlineText }}</p>
            <div class="provider-dashboard-mobile-count">{{ $availableCount }}</div>
            <div class="provider-dashboard-actions">
                <a href="{{ route('provider.asistencias') }}" class="btn full">Abrir solicitudes</a>
            </div>
        </article>

        <article class="provider-dashboard-mobile-section">
            <p class="eyebrow">Siguiente paso</p>
            <h3>{{ $nextStepTitle }}</h3>
            <p>{{ $nextStepDescription }}</p>
            <div class="provider-dashboard-actions">
                <a href="{{ $nextStepButtonRoute }}" class="btn-outline full">{{ $nextStepButtonText }}</a>
            </div>
        </article>
    </section>

    <section class="provider-dashboard-kpi-grid">
        <article class="provider-dashboard-kpi-card">
            <div class="provider-dashboard-kpi-card__top">
                <div>
                    <p class="provider-dashboard-kpi-card__label">Perfil</p>
                    <h3 class="provider-dashboard-kpi-card__value">{{ $context['hasProfile'] ? 'Listo' : 'Pendiente' }}</h3>
                </div>
                <span class="chip {{ $context['hasProfile'] ? 'success' : 'warning' }}">
                    {{ $context['hasProfile'] ? 'Completo' : 'Falta' }}
                </span>
            </div>

            <p class="provider-dashboard-kpi-card__hint">
                Tu información básica como proveedor para operar dentro del sistema.
            </p>
        </article>

        <article class="provider-dashboard-kpi-card">
            <div class="provider-dashboard-kpi-card__top">
                <div>
                    <p class="provider-dashboard-kpi-card__label">Servicios</p>
                    <h3 class="provider-dashboard-kpi-card__value">{{ $r['services_count'] }}</h3>
                </div>
                <span class="chip info">Configurados</span>
            </div>

            <p class="provider-dashboard-kpi-card__hint">
                Tipos de asistencia que puedes atender cuando llegue una solicitud compatible.
            </p>
        </article>

        <article class="provider-dashboard-kpi-card">
            <div class="provider-dashboard-kpi-card__top">
                <div>
                    <p class="provider-dashboard-kpi-card__label">Disponibilidad</p>
                    <h3 class="provider-dashboard-kpi-card__value">{{ $r['active_schedules_count'] }}</h3>
                </div>
                <span class="chip {{ $r['active_schedules_count'] > 0 ? 'success' : 'warning' }}">
                    {{ $r['active_schedules_count'] > 0 ? 'Activa' : 'Pendiente' }}
                </span>
            </div>

            <p class="provider-dashboard-kpi-card__hint">
                Horarios registrados para indicar cuándo puedes recibir nuevas solicitudes.
            </p>
        </article>

        <article class="provider-dashboard-kpi-card">
            <div class="provider-dashboard-kpi-card__top">
                <div>
                    <p class="provider-dashboard-kpi-card__label">Historial</p>
                    <h3 class="provider-dashboard-kpi-card__value">{{ $historyCount }}</h3>
                </div>
                <span class="chip info">Registrado</span>
            </div>

            <p class="provider-dashboard-kpi-card__hint">
                Servicios completados o cancelados que ya forman parte de tu historial.
            </p>
        </article>
    </section>

    <section class="provider-dashboard-lists-grid">
        <section class="card provider-dashboard-list-card">
            <div class="provider-dashboard-list-head">
                <div>
                    <p class="eyebrow">Solicitudes nuevas</p>
                    <h3>Disponibles para atención</h3>
                    <p>Revisa primero lo nuevo sin tener que abrir otras pantallas.</p>
                </div>

                <a href="{{ route('provider.asistencias') }}" class="btn-outline">Ver todas</a>
            </div>

            @if(!$r['backend_can_operate'])
                <div class="empty">
                    <h4>Aún no puedes recibir solicitudes</h4>
                    <p>Completa lo pendiente en tu cuenta para desbloquear esta sección.</p>
                </div>
            @elseif(empty($availableRequests))
                <div class="empty">
                    <h4>No hay solicitudes disponibles por ahora</h4>
                    <p>Cuando llegue una nueva solicitud compatible aparecerá aquí.</p>
                </div>
            @else
                <div class="list">
                    @foreach(array_slice($availableRequests, 0, 3) as $request)
                        <article class="item">
                            <div class="item-head">
                                <div>
                                    <h4>{{ $request['service_name'] ?? 'Servicio' }}</h4>
                                    <p>{{ $request['pickup_address'] ?? 'Ubicación pendiente' }}</p>
                                </div>

                                <span class="chip {{ $requestStatusTone($request) }}">
                                    {{ $requestStatusLabel($request) }}
                                </span>
                            </div>

                            <div class="provider-dashboard-request-meta">
                                <span>{{ $request['public_id'] ?: 'Sin folio visible' }}</span>
                                <span>{{ $request['vehicle'] ?? 'Vehículo no especificado' }}</span>
                            </div>

                            <div class="provider-dashboard-actions" style="margin-top: 2px;">
                                <a href="{{ route('provider.asistencias') }}" class="btn">Revisar solicitud</a>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>

        <section class="card provider-dashboard-list-card">
            <div class="provider-dashboard-list-head">
                <div>
                    <p class="eyebrow">Seguimiento</p>
                    <h3>Servicios en curso</h3>
                    <p>Consulta los servicios que ya estás atendiendo y continúa su avance.</p>
                </div>
            </div>

            @if(empty($activeRequests))
                <div class="empty">
                    <h4>No tienes servicios en curso</h4>
                    <p>Cuando aceptes una solicitud, la verás aquí para continuar el seguimiento.</p>
                </div>
            @else
                <div class="list">
                    @foreach($activeRequests as $request)
                        <article class="item">
                            <div class="item-head">
                                <div>
                                    <h4>{{ $request['service_name'] ?? 'Servicio' }}</h4>
                                    <p>{{ $request['pickup_address'] ?? 'Ubicación pendiente' }}</p>
                                </div>

                                <span class="chip {{ $requestStatusTone($request) }}">
                                    {{ $requestStatusLabel($request) }}
                                </span>
                            </div>

                            <div class="provider-dashboard-request-meta">
                                <span>{{ $request['public_id'] ?: 'Sin folio visible' }}</span>
                                <span>{{ $request['vehicle'] ?? 'Vehículo no especificado' }}</span>
                            </div>

                            <div class="provider-dashboard-actions" style="margin-top: 2px;">
                                <a href="{{ route('provider.asistencias.show', $request['id']) }}" class="btn">Ver detalle</a>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>
    </section>
@endsection
