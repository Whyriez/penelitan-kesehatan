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
    public function storeUpload(Request $request)
    {
        $validated = $request->validate([
            'nama-dokumen' => 'required|string|max:255',
            'tanggal-upload' => 'required|date',
            'deskripsi' => 'required|string',
            'file-input' => 'required|file|mimes:pdf,doc,docx|max:10240', // 10MB
        ], [
            'nama-dokumen.required' => 'Nama dokumen wajib diisi.',
            'file-input.required' => 'File dokumen wajib di-upload.',
            'file-input.mimes' => 'Format file harus PDF, DOC, atau DOCX.',
            'file-input.max' => 'Ukuran file tidak boleh lebih dari 10MB.',
        ]);

        try {
            $file = $request->file('file-input');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('dokumen_arsip', $namaFile, 'public');

            ArsipPenelitianKesehatan::create([
                'user_id' => Auth::id(),
                'nama' => $validated['nama-dokumen'],
                'deskripsi' => $validated['deskripsi'],
                'tgl_upload' => $validated['tanggal-upload'],
                'file' => $path,
                'status' => 'pending',
            ]);

            return redirect()->route('user.riwayat')
                ->with('success', 'Dokumen berhasil di-upload!');
        } catch (\Exception $e) {
            Log::error('Error uploading document: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan pada server. Coba lagi nanti.');
        }
    }

    public function edit(ArsipPenelitianKesehatan $arsip)
    {
        if ($arsip->user_id !== Auth::id()) {
            abort(403, 'Anda tidak diizinkan mengedit dokumen ini.');
        }

        return view('pages.user.riwayat.edit', [
            'arsip' => $arsip
        ]);
    }

    public function update(Request $request, ArsipPenelitianKesehatan $arsip)
    {
        if ($arsip->user_id !== Auth::id()) {
            abort(403, 'Anda tidak diizinkan mengupdate dokumen ini.');
        }

        if (!in_array($arsip->status, ['pending', 'revisi', 'ditolak'])) {
            return redirect()->route('user.riwayat')
                ->with('error', 'Dokumen yang sudah divalidasi tidak dapat diubah.');
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:10240' // file opsional
        ]);

        try {
            $arsip->nama = $validated['nama'];
            $arsip->deskripsi = $validated['deskripsi'];

            if ($request->hasFile('file')) {
                $oldFilePath = $arsip->file;

                $file = $request->file('file');
                $namaFile = time() . '_' . $file->getClientOriginalName();
                $newPath = $file->storeAs('dokumen_arsip', $namaFile, 'public');

                $arsip->file = $newPath;

                if ($oldFilePath && Storage::disk('public')->exists($oldFilePath)) {
                    Storage::disk('public')->delete($oldFilePath);
                }
            }

            $arsip->status = 'pending';
            $arsip->save();

            return redirect()->route('user.riwayat')
                ->with('success', 'Dokumen berhasil diperbarui dan dikirim ulang untuk validasi.');
        } catch (\Exception $e) {
            Log::error('Update Dokumen Gagal (ID: ' . $arsip->id . '): ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan pada server. Silakan coba lagi.');
        }
    }
}
