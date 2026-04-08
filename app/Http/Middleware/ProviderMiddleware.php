<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProviderMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = session('user');

        if (!$user || ($user['role'] ?? null) !== 'provider') {
            return redirect()->route('login');
        }

        if (!session()->has('api_token')) {
            session()->forget('user');

            return redirect()->route('login')
                ->with('error', 'Tu sesión web no tiene token API activo. Inicia sesión nuevamente.');
        }

        return $next($request);
    }
}
