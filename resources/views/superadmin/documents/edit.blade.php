@extends('layouts.app')

@section('title', 'Edit Dokumen')

@section('content')
<div class="container">
    <h2 class="mb-4">Edit Dokumen</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('superadmin.documents.update', $document->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Judul Dokumen -->
        <div class="mb-3">
            <label for="title" class="form-label">Judul Dokumen</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $document->title) }}" required>
        </div>

        <!-- Kategori Dokumen (Menggunakan Select Option) -->
        <div class="mb-3">
            <label for="category" class="form-label">Kategori</label>
            <select class="form-control" id="category" name="category" required onchange="toggleSOPFields()">
                <option value="Pengantar" {{ $document->category == 'Pengantar' ? 'selected' : '' }}>Pengantar</option>
                <option value="BAB 1" {{ $document->category == 'BAB 1' ? 'selected' : '' }}>BAB 1</option>
                <option value="BAB 2" {{ $document->category == 'BAB 2' ? 'selected' : '' }}>BAB 2</option>
                <option value="BAB 3" {{ $document->category == 'BAB 3' ? 'selected' : '' }}>BAB 3</option>
                <option value="BAB 4" {{ $document->category == 'BAB 4' ? 'selected' : '' }}>BAB 4</option>
                <option value="BAB 5" {{ $document->category == 'BAB 5' ? 'selected' : '' }}>BAB 5</option>
                <option value="BAB 6" {{ $document->category == 'BAB 6' ? 'selected' : '' }}>BAB 6</option>
                <option value="BAB 7" {{ $document->category == 'BAB 7' ? 'selected' : '' }}>BAB 7 (Standar Pelayanan)</option>
                <option value="BAB 8" {{ $document->category == 'BAB 8' ? 'selected' : '' }}>BAB 8</option>
                <option value="BAB 9" {{ $document->category == 'BAB 9' ? 'selected' : '' }}>BAB 9</option>
                <option value="BAB 10" {{ $document->category == 'BAB 10' ? 'selected' : '' }}>BAB 10</option>
                <option value="BAB 11" {{ $document->category == 'BAB 11' ? 'selected' : '' }}>BAB 11</option>
                <option value="BAB 12" {{ $document->category == 'BAB 12' ? 'selected' : '' }}>BAB 12</option>
                <option value="Penutup" {{ $document->category == 'Penutup' ? 'selected' : '' }}>Penutup</option>
                <option value="Lampiran" {{ $document->category == 'Lampiran' ? 'selected' : '' }}>Lampiran</option>
            </select>
        </div>

        <!-- Form SOP Type dan Region (Hanya untuk BAB 7) -->
        <div id="sop_fields" style="display: none;">
            <div class="mb-3">
                <label for="sop_type" class="form-label">Jenis SOP</label>
                <select class="form-control" id="sop_type" name="sop_type">
                    <option value="">Pilih SOP</option>
                    <option value="SOP ATS" {{ $document->sop_type == 'SOP ATS' ? 'selected' : '' }}>SOP ATS</option>
                    <option value="SOP PTP" {{ $document->sop_type == 'SOP PTP' ? 'selected' : '' }}>SOP PTP</option>
                    <option value="Tidak Keduanya" {{ $document->sop_type == 'Tidak Keduanya' ? 'selected' : '' }}>Tidak Keduanya</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="region" class="form-label">Wilayah</label>
                <input type="text" class="form-control" id="region" name="region" value="{{ old('region', $document->region) }}">
            </div>
        </div>

        <!-- Catatan -->
        <div class="mb-3">
            <label for="note" class="form-label">Catatan</label>
            <textarea class="form-control" id="note" name="note">{{ old('note', $document->note) }}</textarea>
        </div>

        <!-- File Saat Ini -->
        <div class="mb-3">
            <label class="form-label">File Saat Ini</label>
            <p><a href="{{ Storage::url($document->file_path) }}" target="_blank">{{ $document->file_path }}</a></p>
        </div>

        <!-- Upload File Baru -->
        <div class="mb-3">
            <label for="file" class="form-label">Unggah File Baru (Opsional)</label>
            <input type="file" class="form-control" id="file" name="file">
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('superadmin.documents.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>

<script>
    function toggleSOPFields() {
        var category = document.getElementById("category").value;
        var sopFields = document.getElementById("sop_fields");

        if (category === "BAB 7") {
            sopFields.style.display = "block";
        } else {
            sopFields.style.display = "none";
            document.getElementById("sop_type").value = "";
            document.getElementById("region").value = "";
        }
    }

    // Jalankan fungsi saat halaman dimuat agar form SOP tampil jika kategori sudah BAB 7
    document.addEventListener("DOMContentLoaded", function() {
        toggleSOPFields();
    });
</script>

@endsection
