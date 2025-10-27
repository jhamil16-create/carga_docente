<?php

namespace Tests\Feature;

use App\Models\Asignacion;
use App\Models\Docente;
use App\Models\Grupo;
use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class AsignacionControllerTest extends TestCase
{
    use RefreshDatabase;

    private $admin;
    private $docente;
    private $grupo;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Crear roles
        $rolAdmin = Rol::create(['nombre_rol' => 'administrador']);
        $rolDocente = Rol::create(['nombre_rol' => 'docente']);

        // Crear usuarios
        $this->admin = Usuario::factory()->create(['rol_id' => $rolAdmin->rol_id]);
        $usuarioDocente = Usuario::factory()->create(['rol_id' => $rolDocente->rol_id]);

        // Crear docente
        $this->docente = Docente::create([
            'usuario_id' => $usuarioDocente->usuario_id,
            'nombre' => 'Docente Test',
            'email_institucional' => 'docente@umss.edu.bo'
        ]);

        // Crear grupo
        $this->grupo = Grupo::create([
            'materia_id' => 1,
            'numero_grupo' => 1,
            'capacidad' => 30,
            'semestre' => '1/2024',
            'gestion' => 2024
        ]);
    }

    public function test_admin_puede_listar_asignaciones()
    {
        Sanctum::actingAs($this->admin);

        $asignacion = Asignacion::create([
            'docente_id' => $this->docente->docente_id,
            'grupo_id' => $this->grupo->grupo_id,
            'fecha_asignacion' => now()
        ]);

        $response = $this->getJson('/api/asignaciones');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'asignacion_id',
                            'docente_id',
                            'grupo_id',
                            'fecha_asignacion',
                            'docente' => [
                                'docente_id',
                                'nombre'
                            ],
                            'grupo' => [
                                'grupo_id',
                                'numero_grupo'
                            ]
                        ]
                    ]
                ]);
    }

    public function test_admin_puede_crear_asignacion()
    {
        Sanctum::actingAs($this->admin);

        $response = $this->postJson('/api/asignaciones', [
            'docente_id' => $this->docente->docente_id,
            'grupo_id' => $this->grupo->grupo_id
        ]);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'asignacion' => [
                        'asignacion_id',
                        'docente_id',
                        'grupo_id',
                        'fecha_asignacion'
                    ]
                ]);

        $this->assertDatabaseHas('asignacion', [
            'docente_id' => $this->docente->docente_id,
            'grupo_id' => $this->grupo->grupo_id
        ]);
    }

    public function test_docente_puede_ver_sus_asignaciones()
    {
        $usuarioDocente = Usuario::find($this->docente->usuario_id);
        Sanctum::actingAs($usuarioDocente);

        $asignacion = Asignacion::create([
            'docente_id' => $this->docente->docente_id,
            'grupo_id' => $this->grupo->grupo_id,
            'fecha_asignacion' => now()
        ]);

        $response = $this->getJson('/api/asignaciones/mis-asignaciones');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'asignacion_id',
                            'grupo_id',
                            'fecha_asignacion',
                            'grupo' => [
                                'grupo_id',
                                'numero_grupo',
                                'materia' => [
                                    'materia_id',
                                    'nombre_materia'
                                ]
                            ]
                        ]
                    ]
                ]);
    }

    public function test_admin_puede_eliminar_asignacion_sin_asistencias()
    {
        Sanctum::actingAs($this->admin);

        $asignacion = Asignacion::create([
            'docente_id' => $this->docente->docente_id,
            'grupo_id' => $this->grupo->grupo_id,
            'fecha_asignacion' => now()
        ]);

        $response = $this->deleteJson("/api/asignaciones/{$asignacion->asignacion_id}");

        $response->assertStatus(200)
                ->assertJson([
                    'mensaje' => 'Asignación eliminada exitosamente'
                ]);

        $this->assertDatabaseMissing('asignacion', [
            'asignacion_id' => $asignacion->asignacion_id
        ]);
    }
}