@extends('adminlte::page')

@section('title', 'Data Laporan Pengaduan')

@section('content_header')
<h1>Data Laporan Pengaduan</h1>
@stop

@section('content')
@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">

        <a href="{{ route('pengaduan.index') }}" class="btn btn-success btn-sm mb-2">
            <i class="fas fa-plus"></i> Tambah
        </a>

<div class="d-flex align-items-center mb-2 ml-auto" style="gap: 8px;">

            <!-- Filter Periode Tanggal -->
            <div class="d-flex align-items-center" style="gap: 5px;">
                <input type="date"
                    id="tanggalMulai"
                    class="form-control form-control-sm"
                    value="{{ $tanggalMulai ?? '' }}"
                    title="Tanggal Mulai"
                    style="width: 150px;">

                <span class="text-muted">s/d</span>

                <input type="date"
                    id="tanggalAkhir"
                    class="form-control form-control-sm"
                    value="{{ $tanggalAkhir ?? '' }}"
                    title="Tanggal Akhir"
                    style="width: 150px;">

                <!-- TOMBOL RESET DI SINI -->
                <button type="button"
                        id="btnResetFilter"
                        class="btn btn-secondary btn-sm"
                        title="Reset Filter">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div>

            <!-- Pencarian -->
            <div class="input-group input-group-sm" style="width: 320px;">
                <input type="text"
                    id="searchInput"
                    class="form-control"
                    placeholder="Cari judul / uraian / status..."
                    value="{{ $search ?? '' }}">

                <div class="input-group-append">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                </div>
            </div>

        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                {{-- Indikator loading kecil --}}
                <div id="tableLoading" class="text-center text-muted my-2" style="display: none;">
                    <i class="fas fa-spinner fa-spin"></i> Memuat...
                </div>
                <div id="tableContainer">
                    @include('admin.pengaduan._table')
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
{{-- Add here extra stylesheets --}}
{{--
<link rel="stylesheet" href="/css/admin_custom.css"> --}}

<style>
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
</style>
@stop

@section('js')
<script>
    $(function () {

        var baseUrl = "{{ route('data-pengaduan') }}";
        var searchTimer = null;

        // ==========================================
        // LOAD DATA
        // ==========================================
        function loadData(url) {

            $('#tableLoading').show();

            $.ajax({
                url: url,
                type: 'GET',

                data: {
                    search: $('#searchInput').val(),
                    tanggal_mulai: $('#tanggalMulai').val(),
                    tanggal_akhir: $('#tanggalAkhir').val()
                },

                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },

                success: function (response) {

                    $('#tableContainer').html(response);

                    // Update URL browser
                    var params = $.param({
                        search: $('#searchInput').val(),
                        tanggal_mulai: $('#tanggalMulai').val(),
                        tanggal_akhir: $('#tanggalAkhir').val()
                    });

                    window.history.replaceState(
                        null,
                        '',
                        baseUrl + '?' + params
                    );
                },

                error: function (xhr) {

                    console.log(xhr.responseText);

                    $('#tableContainer').html(
                        '<p class="text-danger text-center my-3">' +
                        'Gagal memuat data. Coba lagi.' +
                        '</p>'
                    );
                },

                complete: function () {
                    $('#tableLoading').hide();
                }
            });
        }


        // ==========================================
        // SEARCH
        // ==========================================
        $('#searchInput').on('keyup', function () {

            clearTimeout(searchTimer);

            searchTimer = setTimeout(function () {
                loadData(baseUrl);
            }, 400);

        });


        // ==========================================
        // FILTER TANGGAL MULAI & AKHIR
        // ==========================================
        $('#tanggalMulai, #tanggalAkhir').on('change', function () {

            loadData(baseUrl);

        });


        // ==========================================
        // RESET FILTER
        // ==========================================
        $('#btnResetFilter').on('click', function () {

            $('#tanggalMulai').val('');
            $('#tanggalAkhir').val('');

            loadData(baseUrl);

        });


        // ==========================================
        // PAGINATION
        // ==========================================
        $('#tableContainer').on('click', '.pagination a', function (e) {

            e.preventDefault();

            var url = $(this).attr('href');

            if (url) {
                loadData(url);
            }

        });

    });
</script>
@stop