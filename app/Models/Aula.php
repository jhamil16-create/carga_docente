<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aula extends Model
{
    use HasFactory;

    protected $table = 'aulas';
    protected $primaryKey = 'aula_id';
    
    protected $fillable = [
        'nombre_aula',
        'capacidad',
        'ubicacion'
    ];

    /**
     * Relación con asignaciones
     */
    public function asignaciones()
    {
        return $this->hasMany(Asignacion::class, 'aula_id', 'aula_id');
    }
}
