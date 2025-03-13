<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
{
    Schema::create('document_versions', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('document_id'); // Gunakan unsignedBigInteger
        $table->string('file_path');
        $table->string('file_type');
        $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
        $table->timestamps();

        // Pastikan foreign key sesuai dengan tipe data tabel documents
        $table->foreign('document_id')->references('id')->on('documents')->onDelete('cascade');
    });
}

    public function down(): void
    {
        Schema::dropIfExists('document_versions');
    }
};
