<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Notifications\DocumentActionNotification;
use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use ZipArchive;
use File;

class DocumentController extends Controller
{
    /**
     * Menampilkan daftar dokumen di Dashboard Admin.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $category = $request->input('category');
        $date = $request->input('date');
        $sort = $request->input('sort');

        $query = Document::query();

        if (!empty($search)) {
            $query->where('title', 'like', "%$search%");
        }
        if (!empty($category)) {
            $query->where('category', $category);
        }
        if (!empty($date)) {
            $query->whereDate('created_at', $date);
        }
        if (!empty($sort)) {
            $query->orderBy('title', $sort);
        } else {
            $query->latest();
        }

        $documents = $query->paginate(10);

        return view('user.documents.index', compact('documents', 'search', 'category', 'date', 'sort'));
    }

    public function show($id)
    {
        $document = Document::findOrFail($id);
        $document->file_url = Storage::url($document->file_path);

        return view('user.documents.show', compact('document'));
    }

    public function bulkDownload(Request $request)
    {
        $documentIds = $request->input('document_ids');

        if (!$documentIds || count($documentIds) === 0) {
            return response()->json(['error' => 'Tidak ada dokumen yang dipilih.'], 400);
        }

        if (count($documentIds) > 20) {
            return response()->json(['error' => 'Maksimal 20 file dapat diunduh dalam satu ZIP.'], 400);
        }

        $zipFileName = 'documents_' . time() . '.zip';
        $zipPath = storage_path('app/public/' . $zipFileName);
        $zip = new ZipArchive();

        if ($zip->open($zipPath, ZipArchive::CREATE) !== true) {
            return response()->json(['error' => 'Gagal membuat file ZIP.'], 500);
        }

        $documents = Document::whereIn('id', $documentIds)->get();

        foreach ($documents as $document) {
            $filePath = storage_path('app/public/' . $document->file_path);

            if (file_exists($filePath)) {
                $zip->addFile($filePath, basename($filePath));
            }
        }

        $zip->close();

        return response()->json([
            'success' => 'ZIP berhasil dibuat.',
            'zip_url' => asset('storage/' . $zipFileName)
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $documentIds = $request->input('document_ids');

        if (!$documentIds || count($documentIds) === 0) {
            return response()->json(['error' => 'Tidak ada dokumen yang dipilih.'], 400);
        }

        $documents = Document::whereIn('id', $documentIds)->get();

        foreach ($documents as $document) {
            if (Storage::exists('public/' . $document->file_path)) {
                Storage::delete('public/' . $document->file_path);
            }
            $document->delete();
        }

        return response()->json(['success' => 'Dokumen berhasil dihapus.']);
    }

    public function showCategory($category)
    {
        // Validasi kategori agar hanya 'teknik', 'operasi', atau 'k3'
        if (!in_array($category, ['teknik', 'operasi', 'k3'])) {
            abort(404); // Jika kategori tidak valid, tampilkan halaman 404
        }

        // Arahkan ke view berdasarkan kategori
        return view("user.documents.$category"); // Mengarah ke admin/documents/teknik.blade.php
    }


}
