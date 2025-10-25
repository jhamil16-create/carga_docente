<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CargaMasiva extends Model
{
    use HasFactory;

    protected $table = 'carga_masiva';
    protected $primaryKey = 'carga_id';
    
    protected $fillable = [
        'usuario_id',
        'tipo_carga',
        'nombre_archivo',
        'fecha_carga',
        'registros_procesados',
        'registros_exitosos',
        'registros_fallidos',
        'estado'
    ];

    protected $casts = [
        'fecha_carga' => 'datetime',
    ];

    /**
     * Relación con usuario
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id', 'usuario_id');
    }

    /**
     * Relación con errores de carga
     */
    public function errores()
    {
        return $this->hasMany(ErrorCarga::class, 'carga_id', 'carga_id');
    }

    /**
     * Scope para filtrar por estado
     */
    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    /**
     * Scope para filtrar por tipo de carga
     */
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo_carga', $tipo);
    }
}
