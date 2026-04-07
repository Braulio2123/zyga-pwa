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
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

Route::get('/home', function () {
    if (!session('user')) {
        return redirect()->route('login');
    }

    $role = session('user')['role'] ?? null;

    return match ($role) {
        'admin' => redirect()->route('admin.dashboard'),
        'provider' => redirect()->route('provider.dashboard'),
        default => redirect()->route('user.dashboard'),
    };
})->name('home');

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::patch('/users/{id}/email', [UserController::class, 'updateEmail'])->name('users.update-email');
    Route::patch('/users/{id}/password', [UserController::class, 'updatePassword'])->name('users.update-password');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile/email', [ProfileController::class, 'updateEmail'])->name('profile.update-email');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');

    Route::get('/providers', [ProviderController::class, 'index'])->name('providers.index');
    Route::get('/providers/{id}', [ProviderController::class, 'show'])->name('providers.show');
    Route::patch('/providers/{id}', [ProviderController::class, 'update'])->name('providers.update');

    Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
    Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
    Route::patch('/services/{id}', [ServiceController::class, 'update'])->name('services.update');
    Route::delete('/services/{id}', [ServiceController::class, 'destroy'])->name('services.destroy');

    Route::get('/solicitudes', [AssistanceController::class, 'index'])->name('assistance.index');
    Route::get('/solicitudes/{id}', [AssistanceController::class, 'show'])->name('assistance.show');
    Route::patch('/solicitudes/{id}', [AssistanceController::class, 'update'])->name('assistance.update');

    Route::get('/pagos', [FinanceController::class, 'index'])->name('finance.index');
    Route::get('/pagos/{id}', [FinanceController::class, 'showPayment'])->name('finance.show-payment');
    Route::patch('/pagos/{id}', [FinanceController::class, 'updatePayment'])->name('finance.update-payment');

    Route::get('/reportes', [ReportController::class, 'index'])->name('reportes.index');
    Route::get('/configuracion', [SettingController::class, 'index'])->name('configuracion.index');
});

/*
|--------------------------------------------------------------------------
| USER / CLIENT
|--------------------------------------------------------------------------
*/
Route::prefix('user')->group(function () {
    Route::get('/', function () {
        if (!session('user')) {
            return redirect()->route('login');
        }

        return app(ClientPortalController::class)->dashboard();
    })->name('user.dashboard');

    Route::get('/historial', function () {
        if (!session('user')) {
            return redirect()->route('login');
        }

        return app(ClientPortalController::class)->historial();
    })->name('user.historial');

    Route::get('/billetera', function () {
        if (!session('user')) {
            return redirect()->route('login');
        }

        return app(ClientPortalController::class)->billetera();
    })->name('user.billetera');

    Route::get('/cuenta', function () {
        if (!session('user')) {
            return redirect()->route('login');
        }

        return app(ClientPortalController::class)->cuenta();
    })->name('user.cuenta');
});

/*
|--------------------------------------------------------------------------
| PROVIDER
|--------------------------------------------------------------------------
*/
Route::prefix('provider')->middleware('provider')->group(function () {
    Route::get('/', [ProviderPortalController::class, 'dashboard'])->name('provider.dashboard');

    Route::get('/perfil', [ProviderPortalController::class, 'perfil'])->name('provider.perfil');
    Route::patch('/perfil', [ProviderPortalController::class, 'actualizarPerfil'])->name('provider.perfil.update');

    Route::get('/servicios', [ProviderPortalController::class, 'servicios'])->name('provider.servicios');
    Route::put('/servicios', [ProviderPortalController::class, 'actualizarServicios'])->name('provider.servicios.update');

    Route::get('/horarios', [ProviderPortalController::class, 'horarios'])->name('provider.horarios');
    Route::post('/horarios', [ProviderPortalController::class, 'guardarHorario'])->name('provider.horarios.store');
    Route::patch('/horarios/{id}', [ProviderPortalController::class, 'actualizarHorario'])->name('provider.horarios.update');
    Route::delete('/horarios/{id}', [ProviderPortalController::class, 'eliminarHorario'])->name('provider.horarios.delete');

    Route::get('/documentos', [ProviderPortalController::class, 'documentos'])->name('provider.documentos');
    Route::post('/documentos', [ProviderPortalController::class, 'guardarDocumento'])->name('provider.documentos.store');
    Route::delete('/documentos/{id}', [ProviderPortalController::class, 'eliminarDocumento'])->name('provider.documentos.delete');

    Route::get('/asistencias', [ProviderPortalController::class, 'asistencias'])->name('provider.asistencias');
    Route::patch('/asistencias/{id}/accept', [ProviderPortalController::class, 'aceptarAsistencia'])->name('provider.asistencias.accept');
    Route::patch('/asistencias/{id}/status', [ProviderPortalController::class, 'actualizarEstadoAsistencia'])->name('provider.asistencias.status');
});

/*
|--------------------------------------------------------------------------
| RUTAS SUELTAS USER
|--------------------------------------------------------------------------
*/
Route::get('/user/safe-driving', function () {
    return view('user.safe-driving');
})->name('user.safe-driving');

Route::get('/user/service-request', function () {
    return view('user.service-request');
});