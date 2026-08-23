<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AccessControl
{
    public function handle(Request $request, Closure $next): Response
    {
        $routeName = $request->route()->getName();

        if (!$request->user()->canAccess($routeName)) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'No tienes acceso a esta sección.'], 403);
            }
            return redirect()->route('dashboard')
                ->with('error', 'No tienes acceso a esta sección.');
        }

        return $next($request);
    }
}