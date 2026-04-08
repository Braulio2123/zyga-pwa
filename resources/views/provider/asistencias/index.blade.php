@extends('provider.layouts.app')

@section('title', 'ZYGA | Asistencias provider')
@section('page-title', 'Asistencias provider')

@section('content')
    @php($r = $context['readiness'])
    <section class="hero hero-split">
        <div>
            <p class="eyebrow">Marketplace operativo</p>
            <h2 style="margin:0 0 12px; font-size:2rem;">Solicitudes disponibles y seguimiento</h2>
            <p class="muted" style="margin:0; line-height:1.6;">Consulta solicitudes disponibles, da seguimiento a servicios activos y revisa tu historial.</p>
            <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top:16px;">
                <span class="chip {{ $r['portal_ready'] ? 'success' : 'warning' }}">Portal: {{ $r['portal_ready'] ? 'listo' : 'bloqueado' }}</span>
                <span class="chip {{ $r['backend_can_operate'] ? 'success' : 'warning' }}">Operación: {{ $r['backend_can_operate'] ? 'habilitada' : 'bloqueada' }}</span>
            </div>
        </div>
        <div class="hero-panel">
            <div class="summary"><span class="helper">Disponibles</span><strong>{{ count($availableRequests) }}</strong></div>
            <div class="summary"><span class="helper">Activas</span><strong>{{ count($activeRequests) }}</strong></div>
            <div class="summary"><span class="helper">Histórico</span><strong>{{ count($historicalRequests) }}</strong></div>
        </div>
    </section>

    @if(!$context['hasProfile'])
        <section class="lockbox">
            <h3>Módulo bloqueado</h3>
            <p class="muted">Primero debes crear tu perfil provider.</p>
            <a href="{{ route('provider.perfil') }}" class="btn">Ir a perfil</a>
        </section>
    @elseif(!$r['portal_ready'])
        <section class="lockbox">
            <h3>No debes operar solicitudes todavía</h3>
            <div class="checklist">
                @foreach($r['blockers'] as $blocker)
                    <div class="check"><span>{{ $blocker }}</span><span class="chip warning">Pendiente</span></div>
                @endforeach
            </div>
        </section>
    @endif

    <section class="two-col">
        <section class="card">
            <div class="section-head">
                <div>
                    <p class="eyebrow">Bandeja de mercado</p>
                    <h3>Solicitudes disponibles</h3>
                </div>
            </div>

            @if(!$r['backend_can_operate'])
                <div class="empty">
                    <h4>Bandeja no operativa</h4>
                    <p>Completa tu configuración operativa para poder aceptar solicitudes.</p>
                </div>
            @elseif(empty($availableRequests))
                <div class="empty">
                    <h4>Sin solicitudes compatibles</h4>
                    <p>No hay solicitudes vivas para tus servicios en este momento.</p>
                </div>
            @else
                <div class="list">
                    @foreach($availableRequests as $request)
                        <article class="item">
                            <div class="item-head">
                                <div>
                                    <h4>{{ $request['service_name'] }}</h4>
                                    <p>{{ $request['pickup_address'] }}</p>
                                    <small>{{ $request['public_id'] ?: 'Sin folio público' }}</small>
                                </div>
                                <span class="chip info">{{ $request['status'] }}</span>
                            </div>
                            <div class="inline-form">
                                <a href="{{ route('provider.asistencias.show', $request['id']) }}" class="btn-outline">Ver detalle</a>
                                @if($r['portal_ready'])
                                    <form action="{{ route('provider.asistencias.accept', $request['id']) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn" type="submit">Aceptar solicitud</button>
                                    </form>
                                @endif
                            </div>
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
                    <h4>Sin solicitudes activas</h4>
                    <p>Cuando aceptes una solicitud y quede asignada o en proceso, aparecerá aquí.</p>
                </div>
            @else
                <div class="list">
                    @foreach($activeRequests as $request)
                        @php($statusOptions = $allowedStatusOptionsResolver($request['status']))
                        <article class="item">
                            <div class="item-head">
                                <div>
                                    <h4>{{ $request['service_name'] }}</h4>
                                    <p>{{ $request['pickup_address'] }}</p>
                                    <small>{{ $request['public_id'] ?: 'Sin folio público' }}</small>
                                </div>
                                <span class="chip dark">{{ $request['status'] }}</span>
                            </div>
                            <div class="inline-form">
                                <a href="{{ route('provider.asistencias.show', $request['id']) }}" class="btn-outline">Ver detalle</a>
                                @if(!empty($statusOptions) && $r['portal_ready'])
                                    <form action="{{ route('provider.asistencias.status', $request['id']) }}" method="POST" class="inline-form">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" required>
                                            <option value="">Transición válida</option>
                                            @foreach($statusOptions as $value => $label)
                                                <option value="{{ $value }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="btn">Actualizar</button>
                                    </form>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>
    </section>

    <section class="card">
        <div class="section-head">
            <div>
                <p class="eyebrow">Historial</p>
                <h3>Solicitudes cerradas</h3>
            </div>
        </div>
        @if(empty($historicalRequests))
            <div class="empty">
                <h4>Sin historial todavía</h4>
                <p>Cuando cierres o canceles solicitudes, aparecerán aquí.</p>
            </div>
        @else
            <div class="list">
                @foreach($historicalRequests as $request)
                    <article class="item">
                        <div class="item-head">
                            <div>
                                <h4>{{ $request['service_name'] }}</h4>
                                <p>{{ $request['pickup_address'] }}</p>
                                <small>{{ $request['public_id'] ?: 'Sin folio público' }}</small>
                            </div>
                            <span class="chip {{ $request['status'] === 'completed' ? 'success' : 'warning' }}">{{ $request['status'] }}</span>
                        </div>
                        <div style="margin-top:12px;"><a href="{{ route('provider.asistencias.show', $request['id']) }}" class="btn-outline">Ver detalle</a></div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>
@endsection
