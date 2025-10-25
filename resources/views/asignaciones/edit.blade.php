@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h1 style="margin: 0; color: #333;">✏️ Editar Asignación</h1>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('asignaciones.show', $asignacion->asignacion_id) }}" style="background: #17a2b8; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 500;">
            👁️ Ver
        </a>
        <a href="{{ route('asignaciones.index') }}" style="background: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 500;">
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

<!-- Información actual de la asignación -->
<div style="background: #e3f2fd; padding: 20px; border-radius: 8px; margin-bottom: 25px; border-left: 4px solid #2196f3;">
    <h3 style="margin: 0 0 15px 0; color: #1976d2;">📋 Asignación Actual</h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px;">
        <div>
            <strong>Grupo:</strong> {{ $asignacion->grupo->nombre_grupo }}<br>
            <small style="color: #666;">{{ $asignacion->grupo->materia->codigo_materia }} - {{ $asignacion->grupo->materia->nombre_materia }}</small>
        </div>
        <div>
            <strong>Horario:</strong> 
            @php
                $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
            @endphp
            {{ $dias[$asignacion->horario->dia_semana] }}<br>
            <small style="color: #666;">{{ date('H:i', strtotime($asignacion->horario->hora_inicio)) }} - {{ date('H:i', strtotime($asignacion->horario->hora_fin)) }}</small>
        </div>
        <div>
            <strong>Aula:</strong> {{ $asignacion->aula->nombre_aula }}<br>
            <small style="color: #666;">{{ $asignacion->aula->ubicacion }} (Cap: {{ $asignacion->aula->capacidad }})</small>
        </div>
        <div>
            <strong>Creada:</strong> {{ $asignacion->created_at->format('d/m/Y H:i') }}<br>
            @if($asignacion->updated_at != $asignacion->created_at)
                <small style="color: #666;">Modificada: {{ $asignacion->updated_at->format('d/m/Y H:i') }}</small>
            @endif
        </div>
    </div>
</div>

<div style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 30px;">
    <form method="POST" action="{{ route('asignaciones.update', $asignacion->asignacion_id) }}" id="asignacion-form">
        @csrf
        @method('PUT')
        
        <!-- Selección de Grupo -->
        <div style="margin-bottom: 25px;">
            <label for="grupo_id" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
                Grupo <span style="color: #dc3545;">*</span>
            </label>
            <select name="grupo_id" id="grupo_id" required
                    style="width: 100%; padding: 12px; border: 1px solid #ced4da; border-radius: 6px; font-size: 16px; background: white;">
                <option value="">Seleccione un grupo</option>
                @foreach($grupos as $grupo)
                    <option value="{{ $grupo->grupo_id }}" 
                            {{ (old('grupo_id', $asignacion->grupo_id) == $grupo->grupo_id) ? 'selected' : '' }}
                            data-materia="{{ $grupo->materia->nombre_materia }}"
                            data-codigo="{{ $grupo->materia->codigo_materia }}"
                            data-creditos="{{ $grupo->materia->creditos }}"
                            data-docente="{{ $grupo->docente->name ?? 'Sin asignar' }}"
                            data-estudiantes="{{ $grupo->estudiantes_inscritos ?? 0 }}"
                            data-estado="{{ $grupo->estado }}">
                        {{ $grupo->nombre_grupo }} - {{ $grupo->materia->codigo_materia }} ({{ $grupo->materia->nombre_materia }})
                        @if($grupo->docente) - {{ $grupo->docente->name }} @endif
                    </option>
                @endforeach
            </select>
            @error('grupo_id')
                <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
            @enderror
        </div>

        <!-- Información del grupo seleccionado -->
        <div id="grupo-info" style="background: #e3f2fd; padding: 20px; border-radius: 8px; margin-bottom: 25px; display: none; border-left: 4px solid #2196f3;">
            <h3 style="margin: 0 0 15px 0; color: #1976d2;">📚 Información del Grupo Seleccionado</h3>
            <div id="grupo-details" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;"></div>
        </div>

        <!-- Selección de Horario -->
        <div style="margin-bottom: 25px;">
            <label for="horario_id" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
                Horario <span style="color: #dc3545;">*</span>
            </label>
            <select name="horario_id" id="horario_id" required
                    style="width: 100%; padding: 12px; border: 1px solid #ced4da; border-radius: 6px; font-size: 16px; background: white;">
                <option value="">Seleccione un horario</option>
                @foreach($horarios as $horario)
                    @php
                        $inicio = new DateTime($horario->hora_inicio);
                        $fin = new DateTime($horario->hora_fin);
                        $duracion = $inicio->diff($fin);
                    @endphp
                    <option value="{{ $horario->horario_id }}" 
                            {{ (old('horario_id', $asignacion->horario_id) == $horario->horario_id) ? 'selected' : '' }}
                            data-dia="{{ $horario->dia_semana }}"
                            data-dia-nombre="{{ $dias[$horario->dia_semana] }}"
                            data-inicio="{{ $horario->hora_inicio }}"
                            data-fin="{{ $horario->hora_fin }}"
                            data-duracion="{{ $duracion->h }}h {{ $duracion->i }}m">
                        {{ $dias[$horario->dia_semana] }} - 
                        {{ date('H:i', strtotime($horario->hora_inicio)) }} a 
                        {{ date('H:i', strtotime($horario->hora_fin)) }}
                        ({{ $duracion->h }}h {{ $duracion->i }}m)
                    </option>
                @endforeach
            </select>
            @error('horario_id')
                <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
            @enderror
        </div>

        <!-- Información del horario seleccionado -->
        <div id="horario-info" style="background: #f3e5f5; padding: 20px; border-radius: 8px; margin-bottom: 25px; display: none; border-left: 4px solid #9c27b0;">
            <h3 style="margin: 0 0 15px 0; color: #7b1fa2;">🕐 Información del Horario Seleccionado</h3>
            <div id="horario-details" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;"></div>
        </div>

        <!-- Selección de Aula -->
        <div style="margin-bottom: 25px;">
            <label for="aula_id" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
                Aula <span style="color: #dc3545;">*</span>
            </label>
            <select name="aula_id" id="aula_id" required
                    style="width: 100%; padding: 12px; border: 1px solid #ced4da; border-radius: 6px; font-size: 16px; background: white;">
                <option value="">Seleccione un aula</option>
                @foreach($aulas as $aula)
                    <option value="{{ $aula->aula_id }}" 
                            {{ (old('aula_id', $asignacion->aula_id) == $aula->aula_id) ? 'selected' : '' }}
                            data-nombre="{{ $aula->nombre_aula }}"
                            data-capacidad="{{ $aula->capacidad }}"
                            data-ubicacion="{{ $aula->ubicacion }}">
                        {{ $aula->nombre_aula }} - {{ $aula->ubicacion }} (Capacidad: {{ $aula->capacidad }})
                    </option>
                @endforeach
            </select>
            @error('aula_id')
                <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
            @enderror
        </div>

        <!-- Información del aula seleccionada -->
        <div id="aula-info" style="background: #e8f5e8; padding: 20px; border-radius: 8px; margin-bottom: 25px; display: none; border-left: 4px solid #4caf50;">
            <h3 style="margin: 0 0 15px 0; color: #2e7d32;">🏫 Información del Aula Seleccionada</h3>
            <div id="aula-details" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;"></div>
        </div>

        <!-- Verificación de conflictos -->
        <div id="conflictos-warning" style="background: #fff3cd; color: #856404; padding: 20px; border-radius: 8px; margin-bottom: 25px; display: none; border: 1px solid #ffeaa7;">
            <h3 style="margin: 0 0 15px 0;">⚠️ Conflictos Detectados</h3>
            <div id="conflictos-list"></div>
        </div>

        <!-- Verificación de capacidad -->
        <div id="capacidad-warning" style="background: #f8d7da; color: #721c24; padding: 20px; border-radius: 8px; margin-bottom: 25px; display: none; border: 1px solid #f5c6cb;">
            <h3 style="margin: 0 0 10px 0;">🚨 Advertencia de Capacidad</h3>
            <div id="capacidad-text"></div>
        </div>

        <!-- Cambios detectados -->
        <div id="cambios-detectados" style="background: #fff3cd; padding: 20px; border-radius: 8px; margin-bottom: 25px; display: none; border-left: 4px solid #ffc107;">
            <h3 style="margin: 0 0 15px 0; color: #856404;">🔄 Cambios Detectados</h3>
            <div id="cambios-list"></div>
        </div>

        <!-- Resumen de la asignación -->
        <div id="resumen-asignacion" style="background: #d4edda; padding: 20px; border-radius: 8px; margin-bottom: 25px; display: none; border-left: 4px solid #28a745;">
            <h3 style="margin: 0 0 15px 0; color: #155724;">✅ Resumen de la Asignación</h3>
            <div id="resumen-details" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px;"></div>
        </div>

        <div style="display: flex; gap: 15px; justify-content: flex-end;">
            <a href="{{ route('asignaciones.show', $asignacion->asignacion_id) }}" 
               style="background: #6c757d; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: 500;">
                Cancelar
            </a>
            <button type="submit" id="submit-btn"
                    style="background: #28a745; color: white; padding: 12px 24px; border: none; border-radius: 6px; cursor: pointer; font-weight: 500; font-size: 16px;">
                Actualizar Asignación
            </button>
        </div>
    </form>
</div>

<!-- Advertencia sobre cambios -->
<div style="margin-top: 20px; background: #fff3cd; padding: 20px; border-radius: 6px; border-left: 4px solid #ffc107;">
    <h4 style="margin: 0 0 15px 0; color: #856404;">⚠️ Importante: Cambios en Asignaciones</h4>
    <div style="color: #856404; font-size: 14px;">
        <p style="margin: 0 0 10px 0;">
            <strong>Tenga en cuenta que modificar esta asignación puede afectar:</strong>
        </p>
        <ul style="margin: 0; padding-left: 20px;">
            <li>Los registros de asistencia existentes para esta clase</li>
            <li>La programación de otras asignaciones si hay conflictos</li>
            <li>Las notificaciones enviadas a estudiantes y docentes</li>
            <li>Los reportes y estadísticas generados</li>
        </ul>
        <p style="margin: 10px 0 0 0;">
            <em>Se recomienda verificar cuidadosamente los cambios antes de confirmar.</em>
        </p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const grupoSelect = document.getElementById('grupo_id');
    const horarioSelect = document.getElementById('horario_id');
    const aulaSelect = document.getElementById('aula_id');
    const submitBtn = document.getElementById('submit-btn');
    
    const grupoInfo = document.getElementById('grupo-info');
    const grupoDetails = document.getElementById('grupo-details');
    const horarioInfo = document.getElementById('horario-info');
    const horarioDetails = document.getElementById('horario-details');
    const aulaInfo = document.getElementById('aula-info');
    const aulaDetails = document.getElementById('aula-details');
    
    const conflictosWarning = document.getElementById('conflictos-warning');
    const conflictosList = document.getElementById('conflictos-list');
    const capacidadWarning = document.getElementById('capacidad-warning');
    const capacidadText = document.getElementById('capacidad-text');
    const cambiosDetectados = document.getElementById('cambios-detectados');
    const cambiosList = document.getElementById('cambios-list');
    const resumenAsignacion = document.getElementById('resumen-asignacion');
    const resumenDetails = document.getElementById('resumen-details');

    // Valores originales para detectar cambios
    const valoresOriginales = {
        grupo_id: '{{ $asignacion->grupo_id }}',
        horario_id: '{{ $asignacion->horario_id }}',
        aula_id: '{{ $asignacion->aula_id }}'
    };

    // Mostrar información del grupo seleccionado
    function mostrarInfoGrupo() {
        const selectedOption = grupoSelect.options[grupoSelect.selectedIndex];
        if (selectedOption.value) {
            const materia = selectedOption.dataset.materia;
            const codigo = selectedOption.dataset.codigo;
            const creditos = selectedOption.dataset.creditos;
            const docente = selectedOption.dataset.docente;
            const estudiantes = selectedOption.dataset.estudiantes;
            const estado = selectedOption.dataset.estado;
            
            grupoDetails.innerHTML = `
                <div><strong>Materia:</strong> ${codigo} - ${materia}</div>
                <div><strong>Créditos:</strong> ${creditos}</div>
                <div><strong>Docente:</strong> ${docente}</div>
                <div><strong>Estudiantes:</strong> ${estudiantes}</div>
                <div><strong>Estado:</strong> <span style="background: ${estado === 'activo' ? '#28a745' : '#dc3545'}; color: white; padding: 2px 8px; border-radius: 12px; font-size: 12px;">${estado.charAt(0).toUpperCase() + estado.slice(1)}</span></div>
            `;
            grupoInfo.style.display = 'block';
        } else {
            grupoInfo.style.display = 'none';
        }
        detectarCambios();
        verificarFormulario();
    }

    // Mostrar información del horario seleccionado
    function mostrarInfoHorario() {
        const selectedOption = horarioSelect.options[horarioSelect.selectedIndex];
        if (selectedOption.value) {
            const diaNombre = selectedOption.dataset.diaNombre;
            const inicio = selectedOption.dataset.inicio;
            const fin = selectedOption.dataset.fin;
            const duracion = selectedOption.dataset.duracion;
            
            horarioDetails.innerHTML = `
                <div><strong>Día:</strong> ${diaNombre}</div>
                <div><strong>Hora de Inicio:</strong> ${inicio.substring(0, 5)}</div>
                <div><strong>Hora de Fin:</strong> ${fin.substring(0, 5)}</div>
                <div><strong>Duración:</strong> ${duracion}</div>
            `;
            horarioInfo.style.display = 'block';
        } else {
            horarioInfo.style.display = 'none';
        }
        detectarCambios();
        verificarConflictos();
        verificarFormulario();
    }

    // Mostrar información del aula seleccionada
    function mostrarInfoAula() {
        const selectedOption = aulaSelect.options[aulaSelect.selectedIndex];
        if (selectedOption.value) {
            const nombre = selectedOption.dataset.nombre;
            const capacidad = selectedOption.dataset.capacidad;
            const ubicacion = selectedOption.dataset.ubicacion;
            
            aulaDetails.innerHTML = `
                <div><strong>Nombre:</strong> ${nombre}</div>
                <div><strong>Capacidad:</strong> ${capacidad} estudiantes</div>
                <div><strong>Ubicación:</strong> ${ubicacion}</div>
            `;
            aulaInfo.style.display = 'block';
        } else {
            aulaInfo.style.display = 'none';
        }
        detectarCambios();
        verificarCapacidad();
        verificarConflictos();
        verificarFormulario();
    }

    // Detectar cambios respecto a los valores originales
    function detectarCambios() {
        const cambios = [];
        
        if (grupoSelect.value !== valoresOriginales.grupo_id) {
            const grupoOption = grupoSelect.options[grupoSelect.selectedIndex];
            cambios.push(`Grupo: ${grupoOption.textContent}`);
        }
        
        if (horarioSelect.value !== valoresOriginales.horario_id) {
            const horarioOption = horarioSelect.options[horarioSelect.selectedIndex];
            cambios.push(`Horario: ${horarioOption.textContent}`);
        }
        
        if (aulaSelect.value !== valoresOriginales.aula_id) {
            const aulaOption = aulaSelect.options[aulaSelect.selectedIndex];
            cambios.push(`Aula: ${aulaOption.textContent}`);
        }
        
        if (cambios.length > 0) {
            let cambiosHtml = '<p style="margin: 0 0 10px 0;"><strong>Los siguientes campos serán modificados:</strong></p><ul style="margin: 0; padding-left: 20px;">';
            cambios.forEach(cambio => {
                cambiosHtml += `<li>${cambio}</li>`;
            });
            cambiosHtml += '</ul>';
            
            cambiosList.innerHTML = cambiosHtml;
            cambiosDetectados.style.display = 'block';
        } else {
            cambiosDetectados.style.display = 'none';
        }
    }

    // Verificar conflictos de horario y aula
    function verificarConflictos() {
        if (!horarioSelect.value || !aulaSelect.value) {
            conflictosWarning.style.display = 'none';
            return;
        }

        // Simulación de conflictos (en producción esto vendría del servidor)
        const conflictos = [];
        const probabilidadConflicto = Math.random();
        
        if (probabilidadConflicto < 0.2) { // 20% de probabilidad para demo
            conflictos.push({
                tipo: 'aula_ocupada',
                mensaje: 'El aula ya está ocupada en este horario por otro grupo'
            });
        }
        
        if (conflictos.length > 0) {
            let conflictosHtml = '<ul style="margin: 0; padding-left: 20px;">';
            conflictos.forEach(conflicto => {
                conflictosHtml += `<li>${conflicto.mensaje}</li>`;
            });
            conflictosHtml += '</ul>';
            
            conflictosList.innerHTML = conflictosHtml;
            conflictosWarning.style.display = 'block';
        } else {
            conflictosWarning.style.display = 'none';
        }
    }

    // Verificar capacidad del aula vs estudiantes
    function verificarCapacidad() {
        if (!grupoSelect.value || !aulaSelect.value) {
            capacidadWarning.style.display = 'none';
            return;
        }

        const grupoOption = grupoSelect.options[grupoSelect.selectedIndex];
        const aulaOption = aulaSelect.options[aulaSelect.selectedIndex];
        
        const estudiantes = parseInt(grupoOption.dataset.estudiantes) || 0;
        const capacidad = parseInt(aulaOption.dataset.capacidad) || 0;
        
        if (estudiantes > capacidad) {
            capacidadText.innerHTML = `
                El grupo tiene <strong>${estudiantes} estudiantes</strong> pero el aula solo tiene capacidad para <strong>${capacidad}</strong>.
                <br>Esto excede la capacidad en <strong>${estudiantes - capacidad} estudiante(s)</strong>.
                <br><em>Se recomienda seleccionar un aula con mayor capacidad.</em>
            `;
            capacidadWarning.style.display = 'block';
        } else if (estudiantes > 0 && (estudiantes / capacidad) > 0.9) {
            capacidadText.innerHTML = `
                El aula estará al <strong>${Math.round((estudiantes / capacidad) * 100)}%</strong> de su capacidad.
                <br>Considere si hay suficiente espacio para comodidad de los estudiantes.
            `;
            capacidadWarning.style.display = 'block';
            capacidadWarning.style.background = '#fff3cd';
            capacidadWarning.style.color = '#856404';
            capacidadWarning.style.borderColor = '#ffeaa7';
        } else {
            capacidadWarning.style.display = 'none';
        }
    }

    // Mostrar resumen de la asignación
    function mostrarResumen() {
        if (!grupoSelect.value || !horarioSelect.value || !aulaSelect.value) {
            resumenAsignacion.style.display = 'none';
            return;
        }

        const grupoOption = grupoSelect.options[grupoSelect.selectedIndex];
        const horarioOption = horarioSelect.options[horarioSelect.selectedIndex];
        const aulaOption = aulaSelect.options[aulaSelect.selectedIndex];
        
        resumenDetails.innerHTML = `
            <div>
                <strong>Grupo:</strong> ${grupoOption.textContent}
            </div>
            <div>
                <strong>Horario:</strong> ${horarioOption.textContent}
            </div>
            <div>
                <strong>Aula:</strong> ${aulaOption.textContent}
            </div>
            <div>
                <strong>Ocupación:</strong> ${grupoOption.dataset.estudiantes}/${aulaOption.dataset.capacidad} estudiantes
            </div>
        `;
        resumenAsignacion.style.display = 'block';
    }

    // Verificar si el formulario está completo y válido
    function verificarFormulario() {
        const grupoValido = grupoSelect.value !== '';
        const horarioValido = horarioSelect.value !== '';
        const aulaValida = aulaSelect.value !== '';
        const sinConflictos = conflictosWarning.style.display === 'none';
        
        if (grupoValido && horarioValido && aulaValida) {
            mostrarResumen();
            
            if (sinConflictos) {
                submitBtn.disabled = false;
                submitBtn.style.cursor = 'pointer';
                submitBtn.style.opacity = '1';
                submitBtn.style.background = '#28a745';
                submitBtn.textContent = 'Actualizar Asignación';
            } else {
                submitBtn.disabled = true;
                submitBtn.style.cursor = 'not-allowed';
                submitBtn.style.opacity = '0.6';
                submitBtn.style.background = '#dc3545';
                submitBtn.textContent = 'Resolver Conflictos Primero';
            }
        } else {
            submitBtn.disabled = true;
            submitBtn.style.cursor = 'not-allowed';
            submitBtn.style.opacity = '0.6';
            submitBtn.style.background = '#6c757d';
            submitBtn.textContent = 'Actualizar Asignación';
            resumenAsignacion.style.display = 'none';
        }
    }

    // Event listeners
    grupoSelect.addEventListener('change', mostrarInfoGrupo);
    horarioSelect.addEventListener('change', mostrarInfoHorario);
    aulaSelect.addEventListener('change', mostrarInfoAula);

    // Inicializar con valores actuales
    mostrarInfoGrupo();
    mostrarInfoHorario();
    mostrarInfoAula();
});
</script>
@endsection