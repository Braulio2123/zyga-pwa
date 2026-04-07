@extends('provider.layouts.app')

@section('title', 'Zyga | Proveedor')
@section('page-title', 'Inicio')

@section('content')

@php
    $popupRequest = [
        'service' => 'Servicio de grúa',
        'location' => 'Av. Vallarta, Guadalajara',
        'time' => 'Hace 2 minutos',
        'folio' => 'ZYGA-P-0021',
    ];
@endphp

<div class="floating-request-card" id="requestPopup">
    <div class="floating-request-card__top">
        <div class="floating-request-card__icon">🚨</div>
        <button type="button" class="floating-request-card__close" onclick="closeRequestPopup()">×</button>
    </div>

    <p class="floating-request-card__eyebrow">Nueva solicitud disponible</p>
    <h3>{{ $popupRequest['service'] }}</h3>
    <p class="muted"><strong>Ubicación:</strong> {{ $popupRequest['location'] }}</p>
    <p class="muted"><strong>Folio:</strong> {{ $popupRequest['folio'] }}</p>
    <span class="meta-text">{{ $popupRequest['time'] }}</span>

    <div class="floating-request-card__actions">
        <a href="{{ route('provider.asistencias') }}" class="btn-primary">Ver solicitud</a>
        <button type="button" class="btn-secondary" onclick="closeRequestPopup()">Cerrar</button>
    </div>
</div>

<section class="hero-card">
    <div>
        <p class="hero-kicker">Bienvenido</p>
        <h2>Hola, Grúas Express GDL</h2>
        <p class="muted">
            Administra tus servicios, revisa solicitudes disponibles y gestiona tus asistencias.
        </p>
    </div>
    <div class="hero-badge">Proveedor</div>
</section>

<section class="section-block">
    <div class="section-head">
        <h3>Acciones rápidas</h3>
    </div>

    <div class="services-grid">

        <a href="{{ route('provider.perfil') }}" class="service-card">
            <div class="service-icon">👤</div>
            <h4>Perfil</h4>
            <p>Consulta y actualiza tus datos como proveedor.</p>
        </a>

        <a href="{{ route('provider.servicios') }}" class="service-card">
            <div class="service-icon">🛠️</div>
            <h4>Servicios</h4>
            <p>Administra los servicios que ofreces.</p>
        </a>

        <a href="{{ route('provider.horarios') }}" class="service-card">
            <div class="service-icon">🕒</div>
            <h4>Horarios</h4>
            <p>Configura tu disponibilidad.</p>
        </a>

        <a href="{{ route('provider.documentos') }}" class="service-card">
            <div class="service-icon">📄</div>
            <h4>Documentos</h4>
            <p>Gestiona tus documentos.</p>
        </a>

    </div>
</section>

<section class="section-block">
    <div class="section-head">
        <h3>Solicitudes disponibles</h3>
        <span class="pill">En tiempo real</span>
    </div>

    <div class="stack-list">

        <article class="list-card">
            <h4>Servicio de grúa</h4>
            <p>Ubicación: Av. Vallarta, Guadalajara</p>
            <span class="meta-text">Hace 2 minutos</span>
        </article>

        <article class="list-card">
            <h4>Cambio de llanta</h4>
            <p>Ubicación: Chapultepec, Guadalajara</p>
            <span class="meta-text">Hace 5 minutos</span>
        </article>

    </div>
</section>

<script>
    function closeRequestPopup() {
        const popup = document.getElementById('requestPopup');
        if (popup) {
            popup.classList.add('floating-request-card--hidden');
        }
    }
</script>

@endsection