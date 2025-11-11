<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up(): void
{
    Schema::create('horario_clase', function (Blueprint $table) {
        $table->id();

        // Llaves Foráneas (FK)
        $table->foreignId('horario_id')->constrained('horario')->cascadeOnDelete();
        $table->foreignId('aula_id')->constrained('aula')->cascadeOnDelete();
        $table->foreignId('grupo_materia_id')->constrained('grupo_materia')->cascadeOnDelete();
        $table->foreignId('docente_persona_id')->constrained('docente', 'persona_id')->cascadeOnDelete();
        $table->foreignId('periodo_id')->constrained('periodo_academico')->cascadeOnDelete();

        // $table->timestamps(); // Opcional

        // Restricciones de conflicto (UNIQUE)
        $table->unique(['periodo_id', 'horario_id', 'aula_id'], 'hc_periodo_horario_aula_unique');
        $table->unique(['periodo_id', 'horario_id', 'docente_persona_id'], 'hc_periodo_horario_docente_unique');
        $table->unique(['periodo_id', 'horario_id', 'grupo_materia_id'], 'hc_periodo_horario_grupo_unique');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::dropIfExists('horario_clase');
}
};
