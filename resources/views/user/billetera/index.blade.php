@extends('user.layouts.app')

@section('content')
<section class="panel hero-panel hero-panel--compact">
    <div>
        <p class="hero-panel__eyebrow">Pagos y métodos</p>
        <h2>Gestiona tus métodos de pago y registra el cierre financiero de tus asistencias.</h2>
        <p>
            Aquí puedes guardar métodos de pago para futuras operaciones y registrar pagos asociados
            a solicitudes ya concluidas.
        </p>
    </div>
</section>

<section class="panel">
    <div class="section-head">
        <h3>Cómo funciona esta sección</h3>
        <span class="section-pill">MVP</span>
    </div>

    <div class="stack-list">
        <article class="card-row">
            <h4 class="card-row__title">Métodos guardados</h4>
            <p class="card-row__meta">
                Te permiten dejar tu cuenta preparada para registrar pagos de forma más rápida.
            </p>
        </article>

        <article class="card-row">
            <h4 class="card-row__title">Pagos registrados</h4>
            <p class="card-row__meta">
                Muestran los pagos ya vinculados a solicitudes finalizadas dentro del flujo del cliente.
            </p>
        </article>

        <article class="card-row">
            <h4 class="card-row__title">Solicitudes elegibles</h4>
            <p class="card-row__meta">
                Solo podrás registrar pagos sobre asistencias completadas que aún no tengan un pago final registrado.
            </p>
        </article>
    </div>
</section>

<section class="grid-two">
    <article class="panel">
        <div class="section-head">
            <h3>Métodos guardados</h3>
            <span class="section-pill">Cuenta</span>
        </div>

        <div class="helper-note">
            Los métodos guardados se cargan desde la API y quedan disponibles para usarse posteriormente.
        </div>

        <div id="paymentMethodsList" class="stack-list">
            <article class="empty-state">Cargando métodos de pago...</article>
        </div>
    </article>

    <article class="panel">
        <div class="section-head">
            <h3>Pagos registrados</h3>
            <span class="section-pill">Historial</span>
        </div>

        <div class="helper-note">
            Aquí se muestran los pagos que ya fueron registrados por el cliente dentro del sistema.
        </div>

        <div id="paymentsList" class="stack-list">
            <article class="empty-state">Cargando pagos...</article>
        </div>
    </article>
</section>

<section class="grid-two">
    <article class="panel">
        <div class="section-head">
            <h3>Agregar método de pago</h3>
            <span class="section-pill">Nuevo</span>
        </div>

        <form id="paymentMethodForm" class="form-grid" autocomplete="off">
            <label class="form-field form-field--full">
                <span>Tipo de método</span>
                <select id="paymentMethodType" name="method_name" required>
                    <option value="">Cargando tipos...</option>
                </select>
            </label>

            <label class="form-field form-field--full">
                <span>Detalle</span>
                <input
                    type="text"
                    name="method_details"
                    id="paymentMethodDetails"
                    placeholder="Ej. Terminación 4242"
                    required
                >
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

        <form id="paymentRegisterForm" class="form-grid" autocomplete="off">
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
                <input
                    type="number"
                    step="0.01"
                    min="0.01"
                    name="amount"
                    id="paymentAmount"
                    placeholder="850.00"
                    required
                >
            </label>

            <div class="form-actions form-field--full">
                <button type="submit" class="button button--primary">Registrar pago</button>
            </div>
        </form>
    </article>
</section>
@endsection
