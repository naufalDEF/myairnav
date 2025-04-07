@extends('layouts.app')

@section('title', 'Detail Dokumen')

@section('content')
<div class="container">
    <h1>Detail Dokumen</h1>

    <!-- Tombol Kembali -->
    <a href="{{ route('admin.documents.index') }}" class="btn btn-secondary mb-3">← Kembali</a>

    <div class="row">
        <!-- Kolom Kiri: Metadata Dokumen -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ $document->title }}</h4>
                    <p><strong>Kategori:</strong> {{ $document->category }}</p>
                    <p><strong>Jenis File:</strong> {{ strtoupper($document->file_type) }}</p>
                    <p><strong>Diunggah Oleh:</strong> {{ $document->user->name }}</p>
                    <p><strong>Tanggal & Waktu Upload:</strong> {{ $document->created_at->setTimezone('Asia/Jakarta')->format('d M Y - H:i:s') }}</p>

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
        </div>

        <!-- Kolom Kanan: Preview Dokumen -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Preview Dokumen</h5>

                    @if($document->file_type === 'pdf')
                        <!-- Preview PDF -->
                        <iframe src="{{ asset('storage/' . $document->file_path) }}" width="100%" height="600px"></iframe>
                    @elseif($document->file_type === 'docx')
                        <!-- Jika file Word, berikan link download -->
                        <p>Dokumen ini adalah file Word (.docx). Silakan download untuk melihat.</p>
                        <a href="{{ asset('storage/' . $document->file_path) }}" class="btn btn-primary" download>Download Dokumen</a>
                    @else
                        <p>Preview tidak tersedia untuk format ini.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
