<!DOCTYPE html>
<html>
<head>
    <title>Applicant Dashboard</title>
</head>
<body>
    <h1>Welcome: {{ auth()->user()->name }}</h1>
    <p>Your Role: {{ auth()->user()->role }}</p>
    <p>You are logged in as an Applicant.</p>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>