@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h1 style="margin: 0; color: #333;">Editar Grupo: {{ $grupo->nombre_grupo }}</h1>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('grupos.show', $grupo) }}" style="background: #17a2b8; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 500;">
            Ver Detalle
        </a>
        <a href="{{ route('grupos.index') }}" style="background: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 500;">
            ← Volver
        </a>
    </div>
</div>

@if($errors->any())
    <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
        <h4 style="margin: 0 0 10px 0;">Por favor corrige los siguientes errores:</h4>
        <ul style="margin: 0; padding-left: 20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Información actual del grupo -->
<div style="background: #e9ecef; border-radius: 8px; padding: 20px; margin-bottom: 20px;">
    <h3 style="margin: 0 0 15px 0; color: #495057;">Información Actual</h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
        <div>
            <strong>Materia:</strong> {{ $grupo->materia->nombre_materia }}
        </div>
        <div>
            <strong>Código:</strong> {{ $grupo->materia->codigo_materia }}
        </div>
        <div>
            <strong>Docente:</strong> {{ $grupo->docente->name ?? 'Sin asignar' }}
        </div>
        <div>
            <strong>Estudiantes:</strong> {{ $grupo->estudiantes_inscritos ?? 0 }}
        </div>
        <div>
            <strong>Asignaciones:</strong> 
            <span style="background: #28a745; color: white; padding: 2px 8px; border-radius: 12px; font-size: 14px;">
                {{ $grupo->asignaciones->count() }}
            </span>
        </div>
        <div>
            <strong>Estado:</strong> 
            <span style="background: {{ $grupo->estado == 'activo' ? '#28a745' : '#dc3545' }}; color: white; padding: 2px 8px; border-radius: 12px; font-size: 14px;">
                {{ ucfirst($grupo->estado) }}
            </span>
        </div>
    </div>
</div>

<div style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 30px;">
    <form method="POST" action="{{ route('grupos.update', $grupo) }}">
        @csrf
        @method('PUT')
        
        <div style="margin-bottom: 25px;">
            <label for="nombre_grupo" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
                Nombre del Grupo <span style="color: #dc3545;">*</span>
            </label>
            <input type="text" name="nombre_grupo" id="nombre_grupo" 
                   value="{{ old('nombre_grupo', $grupo->nombre_grupo) }}" required
                   placeholder="Ej: Grupo A, Sección 1, etc."
                   style="width: 100%; padding: 12px; border: 1px solid #ced4da; border-radius: 6px; font-size: 16px;">
            @error('nombre_grupo')
                <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
            @enderror
            <div style="font-size: 14px; color: #666; margin-top: 5px;">
                Nombre identificativo del grupo (máximo 50 caracteres)
            </div>
        </div>

        <div style="margin-bottom: 25px;">
            <label for="materia_id" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
                Materia <span style="color: #dc3545;">*</span>
            </label>
            <select name="materia_id" id="materia_id" required
                    style="width: 100%; padding: 12px; border: 1px solid #ced4da; border-radius: 6px; font-size: 16px; background: white;">
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
                <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
            @enderror
            @if($grupo->asignaciones->count() > 0)
                <div style="background: #fff3cd; color: #856404; padding: 10px; border-radius: 4px; margin-top: 5px; font-size: 14px;">
                    ⚠️ Cambiar la materia afectará las {{ $grupo->asignaciones->count() }} asignación(es) existente(s)
                </div>
            @endif
        </div>

        <div style="margin-bottom: 25px;">
            <label for="docente_id" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
                Docente Asignado
            </label>
            <select name="docente_id" id="docente_id"
                    style="width: 100%; padding: 12px; border: 1px solid #ced4da; border-radius: 6px; font-size: 16px; background: white;">
                <option value="">Sin asignar</option>
                @foreach($docentes as $docente)
                    <option value="{{ $docente->id }}" 
                            {{ old('docente_id', $grupo->docente_id) == $docente->id ? 'selected' : '' }}>
                        {{ $docente->name }} - {{ $docente->email }}
                    </option>
                @endforeach
            </select>
            @error('docente_id')
                <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
            @enderror
            <div style="font-size: 14px; color: #666; margin-top: 5px;">
                El docente puede cambiarse en cualquier momento
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px;">
            <div>
                <label for="estudiantes_inscritos" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
                    Estudiantes Inscritos
                </label>
                <input type="number" name="estudiantes_inscritos" id="estudiantes_inscritos" 
                       value="{{ old('estudiantes_inscritos', $grupo->estudiantes_inscritos ?? 0) }}" 
                       min="0" max="100"
                       style="width: 100%; padding: 12px; border: 1px solid #ced4da; border-radius: 6px; font-size: 16px;">
                @error('estudiantes_inscritos')
                    <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
                <div style="font-size: 14px; color: #666; margin-top: 5px;">
                    Número actual de estudiantes (0-100)
                </div>
            </div>

            <div>
                <label for="estado" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
                    Estado <span style="color: #dc3545;">*</span>
                </label>
                <select name="estado" id="estado" required
                        style="width: 100%; padding: 12px; border: 1px solid #ced4da; border-radius: 6px; font-size: 16px; background: white;">
                    <option value="activo" {{ old('estado', $grupo->estado) == 'activo' ? 'selected' : '' }}>Activo</option>
                    <option value="inactivo" {{ old('estado', $grupo->estado) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                </select>
                @error('estado')
                    <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
                @if($grupo->asignaciones->count() > 0)
                    <div style="font-size: 14px; color: #856404; margin-top: 5px;">
                        ⚠️ Desactivar afectará las asignaciones activas
                    </div>
                @endif
            </div>
        </div>

        <div style="margin-bottom: 25px;">
            <label for="descripcion" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
                Descripción
            </label>
            <textarea name="descripcion" id="descripcion" rows="4" 
                      placeholder="Descripción opcional del grupo, horarios especiales, observaciones, etc."
                      style="width: 100%; padding: 12px; border: 1px solid #ced4da; border-radius: 6px; font-size: 16px; resize: vertical;">{{ old('descripcion', $grupo->descripcion) }}</textarea>
            @error('descripcion')
                <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
            @enderror
            <div id="descripcion-counter" style="font-size: 14px; color: #666; margin-top: 5px; text-align: right;">
                0 / 500 caracteres
            </div>
        </div>

        <!-- Información de la materia seleccionada -->
        <div id="materia-info" style="background: #e9ecef; padding: 15px; border-radius: 6px; margin-bottom: 25px;">
            <h4 style="margin: 0 0 10px 0; color: #495057;">Información de la Materia:</h4>
            <div id="materia-details" style="color: #666;"></div>
        </div>

        <!-- Advertencia sobre capacidad -->
        <div id="capacidad-warning" style="background: #fff3cd; color: #856404; padding: 15px; border-radius: 6px; margin-bottom: 25px; display: none; border: 1px solid #ffeaa7;">
            <h4 style="margin: 0 0 5px 0;">⚠️ Advertencia de Capacidad</h4>
            <p id="capacidad-text" style="margin: 0;"></p>
        </div>

        <div style="display: flex; gap: 15px; justify-content: flex-end;">
            <a href="{{ route('grupos.show', $grupo) }}" 
               style="background: #6c757d; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: 500;">
                Cancelar
            </a>
            <button type="submit" 
                    style="background: #ffc107; color: #212529; padding: 12px 24px; border: none; border-radius: 6px; cursor: pointer; font-weight: 500; font-size: 16px;">
                Actualizar Grupo
            </button>
        </div>
    </form>
</div>

<!-- Asignaciones afectadas -->
@if($grupo->asignaciones->count() > 0)
<div style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 20px; margin-top: 20px;">
    <h3 style="margin: 0 0 15px 0; color: #333;">Asignaciones que serán afectadas</h3>
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: #f8f9fa;">
                <tr>
                    <th style="padding: 10px; text-align: left; border-bottom: 1px solid #dee2e6;">Horario</th>
                    <th style="padding: 10px; text-align: left; border-bottom: 1px solid #dee2e6;">Aula</th>
                    <th style="padding: 10px; text-align: center; border-bottom: 1px solid #dee2e6;">Día</th>
                    <th style="padding: 10px; text-align: center; border-bottom: 1px solid #dee2e6;">Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($grupo->asignaciones as $asignacion)
                <tr style="border-bottom: 1px solid #f1f3f4;">
                    <td style="padding: 10px;">
                        {{ date('H:i', strtotime($asignacion->horario->hora_inicio)) }} - 
                        {{ date('H:i', strtotime($asignacion->horario->hora_fin)) }}
                    </td>
                    <td style="padding: 10px;">{{ $asignacion->aula->nombre_aula }}</td>
                    <td style="padding: 10px; text-align: center;">
                        @php
                            $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
                        @endphp
                        {{ $dias[$asignacion->horario->dia_semana] }}
                    </td>
                    <td style="padding: 10px; text-align: center;">
                        <span style="background: #28a745; color: white; padding: 2px 8px; border-radius: 12px; font-size: 12px;">
                            Activa
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<div style="margin-top: 20px; background: #f8f9fa; padding: 15px; border-radius: 6px; border-left: 4px solid #ffc107;">
    <h4 style="margin: 0 0 10px 0; color: #856404;">Consideraciones</h4>
    <ul style="margin: 0; padding-left: 20px; color: #666; font-size: 14px;">
        <li>Los cambios en la materia pueden afectar las asignaciones existentes</li>
        <li>Cambiar el estado a "inactivo" ocultará el grupo de nuevas asignaciones</li>
        <li>El número de estudiantes debe considerar la capacidad de las aulas asignadas</li>
        <li>Los cambios de docente se reflejarán inmediatamente en todas las asignaciones</li>
    </ul>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const materiaSelect = document.getElementById('materia_id');
    const materiaInfo = document.getElementById('materia-info');
    const materiaDetails = document.getElementById('materia-details');
    const estudiantesInput = document.getElementById('estudiantes_inscritos');
    const capacidadWarning = document.getElementById('capacidad-warning');
    const capacidadText = document.getElementById('capacidad-text');
    const descripcionTextarea = document.getElementById('descripcion');
    const descripcionCounter = document.getElementById('descripcion-counter');

    // Mostrar información de la materia seleccionada
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

    // Verificar capacidad de estudiantes
    function verificarCapacidad() {
        const numEstudiantes = parseInt(estudiantesInput.value) || 0;
        
        if (numEstudiantes > 50) {
            capacidadText.textContent = `${numEstudiantes} estudiantes es un grupo muy grande. Considere dividirlo en grupos más pequeños para mejor atención.`;
            capacidadWarning.style.display = 'block';
        } else if (numEstudiantes > 0 && numEstudiantes < 5) {
            capacidadText.textContent = `${numEstudiantes} estudiantes es un grupo muy pequeño. Verifique si es viable mantener este grupo.`;
            capacidadWarning.style.display = 'block';
        } else {
            capacidadWarning.style.display = 'none';
        }
    }

    // Contador de caracteres para descripción
    function actualizarContador() {
        const longitud = descripcionTextarea.value.length;
        descripcionCounter.textContent = `${longitud} / 500 caracteres`;
        
        if (longitud > 450) {
            descripcionCounter.style.color = '#dc3545';
        } else if (longitud > 400) {
            descripcionCounter.style.color = '#ffc107';
        } else {
            descripcionCounter.style.color = '#666';
        }
    }

    // Event listeners
    materiaSelect.addEventListener('change', mostrarInfoMateria);
    estudiantesInput.addEventListener('input', verificarCapacidad);
    descripcionTextarea.addEventListener('input', actualizarContador);

    // Inicializar
    mostrarInfoMateria();
    verificarCapacidad();
    actualizarContador();
});
</script>
@endsection