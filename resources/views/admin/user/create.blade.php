@extends('adminlte::page')

@section('title', 'Tambah User')

@section('content_header')
<h1>Tambah User</h1>
@stop

@section('content')
<div class="mb-3">
    <a href="{{ route('user.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="card">
    <div class="card-header"><strong><i class="fas fa-user-plus"></i> Form Tambah User</strong></div>
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

        <form action="{{ route('user.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name"><strong>Nama Lengkap</strong></label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                <small class="text-muted">Nama lengkap (keterangan saja, bukan untuk login).</small>
            </div>

            <div class="form-group">
                <label for="username"><strong>Username</strong></label>
                <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}" required>
                <small class="text-muted">Dipakai untuk login.</small>
            </div>

            <div class="form-group">
                <label for="email"><strong>Email</strong></label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <label for="password"><strong>Password</strong></label>
                <input type="password" class="form-control" id="password" name="password" required>
                <small class="text-muted">Minimal 6 karakter.</small>
            </div>

            <div class="form-group">
                <label for="password_confirmation"><strong>Konfirmasi Password</strong></label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
            </div>

            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan</button>
        </form>
    </div>
</div>
@stop
