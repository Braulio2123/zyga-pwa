@extends('provider.layouts.app')

@section('title', 'ZYGA | Asistencias provider')
@section('page-title', 'Asistencias')

@section('content')
    <section class="hero-card">
        <div>
            <p class="hero-kicker">Operación diaria</p>
            <h2 style="margin:0 0 8px;">Solicitudes y asistencias</h2>
            <p class="muted">Acepta solicitudes compatibles con tus servicios y actualiza su estatus solo con transiciones válidas según la API.</p>
        </div>

        <div class="hero-stats">
            <div class="hero-stat summary-card">
                <span class="helper-text">Disponibles</span>
                <strong>{{ count($availableRequests) }}</strong>
            </div>
            <div class="hero-stat summary-card">
                <span class="helper-text">Asignadas</span>
                <strong>{{ count($myRequests) }}</strong>
            </div>
        </div>
    </section>

    @if(!$hasProfile)
        <section class="locked-module">
            <h3>Módulo bloqueado temporalmente</h3>
            <p>Primero debes crear tu perfil de proveedor para poder ver y gestionar asistencias.</p>
            <a href="{{ route('provider.perfil') }}" class="btn-primary">Ir a crear perfil</a>
        </section>
    @else
        @if(!$availableResponse['ok'] && !$mineResponse['ok'])
            <section class="section-card">
                <div class="alert danger">No se pudo cargar la información de asistencias desde la API.</div>
            </section>
        @endif

        <section class="split-grid">
            <section class="section-card">
                <div class="section-head">
                    <div>
                        <p class="dashboard-card__eyebrow">Bandeja abierta</p>
                        <h3>Solicitudes disponibles</h3>
                    </div>
                </div>

                @if(empty($availableRequests))
                    <div class="empty-state">
                        <h4>No hay solicitudes disponibles</h4>
                        <p>En cuanto existan solicitudes compatibles con tus servicios aparecerán aquí.</p>
                    </div>
                @else
                    <div class="stack-list">
                        @foreach($availableRequests as $request)
                            <article class="list-card">
                                <h4>{{ $request['service']['name'] ?? $request['service_name'] ?? 'Servicio' }}</h4>
                                <p>{{ $request['pickup_address'] ?? $request['address'] ?? 'Sin dirección' }}</p>
                                <small>ID: {{ $request['id'] ?? 'N/D' }}</small>

                                @if(!empty($request['id']))
                                    <form action="{{ route('provider.asistencias.accept', $request['id']) }}" method="POST" style="margin-top:12px;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn-primary">Aceptar solicitud</button>
                                    </form>
                                @endif
                            </article>
                        @endforeach
                    </div>
                @endif
            </section>

            <section class="section-card">
                <div class="section-head">
                    <div>
                        <p class="dashboard-card__eyebrow">Seguimiento</p>
                        <h3>Mis asistencias</h3>
                    </div>
                </div>

                @if(empty($myRequests))
                    <div class="empty-state">
                        <h4>No tienes asistencias asignadas</h4>
                        <p>Cuando aceptes una solicitud aparecerá aquí para seguimiento.</p>
                    </div>
                @else
                    <div class="stack-list">
                        @foreach($myRequests as $request)
                            @php
                                $currentStatus = $request['status'] ?? null;
                                $statusOptions = $allowedStatusOptionsResolver($currentStatus);
                            @endphp
                            <article class="list-card">
                                <h4>{{ $request['service']['name'] ?? $request['service_name'] ?? 'Servicio' }}</h4>
                                <p>{{ $request['pickup_address'] ?? $request['address'] ?? 'Sin dirección' }}</p>
                                <small>Estatus actual: {{ $currentStatus ?? 'Sin estatus' }}</small>

                                @if(!empty($request['id']) && !empty($statusOptions))
                                    <form action="{{ route('provider.asistencias.status', $request['id']) }}" method="POST" class="inline-form" style="margin-top:12px;">
                                        @csrf
                                        @method('PATCH')

                                        <select name="status" required>
                                            <option value="">Selecciona transición válida</option>
                                            @foreach($statusOptions as $value => $label)
                                                <option value="{{ $value }}">{{ $label }}</option>
                                            @endforeach
                                        </select>

                                        <button type="submit" class="btn-primary">Actualizar</button>
                                    </form>
                                @else
                                    <div class="helper-box" style="margin-top:12px;">
                                        <strong>No hay cambios manuales disponibles</strong>
                                        <p class="muted" style="margin-top:6px;">Este estado ya no permite una transición desde el portal o la API lo considera final.</p>
                                    </div>
                                @endif
                            </article>
                        @endforeach
                    </div>
                @endif
            </section>
        </section>
    @endif
@endsection
