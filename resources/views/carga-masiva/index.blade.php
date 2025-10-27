@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Historial de Cargas Masivas</h1>
            <a href="{{ route('carga-masiva.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition">
                ➕ Nueva Carga
            </a>
        </div>

        @if($cargas->count() > 0)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left">ID</th>
                        <th class="px-4 py-3 text-left">Archivo</th>
                        <th class="px-4 py-3 text-left">Procesados</th>
                        <th class="px-4 py-3 text-left">Exitosos</th>
                        <th class="px-4 py-3 text-left">Fallidos</th>
                        <th class="px-4 py-3 text-left">Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cargas as $carga)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $carga->carga_id }}</td>
                        <td class="px-4 py-3">{{ $carga->archivo_nombre }}</td>
                        <td class="px-4 py-3 text-center">{{ $carga->registros_procesados }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="text-green-600 font-bold">{{ $carga->registros_exitosos }}</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="text-red-600 font-bold">{{ $carga->registros_fallidos }}</span>
                        </td>
                        <td class="px-4 py-3">{{ $carga->fecha_carga }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="bg-gray-50 rounded-lg p-8 text-center">
            <p class="text-gray-600 mb-4">No hay cargas registradas</p>
            <a href="{{ route('carga-masiva.create') }}" 
               class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition">
                Realizar Primera Carga
            </a>
        </div>
        @endif
    </div>
</div>
@endsection