@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto p-6 bg-white shadow rounded-lg mt-6 border-t-4 border-orange-600">
    <h1 class="text-xl font-bold text-neutral-900 mb-4">{{ $proyecto['nombre'] }}</h1>
    <dl class="grid grid-cols-2 gap-y-3 text-sm text-neutral-700">
        <dt class="font-medium text-neutral-500">Fecha de Inicio</dt><dd>{{ $proyecto['fecha_inicio'] }}</dd>
        <dt class="font-medium text-neutral-500">Estado</dt>
        <dd>
            <span class="inline-block px-2 py-0.5 rounded-full text-xs bg-orange-100 text-orange-700">
                {{ $proyecto['estado'] }}
            </span>
        </dd>
        <dt class="font-medium text-neutral-500">Responsable</dt><dd>{{ $proyecto['responsable'] }}</dd>
        <dt class="font-medium text-neutral-500">Monto</dt><dd>${{ number_format($proyecto['monto'], 0, ',', '.') }}</dd>
    </dl>
    <a href="/proyectos" class="inline-block mt-5 text-sm text-orange-600 hover:underline">&larr; Volver al listado</a>
</div>
@endsection