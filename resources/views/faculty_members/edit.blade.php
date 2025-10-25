@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Faculty Member</h2>
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('faculty_members.update', $faculty_member) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $faculty_member->name) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $faculty_member->email) }}" required>
        </div>
        <button class="btn btn-primary">Update</button>
        <a href="{{ route('faculty_members.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection