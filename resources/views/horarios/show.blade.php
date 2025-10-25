@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h1 style="margin: 0; color: #333;">Detalle del Horario #{{ $horario->horario_id }}</h1>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('horarios.edit', $horario) }}" style="background: #ffc107; color: #212529; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 500;">
            Editar
        </a>
        <a href="{{ route('horarios.index') }}" style="background: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 500;">
            ← Volver
        </a>
    </div>
</div>

@if(session('success'))
    <div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
        {{ session('success') }}
    </div>
@endif

<!-- Información básica del horario -->
<div style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 30px; margin-bottom: 20px;">
    <h2 style="margin: 0 0 20px 0; color: #333; border-bottom: 2px solid #e9ecef; padding-bottom: 10px;">
        Información Básica
    </h2>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; border-left: 4px solid #17a2b8;">
            <h4 style="margin: 0 0 10px 0; color: #17a2b8;">Día de la Semana</h4>
            @php
                $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
            @endphp
            <p style="margin: 0; font-size: 18px; font-weight: 600; color: #333;">
                {{ $dias[$horario->dia_semana] }}
            </p>
        </div>

        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; border-left: 4px solid #28a745;">
            <h4 style="margin: 0 0 10px 0; color: #28a745;">Horario</h4>
            <p style="margin: 0; font-size: 18px; font-weight: 600; color: #333; font-family: monospace;">
                {{ date('H:i', strtotime($horario->hora_inicio)) }} - {{ date('H:i', strtotime($horario->hora_fin)) }}
            </p>
        </div>

        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; border-left: 4px solid #ffc107;">
            <h4 style="margin: 0 0 10px 0; color: #856404;">Duración</h4>
            @php
                $inicio = strtotime($horario->hora_inicio);
                $fin = strtotime($horario->hora_fin);
                $duracionHoras = ($fin - $inicio) / 3600;
                $horas = floor($duracionHoras);
                $minutos = round(($duracionHoras - $horas) * 60);
            @endphp
            <p style="margin: 0; font-size: 18px; font-weight: 600; color: #333;">
                @if($horas > 0){{ $horas }} hora{{ $horas > 1 ? 's' : '' }}@endif
                @if($minutos > 0){{ $horas > 0 ? ' y ' : '' }}{{ $minutos }} minuto{{ $minutos > 1 ? 's' : '' }}@endif
            </p>
        </div>

        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; border-left: 4px solid #dc3545;">
            <h4 style="margin: 0 0 10px 0; color: #dc3545;">Asignaciones Activas</h4>
            <p style="margin: 0; font-size: 18px; font-weight: 600; color: #333;">
                {{ $horario->asignaciones->count() }}
            </p>
        </div>
    </div>
</div>

<!-- Estadísticas -->
<div style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 30px; margin-bottom: 20px;">
    <h2 style="margin: 0 0 20px 0; color: #333; border-bottom: 2px solid #e9ecef; padding-bottom: 10px;">
        Estadísticas
    </h2>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
        <div style="text-align: center; padding: 15px; background: #e3f2fd; border-radius: 8px;">
            <div style="font-size: 24px; font-weight: bold; color: #1976d2;">
                {{ $horario->asignaciones->count() }}
            </div>
            <div style="color: #666; font-size: 14px;">Total Asignaciones</div>
        </div>

        <div style="text-align: center; padding: 15px; background: #e8f5e8; border-radius: 8px;">
            <div style="font-size: 24px; font-weight: bold; color: #388e3c;">
                {{ $horario->asignaciones->pluck('grupo.materia')->unique()->count() }}
            </div>
            <div style="color: #666; font-size: 14px;">Materias Diferentes</div>
        </div>

        <div style="text-align: center; padding: 15px; background: #fff3e0; border-radius: 8px;">
            <div style="font-size: 24px; font-weight: bold; color: #f57c00;">
                {{ $horario->asignaciones->pluck('aula')->unique()->count() }}
            </div>
            <div style="color: #666; font-size: 14px;">Aulas Utilizadas</div>
        </div>

        <div style="text-align: center; padding: 15px; background: #fce4ec; border-radius: 8px;">
            <div style="font-size: 24px; font-weight: bold; color: #c2185b;">
                {{ $horario->created_at->diffInDays(now()) }}
            </div>
            <div style="color: #666; font-size: 14px;">Días desde creación</div>
        </div>
    </div>
</div>

<!-- Asignaciones del horario -->
<div style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 30px; margin-bottom: 20px;">
    <h2 style="margin: 0 0 20px 0; color: #333; border-bottom: 2px solid #e9ecef; padding-bottom: 10px;">
        Asignaciones Programadas
    </h2>
    
    @if($horario->asignaciones->count() > 0)
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background: #f8f9fa;">
                    <tr>
                        <th style="padding: 15px; text-align: left; border-bottom: 1px solid #dee2e6; font-weight: 600;">Materia</th>
                        <th style="padding: 15px; text-align: left; border-bottom: 1px solid #dee2e6; font-weight: 600;">Grupo</th>
                        <th style="padding: 15px; text-align: left; border-bottom: 1px solid #dee2e6; font-weight: 600;">Aula</th>
                        <th style="padding: 15px; text-align: left; border-bottom: 1px solid #dee2e6; font-weight: 600;">Docente</th>
                        <th style="padding: 15px; text-align: center; border-bottom: 1px solid #dee2e6; font-weight: 600;">Estudiantes</th>
                        <th style="padding: 15px; text-align: center; border-bottom: 1px solid #dee2e6; font-weight: 600;">Estado</th>
                        <th style="padding: 15px; text-align: center; border-bottom: 1px solid #dee2e6; font-weight: 600;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($horario->asignaciones as $asignacion)
                    <tr style="border-bottom: 1px solid #f1f3f4;">
                        <td style="padding: 15px;">
                            <div style="font-weight: 600; color: #333;">{{ $asignacion->grupo->materia->nombre_materia }}</div>
                            <div style="font-size: 14px; color: #666;">{{ $asignacion->grupo->materia->codigo_materia }}</div>
                        </td>
                        <td style="padding: 15px;">
                            <span style="background: #e9ecef; padding: 4px 12px; border-radius: 20px; font-size: 14px; font-weight: 500;">
                                {{ $asignacion->grupo->nombre_grupo }}
                            </span>
                        </td>
                        <td style="padding: 15px;">
                            <div style="font-weight: 500;">{{ $asignacion->aula->nombre_aula }}</div>
                            <div style="font-size: 14px; color: #666;">Cap: {{ $asignacion->aula->capacidad }}</div>
                        </td>
                        <td style="padding: 15px;">
                            {{ $asignacion->grupo->docente->name ?? 'Sin asignar' }}
                        </td>
                        <td style="padding: 15px; text-align: center;">
                            <span style="background: #17a2b8; color: white; padding: 4px 8px; border-radius: 12px; font-size: 14px;">
                                {{ $asignacion->grupo->estudiantes_inscritos ?? 0 }}
                            </span>
                        </td>
                        <td style="padding: 15px; text-align: center;">
                            <span style="background: #28a745; color: white; padding: 4px 12px; border-radius: 20px; font-size: 14px;">
                                Activa
                            </span>
                        </td>
                        <td style="padding: 15px; text-align: center;">
                            <div style="display: flex; gap: 8px; justify-content: center;">
                                <a href="{{ route('asignaciones.show', $asignacion) }}" 
                                   style="background: #17a2b8; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 14px;">
                                    Ver
                                </a>
                                <a href="{{ route('asignaciones.edit', $asignacion) }}" 
                                   style="background: #ffc107; color: #212529; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 14px;">
                                    Editar
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div style="padding: 40px; text-align: center; color: #6c757d; background: #f8f9fa; border-radius: 8px;">
            <div style="font-size: 48px; margin-bottom: 15px;">📅</div>
            <p style="margin: 0; font-size: 18px;">No hay asignaciones programadas para este horario</p>
            <p style="margin: 10px 0 0 0;">
                <a href="{{ route('asignaciones.create') }}" style="color: #0d6efd; text-decoration: none;">
                    Crear una nueva asignación
                </a>
            </p>
        </div>
    @endif
</div>

<!-- Información adicional -->
<div style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 30px; margin-bottom: 20px;">
    <h2 style="margin: 0 0 20px 0; color: #333; border-bottom: 2px solid #e9ecef; padding-bottom: 10px;">
        Información del Sistema
    </h2>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <div>
            <h4 style="margin: 0 0 10px 0; color: #666;">Fecha de Creación</h4>
            <p style="margin: 0; font-weight: 500;">{{ $horario->created_at->format('d/m/Y H:i') }}</p>
        </div>
        <div>
            <h4 style="margin: 0 0 10px 0; color: #666;">Última Actualización</h4>
            <p style="margin: 0; font-weight: 500;">{{ $horario->updated_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>
</div>

<!-- Zona de peligro -->
@if($horario->asignaciones->count() == 0)
<div style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 30px; border: 2px solid #dc3545;">
    <h2 style="margin: 0 0 20px 0; color: #dc3545; border-bottom: 2px solid #dc3545; padding-bottom: 10px;">
        ⚠️ Zona de Peligro
    </h2>
    
    <p style="margin: 0 0 20px 0; color: #666;">
        Este horario no tiene asignaciones activas. Puede eliminarlo de forma segura si ya no lo necesita.
    </p>
    
    <form method="POST" action="{{ route('horarios.destroy', $horario) }}" 
          style="display: inline;" 
          onsubmit="return confirm('¿Está completamente seguro de eliminar este horario? Esta acción no se puede deshacer.')">
        @csrf
        @method('DELETE')
        <button type="submit" 
                style="background: #dc3545; color: white; padding: 12px 24px; border: none; border-radius: 6px; cursor: pointer; font-weight: 500;">
            🗑️ Eliminar Horario
        </button>
    </form>
</div>
@else
<div style="background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 8px; padding: 20px;">
    <h4 style="margin: 0 0 10px 0; color: #856404;">⚠️ Información Importante</h4>
    <p style="margin: 0; color: #856404;">
        Este horario tiene <strong>{{ $horario->asignaciones->count() }} asignación(es)</strong> activa(s) y no puede ser eliminado. 
        Para eliminarlo, primero debe eliminar o reasignar todas las asignaciones asociadas.
    </p>
</div>
@endif

<div style="margin-top: 20px; background: #f8f9fa; padding: 15px; border-radius: 6px; border-left: 4px solid #17a2b8;">
    <h4 style="margin: 0 0 10px 0; color: #17a2b8;">Información</h4>
    <p style="margin: 0; color: #666; font-size: 14px;">
        Los horarios definen los bloques de tiempo disponibles para las clases. Cada horario puede tener múltiples asignaciones en diferentes aulas, 
        permitiendo que la misma materia se imparta en varios grupos simultáneamente.
    </p>
</div>
@endsection