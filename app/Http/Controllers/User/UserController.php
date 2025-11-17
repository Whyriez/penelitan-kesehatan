<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ArsipPenelitianKesehatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $stats = [
            'total' => ArsipPenelitianKesehatan::where('user_id', $user->id)->count(),
            'pending' => ArsipPenelitianKesehatan::where('user_id', $user->id)->where('status', 'pending')->count(),
            'valid' => ArsipPenelitianKesehatan::where('user_id', $user->id)->where('status', 'valid')->count(),
            'revision' => ArsipPenelitianKesehatan::where('user_id', $user->id)->whereIn('status', ['revisi', 'ditolak'])->count(),
        ];

        $recentDocuments = ArsipPenelitianKesehatan::where('user_id', $user->id)
            ->orderBy('created_at', 'desc') 
            ->take(5)
            ->get();

        return view('pages.user.index', [
            'user' => $user,
            'stats' => $stats,
            'recentDocuments' => $recentDocuments
        ]);
    }

    public function indexUpload()
    {
        return view('pages.user.upload.index');
    }

    public function indexRiwayat(Request $request)
    {
        $query = ArsipPenelitianKesehatan::where('user_id', Auth::id());

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                    ->orWhere('deskripsi', 'LIKE', "%{$search}%");
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('tgl_upload', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('tgl_upload', '<=', $request->date_to);
        }

        $dokumen = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('pages.user.riwayat.index', [
            'dokumen' => $dokumen,
            'filters' => $request->all()
        ]);
    }
}
