@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 480px;">
    <h2>Iniciar Sesión</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Email institucional</label>
            <input type="email" name="email_institucional" class="form-control" value="{{ old('email_institucional') }}" required autofocus>
        </div>
        <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input type="password" name="contraseña" class="form-control" required>
        </div>
        <button class="btn btn-primary">Iniciar sesión</button>
    </form>
</div>
@endsection