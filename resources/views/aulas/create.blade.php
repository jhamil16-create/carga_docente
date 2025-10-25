@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h1 style="margin: 0; color: #333;">Nueva Aula</h1>
    <a href="{{ route('aulas.index') }}" style="background: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 500;">
        ← Volver
    </a>
</div>

@if($errors->any())
    <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
        <strong>Por favor corrige los siguientes errores:</strong>
        <ul style="margin: 10px 0 0 0; padding-left: 20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 30px;">
    <form method="POST" action="{{ route('aulas.store') }}">
        @csrf
        
        <div style="margin-bottom: 20px;">
            <label for="nombre_aula" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
                Nombre del Aula <span style="color: #dc3545;">*</span>
            </label>
            <input type="text" 
                   id="nombre_aula" 
                   name="nombre_aula" 
                   value="{{ old('nombre_aula') }}"
                   required
                   maxlength="50"
                   style="width: 100%; padding: 12px; border: 1px solid #ced4da; border-radius: 6px; font-size: 16px; box-sizing: border-box;"
                   placeholder="Ej: Aula 101, Laboratorio A, Auditorio Principal">
            <small style="color: #6c757d; font-size: 14px;">Máximo 50 caracteres</small>
        </div>

        <div style="margin-bottom: 20px;">
            <label for="capacidad" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
                Capacidad <span style="color: #dc3545;">*</span>
            </label>
            <input type="number" 
                   id="capacidad" 
                   name="capacidad" 
                   value="{{ old('capacidad') }}"
                   required
                   min="1"
                   max="500"
                   style="width: 100%; padding: 12px; border: 1px solid #ced4da; border-radius: 6px; font-size: 16px; box-sizing: border-box;"
                   placeholder="Ej: 30">
            <small style="color: #6c757d; font-size: 14px;">Número de estudiantes que puede albergar (1-500)</small>
        </div>

        <div style="margin-bottom: 30px;">
            <label for="ubicacion" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
                Ubicación <span style="color: #dc3545;">*</span>
            </label>
            <textarea id="ubicacion" 
                      name="ubicacion" 
                      required
                      maxlength="200"
                      rows="3"
                      style="width: 100%; padding: 12px; border: 1px solid #ced4da; border-radius: 6px; font-size: 16px; box-sizing: border-box; resize: vertical;"
                      placeholder="Ej: Edificio A, Primer Piso, Ala Norte">{{ old('ubicacion') }}</textarea>
            <small style="color: #6c757d; font-size: 14px;">Descripción detallada de la ubicación (máximo 200 caracteres)</small>
        </div>

        <div style="display: flex; gap: 15px; justify-content: flex-end;">
            <a href="{{ route('aulas.index') }}" 
               style="background: #6c757d; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: 500;">
                Cancelar
            </a>
            <button type="submit" 
                    style="background: #28a745; color: white; padding: 12px 24px; border: none; border-radius: 6px; cursor: pointer; font-weight: 500; font-size: 16px;">
                Crear Aula
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-focus en el primer campo
    document.getElementById('nombre_aula').focus();
    
    // Validación en tiempo real para capacidad
    const capacidadInput = document.getElementById('capacidad');
    capacidadInput.addEventListener('input', function() {
        if (this.value < 1) this.value = 1;
        if (this.value > 500) this.value = 500;
    });
    
    // Contador de caracteres para ubicación
    const ubicacionTextarea = document.getElementById('ubicacion');
    const maxLength = 200;
    
    // Crear contador
    const counter = document.createElement('div');
    counter.style.cssText = 'text-align: right; margin-top: 5px; font-size: 12px; color: #6c757d;';
    ubicacionTextarea.parentNode.insertBefore(counter, ubicacionTextarea.nextSibling.nextSibling);
    
    function updateCounter() {
        const remaining = maxLength - ubicacionTextarea.value.length;
        counter.textContent = `${ubicacionTextarea.value.length}/${maxLength} caracteres`;
        counter.style.color = remaining < 20 ? '#dc3545' : '#6c757d';
    }
    
    ubicacionTextarea.addEventListener('input', updateCounter);
    updateCounter();
});
</script>
@endsection