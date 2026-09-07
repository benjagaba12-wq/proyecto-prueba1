<?php

//rutas

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\AuthController;

// Autenticación (JWT). Estas son las únicas rutas de la API que piden clave/token;
Route::post('/registro', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('validar.auth');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('validar.auth');

// Lectura de proyectos: queda pública a propósito (listado y detalle).
Route::get('/proyectos', [ProyectoController::class, 'index']);
Route::get('/proyectos/{id}', [ProyectoController::class, 'show']);

// Escritura de proyectos: exige JWT válido. Evita que cualquier visitante
// anónimo pueda crear, editar o borrar proyectos, y evita que "created_by"
// pueda ser falsificado desde el cliente (ver ProyectoController::store).
Route::middleware('validar.auth')->group(function () {
    Route::post('/proyectos', [ProyectoController::class, 'store']);
    Route::match(['put', 'patch'], '/proyectos/{id}', [ProyectoController::class, 'update']);
    Route::delete('/proyectos/{id}', [ProyectoController::class, 'destroy']);
});