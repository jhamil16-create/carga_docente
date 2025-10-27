@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-white">Gestión de Usuarios</h1>
            <div class="flex gap-3">
                <a href="{{ route('carga-masiva.index') }}" 
                   class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg transition">
                    📤 Carga Masiva
                </a>
                <a href="{{ route('usuarios.create') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition">
                    ➕ Nuevo Usuario
                </a>
            </div>
        </div>

        <!-- Tabla de usuarios -->
        @if($usuarios && $usuarios->count() > 0)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left">ID</th>
                        <th class="px-4 py-3 text-left">Código</th>
                        <th class="px-4 py-3 text-left">Nombre</th>
                        <th class="px-4 py-3 text-left">Apellido</th>
                        <th class="px-4 py-3 text-left">Email</th>
                        <th class="px-4 py-3 text-left">Rol</th>
                        <th class="px-4 py-3 text-center">Estado</th>
                        <th class="px-4 py-3 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usuarios as $usuario)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm">{{ $usuario->usuario_id }}</td>
                        <td class="px-4 py-3">
                            <span class="font-mono font-bold text-blue-600">{{ $usuario->codigo_usuario }}</span>
                        </td>
                        <td class="px-4 py-3">{{ $usuario->nombre }}</td>
                        <td class="px-4 py-3">{{ $usuario->apellido }}</td>
                        <td class="px-4 py-3 text-sm">{{ $usuario->email_institucional }}</td>
                        <td class="px-4 py-3">
                            @if($usuario->rol)
                                <span class="px-2 py-1 rounded text-xs font-semibold
                                    @if($usuario->rol->nombre_rol === 'Administrador') bg-red-100 text-red-800
                                    @elseif($usuario->rol->nombre_rol === 'Docente') bg-blue-100 text-blue-800
                                    @elseif($usuario->rol->nombre_rol === 'Coordinador') bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ $usuario->rol->nombre_rol }}
                                </span>
                            @else
                                <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs">Sin rol</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($usuario->activo)
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold">
                                    ✓ Activo
                                </span>
                            @else
                                <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-bold">
                                    ✗ Inactivo
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('usuarios.edit', $usuario->usuario_id) }}" 
                                   class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm transition">
                                    ✏️ Editar
                                </a>
                                <form action="{{ route('usuarios.destroy', $usuario->usuario_id) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('¿Estás seguro de eliminar este usuario?');"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm transition">
                                        🗑️ Eliminar
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginación (si la usas) -->
        @if(method_exists($usuarios, 'links'))
        <div class="mt-6">
            {{ $usuarios->links() }}
        </div>
        @endif

        @else
        <div class="bg-gray-800 rounded-lg p-8 text-center border border-gray-700">
            <p class="text-gray-300 mb-4">👥 No hay usuarios registrados</p>
            <a href="{{ route('usuarios.create') }}" 
               class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition">
                Crear Primer Usuario
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
