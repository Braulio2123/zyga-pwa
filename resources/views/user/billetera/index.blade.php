@extends('user.layouts.app')

@section('content')
<section class="panel hero-panel hero-panel--compact">
    <div>
        <p class="hero-panel__eyebrow">Pagos y métodos</p>
        <h2>Concentra aquí tus formas de pago y el cierre financiero de tus servicios.</h2>
        <p>Esta sección sustituye el concepto anterior de billetera. Aquí gestionas métodos guardados y registras pagos de asistencias concluidas.</p>
    </div>
</section>

<section class="panel">
    <div class="section-head">
        <h3>Cómo opera esta sección</h3>
        <span class="section-pill">MVP</span>
    </div>
    <div class="stack-list">
        <article class="card-row">
            <h4 class="card-row__title">Métodos guardados</h4>
            <p class="card-row__meta">Sirven para dejar preparada tu cuenta y registrar pagos posteriores de forma más rápida.</p>
        </article>
        <article class="card-row">
            <h4 class="card-row__title">Pagos realizados</h4>
            <p class="card-row__meta">Muestran los cargos ya registrados sobre solicitudes completadas.</p>
        </article>
        <article class="card-row">
            <h4 class="card-row__title">Registro de pago</h4>
            <p class="card-row__meta">Solo aparecerán como elegibles las asistencias concluidas que aún no tengan pago completado.</p>
        </article>
    </div>
</section>

<section class="grid-two">
    <article class="panel">
        <div class="section-head">
            <h3>Métodos guardados</h3>
            <span class="section-pill">Cuenta</span>
        </div>
        <div id="paymentMethodsList" class="stack-list"></div>
    </article>

    <article class="panel">
        <div class="section-head">
            <h3>Pagos registrados</h3>
            <span class="section-pill">Historial</span>
        </div>
        <div id="paymentsList" class="stack-list"></div>
    </article>
</section>

<section class="grid-two">
    <article class="panel">
        <div class="section-head">
            <h3>Agregar método de pago</h3>
            <span class="section-pill">Nuevo</span>
        </div>
        <form id="paymentMethodForm" class="form-grid">
            <label class="form-field form-field--full">
                <span>Tipo de método</span>
                <select id="paymentMethodType" name="method_name" required>
                    <option value="">Cargando tipos...</option>
                </select>
            </label>
            <label class="form-field form-field--full">
                <span>Detalle</span>
                <input type="text" name="method_details" id="paymentMethodDetails" placeholder="Ej. Terminación 4242" required>
            </label>
            <div class="form-actions form-field--full">
                <button type="submit" class="button button--primary">Guardar método</button>
            </div>
        </form>
    </article>

    <article class="panel">
        <div class="section-head">
            <h3>Registrar pago</h3>
            <span class="section-pill">Solicitud concluida</span>
        </div>
        <form id="paymentRegisterForm" class="form-grid">
            <label class="form-field form-field--full">
                <span>Solicitud completada</span>
                <select id="paymentRequestId" name="assistance_request_id" required>
                    <option value="">Cargando solicitudes elegibles...</option>
                </select>
            </label>
            <label class="form-field">
                <span>Método de pago</span>
                <select id="paymentRegisterMethod" name="payment_method" required>
                    <option value="">Selecciona método</option>
                </select>
            </label>
            <label class="form-field">
                <span>Monto</span>
                <input type="number" step="0.01" min="0.01" name="amount" id="paymentAmount" placeholder="850.00" required>
            </label>
            <div class="form-actions form-field--full">
                <button type="submit" class="button button--primary">Registrar pago</button>
            </div>
        </form>
    </article>
</section>
@endsection
