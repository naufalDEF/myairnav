@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')
<div class="container">
    <h1>Manajemen User</h1>

    <!-- Tombol Tambah User -->
    <a href="{{ route('superadmin.users.create') }}" class="btn btn-success mb-3">Tambah User</a>

    <!-- Notifikasi Sukses -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Form Search & Filter -->
    <form action="{{ route('superadmin.users.index') }}" method="GET" class="mb-4">
        <div class="row">
            <!-- Input Search -->
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Cari nama atau email..." value="{{ request('search') }}">
            </div>

            <!-- Filter Role -->
            <div class="col-md-3">
                <select name="role" class="form-control">
                    <option value="">-- Semua Role --</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                </select>
            </div>

            <!-- Tombol Submit -->
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Cari</button>
            </div>
        </div>
    </form>

    <!-- Tabel Manajemen User -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $key => $user)
                <tr>
                    <td>{{ $users->firstItem() + $key }}</td> <!-- Gunakan firstItem() karena sudah pakai paginate -->
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ ucfirst($user->role) }}</td>
                    <td>
                        <a href="{{ route('superadmin.users.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('superadmin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data user</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $users->links('pagination::bootstrap-5') }}
    </div>

</div>
@endsection
