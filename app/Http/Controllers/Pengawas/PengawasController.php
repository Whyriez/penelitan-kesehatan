<?php

namespace App\Http\Controllers\Pengawas;

use App\Http\Controllers\Controller;
use App\Models\ArsipPenelitianKesehatan;
use Illuminate\Http\Request;
use App\Exports\DokumenMasukExport;
use Maatwebsite\Excel\Facades\Excel;

class PengawasController extends Controller
{
    public function index(Request $request)
    {
        // KUNCI: Hanya ambil yang statusnya 'valid'
        $query = ArsipPenelitianKesehatan::query()->where('status', 'valid');

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

        // Statistik Sederhana (Hanya Total Valid)
        $stats = [
            'total_valid' => ArsipPenelitianKesehatan::where('status', 'valid')->count(),
            'bulan_ini' => ArsipPenelitianKesehatan::where('status', 'valid')
                ->whereMonth('created_at', now()->month)
                ->count(),
        ];

        return view('pages.pengawas.index', [
            'dokumen' => $dokumenPaginator,
            'stats' => $stats,
            'filters' => $request->all()
        ]);
    }

    public function export(Request $request)
    {
        $timestamp = date('d-m-Y_H-i');

        // Panggil Export dengan View Khusus Pengawas (parameter kedua)
        return Excel::download(
            new DokumenMasukExport($request->all(), 'pages.pengawas.exports.rekapan_pengawas'),
            "Rekapan_Penelitian_Kesehatan_Valid_$timestamp.xlsx"
        );
    }
}
