@extends('adminlte::page')

@section('title', 'Detail Pengaduan')

@section('content_header')
<h1>Detail Pengaduan</h1>
@stop

@section('content')
<div class="mb-3">
    <a href="{{ route('data-pengaduan') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    {{-- <a href="{{ route('pengaduan.edit', $pengaduan->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</a> --}}
</div>

{{-- Informasi Pengaduan --}}
<div class="card">
    <div class="card-header"><strong><i class="fas fa-info-circle"></i> Informasi Pengaduan</strong></div>
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th style="width: 25%">Judul</th>
                <td>{{ $pengaduan->judul }}</td>
            </tr>
            <tr>
                <th>Uraian</th>
                <td>{{ $pengaduan->uraian }}</td>
            </tr>
            <tr>
                <th>Kategori</th>
                <td>{{ $pengaduan->kategori->nama ?? '-' }}</td>
            </tr>
            <tr>
                <th>Status Pelapor</th>
                <td>{{ $pengaduan->status }}</td>
            </tr>
            <tr>
                <th>Tanggal Kejadian</th>
                <td>{{ \Illuminate\Support\Carbon::parse($pengaduan->tanggal_kejadian)->format('d-m-Y') }}</td>
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
    </div>
</div>

{{-- Pihak Terlibat --}}
<div class="card">
    <div class="card-header"><strong><i class="fas fa-users"></i> Pihak Yang Diduga Terlibat</strong></div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>Klasifikasi</th>
                    <th>Instansi</th>
                    <th>Paket Kegiatan</th>
                    <th>Alamat</th>
                    <th>No. Telepon</th>
                    <th>Peran</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pengaduan->uraianPihaks as $i => $pihak)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $pihak->nama }}</td>
                    <td>{{ $pihak->jabatan }}</td>
                    <td>{{ $pihak->klasifikasi }}</td>
                    <td>{{ $pihak->instansi }}</td>
                    <td>{{ $pihak->paket_kegiatan }}</td>
                    <td>{{ $pihak->alamat }}</td>
                    <td>{{ $pihak->no_telpon }}</td>
                    <td>{{ $pihak->peran }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Lampiran --}}
<div class="card">
    <div class="card-header"><strong><i class="fas fa-paperclip"></i> Lampiran</strong></div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>File</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pengaduan->lampirans as $i => $lampiran)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $lampiran->file_lampiran }}</td>
                    <td>{{ $lampiran->keterangan }}</td>
                    <td>
                        <button type="button" class="btn btn-info btn-sm btn-lihat"
                            data-url="{{ asset('storage/lampiran/' . $lampiran->file_lampiran) }}"
                            data-title="{{ $lampiran->file_lampiran }}">
                            <i class="fas fa-eye"></i> Lihat
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">Tidak ada lampiran</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Identitas Pelapor --}}
<div class="card">
    <div class="card-header"><strong><i class="fas fa-user-shield"></i> Identitas Pelapor</strong></div>
    <div class="card-body">
        @if ($pengaduan->identitasDiri)
        <table class="table table-bordered">
            <tr>
                <th style="width: 25%">Nama Lengkap</th>
                <td>{{ $pengaduan->identitasDiri->nama_identitas }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $pengaduan->identitasDiri->email_identitas }}</td>
            </tr>
            <tr>
                <th>No. Telepon</th>
                <td>{{ $pengaduan->identitasDiri->no_telpon_identitas }}</td>
            </tr>
            <tr>
                <th>Foto KTP</th>
                <td>
                    @if ($pengaduan->identitasDiri->foto_ktp)
                    <button type="button" class="btn btn-info btn-sm btn-lihat"
                        data-url="{{ asset('storage/identitas/' . $pengaduan->identitasDiri->foto_ktp) }}"
                        data-title="Foto KTP">
                        <i class="fas fa-image"></i> Lihat Foto KTP
                    </button>
                    @else
                    -
                    @endif
                </td>
            </tr>
            <tr>
                <th>Foto Pribadi</th>
                <td>
                    @if ($pengaduan->identitasDiri->foto_identitas)
                    <button type="button" class="btn btn-info btn-sm btn-lihat"
                        data-url="{{ asset('storage/identitas/' . $pengaduan->identitasDiri->foto_identitas) }}"
                        data-title="Foto Pribadi">
                        <i class="fas fa-image"></i> Lihat Foto Pribadi
                    </button>
                    @else
                    -
                    @endif
                </td>
            </tr>
        </table>
        @else
        <p class="text-muted mb-0">Tidak ada data identitas pelapor.</p>
        @endif
    </div>
</div>

{{-- Modal Lihat File --}}
<div class="modal fade" id="modalLihat" tabindex="-1" role="dialog" aria-labelledby="modalLihatTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLihatTitle">Lihat File</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center" id="modalLihatBody" style="min-height: 300px;"></div>
            <div class="modal-footer">
                <a href="#" id="modalLihatDownload" class="btn btn-primary" download>
                    <i class="fas fa-download"></i> Unduh
                </a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
    $(function () {
        $('.btn-lihat').on('click', function () {
            var url = $(this).data('url');
            var title = $(this).data('title');
            var ext = url.split('?')[0].split('.').pop().toLowerCase();
            var body = '';

            if (['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'].includes(ext)) {
                body = '<img src="' + url + '" class="img-fluid" alt="' + title + '">';
            } else if (ext === 'pdf') {
                body = '<iframe src="' + url + '" style="width:100%; height:70vh; border:0;"></iframe>';
            } else {
                body = '<p class="text-muted my-5">Pratinjau tidak tersedia untuk tipe file <strong>.' + ext +
                    '</strong>.<br>Silakan klik <strong>Unduh</strong> untuk membuka file.</p>';
            }

            // Nama file untuk diunduh diambil dari segmen terakhir URL
            var fileName = decodeURIComponent(url.split('?')[0].split('/').pop());

            $('#modalLihatTitle').text(title);
            $('#modalLihatBody').html(body);
            $('#modalLihatDownload').attr('href', url).attr('download', fileName);
            $('#modalLihat').modal('show');
        });

        // Bersihkan isi modal saat ditutup (hentikan pemutaran/iframe)
        $('#modalLihat').on('hidden.bs.modal', function () {
            $('#modalLihatBody').empty();
        });
    });
</script>
@stop
