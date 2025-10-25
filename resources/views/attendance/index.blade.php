@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Attendance</h2>
    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    @if(!$dbReady)
        <div class="alert alert-warning">Database not ready. Run migrations.</div>
    @endif
    <a href="{{ route('attendance.create') }}" class="btn btn-primary">Record Attendance</a>
    <table class="table mt-3">
        <thead>
            <tr><th>ID</th><th>Faculty</th><th>Schedule</th><th>Date</th><th>Status</th></tr>
        </thead>
        <tbody>
        @forelse($items as $a)
            <tr>
                <td>{{ $a->id }}</td>
                <td>{{ $a->faculty_member_id }}</td>
                <td>{{ $a->schedule_id }}</td>
                <td>{{ $a->date }}</td>
                <td>{{ ucfirst($a->status) }}</td>
            </tr>
        @empty
            <tr><td colspan="5">No attendance records found.</td></tr>
        @endforelse
        </tbody>
    </table>
    @if(isset($items))
        {{ $items->links() }}
    @endif
</div>
@endsection