@extends('layouts.app')

@section('content')
<h1>Create Schedule</h1>
@if($errors->any())
<div style="padding:12px;background:#f8d7da;color:#842029;border:1px solid #f5c2c7;">
    {{ $errors->first() }}
</div>
@endif
@if(session('status'))
<div style="padding:12px;background:#d1e7dd;color:#0f5132;border:1px solid #badbcc;">
    {{ session('status') }}
</div>
@endif
<form method="post" action="{{ route('schedules.store') }}" style="max-width:560px">
    @csrf
    <label>Group ID
        <input type="number" name="group_id" required value="{{ old('group_id') }}">
    </label>
    <br>
    <label>Classroom ID
        <input type="number" name="classroom_id" required value="{{ old('classroom_id') }}">
    </label>
    <br>
    <label>Faculty Member ID
        <input type="number" name="faculty_member_id" required value="{{ old('faculty_member_id') }}">
    </label>
    <br>
    <label>Day of Week
        <input type="number" min="1" max="7" name="day_of_week" required value="{{ old('day_of_week') }}">
    </label>
    <br>
    <label>Start Time
        <input type="time" name="start_time" required value="{{ old('start_time') }}">
    </label>
    <br>
    <label>End Time
        <input type="time" name="end_time" required value="{{ old('end_time') }}">
    </label>
    <br>
    <button type="submit">Validate & Create</button>
</form>
@endsection