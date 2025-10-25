@extends('layouts.app')

@section('title', 'Editar Registro de Asistencia')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="fas fa-clipboard-check"></i> Editar Registro de Asistencia</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('asistencias.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Información del Registro</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('asistencias.update', $asistencia->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="asignacion_id" class="form-label">Asignación <span class="text-danger">*</span></label>
                            <select name="asignacion_id" id="asignacion_id" class="form-select" required disabled>
                                <option value="{{ $asistencia->asignacion_id }}">
                                    {{ $asistencia->asignacion->grupo->nombre_grupo }} - 
                                    {{ $asistencia->asignacion->grupo->materia->nombre }} - 
                                    {{ $asistencia->asignacion->horario->dia_semana }} 
                                    {{ $asistencia->asignacion->horario->hora_inicio }}-{{ $asistencia->asignacion->horario->hora_fin }}
                                </option>
                            </select>
                            <input type="hidden" name="asignacion_id" value="{{ $asistencia->asignacion_id }}">
                            <div class="form-text text-muted">La asignación no se puede modificar una vez creado el registro.</div>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="fecha" class="form-label">Fecha <span class="text-danger">*</span></label>
                            <input type="date" name="fecha" id="fecha" class="form-control" value="{{ old('fecha', $asistencia->fecha) }}" required>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card bg-light mb-3">
                            <div class="card-body">
                                <h6 class="card-title">Información de la Asignación</h6>
                                <p class="mb-1"><strong>Grupo:</strong> {{ $asistencia->asignacion->grupo->nombre_grupo }}</p>
                                <p class="mb-1"><strong>Materia:</strong> {{ $asistencia->asignacion->grupo->materia->nombre }}</p>
                                <p class="mb-1"><strong>Docente:</strong> {{ $asistencia->asignacion->grupo->docente->nombre }}</p>
                                <p class="mb-1"><strong>Aula:</strong> {{ $asistencia->asignacion->aula->nombre }}</p>
                                <p class="mb-1"><strong>Horario:</strong> {{ $asistencia->asignacion->horario->dia_semana }} 
                                    {{ $asistencia->asignacion->horario->hora_inicio }} - {{ $asistencia->asignacion->horario->hora_fin }}</p>
                                <p class="mb-0"><strong>Estudiantes:</strong> {{ $asistencia->asignacion->grupo->estudiantes_inscritos }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Lista de Estudiantes</h5>
                                <div>
                                    <button type="button" class="btn btn-sm btn-success" id="marcar-todos-presentes">
                                        <i class="fas fa-check"></i> Todos Presentes
                                    </button>
                                    <button type="button" class="btn btn-sm btn-warning" id="marcar-todos-tardanza">
                                        <i class="fas fa-clock"></i> Todos Tardanza
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Código</th>
                                                <th>Nombre</th>
                                                <th>Estado</th>
                                                <th>Observación</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($detalles as $detalle)
                                            <tr>
                                                <td>{{ $detalle->estudiante->codigo }}</td>
                                                <td>{{ $detalle->estudiante->nombre }}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <input type="radio" class="btn-check" name="estado_{{ $detalle->estudiante_id }}" 
                                                               id="presente_{{ $detalle->estudiante_id }}" value="presente" 
                                                               {{ $detalle->estado == 'presente' ? 'checked' : '' }}>
                                                        <label class="btn btn-outline-success btn-sm" for="presente_{{ $detalle->estudiante_id }}">Presente</label>
                                                        
                                                        <input type="radio" class="btn-check" name="estado_{{ $detalle->estudiante_id }}" 
                                                               id="tardanza_{{ $detalle->estudiante_id }}" value="tardanza" 
                                                               {{ $detalle->estado == 'tardanza' ? 'checked' : '' }}>
                                                        <label class="btn btn-outline-warning btn-sm" for="tardanza_{{ $detalle->estudiante_id }}">Tardanza</label>
                                                        
                                                        <input type="radio" class="btn-check" name="estado_{{ $detalle->estudiante_id }}" 
                                                               id="ausente_{{ $detalle->estudiante_id }}" value="ausente" 
                                                               {{ $detalle->estado == 'ausente' ? 'checked' : '' }}>
                                                        <label class="btn btn-outline-danger btn-sm" for="ausente_{{ $detalle->estudiante_id }}">Ausente</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control form-control-sm" 
                                                           name="observacion_{{ $detalle->estudiante_id }}" 
                                                           value="{{ $detalle->observacion }}" placeholder="Observación">
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12">
                        <div class="form-group mb-3">
                            <label for="observaciones" class="form-label">Observaciones Generales</label>
                            <textarea name="observaciones" id="observaciones" class="form-control" rows="3">{{ old('observaciones', $asistencia->observaciones) }}</textarea>
                            <div class="form-text">Ingrese cualquier observación relevante sobre la clase.</div>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Actualizar Registro
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Marcar todos como presentes
        $('#marcar-todos-presentes').click(function() {
            $('input[name^="estado_"][value="presente"]').prop('checked', true);
        });
        
        // Marcar todos como tardanza
        $('#marcar-todos-tardanza').click(function() {
            $('input[name^="estado_"][value="tardanza"]').prop('checked', true);
        });
    });
</script>
@endsection