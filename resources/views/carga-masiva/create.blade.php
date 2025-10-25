@extends('layouts.app')

@section('title', 'Nueva Carga')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="fas fa-upload"></i> Nueva Carga Masiva</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('carga-masiva.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Formulario de Carga</h5>
        </div>
        <div class="card-body">
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('carga-masiva.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="tipo" class="form-label">Tipo de Datos</label>
                        <select name="tipo" id="tipo" class="form-select" required>
                            <option value="">Seleccione un tipo</option>
                            <option value="estudiantes">Estudiantes</option>
                            <option value="docentes">Docentes</option>
                            <option value="materias">Materias</option>
                            <option value="aulas">Aulas</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="archivo" class="form-label">Archivo Excel</label>
                        <input type="file" name="archivo" id="archivo" class="form-control" accept=".xlsx,.xls,.csv" required>
                        <div class="form-text">Formatos aceptados: .xlsx, .xls, .csv</div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="actualizar_existentes" id="actualizar_existentes">
                            <label class="form-check-label" for="actualizar_existentes">
                                Actualizar registros existentes
                            </label>
                            <div class="form-text">Si está marcado, los registros existentes se actualizarán con la nueva información.</div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea name="observaciones" id="observaciones" class="form-control" rows="3"></textarea>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="reset" class="btn btn-secondary">
                        <i class="fas fa-undo"></i> Limpiar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload"></i> Cargar Datos
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Instrucciones</h5>
                </div>
                <div class="card-body">
                    <ol class="mb-0">
                        <li class="mb-2">Seleccione el tipo de datos que desea cargar.</li>
                        <li class="mb-2">Descargue la plantilla correspondiente si aún no la tiene:
                            <div class="mt-2">
                                <a href="{{ route('carga-masiva.descargar-plantilla', 'estudiantes') }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-download"></i> Plantilla Estudiantes
                                </a>
                                <a href="{{ route('carga-masiva.descargar-plantilla', 'docentes') }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-download"></i> Plantilla Docentes
                                </a>
                                <a href="{{ route('carga-masiva.descargar-plantilla', 'materias') }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-download"></i> Plantilla Materias
                                </a>
                                <a href="{{ route('carga-masiva.descargar-plantilla', 'aulas') }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-download"></i> Plantilla Aulas
                                </a>
                            </div>
                        </li>
                        <li class="mb-2">Complete la plantilla con los datos requeridos.</li>
                        <li class="mb-2">Seleccione el archivo desde su computadora.</li>
                        <li class="mb-2">Marque la opción "Actualizar registros existentes" si desea actualizar datos ya existentes.</li>
                        <li>Haga clic en "Cargar Datos" para iniciar el proceso.</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Información Importante</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-triangle"></i> Antes de cargar</h6>
                        <ul class="mb-0">
                            <li>Verifique que el formato del archivo sea correcto.</li>
                            <li>Asegúrese de que los datos cumplan con los requisitos del sistema.</li>
                            <li>Realice una copia de seguridad si va a actualizar datos existentes.</li>
                        </ul>
                    </div>
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle"></i> Proceso de validación</h6>
                        <p class="mb-0">El sistema validará los datos antes de cargarlos. Si hay errores, se mostrará un informe detallado.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection