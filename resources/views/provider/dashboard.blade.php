@extends('provider.layouts.app')

@section('title', 'Zyga | Proveedor')
@section('page-title', 'Inicio')

@section('content')

<section class="hero-card">
    <div>
        <p class="hero-kicker">Bienvenido</p>
        <h2>Hola, Grúas Express GDL</h2>
        <p class="muted">
            Administra tus servicios, revisa solicitudes disponibles y gestiona tus asistencias.
        </p>
    </div>
    <div class="hero-badge">Provedor</div>
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

@endsection