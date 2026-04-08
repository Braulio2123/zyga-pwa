@extends('provider.layouts.app')

@section('title', 'ZYGA | Perfil provider')
@section('page-title', 'Perfil provider')

@section('content')
    @php($r = $context['readiness'])
    <section class="hero">
        <p class="eyebrow">Identidad operativa</p>
        <h2 style="margin:0 0 12px; font-size:2rem;">Perfil base del proveedor</h2>
        <p class="muted" style="margin:0; line-height:1.6;">Administra la información principal de tu perfil operativo.</p>
        <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top:16px;">
            <span class="chip {{ $r['checks']['has_profile'] ? 'success' : 'warning' }}">{{ $r['checks']['has_profile'] ? 'Perfil creado' : 'Perfil pendiente' }}</span>
            <span class="chip {{ $r['checks']['is_verified'] ? 'success' : 'warning' }}">{{ $r['verification_text'] }}</span>
            <span class="chip {{ $r['checks']['status_active'] ? 'success' : 'warning' }}">Estado: {{ $r['status_name'] }}</span>
        </div>
    </section>

    <section class="two-col">
        <section class="card">
            <div class="section-head">
                <div>
                    <p class="eyebrow">Formulario</p>
                    <h3>{{ $context['hasProfile'] ? 'Editar perfil' : 'Crear perfil' }}</h3>
                </div>
            </div>

            <form method="POST" action="{{ $context['hasProfile'] ? route('provider.perfil.update') : route('provider.perfil.store') }}" class="form-grid">
                @csrf
                @if($context['hasProfile'])
                    @method('PATCH')
                @endif

                <div class="field full">
                    <label class="label" for="display_name">Nombre comercial / visible</label>
                    <input type="text" id="display_name" name="display_name" value="{{ old('display_name', $context['profile']['display_name'] ?? '') }}" placeholder="Ej. Grúas Express GDL" required>
                </div>

                <div class="field full">
                    <label class="label" for="provider_kind">Tipo de proveedor</label>
                    <input type="text" id="provider_kind" name="provider_kind" value="{{ old('provider_kind', $context['profile']['provider_kind'] ?? '') }}" placeholder="Ej. grua, bateria, cerrajeria, gasolina">
                </div>

                <div class="field full">
                    <button type="submit" class="btn full">{{ $context['hasProfile'] ? 'Actualizar perfil' : 'Crear perfil' }}</button>
                </div>
            </form>
        </section>

        <section class="card">
            <div class="section-head">
                <div>
                    <p class="eyebrow">Estado actual</p>
                    <h3>Resumen del provider</h3>
                </div>
            </div>

            <div class="three-col">
                <div class="summary"><span class="helper">Perfil</span><strong>{{ $context['hasProfile'] ? 'Creado' : 'Pendiente' }}</strong></div>
                <div class="summary"><span class="helper">Verificación</span><strong>{{ $r['verification_text'] }}</strong></div>
                <div class="summary"><span class="helper">Estado</span><strong>{{ $r['status_name'] }}</strong></div>
            </div>

            @if($context['hasProfile'])
                <div class="meta-grid" style="margin-top:16px;">
                    <div class="meta-box"><span>Display name</span><strong>{{ $context['profile']['display_name'] ?? 'Sin nombre' }}</strong></div>
                    <div class="meta-box"><span>Tipo</span><strong>{{ $context['profile']['provider_kind'] ?? 'Sin tipo' }}</strong></div>
                    <div class="meta-box"><span>Provider ID</span><strong>{{ $context['profile']['id'] ?? 'N/D' }}</strong></div>
                    <div class="meta-box"><span>Usuario ID</span><strong>{{ $context['profile']['user_id'] ?? session('user.id') }}</strong></div>
                </div>
            @else
                <div class="empty" style="margin-top:16px;">
                    <h4>Sin perfil todavía</h4>
                    <p>Mientras no exista el perfil provider, las demás pantallas operativas deben considerarse bloqueadas.</p>
                </div>
            @endif
        </section>
    </section>
@endsection
