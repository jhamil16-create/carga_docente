<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Horario extends Model
{
    protected $table = 'horario';
    protected $primaryKey = 'horario_id';
    public $timestamps = false;

    protected $fillable = [
        'dia_semana',
        'hora_inicio',
        'hora_fin'
    ];

    protected $casts = [
        'hora_inicio' => 'datetime:H:i:s',
        'hora_fin' => 'datetime:H:i:s',
    ];

    public function asignaciones(): HasMany
    {
        return $this->hasMany(Asignacion::class, 'horario_id', 'horario_id');
    }
}
