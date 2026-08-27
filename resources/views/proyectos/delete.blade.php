@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto p-6 bg-white shadow rounded-lg mt-6 text-center border-t-4 border-orange-600">
    <p class="text-neutral-700 mb-4">¿Confirmas eliminar el proyecto <strong>{{ $proyecto['nombre'] }}</strong>?</p>
    <p class="text-sm text-red-600 mb-4 hidden" id="error-general"></p>
    <div class="flex gap-3 justify-center">
        <a href="/proyectos" class="border border-neutral-300 text-neutral-700 px-4 py-2 rounded-md hover:bg-neutral-50">
            Cancelar
        </a>
        <button id="btn-eliminar" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">
            Eliminar
        </button>
    </div>
</div>

<script>
document.getElementById('btn-eliminar').addEventListener('click', async () => {
    const token = document.querySelector('meta[name="api-token"]')?.content;

    const res = await fetch('/api/proyectos/{{ $proyecto['id'] }}', {
        method: 'DELETE',
        headers: {
            'Accept': 'application/json',
            ...(token ? { 'Authorization': `Bearer ${token}` } : {}),
        }
    });

    if (res.status === 204) {
        window.location.href = '/proyectos';
    } else {
        const general = document.getElementById('error-general');
        general.textContent = 'Ocurrió un error al eliminar el proyecto.';
        general.classList.remove('hidden');
    }
});
</script>
@endsection