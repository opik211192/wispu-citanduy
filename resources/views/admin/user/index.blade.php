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
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        @can('manage-users')
        <a href="{{ route('user.create') }}" class="btn btn-success btn-sm">
            <i class="fas fa-user-plus"></i> Tambah User
        </a>
        @else
        <span></span>
        @endcan

        <form action="{{ route('user.index') }}" method="GET" class="form-inline">
            <div class="input-group input-group-sm" style="max-width: 320px;">
                <input type="text" name="search" class="form-control"
                    placeholder="Cari nama / username / email..."
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

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-striped mb-0">
                <thead class="thead-light">
                    <tr>
                        <th class="text-center" style="width: 50px;">No</th>
                        <th>Nama Lengkap</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th class="text-center">Role</th>
                        <th class="text-center" style="width: 80px;">Terdaftar</th>
                        <th class="text-center" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                    <tr>
                        <td class="text-center">{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                        <td>{{ $user->name }}</td>
                        <td>
                            <code>{{ $user->username }}</code>
                            @if ($user->id === auth()->id())
                            <span class="badge badge-info ml-1">Anda</span>
                            @endif
                        </td>
                        <td>{{ $user->email }}</td>
                        <td class="text-center">
                            @if ($user->role === 'superadmin')
                                <span class="badge badge-danger">Superadmin</span>
                            @elseif ($user->role === 'admin')
                                <span class="badge badge-primary">Admin</span>
                            @else
                                <span class="badge badge-secondary">{{ $user->role }}</span>
                            @endif
                        </td>
                        <td class="text-center">{{ $user->created_at->format('d/m/Y') }}</td>
                        <td class="text-center">
                            <a href="{{ route('user.edit', $user->id) }}"
                               class="btn btn-warning btn-sm" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            @can('manage-users')
                            @if ($user->id !== auth()->id())
                            <form action="{{ route('user.destroy', $user->id) }}" method="POST"
                                style="display: inline-block;"
                                onsubmit="return confirm('Yakin ingin menghapus user {{ $user->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @else
                            <button class="btn btn-secondary btn-sm" title="Tidak dapat menghapus akun sendiri" disabled>
                                <i class="fas fa-lock"></i>
                            </button>
                            @endif
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-3">
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
    </div>

    <div class="card-footer d-flex justify-content-between align-items-center flex-wrap">
        <small class="text-muted">
            Menampilkan {{ $users->firstItem() ?? 0 }}–{{ $users->lastItem() ?? 0 }}
            dari {{ $users->total() }} user
        </small>
        {{ $users->links() }}
    </div>
</div>

@section('css')
<style>
    .gap-2 { gap: 0.5rem; }
</style>

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
@endsection
@stop
