@extends('layouts.dashboard')
@section('title', 'Semua Pesanan Platform')
@section('page-title', 'Semua Pesanan')
@section('content')

{{-- Quick Status Pills & Filter Toolbar --}}
<div class="card border-0 p-3.5 mb-4">
    <div class="d-flex flex-column gap-3">
        {{-- Quick Status Tabs --}}
        <div class="d-flex gap-1.5 flex-wrap align-items-center">
            @php
                $currentStatus = request('status');
                $statuses = [
                    ''           => ['label' => 'Semua', 'count' => $statusCounts['all'] ?? 0],
                    'pending'    => ['label' => 'Pending', 'count' => $statusCounts['pending'] ?? 0],
                    'diproses'   => ['label' => 'Diproses', 'count' => $statusCounts['diproses'] ?? 0],
                    'dikirim'    => ['label' => 'Dikirim', 'count' => $statusCounts['dikirim'] ?? 0],
                    'selesai'    => ['label' => 'Selesai', 'count' => $statusCounts['selesai'] ?? 0],
                    'dibatalkan' => ['label' => 'Dibatalkan', 'count' => $statusCounts['dibatalkan'] ?? 0],
                ];
            @endphp
            @foreach($statuses as $sVal => $sData)
                @php
                    $isActive = ($currentStatus === $sVal) || ($sVal === '' && !$currentStatus);
                    $url = route('admin.orders.index', array_filter(array_merge(request()->except(['page']), ['status' => $sVal ?: null])));
                @endphp
                <a href="{{ $url }}" class="btn btn-sm rounded-pill fw-bold px-3 py-1.5 d-inline-flex align-items-center gap-1.5 {{ $isActive ? 'btn-primary' : 'btn-light text-secondary' }}">
                    <span>{{ $sData['label'] }}</span>
                    <span class="badge rounded-pill {{ $isActive ? 'bg-white text-dark' : 'bg-secondary-subtle text-secondary' }}" style="font-size: 0.68rem;">
                        {{ $sData['count'] }}
                    </span>
                </a>
            @endforeach
        </div>

        {{-- Search & Detailed Filters Form --}}
        <form class="row g-2 align-items-center" method="GET" action="{{ route('admin.orders.index') }}">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <div class="col-md-4 col-sm-6">
                <div class="input-group input-group-sm rounded-pill overflow-hidden border shadow-sm" style="background: rgba(255,255,255,0.95);">
                    <span class="input-group-text border-0 bg-transparent ps-3 text-muted">
                        <i class="bi bi-search text-primary"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control border-0 ps-1" placeholder="Cari ID #, pelanggan, resto...">
                    @if(request('search'))
                        <a href="{{ route('admin.orders.index', array_filter(request()->except(['search', 'page']))) }}" class="input-group-text border-0 bg-transparent pe-3 text-muted" title="Hapus pencarian">
                            <i class="bi bi-x-circle-fill"></i>
                        </a>
                    @endif
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <select name="payment_method" class="form-select form-select-sm rounded-pill shadow-sm" onchange="this.form.submit()">
                    <option value="">Semua Metode Bayar</option>
                    <option value="cod" @selected(request('payment_method') === 'cod')>COD (Bayar di Tempat)</option>
                    <option value="transfer" @selected(request('payment_method') === 'transfer')>Transfer Bank Manual</option>
                    <option value="ewallet" @selected(request('payment_method') === 'ewallet')>E-Wallet (QRIS/Gopay)</option>
                </select>
            </div>
            <div class="col-md-3 col-sm-6">
                <select name="payment_status" class="form-select form-select-sm rounded-pill shadow-sm" onchange="this.form.submit()">
                    <option value="">Semua Status Bayar</option>
                    <option value="pending" @selected(request('payment_status') === 'pending')>Bayar: Pending</option>
                    <option value="paid" @selected(request('payment_status') === 'paid')>Bayar: Lunas</option>
                    <option value="failed" @selected(request('payment_status') === 'failed')>Bayar: Ditolak / Gagal</option>
                </select>
            </div>
            <div class="col-auto d-flex gap-1.5 align-items-center">
                <button type="submit" class="btn btn-primary btn-sm px-3 rounded-pill fw-bold">
                    Cari
                </button>
                @if(request()->hasAny(['search', 'status', 'payment_method', 'payment_status']))
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-light btn-sm px-2.5 rounded-pill text-muted" title="Reset Semua Filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Orders Table --}}
<div class="card border-0 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th class="py-3 px-4">#ID</th>
                    <th class="py-3">Pelanggan</th>
                    <th class="py-3">Restoran</th>
                    <th class="py-3">Total Belanja</th>
                    <th class="py-3">Metode Bayar</th>
                    <th class="py-3">Status Bayar</th>
                    <th class="py-3">Status Pesanan</th>
                    <th class="py-3">Waktu</th>
                    <th class="py-3 px-4 text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td class="px-4">
                        <span class="fw-bold font-monospace text-dark">#{{ $order->id }}</span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px; background: rgba(241, 245, 249, 0.9); color: #475569; font-weight: 700; font-size: 0.75rem;">
                                {{ strtoupper(substr($order->user->name ?? 'G', 0, 1)) }}
                            </div>
                            <div class="min-width-0">
                                <div class="fw-bold text-dark text-truncate" style="max-width: 150px;">{{ $order->user->name ?? 'Tamu' }}</div>
                                <small class="text-muted text-truncate d-block" style="font-size: 0.72rem; max-width: 150px;">{{ $order->user->email ?? '-' }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="fw-semibold text-dark text-truncate" style="max-width: 150px;">{{ $order->restaurant->name ?? '-' }}</div>
                        <small class="text-muted" style="font-size: 0.72rem;">{{ $order->items->count() }} menu</small>
                    </td>
                    <td>
                        <strong style="color: var(--liquid-primary);">
                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </strong>
                    </td>
                    <td>
                        <span class="badge rounded-pill bg-light text-secondary border px-2.5 py-1 small">
                            {{ ucfirst($order->payment_method) }}
                        </span>
                    </td>
                    <td>
                        @php $ps = $order->payment?->payment_status ?? 'pending'; @endphp
                        @if($ps === 'paid')
                            <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-2.5 py-1 small fw-bold">Lunas</span>
                        @elseif($ps === 'failed')
                            <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1 small fw-bold">Ditolak</span>
                        @else
                            <span class="badge rounded-pill bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1 small fw-bold">Pending</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge badge-{{ $order->status }} px-3 py-1.5 rounded-pill">
                            {{ $order->status_label }}
                        </span>
                    </td>
                    <td class="small text-muted">
                        {{ $order->created_at?->format('d M Y, H:i') }}
                    </td>
                    <td class="px-4 text-end">
                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-light btn-sm px-3 rounded-pill fw-bold" style="font-size: 0.8rem;">
                            <span>Detail</span>
                            <i class="bi bi-chevron-right ms-0.5"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-5 text-muted">
                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 56px; height: 56px; background: rgba(241, 245, 249, 0.9); color: #94a3b8;">
                            <i class="bi bi-receipt fs-2"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Tidak Ada Pesanan Ditemukan</h6>
                        <p class="small text-muted mb-0">Coba ubah kata kunci pencarian atau status filter Anda.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($orders->hasPages())
    <div class="card-footer bg-transparent py-3 px-4 border-top d-flex justify-content-center">
        {{ $orders->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
