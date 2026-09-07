<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestión de Proyectos</title>
    @if ($apiToken)
        <meta name="api-token" content="{{ $apiToken }}">
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-black text-neutral-100">
    <div class="aurora-bg"></div>

    <header class="border-b border-white/10 bg-black/30 backdrop-blur-sm">
        <div class="max-w-5xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="/proyectos" class="font-semibold text-white tracking-tight">Gestión de Proyectos</a>
            <div class="flex items-center gap-4 text-sm">
                <a href="/proyectos"
                   class="transition {{ request()->routeIs('proyectos.index') ? 'text-purple-400 font-medium' : 'text-neutral-300 hover:text-purple-400' }}">Proyectos</a>
                <a href="/proyectos/buscar"
                   class="transition {{ request()->routeIs('proyectos.buscar') ? 'text-purple-400 font-medium' : 'text-neutral-300 hover:text-purple-400' }}">Buscar</a>

                @auth
                    <span class="text-neutral-400">Hola, {{ auth()->user()->nombre }}</span>
                    <form method="POST" action="/logout">
                        @csrf
                        <button type="submit"
                                class="border border-purple-600 text-purple-400 px-3 py-1.5 rounded-md hover:bg-purple-600 hover:text-white transition">
                            Cerrar sesión
                        </button>
                    </form>
                @else
                    <a href="/login"
                       class="bg-purple-600 text-white px-3 py-1.5 rounded-md hover:bg-purple-700 transition">
                        Iniciar sesión
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-4 py-8">
        @yield('content')
    </main>
</body>
</html>