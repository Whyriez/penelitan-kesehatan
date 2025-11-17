@extends('layouts.layout')
@section('title', 'Riwayat Dokumen')

@section('content')
    <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-auto">
        <div class="max-w-7xl mx-auto space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Riwayat Dokumen</h1>
                    <p class="text-gray-600 mt-1">Kelola dan pantau semua dokumen yang telah diupload</p>
                </div>
                <a href="{{ route('user.upload') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors text-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Upload Dokumen Baru
                </a>
            </div>

            @if (session('success'))
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                 <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif
            
            <form method="GET" action="{{ route('user.riwayat') }}" id="filter-form">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 fade-in">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div class="md:col-span-2">
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Cari Dokumen</label>
                            <input type="text" id="search" name="search" placeholder="Cari berdasarkan nama atau deskripsi..."
                                value="{{ $filters['search'] ?? '' }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" />
                        </div>
                        <div>
                            <label for="status-filter" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select id="status-filter" name="status"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ ($filters['status'] ?? '') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                                <option value="valid" {{ ($filters['status'] ?? '') == 'valid' ? 'selected' : '' }}>Valid</option>
                                <option value="revisi" {{ ($filters['status'] ?? '') == 'revisi' ? 'selected' : '' }}>Perlu Revisi</option>
                                <option value="ditolak" {{ ($filters['status'] ?? '') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>
                        <div>
                            <label for="date_from" class="block text-sm font-medium text-gray-700 mb-2">Dari Tanggal</label>
                            <input type="date" id="date_from" name="date_from" value="{{ $filters['date_from'] ?? '' }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" />
                        </div>
                        <div>
                            <label for="date_to" class="block text-sm font-medium text-gray-700 mb-2">Sampai Tanggal</label>
                            <input type="date" id="date_to" name="date_to" value="{{ $filters['date_to'] ?? '' }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" />
                        </div>
                    </div>
                    <div class="flex justify-between items-center mt-4">
                        <div class="text-sm text-gray-500">
                            Menampilkan {{ $dokumen->firstItem() ?? 0 }} - {{ $dokumen->lastItem() ?? 0 }} dari {{ $dokumen->total() }} dokumen
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('user.riwayat') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">
                                Reset Filter
                            </a>
                        </div>
                    </div>
                </div>
            </form>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 fade-in">
                <div class="overflow-x-auto">
                    <table class="w-full hidden md:table">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Dokumen</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl Upload</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">File</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="document-table" class="bg-white divide-y divide-gray-200">
                            @forelse ($dokumen as $doc)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $doc->nama }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-600 max-w-xs truncate" title="{{ $doc->deskripsi }}">{{ $doc->deskripsi }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $doc->tgl_upload ? \Carbon\Carbon::parse($doc->tgl_upload)->format('d M Y') : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <button class="preview-btn text-blue-600 hover:text-blue-800 flex items-center"
                                                data-file-url="{{ url('storage/' . str_replace('public/', '', $doc->file)) }}"
                                                data-file-name="{{ $doc->nama }}">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-.001.03-.002.06-.002.09a.097.097 0 01-.096.095 4.5 4.5 0 01-8.9 0 .097.097 0 01-.096-.095c0-.03.001-.06.002-.09z"></path></svg>
                                            Lihat
                                        </button>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusMap = [
                                                'pending' => ['text' => 'Menunggu', 'class' => 'status-pending'],
                                                'valid' => ['text' => 'Valid', 'class' => 'status-valid'],
                                                'revisi' => ['text' => 'Perlu Revisi', 'class' => 'status-revisi'],
                                                'ditolak' => ['text' => 'Ditolak', 'class' => 'status-revisi'],
                                            ];
                                            $s = $statusMap[$doc->status] ?? ['text' => $doc->status, 'class' => 'status-pending'];
                                        @endphp
                                        <span class="status-badge {{ $s['class'] }} text-xs">{{ $s['text'] }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        @if (in_array($doc->status, ['pending', 'revisi', 'ditolak']))
                                            <a href="{{ route('user.dokumen.edit', $doc) }}" 
                                               class="px-3 py-1 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                                                Update
                                            </a>
                                        @else
                                            <span class="px-3 py-1 text-sm font-medium text-gray-500 bg-gray-100 rounded-md">Terkunci</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="p-12 text-center text-gray-500">
                                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewbox="0 0 24 24">
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

                    <div id="document-mobile" class="md:hidden divide-y divide-gray-200">
                        @forelse ($dokumen as $doc)
                            <div class="p-4 space-y-3">
                                <div class="flex justify-between items-start">
                                    <h3 class="text-sm font-medium text-gray-900">{{ $doc->nama }}</h3>
                                    @php
                                        $s = $statusMap[$doc->status] ?? ['text' => $doc->status, 'class' => 'status-pending'];
                                    @endphp
                                    <span class="status-badge {{ $s['class'] }} text-xs">{{ $s['text'] }}</span>
                                </div>
                                <p class="text-sm text-gray-600">{{ $doc->deskripsi }}</p>
                                
                                <div class="flex justify-between items-end pt-2">
                                    <div class="text-xs text-gray-500 space-y-1">
                                        <p>Tgl Upload: {{ $doc->tgl_upload ? \Carbon\Carbon::parse($doc->tgl_upload)->format('d M Y') : 'N/A' }}</p>
                                        <p>
                                            <button class="preview-btn text-blue-600 hover:text-blue-800 flex items-center"
                                                    data-file-url="{{ url('storage/' . str_replace('public/', '', $doc->file)) }}"
                                                    data-file-name="{{ $doc->nama }}">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-.001.03-.002.06-.002.09a.097.097 0 01-.096.095 4.5 4.5 0 01-8.9 0 .097.097 0 01-.096-.095c0-.03.001-.06.002-.09z"></path></svg>
                                                Lihat File
                                            </button>
                                        </p>
                                    </div>
                                    <div class="pt-2">
                                        @if (in_array($doc->status, ['pending', 'revisi', 'ditolak']))
                                            <a href="{{ route('user.dokumen.edit', $doc) }}" 
                                               class="px-3 py-1 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                                                Update
                                            </a>
                                        @else
                                            <span class="px-3 py-1 text-sm font-medium text-gray-500 bg-gray-100 rounded-md">Terkunci</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-12 text-center text-gray-500">
                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-lg font-medium text-gray-900 mb-2">Belum ada dokumen</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div id="pagination" class="px-6 py-4 border-t border-gray-200">
                    {{ $dokumen->links() }}
                </div>
            </div>

            <div id="preview-modal" class="fixed inset-0 z-30 flex items-center justify-center bg-black bg-opacity-50 hidden">
                <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-3/4 lg:w-2/3 h-5/6 flex flex-col">
                    <div class="flex justify-between items-center p-4 border-b">
                        <h3 id="preview-title" class="text-lg font-medium text-gray-900">Preview Dokumen</h3>
                        <button id="close-preview-modal" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="flex-1 p-2">
                        <iframe id="preview-iframe" src="" class="w-full h-full border-0"></iframe>
                    </div>
                </div>
            </div>
            
            </div>
    </main>

    <style>
        .fade-in { animation: fadeIn 0.3s ease-in-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding-left: 0.625rem;
            padding-right: 0.625rem;
            padding-top: 0.125rem;
            padding-bottom: 0.125rem;
            border-radius: 9999px;
            font-weight: 500;
            font-size: 0.75rem; /* text-xs */
        }
        .status-pending { background-color: #FEF3C7; color: #92400E; } /* bg-amber-100 text-amber-800 */
        .status-valid { background-color: #D1FAE5; color: #065F46; } /* bg-green-100 text-green-800 */
        .status-revisi { background-color: #FEE2E2; color: #991B1B; } /* bg-red-100 text-red-800 */
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const previewModal = document.getElementById('preview-modal');
        const closePreviewModal = document.getElementById('close-preview-modal');
        const previewTitle = document.getElementById('preview-title');
        const previewIframe = document.getElementById('preview-iframe');

        function openModal(fileUrl, fileName) {
            previewTitle.textContent = fileName;
            
            // Konversi ke absolute URL jika masih relative
            let fullUrl = fileUrl;
            if (!fullUrl.startsWith('http')) {
                fullUrl = window.location.origin + fullUrl;
            }
            
            // Debug - hapus setelah berhasil
            console.log('Original URL:', fileUrl);
            console.log('Full URL:', fullUrl);
            
            const viewerUrl = `{{ asset('pdfjs/web/viewer.html') }}?file=${encodeURIComponent(fullUrl)}`;
            console.log('Viewer URL:', viewerUrl);
            
            previewIframe.src = viewerUrl;
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
                const fileUrl = previewButton.dataset.fileUrl;
                const fileName = previewButton.dataset.fileName;
                openModal(fileUrl, fileName);
            }
        });

        closePreviewModal.addEventListener('click', closeModal);
        previewModal.addEventListener('click', (e) => {
            if (e.target === previewModal) closeModal();
        });

        document.getElementById('status-filter').addEventListener('change', () => {
            document.getElementById('filter-form').submit();
        });
    });
</script>
@endsection