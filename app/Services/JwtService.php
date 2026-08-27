<?php

namespace App\Services;

use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * Centraliza la generación y validación de JWT para que tanto el
 * AuthController (API pura) como el middleware ValidarAutenticacion y las
 * vistas web (sesión) usen exactamente la misma lógica y configuración.
 */
class JwtService
{
    /**
     * TTL del token en segundos (JWT_TTL en .env está en minutos).
     */
    public static function ttlSegundos(): int
    {
        return (int) config('services.jwt.ttl', 60) * 60;
    }

    /**
     * Genera un JWT firmado (HS256) para el usuario indicado.
     */
    public static function generar(User $usuario): string
    {
        $ahora = time();

        $payload = [
            'iss' => config('app.url'),
            'sub' => $usuario->id,
            'iat' => $ahora,
            'exp' => $ahora + self::ttlSegundos(),
        ];

        return JWT::encode($payload, config('services.jwt.secret'), 'HS256');
    }

    /**
     * Decodifica y valida un JWT. Lanza una excepción (ExpiredException,
     * SignatureInvalidException, etc.) si el token no es válido.
     */
    public static function decodificar(string $token): object
    {
        return JWT::decode($token, new Key(config('services.jwt.secret'), 'HS256'));
    }
}
