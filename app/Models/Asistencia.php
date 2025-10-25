<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    use HasFactory;

    protected $table = 'asistencia';
    protected $primaryKey = 'asistencia_id';
    
    protected $fillable = [
        'asignacion_id',
        'fecha',
        'hora_registro',
        'estado',
        'observaciones'
    ];

    protected $casts = [
        'fecha' => 'date',
        'hora_registro' => 'datetime:H:i',
    ];

    /**
     * Relación con asignación
     */
    public function asignacion()
    {
        return $this->belongsTo(Asignacion::class, 'asignacion_id', 'asignacion_id');
    }

    /**
     * Scope para filtrar por estado
     */
    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    /**
     * Scope para filtrar por fecha
     */
    public function scopePorFecha($query, $fecha)
    {
        return $query->where('fecha', $fecha);
    }
}
