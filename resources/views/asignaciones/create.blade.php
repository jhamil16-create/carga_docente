@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Crear Nueva Asignación</h2>
    
    <form method="POST" action="{{ route('asignaciones.store') }}">
        @csrf
        
        <input type="hidden" name="grupo_id" value="{{ $grupo->grupo_id ?? '' }}">
        
        <div class="mb-3">
            <label class="form-label">Grupo</label>
            <select name="grupo_id" class="form-control" {{ $grupo ? 'disabled' : '' }} required>
                @if($grupo)
                    <option value="{{ $grupo->grupo_id }}">{{ $grupo->nombre_grupo }} - {{ $grupo->materia->nombre_materia }}</option>
                @else
                    <option value="">Seleccione un grupo</option>
                    @foreach($grupos as $g)
                        <option value="{{ $g->grupo_id }}" {{ old('grupo_id') == $g->grupo_id ? 'selected' : '' }}>
                            {{ $g->nombre_grupo }} - {{ $g->materia->nombre_materia }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Docente</label>
            <select name="docente_id" class="form-control" required>
                <option value="">Seleccione un docente</option>
                @foreach($docentes as $d)
                    <option value="{{ $d->docente_id }}" {{ old('docente_id') == $d->docente_id ? 'selected' : '' }}>
                        {{ $d->usuario->nombre }} {{ $d->usuario->apellido }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Aula</label>
            <select name="aula_id" class="form-control" required>
                <option value="">Seleccione un aula</option>
                @foreach($aulas as $a)
                    <option value="{{ $a->aula_id }}" {{ old('aula_id') == $a->aula_id ? 'selected' : '' }}>
                        {{ $a->nombre_aula }} ({{ $a->capacidad }}) - {{ $a->ubicacion }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Horario</label>
            <select name="horario_id" class="form-control" required>
                <option value="">Seleccione un horario</option>
                @foreach($horarios as $h)
                    <option value="{{ $h->horario_id }}" {{ old('horario_id') == $h->horario_id ? 'selected' : '' }}>
                        {{ $h->dia_semana }}: {{ $h->hora_inicio }} - {{ $h->hora_fin }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Crear Asignación</button>
        <a href="{{ $grupo ? route('grupos.show', $grupo->grupo_id) : route('grupos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection