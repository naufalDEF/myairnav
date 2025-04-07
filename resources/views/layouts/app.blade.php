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
            <!-- Tentukan rute dashboard dan documents berdasarkan role -->
            @php
                if (Auth::check()) {
                    if (Auth::user()->role === 'superadmin') {
                        $dashboardRoute = route('superadmin.dashboard');
                        $documentsMainRoute = route('superadmin.documents.index');
                        $documentsRoutePrefix = 'superadmin.documents.category';
                    } elseif (Auth::user()->role === 'admin') {
                        $dashboardRoute = route('admin.dashboard');
                        $documentsMainRoute = route('admin.documents.index');
                        $documentsRoutePrefix = 'admin.documents.category';
                    } else {
                        $dashboardRoute = route('user.dashboard');
                        $documentsMainRoute = route('user.documents.index');
                        $documentsRoutePrefix = 'user.documents.category';
                    }
                } else {
                    $dashboardRoute = '#';
                    $documentsMainRoute = '#';
                    $documentsRoutePrefix = '#';
                }
            @endphp

            <!-- Brand / Logo -->
            <a class="navbar-brand" href="{{ $dashboardRoute }}">MyAirNav</a>

            @if(Auth::check())
                <ul class="navbar-nav">
                    <!-- Menu Documents (Bisa Diklik + Ada Submenu) -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="{{ $documentsMainRoute }}" id="documentsDropdown" role="button" data-bs-toggle="dropdown">
                            Documents
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ $documentsMainRoute }}">Main Documents</a></li>
                            <li><a class="dropdown-item" href="{{ route($documentsRoutePrefix, ['category' => 'teknik']) }}">Teknik</a></li>
                            <li><a class="dropdown-item" href="{{ route($documentsRoutePrefix, ['category' => 'operasi']) }}">Operasi</a></li>
                            <li><a class="dropdown-item" href="{{ route($documentsRoutePrefix, ['category' => 'k3']) }}">K3</a></li>
                        </ul>
                    </li>

                    @if(Auth::user()->role === 'superadmin')
                        <li class="nav-item">
                            <a class="nav-link text-white ms-3" href="{{ route('superadmin.users.index') }}">Kelola Users</a>
                        </li>
                    @endif
                </ul>
            @endif

            <!-- Toggle button for mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menu Logout -->
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
