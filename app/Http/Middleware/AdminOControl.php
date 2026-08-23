<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOControl
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            abort(401, 'No autenticado.');
        }

        $user = auth()->user();
        if (!$user->isAdmin() && !$user->isControlador()) {
            abort(403, 'Acceso restringido a administradores y operadores.');
        }

        return $next($request);
    }
}