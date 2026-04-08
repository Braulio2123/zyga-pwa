@extends('provider.layouts.app')

@section('title', 'ZYGA | Dashboard provider')
@section('page-title', 'Dashboard provider')

@section('content')
    @php($r = $context['readiness'])
    <section class="hero hero-split">
        <div>
            <p class="eyebrow">Operación real</p>
            <h2 style="margin:0 0 12px; font-size:2rem;">Consola operativa del provider</h2>
            <p class="muted" style="margin:0 0 16px; line-height:1.6;">
                Gestiona tu perfil, disponibilidad y atención de servicios desde un solo panel.
            </p>
            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <span class="chip {{ $r['portal_ready'] ? 'success' : 'warning' }}">{{ $r['portal_ready'] ? 'Listo para operar' : 'Onboarding incompleto' }}</span>
                <span class="chip {{ $r['backend_can_operate'] ? 'success' : 'info' }}">Operación: {{ $r['backend_can_operate'] ? 'habilitada' : 'pendiente' }}</span>
                <span class="chip {{ $r['checks']['is_verified'] ? 'success' : 'warning' }}">{{ $r['verification_text'] }}</span>
                <span class="chip {{ $r['checks']['status_active'] ? 'success' : 'warning' }}">Estado: {{ $r['status_name'] }}</span>
            </div>
            <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top:16px;">
                <a href="{{ route('provider.perfil') }}" class="btn">Ir a perfil</a>
                <a href="{{ route('provider.asistencias') }}" class="btn-outline">Ir a asistencias</a>
            </div>
        </div>

        <div class="hero-panel">
            <div class="summary">
                <span class="helper">Servicios activos</span>
                <strong>{{ $r['services_count'] }}</strong>
            </div>
            <div class="summary">
                <span class="helper">Horarios activos</span>
                <strong>{{ $r['active_schedules_count'] }}</strong>
            </div>
            <div class="summary">
                <span class="helper">Solicitudes disponibles</span>
                <strong>{{ count($availableRequests) }}</strong>
            </div>
            <div class="summary">
                <span class="helper">Solicitudes activas</span>
                <strong>{{ count($activeRequests) }}</strong>
            </div>
        </div>
    </section>

    <section class="kpi-grid">
        <article class="kpi">
            <div class="kpi-label">Perfil provider</div>
            <div class="kpi-value">{{ $context['hasProfile'] ? 'OK' : 'NO' }}</div>
            <div class="kpi-hint">Debe existir antes de cualquier operación.</div>
        </article>
        <article class="kpi">
            <div class="kpi-label">Servicios</div>
            <div class="kpi-value">{{ $r['services_count'] }}</div>
            <div class="kpi-hint">Matching real con solicitudes disponibles.</div>
        </article>
        <article class="kpi">
            <div class="kpi-label">Horarios activos</div>
            <div class="kpi-value">{{ $r['active_schedules_count'] }}</div>
            <div class="kpi-hint">Criterio UX para marcar listo al provider.</div>
        </article>
        <article class="kpi">
            <div class="kpi-label">Historial cerrado</div>
            <div class="kpi-value">{{ count($historicalRequests) }}</div>
            <div class="kpi-hint">Solicitudes finalizadas o canceladas.</div>
        </article>
    </section>

    @if(!$r['portal_ready'])
        <section class="lockbox">
            <div class="section-head">
                <div>
                    <p class="eyebrow">Bloqueo operativo</p>
                    <h3>Tu provider todavía no debe tomar solicitudes</h3>
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
            <p class="helper" style="margin-top:14px;">{{ $r['documents_note'] }}</p>
        </section>
    @endif

    <section class="two-col">
        <section class="card">
            <div class="section-head">
                <div>
                    <p class="eyebrow">Pipeline operativo</p>
                    <h3>Siguiente paso recomendado</h3>
                </div>
            </div>
            <div class="stack">
                @if(!$context['hasProfile'])
                    <div class="item">
                        <h4>1. Crear perfil de proveedor</h4>
                        <p>Completa tu perfil para habilitar tu operación en el portal.</p>
                        <div style="margin-top:12px;"><a class="btn" href="{{ route('provider.perfil') }}">Completar perfil</a></div>
                    </div>
                @elseif($r['services_count'] === 0)
                    <div class="item">
                        <h4>2. Asociar servicios</h4>
                        <p>El matching de solicitudes disponibles depende del catálogo ligado al provider.</p>
                        <div style="margin-top:12px;"><a class="btn" href="{{ route('provider.servicios') }}">Configurar servicios</a></div>
                    </div>
                @elseif($r['active_schedules_count'] === 0)
                    <div class="item">
                        <h4>3. Registrar horarios activos</h4>
                        <p>El portal no te considera listo si todavía no declaras disponibilidad operativa.</p>
                        <div style="margin-top:12px;"><a class="btn" href="{{ route('provider.horarios') }}">Configurar horarios</a></div>
                    </div>
                @elseif(!$r['checks']['is_verified'] || !$r['checks']['status_active'])
                    <div class="item">
                        <h4>4. Esperar habilitación administrativa</h4>
                        <p>Tu cuenta debe cumplir validación y estado operativo para habilitar la atención de servicios.</p>
                    </div>
                @else
                    <div class="item">
                        <h4>Provider listo para operar</h4>
                        <p>Ya puedes revisar solicitudes disponibles, aceptar una y avanzar por el flujo de estado permitido.</p>
                        <div style="margin-top:12px;"><a class="btn" href="{{ route('provider.asistencias') }}">Ir a asistencias</a></div>
                    </div>
                @endif
            </div>
        </section>

        <section class="card">
            <div class="section-head">
                <div>
                    <p class="eyebrow">Resumen vivo</p>
                    <h3>Estado de operación</h3>
                </div>
            </div>
            <div class="meta-grid">
                <div class="meta-box"><span>Perfil</span><strong>{{ $context['hasProfile'] ? 'Creado' : 'Sin crear' }}</strong></div>
                <div class="meta-box"><span>Verificación</span><strong>{{ $r['verification_text'] }}</strong></div>
                <div class="meta-box"><span>Estado actual</span><strong>{{ $r['status_name'] }}</strong></div>
                <div class="meta-box"><span>Documentos</span><strong>{{ $r['documents_count'] }}</strong></div>
            </div>
        </section>
    </section>

    <section class="two-col">
        <section class="card">
            <div class="section-head">
                <div>
                    <p class="eyebrow">Mercado</p>
                    <h3>Solicitudes disponibles</h3>
                </div>
                <a href="{{ route('provider.asistencias') }}" class="btn-outline">Ver todas</a>
            </div>

            @if(!$r['backend_can_operate'])
                <div class="empty">
                    <h4>Bandeja cerrada</h4>
                    <p>Tu cuenta aún no cumple las condiciones necesarias para aceptar solicitudes.</p>
                </div>
            @elseif(empty($availableRequests))
                <div class="empty">
                    <h4>Sin solicitudes disponibles</h4>
                    <p>No hay solicitudes compatibles con tus servicios en este momento.</p>
                </div>
            @else
                <div class="list">
                    @foreach(array_slice($availableRequests, 0, 3) as $request)
                        <article class="item">
                            <div class="item-head">
                                <div>
                                    <h4>{{ $request['service_name'] }}</h4>
                                    <p>{{ $request['pickup_address'] }}</p>
                                </div>
                                <span class="chip info">{{ $request['status'] }}</span>
                            </div>
                            <p style="margin-top:10px;">{{ $request['public_id'] ?: 'Sin folio público' }}</p>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>

        <section class="card">
            <div class="section-head">
                <div>
                    <p class="eyebrow">Seguimiento</p>
                    <h3>Solicitudes activas</h3>
                </div>
            </div>

            @if(empty($activeRequests))
                <div class="empty">
                    <h4>No tienes solicitudes activas</h4>
                    <p>Cuando aceptes una solicitud y quede asignada o en proceso, aparecerá aquí.</p>
                </div>
            @else
                <div class="list">
                    @foreach($activeRequests as $request)
                        <article class="item">
                            <div class="item-head">
                                <div>
                                    <h4>{{ $request['service_name'] }}</h4>
                                    <p>{{ $request['pickup_address'] }}</p>
                                </div>
                                <span class="chip dark">{{ $request['status'] }}</span>
                            </div>
                            <div style="margin-top:12px;"><a href="{{ route('provider.asistencias.show', $request['id']) }}" class="btn">Abrir detalle</a></div>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>
    </section>
@endsection
