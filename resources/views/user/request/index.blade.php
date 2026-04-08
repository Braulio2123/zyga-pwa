@extends('user.layouts.app')

@section('content')
<section class="panel hero-panel hero-panel--compact">
    <div>
        <p class="hero-panel__eyebrow">Nueva asistencia</p>
        <h2>Solicita apoyo con precisión y rapidez.</h2>
        <p>Completa la información del servicio para iniciar la atención desde tu ubicación actual o una referencia manual.</p>
    </div>
</section>

<section id="requestBlockingState" class="stack-list"></section>

<section class="panel">
    <form id="assistanceRequestForm" class="form-grid">
        <label class="form-field form-field--full">
            <span>Servicio</span>
            <select id="requestServiceId" name="service_id" required>
                <option value="">Cargando servicios...</option>
            </select>
        </label>

        <label class="form-field form-field--full">
            <span>Vehículo</span>
            <select id="requestVehicleId" name="vehicle_id" required>
                <option value="">Cargando vehículos...</option>
            </select>
        </label>

        <label class="form-field form-field--full">
            <span>Dirección de referencia</span>
            <textarea id="requestPickupAddress" name="pickup_address" rows="3" placeholder="Ej. Av. Juárez 123, Guadalajara, Jalisco" required></textarea>
        </label>

        <label class="form-field">
            <span>Latitud</span>
            <input type="number" step="0.000001" id="requestLat" name="lat" placeholder="20.673600" required>
        </label>

        <label class="form-field">
            <span>Longitud</span>
            <input type="number" step="0.000001" id="requestLng" name="lng" placeholder="-103.344000" required>
        </label>

        <div class="form-actions form-field--full">
            <button type="button" id="requestGeoButton" class="button button--secondary">Usar mi ubicación</button>
            <button type="submit" id="requestSubmitButton" class="button button--primary">Crear asistencia</button>
        </div>
    </form>
</section>
@endsection
