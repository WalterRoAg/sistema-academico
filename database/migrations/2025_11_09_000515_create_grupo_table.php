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
    Schema::create('grupo', function (Blueprint $table) {
        $table->id(); // id INT GENERATED ALWAYS AS IDENTITY PRIMARY KEY
        $table->string('nombre', 100)->unique(); // ej. 'SA'
        // $table->timestamps(); // Opcional
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::dropIfExists('grupo');
}
};
