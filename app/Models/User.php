<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * Las columnas reales de la tabla son nombre/correo/clave
     * (ver migración rename_columns_on_users_table).
     *
     * @var list<string>
     */
    protected $fillable = [
        'nombre',
        'correo',
        'clave',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'clave',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'clave' => 'hashed',
        ];
    }

    /**
     * Laravel busca por defecto la columna "password" para Auth::attempt()
     * (guard web, usado por el login de las vistas). Como la columna real se
     * llama "clave", se sobreescribe explícitamente aquí.
     */
    public function getAuthPassword(): string
    {
        return $this->clave;
    }

    /**
     * Proyectos creados por este usuario (proyectos.created_by -> users.id).
     */
    public function proyectos()
    {
        return $this->hasMany(Proyecto::class, 'created_by');
    }
}
