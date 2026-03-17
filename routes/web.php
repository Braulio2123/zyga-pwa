<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware('api.auth')->group(function () {
    Route::get('/home', function () {
        return view('home');
    });
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::get('/admin', function () {
    if (!session('user') || session('user')['role'] !== 'admin') {
        return redirect('/login');
    }

    return view('admin.dashboard');
});

Route::prefix('admin')->group(function () {
    Route::get('/usuarios', function () {
        if (!session('user') || session('user')['role'] !== 'admin') {
            return redirect('/login');
        }

        return view('admin.usuarios.index');
    });

    Route::get('/conductores', function () {
        if (!session('user') || session('user')['role'] !== 'admin') {
            return redirect('/login');
        }

        return view('admin.conductores.index');
    });

    Route::get('/servicios', function () {
        if (!session('user') || session('user')['role'] !== 'admin') {
            return redirect('/login');
        }

        return view('admin.servicios.index');
    });

    Route::get('/solicitudes', function () {
        if (!session('user') || session('user')['role'] !== 'admin') {
            return redirect('/login');
        }

        return view('admin.solicitudes.index');
    });

    Route::get('/pagos', function () {
        if (!session('user') || session('user')['role'] !== 'admin') {
            return redirect('/login');
        }

        return view('admin.pagos.index');
    });

    Route::get('/reportes', function () {
        if (!session('user') || session('user')['role'] !== 'admin') {
            return redirect('/login');
        }

        return view('admin.reportes.index');
    });

    Route::get('/perfil', function () {
        if (!session('user') || session('user')['role'] !== 'admin') {
            return redirect('/login');
        }

        return view('admin.perfil.index');
    });

    Route::get('/configuracion', function () {
        if (!session('user') || session('user')['role'] !== 'admin') {
            return redirect('/login');
        }

        return view('admin.configuracion.index');
    });
});

Route::get('/user', function () {
    if (!session('user') || session('user')['role'] !== 'user') {
        return redirect('/login');
    }

    return view('user.dashboard');
});