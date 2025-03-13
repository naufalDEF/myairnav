<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Nama dokumen
            $table->enum('category', [
                'Pengantar', 'BAB 1', 'BAB 2', 'BAB 3', 'BAB 4', 'BAB 5',
                'BAB 6', 'BAB 7', 'BAB 8', 'BAB 9', 'BAB 10', 'BAB 11', 'BAB 12', 'Penutup', 'Lampiran'
            ]); // Kategori dokumen
            $table->enum('sop_type', ['SOP ATS', 'SOP PTP', 'Tidak Keduanya'])->nullable(); // Khusus BAB 7
            $table->string('region')->nullable(); // Wilayah (Khusus BAB 7)
            $table->string('file_path'); // Path lokasi penyimpanan file
            $table->string('file_type'); // Jenis file (PDF/DOCX)
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade'); // User yang mengupload
            $table->text('note')->nullable(); // Catatan opsional
            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
