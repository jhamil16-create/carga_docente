<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    use HasFactory;

    protected $table = 'materias';
    protected $primaryKey = 'materia_id';
    
    protected $fillable = [
        'nombre_materia',
        'codigo_materia',
        'creditos'
    ];

    /**
     * Relación con grupos
     */
    public function grupos()
    {
        return $this->hasMany(Grupo::class, 'materia_id', 'materia_id');
    }
}
