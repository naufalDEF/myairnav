<?php

namespace App\Http\Controllers\Admin;


use App\Notifications\DocumentActionNotification;
use App\Http\Controllers\Controller;
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

        return view('admin.documents.index', compact('documents', 'search', 'category', 'date', 'sort'));
    }

    public function show($id)
    {
        $document = Document::findOrFail($id);
        $document->file_url = Storage::url($document->file_path);

        return view('admin.documents.show', compact('document'));
    }

    public function create()
    {
        return view('admin.documents.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'sop_type' => 'nullable|string|in:SOP ATS,SOP PTP,Tidak Keduanya',
            'region' => 'nullable|string',
            'file' => 'required|mimes:pdf,docx|max:5120',
            'note' => 'nullable|string',
        ]);

        $filePath = $request->file('file')->store('documents', 'public');
        $fileType = $request->file('file')->getClientOriginalExtension();

        Document::create([
            'title' => $request->title,
            'category' => $request->category,
            'sop_type' => $request->sop_type,
            'region' => $request->region,
            'file_path' => $filePath,
            'file_type' => $fileType,
            'uploaded_by' => Auth::id(),
            'note' => $request->note,
        ]);
        Auth::user()->notify(new DocumentActionNotification('Dokumen "' . $request->title . '" berhasil diupload.'));

        return redirect()->route('admin.documents.index')->with('success', 'Dokumen berhasil diupload.');
    }

    public function edit($id)
    {
        $document = Document::findOrFail($id);
        return view('admin.documents.edit', compact('document'));
    }

    public function update(Request $request, $id)
    {
        $document = Document::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'sop_type' => 'nullable|string|in:SOP ATS,SOP PTP,Tidak Keduanya',
            'region' => 'nullable|string',
            'file' => 'nullable|mimes:pdf,docx|max:5120',
            'note' => 'nullable|string',
        ]);

        if ($request->hasFile('file')) {
            if (Storage::exists($document->file_path)) {
                Storage::delete($document->file_path);
            }

            $filePath = $request->file('file')->store('documents', 'public');
            $fileType = $request->file('file')->getClientOriginalExtension();

            $document->update([
                'title' => $request->title,
                'category' => $request->category,
                'sop_type' => $request->sop_type,
                'region' => $request->region,
                'file_path' => $filePath,
                'file_type' => $fileType,
                'note' => $request->note,
            ]);
        } else {
            $document->update($request->except('file'));
        }
        Auth::user()->notify(new DocumentActionNotification('Dokumen "' . $document->title . '" berhasil diperbarui.'));


        return redirect()->route('admin.documents.index')->with('success', 'Dokumen berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $document = Document::findOrFail($id);

        if (Storage::exists($document->file_path)) {
            Storage::delete($document->file_path);
        }

        $document->delete();

        Auth::user()->notify(new DocumentActionNotification('Dokumen "' . $document->title . '" berhasil dihapus.'));

        return redirect()->route('admin.documents.index')->with('success', 'Dokumen berhasil dihapus.');
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
        return view("admin.documents.$category"); // Mengarah ke admin/documents/teknik.blade.php
    }

}
