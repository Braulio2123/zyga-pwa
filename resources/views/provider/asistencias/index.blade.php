@extends('provider.layouts.app')

@section('title', 'ZYGA | Asistencias provider')
@section('page-title', 'Asistencias')

@section('content')
@php
    $availableItems = $availableResult['data'] ?? [];
    $myItems = $myRequestsResult['data'] ?? [];

    $statusLabels = [
        'created' => 'Creada',
        'assigned' => 'Asignada',
        'in_progress' => 'En progreso',
        'completed' => 'Completada',
        'cancelled' => 'Cancelada',
    ];
@endphp

<section class="hero-card">
    <div>
        <p class="hero-kicker">Operación del proveedor</p>
        <h2>Solicitudes y asistencias</h2>
        <p class="muted">Aquí se muestran las solicitudes disponibles para aceptar y las que ya están asignadas a tu cuenta.</p>
    </div>
    <div class="hero-badge">{{ count($myItems) }} mías</div>
</section>

<section class="section-block">
    <div class="section-head">
        <h3>Solicitudes disponibles</h3>
        <span class="pill">{{ count($availableItems) }} disponibles</span>
    </div>

    @if(empty($availableItems))
        <div class="panel-card">
            <h4>Sin solicitudes disponibles</h4>
            <p class="muted">No hay solicitudes abiertas compatibles con los servicios del proveedor en este momento.</p>
        </div>
    @else
        <div class="stack-list">
            @foreach($availableItems as $item)
                <article class="list-card">
                    <div class="inline-between gap-12" style="align-items:flex-start;">
                        <div style="flex:1;">
                            <h4>{{ $item['service']['name'] ?? 'Servicio sin nombre' }}</h4>
                            <p><strong>Cliente:</strong> {{ $item['user']['email'] ?? 'No disponible' }}</p>
                            <p><strong>Dirección:</strong> {{ $item['pickup_address'] ?? 'Sin dirección registrada' }}</p>
                            <p><strong>Vehículo:</strong>
                                {{ trim(($item['vehicle']['brand'] ?? '') . ' ' . ($item['vehicle']['model'] ?? '')) ?: 'No registrado' }}
                            </p>
                            <span class="meta-text">Estatus: {{ $statusLabels[$item['status'] ?? ''] ?? ($item['status'] ?? 'Sin estado') }}</span>
                        </div>

                        <form action="{{ route('provider.asistencias.accept', $item['id']) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn-primary">Aceptar</button>
                        </form>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</section>

<section class="section-block">
    <div class="section-head">
        <h3>Mis asistencias</h3>
        <span class="pill">{{ count($myItems) }} registros</span>
    </div>

    @if(empty($myItems))
        <div class="panel-card">
            <h4>Aún no has aceptado asistencias</h4>
            <p class="muted">Cuando aceptes una solicitud disponible, aparecerá aquí para su seguimiento.</p>
        </div>
    @else
        <div class="stack-list">
            @foreach($myItems as $item)
                @php
                    $status = $item['status'] ?? '';
                @endphp
                <article class="list-card">
                    <div class="inline-between gap-12" style="align-items:flex-start;">
                        <div style="flex:1;">
                            <h4>{{ $item['service']['name'] ?? 'Servicio sin nombre' }}</h4>
                            <p><strong>Cliente:</strong> {{ $item['user']['email'] ?? 'No disponible' }}</p>
                            <p><strong>Dirección:</strong> {{ $item['pickup_address'] ?? 'Sin dirección registrada' }}</p>
                            <p><strong>Vehículo:</strong>
                                {{ trim(($item['vehicle']['brand'] ?? '') . ' ' . ($item['vehicle']['model'] ?? '')) ?: 'No registrado' }}
                            </p>
                            <span class="{{ in_array($status, ['completed'], true) ? 'pill pill-success' : (in_array($status, ['cancelled'], true) ? 'pill pill-warning' : 'pill') }}">
                                {{ $statusLabels[$status] ?? ($status ?: 'Sin estado') }}
                            </span>
                        </div>

                        <div class="actions-stack">
                            @if($status === 'assigned')
                                <form action="{{ route('provider.asistencias.status', $item['id']) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="in_progress">
                                    <button type="submit" class="btn-primary">Iniciar</button>
                                </form>
                                <form action="{{ route('provider.asistencias.status', $item['id']) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="cancelled">
                                    <button type="submit" class="btn-secondary">Cancelar</button>
                                </form>
                            @elseif($status === 'in_progress')
                                <form action="{{ route('provider.asistencias.status', $item['id']) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="completed">
                                    <button type="submit" class="btn-primary">Completar</button>
                                </form>
                                <form action="{{ route('provider.asistencias.status', $item['id']) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="cancelled">
                                    <button type="submit" class="btn-secondary">Cancelar</button>
                                </form>
                            @else
                                <span class="meta-text">Sin acciones disponibles</span>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</section>
@endsection
