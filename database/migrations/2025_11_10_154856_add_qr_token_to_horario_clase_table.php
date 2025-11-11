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
    Schema::table('horario_clase', function (Blueprint $table) {
        // Guarda el token único de 40 caracteres
        $table->string('token_qr', 40)->nullable()->unique()->after('id'); 

        // Guarda la fecha y hora exactas en que el token deja de ser válido
        $table->timestamp('token_expira_en')->nullable()->after('token_qr');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('horario_clase', function (Blueprint $table) {
            //
        });
    }
};
