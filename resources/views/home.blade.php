@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>Dashboard</h1>
@stop

@section('content')
{{-- Container flex untuk menampilkan info-box --}}
<div class="d-flex flex-wrap">
    {{-- Menampilkan informasi jumlah data laporan yang masuk --}}
    <div class="info-box mr-3" style="flex: 1 1 200px; max-width: 300px;">
        <span class="info-box-icon bg-info"><i class="fas fa-file-alt"></i></span>
        <div class="info-box-content">
            <span class="info-box-text">Jumlah Laporan</span>
            <span class="info-box-number">{{ $totalPengaduanSemua }}</span> {{-- Total laporan keseluruhan --}}
        </div>
    </div>

    {{-- Menampilkan informasi laporan yang masuk hari ini --}}
    <div class="info-box" style="flex: 1 1 200px; max-width: 300px;">
        <span class="info-box-icon bg-success"><i class="fas fa-calendar-day"></i></span>
        <div class="info-box-content">
            <span class="info-box-text">Laporan Masuk Hari Ini</span>
            <span class="info-box-number">{{ $totalPengaduanHariIni }}</span> {{-- Laporan hari ini --}}
        </div>
    </div>
</div>
@stop

@section('css')
{{-- Add here extra stylesheets --}}
<style>
    /* Sesuaikan margin atau padding jika perlu */
    .info-box {
        margin-bottom: 20px;
    }
</style>
@stop

@section('js')
<script>
    console.log("Hi, I'm using the Laravel-AdminLTE package!"); 
</script>
@stop