@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h1 style="margin: 0; color: #333;">Gestión de Materias</h1>
    <a href="{{ route('materias.create') }}" style="background: #0d6efd; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 500;">
        + Nueva Materia
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

<div style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); overflow: hidden;">
    @if($materias->count() > 0)
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: #f8f9fa;">
                <tr>
                    <th style="padding: 15px; text-align: left; border-bottom: 1px solid #dee2e6; font-weight: 600;">ID</th>
                    <th style="padding: 15px; text-align: left; border-bottom: 1px solid #dee2e6; font-weight: 600;">Código</th>
                    <th style="padding: 15px; text-align: left; border-bottom: 1px solid #dee2e6; font-weight: 600;">Nombre</th>
                    <th style="padding: 15px; text-align: left; border-bottom: 1px solid #dee2e6; font-weight: 600;">Créditos</th>
                    <th style="padding: 15px; text-align: center; border-bottom: 1px solid #dee2e6; font-weight: 600;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($materias as $materia)
                <tr style="border-bottom: 1px solid #f1f3f4;">
                    <td style="padding: 15px;">{{ $materia->materia_id }}</td>
                    <td style="padding: 15px; font-weight: 500;">{{ $materia->codigo_materia }}</td>
                    <td style="padding: 15px;">{{ $materia->nombre_materia }}</td>
                    <td style="padding: 15px;">{{ $materia->creditos }}</td>
                    <td style="padding: 15px; text-align: center;">
                        <div style="display: flex; gap: 8px; justify-content: center;">
                            <a href="{{ route('materias.show', $materia) }}" 
                               style="background: #17a2b8; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 14px;">
                                Ver
                            </a>
                            <a href="{{ route('materias.edit', $materia) }}" 
                               style="background: #ffc107; color: #212529; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 14px;">
                                Editar
                            </a>
                            <form method="POST" action="{{ route('materias.destroy', $materia) }}" 
                                  style="display: inline;" 
                                  onsubmit="return confirm('¿Está seguro de eliminar esta materia?')">
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
            <p style="margin: 0; font-size: 18px;">No hay materias registradas</p>
            <p style="margin: 10px 0 0 0;">
                <a href="{{ route('materias.create') }}" style="color: #0d6efd; text-decoration: none;">
                    Crear la primera materia
                </a>
            </p>
        </div>
    @endif
</div>

@if($materias->hasPages())
    <div style="margin-top: 20px; display: flex; justify-content: center;">
        {{ $materias->links() }}
    </div>
@endif
@endsection