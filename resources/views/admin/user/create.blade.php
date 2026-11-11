@extends('adminlte::page')

@section('title', 'Tambah User')

@section('content_header')
<h1>Tambah User</h1>
@stop

@section('content')
<div class="mb-3">
    <a href="{{ route('user.index') }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="card">
    <div class="card-header">
        <strong><i class="fas fa-user-plus"></i> Form Tambah User</strong>
    </div>
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
                <input type="text" class="form-control @error('name') is-invalid @enderror"
                    id="name" name="name" value="{{ old('name') }}"
                    placeholder="Masukkan nama lengkap" required>
                <small class="text-muted">Nama lengkap untuk keterangan (bukan untuk login).</small>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="username"><strong>Username</strong></label>
                <input type="text" class="form-control @error('username') is-invalid @enderror"
                    id="username" name="username" value="{{ old('username') }}"
                    placeholder="Masukkan username" required>
                <small class="text-muted">Digunakan untuk login. Bisa berupa nama apapun.</small>
                @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="email"><strong>Email</strong></label>
                <input type="email" class="form-control @error('email') is-invalid @enderror"
                    id="email" name="email" value="{{ old('email') }}"
                    placeholder="Masukkan alamat email" required>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="role"><strong>Role</strong></label>
                <select class="form-control @error('role') is-invalid @enderror"
                    id="role" name="role" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="admin"      {{ old('role') === 'admin'      ? 'selected' : '' }}>
                        Admin — dapat mengelola laporan, tidak bisa tambah/hapus akun
                    </option>
                    <option value="superadmin" {{ old('role') === 'superadmin' ? 'selected' : '' }}>
                        Superadmin — akses penuh termasuk kelola akun
                    </option>
                </select>
                @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="password"><strong>Password</strong></label>
                <input type="password" class="form-control @error('password') is-invalid @enderror"
                    id="password" name="password" required>
                <small class="text-muted">Minimal 6 karakter.</small>
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation"><strong>Konfirmasi Password</strong></label>
                <input type="password" class="form-control"
                    id="password_confirmation" name="password_confirmation" required>
            </div>

            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Simpan
            </button>
        </form>
    </div>
</div>
@stop
