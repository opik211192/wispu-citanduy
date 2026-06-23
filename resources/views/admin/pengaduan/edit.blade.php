@extends('adminlte::page')

@section('title', 'Edit Pengaduan')

@section('content_header')
<h1>Edit Pengaduan</h1>
@stop

@section('content')
<div class="mb-3">
    <a href="{{ route('data-pengaduan') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="card">
    <div class="card-header"><strong><i class="fas fa-edit"></i> Form Edit Pengaduan</strong></div>
    <div class="card-body">
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('pengaduan.update', $pengaduan->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="judul"><strong>Judul</strong></label>
                <input type="text" class="form-control" id="judul" name="judul"
                    value="{{ old('judul', $pengaduan->judul) }}" required>
            </div>

            <div class="form-group">
                <label for="uraian"><strong>Uraian</strong></label>
                <textarea class="form-control" id="uraian" name="uraian" rows="5" required>{{ old('uraian', $pengaduan->uraian) }}</textarea>
            </div>

            <div class="form-group">
                <label for="kategori_id"><strong>Kategori</strong></label>
                <select class="form-control" id="kategori_id" name="kategori_id" required>
                    @foreach ($kategoris as $kategori)
                    <option value="{{ $kategori->id }}"
                        {{ old('kategori_id', $pengaduan->kategori_id) == $kategori->id ? 'selected' : '' }}>
                        {{ $kategori->nama }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="status"><strong>Status Pelapor</strong></label>
                <select class="form-control" id="status" name="status" required>
                    <option value="Bukan Pegawai BBWS Citanduy"
                        {{ old('status', $pengaduan->status) == 'Bukan Pegawai BBWS Citanduy' ? 'selected' : '' }}>
                        Bukan Pegawai BBWS Citanduy
                    </option>
                    <option value="Pegawai BBWS Citanduy"
                        {{ old('status', $pengaduan->status) == 'Pegawai BBWS Citanduy' ? 'selected' : '' }}>
                        Pegawai BBWS Citanduy
                    </option>
                </select>
            </div>

            <div class="form-group">
                <label for="tanggal_kejadian"><strong>Tanggal Kejadian</strong></label>
                <input type="date" class="form-control" id="tanggal_kejadian" name="tanggal_kejadian"
                    value="{{ old('tanggal_kejadian', \Illuminate\Support\Carbon::parse($pengaduan->tanggal_kejadian)->format('Y-m-d')) }}"
                    required>
            </div>

            <div class="form-group">
                <label for="tempat_kejadian"><strong>Tempat Kejadian</strong></label>
                <textarea class="form-control" id="tempat_kejadian" name="tempat_kejadian" rows="3" required>{{ old('tempat_kejadian', $pengaduan->tempat_kejadian) }}</textarea>
            </div>

            <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> Simpan Perubahan</button>
        </form>
    </div>
</div>
@stop
