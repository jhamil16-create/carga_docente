<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Docente extends Model
{
    protected $table = 'docente';
    protected $primaryKey = 'docente_id';
    public $timestamps = false;

    protected $fillable = [
        'usuario_id',
        'especialidad',
        'telefono',
        'fecha_registro'
    ];

    protected $casts = [
        'fecha_registro' => 'datetime',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'usuario_id');
    }

    public function asignaciones(): HasMany
    {
        return $this->hasMany(Asignacion::class, 'docente_id', 'docente_id');
    }

    public function asistencias(): HasMany
    {
        return $this->hasMany(Asistencia::class, 'docente_id', 'docente_id');
    }
}
