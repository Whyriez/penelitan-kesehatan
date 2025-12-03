<?php

namespace App\Exports;

use App\Models\ArsipPenelitianKesehatan;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DokumenMasukExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function view(): View
    {
        $query = ArsipPenelitianKesehatan::query()->with(['user', 'jenisIzin']);

        // 1. Filter Search
        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        // 2. Filter Status
        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        // 3. Filter Tanggal
        if (!empty($this->filters['date_from'])) {
            $query->whereDate('created_at', '>=', $this->filters['date_from']);
        }
        if (!empty($this->filters['date_to'])) {
            $query->whereDate('created_at', '<=', $this->filters['date_to']);
        }

        // Default Sort: Created At Descending (Sesuai Controller)
        $query->orderBy('created_at', 'desc');

        $dateFrom = $this->filters['date_from'] ?? null;
        $dateTo = $this->filters['date_to'] ?? null;

        Carbon::setLocale('id');

        if ($dateFrom && $dateTo) {
            $start = Carbon::parse($dateFrom);
            $end = Carbon::parse($dateTo);

            // Jika bulan dan tahun sama (contoh: 1 Agt - 31 Agt)
            if ($start->month === $end->month && $start->year === $end->year) {
                $labelBulan = strtoupper($start->translatedFormat('F'));
                // Output: AGUSTUS
            } else {
                // Jika lintas bulan (contoh: 30 Agt - 2 Sep)
                $labelBulan = strtoupper($start->translatedFormat('F')) . ' - ' . strtoupper($end->translatedFormat('F'));
                // Output: AGUSTUS - SEPTEMBER
            }
            $tahun = $start->year; // Ambil tahun dari filter
        } elseif ($dateFrom) {
            // Jika cuma isi tanggal awal
            $start = Carbon::parse($dateFrom);
            $labelBulan = strtoupper($start->translatedFormat('F'));
            $tahun = $start->year;
        } else {
            // Jika tidak ada filter tanggal, pakai bulan saat ini (Default)
            $labelBulan = strtoupper(now()->translatedFormat('F'));
            $tahun = now()->year;
        }

        return view('pages.operator.exports.rekapan', [
            'data' => $query->get(),
            'bulan' => $labelBulan, // Variable ini dikirim ke View
            'tahun' => $tahun
        ]);
    }

    // Optional: Styling manual kolom tertentu jika perlu
    public function styles(Worksheet $sheet)
    {
        return [
            // Style font default bisa ditaruh di sini atau di Blade
        ];
    }
}
