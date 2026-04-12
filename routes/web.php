<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\User\ClientPortalController;
use App\Http\Controllers\Provider\ProviderPortalController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ProviderController;
use App\Http\Controllers\Admin\AssistanceController;
use App\Http\Controllers\Admin\FinanceController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;

Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
});

Route::match(['get', 'post'], '/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/home', function () {
    if (!session()->has('user')) {
        return redirect()->route('login');
    }

    return match (session('user.role')) {
        'admin' => redirect()->route('admin.dashboard'),
        'provider' => redirect()->route('provider.dashboard'),
        'client' => redirect()->route('user.dashboard'),
        default => redirect()->route('login'),
    };
})->name('home');

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['admin'])
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/{id}', [UserController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
            Route::patch('/{id}/email', [UserController::class, 'updateEmail'])->name('update-email');
            Route::patch('/{id}/password', [UserController::class, 'updatePassword'])->name('update-password');
        });

        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [ProfileController::class, 'index'])->name('index');
            Route::put('/email', [ProfileController::class, 'updateEmail'])->name('update-email');
            Route::patch('/password', [ProfileController::class, 'updatePassword'])->name('update-password');
        });

        Route::prefix('providers')->name('providers.')->group(function () {
            Route::get('/', [ProviderController::class, 'index'])->name('index');
            Route::get('/{id}', [ProviderController::class, 'show'])->name('show');
            Route::patch('/{id}', [ProviderController::class, 'update'])->name('update');
        });

        Route::prefix('services')->name('services.')->group(function () {
            Route::get('/', [ServiceController::class, 'index'])->name('index');
            Route::post('/', [ServiceController::class, 'store'])->name('store');
            Route::patch('/{id}', [ServiceController::class, 'update'])->name('update');
            Route::delete('/{id}', [ServiceController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('solicitudes')->name('assistance.')->group(function () {
            Route::get('/', [AssistanceController::class, 'index'])->name('index');
            Route::get('/{id}', [AssistanceController::class, 'show'])->name('show');
            Route::patch('/{id}', [AssistanceController::class, 'update'])->name('update');
        });

        Route::prefix('pagos')->name('finance.')->group(function () {
            Route::get('/', [FinanceController::class, 'index'])->name('index');
            Route::get('/{id}', [FinanceController::class, 'showPayment'])->name('show-payment');
            Route::patch('/{id}', [FinanceController::class, 'updatePayment'])->name('update-payment');
        });

        Route::get('/reportes', [ReportController::class, 'index'])->name('reportes.index');
        Route::get('/configuracion', [SettingController::class, 'index'])->name('configuracion.index');
    });

Route::prefix('user')
    ->name('user.')
    ->middleware(['client'])
    ->group(function () {
        Route::get('/', [ClientPortalController::class, 'dashboard'])->name('dashboard');
        Route::get('/solicitud', [ClientPortalController::class, 'solicitud'])->name('solicitud');
        Route::get('/servicio-activo', [ClientPortalController::class, 'servicioActivo'])->name('activo');
        Route::get('/historial', [ClientPortalController::class, 'historial'])->name('historial');
        Route::get('/pagos', [ClientPortalController::class, 'pagos'])->name('pagos');
        Route::redirect('/billetera', '/user/pagos')->name('billetera');
        Route::get('/cuenta', [ClientPortalController::class, 'cuenta'])->name('cuenta');
    });

Route::prefix('provider')
    ->name('provider.')
    ->middleware(['provider'])
    ->group(function () {
        Route::get('/', [ProviderPortalController::class, 'dashboard'])->name('dashboard');

        Route::get('/perfil', [ProviderPortalController::class, 'perfil'])->name('perfil');
        Route::post('/perfil', [ProviderPortalController::class, 'crearPerfil'])->name('perfil.store');
        Route::patch('/perfil', [ProviderPortalController::class, 'actualizarPerfil'])->name('perfil.update');

        Route::get('/servicios', [ProviderPortalController::class, 'servicios'])->name('servicios');
        Route::put('/servicios', [ProviderPortalController::class, 'actualizarServicios'])->name('servicios.update');

        Route::get('/horarios', [ProviderPortalController::class, 'horarios'])->name('horarios');
        Route::post('/horarios', [ProviderPortalController::class, 'guardarHorario'])->name('horarios.store');
        Route::patch('/horarios/{id}', [ProviderPortalController::class, 'actualizarHorario'])->name('horarios.update');
        Route::delete('/horarios/{id}', [ProviderPortalController::class, 'eliminarHorario'])->name('horarios.delete');

        Route::get('/documentos', [ProviderPortalController::class, 'documentos'])->name('documentos');
        Route::post('/documentos', [ProviderPortalController::class, 'guardarDocumento'])->name('documentos.store');
        Route::delete('/documentos/{id}', [ProviderPortalController::class, 'eliminarDocumento'])->name('documentos.delete');

        Route::get('/asistencias', [ProviderPortalController::class, 'asistencias'])->name('asistencias');
        Route::get('/asistencias/{id}', [ProviderPortalController::class, 'verAsistencia'])->name('asistencias.show');
        Route::patch('/asistencias/{id}/accept', [ProviderPortalController::class, 'aceptarAsistencia'])->name('asistencias.accept');
        Route::patch('/asistencias/{id}/status', [ProviderPortalController::class, 'actualizarEstatusAsistencia'])->name('asistencias.status');
    });
