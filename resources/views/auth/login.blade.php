@extends('layouts.app')
@section('title', 'Masuk ke Akun – FoodOrder')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5 col-xl-4">
            <div class="card p-4 p-md-5 border-0 rounded-4">
                <div class="text-center mb-4">
                    <div class="category-icon-glass mx-auto mb-3" style="width: 68px; height: 68px; background: rgba(255, 242, 237, 0.85); color: var(--liquid-primary);">
                        <i class="bi bi-bag-heart-fill fs-2"></i>
                    </div>
                    <h3 class="fw-extrabold text-dark mb-1">Masuk ke Food<span class="text-primary">Order</span></h3>
                    <p class="text-muted small mb-0">Selamat datang kembali! Silakan masuk ke akun Anda</p>
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

                {{-- Demo Account Quick Fill (Liquid Glass Pills) --}}
                <div class="p-3 rounded-4 mb-4" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.85);">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="small fw-bold text-dark"><i class="bi bi-person-badge me-1 text-primary"></i>Akun Demo Cepat</span>
                        <span class="badge rounded-pill small" style="background: rgba(255, 255, 255, 0.8); color: #64748b; font-size:0.65rem;">Klik isi instan</span>
                    </div>
                    <div class="d-flex flex-wrap gap-1.5">
                        <button type="button" class="btn btn-light btn-sm small py-1 px-2.5 rounded-pill flex-fill fw-semibold" onclick="fillDemo('customer@food.test')">
                            <i class="bi bi-person me-1 text-success"></i>Customer
                        </button>
                        <button type="button" class="btn btn-light btn-sm small py-1 px-2.5 rounded-pill flex-fill fw-semibold" onclick="fillDemo('owner.padang@food.test')">
                            <i class="bi bi-shop me-1 text-warning"></i>Owner
                        </button>
                        <button type="button" class="btn btn-light btn-sm small py-1 px-2.5 rounded-pill flex-fill fw-semibold" onclick="fillDemo('admin@food.test')">
                            <i class="bi bi-shield-lock me-1 text-danger"></i>Admin
                        </button>
                    </div>
                </div>

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Alamat Email</label>
                        <div class="input-group">
                            <span class="input-group-text border-0 ps-3" style="background: rgba(255, 255, 255, 0.75); border-radius: 1rem 0 0 1rem; border: 1px solid rgba(255, 255, 255, 0.85); border-right: none;">
                                <i class="bi bi-envelope text-primary"></i>
                            </span>
                            <input type="email" name="email" id="emailField" value="{{ old('email') }}" class="form-control border-0 ps-1 fw-semibold" style="border-radius: 0 1rem 1rem 0; border: 1px solid rgba(255, 255, 255, 0.85); border-left: none; color: #0f172a;" placeholder="email@example.com" required autofocus>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-dark">Password</label>
                        <div class="input-group">
                            <span class="input-group-text border-0 ps-3" style="background: rgba(255, 255, 255, 0.75); border-radius: 1rem 0 0 1rem; border: 1px solid rgba(255, 255, 255, 0.85); border-right: none;">
                                <i class="bi bi-lock text-primary"></i>
                            </span>
                            <input type="password" name="password" id="pwField" class="form-control border-0 ps-1 fw-semibold" style="border: 1px solid rgba(255, 255, 255, 0.85); border-left: none; border-right: none; color: #0f172a;" placeholder="••••••••" required>
                            <button class="btn btn-light border-0 pe-3" type="button" onclick="togglePw()" aria-label="Lihat Password" style="border-radius: 0 1rem 1rem 0; border: 1px solid rgba(255, 255, 255, 0.85); border-left: none; background: rgba(255, 255, 255, 0.75);">
                                <i class="bi bi-eye text-muted" id="pwEye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold rounded-pill py-2.5">
                        <i class="bi bi-box-arrow-in-right me-1"></i>Masuk
                    </button>
                </form>

                <p class="text-center mt-4 small text-muted mb-0">
                    Belum punya akun? <a href="{{ route('register') }}" class="fw-bold text-primary">Daftar akun gratis</a>
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function fillDemo(email) {
    document.getElementById('emailField').value = email;
    document.getElementById('pwField').value = 'password';
}

function togglePw() {
    const f = document.getElementById('pwField');
    const e = document.getElementById('pwEye');
    if (f.type === 'password') {
        f.type = 'text';
        e.className = 'bi bi-eye-slash text-primary';
    } else {
        f.type = 'password';
        e.className = 'bi bi-eye text-muted';
    }
}
</script>
@endpush
@endsection
