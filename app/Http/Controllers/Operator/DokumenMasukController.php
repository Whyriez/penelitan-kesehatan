<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\ArsipPenelitianKesehatan;
use Illuminate\Http\Request;
use App\Exports\DokumenMasukExport;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class DokumenMasukController extends Controller
{
    public function index(Request $request)
    {
        $query = ArsipPenelitianKesehatan::query();

        // Filter Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $request->search . '%')
                    ->orWhereHas('user', function($u) use ($request) {
                        $u->where('name', 'like', '%' . $request->search . '%');
                    });
            });
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter Tanggal
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'newest');
        switch ($sortBy) {
            case 'oldest': $query->orderBy('created_at', 'asc'); break;
            case 'name': $query->orderBy('nama', 'asc'); break;
            case 'newest': default: $query->orderBy('created_at', 'desc'); break;
        }

        $dokumenPaginator = $query->with('user:id,name,email')
            ->paginate(10)
            ->withQueryString();

        // Statistik
        $stats = [
            'total' => ArsipPenelitianKesehatan::count(),
            'pending' => ArsipPenelitianKesehatan::where('status', 'pending')->count(),
            'valid' => ArsipPenelitianKesehatan::where('status', 'valid')->count(),
            'revisi' => ArsipPenelitianKesehatan::where('status', 'revisi')->count(),
        ];

        return view('pages.operator.dokumen_masuk.index', [
            'dokumen' => $dokumenPaginator,
            'stats' => $stats,
            'filters' => $request->all()
        ]);
    }

    public function export(Request $request)
    {
        // Kirim semua request (filter search, date, status) ke Class Export
        $filters = $request->all();

        $timestamp = date('d-m-Y_H-i');
        return Excel::download(new DokumenMasukExport($filters), "Rekapan_Izin_$timestamp.xlsx");
    }

    public function uploadSip(Request $request, ArsipPenelitianKesehatan $arsip)
    {
        // Validasi input
        $request->validate([
            'file_surat_izin' => 'required|mimes:pdf|max:512000', // Wajib PDF, max 500MB
        ], [
            'file_surat_izin.required' => 'File Surat Izin wajib diupload.',
            'file_surat_izin.mimes' => 'Format file harus PDF.',
            'file_surat_izin.max' => 'Ukuran file maksimal 500MB.',
        ]);

        // Simpan File
        if ($request->hasFile('file_surat_izin')) {
            // Hapus file lama jika ada (untuk replace)
            if ($arsip->file_surat_izin) {
                Storage::delete('public/' . $arsip->file_surat_izin);
            }

            // Simpan file baru ke folder 'surat_izin' di storage public
            $path = $request->file('file_surat_izin')->store('surat_izin', 'public');

            // Update database
            $arsip->update([
                'file_surat_izin' => $path
            ]);
        }

        return redirect()->back()->with('success', 'Surat Izin Praktik (SIP) berhasil diterbitkan dan diupload.');
    }
}
