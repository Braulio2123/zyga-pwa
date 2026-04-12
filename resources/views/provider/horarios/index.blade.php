@extends('provider.layouts.app')

@section('title', 'ZYGA | Horarios del proveedor')
@section('page-title', 'Horarios')

@section('content')
    @php($r = $context['readiness'])
    <section class="hero">
        <p class="eyebrow">Disponibilidad</p>
        <h2 style="margin:0 0 12px; font-size:2rem;">Horarios de atención</h2>
        <p class="muted" style="margin:0; line-height:1.6;">Registra la disponibilidad con la que deseas operar. El portal exige al menos un horario activo para considerarte listo.</p>
    </section>

    @if(!$context['hasProfile'])
        <section class="lockbox"><h3>Primero registra tu perfil</h3><a href="{{ route('provider.perfil') }}" class="btn">Ir a perfil</a></section>
    @else
        <section class="two-col">
            <section class="card">
                <div class="section-head"><div><p class="eyebrow">Nuevo horario</p><h3>Agregar disponibilidad</h3></div></div>
                <form method="POST" action="{{ route('provider.horarios.store') }}" class="form-grid">
                    @csrf
                    <div class="field"><label class="label" for="day_of_week">Día</label><select id="day_of_week" name="day_of_week" required><option value="">Selecciona</option>@foreach($dayOptions as $value => $label)<option value="{{ $value }}" {{ old('day_of_week') == $value ? 'selected' : '' }}>{{ $label }}</option>@endforeach</select></div>
                    <div class="field"><label class="label" for="start_time">Hora de inicio</label><input id="start_time" type="time" name="start_time" value="{{ old('start_time') }}" required></div>
                    <div class="field"><label class="label" for="end_time">Hora de cierre</label><input id="end_time" type="time" name="end_time" value="{{ old('end_time') }}" required></div>
                    <div class="field full"><button type="submit" class="btn full">Guardar horario</button></div>
                </form>
            </section>
            <section class="card">
                <div class="section-head"><div><p class="eyebrow">Disponibilidad actual</p><h3>Horarios registrados</h3></div></div>
                @if(empty($context['schedules']))
                    <div class="empty"><h4>Aún no tienes horarios</h4><p>Agrega al menos un horario activo para habilitar la operación desde el portal.</p></div>
                @else
                    <div class="list">@foreach($context['schedules'] as $schedule)<article class="item"><div class="item-head"><div><h4>{{ $dayOptions[$schedule['day_of_week']] ?? 'Día desconocido' }}</h4><p>{{ $schedule['start_time'] ?? '--:--' }} a {{ $schedule['end_time'] ?? '--:--' }}</p></div><span class="chip {{ ($schedule['is_active'] ?? true) ? 'success' : 'warning' }}">{{ ($schedule['is_active'] ?? true) ? 'Activo' : 'Inactivo' }}</span></div><form action="{{ route('provider.horarios.update', $schedule['id']) }}" method="POST" class="inline-form">@csrf @method('PATCH')<input type="hidden" name="day_of_week" value="{{ $schedule['day_of_week'] }}"><input type="time" name="start_time" value="{{ substr((string)($schedule['start_time'] ?? '00:00'), 0, 5) }}" required><input type="time" name="end_time" value="{{ substr((string)($schedule['end_time'] ?? '00:00'), 0, 5) }}" required><select name="is_active"><option value="1" {{ ($schedule['is_active'] ?? true) ? 'selected' : '' }}>Activo</option><option value="0" {{ !($schedule['is_active'] ?? true) ? 'selected' : '' }}>Inactivo</option></select><button type="submit" class="btn-outline">Actualizar</button></form><form action="{{ route('provider.horarios.delete', $schedule['id']) }}" method="POST" style="margin-top:10px;">@csrf @method('DELETE')<button type="submit" class="btn-ghost">Eliminar horario</button></form></article>@endforeach</div>
                @endif
            </section>
        </section>
    @endif
@endsection
