<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Grupo extends Model
{
    protected $table = 'grupo';
    protected $primaryKey = 'grupo_id';
    public $timestamps = false;

    protected $fillable = [
        'materia_id',
        'nombre_grupo',
        'capacidad_maxima'
    ];

    protected $casts = [
        'capacidad_maxima' => 'integer',
        'materia_id' => 'integer'
    ];

    public function materia(): BelongsTo
    {
        return $this->belongsTo(Materia::class, 'materia_id', 'materia_id');
    }

    public function asignaciones(): HasMany
    {
        return $this->hasMany(Asignacion::class, 'grupo_id', 'grupo_id');
    }
}
