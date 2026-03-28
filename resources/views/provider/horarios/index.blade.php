@extends('provider.layouts.app')

@section('title', 'Zyga | Horarios')
@section('page-title', 'Horarios')

@section('content')
    @php
        $hasApiError = !empty($horarios['error']);
        $items = $horarios['data'] ?? [];

        if (!is_array($items)) {
            $items = [];
        }

        $dias = [
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
            7 => 'Domingo',
        ];
    @endphp

    @if($hasApiError)
        <section class="section-block">
            <div class="panel-card">
                <h3>Error de conexión</h3>
                <p>{{ $horarios['message'] ?? 'No se pudieron cargar los horarios.' }}</p>

                @if(!empty($horarios['details']))
                    <p class="muted">{{ $horarios['details'] }}</p>
                @endif
            </div>
        </section>
    @endif

    <section class="hero-card">
        <div>
            <p class="hero-kicker">Disponibilidad del proveedor</p>
            <h2>Gestiona tus horarios</h2>
            <p class="muted">
                Agrega, consulta y actualiza los días y horas en que atenderás solicitudes de Zyga.
            </p>
        </div>

        <div class="hero-badge">
            {{ count($items) }} {{ count($items) === 1 ? 'registro' : 'registros' }}
        </div>
    </section>

    <section class="section-block">
        <div class="section-head">
            <h3>Nuevo horario</h3>
            <span class="pill">Editable</span>
        </div>

        <form action="{{ route('provider.horarios.store') }}" method="POST" class="panel-card form-grid">
            @csrf

            <div class="form-field">
                <label for="day_of_week">Día</label>
                <select name="day_of_week" id="day_of_week" required>
                    <option value="">Selecciona un día</option>
                    @foreach($dias as $valor => $texto)
                        <option value="{{ $valor }}" {{ old('day_of_week') == $valor ? 'selected' : '' }}>
                            {{ $texto }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-field">
                <label for="timezone">Zona horaria</label>
                <input
                    type="text"
                    id="timezone"
                    name="timezone"
                    value="{{ old('timezone', 'America/Mexico_City') }}"
                    required
                >
            </div>

            <div class="form-field">
                <label for="start_time">Hora de inicio</label>
                <input
                    type="time"
                    id="start_time"
                    name="start_time"
                    value="{{ old('start_time') }}"
                    required
                >
            </div>

            <div class="form-field">
                <label for="end_time">Hora de fin</label>
                <input
                    type="time"
                    id="end_time"
                    name="end_time"
                    value="{{ old('end_time') }}"
                    required
                >
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
            <span class="pill">Proveedor</span>
        </div>

        @if(empty($items))
            <div class="panel-card">
                <h4>Sin horarios registrados</h4>
                <p class="muted">
                    Aún no hay horarios configurados para este proveedor.
                </p>
            </div>
        @else
            <div class="stack-list">
                @foreach($items as $horario)
                    @php
                        $id = $horario['id'] ?? null;
                        $dia = $dias[$horario['day_of_week'] ?? 0] ?? 'No definido';
                        $inicio = $horario['start_time'] ?? '--:--';
                        $fin = $horario['end_time'] ?? '--:--';
                        $zona = $horario['timezone'] ?? 'Sin zona';
                        $activo = $horario['is_active'] ?? false;
                    @endphp

                    <article class="list-card">
                        <div style="display:flex; justify-content:space-between; gap:16px; align-items:flex-start; flex-wrap:wrap;">
                            <div style="flex:1;">
                                <h4>{{ $dia }}</h4>
                                <p><strong>Horario:</strong> {{ $inicio }} - {{ $fin }}</p>
                                <p><strong>Zona horaria:</strong> {{ $zona }}</p>
                                <span class="{{ $activo ? 'pill pill-success' : 'pill pill-warning' }}">
                                    {{ $activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </div>

                            <div style="flex:1; min-width:260px;">
                                <form action="{{ route('provider.horarios.update', $id) }}" method="POST" class="form-grid" style="margin-bottom:12px;">
                                    @csrf
                                    @method('PATCH')

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

                                <form action="{{ route('provider.horarios.delete', $id) }}" method="POST">
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