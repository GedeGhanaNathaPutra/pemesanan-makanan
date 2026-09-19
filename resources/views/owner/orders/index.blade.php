@extends('layouts.dashboard')
@section('title', 'Kelola Pesanan Masuk')
@section('page-title', 'Pesanan Masuk')
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
                    'diproses'   => ['label' => 'Diproses Dapur', 'count' => $statusCounts['diproses'] ?? 0],
                    'dikirim'    => ['label' => 'Sedang Dikirim', 'count' => $statusCounts['dikirim'] ?? 0],
                    'selesai'    => ['label' => 'Selesai', 'count' => $statusCounts['selesai'] ?? 0],
                    'dibatalkan' => ['label' => 'Dibatalkan', 'count' => $statusCounts['dibatalkan'] ?? 0],
                ];
            @endphp
            @foreach($statuses as $sVal => $sData)
                @php
                    $isActive = ($currentStatus === $sVal) || ($sVal === '' && !$currentStatus);
                    $url = route('owner.orders.index', array_filter(array_merge(request()->except(['page']), ['status' => $sVal ?: null])));
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
        <form class="row g-2 align-items-center" method="GET" action="{{ route('owner.orders.index') }}">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <div class="col-md-4 col-sm-6">
                <div class="input-group input-group-sm rounded-pill overflow-hidden border shadow-sm" style="background: rgba(255,255,255,0.95);">
                    <span class="input-group-text border-0 bg-transparent ps-3 text-muted">
                        <i class="bi bi-search text-primary"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control border-0 ps-1" placeholder="Cari ID #, pelanggan, hp...">
                    @if(request('search'))
                        <a href="{{ route('owner.orders.index', array_filter(request()->except(['search', 'page']))) }}" class="input-group-text border-0 bg-transparent pe-3 text-muted" title="Hapus pencarian">
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
                    <option value="failed" @selected(request('payment_status') === 'failed')>Bayar: Ditolak</option>
                </select>
            </div>
            <div class="col-auto d-flex gap-1.5 align-items-center">
                <button type="submit" class="btn btn-primary btn-sm px-3 rounded-pill fw-bold">
                    Cari
                </button>
                @if(request()->hasAny(['search', 'status', 'payment_method', 'payment_status']))
                    <a href="{{ route('owner.orders.index') }}" class="btn btn-light btn-sm px-2.5 rounded-pill text-muted" title="Reset Semua Filter">
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
                    <th class="py-3 px-4">#ID Pesanan</th>
                    <th class="py-3">Pelanggan</th>
                    <th class="py-3">Total Belanja</th>
                    <th class="py-3">Metode Bayar</th>
                    <th class="py-3">Status Bayar</th>
                    <th class="py-3">Status Pesanan</th>
                    <th class="py-3">Waktu Masuk</th>
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
                                {{ strtoupper(substr($order->user->name ?? 'P', 0, 1)) }}
                            </div>
                            <div class="min-width-0">
                                <div class="fw-bold text-dark text-truncate" style="max-width: 160px;">{{ $order->user->name ?? 'Pelanggan' }}</div>
                                <small class="text-muted text-truncate d-block" style="font-size: 0.72rem;">{{ $order->user->phone ?? '-' }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <strong style="color: var(--liquid-primary);">
                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </strong>
                        <div class="text-muted" style="font-size: 0.72rem;">{{ $order->items->count() }} menu</div>
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
                        <div class="d-inline-flex align-items-center gap-1.5">
                            <a href="{{ route('owner.orders.show', $order) }}" class="btn btn-light btn-sm px-3 rounded-pill fw-bold" style="font-size: 0.8rem;">
                                Detail
                            </a>
                            @if(!in_array($order->status, ['selesai', 'dibatalkan']))
                            <button class="btn btn-primary btn-sm px-3 rounded-pill fw-bold" data-bs-toggle="modal" data-bs-target="#updateStatusModal{{ $order->id }}" style="font-size: 0.8rem;">
                                <i class="bi bi-arrow-repeat me-1"></i>Proses
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5 text-muted">
                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 56px; height: 56px; background: rgba(241, 245, 249, 0.9); color: #94a3b8;">
                            <i class="bi bi-receipt fs-2"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Tidak Ada Pesanan Masuk</h6>
                        <p class="small text-muted mb-0">Pesanan dari customer akan segera muncul saat pelanggan memesan menu Anda.</p>
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

{{-- UPDATE STATUS MODALS --}}
@foreach($orders as $order)
@if(!in_array($order->status, ['selesai', 'dibatalkan']))
@php
    $flow = [
        'pending' => ['diproses' => 'Mulai Masak (Diproses)', 'dibatalkan' => 'Batalkan Pesanan'],
        'diproses' => ['dikirim' => 'Kirim Makanan (Dikirim)', 'dibatalkan' => 'Batalkan Pesanan'],
        'dikirim' => ['selesai' => 'Selesaikan Pesanan (Sampai)', 'dibatalkan' => 'Batalkan Pesanan']
    ];
    $opts = $flow[$order->status] ?? [];
@endphp
@if(count($opts) > 0)
<div class="modal fade" id="updateStatusModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden" style="background: rgba(255, 255, 255, 0.96); backdrop-filter: blur(20px);">
            <div class="modal-header py-3 px-4 border-bottom" style="background: rgba(255, 242, 237, 0.5);">
                <h5 class="modal-title fw-extrabold text-dark d-flex align-items-center gap-2">
                    <span class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: rgba(255, 242, 237, 0.95); color: var(--liquid-primary);">
                        <i class="bi bi-arrow-repeat fs-6"></i>
                    </span>
                    <span>Proses Pesanan #{{ $order->id }}</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('owner.orders.updateStatus', $order) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="p-3 rounded-3 mb-3" style="background: rgba(248, 250, 252, 0.85); border: 1px solid rgba(226, 232, 240, 0.9);">
                        <div class="d-flex justify-content-between mb-1 small">
                            <span class="text-muted">Pelanggan:</span>
                            <strong class="text-dark">{{ $order->user->name ?? '-' }}</strong>
                        </div>
                        <div class="d-flex justify-content-between small">
                            <span class="text-muted">Total Belanja:</span>
                            <strong style="color: var(--liquid-primary)">Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Status Saat Ini:</label>
                        <div>
                            <span class="badge badge-{{ $order->status }} px-3 py-1.5 rounded-pill">
                                {{ $order->status_label }}
                            </span>
                        </div>
                    </div>

                    <div>
                        <label class="form-label small fw-bold">Pilih Status Selanjutnya: <span class="text-danger">*</span></label>
                        <div class="d-flex flex-column gap-2 mt-1">
                            @foreach($opts as $optVal => $optLabel)
                            <div class="form-check p-2.5 rounded-3 border d-flex align-items-center gap-2" style="cursor: pointer; background: rgba(255, 255, 255, 0.85);">
                                <input class="form-check-input ms-1" type="radio" name="status" value="{{ $optVal }}" id="s{{ $order->id }}{{ $optVal }}" required>
                                <label class="form-check-label w-100 fw-bold small text-dark" for="s{{ $order->id }}{{ $optVal }}" style="cursor: pointer;">
                                    {{ $optLabel }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer py-3 px-4 border-top bg-light">
                    <button type="button" class="btn btn-light px-3.5 py-2 rounded-pill fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill fw-bold">
                        <i class="bi bi-check-lg me-1"></i>Perbarui Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endif
@endforeach

@endsection
