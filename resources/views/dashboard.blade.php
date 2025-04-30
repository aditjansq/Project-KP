<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <h1>Welcome to your Dashboard, {{ auth()->user()->name }}!</h1>
    <p>Posisi : {{ Auth()->user()->job }}</p>

    <h2>Recent Login Logs</h2>
    <ul>
        @foreach($loginLogs as $log)
            <li>
                <strong>Login At:</strong> {{ $log->login_at }} <br>
                <strong>IP Address:</strong> {{ $log->ip_address }} <br>
                <strong>User Agent:</strong> {{ $log->user_agent }} <br>
                <hr>
            </li>
        @endforeach
    </ul>

    <!-- Form logout -->
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>
