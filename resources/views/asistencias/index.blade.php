@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h1 style="margin: 0; color: #333;">📝 Gestión de Asistencia</h1>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('asistencias.registro-rapido') }}" style="background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 500;">
            ⚡ Registro Rápido
        </a>
        <a href="{{ route('asistencias.create') }}" style="background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 500;">
            ➕ Nueva Asistencia
        </a>
    </div>
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

<!-- Estadísticas Rápidas -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 25px;">
    <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); text-align: center; border-left: 4px solid #007bff;">
        <div style="font-size: 32px; font-weight: bold; color: #007bff; margin-bottom: 5px;">
            {{ $estadisticas['total_registros'] ?? 0 }}
        </div>
        <div style="color: #666; font-size: 14px;">Total Registros</div>
    </div>
    
    <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); text-align: center; border-left: 4px solid #28a745;">
        <div style="font-size: 32px; font-weight: bold; color: #28a745; margin-bottom: 5px;">
            {{ $estadisticas['presentes_hoy'] ?? 0 }}
        </div>
        <div style="color: #666; font-size: 14px;">Presentes Hoy</div>
    </div>
    
    <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); text-align: center; border-left: 4px solid #dc3545;">
        <div style="font-size: 32px; font-weight: bold; color: #dc3545; margin-bottom: 5px;">
            {{ $estadisticas['ausentes_hoy'] ?? 0 }}
        </div>
        <div style="color: #666; font-size: 14px;">Ausentes Hoy</div>
    </div>
    
    <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); text-align: center; border-left: 4px solid #ffc107;">
        <div style="font-size: 32px; font-weight: bold; color: #ffc107; margin-bottom: 5px;">
            {{ $estadisticas['tardanzas_hoy'] ?? 0 }}
        </div>
        <div style="color: #666; font-size: 14px;">Tardanzas Hoy</div>
    </div>
    
    <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); text-align: center; border-left: 4px solid #6f42c1;">
        <div style="font-size: 32px; font-weight: bold; color: #6f42c1; margin-bottom: 5px;">
            {{ $estadisticas['clases_activas'] ?? 0 }}
        </div>
        <div style="color: #666; font-size: 14px;">Clases Activas</div>
    </div>
    
    <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); text-align: center; border-left: 4px solid #17a2b8;">
        <div style="font-size: 32px; font-weight: bold; color: #17a2b8; margin-bottom: 5px;">
            {{ round($estadisticas['porcentaje_asistencia'] ?? 0, 1) }}%
        </div>
        <div style="color: #666; font-size: 14px;">Asistencia Promedio</div>
    </div>
</div>

<!-- Filtros Avanzados -->
<div style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 25px; margin-bottom: 20px;">
    <h3 style="margin: 0 0 20px 0; color: #333;">🔍 Filtros de Búsqueda</h3>
    
    <form method="GET" action="{{ route('asistencias.index') }}" id="filtros-form">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 20px;">
            <!-- Filtro por Fecha -->
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #333;">Fecha</label>
                <input type="date" name="fecha" value="{{ request('fecha') }}" 
                       style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 4px;">
            </div>
            
            <!-- Filtro por Asignación -->
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #333;">Asignación</label>
                <select name="asignacion_id" style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 4px;">
                    <option value="">Todas las asignaciones</option>
                    @foreach($asignaciones as $asignacion)
                        <option value="{{ $asignacion->asignacion_id }}" {{ request('asignacion_id') == $asignacion->asignacion_id ? 'selected' : '' }}>
                            {{ $asignacion->grupo->nombre_grupo }} - {{ $asignacion->grupo->materia->codigo_materia }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Filtro por Estado -->
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #333;">Estado</label>
                <select name="estado" style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 4px;">
                    <option value="">Todos los estados</option>
                    <option value="presente" {{ request('estado') == 'presente' ? 'selected' : '' }}>Presente</option>
                    <option value="ausente" {{ request('estado') == 'ausente' ? 'selected' : '' }}>Ausente</option>
                    <option value="tardanza" {{ request('estado') == 'tardanza' ? 'selected' : '' }}>Tardanza</option>
                    <option value="justificado" {{ request('estado') == 'justificado' ? 'selected' : '' }}>Justificado</option>
                </select>
            </div>
            
            <!-- Filtro por Estudiante -->
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #333;">Estudiante</label>
                <input type="text" name="estudiante" value="{{ request('estudiante') }}" placeholder="Nombre del estudiante"
                       style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 4px;">
            </div>
            
            <!-- Filtro por Materia -->
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #333;">Materia</label>
                <select name="materia_id" style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 4px;">
                    <option value="">Todas las materias</option>
                    @foreach($materias as $materia)
                        <option value="{{ $materia->materia_id }}" {{ request('materia_id') == $materia->materia_id ? 'selected' : '' }}>
                            {{ $materia->codigo_materia }} - {{ $materia->nombre_materia }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Filtro por Docente -->
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #333;">Docente</label>
                <select name="docente_id" style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 4px;">
                    <option value="">Todos los docentes</option>
                    @foreach($docentes as $docente)
                        <option value="{{ $docente->id }}" {{ request('docente_id') == $docente->id ? 'selected' : '' }}>
                            {{ $docente->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <div style="display: flex; gap: 10px; justify-content: flex-end;">
            <a href="{{ route('asistencias.index') }}" style="background: #6c757d; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px; font-size: 14px;">
                🔄 Limpiar
            </a>
            <button type="submit" style="background: #007bff; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; font-size: 14px;">
                🔍 Filtrar
            </button>
        </div>
    </form>
</div>

<!-- Resumen de Asistencia por Día de la Semana -->
<div style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 25px; margin-bottom: 20px;">
    <h3 style="margin: 0 0 20px 0; color: #333;">📊 Resumen Semanal de Asistencia</h3>
    
    <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 10px;">
        @php
            $dias = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
            $coloresDias = ['#dc3545', '#28a745', '#007bff', '#ffc107', '#6f42c1', '#17a2b8', '#fd7e14'];
        @endphp
        @foreach($dias as $index => $dia)
            @php
                $asistenciaDia = $resumenSemanal[$index] ?? ['presente' => 0, 'ausente' => 0, 'tardanza' => 0, 'total' => 0];
                $porcentaje = $asistenciaDia['total'] > 0 ? round(($asistenciaDia['presente'] / $asistenciaDia['total']) * 100, 1) : 0;
            @endphp
            <div style="background: {{ $coloresDias[$index] }}; color: white; padding: 15px; border-radius: 8px; text-align: center;">
                <div style="font-weight: bold; margin-bottom: 5px;">{{ $dia }}</div>
                <div style="font-size: 20px; font-weight: bold; margin-bottom: 5px;">{{ $porcentaje }}%</div>
                <div style="font-size: 12px; opacity: 0.9;">
                    {{ $asistenciaDia['presente'] }}/{{ $asistenciaDia['total'] }}
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Lista de Registros de Asistencia -->
<div style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 25px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="margin: 0; color: #333;">📋 Registros de Asistencia</h3>
        <div style="color: #666; font-size: 14px;">
            Mostrando {{ $asistencias->firstItem() ?? 0 }} - {{ $asistencias->lastItem() ?? 0 }} de {{ $asistencias->total() ?? 0 }} registros
        </div>
    </div>
    
    @if($asistencias && $asistencias->count() > 0)
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa;">
                        <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6; font-weight: 600;">Fecha</th>
                        <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6; font-weight: 600;">Estudiante</th>
                        <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6; font-weight: 600;">Asignación</th>
                        <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6; font-weight: 600;">Materia</th>
                        <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6; font-weight: 600;">Docente</th>
                        <th style="padding: 12px; text-align: center; border-bottom: 2px solid #dee2e6; font-weight: 600;">Estado</th>
                        <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6; font-weight: 600;">Observaciones</th>
                        <th style="padding: 12px; text-align: center; border-bottom: 2px solid #dee2e6; font-weight: 600;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($asistencias as $asistencia)
                        <tr style="border-bottom: 1px solid #dee2e6; {{ $loop->even ? 'background: #f8f9fa;' : '' }}">
                            <td style="padding: 12px;">
                                {{ $asistencia->fecha->format('d/m/Y') }}<br>
                                <small style="color: #666;">{{ $asistencia->fecha->format('l') }}</small>
                            </td>
                            <td style="padding: 12px;">
                                <strong>{{ $asistencia->estudiante->name ?? 'N/A' }}</strong><br>
                                <small style="color: #666;">{{ $asistencia->estudiante->email ?? '' }}</small>
                            </td>
                            <td style="padding: 12px;">
                                {{ $asistencia->asignacion->grupo->nombre_grupo }}<br>
                                <small style="color: #666;">
                                    @php
                                        $dias = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
                                    @endphp
                                    {{ $dias[$asistencia->asignacion->horario->dia_semana] }} 
                                    {{ date('H:i', strtotime($asistencia->asignacion->horario->hora_inicio)) }}
                                </small>
                            </td>
                            <td style="padding: 12px;">
                                <strong>{{ $asistencia->asignacion->grupo->materia->codigo_materia }}</strong><br>
                                <small style="color: #666;">{{ $asistencia->asignacion->grupo->materia->nombre_materia }}</small>
                            </td>
                            <td style="padding: 12px;">
                                {{ $asistencia->asignacion->grupo->docente->name ?? 'Sin asignar' }}
                            </td>
                            <td style="padding: 12px; text-align: center;">
                                @php
                                    $estadoColors = [
                                        'presente' => '#28a745',
                                        'ausente' => '#dc3545',
                                        'tardanza' => '#ffc107',
                                        'justificado' => '#17a2b8'
                                    ];
                                    $estadoIcons = [
                                        'presente' => '✅',
                                        'ausente' => '❌',
                                        'tardanza' => '⏰',
                                        'justificado' => '📝'
                                    ];
                                @endphp
                                <span style="background: {{ $estadoColors[$asistencia->estado] ?? '#6c757d' }}; color: white; padding: 6px 12px; border-radius: 12px; font-size: 12px; font-weight: 500;">
                                    {{ $estadoIcons[$asistencia->estado] ?? '❓' }} {{ ucfirst($asistencia->estado) }}
                                </span>
                            </td>
                            <td style="padding: 12px;">
                                {{ $asistencia->observaciones ? Str::limit($asistencia->observaciones, 50) : '-' }}
                            </td>
                            <td style="padding: 12px; text-align: center;">
                                <div style="display: flex; gap: 5px; justify-content: center;">
                                    <a href="{{ route('asistencias.show', $asistencia->asistencia_id) }}" 
                                       style="background: #17a2b8; color: white; padding: 6px 10px; text-decoration: none; border-radius: 4px; font-size: 12px;">
                                        👁️
                                    </a>
                                    <a href="{{ route('asistencias.edit', $asistencia->asistencia_id) }}" 
                                       style="background: #ffc107; color: #212529; padding: 6px 10px; text-decoration: none; border-radius: 4px; font-size: 12px;">
                                        ✏️
                                    </a>
                                    <form method="POST" action="{{ route('asistencias.destroy', $asistencia->asistencia_id) }}" 
                                          style="display: inline;" 
                                          onsubmit="return confirm('¿Está seguro de eliminar este registro de asistencia?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                style="background: #dc3545; color: white; padding: 6px 10px; border: none; border-radius: 4px; cursor: pointer; font-size: 12px;">
                                            🗑️
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
        @if($asistencias->hasPages())
            <div style="margin-top: 20px; display: flex; justify-content: center;">
                {{ $asistencias->appends(request()->query())->links() }}
            </div>
        @endif
    @else
        <div style="text-align: center; padding: 40px; color: #666;">
            <div style="font-size: 48px; margin-bottom: 20px;">📝</div>
            <h3 style="margin: 0 0 10px 0;">No hay registros de asistencia</h3>
            <p style="margin: 0 0 20px 0;">
                @if(request()->hasAny(['fecha', 'asignacion_id', 'estado', 'estudiante', 'materia_id', 'docente_id']))
                    No se encontraron registros que coincidan con los filtros aplicados.
                @else
                    Aún no se han registrado asistencias en el sistema.
                @endif
            </p>
            <div style="display: flex; gap: 10px; justify-content: center;">
                @if(request()->hasAny(['fecha', 'asignacion_id', 'estado', 'estudiante', 'materia_id', 'docente_id']))
                    <a href="{{ route('asistencias.index') }}" style="background: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px;">
                        🔄 Limpiar Filtros
                    </a>
                @endif
                <a href="{{ route('asistencias.registro-rapido') }}" style="background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px;">
                    ⚡ Registro Rápido
                </a>
                <a href="{{ route('asistencias.create') }}" style="background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px;">
                    ➕ Registrar Asistencia
                </a>
            </div>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit del formulario de filtros cuando cambian los selects
    const filtrosForm = document.getElementById('filtros-form');
    const selects = filtrosForm.querySelectorAll('select');
    
    selects.forEach(select => {
        select.addEventListener('change', function() {
            // Opcional: auto-submit cuando cambia un select
            // filtrosForm.submit();
        });
    });
    
    // Actualizar estadísticas cada 30 segundos
    setInterval(function() {
        // Aquí podrías hacer una llamada AJAX para actualizar las estadísticas en tiempo real
        // Por ahora, solo mostramos un indicador visual
        const estadisticas = document.querySelectorAll('[style*="font-size: 32px"]');
        estadisticas.forEach(stat => {
            stat.style.transition = 'all 0.3s ease';
            stat.style.transform = 'scale(1.05)';
            setTimeout(() => {
                stat.style.transform = 'scale(1)';
            }, 300);
        });
    }, 30000); // 30 segundos
    
    // Confirmar eliminación
    const deleteButtons = document.querySelectorAll('form[method="POST"] button[type="submit"]');
    deleteButtons.forEach(button => {
        if (button.textContent.includes('🗑️')) {
            button.addEventListener('click', function(e) {
                if (!confirm('¿Está seguro de que desea eliminar este registro de asistencia?\n\nEsta acción no se puede deshacer.')) {
                    e.preventDefault();
                }
            });
        }
    });
});
</script>
@endsection