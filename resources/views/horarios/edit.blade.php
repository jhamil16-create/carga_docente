@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h1 style="margin: 0; color: #333;">Editar Horario #{{ $horario->horario_id }}</h1>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('horarios.show', $horario) }}" style="background: #17a2b8; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 500;">
            Ver Detalle
        </a>
        <a href="{{ route('horarios.index') }}" style="background: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 500;">
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

<!-- Información actual del horario -->
<div style="background: #e9ecef; border-radius: 8px; padding: 20px; margin-bottom: 20px;">
    <h3 style="margin: 0 0 15px 0; color: #495057;">Información Actual</h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
        <div>
            <strong>Día:</strong> 
            @php
                $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
            @endphp
            {{ $dias[$horario->dia_semana] }}
        </div>
        <div>
            <strong>Horario:</strong> 
            {{ date('H:i', strtotime($horario->hora_inicio)) }} - {{ date('H:i', strtotime($horario->hora_fin)) }}
        </div>
        <div>
            <strong>Asignaciones:</strong> 
            <span style="background: #28a745; color: white; padding: 2px 8px; border-radius: 12px; font-size: 14px;">
                {{ $horario->asignaciones->count() }}
            </span>
        </div>
    </div>
</div>

<div style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 30px;">
    <form method="POST" action="{{ route('horarios.update', $horario) }}">
        @csrf
        @method('PUT')
        
        <div style="margin-bottom: 25px;">
            <label for="dia_semana" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
                Día de la Semana <span style="color: #dc3545;">*</span>
            </label>
            <select name="dia_semana" id="dia_semana" required
                    style="width: 100%; padding: 12px; border: 1px solid #ced4da; border-radius: 6px; font-size: 16px; background: white;">
                <option value="">Seleccione un día</option>
                <option value="1" {{ (old('dia_semana', $horario->dia_semana) == '1') ? 'selected' : '' }}>Lunes</option>
                <option value="2" {{ (old('dia_semana', $horario->dia_semana) == '2') ? 'selected' : '' }}>Martes</option>
                <option value="3" {{ (old('dia_semana', $horario->dia_semana) == '3') ? 'selected' : '' }}>Miércoles</option>
                <option value="4" {{ (old('dia_semana', $horario->dia_semana) == '4') ? 'selected' : '' }}>Jueves</option>
                <option value="5" {{ (old('dia_semana', $horario->dia_semana) == '5') ? 'selected' : '' }}>Viernes</option>
                <option value="6" {{ (old('dia_semana', $horario->dia_semana) == '6') ? 'selected' : '' }}>Sábado</option>
                <option value="0" {{ (old('dia_semana', $horario->dia_semana) == '0') ? 'selected' : '' }}>Domingo</option>
            </select>
            @error('dia_semana')
                <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
            @enderror
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px;">
            <div>
                <label for="hora_inicio" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
                    Hora de Inicio <span style="color: #dc3545;">*</span>
                </label>
                <input type="time" name="hora_inicio" id="hora_inicio" 
                       value="{{ old('hora_inicio', date('H:i', strtotime($horario->hora_inicio))) }}" required
                       style="width: 100%; padding: 12px; border: 1px solid #ced4da; border-radius: 6px; font-size: 16px;">
                @error('hora_inicio')
                    <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="hora_fin" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
                    Hora de Fin <span style="color: #dc3545;">*</span>
                </label>
                <input type="time" name="hora_fin" id="hora_fin" 
                       value="{{ old('hora_fin', date('H:i', strtotime($horario->hora_fin))) }}" required
                       style="width: 100%; padding: 12px; border: 1px solid #ced4da; border-radius: 6px; font-size: 16px;">
                @error('hora_fin')
                    <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Información de duración -->
        <div id="duracion-info" style="background: #e9ecef; padding: 15px; border-radius: 6px; margin-bottom: 25px;">
            <h4 style="margin: 0 0 5px 0; color: #495057;">Duración del Horario:</h4>
            <p id="duracion-text" style="margin: 0; font-size: 16px; font-weight: 500; color: #17a2b8;"></p>
        </div>

        <!-- Verificación de conflictos -->
        <div id="conflicto-warning" style="background: #fff3cd; color: #856404; padding: 15px; border-radius: 6px; margin-bottom: 25px; display: none; border: 1px solid #ffeaa7;">
            <h4 style="margin: 0 0 5px 0;">⚠️ Posible Conflicto</h4>
            <p id="conflicto-text" style="margin: 0;"></p>
        </div>

        @if($horario->asignaciones->count() > 0)
        <div style="background: #fff3cd; color: #856404; padding: 15px; border-radius: 6px; margin-bottom: 25px; border: 1px solid #ffeaa7;">
            <h4 style="margin: 0 0 10px 0;">⚠️ Advertencia</h4>
            <p style="margin: 0;">
                Este horario tiene <strong>{{ $horario->asignaciones->count() }} asignación(es)</strong> activa(s). 
                Los cambios pueden afectar las clases programadas.
            </p>
        </div>
        @endif

        <div style="display: flex; gap: 15px; justify-content: flex-end;">
            <a href="{{ route('horarios.show', $horario) }}" 
               style="background: #6c757d; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: 500;">
                Cancelar
            </a>
            <button type="submit" 
                    style="background: #ffc107; color: #212529; padding: 12px 24px; border: none; border-radius: 6px; cursor: pointer; font-weight: 500; font-size: 16px;">
                Actualizar Horario
            </button>
        </div>
    </form>
</div>

<!-- Asignaciones afectadas -->
@if($horario->asignaciones->count() > 0)
<div style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 20px; margin-top: 20px;">
    <h3 style="margin: 0 0 15px 0; color: #333;">Asignaciones que serán afectadas</h3>
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: #f8f9fa;">
                <tr>
                    <th style="padding: 10px; text-align: left; border-bottom: 1px solid #dee2e6;">Materia</th>
                    <th style="padding: 10px; text-align: left; border-bottom: 1px solid #dee2e6;">Grupo</th>
                    <th style="padding: 10px; text-align: left; border-bottom: 1px solid #dee2e6;">Aula</th>
                    <th style="padding: 10px; text-align: left; border-bottom: 1px solid #dee2e6;">Docente</th>
                </tr>
            </thead>
            <tbody>
                @foreach($horario->asignaciones as $asignacion)
                <tr style="border-bottom: 1px solid #f1f3f4;">
                    <td style="padding: 10px;">{{ $asignacion->grupo->materia->nombre_materia }}</td>
                    <td style="padding: 10px;">{{ $asignacion->grupo->nombre_grupo }}</td>
                    <td style="padding: 10px;">{{ $asignacion->aula->nombre_aula }}</td>
                    <td style="padding: 10px;">{{ $asignacion->grupo->docente->name ?? 'Sin asignar' }}</td>
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
        <li>Los cambios en el horario afectarán todas las asignaciones asociadas</li>
        <li>Verifique que no haya conflictos con otros horarios</li>
        <li>Considere notificar a los docentes sobre los cambios</li>
        <li>La hora de fin debe ser posterior a la hora de inicio</li>
    </ul>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const horaInicio = document.getElementById('hora_inicio');
    const horaFin = document.getElementById('hora_fin');
    const duracionInfo = document.getElementById('duracion-info');
    const duracionText = document.getElementById('duracion-text');
    const conflictoWarning = document.getElementById('conflicto-warning');
    const conflictoText = document.getElementById('conflicto-text');

    function calcularDuracion() {
        if (horaInicio.value && horaFin.value) {
            const inicio = new Date('2000-01-01 ' + horaInicio.value);
            const fin = new Date('2000-01-01 ' + horaFin.value);
            
            if (fin > inicio) {
                const duracionMs = fin - inicio;
                const duracionHoras = duracionMs / (1000 * 60 * 60);
                const horas = Math.floor(duracionHoras);
                const minutos = Math.round((duracionHoras - horas) * 60);
                
                let texto = '';
                if (horas > 0) texto += horas + ' hora' + (horas > 1 ? 's' : '');
                if (minutos > 0) {
                    if (texto) texto += ' y ';
                    texto += minutos + ' minuto' + (minutos > 1 ? 's' : '');
                }
                
                duracionText.textContent = texto;
                duracionInfo.style.display = 'block';
                
                // Advertencias
                if (duracionHoras < 0.5) {
                    conflictoText.textContent = 'La duración es muy corta (menos de 30 minutos). Considere aumentar el tiempo.';
                    conflictoWarning.style.display = 'block';
                } else if (duracionHoras > 4) {
                    conflictoText.textContent = 'La duración es muy larga (más de 4 horas). Considere dividir en bloques más pequeños.';
                    conflictoWarning.style.display = 'block';
                } else {
                    conflictoWarning.style.display = 'none';
                }
            } else {
                duracionInfo.style.display = 'none';
                conflictoText.textContent = 'La hora de fin debe ser posterior a la hora de inicio.';
                conflictoWarning.style.display = 'block';
            }
        }
    }

    // Calcular duración inicial
    calcularDuracion();

    horaInicio.addEventListener('change', calcularDuracion);
    horaFin.addEventListener('change', calcularDuracion);
});
</script>
@endsection