<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;

    protected $table = 'horarios';
    protected $primaryKey = 'horario_id';
    
    protected $fillable = [
        'dia_semana',
        'hora_inicio',
        'hora_fin'
    ];

    protected $casts = [
        'hora_inicio' => 'datetime:H:i',
        'hora_fin' => 'datetime:H:i',
    ];

    /**
     * Relación con asignaciones
     */
    public function asignaciones()
    {
        return $this->hasMany(Asignacion::class, 'horario_id', 'horario_id');
    }
}
