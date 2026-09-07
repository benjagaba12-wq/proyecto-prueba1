<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Proyecto",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="nombre", type="string", example="Sistema de Inventario"),
 *     @OA\Property(property="fecha_inicio", type="string", format="date", example="2026-01-15"),
 *     @OA\Property(property="estado", type="string", enum={"Pendiente","En curso","Completado","Cancelado"}, example="Pendiente"),
 *     @OA\Property(property="responsable", type="string", example="Juan Perez"),
 *     @OA\Property(property="monto", type="number", format="float", example=15000.50),
 *     @OA\Property(property="created_by", type="integer", example=1),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * @OA\Schema(
 *     schema="ProyectoInput",
 *     type="object",
 *     required={"nombre","fecha_inicio","estado","responsable","monto"},
 *     @OA\Property(property="nombre", type="string", example="Sistema de Inventario"),
 *     @OA\Property(property="fecha_inicio", type="string", format="date", example="2026-01-15"),
 *     @OA\Property(property="estado", type="string", enum={"Pendiente","En curso","Completado","Cancelado"}, example="Pendiente"),
 *     @OA\Property(property="responsable", type="string", example="Juan Perez"),
 *     @OA\Property(property="monto", type="number", format="float", example=15000.50)
 * )
 */
class ProyectoController extends Controller
{
    public const ESTADOS = ['Pendiente', 'En curso', 'Completado', 'Cancelado'];

    /**
     * @OA\Get(
     *     path="/proyectos",
     *     tags={"Proyectos"},
     *     summary="Listar todos los proyectos",
     *     @OA\Response(
     *         response=200,
     *         description="Listado de proyectos (arreglo vacío si no hay datos)",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Proyecto"))
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        return response()->json(Proyecto::all());
    }

    /**
     * @OA\Post(
     *     path="/proyectos",
     *     tags={"Proyectos"},
     *     summary="Crear un proyecto",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/ProyectoInput")),
     *     @OA\Response(response=201, description="Proyecto creado", @OA\JsonContent(ref="#/components/schemas/Proyecto")),
     *     @OA\Response(response=422, description="Campos requeridos faltantes o inválidos"),
     *     @OA\Response(response=401, description="No autenticado")
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:150', 'regex:/^[\pL0-9][\pL0-9 .,\-_]*$/u'],
            'fecha_inicio' => ['required', 'date', 'after_or_equal:2024-01-01'],
            'estado' => ['required', 'string', 'in:' . implode(',', self::ESTADOS)],
            'responsable' => ['required', 'string', 'max:150', 'regex:/^\pL+(?: \pL+)*$/u'],
            'monto' => ['required', 'numeric', 'min:0', 'max:999999999.99'],
        ], [
           'nombre.required' => 'El nombre del proyecto es obligatorio.',
            'nombre.string' => 'El nombre debe ser texto.',
            'nombre.max' => 'El nombre no puede superar los 150 caracteres.',
            'nombre.regex' => 'El nombre solo puede tener letras, números, espacios y . , - _',
            'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
            'fecha_inicio.date' => 'La fecha de inicio no es una fecha válida.',
            'fecha_inicio.after_or_equal' => 'La fecha de inicio no puede ser anterior al 2024.',
            'estado.required' => 'El estado es obligatorio.',
            'estado.string' => 'El estado debe ser texto.',
            'estado.in' => 'El estado debe ser uno de: ' . implode(', ', self::ESTADOS) . '.',
            'responsable.required' => 'El responsable es obligatorio.',
            'responsable.string' => 'El responsable debe ser texto.',
            'responsable.max' => 'El responsable no puede superar los 150 caracteres.',
            'responsable.regex' => 'El responsable solo debe contener letras y espacios simples entre palabras.',
            'monto.required' => 'El monto es obligatorio.',
            'monto.numeric' => 'El monto debe ser un número.',
            'monto.min' => 'El monto no puede ser negativo.',
            'monto.max' => 'El monto ingresado es demasiado grande.',
        ]);

        $validated['created_by'] = $request->user()->id;

        $proyecto = Proyecto::create($validated);

        return response()->json($proyecto, Response::HTTP_CREATED);
    }

    /**
     * @OA\Delete(
     *     path="/proyectos/{id}",
     *     tags={"Proyectos"},
     *     summary="Eliminar un proyecto por ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Proyecto eliminado, sin contenido"),
     *     @OA\Response(response=404, description="Proyecto no encontrado")
     * )
     */
    public function destroy(int $id): Response
    {
        $proyecto = Proyecto::find($id);

        if (!$proyecto) {
            return response()->json(['message' => 'Proyecto no encontrado'], Response::HTTP_NOT_FOUND);
        }

        $proyecto->delete();

        return response()->noContent();
    }

    /**
     * @OA\Get(
     *     path="/proyectos/{id}",
     *     tags={"Proyectos"},
     *     summary="Buscar un proyecto por ID",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Proyecto encontrado", @OA\JsonContent(ref="#/components/schemas/Proyecto")),
     *     @OA\Response(response=404, description="Proyecto no encontrado")
     * )
     */
    public function show(int $id): JsonResponse
    {
        $proyecto = Proyecto::find($id);

        if (!$proyecto) {
            return response()->json(['message' => 'Proyecto no encontrado'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($proyecto);
    }

    /**
     * @OA\Patch(
     *     path="/proyectos/{id}",
     *     tags={"Proyectos"},
     *     summary="Actualizar un proyecto por ID (parcial)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(@OA\JsonContent(ref="#/components/schemas/ProyectoInput")),
     *     @OA\Response(response=201, description="Proyecto actualizado", @OA\JsonContent(ref="#/components/schemas/Proyecto")),
     *     @OA\Response(response=404, description="Proyecto no encontrado")
     * )
     * @OA\Put(
     *     path="/proyectos/{id}",
     *     tags={"Proyectos"},
     *     summary="Actualizar un proyecto por ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(@OA\JsonContent(ref="#/components/schemas/ProyectoInput")),
     *     @OA\Response(response=201, description="Proyecto actualizado", @OA\JsonContent(ref="#/components/schemas/Proyecto")),
     *     @OA\Response(response=404, description="Proyecto no encontrado")
     * )
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $proyecto = Proyecto::find($id);

        if (!$proyecto) {
            return response()->json(['message' => 'Proyecto no encontrado'], Response::HTTP_NOT_FOUND);
        }

        $validated = $request->validate([
            'nombre' => ['sometimes', 'required', 'string', 'max:150', 'regex:/^[\pL0-9][\pL0-9 .,\-_]*$/u'],
            'fecha_inicio' => ['sometimes', 'required', 'date', 'after_or_equal:2024-01-01'],
            'estado' => ['sometimes', 'required', 'string', 'in:' . implode(',', self::ESTADOS)],
            'responsable' => ['sometimes', 'required', 'string', 'max:150', 'regex:/^\pL+(?: \pL+)*$/u'],
            'monto' => ['sometimes', 'required', 'numeric', 'min:0', 'max:999999999.99'],
        ], [
            'nombre.required' => 'El nombre del proyecto es obligatorio.',
            'nombre.string' => 'El nombre debe ser texto.',
            'nombre.max' => 'El nombre no puede superar los 150 caracteres.',
            'nombre.regex' => 'El nombre solo puede tener letras, números, espacios y . , - _',
            'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
            'fecha_inicio.date' => 'La fecha de inicio no es una fecha válida.',
            'fecha_inicio.after_or_equal' => 'La fecha de inicio no puede ser anterior al 2024.',
            'estado.required' => 'El estado es obligatorio.',
            'estado.string' => 'El estado debe ser texto.',
            'estado.in' => 'El estado debe ser uno de: ' . implode(', ', self::ESTADOS) . '.',
            'responsable.required' => 'El responsable es obligatorio.',
            'responsable.string' => 'El responsable debe ser texto.',
            'responsable.max' => 'El responsable no puede superar los 150 caracteres.',
            'responsable.regex' => 'El responsable solo debe contener letras y espacios simples entre palabras.',
            'monto.required' => 'El monto es obligatorio.',
            'monto.numeric' => 'El monto debe ser un número.',
            'monto.min' => 'El monto no puede ser negativo.',
            'monto.max' => 'El monto ingresado es demasiado grande.',
        ]);

        $proyecto->update($validated);

        return response()->json($proyecto, Response::HTTP_CREATED);
    }

    public function vistaIndex()
    {
        $proyectos = Proyecto::all();
        return view('proyectos.index', compact('proyectos'));
    }

    public function vistaCreate()
    {
        $estados = self::ESTADOS;
        return view('proyectos.create', compact('estados'));
    }

    public function vistaEdit(int $id)
    {
        $proyecto = Proyecto::findOrFail($id);
        $estados = self::ESTADOS;
        return view('proyectos.edit', compact('proyecto', 'estados'));
    }

    public function vistaDelete(int $id)
    {
        $proyecto = Proyecto::findOrFail($id);
        return view('proyectos.delete', compact('proyecto'));
    }

    public function vistaBuscar()
    {
        return view('proyectos.buscar');
    }

    public function vistaShow(int $id)
    {
        $proyecto = Proyecto::findOrFail($id);
        return view('proyectos.show', compact('proyecto'));
    }
}