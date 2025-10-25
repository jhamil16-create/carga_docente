<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\FacultyMemberController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;

// Nuevos controladores del sistema de gestión de asistencia
use App\Http\Controllers\MateriaController;
use App\Http\Controllers\AulaController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\AsignacionController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\CargaMasivaController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::prefix('schedules')->group(function () {
    Route::get('/', [ScheduleController::class, 'index'])->name('schedules.index');
    Route::get('/create', [ScheduleController::class, 'create'])->name('schedules.create');
    Route::post('/', [ScheduleController::class, 'store'])->name('schedules.store');
});

use App\Http\Controllers\ExportController;
Route::get('/exports/schedules/pdf', [ExportController::class, 'schedulesPdf'])->name('exports.schedules.pdf');
Route::get('/exports/schedules/excel', [ExportController::class, 'schedulesExcel'])->name('exports.schedules.excel');

use App\Http\Controllers\QRController;
Route::get('/qr/schedule', [QRController::class, 'schedule'])->name('qr.schedule');

Route::middleware(['auth','role:admin'])->group(function () {
    Route::resource('faculty_members', FacultyMemberController::class)->names([
        'index' => 'faculty_members.index',
        'create' => 'faculty_members.create',
        'store' => 'faculty_members.store',
        'edit' => 'faculty_members.edit',
        'update' => 'faculty_members.update',
        'destroy' => 'faculty_members.destroy',
    ]);
    Route::get('/admin/users/import', [UserController::class, 'importForm'])->name('users.import.form');
    Route::post('/admin/users/import', [UserController::class, 'importFromCSV'])->name('users.import.csv');
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
});

Route::middleware(['auth','role:instructor'])->group(function () {
    Route::get('/attendance/create', [AttendanceController::class, 'create'])->name('attendance.create');
    Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas del nuevo sistema de gestión de asistencia
Route::middleware(['auth'])->group(function () {
    
    // Dashboard de asistencia
    Route::get('/asistencia/dashboard', [AsistenciaController::class, 'dashboard'])->name('asistencia.dashboard');
    
    // Rutas para administradores
    Route::middleware(['role:admin'])->group(function () {
        
        // Gestión de Materias
        Route::resource('materias', MateriaController::class);
        Route::get('/api/materias', [MateriaController::class, 'api'])->name('materias.api');
        
        // Gestión de Aulas
        Route::resource('aulas', AulaController::class);
        Route::get('/aulas/disponibles/{horario_id}', [AulaController::class, 'disponibles'])->name('aulas.disponibles');
        
        // Gestión de Horarios
        Route::resource('horarios', HorarioController::class);
        Route::get('/horarios/disponibles', [HorarioController::class, 'disponibles'])->name('horarios.disponibles');
        Route::get('/api/horarios', [HorarioController::class, 'api'])->name('horarios.api');
        
        // Gestión de Grupos
        Route::resource('grupos', GrupoController::class);
        Route::get('/grupos/materia/{materia_id}', [GrupoController::class, 'porMateria'])->name('grupos.por-materia');
        
        // Gestión de Asignaciones
        Route::resource('asignaciones', AsignacionController::class);
        Route::get('/asignaciones/docente/{docente_id}/horarios', [AsignacionController::class, 'horariosDocente'])->name('asignaciones.horarios-docente');
        
        // Sistema de Carga Masiva
        Route::resource('carga-masiva', CargaMasivaController::class);
        Route::get('/carga-masiva/plantillas/usuarios', [CargaMasivaController::class, 'descargarPlantillaUsuarios'])->name('carga-masiva.plantilla-usuarios');
        Route::get('/carga-masiva/plantillas/docentes', [CargaMasivaController::class, 'descargarPlantillaDocentes'])->name('carga-masiva.plantilla-docentes');
        
        // Sistema de Reportes
        Route::resource('reportes', ReporteController::class);
        Route::get('/reportes/{reporte}/descargar', [ReporteController::class, 'descargar'])->name('reportes.descargar');
        Route::post('/reportes/generar', [ReporteController::class, 'generar'])->name('reportes.generar');
        
        // Gestión completa de asistencias para administradores
        Route::resource('asistencias', AsistenciaController::class);
        Route::get('asistencias/registro-rapido', [AsistenciaController::class, 'registroRapido'])->name('asistencias.registro-rapido');
        Route::post('asistencias/guardar-rapido', [AsistenciaController::class, 'guardarRapido'])->name('asistencias.guardar-rapido');
        Route::get('asistencias/dashboard', [AsistenciaController::class, 'dashboard'])->name('asistencias.dashboard');
    });
    
    // Rutas para docentes
    Route::middleware(['role:docente'])->group(function () {
        
        // Registro de asistencia para docentes
        Route::get('/mi-asistencia', [AsistenciaController::class, 'index'])->name('mi-asistencia.index');
        Route::get('/mi-asistencia/crear', [AsistenciaController::class, 'create'])->name('mi-asistencia.create');
        Route::post('/mi-asistencia', [AsistenciaController::class, 'store'])->name('mi-asistencia.store');
        Route::get('/mi-asistencia/{asistencia}', [AsistenciaController::class, 'show'])->name('mi-asistencia.show');
        Route::get('/mi-asistencia/{asistencia}/editar', [AsistenciaController::class, 'edit'])->name('mi-asistencia.edit');
        Route::put('/mi-asistencia/{asistencia}', [AsistenciaController::class, 'update'])->name('mi-asistencia.update');
        
        // Registro rápido para docentes
        Route::get('/mi-asistencia/registro/rapido', [AsistenciaController::class, 'registroRapido'])->name('mi-asistencia.registro-rapido');
        Route::post('/mi-asistencia/registro/rapido', [AsistenciaController::class, 'procesarRegistroRapido'])->name('mi-asistencia.procesar-registro-rapido');
        
        // Consulta de horarios y asignaciones
        Route::get('/mis-asignaciones', [AsignacionController::class, 'index'])->name('mis-asignaciones.index');
        Route::get('/mis-horarios', [AsignacionController::class, 'horariosDocente'])->name('mis-horarios');
    });
});
