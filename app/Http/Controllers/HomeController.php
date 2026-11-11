<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Pengaduan;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     */
    public function index(Request $request)
    {
        // =====================================================
        // FILTER TAHUN DAN BULAN
        // =====================================================

        $tahun = $request->input(
            'tahun',
            Carbon::now()->year
        );

        $bulan = $request->input(
            'bulan',
            'semua'
        );


        // =====================================================
        // QUERY DASAR SESUAI FILTER
        // =====================================================

        $queryFilter = Pengaduan::query()
            ->whereYear('created_at', $tahun);


        if ($bulan !== 'semua') {

            $queryFilter->whereMonth(
                'created_at',
                $bulan
            );

        }


        // =====================================================
        // TOTAL PENGADUAN
        // =====================================================

        $totalPengaduanSemua =
            (clone $queryFilter)->count();


        // =====================================================
        // LAPORAN MASUK HARI INI
        // =====================================================

        $totalPengaduanHariIni =
            Pengaduan::whereDate(
                'created_at',
                Carbon::today()
            )->count();


        // =====================================================
        // STATUS MENUNGGU
        // =====================================================

        $totalMenunggu =
            (clone $queryFilter)
            ->where(
                'status_proses',
                'Menunggu'
            )
            ->count();


        // =====================================================
        // STATUS DIPROSES
        // =====================================================

        $totalDiproses =
            (clone $queryFilter)
            ->where(
                'status_proses',
                'Diproses'
            )
            ->count();

        // Pengaduan baru adalah laporan yang belum dibaca admin.
        $totalBaru =
            (clone $queryFilter)
            ->where('is_read', false)
            ->count();


        // =====================================================
        // STATUS DITINDAKLANJUTI
        // =====================================================

        $totalDitindaklanjuti =
            (clone $queryFilter)
            ->where(
                'status_proses',
                'Ditindaklanjuti'
            )
            ->count();


        // =====================================================
        // STATUS DITOLAK
        // =====================================================

        $totalDitolak =
            (clone $queryFilter)
            ->where(
                'status_proses',
                'Ditolak'
            )
            ->count();


        // =====================================================
        // GRAFIK
        // =====================================================

        $chartLabels = [];
        $chartData = [];
        $chartTitle = '';


        // =====================================================
        // JIKA SEMUA BULAN
        // GRAFIK PER BULAN
        // =====================================================

        if ($bulan === 'semua') {

            $chartLabels = [
                'Jan',
                'Feb',
                'Mar',
                'Apr',
                'Mei',
                'Jun',
                'Jul',
                'Agu',
                'Sep',
                'Okt',
                'Nov',
                'Des'
            ];


            $chartData = [];


            for ($i = 1; $i <= 12; $i++) {

                $chartData[] =
                    Pengaduan::whereYear(
                        'created_at',
                        $tahun
                    )
                    ->whereMonth(
                        'created_at',
                        $i
                    )
                    ->count();

            }


            $chartTitle =
                'Tren Pengaduan per Bulan Tahun ' . $tahun;

        }


        // =====================================================
        // JIKA BULAN DIPILIH
        // GRAFIK PER HARI
        // =====================================================

        else {

            $jumlahHari =
                Carbon::create(
                    $tahun,
                    $bulan,
                    1
                )->daysInMonth;


            for ($hari = 1; $hari <= $jumlahHari; $hari++) {

                $chartLabels[] = $hari;


                $chartData[] =
                    Pengaduan::whereYear(
                        'created_at',
                        $tahun
                    )
                    ->whereMonth(
                        'created_at',
                        $bulan
                    )
                    ->whereDay(
                        'created_at',
                        $hari
                    )
                    ->count();

            }


            $namaBulan =
                Carbon::create(
                    $tahun,
                    $bulan,
                    1
                )->translatedFormat('F');


            $chartTitle =
                'Tren Pengaduan ' .
                $namaBulan .
                ' ' .
                $tahun;

        }


        // =====================================================
        // 10 PENGADUAN TERBARU
        // =====================================================

        $pengaduanTerbaru =
            (clone $queryFilter)
            ->with([
                'kategori',
                'identitasDiri'
            ])
            ->latest('created_at')
            ->take(10)
            ->get();


        // =====================================================
        // DAFTAR TAHUN OTOMATIS
        // =====================================================

        $daftarTahun =
            Pengaduan::selectRaw(
                'YEAR(created_at) as tahun'
            )
            ->distinct()
            ->orderBy(
                'tahun',
                'desc'
            )
            ->pluck('tahun');


        // =====================================================
        // KIRIM DATA KE HOME.BLADE.PHP
        // =====================================================

        return view(
            'home',
            compact(
                'totalPengaduanSemua',
                'totalPengaduanHariIni',
                'totalMenunggu',
                'totalDiproses',
                'totalBaru',
                'totalDitindaklanjuti',
                'totalDitolak',
                'chartLabels',
                'chartData',
                'chartTitle',
                'pengaduanTerbaru',
                'tahun',
                'bulan',
                'daftarTahun'
            )
        );
    }
}
