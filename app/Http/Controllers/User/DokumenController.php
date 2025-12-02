<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ArsipPenelitianKesehatan;
use App\Models\JenisIzin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DokumenController extends Controller
{
    private $syaratDokumen = [
        'surat_permohonan' => 'Surat Permohonan',
        'str' => 'STR (Surat Tanda Registrasi)',
        'ktp' => 'Foto Copy KTP',
        'bukti_skp' => 'Bukti Kecukupan SKP',
        'pernyataan_skp' => 'Surat Pernyataan Kecukupan SKP',
        'pernyataan_praktek' => 'Surat Pernyataan Tempat Praktek',
        'sisdmk' => 'Bukti Terdaftar pada SISDMK'
    ];

    public function indexUpload()
    {
        // Ambil data Jenis Izin untuk Dropdown
        $jenisIzin = JenisIzin::all();

        return view('pages.user.upload.index', [
            'syarat' => $this->syaratDokumen,
            'jenisIzin' => $jenisIzin // Kirim ke View
        ]);
    }

    public function storeUpload(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'jenis_izin_id' => 'required|exists:jenis_izins,id', // Validasi Dropdown
            'nama-dokumen' => 'required|string|max:255',
            'nomor-surat' => 'required|string|max:100',
            'tempat_praktek' => 'required|string|max:255', // Validasi Tempat
            'tanggal-surat' => 'required|date',
            'deskripsi' => 'required|string',
            'dokumen' => 'required|array',
            'dokumen.*' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        try {
            $filePaths = [];

            // 2. Upload Files
            foreach ($request->file('dokumen') as $key => $file) {
                $filename = time() . '_' . $key . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('dokumen_kesehatan/' . Auth::id(), $filename, 'public');
                $filePaths[$key] = $path;
            }

            // 3. Simpan ke Database
            ArsipPenelitianKesehatan::create([
                'user_id' => Auth::id(),
                'jenis_izin_id' => $request->input('jenis_izin_id'), // Simpan ID Jenis
                'nama' => $request->input('nama-dokumen'),
                'nomor_surat' => $request->input('nomor-surat'),
                'tempat_praktek' => $request->input('tempat_praktek'), // Simpan Tempat
                'tgl_surat' => $request->input('tanggal-surat'),
                'deskripsi' => $request->input('deskripsi'),
                'file' => $filePaths,
                'status' => 'pending',
                // nomor_izin & tgl_terbit biarkan NULL dulu
            ]);

            return redirect()->route('user.riwayat')
                ->with('success', 'Pengajuan Izin berhasil dikirim!');

        } catch (\Exception $e) {
            Log::error('Error upload: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    // Method Edit & Update (Untuk Revisi)
    public function edit(ArsipPenelitianKesehatan $arsip)
    {
        if ($arsip->user_id !== Auth::id()) abort(403);

        // Ambil Data Jenis Izin untuk Dropdown saat Edit
        $jenisIzin = JenisIzin::all();

        return view('pages.user.riwayat.edit', [
            'arsip' => $arsip,
            'syarat' => $this->syaratDokumen,
            'jenisIzin' => $jenisIzin // Kirim variabel ini
        ]);
    }

    public function update(Request $request, ArsipPenelitianKesehatan $arsip)
    {
        if ($arsip->user_id !== Auth::id()) abort(403);

        if (!in_array($arsip->status, ['pending', 'revisi'])) {
            return redirect()->route('user.riwayat')->with('error', 'Dokumen terkunci.');
        }

        // 1. Tambahkan Validasi untuk Field Baru
        $request->validate([
            'jenis_izin_id' => 'required|exists:jenis_izins,id',
            'nama' => 'required|string|max:255',
            'nomor-surat' => 'required|string|max:100',
            'tempat_praktek' => 'required|string|max:255',
            'tanggal-surat' => 'required|date',
            'deskripsi' => 'required|string',
            'dokumen.*' => 'nullable|file|mimes:pdf,jpg,png|max:10240',
        ]);

        try {
            // 2. Update Data Text
            $arsip->jenis_izin_id = $request->input('jenis_izin_id');
            $arsip->nama = $request->input('nama');
            $arsip->nomor_surat = $request->input('nomor-surat');
            $arsip->tempat_praktek = $request->input('tempat_praktek');
            $arsip->tgl_surat = $request->input('tanggal-surat');
            $arsip->deskripsi = $request->input('deskripsi');

            // 3. Update File (Partial Update)
            $currentFiles = is_array($arsip->file) ? $arsip->file : [];

            if ($request->hasFile('dokumen')) {
                foreach ($request->file('dokumen') as $key => $file) {
                    // Hapus file lama jika ada
                    if (isset($currentFiles[$key]) && Storage::disk('public')->exists($currentFiles[$key])) {
                        Storage::disk('public')->delete($currentFiles[$key]);
                    }

                    // Upload file baru
                    $filename = time() . '_' . $key . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('dokumen_kesehatan/' . Auth::id(), $filename, 'public');

                    // Update array path
                    $currentFiles[$key] = $path;
                }
            }

            $arsip->file = $currentFiles;

            // Reset status agar Admin memeriksa ulang revisinya
            $arsip->status = 'pending';
            // Opsional: Kosongkan catatan revisi lama agar bersih
            // $arsip->catatan_revisi = null;

            $arsip->save();

            return redirect()->route('user.riwayat')
                ->with('success', 'Dokumen revisi berhasil dikirim.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }
}
