@extends('provider.layouts.app')

@section('title', 'ZYGA | Servicios provider')
@section('page-title', 'Servicios')

@section('content')
@php
    $current = $providerServicesResult['data']['services'] ?? [];
    $catalog = $catalogServicesResult['data'] ?? [];
    $selectedIds = collect($current)->pluck('id')->map(fn ($id) => (int) $id)->all();
@endphp

<section class="hero-card">
    <div>
        <p class="hero-kicker">Cobertura operativa</p>
        <h2>Servicios del proveedor</h2>
        <p class="muted">Selecciona los servicios activos del catálogo general que realmente puedes atender.</p>
    </div>
    <div class="hero-badge">{{ count($selectedIds) }} seleccionados</div>
</section>

@if(!$providerServicesResult['ok'] && ($providerServicesResult['status'] ?? null) !== 404)
    <section class="section-block">
        <div class="panel-card">
            <h3>No se pudieron cargar tus servicios actuales</h3>
            <p>{{ $providerServicesResult['message'] }}</p>
        </div>
    </section>
@endif

<section class="section-block">
    <div class="section-head">
        <h3>Actualizar servicios</h3>
        <span class="pill">PUT /provider/services</span>
    </div>

    @if(empty($catalog))
        <div class="panel-card">
            <h4>Catálogo no disponible</h4>
            <p class="muted">La API no devolvió servicios públicos activos en este momento.</p>
        </div>
    @else
        <form action="{{ route('provider.servicios.update') }}" method="POST" class="panel-card">
            @csrf
            @method('PUT')

            <div class="checkbox-grid">
                @foreach($catalog as $servicio)
                    @php
                        $id = (int) ($servicio['id'] ?? 0);
                        $checked = in_array($id, old('service_ids', $selectedIds), true);
                    @endphp
                    <label class="check-card">
                        <input type="checkbox" name="service_ids[]" value="{{ $id }}" {{ $checked ? 'checked' : '' }}>
                        <div>
                            <strong>{{ $servicio['name'] ?? 'Servicio sin nombre' }}</strong>
                            <p>{{ $servicio['description'] ?? 'Sin descripción.' }}</p>
                            @if(!empty($servicio['code']))
                                <span class="pill">{{ $servicio['code'] }}</span>
                            @endif
                        </div>
                    </label>
                @endforeach
            </div>

            <div class="form-actions" style="margin-top:16px;">
                <button type="submit" class="btn-primary">Guardar servicios</button>
            </div>
        </form>
    @endif
</section>
@endsection
