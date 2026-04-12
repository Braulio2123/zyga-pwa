@extends('provider.layouts.app')

@section('title', 'ZYGA | Operación del proveedor')
@section('page-title', 'Servicios y solicitudes')

@section('content')
    @php($r = $context['readiness'])
    <section class="hero hero-split">
        <div>
            <p class="eyebrow">Operación diaria</p>
            <h2 style="margin:0 0 12px; font-size:2rem;">Bandeja de solicitudes y seguimiento</h2>
            <p class="muted" style="margin:0; line-height:1.6;">Consulta las solicitudes compatibles con tu cuenta, acepta nuevos servicios y controla los casos que ya están bajo tu atención.</p>
            <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top:16px;">
                <span class="chip {{ $r['portal_ready'] ? 'success' : 'warning' }}">Portal {{ $r['portal_ready'] ? 'listo' : 'pendiente' }}</span>
                <span class="chip {{ $r['backend_can_operate'] ? 'success' : 'warning' }}">Operación {{ $r['backend_can_operate'] ? 'habilitada' : 'restringida' }}</span>
            </div>
        </div>
        <div class="hero-panel">
            <div class="summary"><span class="helper" style="color:rgba(255,255,255,.8)">Disponibles</span><strong>{{ count($availableRequests) }}</strong></div>
            <div class="summary"><span class="helper" style="color:rgba(255,255,255,.8)">Activas</span><strong>{{ count($activeRequests) }}</strong></div>
            <div class="summary"><span class="helper" style="color:rgba(255,255,255,.8)">Historial</span><strong>{{ count($historicalRequests) }}</strong></div>
        </div>
    </section>

    @if(!$context['hasProfile'])
        <section class="lockbox"><h3>Módulo disponible cuando completes tu perfil</h3><p class="muted">Primero registra tu cuenta como proveedor para poder acceder a la operación.</p><a href="{{ route('provider.perfil') }}" class="btn">Ir a perfil</a></section>
    @elseif(!$r['portal_ready'])
        <section class="lockbox"><h3>Tu cuenta aún no puede operar</h3><div class="checklist">@foreach($r['blockers'] as $blocker)<div class="check"><span>{{ $blocker }}</span><span class="chip warning">Pendiente</span></div>@endforeach</div></section>
    @endif

    <section class="two-col">
        <section class="card">
            <div class="section-head"><div><p class="eyebrow">Nuevas oportunidades</p><h3>Solicitudes disponibles</h3></div></div>
            @if(!$r['backend_can_operate'])
                <div class="empty"><h4>Tu bandeja todavía no está habilitada</h4><p>Completa tu configuración y espera validación administrativa para aceptar servicios.</p></div>
            @elseif(empty($availableRequests))
                <div class="empty"><h4>No hay solicitudes compatibles por ahora</h4><p>Cuando exista una asistencia acorde con tus servicios, aparecerá en esta bandeja.</p></div>
            @else
                <div class="list">@foreach($availableRequests as $request)<article class="item"><div class="item-head"><div><h4>{{ $request['service_name'] }}</h4><p>{{ $request['pickup_address'] }}</p><small>{{ $request['public_id'] ?: 'Sin folio visible' }}</small></div><span class="chip {{ $request['status_tone'] ?? 'info' }}">{{ $request['status_label'] }}</span></div><div class="inline-form"><a href="{{ route('provider.asistencias.show', $request['id']) }}" class="btn-outline">Ver detalle</a>@if($r['portal_ready'])<form action="{{ route('provider.asistencias.accept', $request['id']) }}" method="POST">@csrf @method('PATCH')<button class="btn" type="submit">Aceptar servicio</button></form>@endif</div></article>@endforeach</div>
            @endif
        </section>
        <section class="card">
            <div class="section-head"><div><p class="eyebrow">Servicios en curso</p><h3>Solicitudes activas</h3></div></div>
            @if(empty($activeRequests))
                <div class="empty"><h4>No tienes servicios activos</h4><p>Cuando aceptes una solicitud y esta quede asignada o en proceso, aparecerá aquí.</p></div>
            @else
                <div class="list">@foreach($activeRequests as $request)@php($statusOptions = $allowedStatusOptionsResolver($request['status']))<article class="item"><div class="item-head"><div><h4>{{ $request['service_name'] }}</h4><p>{{ $request['pickup_address'] }}</p><small>{{ $request['public_id'] ?: 'Sin folio visible' }}</small></div><span class="chip {{ $request['status_tone'] ?? 'info' }}">{{ $request['status_label'] }}</span></div><div class="inline-form"><a href="{{ route('provider.asistencias.show', $request['id']) }}" class="btn-outline">Ver servicio</a>@if(!empty($statusOptions) && $r['portal_ready'])<form action="{{ route('provider.asistencias.status', $request['id']) }}" method="POST" class="inline-form">@csrf @method('PATCH')<select name="status" required><option value="">Acción disponible</option>@foreach($statusOptions as $value => $label)<option value="{{ $value }}">{{ $label }}</option>@endforeach</select><button type="submit" class="btn">Actualizar</button></form>@endif</div></article>@endforeach</div>
            @endif
        </section>
    </section>

    <section class="card"><div class="section-head"><div><p class="eyebrow">Historial</p><h3>Servicios cerrados</h3></div></div>@if(empty($historicalRequests))<div class="empty"><h4>Aún no tienes historial</h4><p>Los servicios completados o cancelados aparecerán aquí.</p></div>@else<div class="list">@foreach($historicalRequests as $request)<article class="item"><div class="item-head"><div><h4>{{ $request['service_name'] }}</h4><p>{{ $request['pickup_address'] }}</p><small>{{ $request['public_id'] ?: 'Sin folio visible' }}</small></div><span class="chip {{ $request['status_tone'] ?? 'info' }}">{{ $request['status_label'] }}</span></div><div style="margin-top:12px;"><a href="{{ route('provider.asistencias.show', $request['id']) }}" class="btn-outline">Ver detalle</a></div></article>@endforeach</div>@endif</section>
@endsection
