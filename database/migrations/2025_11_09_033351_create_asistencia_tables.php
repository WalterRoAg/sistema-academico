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
    // Tabla para el QR dinámico
    Schema::create('asistencia_tokens', function (Blueprint $table) {
        $table->id();
        $table->string('token', 100)->unique();
        $table->timestamp('expira_en');
        $table->boolean('utilizado')->default(false);
        // $table->timestamps(); // Opcional
    });

    // Tabla para el registro de asistencia
    Schema::create('asistencia', function (Blueprint $table) {
        $table->id();
        $table->timestamp('fecha_hora')->default(now());

        $table->foreignId('horario_clase_id')->constrained('horario_clase')->cascadeOnDelete();

        $table->string('estado', 20)->default('Presente'); // 'Presente', 'Ausente', 'Licencia'
        $table->string('observacion', 255)->nullable();
        // $table->timestamps(); // Opcional
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::dropIfExists('asistencia');
    Schema::dropIfExists('asistencia_tokens');
}
};
