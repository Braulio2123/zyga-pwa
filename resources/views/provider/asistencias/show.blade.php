@extends('provider.layouts.app')

@section('title', 'ZYGA | Detalle de asistencia')
@section('page-title', 'Seguimiento táctico de la asistencia')
@section('page-copy', 'Consulta detalle, progreso y acciones permitidas sobre la solicitud.')

@section('content')
    @php($r = $context['readiness'])
    <section class="hero">
        <p class="eyebrow">Solicitud operativa</p>
        <h2 style="margin:0 0 12px; font-size:2rem;">{{ $requestItem['service_name'] }}</h2>
        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <span class="chip dark">{{ $requestItem['status'] ?? 'Sin estado' }}</span>
            @if($requestItem['public_id'])
                <span class="chip info">{{ $requestItem['public_id'] }}</span>
            @endif
            <span class="chip {{ $r['portal_ready'] ? 'success' : 'warning' }}">Portal {{ $r['portal_ready'] ? 'habilitado' : 'bloqueado' }}</span>
        </div>
        <p class="muted" style="margin:16px 0 0; line-height:1.6;">Vista sustentada por el endpoint real <strong>/api/v1/provider/assistance-requests/{id}</strong>. Aquí sí existe un detalle funcional del trabajo asignado o histórico.</p>
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
                    <p class="eyebrow">Acciones válidas</p>
                    <h3>Transiciones permitidas</h3>
                </div>
            </div>

            @if(!$r['portal_ready'])
                <div class="empty">
                    <h4>Portal bloqueado</h4>
                    <p>Mientras el provider no esté listo, no se exponen acciones operativas aquí.</p>
                </div>
            @elseif(empty($allowedStatusOptions))
                <div class="empty">
                    <h4>Sin transición manual disponible</h4>
                    <p>Este estado ya no admite cambios manuales desde el portal o todavía requiere aceptación previa.</p>
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
    </section>

    <section class="card">
        <div class="section-head">
            <div>
                <p class="eyebrow">Timeline técnico</p>
                <h3>Eventos e historial registrados</h3>
            </div>
        </div>

        <div class="two-col">
            <div>
                <h4 style="margin-top:0;">Request history</h4>
                @if(empty($requestRaw['history']))
                    <div class="empty"><h4>Sin historial</h4><p>Aún no hay movimientos registrados para esta solicitud.</p></div>
                @else
                    <div class="timeline">
                        @foreach($requestRaw['history'] as $history)
                            <div class="timeline-entry">
                                <strong>{{ $history['status'] ?? 'Sin estado' }}</strong>
                                <p class="muted">{{ $history['event_type'] ?? ($history['notes'] ?? 'Sin detalle') }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            <div>
                <h4 style="margin-top:0;">Request events</h4>
                @if(empty($requestRaw['events']))
                    <div class="empty"><h4>Sin eventos</h4><p>Aún no hay eventos disponibles para esta solicitud.</p></div>
                @else
                    <div class="timeline">
                        @foreach($requestRaw['events'] as $event)
                            <div class="timeline-entry">
                                <strong>{{ $event['event_type'] ?? 'Evento' }}</strong>
                                <p class="muted">{{ $event['status'] ?? ($event['created_at'] ?? 'Sin fecha') }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
