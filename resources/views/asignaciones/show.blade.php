@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h1 style="margin: 0; color: #333;">📋 Detalles de Asignación #{{ $asignacion->asignacion_id }}</h1>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('asignaciones.edit', $asignacion->asignacion_id) }}" style="background: #ffc107; color: #212529; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 500;">
            ✏️ Editar
        </a>
        <a href="{{ route('asignaciones.index') }}" style="background: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 500;">
            ← Volver
        </a>
    </div>
</div>

@if(session('success'))
    <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
        {{ session('success') }}
    </div>
@endif

<!-- Información Principal de la Asignación -->
<div style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 30px; margin-bottom: 20px;">
    <h2 style="margin: 0 0 25px 0; color: #333; border-bottom: 2px solid #e9ecef; padding-bottom: 10px;">
        📚 Información Principal
    </h2>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px;">
        <!-- Información del Grupo -->
        <div style="background: #e3f2fd; padding: 20px; border-radius: 8px; border-left: 4px solid #2196f3;">
            <h3 style="margin: 0 0 15px 0; color: #1976d2;">👥 Grupo</h3>
            <div style="space-y: 8px;">
                <div><strong>Nombre:</strong> {{ $asignacion->grupo->nombre_grupo }}</div>
                <div><strong>Materia:</strong> {{ $asignacion->grupo->materia->codigo_materia }} - {{ $asignacion->grupo->materia->nombre_materia }}</div>
                <div><strong>Créditos:</strong> {{ $asignacion->grupo->materia->creditos }}</div>
                <div><strong>Capacidad Máxima:</strong> {{ $asignacion->grupo->capacidad_maxima }} estudiantes</div>
                <div>
                    <a href="{{ route('grupos.show', $asignacion->grupo->grupo_id) }}" style="color: #007bff; text-decoration: none; font-weight: 500;">
                        Ver detalles del grupo →
                    </a>
                </div>
            </div>
        </div>

        <!-- Información del Docente -->
        <div style="background: #fff3e0; padding: 20px; border-radius: 8px; border-left: 4px solid #ff9800;">
            <h3 style="margin: 0 0 15px 0; color: #f57c00;">👨‍🏫 Docente</h3>
            <div style="space-y: 8px;">
                <div><strong>Nombre:</strong> {{ $asignacion->docente->usuario->nombre }} {{ $asignacion->docente->usuario->apellido }}</div>
                <div><strong>Email:</strong> {{ $asignacion->docente->usuario->email_institucional }}</div>
                <div><strong>Código:</strong> {{ $asignacion->docente->usuario->codigo_usuario }}</div>
                @if($asignacion->docente->especialidad)
                    <div><strong>Especialidad:</strong> {{ $asignacion->docente->especialidad }}</div>
                @endif
                @if($asignacion->docente->telefono)
                    <div><strong>Teléfono:</strong> {{ $asignacion->docente->telefono }}</div>
                @endif
            </div>
        </div>

        <!-- Información del Horario -->
        <div style="background: #f3e5f5; padding: 20px; border-radius: 8px; border-left: 4px solid #9c27b0;">
            <h3 style="margin: 0 0 15px 0; color: #7b1fa2;">🕐 Horario</h3>
            @php
                $inicio = new DateTime($asignacion->horario->hora_inicio);
                $fin = new DateTime($asignacion->horario->hora_fin);
                $duracion = $inicio->diff($fin);
            @endphp
            <div style="space-y: 8px;">
                <div><strong>Día:</strong> {{ $asignacion->horario->dia_semana }}</div>
                <div><strong>Hora de Inicio:</strong> {{ date('H:i', strtotime($asignacion->horario->hora_inicio)) }}</div>
                <div><strong>Hora de Fin:</strong> {{ date('H:i', strtotime($asignacion->horario->hora_fin)) }}</div>
                <div><strong>Duración:</strong> {{ $duracion->h }}h {{ $duracion->i }}m</div>
                <div><strong>ID Horario:</strong> #{{ $asignacion->horario->horario_id }}</div>
            </div>
        </div>

        <!-- Información del Aula -->
        <div style="background: #e8f5e8; padding: 20px; border-radius: 8px; border-left: 4px solid #4caf50;">
            <h3 style="margin: 0 0 15px 0; color: #2e7d32;">🏫 Aula</h3>
            <div style="space-y: 8px;">
                <div><strong>Nombre:</strong> {{ $asignacion->aula->nombre_aula }}</div>
                <div><strong>Ubicación:</strong> {{ $asignacion->aula->ubicacion }}</div>
                <div><strong>Capacidad:</strong> {{ $asignacion->aula->capacidad }} estudiantes</div>
                <div>
                    <strong>Ocupación:</strong> 
                    @php
                        $ocupacion = ($asignacion->grupo->capacidad_maxima / $asignacion->aula->capacidad) * 100;
                        $colorOcupacion = $ocupacion > 90 ? '#dc3545' : ($ocupacion > 75 ? '#ffc107' : '#28a745');
                    @endphp
                    <span style="color: {{ $colorOcupacion }}; font-weight: 600;">
                        {{ round($ocupacion, 1) }}% ({{ $asignacion->grupo->capacidad_maxima }}/{{ $asignacion->aula->capacidad }})
                    </span>
                </div>
                <div><strong>ID Aula:</strong> #{{ $asignacion->aula->aula_id }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Estadísticas y Métricas -->
<div style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 30px; margin-bottom: 20px;">
    <h2 style="margin: 0 0 25px 0; color: #333; border-bottom: 2px solid #e9ecef; padding-bottom: 10px;">
        📊 Estadísticas y Métricas
    </h2>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
        <div style="text-align: center; padding: 20px; background: #f8f9fa; border-radius: 8px;">
            <div style="font-size: 32px; font-weight: bold; color: #007bff;">
                {{ $asignacion->asistencias->count() ?? 0 }}
            </div>
            <div style="color: #666; font-size: 14px;">Registros de Asistencia</div>
        </div>
        
        <div style="text-align: center; padding: 20px; background: #f8f9fa; border-radius: 8px;">
            <div style="font-size: 32px; font-weight: bold; color: #28a745;">
                {{ \Carbon\Carbon::parse($asignacion->fecha_asignacion)->diffInDays(now()) }}
            </div>
            <div style="color: #666; font-size: 14px;">Días Activa</div>
        </div>
        
        <div style="text-align: center; padding: 20px; background: #f8f9fa; border-radius: 8px;">
            <div style="font-size: 32px; font-weight: bold; color: #ffc107;">
                {{ $asignacion->grupo->materia->creditos }}
            </div>
            <div style="color: #666; font-size: 14px;">Créditos de la Materia</div>
        </div>
        
        <div style="text-align: center; padding: 20px; background: #f8f9fa; border-radius: 8px;">
            <div style="font-size: 32px; font-weight: bold; color: {{ $colorOcupacion }};">
                {{ round($ocupacion, 0) }}%
            </div>
            <div style="color: #666; font-size: 14px;">Ocupación del Aula</div>
        </div>
    </div>
</div>

<!-- Información de Fechas -->
<div style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 30px; margin-bottom: 20px;">
    <h2 style="margin: 0 0 25px 0; color: #333; border-bottom: 2px solid #e9ecef; padding-bottom: 10px;">
        📅 Información de Fechas
    </h2>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
        <div>
            <strong>Fecha de Asignación:</strong><br>
            <span style="color: #666;">{{ \Carbon\Carbon::parse($asignacion->fecha_asignacion)->format('d/m/Y') }}</span><br>
            <small style="color: #999;">{{ \Carbon\Carbon::parse($asignacion->fecha_asignacion)->diffForHumans() }}</small>
        </div>
        
        <div>
            <strong>Próxima Clase:</strong><br>
            @php
                $hoy = now();
                $diaAsignacion = $asignacion->horario->dia_semana;
                $horaInicio = $asignacion->horario->hora_inicio;
                
                // Calcular próxima clase
                $proximaClase = $hoy->copy();
                while ($proximaClase->dayOfWeek != $diaAsignacion) {
                    $proximaClase->addDay();
                }
                
                // Si es el mismo día pero ya pasó la hora, ir a la próxima semana
                if ($proximaClase->dayOfWeek == $diaAsignacion && $hoy->format('H:i:s') > $horaInicio) {
                    $proximaClase->addWeek();
                }
                
                $proximaClase->setTimeFromTimeString($horaInicio);
            @endphp
            <span style="color: #666;">{{ $proximaClase->format('d/m/Y H:i') }}</span><br>
            <small style="color: #999;">{{ $proximaClase->diffForHumans() }}</small>
        </div>
        
        <div>
            <strong>Día de la Semana:</strong><br>
            <span style="background: #007bff; color: white; padding: 4px 12px; border-radius: 12px; font-size: 14px; font-weight: 500;">
                {{ $asignacion->horario->dia_semana }}
            </span>
        </div>
    </div>
</div>

<!-- Registros de Asistencia Recientes -->
@if($asignacion->asistencias && $asignacion->asistencias->count() > 0)
<div style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 30px; margin-bottom: 20px;">
    <h2 style="margin: 0 0 25px 0; color: #333; border-bottom: 2px solid #e9ecef; padding-bottom: 10px;">
        📝 Registros de Asistencia Recientes
    </h2>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8f9fa;">
                    <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">ID</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Fecha</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Hora Entrada</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Estado</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Método</th>
                    <th style="padding: 12px; text-align: center; border-bottom: 2px solid #dee2e6;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($asignacion->asistencias->take(10) as $asistencia)
                <tr style="border-bottom: 1px solid #dee2e6;">
                    <td style="padding: 12px; font-weight: 500;">#{{ $asistencia->asistencia_id }}</td>
                    <td style="padding: 12px;">{{ \Carbon\Carbon::parse($asistencia->fecha)->format('d/m/Y') }}</td>
                    <td style="padding: 12px;">{{ $asistencia->hora_entrada ? date('H:i', strtotime($asistencia->hora_entrada)) : '-' }}</td>
                    <td style="padding: 12px;">
                        <span style="background: {{ $asistencia->estado === 'presente' ? '#28a745' : ($asistencia->estado === 'ausente' ? '#dc3545' : '#ffc107') }}; color: white; padding: 4px 8px; border-radius: 12px; font-size: 12px;">
                            {{ ucfirst($asistencia->estado) }}
                        </span>
                    </td>
                    <td style="padding: 12px;">{{ $asistencia->metodo_registro ?? '-' }}</td>
                    <td style="padding: 12px; text-align: center;">
                        <a href="{{ route('asistencias.show', $asistencia->asistencia_id) }}" style="color: #007bff; text-decoration: none; font-size: 14px;">
                            👁️ Ver
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    @if($asignacion->asistencias->count() > 10)
    <div style="text-align: center; margin-top: 15px;">
        <a href="{{ route('asistencias.index', ['asignacion_id' => $asignacion->asignacion_id]) }}" style="color: #007bff; text-decoration: none; font-weight: 500;">
            Ver todos los registros de asistencia ({{ $asignacion->asistencias->count() }})
        </a>
    </div>
    @endif
</div>
@else
<div style="background: #f8f9fa; border-radius: 8px; padding: 30px; margin-bottom: 20px; text-align: center; border: 2px dashed #dee2e6;">
    <h3 style="margin: 0 0 15px 0; color: #666;">📝 Sin Registros de Asistencia</h3>
    <p style="margin: 0 0 20px 0; color: #666;">
        Esta asignación aún no tiene registros de asistencia.
    </p>
    <a href="{{ route('asistencias.create', ['asignacion_id' => $asignacion->asignacion_id]) }}" style="background: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: 500;">
        ➕ Registrar Primera Asistencia
    </a>
</div>
@endif

<!-- Zona de Peligro -->
<div style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 30px; border: 2px solid #dc3545;">
    <h2 style="margin: 0 0 20px 0; color: #dc3545; border-bottom: 2px solid #dc3545; padding-bottom: 10px;">
        ⚠️ Zona de Peligro
    </h2>
    
    @if($asignacion->asistencias && $asignacion->asistencias->count() > 0)
    <div style="background: #f8d7da; color: #721c24; padding: 20px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
        <h4 style="margin: 0 0 10px 0;">🚨 Advertencia Crítica</h4>
        <p style="margin: 0;">
            Esta asignación tiene <strong>{{ $asignacion->asistencias->count() }} registro(s) de asistencia</strong> asociados.
            Eliminar esta asignación también eliminará todos estos registros de forma permanente.
        </p>
    </div>
    
    <form method="POST" action="{{ route('asignaciones.destroy', $asignacion->asignacion_id) }}" 
          onsubmit="return confirm('⚠️ ADVERTENCIA CRÍTICA ⚠️\n\nEsta acción eliminará:\n- La asignación\n- Todos los {{ $asignacion->asistencias->count() }} registros de asistencia\n\n¿Está completamente seguro de que desea continuar?')" 
          style="display: inline;">
        @csrf
        @method('DELETE')
        <button type="submit" 
                style="background: #dc3545; color: white; padding: 12px 24px; border: none; border-radius: 6px; cursor: pointer; font-weight: 500;">
            🗑️ Eliminar Asignación y Todos sus Registros
        </button>
    </form>
    @else
    <div style="background: #fff3cd; color: #856404; padding: 20px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #ffeaa7;">
        <h4 style="margin: 0 0 10px 0;">⚠️ Eliminar Asignación</h4>
        <p style="margin: 0;">
            Esta asignación no tiene registros de asistencia asociados, por lo que puede eliminarse de forma segura.
        </p>
    </div>
    
    <form method="POST" action="{{ route('asignaciones.destroy', $asignacion->asignacion_id) }}" 
          onsubmit="return confirm('¿Está seguro de que desea eliminar esta asignación?\n\nEsta acción no se puede deshacer.')" 
          style="display: inline;">
        @csrf
        @method('DELETE')
        <button type="submit" 
                style="background: #dc3545; color: white; padding: 12px 24px; border: none; border-radius: 6px; cursor: pointer; font-weight: 500;">
            🗑️ Eliminar Asignación
        </button>
    </form>
    @endif
</div>
@endsection