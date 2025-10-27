<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Roles (solo si no existen)
        if (!DB::table('rol')->where('nombre_rol', 'Administrador')->exists()) {
            DB::table('rol')->insert(['nombre_rol' => 'Administrador']);
        }
        if (!DB::table('rol')->where('nombre_rol', 'Docente')->exists()) {
            DB::table('rol')->insert(['nombre_rol' => 'Docente']);
        }

        // Usuario Administrador
        if (!DB::table('usuario')->where('codigo_usuario', 'ADM001')->exists()) {
            $usuario_id_admin = DB::table('usuario')->insertGetId([
                'rol_id' => DB::table('rol')->where('nombre_rol', 'Administrador')->value('rol_id'),
                'codigo_usuario' => 'ADM001',
                'nombre' => 'Admin',
                'apellido' => 'Sistema',
                'email_institucional' => 'admin@ficct.uagrm.edu.bo',
                'contraseña_hash' => Hash::make('admin123'),
                'activo' => true,
            ], 'usuario_id');
        }

        // Usuario Docente de prueba
        if (!DB::table('usuario')->where('codigo_usuario', 'DOC001')->exists()) {
            $usuario_id_docente = DB::table('usuario')->insertGetId([
                'rol_id' => DB::table('rol')->where('nombre_rol', 'Docente')->value('rol_id'),
                'codigo_usuario' => 'DOC001',
                'nombre' => 'Juan',
                'apellido' => 'Pérez',
                'email_institucional' => 'juan.perez@ficct.uagrm.edu.bo',
                'contraseña_hash' => Hash::make('docente123'),
                'activo' => true,
            ], 'usuario_id');

            // Docente
            if (!DB::table('docente')->where('usuario_id', $usuario_id_docente)->exists()) {
                $docente_id = DB::table('docente')->insertGetId([
                    'usuario_id' => $usuario_id_docente,
                    'especialidad' => 'Ingeniería de Sistemas',
                    'telefono' => '75566677',
                    'fecha_registro' => Carbon::now(),
                ], 'docente_id');

                // Materias
                if (!DB::table('materia')->where('codigo_materia', 'SI100')->exists()) {
                    $materia_id = DB::table('materia')->insertGetId([
                        'nombre_materia' => 'Sistemas de Información I',
                        'codigo_materia' => 'SI100',
                        'creditos' => 4,
                    ], 'materia_id');

                    // Grupos
                    if (!DB::table('grupo')->where('nombre_grupo', 'SI100-1')->exists()) {
                        $grupo_id = DB::table('grupo')->insertGetId([
                            'materia_id' => $materia_id,
                            'nombre_grupo' => 'SI100-1',
                            'capacidad_maxima' => 40,
                        ], 'grupo_id');

                        // Aulas
                        if (!DB::table('aula')->where('nombre_aula', 'LAB-SIS-1')->exists()) {
                            $aula_id = DB::table('aula')->insertGetId([
                                'nombre_aula' => 'LAB-SIS-1',
                                'capacidad' => 40,
                                'ubicacion' => 'Módulo 235 - Planta Baja',
                            ], 'aula_id');

                            // Horarios
                            if (!DB::table('horario')->where('dia_semana', 'Lunes')->exists()) {
                                $horario_id = DB::table('horario')->insertGetId([
                                    'dia_semana' => 'Lunes',
                                    'hora_inicio' => '07:45',
                                    'hora_fin' => '09:15',
                                ], 'horario_id');

                                // Asignación
                                if (!DB::table('asignacion')->where([
                                    'docente_id' => $docente_id,
                                    'grupo_id' => $grupo_id,
                                    'aula_id' => $aula_id,
                                    'horario_id' => $horario_id,
                                ])->exists()) {
                                    $asignacion_id = DB::table('asignacion')->insertGetId([
                                        'docente_id' => $docente_id,
                                        'grupo_id' => $grupo_id,
                                        'aula_id' => $aula_id,
                                        'horario_id' => $horario_id,
                                        'fecha_asignacion' => Carbon::now(),
                                    ], 'asignacion_id');

                                    // Asistencia de ejemplo
                                    if (!DB::table('asistencia')->where([
                                        'docente_id' => $docente_id,
                                        'asignacion_id' => $asignacion_id,
                                        'fecha' => Carbon::now()->toDateString(),
                                    ])->exists()) {
                                        DB::table('asistencia')->insertGetId([
                                            'docente_id' => $docente_id,
                                            'asignacion_id' => $asignacion_id,
                                            'fecha' => Carbon::now()->toDateString(),
                                            'hora_entrada' => Carbon::createFromTimeString('07:45:00'),
                                            'estado' => 'Presente',
                                            'metodo_registro' => 'Manual',
                                        ], 'asistencia_id');
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
