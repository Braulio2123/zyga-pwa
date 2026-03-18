<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\User\ClientPortalController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware('api.auth')->group(function () {
    Route::get('/home', function () {
        return view('home');
    })->name('home');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::get('/admin', function () {
    if (!session('user') || session('user')['role'] !== 'admin') {
        return redirect('/login');
    }

    return view('admin.dashboard');
})->name('admin.dashboard');

Route::prefix('admin')->group(function () {
    Route::get('/usuarios', function () {
        if (!session('user') || session('user')['role'] !== 'admin') {
            return redirect('/login');
        }

        return view('admin.usuarios.index');
    })->name('admin.usuarios.index');

    Route::get('/conductores', function () {
        if (!session('user') || session('user')['role'] !== 'admin') {
            return redirect('/login');
        }

        return view('admin.conductores.index');
    })->name('admin.conductores.index');

    Route::get('/servicios', function () {
        if (!session('user') || session('user')['role'] !== 'admin') {
            return redirect('/login');
        }

        return view('admin.servicios.index');
    })->name('admin.servicios.index');

    Route::get('/solicitudes', function () {
        if (!session('user') || session('user')['role'] !== 'admin') {
            return redirect('/login');
        }

        return view('admin.solicitudes.index');
    })->name('admin.solicitudes.index');

    Route::get('/pagos', function () {
        if (!session('user') || session('user')['role'] !== 'admin') {
            return redirect('/login');
        }

        return view('admin.pagos.index');
    })->name('admin.pagos.index');

    Route::get('/reportes', function () {
        if (!session('user') || session('user')['role'] !== 'admin') {
            return redirect('/login');
        }

        return view('admin.reportes.index');
    })->name('admin.reportes.index');

    Route::get('/perfil', function () {
        if (!session('user') || session('user')['role'] !== 'admin') {
            return redirect('/login');
        }

        return view('admin.perfil.index');
    })->name('admin.perfil.index');

    Route::get('/configuracion', function () {
        if (!session('user') || session('user')['role'] !== 'admin') {
            return redirect('/login');
        }

        return view('admin.configuracion.index');
    })->name('admin.configuracion.index');
});

Route::prefix('user')->group(function () {
    Route::get('/', [ClientPortalController::class, 'dashboard'])->name('user.dashboard');
    Route::get('/historial', [ClientPortalController::class, 'historial'])->name('user.historial');
    Route::get('/billetera', [ClientPortalController::class, 'billetera'])->name('user.billetera');
    Route::get('/cuenta', [ClientPortalController::class, 'cuenta'])->name('user.cuenta');
});