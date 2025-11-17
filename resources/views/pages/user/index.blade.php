@extends('layouts.layout')
@section('title', 'User Dashboard')

@section('content')
    <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-auto">
        <div class="max-w-7xl mx-auto space-y-8">
            <div class="bg-gradient-to-r from-sky-500 to-blue-600 rounded-xl shadow-lg p-4 sm:p-6 text-white fade-in">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div class="mb-4 md:mb-0">
                        <h2 class="text-xl sm:text-2xl font-bold mb-2">Selamat Datang, {{ $user->name ?? 'User' }}!</h2>
                        <p class="text-sky-100 text-sm sm:text-base">Pantau status dokumen Anda secara real-time di dashboard ini.</p>
                    </div>
                    <div>
                        <a href="{{ route('user.upload') }}"
                            class="inline-flex items-center px-4 py-2 bg-white text-sky-600 font-medium rounded-lg hover:bg-sky-50 transition-colors text-sm sm:text-base">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Upload Dokumen Baru
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                <div class="stat-card bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 fade-in">
                    <div class="flex items-center">
                        <div class="p-2 sm:p-3 rounded-lg bg-blue-100 text-blue-600 mr-3 sm:mr-4">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-gray-600">Total Dokumen</p>
                            <p class="text-lg sm:text-2xl font-bold text-gray-900" id="total-documents">{{ $stats['total'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="stat-card bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 fade-in">
                    <div class="flex items-center">
                        <div class="p-2 sm:p-3 rounded-lg bg-amber-100 text-amber-600 mr-3 sm:mr-4">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-gray-600">Menunggu</p>
                            <p class="text-lg sm:text-2xl font-bold text-gray-900" id="pending-documents">{{ $stats['pending'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="stat-card bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 fade-in">
                    <div class="flex items-center">
                        <div class="p-2 sm:p-3 rounded-lg bg-green-100 text-green-600 mr-3 sm:mr-4">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-gray-600">Dok. Valid</p>
                            <p class="text-lg sm:text-2xl font-bold text-gray-900" id="valid-documents">{{ $stats['valid'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="stat-card bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 fade-in">
                    <div class="flex items-center">
                        <div class="p-2 sm:p-3 rounded-lg bg-red-100 text-red-600 mr-3 sm:mr-4">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-gray-600">Perlu Revisi</p>
                            <p class="text-lg sm:text-2xl font-bold text-gray-900" id="revision-documents">{{ $stats['revision'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 fade-in">
                <div class="p-4 sm:p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-base sm:text-lg font-medium text-gray-900">Dokumen Terbaru</h2>
                        <a href="{{ route('user.riwayat') }}"
                            class="text-xs sm:text-sm text-sky-600 hover:text-sky-700 font-medium">Lihat Semua</a>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full hidden md:table">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Dokumen</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Upload</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody id="recent-documents" class="bg-white divide-y divide-gray-200">
                            @forelse ($recentDocuments as $doc)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $doc->nama }}</div>
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $doc->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
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
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">
                                        <div class="p-8 text-center text-gray-500">
                                            <svg class="w-12 h-12 sm:w-16 sm:h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <p class="text-base sm:text-lg font-medium text-gray-900 mb-2">Belum ada dokumen</p>
                                            <p class="text-sm text-gray-600">Upload dokumen pertama Anda untuk memulai</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div id="recent-documents-mobile" class="md:hidden divide-y divide-gray-200">
                        @forelse ($recentDocuments as $doc)
                            <div class="p-4 space-y-3">
                                <div class="flex justify-between items-start">
                                    <h3 class="text-sm font-medium text-gray-900">{{ $doc->nama }}</h3>
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
                                </div>
                                <div class="flex justify-between items-end">
                                    <div class="text-xs text-gray-500 space-y-1">
                                        <p>Tgl Upload: {{ $doc->created_at->format('d M Y') }}</p>
                                    </div>
                                    <div class="pt-2">
                                        <a href="{{ route('user.riwayat') }}#doc-{{ $doc->id }}" class="text-sm font-medium text-sky-600 hover:text-sky-700">
                                            Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                             <div class="p-8 text-center text-gray-500">
                                <svg class="w-12 h-12 sm:w-16 sm:h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-base sm:text-lg font-medium text-gray-900 mb-2">Belum ada dokumen</p>
                                <p class="text-sm text-gray-600">Upload dokumen pertama Anda untuk memulai</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 fade-in">
                    <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-4">Panduan Upload</h3>
                    <ul class="space-y-3 text-sm text-gray-600">
                        <li class="flex items-start">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-500 mr-2 mt-0.5" fill="none"
                                stroke="currentColor" viewbox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            <span class="text-xs sm:text-sm">Pastikan dokumen dalam format PDF, DOC, atau DOCX</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-500 mr-2 mt-0.5" fill="none"
                                stroke="currentColor" viewbox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            <span class="text-xs sm:text-sm">Maksimal ukuran file 10MB</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-500 mr-2 mt-0.5" fill="none"
                                stroke="currentColor" viewbox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            <span class="text-xs sm:text-sm">Berikan deskripsi yang jelas untuk dokumen</span>
                        </li>
                    </ul>
                    <div class="mt-6">
                        <a href="{{ route('user.upload') }}"
                            class="inline-flex items-center px-4 py-2 bg-sky-600 text-white font-medium rounded-lg hover:bg-sky-700 transition-colors text-sm sm:text-base">
                            Upload Dokumen
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 fade-in">
                    <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-4">Informasi Status</h3>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <span class="status-badge status-pending mr-3 text-xs">Menunggu</span>
                            <span class="text-xs sm:text-sm text-gray-600">Dokumen sedang menunggu validasi</span>
                        </div>
                        <div class="flex items-center">
                            <span class="status-badge status-valid mr-3 text-xs">Valid</span>
                            <span class="text-xs sm:text-sm text-gray-600">Dokumen telah divalidasi</span>
                        </div>
                        <div class="flex items-center">
                            <span class="status-badge status-revisi mr-3 text-xs">Revisi</span>
                            <span class="text-xs sm:text-sm text-gray-600">Dokumen perlu direvisi</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection

@push('styles')
<style>
    .fade-in {
        animation: fadeIn 0.3s ease-in-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .stat-card:hover {
        transform: translateY(-2px);
        transition: all 0.2s ease-in-out;
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
    }
    .status-pending { background-color: #FEF3C7; color: #92400E; } /* bg-amber-100 text-amber-800 */
    .status-valid { background-color: #D1FAE5; color: #065F46; } /* bg-green-100 text-green-800 */
    .status-revisi { background-color: #FEE2E2; color: #991B1B; } /* bg-red-100 text-red-800 */
</style>
@endpush