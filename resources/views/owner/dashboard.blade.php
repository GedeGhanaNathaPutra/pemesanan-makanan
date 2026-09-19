@extends('layouts.dashboard')
@section('title', 'Owner Dashboard – ' . $restaurant->name)
@section('page-title', 'Dashboard Restoran')
@section('content')

{{-- Restaurant Header Banner --}}
<div class="card border-0 rounded-4 p-4 mb-4 text-white position-relative overflow-hidden" style="background: linear-gradient(135deg, #ff5722 0%, #ea580c 55%, #c2410c 100%); box-shadow: 0 10px 25px -5px rgba(234, 88, 12, 0.28);">
    <div class="d-flex align-items-center gap-3.5 flex-wrap position-relative" style="z-index: 2;">
        @if($restaurant->image)
            <img src="{{ asset('storage/' . $restaurant->image) }}" class="rounded-4 flex-shrink-0 border border-white border-2 shadow-sm" style="width: 72px; height: 72px; object-fit: cover;" alt="{{ $restaurant->name }}">
        @else
            <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0 shadow-sm" style="width: 72px; height: 72px; background: rgba(255, 255, 255, 0.25); border: 1px solid rgba(255, 255, 255, 0.4);">
                <i class="bi bi-shop-window text-white fs-2"></i>
            </div>
        @endif

        <div class="flex-grow-1 min-width-0">
            <div class="d-flex align-items-center gap-2 flex-wrap mb-1.5">
                <h3 class="fw-extrabold mb-0 text-white" style="letter-spacing: -0.02em;">{{ $restaurant->name }}</h3>
                <span class="badge rounded-pill px-3 py-1.5 small fw-bold d-inline-flex align-items-center gap-1.5" style="background: #ffffff; color: {{ $restaurant->is_open ? '#15803d' : '#991b1b' }}; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                    <i class="bi bi-circle-fill" style="font-size: 0.45rem;"></i>
                    <span>{{ $restaurant->is_open ? 'Buka Menerima Pesanan' : 'Restoran Tutup' }}</span>
                </span>
            </div>
            <p class="text-white mb-0 small" style="opacity: 0.95; line-height: 1.5;">
                <i class="bi bi-geo-alt-fill me-1"></i>{{ $restaurant->address }} &bull;
                <i class="bi bi-telephone-fill ms-2 me-1"></i>{{ $restaurant->phone ?: 'Belum ada telepon' }}
            </p>
        </div>

        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('owner.restaurant.index') }}" class="btn btn-light fw-bold px-3.5 py-2 rounded-pill shadow-sm d-inline-flex align-items-center gap-1.5" style="color: var(--liquid-primary); font-size: 0.85rem;">
                <i class="bi bi-gear-fill"></i>
                <span>Kelola Restoran</span>
            </a>
            <a href="{{ route('owner.products.create') }}" class="btn fw-bold px-3.5 py-2 rounded-pill d-inline-flex align-items-center gap-1.5" style="background: rgba(255, 255, 255, 0.2); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.4); backdrop-filter: blur(12px); font-size: 0.85rem;">
                <i class="bi bi-plus-circle-fill"></i>
                <span>Tambah Menu</span>
            </a>
        </div>
    </div>
</div>

{{-- 4 Primary KPI Cards --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card card-hover h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="stat-label">Total Pendapatan</span>
                <div class="stat-icon" style="background: rgba(168, 85, 247, 0.1); color: #9333ea;">
                    <i class="bi bi-wallet2"></i>
                </div>
            </div>
            <div class="stat-value text-truncate">Rp {{ number_format($stats['revenue'], 0, ',', '.') }}</div>
            <div class="stat-desc">
                <i class="bi bi-check2-circle text-success me-1"></i>{{ $stats['completed_orders'] }} transaksi sukses
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card card-hover h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="stat-label">Perlu Dimasak</span>
                <div class="stat-icon" style="background: rgba(249, 115, 22, 0.1); color: #ea580c;">
                    <i class="bi bi-hourglass-split"></i>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <div class="stat-value">{{ $stats['pending_orders'] }}</div>
                @if($stats['pending_orders'] > 0)
                    <span class="spinner-grow spinner-grow-sm text-warning" role="status"></span>
                @endif
            </div>
            <div class="stat-desc">
                <i class="bi bi-bell text-warning me-1"></i>Pesanan baru butuh konfirmasi
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card card-hover h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="stat-label">Sedang Diproses</span>
                <div class="stat-icon" style="background: rgba(59, 130, 246, 0.1); color: #2563eb;">
                    <i class="bi bi-fire"></i>
                </div>
            </div>
            <div class="stat-value">{{ $stats['processing_orders'] + $stats['shipping_orders'] }} Pesanan</div>
            <div class="stat-desc">
                <i class="bi bi-bicycle text-info me-1"></i>{{ $stats['processing_orders'] }} masak &bull; {{ $stats['shipping_orders'] }} diantar
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card card-hover h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="stat-label">Menu Aktif</span>
                <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #059669;">
                    <i class="bi bi-box-seam"></i>
                </div>
            </div>
            <div class="stat-value">{{ $stats['total_products'] }} Menu</div>
            <div class="stat-desc">
                <i class="bi bi-check-circle text-success me-1"></i>Katalog hidangan siap saji
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Left Column (8 cols): Orders Queue & Live Table --}}
    <div class="col-lg-8">
        {{-- Live Pending Alert (If orders need attention) --}}
        @if($stats['pending_orders'] > 0)
        <div class="card p-3.5 mb-4 position-relative overflow-hidden" style="background: rgba(254, 243, 199, 0.7); border: 1px solid rgba(251, 191, 36, 0.8);">
            <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: #f59e0b; color: #ffffff; box-shadow: 0 4px 12px rgba(245, 158, 11, 0.35);">
                        <i class="bi bi-bell-fill fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-extrabold mb-0 text-dark">{{ $stats['pending_orders'] }} Pesanan Baru Menunggu Dapur</h6>
                        <small class="text-muted">Segera proses masakan agar pelanggan tidak menunggu terlalu lama.</small>
                    </div>
                </div>
                <a href="{{ route('owner.orders.index', ['status' => 'pending']) }}" class="btn btn-warning btn-sm px-3.5 rounded-pill fw-bold text-dark shadow-sm">
                    <span>Mulai Masak Sekarang</span>
                    <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
        @endif

        {{-- Recent Orders Table --}}
        <div class="card overflow-hidden">
            <div class="card-header bg-transparent d-flex flex-wrap justify-content-between align-items-center py-3 px-4 border-bottom">
                <div>
                    <h6 class="fw-extrabold mb-0 text-dark">Pesanan Masuk Terbaru</h6>
                    <small class="text-muted">Pantau dan proses pesanan yang masuk secara real-time</small>
                </div>
                <a href="{{ route('owner.orders.index') }}" class="btn btn-outline-primary btn-sm px-3 rounded-pill fw-bold" style="font-size: 0.8rem;">
                    <span>Lihat Semua</span>
                    <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="py-3 px-4">#ID</th>
                            <th class="py-3">Pelanggan</th>
                            <th class="py-3">Total Belanja</th>
                            <th class="py-3">Metode</th>
                            <th class="py-3">Status</th>
                            <th class="py-3 px-4 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                        <tr>
                            <td class="px-4">
                                <span class="fw-bold font-monospace text-dark">#{{ $order->id }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 30px; height: 30px; background: rgba(241, 245, 249, 0.9); color: #475569; font-weight: 700; font-size: 0.72rem;">
                                        {{ strtoupper(substr($order->user->name ?? 'P', 0, 1)) }}
                                    </div>
                                    <div class="min-width-0">
                                        <div class="fw-bold text-dark text-truncate" style="max-width: 140px;">{{ $order->user->name ?? 'Pelanggan' }}</div>
                                        <small class="text-muted text-truncate d-block" style="font-size: 0.7rem;">{{ $order->user->phone ?? '-' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <strong style="color: var(--liquid-primary);">
                                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                </strong>
                                <div class="text-muted" style="font-size: 0.7rem;">{{ $order->items->count() }} menu</div>
                            </td>
                            <td>
                                <span class="badge rounded-pill bg-light text-secondary border px-2 py-0.5" style="font-size: 0.7rem;">
                                    {{ ucfirst($order->payment_method) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $order->status }} px-2.5 py-1 rounded-pill" style="font-size: 0.72rem;">
                                    {{ $order->status_label }}
                                </span>
                            </td>
                            <td class="px-4 text-end">
                                <a href="{{ route('owner.orders.show', $order) }}" class="btn btn-light btn-sm px-3 rounded-pill fw-bold" style="font-size: 0.75rem;">
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 52px; height: 52px; background: rgba(241, 245, 249, 0.9); color: #94a3b8;">
                                    <i class="bi bi-receipt fs-3"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-1">Belum Ada Pesanan Masuk</h6>
                                <p class="small text-muted mb-0">Pesanan dari pembeli akan muncul otomatis di tabel ini.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Right Column (4 cols): Kitchen Workflow & Low Stock Warning --}}
    <div class="col-lg-4">
        {{-- Kitchen Workflow Status Breakdown --}}
        <div class="card p-4 mb-4">
            <h6 class="fw-extrabold mb-3 text-dark d-flex align-items-center gap-2">
                <i class="bi bi-activity text-primary"></i>
                <span>Alur Proses Dapur</span>
            </h6>
            @php
                $tot = max($stats['total_orders'], 1);
                $kitchenList = [
                    ['label' => 'Selesai Dikirim', 'count' => $stats['completed_orders'], 'bg' => 'bg-success'],
                    ['label' => 'Sedang Dikirim', 'count' => $stats['shipping_orders'], 'bg' => 'bg-info'],
                    ['label' => 'Sedang Dimasak', 'count' => $stats['processing_orders'], 'bg' => 'bg-primary'],
                    ['label' => 'Menunggu Masak', 'count' => $stats['pending_orders'], 'bg' => 'bg-warning'],
                ];
            @endphp
            <div class="d-flex flex-column gap-3">
                @foreach($kitchenList as $kl)
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-1 small">
                        <span class="text-secondary fw-semibold">{{ $kl['label'] }}</span>
                        <strong class="text-dark">{{ $kl['count'] }} <span class="text-muted fw-normal" style="font-size: 0.72rem;">({{ round(($kl['count'] / $tot) * 100) }}%)</span></strong>
                    </div>
                    <div class="progress" style="height: 6px; background: rgba(241, 245, 249, 0.9);">
                        <div class="progress-bar {{ $kl['bg'] }} rounded-pill" role="progressbar" style="width: {{ round(($kl['count'] / $tot) * 100) }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Low Stock Products Alert Card --}}
        <div class="card p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-extrabold mb-0 text-dark d-flex align-items-center gap-2">
                    <i class="bi bi-exclamation-triangle text-danger"></i>
                    <span>Stok Porsi Menipis</span>
                </h6>
                <a href="{{ route('owner.products.index') }}" class="small fw-bold text-primary text-decoration-none">Kelola</a>
            </div>
            <div class="d-flex flex-column gap-2.5">
                @forelse($lowStockProducts as $lp)
                <div class="d-flex align-items-center justify-content-between gap-2.5 pb-2.5 border-bottom">
                    <div class="d-flex align-items-center gap-2.5 min-width-0">
                        <div class="rounded-3 overflow-hidden d-flex align-items-center justify-content-center border flex-shrink-0" style="width: 38px; height: 38px; background: rgba(241, 245, 249, 0.9);">
                            @if($lp->image)
                                <img src="{{ asset('storage/' . $lp->image) }}" class="w-100 h-100 object-fit-cover" alt="{{ $lp->name }}">
                            @else
                                <i class="bi bi-egg-fried text-muted fs-5"></i>
                            @endif
                        </div>
                        <div class="min-width-0">
                            <div class="fw-bold text-dark text-truncate small" style="max-width: 140px;">{{ $lp->name }}</div>
                            <small class="text-muted text-truncate d-block" style="font-size: 0.7rem;">Rp {{ number_format($lp->price, 0, ',', '.') }}</small>
                        </div>
                    </div>
                    <span class="badge rounded-pill {{ $lp->stock > 0 ? 'bg-warning-subtle text-warning border border-warning-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle' }} px-2 py-0.5 fw-bold" style="font-size: 0.68rem;">
                        {{ $lp->stock > 0 ? $lp->stock . ' porsi' : 'Habis' }}
                    </span>
                </div>
                @empty
                <div class="text-center py-3 text-muted">
                    <i class="bi bi-check-circle text-success fs-4 d-block mb-1"></i>
                    <small>Seluruh stok menu dalam kondisi aman.</small>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection
