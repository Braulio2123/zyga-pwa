@extends('provider.layouts.app')

@section('title', 'ZYGA | Servicios provider')
@section('page-title', 'Servicios y compatibilidad de atención')
@section('page-copy', 'Configura qué atenciones puede tomar tu operación.')

@section('content')
    @php($r = $context['readiness'])
    <section class="hero">
        <p class="eyebrow">Compatibilidad de atención</p>
        <h2 style="margin:0 0 12px; font-size:2rem;">Servicios que ofreces</h2>
        <p class="muted" style="margin:0; line-height:1.6;">Configura los servicios que tu unidad puede atender.</p>
    </section>

    @if(!$context['hasProfile'])
        <section class="lockbox">
            <h3>Primero crea tu perfil</h3>
            <p class="muted">Primero completa tu perfil para poder asociar servicios.</p>
            <a href="{{ route('provider.perfil') }}" class="btn">Ir a perfil</a>
        </section>
    @else
        <section class="two-col">
            <section class="card">
                <div class="section-head">
                    <div>
                        <p class="eyebrow">Configuración</p>
                        <h3>Selecciona servicios activos</h3>
                    </div>
                </div>

                @if(!$catalogResponse['ok'])
                    <div class="alert danger">No fue posible cargar el catálogo de servicios.</div>
                @elseif(empty($catalog))
                    <div class="empty">
                        <h4>Catálogo vacío</h4>
                        <p>No hay servicios disponibles por el momento.</p>
                    </div>
                @else
                    <form method="POST" action="{{ route('provider.servicios.update') }}" class="stack">
                        @csrf
                        @method('PUT')

                        @foreach($catalog as $service)
                            <label class="check">
                                <span>
                                    <strong>{{ $service['name'] ?? 'Servicio' }}</strong>
                                    <small style="display:block; color:var(--muted); margin-top:6px;">{{ $service['description'] ?? ($service['code'] ?? 'Sin descripción') }}</small>
                                </span>
                                <input type="checkbox" name="service_ids[]" value="{{ $service['id'] }}" {{ in_array((int) $service['id'], $selectedIds, true) ? 'checked' : '' }}>
                            </label>
                        @endforeach

                        <button type="submit" class="btn full">Guardar servicios</button>
                    </form>
                @endif
            </section>

            <section class="card">
                <div class="section-head">
                    <div>
                        <p class="eyebrow">Resumen</p>
                        <h3>Impacto operativo</h3>
                    </div>
                </div>
                <div class="three-col">
                    <div class="summary"><span class="helper">Servicios ligados</span><strong>{{ $r['services_count'] }}</strong></div>
                    <div class="summary"><span class="helper">Puede hacer matching</span><strong>{{ $r['services_count'] > 0 ? 'Sí' : 'No' }}</strong></div>
                    <div class="summary"><span class="helper">Operación habilitada</span><strong>{{ $r['backend_can_operate'] ? 'Sí' : 'No' }}</strong></div>
                </div>
                <p class="helper" style="margin-top:16px;">Sin al menos un servicio ligado, el provider no verá solicitudes disponibles compatibles aunque tenga perfil creado.</p>
            </section>
        </section>
    @endif
@endsection
