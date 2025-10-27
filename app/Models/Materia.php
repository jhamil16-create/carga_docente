<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Materia extends Model
{
    protected $table = 'materia';
    protected $primaryKey = 'materia_id';
    public $timestamps = false;

    protected $fillable = [
        'nombre_materia',
        'codigo_materia',
        'creditos'
    ];

    protected $casts = [
        'creditos' => 'integer',
    ];

    public function grupos(): HasMany
    {
        return $this->hasMany(Grupo::class, 'materia_id', 'materia_id');
    }
}
