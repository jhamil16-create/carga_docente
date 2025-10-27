<?php

namespace Tests\Feature;

use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class RolControllerTest extends TestCase
{
    use RefreshDatabase;

    private $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        $rolAdmin = Rol::create(['nombre_rol' => 'administrador']);
        $this->admin = Usuario::factory()->create(['rol_id' => $rolAdmin->rol_id]);
    }

    public function test_admin_puede_listar_roles()
    {
        Sanctum::actingAs($this->admin);

        $response = $this->getJson('/api/roles');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'rol_id',
                            'nombre_rol'
                        ]
                    ]
                ]);
    }

    public function test_admin_puede_crear_rol()
    {
        Sanctum::actingAs($this->admin);

        $response = $this->postJson('/api/roles', [
            'nombre_rol' => 'nuevo_rol'
        ]);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'rol' => [
                        'rol_id',
                        'nombre_rol'
                    ]
                ]);

        $this->assertDatabaseHas('rol', [
            'nombre_rol' => 'nuevo_rol'
        ]);
    }

    public function test_admin_puede_actualizar_rol()
    {
        Sanctum::actingAs($this->admin);

        $rol = Rol::create(['nombre_rol' => 'rol_temporal']);

        $response = $this->putJson("/api/roles/{$rol->rol_id}", [
            'nombre_rol' => 'rol_actualizado'
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'mensaje' => 'Rol actualizado exitosamente'
                ]);

        $this->assertDatabaseHas('rol', [
            'rol_id' => $rol->rol_id,
            'nombre_rol' => 'rol_actualizado'
        ]);
    }

    public function test_admin_puede_eliminar_rol_sin_usuarios()
    {
        Sanctum::actingAs($this->admin);

        $rol = Rol::create(['nombre_rol' => 'rol_para_eliminar']);

        $response = $this->deleteJson("/api/roles/{$rol->rol_id}");

        $response->assertStatus(200)
                ->assertJson([
                    'mensaje' => 'Rol eliminado exitosamente'
                ]);

        $this->assertDatabaseMissing('rol', [
            'rol_id' => $rol->rol_id
        ]);
    }

    public function test_admin_no_puede_eliminar_rol_con_usuarios()
    {
        Sanctum::actingAs($this->admin);

        $rol = Rol::create(['nombre_rol' => 'rol_con_usuarios']);
        Usuario::factory()->create(['rol_id' => $rol->rol_id]);

        $response = $this->deleteJson("/api/roles/{$rol->rol_id}");

        $response->assertStatus(400)
                ->assertJson([
                    'mensaje' => 'No se puede eliminar el rol porque tiene usuarios asociados'
                ]);

        $this->assertDatabaseHas('rol', [
            'rol_id' => $rol->rol_id
        ]);
    }
}