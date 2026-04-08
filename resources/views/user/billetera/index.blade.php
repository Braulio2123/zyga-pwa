@extends('user.layouts.app')

@section('content')
<section class="panel hero-panel hero-panel--compact">
    <div>
        <p class="hero-panel__eyebrow">Pagos</p>
        <h2>Administra tus métodos y registra movimientos con una vista ejecutiva.</h2>
        <p>Conserva el control de tus formas de pago y de los cargos ligados a servicios concluidos.</p>
    </div>
</section>

<section class="grid-two">
    <article class="panel">
        <div class="section-head">
            <h3>Métodos guardados</h3>
            <span class="section-pill">Cliente</span>
        </div>
        <div id="paymentMethodsList" class="stack-list"></div>
    </article>

    <article class="panel">
        <div class="section-head">
            <h3>Pagos realizados</h3>
            <span class="section-pill">Registro</span>
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
