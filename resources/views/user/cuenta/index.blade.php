@extends('user.layouts.app')

@section('content')
<section class="panel hero-panel hero-panel--compact">
    <div>
        <p class="hero-panel__eyebrow">Cuenta</p>
        <h2>Perfil, seguridad y vehículos en un mismo espacio.</h2>
        <p>Mantén actualizada tu información principal y prepara tus vehículos para solicitar atención con mayor rapidez.</p>
    </div>
</section>

<section class="grid-two">
    <article class="panel">
        <div class="section-head">
            <h3>Perfil</h3>
            <span class="section-pill">Identidad</span>
        </div>
        <div id="accountProfileCard" class="stack-list"></div>
    </article>

    <article class="panel">
        <div class="section-head">
            <h3>Vehículos registrados</h3>
            <span class="section-pill">Movilidad</span>
        </div>
        <div class="helper-note">
            Mantén al menos un vehículo disponible para agilizar tus próximas solicitudes.
        </div>
        <div id="accountVehiclesList" class="stack-list"></div>
    </article>
</section>

<section class="grid-two">
    <article class="panel">
        <div class="section-head">
            <h3>Actualizar correo</h3>
            <span class="section-pill">Cuenta</span>
        </div>
        <form id="accountEmailForm" class="form-grid">
            <label class="form-field form-field--full">
                <span>Correo electrónico</span>
                <input type="email" id="accountEmailInput" name="email" placeholder="cliente@zyga.com" required>
            </label>
            <div class="form-actions form-field--full">
                <button type="submit" class="button button--primary">Guardar correo</button>
            </div>
        </form>
    </article>

    <article class="panel">
        <div class="section-head">
            <h3>Cambiar contraseña</h3>
            <span class="section-pill">Seguridad</span>
        </div>
        <form id="accountPasswordForm" class="form-grid">
            <label class="form-field form-field--full">
                <span>Nueva contraseña</span>
                <input type="password" name="password" id="accountPasswordInput" minlength="8" placeholder="Mínimo 8 caracteres" required>
            </label>
            <div class="form-actions form-field--full">
                <button type="submit" class="button button--secondary">Actualizar contraseña</button>
            </div>
        </form>
    </article>
</section>

<section class="panel">
    <div class="section-head">
        <h3>Agregar o editar vehículo</h3>
        <button type="button" id="vehicleFormReset" class="button button--ghost">Nuevo</button>
    </div>
    <form id="vehicleForm" class="form-grid">
        <input type="hidden" name="vehicle_id" id="vehicleIdInput">
        <label class="form-field">
            <span>Tipo de vehículo</span>
            <select name="vehicle_type_id" id="vehicleTypeInput" required>
                <option value="">Selecciona tipo</option>
            </select>
        </label>
        <label class="form-field">
            <span>Placas</span>
            <input type="text" name="plate" id="vehiclePlateInput" placeholder="JAL-458-B" required>
        </label>
        <label class="form-field">
            <span>Marca</span>
            <input type="text" name="brand" id="vehicleBrandInput" placeholder="Nissan" required>
        </label>
        <label class="form-field">
            <span>Modelo</span>
            <input type="text" name="model" id="vehicleModelInput" placeholder="Versa" required>
        </label>
        <label class="form-field form-field--full">
            <span>Año</span>
            <input type="number" name="year" id="vehicleYearInput" min="1900" max="2100" placeholder="2021">
        </label>
        <div class="form-actions form-field--full">
            <button type="submit" id="vehicleSubmitButton" class="button button--primary">Guardar vehículo</button>
        </div>
    </form>
</section>
@endsection
