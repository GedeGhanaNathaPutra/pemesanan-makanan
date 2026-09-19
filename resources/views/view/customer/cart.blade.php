@extends('layouts.app')
@section('title', 'Keranjang Belanja – FoodOrder')
@section('content')
<div class="container-fluid px-3 px-md-4 px-lg-5 py-4">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <span class="text-primary fw-bold text-uppercase small" style="letter-spacing: 1px;">Checkout Makanan</span>
            <h3 class="fw-extrabold mb-0 text-dark">
                <i class="bi bi-cart3 me-2 text-primary"></i>Keranjang Belanja
            </h3>
            <p class="text-muted small mb-0">Periksa kembali hidangan lezat pesanan Anda sebelum checkout</p>
        </div>
        @if($cart && $cart->items->isNotEmpty())
        <form action="{{ route('customer.cart.clear') }}" method="POST" onsubmit="return confirm('Kosongkan semua menu di keranjang belanja Anda?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger btn-sm px-3.5 py-2 rounded-pill fw-bold">
                <i class="bi bi-trash3 me-1"></i>Kosongkan Keranjang
            </button>
        </form>
        @endif
    </div>

    @if(!$cart || $cart->items->isEmpty())
    <div class="card p-5 text-center border-0 rounded-4 my-3">
        <div class="category-icon-glass mx-auto mb-3" style="width: 88px; height: 88px; background: rgba(255, 242, 237, 0.85); color: var(--liquid-primary);">
            <i class="bi bi-cart-x fs-1"></i>
        </div>
        <h4 class="fw-extrabold text-dark">Keranjang Belanja Anda Kosong</h4>
        <p class="text-muted small mb-4" style="max-width: 420px; margin: 0 auto;">
            Perut lapar? Jelajahi berbagai restoran mitra terbaik kami dan pilih hidangan lezat favorit Anda.
        </p>
        <div>
            <a href="{{ route('restaurants') }}" class="btn btn-primary px-4 py-2.5 rounded-pill fw-bold">
                <i class="bi bi-search me-1"></i>Cari Makanan &amp; Restoran
            </a>
        </div>
    </div>
    @else
    <div class="row g-4">
        {{-- Cart Item List (Liquid Glass Card) --}}
        <div class="col-lg-8">
            <div class="card border-0 rounded-4 overflow-hidden mb-3">
                <div class="py-3 px-4 border-bottom d-flex align-items-center justify-content-between" style="background: rgba(255, 255, 255, 0.5);">
                    <div class="d-flex align-items-center gap-2.5">
                        <span class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: linear-gradient(135deg, #ff5722, #ea580c); color: #ffffff; box-shadow: 0 4px 10px rgba(234,88,12,0.3);">
                            <i class="bi bi-shop fs-6"></i>
                        </span>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark">{{ $cart->restaurant->name ?? 'Restoran' }}</h6>
                            <small class="text-muted" style="font-size: 0.75rem;">1 Restoran per Pesanan</small>
                        </div>
                    </div>
                    <span class="badge rounded-pill small fw-bold px-3 py-1.5" style="background: rgba(255, 255, 255, 0.8); color: #475569; border: 1px solid rgba(255, 255, 255, 0.9);">{{ $cart->items->count() }} Variasi Menu</span>
                </div>

                <div class="p-0">
                    @foreach($cart->items as $item)
                    <div class="p-3.5 p-md-4 border-bottom d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
                        <div class="d-flex align-items-center gap-3 min-width-0 flex-grow-1">
                            {{-- Product Thumbnail with Glass Specular Rim --}}
                            <div class="rounded-4 overflow-hidden flex-shrink-0" style="width: 72px; height: 72px; border: 1px solid rgba(255, 255, 255, 0.85); box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05);">
                                @if($item->product->image)
                                    <img src="{{ asset('storage/'.$item->product->image) }}" class="w-100 h-100 object-fit-cover" alt="{{ $item->product->name }}">
                                @else
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, rgba(255, 242, 237, 0.8), rgba(255, 224, 211, 0.8));">
                                        <i class="bi bi-egg-fried fs-4" style="color: var(--liquid-primary);"></i>
                                    </div>
                                @endif
                            </div>

                            {{-- Product Name & Price --}}
                            <div class="min-width-0 flex-grow-1">
                                <h6 class="fw-bold text-dark mb-1 text-truncate" title="{{ $item->product->name }}">
                                    {{ $item->product->name }}
                                </h6>
                                <p class="small text-muted mb-0">
                                    Rp {{ number_format($item->price, 0, ',', '.') }} / porsi
                                </p>
                            </div>
                        </div>

                        {{-- Quantity Stepper & Subtotal --}}
                        <div class="d-flex align-items-center justify-content-between justify-content-sm-end gap-3 flex-shrink-0 pt-2 pt-sm-0 border-top border-sm-0">
                            {{-- Quantity Update Form with Glass Stepper --}}
                            <form action="{{ route('customer.cart.update', $item) }}" method="POST" class="d-flex align-items-center gap-1">
                                @csrf
                                @method('PUT')
                                <button type="button" class="btn btn-light btn-sm px-2.5 py-1 text-muted fw-bold rounded-pill" onclick="stepQty(this, -1)" aria-label="Kurang"><i class="bi bi-dash-lg"></i></button>
                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}" class="form-control form-control-sm text-center fw-bold rounded-pill p-1" style="width: 52px;" onchange="this.closest('form').submit()">
                                <button type="button" class="btn btn-light btn-sm px-2.5 py-1 text-muted fw-bold rounded-pill" onclick="stepQty(this, 1)" aria-label="Tambah"><i class="bi bi-plus-lg"></i></button>
                            </form>

                            {{-- Subtotal --}}
                            <div class="text-end" style="min-width: 110px;">
                                <div class="fw-extrabold fs-6" style="color: var(--liquid-primary);">
                                    Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                </div>
                            </div>

                            {{-- Remove Button --}}
                            <form action="{{ route('customer.cart.remove', $item) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-light text-danger btn-sm p-2 rounded-circle" title="Hapus menu ini">
                                    <i class="bi bi-trash3 fs-6"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="d-flex align-items-center gap-2 text-muted small p-2">
                <i class="bi bi-info-circle text-primary"></i>
                <span>Ingin memesan menu lain? <a href="{{ route('restaurants.show', $cart->restaurant) }}" class="text-primary fw-bold">Tambah dari restoran ini</a></span>
            </div>
        </div>

        {{-- Order Summary Sidebar (Liquid Glass Card) --}}
        <div class="col-lg-4">
            <div class="card border-0 rounded-4 p-4 sticky-top" style="top: 85px;">
                <h5 class="fw-extrabold text-dark mb-3">Ringkasan Belanja</h5>

                <div class="d-flex justify-content-between small text-muted mb-2">
                    <span>Total Item</span>
                    <span class="text-dark fw-bold">{{ $cart->items->sum('quantity') }} porsi</span>
                </div>
                <div class="d-flex justify-content-between small text-muted mb-2">
                    <span>Subtotal Menu</span>
                    <span class="text-dark fw-bold">Rp {{ number_format($cart->total, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between small text-muted mb-3">
                    <span>Biaya Pengantaran</span>
                    <span class="badge badge-selesai px-2.5 py-1 rounded-pill">GRATIS</span>
                </div>

                <hr class="my-3">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <span class="fw-bold text-dark">Total Pembayaran</span>
                    <span class="fw-extrabold fs-4" style="color: var(--liquid-primary);">
                        Rp {{ number_format($cart->total, 0, ',', '.') }}
                    </span>
                </div>

                <button type="button" class="btn btn-primary btn-lg w-100 fw-bold d-flex align-items-center justify-content-center gap-2 rounded-pill py-3" data-bs-toggle="modal" data-bs-target="#checkoutModal">
                    <i class="bi bi-bag-check-fill fs-5"></i>
                    <span>Lanjut ke Checkout</span>
                </button>
            </div>
        </div>
    </div>

    {{-- CHECKOUT MODAL (Liquid Glass Dialog) --}}
    <div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 overflow-hidden">
                <div class="modal-header border-0 py-3.5 px-4" style="background: linear-gradient(135deg, rgba(255, 242, 237, 0.85), rgba(255, 255, 255, 0.9));">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width:36px;height:36px;background:linear-gradient(135deg, #ff5722, #ea580c);color:#fff;box-shadow: 0 4px 10px rgba(234,88,12,0.3);">
                            <i class="bi bi-bag-check fs-6"></i>
                        </div>
                        <h5 class="modal-title fw-extrabold text-dark mb-0" id="checkoutModalLabel">Konfirmasi Pemesanan</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ route('customer.orders.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        {{-- Restaurant & Price Recap --}}
                        <div class="p-3 rounded-4 mb-3" style="background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.85);">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="small text-muted">Mitra Restoran:</span>
                                <strong class="small text-dark">{{ $cart->restaurant->name }}</strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="small text-muted">Total Pembayaran:</span>
                                <span class="fw-extrabold fs-5" style="color: var(--liquid-primary);">
                                    Rp {{ number_format($cart->total, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        {{-- Delivery Address --}}
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label fw-bold small text-dark mb-0">
                                    Alamat Pengantaran <span class="text-danger">*</span>
                                </label>
                                @if(auth()->user()->address)
                                <button type="button" class="btn btn-link btn-sm p-0 text-primary fw-bold text-decoration-none" style="font-size: 0.72rem;" onclick="document.getElementById('checkoutAddressInput').value = '{{ addslashes(auth()->user()->address) }}'">
                                    <i class="bi bi-geo-alt me-0.5"></i>Gunakan Alamat Profil
                                </button>
                                @endif
                            </div>
                            <textarea name="address" id="checkoutAddressInput" class="form-control" rows="3" placeholder="Tuliskan alamat lengkap pengantaran (nomor rumah, patokan, RT/RW)..." required>{{ auth()->user()->address }}</textarea>
                            <small class="text-muted" style="font-size: 0.72rem;">Pastikan alamat jelas agar kurir dapat menemukan lokasi dengan cepat.</small>
                        </div>

                        {{-- Payment Method Selection (Frosted Glass Radio Cards) --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-dark mb-2">
                                Pilih Metode Pembayaran <span class="text-danger">*</span>
                            </label>
                            <div class="d-flex flex-column gap-2.5">
                                <div>
                                    <input type="radio" class="btn-check" name="payment_method" id="pay_transfer" value="transfer" checked>
                                    <label class="payment-radio-card d-flex align-items-center justify-content-between w-100 p-3" for="pay_transfer">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: rgba(224, 242, 254, 0.85); color: #0284c7; border: 1px solid rgba(255, 255, 255, 0.8); box-shadow: 0 4px 12px rgba(2,132,199,0.15);">
                                                <i class="bi bi-bank fs-5"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark small">Transfer Bank Manual</div>
                                                <small class="text-muted">BCA, Mandiri, BRI, BNI (Upload bukti transfer)</small>
                                            </div>
                                        </div>
                                        <i class="bi bi-chevron-right text-muted small"></i>
                                    </label>
                                </div>

                                <div>
                                    <input type="radio" class="btn-check" name="payment_method" id="pay_ewallet" value="ewallet">
                                    <label class="payment-radio-card d-flex align-items-center justify-content-between w-100 p-3" for="pay_ewallet">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: rgba(254, 243, 199, 0.85); color: #d97706; border: 1px solid rgba(255, 255, 255, 0.8); box-shadow: 0 4px 12px rgba(217,119,6,0.15);">
                                                <i class="bi bi-qr-code fs-5"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark small">E-Wallet / QRIS Dinamis</div>
                                                <small class="text-muted">Scan QRIS instant via GoPay, OVO, DANA, ShopeePay</small>
                                            </div>
                                        </div>
                                        <i class="bi bi-chevron-right text-muted small"></i>
                                    </label>
                                </div>

                                <div>
                                    <input type="radio" class="btn-check" name="payment_method" id="pay_cod" value="cod">
                                    <label class="payment-radio-card d-flex align-items-center justify-content-between w-100 p-3" for="pay_cod">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: rgba(220, 252, 231, 0.85); color: #15803d; border: 1px solid rgba(255, 255, 255, 0.8); box-shadow: 0 4px 12px rgba(21,128,61,0.15);">
                                                <i class="bi bi-cash-stack fs-5"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark small">Bayar di Tempat (COD)</div>
                                                <small class="text-muted">Bayar tunai langsung ke kurir saat pesanan tiba</small>
                                            </div>
                                        </div>
                                        <i class="bi bi-chevron-right text-muted small"></i>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer border-0 py-3 px-4" style="background: rgba(255, 255, 255, 0.5);">
                        <button type="button" class="btn btn-light px-3.5 rounded-pill fw-bold" data-bs-dismiss="modal">Kembali</button>
                        <button type="submit" class="btn btn-primary fw-bold px-4 rounded-pill">
                            <i class="bi bi-check2-circle me-1"></i>Buat Pesanan Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
function stepQty(btn, change) {
    const form = btn.closest('form');
    const input = form.querySelector('input[name=quantity]');
    const cur = parseInt(input.value) || 1;
    const min = parseInt(input.min) || 1;
    const max = parseInt(input.max) || 999;
    const nextVal = cur + change;
    if (nextVal >= min && nextVal <= max) {
        input.value = nextVal;
        form.submit();
    }
}
</script>
@endpush
@endsection
