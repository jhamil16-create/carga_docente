<?php

namespace Tests\Feature;

use App\Models\Materia;
use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class MateriaControllerTest extends TestCase
{
    use RefreshDatabase;

    private $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        $rolAdmin = Rol::create(['nombre_rol' => 'administrador']);
        $this->admin = Usuario::factory()->create(['rol_id' => $rolAdmin->rol_id]);
    }

    public function test_admin_puede_listar_materias()
    {
        Sanctum::actingAs($this->admin);

        $materia = Materia::create([
            'nombre_materia' => 'Matemáticas',
            'codigo_materia' => 'MAT101',
            'creditos' => 4
        ]);

        $response = $this->getJson('/api/materias');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'materia_id',
                            'nombre_materia',
                            'codigo_materia',
                            'creditos'
                        ]
                    ]
                ]);
    }

    public function test_admin_puede_crear_materia()
    {
        Sanctum::actingAs($this->admin);

        $nuevaMateria = [
            'nombre_materia' => 'Física',
            'codigo_materia' => 'FIS101',
            'creditos' => 4
        ];

        $response = $this->postJson('/api/materias', $nuevaMateria);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'materia' => [
                        'materia_id',
                        'nombre_materia',
                        'codigo_materia',
                        'creditos'
                    ]
                ]);

        $this->assertDatabaseHas('materia', $nuevaMateria);
    }

    public function test_admin_puede_actualizar_materia()
    {
        Sanctum::actingAs($this->admin);

        $materia = Materia::create([
            'nombre_materia' => 'Química',
            'codigo_materia' => 'QUI101',
            'creditos' => 3
        ]);

        $datosActualizados = [
            'nombre_materia' => 'Química Avanzada',
            'codigo_materia' => 'QUI102',
            'creditos' => 4
        ];

        $response = $this->putJson("/api/materias/{$materia->materia_id}", $datosActualizados);

        $response->assertStatus(200)
                ->assertJson([
                    'mensaje' => 'Materia actualizada exitosamente'
                ]);

        $this->assertDatabaseHas('materia', [
            'materia_id' => $materia->materia_id,
            'nombre_materia' => $datosActualizados['nombre_materia'],
            'codigo_materia' => $datosActualizados['codigo_materia'],
            'creditos' => $datosActualizados['creditos']
        ]);
    }

    public function test_admin_puede_eliminar_materia_sin_grupos()
    {
        Sanctum::actingAs($this->admin);

        $materia = Materia::create([
            'nombre_materia' => 'Materia para Eliminar',
            'codigo_materia' => 'DEL101',
            'creditos' => 3
        ]);

        $response = $this->deleteJson("/api/materias/{$materia->materia_id}");

        $response->assertStatus(200)
                ->assertJson([
                    'mensaje' => 'Materia eliminada exitosamente'
                ]);

        $this->assertDatabaseMissing('materia', [
            'materia_id' => $materia->materia_id
        ]);
    }

    public function test_validacion_al_crear_materia()
    {
        Sanctum::actingAs($this->admin);

        $response = $this->postJson('/api/materias', [
            'nombre_materia' => '',
            'codigo_materia' => '',
            'creditos' => 'no_numerico'
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors([
                    'nombre_materia',
                    'codigo_materia',
                    'creditos'
                ]);
    }
}