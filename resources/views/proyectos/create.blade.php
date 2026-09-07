@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto p-6 bg-white shadow rounded-lg mt-6 border-t-4 border-purple-600">
    <h1 class="text-xl font-bold mb-4 text-neutral-900">Nuevo Proyecto</h1>
    @auth
    <form id="form-crear" class="space-y-4" novalidate>
        <div>
            <label for="nombre" class="block text-sm font-medium text-neutral-700">Nombre</label>
            <input type="text" id="nombre" name="nombre" maxlength="150" required
                   class="mt-1 w-full border-neutral-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
            <p class="text-xs text-red-600 mt-1 hidden" data-error="nombre"></p>
        </div>
        <div>
            <label for="fecha_inicio" class="block text-sm font-medium text-neutral-700">Fecha de Inicio</label>
            <input type="date" id="fecha_inicio" name="fecha_inicio" min="2024-01-01" required
                   class="mt-1 w-full border-neutral-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
            <p class="text-xs text-red-600 mt-1 hidden" data-error="fecha_inicio"></p>
        </div>
        <div>
            <label for="estado" class="block text-sm font-medium text-neutral-700">Estado</label>
            <select id="estado" name="estado" required
                    class="mt-1 w-full border-neutral-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                <option value="">Selecciona un estado</option>
                @foreach ($estados as $estado)
                    <option value="{{ $estado }}">{{ $estado }}</option>
                @endforeach
            </select>
            <p class="text-xs text-red-600 mt-1 hidden" data-error="estado"></p>
        </div>
        <div>
            <label for="responsable" class="block text-sm font-medium text-neutral-700">Responsable</label>
            <input type="text" id="responsable" name="responsable" maxlength="150" required
                   class="mt-1 w-full border-neutral-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
            <p class="text-xs text-red-600 mt-1 hidden" data-error="responsable"></p>
        </div>
        <div>
            <label for="monto" class="block text-sm font-medium text-neutral-700">Monto</label>
            <input type="number" id="monto" step="0.01" min="0" name="monto" required
                   class="mt-1 w-full border-neutral-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
            <p class="text-xs text-red-600 mt-1 hidden" data-error="monto"></p>
        </div>
        <p class="text-sm text-red-600 hidden" id="error-general"></p>
        <button type="submit" id="btn-guardar"
                class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 disabled:opacity-60 disabled:cursor-not-allowed">
            Guardar
        </button>
    </form>
    @else
    <p class="text-sm text-red-600">
        Debes <a href="/login" class="underline">iniciar sesión</a> para crear un proyecto,
        ya que queda asociado a tu usuario.
    </p>
    @endauth
</div>

<script>
document.getElementById('form-crear')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;

    form.querySelectorAll('[data-error]').forEach(el => { el.classList.add('hidden'); el.textContent = ''; });
    document.getElementById('error-general').classList.add('hidden');

    const boton = document.getElementById('btn-guardar');
    boton.disabled = true;
    boton.textContent = 'Guardando…';

    const datos = Object.fromEntries(new FormData(form));
    const token = document.querySelector('meta[name="api-token"]')?.content;

    const res = await fetch('/api/proyectos', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            ...(token ? { 'Authorization': `Bearer ${token}` } : {}),
        },
        body: JSON.stringify(datos)
    });

    if (res.ok) {
        window.location.href = '/proyectos?creado=1';
        return;
    }

    boton.disabled = false;
    boton.textContent = 'Guardar';

    if (res.status === 422) {
        const data = await res.json();
        Object.entries(data.errors || {}).forEach(([campo, mensajes]) => {
            const el = form.querySelector(`[data-error="${campo}"]`);
            if (el) {
                el.textContent = mensajes[0];
                el.classList.remove('hidden');
            }
        });
    } else {
        const general = document.getElementById('error-general');
        general.textContent = 'Ocurrió un error al crear el proyecto. Intenta nuevamente.';
        general.classList.remove('hidden');
    }
});
</script>
@endsection