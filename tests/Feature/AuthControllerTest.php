<?php

namespace Tests\Feature;

use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Hash;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_usuario_puede_iniciar_sesion()
    {
        $usuario = Usuario::factory()->create([
            'email_institucional' => 'test@umss.edu.bo',
            'contraseña_hash' => Hash::make('password123'),
            'activo' => true
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email_institucional' => 'test@umss.edu.bo',
            'contraseña' => 'password123'
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'token',
                    'usuario' => [
                        'usuario_id',
                        'nombre_usuario',
                        'email_institucional',
                        'rol' => [
                            'rol_id',
                            'nombre_rol'
                        ]
                    ]
                ]);
    }

    public function test_usuario_no_puede_iniciar_sesion_con_credenciales_invalidas()
    {
        $usuario = Usuario::factory()->create([
            'email_institucional' => 'test@umss.edu.bo',
            'contraseña_hash' => Hash::make('password123'),
            'activo' => true
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email_institucional' => 'test@umss.edu.bo',
            'contraseña' => 'contraseña_incorrecta'
        ]);

        $response->assertStatus(401)
                ->assertJson([
                    'mensaje' => 'Credenciales inválidas'
                ]);
    }

    public function test_usuario_puede_cerrar_sesion()
    {
        $usuario = Usuario::factory()->create();
        $token = $usuario->createToken('auth_token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/auth/logout');

        $response->assertStatus(200)
                ->assertJson([
                    'mensaje' => 'Sesión cerrada exitosamente'
                ]);
    }

    public function test_usuario_puede_solicitar_recuperacion_contraseña()
    {
        $usuario = Usuario::factory()->create([
            'email_institucional' => 'test@umss.edu.bo'
        ]);

        $response = $this->postJson('/api/auth/recuperar-contraseña', [
            'email_institucional' => 'test@umss.edu.bo'
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'mensaje' => 'Se ha enviado un enlace de recuperación a su correo electrónico'
                ]);

        $this->assertDatabaseHas('usuario', [
            'email_institucional' => 'test@umss.edu.bo',
            'token_recuperacion' => fn ($value) => !is_null($value)
        ]);
    }

    public function test_usuario_puede_restablecer_contraseña()
    {
        $usuario = Usuario::factory()->create([
            'email_institucional' => 'test@umss.edu.bo',
            'token_recuperacion' => 'token_valido',
            'expiracion_token_recuperacion' => now()->addHour()
        ]);

        $response = $this->postJson('/api/auth/restablecer-contraseña', [
            'token' => 'token_valido',
            'contraseña' => 'nueva_contraseña123',
            'confirmar_contraseña' => 'nueva_contraseña123'
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'mensaje' => 'Contraseña restablecida exitosamente'
                ]);

        $this->assertTrue(Hash::check('nueva_contraseña123', $usuario->fresh()->contraseña_hash));
    }
}