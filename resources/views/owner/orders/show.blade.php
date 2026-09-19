@extends('layouts.dashboard')
@section('title', 'Detail Pesanan #' . $order->id)
@section('page-title', 'Detail Pesanan')
@section('content')

{{-- Header Banner & Action --}}
<div class="card border-0 p-3.5 mb-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('owner.orders.index') }}" class="btn btn-light btn-sm px-3 rounded-pill fw-bold" title="Kembali">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
            <div>
                <div class="d-flex align-items-center gap-2">
                    <h5 class="fw-extrabold mb-0 text-dark">Pesanan Masuk #{{ $order->id }}</h5>
                    <span class="badge badge-{{ $order->status }} px-3 py-1.5 rounded-pill">
                        {{ $order->status_label }}
                    </span>
                </div>
                <small class="text-muted">{{ $order->created_at?->format('d M Y, H:i') }} &bull; Pelanggan: <strong class="text-dark">{{ $order->user->name ?? '-' }}</strong></small>
            </div>
        </div>

        @if(!in_array($order->status, ['selesai', 'dibatalkan']))
        <button class="btn btn-primary btn-sm px-4 py-2 rounded-pill fw-bold d-inline-flex align-items-center gap-1.5 shadow-sm" data-bs-toggle="modal" data-bs-target="#updateStatusModal">
            <i class="bi bi-arrow-repeat"></i>
            <span>Proses Status Pesanan</span>
        </button>
        @endif
    </div>
</div>

<div class="row g-4">
    {{-- Left Column: Items & Payment Proof --}}
    <div class="col-lg-8">
        {{-- Ordered Items Card --}}
        <div class="card border-0 overflow-hidden mb-4">
            <div class="card-header bg-transparent py-3 px-4 border-bottom">
                <h6 class="fw-extrabold mb-0 text-dark">Menu Hidangan Yang Dipesan</h6>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="py-3 px-4">Menu</th>
                            <th class="py-3 text-center">Jumlah</th>
                            <th class="py-3 text-end">Harga Satuan</th>
                            <th class="py-3 px-4 text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td class="px-4">
                                <div class="d-flex align-items-center gap-2.5">
                                    <div class="rounded-3 overflow-hidden d-flex align-items-center justify-content-center border flex-shrink-0" style="width: 44px; height: 44px; background: rgba(241, 245, 249, 0.9);">
                                        @if($item->product && $item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" class="w-100 h-100 object-fit-cover" alt="{{ $item->product->name }}">
                                        @else
                                            <i class="bi bi-egg-fried text-muted fs-4"></i>
                                        @endif
                                    </div>
                                    <div class="min-width-0">
                                        <div class="fw-bold text-dark text-truncate">{{ $item->product->name ?? 'Produk Dihapus' }}</div>
                                        <small class="text-muted">{{ $item->product->category->name ?? 'Kategori' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center fw-bold text-dark">
                                &times;{{ $item->quantity }}
                            </td>
                            <td class="text-end text-muted small">
                                Rp {{ number_format($item->price, 0, ',', '.') }}
                            </td>
                            <td class="px-4 text-end fw-bold text-dark">
                                Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background: rgba(255, 242, 237, 0.4);">
                            <td colspan="3" class="px-4 py-3 fw-bold text-dark">Total Yang Harus Dibayar</td>
                            <td class="px-4 py-3 text-end fw-extrabold fs-6" style="color: var(--liquid-primary);">
                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Payment Proof Verification Card --}}
        @if($order->payment?->payment_proof)
        <div class="card border-0 p-4">
            <h6 class="fw-extrabold mb-3 text-dark d-flex align-items-center gap-2">
                <i class="bi bi-credit-card-2-front-fill text-primary"></i>
                <span>Bukti Pembayaran Pelanggan</span>
            </h6>
            <div class="row align-items-center g-3">
                <div class="col-md-5">
                    <a href="{{ asset('storage/' . $order->payment->payment_proof) }}" target="_blank" title="Klik untuk memperbesar">
                        <img src="{{ asset('storage/' . $order->payment->payment_proof) }}" class="img-fluid rounded-3 border shadow-sm w-100 object-fit-cover" style="max-height: 240px;" alt="Bukti Transfer">
                    </a>
                </div>
                <div class="col-md-7">
                    <div class="p-3 rounded-3 mb-3" style="background: rgba(248, 250, 252, 0.85); border: 1px solid rgba(226, 232, 240, 0.9);">
                        <div class="mb-2">
                            <span class="text-muted d-block small">Waktu Unggah Bukti:</span>
                            <strong class="text-dark small">{{ $order->payment->paid_at?->format('d M Y, H:i:s') ?? '-' }}</strong>
                        </div>
                        <div>
                            <span class="text-muted d-block small">Status Verifikasi Saat Ini:</span>
                            @php $ps = $order->payment->payment_status; @endphp
                            @if($ps === 'paid')
                                <span class="badge rounded-pill bg-success px-3 py-1 small fw-bold">Lunas (Terverifikasi)</span>
                            @elseif($ps === 'failed')
                                <span class="badge rounded-pill bg-danger px-3 py-1 small fw-bold">Ditolak</span>
                            @else
                                <span class="badge rounded-pill bg-warning text-dark px-3 py-1 small fw-bold">Menunggu Verifikasi</span>
                            @endif
                        </div>
                    </div>

                    @if($order->payment->payment_status === 'pending')
                    <div class="d-flex gap-2">
                        <form action="{{ route('owner.orders.verifyPayment', $order) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="paid">
                            <button type="submit" class="btn btn-success btn-sm px-3.5 py-2 rounded-pill fw-bold d-inline-flex align-items-center gap-1.5 shadow-sm">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Verifikasi Lunas</span>
                            </button>
                        </form>
                        <form action="{{ route('owner.orders.verifyPayment', $order) }}" method="POST" onsubmit="return confirm('Tolak bukti pembayaran ini?')">
                            @csrf
                            <input type="hidden" name="status" value="failed">
                            <button type="submit" class="btn btn-outline-danger btn-sm px-3.5 py-2 rounded-pill fw-bold d-inline-flex align-items-center gap-1.5">
                                <i class="bi bi-x-circle"></i>
                                <span>Tolak</span>
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Right Column: Customer & Delivery Info --}}
    <div class="col-lg-4">
        {{-- Order Info Card --}}
        <div class="card border-0 p-4 mb-4">
            <h6 class="fw-extrabold mb-3 text-dark">Informasi Pemesan</h6>
            <div class="d-flex flex-column gap-2.5 small">
                <div class="d-flex justify-content-between pb-2 border-bottom">
                    <span class="text-muted">Nama Pelanggan</span>
                    <strong class="text-dark">{{ $order->user->name ?? '-' }}</strong>
                </div>
                <div class="d-flex justify-content-between pb-2 border-bottom">
                    <span class="text-muted">Email</span>
                    <span class="text-dark">{{ $order->user->email ?? '-' }}</span>
                </div>
                <div class="d-flex justify-content-between pb-2 border-bottom">
                    <span class="text-muted">Nomor WhatsApp / Telepon</span>
                    <strong class="text-dark">{{ $order->user->phone ?? '-' }}</strong>
                </div>
                <div class="d-flex justify-content-between pb-2 border-bottom">
                    <span class="text-muted">Metode Pembayaran</span>
                    <span class="badge rounded-pill bg-light text-secondary border px-2.5 py-1">{{ ucfirst($order->payment_method) }}</span>
                </div>
                <div class="d-flex justify-content-between pb-2 border-bottom">
                    <span class="text-muted">Status Pembayaran</span>
                    @php $ps = $order->payment?->payment_status ?? 'pending'; @endphp
                    @if($ps === 'paid')
                        <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-2.5 py-1 fw-bold">Lunas</span>
                    @elseif($ps === 'failed')
                        <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1 fw-bold">Gagal</span>
                    @else
                        <span class="badge rounded-pill bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1 fw-bold">Pending</span>
                    @endif
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Waktu Pemesanan</span>
                    <span class="text-dark">{{ $order->created_at?->format('d M Y, H:i') }}</span>
                </div>
            </div>
        </div>

        {{-- Delivery Address Card --}}
        <div class="card border-0 p-4">
            <h6 class="fw-extrabold mb-2 text-dark d-flex align-items-center gap-2">
                <i class="bi bi-geo-alt-fill text-danger"></i>
                <span>Alamat Pengantaran</span>
            </h6>
            <p class="small text-secondary mb-0" style="line-height: 1.6;">
                {{ $order->address }}
            </p>
        </div>
    </div>
</div>

{{-- UPDATE STATUS MODAL --}}
@if(!in_array($order->status, ['selesai', 'dibatalkan']))
@php
    $flow = [
        'pending' => ['diproses' => 'Mulai Masak (Diproses)', 'dibatalkan' => 'Batalkan Pesanan'],
        'diproses' => ['dikirim' => 'Kirim Makanan (Dikirim)', 'dibatalkan' => 'Batalkan Pesanan'],
        'dikirim' => ['selesai' => 'Selesaikan Pesanan (Sampai)', 'dibatalkan' => 'Batalkan Pesanan']
    ];
    $opts = $flow[$order->status] ?? [];
@endphp
<div class="modal fade" id="updateStatusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden" style="background: rgba(255, 255, 255, 0.96); backdrop-filter: blur(20px);">
            <div class="modal-header py-3 px-4 border-bottom" style="background: rgba(255, 242, 237, 0.5);">
                <h5 class="modal-title fw-extrabold text-dark d-flex align-items-center gap-2">
                    <span class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: rgba(255, 242, 237, 0.95); color: var(--liquid-primary);">
                        <i class="bi bi-arrow-repeat fs-6"></i>
                    </span>
                    <span>Proses Status Pesanan #{{ $order->id }}</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('owner.orders.updateStatus', $order) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
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
                                <input class="form-check-input ms-1" type="radio" name="status" value="{{ $optVal }}" id="ss{{ $optVal }}" required>
                                <label class="form-check-label w-100 fw-bold small text-dark" for="ss{{ $optVal }}" style="cursor: pointer;">
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

@endsection
