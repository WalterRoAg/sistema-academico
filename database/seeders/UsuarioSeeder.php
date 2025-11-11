<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Persona;
use App\Models\User; // <-- ¡ESTA LÍNEA FALTABA O ERA INCORRECTA!
use App\Models\Rol;
use App\Models\Docente;
use App\Models\Administrativo;
use Illuminate\Support\Facades\Hash; // Importante para hashear la contraseña

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // --- 1. Obtener los Roles ---
        $rolAdmin = Rol::where('nombre', 'administrador')->first();
        $rolCoord = Rol::where('nombre', 'coordinador')->first();
        $rolDocente = Rol::where('nombre', 'docente')->first();

        // --- 2. Crear al Administrador (Tus datos) ---
        $personaAdmin = Persona::firstOrCreate(
            ['carnet' => '5555555'], // Carnet único
            [
                'nombre' => 'Jeanpold Admin', // Nombre completo
                'telefono' => '77755555'
            ]
        );
        // Crear su perfil administrativo
        Administrativo::firstOrCreate(
            ['persona_id' => $personaAdmin->id],
            ['cargo' => 'Administrador TI']
        );
        // Crear su cuenta de usuario
        User::firstOrCreate(
            ['correo' => 'jeanpold.admin@ficct.bo'], // Correo único
            [
                'nombre' => '5555555', // <<--- ESTE ES EL USUARIO (CI)
                'contrasena' => Hash::make('123456'), // <<--- ESTA ES LA CONTRASEÑA
                'activo' => true,
                'rol_id' => $rolAdmin->id,
                'persona_id' => $personaAdmin->id
            ]
        );

        // --- 3. Crear un Coordinador (Ejemplo) ---
        $personaCoord = Persona::firstOrCreate(
            ['carnet' => '2222222'],
            [
                'nombre' => 'Juan Coordinador',
                'telefono' => '77722222'
            ]
        );
        Administrativo::firstOrCreate(
            ['persona_id' => $personaCoord->id],
            ['cargo' => 'Coordinador']
        );
        User::firstOrCreate(
            ['correo' => 'coord@ficct.bo'],
            [
                'nombre' => '2222222', // Usuario (CI)
                'contrasena' => Hash::make('123456'), // Contraseña
                'activo' => true,
                'rol_id' => $rolCoord->id,
                'persona_id' => $personaCoord->id
            ]
        );

        // --- 4. Crear un Docente (Ejemplo) ---
        $personaDocente = Persona::firstOrCreate(
            ['carnet' => '3333333'],
            [
                'nombre' => 'Walter Profe',
                'telefono' => '77733333'
            ]
        );
        Docente::firstOrCreate(
            ['persona_id' => $personaDocente->id],
            [
                'anos_experiencia' => 5,
                'fecha_ingreso' => '2020-03-01',
                'activo' => true
            ]
        );
        User::firstOrCreate(
            ['correo' => 'walter.p@ficct.bo'],
            [
                'nombre' => '3333333', // Usuario (CI)
                'contrasena' => Hash::make('123456'), // Contraseña
                'activo' => true,
                'rol_id' => $rolDocente->id,
                'persona_id' => $personaDocente->id
            ]
        );
    }
}