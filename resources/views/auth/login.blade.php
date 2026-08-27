@extends('layouts.auth')

@section('content')
    <h2 class="text-xl font-bold text-neutral-900 mb-6">Iniciar Sesión</h2>

    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="/login" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-neutral-700 mb-1">Correo</label>
            <input type="email" name="correo" value="{{ old('correo') }}" maxlength="255"
                   class="w-full border border-neutral-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none"
                   required autofocus>
        </div>
        <div>
            <label class="block text-sm font-medium text-neutral-700 mb-1">Clave</label>
            <input type="password" name="clave" maxlength="64"
                   class="w-full border border-neutral-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none"
                   required>
        </div>
        <button type="submit"
                class="w-full bg-orange-600 text-white font-medium py-2.5 rounded-lg hover:bg-orange-700 transition text-sm">
            Ingresar
        </button>
    </form>

    <p class="text-sm text-neutral-600 mt-6 text-center">
        ¿No tienes cuenta?
        <a href="/register" class="text-orange-600 font-medium hover:underline">Regístrate aquí</a>
    </p>
@endsection