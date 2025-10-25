<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErrorCarga extends Model
{
    use HasFactory;

    protected $table = 'errores_carga';
    protected $primaryKey = 'error_id';
    
    protected $fillable = [
        'carga_id',
        'numero_fila',
        'campo_error',
        'descripcion_error',
        'datos_fila'
    ];

    protected $casts = [
        'datos_fila' => 'array',
    ];

    /**
     * Relación con carga masiva
     */
    public function cargaMasiva()
    {
        return $this->belongsTo(CargaMasiva::class, 'carga_id', 'carga_id');
    }

    /**
     * Scope para filtrar por campo de error
     */
    public function scopePorCampo($query, $campo)
    {
        return $query->where('campo_error', $campo);
    }
}
