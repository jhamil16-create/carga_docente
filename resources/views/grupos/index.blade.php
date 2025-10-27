@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-users"></i> Gestión de Grupos</h2>
        <a href="{{ route('grupos.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Grupo
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('grupos.index') }}" class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Filtrar por Materia</label>
                    <select name="materia_id" class="form-select">
                        <option value="">Todas las materias</option>
                        @foreach($materias as $materia)
                            <option value="{{ $materia->materia_id }}" 
                                {{ request('materia_id') == $materia->materia_id ? 'selected' : '' }}>
                                {{ $materia->codigo_materia }} - {{ $materia->nombre_materia }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-filter"></i> Filtrar
                    </button>
                    <a href="{{ route('grupos.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de grupos -->
    <div class="card">
        <div class="card-body">
            @if($grupos->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Grupo</th>
                                <th>Materia</th>
                                <th>Capacidad</th>
                                <th>Asignaciones</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($grupos as $grupo)
                            <tr>
                                <td>{{ $grupo->grupo_id }}</td>
                                <td>
                                    <strong>{{ $grupo->nombre_grupo }}</strong>
                                </td>
                                <td>
                                    <div><strong>{{ $grupo->materia->nombre_materia }}</strong></div>
                                    <small class="text-muted">{{ $grupo->materia->codigo_materia }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $grupo->capacidad_maxima }} estudiantes</span>
                                </td>
                                <td>
                                    <span class="badge bg-success">{{ $grupo->asignaciones->count() }}</span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('grupos.show', $grupo->grupo_id) }}" 
                                           class="btn btn-sm btn-info">
                                            Ver
                                        </a>
                                        <a href="{{ route('grupos.edit', $grupo->grupo_id) }}" 
                                           class="btn btn-sm btn-warning">
                                            Editar
                                        </a>
                                        <form method="POST" 
                                              action="{{ route('grupos.destroy', $grupo->grupo_id) }}" 
                                              style="display: inline;"
                                              onsubmit="return confirm('¿Está seguro de eliminar este grupo?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $grupos->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No hay grupos registrados</p>
                    <a href="{{ route('grupos.create') }}" class="btn btn-primary">
                        Crear primer grupo
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-primary">{{ $grupos->total() }}</h3>
                    <p class="text-muted mb-0">Total Grupos</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-success">{{ $grupos->sum(fn($g) => $g->asignaciones->count()) }}</h3>
                    <p class="text-muted mb-0">Total Asignaciones</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-info">{{ $grupos->sum('capacidad_maxima') }}</h3>
                    <p class="text-muted mb-0">Capacidad Total</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection