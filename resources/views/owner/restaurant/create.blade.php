@extends('layouts.dashboard')
@section('title', 'Daftarkan Restoran Baru')
@section('page-title', 'Daftar Restoran')
@section('content')

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 p-4 p-md-5">
            <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
                <div class="d-flex align-items-center gap-2.5">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: rgba(255, 242, 237, 0.95); color: var(--liquid-primary);">
                        <i class="bi bi-shop fs-5"></i>
                    </div>
                    <div>
                        <h5 class="fw-extrabold text-dark mb-0">Daftarkan Restoran Anda</h5>
                        <small class="text-muted">Isi profil restoran untuk mulai menjual kuliner lezat Anda</small>
                    </div>
                </div>
                <a href="{{ route('owner.dashboard') }}" class="btn btn-light btn-sm px-3 rounded-pill fw-bold">
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

            <form action="{{ route('owner.restaurant.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Nama Restoran <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Contoh: Bebek Sinjay Madura, Kopi Senja" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nomor Telepon / WhatsApp</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" placeholder="Contoh: 08123456789">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Foto Sampul Restoran</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <small class="text-muted" style="font-size: 0.72rem;">Format: JPG, PNG, WEBP. Maks 2MB.</small>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                        <textarea name="address" class="form-control" rows="2" placeholder="Alamat jalan, nomor ruko/kedai, kota" required>{{ old('address') }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Deskripsi & Cerita Kuliner</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Ceritakan keistimewaan rasa, bumbu rahasia, atau jam buka restoran Anda...">{{ old('description') }}</textarea>
                    </div>
                    <div class="col-12 pt-3 border-top d-flex gap-2 justify-content-end">
                        <a href="{{ route('owner.dashboard') }}" class="btn btn-light px-4 py-2 rounded-pill fw-bold">Batal</a>
                        <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill fw-bold">
                            <i class="bi bi-check-lg me-1"></i>Daftarkan Restoran
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
