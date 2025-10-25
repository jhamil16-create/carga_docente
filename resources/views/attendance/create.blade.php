@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Record Attendance</h2>
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('attendance.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Faculty Member</label>
            <select name="faculty_member_id" class="form-select" required>
                <option value="">-- Select --</option>
                @foreach($faculty as $f)
                    <option value="{{ $f->id }}">{{ $f->name }} ({{ $f->email }})</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Schedule</label>
            <select name="schedule_id" class="form-select" required>
                <option value="">-- Select --</option>
                @foreach($schedules as $s)
                    <option value="{{ $s->id }}">Day {{ $s->day_of_week }} {{ $s->start_time }}-{{ $s->end_time }} (Classroom {{ $s->classroom_id }})</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Date</label>
            <input type="date" name="date" class="form-control" value="{{ old('date') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
                <option value="present">Present</option>
                <option value="absent">Absent</option>
                <option value="late">Late</option>
            </select>
        </div>
        <button class="btn btn-primary">Save</button>
        <a href="{{ route('attendance.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection