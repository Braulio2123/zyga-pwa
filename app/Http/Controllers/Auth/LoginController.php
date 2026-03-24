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
            'password' => ['required', 'string'],
        ]);

        $baseUrl = rtrim(env('URL_BASE_API', 'http://127.0.0.1:8000'), '/');

        try {
            $response = Http::acceptJson()
                ->post($baseUrl . '/api/v1/auth/login', [
                    'email' => $request->email,
                    'password' => $request->password,
                ]);

            if ($response->successful()) {
                $responseData = $response->json();

                $user = $responseData['data']['user'] ?? $responseData['user'] ?? null;
                $roles = $responseData['data']['roles'] ?? $responseData['roles'] ?? [];
                $token = $responseData['data']['token'] ?? $responseData['token'] ?? null;

                $role = $this->extractPrimaryRole($roles);

                session([
                    'user' => [
                        'name' => $user['name'] ?? ($user['email'] ?? 'Usuario'),
                        'email' => $user['email'] ?? $request->email,
                        'role' => $role,
                        'roles' => $roles,
                    ],
                    'api_token' => $token,
                ]);

                if ($role === 'admin') {
                    return redirect()->route('admin.dashboard');
                }

                return redirect()->route('user.dashboard');
            }

            $errorMessage =
                $response->json('message')
                ?? $response->json('error')
                ?? 'Credenciales incorrectas o respuesta inválida del servidor.';

            return back()->withErrors([
                'email' => $errorMessage,
            ])->withInput();
        } catch (\Throwable $e) {
            return back()->withErrors([
                'email' => 'No fue posible conectar con la API. Verifica URL_BASE_API y que el servidor esté encendido.',
            ])->withInput();
        }
    }

    public function logout()
    {
        session()->forget(['user', 'api_token']);
        session()->flush();

        return redirect('/login');
    }

    private function extractPrimaryRole($roles): string
    {
        if (is_string($roles) && !empty($roles)) {
            return strtolower(trim($roles));
        }

        if (is_array($roles) && count($roles) > 0) {
            $firstRole = $roles[0];

            if (is_string($firstRole) && !empty($firstRole)) {
                return strtolower(trim($firstRole));
            }

            if (is_array($firstRole)) {
                if (!empty($firstRole['code'])) {
                    return strtolower(trim($firstRole['code']));
                }

                if (!empty($firstRole['slug'])) {
                    return strtolower(trim($firstRole['slug']));
                }

                if (!empty($firstRole['name'])) {
                    return strtolower(trim($firstRole['name']));
                }
            }
        }

        return 'user';
    }
}