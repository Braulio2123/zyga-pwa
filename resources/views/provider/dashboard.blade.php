@extends('provider.layouts.app')

@section('title', 'ZYGA | Inicio proveedor')
@section('page-title', 'Inicio')

@section('content')
    @php
        $displayName = $profile['display_name'] ?? (session('user.name') ?? 'Proveedor');
        $providerKind = $profile['provider_kind'] ?? 'Sin definir';
        $totalServices = count($services ?? []);
        $totalSchedules = count($schedules ?? []);
        $totalAvailable = count($availableRequests ?? []);
        $totalMine = count($myRequests ?? []);
        $statusName = $badgeData['statusName'] ?? 'Sin estado';
        $verificationText = $badgeData['verificationText'] ?? 'Pendiente';
        $isVerified = $badgeData['isVerified'] ?? false;
    @endphp

    <section class="provider-hero provider-hero-lg">
        <div class="provider-hero__content">
            <div>
                <p class="provider-hero__eyebrow">Centro de operación</p>
                <h2 class="provider-hero__title">{{ $displayName }}</h2>
                <p class="provider-hero__text">
                    Administra tu operación, configura tu disponibilidad y atiende solicitudes reales desde un portal web alineado con la API actual.
                </p>
            </div>

            <div class="provider-hero__badges">
                <span class="status-chip dark">{{ $providerKind }}</span>
                <span class="status-chip {{ $isVerified ? 'success' : 'warning' }}">{{ $verificationText }}</span>
                <span class="status-chip info">{{ $statusName }}</span>
            </div>

            <div class="cta-row">
                @if($hasProfile)
                    <a href="{{ route('provider.asistencias') }}" class="btn-primary">Ir a asistencias</a>
                    <a href="{{ route('provider.servicios') }}" class="btn-secondary">Administrar configuración</a>
                @else
                    <a href="{{ route('provider.perfil') }}" class="btn-primary">Completar onboarding</a>
                    <a href="{{ route('provider.servicios') }}" class="btn-secondary">Ver módulos</a>
                @endif
            </div>
        </div>

        <div class="provider-hero__panel">
            <div class="mini-stat">
                <span>Disponibles</span>
                <strong>{{ $totalAvailable }}</strong>
            </div>
            <div class="mini-stat">
                <span>Asignadas</span>
                <strong>{{ $totalMine }}</strong>
            </div>
            <div class="mini-stat">
                <span>Servicios</span>
                <strong>{{ $totalServices }}</strong>
            </div>
            <div class="mini-stat">
                <span>Horarios</span>
                <strong>{{ $totalSchedules }}</strong>
            </div>
        </div>
    </section>

    <section class="dashboard-kpis">
        <article class="kpi-card">
            <div class="kpi-card__label">Solicitudes disponibles</div>
            <div class="kpi-card__value">{{ $totalAvailable }}</div>
            <div class="kpi-card__hint">Listas para que las aceptes</div>
        </article>
        <article class="kpi-card">
            <div class="kpi-card__label">Mis asistencias</div>
            <div class="kpi-card__value">{{ $totalMine }}</div>
            <div class="kpi-card__hint">Servicios ya asignados a tu cuenta</div>
        </article>
        <article class="kpi-card">
            <div class="kpi-card__label">Servicios activos</div>
            <div class="kpi-card__value">{{ $totalServices }}</div>
            <div class="kpi-card__hint">Catálogo operativo actual</div>
        </article>
        <article class="kpi-card">
            <div class="kpi-card__label">Horarios cargados</div>
            <div class="kpi-card__value">{{ $totalSchedules }}</div>
            <div class="kpi-card__hint">Disponibilidad registrada</div>
        </article>
    </section>

    @if(!$hasProfile)
        <section class="locked-module">
            <h3>Tu portal aún no está activado</h3>
            <p>
                Ya iniciaste sesión como proveedor, pero la API todavía no tiene tu <strong>provider/profile</strong>.
                Completa este paso para desbloquear servicios, horarios, documentos y asistencias.
            </p>
            <div class="cta-row">
                <a href="{{ route('provider.perfil') }}" class="btn-primary">Crear perfil ahora</a>
            </div>
        </section>

        <section class="section-card">
            <div class="section-head">
                <div>
                    <p class="dashboard-card__eyebrow">Ruta recomendada</p>
                    <h3>Pasos para activar tu cuenta</h3>
                </div>
            </div>

            <div class="profile-summary-grid">
                <article class="summary-card">
                    <span class="status-chip warning">Paso 1</span>
                    <strong>Crear perfil</strong>
                    <p class="muted">Registra tu nombre comercial y el tipo de proveedor.</p>
                </article>
                <article class="summary-card">
                    <span class="status-chip warning">Paso 2</span>
                    <strong>Seleccionar servicios</strong>
                    <p class="muted">Indica qué servicios del catálogo vas a atender.</p>
                </article>
                <article class="summary-card">
                    <span class="status-chip warning">Paso 3</span>
                    <strong>Definir horarios</strong>
                    <p class="muted">Configura tu disponibilidad operativa por día.</p>
                </article>
            </div>
        </section>
    @else
        <section class="provider-dashboard-grid">
            <section class="dashboard-card dashboard-card--wide">
                <div class="dashboard-card__head">
                    <div>
                        <p class="dashboard-card__eyebrow">Operación inmediata</p>
                        <h3>Solicitudes disponibles</h3>
                    </div>
                    <a href="{{ route('provider.asistencias') }}" class="text-link">Abrir módulo</a>
                </div>

                @if(empty($availableRequests))
                    <div class="empty-state">
                        <h4>No hay solicitudes disponibles</h4>
                        <p>Cuando existan solicitudes compatibles con tus servicios aparecerán aquí para atención inmediata.</p>
                    </div>
                @else
                    <div class="request-list">
                        @foreach(array_slice($availableRequests, 0, 5) as $request)
                            <article class="request-item">
                                <div class="request-item__main">
                                    <h4>{{ $request['service']['name'] ?? $request['service_name'] ?? 'Servicio' }}</h4>
                                    <p>{{ $request['pickup_address'] ?? $request['address'] ?? 'Sin dirección disponible' }}</p>
                                    <small>ID solicitud: {{ $request['id'] ?? 'N/D' }}</small>
                                </div>
                                <div class="request-item__side">
                                    <span class="status-chip info">{{ $request['status'] ?? 'created' }}</span>
                                    @if(!empty($request['id']))
                                        <form action="{{ route('provider.asistencias.accept', $request['id']) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn-primary btn-sm">Aceptar</button>
                                        </form>
                                    @endif
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </section>

            <section class="dashboard-card">
                <div class="dashboard-card__head">
                    <div>
                        <p class="dashboard-card__eyebrow">Estado de cuenta</p>
                        <h3>Resumen del perfil</h3>
                    </div>
                </div>

                <div class="info-stack">
                    <div class="info-row"><span>Nombre comercial</span><strong>{{ $displayName }}</strong></div>
                    <div class="info-row"><span>Tipo de proveedor</span><strong>{{ $providerKind }}</strong></div>
                    <div class="info-row"><span>Verificación</span><strong>{{ $verificationText }}</strong></div>
                    <div class="info-row"><span>Estado</span><strong>{{ $statusName }}</strong></div>
                </div>

                <div class="cta-row" style="margin-top:16px;">
                    <a href="{{ route('provider.perfil') }}" class="btn-secondary w-full">Editar perfil</a>
                </div>
            </section>

            <section class="dashboard-card">
                <div class="dashboard-card__head">
                    <div>
                        <p class="dashboard-card__eyebrow">Configuración</p>
                        <h3>Servicios activos</h3>
                    </div>
                    <a href="{{ route('provider.servicios') }}" class="text-link">Editar</a>
                </div>

                @if(empty($services))
                    <div class="empty-state">
                        <h4>Aún no seleccionas servicios</h4>
                        <p>Sin esta configuración no podrás recibir solicitudes compatibles.</p>
                    </div>
                @else
                    <div class="stack-list">
                        @foreach(array_slice($services, 0, 4) as $service)
                            <article class="list-card">
                                <h4>{{ $service['name'] ?? 'Servicio' }}</h4>
                                <p>{{ $service['description'] ?? 'Servicio activo en tu catálogo.' }}</p>
                            </article>
                        @endforeach
                    </div>
                @endif
            </section>

            <section class="dashboard-card">
                <div class="dashboard-card__head">
                    <div>
                        <p class="dashboard-card__eyebrow">Disponibilidad</p>
                        <h3>Horarios registrados</h3>
                    </div>
                    <a href="{{ route('provider.horarios') }}" class="text-link">Editar</a>
                </div>

                @if(empty($schedules))
                    <div class="empty-state">
                        <h4>No hay horarios registrados</h4>
                        <p>Configura tu disponibilidad para operar con claridad.</p>
                    </div>
                @else
                    <div class="stack-list">
                        @foreach(array_slice($schedules, 0, 4) as $schedule)
                            <article class="list-card">
                                <h4>{{ $dayOptions[(int) ($schedule['day_of_week'] ?? 0)] ?? 'Día' }}</h4>
                                <p>{{ $schedule['start_time'] ?? '--:--' }} - {{ $schedule['end_time'] ?? '--:--' }}</p>
                            </article>
                        @endforeach
                    </div>
                @endif
            </section>
        </section>
    @endif
@endsection
