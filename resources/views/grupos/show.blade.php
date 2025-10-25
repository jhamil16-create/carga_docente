@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h1 style="margin: 0; color: #333;">{{ $grupo->nombre_grupo }}</h1>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('grupos.edit', $grupo) }}" style="background: #ffc107; color: #212529; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 500;">
            ✏️ Editar
        </a>
        <a href="{{ route('grupos.index') }}" style="background: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 500;">
            ← Volver
        </a>
    </div>
</div>

@if(session('success'))
    <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
        {{ session('success') }}
    </div>
@endif

<!-- Información básica del grupo -->
<div style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 30px; margin-bottom: 20px;">
    <h2 style="margin: 0 0 20px 0; color: #333; border-bottom: 2px solid #f8f9fa; padding-bottom: 10px;">
        📚 Información del Grupo
    </h2>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 20px;">
        <div style="background: #f8f9fa; padding: 15px; border-radius: 6px;">
            <h4 style="margin: 0 0 5px 0; color: #495057;">ID del Grupo</h4>
            <p style="margin: 0; font-size: 18px; font-weight: 600; color: #007bff;">
                #{{ $grupo->grupo_id }}
            </p>
        </div>
        
        <div style="background: #f8f9fa; padding: 15px; border-radius: 6px;">
            <h4 style="margin: 0 0 5px 0; color: #495057;">Nombre del Grupo</h4>
            <p style="margin: 0; font-size: 18px; font-weight: 600; color: #333;">
                {{ $grupo->nombre_grupo }}
            </p>
        </div>
        
        <div style="background: #f8f9fa; padding: 15px; border-radius: 6px;">
            <h4 style="margin: 0 0 5px 0; color: #495057;">Estado</h4>
            <span style="background: {{ $grupo->estado == 'activo' ? '#28a745' : '#dc3545' }}; color: white; padding: 8px 16px; border-radius: 20px; font-weight: 600; font-size: 14px;">
                {{ ucfirst($grupo->estado) }}
            </span>
        </div>
        
        <div style="background: #f8f9fa; padding: 15px; border-radius: 6px;">
            <h4 style="margin: 0 0 5px 0; color: #495057;">Estudiantes Inscritos</h4>
            <p style="margin: 0; font-size: 18px; font-weight: 600; color: #17a2b8;">
                {{ $grupo->estudiantes_inscritos ?? 0 }} estudiantes
            </p>
        </div>
    </div>

    <!-- Información de la materia -->
    <div style="background: #e3f2fd; padding: 20px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #2196f3;">
        <h3 style="margin: 0 0 15px 0; color: #1976d2;">📖 Materia Asignada</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
            <div>
                <strong>Código:</strong> {{ $grupo->materia->codigo_materia }}
            </div>
            <div>
                <strong>Nombre:</strong> {{ $grupo->materia->nombre_materia }}
            </div>
            <div>
                <strong>Créditos:</strong> {{ $grupo->materia->creditos }}
            </div>
            <div>
                <strong>Horas semanales:</strong> {{ $grupo->materia->creditos * 2 }}h estimadas
            </div>
        </div>
    </div>

    <!-- Información del docente -->
    <div style="background: #f3e5f5; padding: 20px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #9c27b0;">
        <h3 style="margin: 0 0 15px 0; color: #7b1fa2;">👨‍🏫 Docente Asignado</h3>
        @if($grupo->docente)
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                <div>
                    <strong>Nombre:</strong> {{ $grupo->docente->name }}
                </div>
                <div>
                    <strong>Email:</strong> {{ $grupo->docente->email }}
                </div>
                <div>
                    <strong>Rol:</strong> {{ ucfirst($grupo->docente->role) }}
                </div>
                <div>
                    <strong>Estado:</strong> 
                    <span style="background: #4caf50; color: white; padding: 2px 8px; border-radius: 12px; font-size: 12px;">
                        Asignado
                    </span>
                </div>
            </div>
        @else
            <div style="color: #666; font-style: italic;">
                ⚠️ No hay docente asignado a este grupo
            </div>
        @endif
    </div>

    @if($grupo->descripcion)
    <div style="background: #fff3e0; padding: 20px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #ff9800;">
        <h3 style="margin: 0 0 10px 0; color: #f57c00;">📝 Descripción</h3>
        <p style="margin: 0; color: #333; line-height: 1.6;">{{ $grupo->descripcion }}</p>
    </div>
    @endif
</div>

<!-- Estadísticas del grupo -->
<div style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 30px; margin-bottom: 20px;">
    <h2 style="margin: 0 0 20px 0; color: #333; border-bottom: 2px solid #f8f9fa; padding-bottom: 10px;">
        📊 Estadísticas del Grupo
    </h2>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
        <div style="text-align: center; background: #e8f5e8; padding: 20px; border-radius: 8px;">
            <div style="font-size: 32px; font-weight: bold; color: #28a745; margin-bottom: 5px;">
                {{ $grupo->asignaciones->count() }}
            </div>
            <div style="color: #666; font-weight: 500;">Asignaciones Activas</div>
        </div>
        
        <div style="text-align: center; background: #e3f2fd; padding: 20px; border-radius: 8px;">
            <div style="font-size: 32px; font-weight: bold; color: #2196f3; margin-bottom: 5px;">
                {{ $grupo->asignaciones->unique('aula_id')->count() }}
            </div>
            <div style="color: #666; font-weight: 500;">Aulas Utilizadas</div>
        </div>
        
        <div style="text-align: center; background: #fff3e0; padding: 20px; border-radius: 8px;">
            <div style="font-size: 32px; font-weight: bold; color: #ff9800; margin-bottom: 5px;">
                {{ $grupo->asignaciones->unique('horario.dia_semana')->count() }}
            </div>
            <div style="color: #666; font-weight: 500;">Días de Clase</div>
        </div>
        
        <div style="text-align: center; background: #f3e5f5; padding: 20px; border-radius: 8px;">
            <div style="font-size: 32px; font-weight: bold; color: #9c27b0; margin-bottom: 5px;">
                {{ $grupo->created_at->diffInDays(now()) }}
            </div>
            <div style="color: #666; font-weight: 500;">Días Creado</div>
        </div>
    </div>
</div>

<!-- Asignaciones del grupo -->
<div style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 30px; margin-bottom: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="margin: 0; color: #333;">🗓️ Horarios y Asignaciones</h2>
        @if($grupo->asignaciones->count() > 0)
            <span style="background: #17a2b8; color: white; padding: 5px 12px; border-radius: 15px; font-size: 14px; font-weight: 500;">
                {{ $grupo->asignaciones->count() }} asignación(es)
            </span>
        @endif
    </div>

    @if($grupo->asignaciones->count() > 0)
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; background: white;">
                <thead style="background: #f8f9fa;">
                    <tr>
                        <th style="padding: 15px 10px; text-align: left; border-bottom: 2px solid #dee2e6; font-weight: 600;">Día</th>
                        <th style="padding: 15px 10px; text-align: left; border-bottom: 2px solid #dee2e6; font-weight: 600;">Horario</th>
                        <th style="padding: 15px 10px; text-align: left; border-bottom: 2px solid #dee2e6; font-weight: 600;">Aula</th>
                        <th style="padding: 15px 10px; text-align: center; border-bottom: 2px solid #dee2e6; font-weight: 600;">Capacidad</th>
                        <th style="padding: 15px 10px; text-align: center; border-bottom: 2px solid #dee2e6; font-weight: 600;">Duración</th>
                        <th style="padding: 15px 10px; text-align: center; border-bottom: 2px solid #dee2e6; font-weight: 600;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
                        $asignacionesOrdenadas = $grupo->asignaciones->sortBy(['horario.dia_semana', 'horario.hora_inicio']);
                    @endphp
                    @foreach($asignacionesOrdenadas as $asignacion)
                    <tr style="border-bottom: 1px solid #f1f3f4; transition: background-color 0.2s;" 
                        onmouseover="this.style.backgroundColor='#f8f9fa'" 
                        onmouseout="this.style.backgroundColor='white'">
                        <td style="padding: 15px 10px; font-weight: 500;">
                            <span style="background: #007bff; color: white; padding: 4px 8px; border-radius: 12px; font-size: 12px;">
                                {{ $dias[$asignacion->horario->dia_semana] }}
                            </span>
                        </td>
                        <td style="padding: 15px 10px;">
                            <div style="font-weight: 500;">
                                {{ date('H:i', strtotime($asignacion->horario->hora_inicio)) }} - 
                                {{ date('H:i', strtotime($asignacion->horario->hora_fin)) }}
                            </div>
                        </td>
                        <td style="padding: 15px 10px;">
                            <div style="font-weight: 500;">{{ $asignacion->aula->nombre_aula }}</div>
                            <div style="font-size: 12px; color: #666;">{{ $asignacion->aula->ubicacion }}</div>
                        </td>
                        <td style="padding: 15px 10px; text-align: center;">
                            @php
                                $porcentajeUso = $grupo->estudiantes_inscritos > 0 ? 
                                    round(($grupo->estudiantes_inscritos / $asignacion->aula->capacidad) * 100) : 0;
                                $colorCapacidad = $porcentajeUso > 90 ? '#dc3545' : ($porcentajeUso > 75 ? '#ffc107' : '#28a745');
                            @endphp
                            <div style="font-weight: 500;">{{ $asignacion->aula->capacidad }}</div>
                            <div style="font-size: 12px; color: {{ $colorCapacidad }};">
                                {{ $porcentajeUso }}% uso
                            </div>
                        </td>
                        <td style="padding: 15px 10px; text-align: center;">
                            @php
                                $inicio = new DateTime($asignacion->horario->hora_inicio);
                                $fin = new DateTime($asignacion->horario->hora_fin);
                                $duracion = $inicio->diff($fin);
                                $horas = $duracion->h;
                                $minutos = $duracion->i;
                            @endphp
                            <span style="background: #6c757d; color: white; padding: 2px 8px; border-radius: 12px; font-size: 12px;">
                                {{ $horas }}h {{ $minutos }}m
                            </span>
                        </td>
                        <td style="padding: 15px 10px; text-align: center;">
                            <div style="display: flex; gap: 5px; justify-content: center;">
                                <a href="{{ route('asignaciones.show', $asignacion) }}" 
                                   style="background: #17a2b8; color: white; padding: 5px 10px; text-decoration: none; border-radius: 4px; font-size: 12px;">
                                    Ver
                                </a>
                                <a href="{{ route('asignaciones.edit', $asignacion) }}" 
                                   style="background: #ffc107; color: #212529; padding: 5px 10px; text-decoration: none; border-radius: 4px; font-size: 12px;">
                                    Editar
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Resumen de horarios -->
        <div style="margin-top: 20px; background: #f8f9fa; padding: 15px; border-radius: 6px;">
            <h4 style="margin: 0 0 10px 0; color: #495057;">📅 Resumen Semanal</h4>
            @php
                $horasPorDia = [];
                foreach($grupo->asignaciones as $asignacion) {
                    $dia = $dias[$asignacion->horario->dia_semana];
                    $inicio = new DateTime($asignacion->horario->hora_inicio);
                    $fin = new DateTime($asignacion->horario->hora_fin);
                    $duracion = $inicio->diff($fin);
                    $minutosTotales = ($duracion->h * 60) + $duracion->i;
                    
                    if (!isset($horasPorDia[$dia])) {
                        $horasPorDia[$dia] = 0;
                    }
                    $horasPorDia[$dia] += $minutosTotales;
                }
                
                $totalMinutos = array_sum($horasPorDia);
                $totalHoras = floor($totalMinutos / 60);
                $minutosRestantes = $totalMinutos % 60;
            @endphp
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 10px; margin-bottom: 15px;">
                @foreach($horasPorDia as $dia => $minutos)
                    @php
                        $horas = floor($minutos / 60);
                        $mins = $minutos % 60;
                    @endphp
                    <div style="text-align: center; background: white; padding: 10px; border-radius: 4px;">
                        <div style="font-weight: 600; color: #333;">{{ $dia }}</div>
                        <div style="color: #666; font-size: 14px;">{{ $horas }}h {{ $mins }}m</div>
                    </div>
                @endforeach
            </div>
            
            <div style="text-align: center; background: #007bff; color: white; padding: 10px; border-radius: 4px;">
                <strong>Total Semanal: {{ $totalHoras }}h {{ $minutosRestantes }}m</strong>
            </div>
        </div>
    @else
        <div style="text-align: center; padding: 40px; color: #666;">
            <div style="font-size: 48px; margin-bottom: 15px;">📅</div>
            <h3 style="margin: 0 0 10px 0;">No hay asignaciones</h3>
            <p style="margin: 0 0 20px 0;">Este grupo aún no tiene horarios asignados.</p>
            <a href="{{ route('asignaciones.create') }}?grupo_id={{ $grupo->grupo_id }}" 
               style="background: #28a745; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: 500;">
                ➕ Crear Primera Asignación
            </a>
        </div>
    @endif
</div>

<!-- Información de fechas -->
<div style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 30px; margin-bottom: 20px;">
    <h2 style="margin: 0 0 20px 0; color: #333; border-bottom: 2px solid #f8f9fa; padding-bottom: 10px;">
        📅 Información de Fechas
    </h2>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
        <div style="background: #e8f5e8; padding: 15px; border-radius: 6px;">
            <h4 style="margin: 0 0 5px 0; color: #28a745;">Fecha de Creación</h4>
            <p style="margin: 0; color: #333;">
                {{ $grupo->created_at->format('d/m/Y H:i') }}
            </p>
            <p style="margin: 5px 0 0 0; font-size: 14px; color: #666;">
                Hace {{ $grupo->created_at->diffForHumans() }}
            </p>
        </div>
        
        <div style="background: #fff3e0; padding: 15px; border-radius: 6px;">
            <h4 style="margin: 0 0 5px 0; color: #ff9800;">Última Actualización</h4>
            <p style="margin: 0; color: #333;">
                {{ $grupo->updated_at->format('d/m/Y H:i') }}
            </p>
            <p style="margin: 5px 0 0 0; font-size: 14px; color: #666;">
                Hace {{ $grupo->updated_at->diffForHumans() }}
            </p>
        </div>
    </div>
</div>

<!-- Zona de peligro -->
<div style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 30px; border: 2px solid #dc3545;">
    <h2 style="margin: 0 0 20px 0; color: #dc3545;">⚠️ Zona de Peligro</h2>
    
    @if($grupo->asignaciones->count() > 0)
        <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 6px; margin-bottom: 20px;">
            <h4 style="margin: 0 0 10px 0;">⚠️ Advertencia</h4>
            <p style="margin: 0;">
                Este grupo tiene <strong>{{ $grupo->asignaciones->count() }} asignación(es) activa(s)</strong>. 
                Eliminar el grupo también eliminará todas sus asignaciones y registros de asistencia asociados.
            </p>
        </div>
        
        <form method="POST" action="{{ route('grupos.destroy', $grupo) }}" 
              onsubmit="return confirm('⚠️ ADVERTENCIA: Esta acción eliminará el grupo y TODAS sus asignaciones y registros de asistencia.\n\n¿Está completamente seguro de que desea continuar?\n\nEscriba ELIMINAR para confirmar:')" 
              style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    style="background: #dc3545; color: white; padding: 12px 24px; border: none; border-radius: 6px; cursor: pointer; font-weight: 500;">
                🗑️ Eliminar Grupo y Todas sus Asignaciones
            </button>
        </form>
    @else
        <p style="margin: 0 0 20px 0; color: #666;">
            Este grupo no tiene asignaciones activas. Puede eliminarlo de forma segura.
        </p>
        
        <form method="POST" action="{{ route('grupos.destroy', $grupo) }}" 
              onsubmit="return confirm('¿Está seguro de que desea eliminar este grupo?')" 
              style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    style="background: #dc3545; color: white; padding: 12px 24px; border: none; border-radius: 6px; cursor: pointer; font-weight: 500;">
                🗑️ Eliminar Grupo
            </button>
        </form>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mejorar la confirmación de eliminación para grupos con asignaciones
    const deleteForm = document.querySelector('form[action*="destroy"]');
    if (deleteForm && {{ $grupo->asignaciones->count() }} > 0) {
        deleteForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const confirmText = prompt('⚠️ ADVERTENCIA CRÍTICA ⚠️\n\nEsta acción eliminará:\n- El grupo "{{ $grupo->nombre_grupo }}"\n- {{ $grupo->asignaciones->count() }} asignación(es)\n- Todos los registros de asistencia asociados\n\nEsta acción NO se puede deshacer.\n\nPara confirmar, escriba exactamente: ELIMINAR');
            
            if (confirmText === 'ELIMINAR') {
                this.submit();
            } else if (confirmText !== null) {
                alert('Texto incorrecto. Eliminación cancelada.');
            }
        });
    }
});
</script>
@endsection