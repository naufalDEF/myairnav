@extends('layouts.app')

@section('title', 'Superadmin Dashboard')

@section('content')
    <div class="container">
        <h1>Welcome, {{ Auth::user()->name }}!</h1>
        <p>Ini adalah halaman dashboard Superadmin.</p>
        
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title">Manajemen Admin</h5>
                <p class="card-text">Superadmin dapat menambahkan dan mengelola akun Users.</p>
                <a href="{{route('superadmin.documents.index')}}" class="btn btn-primary">Lihat Dokumen</a>

            </div>
        </div>
    </div>
@endsection
