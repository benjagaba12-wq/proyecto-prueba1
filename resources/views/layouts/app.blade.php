<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Proyectos</title>
    @if (!empty($apiToken))
        {{-- Usado por el JS de resources/views/proyectos/*.blade.php para
             autenticar las llamadas fetch() a /api/proyectos --}}
        <meta name="api-token" content="{{ $apiToken }}">
    @endif
    @vite('resources/css/app.css')
</head>
<body class="bg-neutral-100 min-h-screen">

    <nav class="bg-neutral-950 shadow-sm mb-6">
        <div class="max-w-5xl mx-auto px-4 py-3 flex justify-between items-center">
            <a href="/proyectos" class="font-bold text-white flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-orange-600 flex items-center justify-center text-sm">P</span>
                Gestión de Proyectos
            </a>

            <div class="flex items-center gap-4 text-sm">
                <a href="/proyectos" class="text-neutral-300 hover:text-orange-500 transition">Proyectos</a>

                @auth
                    <span class="text-neutral-400">Hola, {{ auth()->user()->nombre }}</span>
                    <form method="POST" action="/logout">
                        @csrf
                        <button type="submit"
                                class="border border-orange-600 text-orange-500 px-3 py-1.5 rounded-md hover:bg-orange-600 hover:text-white transition">
                            Cerrar sesión
                        </button>
                    </form>
                @else
                    <a href="/login"
                       class="bg-orange-600 text-white px-3 py-1.5 rounded-md hover:bg-orange-700 transition">
                        Iniciar sesión
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="max-w-5xl mx-auto px-4">
        @yield('content')
    </div>
</body>
</html>