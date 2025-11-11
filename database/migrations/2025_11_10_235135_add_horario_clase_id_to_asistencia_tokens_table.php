<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * (Añade la columna faltante)
     */
    public function up(): void
    {
        Schema::table('asistencia_tokens', function (Blueprint $table) {
            // Añadimos la columna de la clave foránea
            $table->foreignId('horario_clase_id')
                  ->nullable() // Permite nulos temporalmente
                  ->constrained('horario_clase') // Apunta a la tabla 'horario_clase'
                  ->cascadeOnDelete(); // Si se borra la clase, se borra el token
        });
    }

    /**
     * Reverse the migrations.
     * (Define cómo revertir el cambio)
     */
  public function down(): void
    {
        Schema::table('asistencia_tokens', function (Blueprint $table) {
            // Asegúrate de que ambas líneas estén de vuelta
            $table->dropForeign(['horario_clase_id']);
            $table->dropColumn('horario_clase_id');
        });
    }
};