<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\WebAuthController;

// La página de inicio (la IP/dominio al levantar el server, SIN /proyectos)
// ahora es el login. Si el usuario ya tiene sesión iniciada, se le manda
// directo al listado de proyectos.
Route::get('/', [WebAuthController::class, 'showLanding'])->name('home');

Route::get('/login', [WebAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [WebAuthController::class, 'login']);
Route::get('/register', [WebAuthController::class, 'showRegister'])->name('register');
Route::post('/register', [WebAuthController::class, 'register']);
Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');

// Listar y ver detalle de proyectos se mantienen públicos, tal como estaba.
Route::get('/proyectos', [ProyectoController::class, 'vistaIndex'])->name('proyectos.index');

// Crear, editar y eliminar exige sesión iniciada (guard "web"). Van antes de
// /proyectos/{id} porque si no, el comodín {id} capturaría "create" como id.
Route::middleware('auth')->group(function () {
    Route::get('/proyectos/create', [ProyectoController::class, 'vistaCreate'])->name('proyectos.create');
    Route::get('/proyectos/{id}/edit', [ProyectoController::class, 'vistaEdit'])->name('proyectos.edit');
    Route::get('/proyectos/{id}/delete', [ProyectoController::class, 'vistaDelete'])->name('proyectos.delete');
});

// Debe ir al final porque {id} capturaría cualquier ruta /proyectos/algo de arriba.
Route::get('/proyectos/{id}', [ProyectoController::class, 'vistaShow'])->name('proyectos.show');