@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h1 style="margin: 0; color: #333;">Nueva Materia</h1>
    <a href="{{ route('materias.index') }}" style="background: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 500;">
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
    <form method="POST" action="{{ route('materias.store') }}">
        @csrf
        
        <div style="margin-bottom: 20px;">
            <label for="codigo_materia" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
                Código de Materia <span style="color: #dc3545;">*</span>
            </label>
            <input type="text" 
                   id="codigo_materia" 
                   name="codigo_materia" 
                   value="{{ old('codigo_materia') }}"
                   required
                   maxlength="10"
                   style="width: 100%; padding: 12px; border: 1px solid #ced4da; border-radius: 6px; font-size: 16px; box-sizing: border-box;"
                   placeholder="Ej: MAT101">
            <small style="color: #6c757d; font-size: 14px;">Máximo 10 caracteres</small>
        </div>

        <div style="margin-bottom: 20px;">
            <label for="nombre_materia" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
                Nombre de la Materia <span style="color: #dc3545;">*</span>
            </label>
            <input type="text" 
                   id="nombre_materia" 
                   name="nombre_materia" 
                   value="{{ old('nombre_materia') }}"
                   required
                   maxlength="100"
                   style="width: 100%; padding: 12px; border: 1px solid #ced4da; border-radius: 6px; font-size: 16px; box-sizing: border-box;"
                   placeholder="Ej: Matemáticas I">
            <small style="color: #6c757d; font-size: 14px;">Máximo 100 caracteres</small>
        </div>

        <div style="margin-bottom: 30px;">
            <label for="creditos" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
                Créditos <span style="color: #dc3545;">*</span>
            </label>
            <input type="number" 
                   id="creditos" 
                   name="creditos" 
                   value="{{ old('creditos') }}"
                   required
                   min="1"
                   max="10"
                   style="width: 100%; padding: 12px; border: 1px solid #ced4da; border-radius: 6px; font-size: 16px; box-sizing: border-box;"
                   placeholder="Ej: 4">
            <small style="color: #6c757d; font-size: 14px;">Entre 1 y 10 créditos</small>
        </div>

        <div style="display: flex; gap: 15px; justify-content: flex-end;">
            <a href="{{ route('materias.index') }}" 
               style="background: #6c757d; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: 500;">
                Cancelar
            </a>
            <button type="submit" 
                    style="background: #28a745; color: white; padding: 12px 24px; border: none; border-radius: 6px; cursor: pointer; font-weight: 500; font-size: 16px;">
                Crear Materia
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-focus en el primer campo
    document.getElementById('codigo_materia').focus();
    
    // Validación en tiempo real para el código
    const codigoInput = document.getElementById('codigo_materia');
    codigoInput.addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });
});
</script>
@endsection