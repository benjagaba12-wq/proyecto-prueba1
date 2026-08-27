<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Proyecto extends Model
{
    /**
     * Tabla real creada por la migración
     * 2026_08_18_000002_create_proyectos_table.php
     */
    protected $table = 'proyectos';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nombre',
        'fecha_inicio',
        'estado',
        'responsable',
        'monto',
        'created_by',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'date:Y-m-d',
            'monto' => 'decimal:2',
        ];
    }

    /**
     * Usuario que creó el proyecto (created_by -> users.id).
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
