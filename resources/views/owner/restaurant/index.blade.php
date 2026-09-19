@extends('layouts.dashboard')
@section('title', 'Profil Restoran Saya')
@section('page-title', 'Profil Restoran')
@section('content')

@if(!$restaurant)
<div class="card border-0 text-center py-5 px-4 rounded-4 my-4">
    <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px; background: rgba(255, 242, 237, 0.95); color: var(--liquid-primary);">
        <i class="bi bi-shop-window fs-1"></i>
    </div>
    <h4 class="fw-extrabold text-dark mb-1">Anda Belum Memiliki Profil Restoran</h4>
    <p class="text-muted small mb-3" style="max-width: 480px; margin: 0 auto;">Daftarkan nama restoran, alamat lengkap, dan nomor kontak agar pelanggan dapat mulai memesan menu Anda.</p>
    <div>
        <a href="{{ route('owner.restaurant.create') }}" class="btn btn-primary px-4 py-2.5 rounded-pill fw-bold shadow-sm d-inline-flex align-items-center gap-1.5">
            <i class="bi bi-plus-circle-fill"></i>
            <span>Daftarkan Restoran Sekarang</span>
        </a>
    </div>
</div>
@else
<div class="card border-0 p-4 p-md-5 mb-4">
    <div class="row g-4 align-items-center">
        <div class="col-md-3 text-center">
            @if($restaurant->image)
                <div class="rounded-4 overflow-hidden border shadow-sm mx-auto position-relative" style="max-width: 220px; height: 180px;">
                    <img src="{{ asset('storage/' . $restaurant->image) }}" class="w-100 h-100 object-fit-cover" alt="{{ $restaurant->name }}">
                </div>
            @else
                <div class="rounded-4 d-flex align-items-center justify-content-center mx-auto shadow-sm" style="width: 180px; height: 180px; background: linear-gradient(135deg, rgba(255, 87, 34, 0.85), rgba(234, 88, 12, 0.9)); color: #ffffff;">
                    <i class="bi bi-shop-window" style="font-size: 4rem;"></i>
                </div>
            @endif
        </div>
        <div class="col-md-9">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
                <div>
                    <span class="badge rounded-pill px-2.5 py-1 small fw-bold mb-1 d-inline-flex align-items-center gap-1" style="background: rgba(255, 242, 237, 0.95); color: var(--liquid-primary);">
                        <i class="bi bi-patch-check-fill text-primary"></i>
                        <span>Mitra Terverifikasi</span>
                    </span>
                    <h3 class="fw-extrabold text-dark mb-1">{{ $restaurant->name }}</h3>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge rounded-pill px-3 py-1.5 small fw-bold d-inline-flex align-items-center gap-1.5" style="background: {{ $restaurant->is_open ? 'rgba(220, 252, 231, 0.95)' : 'rgba(254, 226, 226, 0.95)' }}; color: {{ $restaurant->is_open ? '#15803d' : '#991b1b' }}; border: 1px solid {{ $restaurant->is_open ? '#bbf7d0' : '#fecaca' }};">
                        <i class="bi bi-circle-fill" style="font-size: 0.45rem;"></i>
                        <span>{{ $restaurant->is_open ? 'Buka (Menerima Pesanan)' : 'Restoran Tutup' }}</span>
                    </span>
                    <button class="btn btn-primary btn-sm px-3.5 py-1.5 rounded-pill fw-bold d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#editRestoModal">
                        <i class="bi bi-pencil-square"></i>
                        <span>Edit Profil</span>
                    </button>
                </div>
            </div>

            <div class="d-flex flex-column gap-2 mb-3 small">
                <div class="d-flex align-items-center text-muted">
                    <i class="bi bi-geo-alt-fill me-2 text-danger"></i>
                    <span class="text-dark">{{ $restaurant->address }}</span>
                </div>
                @if($restaurant->phone)
                <div class="d-flex align-items-center text-muted">
                    <i class="bi bi-telephone-fill me-2 text-primary"></i>
                    <span class="text-dark">{{ $restaurant->phone }}</span>
                </div>
                @endif
            </div>

            <p class="text-secondary small mb-4" style="line-height: 1.6;">
                {{ $restaurant->description ?: 'Restoran mitra resmi dengan jaminan cita rasa hidangan otentik, higienis, dan segar.' }}
            </p>

            <div class="d-flex gap-2 flex-wrap pt-3 border-top">
                <a href="{{ route('owner.products.index') }}" class="btn btn-light btn-sm px-3.5 py-2 rounded-pill fw-bold d-inline-flex align-items-center gap-1.5">
                    <i class="bi bi-box-seam text-primary"></i>
                    <span>Kelola Menu ({{ $restaurant->products()->count() }})</span>
                </a>
                <a href="{{ route('owner.orders.index') }}" class="btn btn-light btn-sm px-3.5 py-2 rounded-pill fw-bold d-inline-flex align-items-center gap-1.5">
                    <i class="bi bi-receipt text-success"></i>
                    <span>Kelola Pesanan Masuk</span>
                </a>
                <a href="{{ route('restaurants.show', $restaurant) }}" target="_blank" class="btn btn-outline-primary btn-sm px-3.5 py-2 rounded-pill fw-bold d-inline-flex align-items-center gap-1.5">
                    <i class="bi bi-box-arrow-up-right"></i>
                    <span>Lihat Tampilan Publik</span>
                </a>
            </div>
        </div>
    </div>
</div>

{{-- EDIT MODAL --}}
<div class="modal fade" id="editRestoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden" style="background: rgba(255, 255, 255, 0.96); backdrop-filter: blur(20px);">
            <div class="modal-header py-3 px-4 border-bottom" style="background: rgba(255, 242, 237, 0.5);">
                <h5 class="modal-title fw-extrabold text-dark d-flex align-items-center gap-2">
                    <span class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: rgba(255, 242, 237, 0.95); color: var(--liquid-primary);">
                        <i class="bi bi-pencil-square fs-6"></i>
                    </span>
                    <span>Edit Profil Restoran: {{ $restaurant->name }}</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('owner.restaurant.update', $restaurant) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Nama Restoran <span class="text-danger">*</span></label>
                            <input type="text" name="name" value="{{ $restaurant->name }}" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nomor Telepon</label>
                            <input type="text" name="phone" value="{{ $restaurant->phone }}" class="form-control" placeholder="08123456789">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea name="address" class="form-control" rows="2" required>{{ $restaurant->address }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Deskripsi Restoran</label>
                            <textarea name="description" class="form-control" rows="2">{{ $restaurant->description }}</textarea>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Ganti Foto Sampul</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <small class="text-muted" style="font-size: 0.72rem;">Kosongkan jika tidak ingin mengganti foto saat ini.</small>
                        </div>
                        <div class="col-md-4 d-flex align-items-center pt-3">
                            <div class="form-check form-switch">
                                <input type="checkbox" name="is_open" value="1" class="form-check-input" id="isOpenEdit" role="switch" @checked($restaurant->is_open)>
                                <label class="form-check-label fw-bold small text-dark" for="isOpenEdit">Restoran Buka</label>
                            </div>
                        </div>
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
@endif
@endsection
