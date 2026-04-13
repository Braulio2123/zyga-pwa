@extends('user.layouts.app')

@section('content')
<section class="panel hero-panel hero-panel--compact">
    <div>
        <p class="hero-panel__eyebrow">Servicio activo</p>
        <h2>Da seguimiento a tu asistencia en tiempo real.</h2>
        <p>
            Aquí podrás consultar el estado actual de tu solicitud, revisar el timeline del servicio
            y cancelar únicamente cuando el flujo todavía lo permita.
        </p>
    </div>
</section>

<section class="grid-two">
    <article class="panel">
        <div class="section-head">
            <h3>Resumen del servicio</h3>
            <span class="section-pill">Seguimiento</span>
        </div>
        <div class="helper-note">
            Este bloque muestra la solicitud activa del cliente y su estado más reciente recuperado desde la API.
        </div>
        <div id="activeRequestSummary" class="stack-list">
            <article class="empty-state">Buscando tu solicitud activa...</article>
        </div>
    </article>

    <article class="panel">
        <div class="section-head">
            <h3>Consideraciones operativas</h3>
            <span class="section-pill">Flujo</span>
        </div>

        <div class="stack-list">
            <article class="card-row">
                <h4 class="card-row__title">Una solicitud activa a la vez</h4>
                <p class="card-row__meta">
                    Mientras esta asistencia siga abierta, el sistema no permitirá generar una nueva.
                </p>
            </article>

            <article class="card-row">
                <h4 class="card-row__title">Actualización manual disponible</h4>
                <p class="card-row__meta">
                    Puedes refrescar la vista para consultar cambios recientes del proveedor o del proceso.
                </p>
            </article>

            <article class="card-row">
                <h4 class="card-row__title">Cancelación controlada</h4>
                <p class="card-row__meta">
                    La cancelación solo debe usarse cuando el estado actual de la solicitud todavía lo permita.
                </p>
            </article>
        </div>
    </article>
</section>

<section class="panel">
    <div class="section-head">
        <h3>Timeline del servicio</h3>
        <button type="button" id="activeReloadButton" class="button button--ghost">Actualizar</button>
    </div>

    <div class="helper-note">
        El timeline refleja los movimientos registrados por la operación: creación, aceptación, asignación,
        avance, llegada al sitio, finalización o cancelación.
    </div>

    <div id="activeTimeline" class="timeline-list">
        <article class="empty-state">Cargando movimientos del servicio...</article>
    </div>
</section>

<section class="panel">
    <div class="section-head">
        <h3>Cancelar solicitud</h3>
        <span class="section-pill">Gestión</span>
    </div>

    <div class="helper-note">
        Si decides cancelar, registra un motivo breve y claro. La API validará si la cancelación
        todavía está permitida según el estado actual de la asistencia.
    </div>

    <form id="cancelRequestForm" class="form-grid" autocomplete="off">
        <label class="form-field form-field--full">
            <span>Motivo de cancelación</span>
            <textarea
                id="cancelReason"
                name="cancel_reason"
                rows="3"
                placeholder="Ej. Ya no requiero el servicio o resolví la situación por otro medio"
            ></textarea>
        </label>

        <div class="form-actions form-field--full">
            <button type="submit" id="cancelSubmitButton" class="button button--danger">
                Cancelar solicitud
            </button>
        </div>
    </form>
</section>
@endsection
