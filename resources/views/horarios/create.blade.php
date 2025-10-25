@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h1 style="margin: 0; color: #333;">Crear Nuevo Horario</h1>
    <a href="{{ route('horarios.index') }}" style="background: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 500;">
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
    <form method="POST" action="{{ route('horarios.store') }}">
        @csrf
        
        <div style="margin-bottom: 25px;">
            <label for="dia_semana" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
                Día de la Semana <span style="color: #dc3545;">*</span>
            </label>
            <select name="dia_semana" id="dia_semana" required
                    style="width: 100%; padding: 12px; border: 1px solid #ced4da; border-radius: 6px; font-size: 16px; background: white;">
                <option value="">Seleccione un día</option>
                <option value="1" {{ old('dia_semana') == '1' ? 'selected' : '' }}>Lunes</option>
                <option value="2" {{ old('dia_semana') == '2' ? 'selected' : '' }}>Martes</option>
                <option value="3" {{ old('dia_semana') == '3' ? 'selected' : '' }}>Miércoles</option>
                <option value="4" {{ old('dia_semana') == '4' ? 'selected' : '' }}>Jueves</option>
                <option value="5" {{ old('dia_semana') == '5' ? 'selected' : '' }}>Viernes</option>
                <option value="6" {{ old('dia_semana') == '6' ? 'selected' : '' }}>Sábado</option>
                <option value="0" {{ old('dia_semana') == '0' ? 'selected' : '' }}>Domingo</option>
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
                <input type="time" name="hora_inicio" id="hora_inicio" value="{{ old('hora_inicio') }}" required
                       style="width: 100%; padding: 12px; border: 1px solid #ced4da; border-radius: 6px; font-size: 16px;">
                @error('hora_inicio')
                    <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="hora_fin" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
                    Hora de Fin <span style="color: #dc3545;">*</span>
                </label>
                <input type="time" name="hora_fin" id="hora_fin" value="{{ old('hora_fin') }}" required
                       style="width: 100%; padding: 12px; border: 1px solid #ced4da; border-radius: 6px; font-size: 16px;">
                @error('hora_fin')
                    <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Información de duración -->
        <div id="duracion-info" style="background: #e9ecef; padding: 15px; border-radius: 6px; margin-bottom: 25px; display: none;">
            <h4 style="margin: 0 0 5px 0; color: #495057;">Duración del Horario:</h4>
            <p id="duracion-text" style="margin: 0; font-size: 16px; font-weight: 500; color: #17a2b8;"></p>
        </div>

        <!-- Verificación de conflictos -->
        <div id="conflicto-warning" style="background: #fff3cd; color: #856404; padding: 15px; border-radius: 6px; margin-bottom: 25px; display: none; border: 1px solid #ffeaa7;">
            <h4 style="margin: 0 0 5px 0;">⚠️ Posible Conflicto</h4>
            <p id="conflicto-text" style="margin: 0;"></p>
        </div>

        <div style="display: flex; gap: 15px; justify-content: flex-end;">
            <a href="{{ route('horarios.index') }}" 
               style="background: #6c757d; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: 500;">
                Cancelar
            </a>
            <button type="submit" 
                    style="background: #28a745; color: white; padding: 12px 24px; border: none; border-radius: 6px; cursor: pointer; font-weight: 500; font-size: 16px;">
                Crear Horario
            </button>
        </div>
    </form>
</div>

<div style="margin-top: 20px; background: #f8f9fa; padding: 15px; border-radius: 6px; border-left: 4px solid #17a2b8;">
    <h4 style="margin: 0 0 10px 0; color: #17a2b8;">Consejos</h4>
    <ul style="margin: 0; padding-left: 20px; color: #666; font-size: 14px;">
        <li>La hora de fin debe ser posterior a la hora de inicio</li>
        <li>Se recomienda crear horarios de al menos 1 hora de duración</li>
        <li>Los horarios pueden solaparse, pero se mostrará una advertencia</li>
        <li>Considere los tiempos de descanso entre clases</li>
    </ul>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const horaInicio = document.getElementById('hora_inicio');
    const horaFin = document.getElementById('hora_fin');
    const diaSemana = document.getElementById('dia_semana');
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
        } else {
            duracionInfo.style.display = 'none';
            conflictoWarning.style.display = 'none';
        }
    }

    horaInicio.addEventListener('change', calcularDuracion);
    horaFin.addEventListener('change', calcularDuracion);
    
    // Validación en tiempo real
    horaInicio.addEventListener('input', function() {
        if (this.value && !horaFin.value) {
            // Sugerir hora de fin (1 hora después)
            const inicio = new Date('2000-01-01 ' + this.value);
            inicio.setHours(inicio.getHours() + 1);
            const horasSugerida = inicio.getHours().toString().padStart(2, '0');
            const minutosSugerida = inicio.getMinutes().toString().padStart(2, '0');
            horaFin.value = horasSugerida + ':' + minutosSugerida;
            calcularDuracion();
        }
    });
});
</script>
@endsection