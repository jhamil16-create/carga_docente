<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\CargaMasivaController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\MateriaController;
use App\Http\Controllers\AulaController;
use App\Http\Controllers\AsignacionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rutas públicas (sin autenticación)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Rutas protegidas (requieren login)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Usuarios
    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/create', [UsuarioController::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
    Route::get('/usuarios/{usuario_id}/edit', [UsuarioController::class, 'edit'])->name('usuarios.edit');
    Route::put('/usuarios/{usuario_id}', [UsuarioController::class, 'update'])->name('usuarios.update');
    Route::delete('/usuarios/{usuario_id}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');

    // Carga masiva
    Route::get('/carga-masiva', [CargaMasivaController::class, 'index'])->name('carga-masiva.index');
    Route::get('/carga-masiva/create', [CargaMasivaController::class, 'create'])->name('carga-masiva.create');
    Route::post('/carga-masiva', [CargaMasivaController::class, 'store'])->name('carga-masiva.store');
    Route::get('/carga-masiva/plantilla/{tipo}', [CargaMasivaController::class, 'descargarPlantilla'])->name('carga-masiva.plantilla');

    // Asistencia
    Route::get('/asistencias', [AsistenciaController::class, 'index'])->name('asistencias.index');
    Route::get('/asistencias/create', [AsistenciaController::class, 'create'])->name('asistencias.create');
    Route::post('/asistencias', [AsistenciaController::class, 'store'])->name('asistencias.store');

    // Grupos
    Route::get('/grupos', [GrupoController::class, 'index'])->name('grupos.index');
    Route::get('/grupos/create', [GrupoController::class, 'create'])->name('grupos.create');
    Route::post('/grupos', [GrupoController::class, 'store'])->name('grupos.store');
    Route::get('/grupos/{grupo_id}', [GrupoController::class, 'show'])->name('grupos.show');
    Route::get('/grupos/{grupo_id}/edit', [GrupoController::class, 'edit'])->name('grupos.edit');
    Route::put('/grupos/{grupo_id}', [GrupoController::class, 'update'])->name('grupos.update');
    Route::delete('/grupos/{grupo_id}', [GrupoController::class, 'destroy'])->name('grupos.destroy');

    // Asignaciones
    Route::get('/asignaciones', [AsignacionController::class, 'index'])->name('asignaciones.index');
    Route::get('/asignaciones/create', [AsignacionController::class, 'create'])->name('asignaciones.create');
    Route::post('/asignaciones', [AsignacionController::class, 'store'])->name('asignaciones.store');
    Route::get('/asignaciones/{asignacion_id}', [AsignacionController::class, 'show'])->name('asignaciones.show');
    Route::get('/asignaciones/{asignacion_id}/edit', [AsignacionController::class, 'edit'])->name('asignaciones.edit');
    Route::put('/asignaciones/{asignacion_id}', [AsignacionController::class, 'update'])->name('asignaciones.update');
    Route::delete('/asignaciones/{asignacion_id}', [AsignacionController::class, 'destroy'])->name('asignaciones.destroy');
});
