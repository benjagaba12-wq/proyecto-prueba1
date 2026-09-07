<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\JwtService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

// Controlador de autenticación API (JWT manual con firebase/php-jwt)
class AuthController extends Controller
{
    // Registro de usuario: cifra clave y devuelve JWT
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100|regex:/^\pL+(?: \pL+)*$/u',
            'correo' => 'required|email|max:255|unique:users,correo',
            'clave'  => 'required|string|min:8|confirmed',
        ], [
           'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string'   => 'El nombre debe ser texto.',
            'nombre.max'      => 'El nombre no puede superar los 100 caracteres.',
            'nombre.regex'    => 'El nombre solo debe contener letras y espacios simples entre palabras.',
            'correo.required' => 'El correo es obligatorio.',
            'correo.email'    => 'El correo debe ser una dirección válida.',
            'correo.max'      => 'El correo no puede superar los 255 caracteres.',
            'correo.unique'   => 'Ese correo ya está registrado.',
            'clave.required'  => 'La clave es obligatoria.',
            'clave.string'    => 'La clave debe ser texto.',
            'clave.min'       => 'La clave debe tener al menos 8 caracteres.',
            'clave.confirmed' => 'La confirmación de clave no coincide.',
        ]);

        $usuario = User::create([
            'nombre' => $validated['nombre'],
            'correo' => $validated['correo'],
            'clave'  => Hash::make($validated['clave']), // cifrado bcrypt
        ]);

        return response()->json([
            'usuario'    => $usuario,
            'token'      => JwtService::generar($usuario),
            'token_type' => 'bearer',
            'expires_in' => JwtService::ttlSegundos(),
        ], 201);
    }

    // Login de usuario: valida credenciales y devuelve JWT
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'correo' => 'required|email',
            'clave'  => 'required|string',
        ], [
           'correo.required' => 'El correo es obligatorio.',
            'correo.email'    => 'El correo debe ser una dirección válida.',
            'clave.required'  => 'La clave es obligatoria.',
            'clave.string'    => 'La clave debe ser texto.',
        ]);

        $usuario = User::where('correo', $validated['correo'])->first();

        if (!$usuario || !Hash::check($validated['clave'], $usuario->getAuthPassword())) {
            return response()->json(['message' => 'Credenciales incorrectas.'], 401);
        }

        return response()->json([
            'usuario'    => $usuario,
            'token'      => JwtService::generar($usuario),
            'token_type' => 'bearer',
            'expires_in' => JwtService::ttlSegundos(),
        ]);
    }

    // Logout: JWT es stateless, no hay invalidación en servidor
    public function logout(Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'Sesión cerrada. Descarta el token en el cliente.',
        ]);
    }
}
