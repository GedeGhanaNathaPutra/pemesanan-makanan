@extends('layouts.dashboard')
@section('title', 'Edit Pengguna: ' . $user->name)
@section('page-title', 'Edit Pengguna')
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
                        <h5 class="fw-extrabold text-dark mb-0">Edit Pengguna: {{ $user->name }}</h5>
                        <small class="text-muted">Perbarui data kredensial, role, dan kontak akun</small>
                    </div>
                </div>
                <a href="{{ route('admin.users.index') }}" class="btn btn-light btn-sm px-3 rounded-pill fw-bold">
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

            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Alamat Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Ganti Kata Sandi <span class="text-muted fw-normal">(kosongkan jika tidak diubah)</span></label>
                        <input type="password" name="password" class="form-control" placeholder="Kata sandi baru">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Peran (Role) <span class="text-danger">*</span></label>
                        <select name="role" class="form-select" required>
                            <option value="customer" @selected(old('role', $user->role)=='customer')>Customer (Pelanggan)</option>
                            <option value="owner" @selected(old('role', $user->role)=='owner')>Owner (Mitra Restoran)</option>
                            <option value="admin" @selected(old('role', $user->role)=='admin')>Administrator</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nomor Telepon</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Alamat Domisili</label>
                        <textarea name="address" class="form-control" rows="1">{{ old('address', $user->address) }}</textarea>
                    </div>
                    <div class="col-12 pt-3 border-top d-flex gap-2 justify-content-end">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-light px-4 py-2 rounded-pill fw-bold">Batal</a>
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
