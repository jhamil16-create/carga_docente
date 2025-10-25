@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h1 style="margin: 0; color: #333;">Gestión de Grupos</h1>
    <a href="{{ route('grupos.create') }}" style="background: #0d6efd; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 500;">
        + Nuevo Grupo
    </a>
</div>

@if(session('success'))
    <div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
        {{ session('error') }}
    </div>
@endif

<!-- Filtros -->
<div style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 20px; margin-bottom: 20px;">
    <form method="GET" action="{{ route('grupos.index') }}" style="display: flex; gap: 15px; align-items: end; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 200px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #333;">Materia:</label>
            <select name="materia_id" style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 4px;">
                <option value="">Todas las materias</option>
                @foreach($materias as $materia)
                    <option value="{{ $materia->materia_id }}" {{ request('materia_id') == $materia->materia_id ? 'selected' : '' }}>
                        {{ $materia->codigo_materia }} - {{ $materia->nombre_materia }}
                    </option>
                @endforeach
            </select>
        </div>
        <div style="flex: 1; min-width: 200px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #333;">Docente:</label>
            <select name="docente_id" style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 4px;">
                <option value="">Todos los docentes</option>
                @foreach($docentes as $docente)
                    <option value="{{ $docente->id }}" {{ request('docente_id') == $docente->id ? 'selected' : '' }}>
                        {{ $docente->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div style="flex: 1; min-width: 150px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #333;">Estado:</label>
            <select name="estado" style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 4px;">
                <option value="">Todos los estados</option>
                <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                <option value="inactivo" {{ request('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
            </select>
        </div>
        <div style="display: flex; gap: 10px;">
            <button type="submit" style="background: #17a2b8; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer;">
                Filtrar
            </button>
            <a href="{{ route('grupos.index') }}" style="background: #6c757d; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px;">
                Limpiar
            </a>
        </div>
    </form>
</div>

<div style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); overflow: hidden;">
    @if($grupos->count() > 0)
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: #f8f9fa;">
                <tr>
                    <th style="padding: 15px; text-align: left; border-bottom: 1px solid #dee2e6; font-weight: 600;">ID</th>
                    <th style="padding: 15px; text-align: left; border-bottom: 1px solid #dee2e6; font-weight: 600;">Grupo</th>
                    <th style="padding: 15px; text-align: left; border-bottom: 1px solid #dee2e6; font-weight: 600;">Materia</th>
                    <th style="padding: 15px; text-align: left; border-bottom: 1px solid #dee2e6; font-weight: 600;">Docente</th>
                    <th style="padding: 15px; text-align: center; border-bottom: 1px solid #dee2e6; font-weight: 600;">Estudiantes</th>
                    <th style="padding: 15px; text-align: center; border-bottom: 1px solid #dee2e6; font-weight: 600;">Asignaciones</th>
                    <th style="padding: 15px; text-align: center; border-bottom: 1px solid #dee2e6; font-weight: 600;">Estado</th>
                    <th style="padding: 15px; text-align: center; border-bottom: 1px solid #dee2e6; font-weight: 600;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($grupos as $grupo)
                <tr style="border-bottom: 1px solid #f1f3f4;">
                    <td style="padding: 15px;">{{ $grupo->grupo_id }}</td>
                    <td style="padding: 15px;">
                        <div style="font-weight: 600; color: #333;">{{ $grupo->nombre_grupo }}</div>
                        <div style="font-size: 14px; color: #666;">{{ $grupo->descripcion ?? 'Sin descripción' }}</div>
                    </td>
                    <td style="padding: 15px;">
                        <div style="font-weight: 500;">{{ $grupo->materia->nombre_materia }}</div>
                        <div style="font-size: 14px; color: #666;">{{ $grupo->materia->codigo_materia }}</div>
                    </td>
                    <td style="padding: 15px;">
                        @if($grupo->docente)
                            <div style="font-weight: 500;">{{ $grupo->docente->name }}</div>
                            <div style="font-size: 14px; color: #666;">{{ $grupo->docente->email }}</div>
                        @else
                            <span style="color: #dc3545; font-style: italic;">Sin asignar</span>
                        @endif
                    </td>
                    <td style="padding: 15px; text-align: center;">
                        <span style="background: #17a2b8; color: white; padding: 4px 8px; border-radius: 12px; font-size: 14px;">
                            {{ $grupo->estudiantes_inscritos ?? 0 }}
                        </span>
                    </td>
                    <td style="padding: 15px; text-align: center;">
                        <span style="background: #28a745; color: white; padding: 4px 8px; border-radius: 12px; font-size: 14px;">
                            {{ $grupo->asignaciones->count() }}
                        </span>
                    </td>
                    <td style="padding: 15px; text-align: center;">
                        @if($grupo->estado == 'activo')
                            <span style="background: #28a745; color: white; padding: 4px 12px; border-radius: 20px; font-size: 14px;">
                                Activo
                            </span>
                        @else
                            <span style="background: #dc3545; color: white; padding: 4px 12px; border-radius: 20px; font-size: 14px;">
                                Inactivo
                            </span>
                        @endif
                    </td>
                    <td style="padding: 15px; text-align: center;">
                        <div style="display: flex; gap: 8px; justify-content: center;">
                            <a href="{{ route('grupos.show', $grupo) }}" 
                               style="background: #17a2b8; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 14px;">
                                Ver
                            </a>
                            <a href="{{ route('grupos.edit', $grupo) }}" 
                               style="background: #ffc107; color: #212529; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 14px;">
                                Editar
                            </a>
                            <form method="POST" action="{{ route('grupos.destroy', $grupo) }}" 
                                  style="display: inline;" 
                                  onsubmit="return confirm('¿Está seguro de eliminar este grupo? Se eliminarán también todas sus asignaciones.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        style="background: #dc3545; color: white; padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; font-size: 14px;">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div style="padding: 40px; text-align: center; color: #6c757d;">
            <div style="font-size: 48px; margin-bottom: 15px;">👥</div>
            <p style="margin: 0; font-size: 18px;">No hay grupos registrados</p>
            <p style="margin: 10px 0 0 0;">
                <a href="{{ route('grupos.create') }}" style="color: #0d6efd; text-decoration: none;">
                    Crear el primer grupo
                </a>
            </p>
        </div>
    @endif
</div>

@if($grupos->hasPages())
    <div style="margin-top: 20px; display: flex; justify-content: center;">
        {{ $grupos->appends(request()->query())->links() }}
    </div>
@endif

<!-- Estadísticas rápidas -->
<div style="margin-top: 20px; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
    <div style="background: #e3f2fd; padding: 20px; border-radius: 8px; text-align: center;">
        <div style="font-size: 24px; font-weight: bold; color: #1976d2;">{{ $grupos->total() }}</div>
        <div style="color: #666; font-size: 14px;">Total Grupos</div>
    </div>
    <div style="background: #e8f5e8; padding: 20px; border-radius: 8px; text-align: center;">
        <div style="font-size: 24px; font-weight: bold; color: #388e3c;">
            {{ $grupos->where('estado', 'activo')->count() }}
        </div>
        <div style="color: #666; font-size: 14px;">Grupos Activos</div>
    </div>
    <div style="background: #fff3e0; padding: 20px; border-radius: 8px; text-align: center;">
        <div style="font-size: 24px; font-weight: bold; color: #f57c00;">
            {{ $grupos->whereNull('docente_id')->count() }}
        </div>
        <div style="color: #666; font-size: 14px;">Sin Docente</div>
    </div>
    <div style="background: #fce4ec; padding: 20px; border-radius: 8px; text-align: center;">
        <div style="font-size: 24px; font-weight: bold; color: #c2185b;">
            {{ $grupos->sum('estudiantes_inscritos') }}
        </div>
        <div style="color: #666; font-size: 14px;">Total Estudiantes</div>
    </div>
</div>

<div style="margin-top: 20px; background: #f8f9fa; padding: 15px; border-radius: 6px; border-left: 4px solid #17a2b8;">
    <h4 style="margin: 0 0 10px 0; color: #17a2b8;">Información</h4>
    <p style="margin: 0; color: #666; font-size: 14px;">
        Los grupos representan las secciones de una materia específica. Cada grupo debe tener un docente asignado y puede tener múltiples asignaciones de horarios y aulas.
    </p>
</div>
@endsection