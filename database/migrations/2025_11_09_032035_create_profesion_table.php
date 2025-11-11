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
    Schema::create('profesion', function (Blueprint $table) {
        $table->id(); // id INT GENERATED ALWAYS AS IDENTITY PRIMARY KEY
        $table->string('nombre', 100)->unique();
        // $table->timestamps(); // Opcional
    });

    // Esta es la tabla pivote que la une con 'persona'
    Schema::create('profesion_persona', function (Blueprint $table) {
        // Usamos foreignId para llaves foráneas
        $table->foreignId('persona_id')->constrained(table: 'persona')->cascadeOnDelete();
        $table->foreignId('profesion_id')->constrained('profesion')->cascadeOnDelete();
        $table->string('nivel', 50)->nullable(); // ej. 'Licenciatura'

        // Definimos la llave primaria compuesta
        $table->primary(['persona_id', 'profesion_id']);

        // $table->timestamps(); // Opcional
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::dropIfExists('profesion_persona');
    Schema::dropIfExists('profesion');
}
};
