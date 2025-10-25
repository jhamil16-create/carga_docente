@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h1 style="margin: 0; color: #333;">Detalles de Materia</h1>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('materias.edit', $materia) }}" style="background: #ffc107; color: #212529; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 500;">
            Editar
        </a>
        <a href="{{ route('materias.index') }}" style="background: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 500;">
            ← Volver
        </a>
    </div>
</div>

@if(session('success'))
    <div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
        {{ session('success') }}
    </div>
@endif

<div style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 30px; margin-bottom: 20px;">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
        <div>
            <h3 style="margin: 0 0 15px 0; color: #333; border-bottom: 2px solid #0d6efd; padding-bottom: 8px;">Información Básica</h3>
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; color: #666; margin-bottom: 5px;">ID:</label>
                <span style="font-size: 18px; color: #333;">{{ $materia->materia_id }}</span>
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; color: #666; margin-bottom: 5px;">Código:</label>
                <span style="font-size: 18px; color: #333; font-weight: 500;">{{ $materia->codigo_materia }}</span>
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; color: #666; margin-bottom: 5px;">Nombre:</label>
                <span style="font-size: 18px; color: #333;">{{ $materia->nombre_materia }}</span>
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; color: #666; margin-bottom: 5px;">Créditos:</label>
                <span style="font-size: 18px; color: #333; background: #e9ecef; padding: 4px 12px; border-radius: 20px;">{{ $materia->creditos }}</span>
            </div>
        </div>
        
        <div>
            <h3 style="margin: 0 0 15px 0; color: #333; border-bottom: 2px solid #28a745; padding-bottom: 8px;">Estadísticas</h3>
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; color: #666; margin-bottom: 5px;">Grupos Activos:</label>
                <span style="font-size: 18px; color: #333;">{{ $materia->grupos->count() }}</span>
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; color: #666; margin-bottom: 5px;">Fecha de Creación:</label>
                <span style="font-size: 16px; color: #666;">{{ $materia->created_at->format('d/m/Y H:i') }}</span>
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; color: #666; margin-bottom: 5px;">Última Actualización:</label>
                <span style="font-size: 16px; color: #666;">{{ $materia->updated_at->format('d/m/Y H:i') }}</span>
            </div>
        </div>
    </div>
</div>

@if($materia->grupos->count() > 0)
<div style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 30px;">
    <h3 style="margin: 0 0 20px 0; color: #333; border-bottom: 2px solid #17a2b8; padding-bottom: 8px;">Grupos Asociados</h3>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: #f8f9fa;">
                <tr>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #dee2e6; font-weight: 600;">Nombre del Grupo</th>
                    <th style="padding: 12px; text-align: center; border-bottom: 1px solid #dee2e6; font-weight: 600;">Capacidad Máxima</th>
                    <th style="padding: 12px; text-align: center; border-bottom: 1px solid #dee2e6; font-weight: 600;">Asignaciones</th>
                    <th style="padding: 12px; text-align: center; border-bottom: 1px solid #dee2e6; font-weight: 600;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($materia->grupos as $grupo)
                <tr style="border-bottom: 1px solid #f1f3f4;">
                    <td style="padding: 12px; font-weight: 500;">{{ $grupo->nombre_grupo }}</td>
                    <td style="padding: 12px; text-align: center;">{{ $grupo->capacidad_maxima }}</td>
                    <td style="padding: 12px; text-align: center;">
                        <span style="background: #e9ecef; padding: 4px 8px; border-radius: 12px; font-size: 14px;">
                            {{ $grupo->asignaciones->count() }}
                        </span>
                    </td>
                    <td style="padding: 12px; text-align: center;">
                        <a href="{{ route('grupos.show', $grupo) }}" 
                           style="background: #17a2b8; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 14px;">
                            Ver Grupo
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<div style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #dc3545;">
    <h4 style="margin: 0 0 10px 0; color: #dc3545;">Zona de Peligro</h4>
    <p style="margin: 0 0 15px 0; color: #666;">Una vez eliminada, esta materia no se puede recuperar. También se eliminarán todos los grupos asociados.</p>
    <form method="POST" action="{{ route('materias.destroy', $materia) }}" 
          style="display: inline;" 
          onsubmit="return confirm('¿Está completamente seguro de eliminar esta materia? Esta acción no se puede deshacer y eliminará todos los grupos asociados.')">
        @csrf
        @method('DELETE')
        <button type="submit" 
                style="background: #dc3545; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-weight: 500;">
            Eliminar Materia
        </button>
    </form>
</div>
@endsection