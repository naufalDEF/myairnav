<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    /**
     * Menampilkan daftar dokumen di Dashboard Superadmin.
     */
    public function index(Request $request)
    {
        // Ambil data input dari search dan filter
        $search = $request->input('search');
        $category = $request->input('category');
        $date = $request->input('date');

        // Query dokumen
        $query = Document::query();

        // Filter berdasarkan pencarian judul
        if (!empty($search)) {
            $query->where('title', 'like', "%$search%");
        }

        // Filter berdasarkan kategori
        if (!empty($category)) {
            $query->where('category', $category);
        }

        // Filter berdasarkan tanggal upload
        if (!empty($date)) {
            $query->whereDate('created_at', $date);
        }

        // Ambil hasil query
        $documents = $query->latest()->paginate(10);

        return view('superadmin.documents.index', compact('documents', 'search', 'category', 'date'));
    }

    public function show($id)
    {
        $document = Document::findOrFail($id); // Ambil dokumen berdasarkan ID

        // Pastikan file dapat diakses
        $document->file_url = Storage::url($document->file_path);

        return view('superadmin.documents.show', compact('document'));
    }




    /**
     * Menampilkan form upload dokumen baru.
     */
    public function create()
    {
        return view('superadmin.documents.create');
    }

    /**
 * Menampilkan form edit dokumen.
 */
    public function edit($id)
    {
        $document = Document::findOrFail($id);
        return view('superadmin.documents.edit', compact('document'));
    }

    /**
     * Menyimpan perubahan dokumen setelah diedit.
     */
    public function update(Request $request, $id)
    {
        $document = Document::findOrFail($id);

        // Validasi input
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'sop_type' => 'nullable|string|in:SOP ATS,SOP PTP,Tidak Keduanya',
            'region' => 'nullable|string',
            'file' => 'nullable|mimes:pdf,docx|max:5120', // File opsional
            'note' => 'nullable|string',
        ]);

        // Cek apakah ada file baru diunggah
        if ($request->hasFile('file')) {
            // Hapus file lama dari storage
            if (Storage::exists($document->file_path)) {
                Storage::delete($document->file_path);
            }

            // Simpan file baru
            $filePath = $request->file('file')->store('documents', 'public');
            $fileType = $request->file('file')->getClientOriginalExtension();

            // Update data dengan file baru
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
            // Update data tanpa mengganti file
            $document->update([
                'title' => $request->title,
                'category' => $request->category,
                'sop_type' => $request->sop_type,
                'region' => $request->region,
                'note' => $request->note,
            ]);
        }

        return redirect()->route('superadmin.documents.index')->with('success', 'Dokumen berhasil diperbarui.');
    }


    /**
     * Menyimpan dokumen yang diunggah.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'sop_type' => 'nullable|string|in:SOP ATS,SOP PTP,Tidak Keduanya',
            'region' => 'nullable|string',
            'file' => 'required|mimes:pdf,docx|max:5120', // Max 5MB
            'note' => 'nullable|string',
        ]);

        // Simpan file ke storage/public/documents agar bisa diakses
        $filePath = $request->file('file')->store('documents', 'public');  
        $fileType = $request->file('file')->getClientOriginalExtension();

        // Simpan metadata ke database
        Document::create([
            'title' => $request->title,
            'category' => $request->category,
            'sop_type' => $request->sop_type,
            'region' => $request->region,
            'file_path' => $filePath, // Path sudah benar
            'file_type' => $fileType,
            'uploaded_by' => Auth::id(),
            'note' => $request->note,
        ]);

        return redirect()->route('superadmin.documents.index')->with('success', 'Dokumen berhasil diupload.');
    }

    public function destroy($id)
    {
        $document = Document::findOrFail($id);

        // Hapus file dari storage jika ada
        if (Storage::exists($document->file_path)) {
            Storage::delete($document->file_path);
        }

        // Hapus data dokumen dari database
        $document->delete();

        return redirect()->route('superadmin.documents.index')->with('success', 'Dokumen berhasil dihapus.');
    }


}
