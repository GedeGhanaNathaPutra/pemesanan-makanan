@extends('layouts.dashboard')
@section('title', 'Tambah Kategori Makanan')
@section('page-title', 'Tambah Kategori')
@section('content')

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card border-0 p-4 p-md-5">
            <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
                <div class="d-flex align-items-center gap-2.5">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: rgba(255, 242, 237, 0.95); color: var(--liquid-primary);">
                        <i class="bi bi-tag-fill fs-5"></i>
                    </div>
                    <div>
                        <h5 class="fw-extrabold text-dark mb-0">Tambah Kategori Makanan</h5>
                        <small class="text-muted">Buat kelompok menu baru untuk katalog platform</small>
                    </div>
                </div>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-light btn-sm px-3 rounded-pill fw-bold">
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

            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Contoh: Aneka Minuman, Makanan Berat" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Penjelasan singkat kategori...">{{ old('description') }}</textarea>
                </div>
                <div class="mb-4">
                    <label class="form-label">Foto / Ikon Kategori</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>
                <div class="d-flex gap-2 justify-content-end pt-3 border-top">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-light px-4 py-2 rounded-pill fw-bold">Batal</a>
                    <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill fw-bold">
                        <i class="bi bi-check-lg me-1"></i>Simpan Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
