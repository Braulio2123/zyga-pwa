<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

class AdminSessionMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = session('api_token');

        if (! $token) {
            session()->forget(['api_token', 'roles', 'user']);

            return redirect()
                ->route('login')
                ->withErrors(['email' => 'Debes iniciar sesión para continuar.']);
        }

        if (! $this->resolveRoleCodes()->contains('admin')) {
            abort(403, 'Acceso solo para administradores.');
        }

        return $next($request);
    }

    private function resolveRoleCodes(): Collection
    {
        $roles = collect(
            session('roles')
            ?? session('api_roles')
            ?? data_get(session('user'), 'roles', [])
            ?? data_get(session('api_user'), 'roles', [])
            ?? []
        );

        return $roles
            ->map(function ($role) {
                if (is_array($role)) {
                    return $role['code'] ?? $role['slug'] ?? $role['name'] ?? null;
                }

                if (is_object($role)) {
                    return $role->code ?? $role->slug ?? $role->name ?? null;
                }

                return $role;
            })
            ->filter()
            ->map(fn ($role) => strtolower(trim((string) $role)))
            ->values();
    }
}
