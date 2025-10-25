@extends('layouts.app')

@section('content')
<h1>Schedules</h1>
<p>@if(!$dbReady) DB not ready. You can still validate conflicts in the create form. @endif</p>
<p><a href="{{ route('schedules.create') }}">Create schedule</a></p>
@if($items->count())
<table border="1" cellpadding="6" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Group</th>
            <th>Classroom</th>
            <th>Faculty</th>
            <th>Day</th>
            <th>Start</th>
            <th>End</th>
        </tr>
    </thead>
    <tbody>
    @foreach($items as $s)
        <tr>
            <td>{{ $s->id }}</td>
            <td>{{ $s->group_id }}</td>
            <td>{{ $s->classroom_id }}</td>
            <td>{{ $s->faculty_member_id }}</td>
            <td>{{ $s->day_of_week }}</td>
            <td>{{ $s->start_time }}</td>
            <td>{{ $s->end_time }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
{{ $items->links() }}
@else
    <p>No schedules yet.</p>
@endif
@endsection