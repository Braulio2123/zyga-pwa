<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\User\ClientPortalController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Provider\ProviderPortalController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

// Si te da conflicto con tu login personalizado, mejor déjalo comentado.
// Auth::routes();

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

    if ($role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('user.dashboard');
})->name('home');

Route::get('/admin', function () {
    if (!session('user') || (session('user')['role'] ?? null) !== 'admin') {
        return redirect()->route('login');
    }

    return view('admin.dashboard');
})->name('admin.dashboard');

Route::prefix('admin')->name('admin.')->group(function () {

    // =========================
    // USUARIOS
    // =========================
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::patch('/users/{id}/email', [UserController::class, 'updateEmail'])->name('users.update-email');
    Route::patch('/users/{id}/password', [UserController::class, 'updatePassword'])->name('users.update-password');

    // ========================
    // PERFIL ADMINISTRADOR
    // =========================
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile/email', [ProfileController::class, 'updateEmail'])->name('profile.update-email');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');

    // =========================
    // CONDUCTORES
    // =========================
    Route::get('/conductores', function () {
        if (!session('user') || (session('user')['role'] ?? null) !== 'admin') {
            return redirect()->route('login');
        }

        return view('admin.conductores.index');
    })->name('conductores.index');

    // =========================
    // SERVICIOS
    // =========================
    Route::get('/servicios', [ServiceController::class, 'index'])
        ->name('servicios.index');

    // =========================
    // SOLICITUDES
    // =========================
    Route::get('/solicitudes', function () {
        if (!session('user') || (session('user')['role'] ?? null) !== 'admin') {
            return redirect()->route('login');
        }

        return view('admin.solicitudes.index');
    })->name('solicitudes.index');

    // =========================
    // SERVICIOS ADMIN
    // =========================
    Route::get('/services', [ServiceController::class, 'index'])->name('servicios.index');

    /*
    Route::get('/servicios/{id}', [ServiceController::class, 'show'])->name('servicios.show');
    Route::get('/servicios/{id}/edit', [ServiceController::class, 'edit'])->name('servicios.edit');
    Route::post('/servicios', [ServiceController::class, 'store'])->name('servicios.store');
    Route::put('/servicios/{id}', [ServiceController::class, 'update'])->name('servicios.update');
    Route::delete('/servicios/{id}', [ServiceController::class, 'destroy'])->name('servicios.destroy');
    */

    // =========================
    // PAGOS
    // =========================
    Route::get('/pagos', function () {
        if (!session('user') || (session('user')['role'] ?? null) !== 'admin') {
            return redirect()->route('login');
        }

        return view('admin.pagos.index');
    })->name('pagos.index');

    // =========================
    // REPORTES
    // =========================
    Route::get('/reportes', function () {
        if (!session('user') || (session('user')['role'] ?? null) !== 'admin') {
            return redirect()->route('login');
        }

        return view('admin.reportes.index');
    })->name('reportes.index');

    // =========================
    // CONFIGURACIÓN
    // =========================
    Route::get('/configuracion', function () {
        if (!session('user') || (session('user')['role'] ?? null) !== 'admin') {
            return redirect()->route('login');
        }

        return view('admin.configuracion.index');
    })->name('configuracion.index');
});

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

//PROVIDER ROUTES
Route::prefix('provider')->middleware('provider')->group(function () {
    Route::get('/', [ProviderPortalController::class, 'dashboard'])->name('provider.dashboard');
    Route::get('/perfil', [ProviderPortalController::class, 'perfil'])->name('provider.perfil');
    Route::get('/servicios', [ProviderPortalController::class, 'servicios'])->name('provider.servicios');

    Route::get('/horarios', [ProviderPortalController::class, 'horarios'])->name('provider.horarios');
    Route::post('/horarios', [ProviderPortalController::class, 'guardarHorario'])->name('provider.horarios.store');
    Route::patch('/horarios/{id}', [ProviderPortalController::class, 'actualizarHorario'])->name('provider.horarios.update');
    Route::delete('/horarios/{id}', [ProviderPortalController::class, 'eliminarHorario'])->name('provider.horarios.delete');

    Route::get('/documentos', [ProviderPortalController::class, 'documentos'])->name('provider.documentos');
    Route::get('/asistencias', [ProviderPortalController::class, 'asistencias'])->name('provider.asistencias');
});
