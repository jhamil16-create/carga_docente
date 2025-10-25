<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    use HasFactory;

    protected $table = 'grupos';
    protected $primaryKey = 'grupo_id';
    
    protected $fillable = [
        'materia_id',
        'nombre_grupo',
        'capacidad_maxima'
    ];

    /**
     * Relación con materia
     */
    public function materia()
    {
        return $this->belongsTo(Materia::class, 'materia_id', 'materia_id');
    }

    /**
     * Relación con asignaciones
     */
    public function asignaciones()
    {
        return $this->hasMany(Asignacion::class, 'grupo_id', 'grupo_id');
    }

    /**
     * Relación con docentes a través de asignaciones
     */
    public function docentes()
    {
        return $this->hasManyThrough(
            Docente::class,
            Asignacion::class,
            'grupo_id',
            'docente_id',
            'grupo_id',
            'docente_id'
        );
    }
}
