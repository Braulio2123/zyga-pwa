<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $email = $request->email;
        $password = $request->password;

        // 🔐 Usuarios hardcodeados
        if ($email === 'admin@zyga.com' && $password === '123456') {
            session([
                'user' => [
                    'name' => 'Administrador',
                    'email' => $email,
                    'role' => 'admin'
                ]
            ]);

            return redirect('/admin');
        }

        if ($email === 'user@zyga.com' && $password === '123456') {
            session([
                'user' => [
                    'name' => 'Usuario',
                    'email' => $email,
                    'role' => 'user'
                ]
            ]);

            return redirect('/user');
        }

        return back()->withErrors([
            'email' => 'Credenciales incorrectas',
        ])->withInput();
    }

    public function logout()
    {
        session()->forget(['user']);
        session()->flush();

        return redirect('/login');
    }
}
