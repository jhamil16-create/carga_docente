@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h1 style="margin: 0; color: #333;">Detalles del Aula</h1>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('aulas.edit', $aula) }}" style="background: #ffc107; color: #212529; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 500;">
            Editar
        </a>
        <a href="{{ route('aulas.index') }}" style="background: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 500;">
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
                <span style="font-size: 18px; color: #333;">{{ $aula->aula_id }}</span>
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; color: #666; margin-bottom: 5px;">Nombre:</label>
                <span style="font-size: 18px; color: #333; font-weight: 500;">{{ $aula->nombre_aula }}</span>
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; color: #666; margin-bottom: 5px;">Capacidad:</label>
                <span style="font-size: 18px; color: #333; background: #e9ecef; padding: 4px 12px; border-radius: 20px;">{{ $aula->capacidad }} estudiantes</span>
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; color: #666; margin-bottom: 5px;">Ubicación:</label>
                <span style="font-size: 16px; color: #333; line-height: 1.5;">{{ $aula->ubicacion }}</span>
            </div>
        </div>
        
        <div>
            <h3 style="margin: 0 0 15px 0; color: #333; border-bottom: 2px solid #28a745; padding-bottom: 8px;">Estado y Estadísticas</h3>
            
            @php
                $ocupadaAhora = $aula->asignaciones()->whereHas('horario', function($query) {
                    $query->where('dia_semana', now()->dayOfWeek)
                          ->where('hora_inicio', '<=', now()->format('H:i:s'))
                          ->where('hora_fin', '>=', now()->format('H:i:s'));
                })->exists();
                
                $totalAsignaciones = $aula->asignaciones->count();
                $asignacionesHoy = $aula->asignaciones()->whereHas('horario', function($query) {
                    $query->where('dia_semana', now()->dayOfWeek);
                })->count();
            @endphp
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; color: #666; margin-bottom: 5px;">Estado Actual:</label>
                <span style="background: {{ $ocupadaAhora ? '#dc3545' : '#28a745' }}; color: white; padding: 6px 16px; border-radius: 20px; font-size: 16px; font-weight: 500;">
                    {{ $ocupadaAhora ? 'Ocupada' : 'Disponible' }}
                </span>
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; color: #666; margin-bottom: 5px;">Asignaciones Totales:</label>
                <span style="font-size: 18px; color: #333;">{{ $totalAsignaciones }}</span>
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; color: #666; margin-bottom: 5px;">Clases Hoy:</label>
                <span style="font-size: 18px; color: #333;">{{ $asignacionesHoy }}</span>
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; color: #666; margin-bottom: 5px;">Fecha de Creación:</label>
                <span style="font-size: 16px; color: #666;">{{ $aula->created_at->format('d/m/Y H:i') }}</span>
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; color: #666; margin-bottom: 5px;">Última Actualización:</label>
                <span style="font-size: 16px; color: #666;">{{ $aula->updated_at->format('d/m/Y H:i') }}</span>
            </div>
        </div>
    </div>
</div>

@if($aula->asignaciones->count() > 0)
<div style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 30px; margin-bottom: 20px;">
    <h3 style="margin: 0 0 20px 0; color: #333; border-bottom: 2px solid #17a2b8; padding-bottom: 8px;">Horario de Clases</h3>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: #f8f9fa;">
                <tr>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #dee2e6; font-weight: 600;">Día</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #dee2e6; font-weight: 600;">Horario</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #dee2e6; font-weight: 600;">Materia</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #dee2e6; font-weight: 600;">Grupo</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #dee2e6; font-weight: 600;">Docente</th>
                    <th style="padding: 12px; text-align: center; border-bottom: 1px solid #dee2e6; font-weight: 600;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($aula->asignaciones->sortBy('horario.dia_semana') as $asignacion)
                <tr style="border-bottom: 1px solid #f1f3f4;">
                    <td style="padding: 12px; font-weight: 500;">
                        @php
                            $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
                        @endphp
                        {{ $dias[$asignacion->horario->dia_semana] }}
                    </td>
                    <td style="padding: 12px;">
                        {{ date('H:i', strtotime($asignacion->horario->hora_inicio)) }} - 
                        {{ date('H:i', strtotime($asignacion->horario->hora_fin)) }}
                    </td>
                    <td style="padding: 12px;">{{ $asignacion->grupo->materia->nombre_materia }}</td>
                    <td style="padding: 12px;">{{ $asignacion->grupo->nombre_grupo }}</td>
                    <td style="padding: 12px;">{{ $asignacion->docente->name }}</td>
                    <td style="padding: 12px; text-align: center;">
                        <a href="{{ route('asignaciones.show', $asignacion) }}" 
                           style="background: #17a2b8; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 14px;">
                            Ver Asignación
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
    <p style="margin: 0 0 15px 0; color: #666;">Una vez eliminada, esta aula no se puede recuperar. También se eliminarán todas las asignaciones asociadas.</p>
    <form method="POST" action="{{ route('aulas.destroy', $aula) }}" 
          style="display: inline;" 
          onsubmit="return confirm('¿Está completamente seguro de eliminar esta aula? Esta acción no se puede deshacer y eliminará todas las asignaciones asociadas.')">
        @csrf
        @method('DELETE')
        <button type="submit" 
                style="background: #dc3545; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-weight: 500;">
            Eliminar Aula
        </button>
    </form>
</div>
@endsection