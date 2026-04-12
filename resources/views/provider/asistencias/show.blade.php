@extends('provider.layouts.app')

@section('title', 'ZYGA | Detalle del servicio')
@section('page-title', 'Detalle del servicio')

@section('content')
    @php($r = $context['readiness'])
    <section class="hero">
        <p class="eyebrow">Seguimiento puntual</p>
        <h2 style="margin:0 0 12px; font-size:2rem;">{{ $requestItem['service_name'] }}</h2>
        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <span class="chip {{ $requestItem['status_tone'] ?? 'info' }}">{{ $requestItem['status_label'] ?? 'Sin estado' }}</span>
            @if($requestItem['public_id'])<span class="chip info">{{ $requestItem['public_id'] }}</span>@endif
            <span class="chip {{ $r['portal_ready'] ? 'success' : 'warning' }}">Portal {{ $r['portal_ready'] ? 'habilitado' : 'pendiente' }}</span>
        </div>
        <p class="muted" style="margin:16px 0 0; line-height:1.6;">Consulta la información clave del servicio, confirma sus datos y ejecuta solo las acciones válidas para el estado actual.</p>
    </section>

    <section class="two-col">
        <section class="card">
            <div class="section-head"><div><p class="eyebrow">Datos clave</p><h3>Resumen del servicio</h3></div></div>
            <div class="meta-grid">
                <div class="meta-box"><span>Dirección</span><strong>{{ $requestItem['pickup_address'] }}</strong></div>
                <div class="meta-box"><span>Cliente</span><strong>{{ $requestItem['client_email'] ?: 'Sin correo visible' }}</strong></div>
                <div class="meta-box"><span>Vehículo</span><strong>{{ $requestItem['vehicle'] }}</strong></div>
                <div class="meta-box"><span>Ubicación</span><strong>{{ $requestItem['lat'] !== null && $requestItem['lng'] !== null ? $requestItem['lat'] . ', ' . $requestItem['lng'] : 'Sin coordenadas' }}</strong></div>
            </div>
            <div class="inline-form" style="margin-top:16px;">
                <a href="{{ route('provider.asistencias') }}" class="btn-outline">Volver</a>
                @if(($requestItem['status'] ?? null) === 'created' && $r['portal_ready'])
                    <form method="POST" action="{{ route('provider.asistencias.accept', $requestItem['id']) }}">@csrf @method('PATCH')<button type="submit" class="btn">Aceptar servicio</button></form>
                @endif
            </div>
        </section>
        <section class="card">
            <div class="section-head"><div><p class="eyebrow">Acciones</p><h3>Cambios permitidos</h3></div></div>
            @if(!$r['portal_ready'])
                <div class="empty"><h4>Tu cuenta todavía no puede operar</h4><p>Mientras tu configuración siga pendiente, no se habilitan acciones operativas desde esta pantalla.</p></div>
            @elseif(empty($allowedStatusOptions))
                <div class="empty"><h4>No hay acciones manuales disponibles</h4><p>Este servicio no admite transiciones adicionales desde el portal en su estado actual.</p></div>
            @else
                <form method="POST" action="{{ route('provider.asistencias.status', $requestItem['id']) }}" class="form-grid">@csrf @method('PATCH')<div class="field full"><label class="label" for="status">Nuevo estado</label><select id="status" name="status" required><option value="">Selecciona una acción</option>@foreach($allowedStatusOptions as $value => $label)<option value="{{ $value }}">{{ $label }}</option>@endforeach</select></div><div class="field full"><button type="submit" class="btn full">Actualizar estado</button></div></form>
            @endif
        </section>
    </section>

    <section class="card">
        <div class="section-head"><div><p class="eyebrow">Trazabilidad</p><h3>Historial registrado</h3></div></div>
        <div class="two-col">
            <div>
                <h4 style="margin-top:0;">Historial de cambios</h4>
                @if(empty($requestRaw['history']))
                    <div class="empty"><h4>Sin historial disponible</h4><p>Aún no hay movimientos registrados para este servicio.</p></div>
                @else
                    <div class="timeline">@foreach($requestRaw['history'] as $history)<div class="timeline-entry"><strong>{{ $history['status'] ?? 'Sin estado' }}</strong><p class="muted">{{ $history['event_type'] ?? ($history['notes'] ?? 'Sin detalle') }}</p></div>@endforeach</div>
                @endif
            </div>
            <div>
                <h4 style="margin-top:0;">Eventos asociados</h4>
                @if(empty($requestRaw['events']))
                    <div class="empty"><h4>Sin eventos disponibles</h4><p>Aún no se han registrado eventos adicionales para este servicio.</p></div>
                @else
                    <div class="timeline">@foreach($requestRaw['events'] as $event)<div class="timeline-entry"><strong>{{ $event['event_type'] ?? 'Evento' }}</strong><p class="muted">{{ $event['status'] ?? ($event['created_at'] ?? 'Sin fecha') }}</p></div>@endforeach</div>
                @endif
            </div>
        </div>
    </section>
@endsection
