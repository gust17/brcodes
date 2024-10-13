<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if ($request->user() && $request->user()->role !== $role) {
            return response()->json(['message' => 'Acesso não autorizado.'], 403);
        }

        return $next($request);
    }
}
