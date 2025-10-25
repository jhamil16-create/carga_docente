@extends('layouts.app')

@section('content')
<h1>Administrative Dashboard</h1>
<p>The system is scaffolded for departments, subjects, groups, classrooms, schedules, and attendance.</p>
@if(!$dbReady)
    <div style="padding:12px;background:#fff3cd;color:#664d03;border:1px solid #ffecb5;">Database not configured yet. Once migrations run on PostgreSQL, data will appear here.</div>
@endif
<ul>
    <li><a href="{{ route('schedules.index') }}">Manage schedules</a></li>
</ul>
@endsection