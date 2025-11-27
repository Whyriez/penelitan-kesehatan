@extends('layouts.layout')
@section('title', 'Update Dokumen Kesehatan')

@section('content')
    <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-auto pb-20">
        <div class="max-w-7xl mx-auto space-y-6">
            
            {{-- Header Section --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Update Dokumen</h1>
                    <p class="text-gray-600 mt-1">Perbaiki atau lengkapi berkas persyaratan Anda untuk diajukan ulang.</p>
                </div>
                <a href="{{ route('user.riwayat') }}" class="text-sm text-sky-600 hover:text-sky-700 hover:underline flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Riwayat
                </a>
            </div>

            {{-- Error Validation Alert --}}
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm fade-in" role="alert">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="font-bold">Gagal Menyimpan!</p>
                            <ul class="mt-1 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Catatan Revisi Alert (Penting) --}}
            @if ($arsip->status == 'revisi' && $arsip->catatan_revisi)
                 <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded shadow-sm relative fade-in">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm leading-5 font-bold text-yellow-800">
                                Permintaan Revisi dari Admin
                            </h3>
                            <div class="mt-2 text-sm leading-5 text-yellow-700 bg-yellow-100 p-3 rounded-md border border-yellow-200">
                                <p>{{ $arsip->catatan_revisi }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Form Container --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 fade-in">
                <form action="{{ route('user.dokumen.update', $arsip) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    
                    <div class="p-6 space-y-8">
                        {{-- Bagian 1: Informasi Umum --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-4 flex items-center">
                                    <span class="bg-blue-100 text-blue-600 w-8 h-8 rounded-full flex items-center justify-center mr-2 text-sm">1</span>
                                    Informasi Pengajuan
                                </h3>
                            </div>
                            
                            {{-- Nama Dokumen --}}
                            <div>
                                <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Judul Dokumen</label>
                                <input type="text" id="nama" name="nama" value="{{ old('nama', $arsip->nama) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    placeholder="Contoh: Pengajuan Izin Penelitian 2024"
                                    required>
                            </div>

                            {{-- Deskripsi --}}
                            <div class="md:col-span-2">
                                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi / Keterangan</label>
                                <textarea id="deskripsi" name="deskripsi" rows="3"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                    placeholder="Berikan deskripsi singkat mengenai dokumen ini..."
                                    required>{{ old('deskripsi', $arsip->deskripsi) }}</textarea>
                            </div>
                        </div>

                        {{-- Bagian 2: Kelola File --}}
                        <div>
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b pb-2 mb-6 gap-2">
                                <h3 class="text-lg font-bold text-gray-900 flex items-center">
                                    <span class="bg-blue-100 text-blue-600 w-8 h-8 rounded-full flex items-center justify-center mr-2 text-sm">2</span>
                                    Kelengkapan Berkas
                                </h3>
                                <span class="text-xs font-normal text-gray-500 bg-gray-100 px-3 py-1 rounded-full border border-gray-200">
                                    Format: PDF, JPG, PNG (Max 10MB)
                                </span>
                            </div>

                            @php
                                // Mengambil data file yang tersimpan di database (JSON/Array)
                                $currentFiles = is_array($arsip->file) ? $arsip->file : [];
                            @endphp

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                {{-- Loop berdasarkan daftar syarat dari Controller ($syarat) --}}
                                @foreach($syarat as $key => $label)
                                    <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-400 transition-all {{ $errors->has("dokumen.$key") ? 'bg-red-50 border-red-300' : 'bg-gray-50' }}">
                                        
                                        {{-- Label Dokumen --}}
                                        <label class="block text-sm font-bold text-gray-800 mb-2">
                                            {{ $label }} <span class="text-red-500">*</span>
                                        </label>

                                        {{-- Status File Saat Ini --}}
                                        <div class="mb-3 flex items-center justify-between text-sm bg-white p-2 rounded border border-gray-200">
                                            <span class="text-gray-500 text-xs">Status File:</span>
                                            @if(isset($currentFiles[$key]))
                                                <div class="flex items-center text-green-600 font-medium">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    <a href="{{ asset('storage/' . $currentFiles[$key]) }}" target="_blank" class="hover:underline text-xs sm:text-sm truncate max-w-[150px] sm:max-w-[200px]">
                                                        Lihat File Terupload
                                                    </a>
                                                </div>
                                            @else
                                                <div class="flex items-center text-red-500 font-medium text-xs sm:text-sm">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    Belum ada file
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Input Update (Name harus array: dokumen[key]) --}}
                                        <div class="relative">
                                            <input type="file" name="dokumen[{{ $key }}]" 
                                                class="block w-full text-xs text-gray-500
                                                file:mr-4 file:py-2 file:px-4
                                                file:rounded-full file:border-0
                                                file:text-xs file:font-semibold
                                                file:bg-blue-50 file:text-blue-700
                                                hover:file:bg-blue-100 cursor-pointer 
                                                border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-1 focus:ring-blue-500">
                                        </div>
                                    
                                        <p class="text-[10px] text-gray-500 mt-2 italic">
                                            *Upload file baru hanya jika Anda ingin mengganti file yang lama/revisi.
                                        </p>

                                        @error("dokumen.$key")
                                            <p class="text-red-600 text-xs mt-1 font-medium flex items-center">
                                                <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-end gap-3 rounded-b-xl">
                         <a href="{{ route('user.riwayat') }}" class="w-full sm:w-auto px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors shadow-sm text-center">
                            Batal
                        </a>
                        <button type="submit" class="w-full sm:w-auto px-5 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition-all shadow-md hover:shadow-lg flex justify-center items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            Simpan Perubahan & Ajukan Ulang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <style>
        .fade-in { animation: fadeIn 0.4s ease-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
@endsection