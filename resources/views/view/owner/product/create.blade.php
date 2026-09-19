@extends('layouts.dashboard')
@section('title','Tambah Produk')
@section('page-title','Tambah Produk')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card p-4">
            @if($errors->any())
            <div class="alert alert-danger small"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
            @endif
            <form action="{{ route('owner.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label small fw-semibold">Nama Produk <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Contoh: Nasi Goreng Spesial" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Kategori <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(old('category_id')==$cat->id)>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Harga (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text small">Rp</span>
                            <input type="number" name="price" value="{{ old('price') }}" class="form-control" placeholder="15000" min="0" step="500" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Stok <span class="text-danger">*</span></label>
                        <input type="number" name="stock" value="{{ old('stock', 0) }}" class="form-control" min="0" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Ceritakan tentang menu ini...">{{ old('description') }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Foto Produk</label>
                        <input type="file" name="image" class="form-control" accept="image/*" id="imageInput">
                        <small class="text-muted">Format: JPG, PNG. Maks 2MB</small>
                        <div id="previewWrap" class="mt-2 d-none">
                            <img id="imgPreview" class="img-thumbnail" style="max-width:200px">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input type="checkbox" name="is_available" value="1" class="form-check-input" id="isAvail" checked>
                            <label class="form-check-label small fw-semibold" for="isAvail">Produk Tersedia</label>
                        </div>
                    </div>
                    <div class="col-12 d-flex gap-2 mt-2">
                        <button type="submit" class="btn btn-orange">
                            <i class="bi bi-plus-circle me-1"></i>Tambah Produk
                        </button>
                        <a href="{{ route('owner.products.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('imageInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(ev) {
            document.getElementById('imgPreview').src = ev.target.result;
            document.getElementById('previewWrap').classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endpush
@endsection