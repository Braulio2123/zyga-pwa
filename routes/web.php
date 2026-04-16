<?php

/**
 * -----------------------------------------------------------------------------
 * RUTAS WEB DE LA PWA ZYGA
 * -----------------------------------------------------------------------------
 *
 * Este archivo concentra las rutas web del proyecto PWA.
 *
 * Aquí se definen:
 * - landing pública
 * - login y registro
 * - logout
 * - redirección inteligente por rol desde /home
 * - módulo admin
 * - módulo cliente
 * - módulo provider
 *
 * IMPORTANTE:
 * Estas rutas no son la API. La API se consume aparte mediante HTTP desde la PWA.
 * Este archivo solo controla la navegación web del frontend Laravel.
 * -----------------------------------------------------------------------------
 */

use App\Http\Controllers\Admin\AssistanceController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FinanceController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ProviderController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Provider\ProviderPortalController;
use App\Http\Controllers\User\ClientPortalController;
use Illuminate\Support\Facades\Route;

/**
 * -----------------------------------------------------------------------------
 * FUNCIÓN AUXILIAR: RESOLVER PORTAL SEGÚN EL ROL EN SESIÓN
 * -----------------------------------------------------------------------------
 *
 * Esta función determina a qué panel debe entrar el usuario cuando visita /home.
 *
 * Revisa:
 * - session('user.role')
 * - session('roles')
 * - roles guardados como string, array u objeto
 *
 * Retorna el nombre de la ruta destino:
 * - admin.dashboard
 * - provider.dashboard
 * - user.dashboard
 * - login (si no se detecta un rol válido)
 * -----------------------------------------------------------------------------
 */
$resolvePortalRoute = function (): string {
    $sessionUser = session('user', []);

    $rawRoles = collect(
        session('roles')
        ?? data_get($sessionUser, 'roles', [])
        ?? []
    );

    $normalizedRoles = $rawRoles
        ->map(function ($role) {
            if (is_array($role)) {
                return $role['code'] ?? $role['name'] ?? null;
            }

            if (is_object($role)) {
                return $role->code ?? $role->name ?? null;
            }

            return $role;
        })
        ->filter()
        ->map(fn ($role) => strtolower((string) $role))
        ->values();

    $primaryRole = strtolower((string) data_get($sessionUser, 'role', ''));

    if ($primaryRole === 'admin' || $normalizedRoles->contains('admin')) {
        return 'admin.dashboard';
    }

    if ($primaryRole === 'provider' || $normalizedRoles->contains('provider')) {
        return 'provider.dashboard';
    }

    if ($primaryRole === 'client' || $normalizedRoles->contains('client')) {
        return 'user.dashboard';
    }

    return 'login';
};

/**
 * -----------------------------------------------------------------------------
 * RUTA PÚBLICA DE ENTRADA
 * -----------------------------------------------------------------------------
 *
 * Landing principal del sitio.
 * Esta página puede servir como bienvenida, presentación del servicio o acceso
 * inicial antes de autenticarse.
 * -----------------------------------------------------------------------------
 */
Route::get('/', function () {
    return view('landing');
})->name('landing');

/**
 * -----------------------------------------------------------------------------
 * RUTAS DE AUTENTICACIÓN PARA INVITADOS
 * -----------------------------------------------------------------------------
 *
 * Estas rutas solo deben estar disponibles cuando el usuario NO ha iniciado sesión.
 *
 * Incluyen:
 * - formulario de login
 * - envío de login
 * - formulario de registro
 * - envío de registro
 * -----------------------------------------------------------------------------
 */
Route::middleware('guest')->group(function () {
    // Mostrar formulario de inicio de sesión
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

    // Procesar inicio de sesión
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');

    // Mostrar formulario de registro
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');

    // Procesar registro de usuario
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
});

/**
 * -----------------------------------------------------------------------------
 * LOGOUT
 * -----------------------------------------------------------------------------
 *
 * Se permite GET y POST para evitar problemas prácticos con botones, links o
 * formularios heredados del proyecto.
 *
 * Aunque idealmente logout suele ir por POST, aquí se deja flexible para evitar
 * errores 405 y mantener compatibilidad con la navegación actual.
 * -----------------------------------------------------------------------------
 */
Route::match(['get', 'post'], '/logout', [LoginController::class, 'logout'])->name('logout');

/**
 * -----------------------------------------------------------------------------
 * REDIRECCIÓN GENERAL DESPUÉS DE LOGIN O ENTRADA INTERMEDIA
 * -----------------------------------------------------------------------------
 *
 * /home funciona como un enrutador por rol.
 *
 * Si el usuario no tiene sesión:
 * - se redirige a login
 *
 * Si sí tiene sesión:
 * - admin    -> /admin
 * - provider -> /provider
 * - client   -> /user
 * -----------------------------------------------------------------------------
 */
Route::get('/home', function () use ($resolvePortalRoute) {
    if (!session()->has('user')) {
        return redirect()->route('login');
    }

    return redirect()->route($resolvePortalRoute());
})->name('home');

/**
 * -----------------------------------------------------------------------------
 * MÓDULO ADMIN
 * -----------------------------------------------------------------------------
 *
 * Prefijo: /admin
 * Nombre base de rutas: admin.
 * Middleware: admin
 *
 * Todo lo que está aquí solo debe poder verlo un usuario administrador.
 *
 * Incluye:
 * - dashboard
 * - usuarios
 * - perfil admin
 * - providers
 * - servicios
 * - solicitudes/asistencias
 * - pagos / finanzas
 * - reportes
 * - configuración
 * -----------------------------------------------------------------------------
 */
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['admin'])
    ->group(function () {
        /**
         * Dashboard principal de administración
         */
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        /**
         * ---------------------------------------------------------------------
         * Gestión de usuarios
         * ---------------------------------------------------------------------
         */
        Route::prefix('users')->name('users.')->group(function () {
            // Listado de usuarios
            Route::get('/', [UserController::class, 'index'])->name('index');

            // Detalle de un usuario
            Route::get('/{id}', [UserController::class, 'show'])->name('show');

            // Vista de edición de usuario
            Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');

            // Actualizar correo de un usuario
            Route::patch('/{id}/email', [UserController::class, 'updateEmail'])->name('update-email');

            // Actualizar contraseña de un usuario
            Route::patch('/{id}/password', [UserController::class, 'updatePassword'])->name('update-password');
        });

        /**
         * ---------------------------------------------------------------------
         * Perfil del administrador autenticado
         * ---------------------------------------------------------------------
         */
        Route::prefix('profile')->name('profile.')->group(function () {
            // Ver perfil
            Route::get('/', [ProfileController::class, 'index'])->name('index');

            // Actualizar correo del admin autenticado
            Route::put('/email', [ProfileController::class, 'updateEmail'])->name('update-email');

            // Actualizar contraseña del admin autenticado
            Route::patch('/password', [ProfileController::class, 'updatePassword'])->name('update-password');
        });

        /**
         * ---------------------------------------------------------------------
         * Gestión de providers
         * ---------------------------------------------------------------------
         */
        Route::prefix('providers')->name('providers.')->group(function () {
            // Listado de proveedores
            Route::get('/', [ProviderController::class, 'index'])->name('index');

            // Ver detalle de proveedor
            Route::get('/{id}', [ProviderController::class, 'show'])->name('show');

            // Actualizar proveedor
            Route::patch('/{id}', [ProviderController::class, 'update'])->name('update');
        });

        /**
         * ---------------------------------------------------------------------
         * Gestión de servicios
         * ---------------------------------------------------------------------
         */
        Route::prefix('services')->name('services.')->group(function () {
            // Listado de servicios
            Route::get('/', [ServiceController::class, 'index'])->name('index');

            // Crear servicio
            Route::post('/', [ServiceController::class, 'store'])->name('store');

            // Actualizar servicio
            Route::patch('/{id}', [ServiceController::class, 'update'])->name('update');

            // Eliminar servicio
            Route::delete('/{id}', [ServiceController::class, 'destroy'])->name('destroy');
        });

        /**
         * ---------------------------------------------------------------------
         * Gestión de solicitudes / asistencias
         * ---------------------------------------------------------------------
         */
        Route::prefix('solicitudes')->name('assistance.')->group(function () {
            // Listado de solicitudes
            Route::get('/', [AssistanceController::class, 'index'])->name('index');

            // Ver detalle de una solicitud
            Route::get('/{id}', [AssistanceController::class, 'show'])->name('show');

            // Actualizar información de una solicitud
            Route::patch('/{id}', [AssistanceController::class, 'update'])->name('update');
        });

        /**
         * ---------------------------------------------------------------------
         * Gestión financiera / pagos
         * ---------------------------------------------------------------------
         */
        Route::prefix('pagos')->name('finance.')->group(function () {
            // Listado de pagos
            Route::get('/', [FinanceController::class, 'index'])->name('index');

            // Ver detalle de un pago
            Route::get('/{id}', [FinanceController::class, 'showPayment'])->name('show-payment');

            // Actualizar un pago
            Route::patch('/{id}', [FinanceController::class, 'updatePayment'])->name('update-payment');
        });

        /**
         * Reportes
         */
        Route::get('/reportes', [ReportController::class, 'index'])->name('reportes.index');

        /**
         * Configuración general
         */
        Route::get('/configuracion', [SettingController::class, 'index'])->name('configuracion.index');
    });

/**
 * -----------------------------------------------------------------------------
 * MÓDULO CLIENTE
 * -----------------------------------------------------------------------------
 *
 * Prefijo: /user
 * Nombre base de rutas: user.
 * Middleware: client
 *
 * Este es el portal cliente de la PWA.
 * Aquí vive el flujo principal del MVP:
 * - inicio
 * - solicitud
 * - servicio activo
 * - historial
 * - pagos
 * - cuenta
 * -----------------------------------------------------------------------------
 */
Route::prefix('user')
    ->name('user.')
    ->middleware(['client'])
    ->group(function () {
        // Dashboard / inicio del cliente
        Route::get('/', [ClientPortalController::class, 'dashboard'])->name('dashboard');

        // Crear una nueva solicitud de asistencia
        Route::get('/solicitud', [ClientPortalController::class, 'solicitud'])->name('solicitud');

        // Ver y seguir la solicitud activa
        Route::get('/servicio-activo', [ClientPortalController::class, 'servicioActivo'])->name('activo');

        // Historial de asistencias cerradas
        Route::get('/historial', [ClientPortalController::class, 'historial'])->name('historial');

        Route::get('/notificaciones', [ClientPortalController::class, 'notificaciones'])->name('notificaciones');

        // Pagos y métodos de pago
        Route::get('/pagos', [ClientPortalController::class, 'pagos'])->name('pagos');

        /**
         * Alias de compatibilidad:
         * Si alguna parte vieja del proyecto usa /user/billetera,
         * se redirige automáticamente a /user/pagos.
         */
        Route::redirect('/billetera', '/user/pagos')->name('billetera');

        // Cuenta del cliente y vehículos
        Route::get('/cuenta', [ClientPortalController::class, 'cuenta'])->name('cuenta');
    });

/**
 * -----------------------------------------------------------------------------
 * MÓDULO PROVIDER
 * -----------------------------------------------------------------------------
 *
 * Prefijo: /provider
 * Nombre base de rutas: provider.
 * Middleware: provider
 *
 * Este módulo está pensado para la operación del proveedor:
 * - dashboard
 * - perfil
 * - servicios que atiende
 * - horarios
 * - documentos
 * - asistencias asignadas / disponibles
 * -----------------------------------------------------------------------------
 */
Route::prefix('provider')
    ->name('provider.')
    ->middleware(['provider'])
    ->group(function () {
        /**
         * Dashboard del provider
         */
        Route::get('/', [ProviderPortalController::class, 'dashboard'])->name('dashboard');

        /**
         * ---------------------------------------------------------------------
         * Perfil del provider
         * ---------------------------------------------------------------------
         */
        Route::get('/perfil', [ProviderPortalController::class, 'perfil'])->name('perfil');
        Route::post('/perfil', [ProviderPortalController::class, 'crearPerfil'])->name('perfil.store');
        Route::patch('/perfil', [ProviderPortalController::class, 'actualizarPerfil'])->name('perfil.update');

        /**
         * ---------------------------------------------------------------------
         * Servicios del provider
         * ---------------------------------------------------------------------
         */
        Route::get('/servicios', [ProviderPortalController::class, 'servicios'])->name('servicios');
        Route::put('/servicios', [ProviderPortalController::class, 'actualizarServicios'])->name('servicios.update');

        /**
         * ---------------------------------------------------------------------
         * Horarios del provider
         * ---------------------------------------------------------------------
         */
        Route::get('/horarios', [ProviderPortalController::class, 'horarios'])->name('horarios');
        Route::post('/horarios', [ProviderPortalController::class, 'guardarHorario'])->name('horarios.store');
        Route::patch('/horarios/{id}', [ProviderPortalController::class, 'actualizarHorario'])->name('horarios.update');
        Route::delete('/horarios/{id}', [ProviderPortalController::class, 'eliminarHorario'])->name('horarios.delete');

        /**
         * ---------------------------------------------------------------------
         * Documentos del provider
         * ---------------------------------------------------------------------
         */
        Route::get('/documentos', [ProviderPortalController::class, 'documentos'])->name('documentos');
        Route::post('/documentos', [ProviderPortalController::class, 'guardarDocumento'])->name('documentos.store');
        Route::delete('/documentos/{id}', [ProviderPortalController::class, 'eliminarDocumento'])->name('documentos.delete');

        /**
         * ---------------------------------------------------------------------
         * Asistencias del provider
         * ---------------------------------------------------------------------
         */
        Route::get('/asistencias', [ProviderPortalController::class, 'asistencias'])->name('asistencias');
        Route::get('/asistencias/{id}', [ProviderPortalController::class, 'verAsistencia'])->name('asistencias.show');

        // Aceptar una asistencia
        Route::patch('/asistencias/{id}/accept', [ProviderPortalController::class, 'aceptarAsistencia'])->name('asistencias.accept');

        // Actualizar estatus de la asistencia
        Route::patch('/asistencias/{id}/status', [ProviderPortalController::class, 'actualizarEstatusAsistencia'])->name('asistencias.status');
    });
