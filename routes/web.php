<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\User\ClientPortalController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Provider\ProviderPortalController;

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

    return redirect()->route('user.dashboard');
})->name('home');

Route::get('/admin', function () {
    if (!session('user') || (session('user')['role'] ?? null) !== 'admin') {
        return redirect()->route('login');
    }

    return view('admin.dashboard');
})->name('admin.dashboard');

Route::prefix('admin')->group(function () {
    Route::get('/usuarios', function () {
        if (!session('user') || (session('user')['role'] ?? null) !== 'admin') {
            return redirect()->route('login');
        }

        return view('admin.usuarios.index');
    })->name('admin.usuarios.index');

    Route::get('/conductores', function () {
        if (!session('user') || (session('user')['role'] ?? null) !== 'admin') {
            return redirect()->route('login');
        }

        return view('admin.conductores.index');
    })->name('admin.conductores.index');

    Route::get('/servicios', [ServiceController::class, 'index'])
        ->name('admin.servicios.index');

    Route::get('/solicitudes', function () {
        if (!session('user') || (session('user')['role'] ?? null) !== 'admin') {
            return redirect()->route('login');
        }

        return view('admin.solicitudes.index');
    })->name('admin.solicitudes.index');

    Route::get('/pagos', function () {
        if (!session('user') || (session('user')['role'] ?? null) !== 'admin') {
            return redirect()->route('login');
        }

        return view('admin.pagos.index');
    })->name('admin.pagos.index');

    Route::get('/reportes', function () {
        if (!session('user') || (session('user')['role'] ?? null) !== 'admin') {
            return redirect()->route('login');
        }

        return view('admin.reportes.index');
    })->name('admin.reportes.index');

    Route::get('/perfil', function () {
        if (!session('user') || (session('user')['role'] ?? null) !== 'admin') {
            return redirect()->route('login');
        }

        return view('admin.perfil.index');
    })->name('admin.perfil.index');

    Route::get('/configuracion', function () {
        if (!session('user') || (session('user')['role'] ?? null) !== 'admin') {
            return redirect()->route('login');
        }

        return view('admin.configuracion.index');
    })->name('admin.configuracion.index');
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
