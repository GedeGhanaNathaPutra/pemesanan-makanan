@extends('layouts.dashboard')
@section('title','Owner Dashboard')
@section('page-title','Dashboard Restoran')
@section('content')

{{-- Restaurant Header Banner --}}
<div class="card mb-4 p-4 border-0 shadow-xs overflow-hidden" style="background: linear-gradient(135deg, #ea580c 0%, #f97316 60%, #f59e0b 100%);">
    <div class="d-flex align-items-center gap-4 flex-wrap">
        @if($restaurant->image)
        <img src="{{ asset('storage/'.$restaurant->image) }}" class="rounded-3 flex-shrink-0 border border-white border-2 shadow-xs" style="width:76px;height:76px;object-fit:cover" alt="{{ $restaurant->name }}">
        @else
        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0 shadow-xs" style="width:76px;height:76px;background:rgba(255,255,255,0.25)">
            <i class="bi bi-shop-window text-white fs-2"></i>
        </div>
        @endif
        <div class="flex-grow-1 text-white ">
            <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                <h4 class="text-white fw-bold mb-0">{{ $restaurant->name }}</h4>
                <span class="badge {{ $restaurant->is_open ? 'bg-success' : 'bg-dark' }} px-2.5 py-1">
                    <i class="bi bi-circle-fill me-1" style="font-size:0.5rem"></i>{{ $restaurant->is_open ? 'Buka Menerima Pesanan' : 'Restoran Tutup' }}
                </span>
            </div>
            <p class="text-white opacity-75 mb-0 small"><i class="bi bi-geo-alt me-1"></i>{{ $restaurant->address }}</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('owner.restaurant.index') }}" class="btn btn-light btn-sm fw-semibold px-3 py-2 shadow-xs">
                <i class="bi bi-gear me-1 text-dark"></i>Pengaturan Restoran
            </a>
            <a href="{{ route('owner.products.create') }}" class="btn btn-dark btn-sm fw-semibold px-3 py-2 shadow-xs">
                <i class="bi bi-plus-lg me-1"></i>Tambah Menu
            </a>
        </div>
    </div>
</div>

{{-- Metric Cards (Free of Purple) --}}
<div class="row g-3 mb-4">
    @foreach([
        ['bi-box-seam','Total Menu',$stats['total_products'],'#ccfbf1','#0d9488'],
        ['bi-receipt','Total Pesanan Masuk',$stats['total_orders'],'#d1fae5','#059669'],
        ['bi-clock-history','Perlu Diproses',$stats['pending_orders'],'#ffedd5','#ea580c'],
        ['bi-cash-coin','Total Pendapatan','Rp '.number_format($stats['revenue'],0,',','.'),'#dcfce7','#16a34a']
    ] as [$ic,$lb,$vl,$bg,$co])
    <div class="col-6 col-md-3">
        <div class="card p-3 stat-card h-100 shadow-xs border-0">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-box flex-shrink-0" style="background:{{ $bg }};color:{{ $co }}">
                    <i class="bi {{ $ic }}"></i>
                </div>
                <div class="min-width-0">
                    <p class="fw-bold mb-0 text-truncate fs-6">{{ $vl }}</p>
                    <small class="text-muted text-truncate d-block" style="font-size:0.75rem">{{ $lb }}</small>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Recent Orders --}}
<div class="card shadow-xs border-0 overflow-hidden">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 px-4 border-bottom">
        <div>
            <h6 class="fw-bold mb-0">Pesanan Masuk Terbaru</h6>
            <small class="text-muted">Pantau status pesanan pelanggan restoran Anda secara langsung</small>
        </div>
        <a href="{{ route('owner.orders.index') }}" class="btn btn-outline-primary btn-sm px-3">
            <span>Lihat Semua</span>
            <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="small text-muted py-3 px-4">#ID Pesanan</th>
                    <th class="small text-muted py-3">Pelanggan</th>
                    <th class="small text-muted py-3">Total Pesanan</th>
                    <th class="small text-muted py-3">Status</th>
                    <th class="small text-muted py-3">Waktu Masuk</th>
                    <th class="small text-muted py-3 px-4 text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentOrders as $order)
                <tr>
                    <td class="small fw-bold px-4">#{{ $order->id }}</td>
                    <td class="small">
                        <div class="fw-semibold text-dark">{{ $order->user->name ?? 'Pelanggan' }}</div>
                        <div class="text-muted" style="font-size:0.75rem">{{ $order->user->phone ?? '-' }}</div>
                    </td>
                    <td class="small fw-bold" style="color: var(--food-primary)">
                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </td>
                    <td>
                        <span class="badge badge-{{ $order->status }} px-2.5 py-1.5 rounded-pill">
                            {{ $order->status_label }}
                        </span>
                    </td>
                    <td class="small text-muted">{{ $order->created_at?->format('d M Y, H:i') }}</td>
                    <td class="px-4 text-end">
                        <a href="{{ route('owner.orders.show', $order) }}" class="btn btn-light btn-sm px-3 py-1.5 border">
                            Kelola
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="bi bi-receipt fs-2 d-block mb-2 opacity-50"></i>
                        <p class="mb-0">Belum ada pesanan masuk untuk restoran Anda</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
