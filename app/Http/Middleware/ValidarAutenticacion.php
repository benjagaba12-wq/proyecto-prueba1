<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Services\JwtService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Firebase\JWT\ExpiredException;

class ValidarAutenticacion
{
    /**
     * Verifica que la petición traiga un Bearer token JWT válido
     * (firmado con la misma JWT_SECRET) antes de continuar.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $header = (string) $request->header('Authorization', '');

        if (!str_starts_with($header, 'Bearer ')) {
            return response()->json(['message' => 'No autenticado.'], 401);
        }

        $token = substr($header, 7);

        try {
            $decoded = JwtService::decodificar($token);
        } catch (ExpiredException $e) {
            return response()->json(['message' => 'El token expiró.'], 401);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Token inválido.'], 401);
        }

        $usuario = User::find($decoded->sub ?? null);

        if (!$usuario) {
            return response()->json(['message' => 'No autenticado.'], 401);
        }

        // Deja al usuario autenticado disponible vía $request->user()
        $request->setUserResolver(fn () => $usuario);

        return $next($request);
    }
}
