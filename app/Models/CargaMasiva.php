<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CargaMasiva extends Model
{
    use HasFactory;

    protected $table = 'cargamasiva';
    protected $primaryKey = 'carga_id';
    public $timestamps = false;
    
    protected $fillable = [
        'archivo_nombre',        
        'fecha_carga',
        'registros_exitosos',
        'registros_fallidos',
        'usuario_admin_id' 
    ];
    
    protected $casts = [
        'fecha_carga' => 'datetime',
    ];

    /**
     * Relación con usuario
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_admin_id', 'usuario_id');
    }

    /**
     * Relación con errores de carga
     */
    public function errores()
    {
        return $this->hasMany(ErrorCarga::class, 'carga_id', 'carga_id');
    }
}
