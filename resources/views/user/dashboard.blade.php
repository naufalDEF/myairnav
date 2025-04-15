@extends('layouts.app')

@section('title', 'User Dashboard')

@section('content')
    <div class="container">
        <h1>Welcome, {{ Auth::user()->name }}!</h1>
        <p>Ini adalah halaman dashboard User.</p>
        
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title">Lihat Dokumen</h5>
                <p class="card-text">User hanya bisa melihat dan mengunduh dokumen yang tersedia.</p>
                <a href="{{route('user.documents.index')}}" class="btn btn-primary">Lihat Dokumen</a>
            </div>
        </div>
    </div>
@endsection
