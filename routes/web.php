<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; 
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PerfilController;

// --- CONTROLADORES DE ADMINISTRADOR ---
use App\Http\Controllers\Admin\RolController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Admin\ReporteAsistenciaController;
use App\Http\Controllers\Admin\ImportacionController;
use App\Http\Controllers\Admin\BitacoraController;

// --- CONTROLADORES DE COORDINADOR ---
use App\Http\Controllers\Coordinador\AulaController;
use App\Http\Controllers\Coordinador\MateriaController;
use App\Http\Controllers\Coordinador\GrupoController;
use App\Http\Controllers\Coordinador\DocenteController;
use App\Http\Controllers\Coordinador\GrupoMateriaController;
use App\Http\Controllers\Coordinador\DisponibilidadAulaController;

// --- CONTROLADORES CONFLICTIVOS (USANDO ALIAS) ---
// Aquí corregimos el conflicto:
use App\Http\Controllers\Coordinador\HorarioController as CoordHorarioController;      // <-- ALIAS AÑADIDO
use App\Http\Controllers\Coordinador\AsistenciaController as CoordAsistenciaController; // <-- ALIAS AÑADIDO

use App\Http\Controllers\Docente\HorarioController as DocenteHorarioController;
use App\Http\Controllers\Docente\AsistenciaController as DocenteAsistenciaController;

use App\Http\Controllers\Autoridad\HorarioController as AutoridadHorarioController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- 1. Autenticación (Rutas Públicas) ---
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// --- 2. Rutas Protegidas (Requieren Login) ---
Route::middleware(['auth'])->group(function () {

    // Ruta principal y redireccionamiento por rol
    Route::get('/', function () {
        $rol = strtolower(auth()->user()->rol?->nombre);
        return match ($rol) {
            'administrador' => redirect()->route('admin.dashboard'),
            'coordinador' => redirect()->route('coordinador.dashboard'),
            'docente' => redirect()->route('docente.dashboard'),
            'autoridad' => redirect()->route('autoridad.dashboard'),
            default => (Auth::logout() && redirect()->route('login')->withErrors('Rol no reconocido.'))
        };
    })->name('home');

    
    // --- 2.1 GRUPO DE RUTAS DE ADMINISTRADOR ---
    Route::middleware('rol:administrador')->prefix('admin')->name('admin.')->group(function () {
        
        Route::get('/dashboard', fn () => view('admin.dashboard'))->name('dashboard');
        
        Route::resource('roles', RolController::class)->except(['show', 'destroy']);
        Route::resource('usuarios', UsuarioController::class)->except(['show', 'destroy']);

        // Reportes, Importación y Bitácora
        Route::get('reportes/asistencia', [ReporteAsistenciaController::class, 'index'])->name('reportes.asistencia.index');
        Route::get('reportes/asistencia/generar', [ReporteAsistenciaController::class, 'generar'])->name('reportes.asistencia.generar');
        Route::get('importar/usuarios', [ImportacionController::class, 'create'])->name('importar.usuarios.create');
        Route::post('importar/usuarios', [ImportacionController::class, 'store'])->name('importar.usuarios.store');
        Route::get('bitacora', [BitacoraController::class, 'index'])->name('bitacora.index');
    });

    
    // --- 2.2 GRUPO DE RUTAS DE COORDINADOR ---
    Route::middleware('rol:coordinador')->prefix('coordinador')->name('coordinador.')->group(function () {

        Route::get('/dashboard', fn () => view('coordinador.dashboard'))->name('dashboard');

        // CRUDs
        Route::resource('aulas', AulaController::class)->except(['show', 'destroy']);
        Route::resource('materias', MateriaController::class)->except(['show', 'destroy']);
        Route::resource('grupos', GrupoController::class)->except(['show', 'destroy']);
        Route::resource('docentes', DocenteController::class)->except(['show', 'destroy']);

        // Apertura de Materias
        Route::get('/apertura-materias', [GrupoMateriaController::class, 'index'])->name('grupo-materia.index');
        Route::post('/apertura-materias', [GrupoMateriaController::class, 'store'])->name('grupo-materia.store');
        Route::delete('/apertura-materias/{grupoMateria}', [GrupoMateriaController::class, 'destroy'])->name('grupo-materia.destroy');

        // Planificación (USANDO ALIAS CORREGIDO)
        Route::get('/planificacion', [CoordHorarioController::class, 'index'])->name('planificacion.index');
        Route::post('/planificacion', [CoordHorarioController::class, 'store'])->name('planificacion.store');
        Route::delete('/planificacion/{horarioClase}', [CoordHorarioController::class, 'destroy'])->name('planificacion.destroy');

        // Asistencia QR (USANDO ALIAS CORREGIDO)
        Route::get('/asistencia/panel', [CoordAsistenciaController::class, 'showPanel'])->name('asistencia.panel'); 
        Route::post('/asistencia/generar-token', [CoordAsistenciaController::class, 'generarToken'])->name('asistencia.generar-token');
        
        // CU-18: Consultar aulas disponibles
        Route::get('aulas-disponibles', [DisponibilidadAulaController::class, 'index'])->name('aulas.disponibles');
    });

    
    // --- 2.3 GRUPO DE RUTAS DE DOCENTE ---
    Route::middleware('rol:docente')->prefix('docente')->name('docente.')->group(function () {
        
        Route::get('/dashboard', fn () => view('docente.dashboard'))->name('dashboard');

        // Horario
        Route::get('/horario', [DocenteHorarioController::class, 'index'])->name('horario.index');

        // Asistencia QR (CU-09)
        Route::get('/asistencia/escanear', [DocenteAsistenciaController::class, 'escanear'])->name('asistencia.escanear');
        Route::post('/asistencia/registrar', [DocenteAsistenciaController::class, 'registrar'])->name('asistencia.registrar');
    });

    
    // --- 2.4 GRUPO DE RUTAS DE AUTORIDAD ---
    // (Este es el grupo correcto. El duplicado al final se eliminó)
    Route::middleware('rol:autoridad')->prefix('autoridad')->name('autoridad.')->group(function () {
        
        Route::get('/dashboard', fn () => view('autoridad.dashboard'))->name('dashboard');

        // CU-07: Consultar Horarios (USANDO ALIAS)
        Route::get('horarios', [AutoridadHorarioController::class, 'index'])->name('horarios.index');

        // CU-10: Generar Reportes (REUTILIZADO)
        Route::get('reportes/asistencia', [ReporteAsistenciaController::class, 'index'])->name('reportes.asistencia.index');
        Route::get('reportes/asistencia/generar', [ReporteAsistenciaController::class, 'generar'])->name('reportes.asistencia.generar');
    });

    // --- 2.5 GRUPO DE PERFIL DE USUARIO (CU-17) ---
    // (Movido al lugar correcto, fuera del grupo de autoridad)
    Route::prefix('perfil')->name('perfil.')->group(function () {
        Route::get('/password', [PerfilController::class, 'editPassword'])->name('password.edit');
        Route::put('/password', [PerfilController::class, 'updatePassword'])->name('password.update');
    });

}); // <-- FIN DEL GRUPO middleware('auth')