@extends('provider.layouts.app')

@section('title', 'ZYGA | Detalle de asistencia')
@section('page-title', 'Detalle operativo de la asistencia')
@section('page-copy', 'Consulta datos del servicio, avanza estados válidos y comparte tu ubicación cuando la asistencia esté activa.')

@section('content')
    @php($r = $context['readiness'])
    @php($trackingActive = in_array($requestItem['status'] ?? null, ['assigned', 'in_progress'], true))

    <section class="hero">
        <p class="eyebrow">Solicitud operativa</p>
        <h2 style="margin:0 0 12px; font-size:2rem;">{{ $requestItem['service_name'] }}</h2>
        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <span class="chip {{ $requestItem['status_tone'] ?? 'info' }}">{{ $requestItem['status_label'] ?? 'Sin estado' }}</span>
            @if($requestItem['public_id'])
                <span class="chip info">{{ $requestItem['public_id'] }}</span>
            @endif
            <span class="chip {{ $r['portal_ready'] ? 'success' : 'warning' }}">Portal {{ $r['portal_ready'] ? 'habilitado' : 'bloqueado' }}</span>
            @if($trackingActive)
                <span class="chip success">Ubicación en vivo habilitada</span>
            @endif
        </div>
        <p class="muted" style="margin:16px 0 0; line-height:1.6;">Usa esta vista para confirmar datos del servicio y mantener tu ubicación actualizada mientras la solicitud siga asignada o en proceso.</p>
    </section>

    <section class="two-col">
        <section class="card">
            <div class="section-head">
                <div>
                    <p class="eyebrow">Datos clave</p>
                    <h3>Resumen de la solicitud</h3>
                </div>
            </div>
            <div class="meta-grid">
                <div class="meta-box"><span>Dirección</span><strong>{{ $requestItem['pickup_address'] }}</strong></div>
                <div class="meta-box"><span>Cliente</span><strong>{{ $requestItem['client_email'] ?: 'Sin correo visible' }}</strong></div>
                <div class="meta-box"><span>Vehículo</span><strong>{{ $requestItem['vehicle'] }}</strong></div>
                <div class="meta-box"><span>Ubicación</span><strong>{{ $requestItem['lat'] !== null && $requestItem['lng'] !== null ? $requestItem['lat'] . ', ' . $requestItem['lng'] : 'Sin coordenadas' }}</strong></div>
            </div>

            <div class="inline-form" style="margin-top:16px;">
                <a href="{{ route('provider.asistencias') }}" class="btn-outline">Volver</a>
                @if(($requestItem['status'] ?? null) === 'created' && $r['portal_ready'])
                    <form method="POST" action="{{ route('provider.asistencias.accept', $requestItem['id']) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn">Aceptar solicitud</button>
                    </form>
                @endif
            </div>
        </section>

        <section class="card">
            <div class="section-head">
                <div>
                    <p class="eyebrow">Ubicación en vivo</p>
                    <h3>Estado del seguimiento</h3>
                </div>
            </div>

            @if($trackingActive)
                <div class="stack">
                    <div class="meta-box">
                        <span>Compartición actual</span>
                        <strong>Activa mientras esta solicitud siga asignada o en proceso.</strong>
                    </div>
                    <div class="meta-box">
                        <span>Qué se envía</span>
                        <strong>Latitud, longitud, precisión, dirección y velocidad cuando estén disponibles.</strong>
                    </div>
                    <div class="meta-box">
                        <span>Frecuencia</span>
                        <strong>El portal aplica un intervalo mínimo y evita enviar microcambios innecesarios.</strong>
                    </div>
                </div>
            @else
                <div class="empty">
                    <h4>Seguimiento detenido</h4>
                    <p>La ubicación en vivo solo se comparte cuando la asistencia está asignada o en proceso.</p>
                </div>
            @endif
        </section>
    </section>

    <section class="two-col">
        <section class="card">
            <div class="section-head">
                <div>
                    <p class="eyebrow">Acciones válidas</p>
                    <h3>Transiciones permitidas</h3>
                </div>
            </div>

            @if(!$r['portal_ready'])
                <div class="empty">
                    <h4>Portal bloqueado</h4>
                    <p>Mientras tu cuenta no esté lista para operar, aquí no se exponen acciones sobre la solicitud.</p>
                </div>
            @elseif(empty($allowedStatusOptions))
                <div class="empty">
                    <h4>Sin transición manual disponible</h4>
                    <p>Este estado ya no admite cambios manuales desde el portal o requiere una acción previa.</p>
                </div>
            @else
                <form method="POST" action="{{ route('provider.asistencias.status', $requestItem['id']) }}" class="form-grid">
                    @csrf
                    @method('PATCH')
                    <div class="field full">
                        <label class="label" for="status">Nuevo estado</label>
                        <select id="status" name="status" required>
                            <option value="">Selecciona una transición válida</option>
                            @foreach($allowedStatusOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field full">
                        <button type="submit" class="btn full">Actualizar estado</button>
                    </div>
                </form>
            @endif
        </section>

        <section class="card">
            <div class="section-head">
                <div>
                    <p class="eyebrow">Trazabilidad</p>
                    <h3>Historial del servicio</h3>
                </div>
            </div>

            @if(empty($requestRaw['history']) && empty($requestRaw['events']))
                <div class="empty"><h4>Sin movimientos registrados</h4><p>La API todavía no reporta eventos para esta solicitud.</p></div>
            @else
                <div class="timeline">
                    @foreach(($requestRaw['history'] ?? []) as $history)
                        <div class="timeline-entry">
                            <strong>{{ $history['status'] ?? 'Cambio registrado' }}</strong>
                            <p class="muted">{{ $history['event_type'] ?? ($history['notes'] ?? 'Sin detalle adicional') }}</p>
                        </div>
                    @endforeach
                    @foreach(($requestRaw['events'] ?? []) as $event)
                        <div class="timeline-entry">
                            <strong>{{ $event['event_type'] ?? 'Evento' }}</strong>
                            <p class="muted">{{ $event['status'] ?? ($event['created_at'] ?? 'Sin fecha visible') }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>
    </section>
@endsection
