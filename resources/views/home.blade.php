@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')

<div class="dashboard-header">

    <div>
        <h1>Dashboard</h1>

        <p>
            Sistem Informasi Pengaduan BBWS Citanduy
        </p>
    </div>

</div>

@stop


@section('content')

{{-- =========================================================
     FILTER DASHBOARD
========================================================= --}}

<div class="card dashboard-filter-card">

    <div class="card-header dashboard-card-header">

        <h3 class="card-title">

            <i class="fas fa-filter mr-2"></i>

            Filter Dashboard

        </h3>

    </div>


    <div class="card-body">

        <form method="GET"
              action="{{ route('home') }}"
              class="row align-items-end">

            {{-- TAHUN --}}
            <div class="col-md-4">

                <div class="form-group mb-2">

                    <label for="tahun">
                        Tahun
                    </label>

                    <select name="tahun"
                            id="tahun"
                            class="form-control">

                        @foreach ($daftarTahun as $itemTahun)

                            <option value="{{ $itemTahun }}"
                                {{ (string) $tahun === (string) $itemTahun ? 'selected' : '' }}>

                                {{ $itemTahun }}

                            </option>

                        @endforeach

                    </select>

                </div>

            </div>


            {{-- BULAN --}}
            <div class="col-md-4">

                <div class="form-group mb-2">

                    <label for="bulan">
                        Bulan
                    </label>

                    <select name="bulan"
                            id="bulan"
                            class="form-control">

                        <option value="semua"
                            {{ $bulan === 'semua' ? 'selected' : '' }}>

                            Semua Bulan

                        </option>

                        <option value="1"
                            {{ (string) $bulan === '1' ? 'selected' : '' }}>
                            Januari
                        </option>

                        <option value="2"
                            {{ (string) $bulan === '2' ? 'selected' : '' }}>
                            Februari
                        </option>

                        <option value="3"
                            {{ (string) $bulan === '3' ? 'selected' : '' }}>
                            Maret
                        </option>

                        <option value="4"
                            {{ (string) $bulan === '4' ? 'selected' : '' }}>
                            April
                        </option>

                        <option value="5"
                            {{ (string) $bulan === '5' ? 'selected' : '' }}>
                            Mei
                        </option>

                        <option value="6"
                            {{ (string) $bulan === '6' ? 'selected' : '' }}>
                            Juni
                        </option>

                        <option value="7"
                            {{ (string) $bulan === '7' ? 'selected' : '' }}>
                            Juli
                        </option>

                        <option value="8"
                            {{ (string) $bulan === '8' ? 'selected' : '' }}>
                            Agustus
                        </option>

                        <option value="9"
                            {{ (string) $bulan === '9' ? 'selected' : '' }}>
                            September
                        </option>

                        <option value="10"
                            {{ (string) $bulan === '10' ? 'selected' : '' }}>
                            Oktober
                        </option>

                        <option value="11"
                            {{ (string) $bulan === '11' ? 'selected' : '' }}>
                            November
                        </option>

                        <option value="12"
                            {{ (string) $bulan === '12' ? 'selected' : '' }}>
                            Desember
                        </option>

                    </select>

                </div>

            </div>


            {{-- TOMBOL --}}
            <div class="col-md-4">

                <div class="form-group mb-2">

                    <button type="submit"
                            class="btn btn-primary-custom">

                        <i class="fas fa-search mr-1"></i>

                        Terapkan Filter

                    </button>


                    <a href="{{ route('home') }}"
                       class="btn btn-secondary">

                        <i class="fas fa-sync-alt mr-1"></i>

                        Reset

                    </a>

                </div>

            </div>

        </form>

    </div>

</div>

{{-- =========================================================
     INFO BOX
========================================================= --}}

<div class="dashboard-info-row">

    {{-- JUMLAH LAPORAN --}}
    <div class="info-box dashboard-info-box">

        <span class="info-box-icon bg-navy-custom">
            <i class="fas fa-file-alt"></i>
        </span>

        <div class="info-box-content">

            <span class="info-box-text">
                Jumlah Laporan
            </span>

            <span class="info-box-number">
                {{ $totalPengaduanSemua }}
            </span>

        </div>

    </div>


    {{-- LAPORAN HARI INI --}}
    <div class="info-box dashboard-info-box">

        <span class="info-box-icon bg-info">
            <i class="fas fa-calendar-day"></i>
        </span>

        <div class="info-box-content">

            <span class="info-box-text">
                Laporan Masuk Hari Ini
            </span>

            <span class="info-box-number">
                {{ $totalPengaduanHariIni }}
            </span>

        </div>

    </div>


    {{-- MENUNGGU --}}
    <div class="info-box dashboard-info-box">

        <span class="info-box-icon bg-warning">
            <i class="fas fa-clock"></i>
        </span>

        <div class="info-box-content">

            <span class="info-box-text">
                Menunggu Tindakan
            </span>

            <span class="info-box-number">
                {{ $totalMenunggu }}
            </span>

        </div>

    </div>

    {{-- DITINDAKLANJUTI --}}
    <div class="info-box dashboard-info-box">

        <span class="info-box-icon bg-success">
            <i class="fas fa-check-circle"></i>
        </span>

        <div class="info-box-content">

            <span class="info-box-text">
                Ditindaklanjuti
            </span>

            <span class="info-box-number">
                {{ $totalDitindaklanjuti }}
            </span>

        </div>

    </div>
    {{-- DITOLAK --}}
    <div class="info-box dashboard-info-box">

        <span class="info-box-icon bg-danger">
            <i class="fas fa-times-circle"></i>
        </span>

        <div class="info-box-content">

            <span class="info-box-text">
                Ditolak
            </span>

            <span class="info-box-number">
                {{ $totalDitolak }}
            </span>

        </div>

    </div>

</div>


{{-- =========================================================
     GRAFIK
========================================================= --}}

<div class="row">

    {{-- GRAFIK TREN --}}
    <div class="col-lg-8">

        <div class="card dashboard-card">

            <div class="card-header dashboard-card-header">

                <h3 class="card-title">

                    <i class="fas fa-chart-line mr-2"></i>

                    Tren Pengaduan per Bulan

                </h3>

            </div>


            <div class="card-body">

                <div class="chart-container">

                    <canvas id="chartTren"></canvas>

                </div>

            </div>

        </div>

    </div>


    {{-- GRAFIK STATUS --}}
    <div class="col-lg-4">

        <div class="card dashboard-card">

            <div class="card-header dashboard-card-header">

                <h3 class="card-title">

                    <i class="fas fa-chart-pie mr-2"></i>

                    Status Pengaduan

                </h3>

            </div>


            <div class="card-body">

                <div class="chart-container">

                    <canvas id="chartStatus"></canvas>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
     PENGADUAN TERBARU
========================================================= --}}

<div class="card dashboard-card">

    <div class="card-header dashboard-card-header">

        <h3 class="card-title">

            <i class="fas fa-list-alt mr-2"></i>

            Pengaduan Terbaru

        </h3>


        <div class="card-tools">

            <a href="{{ route('data-pengaduan') }}"
               class="btn btn-sm btn-primary-custom">

                <i class="fas fa-list mr-1"></i>

                Lihat Semua

            </a>

        </div>

    </div>


    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover dashboard-table mb-0">

                <thead>

                    <tr>

                        <th width="60">
                            No
                        </th>

                        <th>
                            Kode Laporan
                        </th>

                        <th>
                            Pelapor
                        </th>

                        <th>
                            Kategori
                        </th>

                        <th>
                            Tanggal
                        </th>

                        <th>
                            Status
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse ($pengaduanTerbaru as $index => $pengaduan)

                        <tr>

                            <td>
                                {{ $index + 1 }}
                            </td>


                            <td>

                                <strong class="kode-laporan">

                                    {{ $pengaduan->kode_laporan ?? '-' }}

                                </strong>

                            </td>


                            <td>

                                {{ $pengaduan->identitasDiri->nama_identitas ?? '-' }}

                            </td>


                            <td>

                                {{ $pengaduan->kategori->nama ?? '-' }}

                            </td>


                            <td>

                                {{ $pengaduan->created_at->format('d M Y') }}

                            </td>


                            <td>

                                @if ($pengaduan->status_proses === 'Ditindaklanjuti')

                                    <span class="badge badge-success">

                                        <i class="fas fa-check mr-1"></i>

                                        Ditindaklanjuti

                                    </span>


                                @elseif ($pengaduan->status_proses === 'Diproses')

                                    <span class="badge badge-info">

                                        <i class="fas fa-spinner mr-1"></i>

                                        Diproses

                                    </span>


                                @elseif ($pengaduan->status_proses === 'Ditolak')

                                    <span class="badge badge-danger">

                                        <i class="fas fa-times mr-1"></i>

                                        Ditolak

                                    </span>

                                @elseif (! $pengaduan->is_read)

                                    <span class="badge badge-primary">

                                        <i class="fas fa-bell mr-1"></i>

                                        Baru

                                    </span>


                                @else

                                    <span class="badge badge-warning">

                                        <i class="fas fa-clock mr-1"></i>

                                        Menunggu

                                    </span>

                                @endif

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td colspan="6"
                                class="text-center py-5 text-muted">

                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>

                                Belum ada pengaduan.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@stop


{{-- =========================================================
     CSS
========================================================= --}}

@section('css')



<style>
.dashboard-filter-card {
    border-radius: 5px;
    border: 1px solid #dee2e6;
    box-shadow: 0 2px 7px rgba(0, 0, 0, 0.06);
    margin-bottom: 20px;
}

.dashboard-filter-card label {
    font-weight: 600;
    color: var(--bbws-navy);
}

:root {

    --bbws-navy: #0B2A55;

    --bbws-gold: #F2A900;

}


/* HEADER */

.dashboard-header {

    margin-bottom: 20px;

}


.dashboard-header h1 {

    margin: 0;

    font-weight: 700;

    color: var(--bbws-navy);

}


.dashboard-header p {

    margin: 5px 0 0;

    color: #6c757d;

}


/* INFO BOX */

.dashboard-info-row {

    display: flex;

    flex-wrap: wrap;

    gap: 15px;

    margin-bottom: 20px;

}


.dashboard-info-box {

    flex: 1 1 calc(20% - 15px);

    min-width: 220px;

    margin: 0;

    border-radius: 5px;

    border-left: 4px solid var(--bbws-navy);

    box-shadow: 0 2px 7px rgba(0, 0, 0, 0.08);

}


.bg-navy-custom {

    background-color: var(--bbws-navy) !important;

    color: #fff !important;

}


/* CARD */

.dashboard-card {

    border-radius: 5px;

    border: 1px solid #dee2e6;

    box-shadow: 0 2px 7px rgba(0, 0, 0, 0.06);

    margin-bottom: 20px;

}


.dashboard-card-header {

    background-color: var(--bbws-navy) !important;

    color: #fff !important;

    border-bottom: 3px solid var(--bbws-gold);

}


.dashboard-card-header .card-title {

    font-weight: 700;

}


/* BUTTON */

.btn-primary-custom {

    background-color: var(--bbws-navy) !important;

    border-color: var(--bbws-navy) !important;

    color: #fff !important;

}


/* CHART */

.chart-container {

    position: relative;

    height: 320px;

    width: 100%;

}


/* TABLE */

.dashboard-table {

    margin-bottom: 0;

}


.dashboard-table thead th {

    background-color: #f4f6f9;

    color: var(--bbws-navy);

    font-weight: 700;

    border-top: none;

    white-space: nowrap;

}


.dashboard-table tbody td {

    vertical-align: middle;

}


.dashboard-table tbody tr:hover {

    background-color: #f8f9fa;

}


.kode-laporan {

    color: var(--bbws-navy);

}


.dashboard-table .badge {

    padding: 6px 9px;

    font-size: 11px;

}

/* Animasi ringan saat dashboard dibuka */
@keyframes dashboard-muncul {
    from {
        opacity: 0;
        transform: translateY(8px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.dashboard-header,
.dashboard-filter-card,
.dashboard-info-box,
.dashboard-card {
    animation: dashboard-muncul 420ms ease-out both;
}

.dashboard-filter-card { animation-delay: 40ms; }
.dashboard-info-box:nth-child(1) { animation-delay: 80ms; }
.dashboard-info-box:nth-child(2) { animation-delay: 120ms; }
.dashboard-info-box:nth-child(3) { animation-delay: 160ms; }
.dashboard-info-box:nth-child(4) { animation-delay: 200ms; }
.dashboard-info-box:nth-child(5) { animation-delay: 240ms; }

.dashboard-info-box,
.dashboard-card,
.btn-primary-custom,
.dashboard-filter-card .btn-secondary {
    transition: transform 180ms ease, box-shadow 180ms ease;
}

.dashboard-info-box:hover,
.dashboard-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 14px rgba(0, 0, 0, 0.11);
}

.btn-primary-custom:hover,
.dashboard-filter-card .btn-secondary:hover {
    transform: translateY(-1px);
}

@media (prefers-reduced-motion: reduce) {
    .dashboard-header,
    .dashboard-filter-card,
    .dashboard-info-box,
    .dashboard-card,
    .btn-primary-custom,
    .dashboard-filter-card .btn-secondary {
        animation: none !important;
        transition: none !important;
    }
}


/* SIDEBAR */

/* =====================================================
   SIDEBAR - FORCE BBWS CITANDUY NAVY BLUE
===================================================== */

/* Background utama sidebar */
html body .wrapper .main-sidebar,
html body .wrapper .main-sidebar::before,
html body .wrapper .main-sidebar .sidebar {
    background: #0B2A55 !important;
    background-color: #0B2A55 !important;
}

/* Header logo */
html body .main-sidebar .brand-link {
    background: #0B2A55 !important;
    background-color: #0B2A55 !important;
    color: #FFFFFF !important;
    border-bottom: 1px solid rgba(255,255,255,.15) !important;
}

/* Logo dan teks brand */
html body .main-sidebar .brand-link .brand-text,
html body .main-sidebar .brand-link .brand-image {
    color: #FFFFFF !important;
}

/* Semua menu sidebar */
html body .main-sidebar .nav-sidebar .nav-link {
    color: #FFFFFF !important;
    background-color: transparent !important;
}

/* Ikon menu */
html body .main-sidebar .nav-sidebar .nav-link i,
html body .main-sidebar .nav-sidebar .nav-link p {
    color: #FFFFFF !important;
}

/* Hover menu */
html body .main-sidebar .nav-sidebar .nav-link:hover {
    background-color: rgba(255,255,255,.12) !important;
    color: #FFFFFF !important;
}

/* Menu aktif utama */
html body .main-sidebar .nav-sidebar > .nav-item > .nav-link.active {
    background-color: #F2A900 !important;
    color: #0B2A55 !important;
}

/* Ikon dan teks menu aktif */
html body .main-sidebar .nav-sidebar > .nav-item > .nav-link.active i,
html body .main-sidebar .nav-sidebar > .nav-item > .nav-link.active p {
    color: #0B2A55 !important;
}

/* Submenu */
html body .main-sidebar .nav-treeview .nav-link {
    color: #FFFFFF !important;
}

/* Submenu aktif */
html body .main-sidebar .nav-treeview .nav-link.active {
    background-color: rgba(242,169,0,.25) !important;
    color: #FFFFFF !important;
}

/* Sidebar scrollbar */
html body .main-sidebar .sidebar::-webkit-scrollbar-thumb {
    background-color: rgba(255,255,255,.3);
}



/* RESPONSIVE */

@media (max-width: 991px) {

    .dashboard-info-box {

        flex: 1 1 calc(50% - 15px);

    }

}


@media (max-width: 575px) {

    .dashboard-info-box {

        flex: 1 1 100%;

    }


    .chart-container {

        height: 280px;

    }

}

</style>

@stop


{{-- =========================================================
     JAVASCRIPT / CHART
========================================================= --}}

@section('js')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<script>

$(document).ready(function () {


    /* =====================================================
       GRAFIK TREN
    ===================================================== */

    const chartTren =
        document.getElementById('chartTren');


    if (chartTren) {

        new Chart(chartTren, {

            type: 'line',

            data: {

                labels: @json($chartLabels),

                datasets: [{

                    label: @json($chartTitle),

                    data: @json($chartData),

                    tension: 0.3,

                    fill: true,

                    borderWidth: 2,

                    borderColor: '#0B2A55',

                    backgroundColor:
                        'rgba(11, 42, 85, 0.10)',

                    pointRadius: 4,

                    pointHoverRadius: 6

                }]

            },


            options: {

                responsive: true,

                maintainAspectRatio: false,

                plugins: {

                    legend: {

                        display: true,

                        position: 'top'

                    }

                },


                scales: {

                    y: {

                        beginAtZero: true,

                        ticks: {

                            precision: 0

                        }

                    }

                }

            }

        });

    }


    /* =====================================================
       GRAFIK STATUS
    ===================================================== */

    const chartStatus =
        document.getElementById('chartStatus');


    if (chartStatus) {

        new Chart(chartStatus, {

            type: 'doughnut',

            data: {

                labels: [

                    'Ditindaklanjuti',

                    'Baru',

                    'Menunggu',

                    'Ditolak'

                ],


                datasets: [{

                    data: [

                        {{ $totalDitindaklanjuti }},

                        {{ $totalBaru }},

                        {{ $totalMenunggu }},

                        {{ $totalDitolak }}

                    ],


                    backgroundColor: [

                        '#28a745',

                        '#17a2b8',

                        '#F2A900',

                        '#dc3545'

                    ],


                    borderWidth: 2

                }]

            },


            options: {

                responsive: true,

                maintainAspectRatio: false,

                cutout: '60%',


                plugins: {

                    legend: {

                        position: 'bottom'

                    }

                }

            }

        });

    }

});

</script>

@stop

        
