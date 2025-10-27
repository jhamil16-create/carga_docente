@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">Resultado de Carga Masiva</h1>

        <!-- Resumen -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="bg-green-50 border-l-4 border-green-500 p-4">
                <h3 class="font-bold text-green-800">✅ Usuarios Creados/Actualizados</h3>
                <p class="text-3xl font-bold text-green-600">{{ $total_exitosos }}</p>
            </div>
            <div class="bg-red-50 border-l-4 border-red-500 p-4">
                <h3 class="font-bold text-red-800">❌ Errores</h3>
                <p class="text-3xl font-bold text-red-600">{{ $total_errores }}</p>
            </div>
        </div>

        <!-- Usuarios creados -->
        @if(count($usuarios_creados) > 0)
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-800">👥 Usuarios Procesados</h2>
                <button onclick="copiarCredenciales()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm">
                    📋 Copiar todas las credenciales
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse" id="tabla-usuarios">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border px-4 py-2 text-left">Nombre</th>
                            <th class="border px-4 py-2 text-left">Email</th>
                            <th class="border px-4 py-2 text-left">Código Usuario</th>
                            <th class="border px-4 py-2 text-left">Contraseña Temporal</th>
                            <th class="border px-4 py-2 text-left">Rol</th>
                            <th class="border px-4 py-2 text-left">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($usuarios_creados as $usuario)
                        <tr class="hover:bg-gray-50">
                            <td class="border px-4 py-2">{{ $usuario['nombre'] }}</td>
                            <td class="border px-4 py-2">{{ $usuario['email'] }}</td>
                            <td class="border px-4 py-2 font-mono font-bold">{{ $usuario['codigo'] }}</td>
                            <td class="border px-4 py-2">
                                <code class="bg-yellow-100 px-2 py-1 rounded font-bold text-red-600">
                                    {{ $usuario['contraseña'] }}
                                </code>
                            </td>
                            <td class="border px-4 py-2">
                                <span class="px-2 py-1 rounded text-sm font-semibold
                                    {{ $usuario['rol'] === 'Administrador' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $usuario['rol'] === 'Docente' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $usuario['rol'] === 'Coordinador' ? 'bg-green-100 text-green-800' : '' }}">
                                    {{ $usuario['rol'] }}
                                </span>
                            </td>
                            <td class="border px-4 py-2">
                                <span class="px-2 py-1 rounded text-sm
                                    {{ $usuario['accion'] === 'Creado' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $usuario['accion'] }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mt-4">
                <p class="font-bold text-yellow-800">⚠️ IMPORTANTE:</p>
                <p class="text-yellow-700">
                    Estas credenciales deben ser entregadas a cada usuario de forma segura. 
                    Los usuarios deberán cambiar su contraseña en el primer inicio de sesión.
                </p>
            </div>
        </div>
        @endif

        <!-- Errores -->
        @if(count($errores) > 0)
        <div class="bg-red-50 rounded-lg p-6 mb-6">
            <h2 class="text-xl font-bold text-red-800 mb-4">❌ Errores Encontrados</h2>
            <ul class="list-disc list-inside text-red-700 space-y-1">
                @foreach($errores as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Botones de acción -->
        <div class="flex gap-4">
            <a href="{{ route('carga-masiva.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition">
                🔄 Nueva Carga
            </a>
            <a href="{{ route('carga-masiva.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg transition">
                📋 Ver Historial
            </a>
            <button onclick="window.print()" 
                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg transition">
                🖨️ Imprimir
            </button>
        </div>
    </div>
</div>

<script>
function copiarCredenciales() {
    let texto = "CREDENCIALES DE ACCESO - SISTEMA DE CARGA HORARIA\n";
    texto += "=" .repeat(60) + "\n\n";
    
    const filas = document.querySelectorAll('#tabla-usuarios tbody tr');
    filas.forEach(fila => {
        const celdas = fila.querySelectorAll('td');
        texto += `Nombre: ${celdas[0].textContent}\n`;
        texto += `Email: ${celdas[1].textContent}\n`;
        texto += `Usuario: ${celdas[2].textContent}\n`;
        texto += `Contraseña: ${celdas[3].textContent.trim()}\n`;
        texto += `Rol: ${celdas[4].textContent.trim()}\n`;
        texto += "-".repeat(60) + "\n\n";
    });
    
    navigator.clipboard.writeText(texto).then(() => {
        alert('✅ Credenciales copiadas al portapapeles');
    });
}
</script>
@endsection