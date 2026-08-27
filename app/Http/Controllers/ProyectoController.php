<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ProyectoController extends Controller
{
    /**
     * Estados permitidos para un proyecto. Centralizados aquí para que
     * el <select> del formulario y la validación usen la misma lista.
     */
    public const ESTADOS = ['Pendiente', 'En curso', 'Completado', 'Cancelado'];

    public function index(): JsonResponse
    {
        return response()->json(Proyecto::all());
    }

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
            'nombre.max' => 'El nombre no puede superar los 150 caracteres.',
            'nombre.regex' => 'El nombre solo puede tener letras, números, espacios y . , - _',
            'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
            'fecha_inicio.date' => 'La fecha de inicio no es una fecha válida.',
            'fecha_inicio.after_or_equal' => 'La fecha de inicio no puede ser anterior al 2024.',
            'estado.required' => 'El estado es obligatorio.',
            'estado.in' => 'El estado debe ser uno de: ' . implode(', ', self::ESTADOS) . '.',
            'responsable.required' => 'El responsable es obligatorio.',
            'responsable.max' => 'El responsable no puede superar los 150 caracteres.',
            'responsable.regex' => 'El responsable solo debe contener letras y espacios simples entre palabras.',
            'monto.required' => 'El monto es obligatorio.',
            'monto.numeric' => 'El monto debe ser un número.',
            'monto.min' => 'El monto no puede ser negativo.',
            'monto.max' => 'El monto ingresado es demasiado grande.',
        ]);

        // Esta ruta exige JWT (middleware 'validar.auth'), así que el usuario
        // que crea el proyecto SIEMPRE se toma del token, nunca de lo que
        // envíe el cliente en el body. Evita falsificar created_by.
        $validated['created_by'] = $request->user()->id;

        $proyecto = Proyecto::create($validated);

        return response()->json($proyecto, Response::HTTP_CREATED);
    }

    public function destroy(int $id): JsonResponse
    {
        $proyecto = Proyecto::find($id);

        if (!$proyecto) {
            return response()->json(['message' => 'Proyecto no encontrado'], Response::HTTP_NOT_FOUND);
        }

        $proyecto->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function show(int $id): JsonResponse
    {
        $proyecto = Proyecto::find($id);

        if (!$proyecto) {
            return response()->json(['message' => 'Proyecto no encontrado'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($proyecto);
    }

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
            'nombre.regex' => 'El nombre solo puede tener letras, números, espacios y . , - _',
            'fecha_inicio.after_or_equal' => 'La fecha de inicio no puede ser anterior al 2024.',
            'estado.in' => 'El estado debe ser uno de: ' . implode(', ', self::ESTADOS) . '.',
            'responsable.regex' => 'El responsable solo debe contener letras y espacios simples entre palabras.',
        ]);

        $proyecto->update($validated);

        return response()->json($proyecto);
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

    public function vistaShow(int $id)
    {
        $proyecto = Proyecto::findOrFail($id);
        return view('proyectos.show', compact('proyecto'));
    }
}