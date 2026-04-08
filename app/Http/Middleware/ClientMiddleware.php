<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClientMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = session('api_token');

        if (! $token) {
            return redirect()
                ->route('login')
                ->withErrors(['email' => 'Debes iniciar sesión para continuar.']);
        }

        $roles = collect(
            session('roles')
            ?? session('api_roles')
            ?? data_get(session('user'), 'roles', [])
            ?? data_get(session('api_user'), 'roles', [])
            ?? []
        );

        $roleCodes = $roles
            ->map(function ($role) {
                if (is_array($role)) {
                    return $role['code'] ?? null;
                }

                if (is_object($role)) {
                    return $role->code ?? null;
                }

                return $role;
            })
            ->filter()
            ->values();

        if (! $roleCodes->contains('client')) {
            abort(403, 'Acceso solo para clientes.');
        }

        return $next($request);
    }
}