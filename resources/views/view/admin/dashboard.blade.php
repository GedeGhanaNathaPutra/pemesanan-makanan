@extends('layouts.dashboard')
@section('title', 'Admin Dashboard – Monitoring Platform')
@section('page-title', 'Dashboard Administrator')
@section('content')

{{-- Welcome Hero Banner --}}
<div class="card border-0 rounded-4 p-4 mb-4 text-white position-relative overflow-hidden" style="background: linear-gradient(135deg, #ff5722 0%, #ea580c 55%, #c2410c 100%); box-shadow: 0 10px 25px -5px rgba(234, 88, 12, 0.28);">
    <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 position-relative" style="z-index: 2;">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge rounded-pill px-3 py-1.5 small fw-bold d-inline-flex align-items-center gap-1.5" style="background: #ffffff; color: #0f172a; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                    <i class="bi bi-shield-check text-success fs-6"></i>
                    <span>Sistem Aktif & Terpantau</span>
                </span>
                <span class="badge rounded-pill px-2.5 py-1.5 small" style="background: rgba(255, 255, 255, 0.2); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.4);">
                    Administrator Platform
                </span>
            </div>
            <h3 class="fw-extrabold mb-1 text-white" style="letter-spacing: -0.02em;">
                Halo, {{ auth()->user()->name }}! Ringkasan Performa Platform
            </h3>
            <p class="text-white mb-0 small" style="opacity: 0.95; line-height: 1.5; max-width: 680px;">
                Pantau pesanan masuk secara langsung, kelola mitra restoran terverifikasi, dan pantau arus perputaran omzet harian platform kuliner Anda.
            </p>
        </div>
        <div class="d-flex gap-2 flex-shrink-0 flex-wrap">
            <a href="{{ route('admin.restaurants.create') }}" class="btn btn-light fw-bold px-3.5 py-2 rounded-pill shadow-sm d-inline-flex align-items-center gap-1.5" style="color: var(--liquid-primary); font-size: 0.85rem;">
                <i class="bi bi-plus-circle-fill"></i>
                <span>Tambah Mitra</span>
            </a>
            <a href="{{ route('admin.orders.index') }}" class="btn fw-bold px-3.5 py-2 rounded-pill d-inline-flex align-items-center gap-1.5" style="background: rgba(255, 255, 255, 0.2); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.4); backdrop-filter: blur(12px); font-size: 0.85rem;">
                <i class="bi bi-receipt"></i>
                <span>Semua Pesanan</span>
            </a>
        </div>
    </div>
</div>

{{-- 4 Primary KPI Cards --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card card-hover h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="stat-label">Total Omzet</span>
                <div class="stat-icon" style="background: rgba(168, 85, 247, 0.1); color: #9333ea;">
                    <i class="bi bi-wallet2"></i>
                </div>
            </div>
            <div class="stat-value text-truncate">Rp {{ number_format($stats['revenue'], 0, ',', '.') }}</div>
            <div class="stat-desc">
                <i class="bi bi-check2-circle text-success me-1"></i>{{ $stats['completed_orders'] }} pesanan selesai
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card card-hover h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="stat-label">Total Transaksi</span>
                <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #059669;">
                    <i class="bi bi-receipt"></i>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <div class="stat-value">{{ $stats['total_orders'] }}</div>
                @if($stats['pending_orders'] > 0)
                    <span class="badge rounded-pill bg-warning-subtle text-warning border border-warning-subtle px-2 py-0.5" style="font-size: 0.68rem;">
                        {{ $stats['pending_orders'] }} Menunggu
                    </span>
                @endif
            </div>
            <div class="stat-desc">
                <i class="bi bi-arrow-repeat text-primary me-1"></i>Seluruh pesanan platform
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card card-hover h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="stat-label">Mitra Restoran</span>
                <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1); color: #d97706;">
                    <i class="bi bi-shop-window"></i>
                </div>
            </div>
            <div class="stat-value">{{ $stats['total_restaurants'] }} Resto</div>
            <div class="stat-desc">
                <i class="bi bi-box-seam text-secondary me-1"></i>{{ $stats['total_products'] }} katalog menu aktif
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card card-hover h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="stat-label">Pengguna Terdaftar</span>
                <div class="stat-icon" style="background: rgba(59, 130, 246, 0.1); color: #2563eb;">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
            <div class="stat-value">{{ $stats['total_users'] }} User</div>
            <div class="stat-desc">
                <i class="bi bi-person-check text-info me-1"></i>{{ $stats['total_customers'] }} pembeli &bull; {{ $stats['total_owners'] }} resto
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Left Column (8 cols): Recent Orders & Live Action --}}
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
                        <h6 class="fw-extrabold mb-0 text-dark">{{ $stats['pending_orders'] }} Pesanan Menunggu Konfirmasi</h6>
                        <small class="text-muted">Terdapat transaksi baru yang belum diproses oleh mitra restoran.</small>
                    </div>
                </div>
                <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="btn btn-warning btn-sm px-3.5 rounded-pill fw-bold text-dark shadow-sm">
                    <span>Tinjau Pesanan Pending</span>
                    <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
        @endif

        {{-- Recent Orders Table --}}
        <div class="card overflow-hidden">
            <div class="card-header bg-transparent d-flex flex-wrap justify-content-between align-items-center py-3 px-4 border-bottom">
                <div>
                    <h6 class="fw-extrabold mb-0 text-dark">Pesanan Terbaru Platform</h6>
                    <small class="text-muted">Aktivitas transaksi pesanan terkini dari seluruh restoran mitra</small>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-primary btn-sm px-3 rounded-pill fw-bold" style="font-size: 0.8rem;">
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
                            <th class="py-3">Restoran</th>
                            <th class="py-3">Total Belanja</th>
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
                                <div class="fw-semibold text-dark text-truncate" style="max-width: 140px;">{{ $order->restaurant->name ?? '-' }}</div>
                                <small class="text-muted" style="font-size: 0.7rem;">{{ ucfirst($order->payment_method) }}</small>
                            </td>
                            <td>
                                <strong style="color: var(--liquid-primary);">
                                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                </strong>
                            </td>
                            <td>
                                <span class="badge badge-{{ $order->status }} px-2.5 py-1 rounded-pill" style="font-size: 0.72rem;">
                                    {{ $order->status_label }}
                                </span>
                            </td>
                            <td class="px-4 text-end">
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-light btn-sm px-2.5 py-1 rounded-pill fw-bold" style="font-size: 0.75rem;">
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
                                <h6 class="fw-bold text-dark mb-1">Belum Ada Transaksi</h6>
                                <p class="small text-muted mb-0">Pesanan dari customer akan otomatis muncul di sini.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Right Column (4 cols): Order Breakdown & Recent Partners --}}
    <div class="col-lg-4">
        {{-- Order Status Breakdown Card --}}
        <div class="card p-4 mb-4">
            <h6 class="fw-extrabold mb-3 text-dark d-flex align-items-center gap-2">
                <i class="bi bi-pie-chart-fill text-primary"></i>
                <span>Distribusi Status Pesanan</span>
            </h6>
            @php
                $tot = max($stats['total_orders'], 1);
                $statusList = [
                    ['label' => 'Selesai Diterima', 'count' => $stats['completed_orders'], 'color' => '#10b981', 'bg' => 'bg-success'],
                    ['label' => 'Sedang Diproses', 'count' => $stats['processing_orders'], 'color' => '#3b82f6', 'bg' => 'bg-primary'],
                    ['label' => 'Menunggu (Pending)', 'count' => $stats['pending_orders'], 'color' => '#f59e0b', 'bg' => 'bg-warning'],
                    ['label' => 'Dibatalkan', 'count' => $stats['cancelled_orders'], 'color' => '#ef4444', 'bg' => 'bg-danger'],
                ];
            @endphp
            <div class="d-flex flex-column gap-3">
                @foreach($statusList as $st)
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-1 small">
                        <span class="text-secondary fw-semibold">{{ $st['label'] }}</span>
                        <strong class="text-dark">{{ $st['count'] }} <span class="text-muted fw-normal" style="font-size: 0.72rem;">({{ round(($st['count'] / $tot) * 100) }}%)</span></strong>
                    </div>
                    <div class="progress" style="height: 6px; background: rgba(241, 245, 249, 0.9);">
                        <div class="progress-bar {{ $st['bg'] }} rounded-pill" role="progressbar" style="width: {{ round(($st['count'] / $tot) * 100) }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Recent Restaurants Card --}}
        <div class="card p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-extrabold mb-0 text-dark d-flex align-items-center gap-2">
                    <i class="bi bi-shop text-warning"></i>
                    <span>Mitra Restoran Terbaru</span>
                </h6>
                <a href="{{ route('admin.restaurants.index') }}" class="small fw-bold text-primary text-decoration-none">Kelola</a>
            </div>
            <div class="d-flex flex-column gap-3">
                @forelse($recentRestaurants as $resto)
                <div class="d-flex align-items-center justify-content-between gap-2.5 pb-2.5 border-bottom">
                    <div class="d-flex align-items-center gap-2.5 min-width-0">
                        <div class="rounded-3 overflow-hidden d-flex align-items-center justify-content-center border flex-shrink-0" style="width: 40px; height: 40px; background: rgba(241, 245, 249, 0.9);">
                            @if($resto->image)
                                <img src="{{ asset('storage/' . $resto->image) }}" class="w-100 h-100 object-fit-cover" alt="{{ $resto->name }}">
                            @else
                                <i class="bi bi-shop text-muted fs-5"></i>
                            @endif
                        </div>
                        <div class="min-width-0">
                            <div class="fw-bold text-dark text-truncate small" style="max-width: 140px;">{{ $resto->name }}</div>
                            <small class="text-muted text-truncate d-block" style="font-size: 0.7rem;">Owner: {{ $resto->user->name ?? '-' }}</small>
                        </div>
                    </div>
                    <span class="badge rounded-pill {{ $resto->is_open ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-secondary-subtle text-secondary' }} px-2 py-0.5" style="font-size: 0.68rem;">
                        {{ $resto->is_open ? 'Buka' : 'Tutup' }}
                    </span>
                </div>
                @empty
                <p class="text-muted small mb-0">Belum ada mitra restoran terdaftar.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection
