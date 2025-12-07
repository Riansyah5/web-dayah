<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>

<h1>Selamat Datang, {{ Auth::user()->name }}</h1>
<p>Role: {{ Auth::user()->role }}</p>

<hr>

<ul>
    <li><a href="/dashboard">Dashboard</a></li>

    @if(Auth::user()->role === 'Admin')
        <li><a href="/admin-area">Menu Admin</a></li>
    @endif

    @if(Auth::user()->role === 'Guru')
        <li><a href="/guru-area">Menu Guru</a></li>
    @endif
</ul>

<hr>

<form action="{{ route('logout') }}" method="POST">
    @csrf
    <button type="submit">Logout</button>
</form>

</body>
</html>
