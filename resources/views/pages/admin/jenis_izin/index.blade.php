@extends('layouts.layout')
@section('title', 'Kelola Jenis Izin')

@section('content')
    <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-auto">
        <div class="max-w-7xl mx-auto space-y-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Kelola Jenis Izin</h1>
                    <p class="text-gray-600 mt-1">Atur daftar layanan izin yang tersedia</p>
                </div>
                <a href="{{ route('admin.jenis_izin.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors text-sm sm:text-base">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Jenis Izin
                </a>
            </div>

            @if (session('success'))
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg relative"
                     role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <form method="GET" action="{{ route('admin.jenis_izin.index') }}" id="filter-form">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 fade-in">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-2">
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Cari Izin</label>
                            <input type="text" id="search" name="search" placeholder="Nama izin..."
                                   value="{{ $filters['search'] ?? '' }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base"/>
                        </div>
                        <div>
                            <label for="kategori-filter" class="block text-sm font-medium text-gray-700 mb-2">Filter
                                Kategori</label>
                            <select id="kategori-filter" name="kategori"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base">
                                <option value="">Semua Kategori</option>
                                <option
                                    value="Izin Kerja" {{ ($filters['kategori'] ?? '') == 'Izin Kerja' ? 'selected' : '' }}>
                                    Izin Kerja
                                </option>
                                <option
                                    value="Izin Praktek" {{ ($filters['kategori'] ?? '') == 'Izin Praktek' ? 'selected' : '' }}>
                                    Izin Praktek
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end mt-4">
                        <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                            Terapkan Filter
                        </button>
                    </div>
                </div>
            </form>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 fade-in">
                <div class="p-4 sm:p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg sm:text-xl font-semibold text-gray-900">
                            Daftar Jenis Izin
                        </h2>
                        <div class="text-sm text-gray-500">
                            Total: <span id="total-data">{{ $jenis_izins->total() }}</span> data
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full hidden md:table">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama Izin
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kategori
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($jenis_izins as $izin)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $izin->nama }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($izin->kategori == 'Izin Kerja')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                Izin Kerja
                                            </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Izin Praktek
                                            </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-3">
                                        <a href="{{ route('admin.jenis_izin.edit', $izin) }}"
                                           class="text-blue-600 hover:text-blue-900">Edit</a>

                                        <form action="{{ route('admin.jenis_izin.destroy', $izin) }}" method="POST"
                                              onsubmit="return confirm('Hapus jenis izin {{ $izin->nama }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                                    Tidak ada data jenis izin ditemukan.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-gray-200">
                    {{ $jenis_izins->links() }}
                </div>
            </div>
        </div>
    </main>

    <style>
        .fade-in {
            animation: fadeIn 0.3s ease-in-out;
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const katFilter = document.getElementById('kategori-filter');
            if (katFilter) {
                katFilter.addEventListener('change', function () {
                    document.getElementById('filter-form').submit();
                });
            }
        });
    </script>
@endsection
