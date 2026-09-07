@extends('layouts.auth')

@section('content')
    <h2 class="text-xl font-bold text-neutral-900 mb-6">Crear Cuenta</h2>

    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="/register" class="space-y-4">
        @csrf
        <div>
            <label for="nombre" class="block text-sm font-medium text-neutral-700 mb-1">Nombre</label>
            <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" maxlength="100"
                   pattern="[A-Za-zÀ-ÿ\s]+"
                   title="Solo letras y espacios"
                   class="w-full border border-neutral-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none"
                   required autofocus>
        </div>
        <div>
            <label for="correo" class="block text-sm font-medium text-neutral-700 mb-1">Correo</label>
            <input type="email" id="correo" name="correo" value="{{ old('correo') }}" maxlength="255"
                   class="w-full border border-neutral-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none"
                   required>
        </div>
        <div>
            <label for="clave" class="block text-sm font-medium text-neutral-700 mb-1">Clave</label>
            <input type="password" id="clave" name="clave" minlength="8" maxlength="64"
                   class="w-full border border-neutral-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none"
                   required>
            <p class="text-xs text-neutral-500 mt-1">Mínimo 8 caracteres.</p>
        </div>
        <div>
            <label for="clave_confirmation" class="block text-sm font-medium text-neutral-700 mb-1">Confirmar clave</label>
            <input type="password" id="clave_confirmation" name="clave_confirmation" minlength="8" maxlength="64"
                   class="w-full border border-neutral-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none"
                   required>
        </div>
        <button type="submit" id="btn-registro"
                class="w-full bg-purple-600 text-white font-medium py-2.5 rounded-lg hover:bg-purple-700 transition text-sm disabled:opacity-60 disabled:cursor-not-allowed">
            Registrarme
        </button>
    </form>

    <p class="text-sm text-neutral-600 mt-6 text-center">
        ¿Ya tienes cuenta?
        <a href="/login" class="text-purple-600 font-medium hover:underline">Inicia sesión</a>
    </p>

    <script>
        document.querySelector('form[action="/register"]').addEventListener('submit', () => {
            const boton = document.getElementById('btn-registro');
            boton.disabled = true;
            boton.textContent = 'Creando cuenta…';
        });
    </script>
@endsection