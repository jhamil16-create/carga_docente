<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\MateriaController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\AsignacionController;
use App\Http\Controllers\AsistenciaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Rutas públicas de autenticación
Route::post('/login', [AuthController::class, 'login']);
Route::post('/recuperar-contraseña', [AuthController::class, 'recuperarContraseña']);
Route::post('/restablecer-contraseña', [AuthController::class, 'restablecerContraseña']);

// Rutas protegidas que requieren autenticación
Route::middleware('auth:sanctum')->group(function () {
    // Rutas de autenticación
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/verificar-token', [AuthController::class, 'verificarToken']);

    // Rutas de roles
    Route::get('/roles', [RolController::class, 'listarRoles']);
    Route::get('/roles/{rol_id}', [RolController::class, 'obtenerRol']);
    Route::post('/roles', [RolController::class, 'crearRol']);
    Route::put('/roles/{rol_id}', [RolController::class, 'actualizarRol']);
    Route::delete('/roles/{rol_id}', [RolController::class, 'eliminarRol']);

    // Rutas de usuarios
    Route::get('/usuarios', [UsuarioController::class, 'listarUsuarios']);
    Route::get('/usuarios/{usuario_id}', [UsuarioController::class, 'obtenerUsuario']);
    Route::post('/usuarios', [UsuarioController::class, 'crearUsuario']);
    Route::put('/usuarios/{usuario_id}', [UsuarioController::class, 'actualizarUsuario']);
    Route::delete('/usuarios/{usuario_id}', [UsuarioController::class, 'eliminarUsuario']);
    Route::post('/usuarios/cambiar-contraseña', [UsuarioController::class, 'cambiarContraseña']);
    Route::get('/mi-perfil', [UsuarioController::class, 'miPerfil']);
    Route::put('/mi-perfil', [UsuarioController::class, 'actualizarPerfil']);

    // Rutas de docentes
    Route::get('/docentes', [DocenteController::class, 'listarDocentes']);
    Route::get('/docentes/{docente_id}', [DocenteController::class, 'obtenerDocente']);
    Route::post('/docentes/importar', [DocenteController::class, 'importarDocentes']);

    // Rutas de materias
    Route::get('/materias', [MateriaController::class, 'listarMaterias']);
    Route::get('/materias/{materia_id}', [MateriaController::class, 'obtenerMateria']);
    Route::post('/materias', [MateriaController::class, 'crearMateria']);
    Route::put('/materias/{materia_id}', [MateriaController::class, 'actualizarMateria']);
    Route::delete('/materias/{materia_id}', [MateriaController::class, 'eliminarMateria']);

    // Rutas de grupos
    Route::get('/grupos', [GrupoController::class, 'listarGrupos']);
    Route::get('/grupos/{grupo_id}', [GrupoController::class, 'obtenerGrupo']);
    Route::post('/grupos', [GrupoController::class, 'crearGrupo']);
    Route::put('/grupos/{grupo_id}', [GrupoController::class, 'actualizarGrupo']);
    Route::delete('/grupos/{grupo_id}', [GrupoController::class, 'eliminarGrupo']);

    // Rutas de asignaciones
    Route::get('/asignaciones', [AsignacionController::class, 'listarAsignaciones']);
    Route::get('/asignaciones/{asignacion_id}', [AsignacionController::class, 'obtenerAsignacion']);
    Route::post('/asignaciones', [AsignacionController::class, 'crearAsignacion']);
    Route::put('/asignaciones/{asignacion_id}', [AsignacionController::class, 'actualizarAsignacion']);
    Route::delete('/asignaciones/{asignacion_id}', [AsignacionController::class, 'eliminarAsignacion']);
    Route::get('/mis-asignaciones', [AsignacionController::class, 'misAsignaciones']);

    // Rutas de asistencias
    Route::get('/asistencias', [AsistenciaController::class, 'listarAsistencias']);
    Route::get('/asistencias/{asistencia_id}', [AsistenciaController::class, 'obtenerAsistencia']);
    Route::post('/asistencias', [AsistenciaController::class, 'registrarAsistencia']);
    Route::put('/asistencias/{asistencia_id}', [AsistenciaController::class, 'actualizarAsistencia']);
    Route::delete('/asistencias/{asistencia_id}', [AsistenciaController::class, 'eliminarAsistencia']);
    Route::get('/asistencias/grupo/{grupo_id}', [AsistenciaController::class, 'asistenciasPorGrupo']);
    Route::get('/asistencias/docente/{docente_id}', [AsistenciaController::class, 'asistenciasPorDocente']);
});