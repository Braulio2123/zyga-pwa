<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProviderPortalController extends Controller
{
    private $api;
    private $token;

    public function __construct()
    {
        $this->api = rtrim(env('URL_BASE_API'), '/');
        $this->token = session('api_token');
    }

    private function request($endpoint)
    {
        try {
            $response = Http::withToken($this->token)
                ->acceptJson()
                ->timeout(15)
                ->get($this->api . $endpoint);

            if ($response->successful()) {
                return $response->json();
            }

            return [
                'error' => true,
                'message' => 'La API respondió con error.',
                'details' => 'Código HTTP: ' . $response->status(),
                'data' => []
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => 'No fue posible conectar con la API.',
                'details' => $e->getMessage(),
                'data' => []
            ];
        }
    }

    public function dashboard()
    {
        return view('provider.dashboard');
    }

    public function perfil()
    {
        $perfil = $this->request('/api/v1/provider/profile');

        $fallback = false;
        $apiError = null;

        if (!empty($perfil['error']) || empty($perfil['data'])) {
            $fallback = true;
            $apiError = [
                'message' => $perfil['message'] ?? 'No se pudo cargar el perfil desde la API.',
                'details' => $perfil['details'] ?? null,
            ];

            $perfil = [
                'data' => [
                    'display_name' => session('user.name') ?? 'Proveedor sin nombre',
                    'provider_kind' => 'grua',
                    'status_id' => 1,
                    'is_verified' => false,
                    'email' => session('user.email') ?? 'Sin correo',
                ]
            ];
        }

        return view('provider.perfil.index', compact('perfil', 'fallback', 'apiError'));
    }

    public function servicios()
    {
        $servicios = $this->request('/api/v1/provider/services');

        if (!empty($servicios['error'])) {
            $servicios = [
                'data' => [
                    [
                        'id' => 1,
                        'name' => 'Servicio de grúa',
                        'description' => 'Arrastre y traslado de vehículos averiados.',
                        'code' => 'GRUA',
                        'is_active' => true,
                    ],
                    [
                        'id' => 2,
                        'name' => 'Cambio de llanta',
                        'description' => 'Sustitución de llanta dañada en sitio.',
                        'code' => 'LLANTA',
                        'is_active' => true,
                    ],
                    [
                        'id' => 3,
                        'name' => 'Paso de corriente',
                        'description' => 'Apoyo para encender vehículos con batería descargada.',
                        'code' => 'BATERIA',
                        'is_active' => false,
                    ],
                ]
            ];
        }

        return view('provider.servicios.index', compact('servicios'));
    }

    public function horarios()
    {
        $horarios = $this->request('/api/v1/provider/schedules');

        if (!empty($horarios['error'])) {
            $horarios = [
                'data' => [
                    [
                        'id' => 1,
                        'day_of_week' => 1,
                        'start_time' => '08:00',
                        'end_time' => '18:00',
                        'timezone' => 'America/Mexico_City',
                        'is_active' => true,
                    ],
                    [
                        'id' => 2,
                        'day_of_week' => 2,
                        'start_time' => '08:00',
                        'end_time' => '18:00',
                        'timezone' => 'America/Mexico_City',
                        'is_active' => true,
                    ],
                ]
            ];
        }

        return view('provider.horarios.index', compact('horarios'));
    }

    public function guardarHorario(Request $request)
    {
        $validated = $request->validate([
            'day_of_week' => ['required', 'integer', 'min:1', 'max:7'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'timezone' => ['required', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        try {
            $response = Http::withToken($this->token)
                ->acceptJson()
                ->timeout(15)
                ->post($this->api . '/api/v1/provider/schedules', [
                    'day_of_week' => (int) $validated['day_of_week'],
                    'start_time' => $validated['start_time'],
                    'end_time' => $validated['end_time'],
                    'timezone' => $validated['timezone'],
                    'is_active' => $request->boolean('is_active'),
                ]);

            if ($response->successful()) {
                return redirect()
                    ->route('provider.horarios')
                    ->with('success', 'Horario registrado correctamente.');
            }

            return redirect()
                ->route('provider.horarios')
                ->withInput()
                ->with('error', 'No se pudo guardar el horario. Código HTTP: ' . $response->status());
        } catch (\Exception $e) {
            return redirect()
                ->route('provider.horarios')
                ->withInput()
                ->with('error', 'No fue posible conectar con la API: ' . $e->getMessage());
        }
    }

    public function actualizarHorario(Request $request, int $id)
    {
        $validated = $request->validate([
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        try {
            $response = Http::withToken($this->token)
                ->acceptJson()
                ->timeout(15)
                ->patch($this->api . '/api/v1/provider/schedules/' . $id, [
                    'start_time' => $validated['start_time'],
                    'end_time' => $validated['end_time'],
                    'is_active' => $request->boolean('is_active'),
                ]);

            if ($response->successful()) {
                return redirect()
                    ->route('provider.horarios')
                    ->with('success', 'Horario actualizado correctamente.');
            }

            return redirect()
                ->route('provider.horarios')
                ->withInput()
                ->with('error', 'No se pudo actualizar el horario. Código HTTP: ' . $response->status());
        } catch (\Exception $e) {
            return redirect()
                ->route('provider.horarios')
                ->withInput()
                ->with('error', 'No fue posible conectar con la API: ' . $e->getMessage());
        }
    }

    public function eliminarHorario(int $id)
    {
        try {
            $response = Http::withToken($this->token)
                ->acceptJson()
                ->timeout(15)
                ->delete($this->api . '/api/v1/provider/schedules/' . $id);

            if ($response->successful()) {
                return redirect()
                    ->route('provider.horarios')
                    ->with('success', 'Horario eliminado correctamente.');
            }

            return redirect()
                ->route('provider.horarios')
                ->with('error', 'No se pudo eliminar el horario. Código HTTP: ' . $response->status());
        } catch (\Exception $e) {
            return redirect()
                ->route('provider.horarios')
                ->with('error', 'No fue posible conectar con la API: ' . $e->getMessage());
        }
    }

    public function documentos()
    {
        $documentos = $this->request('/api/v1/provider/documents');
        return view('provider.documentos.index', compact('documentos'));
    }

    public function asistencias()
    {
        $asistencias = $this->request('/api/v1/provider/assistance-requests/available');
        return view('provider.asistencias.index', compact('asistencias'));
    }
}
