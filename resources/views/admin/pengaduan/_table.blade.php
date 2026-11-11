<div class="table-responsive">
    <table class="table table-bordered table-striped" id="tablePengaduan">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Laporan</th>
                <th>Tgl. Lapor</th>
                <th>Judul</th>
                <th>Uraian</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($pengaduans as $index => $pengaduan)

                <tr>
                    <td>
                        {{ $loop->iteration + (($pengaduans->currentPage() - 1) * $pengaduans->perPage()) }}
                    </td>

                    <td>
                        {{ $pengaduan->kode_laporan }}
                    </td>

                    <td>
                        {{ $pengaduan->created_at->format('d-m-Y') }}
                    </td>

                    <td>
                        {{ $pengaduan->judul }}

                        @if ($pengaduan->status_proses === 'Ditindaklanjuti')
                            <span class="badge badge-success">
                                Ditindaklanjuti
                            </span>
                        @elseif ($pengaduan->status_proses === 'Ditolak')
                            <span class="badge badge-danger">
                                Ditolak
                            </span>
                        @elseif ($pengaduan->is_read)
                            <span class="badge badge-warning">
                                Menunggu
                            </span>
                        @else
                            <span class="badge bg-primary ml-1">
                                Baru
                            </span>
                        @endif
                    </td>

                    <td>
                        {{ \Illuminate\Support\Str::limit($pengaduan->uraian, 50) }}
                    </td>

                    <td>
                        {{ $pengaduan->status }}
                    </td>

                    <td>
                        <a href="{{ route('pengaduan.show', $pengaduan->id) }}"
                           class="btn btn-info btn-sm"
                           title="Detail">
                            <i class="fas fa-info-circle"></i> Detail
                        </a>

                        <form action="{{ route('pengaduan.destroy', $pengaduan->id) }}"
                              method="POST"
                              style="display: inline-block;"
                              onsubmit="return confirm('Yakin ingin menghapus pengaduan ini beserta semua lampirannya?')">

                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="btn btn-danger btn-sm"
                                    title="Hapus">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </td>
                </tr>

            @empty

                <tr>
                    <td colspan="7" class="text-center">
                        @if (!empty($search))
                            Tidak ada pengaduan yang cocok dengan pencarian
                            "<strong>{{ $search }}</strong>".
                        @else
                            Belum ada data pengaduan.
                        @endif
                    </td>
                </tr>

            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
<div class="d-flex justify-content-between align-items-center flex-wrap mt-2">

    <small class="text-muted">
        Menampilkan
        {{ $pengaduans->firstItem() ?? 0 }}
        –
        {{ $pengaduans->lastItem() ?? 0 }}
        dari
        {{ $pengaduans->total() }}
        data
    </small>

    <div>
        {{ $pengaduans->links() }}
    </div>

</div>