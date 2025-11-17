@extends('layouts.layout')
@section('title', 'Validasi Dokumen')

@section('content')
    <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-auto">
        <div class="max-w-7xl mx-auto space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="stat-card bg-white rounded-xl shadow-sm border border-gray-200 p-6 fade-in">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-blue-100 text-blue-600 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01. seventh.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Dokumen</p>
                            <p class="text-2xl font-bold text-gray-900" id="total-documents">{{ $stats['total'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="stat-card bg-white rounded-xl shadow-sm border border-gray-200 p-6 fade-in">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-amber-100 text-amber-600 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Menunggu Validasi</p>
                            <p class="text-2xl font-bold text-gray-900" id="pending-documents">{{ $stats['pending'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="stat-card bg-white rounded-xl shadow-sm border border-gray-200 p-6 fade-in">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-green-100 text-green-600 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Tervalidasi</p>
                            <p class="text-2xl font-bold text-gray-900" id="valid-documents">{{ $stats['valid'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="stat-card bg-white rounded-xl shadow-sm border border-gray-200 p-6 fade-in">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-red-100 text-red-600 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Perlu Revisi</p>
                            <p class="text-2xl font-bold text-gray-900" id="revision-documents">{{ $stats['revisi'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
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

            <form method="GET" action="{{ url()->current() }}" id="filter-form">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 fade-in">
                    <div class="flex flex-col lg:flex-row lg:items-end gap-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 flex-1 min-w-0">
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Cari Dokumen</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewbox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>
                                    <input type="text" id="search" name="search"
                                        placeholder="Cari nama dokumen atau deskripsi..."
                                        value="{{ $filters['search'] ?? '' }}"
                                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                            </div>
                            <div>
                                <label for="date-range" class="block text-sm font-medium text-gray-700 mb-2">Rentang Tanggal</label>
                                <div class="flex gap-2">
                                    <input type="date" id="date-from" name="date_from"
                                        value="{{ $filters['date_from'] ?? '' }}"
                                        class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" />
                                    <span class="flex items-center text-gray-500">s/d</span>
                                    <input type="date" id="date-to" name="date_to"
                                        value="{{ $filters['date_to'] ?? '' }}"
                                        class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" />
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-3 flex-shrink-0">
                            <a href="{{ url()->current() }}" id="reset-filters"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                                Reset
                            </a>
                            <button id="apply-filters" type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                                Terapkan Filter
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 fade-in">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900">
                                Dokumen Perlu Validasi
                            </h2>
                            <p class="text-sm text-gray-600 mt-1" id="table-summary">
                                Menampilkan {{ $dokumen->firstItem() ?? 0 }} sampai {{ $dokumen->lastItem() ?? 0 }}
                                dari {{ $dokumen->total() }} dokumen
                            </p>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="relative">
                                <select id="sort-by" name="sort_by" form="filter-form"
                                    class="pl-3 pr-8 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white">
                                    <option value="newest"
                                        {{ ($filters['sort_by'] ?? 'newest') == 'newest' ? 'selected' : '' }}>
                                        Terbaru</option>
                                    <option value="oldest"
                                        {{ ($filters['sort_by'] ?? '') == 'oldest' ? 'selected' : '' }}>
                                        Terlama</option>
                                    <option value="name" {{ ($filters['sort_by'] ?? '') == 'name' ? 'selected' : '' }}>
                                        Nama A-Z</option>
                                </select>
                                <div
                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full hidden md:table">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Dokumen</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Pengunggah</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal Upload</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="document-table" class="bg-white divide-y divide-gray-200">
                            @forelse ($dokumen as $doc)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            @php
                                                $fileType = strtolower($doc->file ?? '');
                                                $icon = '<svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>';
                                                $bg = 'bg-gray-100';
                                                if (str_contains($fileType, '.pdf')) {
                                                    $icon = '<svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>';
                                                    $bg = 'bg-red-100';
                                                } elseif (str_contains($fileType, '.doc') || str_contains($fileType, '.docx')) {
                                                    $icon = '<svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>';
                                                    $bg = 'bg-blue-100';
                                                }
                                            @endphp
                                            <div class="p-2 {{ $bg }} rounded-lg">
                                                {!! $icon !!}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $doc->nama ?? 'N/A' }}</div>
                                                <div class="text-sm text-gray-500 mt-1">{{ $doc->deskripsi ?? 'Tidak ada deskripsi' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $doc->user?->name ?? 'N/A' }}</div>
                                        <div class="text-sm text-gray-500">{{ $doc->user?->email ?? '' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $doc->created_at->format('d M Y, H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $config = [
                                                'pending' => ['text' => 'Menunggu Validasi', 'class' => 'bg-amber-100 text-amber-800'],
                                                'valid'   => ['text' => 'Tervalidasi', 'class' => 'bg-green-100 text-green-800'],
                                                'revisi'  => ['text' => 'Perlu Revisi', 'class' => 'bg-red-100 text-red-800'],
                                            ];
                                            $s = $config[$doc->status] ?? ['text' => $doc->status, 'class' => 'bg-gray-100 text-gray-800'];
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $s['class'] }}">
                                            {{ $s['text'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-3">
                                            <form action="{{ route('admin.dokumen.lakukanValidasi', $doc->id) }}" method="POST"
                                                  onsubmit="return confirm('Anda yakin ingin memvalidasi dokumen ini?');">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-green-600 hover:text-green-900">Validasi</button>
                                            </form>
                                            <button data-modal-toggle="revisi-modal-{{ $doc->id }}"
                                                class="text-amber-600 hover:text-amber-900">Revisi</button>
                                            <button data-modal-toggle="detail-modal-{{ $doc->id }}"
                                                class="text-blue-600 hover:text-blue-900">Detail</button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="p-8 text-center text-gray-500">
                                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none"
                                                stroke="currentColor" viewbox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                            <p class="text-lg font-medium text-gray-900 mb-2">
                                                Tidak Ada Dokumen
                                            </p>
                                            <p class="text-gray-600 mb-4">
                                                Tidak ada dokumen yang menunggu validasi saat ini.
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div id="document-mobile" class="md:hidden p-4 space-y-4">
                        @forelse ($dokumen as $doc)
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex items-center">
                                        @php
                                            $fileType = strtolower($doc->file ?? '');
                                            $icon = '<svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>';
                                            $bg = 'bg-gray-100';
                                            if (str_contains($fileType, '.pdf')) {
                                                $icon = '<svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>';
                                                $bg = 'bg-red-100';
                                            } elseif (str_contains($fileType, '.doc') || str_contains($fileType, '.docx')) {
                                                $icon = '<svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>';
                                                $bg = 'bg-blue-100';
                                            }
                                        @endphp
                                        <div class="p-2 {{ $bg }} rounded-lg">
                                            {!! $icon !!}
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="font-medium text-gray-900 text-sm">{{ $doc->nama ?? 'N/A' }}</h3>
                                            <p class="text-xs text-gray-600">{{ $doc->user?->name ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    @php
                                        $config = [
                                            'pending' => ['text' => 'Menunggu Validasi', 'class' => 'bg-amber-100 text-amber-800'],
                                            'valid'   => ['text' => 'Tervalidasi', 'class' => 'bg-green-100 text-green-800'],
                                            'revisi'  => ['text' => 'Perlu Revisi', 'class' => 'bg-red-100 text-red-800'],
                                        ];
                                        $s = $config[$doc->status] ?? ['text' => $doc->status, 'class' => 'bg-gray-100 text-gray-800'];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $s['class'] }}">
                                        {{ $s['text'] }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 mb-3">{{ $doc->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                                <div class="flex justify-between items-center text-xs text-gray-500 mb-3">
                                    <span>
                                        {{ $doc->created_at->format('d M Y, H:i') }}
                                    </span>
                                </div>
                                <div class="flex justify-end space-x-2">
                                    <form action="{{ route('admin.dokumen.lakukanValidasi', $doc->id) }}" method="POST"
                                          onsubmit="return confirm('Anda yakin ingin memvalidasi dokumen ini?');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="px-3 py-1 text-xs bg-green-600 text-white rounded hover:bg-green-700 transition-colors">
                                            Validasi
                                        </button>
                                    </form>
                                    <button data-modal-toggle="revisi-modal-{{ $doc->id }}"
                                        class="px-3 py-1 text-xs bg-amber-600 text-white rounded hover:bg-amber-700 transition-colors">
                                        Revisi
                                    </button>
                                    <button data-modal-toggle="detail-modal-{{ $doc->id }}"
                                        class="px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                                        Detail
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-gray-500">
                                <p class="text-lg font-medium text-gray-900 mb-2">Belum ada dokumen</p>
                            </div>
                        @endforelse
                    </div>

                </div>

                <div id="pagination" class="p-4 border-t border-gray-200">
                    {{ $dokumen->links() }}
                </div>
            </div>
        </div>
    </main>

    <div id="modal-container" class="hidden">
        @foreach ($dokumen as $doc)
            
            <div id="detail-modal-{{ $doc->id }}"
                class="dokumen-modal fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50 hidden">
                <div class="relative w-full max-w-2xl bg-white rounded-lg shadow-xl flex flex-col max-h-[90vh]">
                    
                    <div class="flex justify-between items-center p-4 border-b flex-shrink-0">
                        <h3 class="text-lg font-medium text-gray-900">Detail Dokumen</h3>
                        <button data-modal-close="detail-modal-{{ $doc->id }}"
                            class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="p-6 space-y-4 overflow-y-auto flex-1">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Dokumen</label>
                                <p class="text-sm text-gray-900 break-words">{{ $doc->nama ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Pengunggah</label>
                                <p class="text-sm text-gray-900">{{ $doc->user?->name ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-600">{{ $doc->user?->email ?? '' }}</p>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                                <p class="text-sm text-gray-900">{{ $doc->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Upload</label>
                                <p class="text-sm text-gray-900">{{ $doc->created_at->format('d M Y, H:i') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                @php
                                    $config = [
                                        'pending' => ['text' => 'Menunggu Validasi', 'class' => 'bg-amber-100 text-amber-800'],
                                        'valid' => ['text' => 'Tervalidasi', 'class' => 'bg-green-100 text-green-800'],
                                        'revisi' => ['text' => 'Perlu Revisi', 'class' => 'bg-red-100 text-red-800'],
                                    ];
                                    $s = $config[$doc->status] ?? ['text' => $doc->status, 'class' => 'bg-gray-100 text-gray-800'];
                                @endphp
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $s['class'] }}">
                                    {{ $s['text'] }}
                                </span>
                            </div>
                        </div>

                        @php
                            $isPdf = str_contains(strtolower($doc->file), '.pdf');
                            $fileAbsoluteUrl = asset(Storage::url($doc->file));
                            $pdfViewerUrl = asset('pdfjs/web/viewer.html') . '?file=' . urlencode($fileAbsoluteUrl);
                        @endphp

                        @if ($isPdf)
                            <div class="mt-4 pt-4 border-t">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Preview Dokumen</h4>
                                <div class="w-full h-[600px] border rounded-lg overflow-hidden bg-gray-200">
                                    <iframe src="{{ $pdfViewerUrl }}" width="100%" height="100%" frameborder="0"
                                        loading="lazy">
                                        Memuat preview...
                                    </iframe>
                                </div>
                            </div>
                        @else
                            <div class="mt-4 pt-4 border-t">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Preview Dokumen</h4>
                                <div class="p-4 text-center bg-gray-50 rounded-lg border">
                                    <p class="text-sm text-gray-600">
                                        Preview tidak tersedia untuk tipe file ini.
                                    </p>
                                    <a href="{{ $fileAbsoluteUrl }}" target="_blank" rel="noopener noreferrer"
                                        class="mt-2 inline-block text-blue-600 hover:underline text-sm font-medium">
                                        Buka/Download File ({{ basename($doc->file) }})
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="flex justify-end space-x-3 p-4 border-t bg-gray-50 rounded-b-lg flex-shrink-0">
                        <button data-modal-close="detail-modal-{{ $doc->id }}" type="button"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>

            <div id="revisi-modal-{{ $doc->id }}"
                class="dokumen-modal fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50 hidden">
                <div class="relative w-full max-w-lg bg-white rounded-lg shadow-xl flex flex-col max-h-[90vh]">
                    <form action="{{ route('admin.dokumen.lakukanRevisi', $doc->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="flex justify-between items-center p-4 border-b flex-shrink-0">
                            <h3 class="text-lg font-semibold text-gray-900">Catatan Revisi untuk "{{ $doc->nama }}"</h3>
                            <button type="button" data-modal-close="revisi-modal-{{ $doc->id }}" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <div class="p-6 space-y-4 overflow-y-auto flex-1">
                            <div>
                                <label for="catatan_revisi_{{ $doc->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                                    Apa yang perlu diperbaiki? (Wajib diisi)
                                </label>
                                <textarea id="catatan_revisi_{{ $doc->id }}" name="catatan_revisi" rows="4"
                                    class="block w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 p-2"
                                    placeholder="Contoh: Tolong perbaiki bagian Tanda Tangan, masih belum sesuai..."
                                    required minlength="10"></textarea>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 p-4 border-t bg-gray-50 rounded-b-lg flex-shrink-0">
                            <button type="button" data-modal-close="revisi-modal-{{ $doc->id }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                                Batal
                            </button>
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                                Kirim Catatan Revisi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
  
            const modalContainer = document.getElementById('modal-container');
            const modals = modalContainer.querySelectorAll('.dokumen-modal');
        
            modals.forEach(modal => {
                document.body.appendChild(modal);
            });
            
            modalContainer.remove();

            const sortBySelect = document.getElementById('sort-by');
            if (sortBySelect) {
                sortBySelect.addEventListener('change', function() {
                    document.getElementById('filter-form').submit();
                });
            }

            const modalToggleButtons = document.querySelectorAll('[data-modal-toggle]');
            modalToggleButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const targetModalId = button.getAttribute('data-modal-toggle');
                    const modal = document.getElementById(targetModalId);
                    if (modal) {
                        modal.classList.remove('hidden');
                    }
                });
            });

            const modalCloseButtons = document.querySelectorAll('[data-modal-close]');
            modalCloseButtons.forEach(button => {
                button.addEventListener('click', (e) => {
                    e.preventDefault(); 
                    const targetModalId = button.getAttribute('data-modal-close');
                    const modal = document.getElementById(targetModalId);
                    if (modal) {
                        modal.classList.add('hidden');
                    }
                });
            });
        });
    </script>

    <style>
        .fade-in { animation: fadeIn 0.3s ease-in-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .stat-card:hover {
            transform: translateY(-2px);
            transition: all 0.2s ease-in-out;
        }
    </style>
@endsection