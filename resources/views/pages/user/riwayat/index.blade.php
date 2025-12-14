@extends('layouts.layout')
@section('title', 'Izin Kesehatan - Riwayat Dokumen')

@section('content')
    <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-auto min-h-screen pb-20">
        <div class="max-w-7xl mx-auto space-y-6">
            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Riwayat Dokumen</h1>
                    <p class="text-gray-600 mt-1">Kelola dan pantau status validasi berkas Anda</p>
                </div>
                {{-- Tombol Upload --}}
                <a href="{{ route('user.upload') }}"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors text-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Upload Dokumen Baru
                </a>
            </div>

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-r shadow-sm relative fade-in" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-r shadow-sm relative fade-in" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            {{-- Filter Section --}}
            <form method="GET" action="{{ route('user.riwayat') }}" id="filter-form">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 fade-in">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="md:col-span-2">
                            <label for="search" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Pencarian</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input type="text" id="search" name="search" placeholder="Cari nama dokumen..."
                                       value="{{ $filters['search'] ?? '' }}"
                                       class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition-shadow" />
                            </div>
                        </div>
                        <div>
                            <label for="status-filter" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Status</label>
                            <select id="status-filter" name="status"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm cursor-pointer bg-white">
                                <option value="">Semua Status</option>
                                <option value="draft" {{ ($filters['status'] ?? '') == 'draft' ? 'selected' : '' }}>📝 Draft</option>
                                <option value="pending" {{ ($filters['status'] ?? '') == 'pending' ? 'selected' : '' }}>⏳ Menunggu</option>
                                <option value="valid" {{ ($filters['status'] ?? '') == 'valid' ? 'selected' : '' }}>✅ Valid</option>
                                <option value="revisi" {{ ($filters['status'] ?? '') == 'revisi' ? 'selected' : '' }}>⚠️ Perlu Revisi</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <a href="{{ route('user.riwayat') }}"
                               class="w-full flex justify-center items-center px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors text-sm border border-gray-300">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Reset
                            </a>
                        </div>
                    </div>
                </div>
            </form>

            {{-- Tabel Dokumen --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 fade-in overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full hidden md:table">
                        <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Judul & Deskripsi</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tgl Upload</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">File Berkas</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                        </thead>
                        <tbody id="document-table" class="bg-white divide-y divide-gray-200">
                        @forelse ($dokumen as $doc)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-900">{{ $doc->nama }}</div>
                                    <div class="text-xs text-gray-500 mt-1 max-w-xs truncate">{{ $doc->deskripsi }}</div>
                                    @if ($doc->status == 'revisi' && $doc->catatan_revisi)
                                        <div class="mt-2 text-xs text-red-600 bg-red-50 p-2 rounded border border-red-100">
                                            <strong>Catatan:</strong> {{ $doc->catatan_revisi }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $doc->tgl_surat ? \Carbon\Carbon::parse($doc->tgl_upload)->translatedFormat('d M Y') : 'Draft' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if (is_array($doc->file) && count($doc->file) > 0)
                                        <div class="relative dropdown-container">
                                            <button type="button" onclick="toggleDropdown('dropdown-{{ $doc->id }}', this)"
                                                    class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                {{ count($doc->file) }} Berkas
                                                <svg class="ml-2 -mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                            </button>

                                            {{-- Dropdown Menu --}}
                                            <div id="dropdown-{{ $doc->id }}" class="dropdown-menu hidden absolute left-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-20">
                                                <div class="py-1">
                                                    @foreach ($doc->file as $key => $path)
                                                        <button class="preview-btn w-full text-left px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 hover:text-gray-900 flex items-center"
                                                                data-file-url="{{ url('storage/' . $path) }}"
                                                                data-file-name="{{ ucwords(str_replace('_', ' ', $key)) }}">
                                                            <svg class="mr-2 h-4 w-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                            {{ ucwords(str_replace('_', ' ', $key)) }}
                                                        </button>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Belum ada file</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        // UPDATE 1: Status Map ditambahkan case 'draft'
                                        $statusMap = [
                                            'draft'   => ['text' => 'Draft / Belum Dikirim', 'class' => 'bg-gray-100 text-gray-600 border-gray-300'],
                                            'pending' => ['text' => 'Menunggu Validasi', 'class' => 'bg-yellow-100 text-yellow-800 border-yellow-200'],
                                            'valid'   => ['text' => 'Valid / Diterima', 'class' => 'bg-green-100 text-green-800 border-green-200'],
                                            'revisi'  => ['text' => 'Perlu Revisi', 'class' => 'bg-red-100 text-red-800 border-red-200'],
                                            'ditolak' => ['text' => 'Ditolak', 'class' => 'bg-red-200 text-red-900 border-red-300'],
                                        ];
                                        $s = $statusMap[$doc->status] ?? ['text' => $doc->status, 'class' => 'bg-gray-50 text-gray-500'];
                                    @endphp
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full border {{ $s['class'] }}">
                                            {{ $s['text'] }}
                                        </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    {{-- LOGIKA BARU: Jika Valid DAN File SIP sudah ada --}}
                                    @if ($doc->status == 'valid' && $doc->file_surat_izin)
                                        <a href="{{ asset('storage/' . $doc->file_surat_izin) }}" target="_blank"
                                           class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white text-xs font-medium rounded-lg hover:bg-green-700 transition-colors shadow-sm">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Unduh SIP
                                        </a>

                                        {{-- Jika Draft/Revisi/Pending --}}
                                    @elseif (in_array($doc->status, ['pending', 'revisi', 'ditolak', 'draft']))
                                        <a href="{{ route('user.dokumen.edit', $doc) }}"
                                           class="inline-flex items-center px-3 py-1.5 text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 rounded border border-indigo-200 transition-colors">
                                            {{ $doc->status === 'draft' ? 'Lanjutkan' : 'Revisi' }}
                                        </a>

                                        {{-- Jika Valid tapi operator belum upload file (Terkunci) --}}
                                    @else
                                        <span class="text-gray-400 cursor-not-allowed px-3 py-1 flex items-center bg-gray-50 rounded border border-gray-200" title="Dokumen disetujui, menunggu penerbitan SIP">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            {{ $doc->status == 'valid' ? 'Proses Terbit' : 'Terkunci' }}
        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <p class="text-lg font-medium text-gray-900 mb-2">Belum ada dokumen</p>
                                        <p class="text-gray-600">Dokumen yang disubmit akan muncul di sini</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                    {{-- MOBILE VIEW --}}
                    <div id="document-mobile" class="md:hidden divide-y divide-gray-200">
                        @forelse ($dokumen as $doc)
                            <div class="p-4 space-y-3 bg-white">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="text-sm font-bold text-gray-900">{{ $doc->nama }}</h3>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ $doc->tgl_upload ? \Carbon\Carbon::parse($doc->tgl_upload)->translatedFormat('d M Y') : 'Draft' }}
                                        </p>
                                    </div>
                                    @php
                                        // UPDATE 3: Map Status Mobile
                                        $statusMapMobile = [
                                            'draft' => ['text' => 'Draft', 'class' => 'bg-gray-100 text-gray-600 border-gray-300'],
                                            'pending' => ['text' => 'Pending', 'class' => 'bg-yellow-100 text-yellow-800 border-yellow-200'],
                                            'valid' => ['text' => 'Valid', 'class' => 'bg-green-100 text-green-800 border-green-200'],
                                            'revisi' => ['text' => 'Revisi', 'class' => 'bg-red-100 text-red-800 border-red-200'],
                                        ];
                                        $s = $statusMapMobile[$doc->status] ?? ['text' => $doc->status, 'class' => 'bg-gray-100'];
                                    @endphp
                                    <span class="px-2 py-1 text-[10px] font-semibold rounded-full border {{ $s['class'] }}">{{ $s['text'] }}</span>
                                </div>
                                <p class="text-sm text-gray-600">{{ $doc->deskripsi }}</p>

                                {{-- List File Mobile --}}
                                <div class="bg-gray-50 p-3 rounded-md border border-gray-100">
                                    <span class="text-xs font-semibold text-gray-500 block mb-2">BERKAS TERLAMPIR:</span>
                                    <div class="space-y-2">
                                        @if (is_array($doc->file) && count($doc->file) > 0)
                                            @foreach ($doc->file as $key => $path)
                                                <button
                                                    class="preview-btn w-full flex items-center justify-between text-left text-sm text-blue-600 bg-white border border-gray-200 p-2 rounded hover:bg-blue-50"
                                                    data-file-url="{{ url('storage/' . $path) }}"
                                                    data-file-name="{{ ucwords(str_replace('_', ' ', $key)) }}">
                                                    <span class="flex items-center">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                                        {{ ucwords(str_replace('_', ' ', $key)) }}
                                                    </span>
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-.001.03-.002.06-.002.09a.097.097 0 01-.096.095 4.5 4.5 0 01-8.9 0 .097.097 0 01-.096-.095c0-.03.001-.06.002-.09z"></path></svg>
                                                </button>
                                            @endforeach
                                        @else
                                            <span class="text-xs text-gray-400 italic block text-center py-2">Belum ada file diupload</span>
                                        @endif
                                    </div>
                                </div>

                                @if ($doc->status == 'revisi' && $doc->catatan_revisi)
                                    <div class="text-xs text-red-600 bg-red-50 p-2 rounded border border-red-100">
                                        <strong>Revisi:</strong> {{ $doc->catatan_revisi }}
                                    </div>
                                @endif

                                @if ($doc->status == 'valid' && $doc->file_surat_izin)
                                    <a href="{{ asset('storage/' . $doc->file_surat_izin) }}" target="_blank"
                                       class="block w-full text-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition shadow-sm flex items-center justify-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        Download Surat Izin (SIP)
                                    </a>
                                @endif

                                {{-- UPDATE 4: Tambahkan draft di mobile --}}
                                @if (in_array($doc->status, ['pending', 'revisi', 'ditolak', 'draft']))
                                    <a href="{{ route('user.dokumen.edit', $doc) }}"
                                       class="block w-full text-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded hover:bg-indigo-700 transition">
                                        {{ $doc->status === 'draft' ? 'Lanjutkan Pengajuan' : 'Update Dokumen' }}
                                    </a>
                                @endif
                            </div>
                        @empty
                            <div class="p-8 text-center text-gray-500">Belum ada dokumen</div>
                        @endforelse
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    {{ $dokumen->links() }}
                </div>
            </div>

            {{-- Modal Preview (Sama seperti sebelumnya) --}}
            <div id="preview-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75 hidden backdrop-blur-sm transition-opacity">
                <div class="bg-white rounded-lg shadow-2xl w-full max-w-5xl h-[90vh] flex flex-col m-4">
                    <div class="flex justify-between items-center p-4 border-b bg-gray-50 rounded-t-lg">
                        <h3 id="preview-title" class="text-lg font-bold text-gray-800 flex items-center">Preview Dokumen</h3>
                        <button id="close-preview-modal" class="text-gray-400 hover:text-red-500 transition-colors p-1 rounded-full hover:bg-gray-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    <div class="flex-1 p-0 bg-gray-200 overflow-hidden relative">
                        <div id="iframe-loader" class="absolute inset-0 flex items-center justify-center z-10 bg-white">
                            <svg class="animate-spin h-10 w-10 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </div>
                        <iframe id="preview-iframe" src="" class="w-full h-full border-0" onload="hideLoader()"></iframe>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <style>
        .fade-in { animation: fadeIn 0.4s cubic-bezier(0.16, 1, 0.3, 1); }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        iframe::-webkit-scrollbar { width: 8px; }
        iframe::-webkit-scrollbar-track { background: #f1f1f1; }
        iframe::-webkit-scrollbar-thumb { background: #888; border-radius: 4px; }
    </style>

    <script>
        // SCRIPT TETAP SAMA SEPERTI SEBELUMNYA
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

            const rect = btnElement.getBoundingClientRect();
            dropdown.style.position = 'fixed';
            dropdown.style.zIndex = '9999';
            dropdown.style.width = '200px';

            const spaceBelow = window.innerHeight - rect.bottom;
            const dropdownHeight = dropdown.offsetHeight || 200;

            if (spaceBelow < dropdownHeight) {
                dropdown.style.top = (rect.top - dropdownHeight - 5) + 'px';
                dropdown.style.left = (rect.left - 20) + 'px';
            } else {
                dropdown.style.top = (rect.bottom + 5) + 'px';
                dropdown.style.left = (rect.left - 20) + 'px';
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

        function hideLoader() {
            document.getElementById('iframe-loader').classList.add('hidden');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const previewModal = document.getElementById('preview-modal');
            const closePreviewModal = document.getElementById('close-preview-modal');
            const previewTitle = document.getElementById('preview-title');
            const previewIframe = document.getElementById('preview-iframe');
            const loader = document.getElementById('iframe-loader');

            window.addEventListener('click', function(e) {
                if (activeDropdown && !e.target.closest('.dropdown-menu') && !e.target.closest('button[onclick^="toggleDropdown"]')) {
                    closeActiveDropdown();
                }
            });
            window.addEventListener('scroll', closeActiveDropdown, true);

            function openModal(fileUrl, fileName) {
                previewTitle.innerHTML = `<svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg> ${fileName}`;
                loader.classList.remove('hidden');

                let fullUrl = fileUrl;
                if (!fullUrl.startsWith('http')) fullUrl = window.location.origin + fullUrl;

                const viewerUrl = `{{ asset('pdfjs/web/viewer.html') }}?file=${encodeURIComponent(fullUrl)}`;
                const extension = fullUrl.split('.').pop().toLowerCase();

                if (['jpg', 'jpeg', 'png'].includes(extension)) {
                    previewIframe.src = fullUrl;
                } else {
                    previewIframe.src = viewerUrl;
                }

                previewModal.classList.remove('hidden');
            }

            function closeModal() {
                previewModal.classList.add('hidden');
                previewIframe.src = '';
            }

            document.body.addEventListener('click', function(e) {
                const previewButton = e.target.closest('.preview-btn');
                if (previewButton) {
                    e.preventDefault();
                    e.stopPropagation();
                    const fileUrl = previewButton.dataset.fileUrl;
                    const fileName = previewButton.dataset.fileName;
                    closeActiveDropdown();
                    openModal(fileUrl, fileName);
                }
            });

            closePreviewModal.addEventListener('click', closeModal);
            previewModal.addEventListener('click', (e) => {
                if (e.target === previewModal) closeModal();
            });

            const statusFilter = document.getElementById('status-filter');
            if(statusFilter) {
                statusFilter.addEventListener('change', () => {
                    document.getElementById('filter-form').submit();
                });
            }
        });
    </script>
@endsection
