@extends('user.layouts.app')

@section('title', 'Zyga | Cuenta')
@section('page-title', 'Mi cuenta')

@section('content')
    <section class="section-block">
        <div class="section-head">
            <h3>Perfil</h3>
            <span class="pill">Cuenta</span>
        </div>

        <form action="#" method="POST" class="panel-card form-grid">
            <div class="form-field">
                <label for="name">Nombre</label>
                <input id="name" type="text" name="name" value="Shrek Flores Guzman" required>
            </div>

            <div class="form-field">
                <label for="email">Correo</label>
                <input id="email" type="email" name="email" value="shrek@zyga.com" required>
            </div>

            <div class="form-field form-field-full">
                <label for="password">Nueva contraseña</label>
                <input id="password" type="password" name="password" placeholder="Opcional, mínimo 8 caracteres">
            </div>

            <div class="form-actions form-field-full">
                <button type="button" class="btn-primary">Actualizar perfil</button>
            </div>
        </form>
    </section>

    <section class="section-block">
        <div class="section-head">
            <h3>Registrar vehículo</h3>
            <span class="pill">2 vehículos</span>
        </div>

        <form action="#" method="POST" class="panel-card form-grid">
            <div class="form-field">
                <label for="vehicle_type_id">Tipo de vehículo</label>
                <select id="vehicle_type_id" name="vehicle_type_id">
                    <option value="">Selecciona un tipo</option>
                    <option value="1">Sedán</option>
                    <option value="2">Hatchback</option>
                    <option value="3">SUV</option>
                    <option value="4">Motocicleta</option>
                </select>
            </div>

            <div class="form-field">
                <label for="plate">Placa</label>
                <input id="plate" type="text" name="plate" value="">
            </div>

            <div class="form-field">
                <label for="brand">Marca</label>
                <input id="brand" type="text" name="brand" value="">
            </div>

            <div class="form-field">
                <label for="model">Modelo</label>
                <input id="model" type="text" name="model" value="">
            </div>

            <div class="form-field">
                <label for="year">Año</label>
                <input id="year" type="number" name="year" value="" min="1900" max="2100">
            </div>

            <div class="form-actions form-field-full">
                <button type="button" class="btn-primary">Guardar vehículo</button>
            </div>
        </form>
    </section>

    <section class="section-block">
        <div class="section-head">
            <h3>Mis vehículos</h3>
            <span class="pill">Edición rápida</span>
        </div>

        <div class="stack-list">
            <form action="#" method="POST" class="panel-card form-grid">
                <div class="form-field">
                    <label>Tipo</label>
                    <select name="vehicle_type_id">
                        <option selected>Hatchback</option>
                        <option>Sedán</option>
                        <option>SUV</option>
                        <option>Motocicleta</option>
                    </select>
                </div>

                <div class="form-field">
                    <label>Placa</label>
                    <input type="text" name="plate" value="JKL-123-A">
                </div>

                <div class="form-field">
                    <label>Marca</label>
                    <input type="text" name="brand" value="Mazda">
                </div>

                <div class="form-field">
                    <label>Modelo</label>
                    <input type="text" name="model" value="Mazda 2">
                </div>

                <div class="form-field">
                    <label>Año</label>
                    <input type="number" name="year" value="2020" min="1900" max="2100">
                </div>

                <div class="form-actions form-field-full inline-between gap-12">
                    <div class="meta-text">Hatchback</div>
                    <button type="button" class="btn-secondary">Actualizar vehículo</button>
                </div>
            </form>

            <form action="#" method="POST" class="panel-card form-grid">
                <div class="form-field">
                    <label>Tipo</label>
                    <select name="vehicle_type_id">
                        <option>Hatchback</option>
                        <option selected>Sedán</option>
                        <option>SUV</option>
                        <option>Motocicleta</option>
                    </select>
                </div>

                <div class="form-field">
                    <label>Placa</label>
                    <input type="text" name="plate" value="ABC-456-Z">
                </div>

                <div class="form-field">
                    <label>Marca</label>
                    <input type="text" name="brand" value="Nissan">
                </div>

                <div class="form-field">
                    <label>Modelo</label>
                    <input type="text" name="model" value="Versa">
                </div>

                <div class="form-field">
                    <label>Año</label>
                    <input type="number" name="year" value="2022" min="1900" max="2100">
                </div>

                <div class="form-actions form-field-full inline-between gap-12">
                    <div class="meta-text">Sedán</div>
                    <button type="button" class="btn-secondary">Actualizar vehículo</button>
                </div>
            </form>
        </div>
    </section>

    <section class="section-block">
        <div class="section-head">
            <h3>Seguimiento del servicio</h3>
            <span class="pill pill-success">EN CAMINO</span>
        </div>

        <article class="panel-card">
            <h4>Solicitud de combustible</h4>
            <p class="muted">Folio ZYGA-2026-0003</p>
            <p><strong>Proveedor:</strong> Servicio Vial 360</p>
            <p><strong>Dirección:</strong> Av. Patria 2401, Guadalajara, Jalisco</p>

            <div class="timeline">
                <div class="timeline-item done">
                    <span class="timeline-dot"></span>
                    <div>
                        <strong>SOLICITUD ACEPTADA</strong>
                        <p>17/03/2026 09:40</p>
                    </div>
                </div>

                <div class="timeline-item done">
                    <span class="timeline-dot"></span>
                    <div>
                        <strong>APOYO EN CAMINO</strong>
                        <p>17/03/2026 09:48</p>
                    </div>
                </div>

                <div class="timeline-item done">
                    <span class="timeline-dot"></span>
                    <div>
                        <strong>PRÓXIMO A LLEGAR</strong>
                        <p>17/03/2026 09:55</p>
                    </div>
                </div>
            </div>
        </article>
    </section>
@endsection