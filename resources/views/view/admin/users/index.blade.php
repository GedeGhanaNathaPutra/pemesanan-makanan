@extends('layouts.dashboard')
@section('title', 'Kelola Pengguna')
@section('page-title', 'Kelola Pengguna')
@section('content')

{{-- Quick Filter Pills & Toolbar --}}
<div class="card border-0 p-3.5 mb-4">
    <div class="d-flex flex-wrap gap-3 justify-content-between align-items-center">
        {{-- Quick Role Tabs --}}
        <div class="d-flex gap-1.5 flex-wrap align-items-center">
            @php
                $currentRole = request('role');
                $roles = [
                    ''         => ['label' => 'Semua', 'count' => $roleCounts['all'] ?? 0],
                    'admin'    => ['label' => 'Admin', 'count' => $roleCounts['admin'] ?? 0],
                    'owner'    => ['label' => 'Owner', 'count' => $roleCounts['owner'] ?? 0],
                    'customer' => ['label' => 'Customer', 'count' => $roleCounts['customer'] ?? 0],
                ];
            @endphp
            @foreach($roles as $rVal => $rData)
                @php
                    $isActive = ($currentRole === $rVal) || ($rVal === '' && !$currentRole);
                    $url = route('admin.users.index', array_filter(array_merge(request()->except(['page']), ['role' => $rVal ?: null])));
                @endphp
                <a href="{{ $url }}" class="btn btn-sm rounded-pill fw-bold px-3 py-1.5 d-inline-flex align-items-center gap-1.5 {{ $isActive ? 'btn-primary' : 'btn-light text-secondary' }}">
                    <span>{{ $rData['label'] }}</span>
                    <span class="badge rounded-pill {{ $isActive ? 'bg-white text-dark' : 'bg-secondary-subtle text-secondary' }}" style="font-size: 0.68rem;">
                        {{ $rData['count'] }}
                    </span>
                </a>
            @endforeach
        </div>

        {{-- Search & Action --}}
        <div class="d-flex gap-2 align-items-center flex-wrap">
            <form class="d-flex gap-2 align-items-center" method="GET" action="{{ route('admin.users.index') }}">
                @if(request('role'))
                    <input type="hidden" name="role" value="{{ request('role') }}">
                @endif
                <div class="input-group input-group-sm rounded-pill overflow-hidden border shadow-sm" style="min-width: 240px; background: rgba(255,255,255,0.95);">
                    <span class="input-group-text border-0 bg-transparent ps-3 text-muted">
                        <i class="bi bi-search text-primary"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control border-0 ps-1" placeholder="Cari nama, email, hp...">
                    @if(request('search'))
                        <a href="{{ route('admin.users.index', array_filter(['role' => request('role')])) }}" class="input-group-text border-0 bg-transparent pe-3 text-muted" title="Hapus pencarian">
                            <i class="bi bi-x-circle-fill"></i>
                        </a>
                    @endif
                </div>
                <button type="submit" class="btn btn-primary btn-sm px-3 rounded-pill fw-bold">
                    Cari
                </button>
                @if(request()->hasAny(['search', 'role']))
                    <a href="{{ route('admin.users.index') }}" class="btn btn-light btn-sm px-2.5 rounded-pill text-muted" title="Reset Semua Filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                @endif
            </form>

            <button class="btn btn-primary btn-sm px-3.5 py-2 rounded-pill fw-bold d-inline-flex align-items-center gap-1.5 shadow-sm" data-bs-toggle="modal" data-bs-target="#createUserModal">
                <i class="bi bi-person-plus-fill"></i>
                <span>Tambah Pengguna</span>
            </button>
        </div>
    </div>
</div>

{{-- Users Table --}}
<div class="card border-0 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th class="py-3 px-4">#</th>
                    <th class="py-3">Pengguna</th>
                    <th class="py-3">Role</th>
                    <th class="py-3">Nomor Telepon</th>
                    <th class="py-3">Terdaftar Sejak</th>
                    <th class="py-3 px-4 text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td class="px-4 text-muted small">
                        {{ $users->firstItem() + $loop->index }}
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2.5">
                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px; background: rgba(255, 242, 237, 0.95); color: var(--liquid-primary); font-weight: 700; font-size: 0.85rem; box-shadow: 0 2px 6px rgba(234, 88, 12, 0.15);">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="min-width-0">
                                <div class="fw-bold text-dark text-truncate">{{ $user->name }}</div>
                                <small class="text-muted text-truncate d-block" style="font-size: 0.75rem;">{{ $user->email }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($user->role === 'admin')
                            <span class="badge rounded-pill bg-danger px-3 py-1.5 small fw-bold">Admin</span>
                        @elseif($user->role === 'owner')
                            <span class="badge rounded-pill bg-warning text-dark px-3 py-1.5 small fw-bold">Owner</span>
                        @else
                            <span class="badge rounded-pill bg-light text-secondary border px-3 py-1.5 small fw-semibold">Customer</span>
                        @endif
                    </td>
                    <td class="small text-muted">
                        {{ $user->phone ?: '-' }}
                    </td>
                    <td class="small text-muted">
                        {{ $user->created_at?->format('d M Y') ?: '-' }}
                    </td>
                    <td class="px-4 text-end">
                        <div class="d-inline-flex align-items-center gap-1.5">
                            <button class="btn btn-light btn-sm px-2.5 py-1.5 rounded-pill" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}" title="Edit Pengguna">
                                <i class="bi bi-pencil-square text-primary"></i>
                            </button>
                            @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $user->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-light btn-sm px-2.5 py-1.5 rounded-pill text-danger" title="Hapus Pengguna">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 56px; height: 56px; background: rgba(241, 245, 249, 0.9); color: #94a3b8;">
                            <i class="bi bi-people fs-2"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Pengguna Tidak Ditemukan</h6>
                        <p class="small text-muted mb-0">Coba ubah kata kunci pencarian atau role filter Anda.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div class="card-footer bg-transparent py-3 px-4 border-top d-flex justify-content-center">
        {{ $users->withQueryString()->links() }}
    </div>
    @endif
</div>

{{-- CREATE USER MODAL --}}
<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden" style="background: rgba(255, 255, 255, 0.96); backdrop-filter: blur(20px);">
            <div class="modal-header py-3 px-4 border-bottom" style="background: rgba(255, 242, 237, 0.5);">
                <h5 class="modal-title fw-extrabold text-dark d-flex align-items-center gap-2" id="createUserModalLabel">
                    <span class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: rgba(255, 242, 237, 0.95); color: var(--liquid-primary);">
                        <i class="bi bi-person-plus-fill fs-6"></i>
                    </span>
                    <span>Tambah Pengguna Baru</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Contoh: Budi Santoso" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Alamat Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="nama@email.com" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kata Sandi <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Peran (Role) <span class="text-danger">*</span></label>
                            <select name="role" class="form-select" required>
                                <option value="customer" selected>Customer (Pelanggan)</option>
                                <option value="owner">Owner (Mitra Restoran)</option>
                                <option value="admin">Administrator</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nomor Telepon / WhatsApp</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" placeholder="Contoh: 08123456789">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Alamat Pengiriman</label>
                            <textarea name="address" class="form-control" rows="1" placeholder="Alamat domisili / jalan">{{ old('address') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer py-3 px-4 border-top bg-light">
                    <button type="button" class="btn btn-light px-3.5 py-2 rounded-pill fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill fw-bold">
                        <i class="bi bi-check-lg me-1"></i>Simpan Pengguna
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- EDIT USER MODALS --}}
@foreach($users as $user)
<div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden" style="background: rgba(255, 255, 255, 0.96); backdrop-filter: blur(20px);">
            <div class="modal-header py-3 px-4 border-bottom" style="background: rgba(255, 242, 237, 0.5);">
                <h5 class="modal-title fw-extrabold text-dark d-flex align-items-center gap-2">
                    <span class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: rgba(255, 242, 237, 0.95); color: var(--liquid-primary);">
                        <i class="bi bi-pencil-square fs-6"></i>
                    </span>
                    <span>Edit Pengguna: {{ $user->name }}</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" value="{{ $user->name }}" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Alamat Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" value="{{ $user->email }}" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ganti Kata Sandi <span class="text-muted fw-normal">(opsional)</span></label>
                            <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah sandi">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Peran (Role) <span class="text-danger">*</span></label>
                            <select name="role" class="form-select" required>
                                <option value="customer" @selected($user->role==='customer')>Customer (Pelanggan)</option>
                                <option value="owner" @selected($user->role==='owner')>Owner (Mitra Restoran)</option>
                                <option value="admin" @selected($user->role==='admin')>Administrator</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nomor Telepon</label>
                            <input type="text" name="phone" value="{{ $user->phone }}" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Alamat Domisili</label>
                            <textarea name="address" class="form-control" rows="1">{{ $user->address }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer py-3 px-4 border-top bg-light">
                    <button type="button" class="btn btn-light px-3.5 py-2 rounded-pill fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill fw-bold">
                        <i class="bi bi-check-lg me-1"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection
