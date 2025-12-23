@extends('layouts.layout')
@section('title', 'Upload Izin Kesehatan')

@section('content')
    <main class="flex-1 p-4 sm:p-6 lg:p-8 bg-gray-50 overflow-x-auto">
        <div class="max-w-7xl mx-auto space-y-8">

            {{-- Flash Message Error --}}
            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm">
                    <p class="font-bold">Terjadi Kesalahan</p>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 fade-in">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                        Pengajuan Izin Kesehatan / Praktik
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">Lengkapi formulir di bawah ini atau simpan sebagai draft.</p>
                </div>

                <form action="{{ route('user.upload.store') }}" method="POST" enctype="multipart/form-data"
                      class="p-6 space-y-6">
                    @csrf

                    {{-- Error Validation --}}
                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                            <strong class="block font-bold mb-1">Mohon periksa kembali:</strong>
                            <ul class="list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- 1. Jenis Izin --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Izin / Layanan</label>
                        <select name="jenis_izin_id"
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
                        {{-- Nomor Surat --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Surat Permohonan</label>
                            <input type="text" name="nomor-surat" value="{{ old('nomor-surat') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Contoh: 800/RSUD-TK/932/VIII/2025">
                        </div>

                        {{-- Tanggal Surat --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Surat</label>
                            <input type="date" name="tanggal-surat" value="{{ old('tanggal-surat', date('Y-m-d')) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        {{-- Tempat Praktik --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tempat Praktik</label>
                            <input type="text" name="tempat_praktek" value="{{ old('tempat_praktek') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Contoh: RSUD Prof. Aloe Saboe">
                        </div>
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan Tambahan</label>
                        <textarea name="deskripsi" rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Berikan keterangan singkat...">{{ old('deskripsi') }}</textarea>
                    </div>

                    <hr class="border-gray-200">

                    {{-- 2. Upload Berkas --}}
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Kelengkapan Berkas</h3>
                            <span
                                class="px-3 py-1 bg-blue-50 text-blue-700 text-xs font-semibold rounded-full border border-blue-100">
                                PDF, JPG, PNG (Max 500MB)
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($syarat as $key => $label)
                                @php
                                    // Cek apakah ini file optional (pernyataan_skp ATAU bukti_skp)
                                    $isOptional = in_array($key, ['pernyataan_skp', 'bukti_skp', 'sip_lama']);
                                @endphp
                                <div
                                    class="bg-gray-50 p-4 rounded-lg border border-gray-200 hover:border-blue-300 transition-colors {{ $errors->has('dokumen.'.$key) ? 'border-red-300 bg-red-50' : '' }}">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        {{ $loop->iteration }}. {{ $label }}

                                        {{-- Tampilkan Bintang Merah HANYA jika TIDAK Optional --}}
                                        @if(!$isOptional)
                                            <span class="text-red-500">*</span>
                                        @endif
                                    </label>

                                    {{-- Input File --}}
                                    <input type="file" name="dokumen[{{ $key }}]" accept=".pdf,.jpg,.jpeg,.png"
                                           {{-- Tambahkan attribut required HANYA jika TIDAK Optional --}}
                                           @if(!$isOptional) required @endif
                                           class="block w-full text-sm text-gray-500
                                           file:mr-4 file:py-2 file:px-4
                                           file:rounded-full file:border-0
                                           file:bg-white file:text-blue-700
                                           file:font-semibold file:shadow-sm
                                           hover:file:bg-blue-50 transition-all cursor-pointer">

                                    {{-- Keterangan Khusus Optional --}}
                                    @if($isOptional)
                                        <p class="text-[11px] text-amber-600 mt-2 font-medium">
                                            ⓘ (Opsional) Upload hanya bagi yang melakukan perpanjangan.
                                        </p>
                                    @endif

                                    @error('dokumen.'.$key)
                                    <p class="text-red-600 text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4 border-t border-gray-100">
                        {{-- Tombol Draft --}}
                        <button type="submit" name="action" value="draft"
                                class="px-6 py-3 bg-gray-500 text-white font-medium rounded-lg hover:bg-gray-600 shadow-sm transition-all flex justify-center items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                            </svg>
                            Simpan Draft
                        </button>

                        {{-- Tombol Submit --}}
                        <button type="submit" name="action" value="submit"
                                class="px-8 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 shadow-lg transition-all flex justify-center items-center">
                            <span id="btn-text">Kirim Pengajuan</span>
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Ambil semua elemen input file di halaman ini
            const fileInputs = document.querySelectorAll('input[type="file"]');

            // Batas ukuran 500MB dalam bytes
            // 500 * 1024 * 1024 = 524,288,000 bytes
            const MAX_SIZE = 500 * 1024 * 1024;

            fileInputs.forEach(input => {
                input.addEventListener('change', function () {
                    // Cek apakah ada file yang dipilih
                    if (this.files && this.files.length > 0) {
                        const file = this.files[0];
                        const fileSize = file.size;
                        const fileType = file.type;

                        // Validasi 1: Ukuran File (Max 500MB)
                        if (fileSize > MAX_SIZE) {
                            // Reset input agar file tidak jadi ter-upload
                            this.value = '';

                            Swal.fire({
                                icon: 'error',
                                title: 'File Terlalu Besar!',
                                text: 'Ukuran file maksimal adalah 500MB. File Anda berukuran ' + (fileSize / (1024 * 1024)).toFixed(2) + 'MB.',
                                confirmButtonColor: '#2563EB', // Sesuai warna biru tema
                                confirmButtonText: 'Mengerti'
                            });
                            return;
                        }

                        // Validasi 2: Tipe File (PDF, JPG, JPEG, PNG)
                        // Browser modern biasanya sudah memfilter lewat atribut accept="",
                        // tapi ini double check jika user memaksa 'All Files'
                        const allowedTypes = ['application/pdf', 'image/jpeg', 'image/png', 'image/jpg'];

                        // Note: Kadang MIME type bisa berbeda tergantung OS, jadi kita cek ekstensi juga sebagai cadangan
                        const fileName = file.name.toLowerCase();
                        const isValidExtension = fileName.endsWith('.pdf') || fileName.endsWith('.jpg') || fileName.endsWith('.jpeg') || fileName.endsWith('.png');

                        if (!allowedTypes.includes(fileType) && !isValidExtension) {
                            this.value = ''; // Reset input

                            Swal.fire({
                                icon: 'warning',
                                title: 'Format Tidak Sesuai',
                                text: 'Harap unggah file dengan format PDF, JPG, atau PNG.',
                                confirmButtonColor: '#2563EB',
                                confirmButtonText: 'Oke'
                            });
                        }
                    }
                });
            });
        });
    </script>
@endsection
