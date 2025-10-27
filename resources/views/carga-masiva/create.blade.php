@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">Carga Masiva de Usuarios</h1>

        <!-- Explicación del proceso -->
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
            <h3 class="font-bold text-blue-800 mb-2">📋 ¿Cómo funciona?</h3>
            <p class="text-blue-700">
                El sistema generará automáticamente las cuentas a partir de los datos básicos que proporcione la facultad:
            </p>
            <ul class="list-disc list-inside text-blue-700 mt-2 space-y-1">
                <li><strong>Código de usuario:</strong> Se genera automáticamente (DOC001, ADM001, etc.)</li>
                <li><strong>Contraseña temporal:</strong> Se crea de forma aleatoria y segura</li>
                <li><strong>Rol:</strong> Se asigna según el tipo de usuario especificado</li>
            </ul>
        </div>

        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                <h3 class="font-bold text-red-800 mb-2">⚠️ Errores</h3>
                <ul class="list-disc list-inside text-red-700">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('warning'))
            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-6">
                <p class="text-yellow-700">{{ session('warning') }}</p>
            </div>
        @endif

        <!-- Formulario de carga -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('carga-masiva.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="tipo" value="docentes">
                <div class="mb-6 bg-blue-50 p-4 rounded-lg">
                    <p class="text-blue-700 font-medium">
                        📌 Este formulario está configurado para cargar <strong>Docentes y Personal Administrativo</strong>.
                    </p>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2">
                        Archivo CSV/Excel
                    </label>
                    <input type="file" name="archivo" accept=".csv,.xlsx,.xls" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                    <p class="text-sm text-gray-500 mt-1">
                        Formatos aceptados: CSV, XLSX, XLS (máximo 10MB)
                    </p>
                </div>

                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="actualizar_existentes" class="mr-2 w-4 h-4">
                        <span class="text-gray-700">Actualizar usuarios existentes si ya están registrados</span>
                    </label>
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition">
                        📤 Cargar Archivo
                    </button>
                    <a href="{{ route('carga-masiva.plantilla', 'docentes') }}" 
                       class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg transition">
                        📥 Descargar Plantilla
                    </a>
                    <a href="{{ route('carga-masiva.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg transition">
                        ← Volver
                    </a>
                </div>
            </form>
        </div>

        <!-- Instrucciones de la plantilla -->
        <div class="bg-gray-50 rounded-lg p-6 mt-6">
            <h3 class="font-bold text-gray-800 mb-3">📝 Estructura del archivo requerido</h3>
            <p class="text-gray-700 mb-2">El archivo debe contener las siguientes columnas:</p>
            <div class="bg-white p-4 rounded border border-gray-200 font-mono text-sm">
                <div class="font-bold text-gray-800">nombre, apellido, email_institucional, tipo_usuario</div>
                <div class="text-gray-600 mt-2">Juan, Pérez, juan.perez@ficct.uagrm.edu.bo, docente</div>
            </div>
            
            <div class="mt-4">
                <p class="font-bold text-gray-700 mb-2">Tipos de usuario válidos:</p>
                <ul class="list-disc list-inside text-gray-600 space-y-1">
                    <li><code class="bg-gray-200 px-2 py-1 rounded">docente</code> o <code class="bg-gray-200 px-2 py-1 rounded">profesor</code> → Rol de Docente</li>
                    <li><code class="bg-gray-200 px-2 py-1 rounded">administrador</code> o <code class="bg-gray-200 px-2 py-1 rounded">admin</code> → Rol de Administrador</li>
                    <li><code class="bg-gray-200 px-2 py-1 rounded">coordinador</code> → Rol de Coordinador</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection