@extends('layouts.layout')
@section('title', 'Upload Izin Kesehatan')

@section('content')
    <main class="flex-1 p-4 sm:p-6 lg:p-8 bg-gray-50 overflow-x-auto">
        <div class="max-w-7xl mx-auto space-y-8">
            {{-- ... Bagian Alert tetap sama ... --}}

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 fade-in">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                        Pengajuan Izin Kesehatan / Praktik
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">Lengkapi formulir di bawah ini.</p>
                </div>

                <form action="{{ route('user.upload.store') }}" method="POST" enctype="multipart/form-data"
                      class="p-6 space-y-6">
                    @csrf

                    {{-- Error Validation Summary --}}
                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                            <ul class="list-disc list-inside mt-1 text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- 1. Jenis Izin (DROPDOWN DINAMIS) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Izin / Layanan <span
                                class="text-red-500">*</span></label>
                        <select name="jenis_izin_id" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white">
                            <option value="">-- Pilih Jenis Izin --</option>
                            @foreach($jenisIzin as $izin)
                                <option
                                    value="{{ $izin->id }}" {{ old('jenis_izin_id') == $izin->id ? 'selected' : '' }}>
                                    {{ $izin->nama }} ({{ $izin->kategori }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Nama Pengajuan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Judul
                                Penelitian</label>
                            <input type="text" name="nama-dokumen" value="{{ old('nama-dokumen') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Nama Lengkap atau Judul Skripsi">
                        </div>

                        {{-- Nomor Surat (BARU) --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Surat Permohonan <span class="text-red-500">*</span></label>
                            <input type="text" name="nomor-surat" value="{{ old('nomor-surat') }}" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Contoh: 800/RSUD-TK/932/VIII/2025">
                        </div>

                        {{-- Tanggal Surat --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Surat <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal-surat" value="{{ old('tanggal-surat', date('Y-m-d')) }}" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    {{-- Tempat Praktik (BARU) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tempat Praktik / Lokasi Penelitian
                            <span class="text-red-500">*</span></label>
                        <input type="text" name="tempat_praktek" value="{{ old('tempat_praktek') }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Contoh: RSUD Prof. Aloe Saboe atau Puskesmas Kota Tengah">
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan Tambahan</label>
                        <textarea name="deskripsi" rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Catatan...">{{ old('deskripsi') }}</textarea>
                    </div>

                    <hr class="border-gray-200">

                    {{-- 2. Upload Berkas (Looping) --}}
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Kelengkapan Berkas</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($syarat as $key => $label)
                                <div
                                    class="bg-gray-50 p-4 rounded-lg border border-gray-200 hover:border-blue-300 transition-colors">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        {{ $loop->iteration }}. {{ $label }}
                                    </label>
                                    <input type="file" name="dokumen[{{ $key }}]" accept=".pdf,.jpg,.jpeg,.png"
                                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all cursor-pointer">
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" id="submit-btn"
                                class="px-8 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 shadow-lg">
                            <span id="btn-text">Kirim Pengajuan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    {{-- Script hanya untuk animasi tombol submit, preview file sudah dihandle browser --}}
    <script>
        document.querySelector('form').addEventListener('submit', function () {
            const btn = document.getElementById('submit-btn');
            const text = document.getElementById('btn-text');
            btn.disabled = true;
            btn.classList.add('opacity-75', 'cursor-not-allowed');
            text.textContent = 'Sedang Mengupload...';
        });
    </script>

    <style>
        .fade-in {
            animation: fadeIn 0.4s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endsection
