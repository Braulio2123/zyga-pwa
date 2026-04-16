@extends('provider.layouts.app')

@section('title', 'ZYGA | Detalle del servicio')
@section('page-title', 'Detalle del servicio')
@section('page-copy', 'Consulta la información del servicio, revisa la ubicación del cliente y da seguimiento al avance desde una vista más clara.')
@section('page-key', 'asistencias')

@push('page_styles')
<link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
/>

<style>
    .service-hero-grid,
    .service-overview-grid,
    .service-desktop-grid,
    .service-map-layout,
    .service-meta-grid,
    .service-action-stack,
    .service-status-form,
    .service-tracking-grid,
    .service-timeline-grid,
    .service-mobile-stack,
    .service-mobile-section__body {
        display: grid;
        gap: 14px;
    }

    .service-hero-grid,
    .service-overview-grid,
    .service-desktop-grid,
    .service-map-layout,
    .service-timeline-grid {
        grid-template-columns: 1fr;
    }

    .service-hero-panel {
        display: grid;
        gap: 12px;
        padding: 18px;
        border-radius: 24px;
        background: linear-gradient(180deg, #0f2e6f 0%, #173b8a 100%);
        color: #fff;
    }

    .service-kpi {
        padding: 16px;
        border-radius: 18px;
        background: rgba(255,255,255,.12);
        border: 1px solid rgba(255,255,255,.10);
    }

    .service-kpi span {
        display: block;
        color: rgba(255,255,255,.74);
        font-size: .84rem;
    }

    .service-kpi strong {
        display: block;
        margin-top: 8px;
        color: #fff;
        font-size: 1.55rem;
        line-height: 1.1;
        letter-spacing: -.03em;
        word-break: break-word;
    }

    .service-alert {
        padding: 16px 18px;
        border-radius: 20px;
        border: 1px solid #dbe6ff;
        background: linear-gradient(180deg, #f8fbff 0%, #f3f7ff 100%);
    }

    .service-alert h3 {
        margin: 0 0 8px;
        font-size: 1rem;
    }

    .service-alert p {
        margin: 0;
        color: var(--provider-text-soft);
        line-height: 1.55;
    }

    .service-summary-card,
    .service-action-card,
    .service-map-card,
    .service-tracking-card,
    .service-timeline-card {
        display: grid;
        gap: 14px;
    }

    .service-meta-grid {
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    }

    .service-meta-item {
        padding: 14px 15px;
        border-radius: 18px;
        border: 1px solid #e4ebf5;
        background: #f8fafc;
    }

    .service-meta-item span {
        display: block;
        margin-bottom: 6px;
        font-size: .8rem;
        color: var(--provider-text-soft);
    }

    .service-meta-item strong {
        display: block;
        font-size: .94rem;
        color: var(--provider-text);
        line-height: 1.5;
        word-break: break-word;
    }

    .service-action-stack {
        grid-template-columns: 1fr;
        align-items: start;
    }

    .service-status-form {
        grid-template-columns: 1fr;
        align-items: start;
    }

    .service-note-box {
        padding: 16px;
        border-radius: 18px;
        background: linear-gradient(180deg, #fffaf2 0%, #fff 100%);
        border: 1px solid #ffe1b3;
        color: var(--provider-text);
    }

    .service-note-box h4 {
        margin: 0 0 8px;
        font-size: 1rem;
    }

    .service-note-box p {
        margin: 0;
        color: var(--provider-text-soft);
        line-height: 1.55;
    }

    .service-map-shell {
        position: relative;
        border-radius: 24px;
        overflow: hidden;
        border: 1px solid rgba(15, 23, 42, 0.08);
        background: #e2e8f0;
        min-height: 360px;
    }

    .service-map {
        width: 100%;
        min-height: 360px;
    }

    .service-map-overlay {
        position: absolute;
        inset: auto 16px 16px 16px;
        z-index: 500;
        background: rgba(15, 23, 42, 0.76);
        color: #fff;
        padding: 12px 14px;
        border-radius: 16px;
        font-size: .9rem;
        line-height: 1.5;
        backdrop-filter: blur(8px);
    }

    .service-map-side {
        display: grid;
        gap: 12px;
    }

    .service-map-info {
        padding: 15px;
        border-radius: 18px;
        border: 1px solid #e4ebf5;
        background: #f8fafc;
    }

    .service-map-info span {
        display: block;
        margin-bottom: 6px;
        font-size: .8rem;
        color: var(--provider-text-soft);
    }

    .service-map-info strong {
        display: block;
        font-size: .94rem;
        line-height: 1.5;
        color: var(--provider-text);
        word-break: break-word;
    }

    .service-map-links {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .service-map-marker-wrapper {
        background: transparent;
        border: 0;
    }

    .service-map-marker {
        display: block;
        width: 20px;
        height: 20px;
        border-radius: 999px;
        border: 3px solid #fff;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.24);
    }

    .service-map-marker--target {
        background: #2563eb;
    }

    .service-map-marker--provider {
        background: #f97316;
    }

    .service-tracking-status {
        padding: 16px 18px;
        border-radius: 18px;
        border: 1px solid rgba(15, 23, 42, 0.08);
        background: rgba(37, 99, 235, 0.08);
        color: #1d4ed8;
        font-weight: 700;
        line-height: 1.5;
    }

    .service-tracking-status--success {
        background: rgba(22, 163, 74, 0.08);
        color: #15803d;
    }

    .service-tracking-status--warning {
        background: rgba(245, 158, 11, 0.12);
        color: #b45309;
    }

    .service-tracking-status--danger {
        background: rgba(239, 68, 68, 0.10);
        color: #b91c1c;
    }

    .service-tracking-grid {
        grid-template-columns: repeat(4, minmax(0, 1fr));
    }

    .service-tracking-box {
        padding: 15px;
        border-radius: 18px;
        border: 1px solid #e4ebf5;
        background: #f8fafc;
    }

    .service-tracking-box span {
        display: block;
        margin-bottom: 6px;
        font-size: .8rem;
        color: var(--provider-text-soft);
    }

    .service-tracking-box strong {
        display: block;
        font-size: .94rem;
        color: var(--provider-text);
        line-height: 1.5;
        word-break: break-word;
    }

    .service-tracking-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .service-tracking-log {
        padding: 16px;
        border-radius: 18px;
        border: 1px dashed #d4dce8;
        background: #fbfcfe;
        color: var(--provider-text-soft);
        line-height: 1.6;
        min-height: 68px;
    }

    .service-mobile-section {
        border-radius: 24px;
        background: rgba(255,255,255,.96);
        border: 1px solid rgba(229,231,235,.95);
        box-shadow: var(--provider-shadow-card);
        overflow: hidden;
    }

    .service-mobile-section summary {
        list-style: none;
        cursor: pointer;
        padding: 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .service-mobile-section summary::-webkit-details-marker {
        display: none;
    }

    .service-mobile-title strong {
        display: block;
        font-size: 1rem;
        color: var(--provider-text);
    }

    .service-mobile-title span {
        display: block;
        margin-top: 5px;
        font-size: .86rem;
        color: var(--provider-text-soft);
        line-height: 1.4;
    }

    .service-mobile-section__body {
        padding: 0 18px 18px;
    }

    .service-mobile-chevron {
        width: 18px;
        height: 18px;
        color: var(--provider-text-soft);
        transition: transform .2s ease;
        flex-shrink: 0;
    }

    .service-mobile-section[open] .service-mobile-chevron {
        transform: rotate(180deg);
    }

    .service-timeline-list {
        display: grid;
        gap: 12px;
    }

    .service-timeline-entry {
        padding: 15px;
        border-radius: 18px;
        border-left: 4px solid #c7d7ff;
        background: #f8fafc;
    }

    .service-timeline-entry strong {
        display: block;
        color: var(--provider-text);
    }

    .service-timeline-entry p,
    .service-timeline-entry small {
        margin: 6px 0 0;
        color: var(--provider-text-soft);
        line-height: 1.5;
    }

    @media (min-width: 768px) {
        .service-overview-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .service-action-stack,
        .service-status-form,
        .service-timeline-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (min-width: 1024px) {
        .service-hero-grid {
            grid-template-columns: 1.15fr .92fr;
        }

        .service-desktop-grid {
            grid-template-columns: minmax(0, 1.04fr) minmax(0, .96fr);
        }

        .service-map-layout {
            grid-template-columns: minmax(0, 1.24fr) minmax(320px, .86fr);
            align-items: start;
        }

        .service-status-form {
            grid-template-columns: minmax(220px, 280px) auto;
            justify-content: start;
        }
    }

    @media (max-width: 960px) {
        .service-tracking-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 640px) {
        .service-tracking-grid {
            grid-template-columns: 1fr;
        }

        .service-map-shell,
        .service-map {
            min-height: 300px;
        }
    }
</style>
@endpush

@section('content')
    @php
        $r = $context['readiness'];
        $portalReady = (bool) ($r['portal_ready'] ?? false);
        $currentStatus = $requestItem['status'] ?? null;
        $trackingAllowed = $portalReady && in_array($currentStatus, ['assigned', 'in_progress'], true);
        $hasTargetCoordinates = ($requestItem['lat'] ?? null) !== null && ($requestItem['lng'] ?? null) !== null;

        $statusText = $requestItem['status_label']
            ?? match ($currentStatus) {
                'created' => 'Nueva',
                'assigned' => 'Asignada',
                'in_progress' => 'En proceso',
                'completed' => 'Completada',
                'cancelled' => 'Cancelada',
                default => 'Sin estado',
            };

        $statusTone = $requestItem['status_tone']
            ?? match ($currentStatus) {
                'completed' => 'success',
                'cancelled' => 'warning',
                'assigned', 'in_progress' => 'info',
                default => 'info',
            };

        $formatDate = function (?string $value): string {
            if (!$value) {
                return 'No disponible';
            }

            try {
                return \Illuminate\Support\Carbon::parse($value)->format('d/m/Y H:i');
            } catch (\Throwable $e) {
                return 'No disponible';
            }
        };

        $serviceTitle = $requestItem['service_name'] ?? 'Servicio';
        $pickupAddress = $requestItem['pickup_address'] ?? 'Ubicación pendiente';
        $pickupReference = $requestItem['pickup_reference'] ?: 'El cliente no registró una referencia adicional.';
        $publicId = $requestItem['public_id'] ?: 'Sin folio visible';
        $vehicle = $requestItem['vehicle'] ?? 'Vehículo no especificado';
        $clientEmail = $requestItem['client_email'] ?: 'Sin correo visible';
        $coordinatesText = $hasTargetCoordinates
            ? $requestItem['lat'] . ', ' . $requestItem['lng']
            : 'Sin coordenadas disponibles';

        $headlineTitle = match ($currentStatus) {
            'created' => 'Solicitud lista para revisión',
            'assigned' => 'Servicio aceptado y en seguimiento',
            'in_progress' => 'Servicio en proceso',
            'completed' => 'Servicio finalizado',
            'cancelled' => 'Servicio cancelado',
            default => 'Detalle del servicio',
        };

        $headlineCopy = match ($currentStatus) {
            'created' => 'Revisa la información principal del servicio y decide si deseas aceptarlo.',
            'assigned' => 'Ya aceptaste este servicio. Desde aquí puedes consultar la ubicación y continuar el seguimiento.',
            'in_progress' => 'El servicio está activo. Mantén actualizado el estado y utiliza el mapa para orientarte.',
            'completed' => 'Este servicio ya fue finalizado. Aquí puedes consultar su información registrada.',
            'cancelled' => 'Este servicio fue cancelado. Aquí se mantiene el detalle para consulta.',
            default => 'Consulta la información del servicio y sus movimientos principales.',
        };

        $nextStepTitle = match (true) {
            !$portalReady => 'Tu cuenta todavía necesita pasos pendientes',
            $currentStatus === 'created' => 'Puedes aceptar esta solicitud',
            $currentStatus === 'assigned' => 'Conviene actualizar el avance cuando te dirijas al cliente',
            $currentStatus === 'in_progress' => 'Mantén actualizado el estado hasta terminar el servicio',
            $currentStatus === 'completed' => 'Este servicio ya quedó concluido',
            $currentStatus === 'cancelled' => 'Este servicio ya no requiere más acciones',
            default => 'Consulta el detalle y toma la acción necesaria',
        };

        $nextStepCopy = match (true) {
            !$portalReady => 'Antes de operar con normalidad conviene completar lo pendiente en tu cuenta.',
            $currentStatus === 'created' => 'Si deseas atender este servicio, puedes aceptarlo desde esta misma pantalla.',
            $currentStatus === 'assigned' => 'Cuando ya te encuentres rumbo al cliente o atendiendo el caso, actualiza el estado.',
            $currentStatus === 'in_progress' => 'Usa esta vista para dar seguimiento, consultar el mapa y cerrar correctamente el servicio.',
            $currentStatus === 'completed' => 'La información queda disponible como referencia e historial.',
            $currentStatus === 'cancelled' => 'No se requieren acciones adicionales para esta solicitud.',
            default => 'Revisa el estado actual y avanza según corresponda.',
        };
    @endphp

    <section class="hero hero-split service-hero-grid desktop-only">
        <div>
            <p class="eyebrow">Detalle del servicio</p>
            <h2 style="margin:0 0 12px; font-size: clamp(2rem, 3vw, 2.7rem); line-height: 1.05;">
                {{ $headlineTitle }}
            </h2>
            <p style="margin:0 0 16px; color: var(--provider-text-soft); line-height: 1.6;">
                {{ $headlineCopy }}
            </p>

            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <span class="chip {{ $statusTone }}">{{ $statusText }}</span>
                <span class="chip info">{{ $publicId }}</span>
                <span class="chip {{ $portalReady ? 'success' : 'warning' }}">
                    {{ $portalReady ? 'Cuenta lista para operar' : 'Cuenta con pasos pendientes' }}
                </span>
                <span class="chip {{ $trackingAllowed ? 'success' : 'warning' }}">
                    {{ $trackingAllowed ? 'Ubicación activa disponible' : 'Ubicación en vivo no disponible' }}
                </span>
            </div>

            <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top:16px;">
                <a href="{{ route('provider.asistencias') }}" class="btn-outline">Volver a solicitudes</a>

                @if($currentStatus === 'created' && $portalReady)
                    <form method="POST" action="{{ route('provider.asistencias.accept', $requestItem['id']) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn">Aceptar servicio</button>
                    </form>
                @endif
            </div>

            <div class="service-alert" style="margin-top:18px;">
                <h3>{{ $nextStepTitle }}</h3>
                <p>{{ $nextStepCopy }}</p>
            </div>
        </div>

        <div class="service-hero-panel">
            <div class="service-kpi">
                <span>Servicio</span>
                <strong>{{ $serviceTitle }}</strong>
            </div>

            <div class="service-kpi">
                <span>Ubicación del cliente</span>
                <strong>{{ $pickupAddress }}</strong>
            </div>

            <div class="service-kpi">
                <span>Vehículo</span>
                <strong>{{ $vehicle }}</strong>
            </div>

            <div class="service-kpi">
                <span>Última actualización</span>
                <strong>{{ $formatDate($requestItem['updated_at'] ?? null) }}</strong>
            </div>
        </div>
    </section>

    <section class="assist-mobile-stack mobile-only" style="display:grid; gap:16px;">
        <section class="hero">
            <p class="eyebrow">Detalle del servicio</p>
            <h2 style="margin:0 0 12px; font-size: 1.9rem; line-height:1.1;">
                {{ $headlineTitle }}
            </h2>
            <p style="margin:0 0 14px; color: var(--provider-text-soft); line-height:1.6;">
                {{ $headlineCopy }}
            </p>

            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <span class="chip {{ $statusTone }}">{{ $statusText }}</span>
                <span class="chip info">{{ $publicId }}</span>
            </div>

            <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top:14px;">
                <a href="{{ route('provider.asistencias') }}" class="btn-outline">Volver</a>

                @if($currentStatus === 'created' && $portalReady)
                    <form method="POST" action="{{ route('provider.asistencias.accept', $requestItem['id']) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn">Aceptar</button>
                    </form>
                @endif
            </div>
        </section>

        <details class="service-mobile-section" open>
            <summary>
                <div class="service-mobile-title">
                    <strong>Resumen del servicio</strong>
                    <span>Lo más importante del caso en un solo lugar.</span>
                </div>
                <span class="service-mobile-chevron">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M7 10l5 5 5-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
            </summary>

            <div class="service-mobile-section__body">
                <div class="service-meta-grid">
                    <div class="service-meta-item">
                        <span>Servicio</span>
                        <strong>{{ $serviceTitle }}</strong>
                    </div>

                    <div class="service-meta-item">
                        <span>Dirección</span>
                        <strong>{{ $pickupAddress }}</strong>
                    </div>

                    <div class="service-meta-item">
                        <span>Vehículo</span>
                        <strong>{{ $vehicle }}</strong>
                    </div>

                    <div class="service-meta-item">
                        <span>Cliente</span>
                        <strong>{{ $clientEmail }}</strong>
                    </div>

                    <div class="service-meta-item">
                        <span>Referencia</span>
                        <strong>{{ $pickupReference }}</strong>
                    </div>

                    <div class="service-meta-item">
                        <span>Coordenadas</span>
                        <strong>{{ $coordinatesText }}</strong>
                    </div>
                </div>

                <div class="service-note-box">
                    <h4>{{ $nextStepTitle }}</h4>
                    <p>{{ $nextStepCopy }}</p>
                </div>
            </div>
        </details>
    </section>

    <section class="service-desktop-grid desktop-only">
        <section class="card service-summary-card">
            <div class="section-head">
                <div>
                    <p class="eyebrow">Información principal</p>
                    <h3>Resumen del servicio</h3>
                </div>
            </div>

            <div class="service-meta-grid">
                <div class="service-meta-item">
                    <span>Servicio</span>
                    <strong>{{ $serviceTitle }}</strong>
                </div>

                <div class="service-meta-item">
                    <span>Dirección</span>
                    <strong>{{ $pickupAddress }}</strong>
                </div>

                <div class="service-meta-item">
                    <span>Cliente</span>
                    <strong>{{ $clientEmail }}</strong>
                </div>

                <div class="service-meta-item">
                    <span>Vehículo</span>
                    <strong>{{ $vehicle }}</strong>
                </div>

                <div class="service-meta-item">
                    <span>Coordenadas del cliente</span>
                    <strong>{{ $coordinatesText }}</strong>
                </div>

                <div class="service-meta-item">
                    <span>Última actualización</span>
                    <strong>{{ $formatDate($requestItem['updated_at'] ?? null) }}</strong>
                </div>

                <div class="service-meta-item" style="grid-column: 1 / -1;">
                    <span>Referencia adicional</span>
                    <strong>{{ $pickupReference }}</strong>
                </div>
            </div>
        </section>

        <section class="card service-action-card">
            <div class="section-head">
                <div>
                    <p class="eyebrow">Acciones</p>
                    <h3>Qué puedes hacer ahora</h3>
                </div>
            </div>

            <div class="service-note-box">
                <h4>{{ $nextStepTitle }}</h4>
                <p>{{ $nextStepCopy }}</p>
            </div>

            @if(!$portalReady)
                <div class="empty">
                    <h4>Tu cuenta todavía no está lista</h4>
                    <p>Primero completa lo pendiente en tu configuración para poder operar con normalidad.</p>
                </div>
            @elseif($currentStatus === 'created')
                <div class="service-action-stack">
                    <form method="POST" action="{{ route('provider.asistencias.accept', $requestItem['id']) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn">Aceptar servicio</button>
                    </form>

                    <a href="{{ route('provider.asistencias') }}" class="btn-outline">Regresar a solicitudes</a>
                </div>
            @elseif(empty($allowedStatusOptions))
                <div class="empty">
                    <h4>No hay cambios manuales disponibles</h4>
                    <p>Este servicio no admite una actualización manual adicional desde esta pantalla.</p>
                </div>
            @else
                <form method="POST" action="{{ route('provider.asistencias.status', $requestItem['id']) }}" class="service-status-form">
                    @csrf
                    @method('PATCH')

                    <select id="status" name="status" required>
                        <option value="">Selecciona una actualización</option>
                        @foreach($allowedStatusOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>

                    <button type="submit" class="btn">Guardar estado</button>
                </form>
            @endif
        </section>
    </section>

    <section class="card service-map-card">
        <div class="section-head">
            <div>
                <p class="eyebrow">Ubicación</p>
                <h3>Mapa y navegación</h3>
            </div>
        </div>

        @if(!$hasTargetCoordinates)
            <div class="empty">
                <h4>No hay coordenadas disponibles</h4>
                <p>Esta solicitud no cuenta con una ubicación precisa para mostrar el mapa o abrir navegación externa.</p>
            </div>
        @else
            <div class="service-map-layout">
                <div class="service-map-shell">
                    <div id="providerServiceMap" class="service-map"></div>
                    <div id="providerServiceMapOverlay" class="service-map-overlay">
                        El punto azul muestra la ubicación del cliente. Cuando tu posición se actualice aparecerá en color naranja.
                    </div>
                </div>

                <div class="service-map-side">
                    <div class="service-map-info">
                        <span>Dirección del cliente</span>
                        <strong id="providerTargetAddress">{{ $pickupAddress }}</strong>
                    </div>

                    <div class="service-map-info">
                        <span>Referencia adicional</span>
                        <strong id="providerTargetReference">{{ $pickupReference }}</strong>
                    </div>

                    <div class="service-map-info">
                        <span>Coordenadas del cliente</span>
                        <strong id="providerTargetCoordinates">{{ $coordinatesText }}</strong>
                    </div>

                    <div class="service-map-info">
                        <span>Tu ubicación actual</span>
                        <strong id="providerCurrentCoordinates">Pendiente de ubicación</strong>
                    </div>

                    <div class="service-map-info">
                        <span>Navegación externa</span>
                        <div class="service-map-links" style="margin-top:10px;">
                            <a
                                id="providerGoogleMapsLink"
                                href="#"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="btn"
                            >
                                Abrir en Google Maps
                            </a>

                            <a
                                id="providerWazeLink"
                                href="#"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="btn-outline"
                            >
                                Abrir en Waze
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </section>

    <section class="card service-tracking-card">
        <div class="section-head">
            <div>
                <p class="eyebrow">Ubicación en tiempo real</p>
                <h3>Seguimiento del proveedor</h3>
            </div>
        </div>

        @if(!$portalReady)
            <div class="empty">
                <h4>Ubicación en tiempo real no disponible</h4>
                <p>Tu cuenta todavía no está lista para compartir ubicación durante el servicio.</p>
            </div>
        @elseif(!in_array($currentStatus, ['assigned', 'in_progress'], true))
            <div class="empty">
                <h4>La ubicación en vivo aún no aplica para este servicio</h4>
                <p>La ubicación en tiempo real se activa cuando el servicio ya fue aceptado o está en proceso.</p>
            </div>
        @else
            <div id="providerTrackingStatus" class="service-tracking-status">
                Preparando la ubicación del proveedor...
            </div>

            <div class="service-tracking-grid">
                <article class="service-tracking-box">
                    <span>Estado actual</span>
                    <strong id="providerTrackingState">Inicializando</strong>
                </article>

                <article class="service-tracking-box">
                    <span>Última coordenada enviada</span>
                    <strong id="providerTrackingCoordinates">Pendiente</strong>
                </article>

                <article class="service-tracking-box">
                    <span>Última sincronización</span>
                    <strong id="providerTrackingLastSync">Pendiente</strong>
                </article>

                <article class="service-tracking-box">
                    <span>Precisión reportada</span>
                    <strong id="providerTrackingAccuracy">Pendiente</strong>
                </article>
            </div>

            <div class="service-tracking-actions">
                <button type="button" id="providerTrackingRetryButton" class="btn-outline">
                    Reintentar ubicación
                </button>

                <button type="button" id="providerTrackingToggleButton" class="btn-ghost">
                    Pausar ubicación en vivo
                </button>
            </div>

            <div id="providerTrackingLog" class="service-tracking-log">
                El sistema intentará compartir tu ubicación automáticamente mientras este servicio siga activo.
            </div>
        @endif
    </section>

    <section class="card service-timeline-card desktop-only">
        <div class="section-head">
            <div>
                <p class="eyebrow">Historial del servicio</p>
                <h3>Movimientos registrados</h3>
            </div>
        </div>

        <div class="service-timeline-grid">
            <div>
                <h4 style="margin-top:0;">Cambios de estado</h4>

                @if(empty($requestRaw['history']))
                    <div class="empty">
                        <h4>Sin historial todavía</h4>
                        <p>Aún no hay movimientos registrados para esta solicitud.</p>
                    </div>
                @else
                    <div class="service-timeline-list">
                        @foreach($requestRaw['history'] as $history)
                            <div class="service-timeline-entry">
                                <strong>{{ $history['status'] ?? 'Sin estado' }}</strong>
                                <p>{{ $history['event_type'] ?? ($history['notes'] ?? 'Sin detalle adicional') }}</p>
                                <small>{{ $formatDate($history['created_at'] ?? null) }}</small>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div>
                <h4 style="margin-top:0;">Actividad registrada</h4>

                @if(empty($requestRaw['events']))
                    <div class="empty">
                        <h4>Sin actividad registrada</h4>
                        <p>Aún no hay eventos visibles para este servicio.</p>
                    </div>
                @else
                    <div class="service-timeline-list">
                        @foreach($requestRaw['events'] as $event)
                            <div class="service-timeline-entry">
                                <strong>{{ $event['event_type'] ?? 'Evento' }}</strong>
                                <p>{{ $event['status'] ?? 'Sin detalle adicional' }}</p>
                                <small>{{ $formatDate($event['created_at'] ?? null) }}</small>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="service-mobile-stack mobile-only" style="display:grid; gap:16px;">
        <details class="service-mobile-section">
            <summary>
                <div class="service-mobile-title">
                    <strong>Acciones disponibles</strong>
                    <span>Actualiza el servicio según su estado.</span>
                </div>
                <span class="service-mobile-chevron">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M7 10l5 5 5-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
            </summary>

            <div class="service-mobile-section__body">
                <div class="service-note-box">
                    <h4>{{ $nextStepTitle }}</h4>
                    <p>{{ $nextStepCopy }}</p>
                </div>

                @if(!$portalReady)
                    <div class="empty">
                        <h4>Tu cuenta todavía no está lista</h4>
                        <p>Completa lo pendiente antes de operar con normalidad.</p>
                    </div>
                @elseif($currentStatus === 'created')
                    <form method="POST" action="{{ route('provider.asistencias.accept', $requestItem['id']) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn full">Aceptar servicio</button>
                    </form>
                @elseif(empty($allowedStatusOptions))
                    <div class="empty">
                        <h4>No hay cambios manuales disponibles</h4>
                        <p>Este servicio no requiere una actualización manual adicional desde esta pantalla.</p>
                    </div>
                @else
                    <form method="POST" action="{{ route('provider.asistencias.status', $requestItem['id']) }}" class="service-status-form">
                        @csrf
                        @method('PATCH')

                        <select name="status" required>
                            <option value="">Selecciona una actualización</option>
                            @foreach($allowedStatusOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>

                        <button type="submit" class="btn full">Guardar estado</button>
                    </form>
                @endif
            </div>
        </details>

        <details class="service-mobile-section">
            <summary>
                <div class="service-mobile-title">
                    <strong>Historial</strong>
                    <span>Cambios y actividad registrados.</span>
                </div>
                <span class="service-mobile-chevron">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M7 10l5 5 5-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
            </summary>

            <div class="service-mobile-section__body">
                @if(empty($requestRaw['history']) && empty($requestRaw['events']))
                    <div class="empty">
                        <h4>Sin registros todavía</h4>
                        <p>Cuando existan movimientos visibles aparecerán aquí.</p>
                    </div>
                @else
                    @if(!empty($requestRaw['history']))
                        <div class="service-timeline-list">
                            @foreach($requestRaw['history'] as $history)
                                <div class="service-timeline-entry">
                                    <strong>{{ $history['status'] ?? 'Sin estado' }}</strong>
                                    <p>{{ $history['event_type'] ?? ($history['notes'] ?? 'Sin detalle adicional') }}</p>
                                    <small>{{ $formatDate($history['created_at'] ?? null) }}</small>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if(!empty($requestRaw['events']))
                        <div class="service-timeline-list" style="margin-top:12px;">
                            @foreach($requestRaw['events'] as $event)
                                <div class="service-timeline-entry">
                                    <strong>{{ $event['event_type'] ?? 'Evento' }}</strong>
                                    <p>{{ $event['status'] ?? 'Sin detalle adicional' }}</p>
                                    <small>{{ $formatDate($event['created_at'] ?? null) }}</small>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endif
            </div>
        </details>
    </section>
@endsection

@push('page_scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
(function () {
    const app = window.ZYGA_PROVIDER_APP || {};
    const trackingConfig = {
        requestId: @json($requestItem['id'] ?? null),
        status: @json($requestItem['status'] ?? null),
        portalReady: @json((bool) $portalReady),
        trackingAllowed: @json($trackingAllowed),
        targetLat: @json(($requestItem['lat'] ?? null) !== null ? (float) $requestItem['lat'] : null),
        targetLng: @json(($requestItem['lng'] ?? null) !== null ? (float) $requestItem['lng'] : null),
        targetAddress: @json($pickupAddress),
        targetReference: @json($pickupReference),
        publicId: @json($publicId),
    };

    const ALLOWED_STATUSES = ['assigned', 'in_progress'];
    const SEND_INTERVAL_MS = 8000;

    let watchId = null;
    let trackingEnabled = true;
    let sending = false;
    let lastSentAt = 0;
    let map = null;
    let targetMarker = null;
    let providerMarker = null;
    let providerAccuracyCircle = null;
    let latestProviderPosition = null;

    function boot() {
        initMap();
        updateNavigationLinks();

        if (!trackingConfig.portalReady || !trackingConfig.trackingAllowed) {
            return;
        }

        if (!navigator.geolocation) {
            setTrackingStatus('Este dispositivo no permite obtener ubicación.', 'danger');
            setStateText('Ubicación no disponible');
            appendLog('No fue posible iniciar la ubicación en vivo porque el navegador no soporta geolocalización.');
            return;
        }

        bindButtons();
        startTracking();

        document.addEventListener('visibilitychange', handleVisibilityChange);
        window.addEventListener('beforeunload', stopTracking, { once: true });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot, { once: true });
    } else {
        boot();
    }

    function initMap() {
        if (!window.L) {
            return;
        }

        if (!isFiniteNumber(trackingConfig.targetLat) || !isFiniteNumber(trackingConfig.targetLng)) {
            return;
        }

        const mapElement = document.getElementById('providerServiceMap');

        if (!mapElement) {
            return;
        }

        map = L.map(mapElement).setView([trackingConfig.targetLat, trackingConfig.targetLng], 15);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        targetMarker = L.marker([trackingConfig.targetLat, trackingConfig.targetLng], {
            icon: createMarkerIcon('target')
        }).addTo(map);

        targetMarker.bindPopup(
            '<strong>Ubicación del cliente</strong><br>' +
            escapeHtml(trackingConfig.targetAddress || 'Sin dirección') +
            '<br><small>' + escapeHtml(trackingConfig.targetReference || 'Sin referencia adicional') + '</small>'
        );

        setTimeout(function () {
            if (map) {
                map.invalidateSize();
            }
        }, 250);
    }

    function bindButtons() {
        const retryButton = document.getElementById('providerTrackingRetryButton');
        const toggleButton = document.getElementById('providerTrackingToggleButton');

        if (retryButton && retryButton.dataset.bound !== '1') {
            retryButton.dataset.bound = '1';
            retryButton.addEventListener('click', function () {
                appendLog('Reintentando obtener la ubicación...');
                restartTracking();
            });
        }

        if (toggleButton && toggleButton.dataset.bound !== '1') {
            toggleButton.dataset.bound = '1';
            toggleButton.addEventListener('click', function () {
                trackingEnabled = !trackingEnabled;

                if (trackingEnabled) {
                    toggleButton.textContent = 'Pausar ubicación en vivo';
                    appendLog('La ubicación en vivo fue reactivada manualmente.');
                    startTracking();
                } else {
                    toggleButton.textContent = 'Reanudar ubicación en vivo';
                    appendLog('La ubicación en vivo fue pausada manualmente.');
                    stopTracking();
                    setTrackingStatus('La ubicación en vivo fue pausada manualmente.', 'warning');
                    setStateText('Pausada');
                }
            });
        }
    }

    function handleVisibilityChange() {
        if (!trackingConfig.trackingAllowed || !trackingEnabled) {
            return;
        }

        if (document.visibilityState === 'visible') {
            appendLog('La vista volvió a estar visible. Reanudando ubicación en vivo.');
            startTracking();
        } else {
            appendLog('La vista dejó de estar visible. La ubicación en vivo se pausó para ahorrar recursos.');
            stopTracking();
            setTrackingStatus('La ubicación en vivo está en pausa porque esta vista no está visible.', 'warning');
            setStateText('En pausa');
        }
    }

    function restartTracking() {
        stopTracking();
        startTracking();
    }

    function startTracking() {
        if (!trackingEnabled) {
            return;
        }

        if (!trackingConfig.requestId || !ALLOWED_STATUSES.includes(String(trackingConfig.status || '').toLowerCase())) {
            setTrackingStatus('Este servicio no permite ubicación en vivo en su estado actual.', 'warning');
            setStateText('No disponible');
            return;
        }

        if (watchId !== null) {
            return;
        }

        setTrackingStatus('Solicitando permiso de ubicación y esperando coordenadas del dispositivo...', 'info');
        setStateText('Esperando ubicación');

        watchId = navigator.geolocation.watchPosition(
            handlePosition,
            handlePositionError,
            {
                enableHighAccuracy: true,
                timeout: 15000,
                maximumAge: 0,
            }
        );
    }

    function stopTracking() {
        if (watchId !== null) {
            navigator.geolocation.clearWatch(watchId);
            watchId = null;
        }
    }

    function handlePosition(position) {
        if (!trackingEnabled) {
            return;
        }

        const coords = position.coords || {};
        const lat = Number(coords.latitude);
        const lng = Number(coords.longitude);
        const accuracy = isFiniteNumber(coords.accuracy) ? Number(coords.accuracy) : null;

        latestProviderPosition = {
            lat: lat,
            lng: lng,
            accuracy: accuracy,
            recordedAt: new Date(position.timestamp || Date.now()).toISOString(),
        };

        updateProviderVisuals(lat, lng, accuracy);
        updateNavigationLinks();
        setAccuracyText(accuracy);
        setCoordinatesText(lat, lng);
        setCurrentCoordinatesPanel(lat, lng);

        const now = Date.now();

        if (now - lastSentAt < SEND_INTERVAL_MS) {
            setStateText('Ubicación activa');
            appendLog('Se recibió una nueva ubicación. Esperando la siguiente ventana de envío.');
            return;
        }

        sendLocation(position);
    }

    async function sendLocation(position) {
        if (sending) {
            return;
        }

        const apiBaseUrl = String(app.apiBaseUrl || '').trim();
        const token = String(app.token || '').trim();

        if (!apiBaseUrl) {
            setTrackingStatus('No existe una conexión configurada con la API.', 'danger');
            setStateText('Error de configuración');
            appendLog('No se pudo continuar porque falta la URL base de la API.');
            return;
        }

        if (!token) {
            setTrackingStatus('No existe una sesión válida para enviar ubicación.', 'danger');
            setStateText('Sin sesión');
            appendLog('No se pudo continuar porque falta el token de autenticación.');
            return;
        }

        sending = true;
        setTrackingStatus('Enviando tu ubicación actual...', 'info');
        setStateText('Sincronizando');

        const payload = {
            assistance_request_id: trackingConfig.requestId,
            lat: Number(position.coords.latitude),
            lng: Number(position.coords.longitude),
            accuracy: isFiniteNumber(position.coords.accuracy) ? Number(position.coords.accuracy) : null,
            heading: isFiniteNumber(position.coords.heading) ? Number(position.coords.heading) : null,
            speed: isFiniteNumber(position.coords.speed) ? Number(position.coords.speed) : null,
            recorded_at: new Date(position.timestamp || Date.now()).toISOString(),
        };

        try {
            const response = await fetch(apiBaseUrl + '/api/v1/provider/tracking', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + token,
                },
                body: JSON.stringify(payload),
            });

            const result = await response.json().catch(function () {
                return {};
            });

            if (!response.ok) {
                throw new Error(result.message || 'No fue posible enviar la ubicación.');
            }

            lastSentAt = Date.now();

            setTrackingStatus('Tu ubicación se envió correctamente.', 'success');
            setStateText('Activa');
            setCoordinatesText(payload.lat, payload.lng);
            setAccuracyText(payload.accuracy);
            setLastSyncText(payload.recorded_at);
            setCurrentCoordinatesPanel(payload.lat, payload.lng);
            appendLog('La ubicación se sincronizó correctamente para este servicio.');
        } catch (error) {
            setTrackingStatus(error.message || 'No fue posible enviar la ubicación.', 'danger');
            setStateText('Error al sincronizar');
            appendLog(error.message || 'Falló el envío de coordenadas.');
        } finally {
            sending = false;
        }
    }

    function handlePositionError(error) {
        let message = 'No fue posible obtener la ubicación del dispositivo.';

        if (error && typeof error.code !== 'undefined') {
            switch (error.code) {
                case 1:
                    message = 'Se negó el permiso para obtener la ubicación.';
                    break;
                case 2:
                    message = 'La ubicación no está disponible en este momento.';
                    break;
                case 3:
                    message = 'Se agotó el tiempo al intentar obtener la ubicación.';
                    break;
            }
        }

        setTrackingStatus(message, 'danger');
        setStateText('Ubicación con error');
        appendLog(message);
    }

    function updateProviderVisuals(lat, lng, accuracy) {
        if (!map || !isFiniteNumber(lat) || !isFiniteNumber(lng)) {
            return;
        }

        if (!providerMarker) {
            providerMarker = L.marker([lat, lng], {
                icon: createMarkerIcon('provider')
            }).addTo(map);
        } else {
            providerMarker.setLatLng([lat, lng]);
        }

        providerMarker.bindPopup(
            '<strong>Tu ubicación actual</strong><br>' +
            escapeHtml(formatCoord(lat) + ', ' + formatCoord(lng))
        );

        if (isFiniteNumber(accuracy)) {
            if (!providerAccuracyCircle) {
                providerAccuracyCircle = L.circle([lat, lng], {
                    radius: accuracy,
                    weight: 1,
                    opacity: 0.55,
                    fillOpacity: 0.08,
                }).addTo(map);
            } else {
                providerAccuracyCircle.setLatLng([lat, lng]);
                providerAccuracyCircle.setRadius(accuracy);
            }
        }

        fitMapToMarkers();
        clearMapOverlay();
    }

    function fitMapToMarkers() {
        if (!map || !targetMarker || !providerMarker) {
            return;
        }

        const bounds = L.latLngBounds([
            targetMarker.getLatLng(),
            providerMarker.getLatLng()
        ]);

        map.fitBounds(bounds, {
            padding: [40, 40],
            maxZoom: 16
        });
    }

    function updateNavigationLinks() {
        const googleLink = document.getElementById('providerGoogleMapsLink');
        const wazeLink = document.getElementById('providerWazeLink');

        if (!googleLink || !wazeLink) {
            return;
        }

        if (!isFiniteNumber(trackingConfig.targetLat) || !isFiniteNumber(trackingConfig.targetLng)) {
            googleLink.href = '#';
            wazeLink.href = '#';
            googleLink.setAttribute('aria-disabled', 'true');
            wazeLink.setAttribute('aria-disabled', 'true');
            return;
        }

        const destination = trackingConfig.targetLat + ',' + trackingConfig.targetLng;

        let googleUrl = 'https://www.google.com/maps/dir/?api=1&destination=' + encodeURIComponent(destination) + '&travelmode=driving';

        if (latestProviderPosition && isFiniteNumber(latestProviderPosition.lat) && isFiniteNumber(latestProviderPosition.lng)) {
            const origin = latestProviderPosition.lat + ',' + latestProviderPosition.lng;
            googleUrl += '&origin=' + encodeURIComponent(origin);
        }

        const wazeUrl = 'https://waze.com/ul?ll=' +
            encodeURIComponent(destination) +
            '&navigate=yes';

        googleLink.href = googleUrl;
        wazeLink.href = wazeUrl;
        googleLink.removeAttribute('aria-disabled');
        wazeLink.removeAttribute('aria-disabled');
    }

    function createMarkerIcon(type) {
        return L.divIcon({
            className: 'service-map-marker-wrapper',
            html: '<span class="service-map-marker service-map-marker--' + type + '"></span>',
            iconSize: [20, 20],
            iconAnchor: [10, 10],
            popupAnchor: [0, -10]
        });
    }

    function clearMapOverlay() {
        const overlay = document.getElementById('providerServiceMapOverlay');

        if (!overlay) {
            return;
        }

        overlay.hidden = true;
        overlay.textContent = '';
    }

    function setTrackingStatus(message, tone) {
        const target = document.getElementById('providerTrackingStatus');

        if (!target) {
            return;
        }

        target.className = 'service-tracking-status';

        if (tone === 'success') {
            target.classList.add('service-tracking-status--success');
        } else if (tone === 'warning') {
            target.classList.add('service-tracking-status--warning');
        } else if (tone === 'danger') {
            target.classList.add('service-tracking-status--danger');
        }

        target.textContent = message;
    }

    function setStateText(value) {
        const target = document.getElementById('providerTrackingState');
        if (target) target.textContent = value || 'Pendiente';
    }

    function setCoordinatesText(lat, lng) {
        const target = document.getElementById('providerTrackingCoordinates');
        if (target) target.textContent = [formatCoord(lat), formatCoord(lng)].join(', ');
    }

    function setCurrentCoordinatesPanel(lat, lng) {
        const target = document.getElementById('providerCurrentCoordinates');
        if (target) target.textContent = [formatCoord(lat), formatCoord(lng)].join(', ');
    }

    function setLastSyncText(value) {
        const target = document.getElementById('providerTrackingLastSync');
        if (!target) {
            return;
        }

        if (!value) {
            target.textContent = 'Pendiente';
            return;
        }

        const date = new Date(value);

        if (Number.isNaN(date.getTime())) {
            target.textContent = 'Fecha inválida';
            return;
        }

        target.textContent = new Intl.DateTimeFormat('es-MX', {
            dateStyle: 'medium',
            timeStyle: 'short',
        }).format(date);
    }

    function setAccuracyText(value) {
        const target = document.getElementById('providerTrackingAccuracy');
        if (!target) {
            return;
        }

        if (!isFiniteNumber(value)) {
            target.textContent = 'No reportada';
            return;
        }

        target.textContent = Number(value).toFixed(2) + ' m';
    }

    function appendLog(message) {
        const target = document.getElementById('providerTrackingLog');

        if (!target) {
            return;
        }

        const prefix = new Intl.DateTimeFormat('es-MX', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
        }).format(new Date());

        target.textContent = '[' + prefix + '] ' + message;
    }

    function isFiniteNumber(value) {
        return typeof value === 'number' && Number.isFinite(value);
    }

    function formatCoord(value) {
        const number = Number(value);
        return Number.isFinite(number) ? number.toFixed(6) : 'N/D';
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
</script>
@endpush
