<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $table = 'users';
    protected $primaryKey = 'usuario_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'apellido',
        'email_institucional',
        'contraseña_hash',
        'codigo_usuario',
        'rol_id',
        'activo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'contraseña_hash',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'contraseña_hash' => 'hashed',
            'activo' => 'boolean',
        ];
    }

    /**
     * Relación con rol
     */
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id', 'rol_id');
    }

    /**
     * Relación con docente
     */
    public function docente()
    {
        return $this->hasOne(Docente::class, 'usuario_id', 'usuario_id');
    }

    /**
     * Relación con reportes generados
     */
    public function reportes()
    {
        return $this->hasMany(Reporte::class, 'usuario_id', 'usuario_id');
    }

    /**
     * Relación con cargas masivas realizadas
     */
    public function cargasMasivas()
    {
        return $this->hasMany(CargaMasiva::class, 'usuario_id', 'usuario_id');
    }

    /**
     * Relación con bitácora de acciones
     */
    public function bitacoras()
    {
        return $this->hasMany(Bitacora::class, 'usuario_id', 'usuario_id');
    }

    // Mantener compatibilidad con Laravel Auth
    public function getAuthPassword()
    {
        return $this->contraseña_hash;
    }

    public function getEmailForPasswordReset()
    {
        return $this->email_institucional;
    }
}
