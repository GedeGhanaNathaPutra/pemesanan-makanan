@extends('layouts.dashboard')
@section('title', 'Detail Pesanan #' . $order->id)
@section('page-title', 'Detail Pesanan')
@section('content')

{{-- Header Banner & Status Action --}}
<div class="card border-0 p-3.5 mb-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-light btn-sm px-3 rounded-pill fw-bold" title="Kembali">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
            <div>
                <div class="d-flex align-items-center gap-2">
                    <h5 class="fw-extrabold mb-0 text-dark">Pesanan #{{ $order->id }}</h5>
                    <span class="badge badge-{{ $order->status }} px-3 py-1.5 rounded-pill">
                        {{ $order->status_label }}
                    </span>
                </div>
                <small class="text-muted">{{ $order->created_at?->format('d M Y, H:i') }} &bull; Restoran: <strong class="text-dark">{{ $order->restaurant->name ?? '-' }}</strong></small>
            </div>
        </div>

        <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="d-flex align-items-center gap-2">
            @csrf
            @method('PUT')
            <span class="small fw-bold text-muted d-none d-sm-inline">Ubah Status:</span>
            <select name="status" class="form-select form-select-sm" style="min-width: 150px;">
                @foreach(['pending' => 'Pending', 'diproses' => 'Diproses', 'dikirim' => 'Dikirim', 'selesai' => 'Selesai', 'dibatalkan' => 'Dibatalkan'] as $val => $lbl)
                    <option value="{{ $val }}" {{ $order->status === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary btn-sm px-3.5 rounded-pill fw-bold">
                Update
            </button>
        </form>
    </div>
</div>

<div class="row g-4">
    {{-- Left Column: Items & Payment Proof --}}
    <div class="col-lg-8">
        {{-- Items List Card --}}
        <div class="card border-0 overflow-hidden mb-4">
            <div class="card-header bg-transparent py-3 px-4 border-bottom">
                <h6 class="fw-extrabold mb-0 text-dark">Rincian Menu Dipesan</h6>
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
                                    <div class="rounded-3 overflow-hidden d-flex align-items-center justify-content-center border flex-shrink-0" style="width: 42px; height: 42px; background: rgba(241, 245, 249, 0.9);">
                                        @if($item->product && $item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" class="w-100 h-100 object-fit-cover" alt="{{ $item->product->name }}">
                                        @else
                                            <i class="bi bi-egg-fried text-muted fs-5"></i>
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
                            <td colspan="3" class="px-4 py-3 fw-bold text-dark">Total Pembayaran</td>
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
                <i class="bi bi-image text-primary"></i>
                <span>Bukti Pembayaran Manual</span>
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
                        <form action="{{ route('admin.orders.verifyPayment', $order) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="paid">
                            <button type="submit" class="btn btn-success btn-sm px-3.5 py-2 rounded-pill fw-bold d-inline-flex align-items-center gap-1.5 shadow-sm">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Verifikasi Lunas</span>
                            </button>
                        </form>
                        <form action="{{ route('admin.orders.verifyPayment', $order) }}" method="POST" onsubmit="return confirm('Tolak bukti pembayaran ini?')">
                            @csrf
                            <input type="hidden" name="status" value="failed">
                            <button type="submit" class="btn btn-outline-danger btn-sm px-3.5 py-2 rounded-pill fw-bold d-inline-flex align-items-center gap-1.5">
                                <i class="bi bi-x-circle"></i>
                                <span>Tolak Pembayaran</span>
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
            <h6 class="fw-extrabold mb-3 text-dark">Informasi Transaksi</h6>
            <div class="d-flex flex-column gap-2.5 small">
                <div class="d-flex justify-content-between pb-2 border-bottom">
                    <span class="text-muted">Pelanggan</span>
                    <strong class="text-dark">{{ $order->user->name ?? '-' }}</strong>
                </div>
                <div class="d-flex justify-content-between pb-2 border-bottom">
                    <span class="text-muted">Email</span>
                    <span class="text-dark">{{ $order->user->email ?? '-' }}</span>
                </div>
                <div class="d-flex justify-content-between pb-2 border-bottom">
                    <span class="text-muted">Telepon</span>
                    <span class="text-dark">{{ $order->user->phone ?? '-' }}</span>
                </div>
                <div class="d-flex justify-content-between pb-2 border-bottom">
                    <span class="text-muted">Metode Bayar</span>
                    <span class="badge rounded-pill bg-light text-secondary border px-2.5 py-1">{{ ucfirst($order->payment_method) }}</span>
                </div>
                <div class="d-flex justify-content-between pb-2 border-bottom">
                    <span class="text-muted">Status Bayar</span>
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
@endsection
