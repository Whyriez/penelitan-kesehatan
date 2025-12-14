@extends('layouts.layout')
@section('title', 'Tambah Jenis Izin')

@section('content')
    <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-auto">
        <div class="max-w-7xl mx-auto space-y-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Tambah Jenis Izin</h1>
                    <p class="text-gray-600 mt-1">Tambahkan layanan izin baru</p>
                </div>
                <a href="{{ route('admin.jenis_izin.index') }}" class="text-sm text-blue-600 hover:underline">
                    &larr; Kembali ke Daftar
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

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 fade-in">
                <form action="{{ route('admin.jenis_izin.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @csrf

                    <div class="md:col-span-2">
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Jenis Izin</label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required
                               placeholder="Contoh: SIP Perawat, Izin Penelitian Skripsi"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </div>

                    <div class="md:col-span-2">
                        <label for="kategori" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <select id="kategori" name="kategori" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                            <option value="">Pilih Kategori</option>
                            <option value="Izin Kerja" {{ old('kategori') == 'Izin Kerja' ? 'selected' : '' }}>Izin Kerja</option>
                            <option value="Izin Praktek" {{ old('kategori') == 'Izin Praktek' ? 'selected' : '' }}>Izin Praktek</option>
                        </select>
                    </div>

                    <div class="md:col-span-2 flex justify-end space-x-3 pt-4 border-t">
                        <a href="{{ route('admin.jenis_izin.index') }}"
                           class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                            Batal
                        </a>
                        <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection
