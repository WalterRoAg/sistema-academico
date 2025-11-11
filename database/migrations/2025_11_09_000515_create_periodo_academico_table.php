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
    Schema::create('periodo_academico', function (Blueprint $table) {
        $table->id(); // id INT GENERATED ALWAYS AS IDENTITY PRIMARY KEY
        $table->string('nombre', 50)->unique(); // ej: "1-2025"
        $table->date('fecha_inicio');
        $table->date('fecha_fin');
        $table->boolean('activo')->default(false);
        // $table->timestamps(); // Opcional
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periodo_academico');
    }
};
