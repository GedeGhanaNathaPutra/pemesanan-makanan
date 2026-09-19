@extends('layouts.dashboard')
@section('title', 'Kelola Menu Restoran')
@section('page-title', 'Menu Hidangan')
@section('content')

{{-- Quick Filter Pills & Toolbar --}}
<div class="card border-0 p-3.5 mb-4">
    <div class="d-flex flex-wrap gap-3 justify-content-between align-items-center">
        {{-- Quick Availability Tabs --}}
        <div class="d-flex gap-1.5 flex-wrap align-items-center">
            @php
                $currentStatus = request('status');
                $statuses = [
                    ''             => ['label' => 'Semua', 'count' => $statusCounts['all'] ?? 0],
                    'available'    => ['label' => 'Siap Dipesan', 'count' => $statusCounts['available'] ?? 0],
                    'out_of_stock' => ['label' => 'Stok Habis', 'count' => $statusCounts['out_of_stock'] ?? 0],
                    'inactive'     => ['label' => 'Tidak Aktif', 'count' => $statusCounts['inactive'] ?? 0],
                ];
            @endphp
            @foreach($statuses as $sVal => $sData)
                @php
                    $isActive = ($currentStatus === $sVal) || ($sVal === '' && !$currentStatus);
                    $url = route('owner.products.index', array_filter(array_merge(request()->except(['page']), ['status' => $sVal ?: null])));
                @endphp
                <a href="{{ $url }}" class="btn btn-sm rounded-pill fw-bold px-3 py-1.5 d-inline-flex align-items-center gap-1.5 {{ $isActive ? 'btn-primary' : 'btn-light text-secondary' }}">
                    <span>{{ $sData['label'] }}</span>
                    <span class="badge rounded-pill {{ $isActive ? 'bg-white text-dark' : 'bg-secondary-subtle text-secondary' }}" style="font-size: 0.68rem;">
                        {{ $sData['count'] }}
                    </span>
                </a>
            @endforeach
        </div>

        {{-- Search & Category Filter --}}
        <div class="d-flex gap-2 align-items-center flex-wrap">
            <form class="d-flex gap-2 align-items-center" method="GET" action="{{ route('owner.products.index') }}">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <div class="input-group input-group-sm rounded-pill overflow-hidden border shadow-sm" style="min-width: 220px; background: rgba(255,255,255,0.95);">
                    <span class="input-group-text border-0 bg-transparent ps-3 text-muted">
                        <i class="bi bi-search text-primary"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control border-0 ps-1" placeholder="Cari menu...">
                    @if(request('search'))
                        <a href="{{ route('owner.products.index', array_filter(request()->except(['search', 'page']))) }}" class="input-group-text border-0 bg-transparent pe-3 text-muted" title="Hapus pencarian">
                            <i class="bi bi-x-circle-fill"></i>
                        </a>
                    @endif
                </div>

                <select name="category_id" class="form-select form-select-sm rounded-pill shadow-sm" style="min-width: 140px;" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>

                <button type="submit" class="btn btn-primary btn-sm px-3 rounded-pill fw-bold">
                    Cari
                </button>

                @if(request()->hasAny(['search', 'category_id', 'status']))
                    <a href="{{ route('owner.products.index') }}" class="btn btn-light btn-sm px-2.5 rounded-pill text-muted" title="Reset Semua Filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                @endif
            </form>

            <a href="{{ route('owner.products.create') }}" class="btn btn-primary btn-sm px-3.5 py-2 rounded-pill fw-bold d-inline-flex align-items-center gap-1.5 shadow-sm">
                <i class="bi bi-plus-circle-fill"></i>
                <span>Tambah Menu</span>
            </a>
        </div>
    </div>
</div>

{{-- Product Table --}}
<div class="card border-0 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th class="py-3 px-4">Menu Hidangan</th>
                    <th class="py-3">Kategori</th>
                    <th class="py-3">Harga Porsi</th>
                    <th class="py-3">Sisa Stok</th>
                    <th class="py-3">Status Ketersediaan</th>
                    <th class="py-3 px-4 text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td class="px-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-3 overflow-hidden d-flex align-items-center justify-content-center border flex-shrink-0" style="width: 46px; height: 46px; background: rgba(241, 245, 249, 0.9);">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" class="w-100 h-100 object-fit-cover" alt="{{ $product->name }}">
                                @else
                                    <i class="bi bi-egg-fried text-muted fs-4"></i>
                                @endif
                            </div>
                            <div class="min-width-0">
                                <div class="fw-bold text-dark text-truncate" style="max-width: 220px;">{{ $product->name }}</div>
                                <small class="text-muted text-truncate d-block" style="font-size: 0.72rem; max-width: 220px;">{{ Str::limit($product->description, 38) ?: 'Tidak ada deskripsi' }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge rounded-pill bg-light text-secondary border px-2.5 py-1 small">
                            {{ $product->category->name ?? '-' }}
                        </span>
                    </td>
                    <td>
                        <strong style="color: var(--liquid-primary);">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </strong>
                    </td>
                    <td>
                        @if($product->stock > 10)
                            <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-2.5 py-1 small fw-bold">
                                {{ $product->stock }} porsi
                            </span>
                        @elseif($product->stock > 0)
                            <span class="badge rounded-pill bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1 small fw-bold">
                                {{ $product->stock }} (Hampir Habis)
                            </span>
                        @else
                            <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1 small fw-bold">
                                Habis
                            </span>
                        @endif
                    </td>
                    <td>
                        @if($product->is_available && $product->stock > 0)
                            <span class="badge rounded-pill bg-success px-3 py-1.5 small fw-bold d-inline-flex align-items-center gap-1">
                                <i class="bi bi-check-circle-fill" style="font-size: 0.5rem;"></i>Siap Dipesan
                            </span>
                        @else
                            <span class="badge rounded-pill bg-secondary px-3 py-1.5 small fw-bold d-inline-flex align-items-center gap-1">
                                <i class="bi bi-dash-circle-fill" style="font-size: 0.5rem;"></i>Tidak Aktif
                            </span>
                        @endif
                    </td>
                    <td class="px-4 text-end">
                        <div class="d-inline-flex align-items-center gap-1.5">
                            <a href="{{ route('owner.products.edit', $product) }}" class="btn btn-light btn-sm px-2.5 py-1.5 rounded-pill" title="Edit Menu">
                                <i class="bi bi-pencil-square text-primary"></i>
                            </a>
                            <form action="{{ route('owner.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Hapus menu {{ $product->name }} dari restoran?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-light btn-sm px-2.5 py-1.5 rounded-pill text-danger" title="Hapus Menu">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 56px; height: 56px; background: rgba(241, 245, 249, 0.9); color: #94a3b8;">
                            <i class="bi bi-box-seam fs-2"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Belum Ada Menu Hidangan</h6>
                        <p class="small text-muted mb-3">Mulai tambahkan hidangan andalan restoran Anda sekarang.</p>
                        <a href="{{ route('owner.products.create') }}" class="btn btn-primary btn-sm px-4 rounded-pill fw-bold">
                            <i class="bi bi-plus-circle me-1"></i>Tambah Menu Sekarang
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($products->hasPages())
    <div class="card-footer bg-transparent py-3 px-4 border-top d-flex justify-content-center">
        {{ $products->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
