<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisIzin;
use Illuminate\Http\Request;

class JenisIzinController extends Controller
{
    public function index(Request $request)
    {
        $query = JenisIzin::query();

        // Filter Search
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%')
                ->orWhere('kategori', 'like', '%' . $request->search . '%');
        }

        // Filter Kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $data = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('pages.admin.jenis_izin.index', [
            'jenis_izins' => $data,
            'filters' => $request->all()
        ]);
    }

    public function create()
    {
        return view('pages.admin.jenis_izin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|in:Penelitian,Praktik Nakes', // Sesuaikan opsi
        ]);

        JenisIzin::create($request->all());

        return redirect()->route('admin.jenis_izin.index')
            ->with('success', 'Jenis Izin berhasil ditambahkan.');
    }

    public function edit(JenisIzin $jenisIzin)
    {
        return view('pages.admin.jenis_izin.edit', compact('jenisIzin'));
    }

    public function update(Request $request, JenisIzin $jenisIzin)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|in:Penelitian,Praktik Nakes',
        ]);

        $jenisIzin->update($request->all());

        return redirect()->route('admin.jenis_izin.index')
            ->with('success', 'Jenis Izin berhasil diperbarui.');
    }

    public function destroy(JenisIzin $jenisIzin)
    {
        // Cek apakah sedang dipakai di arsip (opsional, untuk keamanan data)
        if ($jenisIzin->arsip()->exists()) {
            return back()->with('error', 'Gagal menghapus! Jenis izin ini sedang digunakan dalam data arsip.');
        }

        $jenisIzin->delete();

        return redirect()->route('admin.jenis_izin.index')
            ->with('success', 'Jenis Izin berhasil dihapus.');
    }
}
