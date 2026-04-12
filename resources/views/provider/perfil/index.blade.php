@extends('provider.layouts.app')

@section('title', 'ZYGA | Perfil del proveedor')
@section('page-title', 'Perfil del proveedor')

@section('content')
    @php($r = $context['readiness'])
    <section class="hero">
        <p class="eyebrow">Identidad comercial</p>
        <h2 style="margin:0 0 12px; font-size:2rem;">Información principal de tu cuenta</h2>
        <p class="muted" style="margin:0; line-height:1.6;">Registra o actualiza los datos base con los que operarás dentro de ZYGA. Esta información es la que administración revisa para validar tu cuenta.</p>
        <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top:16px;">
            <span class="chip {{ $r['checks']['has_profile'] ? 'success' : 'warning' }}">{{ $r['checks']['has_profile'] ? 'Perfil registrado' : 'Perfil pendiente' }}</span>
            <span class="chip {{ $r['status_tone'] ?? 'info' }}">{{ $r['status_name'] }}</span>
            <span class="chip {{ $r['checks']['is_verified'] ? 'success' : 'warning' }}">{{ $r['verification_text'] }}</span>
        </div>
    </section>

    <section class="two-col">
        <section class="card">
            <div class="section-head"><div><p class="eyebrow">Formulario</p><h3>{{ $context['hasProfile'] ? 'Actualizar perfil' : 'Crear perfil' }}</h3></div></div>
            <form method="POST" action="{{ $context['hasProfile'] ? route('provider.perfil.update') : route('provider.perfil.store') }}" class="form-grid">
                @csrf
                @if($context['hasProfile']) @method('PATCH') @endif
                <div class="field">
                    <label class="label" for="display_name">Nombre comercial</label>
                    <input type="text" id="display_name" name="display_name" value="{{ old('display_name', $context['profile']['display_name'] ?? '') }}" placeholder="Ej. Grúas Express Guadalajara" required>
                </div>
                <div class="field">
                    <label class="label" for="provider_kind">Tipo de proveedor</label>
                    <input type="text" id="provider_kind" name="provider_kind" value="{{ old('provider_kind', $context['profile']['provider_kind'] ?? '') }}" placeholder="Ej. grúa, batería, cerrajería">
                </div>
                <div class="field full">
                    <button class="btn full" type="submit">{{ $context['hasProfile'] ? 'Guardar cambios' : 'Crear perfil' }}</button>
                </div>
            </form>
        </section>

        <section class="card">
            <div class="section-head"><div><p class="eyebrow">Estado operativo</p><h3>Lo que falta para operar</h3></div></div>
            @if($r['portal_ready'])
                <div class="empty"><h4>Tu cuenta ya está lista</h4><p>Ya cumples con los elementos necesarios del portal para recibir y atender servicios.</p></div>
            @else
                <div class="checklist">
                    @foreach($r['blockers'] as $blocker)
                        <div class="check"><span>{{ $blocker }}</span><span class="chip warning">Pendiente</span></div>
                    @endforeach
                </div>
            @endif
            <div class="meta-grid" style="margin-top:16px;">
                <div class="meta-box"><span>Servicios</span><strong>{{ $r['services_count'] }}</strong></div>
                <div class="meta-box"><span>Horarios activos</span><strong>{{ $r['active_schedules_count'] }}</strong></div>
                <div class="meta-box"><span>Documentos</span><strong>{{ $r['documents_count'] }}</strong></div>
                <div class="meta-box"><span>Validación</span><strong>{{ $r['verification_text'] }}</strong></div>
            </div>
        </section>
    </section>
@endsection
