<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProfileController extends Controller
{
    public function index()
    {
        $baseUrl = env('URL_BASE_API');
        $token = session('api_token');
        $sessionUser = session('user', []);

        if (!$token || !$baseUrl) {
            return view('admin.profile.index', [
                'profile' => $sessionUser,
                'apiError' => 'No existe token de sesión o la variable URL_BASE_API no está configurada.',
            ]);
        }

        try {
            $response = Http::acceptJson()
                ->withToken($token)
                ->get($baseUrl . '/api/v1/me');

            if (!$response->successful()) {
                return view('admin.profile.index', [
                    'profile' => $sessionUser,
                    'apiError' => $response->json('message') ?? 'No fue posible obtener la información del perfil.',
                ]);
            }

            $data = $response->json('data', []);
            $user = $data['user'] ?? $data ?? $sessionUser;

            return view('admin.profile.index', [
                'profile' => $user,
                'apiError' => null,
            ]);
        } catch (\Throwable $e) {
            return view('admin.profile.index', [
                'profile' => $sessionUser,
                'apiError' => 'Error al conectar con la API: ' . $e->getMessage(),
            ]);
        }
    }

    public function updateEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $baseUrl = env('URL_BASE_API');
        $token = session('api_token');

        if (!$token || !$baseUrl) {
            return redirect()
                ->route('admin.profile.index')
                ->with('error', 'No existe token de sesión o URL_BASE_API no está configurada.');
        }

        try {
            $response = Http::acceptJson()
                ->withToken($token)
                ->put($baseUrl . '/api/v1/me', [
                    'email' => $request->email,
                ]);

            if (!$response->successful()) {
                return redirect()
                    ->route('admin.profile.index')
                    ->with('error', $response->json('message') ?? 'No fue posible actualizar el correo.');
            }

            $updatedUser = $response->json('data.user')
                ?? $response->json('data')
                ?? [];

            $currentSessionUser = session('user', []);
            $currentSessionUser['email'] = $updatedUser['email'] ?? $request->email;
            session(['user' => $currentSessionUser]);

            return redirect()
                ->route('admin.profile.index')
                ->with('success', 'Correo actualizado correctamente.');
        } catch (\Throwable $e) {
            return redirect()
                ->route('admin.profile.index')
                ->with('error', 'Error al conectar con la API: ' . $e->getMessage());
        }
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $baseUrl = env('URL_BASE_API');
        $token = session('api_token');

        if (!$token || !$baseUrl) {
            return redirect()
                ->route('admin.profile.index')
                ->with('error', 'No existe token de sesión o URL_BASE_API no está configurada.');
        }

        try {
            $response = Http::acceptJson()
                ->withToken($token)
                ->patch($baseUrl . '/api/v1/me', [
                    'current_password' => $request->current_password,
                    'password' => $request->password,
                    'password_confirmation' => $request->password_confirmation,
                ]);

            if (!$response->successful()) {
                return redirect()
                    ->route('admin.profile.index')
                    ->with('error', $response->json('message') ?? 'No fue posible actualizar la contraseña.');
            }

            return redirect()
                ->route('admin.profile.index')
                ->with('success', 'Contraseña actualizada correctamente.');
        } catch (\Throwable $e) {
            return redirect()
                ->route('admin.profile.index')
                ->with('error', 'Error al conectar con la API: ' . $e->getMessage());
        }
    }
}