<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
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

            if (! $response->successful()) {
                $errorMessage = $response->json('message')
                    ?? $response->json('error')
                    ?? 'Credenciales incorrectas o respuesta inválida del servidor.';

                return back()
                    ->withErrors(['email' => $errorMessage])
                    ->withInput();
            }

            $payload = $response->json('data') ?? [];
            $user = $payload['user'] ?? [];
            $roles = $payload['roles'] ?? [];
            $token = $payload['token'] ?? null;
            $roleCodes = $this->normalizeRoleCodes($roles);
            $primaryRole = $roleCodes->first() ?? 'client';

            if (! $token) {
                return back()
                    ->withErrors(['email' => 'La API respondió sin token de acceso.'])
                    ->withInput();
            }

            session()->put([
                'user' => [
                    'id' => $user['id'] ?? null,
                    'name' => $user['name'] ?? ($user['email'] ?? 'Usuario'),
                    'email' => $user['email'] ?? $request->email,
                    'role' => $primaryRole,
                    'roles' => $roles,
                ],
                'roles' => $roles,
                'api_token' => $token,
            ]);

            if ($primaryRole === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            if ($primaryRole === 'provider') {
                if (! $this->providerHasProfile($baseUrl, (string) $token)) {
                    return redirect()
                        ->route('provider.perfil')
                        ->with('success', 'Sesión iniciada correctamente. Completa tu perfil de proveedor para habilitar el portal operativo.');
                }

                return redirect()->route('provider.dashboard');
            }

            return redirect()->route('user.dashboard');
        } catch (\Throwable $e) {
            return back()
                ->withErrors(['email' => 'No fue posible conectar con la API. Verifica URL_BASE_API y que el backend esté encendido.'])
                ->withInput();
        }
    }

    public function logout(): RedirectResponse
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
                // La sesión web se limpia aunque la API no responda.
            }
        }

        session()->invalidate();
        session()->regenerateToken();

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

    private function normalizeRoleCodes(mixed $roles): Collection
    {
        if (is_string($roles) && $roles !== '') {
            return collect([strtolower(trim($roles))]);
        }

        if (! is_array($roles)) {
            return collect();
        }

        return collect($roles)
            ->map(function ($role) {
                if (is_string($role)) {
                    return $role;
                }

                if (is_array($role)) {
                    return $role['code'] ?? $role['slug'] ?? $role['name'] ?? null;
                }

                if (is_object($role)) {
                    return $role->code ?? $role->slug ?? $role->name ?? null;
                }

                return null;
            })
            ->filter()
            ->map(fn ($role) => strtolower(trim((string) $role)))
            ->values();
    }
}
