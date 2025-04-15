<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .notif-message {
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            @php
                $role = Auth::user()->role ?? null;

                $dashboardRoute = match ($role) {
                    'superadmin' => route('superadmin.dashboard'),
                    'admin' => route('admin.dashboard'),
                    'user' => route('user.dashboard'),
                    default => '#',
                };

                $documentsMainRoute = match ($role) {
                    'superadmin' => route('superadmin.documents.index'),
                    'admin' => route('admin.documents.index'),
                    'user' => route('user.documents.index'),
                    default => '#',
                };

                $documentsRoutePrefix = match ($role) {
                    'superadmin' => 'superadmin.documents.category',
                    'admin' => 'admin.documents.category',
                    'user' => 'user.documents.category',
                    default => '#',
                };

                $notificationsRoute = route($role . '.notifications.index');
            @endphp

            <a class="navbar-brand" href="{{ $dashboardRoute }}">MyAirNav</a>

            @if(Auth::check())
                <ul class="navbar-nav">
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

                    @if($role === 'superadmin')
                        <li class="nav-item">
                            <a class="nav-link text-white ms-3" href="{{ route('superadmin.users.index') }}">Kelola Users</a>
                        </li>
                    @endif
                </ul>
            @endif

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link position-relative" href="#" id="notifDropdown" role="button" data-bs-toggle="dropdown">
                            🔔
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notif-count">
                                {{ Auth::user()->unreadNotifications->count() }}
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notifDropdown" style="width: 300px; max-height: 300px; overflow-y: auto;">
                            @php
                                $notifLimit = 5;
                                $limitedNotifs = Auth::user()->unreadNotifications->take($notifLimit);
                            @endphp
                            @forelse ($limitedNotifs as $notif)
                                <li class="dropdown-item d-flex justify-content-between align-items-start flex-column">
                                    <div class="d-flex justify-content-between align-items-center w-100">
                                        <span class="notif-message" title="{{ $notif->data['message'] }}">
                                            {{ $notif->data['message'] }}
                                        </span>
                                        <form action="{{ route($role . '.notifications.destroy', $notif->id) }}" method="POST" class="ms-2">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-link text-danger p-0">x</button>
                                        </form>
                                    </div>
                                    <small class="text-muted mt-1" style="font-size: 0.75rem;">
                                        {{ $notif->created_at->diffForHumans() }}
                                    </small>
                                </li>
                            @empty
                                <li class="dropdown-item text-center text-muted">Tidak ada notifikasi</li>
                            @endforelse
                            <li><hr class="dropdown-divider"></li>
                            <li class="text-center"><a href="{{ $notificationsRoute }}">View all</a></li>
                        </ul>
                    </li>

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
