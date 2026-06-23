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
        <a href="{{ route('pengaduan.index') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Tambah</a>
        <div class="input-group input-group-sm" style="max-width: 320px;">
            <input type="text" id="searchInput" class="form-control" placeholder="Cari judul / uraian / status..."
                value="{{ $search ?? '' }}">
            <div class="input-group-append">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
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
@stop

@section('js')
<script>
    $(function () {
        var baseUrl = "{{ route('data-pengaduan') }}";
        var searchTimer = null;

        // Muat data via AJAX dan ganti isi tabel tanpa reload halaman
        function loadData(url) {
            $('#tableLoading').show();
            $.ajax({
                url: url,
                type: 'GET',
                data: { search: $('#searchInput').val() },
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                success: function (response) {
                    $('#tableContainer').html(response);
                    // Perbarui URL di browser agar bisa di-bookmark / refresh
                    var params = $.param({ search: $('#searchInput').val() });
                    window.history.replaceState(null, '', baseUrl + '?' + params);
                },
                error: function () {
                    $('#tableContainer').html(
                        '<p class="text-danger text-center my-3">Gagal memuat data. Coba lagi.</p>'
                    );
                },
                complete: function () {
                    $('#tableLoading').hide();
                }
            });
        }

        // Search dengan debounce (menunggu 400ms setelah berhenti mengetik)
        $('#searchInput').on('keyup', function () {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(function () {
                loadData(baseUrl);
            }, 400);
        });

        // Klik link pagination -> ambil via AJAX (delegasi karena konten diganti)
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