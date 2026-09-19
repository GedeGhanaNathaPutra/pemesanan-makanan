
@extends('layouts.app')
@section('title', 'Beranda – Pesan Makanan Lezat & Cepat')
@section('content')
<div class="container-fluid px-3 px-md-4 px-lg-5 py-3">
    {{-- LIQUID GLASS HERO BLOCK (Full Desktop Expansive Banner) --}}
    <section class="hero-section text-white position-relative overflow-hidden p-4 p-md-5 my-3">
        {{-- Ambient Glass Orbs & Water Droplet Depth Effects --}}
        <div class="position-absolute rounded-circle" style="width: 320px; height: 320px; top: -80px; right: -60px; background: radial-gradient(circle, rgba(255,255,255,0.22) 0%, transparent 70%); pointer-events: none;"></div>
        <div class="position-absolute rounded-circle" style="width: 240px; height: 240px; bottom: -70px; left: 25%; background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%); pointer-events: none;"></div>
        <div class="position-absolute rounded-circle" style="width: 160px; height: 160px; top: 15%; left: -50px; background: radial-gradient(circle, rgba(255,255,255,0.12) 0%, transparent 70%); pointer-events: none;"></div>

        <div class="position-relative" style="z-index: 2;">
            <div class="row align-items-center g-4 g-xl-5">
                {{-- Left Column: High-Impact Typography & Interactive Search --}}
                <div class="col-12 col-lg-7 text-start py-2 py-lg-3">
                    {{-- Top Premium Glass Pill --}}
                    <div class="d-inline-flex align-items-center gap-2 px-3.5 py-1.5 mb-3 rounded-pill fw-bold small" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(16px); color: #0f172a; box-shadow: 0 8px 20px rgba(0,0,0,0.12), inset 0 1px 1px #ffffff;">
                        <span class="badge rounded-pill bg-danger text-white px-2 py-0.5 fw-extrabold" style="font-size: 0.68rem; letter-spacing: 0.5px;">PROMO</span>
                        <i class="bi bi-fire text-danger fs-6"></i>
                        <span>#1 Platform Pemesanan Kuliner Nusantara</span>
                    </div>

                    {{-- Main Headline --}}
                    <h1 class="fw-extrabold display-5 display-xl-4 mb-3 text-white" style="letter-spacing: -0.03em; line-height: 1.18; text-shadow: 0 4px 18px rgba(15, 23, 42, 0.35);">
                        Lapar? Pesan Menu Lezat,<br class="d-none d-md-block"> Diantar Hangat &amp; Cepat.
                    </h1>

                    {{-- Subtitle with High Contrast Legibility --}}
                    <p class="lead mb-4 fs-6 fs-md-5 fw-normal text-white" style="text-shadow: 0 2px 8px rgba(15, 23, 42, 0.25); opacity: 0.96; line-height: 1.6; max-width: 580px;">
                        Pilih dari ratusan hidangan otentik mitra restoran terverifikasi. Nikmati proses pesanan tanpa repot, pelacakan real-time, dan garansi makanan tiba higienis.
                    </p>

                    {{-- Liquid Glass Search Bar --}}
                    <div class="card p-2 p-md-2.5 border-0 rounded-4 mb-3" style="max-width: 600px; background: rgba(255, 255, 255, 0.96); backdrop-filter: blur(24px) saturate(190%); border: 1px solid rgba(255, 255, 255, 1); box-shadow: 0 20px 44px -10px rgba(0,0,0,0.25), inset 0 1px 2px #ffffff;">
                        <form action="{{ route('restaurants') }}" method="GET" class="d-flex flex-column flex-sm-row gap-2">
                            <div class="input-group border-0 flex-grow-1">
                                <span class="input-group-text bg-transparent border-0 text-primary ps-3">
                                    <i class="bi bi-search fs-5"></i>
                                </span>
                                <input type="text" name="search" class="form-control border-0 shadow-none fs-6 bg-transparent fw-semibold" style="color: #0f172a;" placeholder="Mau makan apa hari ini? (Padang, Kopi, Ayam...)" value="{{ request('search') }}">
                            </div>
                            <button type="submit" class="btn btn-primary px-4 py-2.5 rounded-pill fw-bold flex-shrink-0 d-flex align-items-center justify-content-center gap-1.5 shadow-sm">
                                <span>Cari Menu</span>
                                <i class="bi bi-arrow-right"></i>
                            </button>
                        </form>
                    </div>

                    {{-- Quick Search Keyword Tags --}}
                    <div class="d-flex align-items-center gap-2 flex-wrap mb-4 small">
                        <span class="fw-semibold text-white d-inline-flex align-items-center gap-1" style="text-shadow: 0 1px 3px rgba(0,0,0,0.25);">
                            <i class="bi bi-lightning-charge-fill text-warning"></i>Populer:
                        </span>
                        <a href="{{ route('restaurants') }}?search=Padang" class="badge rounded-pill text-decoration-none px-3 py-1.5 fw-bold" style="background: rgba(255,255,255,0.9); color: #0f172a; box-shadow: 0 3px 10px rgba(0,0,0,0.08); transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">Nasi Padang</a>
                        <a href="{{ route('restaurants') }}?search=Ayam" class="badge rounded-pill text-decoration-none px-3 py-1.5 fw-bold" style="background: rgba(255,255,255,0.9); color: #0f172a; box-shadow: 0 3px 10px rgba(0,0,0,0.08); transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">Ayam Geprek</a>
                        <a href="{{ route('restaurants') }}?search=Kopi" class="badge rounded-pill text-decoration-none px-3 py-1.5 fw-bold" style="background: rgba(255,255,255,0.9); color: #0f172a; box-shadow: 0 3px 10px rgba(0,0,0,0.08); transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">Kopi Susu</a>
                        <a href="{{ route('restaurants') }}?search=Bebek" class="badge rounded-pill text-decoration-none px-3 py-1.5 fw-bold" style="background: rgba(255,255,255,0.9); color: #0f172a; box-shadow: 0 3px 10px rgba(0,0,0,0.08); transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">Bebek Sinjay</a>
                    </div>

                    {{-- Trust Value Highlights --}}
                    <div class="d-flex gap-3 gap-md-4 flex-wrap small pt-3 border-top border-white border-opacity-25">
                        <div class="d-flex align-items-center gap-2 text-white">
                            <span class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 34px; height: 34px; background: rgba(255,255,255,0.22); backdrop-filter: blur(8px); box-shadow: inset 0 1px 1px rgba(255,255,255,0.6);">
                                <i class="bi bi-patch-check-fill text-white fs-6"></i>
                            </span>
                            <div>
                                <strong class="d-block" style="font-size: 0.85rem; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">Mitra Terverifikasi</strong>
                                <span style="font-size: 0.72rem; opacity: 0.9;">100% Higienis &amp; Segar</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2 text-white">
                            <span class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 34px; height: 34px; background: rgba(255,255,255,0.22); backdrop-filter: blur(8px); box-shadow: inset 0 1px 1px rgba(255,255,255,0.6);">
                                <i class="bi bi-truck text-white fs-6"></i>
                            </span>
                            <div>
                                <strong class="d-block" style="font-size: 0.85rem; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">Pengantaran Kilat</strong>
                                <span style="font-size: 0.72rem; opacity: 0.9;">Rata-rata 25 Menit</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2 text-white">
                            <span class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 34px; height: 34px; background: rgba(255,255,255,0.22); backdrop-filter: blur(8px); box-shadow: inset 0 1px 1px rgba(255,255,255,0.6);">
                                <i class="bi bi-wallet2 text-white fs-6"></i>
                            </span>
                            <div>
                                <strong class="d-block" style="font-size: 0.85rem; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">Bayar Mudah</strong>
                                <span style="font-size: 0.72rem; opacity: 0.9;">Transfer, E-Wallet, COD</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Column: Interactive Food Visual Mockup (Desktop) --}}
                <div class="col-12 col-lg-5 d-none d-lg-flex align-items-center justify-content-center position-relative py-3">
                    <div class="position-relative w-100" style="max-width: 390px;">
                        {{-- Main Food Showcase Glass Card --}}
                        <div class="card border-0 rounded-4 overflow-hidden p-3.5 shadow-xl text-dark position-relative" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(24px) saturate(190%); border: 1px solid rgba(255, 255, 255, 1); box-shadow: 0 24px 48px -12px rgba(15, 23, 42, 0.25);">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="badge rounded-pill px-2.5 py-1 small fw-bold d-inline-flex align-items-center gap-1" style="background: rgba(254, 243, 199, 0.95); color: #92400e; border: 1px solid rgba(252, 211, 77, 0.8);">
                                    <i class="bi bi-star-fill text-warning"></i> Paling Banyak Dipesan
                                </span>
                                <span class="badge rounded-pill bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1 small fw-bold">
                                    <i class="bi bi-dot"></i> Buka Sekarang
                                </span>
                            </div>

                            <div class="rounded-4 overflow-hidden mb-3 position-relative" style="height: 190px; background: linear-gradient(135deg, #ffedd5 0%, #fed7aa 60%, #fdba74 100%);">
                                <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-center p-3" style="background: radial-gradient(circle at center, rgba(255,255,255,0.45) 0%, transparent 70%);">
                                    <i class="bi bi-egg-fried" style="font-size: 4.5rem; color: #ea580c; filter: drop-shadow(0 8px 18px rgba(234, 88, 12, 0.35));"></i>
                                    <span class="fw-extrabold text-dark mt-1" style="font-size: 1.1rem; letter-spacing: -0.01em;">Hidangan Nusantara Favorit</span>
                                    <small class="text-secondary" style="font-size: 0.8rem;">Disiapkan hangat &amp; resep otentik</small>
                                </div>
                                <div class="position-absolute bottom-0 end-0 m-2.5 badge rounded-pill px-3 py-1.5 fw-bold" style="background: rgba(255, 255, 255, 0.96); color: #0f172a; box-shadow: 0 4px 12px rgba(0,0,0,0.12);">
                                    <i class="bi bi-clock-history text-primary me-1"></i>15 &ndash; 25 mnt
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div>
                                    <h6 class="fw-bold mb-0 text-dark">Ratusan Menu Menantimu</h6>
                                    <small class="text-muted"><i class="bi bi-geo-alt text-danger me-1"></i>Mitra terdekat di lokasimu</small>
                                </div>
                                <div class="text-end">
                                    <span class="fw-extrabold fs-5 text-dark">4.9</span>
                                    <i class="bi bi-star-fill text-warning fs-6"></i>
                                </div>
                            </div>

                            <div class="d-grid mt-3">
                                <a href="{{ route('restaurants') }}" class="btn btn-primary btn-sm py-2.5 rounded-pill fw-bold shadow-sm d-flex align-items-center justify-content-center gap-1.5">
                                    <span>Pilih Restoran &amp; Menu</span>
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>

                        {{-- Floating Glass Accent 1 (Top-Left Accent) --}}
                        <div class="card border-0 rounded-4 p-2.5 position-absolute d-flex flex-row align-items-center gap-2.5 text-dark" style="top: -22px; left: -25px; background: rgba(255, 255, 255, 0.96); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 1); box-shadow: 0 16px 36px rgba(15, 23, 42, 0.16); z-index: 3;">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: rgba(220, 252, 231, 0.95); color: #166534;">
                                <i class="bi bi-shield-check fs-5"></i>
                            </div>
                            <div class="pe-2">
                                <div class="fw-bold small" style="font-size: 0.8rem;">100% Higienis</div>
                                <small class="text-muted d-block" style="font-size: 0.7rem;">Kualitas Terjamin</small>
                            </div>
                        </div>

                        {{-- Floating Glass Accent 2 (Bottom-Right Accent) --}}
                        <div class="card border-0 rounded-4 p-2.5 position-absolute d-flex flex-row align-items-center gap-2.5 text-dark" style="bottom: -16px; right: -20px; background: rgba(255, 255, 255, 0.96); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 1); box-shadow: 0 16px 36px rgba(15, 23, 42, 0.16); z-index: 3;">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: rgba(254, 243, 199, 0.95); color: #b45309;">
                                <i class="bi bi-bicycle fs-5"></i>
                            </div>
                            <div class="pe-2">
                                <div class="fw-bold small" style="font-size: 0.8rem;">Antar Kilat</div>
                                <small class="text-muted d-block" style="font-size: 0.7rem;">Hangat Sampai Tujuan</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CATEGORIES SECTION (Liquid Glass Tiles) --}}
    @if($categories->isNotEmpty())
    <section class="py-4 my-2">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <span class="text-primary fw-bold text-uppercase small" style="letter-spacing: 1px;">Katalog Menu</span>
                <h3 class="fw-extrabold mb-0 text-dark">Kategori Pilihan</h3>
            </div>
            <a href="{{ route('restaurants') }}" class="btn btn-light btn-sm rounded-pill px-3.5 fw-bold">
                Jelajahi Semua <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-3 row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-6">
            @foreach($categories as $cat)
            @php
                $isDrink = Str::contains(strtolower($cat->name), ['minum', 'kopi', 'tea']);
                $isSnack = Str::contains(strtolower($cat->name), ['snack', 'camilan', 'kue']);
                $isNoodle = Str::contains(strtolower($cat->name), ['mie', 'bakso', 'pasta']);

                $iconColor = $isDrink ? '#0284c7' : ($isSnack ? '#d97706' : ($isNoodle ? '#ea580c' : '#ff5722'));
                $iconBg = $isDrink ? 'rgba(224, 242, 254, 0.85)' : ($isSnack ? 'rgba(254, 243, 199, 0.85)' : ($isNoodle ? 'rgba(255, 237, 213, 0.85)' : 'rgba(255, 242, 237, 0.85)'));
            @endphp
            <div class="col">
                <a href="{{ route('restaurants') }}?search={{ urlencode($cat->name) }}" class="text-decoration-none">
                    <div class="category-tile text-center h-100 d-flex flex-column align-items-center justify-content-center">
                        <div class="category-icon-glass mb-2.5" style="background: {{ $iconBg }}; color: {{ $iconColor }};">
                            @if($isDrink)
                                <i class="bi bi-cup-straw fs-4"></i>
                            @elseif($isSnack)
                                <i class="bi bi-cake2 fs-4"></i>
                            @elseif($isNoodle)
                                <i class="bi bi-water fs-4"></i>
                            @else
                                <i class="bi bi-egg-fried fs-4"></i>
                            @endif
                        </div>
                        <h6 class="fw-bold text-dark small mb-0">{{ $cat->name }}</h6>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- FEATURED RESTAURANTS (Liquid Glass Cards Grid) --}}
    <section class="py-4 my-2">
        <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-2">
            <div>
                <span class="text-primary fw-bold text-uppercase small" style="letter-spacing: 1px;">Mitra Terverifikasi</span>
                <h3 class="fw-extrabold mb-0 text-dark">Restoran Terpopuler</h3>
            </div>
            <a href="{{ route('restaurants') }}" class="btn btn-light btn-sm px-3.5 rounded-pill fw-bold">
                Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        @if($restaurants->isEmpty())
        <div class="card p-5 text-center border-0 rounded-4 my-4">
            <div class="category-icon-glass mx-auto mb-3" style="width:72px;height:72px;background:rgba(241,245,249,0.85);color:#64748b;">
                <i class="bi bi-shop fs-2"></i>
            </div>
            <h5 class="fw-bold text-dark">Belum ada restoran yang buka</h5>
            <p class="small text-muted mb-0">Silakan kembali lagi nanti untuk melihat restoran yang sedang melayani pesanan.</p>
        </div>
        @else
        <div class="row g-4 row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4">
            @foreach($restaurants as $resto)
            <div class="col">
                <div class="card h-100 card-hover border-0 overflow-hidden rounded-4 d-flex flex-column">
                    {{-- Cover Image with Ambient Scrim & Glass Badges --}}
                    <div class="position-relative overflow-hidden w-100" style="height: 190px;">
                        @if($resto->image)
                            <img src="{{ asset('storage/'.$resto->image) }}" class="w-100 h-100 object-fit-cover" alt="{{ $resto->name }}">
                        @else
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, rgba(255, 87, 34, 0.85), rgba(234, 88, 12, 0.9));">
                                <i class="bi bi-shop-window text-white" style="font-size: 3.5rem; opacity: 0.95;"></i>
                            </div>
                        @endif

                        {{-- Ambient bottom scrim for badge contrast --}}
                        <div class="position-absolute inset-0 w-100 h-100" style="top:0; left:0; right:0; bottom:0; background: linear-gradient(to top, rgba(15, 23, 42, 0.5) 0%, transparent 60%); pointer-events: none;"></div>

                        {{-- Glass Status Badge --}}
                        <span class="position-absolute top-0 end-0 m-3 badge {{ $resto->is_open ? 'badge-selesai' : 'badge-pending' }} px-3 py-1.5 rounded-pill small">
                            <i class="bi bi-circle-fill me-1" style="font-size: 0.45rem;"></i>{{ $resto->is_open ? 'Buka' : 'Tutup' }}
                        </span>

                        {{-- Liquid Glass Rating Pill --}}
                        <span class="position-absolute bottom-0 start-0 m-3 badge rounded-pill px-2.5 py-1.5 d-inline-flex align-items-center gap-1 font-monospace" style="background: rgba(255, 255, 255, 0.92); backdrop-filter: blur(16px); color: #0f172a; border: 1px solid rgba(255, 255, 255, 0.95); box-shadow: 0 6px 16px rgba(0,0,0,0.1);">
                            <i class="bi bi-star-fill text-warning"></i>
                            <strong>{{ number_format($resto->average_rating, 1) }}</strong>
                        </span>
                    </div>

                    {{-- Card Body --}}
                    <div class="card-body p-3.5 d-flex flex-column justify-content-between flex-grow-1">
                        <div>
                            <a href="{{ route('restaurants.show', $resto) }}" class="text-decoration-none">
                                <h6 class="fw-bold text-dark mb-1 text-truncate hover-primary" title="{{ $resto->name }}">
                                    {{ $resto->name }}
                                </h6>
                            </a>
                            <p class="small text-muted mb-3 text-truncate" style="font-size: 0.78rem;">
                                <i class="bi bi-geo-alt me-1 text-danger"></i>{{ $resto->address }}
                            </p>
                        </div>
                        <div class="mt-auto pt-2.5 border-top d-flex justify-content-between align-items-center small">
                            <span class="text-muted fw-medium" style="font-size: 0.75rem;">
                                <i class="bi bi-box-seam me-1 text-primary"></i>{{ $resto->products_count }} Menu
                            </span>
                            <a href="{{ route('restaurants.show', $resto) }}" class="btn btn-outline-primary btn-sm px-3 py-1 rounded-pill fw-bold d-inline-flex align-items-center gap-1" style="font-size: 0.75rem;">
                                <span>Lihat</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </section>

    {{-- HOW IT WORKS (Liquid Glass Steps) --}}
    <section class="py-4 my-3">
        <div class="text-center max-w-2xl mx-auto mb-4">
            <span class="text-primary fw-bold text-uppercase small" style="letter-spacing: 1px;">Mudah &amp; Praktis</span>
            <h3 class="fw-extrabold text-dark mt-1">Langkah Mudah Pemesanan</h3>
            <p class="text-muted small">Nikmati kemudahan memesan makanan lezat hanya dalam 4 langkah simpel</p>
        </div>

        <div class="row g-3.5 text-center">
            @foreach([
                ['bi-search','1. Pilih Restoran','Jelajahi berbagai restoran mitra terbaik di sekitar lokasimu.','rgba(254, 243, 199, 0.8)','#b45309'],
                ['bi-basket2','2. Masukkan Menu','Pilih makanan lezat favorit dan tentukan jumlah porsinya.','rgba(255, 237, 213, 0.8)','#c2410c'],
                ['bi-credit-card','3. Bayar Mudah','Selesaikan transaksi via Transfer, E-Wallet, atau COD.','rgba(204, 251, 241, 0.8)','#0f766e'],
                ['bi-truck','4. Diantar Cepat','Pesanan dimasak higienis dan diantar ke depan pintumu.','rgba(220, 252, 231, 0.8)','#15803d']
            ] as [$ic,$t,$d,$bg,$co])
            <div class="col-6 col-md-3">
                <div class="card h-100 p-4 border-0 rounded-4 card-hover">
                    <div class="category-icon-glass mx-auto mb-3" style="width: 64px; height: 64px; background: {{ $bg }}; color: {{ $co }};">
                        <i class="bi {{ $ic }} fs-3"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1">{{ $t }}</h6>
                    <p class="small text-muted mb-0">{{ $d }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </section>
</div>
@endsection
