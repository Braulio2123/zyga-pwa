@extends('user.layouts.app')

@section('title', 'Zyga | Historial')
@section('page-title', 'Historial de servicios')

@section('content')
    <section class="section-block">
        <div class="section-head">
            <h3>Pagos registrados</h3>
            <span class="pill">2</span>
        </div>

        <div class="stack-list">
            <article class="history-card">
                <div class="history-card__left">
                    <h4>Cambio de llanta</h4>
                    <ul>
                        <li><strong>Vehículo:</strong> Mazda 2 Hatchback</li>
                        <li><strong>Método:</strong> Tarjeta débito</li>
                        <li><strong>Transacción:</strong> TXN-000245</li>
                        <li><strong>Estatus:</strong> Pagado</li>
                    </ul>
                </div>
                <div class="history-card__right">
                    <span class="meta-text">10/04/2025</span>
                    <strong class="history-amount">$360.00 MXN</strong>
                </div>
            </article>

            <article class="history-card">
                <div class="history-card__left">
                    <h4>Servicio de grúa</h4>
                    <ul>
                        <li><strong>Vehículo:</strong> Nissan Versa</li>
                        <li><strong>Método:</strong> Tarjeta crédito</li>
                        <li><strong>Transacción:</strong> TXN-000246</li>
                        <li><strong>Estatus:</strong> Pagado</li>
                    </ul>
                </div>
                <div class="history-card__right">
                    <span class="meta-text">25/11/2024</span>
                    <strong class="history-amount">$799.00 MXN</strong>
                </div>
            </article>
        </div>
    </section>

    <section class="section-block">
        <div class="section-head">
            <h3>Solicitudes realizadas</h3>
            <span class="pill">2</span>
        </div>

        <div class="stack-list">
            <article class="history-card">
                <div class="history-card__left">
                    <h4>Cambio de llanta</h4>
                    <ul>
                        <li><strong>Folio:</strong> ZYGA-2025-0002</li>
                        <li><strong>Vehículo:</strong> Mazda 2 Hatchback · JKL-123-A</li>
                        <li><strong>Dirección:</strong> Av. Vallarta 1350, Guadalajara, Jalisco</li>
                        <li><strong>Proveedor:</strong> Servicio Vial 360</li>
                    </ul>
                </div>
                <div class="history-card__right">
                    <span class="meta-text">10/04/2025</span>
                    <strong class="status-tag">FINALIZADO</strong>
                </div>
            </article>

            <article class="history-card">
                <div class="history-card__left">
                    <h4>Solicitud de grúa</h4>
                    <ul>
                        <li><strong>Folio:</strong> ZYGA-2024-0001</li>
                        <li><strong>Vehículo:</strong> Nissan Versa · ABC-456-Z</li>
                        <li><strong>Dirección:</strong> Periférico Sur, Guadalajara, Jalisco</li>
                        <li><strong>Proveedor:</strong> Grúas Express GDL</li>
                    </ul>
                </div>
                <div class="history-card__right">
                    <span class="meta-text">25/11/2024</span>
                    <strong class="status-tag">FINALIZADO</strong>
                </div>
            </article>
        </div>
    </section>
@endsection