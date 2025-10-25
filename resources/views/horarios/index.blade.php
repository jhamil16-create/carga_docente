@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h1 style="margin: 0; color: #333;">Gestión de Horarios</h1>
    <a href="{{ route('horarios.create') }}" style="background: #0d6efd; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 500;">
        + Nuevo Horario
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
    <form method="GET" action="{{ route('horarios.index') }}" style="display: flex; gap: 15px; align-items: end; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 200px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #333;">Día de la Semana:</label>
            <select name="dia_semana" style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 4px;">
                <option value="">Todos los días</option>
                <option value="1" {{ request('dia_semana') == '1' ? 'selected' : '' }}>Lunes</option>
                <option value="2" {{ request('dia_semana') == '2' ? 'selected' : '' }}>Martes</option>
                <option value="3" {{ request('dia_semana') == '3' ? 'selected' : '' }}>Miércoles</option>
                <option value="4" {{ request('dia_semana') == '4' ? 'selected' : '' }}>Jueves</option>
                <option value="5" {{ request('dia_semana') == '5' ? 'selected' : '' }}>Viernes</option>
                <option value="6" {{ request('dia_semana') == '6' ? 'selected' : '' }}>Sábado</option>
                <option value="0" {{ request('dia_semana') == '0' ? 'selected' : '' }}>Domingo</option>
            </select>
        </div>
        <div style="flex: 1; min-width: 150px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #333;">Hora Inicio:</label>
            <input type="time" name="hora_inicio" value="{{ request('hora_inicio') }}" 
                   style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 4px;">
        </div>
        <div style="display: flex; gap: 10px;">
            <button type="submit" style="background: #17a2b8; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer;">
                Filtrar
            </button>
            <a href="{{ route('horarios.index') }}" style="background: #6c757d; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px;">
                Limpiar
            </a>
        </div>
    </form>
</div>

<div style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); overflow: hidden;">
    @if($horarios->count() > 0)
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: #f8f9fa;">
                <tr>
                    <th style="padding: 15px; text-align: left; border-bottom: 1px solid #dee2e6; font-weight: 600;">ID</th>
                    <th style="padding: 15px; text-align: left; border-bottom: 1px solid #dee2e6; font-weight: 600;">Día</th>
                    <th style="padding: 15px; text-align: center; border-bottom: 1px solid #dee2e6; font-weight: 600;">Hora Inicio</th>
                    <th style="padding: 15px; text-align: center; border-bottom: 1px solid #dee2e6; font-weight: 600;">Hora Fin</th>
                    <th style="padding: 15px; text-align: center; border-bottom: 1px solid #dee2e6; font-weight: 600;">Duración</th>
                    <th style="padding: 15px; text-align: center; border-bottom: 1px solid #dee2e6; font-weight: 600;">Asignaciones</th>
                    <th style="padding: 15px; text-align: center; border-bottom: 1px solid #dee2e6; font-weight: 600;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($horarios as $horario)
                <tr style="border-bottom: 1px solid #f1f3f4;">
                    <td style="padding: 15px;">{{ $horario->horario_id }}</td>
                    <td style="padding: 15px; font-weight: 500;">
                        @php
                            $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
                        @endphp
                        <span style="background: #e9ecef; padding: 4px 12px; border-radius: 20px; font-size: 14px;">
                            {{ $dias[$horario->dia_semana] }}
                        </span>
                    </td>
                    <td style="padding: 15px; text-align: center; font-family: monospace; font-size: 16px;">
                        {{ date('H:i', strtotime($horario->hora_inicio)) }}
                    </td>
                    <td style="padding: 15px; text-align: center; font-family: monospace; font-size: 16px;">
                        {{ date('H:i', strtotime($horario->hora_fin)) }}
                    </td>
                    <td style="padding: 15px; text-align: center;">
                        @php
                            $inicio = strtotime($horario->hora_inicio);
                            $fin = strtotime($horario->hora_fin);
                            $duracion = ($fin - $inicio) / 3600;
                        @endphp
                        <span style="background: #17a2b8; color: white; padding: 4px 8px; border-radius: 12px; font-size: 14px;">
                            {{ $duracion }}h
                        </span>
                    </td>
                    <td style="padding: 15px; text-align: center;">
                        <span style="background: #28a745; color: white; padding: 4px 8px; border-radius: 12px; font-size: 14px;">
                            {{ $horario->asignaciones->count() }}
                        </span>
                    </td>
                    <td style="padding: 15px; text-align: center;">
                        <div style="display: flex; gap: 8px; justify-content: center;">
                            <a href="{{ route('horarios.show', $horario) }}" 
                               style="background: #17a2b8; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 14px;">
                                Ver
                            </a>
                            <a href="{{ route('horarios.edit', $horario) }}" 
                               style="background: #ffc107; color: #212529; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 14px;">
                                Editar
                            </a>
                            <form method="POST" action="{{ route('horarios.destroy', $horario) }}" 
                                  style="display: inline;" 
                                  onsubmit="return confirm('¿Está seguro de eliminar este horario?')">
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
            <p style="margin: 0; font-size: 18px;">No hay horarios registrados</p>
            <p style="margin: 10px 0 0 0;">
                <a href="{{ route('horarios.create') }}" style="color: #0d6efd; text-decoration: none;">
                    Crear el primer horario
                </a>
            </p>
        </div>
    @endif
</div>

@if($horarios->hasPages())
    <div style="margin-top: 20px; display: flex; justify-content: center;">
        {{ $horarios->appends(request()->query())->links() }}
    </div>
@endif

<div style="margin-top: 20px; background: #f8f9fa; padding: 15px; border-radius: 6px; border-left: 4px solid #17a2b8;">
    <h4 style="margin: 0 0 10px 0; color: #17a2b8;">Información</h4>
    <p style="margin: 0; color: #666; font-size: 14px;">
        Los horarios definen los bloques de tiempo disponibles para las clases. Cada horario puede tener múltiples asignaciones en diferentes aulas.
    </p>
</div>
@endsection