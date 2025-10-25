<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    use HasFactory;

    protected $table = 'docentes';
    protected $primaryKey = 'docente_id';
    
    protected $fillable = [
        'usuario_id',
        'especialidad',
        'telefono',
        'fecha_registro'
    ];

    protected $casts = [
        'fecha_registro' => 'date',
    ];

    /**
     * Relación con usuario
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id', 'usuario_id');
    }

    /**
     * Relación con asignaciones
     */
    public function asignaciones()
    {
        return $this->hasMany(Asignacion::class, 'docente_id', 'docente_id');
    }

    /**
     * Relación con grupos a través de asignaciones
     */
    public function grupos()
    {
        return $this->hasManyThrough(
            Grupo::class,
            Asignacion::class,
            'docente_id',
            'grupo_id',
            'docente_id',
            'grupo_id'
        );
    }
}
