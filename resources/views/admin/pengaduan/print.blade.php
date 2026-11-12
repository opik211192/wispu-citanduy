@extends('adminlte::page')

@section('title', 'Print Pengaduan')

@section('content_header')

<h1>Print / Tindak Lanjut Pengaduan</h1>
@stop

@section('content')

{{-- =========================================================
TOMBOL NAVIGASI
========================================================= --}}

<div class="mb-3 no-print">

    <div class="mb-2">

        <a href="{{ route('data-pengaduan') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Kembali
        </a>

        <button type="button"
                class="btn btn-primary"
                id="btnCetak">
            <i class="fas fa-print"></i>
            Cetak
        </button>

    </div>

</div>

{{-- =========================================================
DATA PENGADUAN
========================================================= --}}

<div class="card">

<div class="card-header text-center">
    <h4>
        <strong>LAPORAN PENGADUAN</strong>
    </h4>

    <div>BBWS CITANDUY</div>
</div>

<div class="card-body">

    <table class="table table-bordered">
        <tr>
            <th style="width: 25%">Kode Laporan</th>
            <td>{{ $pengaduan->kode_laporan }}</td>
        </tr>

        <tr>
            <th>Judul</th>
            <td>{{ $pengaduan->judul }}</td>
        </tr>

        <tr>
            <th>Kategori</th>
            <td>{{ $pengaduan->kategori->nama ?? '-' }}</td>
        </tr>

        <tr>
            <th>Uraian</th>
            <td>{{ $pengaduan->uraian }}</td>
        </tr>

        <tr>
            <th>Status</th>
            <td>{{ $pengaduan->status }}</td>
        </tr>

        <tr>
            <th>Tanggal Kejadian</th>
            <td>
                {{ \Illuminate\Support\Carbon::parse($pengaduan->tanggal_kejadian)->format('d-m-Y') }}
            </td>
        </tr>

        <tr>
            <th>Tempat Kejadian</th>
            <td>{{ $pengaduan->tempat_kejadian }}</td>
        </tr>

        <tr>
            <th>Tanggal Lapor</th>
            <td>{{ $pengaduan->created_at->format('d-m-Y H:i') }}</td>
        </tr>
    </table>


    {{-- =====================================================
         URAIAN PIHAK
    ====================================================== --}}

    @if ($pengaduan->uraianPihaks && $pengaduan->uraianPihaks->count())

        <h5 class="mt-4">
            <strong>Pihak Terkait</strong>
        </h5>

        <table class="table table-bordered">
            @foreach ($pengaduan->uraianPihaks as $pihak)
                <tr>
                    <td>
                        {{ $pihak->uraian ?? $pihak->nama ?? '-' }}
                    </td>
                </tr>
            @endforeach
        </table>

    @endif


    {{-- =====================================================
         IDENTITAS PELAPOR
    ====================================================== --}}

    <h5 class="mt-4">
        <strong>Identitas Pelapor</strong>
    </h5>

    @if ($pengaduan->identitasDiri)

        <table class="table table-bordered">

            <tr>
                <th style="width: 25%">Nama</th>
                <td>{{ $pengaduan->identitasDiri->nama_identitas }}</td>
            </tr>

            <tr>
                <th>No. Telepon</th>
                <td>{{ $pengaduan->identitasDiri->no_telpon_identitas }}</td>
            </tr>

            <tr>
                <th>Email</th>
                <td>{{ $pengaduan->identitasDiri->email_identitas }}</td>
            </tr>



        </table>

    @else

        <p class="text-muted">
            Tidak ada data identitas pelapor.
        </p>

    @endif

</div>

</div>

{{-- =========================================================
HALAMAN 2
DOKUMENTASI IDENTITAS PELAPOR
========================================================= --}}

@if ($pengaduan->identitasDiri)

<div class="print-page-break"></div>

<div class="card identity-page">


<div class="card-header text-center">
    <h5 class="mb-0">
        <strong>DOKUMENTASI IDENTITAS PELAPOR</strong>
    </h5>
</div>

<div class="card-body">

    <div class="row">

        {{-- FOTO PRIBADI --}}
        @if ($pengaduan->identitasDiri->foto_identitas)

            <div class="col-md-6 mb-3">

                <div class="card border">

                    <div class="card-header text-center">
                        <strong>Foto Pelapor</strong>
                    </div>

                    <div class="card-body text-center">

                        <img
                            src="{{ asset('storage/identitas/' . $pengaduan->identitasDiri->foto_identitas) }}"
                            class="print-identity-image"
                            alt="Foto Pelapor"
                        >

                    </div>

                </div>

            </div>

        @endif


        {{-- FOTO KTP --}}
        @if ($pengaduan->identitasDiri->foto_ktp)

            <div class="col-md-6 mb-3">

                <div class="card border">

                    <div class="card-header text-center">
                        <strong>Foto KTP</strong>
                    </div>

                    <div class="card-body text-center">

                        <img
                            src="{{ asset('storage/identitas/' . $pengaduan->identitasDiri->foto_ktp) }}"
                            class="print-identity-image"
                            alt="Foto KTP"
                        >

                    </div>

                </div>

            </div>

        @endif

    </div>

</div>


</div>

@endif

{{-- =========================================================
HALAMAN 3
LAMPIRAN PENGADUAN
========================================================= --}}

<div class="print-page-break"></div>

<div class="card attachment-page">


<div class="card-header text-center">
    <h5 class="mb-0">
        <strong>LAMPIRAN PENGADUAN</strong>
    </h5>
</div>

<div class="card-body">

    @if ($pengaduan->lampirans && $pengaduan->lampirans->count())

        <div class="row">

            @foreach ($pengaduan->lampirans as $lampiran)

                @if ($lampiran->file_lampiran)

                    <div class="col-md-6 mb-4">

                        <div class="card border">

                            <div class="card-header text-center">
                                <strong>Lampiran</strong>
                            </div>

                            <div class="card-body text-center">

                                @if (preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $lampiran->file_lampiran))

                                    <img
                                        src="{{ asset('storage/lampiran/' . $lampiran->file_lampiran) }}"
                                        class="print-attachment-image"
                                        alt="Lampiran Pengaduan"
                                    >

                                @else

                                    <div class="py-4">

                                        <i class="fas fa-file fa-3x mb-3"></i>

                                        <p>
                                            {{ basename($lampiran->file_lampiran) }}
                                        </p>

                                        <a
                                            href="{{ asset('storage/lampiran/' . $lampiran->file_lampiran) }}"
                                            target="_blank"
                                            class="btn btn-sm btn-primary no-print">
                                            <i class="fas fa-download"></i>
                                            Buka File
                                        </a>

                                    </div>

                                @endif

                            </div>

                        </div>

                    </div>

                @endif

            @endforeach

        </div>

    @else

        <p class="text-muted">
            Tidak ada lampiran pengaduan.
        </p>

    @endif

</div>
</div>

    {{-- Tombol aksi status --}}
<div class="no-print mt-4 mb-4 text-left">

    <button type="button"
            class="btn btn-success"
            id="btnTindakLanjut"
            @if(in_array($pengaduan->status_proses, ['Ditindaklanjuti', 'Ditolak']))
                disabled
            @endif>
        <i class="fas fa-check"></i>
        Ditindaklanjuti
    </button>

    <button type="button"
            class="btn btn-danger"
            id="btnDitolak"
            @if(in_array($pengaduan->status_proses, ['Ditindaklanjuti', 'Ditolak']))
                disabled
            @endif>
        <i class="fas fa-times"></i>
        Ditolak
    </button>

</div>


@stop

@section('css')
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function () {

    // =========================
    // CETAK
    // =========================
    $('#btnCetak').click(function () {
        window.print();
    });


    // =========================
    // TINDAK LANJUT
    // =========================
    $('#btnTindakLanjut').click(function () {

        var button = $(this);

        Swal.fire({
            title: 'Tindak Lanjut Pengaduan?',
            text: 'Notifikasi WhatsApp akan dikirim kepada pelapor.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Tindak Lanjut',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then(function (result) {

            if (!result.isConfirmed) {
                return;
            }

            button.prop('disabled', true);
            $('#btnDitolak').prop('disabled', true);

            $.ajax({
                url: "{{ route('pengaduan.tindak-lanjut', $pengaduan->id) }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },

                success: function (response) {

                    if (response.success) {

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Pengaduan sudah ditindaklanjuti.',
                            confirmButtonText: 'OK',
                            allowOutsideClick: false
                        }).then(function () {
                            location.reload();
                        });

                    } else {

                        button.prop('disabled', false);
                        $('#btnDitolak').prop('disabled', false);

                        Swal.fire({
                            icon: 'warning',
                            title: 'Tidak Dapat Diproses',
                            text: response.message,
                            confirmButtonText: 'OK'
                        });
                    }
                },

                error: function (xhr) {

                    button.prop('disabled', false);
                    $('#btnDitolak').prop('disabled', false);

                    var message = 'Terjadi kesalahan saat memproses pengaduan.';

                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: message,
                        confirmButtonText: 'OK'
                    });
                }
            });
        });
    });


    // =========================
    // DITOLAK
    // =========================
$('#btnDitolak').click(function () {

    var button = $(this);

    Swal.fire({
        title: 'Tolak Pengaduan?',

        html: `
            <div style="text-align: left;">

                <div class="alert alert-light border mb-3">
                    <div>
                        <strong>Kode Laporan:</strong>
                        #{{ $pengaduan->kode_laporan }}
                    </div>

                    <div class="mt-2">
                        <strong>Judul:</strong>
                        {{ $pengaduan->judul }}
                    </div>
                </div>

                <label for="pesan_penolakan">
                    Alasan Penolakan
                </label>

                <textarea
                    id="pesan_penolakan"
                    class="form-control"
                    rows="5"
                    placeholder="Tuliskan alasan pengaduan ditolak..."
                ></textarea>

            </div>
        `,

        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Tolak & Kirim',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        focusConfirm: false,

        preConfirm: function () {

            var pesan =
                $('#pesan_penolakan').val().trim();

            if (!pesan) {

                Swal.showValidationMessage(
                    'Alasan penolakan wajib diisi.'
                );

                return false;
            }

            return pesan;
        }

    }).then(function (result) {

        if (!result.isConfirmed) {
            return;
        }

        var pesanPenolakan = result.value;

        button.prop('disabled', true);
        $('#btnTindakLanjut').prop('disabled', true);

        Swal.fire({
            title: 'Memproses...',
            text: 'Mengirim notifikasi WhatsApp kepada pelapor.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: function () {
                Swal.showLoading();
            }
        });

        $.ajax({

            url: "{{ route('pengaduan.ditolak', $pengaduan->id) }}",

            type: "POST",

            data: {
                _token: "{{ csrf_token() }}",
                pesan_penolakan: pesanPenolakan
            },

            success: function (response) {

                if (response.success) {

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Pengaduan sudah ditolak dan notifikasi WhatsApp telah dikirim.',
                        confirmButtonText: 'OK',
                        allowOutsideClick: false
                    }).then(function () {
                        location.reload();
                    });

                } else {

                    button.prop('disabled', false);
                    $('#btnTindakLanjut').prop('disabled', false);

                    Swal.fire({
                        icon: 'warning',
                        title: 'Tidak Dapat Diproses',
                        text: response.message,
                        confirmButtonText: 'OK'
                    });
                }
            },

            error: function (xhr) {

                button.prop('disabled', false);
                $('#btnTindakLanjut').prop('disabled', false);

                var message =
                    'Terjadi kesalahan saat memproses pengaduan.';

                if (
                    xhr.responseJSON &&
                    xhr.responseJSON.message
                ) {
                    message = xhr.responseJSON.message;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: message,
                    confirmButtonText: 'OK'
                });
            }
        });

        });

    });

});
</script>
<style>
/* STYLE PERTAMA YANG SUDAH ADA DI SINI */
</style>

<style>

.print-image {
    max-width: 100%;
    max-height: 500px;
    width: auto;
    height: auto;
    object-fit: contain;
}

.print-identity-image {
    max-width: 100%;
    max-height: 400px;
    width: auto;
    height: auto;
    object-fit: contain;
}

.print-attachment-image {
    max-width: 100%;
    max-height: 550px;
    width: auto;
    height: auto;
    object-fit: contain;
}

@media print {

    @page {
        margin: 12mm;
    }

    .no-print,
    .main-header,
    .main-sidebar,
    .main-footer {
        display: none !important;
    }

    .content-wrapper {
        margin-left: 0 !important;
    }

    .card {
        border: none !important;
        box-shadow: none !important;
    }

    .print-page-break {
        page-break-before: always;
        break-before: page;
    }

    .identity-page {
        page-break-inside: avoid;
        break-inside: avoid;
    }

    .identity-page .card {
        page-break-inside: avoid;
        break-inside: avoid;
    }

    .print-identity-image {
        max-width: 100%;
        max-height: 380px;
        width: auto;
        height: auto;
        object-fit: contain;
    }

    .attachment-page {
        page-break-inside: auto;
    }

    .attachment-page .card {
        page-break-inside: avoid;
        break-inside: avoid;
    }

    .print-attachment-image {
        max-width: 100%;
        max-height: 600px;
        width: auto;
        height: auto;
        object-fit: contain;
    }

    table {
        page-break-inside: avoid;
    }

}

</style>

@stop