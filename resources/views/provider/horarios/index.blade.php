@extends('provider.layouts.app')

@section('title', 'ZYGA | Horarios provider')
@section('page-title', 'Horarios')

@section('content')
@php
    $items = $horariosResult['data']['schedules'] ?? [];
    $dias = [1 => 'Lunes', 2 => 'Martes', 3 => 'Miércoles', 4 => 'Jueves', 5 => 'Viernes', 6 => 'Sábado', 7 => 'Domingo'];
@endphp

<section class="hero-card">
    <div>
        <p class="hero-kicker">Disponibilidad</p>
        <h2>Gestiona tus horarios</h2>
        <p class="muted">Registra un horario por día y mantén actualizada tu disponibilidad operativa.</p>
    </div>
    <div class="hero-badge">{{ count($items) }} registros</div>
</section>

<section class="section-block">
    <div class="section-head">
        <h3>Nuevo horario</h3>
        <span class="pill">POST /provider/schedules</span>
    </div>

    <form action="{{ route('provider.horarios.store') }}" method="POST" class="panel-card form-grid">
        @csrf

        <div class="form-field">
            <label for="day_of_week">Día</label>
            <select name="day_of_week" id="day_of_week" required>
                <option value="">Selecciona un día</option>
                @foreach($dias as $valor => $texto)
                    <option value="{{ $valor }}" {{ old('day_of_week') == $valor ? 'selected' : '' }}>{{ $texto }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-field">
            <label for="timezone">Zona horaria</label>
            <input type="text" id="timezone" name="timezone" value="{{ old('timezone', 'America/Mexico_City') }}">
        </div>

        <div class="form-field">
            <label for="start_time">Hora de inicio</label>
            <input type="time" id="start_time" name="start_time" value="{{ old('start_time') }}" required>
        </div>

        <div class="form-field">
            <label for="end_time">Hora de fin</label>
            <input type="time" id="end_time" name="end_time" value="{{ old('end_time') }}" required>
        </div>

        <div class="form-field form-field-full">
            <label style="display:flex; align-items:center; gap:10px;">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                Horario activo
            </label>
        </div>

        <div class="form-actions form-field-full">
            <button type="submit" class="btn-primary">Guardar horario</button>
        </div>
    </form>
</section>

<section class="section-block">
    <div class="section-head">
        <h3>Horarios registrados</h3>
        <span class="pill">{{ count($items) }} configurados</span>
    </div>

    @if(empty($items))
        <div class="panel-card">
            <h4>Sin horarios registrados</h4>
            <p class="muted">Aún no hay disponibilidad capturada para este proveedor.</p>
        </div>
    @else
        <div class="stack-list">
            @foreach($items as $horario)
                @php
                    $id = $horario['id'] ?? null;
                    $dayOfWeek = (int) ($horario['day_of_week'] ?? 0);
                    $inicio = substr((string) ($horario['start_time'] ?? '--:--'), 0, 5);
                    $fin = substr((string) ($horario['end_time'] ?? '--:--'), 0, 5);
                    $activo = (bool) ($horario['is_active'] ?? false);
                @endphp

                <article class="list-card">
                    <div class="inline-between gap-12" style="align-items:flex-start;">
                        <div style="flex:1; min-width:220px;">
                            <h4>{{ $dias[$dayOfWeek] ?? 'No definido' }}</h4>
                            <p><strong>Horario:</strong> {{ $inicio }} - {{ $fin }}</p>
                            <p><strong>Zona horaria:</strong> {{ $horario['timezone'] ?? 'America/Mexico_City' }}</p>
                            <span class="{{ $activo ? 'pill pill-success' : 'pill pill-warning' }}">
                                {{ $activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>

                        <div style="flex:1; min-width:280px;">
                            <form action="{{ route('provider.horarios.update', $id) }}" method="POST" class="form-grid">
                                @csrf
                                @method('PATCH')

                                <div class="form-field">
                                    <label>Día</label>
                                    <select name="day_of_week">
                                        @foreach($dias as $valor => $texto)
                                            <option value="{{ $valor }}" {{ $dayOfWeek === $valor ? 'selected' : '' }}>{{ $texto }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-field">
                                    <label>Zona horaria</label>
                                    <input type="text" name="timezone" value="{{ $horario['timezone'] ?? 'America/Mexico_City' }}">
                                </div>

                                <div class="form-field">
                                    <label>Inicio</label>
                                    <input type="time" name="start_time" value="{{ $inicio }}" required>
                                </div>

                                <div class="form-field">
                                    <label>Fin</label>
                                    <input type="time" name="end_time" value="{{ $fin }}" required>
                                </div>

                                <div class="form-field form-field-full">
                                    <label style="display:flex; align-items:center; gap:10px;">
                                        <input type="checkbox" name="is_active" value="1" {{ $activo ? 'checked' : '' }}>
                                        Horario activo
                                    </label>
                                </div>

                                <div class="form-actions form-field-full">
                                    <button type="submit" class="btn-primary">Actualizar</button>
                                </div>
                            </form>

                            <form action="{{ route('provider.horarios.delete', $id) }}" method="POST" style="margin-top:10px;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-secondary">Eliminar</button>
                            </form>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</section>
@endsection
