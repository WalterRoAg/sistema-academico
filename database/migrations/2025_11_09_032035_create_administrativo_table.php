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
    Schema::create('administrativo', function (Blueprint $table) {
        // Esta tabla hereda de 'persona', por lo que su PK es también una FK
     $table->foreignId('persona_id')->primary()->constrained(table: 'persona')->cascadeOnDelete();
        $table->string('cargo', 100);
        // $table->timestamps(); // Opcional
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::dropIfExists('administrativo');
}
};
