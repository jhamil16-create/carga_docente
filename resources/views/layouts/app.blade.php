<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="manifest" href="/manifest.json" />
    <title>FICCT Scheduling</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    <style>
        body{font-family:system-ui,Segoe UI,Roboto,Arial,sans-serif;margin:0}
        header{background:#0d6efd;color:#fff;padding:12px}
        nav a{color:#fff;margin-right:12px;text-decoration:none}
        .container{max-width:1100px;margin:20px auto;padding:0 16px}
        footer{margin-top:40px;color:#666;padding:12px 16px;border-top:1px solid #eee}
        .nav-right{float:right}
        .nav-user{margin-right:8px;opacity:0.9}
        .btn-logout{background:transparent;color:#fff;border:1px solid rgba(255,255,255,0.7);padding:6px 10px;border-radius:6px;cursor:pointer}
        .btn-logout:hover{background:rgba(255,255,255,0.15)}
    </style>
</head>
<body>
<header>
    <nav>
        <a href="/">Dashboard</a>
        <a href="/schedules">Schedules</a>
        @auth
            @if(auth()->user()->hasRole('admin'))
                <a href="{{ route('faculty_members.index') }}">Faculty</a>
                <a href="{{ route('users.import.form') }}">Import Users</a>
                <a href="{{ route('attendance.index') }}">Attendance</a>
            @endif
            @if(auth()->user()->hasRole('instructor'))
                <a href="{{ route('attendance.create') }}">Record Attendance</a>
            @endif
            <span class="nav-right">
                <span class="nav-user">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" style="display:inline">
                    @csrf
                    <button type="submit" class="btn-logout">Logout</button>
                </form>
            </span>
        @endauth
        @guest
            <span class="nav-right">
                <a href="{{ route('login') }}">Login</a>
            </span>
        @endguest
    </nav>
</header>
<div class="container">
    @yield('content')
</div>
<footer>
    <small>FICCT Scheduling · Responsive PWA-ready</small>
</footer>
</body>
</html>