@extends('provider.layouts.app')

@section('title', 'ZYGA | Servicios del proveedor')
@section('page-title', 'Servicios')

@section('content')
    @php($r = $context['readiness'])
    <section class="hero">
        <p class="eyebrow">Compatibilidad</p>
        <h2 style="margin:0 0 12px; font-size:2rem;">Servicios que puedes atender</h2>
        <p class="muted" style="margin:0; line-height:1.6;">Selecciona únicamente los servicios que realmente puedes cubrir. Esto define qué solicitudes se mostrarán en tu bandeja.</p>
    </section>

    @if(!$context['hasProfile'])
        <section class="lockbox"><h3>Primero registra tu perfil</h3><p class="muted">Necesitas un perfil antes de asociar servicios a tu cuenta.</p><a href="{{ route('provider.perfil') }}" class="btn">Ir a perfil</a></section>
    @else
        <section class="two-col">
            <section class="card">
                <div class="section-head"><div><p class="eyebrow">Catálogo</p><h3>Selecciona tus servicios</h3></div></div>
                @if(!$catalogResponse['ok'])
                    <div class="empty"><h4>No se pudo cargar el catálogo</h4><p>{{ $catalogResponse['message'] ?? 'Intenta nuevamente más tarde.' }}</p></div>
                @else
                    <form method="POST" action="{{ route('provider.servicios.update') }}" class="stack">
                        @csrf @method('PUT')
                        @foreach($catalog as $service)
                            <label class="check">
                                <span>
                                    <strong>{{ $service['name'] ?? 'Servicio' }}</strong><br>
                                    <small>{{ $service['description'] ?? 'Sin descripción' }}</small>
                                </span>
                                <input type="checkbox" name="service_ids[]" value="{{ $service['id'] }}" {{ in_array((int) $service['id'], $selectedIds, true) ? 'checked' : '' }}>
                            </label>
                        @endforeach
                        <button class="btn full" type="submit">Guardar servicios</button>
                    </form>
                @endif
            </section>
            <section class="card">
                <div class="section-head"><div><p class="eyebrow">Estado</p><h3>Resumen de compatibilidad</h3></div></div>
                <div class="meta-grid">
                    <div class="meta-box"><span>Servicios activos</span><strong>{{ $r['services_count'] }}</strong></div>
                    <div class="meta-box"><span>Cuenta validada</span><strong>{{ $r['verification_text'] }}</strong></div>
                </div>
                <div class="empty" style="margin-top:16px;"><h4>Recomendación</h4><p>Activa solo los servicios que realmente puedes atender con tiempos y recursos consistentes. Una configuración precisa mejora la calidad del matching del MVP.</p></div>
            </section>
        </section>
    @endif
@endsection
