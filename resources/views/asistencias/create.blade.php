@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Registrar Asistencia</h2>

    <form action="{{ route('asistencias.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Docente</label>
                    <select name="docente_id" class="form-control" required>
                        <option value="">Seleccione un docente</option>
                        @foreach($docentes as $docente)
                            <option value="{{ $docente->docente_id }}">
                                {{ $docente->usuario->nombre }} {{ $docente->usuario->apellido }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Asignación</label>
                    <select name="asignacion_id" class="form-control" required>
                        <option value="">Seleccione una asignación</option>
                        @foreach($asignaciones as $asignacion)
                            <option value="{{ $asignacion->asignacion_id }}">
                                {{ $asignacion->grupo->materia->nombre_materia }} - {{ $asignacion->grupo->nombre_grupo }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Fecha</label>
                    <input type="date" name="fecha" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-control" required>
                        <option value="Presente">Presente</option>
                        <option value="Ausente">Ausente</option>
                        <option value="Tardanza">Tardanza</option>
                    </select>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Registrar</button>
        <a href="{{ route('asistencias.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection