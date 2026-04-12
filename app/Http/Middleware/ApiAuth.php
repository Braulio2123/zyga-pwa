<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = session('api_token');
        $user = session('user');

        if (! $token || ! is_array($user) || empty($user)) {
            session()->forget(['api_token', 'roles', 'user']);

            return redirect()
                ->route('login')
                ->withErrors(['email' => 'Tu sesión expiró o no es válida. Inicia sesión nuevamente.']);
        }

        return $next($request);
    }
}
