<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reporte extends Model
{
    use HasFactory;

    protected $table = 'reportes';
    protected $primaryKey = 'reporte_id';
    
    protected $fillable = [
        'usuario_id',
        'tipo_reporte',
        'fecha_inicio',
        'fecha_fin',
        'fecha_generacion',
        'ruta_archivo'
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'fecha_generacion' => 'datetime',
    ];

    /**
     * Relación con usuario
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'usuario_id');
    }

    /**
     * Scope para filtrar por tipo de reporte
     */
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo_reporte', $tipo);
    }
}
