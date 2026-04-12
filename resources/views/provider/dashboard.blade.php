@extends('provider.layouts.app')

@section('title', 'ZYGA | Inicio del proveedor')
@section('page-title', 'Panel operativo')

@section('content')
    @php($r = $context['readiness'])
    <section class="hero hero-split">
        <div>
            <p class="eyebrow">Resumen del día</p>
            <h2 style="margin:0 0 12px; font-size:2rem;">Tu operación en ZYGA</h2>
            <p class="muted" style="margin:0; line-height:1.6;">Desde aquí puedes revisar si tu cuenta ya está lista para recibir servicios, ver oportunidades disponibles y dar seguimiento a los casos en curso.</p>
            <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top:16px;">
                <span class="chip {{ $r['portal_ready'] ? 'success' : 'warning' }}">Portal {{ $r['portal_ready'] ? 'listo' : 'pendiente' }}</span>
                <span class="chip {{ $r['backend_can_operate'] ? 'success' : 'warning' }}">Operación {{ $r['backend_can_operate'] ? 'habilitada' : 'restringida' }}</span>
                <span class="chip {{ $r['status_tone'] ?? 'info' }}">{{ $r['status_name'] }}</span>
            </div>
        </div>
        <div class="hero-panel">
            <div class="summary"><span class="helper" style="color:rgba(255,255,255,.8)">Solicitudes disponibles</span><strong>{{ count($availableRequests) }}</strong></div>
            <div class="summary"><span class="helper" style="color:rgba(255,255,255,.8)">Servicios activos</span><strong>{{ count($activeRequests) }}</strong></div>
            <div class="summary"><span class="helper" style="color:rgba(255,255,255,.8)">Servicios cerrados</span><strong>{{ count($historicalRequests) }}</strong></div>
        </div>
    </section>

    @if(!$context['hasProfile'])
        <section class="lockbox">
            <h3>Primero completa tu alta como proveedor</h3>
            <p class="muted">Aún no tienes un perfil operativo. Crea tu perfil para comenzar a configurar servicios, horarios y documentos.</p>
            <a href="{{ route('provider.perfil') }}" class="btn">Completar perfil</a>
        </section>
    @elseif(!$r['portal_ready'])
        <section class="lockbox">
            <h3>Tu cuenta todavía no está lista para recibir servicios</h3>
            <div class="checklist">
                @foreach($r['blockers'] as $blocker)
                    <div class="check"><span>{{ $blocker }}</span><span class="chip warning">Pendiente</span></div>
                @endforeach
            </div>
        </section>
    @endif

    <section class="kpi-grid">
        <article class="kpi"><div class="kpi-label">Servicios activos</div><div class="kpi-value">{{ count($activeRequests) }}</div><div class="kpi-hint">Casos asignados o en proceso.</div></article>
        <article class="kpi"><div class="kpi-label">Disponibles hoy</div><div class="kpi-value">{{ count($availableRequests) }}</div><div class="kpi-hint">Oportunidades compatibles con tu cuenta.</div></article>
        <article class="kpi"><div class="kpi-label">Servicios configurados</div><div class="kpi-value">{{ $r['services_count'] }}</div><div class="kpi-hint">Debes tener al menos uno activo.</div></article>
        <article class="kpi"><div class="kpi-label">Horarios activos</div><div class="kpi-value">{{ $r['active_schedules_count'] }}</div><div class="kpi-hint">Tu disponibilidad declarada en el portal.</div></article>
    </section>

    <section class="two-col">
        <section class="card">
            <div class="section-head"><div><p class="eyebrow">Configuración</p><h3>Estado de tu cuenta</h3></div></div>
            <div class="meta-grid">
                <div class="meta-box"><span>Perfil</span><strong>{{ $context['hasProfile'] ? 'Registrado' : 'Pendiente' }}</strong></div>
                <div class="meta-box"><span>Validación</span><strong>{{ $r['verification_text'] }}</strong></div>
                <div class="meta-box"><span>Servicios</span><strong>{{ $r['services_count'] }}</strong></div>
                <div class="meta-box"><span>Documentos</span><strong>{{ $r['documents_count'] }}</strong></div>
            </div>
            <div class="inline-form">
                <a href="{{ route('provider.perfil') }}" class="btn-outline">Perfil</a>
                <a href="{{ route('provider.servicios') }}" class="btn-outline">Servicios</a>
                <a href="{{ route('provider.horarios') }}" class="btn-outline">Horarios</a>
                <a href="{{ route('provider.documentos') }}" class="btn-outline">Documentos</a>
            </div>
        </section>

        <section class="card">
            <div class="section-head"><div><p class="eyebrow">Bandeja rápida</p><h3>Solicitudes disponibles</h3></div></div>
            @if(!$r['backend_can_operate'])
                <div class="empty"><h4>Aún no puedes aceptar servicios</h4><p>Completa tu configuración y espera validación administrativa para ver solicitudes compatibles.</p></div>
            @elseif(empty($availableRequests))
                <div class="empty"><h4>No hay solicitudes disponibles por ahora</h4><p>Cuando exista una asistencia compatible con tus servicios, aparecerá aquí.</p></div>
            @else
                <div class="list">
                    @foreach($availableRequests as $request)
                        <article class="item">
                            <div class="item-head">
                                <div><h4>{{ $request['service_name'] }}</h4><p>{{ $request['pickup_address'] }}</p><small>{{ $request['public_id'] ?: 'Sin folio visible' }}</small></div>
                                <span class="chip {{ $request['status_tone'] ?? 'info' }}">{{ $request['status_label'] ?? 'Nueva' }}</span>
                            </div>
                            <div style="margin-top:12px;"><a href="{{ route('provider.asistencias.show', $request['id']) }}" class="btn">Ver detalle</a></div>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>
    </section>

    <section class="card">
        <div class="section-head"><div><p class="eyebrow">Seguimiento</p><h3>Servicios activos</h3></div></div>
        @if(empty($activeRequests))
            <div class="empty"><h4>No tienes servicios activos</h4><p>Cuando aceptes una asistencia y esta pase a asignada o en proceso, aparecerá aquí.</p></div>
        @else
            <div class="list">
                @foreach($activeRequests as $request)
                    <article class="item">
                        <div class="item-head">
                            <div><h4>{{ $request['service_name'] }}</h4><p>{{ $request['pickup_address'] }}</p></div>
                            <span class="chip {{ $request['status_tone'] ?? 'info' }}">{{ $request['status_label'] }}</span>
                        </div>
                        <div style="margin-top:12px;"><a href="{{ route('provider.asistencias.show', $request['id']) }}" class="btn">Abrir servicio</a></div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>
@endsection
