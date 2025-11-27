<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ArsipPenelitianKesehatan;
use Illuminate\Http\Request;

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

        return view('pages.admin.dokumen_masuk.index', [
            'dokumen' => $dokumenPaginator,
            'stats' => $stats,
            'filters' => $request->all()
        ]);
    }
}