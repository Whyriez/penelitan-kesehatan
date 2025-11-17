@extends('layouts.layout')
@section('title', 'Admin Dashboard')

@section('content')
    <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-auto">
        <div class="max-w-7xl mx-auto space-y-8">
            <div class="fade-in">
                <h1 class="text-2xl font-bold text-gray-900">Dashboard Admin</h1>
                <p class="text-gray-600 mt-2">Ringkasan aktivitas dan statistik sistem</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover-lift fade-in">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Dokumen</p>
                            <p id="total-documents" class="text-2xl font-semibold text-gray-900">
                                {{ $stats['total'] ?? 0 }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover-lift fade-in">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor"
                                    viewbox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Menunggu Validasi</p>
                            <p id="pending-documents" class="text-2xl font-semibold text-gray-900">
                                {{ $stats['pending'] ?? 0 }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover-lift fade-in">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                    viewbox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Tervalidasi</p>
                            <p id="verified-documents" class="text-2xl font-semibold text-gray-900">
                                {{ $stats['valid'] ?? 0 }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover-lift fade-in">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Perlu Revisi</p>
                            <p id="revision-documents" class="text-2xl font-semibold text-gray-900">
                                {{ $stats['revisi'] ?? 0 }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 fade-in">

                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Statistik Dokumen</h3>
                        <form id="chart-filter-form" method="GET" action="{{ route('operator.index') }}">
                            <select name="chart_filter" id="chart_filter"
                                class="text-sm border border-gray-300 rounded-lg px-3 py-1 focus:ring-2 focus:ring-blue-500">
                                <option value="bulan_ini"
                                    {{ ($filters['chart_filter'] ?? 'bulan_ini') == 'bulan_ini' ? 'selected' : '' }}>
                                    Bulan Ini
                                </option>
                                <option value="3_bulan_terakhir"
                                    {{ ($filters['chart_filter'] ?? '') == '3_bulan_terakhir' ? 'selected' : '' }}>
                                    3 Bulan Terakhir
                                </option>
                                <option value="tahun_ini"
                                    {{ ($filters['chart_filter'] ?? '') == 'tahun_ini' ? 'selected' : '' }}>
                                    Tahun Ini
                                </option>
                            </select>
                        </form>
                    </div>
                    <div class="h-64">
                        <canvas id="dokumenChart"></canvas>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 fade-in">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Aktivitas Terbaru</h3>
                        <a href="{{ route('admin.dokumen_masuk') }}"
                            class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                            Lihat Semua
                        </a>
                    </div>
                    <div class="space-y-4">
                        @forelse($recentActivities as $activity)
                            <div class="flex items-start space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors">
                                <div
                                    class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                        viewbox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-gray-900">
                                        <span class="font-medium">{{ $activity->user->name ?? 'User' }}</span>
                                        mengunggah dokumen baru
                                    </p>
                                    <p class="text-sm text-gray-500 truncate">{{ $activity->nama ?? 'Dokumen' }}</p>
                                    <p class="text-xs text-gray-400 mt-1">{{ $activity->created_at->diffForHumans() }}</p>
                                </div>
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                {{ $activity->status === 'pending'
                                    ? 'bg-amber-100 text-amber-800'
                                    : ($activity->status === 'valid'
                                        ? 'bg-green-100 text-green-800'
                                        : 'bg-red-100 text-red-800') }}">
                                    {{ $activity->status === 'pending' ? 'Menunggu' : ($activity->status === 'valid' ? 'Valid' : 'Revisi') }}
                                </span>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor"
                                    viewbox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p>Tidak ada aktivitas terbaru</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 fade-in">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h3>
                    <div class="space-y-3">
                        <a href="{{ route('admin.dokumen_masuk') }}"
                            class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-200 transition-colors group">
                            <div
                                class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-200">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                    viewbox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700">Lihat Dokumen Masuk</span>
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6 fade-in">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Ringkasan Performa</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ $stats['total'] ?? 0 }}</div>
                            <div class="text-sm text-gray-600 mt-1">Total Dokumen</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">
                                {{ $stats['total'] > 0 ? round(($stats['valid'] / $stats['total']) * 100, 1) : 0 }}%
                            </div>
                            <div class="text-sm text-gray-600 mt-1">Tingkat Validasi</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-amber-600">
                                {{ $stats['today'] ?? 0 }}
                            </div>
                            <div class="text-sm text-gray-600 mt-1">Dokumen Hari Ini</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-600">
                                {{ $stats['users'] ?? 0 }}
                            </div>
                            <div class="text-sm text-gray-600 mt-1">Total Pengguna</div>
                        </div>
                    </div>

                    <div class="mt-6 space-y-4">
                        <div>
                            <div class="flex justify-between text-sm text-gray-600 mb-1">
                                <span>Dokumen Tervalidasi</span>
                                <span>{{ $stats['valid'] ?? 0 }}/{{ $stats['total'] ?? 0 }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-600 h-2 rounded-full"
                                    style="width: {{ $stats['total'] > 0 ? ($stats['valid'] / $stats['total']) * 100 : 0 }}%">
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-sm text-gray-600 mb-1">
                                <span>Menunggu Validasi</span>
                                <span>{{ $stats['pending'] ?? 0 }}/{{ $stats['total'] ?? 0 }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-amber-500 h-2 rounded-full"
                                    style="width: {{ $stats['total'] > 0 ? ($stats['pending'] / $stats['total']) * 100 : 0 }}%">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.hover-lift');
            cards.forEach(card => {
                card.addEventListener('mouseenter', () => {
                    card.style.transform = 'translateY(-4px)';
                    card.style.transition = 'all 0.2s ease-in-out';
                });
                card.addEventListener('mouseleave', () => {
                    card.style.transform = 'translateY(0)';
                });
            });

            const ctx = document.getElementById('dokumenChart');
            if (ctx) {
                const chartData = @json($chartData ?? ['labels' => [], 'counts' => []]);

                new Chart(ctx, {
                    type: 'bar', 
                    data: {
                        labels: chartData.labels, 
                        datasets: [{
                            label: 'Dokumen Diunggah',
                            data: chartData.counts,
                            backgroundColor: 'rgba(59, 130, 246, 0.5)', 
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 1,
                            borderRadius: 4,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1,
                                    precision: 0
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }

            const chartFilterSelect = document.getElementById('chart_filter');
            if (chartFilterSelect) {
                chartFilterSelect.addEventListener('change', function() {
                    document.getElementById('chart-filter-form').submit();
                });
            }
        });
    </script>
@endsection

@push('styles')
    <style>
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
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
@endpush
