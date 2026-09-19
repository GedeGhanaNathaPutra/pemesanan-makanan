@extends('layouts.app')
@section('title', 'Dashboard Pelanggan – FoodOrder')
@section('content')
<div class="container-fluid px-3 px-md-4 px-lg-5 py-4 py-md-5">
    {{-- Greeting Banner (Liquid Glass Hero Accent) --}}
    <div class="card border-0 rounded-4 p-4 p-md-5 mb-4 text-white position-relative overflow-hidden" style="background: linear-gradient(135deg, rgba(249, 87, 34, 0.96) 0%, rgba(234, 88, 12, 0.96) 55%, rgba(217, 119, 6, 0.94) 100%); box-shadow: 0 24px 48px -12px rgba(234, 88, 12, 0.38); border: 1px solid rgba(255, 255, 255, 0.35);">
        {{-- Decorative Ambient Refraction Orbs --}}
        <div class="position-absolute rounded-circle" style="width: 260px; height: 260px; top: -60px; right: -40px; background: radial-gradient(circle, rgba(255,255,255,0.22) 0%, transparent 70%); pointer-events: none;"></div>
        <div class="position-absolute rounded-circle" style="width: 200px; height: 200px; bottom: -50px; left: 35%; background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%); pointer-events: none;"></div>

        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-4 position-relative" style="z-index: 2;">
            <div style="max-width: 720px;">
                <div class="d-flex align-items-center gap-2 mb-2.5 flex-wrap">
                    <span class="badge rounded-pill px-3 py-1.5 small fw-bold d-inline-flex align-items-center gap-1.5" style="background: rgba(255, 255, 255, 0.95); color: #0f172a; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                        <i class="bi bi-sparkles text-warning fs-6"></i>
                        <span>Pelanggan Setia Food<span style="color: var(--liquid-primary)">Order</span></span>
                    </span>
                    <span class="badge rounded-pill px-2.5 py-1.5 small fw-semibold" style="background: rgba(255, 255, 255, 0.2); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.4);">
                        <i class="bi bi-shield-check me-1"></i>Akun Terverifikasi
                    </span>
                </div>

                <h2 class="fw-extrabold mb-2 text-white" style="letter-spacing: -0.02em; text-shadow: 0 2px 10px rgba(15, 23, 42, 0.3);">
                    Halo, {{ auth()->user()->name }}! Mau Santap Apa Hari Ini?
                </h2>
                <p class="text-white mb-0" style="opacity: 0.96; font-size: 0.95rem; line-height: 1.6; text-shadow: 0 1px 4px rgba(15, 23, 42, 0.25);">
                    Ratusan hidangan lezat dan segar dari restoran mitra terpercaya siap disajikan hangat. Nikmati pengantaran kilat, harga transparan, dan jaminan makanan higienis langsung ke depan pintu Anda.
                </p>
            </div>

            <div class="d-flex flex-column flex-sm-row gap-2.5 flex-shrink-0 align-items-stretch align-items-sm-center">
                <a href="{{ route('restaurants') }}" class="btn btn-light fw-bold px-4 py-2.5 rounded-pill shadow-sm d-inline-flex align-items-center justify-content-center gap-2 text-primary" style="font-size: 0.9rem;">
                    <i class="bi bi-search"></i>
                    <span>Pesan Makanan Sekarang</span>
                </a>
                <a href="{{ route('customer.orders.index') }}" class="btn fw-bold px-3.5 py-2.5 rounded-pill d-inline-flex align-items-center justify-content-center gap-2" style="background: rgba(255, 255, 255, 0.2); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.4); backdrop-filter: blur(12px); font-size: 0.9rem;">
                    <i class="bi bi-receipt"></i>
                    <span>Riwayat Pesanan</span>
                </a>
            </div>
        </div>
    </div>

    {{-- 4 Stat Metric Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card stat-card card-hover h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="stat-label">Total Pesanan</span>
                    <div class="stat-icon" style="background: rgba(59, 130, 246, 0.1); color: #2563eb;">
                        <i class="bi bi-receipt"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $stats['total_orders'] ?? 0 }}</div>
                <div class="stat-desc">
                    <i class="bi bi-arrow-repeat text-primary me-1"></i>Semua transaksi tercatat
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card stat-card card-hover h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="stat-label">Sedang Aktif</span>
                    <div class="stat-icon" style="background: rgba(249, 115, 22, 0.1); color: #ea580c;">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <div class="stat-value">{{ $stats['active_orders'] ?? 0 }}</div>
                    @if(($stats['active_orders'] ?? 0) > 0)
                        <span class="spinner-grow spinner-grow-sm text-warning" role="status"></span>
                    @endif
                </div>
                <div class="stat-desc">
                    <i class="bi bi-bell text-warning me-1"></i>Dalam proses / diantar
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card stat-card card-hover h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="stat-label">Pesanan Selesai</span>
                    <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #059669;">
                        <i class="bi bi-check2-circle"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $stats['completed_orders'] ?? 0 }}</div>
                <div class="stat-desc">
                    <i class="bi bi-check2 text-success me-1"></i>Hidangan sukses dinikmati
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card stat-card card-hover h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="stat-label">Total Pengeluaran</span>
                    <div class="stat-icon" style="background: rgba(168, 85, 247, 0.1); color: #9333ea;">
                        <i class="bi bi-wallet2"></i>
                    </div>
                </div>
                <div class="stat-value text-truncate">Rp {{ number_format($stats['total_spent'] ?? 0, 0, ',', '.') }}</div>
                <div class="stat-desc">
                    <i class="bi bi-cash-stack text-info me-1"></i>Akumulasi pesanan selesai
                </div>
            </div>
        </div>
    </div>

    {{-- Active Live Order Tracker Banner (If customer has active order) --}}
    @if(isset($activeOrder) && $activeOrder)
    <div class="card rounded-4 p-4 mb-4 position-relative overflow-hidden" style="background: rgba(255, 247, 237, 0.95); border: 1.5px solid rgba(254, 215, 170, 0.9) !important; box-shadow: 0 12px 28px rgba(234, 88, 12, 0.1);">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: linear-gradient(135deg, #ea580c, #f97316); color: #ffffff; box-shadow: 0 6px 16px rgba(234,88,12,0.35);">
                    <i class="bi bi-bicycle fs-4"></i>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="badge bg-warning text-dark px-2.5 py-1 rounded-pill small fw-bold d-inline-flex align-items-center gap-1">
                            <span class="spinner-grow spinner-grow-sm" style="width: 0.6rem; height: 0.6rem;" role="status"></span>
                            Pesanan Sedang Berjalan
                        </span>
                        <span class="small text-muted font-monospace">#{{ $activeOrder->id }}</span>
                    </div>
                    <h5 class="fw-extrabold text-dark mb-0">{{ $activeOrder->restaurant->name ?? 'Restoran Mitra' }}</h5>
                    <div class="d-flex flex-wrap gap-1.5 mt-1.5">
                        @foreach($activeOrder->items->take(2) as $it)
                            <span class="badge rounded-pill bg-white text-secondary border px-2 py-1 small">
                                {{ $it->product->name ?? 'Menu' }} &times;{{ $it->quantity }}
                            </span>
                        @endforeach
                        @if($activeOrder->items->count() > 2)
                            <span class="badge rounded-pill bg-white text-muted border px-2 py-1 small">
                                +{{ $activeOrder->items->count() - 2 }} lainnya
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2.5">
                <div class="text-end d-none d-sm-block">
                    <span class="small text-muted d-block">Status:</span>
                    <strong class="text-primary">{{ $activeOrder->status_label }}</strong>
                </div>
                <a href="{{ route('customer.orders.show', $activeOrder) }}" class="btn btn-primary px-4 py-2.5 rounded-pill fw-bold shadow-xs">
                    <i class="bi bi-geo-alt me-1"></i>Pantau Pesanan
                </a>
            </div>
        </div>
    </div>
    @endif

    <div class="row g-4">
        {{-- Profile Sidebar Card --}}
        <div class="col-lg-4">
            <div class="card rounded-4 p-4 text-center h-100">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mx-auto mb-3" style="width: 76px; height: 76px; background: rgba(255, 242, 237, 0.85); color: var(--liquid-primary); box-shadow: 0 8px 18px rgba(234,88,12,0.15);">
                    <i class="bi bi-person-circle fs-1"></i>
                </div>
                <h5 class="fw-extrabold text-dark mb-1">{{ auth()->user()->name }}</h5>
                <p class="text-muted small mb-3">{{ auth()->user()->email }}</p>

                <div class="text-start rounded-4 p-3 mb-3 small" style="background: rgba(248, 250, 252, 0.85); border: 1px solid rgba(226, 232, 240, 0.9);">
                    <div class="mb-2">
                        <span class="text-muted d-block" style="font-size: 0.72rem;">Nomor Telepon:</span>
                        <strong class="text-dark">{{ auth()->user()->phone ?? 'Belum ditambahkan' }}</strong>
                    </div>
                    <div>
                        <span class="text-muted d-block" style="font-size: 0.72rem;">Alamat Pengiriman Utama:</span>
                        <strong class="text-dark">{{ auth()->user()->address ?? 'Belum diatur' }}</strong>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <a href="{{ route('customer.orders.index') }}" class="btn btn-primary btn-sm py-2 rounded-pill fw-bold">
                        <i class="bi bi-bag-check me-1"></i>Lihat Riwayat Pesanan
                    </a>
                    <a href="{{ route('customer.cart.index') }}" class="btn btn-light btn-sm py-2 rounded-pill fw-bold">
                        <i class="bi bi-cart3 me-1 text-primary"></i>Buka Keranjang
                    </a>
                </div>
            </div>
        </div>

        {{-- Recent Orders Section --}}
        <div class="col-lg-8">
            <div class="card rounded-4 overflow-hidden h-100">
                <div class="py-3 px-4 border-bottom d-flex align-items-center justify-content-between" style="background: rgba(255, 255, 255, 0.5);">
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">Pesanan Terbaru Anda</h6>
                        <small class="text-muted">Pantau status hidangan yang baru saja Anda pesan</small>
                    </div>
                    <a href="{{ route('customer.orders.index') }}" class="btn btn-outline-primary btn-sm px-3.5 rounded-pill fw-bold">
                        Semua Pesanan <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>

                <div class="card-body p-0">
                    @if($orders->isEmpty())
                    <div class="p-5 text-center text-muted">
                        <div class="category-icon-glass mx-auto mb-3" style="width: 72px; height: 72px; background: rgba(241, 245, 249, 0.85); color: #64748b;">
                            <i class="bi bi-bag-x fs-2"></i>
                        </div>
                        <h6 class="fw-bold text-dark">Belum ada pesanan aktif</h6>
                        <p class="small text-muted mb-3">Pesan menu favorit Anda dan pantau proses memasak secara langsung.</p>
                        <a href="{{ route('restaurants') }}" class="btn btn-primary btn-sm px-4 rounded-pill fw-bold">
                            Mulai Pesan
                        </a>
                    </div>
                    @else
                    <div class="list-group list-group-flush">
                        @foreach($orders as $order)
                        <div class="list-group-item p-3.5 px-4 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 border-bottom" style="background: transparent;">
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <h6 class="fw-bold mb-0 text-dark">{{ $order->restaurant->name ?? 'Restoran' }}</h6>
                                    <span class="badge badge-{{ $order->status }} rounded-pill" style="font-size: 0.7rem;">
                                        {{ $order->status_label }}
                                    </span>
                                </div>
                                <small class="text-muted">
                                    {{ $order->created_at?->format('d M Y, H:i') }} &bull; #{{ $order->id }} &bull;
                                    <strong style="color: var(--liquid-primary)">Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong>
                                </small>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <a href="{{ route('customer.orders.show', $order) }}" class="btn btn-light btn-sm px-3.5 rounded-pill fw-bold">
                                    Detail
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection