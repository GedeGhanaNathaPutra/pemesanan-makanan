@extends('layouts.app')
@section('title', 'Daftar Akun Baru – FoodOrder')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6 col-xl-5">
            <div class="card p-4 p-md-5 border-0 rounded-4">
                <div class="text-center mb-4">
                    <div class="category-icon-glass mx-auto mb-3" style="width: 68px; height: 68px; background: rgba(255, 242, 237, 0.85); color: var(--liquid-primary);">
                        <i class="bi bi-person-plus-fill fs-2"></i>
                    </div>
                    <h3 class="fw-extrabold text-dark mb-1">Buat Akun Baru</h3>
                    <p class="text-muted small mb-0">Daftar sekarang dan nikmati pesan makanan lebih mudah</p>
                </div>

                @if($errors->any())
                <div class="alert alert-danger small py-2 px-3 border-0 rounded-3 mb-3" style="background: rgba(254, 226, 226, 0.85); backdrop-filter: blur(10px); color: #991b1b; border: 1px solid rgba(255,255,255,0.8);">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold small text-dark">Nama Lengkap <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text border-0 ps-3" style="background: rgba(255, 255, 255, 0.75); border-radius: 1rem 0 0 1rem; border: 1px solid rgba(255, 255, 255, 0.85); border-right: none;">
                                    <i class="bi bi-person text-primary"></i>
                                </span>
                                <input type="text" name="name" value="{{ old('name') }}" class="form-control border-0 ps-1 fw-semibold" style="border-radius: 0 1rem 1rem 0; border: 1px solid rgba(255, 255, 255, 0.85); border-left: none; color: #0f172a;" placeholder="Nama Anda" required autofocus>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold small text-dark">Alamat Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text border-0 ps-3" style="background: rgba(255, 255, 255, 0.75); border-radius: 1rem 0 0 1rem; border: 1px solid rgba(255, 255, 255, 0.85); border-right: none;">
                                    <i class="bi bi-envelope text-primary"></i>
                                </span>
                                <input type="email" name="email" value="{{ old('email') }}" class="form-control border-0 ps-1 fw-semibold" style="border-radius: 0 1rem 1rem 0; border: 1px solid rgba(255, 255, 255, 0.85); border-left: none; color: #0f172a;" placeholder="email@example.com" required>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6">
                            <label class="form-label fw-bold small text-dark">Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text border-0 ps-3" style="background: rgba(255, 255, 255, 0.75); border-radius: 1rem 0 0 1rem; border: 1px solid rgba(255, 255, 255, 0.85); border-right: none;">
                                    <i class="bi bi-lock text-primary"></i>
                                </span>
                                <input type="password" name="password" class="form-control border-0 ps-1 fw-semibold" style="border-radius: 0 1rem 1rem 0; border: 1px solid rgba(255, 255, 255, 0.85); border-left: none; color: #0f172a;" placeholder="Min. 8 karakter" required>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6">
                            <label class="form-label fw-bold small text-dark">Konfirmasi Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text border-0 ps-3" style="background: rgba(255, 255, 255, 0.75); border-radius: 1rem 0 0 1rem; border: 1px solid rgba(255, 255, 255, 0.85); border-right: none;">
                                    <i class="bi bi-shield-check text-primary"></i>
                                </span>
                                <input type="password" name="password_confirmation" class="form-control border-0 ps-1 fw-semibold" style="border-radius: 0 1rem 1rem 0; border: 1px solid rgba(255, 255, 255, 0.85); border-left: none; color: #0f172a;" placeholder="Ulangi password" required>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold small text-dark">Nomor Handphone / WhatsApp</label>
                            <div class="input-group">
                                <span class="input-group-text border-0 ps-3" style="background: rgba(255, 255, 255, 0.75); border-radius: 1rem 0 0 1rem; border: 1px solid rgba(255, 255, 255, 0.85); border-right: none;">
                                    <i class="bi bi-telephone text-primary"></i>
                                </span>
                                <input type="tel" name="phone" value="{{ old('phone') }}" class="form-control border-0 ps-1 fw-semibold" style="border-radius: 0 1rem 1rem 0; border: 1px solid rgba(255, 255, 255, 0.85); border-left: none; color: #0f172a;" placeholder="08xxxxxxxxxx">
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold small text-dark">Alamat Pengantaran Makanan</label>
                            <textarea name="address" rows="2" class="form-control" placeholder="Alamat rumah atau kantor untuk pengantaran makanan...">{{ old('address') }}</textarea>
                        </div>

                        <div class="col-12 mt-3">
                            <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold rounded-pill py-2.5">
                                <i class="bi bi-person-plus me-1"></i>Daftar Sekarang
                            </button>
                        </div>
                    </div>
                </form>

                <p class="text-center mt-4 small text-muted mb-0">
                    Sudah memiliki akun? <a href="{{ route('login') }}" class="fw-bold text-primary">Masuk di sini</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
