<?php

namespace App\Http\Controllers;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="API Proyectos",
 *     version="1.0.0",
 *     description="Documentación de los endpoints de gestión de proyectos."
 * )
 * @OA\Server(url=L5_SWAGGER_CONST_HOST, description="Servidor API")
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 * @OA\Tag(name="Proyectos", description="CRUD de proyectos")
 */
abstract class Controller
{
    //
}