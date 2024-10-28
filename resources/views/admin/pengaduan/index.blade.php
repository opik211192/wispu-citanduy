@extends('adminlte::page')

@section('title', 'Data Laporan Pengaduan')

@section('content_header')
<h1>Data Laporan Pengaduan</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <a href="{{ route('pengaduan.index') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Tambah</a>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="tablePengaduan">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tgl. Lapor</th>
                                <th>Judul</th>
                                <th>Uraian</th>
                                <th>Status</th>
                                <th>#</th>
                            </tr>
                        </thead>    
                        <tbody>
                            @foreach ($pengaduans as $index => $pengaduan)
                            <tr>
                                <td>{{ $loop->iteration + ($pengaduans->currentPage() - 1) * $pengaduans->perPage() }}</td>
                                <td>{{ $pengaduan->created_at->format('d-m-Y') }}</td>
                                <td>{{ $pengaduan->judul }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($pengaduan->uraian, 50) }}</td>
                                <td>{{ $pengaduan->status }}</td>
                                <td>
                                    <a href="" class="btn btn-info btn-sm">Detail</a>
                                    <a href="" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="" method="POST"
                                        style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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
    
</script>
@stop