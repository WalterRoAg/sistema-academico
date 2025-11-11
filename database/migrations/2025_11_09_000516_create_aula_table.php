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
    Schema::create('aula', function (Blueprint $table) {
        $table->id();
        $table->string('numero', 20)->unique();
        $table->integer('piso')->nullable();
        $table->integer('capacidad')->nullable();
        $table->boolean('activo')->default(true);
        // $table->timestamps(); // Opcional
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::dropIfExists('aula');
}
};
