@extends('user.layouts.app')

@section('content')
<section class="panel hero-panel hero-panel--compact">
    <div>
        <p class="hero-panel__eyebrow">Nueva asistencia</p>
        <h2>Genera tu solicitud de forma clara y rápida.</h2>
        <p>
            Selecciona el servicio, el vehículo involucrado y la ubicación donde necesitas apoyo.
            Una vez creada la solicitud, podrás darle seguimiento desde la sección de servicio activo.
        </p>
    </div>
</section>

<section id="requestBlockingState" class="stack-list"></section>

<section class="grid-two">
    <article class="panel">
        <div class="section-head">
            <h3>Antes de solicitar</h3>
            <span class="section-pill">Revisión</span>
        </div>

        <div class="stack-list">
            <article class="card-row">
                <h4 class="card-row__title">1. Verifica tu vehículo</h4>
                <p class="card-row__meta">
                    Debes tener al menos un vehículo registrado en tu cuenta para poder continuar.
                </p>
            </article>

            <article class="card-row">
                <h4 class="card-row__title">2. Confirma tu ubicación</h4>
                <p class="card-row__meta">
                    Puedes capturar latitud y longitud manualmente o usar la ubicación actual del dispositivo.
                </p>
            </article>

            <article class="card-row">
                <h4 class="card-row__title">3. Describe bien el punto de atención</h4>
                <p class="card-row__meta">
                    Entre más precisa sea la referencia, más fácil será para el proveedor llegar al sitio correcto.
                </p>
            </article>
        </div>
    </article>

    <article class="panel">
        <div class="section-head">
            <h3>Estado del flujo</h3>
            <span class="section-pill">MVP</span>
        </div>

        <div class="helper-note">
            Si ya tienes una solicitud activa, el sistema bloqueará la creación de otra hasta que la actual
            termine o sea cancelada.
        </div>

        <div class="stack-list">
            <article class="card-row">
                <h4 class="card-row__title">Solicitud única activa</h4>
                <p class="card-row__meta">
                    Esto evita duplicidades y mantiene ordenado el proceso entre cliente, proveedor y administración.
                </p>
            </article>

            <article class="card-row">
                <h4 class="card-row__title">Seguimiento posterior</h4>
                <p class="card-row__meta">
                    Después de crear la asistencia, el control principal pasa a la vista de servicio activo.
                </p>
            </article>
        </div>
    </article>
</section>

<section class="panel">
    <div class="section-head">
        <h3>Formulario de solicitud</h3>
        <span class="section-pill">Operación</span>
    </div>

    <form id="assistanceRequestForm" class="form-grid" autocomplete="off">
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
            <span>Dirección o referencia</span>
            <textarea
                id="requestPickupAddress"
                name="pickup_address"
                rows="4"
                placeholder="Ej. Av. Juárez 123, frente a la farmacia, Guadalajara, Jalisco"
                required
            ></textarea>
        </label>

        <label class="form-field">
            <span>Latitud</span>
            <input
                type="number"
                step="0.000001"
                id="requestLat"
                name="lat"
                placeholder="20.673600"
                required
            >
        </label>

        <label class="form-field">
            <span>Longitud</span>
            <input
                type="number"
                step="0.000001"
                id="requestLng"
                name="lng"
                placeholder="-103.344000"
                required
            >
        </label>

        <div class="form-actions form-field--full">
            <button type="button" id="requestGeoButton" class="button button--secondary">
                Usar mi ubicación
            </button>

            <button type="submit" id="requestSubmitButton" class="button button--primary">
                Crear asistencia
            </button>
        </div>
    </form>
</section>
@endsection
