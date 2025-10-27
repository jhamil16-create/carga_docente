<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asistencia extends Model
{
    protected $table = 'asistencia';
    protected $primaryKey = 'asistencia_id';
    public $timestamps = false;

    protected $fillable = [
        'docente_id',
        'asignacion_id',
        'fecha',
        'hora_entrada',
        'estado',
        'metodo_registro'
    ];

    // No necesitas casts si no los usas, pero si quieres:
    protected $casts = [
        'fecha' => 'date',
        'hora_entrada' => 'datetime', // o 'time' si solo usas hora
    ];

    // Relaciones
    public function docente(): BelongsTo
    {
        return $this->belongsTo(Docente::class, 'docente_id', 'docente_id');
    }

    public function asignacion(): BelongsTo
    {
        return $this->belongsTo(Asignacion::class, 'asignacion_id', 'asignacion_id');
    }
}