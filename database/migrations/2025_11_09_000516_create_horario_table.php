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
    Schema::create('horario', function (Blueprint $table) {
        $table->id();
        $table->string('dia', 20);
        $table->time('hora_ini');
        $table->time('hora_fin');
        // $table->timestamps(); // Opcional

        // Restricción Única para evitar duplicados
        $table->unique(['dia', 'hora_ini', 'hora_fin']); 
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::dropIfExists('horario');
}
};
