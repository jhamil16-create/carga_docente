@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 640px;">
    <h2>Confirm Logout</h2>
    <p>You reached <code>/logout</code> via a GET request. For security, logout must be done using a POST form. Click the button below to end your session and return to the login page.</p>
    <form method="POST" action="{{ route('logout') }}" class="mt-3">
        @csrf
        <button class="btn btn-danger">Logout</button>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary" style="margin-left:8px">Cancel</a>
    </form>
    <hr class="mt-4" />
    <p class="text-muted">Tip: Use the Logout button in the top navigation to avoid this page.</p>
</div>
@endsection