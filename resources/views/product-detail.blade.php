@extends('layouts.app')
@section('title', $product->name . ' – ' . ($product->restaurant->name ?? 'Menu'))
@section('content')
<div class="container-fluid px-3 px-md-4 px-lg-5 py-4">
    {{-- Breadcrumb Navigation (Liquid Glass Pill) --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb small px-3.5 py-2 rounded-pill mb-0 d-inline-flex" style="background: rgba(255, 255, 255, 0.72); backdrop-filter: blur(16px); border: 1px solid rgba(255, 255, 255, 0.85); box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04);">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-primary fw-semibold">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('restaurants') }}" class="text-decoration-none text-primary fw-semibold">Restoran</a></li>
            @if($product->restaurant)
                <li class="breadcrumb-item"><a href="{{ route('restaurants.show', $product->restaurant) }}" class="text-decoration-none text-primary fw-semibold">{{ $product->restaurant->name }}</a></li>
            @endif
            <li class="breadcrumb-item active text-muted text-truncate" style="max-width: 200px;">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row g-4 justify-content-center">
        {{-- Product Image with Glass Container --}}
        <div class="col-lg-6">
            <div class="card border-0 rounded-4 overflow-hidden sticky-top" style="top: 85px;">
                <div class="position-relative" style="height: 420px;">
                    @if($product->image)
                        <img src="{{ asset('storage/'.$product->image) }}" class="w-100 h-100 object-fit-cover" alt="{{ $product->name }}">
                    @else
                        <div class="w-100 h-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, rgba(255, 242, 237, 0.8), rgba(255, 224, 211, 0.8));">
                            <i class="bi bi-egg-fried" style="font-size: 5.5rem; color: var(--liquid-primary); opacity: 0.85;"></i>
                        </div>
                    @endif

                    @if($product->stock <= 0)
                        <div class="position-absolute d-flex align-items-center justify-content-center text-white text-uppercase fw-bold" style="top:0;left:0;right:0;bottom:0;background:rgba(15,23,42,0.75);font-size:1.2rem;letter-spacing:2px; backdrop-filter: blur(4px);">
                            <span class="bg-danger px-4 py-2 rounded-pill" style="box-shadow: 0 8px 20px rgba(239,68,68,0.4);">Stok Habis</span>
                        </div>
                    @endif

                    {{-- Category Glass Pill --}}
                    <span class="position-absolute top-0 end-0 m-3 badge rounded-pill px-3 py-2 small fw-bold" style="background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(16px); color: #0f172a; border: 1px solid rgba(255, 255, 255, 0.9); box-shadow: 0 4px 14px rgba(0,0,0,0.1);">
                        {{ $product->category->name ?? 'Menu' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Product Action Panel (Liquid Glass Card) --}}
        <div class="col-lg-6">
            <div class="card border-0 rounded-4 p-4 p-md-5 h-100 d-flex flex-column">
                <div class="flex-grow-1">
                    {{-- Restaurant Pill --}}
                    @if($product->restaurant)
                    <div class="mb-3">
                        <a href="{{ route('restaurants.show', $product->restaurant) }}" class="d-inline-flex align-items-center gap-2 text-decoration-none rounded-pill px-3.5 py-2 small" style="background: rgba(255, 255, 255, 0.65); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.85); box-shadow: 0 4px 10px rgba(15, 23, 42, 0.04);">
                            <i class="bi bi-shop text-primary"></i>
                            <span class="fw-bold text-dark">{{ $product->restaurant->name }}</span>
                            @if($product->restaurant->is_open)
                                <span class="badge badge-selesai rounded-pill" style="font-size:0.65rem;">Buka</span>
                            @else
                                <span class="badge badge-pending rounded-pill" style="font-size:0.65rem;">Tutup</span>
                            @endif
                        </a>
                    </div>
                    @endif

                    <h2 class="fw-extrabold text-dark mb-2" style="letter-spacing: -0.02em;">{{ $product->name }}</h2>

                    <div class="d-flex align-items-center flex-wrap gap-3 mb-3">
                        <h3 class="fw-extrabold mb-0 fs-2" style="color: var(--liquid-primary);">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </h3>
                        <span class="badge {{ $product->stock > 0 ? 'badge-selesai' : 'badge-dibatalkan' }} px-3 py-1.5 rounded-pill small">
                            <i class="bi bi-{{ $product->stock > 0 ? 'check-circle' : 'x-circle' }} me-1"></i>
                            {{ $product->stock > 0 ? 'Tersedia: '.$product->stock.' porsi' : 'Stok Kosong' }}
                        </span>
                    </div>

                    {{-- Description --}}
                    <div class="mb-4">
                        <h6 class="fw-bold text-dark small mb-2"><i class="bi bi-card-text me-1 text-primary"></i>Deskripsi Menu</h6>
                        <p class="text-secondary mb-0 lh-lg">
                            {{ $product->description ?? 'Hidangan istimewa yang dibuat menggunakan bahan-bahan segar pilihan dan racikan bumbu khas nusantara.' }}
                        </p>
                    </div>

                    {{-- Info pills --}}
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <span class="badge rounded-pill px-3 py-2 small" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(10px); color: #475569; border: 1px solid rgba(255, 255, 255, 0.8);">
                            <i class="bi bi-clock me-1 text-success"></i>Estimasi 15-25 menit
                        </span>
                        <span class="badge rounded-pill px-3 py-2 small" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(10px); color: #475569; border: 1px solid rgba(255, 255, 255, 0.8);">
                            <i class="bi bi-truck me-1 text-primary"></i>Gratis ongkir
                        </span>
                        <span class="badge rounded-pill px-3 py-2 small" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(10px); color: #475569; border: 1px solid rgba(255, 255, 255, 0.8);">
                            <i class="bi bi-shield-check me-1 text-info"></i>Higienis &amp; Segar
                        </span>
                    </div>
                </div>

                {{-- Order / Add to Cart Form --}}
                <div class="pt-3 border-top mt-auto">
                    @auth
                        @if(auth()->user()->isCustomer())
                            @if($product->stock > 0 && ($product->restaurant->is_open ?? true))
                            <form id="addToCartForm">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <label class="fw-bold small text-dark flex-shrink-0">Jumlah Porsi:</label>
                                    <div class="d-flex align-items-center gap-1.5">
                                        <button type="button" class="btn btn-light btn-sm px-2.5 rounded-pill fw-bold" onclick="stepQty(-1)" aria-label="Kurang"><i class="bi bi-dash-lg"></i></button>
                                        <input type="number" id="qtyInput" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="form-control text-center fw-bold rounded-pill" style="width: 70px;">
                                        <button type="button" class="btn btn-light btn-sm px-2.5 rounded-pill fw-bold" onclick="stepQty(1)" aria-label="Tambah"><i class="bi bi-plus-lg"></i></button>
                                    </div>
                                </div>
                                <button type="button" id="btnAddToCart" class="btn btn-primary btn-lg w-100 fw-bold d-flex align-items-center justify-content-center gap-2 rounded-pill py-3">
                                    <i class="bi bi-cart-plus-fill fs-5"></i>
                                    <span>Tambah ke Keranjang</span>
                                </button>
                            </form>
                            @elseif(!($product->restaurant->is_open ?? true))
                                <div class="alert text-center small mb-0 rounded-4 d-flex align-items-center justify-content-center gap-2 py-3 border-0" style="background: rgba(241, 245, 249, 0.85); backdrop-filter: blur(16px); color: #64748b;">
                                    <i class="bi bi-shop fs-5"></i>
                                    <span>Restoran sedang tutup dan tidak menerima pesanan saat ini.</span>
                                </div>
                            @else
                                <div class="alert text-center small mb-0 rounded-4 d-flex align-items-center justify-content-center gap-2 py-3 border-0" style="background: rgba(254, 226, 226, 0.85); backdrop-filter: blur(16px); color: #991b1b;">
                                    <i class="bi bi-bag-x fs-5"></i>
                                    <span>Maaf, menu hidangan ini sedang habis.</span>
                                </div>
                            @endif
                        @else
                            <div class="alert text-center small mb-0 rounded-4 border-0" style="background: rgba(224, 242, 254, 0.85); backdrop-filter: blur(16px); color: #0369a1;">
                                <i class="bi bi-info-circle me-1"></i>Masuk sebagai pembeli untuk memesan hidangan ini.
                            </div>
                        @endif
                    @else
                        <div class="d-flex flex-column gap-2">
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg w-100 fw-bold rounded-pill py-3">
                                <i class="bi bi-box-arrow-in-right me-1"></i>Masuk untuk Memesan
                            </a>
                            <small class="text-center text-muted">Belum punya akun? <a href="{{ route('register') }}" class="text-primary fw-bold">Daftar sekarang</a></small>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    {{-- Related Products --}}
    @if(isset($relatedProducts) && $relatedProducts->isNotEmpty())
    <div class="mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <span class="text-primary fw-bold text-uppercase small" style="letter-spacing: 1px;">Rekomendasi</span>
                <h4 class="fw-extrabold text-dark mb-0">Menu Lainnya di Restoran Ini</h4>
            </div>
            @if($product->restaurant)
            <a href="{{ route('restaurants.show', $product->restaurant) }}" class="btn btn-light btn-sm px-3.5 rounded-pill fw-bold">
                Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
            </a>
            @endif
        </div>
        <div class="row g-3 row-cols-2 row-cols-md-4">
            @foreach($relatedProducts as $related)
            <div class="col">
                <a href="{{ route('products.show', $related) }}" class="text-decoration-none">
                    <div class="card h-100 card-hover border-0 rounded-4 overflow-hidden">
                        <div class="position-relative" style="height: 140px;">
                            @if($related->image)
                                <img src="{{ asset('storage/'.$related->image) }}" class="w-100 h-100 object-fit-cover" alt="{{ $related->name }}">
                            @else
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, rgba(255, 242, 237, 0.8), rgba(255, 224, 211, 0.8));">
                                    <i class="bi bi-egg-fried fs-3" style="color: var(--liquid-primary);"></i>
                                </div>
                            @endif
                            @if($related->stock <= 0)
                                <div class="position-absolute d-flex align-items-center justify-content-center" style="top:0;left:0;right:0;bottom:0;background:rgba(15,23,42,0.65); backdrop-filter: blur(2px);">
                                    <span class="badge bg-danger small">Habis</span>
                                </div>
                            @endif
                        </div>
                        <div class="card-body p-3">
                            <h6 class="fw-bold text-dark mb-1 text-truncate small">{{ $related->name }}</h6>
                            <span class="fw-bold small" style="color: var(--liquid-primary);">Rp {{ number_format($related->price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Product Reviews Section (Glass Card) --}}
    @if($reviews->isNotEmpty())
    <div class="card border-0 rounded-4 p-4 p-md-5 mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-extrabold text-dark mb-0">
                <i class="bi bi-chat-quote-fill me-2 text-primary"></i>Ulasan Pelanggan
            </h4>
            <span class="badge rounded-pill px-3 py-2" style="background: rgba(255, 255, 255, 0.8); color: #475569; border: 1px solid rgba(255, 255, 255, 0.9);">{{ $reviews->count() }} ulasan</span>
        </div>
        <div class="row g-3">
            @foreach($reviews as $review)
            <div class="col-md-6">
                <div class="p-3.5 rounded-4 h-100 border-0" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.8); box-shadow: 0 4px 12px rgba(15, 23, 42, 0.03);">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width:32px;height:32px;background:linear-gradient(135deg, #ff5722, #ea580c);font-size:0.8rem; box-shadow: 0 4px 10px rgba(234,88,12,0.3);">
                                {{ strtoupper(substr($review->user->name ?? 'P', 0, 1)) }}
                            </div>
                            <div>
                                <span class="fw-bold text-dark small d-block lh-1">{{ $review->user->name ?? 'Pelanggan' }}</span>
                                <small class="text-muted" style="font-size:0.7rem;">{{ $review->created_at?->diffForHumans() }}</small>
                            </div>
                        </div>
                        <div class="text-warning small font-monospace">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : ' text-muted' }}" style="font-size:0.75rem;"></i>
                            @endfor
                        </div>
                    </div>
                    <p class="small text-secondary mb-0">{{ $review->comment ?? 'Tidak ada catatan tertulis.' }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

{{-- Confirmation Modal (Liquid Glass Dialog) --}}
<div class="modal fade" id="confirmCartModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 overflow-hidden">
            <div class="modal-body p-0">
                <div class="text-center p-4 pb-3" style="background: linear-gradient(135deg, rgba(255, 242, 237, 0.85), rgba(255, 255, 255, 0.9));">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:68px;height:68px;background:linear-gradient(135deg, #ff5722, #ea580c);box-shadow: 0 8px 20px rgba(234,88,12,0.35);">
                        <i class="bi bi-cart-plus-fill text-white fs-2"></i>
                    </div>
                    <h5 class="fw-extrabold text-dark mb-1">Konfirmasi Pesanan</h5>
                    <p class="text-muted small mb-0">Pastikan hidangan pilihan Anda sudah sesuai</p>
                </div>
                <div class="px-4 py-3">
                    <div class="d-flex gap-3 align-items-center p-3 rounded-4 mb-3" style="background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.85);">
                        @if($product->image)
                            <img src="{{ asset('storage/'.$product->image) }}" class="rounded-3 object-fit-cover flex-shrink-0" style="width:60px;height:60px;" alt="">
                        @else
                            <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width:60px;height:60px;background:rgba(255,87,34,0.15);">
                                <i class="bi bi-egg-fried fs-4 text-primary"></i>
                            </div>
                        @endif
                        <div class="flex-grow-1 min-width-0">
                            <h6 class="fw-bold text-dark mb-0 text-truncate">{{ $product->name }}</h6>
                            <small class="text-muted">{{ $product->restaurant->name ?? '' }}</small>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <span class="text-muted small">Harga Satuan</span>
                        <span class="fw-semibold">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <span class="text-muted small">Jumlah Porsi</span>
                        <span class="fw-bold" id="modalQty">1</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2">
                        <span class="fw-bold text-dark">Total Harga</span>
                        <span class="fw-extrabold fs-5" style="color:var(--liquid-primary);" id="modalTotal">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    </div>
                </div>
                <div class="px-4 pb-4 d-flex gap-2">
                    <button type="button" class="btn btn-light flex-fill py-2.5 fw-bold rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="btnConfirmAdd" class="btn btn-primary flex-fill py-2.5 fw-bold d-flex align-items-center justify-content-center gap-2 rounded-pill">
                        <i class="bi bi-check2-circle"></i>
                        <span>Ya, Tambahkan</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Different Restaurant Warning Modal --}}
<div class="modal fade" id="diffRestoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 rounded-4 text-center p-4">
            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mx-auto mb-3" style="width:64px;height:64px;background:rgba(254, 243, 199, 0.9);box-shadow: 0 6px 16px rgba(245,158,11,0.25);">
                <i class="bi bi-exclamation-triangle-fill fs-3 text-warning"></i>
            </div>
            <h6 class="fw-extrabold text-dark mb-2">Restoran Berbeda</h6>
            <p class="text-muted small mb-3" id="diffRestoMsg"></p>
            <div class="d-flex flex-column gap-2">
                <form action="{{ route('customer.cart.clear') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-warning btn-sm w-100 fw-bold rounded-pill py-2 text-dark">Kosongkan &amp; Tambah</button>
                </form>
                <button type="button" class="btn btn-light btn-sm w-100 fw-bold rounded-pill" data-bs-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

{{-- Success Toast (Liquid Glass Floating Pill) --}}
<div class="position-fixed bottom-0 end-0 p-3" style="z-index:1090;">
    <div id="cartToast" class="toast align-items-center border-0" role="alert" style="border-radius:1.5rem; background: rgba(15, 23, 42, 0.88); backdrop-filter: blur(24px) saturate(190%); border: 1px solid rgba(255, 255, 255, 0.2); box-shadow: 0 20px 48px rgba(0,0,0,0.35);">
        <div class="d-flex p-3 gap-3 align-items-center">
            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:42px;height:42px;background:linear-gradient(135deg, #ff5722, #ea580c);box-shadow: 0 6px 16px rgba(234,88,12,0.4);">
                <i class="bi bi-check-lg text-white fs-5"></i>
            </div>
            <div class="flex-grow-1">
                <strong class="text-white small d-block">Berhasil ditambahkan!</strong>
                <div class="text-white-50 small" id="toastDetail"></div>
            </div>
            <a href="{{ route('customer.cart.index') }}" class="btn btn-primary btn-sm px-3 flex-shrink-0 rounded-pill fw-bold">Keranjang</a>
        </div>
    </div>
</div>

@push('scripts')
<script>
const unitPrice = {{ $product->price }};

function stepQty(change) {
    const input = document.getElementById('qtyInput');
    const curVal = parseInt(input.value) || 1;
    const minVal = parseInt(input.min) || 1;
    const maxVal = parseInt(input.max) || 999;
    const nextVal = curVal + change;
    if (nextVal >= minVal && nextVal <= maxVal) {
        input.value = nextVal;
    }
}

function formatRp(n) {
    return 'Rp ' + n.toLocaleString('id-ID');
}

// Open confirmation modal
document.getElementById('btnAddToCart')?.addEventListener('click', function() {
    const qty = parseInt(document.getElementById('qtyInput').value) || 1;
    document.getElementById('modalQty').textContent = qty;
    document.getElementById('modalTotal').textContent = formatRp(qty * unitPrice);
    bootstrap.Modal.getOrCreateInstance(document.getElementById('confirmCartModal')).show();
});

// Confirm add to cart via AJAX
document.getElementById('btnConfirmAdd')?.addEventListener('click', function() {
    const btn = this;
    const form = document.getElementById('addToCartForm');
    const formData = new FormData(form);

    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Menambahkan...';

    fetch("{{ route('customer.cart.add') }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
            'Accept': 'application/json',
        },
        body: formData,
    })
    .then(r => r.json().then(data => ({status: r.status, data})))
    .then(({status, data}) => {
        bootstrap.Modal.getOrCreateInstance(document.getElementById('confirmCartModal')).hide();
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-check2-circle"></i><span>Ya, Tambahkan</span>';

        if (status === 409 && data.different_restaurant) {
            document.getElementById('diffRestoMsg').textContent = data.message;
            bootstrap.Modal.getOrCreateInstance(document.getElementById('diffRestoModal')).show();
            return;
        }

        if (!data.success) {
            alert(data.message || 'Gagal menambahkan ke keranjang.');
            return;
        }

        // Show success toast
        const qty = parseInt(document.getElementById('qtyInput').value) || 1;
        document.getElementById('toastDetail').textContent = qty + ' porsi · ' + formatRp(data.cart_total);
        const toast = new bootstrap.Toast(document.getElementById('cartToast'), {delay: 3500});
        toast.show();

        // Update navbar cart badges & global floating cart
        if (typeof window.updateGlobalCart === 'function') {
            window.updateGlobalCart(data.cart_count, data.cart_total, data.restaurant_name);
        } else {
            const mBadge = document.getElementById('mobileNavCartBadge');
            const dBadge = document.getElementById('desktopNavCartBadge');
            if (mBadge) { mBadge.textContent = data.cart_count; mBadge.classList.toggle('d-none', data.cart_count <= 0); }
            if (dBadge) { dBadge.textContent = data.cart_count; dBadge.classList.toggle('d-none', data.cart_count <= 0); }
        }
    })
    .catch(() => {
        bootstrap.Modal.getOrCreateInstance(document.getElementById('confirmCartModal')).hide();
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-check2-circle"></i><span>Ya, Tambahkan</span>';
        alert('Terjadi kesalahan jaringan. Silakan coba lagi.');
    });
});
</script>
@endpush
@endsection
