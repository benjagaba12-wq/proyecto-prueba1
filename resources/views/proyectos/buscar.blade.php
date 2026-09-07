@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto p-6 bg-white shadow rounded-lg mt-6 border-t-4 border-purple-600">
    <h1 class="text-xl font-bold text-neutral-900 mb-4">Buscar proyecto por ID</h1>

    <div class="mb-4">
        <label for="buscar-id" class="block text-sm font-medium text-neutral-700 mb-1">ID del proyecto</label>
        <div class="flex gap-2">
            <input type="number" id="buscar-id" min="1" placeholder="Ej: 5"
                   class="flex-1 border border-neutral-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
            <button id="btn-buscar"
                    class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 disabled:opacity-60 disabled:cursor-not-allowed text-sm font-medium min-w-[90px]">
                Buscar
            </button>
        </div>
    </div>

    <p class="text-sm text-red-600 mb-4 hidden" id="error-general"></p>

    <dl class="grid grid-cols-2 gap-y-3 text-sm text-neutral-700 hidden" id="resultado">
        <dt class="font-medium text-neutral-500">Nombre</dt><dd id="res-nombre"></dd>
        <dt class="font-medium text-neutral-500">Fecha de Inicio</dt><dd id="res-fecha"></dd>
        <dt class="font-medium text-neutral-500">Estado</dt><dd id="res-estado"></dd>
        <dt class="font-medium text-neutral-500">Responsable</dt><dd id="res-responsable"></dd>
        <dt class="font-medium text-neutral-500">Monto</dt><dd id="res-monto"></dd>
    </dl>

    <a href="/proyectos" class="inline-block mt-5 text-sm text-purple-600 hover:underline">&larr; Volver al listado</a>
</div>

<script>
const input = document.getElementById('buscar-id');
const boton = document.getElementById('btn-buscar');
const error = document.getElementById('error-general');
const resultado = document.getElementById('resultado');

async function buscar() {
    const id = input.value.trim();
    error.classList.add('hidden');
    resultado.classList.add('hidden');

    if (!id || Number(id) <= 0) {
        error.textContent = 'Ingresa un ID válido.';
        error.classList.remove('hidden');
        return;
    }

    boton.disabled = true;
    const textoOriginal = boton.textContent;
    boton.textContent = 'Buscando…';

    const res = await fetch(`/api/proyectos/${id}`, {
        headers: { 'Accept': 'application/json' }
    });

    boton.disabled = false;
    boton.textContent = textoOriginal;

    if (res.status === 200) {
        const proyecto = await res.json();
        document.getElementById('res-nombre').textContent = proyecto.nombre;
        document.getElementById('res-fecha').textContent = proyecto.fecha_inicio;
        document.getElementById('res-estado').textContent = proyecto.estado;
        document.getElementById('res-responsable').textContent = proyecto.responsable;
        document.getElementById('res-monto').textContent =
            `$${Number(proyecto.monto).toLocaleString('es-CL', { maximumFractionDigits: 0 })}`;
        resultado.classList.remove('hidden');
    } else if (res.status === 404) {
        error.textContent = 'No existe un proyecto con ese ID.';
        error.classList.remove('hidden');
    } else {
        error.textContent = 'Ocurrió un error al buscar el proyecto.';
        error.classList.remove('hidden');
    }
}

boton.addEventListener('click', buscar);
input.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') buscar();
});
</script>
@endsection