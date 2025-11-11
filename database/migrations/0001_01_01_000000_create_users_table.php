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
        // 1. Creamos la tabla 'rol' (de nuestro script)
        Schema::create('rol', function (Blueprint $table) {
            $table->id(); // INT GENERATED ALWAYS AS IDENTITY PRIMARY KEY
            $table->string('nombre', 100)->unique();
        });

        // 2. Creamos la tabla 'persona' (de nuestro script)
        Schema::create('persona', function (Blueprint $table) {
            $table->id();
            $table->string('carnet', 50)->unique();
            $table->string('nombre', 100);
            $table->string('telefono', 20)->nullable();
        });

        // 3. Borramos las tablas 'users' por defecto (si existen)
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');

        // 4. Creamos nuestra tabla 'users' personalizada
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->unique(); // Para el login (CI)
            $table->string('correo', 100)->unique();
            $table->string('contrasena', 255);
            $table->boolean('activo')->default(true);
            
            // Llaves Foráneas (FK) - Usamos (table: '...') para apuntar al nombre singular
            $table->foreignId('rol_id')->nullable()->constrained(table: 'rol')->nullOnDelete();
            $table->foreignId('persona_id')->unique()->constrained(table: 'persona')->cascadeOnDelete();

            // $table->rememberToken(); // No lo usaremos por ahora
            $table->timestamps(); // (Estándar de Laravel, lo dejamos)
        });

        // 5. Re-creamos las tablas de Laravel que sí son útiles
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
        Schema::dropIfExists('persona');
        Schema::dropIfExists('rol');
    }
};