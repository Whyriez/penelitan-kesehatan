@extends('layouts.layout')
@section('title', 'Update Dokumen')

@section('content')
    <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-auto">
        <div class="max-w-7xl mx-auto space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Update Dokumen</h1>
                    <p class="text-gray-600 mt-1">Perbarui detail dokumen Anda</p>
                </div>
                <a href="{{ route('user.riwayat') }}" class="text-sm text-sky-600 hover:underline">
                    &larr; Kembali ke Riwayat
                </a>
            </div>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg relative" role="alert">
                    <strong class="font-bold">Oops! Ada kesalahan:</strong>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($arsip->status == 'revisi' && $arsip->catatan_revisi)
                 <div class="bg-yellow-100 border border-yellow-300 text-yellow-800 px-4 py-3 rounded-lg relative" role="alert">
                    <strong class="font-bold">Catatan Revisi dari Admin:</strong>
                    <p class="mt-2">{{ $arsip->catatan_revisi }}</p>
                </div>
            @endif

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 fade-in">
                <form action="{{ route('user.dokumen.update', $arsip) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    
                    <div class="p-6 space-y-4">
                        <div>
                            <label for="update-nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Dokumen</label>
                            <input type="text" id="update-nama" name="nama" value="{{ old('nama', $arsip->nama) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                required>
                        </div>
                        <div>
                            <label for="update-deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                            <textarea id="update-deskripsi" name="deskripsi" rows="4"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" required>{{ old('deskripsi', $arsip->deskripsi) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">File Saat Ini</label>
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <span class="text-sm text-gray-600">{{ basename($arsip->file) }}</span>
                            </div>
                        </div>
                        <div>
                            <label for="update-file" class="block text-sm font-medium text-gray-700 mb-1">
                                Upload File Baru (Opsional)
                            </label>
                            <input type="file" id="update-file" name="file"
                                class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                            <p class="mt-1 text-xs text-gray-500">
                                Kosongkan jika Anda tidak ingin mengganti file yang sudah ada.
                            </p>
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 border-t text-right space-x-3">
                         <a href="{{ route('user.riwayat') }}" class="px-6 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:ring-4 focus:ring-blue-200">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <style>
        .fade-in { animation: fadeIn 0.3s ease-in-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
@endsection