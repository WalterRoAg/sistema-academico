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
    Schema::create('grupo_materia', function (Blueprint $table) {
        $table->id();

        // Llaves Foráneas (FK)
        $table->foreignId('grupo_id')->constrained('grupo')->cascadeOnDelete();

        // Laravel no tiene un helper para FK a columnas 'string' (sigla)
        // Lo hacemos manualmente:
        $table->string('materia_sigla', 20);
        $table->foreign('materia_sigla')->references('sigla')->on('materia')->cascadeOnDelete();

        $table->foreignId('periodo_id')->constrained('periodo_academico')->cascadeOnDelete();

        $table->boolean('activo')->default(true);
        // $table->timestamps(); // Opcional

        // Restricción Única
        $table->unique(['grupo_id', 'materia_sigla', 'periodo_id'], 'gm_periodo_unique');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::dropIfExists('grupo_materia');
}
};
