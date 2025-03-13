<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <!-- Tentukan rute dashboard berdasarkan role -->
        @php
            if (Auth::check()) {
                if (Auth::user()->role === 'superadmin') {
                    $dashboardRoute = route('superadmin.dashboard');
                } elseif (Auth::user()->role === 'admin') {
                    $dashboardRoute = route('admin.dashboard');
                } else {
                    $dashboardRoute = route('user.dashboard');
                }
            } else {
                $dashboardRoute = '#';
            }
        @endphp

        <!-- Brand / Logo yang mengarah ke dashboard sesuai role -->
        <a class="navbar-brand" href="{{ $dashboardRoute }}">MyAirNav</a>

        @if(Auth::check())
            @if(Auth::user()->role === 'superadmin')
                <a class="nav-link text-white ms-3" href="{{ route('superadmin.documents.index') }}">Documents</a>
                <a class="nav-link text-white ms-3" href="{{ route('superadmin.users.index') }}">Kelola Users</a>
            @elseif(Auth::user()->role === 'admin')
                <a class="nav-link text-white ms-3" href="#">Documents</a>
            @elseif(Auth::user()->role === 'user')
                <a class="nav-link text-white ms-3" href="#">Documents</a>
            @endif
        @endif


        <!-- Toggle button for mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu di sebelah kanan -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

    

    <div class="container mt-4">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
