<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asignacion extends Model
{
    protected $table = 'asignacion';
    protected $primaryKey = 'asignacion_id';
    public $timestamps = false;

    protected $fillable = [
        'docente_id',
        'grupo_id',
        'aula_id',
        'horario_id',
        'fecha_asignacion'
    ];

    protected $casts = [
        'fecha_asignacion' => 'datetime'
    ];

    public function docente(): BelongsTo
    {
        return $this->belongsTo(Docente::class, 'docente_id', 'docente_id');
    }

    public function grupo(): BelongsTo
    {
        return $this->belongsTo(Grupo::class, 'grupo_id', 'grupo_id');
    }

    public function asistencias(): HasMany
    {
        return $this->hasMany(Asistencia::class, 'asignacion_id', 'asignacion_id');
    }

    public function aula(): BelongsTo
    {
        return $this->belongsTo(Aula::class, 'aula_id', 'aula_id');
    }

    public function horario(): BelongsTo
    {
        return $this->belongsTo(Horario::class, 'horario_id', 'horario_id');
    }
}
