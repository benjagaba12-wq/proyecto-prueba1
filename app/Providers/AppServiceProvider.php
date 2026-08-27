<?php

namespace App\Providers;

use App\Services\JwtService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Las vistas de proyectos (create/edit/delete) llaman a /api/proyectos
        // por fetch(), y esa API ahora exige JWT en las rutas de escritura.
        // Para que la sesión web (guard "web") pueda usarlas sin duplicar el
        // login, se genera un JWT de corta vida para el usuario autenticado
        // en cada carga de página y se expone a la vista vía $apiToken.
        View::composer('layouts.app', function ($view) {
            $view->with(
                'apiToken',
                Auth::check() ? JwtService::generar(Auth::user()) : null
            );
        });
    }
}
