<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

// Controlador de autenticación web (sesión, guard "web")
class WebAuthController extends Controller
{
    // Página de inicio ("/"). Si ya hay sesión, va directo al listado.
    public function showLanding(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect('/proyectos');
        }

        return view('auth.login');
    }

    // Vista de login (también destino del middleware 'auth' si alguien
    // entra directo a una URL protegida sin sesión).
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect('/proyectos');
        }

        return view('auth.login');
    }

    // Vista de registro
    public function showRegister(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect('/proyectos');
        }

        return view('auth.register');
    }

    // Procesa login por sesión
    public function login(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'correo' => 'required|email:rfc|max:255',
            'clave'  => 'required|string',
        ], [
            'correo.required' => 'El correo es obligatorio.',
            'correo.email'    => 'El correo debe ser una dirección válida.',
            'clave.required'  => 'La clave es obligatoria.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->onlyInput('correo')
                ->with('auth_form', 'login');
        }

        $datos = $validator->validated();

        $credenciales = [
            'correo'   => $datos['correo'],
            'password' => $datos['clave'], // Auth::attempt requiere "password"
        ];

        if (!Auth::attempt($credenciales)) {
            return back()
                ->withErrors(['clave' => 'Credenciales incorrectas.'])
                ->onlyInput('correo')
                ->with('auth_form', 'login');
        }

        $request->session()->regenerate();

        return redirect()->intended('/proyectos');
    }

    // Procesa registro por sesión
    public function register(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100|regex:/^\pL+(?: \pL+)*$/u',
            'correo' => 'required|email:rfc|max:255|unique:users,correo',
            'clave'  => 'required|string|min:8|max:64|confirmed',
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.regex'    => 'El nombre solo debe contener letras y espacios simples entre palabras.',
            'correo.required' => 'El correo es obligatorio.',
            'correo.email'    => 'El correo debe ser una dirección válida.',
            'correo.unique'   => 'Ese correo ya está registrado.',
            'clave.required'  => 'La clave es obligatoria.',
            'clave.min'       => 'La clave debe tener al menos 8 caracteres.',
            'clave.max'       => 'La clave no puede superar los 64 caracteres.',
            'clave.confirmed' => 'La confirmación de clave no coincide.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->onlyInput('nombre', 'correo')
                ->with('auth_form', 'register');
        }

        $datos = $validator->validated();

        $usuario = User::create([
            'nombre' => $datos['nombre'],
            'correo' => $datos['correo'],
            'clave'  => Hash::make($datos['clave']), // cifrado bcrypt
        ]);

        Auth::login($usuario);
        $request->session()->regenerate();

        return redirect()->intended('/proyectos');
    }

    // Cierra sesión
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}