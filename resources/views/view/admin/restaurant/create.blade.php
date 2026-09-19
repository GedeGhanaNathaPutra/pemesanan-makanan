@extends('layouts.dashboard')
@section('title', 'Tambah Mitra Restoran')
@section('page-title', 'Tambah Restoran')
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
                        <h5 class="fw-extrabold text-dark mb-0">Tambah Mitra Restoran Baru</h5>
                        <small class="text-muted">Hubungkan mitra restoran dengan akun owner terdaftar</small>
                    </div>
                </div>
                <a href="{{ route('admin.restaurants.index') }}" class="btn btn-light btn-sm px-3 rounded-pill fw-bold">
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

            <form action="{{ route('admin.restaurants.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Pemilik Restoran (Akun Owner) <span class="text-danger">*</span></label>
                        <select name="user_id" class="form-select" required>
                            <option value="">-- Pilih Akun Owner Mitra --</option>
                            @foreach($owners as $o)
                                <option value="{{ $o->id }}" @selected(old('user_id') == $o->id)>
                                    {{ $o->name }} ({{ $o->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nama Restoran <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Contoh: Rumah Makan Padang Sederhana" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nomor Telepon Restoran</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" placeholder="08123456789">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                        <textarea name="address" class="form-control" rows="2" placeholder="Alamat jalan, nomor, kota" required>{{ old('address') }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Deskripsi Restoran</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Informasi kuliner, jam operasional, atau keunggulan menu">{{ old('description') }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Foto Sampul / Banner Restoran</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <small class="text-muted" style="font-size: 0.72rem;">Format: JPG, PNG, WEBP. Maks 2MB.</small>
                    </div>
                    <div class="col-md-6 d-flex align-items-center pt-3">
                        <div class="form-check form-switch">
                            <input type="checkbox" name="is_open" value="1" class="form-check-input" id="isOpen" role="switch" checked>
                            <label class="form-check-label fw-bold small text-dark" for="isOpen">Status Buka (Siap Menerima Pesanan)</label>
                        </div>
                    </div>
                    <div class="col-12 pt-3 border-top d-flex gap-2 justify-content-end">
                        <a href="{{ route('admin.restaurants.index') }}" class="btn btn-light px-4 py-2 rounded-pill fw-bold">Batal</a>
                        <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill fw-bold">
                            <i class="bi bi-check-lg me-1"></i>Simpan Restoran
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
