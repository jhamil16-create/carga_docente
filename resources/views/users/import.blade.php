@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Bulk Upload Faculty (CSV)</h2>
    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <p>Expected headers: <code>name,email</code></p>
    <form method="POST" action="{{ route('users.import.csv') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label">CSV File</label>
            <input type="file" name="csv" class="form-control" required>
        </div>
        <button class="btn btn-primary">Upload and Import</button>
    </form>
</div>
@endsection