@extends('layouts.dashboard')
@section('title','Pesanan Masuk')
@section('page-title','Pesanan Masuk')
@section('content')
<form class="row g-2 mb-4" method="GET">
    <div class="col-sm-4 col-md-3">
        <select name="status" class="form-select form-select-sm">
            <option value="">Semua Status</option>
            @foreach(['pending','diproses','dikirim','selesai','dibatalkan'] as $s)
            <option value="{{ $s }}" @selected(request('status')==$s)>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-auto d-flex gap-2">
        <button class="btn btn-secondary btn-sm">Filter</button>
        @if(request('status'))<a href="{{ route('owner.orders.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>@endif
    </div>
</form>
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light"><tr>
                <th class="small text-muted">#ID</th><th class="small text-muted">Pelanggan</th>
                <th class="small text-muted">Total</th><th class="small text-muted">Pembayaran</th>
                <th class="small text-muted">Status</th><th class="small text-muted">Tanggal</th><th></th>
            </tr></thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td class="small fw-semibold">#{{ $order->id }}</td>
                    <td class="small">{{ $order->user->name??'-' }}</td>
                    <td class="small fw-semibold">Rp {{ number_format($order->total_price,0,',','.') }}</td>
                    <td class="small text-muted">{{ ucfirst($order->payment_method) }}</td>
                    <td><span class="badge badge-{{ $order->status }}">{{ $order->status_label }}</span></td>
                    <td class="small text-muted">{{ $order->created_at?->format('d M Y, H:i') }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('owner.orders.show',$order) }}" class="btn btn-outline-secondary btn-sm">Detail</a>
                            @if(!in_array($order->status,['selesai','dibatalkan']))
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#updateStatusModal{{ $order->id }}">Update</button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-4 text-muted small">Belum ada pesanan masuk</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white py-2">{{ $orders->withQueryString()->links() }}</div>
</div>

{{-- UPDATE STATUS MODALS --}}
@foreach($orders as $order)
@if(!in_array($order->status,['selesai','dibatalkan']))
@php $flow=['pending'=>['diproses','dibatalkan'],'diproses'=>['dikirim','dibatalkan'],'dikirim'=>['selesai','dibatalkan']]; $opts=$flow[$order->status]??[]; @endphp
@if(count($opts)>0)
<div class="modal fade" id="updateStatusModal{{ $order->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="bi bi-arrow-repeat me-2" style="color:#fd7e14"></i>Update Status #{{ $order->id }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('owner.orders.updateStatus',$order) }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-body">
                    <p class="text-muted small mb-3">Pelanggan: <strong>{{ $order->user->name??'-' }}</strong> · Rp {{ number_format($order->total_price,0,',','.') }}</p>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Status Saat Ini</label>
                        <p><span class="badge badge-{{ $order->status }} px-3 py-2">{{ $order->status_label }}</span></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Ubah ke Status <span class="text-danger">*</span></label>
                        <div class="d-flex gap-2 flex-wrap">
                            @foreach($opts as $opt)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" value="{{ $opt }}" id="s{{ $order->id }}{{ $opt }}" required>
                                <label class="form-check-label badge badge-{{ $opt }} px-3 py-2" for="s{{ $order->id }}{{ $opt }}" style="cursor:pointer">{{ ucfirst($opt) }}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endif
@endforeach
@endsection