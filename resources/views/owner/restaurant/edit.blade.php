@extends('layouts.dashboard')
@section('title', 'Edit Restoran: ' . $restaurant->name)
@section('page-title', 'Edit Restoran')
@section('content')

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 p-4 p-md-5">
            <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
                <div class="d-flex align-items-center gap-2.5">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: rgba(255, 242, 237, 0.95); color: var(--liquid-primary);">
                        <i class="bi bi-pencil-square fs-5"></i>
                    </div>
                    <div>
                        <h5 class="fw-extrabold text-dark mb-0">Edit Restoran: {{ $restaurant->name }}</h5>
                        <small class="text-muted">Perbarui informasi profil, kontak, dan jam operasional</small>
                    </div>
                </div>
                <a href="{{ route('owner.restaurant.index') }}" class="btn btn-light btn-sm px-3 rounded-pill fw-bold">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
            </div>

            @if($errors->any())
            <div class="alert alert-danger rounded-4 mb-4 border-0 small">
                <ul class="mb-0">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('owner.restaurant.update', $restaurant) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Nama Restoran <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $restaurant->name) }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nomor Telepon</label>
                        <input type="text" name="phone" value="{{ old('phone', $restaurant->phone) }}" class="form-control" placeholder="08123456789">
                    </div>
                    <div class="col-md-6 d-flex align-items-center pt-3">
                        <div class="form-check form-switch">
                            <input type="checkbox" name="is_open" value="1" class="form-check-input" id="isOpen" role="switch" @checked(old('is_open', $restaurant->is_open))>
                            <label class="form-check-label fw-bold small text-dark" for="isOpen">Status Buka (Siap Menerima Pesanan)</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                        <textarea name="address" class="form-control" rows="2" required>{{ old('address', $restaurant->address) }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Deskripsi Restoran</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $restaurant->description) }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Foto Sampul Restoran</label>
                        @if($restaurant->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $restaurant->image) }}" class="rounded-3 border" style="width: 100px; height: 60px; object-fit: cover;">
                            </div>
                        @endif
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <small class="text-muted" style="font-size: 0.72rem;">Kosongkan jika tidak ingin mengganti foto saat ini.</small>
                    </div>
                    <div class="col-12 pt-3 border-top d-flex gap-2 justify-content-end">
                        <a href="{{ route('owner.restaurant.index') }}" class="btn btn-light px-4 py-2 rounded-pill fw-bold">Batal</a>
                        <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill fw-bold">
                            <i class="bi bi-check-lg me-1"></i>Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
