<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Si no está autenticado o su rol no coincide con el requerido, redirige al dashboard
        if (!Auth::check() || Auth::user()->role !== $role) {
            return redirect('/dashboard');
        }

        return $next($request);
    }
}