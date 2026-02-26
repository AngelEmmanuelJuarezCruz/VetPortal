<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantExists
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // If user is authenticated but has no tenant, redirect to create tenant
        if ($user && !$user->tenant_id) {
            return redirect()->route('tenants.create')->with('message', 'Debes crear una clínica veterinaria primero.');
        }

        return $next($request);
    }
}
