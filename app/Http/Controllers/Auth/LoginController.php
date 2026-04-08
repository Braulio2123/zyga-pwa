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

        $baseUrl = rtrim((string) env('URL_BASE_API', 'http://127.0.0.1:8000'), '/');

        try {
            $response = Http::acceptJson()
                ->timeout(20)
                ->post($baseUrl . '/api/v1/auth/login', [
                    'email' => $request->email,
                    'password' => $request->password,
                ]);

            if (!$response->successful()) {
                $errorMessage = $response->json('message')
                    ?? $response->json('error')
                    ?? 'Credenciales incorrectas o respuesta inválida del servidor.';

                return back()->withErrors([
                    'email' => $errorMessage,
                ])->withInput();
            }

            $payload = $response->json('data') ?? [];
            $user = $payload['user'] ?? [];
            $roles = $payload['roles'] ?? [];
            $token = $payload['token'] ?? null;
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

            if ($role === 'provider') {
                if (!$this->providerHasProfile($baseUrl, (string) $token)) {
                    return redirect()
                        ->route('provider.perfil')
                        ->with('success', 'Tu cuenta de proveedor ya inició sesión. Ahora completa tu perfil para activar el portal.');
                }

                return redirect()->route('provider.dashboard');
            }

            return redirect()->route('user.dashboard');
        } catch (\Throwable $e) {
            return back()->withErrors([
                'email' => 'No fue posible conectar con la API. Verifica URL_BASE_API y que el servidor esté encendido.',
            ])->withInput();
        }
    }

    public function logout()
    {
        $baseUrl = rtrim((string) env('URL_BASE_API', 'http://127.0.0.1:8000'), '/');
        $token = session('api_token');

        if ($token) {
            try {
                Http::withToken($token)
                    ->acceptJson()
                    ->timeout(15)
                    ->post($baseUrl . '/api/v1/auth/logout');
            } catch (\Throwable $e) {
                // Se limpia la sesión web aunque la API no haya respondido.
            }
        }

        session()->forget(['user', 'api_token']);
        session()->flush();

        return redirect()->route('login');
    }

    private function providerHasProfile(string $baseUrl, string $token): bool
    {
        if ($token === '') {
            return false;
        }

        try {
            $response = Http::withToken($token)
                ->acceptJson()
                ->timeout(15)
                ->get($baseUrl . '/api/v1/provider/profile');

            return $response->successful();
        } catch (\Throwable $e) {
            return false;
        }
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

        return 'client';
    }
}
