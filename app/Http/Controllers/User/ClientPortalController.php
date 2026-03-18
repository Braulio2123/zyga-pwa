<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ClientPortalController extends Controller
{
    private function validateClientSession(): RedirectResponse|null
    {
        $sessionUser = session('user');

        if (!$sessionUser) {
            return redirect()->route('login');
        }

        $role = $sessionUser['role'] ?? null;

        if (!in_array($role, ['user', 'client'], true)) {
            return redirect()->route('login');
        }

        return null;
    }

    public function dashboard(): View|RedirectResponse
    {
        if ($redirect = $this->validateClientSession()) {
            return $redirect;
        }

        return view('user.dashboard');
    }

    public function historial(): View|RedirectResponse
    {
        if ($redirect = $this->validateClientSession()) {
            return $redirect;
        }

        return view('user.historial.index');
    }

    public function billetera(): View|RedirectResponse
    {
        if ($redirect = $this->validateClientSession()) {
            return $redirect;
        }

        return view('user.billetera.index');
    }

    public function cuenta(): View|RedirectResponse
    {
        if ($redirect = $this->validateClientSession()) {
            return $redirect;
        }

        return view('user.cuenta.index');
    }
}