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
    Schema::create('docente', function (Blueprint $table) {
        // Esta tabla también hereda de 'persona'
       $table->foreignId('persona_id')->primary()->constrained(table: 'persona')->cascadeOnDelete();
        $table->integer('anos_experiencia')->nullable();
        $table->date('fecha_ingreso')->nullable();
        $table->boolean('activo')->default(true);
        // $table->timestamps(); // Opcional
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::dropIfExists('docente');
}
};
