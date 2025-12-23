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
    protected $viewName;

    public function __construct($filters, $viewName = 'pages.operator.exports.rekapan')
    {
        $this->filters = $filters;
        $this->viewName = $viewName;
    }

    public function view(): View
    {
        $query = ArsipPenelitianKesehatan::query()
            ->with(['user', 'jenisIzin'])
            ->where('status', 'valid');

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

        if (!empty($this->filters['date_from'])) {
            $query->whereDate('created_at', '>=', $this->filters['date_from']);
        }
        if (!empty($this->filters['date_to'])) {
            $query->whereDate('created_at', '<=', $this->filters['date_to']);
        }

        // Sorting default
        $query->orderBy('jenis_izin_id', 'asc')
            ->orderBy('created_at', 'desc');

        // AMBIL DATA & GROUPING
        $rawData = $query->get();

        $groupedData = $rawData->groupBy(function ($item) {
            return $item->jenisIzin->nama ?? 'Lainnya';
        });

        // --- Logika Penentuan Label Bulan ---
        $dateFrom = $this->filters['date_from'] ?? null;
        $dateTo = $this->filters['date_to'] ?? null;
        Carbon::setLocale('id');

        if ($dateFrom && $dateTo) {
            $start = Carbon::parse($dateFrom);
            $end = Carbon::parse($dateTo);
            if ($start->month === $end->month && $start->year === $end->year) {
                $labelBulan = strtoupper($start->translatedFormat('F'));
            } else {
                $labelBulan = strtoupper($start->translatedFormat('F')) . ' - ' . strtoupper($end->translatedFormat('F'));
            }
            $tahun = $start->year;
        } elseif ($dateFrom) {
            $start = Carbon::parse($dateFrom);
            $labelBulan = strtoupper($start->translatedFormat('F'));
            $tahun = $start->year;
        } else {
            $labelBulan = strtoupper(now()->translatedFormat('F'));
            $tahun = now()->year;
        }

        return view($this->viewName, [
            'groupedData' => $groupedData,
            'bulan' => $labelBulan,
            'tahun' => $tahun,
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [];
    }
}
