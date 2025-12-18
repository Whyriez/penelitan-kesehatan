@extends('layouts.layout')
@section('title', 'Rekapan SIP - Pengawas')

@section('content')
    <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-auto min-h-screen pb-20">
        <div class="max-w-7xl mx-auto space-y-8">

            {{-- HEADER SIMPLE --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Rekapan Data SIP</h1>
                    <p class="text-sm text-gray-500">Data dokumen yang telah disetujui dan diterbitkan.</p>
                </div>

                {{-- EXPORT BUTTON --}}
                <a href="{{ route('pengawas.export', request()->query()) }}" target="_blank"
                   class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export ke Excel
                </a>
            </div>

            {{-- STATISTIK KECIL --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 flex items-center">
                    <div class="p-3 rounded-lg bg-green-100 text-green-600 mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total SIP Terbit</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_valid'] }}</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 flex items-center">
                    <div class="p-3 rounded-lg bg-blue-100 text-blue-600 mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Terbit Bulan Ini</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['bulan_ini'] }}</p>
                    </div>
                </div>
            </div>

            <form method="GET" action="{{ url()->current() }}" id="filter-form">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 fade-in">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">

                        {{-- SEARCH --}}
                        <div class="md:col-span-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cari Data</label>
                            <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                                   placeholder="Nama user, judul, atau deskripsi..."
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"/>
                        </div>

                        {{-- TANGGAL --}}
                        <div class="md:col-span-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Terbit</label>
                            <div class="flex items-center gap-2">
                                <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"/>
                                <span class="text-gray-400">-</span>
                                <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"/>
                            </div>
                        </div>

                        {{-- TOMBOL FILTER --}}
                        <div class="md:col-span-2 flex gap-2">
                            <button type="submit" class="flex-1 px-3 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Filter</button>
                            <a href="{{ url()->current() }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 border border-gray-200">Reset</a>
                        </div>
                    </div>
                </div>
            </form>

            {{-- TABLE SECTION --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 fade-in overflow-hidden">
                <div class="p-6 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <p class="text-sm text-gray-600">Menampilkan {{ $dokumen->firstItem() ?? 0 }} - {{ $dokumen->lastItem() ?? 0 }} dari {{ $dokumen->total() }} data</p>
                    <select name="sort_by" form="filter-form" onchange="this.form.submit()" class="pl-3 pr-8 py-2 text-sm border border-gray-300 rounded-lg bg-white">
                        <option value="newest" {{ ($filters['sort_by'] ?? 'newest') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="oldest" {{ ($filters['sort_by'] ?? '') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                        <option value="name" {{ ($filters['sort_by'] ?? '') == 'name' ? 'selected' : '' }}>Nama A-Z</option>
                    </select>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Judul & Izin</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Pemohon</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Dokumen SIP</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($dokumen as $doc)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-900">{{ $doc->nama }}</div>
                                    <div class="text-xs text-green-600 mt-1 font-semibold">
                                        {{ $doc->jenisIzin->nama ?? 'Izin Kesehatan' }}
                                    </div>
                                    <div class="text-xs text-gray-400 mt-1">{{ $doc->created_at->format('d M Y, H:i') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $doc->user->name ?? 'User Terhapus' }}</div>
                                    <div class="text-xs text-gray-500">{{ $doc->user->email ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($doc->file_surat_izin)
                                        <a href="{{ asset('storage/' . $doc->file_surat_izin) }}" target="_blank" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 hover:bg-green-200">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            Download SIP
                                        </a>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Belum diupload</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <button data-modal-toggle="detail-modal-{{ $doc->id }}"
                                            class="text-blue-600 hover:text-blue-900 bg-blue-50 px-3 py-1 rounded hover:bg-blue-100 transition-colors text-xs font-medium">
                                        Lihat Detail
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-6 py-10 text-center text-gray-500">Tidak ada data rekapan ditemukan.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-200">{{ $dokumen->links() }}</div>
            </div>
        </div>
    </main>

    {{-- MODAL DETAIL (SAMA PERSIS DENGAN OPERATOR TAPI TANPA FITUR EDIT) --}}
    <div id="modal-container">
        @foreach ($dokumen as $doc)
            <div id="detail-modal-{{ $doc->id }}" class="dokumen-modal fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-70 hidden backdrop-blur-sm">
                <div class="relative w-full max-w-6xl bg-white rounded-xl shadow-2xl flex flex-col h-[90vh]">
                    {{-- HEADER --}}
                    <div class="flex justify-between items-center p-4 border-b bg-gray-50 rounded-t-xl">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">{{ $doc->nama }}</h3>
                            <p class="text-xs text-gray-500">Oleh: {{ $doc->user->name ?? 'N/A' }} | Validasi: {{ $doc->updated_at->format('d M Y') }}</p>
                        </div>
                        <button data-modal-close="detail-modal-{{ $doc->id }}" class="text-gray-400 hover:text-red-500 p-2"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                    </div>

                    {{-- BODY --}}
                    <div class="flex-1 overflow-hidden flex flex-col md:flex-row">
                        {{-- KIRI: LIST FILE --}}
                        <div class="w-full md:w-1/3 border-r border-gray-200 flex flex-col bg-white overflow-y-auto">
                            <div class="p-4">
                                <h4 class="text-xs font-bold text-gray-500 uppercase mb-3">Berkas Persyaratan</h4>
                                <div class="space-y-2">
                                    @if(is_array($doc->file))
                                        @foreach($doc->file as $key => $path)
                                            <button onclick="changePreview('{{ $doc->id }}', '{{ asset('storage/' . $path) }}', '{{ $path }}')"
                                                    class="w-full text-left px-4 py-3 rounded-lg border border-gray-200 hover:border-blue-500 hover:bg-blue-50 transition-all group">
                                                <div class="flex items-center">
                                                    <span class="bg-blue-100 text-blue-600 p-2 rounded-md mr-3"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg></span>
                                                    <div>
                                                        <p class="text-sm font-semibold text-gray-700">{{ ucwords(str_replace('_', ' ', $key)) }}</p>
                                                        <p class="text-[10px] text-gray-400">Klik untuk preview</p>
                                                    </div>
                                                </div>
                                            </button>
                                        @endforeach
                                    @endif
                                    {{-- Tambahan: Tombol Lihat SIP --}}
                                    @if($doc->file_surat_izin)
                                        <div class="mt-4 pt-4 border-t border-gray-100">
                                            <h4 class="text-xs font-bold text-green-600 uppercase mb-2">Dokumen Resmi</h4>
                                            <button onclick="changePreview('{{ $doc->id }}', '{{ asset('storage/' . $doc->file_surat_izin) }}', 'Surat_Izin_Praktik.pdf')"
                                                    class="w-full text-left px-4 py-3 rounded-lg border border-green-200 bg-green-50 hover:bg-green-100 transition-all group">
                                                <div class="flex items-center">
                                                    <span class="bg-green-200 text-green-700 p-2 rounded-md mr-3"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></span>
                                                    <div>
                                                        <p class="text-sm font-semibold text-green-800">Surat Izin Praktik (SIP)</p>
                                                        <p class="text-[10px] text-green-600">File Output</p>
                                                    </div>
                                                </div>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- KANAN: PREVIEW --}}
                        <div class="w-full md:w-2/3 bg-gray-100 flex flex-col relative">
                            <div class="flex-1 relative overflow-hidden flex items-center justify-center p-4">
                                <div id="loader-{{ $doc->id }}" class="absolute inset-0 flex items-center justify-center bg-gray-100 z-10 hidden">Loading...</div>
                                <iframe id="preview-frame-{{ $doc->id }}" src="about:blank" class="w-full h-full rounded-lg shadow-sm bg-white border border-gray-200 hidden"></iframe>
                                <div id="placeholder-{{ $doc->id }}" class="text-center text-gray-400">
                                    <p class="text-sm">Pilih berkas di sebelah kiri untuk melihat preview</p>
                                </div>
                                <img id="preview-img-{{ $doc->id }}" src="" class="max-w-full max-h-full object-contain hidden rounded-lg shadow-sm"/>
                            </div>
                            <div class="p-3 bg-white border-t border-gray-200 flex justify-between items-center">
                                <span id="filename-display-{{ $doc->id }}" class="text-xs text-gray-500 italic">Belum ada file dipilih</span>
                                <a id="download-btn-{{ $doc->id }}" href="#" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm font-medium hidden">Download &rarr;</a>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 border-t bg-gray-50 flex justify-end">
                        <button data-modal-close="detail-modal-{{ $doc->id }}" class="px-5 py-2 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700">Tutup</button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- SCRIPT SAMA DENGAN OPERATOR UNTUK HANDLING MODAL & PREVIEW --}}
    <script>
        // Copy fungsi changePreview dan modal listener dari file Operator di sini
        // Pastikan fungsi changePreview() ada.
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

            if (['jpg', 'jpeg', 'png'].includes(ext)) {
                img.src = fullUrl;
                img.onload = () => { loader.classList.add('hidden'); img.classList.remove('hidden'); };
            } else {
                const viewerUrl = `{{ asset('pdfjs/web/viewer.html') }}?file=${encodeURIComponent(fullUrl)}`;
                iframe.src = viewerUrl;
                iframe.onload = () => { loader.classList.add('hidden'); iframe.classList.remove('hidden'); };
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const modalToggles = document.querySelectorAll('[data-modal-toggle]');
            const modalCloses = document.querySelectorAll('[data-modal-close]');

            modalToggles.forEach(btn => {
                btn.addEventListener('click', () => {
                    document.getElementById(btn.getAttribute('data-modal-toggle')).classList.remove('hidden');
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
        });
    </script>
@endsection
