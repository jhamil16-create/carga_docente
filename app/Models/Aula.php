<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aula extends Model
{
    use HasFactory;

    protected $table = 'aula';
    protected $primaryKey = 'aula_id';
    public $timestamps = false;
    
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
