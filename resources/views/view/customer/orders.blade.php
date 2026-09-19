@extends('layouts.app')
@section('title', 'Pesanan Saya – FoodOrder')
@section('content')
<div class="container-fluid px-3 px-md-4 px-lg-5 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <span class="text-primary fw-bold text-uppercase small" style="letter-spacing: 1px;">Riwayat Transaksi</span>
            <h3 class="fw-extrabold mb-0 text-dark">
                <i class="bi bi-bag-check me-2 text-primary"></i>Pesanan Saya
            </h3>
            <p class="text-muted small mb-0">Riwayat dan pelacakan seluruh pesanan makanan Anda</p>
        </div>
        <a href="{{ route('restaurants') }}" class="btn btn-primary btn-sm px-4 py-2 fw-bold rounded-pill">
            <i class="bi bi-plus-lg me-1"></i>Pesan Makanan Lagi
        </a>
    </div>

    {{-- Filter Tabs & Search Bar --}}
    <div class="card border-0 rounded-4 p-3 mb-4">
        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
            {{-- Status Filter Pills --}}
            <div class="d-flex gap-2 overflow-x-auto pb-1 pb-lg-0 text-nowrap" style="scrollbar-width: thin;">
                @php
                    $currentStatus = request('status', 'all');
                    $statusTabs = [
                        'all'        => ['label' => 'Semua', 'icon' => 'bi-grid-fill'],
                        'pending'    => ['label' => 'Menunggu', 'icon' => 'bi-hourglass-split'],
                        'diproses'   => ['label' => 'Diproses', 'icon' => 'bi-fire'],
                        'dikirim'    => ['label' => 'Dikirim', 'icon' => 'bi-bicycle'],
                        'selesai'    => ['label' => 'Selesai', 'icon' => 'bi-check2-circle'],
                        'dibatalkan' => ['label' => 'Dibatalkan', 'icon' => 'bi-x-circle'],
                    ];
                @endphp

                @foreach($statusTabs as $stKey => $stData)
                <a href="{{ route('customer.orders.index', array_merge(request()->query(), ['status' => $stKey, 'page' => 1])) }}"
                   class="btn btn-sm rounded-pill fw-bold d-inline-flex align-items-center gap-1.5 px-3 py-1.5 {{ $currentStatus === $stKey ? 'btn-primary shadow-sm' : 'btn-light text-secondary' }}"
                   style="font-size: 0.82rem; transition: all 0.2s ease;">
                    <i class="bi {{ $stData['icon'] }}"></i>
                    <span>{{ $stData['label'] }}</span>
                    <span class="badge rounded-pill {{ $currentStatus === $stKey ? 'bg-white text-primary' : 'bg-secondary bg-opacity-25 text-dark' }} px-1.5 py-0.5" style="font-size: 0.7rem;">
                        {{ $counts[$stKey] ?? 0 }}
                    </span>
                </a>
                @endforeach
            </div>

            {{-- Search Form --}}
            <form action="{{ route('customer.orders.index') }}" method="GET" class="d-flex gap-2 w-100 w-lg-auto" style="min-width: 260px;">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white border-end-0 rounded-start-pill text-muted ps-3">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0 border-end-0" placeholder="Cari ID / Restoran..." value="{{ request('search') }}">
                    @if(request('search'))
                    <a href="{{ route('customer.orders.index', ['status' => request('status', 'all')]) }}" class="btn btn-outline-secondary border-start-0 border-end-0" title="Hapus Pencarian">
                        <i class="bi bi-x"></i>
                    </a>
                    @endif
                    <button class="btn btn-primary rounded-end-pill px-3 fw-bold" type="submit">Cari</button>
                </div>
            </form>
        </div>
    </div>

    @if($orders->isEmpty())
    <div class="card p-5 text-center border-0 rounded-4 my-3">
        <div class="category-icon-glass mx-auto mb-3" style="width: 80px; height: 80px; background: rgba(255, 242, 237, 0.85); color: var(--liquid-primary);">
            <i class="bi bi-bag-x fs-1"></i>
        </div>
        <h4 class="fw-extrabold text-dark">Belum Ada Riwayat Pesanan</h4>
        <p class="text-muted small mb-4" style="max-width: 400px; margin: 0 auto;">
            Anda belum pernah membuat pesanan makanan. Jelajahi restoran mitra kami dan nikmati berbagai menu lezat!
        </p>
        <div>
            <a href="{{ route('restaurants') }}" class="btn btn-primary px-4 py-2.5 rounded-pill fw-bold">
                Mulai Pesan Sekarang
            </a>
        </div>
    </div>
    @else
    <div class="d-flex flex-column gap-3">
        @foreach($orders as $order)
        <div class="card p-3.5 p-md-4 border-0 rounded-4 card-hover">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
                <div class="d-flex align-items-center gap-2.5">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: linear-gradient(135deg, #ff5722, #ea580c); color: #ffffff; box-shadow: 0 4px 12px rgba(234,88,12,0.3);">
                        <i class="bi bi-shop fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">{{ $order->restaurant->name ?? 'Restoran Mitra' }}</h6>
                        <small class="text-muted">
                            {{ $order->created_at?->format('d M Y, H:i') }} WIB &bull; <strong class="text-dark font-monospace">#{{ $order->id }}</strong>
                        </small>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    @if(in_array($order->status, ['pending', 'diproses', 'dikirim']))
                        <span class="spinner-grow spinner-grow-sm text-primary" style="width: 0.75rem; height: 0.75rem;" role="status" title="Pesanan sedang aktif"></span>
                    @endif
                    <span class="badge badge-{{ $order->status }} px-3 py-1.5 rounded-pill small">
                        <i class="bi bi-circle-fill me-1" style="font-size: 0.45rem;"></i>{{ $order->status_label }}
                    </span>
                </div>
            </div>

            {{-- Summary of items (Liquid Glass Pills) --}}
            <div class="d-flex flex-wrap gap-2 mb-3">
                @foreach($order->items->take(3) as $item)
                <span class="badge rounded-pill py-2 px-3 fw-semibold small" style="background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); color: #334155; border: 1px solid rgba(255, 255, 255, 0.85);">
                    {{ $item->product->name ?? 'Menu' }} <strong class="text-primary">&times;{{ $item->quantity }}</strong>
                </span>
                @endforeach
                @if($order->items->count() > 3)
                <span class="badge rounded-pill py-2 px-3 fw-semibold small" style="background: rgba(255, 255, 255, 0.5); backdrop-filter: blur(8px); color: #64748b; border: 1px solid rgba(255, 255, 255, 0.8);">
                    +{{ $order->items->count() - 3 }} menu lainnya
                </span>
                @endif
            </div>

            <div class="d-flex flex-wrap justify-content-between align-items-center pt-2.5 border-top gap-2">
                <div>
                    <span class="small text-muted me-1">Total Pembayaran:</span>
                    <strong class="fs-5 fw-extrabold" style="color: var(--liquid-primary);">
                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </strong>
                    <span class="badge rounded-pill px-2.5 py-1 ms-2 small" style="background: rgba(255, 255, 255, 0.7); color: #475569; border: 1px solid rgba(255, 255, 255, 0.85);">
                        {{ strtoupper($order->payment_method) }}
                    </span>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('customer.orders.show', $order) }}" class="btn btn-light btn-sm px-3.5 py-1.5 rounded-pill fw-bold">
                        Lihat Detail <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                    @if($order->status === 'selesai' && $order->restaurant_id)
                    <a href="{{ route('restaurants.show', $order->restaurant_id) }}" class="btn btn-primary btn-sm px-3 py-1.5 rounded-pill fw-bold" title="Pesan menu dari restoran ini lagi">
                        <i class="bi bi-arrow-repeat me-1"></i>Pesan Lagi
                    </a>
                    @endif
                    @if($order->status === 'pending')
                    <form action="{{ route('customer.orders.cancel', $order) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm px-3 py-1.5 rounded-pill fw-semibold">
                            Batalkan
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $orders->links() }}
    </div>
    @endif
</div>
@endsection
