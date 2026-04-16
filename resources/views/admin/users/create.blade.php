@extends('adminlte::page')

@section('title', 'Crear administrador')

@section('content_header')
    <div class="zyga-page-header">
        <div>
            <h1 class="zyga-page-title mb-1">Crear administrador</h1>
            <p class="zyga-page-subtitle mb-0">
                Registra una nueva cuenta administrativa para la operación interna de ZYGA.
            </p>
        </div>

        <a href="{{ route('admin.users.index') }}" class="zyga-btn zyga-btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i> Volver a usuarios
        </a>
    </div>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success zyga-alert mb-4">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger zyga-alert mb-4">
            <i class="fas fa-exclamation-triangle mr-2"></i>{{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger zyga-alert mb-4">
            <div class="font-weight-bold mb-2">Corrige los siguientes errores:</div>
            <ul class="mb-0 pl-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="zyga-hero-card mb-4">
        <div>
            <div class="zyga-hero-kicker">ALTA ADMINISTRATIVA</div>
            <h2 class="zyga-hero-title">Nuevo administrador</h2>
            <p class="zyga-hero-text mb-0">
                Esta vista permite registrar usuarios con privilegios administrativos dentro del panel de operación.
            </p>
        </div>

        <div class="zyga-hero-badge">
            Rol: admin
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="zyga-card">
                <div class="zyga-card-header">
                    <div>
                        <div class="zyga-section-label">FORMULARIO</div>
                        <h3 class="zyga-section-title mb-0">Datos de acceso</h3>
                    </div>
                </div>

                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf

                    <div class="zyga-card-body">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label class="zyga-label" for="email">Correo electrónico</label>
                                <input
                                    type="email"
                                    name="email"
                                    id="email"
                                    class="form-control zyga-input @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}"
                                    placeholder="admin@zyga.com"
                                    required
                                >
                                @error('email')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label class="zyga-label" for="password">Contraseña</label>
                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    class="form-control zyga-input @error('password') is-invalid @enderror"
                                    placeholder="Mínimo 8 caracteres"
                                    required
                                >
                                @error('password')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label class="zyga-label" for="password_confirmation">Confirmar contraseña</label>
                                <input
                                    type="password"
                                    name="password_confirmation"
                                    id="password_confirmation"
                                    class="form-control zyga-input"
                                    placeholder="Repite la contraseña"
                                    required
                                >
                            </div>

                            <div class="form-group col-md-12 mb-0">
                                <label class="zyga-label">Rol asignado</label>
                                <div class="zyga-role-box">
                                    <div>
                                        <div class="zyga-role-title">Administrador</div>
                                        <div class="zyga-role-text">
                                            Este formulario siempre registra usuarios con rol administrativo.
                                        </div>
                                    </div>

                                    <span class="zyga-role-badge">admin</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="zyga-card-footer">
                        <a href="{{ route('admin.users.index') }}" class="zyga-btn zyga-btn-light">
                            Cancelar
                        </a>

                        <button type="submit" class="zyga-btn zyga-btn-primary">
                            <i class="fas fa-user-shield mr-2"></i>Crear administrador
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="zyga-card">
                <div class="zyga-card-header">
                    <div>
                        <div class="zyga-section-label">INFORMACIÓN</div>
                        <h3 class="zyga-section-title mb-0">Consideraciones</h3>
                    </div>
                </div>

                <div class="zyga-card-body">
                    <div class="zyga-info-item">
                        <div class="zyga-info-icon">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <div>
                            <div class="zyga-info-title">Acceso administrativo</div>
                            <div class="zyga-info-text">
                                El usuario creado tendrá acceso a funciones sensibles del panel administrativo.
                            </div>
                        </div>
                    </div>

                    <div class="zyga-info-item">
                        <div class="zyga-info-icon">
                            <i class="fas fa-lock"></i>
                        </div>
                        <div>
                            <div class="zyga-info-title">Seguridad</div>
                            <div class="zyga-info-text">
                                Usa una contraseña robusta y compártela únicamente por canales seguros.
                            </div>
                        </div>
                    </div>

                    <div class="zyga-info-item mb-0">
                        <div class="zyga-info-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div>
                            <div class="zyga-info-title">Correo único</div>
                            <div class="zyga-info-text">
                                El correo no debe existir previamente en el sistema.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
<style>
    .content-wrapper {
        background: #f4f6f9;
    }

    .zyga-page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }

    .zyga-page-title {
        font-size: 2rem;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.02em;
    }

    .zyga-page-subtitle {
        color: #64748b;
        font-size: 0.98rem;
    }

    .zyga-hero-card {
        background: linear-gradient(135deg, #0b132b 0%, #172554 52%, #c2410c 100%);
        border-radius: 26px;
        padding: 28px 30px;
        color: #fff;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        box-shadow: 0 18px 45px rgba(15, 23, 42, 0.16);
    }

    .zyga-hero-kicker {
        font-size: 0.78rem;
        font-weight: 800;
        letter-spacing: 0.16em;
        opacity: 0.8;
        margin-bottom: 10px;
    }

    .zyga-hero-title {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 8px;
    }

    .zyga-hero-text {
        color: rgba(255, 255, 255, 0.82);
        max-width: 760px;
        font-size: 0.98rem;
    }

    .zyga-hero-badge {
        background: rgba(255, 255, 255, 0.12);
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.18);
        padding: 10px 16px;
        border-radius: 999px;
        font-weight: 700;
        white-space: nowrap;
    }

    .zyga-card {
        background: #ffffff;
        border-radius: 24px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
        overflow: hidden;
        margin-bottom: 24px;
    }

    .zyga-card-header {
        padding: 22px 24px 16px;
        border-bottom: 1px solid #edf2f7;
        background: #fff;
    }

    .zyga-card-body {
        padding: 24px;
    }

    .zyga-card-footer {
        padding: 18px 24px 24px;
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        flex-wrap: wrap;
        background: #fff;
    }

    .zyga-section-label {
        font-size: 0.76rem;
        font-weight: 800;
        letter-spacing: 0.14em;
        color: #94a3b8;
        margin-bottom: 6px;
    }

    .zyga-section-title {
        font-size: 1.3rem;
        font-weight: 800;
        color: #0f172a;
    }

    .zyga-label {
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 8px;
        display: inline-block;
    }

    .zyga-input {
        border-radius: 14px;
        border: 1px solid #dbe2ea;
        min-height: 52px;
        padding: 0.75rem 1rem;
        font-size: 0.97rem;
        box-shadow: none !important;
    }

    .zyga-input:focus {
        border-color: #f97316;
        box-shadow: 0 0 0 0.2rem rgba(249, 115, 22, 0.15) !important;
    }

    .zyga-role-box {
        border: 1px solid #e2e8f0;
        background: #fff7ed;
        border-radius: 18px;
        padding: 18px 18px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 14px;
    }

    .zyga-role-title {
        font-weight: 800;
        color: #9a3412;
        margin-bottom: 4px;
    }

    .zyga-role-text {
        color: #7c2d12;
        font-size: 0.93rem;
    }

    .zyga-role-badge {
        background: #f97316;
        color: #fff;
        font-weight: 800;
        font-size: 0.82rem;
        padding: 8px 14px;
        border-radius: 999px;
        white-space: nowrap;
    }

    .zyga-btn {
        border: none;
        border-radius: 14px;
        padding: 0.78rem 1.2rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none !important;
        transition: all 0.18s ease-in-out;
    }

    .zyga-btn-primary {
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        color: #fff !important;
        box-shadow: 0 10px 20px rgba(249, 115, 22, 0.24);
    }

    .zyga-btn-primary:hover {
        transform: translateY(-1px);
        color: #fff !important;
        box-shadow: 0 14px 24px rgba(249, 115, 22, 0.3);
    }

    .zyga-btn-secondary {
        background: #ffffff;
        color: #0f172a !important;
        border: 1px solid #e2e8f0;
    }

    .zyga-btn-secondary:hover {
        color: #0f172a !important;
        background: #f8fafc;
    }

    .zyga-btn-light {
        background: #f8fafc;
        color: #0f172a !important;
        border: 1px solid #e2e8f0;
    }

    .zyga-btn-light:hover {
        background: #eef2f7;
        color: #0f172a !important;
    }

    .zyga-alert {
        border-radius: 18px;
        border: none;
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.06);
    }

    .zyga-info-item {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding-bottom: 18px;
        margin-bottom: 18px;
        border-bottom: 1px solid #edf2f7;
    }

    .zyga-info-icon {
        width: 46px;
        height: 46px;
        min-width: 46px;
        border-radius: 14px;
        background: #fff7ed;
        color: #f97316;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.05rem;
    }

    .zyga-info-title {
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 4px;
    }

    .zyga-info-text {
        color: #64748b;
        font-size: 0.94rem;
        line-height: 1.55;
    }

    @media (max-width: 991.98px) {
        .zyga-hero-card {
            flex-direction: column;
            align-items: flex-start;
        }
    }

    @media (max-width: 575.98px) {
        .zyga-page-title {
            font-size: 1.6rem;
        }

        .zyga-hero-title {
            font-size: 1.5rem;
        }

        .zyga-card-body,
        .zyga-card-header,
        .zyga-card-footer {
            padding-left: 18px;
            padding-right: 18px;
        }

        .zyga-role-box {
            flex-direction: column;
            align-items: flex-start;
        }

        .zyga-card-footer {
            flex-direction: column;
        }

        .zyga-btn,
        .zyga-btn-primary,
        .zyga-btn-secondary,
        .zyga-btn-light {
            width: 100%;
        }
    }
</style>
@stop