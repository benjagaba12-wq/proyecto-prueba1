<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestión de Proyectos</title>
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen bg-neutral-950 flex items-center justify-center px-4">

    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <span class="inline-flex items-center justify-center w-14 h-14 rounded-xl bg-orange-600 text-white text-2xl font-bold mb-3">P</span>
            <h1 class="text-2xl font-bold text-white">Gestión de Proyectos</h1>
            <p class="text-neutral-400 text-sm mt-1">Ingresa o crea tu cuenta para continuar</p>
        </div>

        <div class="bg-white rounded-2xl shadow-2xl p-8 border-t-4 border-orange-600">
            @yield('content')
        </div>

        <p class="text-center text-neutral-500 text-xs mt-6">
            &copy; {{ date('Y') }} Gestión de Proyectos
        </p>
    </div>

</body>
</html>