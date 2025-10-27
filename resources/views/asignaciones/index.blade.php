@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h1 style="margin: 0; color: #333;">📅 Gestión de Asignaciones</h1>
    <a href="{{ route('asignaciones.create') }}" style="background: #28a745; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: 500;">
        ➕ Nueva Asignación
    </a>
</div>

@if(session('success'))
    <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
        {{ session('error') }}
    </div>
@endif

<!-- Estadísticas rápidas -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 20px;">
    <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); text-align: center;">
        <div style="font-size: 32px; font-weight: bold; color: #28a745; margin-bottom: 5px;">
            {{ $asignaciones->total() }}
        </div>
        <div style="color: #666; font-weight: 500;">Total Asignaciones</div>
    </div>
    
    <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); text-align: center;">
        <div style="font-size: 32px; font-weight: bold; color: #007bff; margin-bottom: 5px;">
            {{ $asignaciones->unique('docente_id')->count() }}
        </div>
        <div style="color: #666; font-weight: 500;">Docentes Asignados</div>
    </div>
    
    <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); text-align: center;">
        <div style="font-size: 32px; font-weight: bold; color: #17a2b8; margin-bottom: 5px;">
            {{ $asignaciones->unique('aula_id')->count() }}
        </div>
        <div style="color: #666; font-weight: 500;">Aulas en Uso</div>
    </div>
    
    <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); text-align: center;">
        <div style="font-size: 32px; font-weight: bold; color: #ffc107; margin-bottom: 5px;">
            {{ $asignaciones->unique('grupo_id')->count() }}
        </div>
        <div style="color: #666; font-weight: 500;">Grupos Activos</div>
    </div>
</div>

<!-- Vista de calendario semanal -->
<div style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 20px; margin-bottom: 20px;">
    <h3 style="margin: 0 0 15px 0; color: #333;">📅 Vista de Calendario Semanal</h3>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; min-width: 800px;">
            <thead>
                <tr style="background: #f8f9fa;">
                    <th style="padding: 10px; border: 1px solid #dee2e6; font-weight: 600; width: 100px;">Hora</th>
                    @php $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado']; @endphp
                    @for($dia = 1; $dia <= 6; $dia++)
                        <th style="padding: 10px; border: 1px solid #dee2e6; font-weight: 600; text-align: center;">{{ $dias[$dia] }}</th>
                    @endfor
                </tr>
            </thead>
            <tbody>
                @php
                    $horas = [];
                    foreach($asignaciones as $asignacion) {
                        $hora = date('H:i', strtotime($asignacion->horario->hora_inicio));
                        if (!in_array($hora, $horas)) {
                            $horas[] = $hora;
                        }
                    }
                    sort($horas);
                @endphp
                
                @foreach($horas as $hora)
                <tr>
                    <td style="padding: 10px; border: 1px solid #dee2e6; font-weight: 600; background: #f8f9fa;">
                        {{ $hora }}
                    </td>
                    @for($dia = 1; $dia <= 6; $dia++)
                        <td style="padding: 5px; border: 1px solid #dee2e6; vertical-align: top; min-height: 80px;">
                            @php
                                $asignacionesDiaHora = $asignaciones->filter(function($asignacion) use ($dia, $hora) {
                                    return $asignacion->horario->dia_semana == $dia && 
                                           date('H:i', strtotime($asignacion->horario->hora_inicio)) == $hora;
                                });
                            @endphp
                            
                            @foreach($asignacionesDiaHora as $asignacion)
                                <div style="background: #007bff; color: white; padding: 5px; margin: 2px; border-radius: 4px; font-size: 11px; cursor: pointer;"
                                     onclick="window.location.href='{{ route('asignaciones.show', $asignacion->asignacion_id) }}'">
                                    <div style="font-weight: 600;">{{ $asignacion->grupo->nombre_grupo }}</div>
                                    <div>{{ $asignacion->aula->nombre_aula }}</div>
                                    <div>{{ $asignacion->grupo->materia->codigo_materia }}</div>
                                    <div style="font-size: 10px;">{{ $asignacion->docente->usuario->nombre }}</div>
                                </div>
                            @endforeach
                        </td>
                    @endfor
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Lista detallada de asignaciones -->
<div style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="margin: 0; color: #333;">📋 Lista Detallada de Asignaciones</h3>
        <span style="background: #17a2b8; color: white; padding: 5px 12px; border-radius: 15px; font-size: 14px; font-weight: 500;">
            {{ $asignaciones->total() }} asignación(es)
        </span>
    </div>

    @if($asignaciones->count() > 0)
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background: #f8f9fa;">
                    <tr>
                        <th style="padding: 12px 8px; text-align: left; border-bottom: 2px solid #dee2e6; font-weight: 600;">ID</th>
                        <th style="padding: 12px 8px; text-align: left; border-bottom: 2px solid #dee2e6; font-weight: 600;">Grupo</th>
                        <th style="padding: 12px 8px; text-align: left; border-bottom: 2px solid #dee2e6; font-weight: 600;">Materia</th>
                        <th style="padding: 12px 8px; text-align: left; border-bottom: 2px solid #dee2e6; font-weight: 600;">Docente</th>
                        <th style="padding: 12px 8px; text-align: center; border-bottom: 2px solid #dee2e6; font-weight: 600;">Día</th>
                        <th style="padding: 12px 8px; text-align: center; border-bottom: 2px solid #dee2e6; font-weight: 600;">Horario</th>
                        <th style="padding: 12px 8px; text-align: left; border-bottom: 2px solid #dee2e6; font-weight: 600;">Aula</th>
                        <th style="padding: 12px 8px; text-align: center; border-bottom: 2px solid #dee2e6; font-weight: 600;">Fecha Asig.</th>
                        <th style="padding: 12px 8px; text-align: center; border-bottom: 2px solid #dee2e6; font-weight: 600;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($asignaciones as $asignacion)
                    <tr style="border-bottom: 1px solid #f1f3f4; transition: background-color 0.2s;" 
                        onmouseover="this.style.backgroundColor='#f8f9fa'" 
                        onmouseout="this.style.backgroundColor='white'">
                        <td style="padding: 12px 8px; font-weight: 500; color: #007bff;">
                            #{{ $asignacion->asignacion_id }}
                        </td>
                        <td style="padding: 12px 8px;">
                            <div style="font-weight: 500;">{{ $asignacion->grupo->nombre_grupo }}</div>
                            <div style="font-size: 12px; color: #666;">
                                Capacidad: {{ $asignacion->grupo->capacidad_maxima }}
                            </div>
                        </td>
                        <td style="padding: 12px 8px;">
                            <div style="font-weight: 500;">{{ $asignacion->grupo->materia->codigo_materia }}</div>
                            <div style="font-size: 12px; color: #666;">{{ $asignacion->grupo->materia->nombre_materia }}</div>
                        </td>
                        <td style="padding: 12px 8px;">
                            <div style="font-weight: 500;">{{ $asignacion->docente->usuario->nombre }} {{ $asignacion->docente->usuario->apellido }}</div>
                            <div style="font-size: 12px; color: #666;">{{ $asignacion->docente->usuario->email_institucional }}</div>
                        </td>
                        <td style="padding: 12px 8px; text-align: center;">
                            <span style="background: #007bff; color: white; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 500;">
                                {{ $dias[$asignacion->horario->dia_semana] }}
                            </span>
                        </td>
                        <td style="padding: 12px 8px; text-align: center;">
                            <div style="font-weight: 500;">
                                {{ date('H:i', strtotime($asignacion->horario->hora_inicio)) }} - 
                                {{ date('H:i', strtotime($asignacion->horario->hora_fin)) }}
                            </div>
                            @php
                                $inicio = new DateTime($asignacion->horario->hora_inicio);
                                $fin = new DateTime($asignacion->horario->hora_fin);
                                $duracion = $inicio->diff($fin);
                            @endphp
                            <div style="font-size: 12px; color: #666;">
                                {{ $duracion->h }}h {{ $duracion->i }}m
                            </div>
                        </td>
                        <td style="padding: 12px 8px;">
                            <div style="font-weight: 500;">{{ $asignacion->aula->nombre_aula }}</div>
                            <div style="font-size: 12px; color: #666;">
                                {{ $asignacion->aula->ubicacion }} (Cap: {{ $asignacion->aula->capacidad }})
                            </div>
                        </td>
                        <td style="padding: 12px 8px; text-align: center;">
                            <div style="font-weight: 500;">
                                {{ date('d/m/Y', strtotime($asignacion->fecha_asignacion)) }}
                            </div>
                        </td>
                        <td style="padding: 12px 8px; text-align: center;">
                            <div style="display: flex; gap: 5px; justify-content: center; flex-wrap: wrap;">
                                <a href="{{ route('asignaciones.show', $asignacion->asignacion_id) }}" 
                                   style="background: #17a2b8; color: white; padding: 5px 10px; text-decoration: none; border-radius: 4px; font-size: 12px; font-weight: 500;">
                                    👁️ Ver
                                </a>
                                <a href="{{ route('asignaciones.edit', $asignacion->asignacion_id) }}" 
                                   style="background: #ffc107; color: #212529; padding: 5px 10px; text-decoration: none; border-radius: 4px; font-size: 12px; font-weight: 500;">
                                    ✏️ Editar
                                </a>
                                <form method="POST" action="{{ route('asignaciones.destroy', $asignacion->asignacion_id) }}" 
                                      style="display: inline;" 
                                      onsubmit="return confirm('¿Está seguro de que desea eliminar esta asignación?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            style="background: #dc3545; color: white; padding: 5px 10px; border: none; border-radius: 4px; cursor: pointer; font-size: 12px; font-weight: 500;">
                                        🗑️ Eliminar
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div style="margin-top: 20px; display: flex; justify-content: center;">
            {{ $asignaciones->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 40px; color: #666;">
            <div style="font-size: 48px; margin-bottom: 15px;">📅</div>
            <h3 style="margin: 0 0 10px 0;">No hay asignaciones registradas</h3>
            <p style="margin: 0 0 20px 0;">Comience creando la primera asignación para organizar los horarios.</p>
            <a href="{{ route('asignaciones.create') }}" 
               style="background: #28a745; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: 500;">
                ➕ Crear Primera Asignación
            </a>
        </div>
    @endif
</div>

<!-- Información adicional -->
<div style="margin-top: 20px; background: #f8f9fa; padding: 15px; border-radius: 6px; border-left: 4px solid #17a2b8;">
    <h4 style="margin: 0 0 10px 0; color: #0c5460;">💡 Información</h4>
    <ul style="margin: 0; padding-left: 20px; color: #666; font-size: 14px;">
        <li>Las asignaciones conectan docentes, grupos, aulas y horarios</li>
        <li>El calendario semanal muestra una vista general de todas las asignaciones</li>
        <li>No puede haber conflictos: un docente o aula no puede estar en dos lugares al mismo tiempo</li>
        <li>Solo se pueden eliminar asignaciones sin registros de asistencia</li>
    </ul>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mejorar la experiencia del calendario
    const calendarioCeldas = document.querySelectorAll('td[onclick]');
    calendarioCeldas.forEach(celda => {
        celda.style.cursor = 'pointer';
    });
});
</script>
@endsection