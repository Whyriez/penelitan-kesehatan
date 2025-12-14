@extends('layouts.layout')
@section('title', 'Izin Kesehatan - Update Dokumen Kesehatan')

@section('content')
    <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-auto pb-20">
        <div class="max-w-7xl mx-auto space-y-6">

            {{-- Header Section --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    {{-- Judul dinamis tergantung status --}}
                    @if($arsip->status == 'draft')
                        <h1 class="text-2xl font-bold text-gray-900">Lanjutkan Pengajuan (Draft)</h1>
                        <p class="text-gray-600 mt-1">Lengkapi data dan berkas Anda sebelum dikirim.</p>
                    @else
                        <h1 class="text-2xl font-bold text-gray-900">Revisi Dokumen</h1>
                        <p class="text-gray-600 mt-1">Perbaiki data atau lengkapi berkas sesuai catatan admin.</p>
                    @endif
                </div>
                <a href="{{ route('user.riwayat') }}" class="text-sm text-sky-600 hover:underline flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Riwayat
                </a>
            </div>

            {{-- Error Validation --}}
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm">
                    <p class="font-bold">Gagal Menyimpan!</p>
                    <ul class="mt-1 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Catatan Revisi (Hanya muncul jika status REVISI) --}}
            @if ($arsip->status == 'revisi' && $arsip->catatan_revisi)
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                      d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-bold text-yellow-800">Permintaan Revisi:</h3>
                            <div class="mt-1 text-sm text-yellow-700 bg-yellow-100 p-2 rounded">
                                {{ $arsip->catatan_revisi }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 fade-in">
                <form action="{{ route('user.dokumen.update', $arsip) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <div class="p-6 space-y-8">
                        {{-- Bagian 1: Informasi Umum --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2 pb-2 border-b">
                                <h3 class="text-lg font-bold text-gray-900 flex items-center">
                                    <span
                                        class="bg-blue-100 text-blue-600 w-8 h-8 rounded-full flex items-center justify-center mr-2 text-sm">1</span>
                                    Informasi Pengajuan
                                </h3>
                            </div>

                            {{-- Dropdown Jenis Izin --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Izin / Layanan</label>
                                <select name="jenis_izin_id" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                    @foreach($jenisIzin as $izin)
                                        <option
                                            value="{{ $izin->id }}" {{ old('jenis_izin_id', $arsip->jenis_izin_id) == $izin->id ? 'selected' : '' }}>
                                            {{ $izin->nama }} ({{ $izin->kategori }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Judul --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Judul Dokumen / Nama
                                    Pemohon</label>
                                <input type="text" name="nama" value="{{ old('nama', $arsip->nama) }}" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            {{-- Nomor Surat --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Surat
                                    Permohonan</label>
                                <input type="text" name="nomor-surat"
                                       value="{{ old('nomor-surat', $arsip->nomor_surat) }}" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            {{-- Tanggal Surat --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pengajuan</label>
                                <input type="date" name="tanggal-surat"
                                       value="{{ old('tanggal-surat', $arsip->tgl_surat ? $arsip->tgl_surat->format('Y-m-d') : '') }}"
                                       required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            {{-- Tempat Praktik --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tempat Praktik / Lokasi
                                    Penelitian</label>
                                <input type="text" name="tempat_praktek"
                                       value="{{ old('tempat_praktek', $arsip->tempat_praktek) }}" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            {{-- Deskripsi --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi /
                                    Keterangan</label>
                                <textarea name="deskripsi" rows="3" required
                                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">{{ old('deskripsi', $arsip->deskripsi) }}</textarea>
                            </div>
                        </div>

                        {{-- Bagian 2: Kelengkapan Berkas --}}
                        <div>
                            <div
                                class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b pb-2 mb-6 gap-2">
                                <h3 class="text-lg font-bold text-gray-900 flex items-center">
                                    <span
                                        class="bg-blue-100 text-blue-600 w-8 h-8 rounded-full flex items-center justify-center mr-2 text-sm">2</span>
                                    Kelengkapan Berkas
                                </h3>
                                <span class="text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                                    PDF, JPG, PNG (Max 500MB)
                                </span>
                            </div>

                            @php
                                $currentFiles = is_array($arsip->file) ? $arsip->file : [];

                                // LOGIKA PENGUNCIAN FILE
                                $filesToRevise = $arsip->file_revisi ?? [];
                                $isRevisi = $arsip->status == 'revisi';
                                $isDraft = $arsip->status == 'draft';
                            @endphp

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                @foreach($syarat as $key => $label)
                                    @php
                                        $filesToRevise = $arsip->file_revisi ?? [];
                                        $isRevisi = $arsip->status == 'revisi';
                                        $isDraft = $arsip->status == 'draft';

                                        $canEdit = $isDraft || !$isRevisi || ($isRevisi && in_array($key, $filesToRevise));
                                        $hasFile = isset($currentFiles[$key]);

                                        // Cek Optional
                                        $isOptional = ($key === 'pernyataan_skp');
                                    @endphp

                                    <div
                                        class="border rounded-lg p-4 transition-all {{ $canEdit ? ($errors->has("dokumen.$key") ? 'bg-red-50 border-red-300' : 'bg-white border-gray-200 hover:border-blue-400') : 'bg-gray-50 border-gray-200 opacity-80' }}">

                                        <div class="flex justify-between items-start mb-2">
                                            <label class="block text-sm font-bold text-gray-800">
                                                {{ $label }}
                                            </label>

                                            @if ($isRevisi)
                                                @if ($canEdit)
                                                    <span
                                                        class="px-2 py-0.5 bg-red-100 text-red-700 text-[10px] font-bold uppercase rounded border border-red-200">Perlu Revisi</span>
                                                @else
                                                    <span
                                                        class="px-2 py-0.5 bg-green-100 text-green-700 text-[10px] font-bold uppercase rounded border border-green-200 flex items-center">Sudah Benar</span>
                                                @endif
                                            @elseif($isDraft)
                                                @if(!$hasFile)
                                                    {{-- Jika file optional tidak ada, jangan beri label merah "Belum Upload" --}}
                                                    @if(!$isOptional)
                                                        <span
                                                            class="text-[10px] text-red-500 font-semibold bg-red-50 px-2 py-0.5 rounded">* Belum Upload</span>
                                                    @else
                                                        <span
                                                            class="text-[10px] text-gray-400 font-semibold bg-gray-50 px-2 py-0.5 rounded">Opsional</span>
                                                    @endif
                                                @else
                                                    <span
                                                        class="text-[10px] text-blue-600 font-semibold bg-blue-50 px-2 py-0.5 rounded">Terupload</span>
                                                @endif
                                            @endif
                                        </div>

                                        {{-- Link File Existing --}}
                                        <div
                                            class="mb-3 flex items-center justify-between text-sm bg-white p-2 rounded border border-gray-100">
                                            @if($hasFile)
                                                <div
                                                    class="flex items-center text-blue-600 font-medium overflow-hidden">
                                                    <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="none"
                                                         stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              stroke-width="2"
                                                              d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                                    </svg>
                                                    <a href="{{ asset('storage/' . $currentFiles[$key]) }}"
                                                       target="_blank" class="hover:underline truncate text-xs">Lihat
                                                        File Terupload</a>
                                                </div>
                                            @else
                                                <span class="text-gray-400 text-xs italic">Belum ada file.</span>
                                            @endif
                                        </div>

                                        {{-- Input File --}}
                                        @if ($canEdit)
                                            <input type="file" name="dokumen[{{ $key }}]"
                                                   class="block w-full text-xs text-gray-500
                                                file:mr-4 file:py-2 file:px-4
                                                file:rounded-full file:border-0
                                                file:text-xs file:font-semibold
                                                file:bg-blue-50 file:text-blue-700
                                                hover:file:bg-blue-100 cursor-pointer border border-gray-300 rounded-lg bg-white">

                                            {{-- Keterangan Khusus Optional --}}
                                            @if($isOptional)
                                                <p class="text-[10px] text-amber-600 mt-2 font-medium">
                                                    ⓘ (Opsional) Upload jika perpanjangan.
                                                </p>
                                            @else
                                                <p class="text-[10px] text-gray-500 mt-2 italic">
                                                    *{{ $isDraft ? 'Upload file (Max 500MB)' : 'Silakan upload file perbaikan (Max 500MB)' }}
                                                </p>
                                            @endif

                                            @error("dokumen.$key")
                                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        @else
                                            <p class="text-[10px] text-green-600 italic mt-1">File ini dikunci.</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3 rounded-b-xl">
                        <a href="{{ route('user.riwayat') }}"
                           class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                            Batal
                        </a>

                        @if($isDraft)
                            {{-- Jika status Draft: Tampilkan tombol Simpan Draft & Kirim --}}
                            {{-- Tombol ini akan menghandle logika di controller update jika Anda menambahkan logika request('action') nanti --}}
                            {{-- Karena controller update saat ini otomatis cek kelengkapan, tombol submit biasa cukup, tapi kita kasih visual pembeda --}}

                            <button type="submit" name="action" value="draft"
                                    class="px-5 py-2.5 bg-gray-500 text-white font-medium rounded-lg hover:bg-gray-600 shadow-sm transition-all">
                                Simpan Draft Lagi
                            </button>

                            <button type="submit" name="action" value="submit"
                                    class="px-5 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 shadow-md transition-all">
                                Kirim Pengajuan
                            </button>
                        @else
                            {{-- Jika status Revisi / Pending --}}
                            <button type="submit"
                                    class="px-5 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 shadow-md">
                                Simpan Perubahan & Ajukan Ulang
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection
