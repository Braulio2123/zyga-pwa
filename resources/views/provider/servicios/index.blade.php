@extends('provider.layouts.app')

@section('title', 'ZYGA | Servicios provider')
@section('page-title', 'Servicios')

@section('content')
    <section class="hero-card">
        <div>
            <p class="hero-kicker">Paso 2 de 3</p>
            <h2 style="margin:0 0 8px;">Servicios que atiendes</h2>
            <p class="muted">Selecciona únicamente los servicios que realmente puede cubrir tu operación.</p>
        </div>
        <div class="hero-stat summary-card">
            <span class="helper-text">Seleccionados</span>
            <strong>{{ count($selectedIds) }}</strong>
        </div>
    </section>

    @if(!$hasProfile)
        <section class="locked-module">
            <h3>Módulo bloqueado temporalmente</h3>
            <p>Primero debes crear tu perfil de proveedor para poder seleccionar servicios.</p>
            <a href="{{ route('provider.perfil') }}" class="btn-primary">Ir a crear perfil</a>
        </section>
    @else
        @if(!$catalogResponse['ok'])
            <section class="section-card"><div class="alert danger">{{ $catalogResponse['message'] ?? 'No se pudo cargar el catálogo general de servicios.' }}</div></section>
        @endif

        <section class="section-card">
            <div class="section-head">
                <div>
                    <p class="dashboard-card__eyebrow">Catálogo público</p>
                    <h3>Selecciona tus servicios activos</h3>
                </div>
            </div>

            @if(empty($catalog))
                <div class="empty-state">
                    <h4>No hay servicios disponibles en el catálogo</h4>
                    <p>La API no devolvió servicios activos para mostrar.</p>
                </div>
            @else
                <form action="{{ route('provider.servicios.update') }}" method="POST" class="stack-list">
                    @csrf
                    @method('PUT')

                    @foreach($catalog as $service)
                        <label class="list-card" style="display:block; cursor:pointer;">
                            <div style="display:flex; justify-content:space-between; gap:14px; align-items:flex-start;">
                                <div>
                                    <h4>{{ $service['name'] ?? 'Servicio' }}</h4>
                                    <p>{{ $service['description'] ?? 'Servicio disponible en el catálogo general.' }}</p>
                                </div>
                                <div>
                                    <input type="checkbox" name="service_ids[]" value="{{ $service['id'] ?? 0 }}" {{ in_array((int) ($service['id'] ?? 0), $selectedIds, true) ? 'checked' : '' }} style="width:20px; height:20px; margin:0;">
                                </div>
                            </div>
                        </label>
                    @endforeach

                    <div class="cta-row">
                        <button type="submit" class="btn-primary">Guardar servicios</button>
                        <a href="{{ route('provider.horarios') }}" class="btn-secondary">Continuar con horarios</a>
                    </div>
                </form>
            @endif
        </section>
    @endif
@endsection
