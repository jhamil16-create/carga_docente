@extends('layouts.app')

@section('title', 'Detalle de Registro de Asistencia')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="fas fa-clipboard-check"></i> Detalle de Registro de Asistencia</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('asistencias.edit', $asistencia->id) }}" class="btn btn-primary me-2">
                <i class="fas fa-edit"></i> Editar
            </a>
            <a href="{{ route('asistencias.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Información General</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-muted">Fecha</h6>
                        <p class="fs-5">{{ date('d/m/Y', strtotime($asistencia->fecha)) }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-muted">Grupo</h6>
                        <p class="fs-5">{{ $asistencia->asignacion->grupo->nombre_grupo }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-muted">Materia</h6>
                        <p class="fs-5">{{ $asistencia->asignacion->grupo->materia->nombre }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-muted">Docente</h6>
                        <p class="fs-5">{{ $asistencia->asignacion->grupo->docente->nombre }}</p>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Detalles de la Clase</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-muted">Aula</h6>
                        <p>{{ $asistencia->asignacion->aula->nombre }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-muted">Horario</h6>
                        <p>{{ $asistencia->asignacion->horario->dia_semana }} {{ $asistencia->asignacion->horario->hora_inicio }} - {{ $asistencia->asignacion->horario->hora_fin }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-muted">Observaciones</h6>
                        <p>{{ $asistencia->observaciones ?: 'Sin observaciones' }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-muted">Registro creado</h6>
                        <p>{{ $asistencia->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-muted">Última actualización</h6>
                        <p>{{ $asistencia->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Estadísticas de Asistencia</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="card bg-success text-white mb-3">
                                <div class="card-body py-3">
                                    <h3 class="mb-0">{{ $estadisticas['presentes'] }}</h3>
                                    <p class="mb-0">Presentes</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="card bg-warning text-white mb-3">
                                <div class="card-body py-3">
                                    <h3 class="mb-0">{{ $estadisticas['tardanzas'] }}</h3>
                                    <p class="mb-0">Tardanzas</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="card bg-danger text-white mb-3">
                                <div class="card-body py-3">
                                    <h3 class="mb-0">{{ $estadisticas['ausentes'] }}</h3>
                                    <p class="mb-0">Ausentes</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <canvas id="asistenciaChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Lista de Estudiantes</h5>
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
                                        @if($detalle->estado == 'presente')
                                            <span class="badge bg-success">Presente</span>
                                        @elseif($detalle->estado == 'tardanza')
                                            <span class="badge bg-warning">Tardanza</span>
                                        @else
                                            <span class="badge bg-danger">Ausente</span>
                                        @endif
                                    </td>
                                    <td>{{ $detalle->observacion ?: '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Zona de Peligro</h5>
                </div>
                <div class="card-body">
                    <p class="mb-3">Las siguientes acciones son irreversibles. Tenga precaución.</p>
                    
                    <form action="{{ route('asistencias.destroy', $asistencia->id) }}" method="POST" id="delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                            <i class="fas fa-trash-alt"></i> Eliminar Registro de Asistencia
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación de eliminación -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de que desea eliminar este registro de asistencia?</p>
                <p><strong>Fecha:</strong> {{ date('d/m/Y', strtotime($asistencia->fecha)) }}</p>
                <p><strong>Grupo:</strong> {{ $asistencia->asignacion->grupo->nombre_grupo }}</p>
                <p><strong>Materia:</strong> {{ $asistencia->asignacion->grupo->materia->nombre }}</p>
                <p class="mb-0 text-danger"><strong>Esta acción no se puede deshacer.</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Eliminar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        // Gráfico de asistencia
        const ctx = document.getElementById('asistenciaChart').getContext('2d');
        const asistenciaChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Presentes', 'Tardanzas', 'Ausentes'],
                datasets: [{
                    data: [{{ $estadisticas['presentes'] }}, {{ $estadisticas['tardanzas'] }}, {{ $estadisticas['ausentes'] }}],
                    backgroundColor: [
                        'rgba(40, 167, 69, 0.8)',
                        'rgba(255, 193, 7, 0.8)',
                        'rgba(220, 53, 69, 0.8)'
                    ],
                    borderColor: [
                        'rgba(40, 167, 69, 1)',
                        'rgba(255, 193, 7, 1)',
                        'rgba(220, 53, 69, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
        
        // Confirmación de eliminación
        $('#confirmDelete').click(function() {
            $('#delete-form').submit();
        });
    });
</script>
@endsection