@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')
<div class="container">
    <h1>Tambah User</h1>

    <form action="{{ route('superadmin.users.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Nama</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select name="role" id="role" class="form-control">
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>
        </div>

        <a href="{{route('superadmin.users.index')}}" class="btn btn-secondary">Kembali</a>
        <button type="submit" class="btn btn-success">Tambah User</button>
    </form>
</div>
@endsection
