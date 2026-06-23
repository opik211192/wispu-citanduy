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
            @forelse ($pengaduans as $index => $pengaduan)
            <tr>
                <td>{{ $loop->iteration + ($pengaduans->currentPage() - 1) * $pengaduans->perPage() }}</td>
                <td>{{ $pengaduan->created_at->format('d-m-Y') }}</td>
                <td>
                    {{ $pengaduan->judul }}
                    @if (! $pengaduan->is_read)
                    <span class="badge badge-danger ml-1">Baru</span>
                    @endif
                </td>
                <td>{{ \Illuminate\Support\Str::limit($pengaduan->uraian, 50) }}</td>
                <td>{{ $pengaduan->status }}</td>
                <td>
                    <a href="{{ route('pengaduan.show', $pengaduan->id) }}" class="btn btn-info btn-sm" title="Detail">
                        <i class="fas fa-info-circle"></i> Detail
                    </a>
                    {{-- <a href="{{ route('pengaduan.edit', $pengaduan->id) }}" class="btn btn-warning btn-sm">Edit</a> --}}
                    <form action="{{ route('pengaduan.destroy', $pengaduan->id) }}" method="POST"
                        style="display: inline-block;"
                        onsubmit="return confirm('Yakin ingin menghapus pengaduan ini beserta semua lampirannya?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">
                    @if (!empty($search))
                    Tidak ada pengaduan yang cocok dengan pencarian "<strong>{{ $search }}</strong>".
                    @else
                    Belum ada data pengaduan.
                    @endif
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-between align-items-center flex-wrap mt-2">
    <small class="text-muted">
        Menampilkan {{ $pengaduans->firstItem() ?? 0 }}–{{ $pengaduans->lastItem() ?? 0 }}
        dari {{ $pengaduans->total() }} data
    </small>
    {{ $pengaduans->links() }}
</div>
