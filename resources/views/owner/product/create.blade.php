@extends('layouts.dashboard')
@section('title', 'Tambah Menu Baru')
@section('page-title', 'Tambah Menu')
@section('content')

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 p-4 p-md-5">
            <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
                <div class="d-flex align-items-center gap-2.5">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: rgba(255, 242, 237, 0.95); color: var(--liquid-primary);">
                        <i class="bi bi-box-seam fs-5"></i>
                    </div>
                    <div>
                        <h5 class="fw-extrabold text-dark mb-0">Tambah Menu Hidangan Baru</h5>
                        <small class="text-muted">Masukkan rincian masakan, harga, dan stok porsi harian</small>
                    </div>
                </div>
                <a href="{{ route('owner.products.index') }}" class="btn btn-light btn-sm px-3 rounded-pill fw-bold">
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

            <form action="{{ route('owner.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Nama Menu Hidangan <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Contoh: Bebek Goreng Kremes Sambal Korek" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Harga Satuan (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text border-0 bg-light text-muted fw-bold">Rp</span>
                            <input type="number" name="price" value="{{ old('price') }}" class="form-control" placeholder="25000" min="0" step="500" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jumlah Stok Siap Saji <span class="text-danger">*</span></label>
                        <input type="number" name="stock" value="{{ old('stock', 10) }}" class="form-control" min="0" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Deskripsi Hidangan</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Jelaskan cita rasa, komposisi bahan, atau tingkat kepedasan masakan...">{{ old('description') }}</textarea>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Foto Makanan / Minuman</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <small class="text-muted" style="font-size: 0.72rem;">Format: JPG, PNG, WEBP. Maks 2MB.</small>
                    </div>
                    <div class="col-md-4 d-flex align-items-center pt-3">
                        <div class="form-check form-switch">
                            <input type="checkbox" name="is_available" value="1" class="form-check-input" id="isAvail" role="switch" checked>
                            <label class="form-check-label fw-bold small text-dark" for="isAvail">Menu Tersedia</label>
                        </div>
                    </div>
                    <div class="col-12 pt-3 border-top d-flex gap-2 justify-content-end">
                        <a href="{{ route('owner.products.index') }}" class="btn btn-light px-4 py-2 rounded-pill fw-bold">Batal</a>
                        <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill fw-bold">
                            <i class="bi bi-check-lg me-1"></i>Simpan Menu
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
