@extends('layouts.layout')
@section('title', 'Validasi Dokumen')

@section('content')
    <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-auto min-h-screen pb-20">
        <div class="max-w-7xl mx-auto space-y-8">

            {{-- HEADER & STATS --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 text-blue-600 rounded-lg mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Perlu Validasi</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['pending'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ALERT MESSAGES --}}
            @if (session('success'))
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg relative fade-in">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg relative fade-in">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- FILTER FORM --}}
            <form method="GET" action="{{ url()->current() }}" id="filter-form">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex flex-col lg:flex-row lg:items-end gap-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 flex-1">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                                <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                                       placeholder="Nama, Deskripsi, User..."
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                                <div class="flex gap-2">
                                    <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}"
                                           class="flex-1 px-3 py-2 border border-gray-300 rounded-lg">
                                    <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}"
                                           class="flex-1 px-3 py-2 border border-gray-300 rounded-lg">
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ url()->current() }}"
                               class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">Reset</a>
                            <button type="submit"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Filter</button>
                        </div>
                    </div>
                </div>
            </form>

            {{-- TABLE --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-900">Antrian Validasi</h2>
                    <select name="sort_by" form="filter-form" onchange="this.form.submit()"
                            class="text-sm border-gray-300 rounded-lg">
                        <option value="newest" {{ ($filters['sort_by'] ?? '') == 'newest' ? 'selected' : '' }}>Terbaru
                        </option>
                        <option value="oldest" {{ ($filters['sort_by'] ?? '') == 'oldest' ? 'selected' : '' }}>Terlama
                        </option>
                    </select>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full hidden md:table">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Dokumen Info
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Pengunggah</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Kelengkapan</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                        @forelse ($dokumen as $doc)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-900">{{ $doc->nama }}</div>
                                    <div class="text-xs text-gray-500 mt-1 max-w-xs truncate">{{ $doc->deskripsi }}
                                    </div>
                                    <div class="text-xs text-gray-400 mt-1">
                                        {{ $doc->created_at->format('d M Y, H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $doc->user->name ?? 'User Hapus' }}</div>
                                    <div class="text-xs text-gray-500">{{ $doc->user->email ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if (is_array($doc->file))
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                                {{ count($doc->file) }} Berkas
                                            </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                Single File
                                            </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button data-modal-toggle="detail-modal-{{ $doc->id }}"
                                            class="text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg text-sm font-medium shadow-sm transition-colors">
                                        Periksa & Validasi
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500">Tidak ada dokumen
                                    pending.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                    {{-- MOBILE VIEW --}}
                    <div class="md:hidden p-4 space-y-4">
                        @foreach ($dokumen as $doc)
                            <div class="bg-white border rounded-lg p-4 shadow-sm">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <h3 class="font-bold text-gray-900">{{ $doc->nama }}</h3>
                                        <p class="text-xs text-gray-500">{{ $doc->user->name ?? 'N/A' }}</p>
                                    </div>
                                    <span class="px-2 py-1 bg-amber-100 text-amber-800 text-xs rounded-full">Pending</span>
                                </div>
                                <div class="text-sm text-gray-600 mb-4">{{ count(is_array($doc->file) ? $doc->file : []) }}
                                    Berkas terlampir</div>
                                <button data-modal-toggle="detail-modal-{{ $doc->id }}"
                                        class="w-full bg-blue-600 text-white py-2 rounded-lg text-sm font-medium">Periksa</button>
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

    {{-- MODALS CONTAINER --}}
    <div id="modal-container">
        @foreach ($dokumen as $doc)
            {{-- DETAIL MODAL (SPLIT VIEW) --}}
            <div id="detail-modal-{{ $doc->id }}"
                 class="dokumen-modal fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-70 hidden backdrop-blur-sm">
                <div class="relative w-full max-w-6xl bg-white rounded-xl shadow-2xl flex flex-col h-[90vh]">

                    {{-- Header --}}
                    <div class="flex justify-between items-center p-4 border-b bg-gray-50 rounded-t-xl">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Validasi: {{ $doc->nama }}</h3>
                            <p class="text-xs text-gray-500">Pengunggah: {{ $doc->user->name ?? 'N/A' }}</p>
                        </div>
                        <button data-modal-close="detail-modal-{{ $doc->id }}"
                                class="text-gray-400 hover:text-red-500 p-1">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    {{-- Body --}}
                    <div class="flex-1 overflow-hidden flex flex-col md:flex-row">
                        {{-- Kiri: List File --}}
                        <div class="w-full md:w-1/3 border-r border-gray-200 bg-white flex flex-col overflow-y-auto">
                            <div class="p-4">
                                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Berkas
                                    Persyaratan</h4>
                                <div class="space-y-2">
                                    @if (is_array($doc->file))
                                        @foreach ($doc->file as $key => $path)
                                            <button
                                                onclick="changePreview('{{ $doc->id }}', '{{ asset('storage/' . $path) }}', '{{ $path }}')"
                                                class="w-full text-left px-4 py-3 rounded-lg border border-gray-200 hover:border-blue-500 hover:bg-blue-50 transition-all group focus:ring-2 focus:ring-blue-500">
                                                <div class="flex items-center">
                                                    <span class="bg-blue-100 text-blue-600 p-2 rounded-md mr-3">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                             viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                  stroke-width="2"
                                                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                            </path>
                                                        </svg>
                                                    </span>
                                                    <div>
                                                        <p
                                                            class="text-sm font-semibold text-gray-700 group-hover:text-blue-800">
                                                            {{ ucwords(str_replace('_', ' ', $key)) }}</p>
                                                        <p class="text-[10px] text-gray-400">Klik untuk melihat</p>
                                                    </div>
                                                </div>
                                            </button>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="mt-6">
                                    <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Deskripsi
                                    </h4>
                                    <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded border">{{ $doc->deskripsi }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Kanan: Preview --}}
                        <div class="w-full md:w-2/3 bg-gray-100 flex flex-col relative">
                            <div class="flex-1 relative overflow-hidden flex items-center justify-center p-4">
                                <div id="loader-{{ $doc->id }}"
                                     class="hidden absolute inset-0 flex items-center justify-center bg-gray-100 z-10">
                                    <svg class="animate-spin h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                </div>
                                <div id="placeholder-{{ $doc->id }}" class="text-center text-gray-400">
                                    <svg class="w-16 h-16 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    <p class="text-sm">Pilih berkas di sisi kiri untuk preview</p>
                                </div>
                                <iframe id="preview-frame-{{ $doc->id }}" src="about:blank"
                                        class="hidden w-full h-full rounded shadow bg-white"></iframe>
                                <img id="preview-img-{{ $doc->id }}" src=""
                                     class="hidden max-w-full max-h-full object-contain rounded shadow" />
                            </div>
                            <div class="p-2 bg-white border-t flex justify-between items-center text-xs">
                                <span id="filename-display-{{ $doc->id }}" class="text-gray-500 italic">File belum
                                    dipilih</span>
                                <a id="download-btn-{{ $doc->id }}" href="#" target="_blank"
                                   class="hidden text-blue-600 hover:underline">Download Asli</a>
                            </div>
                        </div>
                    </div>

                    {{-- Footer: Actions --}}
                    <div class="p-4 border-t bg-gray-50 rounded-b-xl flex justify-end space-x-3">
                        <button onclick="toggleRevisiForm('{{ $doc->id }}')"
                                class="px-5 py-2 bg-amber-500 text-white font-medium rounded-lg hover:bg-amber-600 shadow-sm">
                            ⚠️ Minta Revisi
                        </button>
                        <form action="{{ route('operator.dokumen.lakukanValidasi', $doc->id) }}" method="POST"
                              onsubmit="return confirm('Yakin validasi dokumen ini?')">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    class="px-5 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 shadow-sm">
                                ✅ Setujui / Validasi
                            </button>
                        </form>
                    </div>

                    {{-- REVISI FORM POPUP --}}
                    <div id="revisi-form-{{ $doc->id }}"
                         class="hidden absolute bottom-20 right-4 w-96 bg-white shadow-2xl rounded-xl border border-gray-200 p-4 z-20">

                        {{-- Header --}}
                        <div class="flex items-center mb-4 pb-3 border-b border-gray-100">
                            <div class="flex-shrink-0 w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
                                <svg class="h-5 w-5 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                          clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-sm font-bold text-gray-800">Permintaan Revisi</h4>
                                <p class="text-xs text-gray-500">Pilih berkas yang perlu diperbaiki</p>
                            </div>
                        </div>

                        <form action="{{ route('operator.dokumen.lakukanRevisi', $doc->id) }}" method="POST">
                            @csrf @method('PATCH')

                            {{-- NEW: Checkbox List File --}}
                            <div class="mb-3 max-h-40 overflow-y-auto border rounded-lg p-3 bg-gray-50">
                                <p class="text-xs font-bold text-gray-600 mb-2 uppercase">Pilih File Bermasalah:</p>
                                @if (is_array($doc->file))
                                    @foreach ($doc->file as $key => $path)
                                        <label
                                            class="flex items-center space-x-2 mb-2 cursor-pointer hover:bg-gray-100 p-1.5 rounded transition-colors">
                                            <input type="checkbox" name="file_revisi[]" value="{{ $key }}"
                                                   class="w-4 h-4 text-amber-600 border-gray-300 rounded focus:ring-amber-500">
                                            <span
                                                class="text-xs text-gray-700 font-medium">{{ ucwords(str_replace('_', ' ', $key)) }}</span>
                                        </label>
                                    @endforeach
                                @endif
                            </div>

                            {{-- Catatan Text Area --}}
                            <div class="mb-2">
                                <label for="catatan-revisi-{{ $doc->id }}"
                                       class="block text-xs font-medium text-gray-700 mb-1">
                                    Catatan Tambahan <span class="text-red-500">*</span>
                                </label>
                                <textarea id="catatan-revisi-{{ $doc->id }}" name="catatan_revisi" rows="3"
                                          class="w-full text-sm border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-amber-500"
                                          placeholder="Jelaskan detail kesalahan..." required minlength="5"></textarea>
                            </div>

                            {{-- Tombol Aksi --}}
                            <div class="flex justify-end space-x-2 mt-4 pt-3 border-t border-gray-100">
                                <button type="button" onclick="toggleRevisiForm('{{ $doc->id }}')"
                                        class="px-3 py-2 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                    Batal
                                </button>
                                <button type="submit"
                                        class="px-3 py-2 text-xs font-medium text-white bg-amber-600 rounded-lg hover:bg-amber-700 shadow-sm transition-colors">
                                    Kirim Revisi
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        @endforeach
    </div>

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
        // Toggle Revisi Form Popover
        function toggleRevisiForm(id) {
            const form = document.getElementById('revisi-form-' + id);
            form.classList.toggle('hidden');
        }

        // Preview Logic
        function changePreview(docId, fullUrl, filePath) {
            const loader = document.getElementById('loader-' + docId);
            const iframe = document.getElementById('preview-frame-' + docId);
            const img = document.getElementById('preview-img-' + docId);
            const placeholder = document.getElementById('placeholder-' + docId);
            const dlBtn = document.getElementById('download-btn-' + docId);
            const fnDisplay = document.getElementById('filename-display-' + docId);

            placeholder.classList.add('hidden');
            iframe.classList.add('hidden');
            img.classList.add('hidden');
            loader.classList.remove('hidden');

            dlBtn.classList.remove('hidden');
            dlBtn.href = fullUrl;
            fnDisplay.textContent = filePath.split('/').pop();

            const ext = filePath.split('.').pop().toLowerCase();

            if (['jpg', 'jpeg', 'png'].includes(ext)) {
                img.src = fullUrl;
                img.onload = () => {
                    loader.classList.add('hidden');
                    img.classList.remove('hidden');
                };
            } else {
                // PDF Viewer
                const viewerUrl = `{{ asset('pdfjs/web/viewer.html') }}?file=${encodeURIComponent(fullUrl)}`;
                iframe.src = viewerUrl;
                iframe.onload = () => {
                    loader.classList.add('hidden');
                    iframe.classList.remove('hidden');
                };
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Modal Toggles
            document.querySelectorAll('[data-modal-toggle]').forEach(btn => {
                btn.addEventListener('click', () => {
                    document.getElementById(btn.dataset.modalToggle).classList.remove('hidden');
                });
            });
            document.querySelectorAll('[data-modal-close]').forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = btn.dataset.modalClose;
                    document.getElementById(id).classList.add('hidden');
                    const iframe = document.querySelector(`#${id} iframe`);
                    if (iframe) iframe.src = 'about:blank'; // Reset iframe memory
                });
            });
        });
    </script>
@endsection
