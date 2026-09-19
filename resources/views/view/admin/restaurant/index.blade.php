@extends('layouts.dashboard')
@section('title', 'Kelola Mitra Restoran')
@section('page-title', 'Mitra Restoran')
@section('content')

{{-- Quick Filter Pills & Toolbar --}}
<div class="card border-0 p-3.5 mb-4">
    <div class="d-flex flex-wrap gap-3 justify-content-between align-items-center">
        {{-- Quick Operational Status Tabs --}}
        <div class="d-flex gap-1.5 flex-wrap align-items-center">
            @php
                $currentStatus = request('is_open');
                $statuses = [
                    ''  => ['label' => 'Semua Resto', 'count' => $statusCounts['all'] ?? 0],
                    '1' => ['label' => 'Sedang Buka', 'count' => $statusCounts['open'] ?? 0],
                    '0' => ['label' => 'Tutup Sementara', 'count' => $statusCounts['closed'] ?? 0],
                ];
            @endphp
            @foreach($statuses as $sVal => $sData)
                @php
                    $isActive = ($currentStatus === (string)$sVal) || ($sVal === '' && $currentStatus === null);
                    $url = route('admin.restaurants.index', array_filter(array_merge(request()->except(['page']), ['is_open' => $sVal !== '' ? $sVal : null])));
                @endphp
                <a href="{{ $url }}" class="btn btn-sm rounded-pill fw-bold px-3 py-1.5 d-inline-flex align-items-center gap-1.5 {{ $isActive ? 'btn-primary' : 'btn-light text-secondary' }}">
                    <span>{{ $sData['label'] }}</span>
                    <span class="badge rounded-pill {{ $isActive ? 'bg-white text-dark' : 'bg-secondary-subtle text-secondary' }}" style="font-size: 0.68rem;">
                        {{ $sData['count'] }}
                    </span>
                </a>
            @endforeach
        </div>

        {{-- Search & Action --}}
        <div class="d-flex gap-2 align-items-center flex-wrap">
            <form class="d-flex gap-2 align-items-center" method="GET" action="{{ route('admin.restaurants.index') }}">
                @if(request()->has('is_open') && request('is_open') !== '')
                    <input type="hidden" name="is_open" value="{{ request('is_open') }}">
                @endif
                <div class="input-group input-group-sm rounded-pill overflow-hidden border shadow-sm" style="min-width: 240px; background: rgba(255,255,255,0.95);">
                    <span class="input-group-text border-0 bg-transparent ps-3 text-muted">
                        <i class="bi bi-search text-primary"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control border-0 ps-1" placeholder="Cari resto, alamat, owner...">
                    @if(request('search'))
                        <a href="{{ route('admin.restaurants.index', array_filter(['is_open' => request('is_open')])) }}" class="input-group-text border-0 bg-transparent pe-3 text-muted" title="Hapus pencarian">
                            <i class="bi bi-x-circle-fill"></i>
                        </a>
                    @endif
                </div>
                <button type="submit" class="btn btn-primary btn-sm px-3 rounded-pill fw-bold">
                    Cari
                </button>
                @if(request()->hasAny(['search', 'is_open']))
                    <a href="{{ route('admin.restaurants.index') }}" class="btn btn-light btn-sm px-2.5 rounded-pill text-muted" title="Reset Semua Filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                @endif
            </form>

            <a href="{{ route('admin.restaurants.create') }}" class="btn btn-primary btn-sm px-3.5 py-2 rounded-pill fw-bold d-inline-flex align-items-center gap-1.5 shadow-sm">
                <i class="bi bi-plus-circle-fill"></i>
                <span>Tambah Restoran</span>
            </a>
        </div>
    </div>
</div>

{{-- Restaurant Table --}}
<div class="card border-0 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th class="py-3 px-4">#</th>
                    <th class="py-3">Restoran</th>
                    <th class="py-3">Pemilik (Owner)</th>
                    <th class="py-3">Telepon</th>
                    <th class="py-3">Alamat</th>
                    <th class="py-3">Status Operasional</th>
                    <th class="py-3 px-4 text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($restaurants as $resto)
                <tr>
                    <td class="px-4 text-muted small">
                        {{ $restaurants->firstItem() + $loop->index }}
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-3 overflow-hidden d-flex align-items-center justify-content-center border flex-shrink-0" style="width: 48px; height: 48px; background: rgba(241, 245, 249, 0.9);">
                                @if($resto->image)
                                    <img src="{{ asset('storage/' . $resto->image) }}" class="w-100 h-100 object-fit-cover" alt="{{ $resto->name }}">
                                @else
                                    <i class="bi bi-shop text-muted fs-4"></i>
                                @endif
                            </div>
                            <div class="min-width-0">
                                <div class="fw-bold text-dark text-truncate" style="max-width: 200px;">{{ $resto->name }}</div>
                                <small class="text-muted text-truncate d-block" style="font-size: 0.72rem;">ID: #{{ $resto->id }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="fw-semibold text-dark">{{ $resto->user->name ?? '-' }}</div>
                        <small class="text-muted" style="font-size: 0.72rem;">{{ $resto->user->email ?? '-' }}</small>
                    </td>
                    <td class="small text-muted">
                        {{ $resto->phone ?: '-' }}
                    </td>
                    <td class="small text-muted" style="max-width: 220px;">
                        <span class="text-truncate d-block">{{ $resto->address }}</span>
                    </td>
                    <td>
                        @if($resto->is_open)
                            <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-3 py-1.5 small fw-bold d-inline-flex align-items-center gap-1">
                                <i class="bi bi-circle-fill" style="font-size: 0.45rem;"></i>Buka
                            </span>
                        @else
                            <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger-subtle px-3 py-1.5 small fw-bold d-inline-flex align-items-center gap-1">
                                <i class="bi bi-circle-fill" style="font-size: 0.45rem;"></i>Tutup
                            </span>
                        @endif
                    </td>
                    <td class="px-4 text-end">
                        <div class="d-inline-flex align-items-center gap-1.5">
                            <a href="{{ route('admin.restaurants.edit', $resto) }}" class="btn btn-light btn-sm px-2.5 py-1.5 rounded-pill" title="Edit Restoran">
                                <i class="bi bi-pencil-square text-primary"></i>
                            </a>
                            <form action="{{ route('admin.restaurants.destroy', $resto) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus restoran {{ $resto->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-light btn-sm px-2.5 py-1.5 rounded-pill text-danger" title="Hapus Restoran">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 56px; height: 56px; background: rgba(241, 245, 249, 0.9); color: #94a3b8;">
                            <i class="bi bi-shop fs-2"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Restoran Tidak Ditemukan</h6>
                        <p class="small text-muted mb-0">Klik tombol Tambah Restoran untuk mendaftarkan mitra kuliner baru.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($restaurants->hasPages())
    <div class="card-footer bg-transparent py-3 px-4 border-top d-flex justify-content-center">
        {{ $restaurants->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
