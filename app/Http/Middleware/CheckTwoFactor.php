<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTwoFactor
{
    /**
     * Handle an incoming request.
     * DESACTIVADO TEMPORALMENTE A PEDIDO DEL USUARIO
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Dejamos pasar todas las peticiones directamente
        return $next($request);
    }
}
