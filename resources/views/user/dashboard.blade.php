@extends('user.layouts.app')

@section('title', 'Zyga | Inicio')
@section('page-title', 'Inicio')

@section('content')
    <section class="hero-card">
        <div>
            <p class="hero-kicker">Bienvenido</p>
            <h2>Hola, Cliente</h2>
            <p class="muted">
                Solicita apoyo vial, consulta el estado de tu servicio y revisa tus notificaciones.
            </p>
        </div>
        <div class="hero-badge">Cliente</div>
    </section>

    <section class="section-block">
        <div class="section-head">
            <h3>Servicios disponibles</h3>
            <span class="pill">4 activos</span>
        </div>

        <div class="services-grid">
            <article class="service-card">
                <div class="service-icon">🚚</div>
                <h4>Servicio de grúa</h4>
                <p>Solicita apoyo de arrastre o traslado de tu vehículo en caso de avería o accidente.</p>
            </article>

            <article class="service-card">
                <div class="service-icon">🛞</div>
                <h4>Cambio de llanta</h4>
                <p>Recibe asistencia para sustituir una llanta ponchada o dañada en tu ubicación actual.</p>
            </article>

            <article class="service-card">
                <div class="service-icon">⛽</div>
                <h4>Combustible</h4>
                <p>Solicita envío de combustible cuando tu vehículo se quede sin gasolina en el camino.</p>
            </article>

            <article class="service-card">
                <div class="service-icon">🔋</div>
                <h4>Paso de corriente</h4>
                <p>Obtén ayuda para encender tu vehículo cuando la batería se haya descargado.</p>
            </article>
        </div>
    </section>

    <section class="section-block">
        <div class="section-head">
            <h3>Solicitar asistencia</h3>
            <span class="pill pill-warning">Vista previa</span>
        </div>

        <div class="panel-card form-grid">
            <div class="form-field">
                <label for="service_id">Servicio</label>
                <select id="service_id" name="service_id">
                    <option value="">Selecciona un servicio</option>
                    <option value="1">Servicio de grúa</option>
                    <option value="2">Cambio de llanta</option>
                    <option value="3">Combustible</option>
                    <option value="4">Paso de corriente</option>
                </select>
            </div>

            <div class="form-field">
                <label for="vehicle_id">Vehículo</label>
                <select id="vehicle_id" name="vehicle_id">
                    <option value="">Selecciona un vehículo</option>
                    <option value="1">Mazda 2 Hatchback · JKL-123-A</option>
                    <option value="2">Nissan Versa · ABC-456-Z</option>
                </select>
            </div>

            <div class="form-field form-field-full">
                <label for="pickup_address">Dirección de auxilio</label>
                <input
                    id="pickup_address"
                    type="text"
                    name="pickup_address"
                    placeholder="Ej. Av. Vallarta 1350, Guadalajara, Jalisco"
                >
            </div>

            <div class="form-field">
                <label for="lat">Latitud</label>
                <input
                    id="lat"
                    type="number"
                    step="0.000001"
                    name="lat"
                    placeholder="20.673600"
                >
            </div>

            <div class="form-field">
                <label for="lng">Longitud</label>
                <input
                    id="lng"
                    type="number"
                    step="0.000001"
                    name="lng"
                    placeholder="-103.344000"
                >
            </div>

            <div class="form-actions form-field-full">
                <button type="button" class="btn-primary">Crear solicitud</button>
            </div>
        </div>
    </section>

    <section class="section-block">
        <div class="section-head">
            <h3>Solicitud en proceso</h3>
            <span class="pill pill-success">ASIGNADA</span>
        </div>

        <article class="panel-card request-card">
            <div class="request-main">
                <h4>Servicio de grúa</h4>
                <p class="muted">Folio: ZYGA-2026-0001</p>
                <p><strong>Vehículo:</strong> Mazda 2 Hatchback · JKL-123-A</p>
                <p><strong>Dirección:</strong> Av. Vallarta 1350, Guadalajara, Jalisco</p>
                <p><strong>Proveedor:</strong> Grúas Express GDL</p>
            </div>

            <button type="button" class="btn-secondary">Cancelar solicitud</button>
        </article>
    </section>

    <section class="section-block">
        <div class="section-head">
            <h3>Notificaciones recientes</h3>
            <span class="pill">3</span>
        </div>

        <div class="stack-list">
            <article class="list-card">
                <h4>Solicitud aceptada</h4>
                <p>Tu solicitud de asistencia fue aceptada y un operador va en camino.</p>
                <span class="meta-text">17/03/2026 10:30</span>
            </article>

            <article class="list-card">
                <h4>Proveedor asignado</h4>
                <p>Se asignó a Grúas Express GDL para atender tu servicio.</p>
                <span class="meta-text">17/03/2026 10:34</span>
            </article>

            <article class="list-card">
                <h4>Actualización de estado</h4>
                <p>El apoyo vial está próximo a llegar a tu ubicación.</p>
                <span class="meta-text">17/03/2026 10:42</span>
            </article>
        </div>
    </section>
@endsection