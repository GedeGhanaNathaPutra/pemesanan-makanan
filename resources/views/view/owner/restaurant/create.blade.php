@extends('layouts.dashboard')
@section('title','Buat Restoran')
@section('page-title','Buat Restoran')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card p-4">
            <h5 class="fw-bold mb-4"><i class="bi bi-shop me-2" style="color:#fd7e14"></i>Data Restoran Anda</h5>
            @if($errors->any())
            <div class="alert alert-danger small"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
            @endif
            <form action="{{ route('owner.restaurant.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Nama Restoran <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Contoh: Warung Makan Barokah" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">No. Telepon</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" placeholder="+62 812 3456 7890">
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Alamat Lengkap <span class="text-danger">*</span></label>
                        <textarea name="address" class="form-control" rows="3" placeholder="Masukkan alamat lengkap restoran" required>{{ old('address') }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Ceritakan tentang restoran Anda...">{{ old('description') }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Foto Restoran</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <small class="text-muted">Format: JPG, PNG. Maks 2MB</small>
                    </div>
                    <div class="col-12 d-flex gap-2 mt-2">
                        <button type="submit" class="btn btn-orange px-4">
                            <i class="bi bi-check-lg me-1"></i>Buat Restoran
                        </button>
                        <a href="{{ route('owner.dashboard') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection