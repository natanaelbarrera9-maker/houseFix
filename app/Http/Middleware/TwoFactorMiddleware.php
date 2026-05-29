<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class TwoFactorMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // 1. Si el usuario ya inició sesión...
        // 2. Y tiene un código de seguridad generado en la base de datos...
        if (Auth::check() && $user->two_factor_code) {
            
            // 3. Verificamos que NO esté ya en la página de poner el código
            //    y que NO esté intentando cerrar sesión.
            if (!$request->is('verify*') && !$request->is('logout')) {
                
                // ¡ALTO AHÍ! Lo regresamos a la fuerza a la pantalla de verificación.
                return redirect()->route('verify.index');
            }
        }

        return $next($request);
    }
}