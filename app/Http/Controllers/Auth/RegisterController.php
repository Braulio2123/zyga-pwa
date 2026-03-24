<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RegisterController extends Controller
{
    /**
     * A dónde redirigir después del registro.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * Crear una nueva instancia del controlador.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Mostrar la vista de registro.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Registrar usuario consumiendo la API.
     */
    public function register(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['nullable', 'string', 'in:client,provider,admin'],
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Debes ingresar un correo válido.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
            'role.in' => 'El rol seleccionado no es válido.',
        ]);

        $baseUrl = rtrim(env('URL_BASE_API', 'http://127.0.0.1:8000'), '/');

        try {
            $response = Http::acceptJson()
                ->post($baseUrl . '/api/v1/auth/register', [
                    'email' => $request->email,
                    'password' => $request->password,
                    'role' => $request->role ?? 'client',
                ]);

            if ($response->successful()) {
                return redirect()
                    ->route('login')
                    ->with('success', 'Usuario registrado correctamente. Ahora ya puedes iniciar sesión.');
            }

            $responseData = $response->json();

            $errorMessage =
                $responseData['message']
                ?? 'No fue posible registrar el usuario.';

            if (isset($responseData['errors']) && is_array($responseData['errors'])) {
                $validationErrors = [];

                foreach ($responseData['errors'] as $field => $messages) {
                    if (is_array($messages) && count($messages) > 0) {
                        $validationErrors[$field] = $messages[0];
                    }
                }

                if (!empty($validationErrors)) {
                    return back()
                        ->withErrors($validationErrors)
                        ->withInput();
                }
            }

            return back()
                ->withErrors(['email' => $errorMessage])
                ->withInput();
        } catch (\Throwable $e) {
            return back()
                ->withErrors([
                    'email' => 'No fue posible conectar con la API. Verifica URL_BASE_API y que el servidor esté encendido.',
                ])
                ->withInput();
        }
    }
}