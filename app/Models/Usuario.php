<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Usuario extends Authenticatable
{

    protected $table = 'usuario';
    protected $primaryKey = 'usuario_id';
    public $timestamps = false;

    protected $fillable = [
        'rol_id',
        'codigo_usuario',
        'nombre',
        'apellido',
        'email_institucional',
        'contraseña_hash',
        'activo'
    ];

    protected $hidden = [
        'contraseña_hash',
        'remember_token',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'contraseña_hash' => 'hashed',
    ];

    public function getAuthPassword()
    {
        return $this->contraseña_hash;
    }

    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class, 'rol_id', 'rol_id');
    }

    public function docente(): HasOne
    {
        return $this->hasOne(Docente::class, 'usuario_id', 'usuario_id');
    }
}