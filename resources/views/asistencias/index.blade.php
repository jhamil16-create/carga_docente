@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Registro de Asistencia</h2>
        <a href="{{ route('asistencias.create') }}" class="btn btn-success">Registrar Asistencia</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Docente</th>
                    <th>Materia</th>
                    <th>Grupo</th>
                    <th>Aula</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($asistencias as $asistencia)
                    <tr>
                        <td>{{ $asistencia->docente->usuario->nombre ?? 'N/A' }}</td>
                        <td>{{ $asistencia->asignacion->grupo->materia->nombre_materia ?? 'N/A' }}</td>
                        <td>{{ $asistencia->asignacion->grupo->nombre_grupo ?? 'N/A' }}</td>
                        <td>{{ $asistencia->asignacion->aula->nombre_aula ?? 'N/A' }}</td>
                        <td>{{ $asistencia->fecha }}</td>
                        <td>{{ $asistencia->estado }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No hay registros de asistencia.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $asistencias->links() }}
</div>
@endsection