@extends('adminlte::page')

@section('title', 'Pengelolaan User')

@section('content_header')
<h1>Pengelolaan User</h1>
@stop

@section('content')
@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
@endif
@if (session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
@endif

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
        @can('manage-users')
        <a href="{{ route('user.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Tambah User</a>
        @else
        <span></span>
        @endcan
        <form action="{{ route('user.index') }}" method="GET" class="form-inline">
            <div class="input-group input-group-sm" style="max-width: 320px;">
                <input type="text" name="search" class="form-control" placeholder="Cari nama / email..."
                    value="{{ $search ?? '' }}">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                    @if (!empty($search))
                    <a href="{{ route('user.index') }}" class="btn btn-secondary" title="Reset"><i class="fas fa-times"></i></a>
                    @endif
                </div>
            </div>
        </form>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Terdaftar</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                    <tr>
                        <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->created_at->format('d-m-Y') }}</td>
                        <td>
                            <a href="{{ route('user.edit', $user->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            @can('manage-users')
                            @if ($user->username !== 'admin')
                            <form action="{{ route('user.destroy', $user->id) }}" method="POST"
                                style="display: inline-block;"
                                onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                            @else
                            <button class="btn btn-secondary btn-sm" title="Akun admin tidak dapat dihapus" disabled>
                                <i class="fas fa-lock"></i> Terkunci
                            </button>
                            @endif
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">
                            @if (!empty($search))
                            Tidak ada user yang cocok dengan pencarian "<strong>{{ $search }}</strong>".
                            @else
                            Belum ada data user.
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center flex-wrap mt-2">
            <small class="text-muted">
                Menampilkan {{ $users->firstItem() ?? 0 }}–{{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} user
            </small>
            {{ $users->links() }}
        </div>
    </div>
</div>
@stop
