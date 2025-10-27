@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-users"></i> {{ $grupo->nombre_grupo }}</h2>
        <div class="btn-group">
            <a href="{{ route('grupos.edit', $grupo->grupo_id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Editar
            </a>
            <a href="{{ route('grupos.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Información básica -->
    <div class="card mb-3">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-info-circle"></i> Información del Grupo</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="mb-3">
                        <strong class="text-muted">ID del Grupo</strong>
                        <div class="h4 text-primary">#{{ $grupo->grupo_id }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <strong class="text-muted">Nombre del Grupo</strong>
                        <div class="h4">{{ $grupo->nombre_grupo }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <strong class="text-muted">Capacidad Máxima</strong>
                        <div class="h4 text-info">{{ $grupo->capacidad_maxima }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <strong class="text-muted">Asignaciones</strong>
                        <div class="h4 text-success">{{ $grupo->asignaciones->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Información de la materia -->
    <div class="card mb-3">
        <div class="card-header" style="background: #e3f2fd;">
            <h5 class="mb-0"><i class="fas fa-book"></i> Materia Asignada</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <strong>Código:</strong> {{ $grupo->materia->codigo_materia }}
                </div>
                <div class="col-md-4">
                    <strong>Nombre:</strong> {{ $grupo->materia->nombre_materia }}
                </div>
                <div class="col-md-4">
                    <strong>Créditos:</strong> {{ $grupo->materia->creditos }}
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="card text-center" style="background: #e8f5e9;">
                <div class="card-body">
                    <h2 class="text-success">{{ $grupo->asignaciones->count() }}</h2>
                    <p class="text-muted mb-0">Asignaciones Activas</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center" style="background: #e3f2fd;">
                <div class="card-body">
                    <h2 class="text-primary">{{ $grupo->asignaciones->unique('aula_id')->count() }}</h2>
                    <p class="text-muted mb-0">Aulas Utilizadas</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center" style="background: #fff3e0;">
                <div class="card-body">
                    <h2 class="text-warning">{{ $grupo->asignaciones->unique('horario.dia_semana')->count() }}</h2>
                    <p class="text-muted mb-0">Días de Clase</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Asignaciones -->
    <div class="card mb-3">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-calendar-alt"></i> Horarios y Asignaciones</h5>
                @if($grupo->asignaciones->count() > 0)
                    <span class="badge bg-info">{{ $grupo->asignaciones->count() }} asignación(es)</span>
                @endif
            </div>
        </div>
        <div class="card-body">
            @if($grupo->asignaciones->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Día</th>
                                <th>Horario</th>
                                <th>Aula</th>
                                <th>Ubicación</th>
                                <th>Capacidad Aula</th>
                                <th>Docente</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($grupo->asignaciones->sortBy(['horario.dia_semana', 'horario.hora_inicio']) as $asignacion)
                            <tr>
                                <td>
                                    <span class="badge bg-primary">{{ $asignacion->horario->dia_semana }}</span>
                                </td>
                                <td>
                                    {{ date('H:i', strtotime($asignacion->horario->hora_inicio)) }} - 
                                    {{ date('H:i', strtotime($asignacion->horario->hora_fin)) }}
                                </td>
                                <td>
                                    <strong>{{ $asignacion->aula->nombre_aula }}</strong>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $asignacion->aula->ubicacion }}</small>
                                </td>
                                <td>
                                    @php
                                        $porcentaje = round(($grupo->capacidad_maxima / $asignacion->aula->capacidad) * 100);
                                        $color = $porcentaje > 90 ? 'danger' : ($porcentaje > 75 ? 'warning' : 'success');
                                    @endphp
                                    <span class="badge bg-{{ $color }}">
                                        {{ $asignacion->aula->capacidad }} ({{ $porcentaje }}% uso)
                                    </span>
                                </td>
                                <td>
                                    @if($asignacion->docente && $asignacion->docente->usuario)
                                        {{ $asignacion->docente->usuario->nombre }} {{ $asignacion->docente->usuario->apellido }}
                                    @else
                                        <span class="text-muted">No asignado</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('asignaciones.show', $asignacion->asignacion_id) }}" 
                                       class="btn btn-sm btn-info">
                                        Ver
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Resumen semanal -->
                <div class="alert alert-light mt-3">
                    <h6><i class="fas fa-calendar-week"></i> Resumen Semanal</h6>
                    @php
                        $totalMinutos = 0;
                        foreach($grupo->asignaciones as $asignacion) {
                            $inicio = new DateTime($asignacion->horario->hora_inicio);
                            $fin = new DateTime($asignacion->horario->hora_fin);
                            $duracion = $inicio->diff($fin);
                            $totalMinutos += ($duracion->h * 60) + $duracion->i;
                        }
                        $totalHoras = floor($totalMinutos / 60);
                        $minutosRestantes = $totalMinutos % 60;
                    @endphp
                    <div class="text-center">
                        <strong>Total Semanal: {{ $totalHoras }}h {{ $minutosRestantes }}m</strong>
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                    <h5>No hay asignaciones</h5>
                    <p class="text-muted">Este grupo aún no tiene horarios asignados.</p>
                    <a href="{{ route('asignaciones.create') }}?grupo_id={{ $grupo->grupo_id }}" 
                       class="btn btn-success">
                        <i class="fas fa-plus"></i> Crear Primera Asignación
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Zona de peligro -->
    <div class="card border-danger">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Zona de Peligro</h5>
        </div>
        <div class="card-body">
            @if($grupo->asignaciones->count() > 0)
                <div class="alert alert-warning">
                    <strong>⚠️ Advertencia:</strong> 
                    Este grupo tiene <strong>{{ $grupo->asignaciones->count() }} asignación(es) activa(s)</strong>. 
                    Eliminar el grupo también eliminará todas sus asignaciones asociadas.
                </div>
            @else
                <p class="text-muted">Este grupo no tiene asignaciones activas. Puede eliminarlo de forma segura.</p>
            @endif
            
            <form method="POST" action="{{ route('grupos.destroy', $grupo->grupo_id) }}" 
                  onsubmit="return confirm('¿Está seguro de que desea eliminar este grupo?{{ $grupo->asignaciones->count() > 0 ? ' Se eliminarán también todas sus asignaciones.' : '' }}')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Eliminar Grupo
                </button>
            </form>
        </div>
    </div>
</div>
@endsection