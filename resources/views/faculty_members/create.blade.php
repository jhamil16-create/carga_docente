@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Create Faculty Member</h2>
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('faculty_members.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>
        <button class="btn btn-primary">Save</button>
        <a href="{{ route('faculty_members.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection