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
        'pernyataan_praktek' => 'Surat Pernyataan Tempat Praktek',
        'pernyataan_skp' => 'Surat Pernyataan Kecukupan SKP'
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
        $isDraft = $request->input('action') === 'draft';

        $rules = [
            'dokumen.*'    => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:512000',
        ];

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
            'dokumen.required' => 'Harap unggah berkas persyaratan.',
            'dokumen.*.max'    => 'Ukuran file maksimal 500MB per file.',
        ]);

        try {
            $filePaths = [];

            if ($request->hasFile('dokumen')) {
                foreach ($request->file('dokumen') as $key => $file) {
                    $filename = time() . '_' . $key . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('dokumen_kesehatan/' . Auth::id(), $filename, 'public');
                    $filePaths[$key] = $path;
                }
            }

            // Cek Manual Kelengkapan jika SUBMIT (Bukan Draft)
            // Karena 'pernyataan_skp' sekarang optional, kita pastikan file WAJIB lainnya ada
            if (!$isDraft) {
                foreach ($this->syaratDokumen as $key => $label) {
                    if ($key === 'pernyataan_skp') continue; // Skip yang optional

                    if (!isset($filePaths[$key])) {
                        return back()->withInput()->withErrors(['dokumen.' . $key => "Dokumen $label wajib diunggah."]);
                    }
                }
            }

            $judulOtomatis = 'Izin Penelitian Kesehatan - ' . Auth::user()->name;
            ArsipPenelitianKesehatan::create([
                'user_id' => Auth::id(),
                'jenis_izin_id' => $request->input('jenis_izin_id'),
                'nama' => $judulOtomatis,
                'nomor_surat' => $request->input('nomor-surat'),
                'tempat_praktek' => $request->input('tempat_praktek'),
                'tgl_surat' => $request->input('tanggal-surat'),
                'deskripsi' => $request->input('deskripsi'),
                'file' => $filePaths,
                'status' => $isDraft ? 'draft' : 'pending',
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

        if (!in_array($arsip->status, ['pending', 'revisi', 'draft'])) {
            return redirect()->route('user.riwayat')->with('error', 'Dokumen terkunci.');
        }

        $isDraft = $arsip->status === 'draft';

        $rules = [
            'nama' => 'required|string|max:255',
            'dokumen.*' => 'nullable|file|mimes:pdf,jpg,png|max:512000',
        ];

        if ($isDraft) {
            $rules['jenis_izin_id'] = 'required|exists:jenis_izins,id';
            $rules['nomor-surat'] = 'required|string|max:100';
            $rules['tempat_praktek'] = 'required|string|max:255';
            $rules['tanggal-surat'] = 'required|date';
            $rules['deskripsi'] = 'required|string';
        }

        $request->validate($rules);

        try {
            $arsip->jenis_izin_id = $request->input('jenis_izin_id');
            $arsip->nama = $request->input('nama');
            $arsip->nomor_surat = $request->input('nomor-surat');
            $arsip->tempat_praktek = $request->input('tempat_praktek');
            $arsip->tgl_surat = $request->input('tanggal-surat');
            $arsip->deskripsi = $request->input('deskripsi');

            $currentFiles = is_array($arsip->file) ? $arsip->file : [];
            $filesToRevise = $arsip->file_revisi ?? [];
            $isRevisi = $arsip->status == 'revisi';

            if ($request->hasFile('dokumen')) {
                foreach ($request->file('dokumen') as $key => $file) {
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

            if ($isDraft) {
                // LOGIKA BARU: Cek kelengkapan file (Draft -> Pending)
                $missingFiles = [];
                foreach ($this->syaratDokumen as $key => $label) {

                    // SKIP Pernyataan SKP agar tidak dianggap 'Kurang'
                    if ($key === 'pernyataan_skp') continue;

                    if (!isset($currentFiles[$key])) $missingFiles[] = $label;
                }

                if (count($missingFiles) > 0) {
                    $arsip->save();
                    return redirect()->route('user.riwayat')->with('warning', 'Data tersimpan sebagai DRAFT. Berkas belum lengkap: ' . implode(', ', $missingFiles));
                } else {
                    $arsip->status = 'pending';
                }
            }
            elseif ($isRevisi) {
                $arsip->status = 'pending';
                $arsip->file_revisi = null;
            }

            $arsip->save();

            return redirect()->route('user.riwayat')->with('success', 'Dokumen berhasil diperbarui.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }
}
