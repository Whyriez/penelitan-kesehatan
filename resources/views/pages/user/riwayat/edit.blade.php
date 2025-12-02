@extends('layouts.layout')
@section('title', 'Update Dokumen Kesehatan')

@section('content')
    <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-auto pb-20">
        <div class="max-w-7xl mx-auto space-y-6">

            {{-- Header Section --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Update / Revisi Dokumen</h1>
                    <p class="text-gray-600 mt-1">Perbaiki data atau lengkapi berkas persyaratan.</p>
                </div>
                <a href="{{ route('user.riwayat') }}" class="text-sm text-sky-600 hover:underline flex items-center">
                    &larr; Kembali ke Riwayat
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

            {{-- Catatan Revisi (Jika Ada) --}}
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
                            <h3 class="text-sm font-bold text-yellow-800">Catatan Revisi Admin:</h3>
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

                            {{-- Nama Dokumen --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Judul Dokumen / Nama
                                    Pemohon</label>
                                <input type="text" name="nama" value="{{ old('nama', $arsip->nama) }}" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            {{-- Nomor Surat (BARU - Ditambahkan Disini) --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Surat
                                    Permohonan</label>
                                <input type="text" name="nomor-surat"
                                       value="{{ old('nomor-surat', $arsip->nomor_surat) }}" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Contoh: 800/RSUD-TK/932/VIII/2025">
                            </div>

                            {{-- Tanggal Surat --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pengajuan</label>
                                <input type="date" name="tanggal-surat"
                                       value="{{ old('tanggal-surat', $arsip->tgl_surat ? $arsip->tgl_surat->format('Y-m-d') : '') }}"
                                       required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            {{-- Tempat Praktik (NEW) --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tempat Praktik / Lokasi
                                    Penelitian</label>
                                <input type="text" name="tempat_praktek"
                                       value="{{ old('tempat_praktek', $arsip->tempat_praktek) }}" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Nama RS / Puskesmas / Klinik">
                            </div>

                            {{-- Deskripsi --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi /
                                    Keterangan</label>
                                <textarea name="deskripsi" rows="3" required
                                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">{{ old('deskripsi', $arsip->deskripsi) }}</textarea>
                            </div>
                        </div>

                        <div>
                            <div
                                class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b pb-2 mb-6 gap-2">
                                <h3 class="text-lg font-bold text-gray-900 flex items-center">
                                    <span
                                        class="bg-blue-100 text-blue-600 w-8 h-8 rounded-full flex items-center justify-center mr-2 text-sm">2</span>
                                    Kelengkapan Berkas
                                </h3>
                                <span class="text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                                    PDF, JPG, PNG (Max 10MB)
                                </span>
                            </div>

                            @php
                                $currentFiles = is_array($arsip->file) ? $arsip->file : [];
                            @endphp

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                @foreach($syarat as $key => $label)
                                    <div
                                        class="border border-gray-200 rounded-lg p-4 hover:border-blue-400 transition-all {{ $errors->has("dokumen.$key") ? 'bg-red-50 border-red-300' : 'bg-gray-50' }}">

                                        <label class="block text-sm font-bold text-gray-800 mb-2">
                                            {{ $label }} <span class="text-red-500">*</span>
                                        </label>

                                        {{-- Status File --}}
                                        <div
                                            class="mb-3 flex items-center justify-between text-sm bg-white p-2 rounded border border-gray-200">
                                            @if(isset($currentFiles[$key]))
                                                <div
                                                    class="flex items-center text-green-600 font-medium overflow-hidden">
                                                    <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="none"
                                                         stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              stroke-width="2"
                                                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <a href="{{ asset('storage/' . $currentFiles[$key]) }}"
                                                       target="_blank" class="hover:underline truncate">
                                                        Lihat File Lama
                                                    </a>
                                                </div>
                                            @else
                                                <span class="text-red-500 text-xs flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                         viewBox="0 0 24 24"><path stroke-linecap="round"
                                                                                   stroke-linejoin="round"
                                                                                   stroke-width="2"
                                                                                   d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    Belum upload
                                                </span>
                                            @endif
                                        </div>

                                        {{-- Input Upload --}}
                                        <input type="file" name="dokumen[{{ $key }}]"
                                               class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer border border-gray-300 rounded-lg bg-white">

                                        <p class="text-[10px] text-gray-500 mt-2 italic">
                                            *Upload hanya jika ingin mengganti/merevisi file ini.
                                        </p>

                                        @error("dokumen.$key")
                                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3 rounded-b-xl">
                        <a href="{{ route('user.riwayat') }}"
                           class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                            Batal
                        </a>
                        <button type="submit"
                                class="px-5 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 shadow-md">
                            Simpan Perubahan & Ajukan Ulang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection
