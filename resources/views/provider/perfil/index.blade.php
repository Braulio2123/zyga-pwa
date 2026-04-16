@extends('provider.layouts.app')

@section('title', 'ZYGA | Mi perfil')
@section('page-key', 'perfil')
@section('page-title', 'Mi perfil')
@section('page-copy', 'Completa tu información, revisa el estado de tu cuenta y deja listo tu perfil para comenzar a atender servicios.')

@push('page_styles')
<style>
    .provider-profile-grid,
    .provider-profile-hero-grid,
    .provider-profile-main-grid,
    .provider-profile-side-grid,
    .provider-profile-kpi-grid,
    .provider-profile-checklist,
    .provider-profile-mobile-stack,
    .provider-profile-form-grid,
    .provider-profile-preview-grid {
        display: grid;
        gap: 16px;
    }

    .provider-profile-grid,
    .provider-profile-hero-grid,
    .provider-profile-main-grid,
    .provider-profile-side-grid,
    .provider-profile-kpi-grid,
    .provider-profile-preview-grid {
        grid-template-columns: 1fr;
    }

    .provider-profile-badge-row,
    .provider-profile-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .provider-profile-priority-card,
    .provider-profile-preview-card,
    .provider-profile-form-card,
    .provider-profile-check-card,
    .provider-profile-mobile-card {
        display: grid;
        gap: 14px;
    }

    .provider-profile-priority-card {
        padding: 18px;
        border-radius: 24px;
        border: 1px solid rgba(255, 122, 0, 0.12);
        background: linear-gradient(180deg, #fff9f3 0%, #ffffff 100%);
        box-shadow: 0 12px 32px rgba(15, 23, 42, 0.05);
    }

    .provider-profile-priority-card__head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
    }

    .provider-profile-priority-card__head h3,
    .provider-profile-preview-card h3,
    .provider-profile-form-card h3,
    .provider-profile-check-card h3,
    .provider-profile-mobile-card h3 {
        margin: 0;
        color: var(--provider-text-dark);
    }

    .provider-profile-priority-card__head p,
    .provider-profile-preview-card p,
    .provider-profile-form-card p,
    .provider-profile-check-card p,
    .provider-profile-mobile-card p {
        margin: 6px 0 0;
        color: var(--provider-text-soft);
        line-height: 1.55;
    }

    .provider-profile-summary-card {
        padding: 16px;
        border-radius: 20px;
        background: rgba(255,255,255,.12);
        border: 1px solid rgba(255,255,255,.10);
    }

    .provider-profile-summary-card span {
        display: block;
        color: rgba(255,255,255,.74);
        font-size: .84rem;
    }

    .provider-profile-summary-card strong {
        display: block;
        margin-top: 8px;
        font-size: 1.65rem;
        color: #fff;
        line-height: 1.1;
        letter-spacing: -.03em;
        word-break: break-word;
    }

    .provider-profile-summary-card small {
        display: block;
        margin-top: 8px;
        color: rgba(255,255,255,.70);
        line-height: 1.45;
        font-size: .8rem;
    }

    .provider-profile-form-grid {
        grid-template-columns: 1fr;
        gap: 14px;
    }

    .provider-profile-form-helper {
        margin: 0;
        color: var(--provider-text-soft);
        line-height: 1.55;
        font-size: .92rem;
    }

    .provider-profile-preview-box {
        padding: 16px;
        border-radius: 18px;
        border: 1px solid #e4ebf5;
        background: #f8fafc;
    }

    .provider-profile-preview-box span {
        display: block;
        margin-bottom: 6px;
        font-size: .8rem;
        color: var(--provider-text-soft);
    }

    .provider-profile-preview-box strong {
        display: block;
        color: var(--provider-text-dark);
        line-height: 1.5;
        word-break: break-word;
    }

    .provider-profile-checklist {
        gap: 10px;
    }

    .provider-profile-check {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        padding: 14px 15px;
        border-radius: 18px;
        border: 1px solid #e7ebf3;
        background: #fff;
    }

    .provider-profile-check strong {
        display: block;
        color: var(--provider-text-dark);
    }

    .provider-profile-check p {
        margin: 4px 0 0;
        color: var(--provider-text-soft);
        line-height: 1.45;
        font-size: .9rem;
    }

    .provider-profile-kpi-card {
        padding: 18px;
        border-radius: 22px;
        border: 1px solid #e7ebf3;
        background: linear-gradient(180deg, #ffffff 0%, #fafbfd 100%);
        box-shadow: 0 8px 22px rgba(15, 23, 42, 0.04);
        min-height: 164px;
        display: grid;
        gap: 14px;
    }

    .provider-profile-kpi-card__top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
    }

    .provider-profile-kpi-card__label {
        margin: 0;
        color: var(--provider-text-soft);
        font-size: .9rem;
    }

    .provider-profile-kpi-card__value {
        margin: 0;
        font-size: clamp(1.7rem, 2.6vw, 2.2rem);
        line-height: 1;
        font-weight: 900;
        letter-spacing: -.04em;
        color: var(--provider-text-dark);
        word-break: break-word;
    }

    .provider-profile-kpi-card__hint {
        margin: 0;
        color: var(--provider-text-soft);
        line-height: 1.52;
        font-size: .92rem;
    }

    .provider-profile-tip {
        padding: 16px;
        border-radius: 18px;
        background: linear-gradient(180deg, #fffaf2 0%, #fff 100%);
        border: 1px solid #ffe1b3;
    }

    .provider-profile-tip h4 {
        margin: 0 0 8px;
        color: var(--provider-text-dark);
    }

    .provider-profile-tip p {
        margin: 0;
        color: var(--provider-text-soft);
        line-height: 1.55;
    }

    @media (min-width: 768px) {
        .provider-profile-form-grid,
        .provider-profile-preview-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .provider-profile-kpi-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (min-width: 1024px) {
        .provider-profile-hero-grid {
            grid-template-columns: 1.15fr .95fr;
        }

        .provider-profile-main-grid {
            grid-template-columns: 1.08fr .92fr;
        }

        .provider-profile-kpi-grid {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }
    }
</style>
@endpush

@section('content')
    @php
        $r = $context['readiness'];
        $profile = $context['profile'] ?? [];
        $hasProfile = (bool) $context['hasProfile'];

        $providerKindOptions = [
            'grua' => 'Grúa',
            'bateria' => 'Paso de corriente / batería',
            'cerrajeria' => 'Cerrajería',
            'gasolina' => 'Suministro de gasolina',
            'ponchadura' => 'Cambio o reparación de llanta',
            'mecanica' => 'Apoyo mecánico',
            'motocicleta' => 'Asistencia para motocicleta',
            'multiple' => 'Varios servicios',
        ];

        $currentProviderKind = old('provider_kind', $profile['provider_kind'] ?? '');
        $normalizedProviderKind = strtolower(trim((string) $currentProviderKind));

        $displayName = old('display_name', $profile['display_name'] ?? '');
        $visibleName = trim((string) $displayName) !== '' ? $displayName : 'Aún sin nombre visible';

        $providerKindLabel = $providerKindOptions[$normalizedProviderKind] ?? ($currentProviderKind !== '' ? $currentProviderKind : 'Aún sin definir');

        $statusBadgeClass = $r['portal_ready']
            ? 'success'
            : ($r['backend_can_operate'] ? 'info' : 'warning');

        $statusBadgeText = $r['portal_ready']
            ? 'Cuenta lista para trabajar'
            : ($r['backend_can_operate'] ? 'Casi lista para comenzar' : 'Todavía faltan pasos');

        $verificationText = $r['checks']['is_verified']
            ? 'Cuenta validada'
            : 'Validación pendiente';

        $accountStatusText = $r['checks']['status_active']
            ? 'Cuenta activa'
            : 'Cuenta pendiente de activación';

        $profileActionText = $hasProfile ? 'Actualizar perfil' : 'Guardar perfil';
        $profileTitle = $hasProfile ? 'Edita tu información principal' : 'Completa tu perfil inicial';
        $profileCopy = $hasProfile
            ? 'Mantén actualizados los datos con los que aparecerás dentro del sistema.'
            : 'Agrega la información básica con la que comenzarás a operar dentro de ZYGA.';

        $nextStepTitle = 'Tu siguiente paso es configurar servicios';
        $nextStepCopy = 'Después de guardar tu perfil, lo ideal es seleccionar los servicios que ofreces para comenzar a recibir solicitudes compatibles.';
        $nextStepRoute = route('provider.servicios');
        $nextStepButton = 'Configurar servicios';

        if (!$hasProfile) {
            $nextStepTitle = 'Primero guarda tu perfil';
            $nextStepCopy = 'Antes de avanzar con horarios, documentos o atención, necesitas registrar tu información básica.';
            $nextStepRoute = route('provider.perfil');
            $nextStepButton = 'Completar perfil';
        } elseif ($r['services_count'] === 0) {
            $nextStepTitle = 'Selecciona los servicios que ofreces';
            $nextStepCopy = 'Así el sistema sabrá qué solicitudes mostrarte.';
            $nextStepRoute = route('provider.servicios');
            $nextStepButton = 'Configurar servicios';
        } elseif ($r['active_schedules_count'] === 0) {
            $nextStepTitle = 'Configura tu disponibilidad';
            $nextStepCopy = 'Agrega al menos un horario activo para indicar cuándo puedes recibir solicitudes.';
            $nextStepRoute = route('provider.horarios');
            $nextStepButton = 'Configurar horarios';
        } elseif (!$r['checks']['is_verified']) {
            $nextStepTitle = 'Tu cuenta está en revisión';
            $nextStepCopy = 'Tu perfil ya está capturado. Ahora solo falta la validación administrativa.';
            $nextStepRoute = route('provider.documentos');
            $nextStepButton = 'Ver documentos';
        } elseif (!$r['checks']['status_active']) {
            $nextStepTitle = 'Tu cuenta aún no está activa';
            $nextStepCopy = 'Tu información está casi lista, pero todavía no aparece activa para operar.';
            $nextStepRoute = route('provider.dashboard');
            $nextStepButton = 'Volver al inicio';
        } elseif ($r['portal_ready']) {
            $nextStepTitle = 'Tu cuenta está lista';
            $nextStepCopy = 'Ya puedes concentrarte en revisar solicitudes y dar seguimiento a tus servicios.';
            $nextStepRoute = route('provider.asistencias');
            $nextStepButton = 'Ver solicitudes';
        }

        $profileChecklist = [
            [
                'title' => 'Perfil guardado',
                'description' => 'Tu nombre visible y tu tipo de servicio principal.',
                'done' => $hasProfile,
            ],
            [
                'title' => 'Servicios configurados',
                'description' => 'Los tipos de asistencia que realmente ofreces.',
                'done' => $r['services_count'] > 0,
            ],
            [
                'title' => 'Disponibilidad configurada',
                'description' => 'Horarios activos para indicar cuándo puedes trabajar.',
                'done' => $r['active_schedules_count'] > 0,
            ],
            [
                'title' => 'Cuenta validada',
                'description' => 'Revisión administrativa para habilitar tu operación.',
                'done' => $r['checks']['is_verified'],
            ],
        ];
    @endphp

    <section class="hero hero-split provider-profile-hero-grid">
        <div class="provider-profile-priority-card">
            <div class="provider-profile-priority-card__head">
                <div>
                    <p class="eyebrow">Resumen de tu perfil</p>
                    <h3>Tu cuenta y tu información principal</h3>
                    <p>
                        Desde aquí puedes completar tu nombre visible, definir tu tipo de servicio
                        y revisar qué tan lista está tu cuenta para comenzar a atender solicitudes.
                    </p>
                </div>

                <span class="chip {{ $statusBadgeClass }}">{{ $statusBadgeText }}</span>
            </div>

            <div class="provider-profile-badge-row">
                <span class="chip {{ $hasProfile ? 'success' : 'warning' }}">
                    {{ $hasProfile ? 'Perfil guardado' : 'Perfil pendiente' }}
                </span>
                <span class="chip {{ $r['checks']['is_verified'] ? 'success' : 'warning' }}">{{ $verificationText }}</span>
                <span class="chip {{ $r['checks']['status_active'] ? 'success' : 'warning' }}">{{ $accountStatusText }}</span>
            </div>

            <div class="provider-profile-tip">
                <h4>{{ $nextStepTitle }}</h4>
                <p>{{ $nextStepCopy }}</p>
            </div>

            <div class="provider-profile-actions">
                <a href="{{ $nextStepRoute }}" class="btn">{{ $nextStepButton }}</a>
                <a href="{{ route('provider.dashboard') }}" class="btn-outline">Volver al inicio</a>
            </div>
        </div>

        <div class="hero-panel">
            <div class="provider-profile-summary-card">
                <span>Nombre visible</span>
                <strong>{{ $visibleName }}</strong>
                <small>Así te identificarás dentro del sistema.</small>
            </div>

            <div class="provider-profile-summary-card">
                <span>Tipo principal</span>
                <strong>{{ $providerKindLabel }}</strong>
                <small>El servicio o especialidad con la que deseas presentarte.</small>
            </div>

            <div class="provider-profile-summary-card">
                <span>Servicios configurados</span>
                <strong>{{ $r['services_count'] }}</strong>
                <small>Tipos de asistencia que ya registraste.</small>
            </div>

            <div class="provider-profile-summary-card">
                <span>Horarios activos</span>
                <strong>{{ $r['active_schedules_count'] }}</strong>
                <small>Disponibilidad actual registrada.</small>
            </div>
        </div>
    </section>

    <section class="provider-profile-main-grid">
        <section class="card provider-profile-form-card">
            <div class="section-head">
                <div>
                    <p class="eyebrow">Formulario</p>
                    <h3>{{ $profileTitle }}</h3>
                    <p>{{ $profileCopy }}</p>
                </div>
            </div>

            <form method="POST" action="{{ $hasProfile ? route('provider.perfil.update') : route('provider.perfil.store') }}" class="provider-profile-form-grid">
                @csrf
                @if($hasProfile)
                    @method('PATCH')
                @endif

                <div class="field full">
                    <label class="label" for="display_name">Nombre visible</label>
                    <input
                        type="text"
                        id="display_name"
                        name="display_name"
                        value="{{ $displayName }}"
                        placeholder="Ej. Grúas Express Guadalajara"
                        required
                    >
                    <p class="provider-profile-form-helper">
                        Este será el nombre con el que aparecerás dentro del sistema.
                    </p>
                </div>

                <div class="field full">
                    <label class="label" for="provider_kind">Tipo principal de servicio</label>
                    <select id="provider_kind" name="provider_kind">
                        <option value="">Selecciona una opción</option>
                        @foreach($providerKindOptions as $value => $label)
                            <option value="{{ $value }}" {{ $normalizedProviderKind === $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach

                        @if($currentProviderKind !== '' && !array_key_exists($normalizedProviderKind, $providerKindOptions))
                            <option value="{{ $currentProviderKind }}" selected>
                                {{ $currentProviderKind }}
                            </option>
                        @endif
                    </select>

                    <p class="provider-profile-form-helper">
                        Elige la opción que mejor describa tu servicio principal.
                    </p>
                </div>

                <div class="field full">
                    <button type="submit" class="btn full">{{ $profileActionText }}</button>
                </div>
            </form>
        </section>

        <section class="card provider-profile-preview-card">
            <div class="section-head">
                <div>
                    <p class="eyebrow">Vista previa</p>
                    <h3>Así se ve tu información actual</h3>
                    <p>Una lectura rápida de los datos principales de tu cuenta.</p>
                </div>
            </div>

            <div class="provider-profile-preview-grid">
                <div class="provider-profile-preview-box">
                    <span>Nombre visible</span>
                    <strong>{{ $visibleName }}</strong>
                </div>

                <div class="provider-profile-preview-box">
                    <span>Tipo principal</span>
                    <strong>{{ $providerKindLabel }}</strong>
                </div>

                <div class="provider-profile-preview-box">
                    <span>Estado de validación</span>
                    <strong>{{ $verificationText }}</strong>
                </div>

                <div class="provider-profile-preview-box">
                    <span>Estado de cuenta</span>
                    <strong>{{ $accountStatusText }}</strong>
                </div>
            </div>

            @if(!$hasProfile)
                <div class="empty">
                    <h4>Aún no has guardado tu perfil</h4>
                    <p>Completa el formulario para continuar con la configuración de tu cuenta.</p>
                </div>
            @endif
        </section>
    </section>

    <section class="provider-profile-kpi-grid">
        <article class="provider-profile-kpi-card">
            <div class="provider-profile-kpi-card__top">
                <div>
                    <p class="provider-profile-kpi-card__label">Perfil</p>
                    <h3 class="provider-profile-kpi-card__value">{{ $hasProfile ? 'Listo' : 'Pendiente' }}</h3>
                </div>
                <span class="chip {{ $hasProfile ? 'success' : 'warning' }}">
                    {{ $hasProfile ? 'Guardado' : 'Falta' }}
                </span>
            </div>

            <p class="provider-profile-kpi-card__hint">
                Tu información básica para comenzar a configurar el resto de la cuenta.
            </p>
        </article>

        <article class="provider-profile-kpi-card">
            <div class="provider-profile-kpi-card__top">
                <div>
                    <p class="provider-profile-kpi-card__label">Servicios</p>
                    <h3 class="provider-profile-kpi-card__value">{{ $r['services_count'] }}</h3>
                </div>
                <span class="chip info">Configurados</span>
            </div>

            <p class="provider-profile-kpi-card__hint">
                Tipos de asistencia que ya registraste dentro del sistema.
            </p>
        </article>

        <article class="provider-profile-kpi-card">
            <div class="provider-profile-kpi-card__top">
                <div>
                    <p class="provider-profile-kpi-card__label">Horarios</p>
                    <h3 class="provider-profile-kpi-card__value">{{ $r['active_schedules_count'] }}</h3>
                </div>
                <span class="chip {{ $r['active_schedules_count'] > 0 ? 'success' : 'warning' }}">
                    {{ $r['active_schedules_count'] > 0 ? 'Activos' : 'Pendientes' }}
                </span>
            </div>

            <p class="provider-profile-kpi-card__hint">
                Disponibilidad registrada para indicar cuándo puedes recibir solicitudes.
            </p>
        </article>

        <article class="provider-profile-kpi-card">
            <div class="provider-profile-kpi-card__top">
                <div>
                    <p class="provider-profile-kpi-card__label">Documentos</p>
                    <h3 class="provider-profile-kpi-card__value">{{ $r['documents_count'] }}</h3>
                </div>
                <span class="chip info">Registrados</span>
            </div>

            <p class="provider-profile-kpi-card__hint">
                Archivos o documentos que ya existen dentro de tu cuenta.
            </p>
        </article>
    </section>

    <section class="card provider-profile-check-card">
        <div class="section-head">
            <div>
                <p class="eyebrow">Progreso de tu cuenta</p>
                <h3>Qué falta para dejarla lista</h3>
                <p>Estos pasos te ayudan a saber rápidamente en qué punto va tu configuración.</p>
            </div>
        </div>

        <div class="provider-profile-checklist">
            @foreach($profileChecklist as $item)
                <div class="provider-profile-check">
                    <div>
                        <strong>{{ $item['title'] }}</strong>
                        <p>{{ $item['description'] }}</p>
                    </div>

                    <span class="chip {{ $item['done'] ? 'success' : 'warning' }}">
                        {{ $item['done'] ? 'Completo' : 'Pendiente' }}
                    </span>
                </div>
            @endforeach
        </div>
    </section>

    <section class="provider-profile-mobile-stack mobile-only">
        <article class="card provider-profile-mobile-card">
            <p class="eyebrow">Siguiente paso</p>
            <h3>{{ $nextStepTitle }}</h3>
            <p>{{ $nextStepCopy }}</p>

            <div class="provider-profile-actions">
                <a href="{{ $nextStepRoute }}" class="btn full">{{ $nextStepButton }}</a>
            </div>
        </article>
    </section>
@endsection
