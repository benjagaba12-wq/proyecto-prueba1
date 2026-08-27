<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ValorUf extends Component
{
    public $valor;
    public $fecha;

    public function __construct()
    {
        // No se usa Cache::remember() a secas porque este también cachearía
        // un null si el servicio externo falla, dejando "No disponible" en
        // pantalla por 6 horas aunque el servicio se recupere antes.
        $data = Cache::get('uf_del_dia');

        if ($data === null) {
            try {
                $response = Http::timeout(5)->get('https://mindicador.cl/api/uf');

                if ($response->successful()) {
                    $data = $response->json();
                    Cache::put('uf_del_dia', $data, now()->addHours(6));
                }
            } catch (\Throwable $e) {
                // Si el servicio externo no responde (timeout, DNS, etc.),
                // no debe tumbar toda la vista de proyectos: se muestra
                // "No disponible" y se reintenta en la siguiente carga
                // (no se cachea el fallo).
                $data = null;
            }
        }

        if ($data && isset($data['serie'][0])) {
            $this->valor = $data['serie'][0]['valor'];
            $this->fecha = $data['serie'][0]['fecha'];
        } else {
            $this->valor = null;
            $this->fecha = null;
        }
    }

    public function render()
    {
        return view('components.valor-uf');
    }
}