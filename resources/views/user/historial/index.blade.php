@extends('user.layouts.app')

@section('content')
<section class="panel hero-panel hero-panel--compact">
    <div>
        <p class="hero-panel__eyebrow">Historial</p>
        <h2>Consulta tus servicios y pagos con una lectura ordenada.</h2>
        <p>Ten a la mano el registro de asistencias cerradas y los movimientos relacionados con tu cuenta.</p>
    </div>
</section>

<section class="grid-two">
    <article class="panel">
        <div class="section-head">
            <h3>Asistencias cerradas</h3>
            <span class="section-pill">Finalizadas / canceladas</span>
        </div>
        <div id="historyRequestsList" class="stack-list"></div>
    </article>

    <article class="panel">
        <div class="section-head">
            <h3>Pagos registrados</h3>
            <a href="{{ route('user.pagos') }}" class="text-link">Abrir pagos</a>
        </div>
        <div id="historyPaymentsList" class="stack-list"></div>
    </article>
</section>
@endsection
