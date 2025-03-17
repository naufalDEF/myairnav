@extends('layouts.app')

@section('title', 'Daftar Dokumen')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Daftar Dokumen</h1>
        <a href="{{ route('superadmin.documents.create') }}" class="btn btn-primary">
            + Tambah Dokumen
        </a>
    </div>

    <!-- Notifikasi jika ada pesan sukses -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Form Pencarian & Filter -->
    <form action="{{ route('superadmin.documents.index') }}" method="GET" class="mb-4">
        <div class="row">
            <!-- Search -->
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan judul..." value="{{ request('search') }}">
            </div>
            
            <!-- Filter Kategori -->
            <div class="col-md-3">
                <select name="category" class="form-control">
                    <option value="">-- Semua Kategori --</option>
                    <option value="Pengantar" {{ request('category') == 'Pengantar' ? 'selected' : '' }}>Pengantar</option>
                    <option value="BAB 1" {{ request('category') == 'BAB 1' ? 'selected' : '' }}>BAB 1</option>
                    <option value="BAB 2" {{ request('category') == 'BAB 2' ? 'selected' : '' }}>BAB 2</option>
                    <option value="BAB 3" {{ request('category') == 'BAB 3' ? 'selected' : '' }}>BAB 3</option>
                    <option value="BAB 4" {{ request('category') == 'BAB 4' ? 'selected' : '' }}>BAB 4</option>
                    <option value="BAB 5" {{ request('category') == 'BAB 5' ? 'selected' : '' }}>BAB 5</option>
                    <option value="BAB 6" {{ request('category') == 'BAB 6' ? 'selected' : '' }}>BAB 6</option>
                    <option value="BAB 7" {{ request('category') == 'BAB 7' ? 'selected' : '' }}>BAB 7</option>
                    <option value="BAB 8" {{ request('category') == 'BAB 8' ? 'selected' : '' }}>BAB 8</option>
                    <option value="BAB 9" {{ request('category') == 'BAB 9' ? 'selected' : '' }}>BAB 9</option>
                    <option value="BAB 10" {{ request('category') == 'BAB 10' ? 'selected' : '' }}>BAB 10</option>
                    <option value="BAB 11" {{ request('category') == 'BAB 11' ? 'selected' : '' }}>BAB 11</option>
                    <option value="BAB 12" {{ request('category') == 'BAB 12' ? 'selected' : '' }}>BAB 12</option>
                    <option value="Penutup" {{ request('category') == 'Penutup' ? 'selected' : '' }}>Penutup</option>
                    <option value="Lampiran" {{ request('category') == 'Lampiran' ? 'selected' : '' }}>Lampiran</option>
                </select>
            </div>

            <!-- Filter Tanggal Upload -->
            <div class="col-md-3">
                <input type="date" name="date" class="form-control" value="{{ request('date') }}">
            </div>

            <!-- Tombol Submit -->
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Cari</button>
            </div>
        </div>
    </form>

    <!-- Tabel Daftar Dokumen -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Judul</th>
                <th>Kategori</th>
                <th>Jenis File</th>
                <th>Diunggah Oleh</th>
                <th>Tanggal Upload</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($documents as $document)
                <tr>
                    <td>
                        <span title="{{ $document->title }}">
                            {{ Str::limit($document->title, 30, '...') }}
                        </span>
                    </td>
                    
                    <td>{{ $document->category }}</td>
                    <td>{{ strtoupper($document->file_type) }}</td>
                    <td>{{ $document->user->name }}</td>
                    <td>{{ $document->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('superadmin.documents.show', $document->id) }}" class="btn btn-sm btn-info">Detail</a>
                        <a href="{{ route('superadmin.documents.edit', $document->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('superadmin.documents.destroy', $document->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokumen ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                        
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $documents->links() }}
    </div>

</div>
@endsection
