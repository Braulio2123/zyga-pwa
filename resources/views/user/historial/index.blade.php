@extends('user.layouts.app')

@section('content')
<section class="panel hero-panel hero-panel--compact">
    <div>
        <p class="hero-panel__eyebrow">Historial</p>
        <h2>Consulta tus asistencias cerradas y los pagos asociados.</h2>
        <p>
            Esta sección concentra el cierre del flujo del cliente: solicitudes finalizadas o canceladas,
            junto con los pagos que ya fueron registrados en el sistema.
        </p>
    </div>
</section>

<section class="panel">
    <div class="section-head">
        <h3>Qué encontrarás aquí</h3>
        <span class="section-pill">Resumen</span>
    </div>

    <div class="stack-list">
        <article class="card-row">
            <h4 class="card-row__title">Solicitudes cerradas</h4>
            <p class="card-row__meta">
                Verás asistencias completadas o canceladas, ya fuera del flujo activo.
            </p>
        </article>

        <article class="card-row">
            <h4 class="card-row__title">Pagos relacionados</h4>
            <p class="card-row__meta">
                También se mostrarán los pagos registrados para que tengas trazabilidad del cierre financiero.
            </p>
        </article>

        <article class="card-row">
            <h4 class="card-row__title">Consulta ordenada</h4>
            <p class="card-row__meta">
                Esta vista sirve como referencia rápida para revisar tu actividad previa sin mezclarla con el servicio activo.
            </p>
        </article>
    </div>
</section>

<section class="grid-two">
    <article class="panel">
        <div class="section-head">
            <h3>Asistencias cerradas</h3>
            <span class="section-pill">Finalizadas / canceladas</span>
        </div>

        <div class="helper-note">
            Aquí se listan únicamente solicitudes que ya no forman parte del flujo activo del cliente.
        </div>

        <div id="historyRequestsList" class="stack-list">
            <article class="empty-state">Cargando solicitudes cerradas...</article>
        </div>
    </article>

    <article class="panel">
        <div class="section-head">
            <h3>Pagos registrados</h3>
            <a href="{{ route('user.pagos') }}" class="text-link">Abrir pagos</a>
        </div>

        <div class="helper-note">
            Este bloque muestra los pagos disponibles en tu historial, vinculados al ciclo de asistencias completadas.
        </div>

        <div id="historyPaymentsList" class="stack-list">
            <article class="empty-state">Cargando pagos del historial...</article>
        </div>
    </article>
</section>
@endsection
