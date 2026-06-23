<span class="dropdown-item dropdown-header">{{ $count }} Laporan Baru</span>
<div class="dropdown-divider"></div>
@forelse ($unread as $item)
<a href="{{ route('pengaduan.show', $item->id) }}" class="dropdown-item">
    <i class="fas fa-file-alt mr-2"></i>
    {{ \Illuminate\Support\Str::limit($item->judul, 25) }}
    <span class="float-right text-muted text-sm">{{ $item->created_at->diffForHumans() }}</span>
</a>
<div class="dropdown-divider"></div>
@empty
<span class="dropdown-item text-muted text-center">Tidak ada laporan baru</span>
<div class="dropdown-divider"></div>
@endforelse
