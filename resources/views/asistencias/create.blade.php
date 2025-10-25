@extends('layouts.app')

@section('title', 'Crear Registro de Asistencia')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="fas fa-clipboard-check"></i> Crear Registro de Asistencia</h2>
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
            <form action="{{ route('asistencias.store') }}" method="POST">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="asignacion_id" class="form-label">Asignación <span class="text-danger">*</span></label>
                            <select name="asignacion_id" id="asignacion_id" class="form-select" required>
                                <option value="">Seleccione una asignación</option>
                                @foreach($asignaciones as $asignacion)
                                    <option value="{{ $asignacion->id }}" {{ old('asignacion_id') == $asignacion->id ? 'selected' : '' }}>
                                        {{ $asignacion->grupo->nombre_grupo }} - {{ $asignacion->grupo->materia->nombre }} - {{ $asignacion->horario->dia_semana }} {{ $asignacion->horario->hora_inicio }}-{{ $asignacion->horario->hora_fin }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="fecha" class="form-label">Fecha <span class="text-danger">*</span></label>
                            <input type="date" name="fecha" id="fecha" class="form-control" value="{{ old('fecha', date('Y-m-d')) }}" required>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div id="asignacion-info" class="card bg-light mb-3 d-none">
                            <div class="card-body">
                                <h6 class="card-title">Información de la Asignación</h6>
                                <p class="mb-1"><strong>Grupo:</strong> <span id="grupo-nombre"></span></p>
                                <p class="mb-1"><strong>Materia:</strong> <span id="materia-nombre"></span></p>
                                <p class="mb-1"><strong>Docente:</strong> <span id="docente-nombre"></span></p>
                                <p class="mb-1"><strong>Aula:</strong> <span id="aula-nombre"></span></p>
                                <p class="mb-1"><strong>Horario:</strong> <span id="horario-detalle"></span></p>
                                <p class="mb-0"><strong>Estudiantes:</strong> <span id="estudiantes-count"></span></p>
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
                                <div id="estudiantes-container">
                                    <div class="alert alert-info">
                                        Seleccione una asignación para cargar la lista de estudiantes.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12">
                        <div class="form-group mb-3">
                            <label for="observaciones" class="form-label">Observaciones Generales</label>
                            <textarea name="observaciones" id="observaciones" class="form-control" rows="3">{{ old('observaciones') }}</textarea>
                            <div class="form-text">Ingrese cualquier observación relevante sobre la clase de hoy.</div>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Registro
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
        // Simulación de datos para la demostración
        const asignacionesData = {
            1: {
                grupo: {
                    id: 1,
                    nombre_grupo: 'Grupo A',
                    estudiantes_inscritos: 25,
                    materia: { id: 1, nombre: 'Matemáticas' },
                    docente: { id: 1, nombre: 'Juan Pérez' }
                },
                horario: { id: 1, dia_semana: 'Lunes', hora_inicio: '08:00', hora_fin: '10:00' },
                aula: { id: 1, nombre: 'Aula 101', capacidad: 30 },
                estudiantes: [
                    { id: 1, nombre: 'Ana García', codigo: '2023001' },
                    { id: 2, nombre: 'Carlos López', codigo: '2023002' },
                    { id: 3, nombre: 'María Rodríguez', codigo: '2023003' },
                    { id: 4, nombre: 'Pedro Martínez', codigo: '2023004' },
                    { id: 5, nombre: 'Laura Sánchez', codigo: '2023005' }
                ]
            },
            2: {
                grupo: {
                    id: 2,
                    nombre_grupo: 'Grupo B',
                    estudiantes_inscritos: 20,
                    materia: { id: 2, nombre: 'Física' },
                    docente: { id: 2, nombre: 'María González' }
                },
                horario: { id: 2, dia_semana: 'Martes', hora_inicio: '10:00', hora_fin: '12:00' },
                aula: { id: 2, nombre: 'Aula 202', capacidad: 25 },
                estudiantes: [
                    { id: 6, nombre: 'José Ramírez', codigo: '2023006' },
                    { id: 7, nombre: 'Sofía Torres', codigo: '2023007' },
                    { id: 8, nombre: 'Daniel Flores', codigo: '2023008' },
                    { id: 9, nombre: 'Valentina Díaz', codigo: '2023009' }
                ]
            }
        };
        
        // Cuando se selecciona una asignación
        $('#asignacion_id').change(function() {
            const asignacionId = $(this).val();
            
            if (asignacionId) {
                // En un entorno real, esto sería una llamada AJAX
                const asignacion = asignacionesData[asignacionId];
                
                if (asignacion) {
                    // Mostrar información de la asignación
                    $('#asignacion-info').removeClass('d-none');
                    $('#grupo-nombre').text(asignacion.grupo.nombre_grupo);
                    $('#materia-nombre').text(asignacion.grupo.materia.nombre);
                    $('#docente-nombre').text(asignacion.grupo.docente.nombre);
                    $('#aula-nombre').text(asignacion.aula.nombre);
                    $('#horario-detalle').text(`${asignacion.horario.dia_semana} ${asignacion.horario.hora_inicio} - ${asignacion.horario.hora_fin}`);
                    $('#estudiantes-count').text(asignacion.grupo.estudiantes_inscritos);
                    
                    // Generar lista de estudiantes
                    let estudiantesHtml = '<div class="table-responsive"><table class="table table-striped table-hover">';
                    estudiantesHtml += '<thead><tr><th>Código</th><th>Nombre</th><th>Estado</th><th>Observación</th></tr></thead><tbody>';
                    
                    asignacion.estudiantes.forEach(estudiante => {
                        estudiantesHtml += `
                            <tr>
                                <td>${estudiante.codigo}</td>
                                <td>${estudiante.nombre}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <input type="radio" class="btn-check" name="estado_${estudiante.id}" id="presente_${estudiante.id}" value="presente" checked>
                                        <label class="btn btn-outline-success btn-sm" for="presente_${estudiante.id}">Presente</label>
                                        
                                        <input type="radio" class="btn-check" name="estado_${estudiante.id}" id="tardanza_${estudiante.id}" value="tardanza">
                                        <label class="btn btn-outline-warning btn-sm" for="tardanza_${estudiante.id}">Tardanza</label>
                                        
                                        <input type="radio" class="btn-check" name="estado_${estudiante.id}" id="ausente_${estudiante.id}" value="ausente">
                                        <label class="btn btn-outline-danger btn-sm" for="ausente_${estudiante.id}">Ausente</label>
                                    </div>
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm" name="observacion_${estudiante.id}" placeholder="Observación">
                                </td>
                            </tr>
                        `;
                    });
                    
                    estudiantesHtml += '</tbody></table></div>';
                    $('#estudiantes-container').html(estudiantesHtml);
                }
            } else {
                // Ocultar información si no hay asignación seleccionada
                $('#asignacion-info').addClass('d-none');
                $('#estudiantes-container').html('<div class="alert alert-info">Seleccione una asignación para cargar la lista de estudiantes.</div>');
            }
        });
        
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