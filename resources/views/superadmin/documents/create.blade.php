@extends('layouts.app')

@section('title', 'Upload Dokumen')

@section('content')
<div class="container">
    <h1>Upload Dokumen Baru</h1>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('superadmin.documents.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Judul Dokumen -->
        <div class="mb-3">
            <label for="title" class="form-label">Judul Dokumen</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>

        <!-- Kategori Dokumen -->
        <div class="mb-3">
            <label for="category" class="form-label">Kategori</label>
            <select class="form-control" id="category" name="category" required onchange="toggleSOPFields()">
                <option value="Pengantar">Pengantar</option>
                <option value="BAB 1">BAB 1</option>
                <option value="BAB 2">BAB 2</option>
                <option value="BAB 3">BAB 3</option>
                <option value="BAB 4">BAB 4</option>
                <option value="BAB 5">BAB 5</option>
                <option value="BAB 6">BAB 6</option>
                <option value="BAB 7">BAB 7 (Standar Pelayanan)</option>
                <option value="BAB 8">BAB 8</option>
                <option value="BAB 9">BAB 9</option>
                <option value="BAB 10">BAB 10</option>
                <option value="BAB 11">BAB 11</option>
                <option value="BAB 12">BAB 12</option>
                <option value="Penutup">Penutup</option>
                <option value="Lampiran">Lampiran</option>
            </select>
        </div>

        <!-- Form Khusus BAB 7 -->
        <div id="sop_fields" style="display: none;">
            <!-- SOP Type -->
            <div class="mb-3">
                <label for="sop_type" class="form-label">Jenis SOP</label>
                <select class="form-control" id="sop_type" name="sop_type">
                    <option value="SOP ATS">SOP ATS</option>
                    <option value="SOP PTP">SOP PTP</option>
                    <option value="Tidak Keduanya">Tidak Keduanya</option>
                </select>
            </div>

            <!-- Region -->
            <div class="mb-3">
                <label for="region" class="form-label">Wilayah</label>
                <input type="text" class="form-control" id="region" name="region" placeholder="Masukkan Wilayah (Pontianak, Sintang, Ketapang, dll.)">
            </div>
        </div>

        <!-- Upload File -->
        <div class="mb-3">
            <label for="file" class="form-label">Upload Dokumen (PDF/DOCX)</label>
            <input type="file" class="form-control" id="file" name="file" required>
        </div>

        <!-- Catatan (Opsional) -->
        <div class="mb-3">
            <label for="note" class="form-label">Catatan (Opsional)</label>
            <textarea class="form-control" id="note" name="note"></textarea>
        </div>

        <a href="{{route('superadmin.documents.index')}}" class="btn btn-secondary">Kembali</a>
        <button type="submit" class="btn btn-primary">Upload</button>
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
</script>

@endsection
