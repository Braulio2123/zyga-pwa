@extends('provider.layouts.app')

@section('title', 'ZYGA | Solicitudes')
@section('page-key', 'asistencias')
@section('page-title', 'Solicitudes y seguimiento')
@section('page-copy', 'Revisa nuevas solicitudes, da seguimiento a servicios en curso y consulta tu historial desde una vista más clara y cómoda.')

@push('page_styles')
    <style>
        .assist-overview-grid,
        .assist-desktop-grid,
        .assist-mobile-stack,
        .assist-history-grid,
        .assist-meta,
        .assist-action-group,
        .assist-status-form,
        .assist-mobile-section__body,
        .assist-empty-stack {
            display: grid;
            gap: 14px;
        }

        .assist-overview-grid {
            grid-template-columns: 1fr;
        }

        .assist-desktop-grid,
        .assist-history-grid {
            grid-template-columns: 1fr;
        }

        .assist-mobile-stack {
            gap: 16px;
        }

        .assist-stat-card {
            padding: 18px;
            border-radius: 22px;
            border: 1px solid #e7ebf3;
            background: linear-gradient(180deg, #ffffff 0%, #fafbfd 100%);
            box-shadow: var(--provider-shadow-soft);
            min-height: 160px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 12px;
        }

        .assist-stat-card__top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
        }

        .assist-stat-card__label {
            margin: 0;
            color: var(--provider-text-soft);
            font-size: .92rem;
        }

        .assist-stat-card__value {
            margin: 0;
            font-size: clamp(1.9rem, 3vw, 2.45rem);
            line-height: 1;
            font-weight: 900;
            letter-spacing: -.04em;
            color: var(--provider-text);
            word-break: break-word;
        }

        .assist-stat-card__hint {
            margin: 0;
            color: var(--provider-text-soft);
            line-height: 1.5;
        }

        .assist-alert-strip {
            display: grid;
            gap: 12px;
            padding: 16px 18px;
            border-radius: 22px;
            border: 1px solid #dbe6ff;
            background: linear-gradient(180deg, #f8fbff 0%, #f3f7ff 100%);
        }

        .assist-alert-strip__head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
        }

        .assist-alert-strip__head h3 {
            margin: 0;
            font-size: 1.02rem;
        }

        .assist-alert-strip__head p {
            margin: 6px 0 0;
            color: var(--provider-text-soft);
            line-height: 1.5;
        }

        .assist-board {
            display: grid;
            gap: 14px;
        }

        .assist-board__header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
        }

        .assist-board__header h3 {
            margin: 0;
        }

        .assist-board__header p {
            margin: 6px 0 0;
            color: var(--provider-text-soft);
            line-height: 1.5;
        }

        .assist-list {
            display: grid;
            gap: 14px;
        }

        .assist-card {
            padding: 18px;
            border-radius: 22px;
            border: 1px solid #e7ebf3;
            background: linear-gradient(180deg, #ffffff 0%, #fafbfd 100%);
            box-shadow: var(--provider-shadow-soft);
            display: grid;
            gap: 14px;
        }

        .assist-card__head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 14px;
        }

        .assist-card__title {
            margin: 0;
            font-size: 1.04rem;
            line-height: 1.35;
        }

        .assist-card__address {
            margin: 6px 0 0;
            color: var(--provider-text-soft);
            line-height: 1.5;
        }

        .assist-card__date {
            margin: 8px 0 0;
            color: var(--provider-text-soft);
            font-size: .84rem;
        }

        .assist-meta {
            grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
        }

        .assist-meta__item {
            padding: 12px 13px;
            border-radius: 16px;
            background: #f8fafc;
            border: 1px solid #e4ebf5;
        }

        .assist-meta__item span {
            display: block;
            margin-bottom: 5px;
            font-size: .78rem;
            color: var(--provider-text-soft);
        }

        .assist-meta__item strong {
            display: block;
            font-size: .92rem;
            color: var(--provider-text);
            word-break: break-word;
            line-height: 1.4;
        }

        .assist-action-group {
            grid-template-columns: 1fr;
            align-items: start;
        }

        .assist-status-form {
            grid-template-columns: 1fr;
            align-items: start;
        }

        .assist-mobile-section {
            border-radius: 24px;
            background: rgba(255,255,255,.96);
            border: 1px solid rgba(229,231,235,.95);
            box-shadow: var(--provider-shadow-card);
            overflow: hidden;
        }

        .assist-mobile-section summary {
            list-style: none;
            cursor: pointer;
            padding: 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .assist-mobile-section summary::-webkit-details-marker {
            display: none;
        }

        .assist-mobile-section__title strong {
            display: block;
            font-size: 1rem;
            color: var(--provider-text);
        }

        .assist-mobile-section__title span {
            display: block;
            margin-top: 5px;
            font-size: .86rem;
            color: var(--provider-text-soft);
            line-height: 1.4;
        }

        .assist-mobile-section__body {
            padding: 0 18px 18px;
        }

        .assist-mobile-chevron {
            width: 18px;
            height: 18px;
            color: var(--provider-text-soft);
            transition: transform .2s ease;
            flex-shrink: 0;
        }

        .assist-mobile-section[open] .assist-mobile-chevron {
            transform: rotate(180deg);
        }

        .assist-empty-stack {
            padding: 18px;
            border-radius: 18px;
            border: 1px dashed #d6dee9;
            background: linear-gradient(180deg, #fbfbfc 0%, #f6f8fb 100%);
        }

        .assist-empty-stack h4 {
            margin: 0 0 8px;
        }

        .assist-empty-stack p {
            margin: 0;
            color: var(--provider-text-soft);
            line-height: 1.5;
        }

        .assist-lock-note {
            margin: 0;
            color: var(--provider-text-soft);
            line-height: 1.55;
        }

        @media (min-width: 768px) {
            .assist-overview-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }

            .assist-action-group,
            .assist-status-form {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (min-width: 1024px) {
            .assist-desktop-grid {
                grid-template-columns: minmax(0, 1.05fr) minmax(0, 1fr);
            }

            .assist-history-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .assist-action-group {
                grid-template-columns: auto auto;
                justify-content: start;
            }

            .assist-status-form {
                grid-template-columns: minmax(220px, 280px) auto;
                justify-content: start;
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

        $canOperate = (bool) ($r['backend_can_operate'] ?? false);
        $portalReady = (bool) ($r['portal_ready'] ?? false);

        $statusLabel = function (array $request): string {
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

        $statusTone = function (array $request): string {
            return $request['status_tone']
                ?? match ($request['status'] ?? null) {
                    'completed' => 'success',
                    'cancelled' => 'warning',
                    'assigned', 'in_progress' => 'info',
                    default => 'info',
                };
        };

        $dateLabel = function (?string $value): string {
            if (!$value) {
                return 'Fecha no disponible';
            }

            try {
                return \Illuminate\Support\Carbon::parse($value)->format('d/m/Y H:i');
            } catch (\Throwable $e) {
                return 'Fecha no disponible';
            }
        };

        $headlineTitle = $availableCount > 0
            ? 'Tienes solicitudes esperando atención'
            : 'No hay solicitudes nuevas por ahora';

        $headlineCopy = $availableCount > 0
            ? 'Revisa las solicitudes disponibles y decide si deseas aceptar alguna. Lo más importante aparece primero.'
            : 'Cuando exista una nueva solicitud compatible con tus servicios aparecerá aquí sin necesidad de revisar otras pantallas.';

        $operationChipText = $portalReady ? 'Cuenta lista para operar' : 'Todavía faltan pasos';
        $operationChipTone = $portalReady ? 'success' : 'warning';
    @endphp

    <section class="hero hero-split">
        <div>
            <p class="eyebrow">Atención del día</p>
            <h2 style="margin:0 0 12px; font-size: clamp(2rem, 3vw, 2.7rem); line-height: 1.05;">
                {{ $headlineTitle }}
            </h2>
            <p class="assist-lock-note" style="margin:0 0 16px;">
                {{ $headlineCopy }}
            </p>

            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <span class="chip {{ $operationChipTone }}">{{ $operationChipText }}</span>
                <span class="chip {{ $canOperate ? 'success' : 'warning' }}">
                    {{ $canOperate ? 'Puedes recibir solicitudes' : 'Recepción de solicitudes bloqueada' }}
                </span>
                <span class="chip info">
                    {{ $availableCount }} disponible{{ $availableCount === 1 ? '' : 's' }}
                </span>
            </div>

            <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top:16px;">
                <a href="{{ route('provider.dashboard') }}" class="btn-outline">Volver al inicio</a>
                @if(!$context['hasProfile'])
                    <a href="{{ route('provider.perfil') }}" class="btn">Completar perfil</a>
                @elseif(!$portalReady)
                    <a href="{{ route('provider.perfil') }}" class="btn">Revisar cuenta</a>
                @else
                    <a href="{{ route('provider.asistencias') }}" class="btn">Actualizar vista</a>
                @endif
            </div>

            <div class="assist-alert-strip" style="margin-top:18px;">
                <div class="assist-alert-strip__head">
                    <div>
                        <h3>
                            @if(!$context['hasProfile'])
                                Primero completa tu perfil
                            @elseif(!$portalReady)
                                Aún no puedes operar con normalidad
                            @elseif($availableCount > 0)
                                Hay oportunidades disponibles para ti
                            @else
                                Tu bandeja está al día
                            @endif
                        </h3>
                        <p>
                            @if(!$context['hasProfile'])
                                Antes de aceptar solicitudes necesitas registrar tu información como proveedor.
                            @elseif(!$portalReady)
                                Completa los pasos pendientes de tu cuenta para recibir y atender solicitudes sin restricciones.
                            @elseif($availableCount > 0)
                                Ya puedes revisar las solicitudes nuevas, ver el detalle y aceptar la que desees atender.
                            @else
                                No tienes solicitudes nuevas en este momento. Puedes seguir de cerca tus servicios activos e historial.
                            @endif
                        </p>
                    </div>

                    <span class="chip {{ $availableCount > 0 ? 'success' : 'info' }}">
                        {{ $availableCount > 0 ? 'Hay nuevas' : 'Sin nuevas' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="hero-panel">
            <div class="summary">
                <span class="helper">Solicitudes disponibles</span>
                <strong>{{ $availableCount }}</strong>
            </div>
            <div class="summary">
                <span class="helper">Servicios en curso</span>
                <strong>{{ $activeCount }}</strong>
            </div>
            <div class="summary">
                <span class="helper">Historial</span>
                <strong>{{ $historyCount }}</strong>
            </div>
        </div>
    </section>

    @if(!$context['hasProfile'])
        <section class="lockbox">
            <div class="section-head">
                <div>
                    <p class="eyebrow">Antes de comenzar</p>
                    <h3>Tu cuenta todavía no está lista</h3>
                </div>
            </div>

            <p class="assist-lock-note">
                Antes de recibir solicitudes necesitas completar tu perfil de proveedor.
            </p>

            <div style="margin-top:14px;">
                <a href="{{ route('provider.perfil') }}" class="btn">Ir a mi perfil</a>
            </div>
        </section>
    @elseif(!$portalReady)
        <section class="lockbox">
            <div class="section-head">
                <div>
                    <p class="eyebrow">Pasos pendientes</p>
                    <h3>Completa tu cuenta para operar sin restricciones</h3>
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

    <section class="assist-overview-grid desktop-only">
        <article class="assist-stat-card">
            <div class="assist-stat-card__top">
                <div>
                    <p class="assist-stat-card__label">Solicitudes nuevas</p>
                    <h3 class="assist-stat-card__value">{{ $availableCount }}</h3>
                </div>
                <span class="chip {{ $availableCount > 0 ? 'success' : 'info' }}">
                    {{ $availableCount > 0 ? 'Revisar' : 'Al día' }}
                </span>
            </div>

            <p class="assist-stat-card__hint">
                Aquí ves primero las oportunidades que puedes aceptar.
            </p>
        </article>

        <article class="assist-stat-card">
            <div class="assist-stat-card__top">
                <div>
                    <p class="assist-stat-card__label">Servicios en curso</p>
                    <h3 class="assist-stat-card__value">{{ $activeCount }}</h3>
                </div>
                <span class="chip {{ $activeCount > 0 ? 'info' : 'warning' }}">
                    {{ $activeCount > 0 ? 'Seguimiento' : 'Sin activos' }}
                </span>
            </div>

            <p class="assist-stat-card__hint">
                Solicitudes que ya aceptaste y todavía requieren seguimiento.
            </p>
        </article>

        <article class="assist-stat-card">
            <div class="assist-stat-card__top">
                <div>
                    <p class="assist-stat-card__label">Historial</p>
                    <h3 class="assist-stat-card__value">{{ $historyCount }}</h3>
                </div>
                <span class="chip info">Registrado</span>
            </div>

            <p class="assist-stat-card__hint">
                Servicios completados o cancelados guardados en tu historial.
            </p>
        </article>
    </section>

    <section class="assist-desktop-grid desktop-only">
        <section class="card assist-board">
            <div class="assist-board__header">
                <div>
                    <p class="eyebrow">Nuevas solicitudes</p>
                    <h3>Disponibles para atención</h3>
                    <p>Revisa la información principal y acepta solo la que desees atender.</p>
                </div>
                <span class="chip {{ $availableCount > 0 ? 'success' : 'info' }}">
                    {{ $availableCount }} disponible{{ $availableCount === 1 ? '' : 's' }}
                </span>
            </div>

            @if(!$canOperate)
                <div class="assist-empty-stack">
                    <h4>Aún no puedes recibir solicitudes</h4>
                    <p>Completa lo pendiente de tu cuenta para desbloquear esta sección.</p>
                </div>
            @elseif(empty($availableRequests))
                <div class="assist-empty-stack">
                    <h4>No hay solicitudes disponibles</h4>
                    <p>Cuando llegue una nueva solicitud compatible aparecerá aquí automáticamente.</p>
                </div>
            @else
                <div class="assist-list">
                    @foreach($availableRequests as $request)
                        <article class="assist-card">
                            <div class="assist-card__head">
                                <div>
                                    <h4 class="assist-card__title">{{ $request['service_name'] ?? 'Servicio' }}</h4>
                                    <p class="assist-card__address">{{ $request['pickup_address'] ?? 'Ubicación pendiente' }}</p>
                                    <p class="assist-card__date">Registrada: {{ $dateLabel($request['created_at'] ?? null) }}</p>
                                </div>

                                <span class="chip {{ $statusTone($request) }}">
                                    {{ $statusLabel($request) }}
                                </span>
                            </div>

                            <div class="assist-meta">
                                <div class="assist-meta__item">
                                    <span>Folio</span>
                                    <strong>{{ $request['public_id'] ?: 'Sin folio visible' }}</strong>
                                </div>

                                <div class="assist-meta__item">
                                    <span>Vehículo</span>
                                    <strong>{{ $request['vehicle'] ?? 'Vehículo no especificado' }}</strong>
                                </div>

                                @if(!empty($request['pickup_reference']))
                                    <div class="assist-meta__item">
                                        <span>Referencia</span>
                                        <strong>{{ $request['pickup_reference'] }}</strong>
                                    </div>
                                @endif
                            </div>

                            <div class="assist-action-group">
                                <a href="{{ route('provider.asistencias.show', $request['id']) }}" class="btn-outline">
                                    Ver detalle
                                </a>

                                @if($portalReady)
                                    <form action="{{ route('provider.asistencias.accept', $request['id']) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn" type="submit">Aceptar servicio</button>
                                    </form>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>

        <section class="card assist-board">
            <div class="assist-board__header">
                <div>
                    <p class="eyebrow">Seguimiento</p>
                    <h3>Servicios en curso</h3>
                    <p>Actualiza el estado de cada servicio y mantén el seguimiento claro.</p>
                </div>
                <span class="chip {{ $activeCount > 0 ? 'info' : 'warning' }}">
                    {{ $activeCount }} activo{{ $activeCount === 1 ? '' : 's' }}
                </span>
            </div>

            @if(empty($activeRequests))
                <div class="assist-empty-stack">
                    <h4>No tienes servicios en curso</h4>
                    <p>Cuando aceptes una solicitud, la verás aquí para darle seguimiento.</p>
                </div>
            @else
                <div class="assist-list">
                    @foreach($activeRequests as $request)
                        @php($statusOptions = $allowedStatusOptionsResolver($request['status']))
                        <article class="assist-card">
                            <div class="assist-card__head">
                                <div>
                                    <h4 class="assist-card__title">{{ $request['service_name'] ?? 'Servicio' }}</h4>
                                    <p class="assist-card__address">{{ $request['pickup_address'] ?? 'Ubicación pendiente' }}</p>
                                    <p class="assist-card__date">Actualizada: {{ $dateLabel($request['updated_at'] ?? null) }}</p>
                                </div>

                                <span class="chip {{ $statusTone($request) }}">
                                    {{ $statusLabel($request) }}
                                </span>
                            </div>

                            <div class="assist-meta">
                                <div class="assist-meta__item">
                                    <span>Folio</span>
                                    <strong>{{ $request['public_id'] ?: 'Sin folio visible' }}</strong>
                                </div>

                                <div class="assist-meta__item">
                                    <span>Vehículo</span>
                                    <strong>{{ $request['vehicle'] ?? 'Vehículo no especificado' }}</strong>
                                </div>

                                @if(!empty($request['client_email']))
                                    <div class="assist-meta__item">
                                        <span>Cliente</span>
                                        <strong>{{ $request['client_email'] }}</strong>
                                    </div>
                                @endif
                            </div>

                            <div class="assist-action-group">
                                <a href="{{ route('provider.asistencias.show', $request['id']) }}" class="btn-outline">
                                    Ver detalle
                                </a>

                                @if(!empty($statusOptions) && $portalReady)
                                    <form action="{{ route('provider.asistencias.status', $request['id']) }}" method="POST" class="assist-status-form">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" required>
                                            <option value="">Selecciona una actualización</option>
                                            @foreach($statusOptions as $value => $label)
                                                <option value="{{ $value }}">{{ $label }}</option>
                                            @endforeach
                                        </select>

                                        <button type="submit" class="btn">Guardar estado</button>
                                    </form>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>
    </section>

    <section class="card desktop-only">
        <div class="assist-board__header">
            <div>
                <p class="eyebrow">Historial</p>
                <h3>Servicios cerrados</h3>
                <p>Consulta servicios completados o cancelados sin saturar la vista principal.</p>
            </div>
            <span class="chip info">{{ $historyCount }} registro{{ $historyCount === 1 ? '' : 's' }}</span>
        </div>

        @if(empty($historicalRequests))
            <div class="assist-empty-stack">
                <h4>Sin historial todavía</h4>
                <p>Cuando completes o canceles solicitudes aparecerán aquí.</p>
            </div>
        @else
            <div class="assist-history-grid">
                @foreach($historicalRequests as $request)
                    <article class="assist-card">
                        <div class="assist-card__head">
                            <div>
                                <h4 class="assist-card__title">{{ $request['service_name'] ?? 'Servicio' }}</h4>
                                <p class="assist-card__address">{{ $request['pickup_address'] ?? 'Ubicación pendiente' }}</p>
                                <p class="assist-card__date">Actualizada: {{ $dateLabel($request['updated_at'] ?? null) }}</p>
                            </div>

                            <span class="chip {{ $statusTone($request) }}">
                                {{ $statusLabel($request) }}
                            </span>
                        </div>

                        <div class="assist-meta">
                            <div class="assist-meta__item">
                                <span>Folio</span>
                                <strong>{{ $request['public_id'] ?: 'Sin folio visible' }}</strong>
                            </div>

                            <div class="assist-meta__item">
                                <span>Vehículo</span>
                                <strong>{{ $request['vehicle'] ?? 'Vehículo no especificado' }}</strong>
                            </div>
                        </div>

                        <div class="assist-action-group">
                            <a href="{{ route('provider.asistencias.show', $request['id']) }}" class="btn-outline">
                                Ver detalle
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>

    <section class="assist-mobile-stack mobile-only">
        <details class="assist-mobile-section" open>
            <summary>
                <div class="assist-mobile-section__title">
                    <strong>Solicitudes disponibles</strong>
                    <span>Lo primero que conviene revisar.</span>
                </div>

                <div style="display:flex; align-items:center; gap:10px;">
                    <span class="chip {{ $availableCount > 0 ? 'success' : 'info' }}">{{ $availableCount }}</span>
                    <span class="assist-mobile-chevron">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M7 10l5 5 5-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </div>
            </summary>

            <div class="assist-mobile-section__body">
                @if(!$canOperate)
                    <div class="assist-empty-stack">
                        <h4>Aún no puedes recibir solicitudes</h4>
                        <p>Completa lo pendiente en tu cuenta para desbloquear esta sección.</p>
                    </div>
                @elseif(empty($availableRequests))
                    <div class="assist-empty-stack">
                        <h4>No hay solicitudes nuevas</h4>
                        <p>Cuando llegue una solicitud compatible aparecerá aquí.</p>
                    </div>
                @else
                    <div class="assist-list">
                        @foreach($availableRequests as $request)
                            <article class="assist-card">
                                <div class="assist-card__head">
                                    <div>
                                        <h4 class="assist-card__title">{{ $request['service_name'] ?? 'Servicio' }}</h4>
                                        <p class="assist-card__address">{{ $request['pickup_address'] ?? 'Ubicación pendiente' }}</p>
                                    </div>

                                    <span class="chip {{ $statusTone($request) }}">
                                        {{ $statusLabel($request) }}
                                    </span>
                                </div>

                                <div class="assist-meta">
                                    <div class="assist-meta__item">
                                        <span>Folio</span>
                                        <strong>{{ $request['public_id'] ?: 'Sin folio visible' }}</strong>
                                    </div>

                                    <div class="assist-meta__item">
                                        <span>Vehículo</span>
                                        <strong>{{ $request['vehicle'] ?? 'Vehículo no especificado' }}</strong>
                                    </div>
                                </div>

                                <div class="assist-action-group">
                                    <a href="{{ route('provider.asistencias.show', $request['id']) }}" class="btn-outline">
                                        Ver detalle
                                    </a>

                                    @if($portalReady)
                                        <form action="{{ route('provider.asistencias.accept', $request['id']) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button class="btn" type="submit">Aceptar servicio</button>
                                        </form>
                                    @endif
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </div>
        </details>

        <details class="assist-mobile-section" open>
            <summary>
                <div class="assist-mobile-section__title">
                    <strong>Servicios en curso</strong>
                    <span>Seguimiento y actualización de estado.</span>
                </div>

                <div style="display:flex; align-items:center; gap:10px;">
                    <span class="chip {{ $activeCount > 0 ? 'info' : 'warning' }}">{{ $activeCount }}</span>
                    <span class="assist-mobile-chevron">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M7 10l5 5 5-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </div>
            </summary>

            <div class="assist-mobile-section__body">
                @if(empty($activeRequests))
                    <div class="assist-empty-stack">
                        <h4>No tienes servicios en curso</h4>
                        <p>Cuando aceptes una solicitud la verás aquí para continuar el seguimiento.</p>
                    </div>
                @else
                    <div class="assist-list">
                        @foreach($activeRequests as $request)
                            @php($statusOptions = $allowedStatusOptionsResolver($request['status']))
                            <article class="assist-card">
                                <div class="assist-card__head">
                                    <div>
                                        <h4 class="assist-card__title">{{ $request['service_name'] ?? 'Servicio' }}</h4>
                                        <p class="assist-card__address">{{ $request['pickup_address'] ?? 'Ubicación pendiente' }}</p>
                                    </div>

                                    <span class="chip {{ $statusTone($request) }}">
                                        {{ $statusLabel($request) }}
                                    </span>
                                </div>

                                <div class="assist-meta">
                                    <div class="assist-meta__item">
                                        <span>Folio</span>
                                        <strong>{{ $request['public_id'] ?: 'Sin folio visible' }}</strong>
                                    </div>

                                    <div class="assist-meta__item">
                                        <span>Vehículo</span>
                                        <strong>{{ $request['vehicle'] ?? 'Vehículo no especificado' }}</strong>
                                    </div>
                                </div>

                                <div class="assist-action-group">
                                    <a href="{{ route('provider.asistencias.show', $request['id']) }}" class="btn-outline">
                                        Ver detalle
                                    </a>
                                </div>

                                @if(!empty($statusOptions) && $portalReady)
                                    <form action="{{ route('provider.asistencias.status', $request['id']) }}" method="POST" class="assist-status-form">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" required>
                                            <option value="">Selecciona una actualización</option>
                                            @foreach($statusOptions as $value => $label)
                                                <option value="{{ $value }}">{{ $label }}</option>
                                            @endforeach
                                        </select>

                                        <button type="submit" class="btn">Guardar estado</button>
                                    </form>
                                @endif
                            </article>
                        @endforeach
                    </div>
                @endif
            </div>
        </details>

        <details class="assist-mobile-section">
            <summary>
                <div class="assist-mobile-section__title">
                    <strong>Historial</strong>
                    <span>Servicios completados o cancelados.</span>
                </div>

                <div style="display:flex; align-items:center; gap:10px;">
                    <span class="chip info">{{ $historyCount }}</span>
                    <span class="assist-mobile-chevron">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M7 10l5 5 5-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </div>
            </summary>

            <div class="assist-mobile-section__body">
                @if(empty($historicalRequests))
                    <div class="assist-empty-stack">
                        <h4>Sin historial todavía</h4>
                        <p>Cuando completes o canceles servicios aparecerán aquí.</p>
                    </div>
                @else
                    <div class="assist-list">
                        @foreach($historicalRequests as $request)
                            <article class="assist-card">
                                <div class="assist-card__head">
                                    <div>
                                        <h4 class="assist-card__title">{{ $request['service_name'] ?? 'Servicio' }}</h4>
                                        <p class="assist-card__address">{{ $request['pickup_address'] ?? 'Ubicación pendiente' }}</p>
                                    </div>

                                    <span class="chip {{ $statusTone($request) }}">
                                        {{ $statusLabel($request) }}
                                    </span>
                                </div>

                                <div class="assist-meta">
                                    <div class="assist-meta__item">
                                        <span>Folio</span>
                                        <strong>{{ $request['public_id'] ?: 'Sin folio visible' }}</strong>
                                    </div>

                                    <div class="assist-meta__item">
                                        <span>Vehículo</span>
                                        <strong>{{ $request['vehicle'] ?? 'Vehículo no especificado' }}</strong>
                                    </div>
                                </div>

                                <div class="assist-action-group">
                                    <a href="{{ route('provider.asistencias.show', $request['id']) }}" class="btn-outline">
                                        Ver detalle
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </div>
        </details>
    </section>
@endsection
