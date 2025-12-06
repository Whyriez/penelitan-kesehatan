@extends('layouts.layout')
@section('title', 'Dokumen Masuk')

@section('content')
    <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-auto min-h-screen pb-20">
        <div class="max-w-7xl mx-auto space-y-8">

            {{-- STATISTIK CARD --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 fade-in">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-blue-100 text-blue-600 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Dokumen</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 fade-in">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-amber-100 text-amber-600 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Menunggu Validasi</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['pending'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 fade-in">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-green-100 text-green-600 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Tervalidasi</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['valid'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 fade-in">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-red-100 text-red-600 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Perlu Revisi</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['revisi'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FILTER SECTION --}}
            <form method="GET" action="{{ url()->current() }}" id="filter-form">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 fade-in">

                    {{-- Menggunakan Grid 12 Kolom untuk kontrol yang lebih presisi --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-4 items-end">

                        {{-- 1. SEARCH (Mengambil 3 dari 12 bagian) --}}
                        <div class="lg:col-span-3">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Nama / Deskripsi..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
                            </div>
                        </div>

                        {{-- 2. STATUS (Mengambil 3 dari 12 bagian) --}}
                        <div class="lg:col-span-3">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ ($filters['status'] ?? '') == 'pending' ? 'selected' : '' }}>Menunggu Validasi</option>
                                <option value="valid" {{ ($filters['status'] ?? '') == 'valid' ? 'selected' : '' }}>Tervalidasi</option>
                                <option value="revisi" {{ ($filters['status'] ?? '') == 'revisi' ? 'selected' : '' }}>Perlu Revisi</option>
                            </select>
                        </div>

                        {{-- 3. TANGGAL (Mengambil 4 dari 12 bagian - LEBIH LEBAR) --}}
                        <div class="lg:col-span-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Rentang Tanggal</label>
                            <div class="flex items-center gap-2">
                                <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Dari" />
                                <span class="text-gray-400">-</span>
                                <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Sampai" />
                            </div>
                        </div>

                        {{-- 4. TOMBOL (Mengambil 2 dari 12 bagian) --}}
                        <div class="lg:col-span-2 flex gap-2">
                            <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                                Filter
                            </button>
                            <a href="{{ url()->current() }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors border border-gray-200" title="Reset Filter">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                            </a>
                        </div>

                    </div>
                </div>
            </form>

            {{-- TABLE SECTION --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 fade-in overflow-hidden">
                <div class="p-6 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">Daftar Dokumen Masuk</h2>
                        <p class="text-sm text-gray-600 mt-1">
                            Menampilkan {{ $dokumen->firstItem() ?? 0 }} - {{ $dokumen->lastItem() ?? 0 }} dari {{ $dokumen->total() }} data
                        </p>
                    </div>
                    <div>
                        <select name="sort_by" form="filter-form" onchange="this.form.submit()" class="pl-3 pr-8 py-2 text-sm border border-gray-300 rounded-lg bg-white">
                            <option value="newest" {{ ($filters['sort_by'] ?? 'newest') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="oldest" {{ ($filters['sort_by'] ?? '') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                            <option value="name" {{ ($filters['sort_by'] ?? '') == 'name' ? 'selected' : '' }}>Nama A-Z</option>
                        </select>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full hidden md:table">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Judul & Deskripsi</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pengunggah</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Berkas</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Info</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($dokumen as $doc)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-gray-900">{{ $doc->nama }}</div>
                                        <div class="text-xs text-gray-500 mt-1 max-w-xs truncate">{{ $doc->deskripsi }}</div>
                                        <div class="text-xs text-gray-400 mt-1">{{ $doc->created_at->format('d M Y, H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $doc->user->name ?? 'User Terhapus' }}</div>
                                        <div class="text-xs text-gray-500">{{ $doc->user->email ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{-- Dropdown List Files (Fixed Position) --}}
                                        @if(is_array($doc->file))
                                            <div class="relative dropdown-container">
                                                <button type="button" onclick="toggleDropdown('dropdown-admin-{{ $doc->id }}', this)"
                                                        class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    {{ count($doc->file) }} Berkas
                                                    <svg class="ml-2 -mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                                </button>

                                                <div id="dropdown-admin-{{ $doc->id }}" class="dropdown-menu hidden fixed w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-[9999]">
                                                    <div class="py-1">
                                                        @foreach($doc->file as $key => $path)
                                                            <div class="px-4 py-2 text-xs text-gray-700 border-b border-gray-100 last:border-0 flex items-center hover:bg-gray-50">
                                                                <svg class="w-3 h-3 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"></path></svg>
                                                                {{ ucwords(str_replace('_', ' ', $key)) }}
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">Single File</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $config = [
                                                'pending' => ['text' => 'Menunggu', 'class' => 'bg-amber-100 text-amber-800'],
                                                'valid' => ['text' => 'Valid', 'class' => 'bg-green-100 text-green-800'],
                                                'revisi' => ['text' => 'Revisi', 'class' => 'bg-red-100 text-red-800'],
                                            ];
                                            $s = $config[$doc->status] ?? ['text' => $doc->status, 'class' => 'bg-gray-100'];
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $s['class'] }}">{{ $s['text'] }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button data-modal-toggle="detail-modal-{{ $doc->id }}"
                                                class="text-blue-600 hover:text-blue-900 bg-blue-50 px-3 py-1 rounded hover:bg-blue-100 transition-colors flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-.001.03-.002.06-.002.09a.097.097 0 01-.096.095 4.5 4.5 0 01-8.9 0 .097.097 0 01-.096-.095c0-.03.001-.06.002-.09z"></path></svg>
                                            Lihat Detail
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                        Tidak ada dokumen ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- MOBILE VIEW --}}
                    <div class="md:hidden p-4 space-y-4">
                        @foreach($dokumen as $doc)
                        <div class="bg-white border rounded-lg shadow-sm p-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-bold text-gray-900">{{ $doc->nama }}</h3>
                                    <p class="text-xs text-gray-500">{{ $doc->user->name ?? 'N/A' }}</p>
                                </div>
                                @php
                                    $s = $config[$doc->status] ?? ['text' => $doc->status, 'class' => 'bg-gray-100'];
                                @endphp
                                <span class="px-2 py-1 text-xs rounded-full {{ $s['class'] }}">{{ $s['text'] }}</span>
                            </div>
                            <div class="mt-3 text-sm text-gray-600">
                                {{ count(is_array($doc->file) ? $doc->file : []) }} Berkas terlampir.
                            </div>
                            <div class="mt-4 flex justify-end">
                                <button data-modal-toggle="detail-modal-{{ $doc->id }}" class="w-full bg-blue-600 text-white py-2 rounded-lg text-sm font-medium">
                                    Lihat Detail
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $dokumen->links() }}
                </div>
            </div>
        </div>
    </main>

    {{-- MODALS CONTAINER (READ ONLY) --}}
    <div id="modal-container">
        @foreach ($dokumen as $doc)
            <div id="detail-modal-{{ $doc->id }}" class="dokumen-modal fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-70 hidden backdrop-blur-sm">
                <div class="relative w-full max-w-6xl bg-white rounded-xl shadow-2xl flex flex-col h-[90vh]">

                    {{-- Modal Header --}}
                    <div class="flex justify-between items-center p-4 border-b bg-gray-50 rounded-t-xl">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">{{ $doc->nama }}</h3>
                            <p class="text-xs text-gray-500">Oleh: {{ $doc->user->name ?? 'N/A' }} | {{ $doc->created_at->format('d M Y H:i') }}</p>
                        </div>
                        <button data-modal-close="detail-modal-{{ $doc->id }}" class="text-gray-400 hover:text-red-500 p-2 hover:bg-gray-100 rounded-full transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    {{-- Modal Body: Split Layout --}}
                    <div class="flex-1 overflow-hidden flex flex-col md:flex-row">

                        {{-- KIRI: List File & Info --}}
                        <div class="w-full md:w-1/3 border-r border-gray-200 flex flex-col bg-white overflow-y-auto">
                            <div class="p-4 border-b border-gray-100">
                                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Daftar Berkas</h4>
                                <div class="space-y-2">
                                    @if(is_array($doc->file))
                                        @foreach($doc->file as $key => $path)
                                            <button onclick="changePreview('{{ $doc->id }}', '{{ asset('storage/' . $path) }}', '{{ $path }}')"
                                                    class="w-full text-left px-4 py-3 rounded-lg border border-gray-200 hover:border-blue-500 hover:bg-blue-50 transition-all group focus:ring-2 focus:ring-blue-500">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center">
                                                        <span class="bg-blue-100 text-blue-600 p-2 rounded-md mr-3 group-hover:bg-blue-200">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                                        </span>
                                                        <div>
                                                            <p class="text-sm font-semibold text-gray-700 group-hover:text-blue-800">{{ ucwords(str_replace('_', ' ', $key)) }}</p>
                                                            <p class="text-[10px] text-gray-400">Klik untuk preview</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </button>
                                        @endforeach
                                    @else
                                        <div class="p-3 bg-red-50 text-red-600 text-sm rounded">Format file lama (Single String)</div>
                                    @endif
                                </div>
                            </div>

                            <div class="p-4">
                                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Deskripsi Pengajuan</h4>
                                <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg border border-gray-100">
                                    {{ $doc->deskripsi }}
                                </p>
                            </div>
                        </div>

                        {{-- KANAN: Preview Area --}}
                        <div class="w-full md:w-2/3 bg-gray-100 flex flex-col relative">
                            <div class="flex-1 relative overflow-hidden flex items-center justify-center p-4">
                                {{-- Loading State --}}
                                <div id="loader-{{ $doc->id }}" class="absolute inset-0 flex items-center justify-center bg-gray-100 z-10 hidden">
                                    <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                </div>

                                {{-- Iframe Preview --}}
                                <iframe id="preview-frame-{{ $doc->id }}" src="about:blank" class="w-full h-full rounded-lg shadow-sm bg-white border border-gray-200 hidden"></iframe>

                                {{-- Placeholder State (Initial) --}}
                                <div id="placeholder-{{ $doc->id }}" class="text-center text-gray-400">
                                    <svg class="w-16 h-16 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <p class="text-sm">Pilih berkas di sebelah kiri untuk melihat preview</p>
                                </div>

                                {{-- Image Preview Tag (Alternative if not PDF) --}}
                                <img id="preview-img-{{ $doc->id }}" src="" class="max-w-full max-h-full object-contain hidden rounded-lg shadow-sm" />
                            </div>

                            {{-- Action Bar (Download Button) --}}
                            <div class="p-3 bg-white border-t border-gray-200 flex justify-between items-center">
                                <span id="filename-display-{{ $doc->id }}" class="text-xs text-gray-500 italic">Belum ada file dipilih</span>
                                <a id="download-btn-{{ $doc->id }}" href="#" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm font-medium hidden">
                                    Download File Asli &rarr;
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Modal Footer: Hanya Tutup --}}
                    <div class="p-4 border-t bg-gray-50 flex justify-end space-x-3 rounded-b-xl">
                        <button data-modal-close="detail-modal-{{ $doc->id }}" class="px-5 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 shadow-sm">
                            Tutup
                        </button>
                    </div>

                </div>
            </div>
        @endforeach
    </div>

    <style>
        .fade-in { animation: fadeIn 0.3s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        iframe::-webkit-scrollbar { width: 8px; }
        iframe::-webkit-scrollbar-track { background: #f1f1f1; }
        iframe::-webkit-scrollbar-thumb { background: #888; border-radius: 4px; }
    </style>

    <script>
        // Variable global untuk melacak dropdown aktif
        let activeDropdown = null;

        function toggleDropdown(id, btnElement) {
            const dropdown = document.getElementById(id);
            if (activeDropdown === dropdown) {
                closeActiveDropdown();
                return;
            }
            if (activeDropdown) {
                closeActiveDropdown();
            }
            dropdown.classList.remove('hidden');
            activeDropdown = dropdown;

            // Smart Positioning (Fixed)
            const rect = btnElement.getBoundingClientRect();
            dropdown.style.position = 'fixed';
            dropdown.style.width = '200px';

            const spaceBelow = window.innerHeight - rect.bottom;
            const dropdownHeight = dropdown.offsetHeight || 200;

            if (spaceBelow < dropdownHeight) {
                dropdown.style.top = (rect.top - dropdownHeight - 5) + 'px';
                dropdown.style.left = (rect.left - 50) + 'px';
            } else {
                dropdown.style.top = (rect.bottom + 5) + 'px';
                dropdown.style.left = (rect.left - 50) + 'px';
            }
        }

        function closeActiveDropdown() {
            if (activeDropdown) {
                activeDropdown.classList.add('hidden');
                activeDropdown.style.position = '';
                activeDropdown.style.top = '';
                activeDropdown.style.left = '';
                activeDropdown = null;
            }
        }

        function changePreview(docId, fullUrl, filePath) {
            const loader = document.getElementById('loader-' + docId);
            const iframe = document.getElementById('preview-frame-' + docId);
            const img = document.getElementById('preview-img-' + docId);
            const placeholder = document.getElementById('placeholder-' + docId);
            const downloadBtn = document.getElementById('download-btn-' + docId);
            const filenameDisp = document.getElementById('filename-display-' + docId);

            placeholder.classList.add('hidden');
            loader.classList.remove('hidden');
            iframe.classList.add('hidden');
            img.classList.add('hidden');
            downloadBtn.classList.remove('hidden');

            downloadBtn.href = fullUrl;
            filenameDisp.textContent = filePath.split('/').pop();

            const ext = filePath.split('.').pop().toLowerCase();

            if(['jpg', 'jpeg', 'png'].includes(ext)) {
                img.src = fullUrl;
                img.onload = () => {
                    loader.classList.add('hidden');
                    img.classList.remove('hidden');
                };
            } else {
                const viewerUrl = `{{ asset('pdfjs/web/viewer.html') }}?file=${encodeURIComponent(fullUrl)}`;
                iframe.src = viewerUrl;
                iframe.onload = () => {
                    loader.classList.add('hidden');
                    iframe.classList.remove('hidden');
                };
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const modalToggles = document.querySelectorAll('[data-modal-toggle]');
            const modalCloses = document.querySelectorAll('[data-modal-close]');

            modalToggles.forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = btn.getAttribute('data-modal-toggle');
                    document.getElementById(id).classList.remove('hidden');
                });
            });

            modalCloses.forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = btn.getAttribute('data-modal-close');
                    document.getElementById(id).classList.add('hidden');
                    const iframe = document.querySelector(`#${id} iframe`);
                    if(iframe) iframe.src = 'about:blank';
                });
            });

            // Close dropdown on click outside or scroll
            window.addEventListener('click', function(e) {
                if (activeDropdown && !e.target.closest('.dropdown-menu') && !e.target.closest('button[onclick^="toggleDropdown"]')) {
                    closeActiveDropdown();
                }
            });
            window.addEventListener('scroll', closeActiveDropdown, true);
        });
    </script>
@endsection
