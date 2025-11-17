<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ArsipPenelitianKesehatan;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $total = ArsipPenelitianKesehatan::count();
        $pending = ArsipPenelitianKesehatan::where('status', 'pending')->count();
        $valid = ArsipPenelitianKesehatan::where('status', 'valid')->count();
        $revisi = ArsipPenelitianKesehatan::where('status', 'revisi')->count();
        $today = ArsipPenelitianKesehatan::whereDate('created_at', Carbon::today())->count();
        $users = User::count();

        $stats = [
            'total'   => $total,
            'pending' => $pending,
            'valid'   => $valid,
            'revisi'  => $revisi,
            'today'   => $today,
            'users'   => $users,
        ];

        $recentActivities = ArsipPenelitianKesehatan::with('user:id,name')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $chartFilter = $request->input('chart_filter', 'bulan_ini');

        $chartData = $this->getChartData($chartFilter);

        return view('pages.admin.index', [
            'stats' => $stats,
            'recentActivities' => $recentActivities,
            'chartData' => $chartData,
            'filters' => $request->all()
        ]);
    }

    private function getChartData($filter)
    {
        $labels = [];
        $counts = [];

        if ($filter === 'tahun_ini') {
            $startDate = Carbon::now()->startOfYear();
            $endDate = Carbon::now()->endOfYear();

            $dokumenPerBulan = ArsipPenelitianKesehatan::whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('bulan')
                ->orderBy('bulan', 'asc')
                ->get([
                    DB::raw('DATE_FORMAT(created_at, "%Y-%m") as bulan'),
                    DB::raw('count(*) as jumlah')
                ])
                ->pluck('jumlah', 'bulan');

            $period = CarbonPeriod::create($startDate, '1 month', $endDate);
            foreach ($period as $date) {
                $bulanString = $date->format('Y-m');
                $labels[] = $date->format('M');
                $counts[] = $dokumenPerBulan[$bulanString] ?? 0;
            }

        } elseif ($filter === '3_bulan_terakhir') {
            $endDate = Carbon::now()->startOfMonth();
            $startDate = Carbon::now()->subMonths(2)->startOfMonth();

            $dokumenPerBulan = ArsipPenelitianKesehatan::whereBetween('created_at', [$startDate, $endDate->endOfMonth()])
                ->groupBy('bulan')
                ->orderBy('bulan', 'asc')
                ->get([
                    DB::raw('DATE_FORMAT(created_at, "%Y-%m") as bulan'),
                    DB::raw('count(*) as jumlah')
                ])
                ->pluck('jumlah', 'bulan');

            $period = CarbonPeriod::create($startDate, '1 month', $endDate);
            foreach ($period as $date) {
                $bulanString = $date->format('Y-m');
                $labels[] = $date->format('M Y'); // Label: "Sep 2025", "Okt 2025"
                $counts[] = $dokumenPerBulan[$bulanString] ?? 0;
            }

        } else {
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();

            $dokumenPerHari = ArsipPenelitianKesehatan::whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('tanggal')
                ->orderBy('tanggal', 'asc')
                ->get([
                    DB::raw('DATE(created_at) as tanggal'),
                    DB::raw('count(*) as jumlah')
                ])
                ->pluck('jumlah', 'tanggal'); // Hasil: ['2025-11-01' => 5]

            $period = CarbonPeriod::create($startDate, '1 day', $endDate);
            foreach ($period as $date) {
                $tanggalString = $date->format('Y-m-d');
                $labels[] = $date->format('d M'); // Label: "01 Nov"
                $counts[] = $dokumenPerHari[$tanggalString] ?? 0;
            }
        }

        return ['labels' => $labels, 'counts' => $counts];
    }
}
