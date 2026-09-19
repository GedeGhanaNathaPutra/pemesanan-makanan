@extends('layouts.dashboard')
@section('title','Detail Pesanan #'.$order->id)
@section('page-title','Detail Pesanan')
@section('content')
<div class="d-flex align-items-center gap-3 mb-4 flex-wrap">
    <a href="{{ route('owner.orders.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i></a>
    <h5 class="fw-bold mb-0">Pesanan #{{ $order->id }}</h5>
    <span class="badge badge-{{ $order->status }} px-3 py-2">{{ $order->status_label }}</span>
    @if(!in_array($order->status,['selesai','dibatalkan']))
    <button class="btn btn-primary btn-sm ms-auto" data-bs-toggle="modal" data-bs-target="#updateStatusModal">
        <i class="bi bi-arrow-repeat me-1"></i>Update Status
    </button>
    @endif
</div>
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header bg-white py-3"><h6 class="fw-bold mb-0">Item Pesanan</h6></div>
            <div class="card-body p-0">
                @foreach($order->items as $item)
                <div class="d-flex align-items-center gap-3 p-3 border-bottom">
                    @if($item->product?->image)
                    <img src="{{ asset('storage/'.$item->product->image) }}" class="rounded-2 flex-shrink-0" style="width:50px;height:50px;object-fit:cover">
                    @else
                    <div class="rounded-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width:50px;height:50px;background:#fff3e0"><i class="bi bi-egg-fried" style="color:#fd7e14"></i></div>
                    @endif
                    <div class="flex-grow-1 min-width-0">
                        <p class="fw-semibold small mb-0 text-truncate">{{ $item->product->name??'Produk Dihapus' }}</p>
                        <small class="text-muted">Rp {{ number_format($item->price,0,',','.') }} × {{ $item->quantity }}</small>
                    </div>
                    <span class="fw-semibold small flex-shrink-0">Rp {{ number_format($item->price*$item->quantity,0,',','.') }}</span>
                </div>
                @endforeach
                <div class="p-3 d-flex justify-content-between fw-bold">
                    <span>Total</span><span style="color:#fd7e14">Rp {{ number_format($order->total_price,0,',','.') }}</span>
                </div>
            </div>
        </div>
        @if($order->payment?->payment_proof)
        <div class="card p-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-credit-card me-2"></i>Bukti Pembayaran</h6>
            <img src="{{ asset('storage/'.$order->payment->payment_proof) }}" class="img-fluid rounded" style="max-width:280px">
            <p class="small text-muted mt-2 mb-0">Dibayar: {{ $order->payment->paid_at?->format('d M Y H:i')??'-' }}</p>
        </div>
        @endif
    </div>
    <div class="col-lg-4">
        <div class="card p-4 mb-3">
            <h6 class="fw-bold mb-3">Informasi Pelanggan</h6>
            <table class="table table-sm table-borderless small mb-0">
                <tr><td class="text-muted">Nama</td><td class="fw-semibold">{{ $order->user->name??'-' }}</td></tr>
                <tr><td class="text-muted">Email</td><td>{{ $order->user->email??'-' }}</td></tr>
                <tr><td class="text-muted">Telepon</td><td>{{ $order->user->phone??'-' }}</td></tr>
                <tr><td class="text-muted">Pembayaran</td><td>{{ ucfirst($order->payment_method) }}</td></tr>
                <tr><td class="text-muted">Status Bayar</td>
                    <td>@php $ps=$order->payment?->payment_status??'pending'; @endphp
                    <span class="badge {{ $ps==='paid'?'bg-success':($ps==='failed'?'bg-danger':'bg-warning text-dark') }}">{{ ucfirst($ps) }}</span></td>
                </tr>
                <tr><td class="text-muted">Tanggal</td><td>{{ $order->created_at?->format('d M Y H:i') }}</td></tr>
            </table>
        </div>
        <div class="card p-4">
            <h6 class="fw-bold mb-2"><i class="bi bi-geo-alt me-1"></i>Alamat Pengiriman</h6>
            <p class="small text-muted mb-0">{{ $order->address }}</p>
        </div>
    </div>
</div>

{{-- UPDATE STATUS MODAL --}}
@if(!in_array($order->status,['selesai','dibatalkan']))
@php $flow=['pending'=>['diproses','dibatalkan'],'diproses'=>['dikirim','dibatalkan'],'dikirim'=>['selesai','dibatalkan']]; $opts=$flow[$order->status]??[]; @endphp
<div class="modal fade" id="updateStatusModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="bi bi-arrow-repeat me-2" style="color:#fd7e14"></i>Update Status Pesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('owner.orders.updateStatus',$order) }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Status Saat Ini</label>
                        <p><span class="badge badge-{{ $order->status }} px-3 py-2">{{ $order->status_label }}</span></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Ubah ke <span class="text-danger">*</span></label>
                        <div class="d-flex gap-2 flex-wrap">
                            @foreach($opts as $opt)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" value="{{ $opt }}" id="ss{{ $opt }}" required>
                                <label class="form-check-label badge badge-{{ $opt }} px-3 py-2" for="ss{{ $opt }}" style="cursor:pointer">{{ ucfirst($opt) }}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection