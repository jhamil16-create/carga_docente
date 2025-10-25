@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h1 style="margin: 0; color: #333;">Crear Nuevo Grupo</h1>
    <a href="{{ route('grupos.index') }}" style="background: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 500;">
        ← Volver
    </a>
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

<div style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 30px;">
    <form method="POST" action="{{ route('grupos.store') }}">
        @csrf
        
        <div style="margin-bottom: 25px;">
            <label for="nombre_grupo" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
                Nombre del Grupo <span style="color: #dc3545;">*</span>
            </label>
            <input type="text" name="nombre_grupo" id="nombre_grupo" value="{{ old('nombre_grupo') }}" required
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
                    <option value="{{ $materia->materia_id }}" {{ old('materia_id') == $materia->materia_id ? 'selected' : '' }}
                            data-creditos="{{ $materia->creditos }}">
                        {{ $materia->codigo_materia }} - {{ $materia->nombre_materia }} ({{ $materia->creditos }} créditos)
                    </option>
                @endforeach
            </select>
            @error('materia_id')
                <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
            @enderror
        </div>

        <div style="margin-bottom: 25px;">
            <label for="docente_id" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
                Docente Asignado
            </label>
            <select name="docente_id" id="docente_id"
                    style="width: 100%; padding: 12px; border: 1px solid #ced4da; border-radius: 6px; font-size: 16px; background: white;">
                <option value="">Sin asignar (se puede asignar después)</option>
                @foreach($docentes as $docente)
                    <option value="{{ $docente->id }}" {{ old('docente_id') == $docente->id ? 'selected' : '' }}>
                        {{ $docente->name }} - {{ $docente->email }}
                    </option>
                @endforeach
            </select>
            @error('docente_id')
                <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
            @enderror
            <div style="font-size: 14px; color: #666; margin-top: 5px;">
                El docente puede asignarse ahora o posteriormente
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px;">
            <div>
                <label for="estudiantes_inscritos" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
                    Estudiantes Inscritos
                </label>
                <input type="number" name="estudiantes_inscritos" id="estudiantes_inscritos" 
                       value="{{ old('estudiantes_inscritos', 0) }}" min="0" max="100"
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
                    <option value="activo" {{ old('estado', 'activo') == 'activo' ? 'selected' : '' }}>Activo</option>
                    <option value="inactivo" {{ old('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                </select>
                @error('estado')
                    <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div style="margin-bottom: 25px;">
            <label for="descripcion" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
                Descripción
            </label>
            <textarea name="descripcion" id="descripcion" rows="4" 
                      placeholder="Descripción opcional del grupo, horarios especiales, observaciones, etc."
                      style="width: 100%; padding: 12px; border: 1px solid #ced4da; border-radius: 6px; font-size: 16px; resize: vertical;">{{ old('descripcion') }}</textarea>
            @error('descripcion')
                <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
            @enderror
            <div id="descripcion-counter" style="font-size: 14px; color: #666; margin-top: 5px; text-align: right;">
                0 / 500 caracteres
            </div>
        </div>

        <!-- Información de la materia seleccionada -->
        <div id="materia-info" style="background: #e9ecef; padding: 15px; border-radius: 6px; margin-bottom: 25px; display: none;">
            <h4 style="margin: 0 0 10px 0; color: #495057;">Información de la Materia:</h4>
            <div id="materia-details" style="color: #666;"></div>
        </div>

        <!-- Advertencia sobre capacidad -->
        <div id="capacidad-warning" style="background: #fff3cd; color: #856404; padding: 15px; border-radius: 6px; margin-bottom: 25px; display: none; border: 1px solid #ffeaa7;">
            <h4 style="margin: 0 0 5px 0;">⚠️ Advertencia de Capacidad</h4>
            <p id="capacidad-text" style="margin: 0;"></p>
        </div>

        <div style="display: flex; gap: 15px; justify-content: flex-end;">
            <a href="{{ route('grupos.index') }}" 
               style="background: #6c757d; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: 500;">
                Cancelar
            </a>
            <button type="submit" 
                    style="background: #28a745; color: white; padding: 12px 24px; border: none; border-radius: 6px; cursor: pointer; font-weight: 500; font-size: 16px;">
                Crear Grupo
            </button>
        </div>
    </form>
</div>

<div style="margin-top: 20px; background: #f8f9fa; padding: 15px; border-radius: 6px; border-left: 4px solid #17a2b8;">
    <h4 style="margin: 0 0 10px 0; color: #17a2b8;">Consejos</h4>
    <ul style="margin: 0; padding-left: 20px; color: #666; font-size: 14px;">
        <li>El nombre del grupo debe ser único para cada materia</li>
        <li>Puede crear el grupo sin docente y asignarlo posteriormente</li>
        <li>El número de estudiantes puede actualizarse en cualquier momento</li>
        <li>Los grupos inactivos no aparecerán en las asignaciones de horarios</li>
        <li>Considere la capacidad de las aulas al definir el número de estudiantes</li>
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

    // Validación del formulario
    document.querySelector('form').addEventListener('submit', function(e) {
        const nombreGrupo = document.getElementById('nombre_grupo').value.trim();
        const materiaId = document.getElementById('materia_id').value;
        
        if (!nombreGrupo) {
            alert('Por favor ingrese el nombre del grupo');
            e.preventDefault();
            return;
        }
        
        if (!materiaId) {
            alert('Por favor seleccione una materia');
            e.preventDefault();
            return;
        }
    });
});
</script>
@endsection