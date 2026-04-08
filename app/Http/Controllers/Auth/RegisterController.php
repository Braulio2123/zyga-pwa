<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RegisterController extends Controller
{
    protected $redirectTo = '/login';

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm(Request $request)
    {
        $selectedRole = $request->query('role', 'provider');

        if (!in_array($selectedRole, ['client', 'provider'], true)) {
            $selectedRole = 'provider';
        }

        return view('auth.register', [
            'selectedRole' => $selectedRole,
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', 'in:client,provider'],
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Debes ingresar un correo válido.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
            'role.in' => 'El rol seleccionado no es válido.',
        ]);

        $baseUrl = rtrim((string) env('URL_BASE_API', 'http://127.0.0.1:8000'), '/');

        try {
            $response = Http::acceptJson()
                ->timeout(20)
                ->post($baseUrl . '/api/v1/auth/register', [
                    'email' => $request->email,
                    'password' => $request->password,
                    'role' => $request->role,
                ]);

            if ($response->successful()) {
                $successMessage = $request->role === 'provider'
                    ? 'Cuenta de proveedor registrada correctamente. Inicia sesión para completar tu perfil operativo.'
                    : 'Usuario registrado correctamente. Ahora ya puedes iniciar sesión.';

                return redirect()
                    ->route('login')
                    ->with('success', $successMessage);
            }

            $responseData = $response->json();
            $errorMessage = $responseData['message'] ?? 'No fue posible registrar el usuario.';

            if (isset($responseData['errors']) && is_array($responseData['errors'])) {
                $validationErrors = [];

                foreach ($responseData['errors'] as $field => $messages) {
                    if (is_array($messages) && count($messages) > 0) {
                        $validationErrors[$field] = $messages[0];
                    }
                }

                if (!empty($validationErrors)) {
                    return back()->withErrors($validationErrors)->withInput();
                }
            }

            return back()->withErrors([
                'email' => $errorMessage,
            ])->withInput();
        } catch (\Throwable $e) {
            return back()->withErrors([
                'email' => 'No fue posible conectar con la API. Verifica URL_BASE_API y que el servidor esté encendido.',
            ])->withInput();
        }
    }
}
