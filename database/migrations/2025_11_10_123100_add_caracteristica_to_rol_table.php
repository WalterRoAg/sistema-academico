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
    Schema::table('rol', function (Blueprint $table) {
        // La añadimos después de la columna 'nombre'
        $table->string('caracteristica', 255)->nullable()->after('nombre');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rol', function (Blueprint $table) {
            //
        });
    }
};
