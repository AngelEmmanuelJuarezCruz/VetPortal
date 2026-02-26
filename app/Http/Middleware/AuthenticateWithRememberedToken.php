<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateWithRememberedToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Si el usuario ya está autenticado, continúa
        if (Auth::check()) {
            return $next($request);
        }

        // Busca la cookie de remember_token
        $rememberCookie = $request->cookie('remember_web_' . hash('sha256', config('app.key')));
        
        if ($rememberCookie) {
            // Decodifica el token (Laravel lo encripta)
            $token = base64_decode($rememberCookie);
            
            // Busca el usuario por su remember_token
            $user = User::where('remember_token', $token)->first();
            
            if ($user) {
                Auth::login($user, true);
            }
        }

        return $next($request);
    }
}
