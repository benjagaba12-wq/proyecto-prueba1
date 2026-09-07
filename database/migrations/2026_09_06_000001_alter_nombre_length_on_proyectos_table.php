<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // La validación de 'nombre' permite hasta 150 caracteres, pero la
    // columna original quedó en varchar(100). Se amplía sin tocar datos
    // existentes ni la migración inicial (que ya corrió en el entorno).
    public function up(): void
    {
        Schema::table('proyectos', function (Blueprint $table) {
            $table->string('nombre', 150)->change();
        });
    }

    public function down(): void
    {
        Schema::table('proyectos', function (Blueprint $table) {
            $table->string('nombre', 100)->change();
        });
    }
};
