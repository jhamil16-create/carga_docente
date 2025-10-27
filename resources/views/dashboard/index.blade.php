@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Dashboard - Sistema de Gestión Académica</h1>
    <p>Bienvenido, {{ Auth::user()->nombre }}.</p>

    <div class="mt-4">
        <h3>Módulos disponibles:</h3>
        <ul class="list-group">
            <li class="list-group-item">
                <a href="{{ route('usuarios.index') }}">Gestión de Usuarios</a>
            </li>
            <li class="list-group-item">
                <a href="{{ route('carga-masiva.index') }}">Carga Masiva de Usuarios</a>
            </li>
            <li class="list-group-item">
                <a href="{{ route('asistencias.index') }}">Registro de Asistencia</a>
            </li>
            <li class="list-group-item">
                <a href="{{ route('grupos.index') }}">Gestión de Grupos</a>
            </li> 
        </ul>
    </div>
</div>
@endsection