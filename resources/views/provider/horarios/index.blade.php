@extends('provider.layouts.app')

@section('title', 'ZYGA | Horarios provider')
@section('page-title', 'Horarios')

@section('content')
    <section class="hero-card">
        <div>
            <p class="hero-kicker">Paso 3 de 3</p>
            <h2 style="margin:0 0 8px;">Disponibilidad operativa</h2>
            <p class="muted">Configura un horario por día para que la API conozca tu disponibilidad.</p>
        </div>
        <div class="hero-stat summary-card">
            <span class="helper-text">Registros</span>
            <strong>{{ count($schedules) }}</strong>
        </div>
    </section>

    @if(!$hasProfile)
        <section class="locked-module">
            <h3>Módulo bloqueado temporalmente</h3>
            <p>Primero debes crear tu perfil de proveedor para poder registrar horarios.</p>
            <a href="{{ route('provider.perfil') }}" class="btn-primary">Ir a crear perfil</a>
        </section>
    @else
        <section class="section-card">
            <div class="section-head">
                <div>
                    <p class="dashboard-card__eyebrow">Nuevo horario</p>
                    <h3>Agregar disponibilidad</h3>
                </div>
            </div>

            <form action="{{ route('provider.horarios.store') }}" method="POST" class="form-grid">
                @csrf
                <div class="form-field">
                    <label for="day_of_week" class="label">Día</label>
                    <select name="day_of_week" id="day_of_week" required>
                        <option value="">Selecciona un día</option>
                        @foreach($dayOptions as $value => $label)
                            <option value="{{ $value }}" {{ (string) old('day_of_week') === (string) $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-field">
                    <label for="start_time" class="label">Hora de inicio</label>
                    <input type="time" name="start_time" id="start_time" value="{{ old('start_time') }}" required>
                </div>
                <div class="form-field">
                    <label for="end_time" class="label">Hora de fin</label>
                    <input type="time" name="end_time" id="end_time" value="{{ old('end_time') }}" required>
                </div>
                <div class="form-field" style="align-self:end;">
                    <button type="submit" class="btn-primary">Guardar horario</button>
                </div>
            </form>
        </section>

        <section class="section-card">
            <div class="section-head">
                <div>
                    <p class="dashboard-card__eyebrow">Disponibilidad cargada</p>
                    <h3>Horarios actuales</h3>
                </div>
            </div>

            @if(empty($schedules))
                <div class="empty-state">
                    <h4>Aún no tienes horarios registrados</h4>
                    <p>Agrega al menos un horario para mostrar tu disponibilidad operativa.</p>
                </div>
            @else
                <div class="stack-list">
                    @foreach($schedules as $schedule)
                        <article class="list-card">
                            <div class="list-card-grid">
                                <div>
                                    <h4>{{ $dayOptions[(int) ($schedule['day_of_week'] ?? 0)] ?? 'Día no definido' }}</h4>
                                    <p>{{ $schedule['start_time'] ?? '--:--' }} - {{ $schedule['end_time'] ?? '--:--' }}</p>
                                </div>

                                <div class="list-card-actions">
                                    <form action="{{ route('provider.horarios.update', $schedule['id']) }}" method="POST" class="inline-form">
                                        @csrf
                                        @method('PATCH')
                                        <input type="time" name="start_time" value="{{ $schedule['start_time'] ?? '' }}" required>
                                        <input type="time" name="end_time" value="{{ $schedule['end_time'] ?? '' }}" required>
                                        <button type="submit" class="btn-primary btn-sm">Actualizar</button>
                                    </form>
                                </div>

                                <div>
                                    <form action="{{ route('provider.horarios.delete', $schedule['id']) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-secondary btn-sm">Eliminar</button>
                                    </form>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>
    @endif
@endsection
