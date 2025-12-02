<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\ArsipPenelitianKesehatan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ValidasiDokumenController extends Controller
{
    public function index(Request $request)
    {
        // Query utama: Hanya ambil status pending
        $query = ArsipPenelitianKesehatan::query()->where('status', 'pending');

        // Filter Pencarian
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $request->search . '%')
                    ->orWhereHas('user', function ($u) use ($request) {
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
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'name':
                $query->orderBy('nama', 'asc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
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

        return view('pages.operator.validasi_dokumen.index', [
            'dokumen' => $dokumenPaginator,
            'stats' => $stats,
            'filters' => $request->all()
        ]);
    }

    public function validasi(ArsipPenelitianKesehatan $arsip)
    {
        $now = Carbon::now();

        // 1. Generate Nomor Izin Otomatis
        $nomorBaru = $this->generateNomorIzin($arsip, $now);

        // 2. Update Data
        $arsip->update([
            'status' => 'valid',
            'catatan_revisi' => null,
            'tgl_terbit' => $now->toDateString(), // Isi Tanggal Terbit Hari Ini
            'nomor_izin' => $nomorBaru,           // Isi Nomor Izin
        ]);

        return redirect()->route('operator.validasi_dokumen')
            ->with('success', 'Dokumen berhasil divalidasi. Nomor Izin Terbit: ' . $nomorBaru);
    }

    public function revisi(Request $request, ArsipPenelitianKesehatan $arsip)
    {
        $request->validate([
            'catatan_revisi' => 'required|string|min:5',
        ], [
            'catatan_revisi.required' => 'Catatan revisi wajib diisi.',
            'catatan_revisi.min' => 'Catatan revisi terlalu pendek.',
        ]);

        $arsip->update([
            'status' => 'revisi',
            'catatan_revisi' => $request->catatan_revisi
        ]);

        return redirect()->route('operator.validasi_dokumen')
            ->with('success', 'Dokumen "' . $arsip->nama . '" telah dikembalikan untuk revisi.');
    }

    private function generateNomorIzin($arsip, $date)
    {
        // A. Tentukan Kode Berdasarkan Jenis Izin (Sesuai gambar Anda)
        // Ambil nama jenis izin dari relasi
        $namaIzin = strtoupper($arsip->jenisIzin->nama ?? '');
        $kode = 'SK'; // Default jika tidak dikenal

        if (str_contains($namaIzin, 'PERAWAT')) {
            $kode = 'SIPP';
        } elseif (str_contains($namaIzin, 'DOKTER')) {
            $kode = 'SIPD';
        } elseif (str_contains($namaIzin, 'APOTEKER') || str_contains($namaIzin, 'SIPA')) {
            $kode = 'SIPA';
        } elseif (str_contains($namaIzin, 'BIDAN')) {
            $kode = 'SIPB';
        } elseif (str_contains($namaIzin, 'FISIOTERAPIS')) {
            $kode = 'F'; // Sesuai gambar
        } elseif (str_contains($namaIzin, 'TTK')) {
            $kode = 'SIPTTK'; // Sesuai gambar
        }

        // B. Tentukan Nomor Urut (Auto Increment per Tahun)
        $tahun = $date->year;

        // Cari nomor terakhir yang sudah VALID di tahun ini
        $lastDoc = ArsipPenelitianKesehatan::whereYear('tgl_terbit', $tahun)
            ->whereNotNull('nomor_izin')
            ->where('status', 'valid')
            ->orderBy('tgl_terbit', 'desc')
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = 1; // Default mulai dari 1

        if ($lastDoc) {
            // Format DB: 503/DPMPTSP-BB/SIPP/0042/VIII/2025
            // Kita pecah string berdasarkan '/'
            $parts = explode('/', $lastDoc->nomor_izin);

            // Angka urut ada di index ke-3 (array mulai dari 0)
            // 0: 503, 1: DPMPTSP-BB, 2: KODE, 3: NOMOR, 4: BULAN, 5: TAHUN
            if (isset($parts[3]) && is_numeric($parts[3])) {
                $nextNumber = (int)$parts[3] + 1;
            }
        }

        // Pad dengan 0 agar jadi 4 digit (misal: 1 -> 0001, 42 -> 0042)
        $formattedNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        // C. Tentukan Bulan Romawi
        $bulanRomawi = $this->getRomawi($date->month);

        // D. Gabungkan Menjadi String Final
        // Format: 503/DPMPTSP-BB/[KODE]/[NOMOR]/[ROMAWI]/[TAHUN]
        return "503/DPMPTSP-BB/{$kode}/{$formattedNumber}/{$bulanRomawi}/{$tahun}";
    }

    private function getRomawi($bulan)
    {
        $map = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
            7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];
        return $map[$bulan] ?? 'I';
    }
}
