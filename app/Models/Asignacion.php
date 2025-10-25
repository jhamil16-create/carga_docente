<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asignacion extends Model
{
    use HasFactory;

    protected $table = 'asignaciones';
    protected $primaryKey = 'asignacion_id';
    
    protected $fillable = [
        'docente_id',
        'grupo_id',
        'aula_id',
        'horario_id',
        'fecha_asignacion'
    ];

    protected $casts = [
        'fecha_asignacion' => 'date',
    ];

    /**
     * Relación con docente
     */
    public function docente()
    {
        return $this->belongsTo(Docente::class, 'docente_id', 'docente_id');
    }

    /**
     * Relación con grupo
     */
    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'grupo_id', 'grupo_id');
    }

    /**
     * Relación con aula
     */
    public function aula()
    {
        return $this->belongsTo(Aula::class, 'aula_id', 'aula_id');
    }

    /**
     * Relación con horario
     */
    public function horario()
    {
        return $this->belongsTo(Horario::class, 'horario_id', 'horario_id');
    }

    /**
     * Relación con asistencias
     */
    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'asignacion_id', 'asignacion_id');
    }
}
