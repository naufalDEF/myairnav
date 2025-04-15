@extends('layouts.app')

@section('title', 'Semua Notifikasi')

@section('content')
<div class="container">
    <h2>Semua Notifikasi</h2>

    <ul class="list-group">
        @forelse (Auth::user()->notifications as $notif)
            <li class="list-group-item d-flex justify-content-between align-items-start flex-column">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <span>{{ $notif->data['message'] }}</span>

                    @if($notif->unread())
                    @php
                        $role = Auth::user()->role; // hasilnya: superadmin, admin, user
                    @endphp
                    
                    <form action="{{ route($role . '.notifications.destroy', $notif->id) }}" method="POST" class="ms-2">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">x</button>
                    </form>
                    @endif
                </div>

                <!-- Tampilkan waktu -->
                <div class="mt-2">
                    <small class="text-muted">
                        {{ $notif->created_at->diffForHumans() }} &bull; {{ $notif->created_at->format('d M Y, H:i') }}
                    </small>
                </div>
            </li>
        @empty
            <li class="list-group-item text-muted text-center">Tidak ada notifikasi</li>
        @endforelse
    </ul>
</div>
@endsection
