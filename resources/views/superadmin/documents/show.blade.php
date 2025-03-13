@extends('layouts.app')

@section('title', 'Detail Dokumen')

@section('content')
<div class="container">
    <h1>Detail Dokumen</h1>

    <!-- Tombol Kembali -->
    <a href="{{ route('superadmin.documents.index') }}" class="btn btn-secondary mb-3">← Kembali</a>

    <!-- Menampilkan Metadata Dokumen -->
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">{{ $document->title }}</h4>
            <p><strong>Kategori:</strong> {{ $document->category }}</p>
            <p><strong>Jenis File:</strong> {{ strtoupper($document->file_type) }}</p>
            <p><strong>Diunggah Oleh:</strong> {{ $document->user->name }}</p>
            <p><strong>Tanggal Upload:</strong> {{ $document->created_at->format('d M Y') }}</p>

            <!-- Jika kategori BAB 7, tampilkan SOP & Wilayah -->
            @if($document->category === 'BAB 7')
                <p><strong>Jenis SOP:</strong> {{ $document->sop_type ?? '-' }}</p>
                <p><strong>Wilayah:</strong> {{ $document->region ?? '-' }}</p>
            @endif

            <!-- Menampilkan Catatan (jika ada) -->
            @if(!empty($document->note))
                <p><strong>Catatan:</strong> {{ $document->note }}</p>
            @endif
        </div>
    </div>

    <!-- Preview File -->
    <div class="mt-4">
        <h5>Preview Dokumen</h5>

        @if($document->file_type === 'pdf')
            <!-- Preview PDF -->
            <iframe src="{{ $document->file_url }}" width="100%" height="500px"></iframe>
        @elseif($document->file_type === 'docx')
            <!-- Jika file Word, berikan link download -->
            <p>Dokumen ini adalah file Word (.docx). Silakan download untuk melihat.</p>
            <a href="{{ asset('storage/' . $document->file_path) }}" class="btn btn-primary" download>Download Dokumen</a>
        @endif
    </div>
</div>
@endsection
