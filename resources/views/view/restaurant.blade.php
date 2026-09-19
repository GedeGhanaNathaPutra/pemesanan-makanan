@extends('layouts.app')
@section('title', 'Jelajahi Restoran Mitra')
@section('content')
<div class="container-fluid px-3 px-md-4 px-lg-5 py-4">
    {{-- Header & Search Bar (Liquid Glass Container) --}}
    <div class="card p-4 border-0 rounded-4 mb-4">
        <div class="row align-items-center g-3">
            <div class="col-md-6">
                <span class="text-primary fw-bold text-uppercase small" style="letter-spacing: 1px;">Katalog Kuliner</span>
                <h3 class="fw-extrabold mb-1 text-dark">Daftar Restoran Mitra</h3>
                <p class="text-muted small mb-0">
                    Menampilkan <strong class="text-dark">{{ $restaurants->total() }}</strong> restoran pilihan siap melayani pesanan Anda
                </p>
            </div>
            <div class="col-md-6">
                <form action="{{ route('restaurants') }}" method="GET" class="d-flex gap-2">
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    @if(request('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif
                    <div class="input-group">
                        <span class="input-group-text border-0 text-muted ps-3" style="background: rgba(255, 255, 255, 0.75); border-radius: 1rem 0 0 1rem; border: 1px solid rgba(255, 255, 255, 0.85); border-right: none;">
                            <i class="bi bi-search text-primary"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control border-0 ps-1 fw-semibold" style="border-radius: 0 1rem 1rem 0; border: 1px solid rgba(255, 255, 255, 0.85); border-left: none; color: #0f172a;" placeholder="Cari nama restoran atau alamat...">
                    </div>
                    <button type="submit" class="btn btn-primary px-3.5 rounded-pill fw-bold flex-shrink-0">
                        Cari
                    </button>
                    @if(request('search'))
                        <a href="{{ route('restaurants', array_filter(['category' => request('category'), 'sort' => request('sort')])) }}" class="btn btn-light px-3 rounded-pill" title="Reset Pencarian">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </form>
            </div>
        </div>

        {{-- Categories Filter Chips & Sorting Bar --}}
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 pt-3 mt-3 border-top">
            {{-- Category Filter Chips (Horizontal Scrollable) --}}
            <div class="d-flex gap-2 overflow-x-auto pb-1 pb-md-0 text-nowrap" style="scrollbar-width: thin;">
                <a href="{{ route('restaurants', array_filter(['search' => request('search'), 'sort' => request('sort')])) }}"
                   class="btn btn-sm rounded-pill fw-bold px-3 py-1.5 {{ !request('category') ? 'btn-primary shadow-sm' : 'btn-light text-secondary' }}"
                   style="font-size: 0.82rem;">
                    Semua Kategori
                </a>
                @if(isset($categories))
                    @foreach($categories as $cat)
                        <a href="{{ route('restaurants', array_merge(request()->query(), ['category' => $cat->id, 'page' => 1])) }}"
                           class="btn btn-sm rounded-pill fw-bold px-3 py-1.5 {{ request('category') == $cat->id ? 'btn-primary shadow-sm' : 'btn-light text-secondary' }}"
                           style="font-size: 0.82rem;">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                @endif
            </div>

            {{-- Sorting Dropdown --}}
            <div class="d-flex align-items-center gap-2 flex-shrink-0">
                <span class="small text-muted fw-bold">Urutkan:</span>
                <div class="dropdown">
                    <button class="btn btn-light btn-sm rounded-pill px-3 py-1.5 fw-bold dropdown-toggle d-flex align-items-center gap-1.5" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 0.82rem;">
                        @if(request('sort') === 'rating')
                            <i class="bi bi-star-fill text-warning"></i> Rating Tertinggi
                        @elseif(request('sort') === 'popular')
                            <i class="bi bi-fire text-danger"></i> Menu Terbanyak
                        @else
                            <i class="bi bi-clock text-primary"></i> Terbaru
                        @endif
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end border-0 rounded-4 shadow-sm p-2" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(14px);">
                        <li>
                            <a class="dropdown-item rounded-3 py-2 small fw-bold {{ !request('sort') ? 'active' : '' }}" href="{{ route('restaurants', array_merge(request()->query(), ['sort' => null, 'page' => 1])) }}">
                                <i class="bi bi-clock me-1.5 text-primary"></i>Terbaru
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item rounded-3 py-2 small fw-bold {{ request('sort') === 'rating' ? 'active' : '' }}" href="{{ route('restaurants', array_merge(request()->query(), ['sort' => 'rating', 'page' => 1])) }}">
                                <i class="bi bi-star-fill me-1.5 text-warning"></i>Rating Tertinggi
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item rounded-3 py-2 small fw-bold {{ request('sort') === 'popular' ? 'active' : '' }}" href="{{ route('restaurants', array_merge(request()->query(), ['sort' => 'popular', 'page' => 1])) }}">
                                <i class="bi bi-fire me-1.5 text-danger"></i>Menu Terbanyak
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Resto Grid --}}
    @if($restaurants->isEmpty())
        <div class="card p-5 text-center border-0 rounded-4 my-4">
            <div class="category-icon-glass mx-auto mb-3" style="width: 80px; height: 80px; background: rgba(241,245,249,0.85); color: #64748b;">
                <i class="bi bi-shop-window fs-1"></i>
            </div>
            <h5 class="fw-bold text-dark">Tidak Ada Restoran Ditemukan</h5>
            <p class="text-muted small mb-3">Coba gunakan kata kunci pencarian yang lain atau reset filter pencarian Anda.</p>
            <div>
                <a href="{{ route('restaurants') }}" class="btn btn-primary btn-sm px-4 rounded-pill fw-bold">
                    Lihat Semua Restoran
                </a>
            </div>
        </div>
    @else
        <div class="row g-4 row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-4">
            @foreach($restaurants as $resto)
                <div class="col">
                    <div class="card h-100 card-hover border-0 overflow-hidden rounded-4 d-flex flex-column">
                        {{-- Cover Image with Ambient Scrim & Glass Badges --}}
                        <div class="position-relative overflow-hidden w-100" style="height: 210px;">
                            @if($resto->image)
                                <img src="{{ asset('storage/'.$resto->image) }}" class="w-100 h-100 object-fit-cover" alt="{{ $resto->name }}">
                            @else
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, rgba(255, 87, 34, 0.85), rgba(234, 88, 12, 0.9));">
                                    <i class="bi bi-shop-window text-white" style="font-size: 3.8rem; opacity: 0.95;"></i>
                                </div>
                            @endif

                            {{-- Ambient bottom scrim for badge contrast --}}
                            <div class="position-absolute inset-0 w-100 h-100" style="top:0; left:0; right:0; bottom:0; background: linear-gradient(to top, rgba(15, 23, 42, 0.55) 0%, transparent 60%); pointer-events: none;"></div>

                            {{-- Verified Mitra Pill (Top-Start) --}}
                            <span class="position-absolute top-0 start-0 m-3 badge rounded-pill px-2.5 py-1.5 small d-inline-flex align-items-center gap-1" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(14px); color: #0284c7; border: 1px solid rgba(255, 255, 255, 0.95); font-size: 0.7rem; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                                <i class="bi bi-patch-check-fill text-primary"></i>
                                <span>Mitra Resmi</span>
                            </span>

                            {{-- Status Badge (Top-End) --}}
                            <span class="position-absolute top-0 end-0 m-3 badge {{ $resto->is_open ? 'badge-selesai' : 'badge-pending' }} px-3 py-1.5 rounded-pill small">
                                <i class="bi bi-circle-fill me-1" style="font-size: 0.45rem;"></i>{{ $resto->is_open ? 'Buka Sekarang' : 'Tutup' }}
                            </span>

                            {{-- Bottom Badges: Rating & Estimated Time --}}
                            <div class="position-absolute bottom-0 start-0 end-0 m-3 d-flex justify-content-between align-items-center">
                                <span class="badge rounded-pill px-3 py-1.5 d-inline-flex align-items-center gap-1.5" style="background: rgba(255, 255, 255, 0.92); backdrop-filter: blur(16px); color: #0f172a; border: 1px solid rgba(255, 255, 255, 0.95); box-shadow: 0 6px 16px rgba(0,0,0,0.12);">
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <strong>{{ number_format($resto->average_rating, 1) }}</strong>
                                </span>
                                <span class="badge rounded-pill px-2.5 py-1.5 d-inline-flex align-items-center gap-1" style="background: rgba(255, 255, 255, 0.92); backdrop-filter: blur(14px); color: #0f172a; border: 1px solid rgba(255, 255, 255, 0.95); font-size: 0.72rem; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                                    <i class="bi bi-clock text-primary"></i>
                                    <span class="fw-semibold">15-25 min</span>
                                </span>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="card-body p-3.5 p-md-4 d-flex flex-column justify-content-between flex-grow-1">
                            <div>
                                <a href="{{ route('restaurants.show', $resto) }}" class="text-decoration-none">
                                    <h5 class="fw-extrabold text-dark mb-1 text-truncate hover-primary" title="{{ $resto->name }}">
                                        {{ $resto->name }}
                                    </h5>
                                </a>

                                <p class="small text-muted mb-2 text-truncate" style="font-size: 0.8rem;">
                                    <i class="bi bi-geo-alt me-1 text-danger"></i>{{ $resto->address }}
                                </p>

                                <p class="small text-secondary mb-3" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; font-size: 0.78rem; line-height: 1.45; min-height: 2.3em;">
                                    {{ $resto->description ?: 'Restoran mitra resmi dengan jaminan cita rasa hidangan otentik, higienis, dan segar.' }}
                                </p>

                                {{-- Features Pills --}}
                                <div class="d-flex flex-wrap gap-1.5 mb-3">
                                    <span class="badge rounded-pill px-2.5 py-1" style="background: rgba(255, 255, 255, 0.7); color: #475569; border: 1px solid rgba(255, 255, 255, 0.85); font-size: 0.72rem;">
                                        <i class="bi bi-box-seam text-primary me-1"></i>{{ $resto->products_count }} Menu Pilihan
                                    </span>
                                    <span class="badge rounded-pill px-2.5 py-1" style="background: rgba(255, 255, 255, 0.7); color: #475569; border: 1px solid rgba(255, 255, 255, 0.85); font-size: 0.72rem;">
                                        <i class="bi bi-truck text-success me-1"></i>Antar Cepat
                                    </span>
                                </div>
                            </div>

                            {{-- Footer: Contact / Status & Action Button --}}
                            <div class="pt-3 border-top d-flex align-items-center justify-content-between gap-2">
                                <div class="min-width-0">
                                    @if($resto->phone)
                                        <small class="text-muted text-truncate d-block" style="font-size: 0.75rem;">
                                            <i class="bi bi-telephone me-1 text-primary"></i>{{ $resto->phone }}
                                        </small>
                                    @else
                                        <small class="text-success fw-semibold" style="font-size: 0.75rem;">
                                            <i class="bi bi-shield-check me-1"></i>Terverifikasi
                                        </small>
                                    @endif
                                </div>

                                <a href="{{ route('restaurants.show', $resto) }}" class="btn btn-primary btn-sm px-3.5 py-1.5 rounded-pill fw-bold d-inline-flex align-items-center gap-1.5 flex-shrink-0" style="font-size: 0.82rem;" title="Lihat menu {{ $resto->name }}">
                                    <span>Lihat Menu</span>
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-5 d-flex justify-content-center">
            {{ $restaurants->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
