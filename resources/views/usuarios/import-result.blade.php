@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4>Resultado de la Carga Masiva</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-success">
                        <h5>Resumen:</h5>
                        <ul>
                            <li><strong>Usuarios creados exitosamente:</strong> {{ $total_exitosos }}</li>
                            <li><strong>Errores encontrados:</strong> {{ $total_errores }}</li>
                        </ul>
                    </div>

                    @if(count($errores) > 0)
                        <div class="alert alert-warning">
                            <h5>Errores:</h5>
                            <ul>
                                @foreach($errores as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(count($usuarios_creados) > 0)
                        <h5 class="mt-4">Usuarios Creados:</h5>
                        <div class="alert alert-info">
                            <strong>IMPORTANTE:</strong> Guarde esta información. Las contraseñas temporales deben ser entregadas a cada usuario para su primer acceso.
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Código Usuario</th>
                                        <th>Contraseña Temporal</th>
                                        <th>Rol</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($usuarios_creados as $usuario)
                                        <tr>
                                            <td>{{ $usuario['nombre'] }}</td>
                                            <td>{{ $usuario['email'] }}</td>
                                            <td><code>{{ $usuario['codigo'] }}</code></td>
                                            <td><code class="text-danger">{{ $usuario['contraseña'] }}</code></td>
                                            <td>{{ $usuario['rol'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <button onclick="imprimirCredenciales()" class="btn btn-primary mt-3">
                            <i class="bi bi-printer"></i> Imprimir Credenciales
                        </button>
                    @endif

                    <div class="mt-4">
                        <a href="{{ route('usuarios.index') }}" class="btn btn-success">Ir a Gestión de Usuarios</a>
                        <a href="{{ route('usuarios.import.form') }}" class="btn btn-secondary">Cargar Más Usuarios</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function imprimirCredenciales() {
    window.print();
}
</script>

<style>
@media print {
    .btn, .card-header, .alert-info { display: none; }
}
</style>
@endsection