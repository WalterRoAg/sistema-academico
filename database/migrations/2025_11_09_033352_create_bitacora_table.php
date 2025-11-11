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
    Schema::create('bitacora', function (Blueprint $table) {
        $table->id();
        $table->timestamp('fecha_hora')->default(now());

        // Hacemos el usuario_id anulable, por si el sistema (no un usuario) registra algo
        $table->foreignId('usuario_id')->nullable()->constrained('users')->nullOnDelete();

        $table->string('accion', 50);
        $table->string('entidad', 50)->nullable();
        $table->integer('entidad_id')->nullable();
        $table->text('descripcion')->nullable();
        $table->string('ip_address', 45)->nullable();
        // $table->timestamps(); // Opcional, aunque fecha_hora ya cumple esa función
    });
}

    /**
     * Reverse the migrations.
     */
   public function down(): void
{
    Schema::dropIfExists('bitacora');
}
};
