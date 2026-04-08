@extends('user.layouts.app')

@section('content')
<section class="panel hero-panel hero-panel--compact">
    <div>
        <p class="hero-panel__eyebrow">Seguimiento activo</p>
        <h2>Observa el avance del servicio en una vista limpia y directa.</h2>
        <p>Consulta el estado actual, revisa el historial de movimiento y actúa solo cuando sea necesario.</p>
    </div>
</section>

<section id="activeRequestSummary" class="panel empty-state">Buscando tu solicitud activa...</section>

<section class="panel">
    <div class="section-head">
        <h3>Timeline</h3>
        <button type="button" id="activeReloadButton" class="button button--ghost">Actualizar</button>
    </div>
    <div id="activeTimeline" class="timeline-list"></div>
</section>

<section class="panel">
    <div class="section-head">
        <h3>Cancelar solicitud</h3>
        <span class="section-pill">Gestión</span>
    </div>
    <form id="cancelRequestForm" class="form-grid">
        <label class="form-field form-field--full">
            <span>Motivo de cancelación</span>
            <textarea id="cancelReason" name="cancel_reason" rows="3" placeholder="Ej. Ya no requiero el servicio"></textarea>
        </label>
        <div class="form-actions form-field--full">
            <button type="submit" id="cancelSubmitButton" class="button button--danger">Cancelar solicitud</button>
        </div>
    </form>
</section>
@endsection
