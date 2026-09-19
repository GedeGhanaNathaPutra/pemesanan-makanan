@extends('layouts.app')
@section('title', $restaurant->name . ' – Menu Makanan & Ulasan')

@push('styles')
<style>
    .menu-card {
        border: 1px solid rgba(226, 232, 240, 0.95) !important;
        background: #ffffff !important;
        border-radius: 1.15rem !important;
        box-shadow: 0 1px 3px 0 rgba(15, 23, 42, 0.04), 0 1px 2px -1px rgba(15, 23, 42, 0.04);
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .menu-card:hover {
        transform: translateY(-3px);
        border-color: #cbd5e1 !important;
        box-shadow: 0 12px 28px -6px rgba(15, 23, 42, 0.09), 0 8px 10px -6px rgba(15, 23, 42, 0.04) !important;
    }
    .menu-card-img {
        transition: transform 0.35s ease;
    }
    .menu-card:hover .menu-card-img {
        transform: scale(1.05);
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-3 px-md-4 px-lg-5 py-4">
    {{-- Navigation Breadcrumbs & Back Button --}}
    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
        <a href="{{ route('restaurants') }}" class="btn btn-light btn-sm px-3.5 py-1.5 rounded-pill fw-bold d-inline-flex align-items-center gap-1.5" style="box-shadow: var(--glass-btn-light-shadow);">
            <i class="bi bi-arrow-left"></i>
            <span>Semua Restoran</span>
        </a>
        <div class="d-flex align-items-center gap-2 small text-muted">
            <i class="bi bi-shop text-primary"></i>
            <span>Katalog Mitra</span>
            <span>&bull;</span>
            <strong class="text-dark">{{ $restaurant->name }}</strong>
        </div>
    </div>

    {{-- RESTAURANT HERO GLASS BLOCK --}}
    <div class="card rounded-4 overflow-hidden mb-4" style="border: 1px solid rgba(226, 232, 240, 0.95); background: #ffffff;">
        <div class="row g-0">
            <div class="col-md-4 col-lg-3 position-relative overflow-hidden" style="min-height: 240px;">
                @if($restaurant->image)
                    <img src="{{ asset('storage/'.$restaurant->image) }}" class="w-100 h-100 object-fit-cover" style="min-height: 240px;" alt="{{ $restaurant->name }}">
                @else
                    <div class="h-100 w-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, rgba(255, 87, 34, 0.85), rgba(234, 88, 12, 0.9)); min-height: 240px;">
                        <i class="bi bi-shop-window text-white" style="font-size: 4.5rem; opacity: 0.95;"></i>
                    </div>
                @endif
                <div class="position-absolute inset-0 w-100 h-100" style="top:0;left:0;right:0;bottom:0;background:linear-gradient(to top, rgba(15,23,42,0.45) 0%, transparent 60%);pointer-events:none;"></div>
                <span class="position-absolute top-0 start-0 m-3 badge {{ $restaurant->is_open ? 'badge-selesai' : 'badge-pending' }} px-3 py-1.5 rounded-pill">
                    <i class="bi bi-circle-fill me-1" style="font-size: 0.45rem;"></i>{{ $restaurant->is_open ? 'Buka Sekarang' : 'Restoran Tutup' }}
                </span>
            </div>
            <div class="col-md-8 col-lg-9">
                <div class="card-body p-4 d-flex flex-column justify-content-center h-100">
                    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-2">
                        <div>
                            <span class="badge rounded-pill px-2.5 py-1 mb-1.5 d-inline-flex align-items-center gap-1" style="background: rgba(255, 87, 34, 0.1); color: var(--liquid-primary); border: 1px solid rgba(255, 87, 34, 0.25); font-size: 0.72rem; letter-spacing: 0.5px;">
                                <i class="bi bi-patch-check-fill"></i>
                                <span>MITRA KULINER TERVERIFIKASI</span>
                            </span>
                            <h2 class="fw-extrabold mb-1 text-dark">{{ $restaurant->name }}</h2>
                        </div>
                        <div class="badge rounded-pill px-3 py-2 fs-6 d-inline-flex align-items-center gap-1.5" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(16px); color: #0f172a; border: 1px solid rgba(255, 255, 255, 0.95); box-shadow: 0 6px 16px rgba(0,0,0,0.08);">
                            <i class="bi bi-star-fill text-warning"></i>
                            <strong>{{ number_format($restaurant->average_rating, 1) }}</strong>
                            <span class="text-muted fw-normal small">({{ $reviews->count() }} ulasan)</span>
                        </div>
                    </div>

                    <p class="text-muted small mb-1">
                        <i class="bi bi-geo-alt me-1 text-danger"></i>{{ $restaurant->address }}
                    </p>
                    @if($restaurant->phone)
                        <p class="text-muted small mb-2">
                            <i class="bi bi-telephone me-1 text-primary"></i>{{ $restaurant->phone }}
                        </p>
                    @endif
                    @if($restaurant->description)
                        <p class="text-secondary small mb-3" style="line-height: 1.6;">{{ $restaurant->description }}</p>
                    @endif

                    <div class="d-flex flex-wrap gap-2 pt-2.5 border-top small">
                        <span class="d-inline-flex align-items-center gap-1.5 px-3 py-1 rounded-pill fw-semibold" style="background: rgba(255, 255, 255, 0.65); backdrop-filter: blur(10px); color: #334155; border: 1px solid rgba(255, 255, 255, 0.85);">
                            <i class="bi bi-box-seam text-primary"></i>{{ $products->count() }} Pilihan Menu
                        </span>
                        <span class="d-inline-flex align-items-center gap-1.5 px-3 py-1 rounded-pill fw-semibold" style="background: rgba(255, 255, 255, 0.65); backdrop-filter: blur(10px); color: #334155; border: 1px solid rgba(255, 255, 255, 0.85);">
                            <i class="bi bi-clock text-success"></i>Estimasi Masak: 15-25 Menit
                        </span>
                        <span class="d-inline-flex align-items-center gap-1.5 px-3 py-1 rounded-pill fw-semibold" style="background: rgba(255, 255, 255, 0.65); backdrop-filter: blur(10px); color: #334155; border: 1px solid rgba(255, 255, 255, 0.85);">
                            <i class="bi bi-shield-check text-info"></i>Higienis &amp; Segar
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Active Cart Floating Banner for Customer --}}
    @auth
        @if(auth()->user()->isCustomer())
            @php
                $activeCart = auth()->user()->carts()->where('restaurant_id', $restaurant->id)->with('items.product')->first();
                $activeCartCount = $activeCart?->items?->sum('quantity') ?? 0;
            @endphp
            @if($activeCartCount > 0)
                <div class="p-3.5 rounded-4 mb-4 d-flex align-items-center justify-content-between flex-wrap gap-3" style="background: linear-gradient(135deg, rgba(255, 242, 237, 0.95), rgba(255, 255, 255, 0.92)); backdrop-filter: blur(20px); border: 2px solid var(--liquid-primary); box-shadow: 0 12px 28px -6px rgba(234,88,12,0.25);">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width: 46px; height: 46px; background: linear-gradient(135deg, #ff5722, #ea580c); box-shadow: 0 4px 14px rgba(234,88,12,0.4);">
                            <i class="bi bi-cart3 fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-0">Pesanan Aktif di Restoran Ini</h6>
                            <small class="text-muted">Terdapat <strong>{{ $activeCartCount }} porsi</strong> menu (Rp {{ number_format($activeCart->total, 0, ',', '.') }}) dalam keranjang belanja Anda.</small>
                        </div>
                    </div>
                    <a href="{{ route('customer.cart.index') }}" class="btn btn-primary btn-sm px-4 py-2 rounded-pill fw-bold d-inline-flex align-items-center gap-2">
                        <i class="bi bi-bag-check-fill"></i>
                        <span>Buka Keranjang &amp; Checkout</span>
                    </a>
                </div>
            @endif
        @endif
    @endauth

    {{-- CONTENT ROW: PRODUCTS & REVIEWS --}}
    <div class="row g-4">
        {{-- Products Section --}}
        <div class="col-lg-8">
            {{-- Category Filter Bar (Glass Pills) --}}
            @if($categories->isNotEmpty())
            <div class="d-flex gap-2 overflow-x-auto pb-2 mb-3" style="white-space: nowrap; scrollbar-width: thin;">
                <button type="button" class="btn btn-primary btn-sm px-3.5 rounded-pill flex-shrink-0 fw-bold d-inline-flex align-items-center gap-1.5" data-filter="all">
                    <span>Semua Menu</span>
                    <span class="badge rounded-pill bg-white text-dark ms-1" style="font-size: 0.68rem;">{{ $products->count() }}</span>
                </button>
                @foreach($categories as $cat)
                    @php $catCount = $products->where('category_id', $cat->id)->count(); @endphp
                    <button type="button" class="btn btn-light btn-sm px-3.5 rounded-pill flex-shrink-0 fw-bold d-inline-flex align-items-center gap-1.5" data-filter="{{ $cat->id }}">
                        <span>{{ $cat->name }}</span>
                        <span class="badge rounded-pill bg-light text-secondary border ms-1" style="font-size: 0.68rem;">{{ $catCount }}</span>
                    </button>
                @endforeach
            </div>
            @endif

            {{-- Menu Heading & Guidance --}}
            <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                <div>
                    <h5 class="fw-extrabold text-dark mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-grid-fill text-primary fs-6"></i>
                        <span>Daftar Pilihan Menu</span>
                    </h5>
                    <small class="text-muted">Pilih menu untuk melihat rincian porsi dan memesan ke keranjang</small>
                </div>
                <span class="badge rounded-pill px-3 py-1.5 small text-secondary border bg-white" style="font-size: 0.75rem;">
                    {{ $products->count() }} Hidangan Tersedia
                </span>
            </div>

            @if(!$restaurant->is_open)
                <div class="alert text-center small mb-3 rounded-4 d-flex align-items-center justify-content-center gap-2 py-2.5 border" style="background: rgba(254, 243, 199, 0.9); backdrop-filter: blur(12px); color: #92400e; border-color: rgba(252, 211, 77, 0.7) !important;">
                    <i class="bi bi-shop fs-5"></i>
                    <span>Restoran saat ini sedang tutup. Anda dapat melihat menu, namun pesanan baru dapat dilakukan saat restoran buka kembali.</span>
                </div>
            @endif

            {{-- Products Grid --}}
            @if($products->isEmpty())
                <div class="card p-5 text-center rounded-4" style="border: 1px solid rgba(226, 232, 240, 0.95); background: #ffffff;">
                    <div class="category-icon-glass mx-auto mb-3" style="width:72px;height:72px;background:rgba(241,245,249,0.85);color:#64748b;">
                        <i class="bi bi-basket2 fs-2"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Belum Ada Menu Tersedia</h5>
                    <p class="small text-muted mb-0">Restoran ini sedang menyiapkan hidangan lezat terbaiknya.</p>
                </div>
            @else
                <div class="row g-3" id="product-grid">
                    @foreach($products as $product)
                    <div class="col-sm-6 product-item" data-category="{{ $product->category_id }}">
                        <div class="card menu-card h-100 rounded-4 overflow-hidden d-flex flex-column position-relative">
                            {{-- Product Image Area with Badges --}}
                            <a href="{{ route('products.show', $product) }}" class="text-decoration-none position-relative overflow-hidden w-100 d-block" style="height: 175px; background: rgba(241, 245, 249, 0.6);" title="Lihat detail {{ $product->name }}">
                                @if($product->image)
                                    <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" class="w-100 h-100 object-fit-cover menu-card-img">
                                @else
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, rgba(255, 242, 237, 0.8), rgba(255, 224, 211, 0.8));">
                                        <i class="bi bi-egg-fried" style="font-size: 3.2rem; color: var(--liquid-primary);"></i>
                                    </div>
                                @endif

                                {{-- Category Floating Pill --}}
                                <span class="position-absolute top-0 start-0 m-2.5 badge rounded-pill px-2.5 py-1" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(12px); color: #334155; border: 1px solid rgba(226, 232, 240, 0.95); font-size: 0.7rem; box-shadow: 0 4px 10px rgba(0,0,0,0.06); font-weight: 700;">
                                    {{ $product->category->name ?? 'Menu' }}
                                </span>

                                {{-- Stock Status Pill / Overlay --}}
                                @if($product->stock <= 0)
                                    <div class="position-absolute top-0 end-0 bottom-0 start-0 d-flex align-items-center justify-content-center" style="background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(2px);">
                                        <span class="badge bg-danger text-white px-3 py-1.5 rounded-pill fw-bold shadow-sm" style="font-size: 0.72rem;">
                                            <i class="bi bi-x-circle me-1"></i>Stok Habis
                                        </span>
                                    </div>
                                @else
                                    <span class="position-absolute top-0 end-0 m-2.5 badge rounded-pill px-2.5 py-1" style="background: rgba(220, 252, 231, 0.95); backdrop-filter: blur(12px); color: #166534; border: 1px solid #bbf7d0; font-size: 0.7rem; font-weight: 700;">
                                        <i class="bi bi-check2 me-1"></i>Stok: {{ $product->stock }}
                                    </span>
                                @endif
                            </a>

                            {{-- Product Details Body --}}
                            <div class="p-3.5 d-flex flex-column justify-content-between flex-grow-1">
                                <div class="mb-3">
                                    <a href="{{ route('products.show', $product) }}" class="text-decoration-none d-block mb-1">
                                        <h6 class="fw-extrabold text-dark mb-1 text-truncate hover-primary" title="{{ $product->name }}" style="font-size: 0.95rem;">
                                            {{ $product->name }}
                                        </h6>
                                    </a>
                                    <p class="small text-muted mb-0" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; font-size: 0.78rem; line-height: 1.45; min-height: 2.3em;">
                                        {{ $product->description ?: 'Menu hidangan segar pilihan disiapkan dengan bahan berkualitas terbaik.' }}
                                    </p>
                                </div>

                                {{-- Footer: Price and CTA --}}
                                <div class="pt-2.5 border-top d-flex align-items-center justify-content-between gap-2">
                                    <div class="min-width-0">
                                        <small class="text-muted d-block" style="font-size: 0.68rem; font-weight: 600;">Harga per porsi</small>
                                        <div class="fw-extrabold text-truncate" style="color: var(--liquid-primary); font-size: 1.02rem; letter-spacing: -0.01em;">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </div>
                                    </div>

                                    <div class="flex-shrink-0">
                                        @if(!$restaurant->is_open)
                                            <span class="badge rounded-pill bg-light text-muted border px-2.5 py-1.5" style="font-size: 0.72rem;">
                                                <i class="bi bi-lock me-1"></i>Tutup
                                            </span>
                                        @elseif($product->stock <= 0)
                                            <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1.5" style="font-size: 0.72rem;">
                                                Habis
                                            </span>
                                        @else
                                            <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary btn-sm px-3 py-1.5 rounded-pill fw-bold d-inline-flex align-items-center gap-1.5" title="Lihat rincian porsi & pesan">
                                                <span>Pilih Menu</span>
                                                <i class="bi bi-arrow-right"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Empty state for category filter --}}
                <div id="categoryEmptyNotice" class="d-none card p-4 text-center rounded-4 my-3 text-muted" style="border: 1px solid rgba(226, 232, 240, 0.95); background: #ffffff;">
                    <i class="bi bi-funnel fs-2 text-secondary mb-1"></i>
                    <h6 class="fw-bold text-dark mb-1">Tidak Ada Menu di Kategori Ini</h6>
                    <small>Silakan pilih kategori menu lainnya di atas.</small>
                </div>
            @endif
        </div>

        {{-- Reviews Sidebar (Glass Card) --}}
        <div class="col-lg-4">
            <div class="card rounded-4 p-4" style="border: 1px solid rgba(226, 232, 240, 0.95); background: #ffffff;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="bi bi-star-fill text-warning me-1.5"></i>Ulasan Pelanggan
                    </h5>
                    <span class="badge rounded-pill px-2.5 py-1 small" style="background: rgba(255, 255, 255, 0.85); color: #0f172a; border: 1px solid rgba(255, 255, 255, 0.9);">{{ $reviews->count() }} Total</span>
                </div>

                @if($reviews->isEmpty())
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-chat-heart fs-2 d-block mb-1 text-secondary opacity-50"></i>
                        <p class="small mb-0">Belum ada ulasan untuk restoran ini. Jadilah yang pertama memberikan penilaian setelah menikmati hidangannya!</p>
                    </div>
                @else
                    <div class="d-flex flex-column gap-3" style="max-height: 380px; overflow-y: auto; padding-right: 4px;">
                        @foreach($reviews as $review)
                        <div class="p-3 rounded-4 border-0" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.75); box-shadow: 0 4px 12px rgba(15, 23, 42, 0.03);">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width: 32px; height: 32px; background: linear-gradient(135deg, #ff5722, #ea580c); font-size: 0.8rem; box-shadow: 0 4px 10px rgba(234,88,12,0.3);">
                                        {{ strtoupper(substr($review->user->name ?? 'P', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="mb-0 small fw-bold text-dark lh-sm">{{ $review->user->name ?? 'Pelanggan' }}</p>
                                        <small class="text-muted" style="font-size: 0.7rem;">{{ $review->created_at?->diffForHumans() }}</small>
                                    </div>
                                </div>
                                <div class="text-warning small font-monospace">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : ' text-muted' }}" style="font-size: 0.75rem;"></i>
                                    @endfor
                                </div>
                            </div>
                            @if($review->comment)
                                <p class="small text-secondary mb-0 mt-2">{{ $review->comment }}</p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                @endif

                {{-- Review Form --}}
                @auth
                    @if(auth()->user()->isCustomer())
                    <div class="mt-4 pt-3 border-top">
                        <h6 class="fw-bold text-dark small mb-2"><i class="bi bi-pencil me-1 text-primary"></i>Beri Penilaian Restoran</h6>
                        <form action="{{ route('customer.reviews.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="restaurant_id" value="{{ $restaurant->id }}">
                            <div class="mb-2">
                                <label class="small text-muted mb-1 fw-semibold"><i class="bi bi-star-fill text-warning me-1"></i>Rating Bintang</label>
                                <select name="rating" class="form-select form-select-sm" required>
                                    <option value="5">5 Bintang (Sangat Puas)</option>
                                    <option value="4">4 Bintang (Enak &amp; Puas)</option>
                                    <option value="3">3 Bintang (Cukup Baik)</option>
                                    <option value="2">2 Bintang (Kurang)</option>
                                    <option value="1">1 Bintang (Tidak Sesuai)</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <textarea name="comment" class="form-control form-control-sm" rows="2" placeholder="Tulis tanggapan atau ulasan rasa hidangan..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold rounded-pill">
                                <i class="bi bi-send me-1"></i>Kirim Penilaian
                            </button>
                            <small class="text-muted d-block text-center mt-1.5" style="font-size: 0.68rem;">
                                * Ulasan hanya berlaku setelah Anda menyelesaikan pesanan dari restoran ini.
                            </small>
                        </form>
                    </div>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</div>

{{-- Different Restaurant Warning Modal --}}
<div class="modal fade" id="restoDetailDiffModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 rounded-4 text-center p-4">
            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mx-auto mb-3" style="width:64px;height:64px;background:rgba(254, 243, 199, 0.9);box-shadow: 0 6px 16px rgba(245,158,11,0.25);">
                <i class="bi bi-exclamation-triangle-fill fs-3 text-warning"></i>
            </div>
            <h6 class="fw-extrabold text-dark mb-2">Ganti Restoran?</h6>
            <p class="text-muted small mb-3" id="restoDiffMsg">Keranjang Anda berisi menu dari restoran lain.</p>
            <div class="d-flex flex-column gap-2">
                <form action="{{ route('customer.cart.clear') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-warning btn-sm w-100 fw-bold rounded-pill py-2 text-dark">Kosongkan Keranjang Lama</button>
                </form>
                <button type="button" class="btn btn-light btn-sm w-100 fw-bold rounded-pill" data-bs-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

{{-- Success Toast Notification --}}
<div class="position-fixed bottom-0 end-0 p-3" style="z-index:1090;">
    <div id="quickCartToast" class="toast align-items-center border-0" role="alert" style="border-radius:1.5rem; background: rgba(15, 23, 42, 0.9); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.2); box-shadow: 0 20px 48px rgba(0,0,0,0.35);">
        <div class="d-flex p-3 gap-3 align-items-center">
            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:40px;height:40px;background:linear-gradient(135deg, #ff5722, #ea580c);color:#fff;">
                <i class="bi bi-check-lg fs-5"></i>
            </div>
            <div class="flex-grow-1 min-width-0">
                <strong class="text-white small d-block" id="toastItemName">Menu Ditambahkan</strong>
                <div class="text-white-50 small" id="toastItemQty">1 porsi masuk ke keranjang</div>
            </div>
            <a href="{{ route('customer.cart.index') }}" class="btn btn-primary btn-sm px-3 flex-shrink-0 rounded-pill fw-bold">Keranjang</a>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.querySelectorAll('[data-filter]').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('[data-filter]').forEach(b => {
            b.classList.remove('btn-primary');
            b.classList.add('btn-light');
            const bd = b.querySelector('.badge');
            if (bd) {
                bd.className = 'badge rounded-pill bg-light text-secondary border ms-1';
            }
        });
        this.classList.add('btn-primary');
        this.classList.remove('btn-light');
        const activeBd = this.querySelector('.badge');
        if (activeBd) {
            activeBd.className = 'badge rounded-pill bg-white text-dark ms-1';
        }

        const filterVal = this.dataset.filter;
        let visibleCount = 0;
        document.querySelectorAll('.product-item').forEach(item => {
            const matches = (filterVal === 'all' || item.dataset.category === filterVal);
            item.style.display = matches ? '' : 'none';
            if (matches) visibleCount++;
        });

        const emptyNotice = document.getElementById('categoryEmptyNotice');
        if (emptyNotice) {
            emptyNotice.classList.toggle('d-none', visibleCount > 0);
        }
    });
});

const currentRestoName = "{{ addslashes($restaurant->name) }}";

function quickAddToCart(productId, productName, price) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    fetch("{{ route('customer.cart.add') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            "X-CSRF-TOKEN": csrfToken
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: 1
        })
    })
    .then(async res => {
        const data = await res.json();
        if (res.status === 409 && data.different_restaurant) {
            document.getElementById('restoDiffMsg').textContent = data.message;
            bootstrap.Modal.getOrCreateInstance(document.getElementById('restoDetailDiffModal')).show();
            return;
        }
        if (!res.ok || !data.success) {
            alert(data.message || 'Gagal menambahkan menu ke keranjang.');
            return;
        }

        // Show Toast
        document.getElementById('toastItemName').textContent = productName;
        document.getElementById('toastItemQty').textContent = '1 porsi berhasil ditambahkan';
        bootstrap.Toast.getOrCreateInstance(document.getElementById('quickCartToast')).show();

        // Sync Global Cart Floating Bar
        if (window.updateGlobalCart) {
            window.updateGlobalCart(data.cart_count, data.cart_total, currentRestoName);
        }
    })
    .catch(err => {
        console.error('Cart add error:', err);
    });
}
</script>
@endpush
@endsection
