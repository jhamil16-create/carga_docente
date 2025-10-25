@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Faculty Members</h2>
    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    @if(!$dbReady)
        <div class="alert alert-warning">Database not ready. Run migrations.</div>
    @endif
    <a href="{{ route('faculty_members.create') }}" class="btn btn-primary">Add Faculty</a>
    <table class="table mt-3">
        <thead>
            <tr><th>ID</th><th>Name</th><th>Email</th><th>Actions</th></tr>
        </thead>
        <tbody>
        @forelse($items as $f)
            <tr>
                <td>{{ $f->id }}</td>
                <td>{{ $f->name }}</td>
                <td>{{ $f->email }}</td>
                <td>
                    <a href="{{ route('faculty_members.edit', $f) }}" class="btn btn-sm btn-secondary">Edit</a>
                    <form action="{{ route('faculty_members.destroy', $f) }}" method="POST" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this faculty?')">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="4">No faculty found.</td></tr>
        @endforelse
        </tbody>
    </table>
    @if(isset($items))
        {{ $items->links() }}
    @endif
</div>
@endsection