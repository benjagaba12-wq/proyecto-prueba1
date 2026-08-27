@extends('layouts.app')

@section('content')

<div class="flex flex-wrap gap-4 justify-between items-center mb-4">
    <h1 class="text-2xl font-bold text-neutral-900">Listado de Proyectos</h1>
    <x-valor-uf />
    @auth
        <a href="/proyectos/create"
           class="bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700 text-sm font-medium">
            + Nuevo Proyecto
        </a>
    @else
        <a href="/login" class="text-sm text-orange-600 hover:underline">
            Inicia sesión para crear proyectos
        </a>
    @endauth
</div>

<div class="bg-white shadow rounded-lg overflow-hidden">
    <table class="w-full">
        <thead class="bg-neutral-900 text-left text-xs uppercase tracking-wide text-neutral-300">
            <tr>
                <th class="p-3">Nombre</th>
                <th class="p-3">Fecha Inicio</th>
                <th class="p-3">Estado</th>
                <th class="p-3">Responsable</th>
                <th class="p-3">Monto</th>
                <th class="p-3">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($proyectos as $proyecto)
            <tr class="border-t border-neutral-100 hover:bg-orange-50/40">
                <td class="p-3 font-medium text-neutral-800">{{ $proyecto['nombre'] }}</td>
                <td class="p-3 text-neutral-600">{{ $proyecto['fecha_inicio'] }}</td>
                <td class="p-3">
                    <span class="inline-block px-2 py-0.5 rounded-full text-xs bg-orange-100 text-orange-700">
                        {{ $proyecto['estado'] }}
                    </span>
                </td>
                <td class="p-3 text-neutral-600">{{ $proyecto['responsable'] }}</td>
                <td class="p-3 text-neutral-800">${{ number_format($proyecto['monto'], 0, ',', '.') }}</td>
                <td class="p-3 space-x-2 text-sm">
                    <a href="/proyectos/{{ $proyecto['id'] }}" class="text-neutral-600 hover:underline">Ver</a>
                    @auth
                        <a href="/proyectos/{{ $proyecto['id'] }}/edit" class="text-orange-600 hover:underline">Editar</a>
                        <a href="/proyectos/{{ $proyecto['id'] }}/delete" class="text-red-600 hover:underline">Eliminar</a>
                    @endauth
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="p-6 text-center text-neutral-500">Aún no hay proyectos creados.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection