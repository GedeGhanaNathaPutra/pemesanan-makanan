@extends('layouts.dashboard')
@section('title', 'Kelola Kategori Makanan')
@section('page-title', 'Kategori Makanan')
@section('content')

{{-- Header Toolbar & Search --}}
<div class="card border-0 p-3.5 mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <h6 class="fw-extrabold text-dark mb-0">Daftar Kategori Kuliner</h6>
            <small class="text-muted">Kelola kelompok hidangan untuk memudahkan pencarian oleh pelanggan</small>
        </div>
        <div class="d-flex gap-2 align-items-center flex-wrap">
            <form class="d-flex gap-2 align-items-center" method="GET" action="{{ route('admin.categories.index') }}">
                <div class="input-group input-group-sm rounded-pill overflow-hidden border shadow-sm" style="min-width: 220px; background: rgba(255,255,255,0.95);">
                    <span class="input-group-text border-0 bg-transparent ps-3 text-muted">
                        <i class="bi bi-search text-primary"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control border-0 ps-1" placeholder="Cari nama kategori...">
                    @if(request('search'))
                        <a href="{{ route('admin.categories.index') }}" class="input-group-text border-0 bg-transparent pe-3 text-muted" title="Hapus pencarian">
                            <i class="bi bi-x-circle-fill"></i>
                        </a>
                    @endif
                </div>
                <button type="submit" class="btn btn-primary btn-sm px-3 rounded-pill fw-bold">
                    Cari
                </button>
            </form>

            <button class="btn btn-primary btn-sm px-3.5 py-2 rounded-pill fw-bold d-inline-flex align-items-center gap-1.5 shadow-sm" data-bs-toggle="modal" data-bs-target="#createCatModal">
                <i class="bi bi-plus-circle-fill"></i>
                <span>Tambah Kategori</span>
            </button>
        </div>
    </div>
</div>

{{-- Categories Table --}}
<div class="card border-0 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th class="py-3 px-4">#</th>
                    <th class="py-3">Gambar</th>
                    <th class="py-3">Nama Kategori</th>
                    <th class="py-3">Slug URL</th>
                    <th class="py-3">Deskripsi</th>
                    <th class="py-3">Jumlah Menu</th>
                    <th class="py-3 px-4 text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $cat)
                <tr>
                    <td class="px-4 text-muted small">
                        {{ $categories->firstItem() + $loop->index }}
                    </td>
                    <td>
                        <div class="rounded-3 overflow-hidden d-flex align-items-center justify-content-center border" style="width: 44px; height: 44px; background: rgba(241, 245, 249, 0.9);">
                            @if($cat->image)
                                <img src="{{ asset('storage/' . $cat->image) }}" class="w-100 h-100 object-fit-cover" alt="{{ $cat->name }}">
                            @else
                                <i class="bi bi-tags text-muted fs-5"></i>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="fw-bold text-dark">{{ $cat->name }}</div>
                    </td>
                    <td>
                        <code class="text-secondary small bg-light px-2 py-1 rounded">{{ $cat->slug ?: '-' }}</code>
                    </td>
                    <td class="small text-muted" style="max-width: 260px;">
                        <span class="text-truncate d-block">{{ $cat->description ?: '-' }}</span>
                    </td>
                    <td>
                        <span class="badge rounded-pill bg-light text-primary border px-2.5 py-1 fw-bold">
                            {{ $cat->products_count }} Menu
                        </span>
                    </td>
                    <td class="px-4 text-end">
                        <div class="d-inline-flex align-items-center gap-1.5">
                            <button class="btn btn-light btn-sm px-2.5 py-1.5 rounded-pill" data-bs-toggle="modal" data-bs-target="#editCatModal{{ $cat->id }}" title="Edit Kategori">
                                <i class="bi bi-pencil-square text-primary"></i>
                            </button>
                            <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori {{ $cat->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-light btn-sm px-2.5 py-1.5 rounded-pill text-danger" title="Hapus Kategori">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 56px; height: 56px; background: rgba(241, 245, 249, 0.9); color: #94a3b8;">
                            <i class="bi bi-tags fs-2"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Belum Ada Kategori Makanan</h6>
                        <p class="small text-muted mb-0">Klik tombol Tambah Kategori untuk mendaftarkan kategori pertama.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($categories->hasPages())
    <div class="card-footer bg-transparent py-3 px-4 border-top d-flex justify-content-center">
        {{ $categories->links() }}
    </div>
    @endif
</div>

{{-- CREATE MODAL --}}
<div class="modal fade" id="createCatModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden" style="background: rgba(255, 255, 255, 0.96); backdrop-filter: blur(20px);">
            <div class="modal-header py-3 px-4 border-bottom" style="background: rgba(255, 242, 237, 0.5);">
                <h5 class="modal-title fw-extrabold text-dark d-flex align-items-center gap-2">
                    <span class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: rgba(255, 242, 237, 0.95); color: var(--liquid-primary);">
                        <i class="bi bi-tag-fill fs-6"></i>
                    </span>
                    <span>Tambah Kategori Baru</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Aneka Nasi, Kopi, Ayam" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi Singkat</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Penjelasan singkat kategori..."></textarea>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Foto / Ikon Kategori</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <small class="text-muted" style="font-size: 0.72rem;">Format: JPG, PNG, WEBP. Maks 2MB.</small>
                    </div>
                </div>
                <div class="modal-footer py-3 px-4 border-top bg-light">
                    <button type="button" class="btn btn-light px-3.5 py-2 rounded-pill fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill fw-bold">
                        <i class="bi bi-check-lg me-1"></i>Simpan Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- EDIT MODALS --}}
@foreach($categories as $cat)
<div class="modal fade" id="editCatModal{{ $cat->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden" style="background: rgba(255, 255, 255, 0.96); backdrop-filter: blur(20px);">
            <div class="modal-header py-3 px-4 border-bottom" style="background: rgba(255, 242, 237, 0.5);">
                <h5 class="modal-title fw-extrabold text-dark d-flex align-items-center gap-2">
                    <span class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: rgba(255, 242, 237, 0.95); color: var(--liquid-primary);">
                        <i class="bi bi-pencil-square fs-6"></i>
                    </span>
                    <span>Edit Kategori: {{ $cat->name }}</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.categories.update', $cat) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $cat->name) }}" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="2">{{ old('description', $cat->description) }}</textarea>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Ganti Foto Kategori</label>
                        @if($cat->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $cat->image) }}" class="rounded-3 border" style="width: 64px; height: 64px; object-fit: cover;">
                            </div>
                        @endif
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <small class="text-muted" style="font-size: 0.72rem;">Kosongkan jika tidak ingin mengubah foto.</small>
                    </div>
                </div>
                <div class="modal-footer py-3 px-4 border-top bg-light">
                    <button type="button" class="btn btn-light px-3.5 py-2 rounded-pill fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill fw-bold">
                        <i class="bi bi-check-lg me-1"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection
