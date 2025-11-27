<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ArsipPenelitianKesehatan;
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
        return view('pages.user.upload.index', [
            'syarat' => $this->syaratDokumen
        ]);
    }

    public function storeUpload(Request $request)
    {
        // 1. Validasi Input Dasar
        $request->validate([
            'nama-dokumen' => 'required|string|max:255',
            'tanggal-upload' => 'required|date',
            'deskripsi' => 'required|string',
            'dokumen' => 'required|array', // Array input file
            'dokumen.*' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240', // Validasi tiap file
        ], [
            'dokumen.*.required' => 'Berkas ini wajib diunggah.',
            'dokumen.*.mimes' => 'Format file harus PDF atau Gambar (JPG/PNG).',
            'dokumen.*.max' => 'Ukuran file maksimal 10MB per berkas.',
        ]);

        try {
            $filePaths = [];

            // 2. Loop setiap file yang diupload
            foreach ($request->file('dokumen') as $key => $file) {
                // Generate nama file unik: time_jenis_namaasli
                $filename = time() . '_' . $key . '.' . $file->getClientOriginalExtension();
                
                // Simpan ke storage (folder: dokumen_kesehatan)
                $path = $file->storeAs('dokumen_kesehatan/' . Auth::id(), $filename, 'public');
                
                // Masukkan ke array untuk disimpan di DB
                $filePaths[$key] = $path;
            }

            // 3. Simpan ke Database
            ArsipPenelitianKesehatan::create([
                'user_id' => Auth::id(),
                'nama' => $request->input('nama-dokumen'),
                'deskripsi' => $request->input('deskripsi'),
                'tgl_upload' => $request->input('tanggal-upload'),
                'file' => $filePaths, // Laravel otomatis convert array ke JSON (jika model dicasting)
                'status' => 'pending',
            ]);

            return redirect()->route('user.riwayat')
                ->with('success', 'Pengajuan Izin Kesehatan berhasil dikirim!');

        } catch (\Exception $e) {
            Log::error('Error uploading health document: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }

    // Method Edit & Update (Untuk Revisi)
    public function edit(ArsipPenelitianKesehatan $arsip)
    {
        if ($arsip->user_id !== Auth::id()) abort(403);

        return view('pages.user.riwayat.edit', [
            'arsip' => $arsip,
            'syarat' => $this->syaratDokumen
        ]);
    }

    public function update(Request $request, ArsipPenelitianKesehatan $arsip)
    {
        if ($arsip->user_id !== Auth::id()) abort(403);
        
        // Hanya boleh update jika status pending/revisi
        if (!in_array($arsip->status, ['pending', 'revisi'])) {
            return redirect()->route('user.riwayat')->with('error', 'Dokumen terkunci.');
        }

        $request->validate([
            'nama' => 'required|string',
            'deskripsi' => 'required|string',
            'dokumen.*' => 'nullable|file|mimes:pdf,jpg,png|max:10240',
        ]);

        try {
            $arsip->nama = $request->nama;
            $arsip->deskripsi = $request->deskripsi;

            // Ambil data file lama
            $currentFiles = is_array($arsip->file) ? $arsip->file : [];

            // Cek jika ada file baru yang diupload (Revisi sebagian)
            if ($request->hasFile('dokumen')) {
                foreach ($request->file('dokumen') as $key => $file) {
                    // Hapus file lama fisik jika ada
                    if (isset($currentFiles[$key]) && Storage::disk('public')->exists($currentFiles[$key])) {
                        Storage::disk('public')->delete($currentFiles[$key]);
                    }

                    // Upload file baru
                    $filename = time() . '_' . $key . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('dokumen_kesehatan/' . Auth::id(), $filename, 'public');
                    
                    // Update array
                    $currentFiles[$key] = $path;
                }
            }

            $arsip->file = $currentFiles;
            $arsip->status = 'pending'; // Reset status jadi pending agar admin cek ulang
            $arsip->catatan_revisi = null; // Hapus catatan revisi lama
            $arsip->save();

            return redirect()->route('user.riwayat')
                ->with('success', 'Dokumen revisi berhasil dikirim.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }
}
