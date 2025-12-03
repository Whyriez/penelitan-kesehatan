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
        $jenisIzin = JenisIzin::all();

        return view('pages.user.upload.index', [
            'syarat' => $this->syaratDokumen,
            'jenisIzin' => $jenisIzin
        ]);
    }

    public function storeUpload(Request $request)
    {
        // Cek Action: 'submit' atau 'draft'
        $isDraft = $request->input('action') === 'draft';

        // Aturan Dasar (Selalu dicek tipe datanya, tapi nullable jika draft)
        $rules = [
            'nama-dokumen' => 'required|string|max:255', // Judul wajib agar bisa diklik di riwayat
            'dokumen.*'    => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:512000', // Validasi file tetap jalan jika diupload
        ];

        // Jika SUBMIT (Bukan Draft), tambahkan aturan 'Required'
        if (!$isDraft) {
            $rules['jenis_izin_id'] = 'required|exists:jenis_izins,id';
            $rules['nomor-surat']   = 'required|string|max:100';
            $rules['tempat_praktek'] = 'required|string|max:255';
            $rules['tanggal-surat'] = 'required|date';
            $rules['deskripsi']     = 'required|string';
            $rules['dokumen']       = 'required|array';
            $rules['dokumen.*']     = 'required|file|mimes:pdf,jpg,jpeg,png|max:512000';
        }

        $request->validate($rules, [
            'dokumen.required' => 'Harap unggah berkas persyaratan untuk mengirim pengajuan.',
            'dokumen.*.max'    => 'Ukuran file maksimal 500MB per file.',
        ]);

        try {
            $filePaths = [];

            // Upload Files (Looping aman meskipun array dokumen kosong saat draft)
            if ($request->hasFile('dokumen')) {
                foreach ($request->file('dokumen') as $key => $file) {
                    $filename = time() . '_' . $key . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('dokumen_kesehatan/' . Auth::id(), $filename, 'public');
                    $filePaths[$key] = $path;
                }
            }

            // Simpan ke Database
            ArsipPenelitianKesehatan::create([
                'user_id' => Auth::id(),
                'jenis_izin_id' => $request->input('jenis_izin_id'),
                'nama' => $request->input('nama-dokumen'),
                'nomor_surat' => $request->input('nomor-surat'),
                'tempat_praktek' => $request->input('tempat_praktek'),
                'tgl_surat' => $request->input('tanggal-surat'),
                'deskripsi' => $request->input('deskripsi'),
                'file' => $filePaths,
                'status' => $isDraft ? 'draft' : 'pending', // Tentukan Status
            ]);

            $msg = $isDraft ? 'Dokumen berhasil disimpan sebagai Draft.' : 'Pengajuan Izin berhasil dikirim!';

            return redirect()->route('user.riwayat')->with('success', $msg);

        } catch (\Exception $e) {
            Log::error('Error upload: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function edit(ArsipPenelitianKesehatan $arsip)
    {
        if ($arsip->user_id !== Auth::id()) abort(403);

        $jenisIzin = JenisIzin::all();

        return view('pages.user.riwayat.edit', [
            'arsip' => $arsip,
            'syarat' => $this->syaratDokumen,
            'jenisIzin' => $jenisIzin
        ]);
    }

    public function update(Request $request, ArsipPenelitianKesehatan $arsip)
    {
        if ($arsip->user_id !== Auth::id()) abort(403);

        // Izinkan update jika status 'pending', 'revisi', ATAU 'draft'
        if (!in_array($arsip->status, ['pending', 'revisi', 'draft'])) {
            return redirect()->route('user.riwayat')->with('error', 'Dokumen terkunci.');
        }

        // Cek apakah user menekan 'Simpan Draft' lagi saat edit, atau 'Kirim'
        // Default ke 'pending' jika tidak ada input action (misal dari form edit biasa yg lama)
        // Kita asumsikan form edit nanti juga punya tombol 'draft' dan 'submit'
        // Tapi untuk menjaga kompatibilitas dengan form edit sebelumnya yang hanya punya 1 tombol submit:
        // Jika status awal adalah DRAFT, dan user submit form edit -> ubah jadi PENDING (Kirim)

        // Logika Validasi Update
        // Jika status sekarang DRAFT, user harus melengkapi semua data untuk mengubah jadi PENDING
        $isDraft = $arsip->status === 'draft';

        $rules = [
            'nama' => 'required|string|max:255',
            'dokumen.*' => 'nullable|file|mimes:pdf,jpg,png|max:512000',
        ];

        // Jika ini adalah FINALISASI DRAFT (mengubah draft jadi pending), wajibkan semua field
        if ($isDraft) {
            $rules['jenis_izin_id'] = 'required|exists:jenis_izins,id';
            $rules['nomor-surat'] = 'required|string|max:100';
            $rules['tempat_praktek'] = 'required|string|max:255';
            $rules['tanggal-surat'] = 'required|date';
            $rules['deskripsi'] = 'required|string';
            // Cek kelengkapan file manual nanti di bawah
        }

        $request->validate($rules);

        try {
            // Update Data Text
            $arsip->jenis_izin_id = $request->input('jenis_izin_id');
            $arsip->nama = $request->input('nama');
            $arsip->nomor_surat = $request->input('nomor-surat');
            $arsip->tempat_praktek = $request->input('tempat_praktek');
            $arsip->tgl_surat = $request->input('tanggal-surat');
            $arsip->deskripsi = $request->input('deskripsi');

            // Update File
            $currentFiles = is_array($arsip->file) ? $arsip->file : [];
            $filesToRevise = $arsip->file_revisi ?? [];
            $isRevisi = $arsip->status == 'revisi';

            if ($request->hasFile('dokumen')) {
                foreach ($request->file('dokumen') as $key => $file) {
                    // Skip jika revisi tapi bukan file yang diminta (dan bukan draft)
                    if ($isRevisi && !in_array($key, $filesToRevise)) continue;

                    if (isset($currentFiles[$key]) && Storage::disk('public')->exists($currentFiles[$key])) {
                        Storage::disk('public')->delete($currentFiles[$key]);
                    }

                    $filename = time() . '_' . $key . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('dokumen_kesehatan/' . Auth::id(), $filename, 'public');
                    $currentFiles[$key] = $path;
                }
            }
            $arsip->file = $currentFiles;

            // STATUS LOGIC
            if ($isDraft) {
                // Jika dari DRAFT, kita cek apakah file sudah lengkap semua?
                $missingFiles = [];
                foreach ($this->syaratDokumen as $key => $label) {
                    if (!isset($currentFiles[$key])) $missingFiles[] = $label;
                }

                if (count($missingFiles) > 0) {
                    // Jika masih ada file kurang, tetap DRAFT (atau bisa return error jika ingin memaksa)
                    // Disini saya pilih: Tetap Simpan, tapi Status tetap Draft dan beri peringatan
                    $arsip->save();
                    return redirect()->route('user.riwayat')->with('warning', 'Data tersimpan, namun status masih DRAFT karena berkas belum lengkap: ' . implode(', ', $missingFiles));
                } else {
                    // Jika file lengkap, ubah jadi PENDING
                    $arsip->status = 'pending';
                }
            }
            elseif ($isRevisi) {
                $arsip->status = 'pending';
                $arsip->file_revisi = null;
            }
            // Jika status 'pending', tetap 'pending' (hanya update data)

            $arsip->save();

            return redirect()->route('user.riwayat')->with('success', 'Dokumen berhasil diperbarui.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }
}
