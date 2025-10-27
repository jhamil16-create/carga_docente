@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-edit"></i> Editar Grupo: {{ $grupo->nombre_grupo }}</h2>
        <div class="btn-group">
            <a href="{{ route('grupos.show', $grupo->grupo_id) }}" class="btn btn-info">
                <i class="fas fa-eye"></i> Ver Detalle
            </a>
            <a href="{{ route('grupos.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <h5>Por favor corrige los siguientes errores:</h5>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Información actual -->
    <div class="card mb-3">
        <div class="card-header bg-light">
            <h5 class="mb-0">Información Actual</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <strong>Materia:</strong> {{ $grupo->materia->nombre_materia }}
                </div>
                <div class="col-md-4">
                    <strong>Código:</strong> {{ $grupo->materia->codigo_materia }}
                </div>
                <div class="col-md-4">
                    <strong>Asignaciones:</strong> 
                    <span class="badge bg-success">{{ $grupo->asignaciones->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulario de edición -->
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('grupos.update', $grupo->grupo_id) }}">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="materia_id" class="form-label">
                        Materia <span class="text-danger">*</span>
                    </label>
                    <select name="materia_id" id="materia_id" class="form-select @error('materia_id') is-invalid @enderror" required>
                        <option value="">Seleccione una materia</option>
                        @foreach($materias as $materia)
                            <option value="{{ $materia->materia_id }}" 
                                    {{ old('materia_id', $grupo->materia_id) == $materia->materia_id ? 'selected' : '' }}
                                    data-creditos="{{ $materia->creditos }}">
                                {{ $materia->codigo_materia }} - {{ $materia->nombre_materia }} ({{ $materia->creditos }} créditos)
                            </option>
                        @endforeach
                    </select>
                    @error('materia_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @if($grupo->asignaciones->count() > 0)
                        <small class="text-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            Cambiar la materia afectará las {{ $grupo->asignaciones->count() }} asignación(es) existente(s)
                        </small>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="nombre_grupo" class="form-label">
                        Nombre del Grupo <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           name="nombre_grupo" 
                           id="nombre_grupo" 
                           class="form-control @error('nombre_grupo') is-invalid @enderror"
                           value="{{ old('nombre_grupo', $grupo->nombre_grupo) }}"
                           placeholder="Ej: SI100-1, Grupo A, etc."
                           maxlength="50"
                           required>
                    @error('nombre_grupo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Nombre identificativo del grupo (máximo 50 caracteres)</small>
                </div>

                <div class="mb-3">
                    <label for="capacidad_maxima" class="form-label">
                        Capacidad Máxima <span class="text-danger">*</span>
                    </label>
                    <input type="number" 
                           name="capacidad_maxima" 
                           id="capacidad_maxima" 
                           class="form-control @error('capacidad_maxima') is-invalid @enderror"
                           value="{{ old('capacidad_maxima', $grupo->capacidad_maxima) }}"
                           min="1"
                           max="200"
                           required>
                    @error('capacidad_maxima')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Número máximo de estudiantes (1-200)</small>
                </div>

                <!-- Información de la materia seleccionada -->
                <div id="materia-info" class="alert alert-info">
                    <h6>Información de la Materia:</h6>
                    <div id="materia-details"></div>
                </div>

                <!-- Advertencia de capacidad -->
                <div id="capacidad-warning" class="alert alert-warning" style="display: none;">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span id="capacidad-text"></span>
                </div>

                <div class="d-flex gap-2 justify-content-end">
                    <a href="{{ route('grupos.show', $grupo->grupo_id) }}" class="btn btn-secondary">
                        Cancelar
                    </a>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save"></i> Actualizar Grupo
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Asignaciones afectadas -->
    @if($grupo->asignaciones->count() > 0)
    <div class="card mt-3">
        <div class="card-header bg-warning">
            <h5 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Asignaciones que serán afectadas</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Día</th>
                            <th>Horario</th>
                            <th>Aula</th>
                            <th>Docente</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($grupo->asignaciones as $asignacion)
                        <tr>
                            <td>{{ $asignacion->horario->dia_semana }}</td>
                            <td>
                                {{ date('H:i', strtotime($asignacion->horario->hora_inicio)) }} - 
                                {{ date('H:i', strtotime($asignacion->horario->hora_fin)) }}
                            </td>
                            <td>{{ $asignacion->aula->nombre_aula }}</td>
                            <td>{{ $asignacion->docente->usuario->nombre ?? 'N/A' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <div class="card mt-3">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="fas fa-info-circle"></i> Consideraciones</h5>
        </div>
        <div class="card-body">
            <ul class="mb-0">
                <li>Los cambios en la materia pueden afectar las asignaciones existentes</li>
                <li>La capacidad debe considerar el tamaño de las aulas asignadas</li>
                <li>El nombre del grupo debe ser único para cada materia</li>
            </ul>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const materiaSelect = document.getElementById('materia_id');
    const materiaInfo = document.getElementById('materia-info');
    const materiaDetails = document.getElementById('materia-details');
    const capacidadInput = document.getElementById('capacidad_maxima');
    const capacidadWarning = document.getElementById('capacidad-warning');
    const capacidadText = document.getElementById('capacidad-text');

    function mostrarInfoMateria() {
        const selectedOption = materiaSelect.options[materiaSelect.selectedIndex];
        if (selectedOption.value) {
            const creditos = selectedOption.dataset.creditos;
            const texto = selectedOption.textContent;
            
            materiaDetails.innerHTML = `
                <strong>Materia:</strong> ${texto}<br>
                <strong>Créditos:</strong> ${creditos}<br>
                <strong>Horas semanales estimadas:</strong> ${creditos * 2}h
            `;
            materiaInfo.style.display = 'block';
        } else {
            materiaInfo.style.display = 'none';
        }
    }

    function verificarCapacidad() {
        const valor = parseInt(capacidadInput.value) || 0;
        
        if (valor > 50) {
            capacidadText.textContent = `${valor} estudiantes es un grupo muy grande. Considere dividirlo.`;
            capacidadWarning.style.display = 'block';
        } else if (valor > 0 && valor < 10) {
            capacidadText.textContent = `${valor} estudiantes es un grupo muy pequeño.`;
            capacidadWarning.style.display = 'block';
        } else {
            capacidadWarning.style.display = 'none';
        }
    }

    materiaSelect.addEventListener('change', mostrarInfoMateria);
    capacidadInput.addEventListener('input', verificarCapacidad);

    // Inicializar
    mostrarInfoMateria();
    verificarCapacidad();
});
</script>
@endsection