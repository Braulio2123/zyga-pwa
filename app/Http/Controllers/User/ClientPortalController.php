<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ClientPortalController extends Controller
{
    private function validateClientSession(): ?RedirectResponse
    {
        $sessionUser = session('user');

        if (!$sessionUser || ($sessionUser['role'] ?? null) !== 'client' || !session('api_token')) {
            return redirect()->route('login');
        }

        return null;
    }

    private function sharedViewData(string $pageKey, string $pageTitle, string $pageHeading): array
    {
        return [
            'pageKey' => $pageKey,
            'pageTitle' => $pageTitle,
            'pageHeading' => $pageHeading,
            'sessionUser' => session('user', []),
            'apiBaseUrl' => rtrim((string) env('URL_BASE_API', 'http://127.0.0.1:8000'), '/'),
            'apiToken' => (string) session('api_token'),
            'vehicleTypeOptions' => [
                ['id' => 1, 'name' => 'Automóvil'],
                ['id' => 2, 'name' => 'Motocicleta'],
                ['id' => 3, 'name' => 'Camioneta'],
            ],
        ];
    }

    public function dashboard(): View|RedirectResponse
    {
        if ($redirect = $this->validateClientSession()) {
            return $redirect;
        }

        return view('user.dashboard', $this->sharedViewData('dashboard', 'ZYGA | Inicio', 'Inicio'));
    }

    public function solicitud(): View|RedirectResponse
    {
        if ($redirect = $this->validateClientSession()) {
            return $redirect;
        }

        return view('user.request.index', $this->sharedViewData('request', 'ZYGA | Solicitar asistencia', 'Solicitar asistencia'));
    }

    public function servicioActivo(): View|RedirectResponse
    {
        if ($redirect = $this->validateClientSession()) {
            return $redirect;
        }

        return view('user.active.index', $this->sharedViewData('active', 'ZYGA | Servicio activo', 'Servicio activo'));
    }

    public function historial(): View|RedirectResponse
    {
        if ($redirect = $this->validateClientSession()) {
            return $redirect;
        }

        return view('user.historial.index', $this->sharedViewData('history', 'ZYGA | Historial', 'Historial'));
    }

    public function billetera(): View|RedirectResponse
    {
        if ($redirect = $this->validateClientSession()) {
            return $redirect;
        }

        return view('user.billetera.index', $this->sharedViewData('payments', 'ZYGA | Pagos', 'Pagos y métodos'));
    }

    public function cuenta(): View|RedirectResponse
    {
        if ($redirect = $this->validateClientSession()) {
            return $redirect;
        }

        return view('user.cuenta.index', $this->sharedViewData('account', 'ZYGA | Cuenta', 'Mi cuenta'));
    }
}
