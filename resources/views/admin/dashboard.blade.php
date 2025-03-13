@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="container">
        <h1>Welcome, {{ Auth::user()->name }}!</h1>
        <p>Ini adalah halaman dashboard Admin.</p>
        
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title">Manajemen Dokumen</h5>
                <p class="card-text">Admin dapat menambahkan, mengedit, dan menghapus dokumen.</p>
                <a href="#" class="btn btn-primary">Kelola Dokumen</a>
            </div>
        </div>
    </div>
@endsection
