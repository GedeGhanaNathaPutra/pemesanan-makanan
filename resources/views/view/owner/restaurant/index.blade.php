@extends('layouts.dashboard')
@section('title','Restoran Saya')
@section('page-title','Restoran Saya')
@section('content')
@if(!$restaurant)
<div class="text-center py-5">
    <i class="bi bi-shop text-muted" style="font-size:4rem"></i>
    <h5 class="mt-3 text-muted">Anda belum memiliki restoran</h5>
    <button class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#createRestoModal">
        <i class="bi bi-plus me-1"></i>Buat Restoran
    </button>
</div>
{{-- CREATE MODAL --}}
<div class="modal fade" id="createRestoModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="bi bi-shop me-2" style="color:#fd7e14"></i>Buat Restoran Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('owner.restaurant.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label small fw-semibold">Nama Restoran <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Contoh: Warung Makan Barokah" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Telepon</label>
                            <input type="text" name="phone" class="form-control" placeholder="08xx-xxxx-xxxx">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea name="address" class="form-control" rows="2" placeholder="Jl. Contoh No.1, Kota" required></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Deskripsi</label>
                            <textarea name="description" class="form-control" rows="2" placeholder="Ceritakan tentang restoran Anda..."></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Foto Restoran</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <small class="text-muted">JPG/PNG, maks 2MB</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-semibold"><i class="bi bi-check-lg me-1"></i>Buat Restoran</button>
                </div>
            </form>
        </div>
    </div>
</div>
@else
<div class="card p-4 mb-4">
    <div class="row g-4 align-items-center">
        <div class="col-md-3 text-center">
            @if($restaurant->image)
            <img src="{{ asset('storage/'.$restaurant->image) }}" class="img-fluid rounded-3" style="max-height:180px;object-fit:cover">
            @else
            <div class="rounded-3 d-flex align-items-center justify-content-center mx-auto" style="width:150px;height:150px;background:linear-gradient(135deg,#fd7e14,#e85d04)">
                <i class="bi bi-shop-window text-white" style="font-size:3rem"></i>
            </div>
            @endif
        </div>
        <div class="col-md-9">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
                <h4 class="fw-bold mb-0">{{ $restaurant->name }}</h4>
                <div class="d-flex gap-2">
                    <span class="badge {{ $restaurant->is_open?'bg-success':'bg-danger' }} px-3 py-2">{{ $restaurant->is_open?'Buka':'Tutup' }}</span>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editRestoModal"><i class="bi bi-pencil me-1"></i>Edit</button>
                </div>
            </div>
            <p class="text-muted mb-1"><i class="bi bi-geo-alt me-2"></i>{{ $restaurant->address }}</p>
            @if($restaurant->phone)<p class="text-muted mb-1"><i class="bi bi-telephone me-2"></i>{{ $restaurant->phone }}</p>@endif
            @if($restaurant->description)<p class="text-muted small mb-3">{{ $restaurant->description }}</p>@endif
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('owner.products.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-box-seam me-1"></i>Kelola Produk</a>
                <a href="{{ route('owner.orders.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-receipt me-1"></i>Kelola Pesanan</a>
            </div>
        </div>
    </div>
</div>

{{-- EDIT MODAL --}}
<div class="modal fade" id="editRestoModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="bi bi-pencil me-2" style="color:#fd7e14"></i>Edit Restoran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('owner.restaurant.update',$restaurant) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label small fw-semibold">Nama Restoran</label>
                            <input type="text" name="name" value="{{ $restaurant->name }}" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Telepon</label>
                            <input type="text" name="phone" value="{{ $restaurant->phone }}" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Alamat</label>
                            <textarea name="address" class="form-control" rows="2" required>{{ $restaurant->address }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Deskripsi</label>
                            <textarea name="description" class="form-control" rows="2">{{ $restaurant->description }}</textarea>
                        </div>
                        @if($restaurant->image)
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Foto Saat Ini</label>
                            <div><img src="{{ asset('storage/'.$restaurant->image) }}" class="img-thumbnail" style="max-width:150px"></div>
                        </div>
                        @endif
                        <div class="col-md-8">
                            <label class="form-label small fw-semibold">Ganti Foto</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <div class="form-check form-switch">
                                <input type="checkbox" name="is_open" value="1" class="form-check-input" id="isOpenEdit" @checked($restaurant->is_open)>
                                <label class="form-check-label fw-semibold small" for="isOpenEdit">Restoran Buka</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection