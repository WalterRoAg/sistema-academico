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
    Schema::create('materia', function (Blueprint $table) {
        // En Laravel, si tu PK no es un 'id' numérico, usas primary()
        $table->string('sigla', 20)->primary(); 
        $table->string('nombre', 100);
        $table->string('nivel', 50)->nullable();
        // $table->timestamps(); // Opcional
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::dropIfExists('materia');
}
};
