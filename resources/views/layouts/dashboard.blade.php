<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') – FoodOrder</title>

    <!-- Bootstrap 5.3 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --liquid-primary: #ff5722;
            --liquid-primary-dark: #ea580c;
            --liquid-primary-gradient: linear-gradient(135deg, #ff5722 0%, #ea580c 100%);
            --food-primary: #ea580c;
            --food-primary-hover: #c2410c;
            --food-bg: #f8fafc;
            --sidebar-width: 260px;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--food-bg);
            background-image:
                radial-gradient(at 0% 0%, rgba(255, 87, 34, 0.05) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(59, 130, 246, 0.04) 0px, transparent 50%),
                radial-gradient(at 50% 100%, rgba(245, 158, 11, 0.04) 0px, transparent 50%);
            background-attachment: fixed;
            color: #1e293b;
            -webkit-font-smoothing: antialiased;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        /* Liquid Glass Sidebar */
        #sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1050;
            transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            border-right: 1px solid rgba(226, 232, 240, 0.9);
            box-shadow: 4px 0 24px rgba(15, 23, 42, 0.03);
        }
        #sidebar .brand {
            color: #0f172a;
            font-weight: 800;
            font-size: 1.22rem;
            padding: 22px 20px 18px;
            display: flex;
            align-items: center;
            text-decoration: none;
            border-bottom: 1px solid rgba(226, 232, 240, 0.85);
            letter-spacing: -0.02em;
        }
        #sidebar .brand .brand-food {
            color: #0f172a !important;
        }
        #sidebar .brand .brand-order {
            color: var(--liquid-primary) !important;
        }
        #sidebar .nav-link {
            color: #475569;
            border-radius: 9999px;
            margin: 3px 12px;
            padding: 9px 16px;
            font-size: 0.88rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        #sidebar .nav-link:hover {
            color: var(--liquid-primary-dark);
            background: rgba(255, 242, 237, 0.85);
            transform: translateX(3px);
        }
        #sidebar .nav-link.active {
            color: #ffffff !important;
            background: var(--liquid-primary-gradient);
            font-weight: 700;
            box-shadow: 0 4px 14px rgba(234, 88, 12, 0.35);
        }
        #sidebar .nav-link i {
            width: 22px;
            font-size: 1.15rem;
            margin-right: 10px;
        }
        #sidebar .section-title {
            color: #94a3b8;
            font-size: 0.68rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            padding: 18px 24px 6px;
        }
        #sidebar .sidebar-footer {
            padding: 14px 12px;
            border-top: 1px solid rgba(226, 232, 240, 0.85);
            background: rgba(255, 255, 255, 0.6);
        }

        /* Main Workspace */
        #main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.25s ease;
        }
        #topbar {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.9);
            padding: 14px 24px;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
        }
        .content-wrap {
            padding: 24px 24px 48px;
            flex: 1;
        }

        /* Modern Cards & KPI Stat Cards */
        .card {
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.95);
            border-radius: 1rem;
            box-shadow: 0 1px 3px 0 rgba(15, 23, 42, 0.04), 0 1px 2px -1px rgba(15, 23, 42, 0.04);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
        }
        .card-hover:hover {
            transform: translateY(-2px);
            border-color: #cbd5e1;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08), 0 8px 10px -6px rgba(15, 23, 42, 0.04);
        }
        .p-3\.5 {
            padding: 1.25rem !important;
        }
        .stat-card {
            padding: 1.25rem 1.4rem;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            background: #ffffff;
        }
        .stat-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }
        .stat-label {
            font-size: 0.76rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            margin-bottom: 0.35rem;
            display: block;
        }
        .stat-value {
            font-size: 1.55rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.2;
            letter-spacing: -0.02em;
            margin-bottom: 0.3rem;
        }
        .stat-desc {
            font-size: 0.78rem;
            color: #64748b;
            margin-bottom: 0;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* Culinary Buttons */
        .btn {
            font-family: inherit;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .btn-primary {
            background: var(--liquid-primary-gradient);
            border: none;
            color: #ffffff;
            font-weight: 700;
            border-radius: 9999px;
            box-shadow: 0 4px 14px rgba(234, 88, 12, 0.28);
        }
        .btn-primary:hover, .btn-primary:focus, .btn-primary:active {
            background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%) !important;
            color: #ffffff !important;
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(234, 88, 12, 0.36) !important;
        }
        .btn-outline-primary {
            border: 1.5px solid var(--liquid-primary);
            color: var(--liquid-primary);
            font-weight: 700;
            border-radius: 9999px;
            background: transparent;
        }
        .btn-outline-primary:hover {
            background: var(--liquid-primary-gradient);
            border-color: transparent;
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(234, 88, 12, 0.25);
        }
        .btn-light {
            background: rgba(255, 255, 255, 0.92);
            border: 1px solid rgba(226, 232, 240, 0.95);
            color: #1e293b;
            font-weight: 600;
            border-radius: 9999px;
            box-shadow: 0 2px 6px rgba(15, 23, 42, 0.04);
        }
        .btn-light:hover {
            background: #ffffff;
            border-color: #cbd5e1;
            color: #0f172a;
            transform: translateY(-1px);
        }
        .text-primary { color: var(--liquid-primary) !important; }

        /* Status Badges */
        .badge-pending {
            background-color: rgba(254, 243, 199, 0.95) !important;
            color: #92400e !important;
            border: 1px solid #fde68a;
            font-weight: 700;
        }
        .badge-diproses {
            background-color: rgba(224, 242, 254, 0.95) !important;
            color: #0369a1 !important;
            border: 1px solid #bae6fd;
            font-weight: 700;
        }
        .badge-dikirim {
            background-color: rgba(204, 251, 241, 0.95) !important;
            color: #0f766e !important;
            border: 1px solid #99f6e4;
            font-weight: 700;
        }
        .badge-selesai {
            background-color: rgba(220, 252, 231, 0.95) !important;
            color: #166534 !important;
            border: 1px solid #bbf7d0;
            font-weight: 700;
        }
        .badge-dibatalkan {
            background-color: rgba(254, 226, 226, 0.95) !important;
            color: #991b1b !important;
            border: 1px solid #fecaca;
            font-weight: 700;
        }

        /* Modern Glass Tables */
        .table {
            --bs-table-bg: transparent;
            margin-bottom: 0;
        }
        .table thead th {
            background: rgba(248, 250, 252, 0.9);
            color: #475569;
            font-weight: 700;
            font-size: 0.76rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 12px 18px;
            border-bottom: 1px solid rgba(226, 232, 240, 0.95);
        }
        .table tbody td {
            border-bottom: 1px solid rgba(241, 245, 249, 0.95);
            vertical-align: middle;
            color: #1e293b;
            font-size: 0.875rem;
            padding: 14px 18px;
        }
        .table-hover tbody tr:hover td {
            background-color: rgba(255, 242, 237, 0.35);
        }

        /* Glass Form Controls */
        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(226, 232, 240, 0.95);
            border-radius: 0.85rem;
            color: #0f172a;
            font-size: 0.875rem;
            padding: 0.65rem 1rem;
            transition: all 0.2s ease;
        }
        .form-control:focus, .form-select:focus {
            background: #ffffff;
            border-color: #fdba74;
            box-shadow: 0 0 0 4px rgba(255, 87, 34, 0.12);
            color: #0f172a;
        }
        .form-label {
            font-weight: 700;
            font-size: 0.82rem;
            color: #334155;
            margin-bottom: 0.4rem;
        }

        /* Mobile Responsiveness */
        @media (max-width: 991.98px) {
            #sidebar {
                transform: translateX(-100%);
            }
            #sidebar.show {
                transform: translateX(0);
                box-shadow: 0 0 32px rgba(15, 23, 42, 0.25);
            }
            #main-wrapper {
                margin-left: 0;
            }
            .content-wrap {
                padding: 16px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- Sidebar overlay (mobile touch backdrop) --}}
<div id="sidebar-overlay" class="d-none position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-25" style="z-index: 1049; backdrop-filter: blur(4px);" onclick="closeSidebar()"></div>

{{-- SIDEBAR --}}
<aside id="sidebar">
    <a href="{{ route('home') }}" class="brand">
        <span class="d-inline-flex align-items-center justify-content-center rounded-3 p-1.5 me-2" style="background: rgba(255, 242, 237, 0.9); box-shadow: 0 2px 6px rgba(234, 88, 12, 0.15);">
            <i class="bi bi-bag-heart-fill fs-5 text-primary"></i>
        </span>
        <span class="brand-food text-dark">Food</span><span class="brand-order text-primary">Order</span>
    </a>

    @if(auth()->user()->isAdmin())
    <span class="section-title">Admin Management</span>
    <nav class="nav flex-column px-1 mt-1">
        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
            <i class="bi bi-speedometer2"></i>Dashboard
        </a>
        <a class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
            <i class="bi bi-people"></i>Kelola User
        </a>
        <a class="nav-link {{ request()->routeIs('admin.restaurants*') ? 'active' : '' }}" href="{{ route('admin.restaurants.index') }}">
            <i class="bi bi-shop"></i>Mitra Restoran
        </a>
        <a class="nav-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
            <i class="bi bi-tags"></i>Kategori Makanan
        </a>
        <a class="nav-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
            <i class="bi bi-receipt"></i>Semua Pesanan
        </a>
    </nav>
    @elseif(auth()->user()->isOwner())
    <span class="section-title">Owner Management</span>
    <nav class="nav flex-column px-1 mt-1">
        <a class="nav-link {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}" href="{{ route('owner.dashboard') }}">
            <i class="bi bi-speedometer2"></i>Dashboard
        </a>
        <a class="nav-link {{ request()->routeIs('owner.restaurant*') ? 'active' : '' }}" href="{{ route('owner.restaurant.index') }}">
            <i class="bi bi-shop"></i>Profil Restoran
        </a>
        <a class="nav-link {{ request()->routeIs('owner.products*') ? 'active' : '' }}" href="{{ route('owner.products.index') }}">
            <i class="bi bi-box-seam"></i>Menu Makanan
        </a>
        <a class="nav-link {{ request()->routeIs('owner.orders*') ? 'active' : '' }}" href="{{ route('owner.orders.index') }}">
            <i class="bi bi-receipt"></i>Pesanan Masuk
        </a>
    </nav>
    @endif

    <div class="sidebar-footer mt-auto">
        <a href="{{ route('home') }}" class="nav-link text-secondary mb-1">
            <i class="bi bi-house"></i>Halaman Utama
        </a>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="nav-link text-danger w-100 text-start bg-transparent border-0">
                <i class="bi bi-box-arrow-right"></i>Keluar
            </button>
        </form>
    </div>
</aside>

{{-- MAIN WRAPPER --}}
<div id="main-wrapper">
    {{-- TOPBAR --}}
    <div id="topbar" class="d-flex align-items-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm btn-light border d-lg-none p-2 d-inline-flex align-items-center" onclick="openSidebar()" aria-label="Buka Menu">
                <i class="bi bi-list fs-5"></i>
            </button>
            <nav aria-label="breadcrumb" class="d-none d-sm-block">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none fw-bold" style="color: var(--liquid-primary)">Home</a></li>
                    <li class="breadcrumb-item active text-muted fw-semibold">@yield('page-title', 'Dashboard')</li>
                </ol>
            </nav>
        </div>

        <div class="d-flex align-items-center gap-2">
            <div class="d-flex align-items-center gap-2.5 px-3 py-1.5 rounded-pill" style="background: rgba(255, 255, 255, 0.92); border: 1px solid rgba(226, 232, 240, 0.95); box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);">
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: rgba(255, 242, 237, 0.95); color: var(--liquid-primary);">
                    <i class="bi bi-person-fill fs-6"></i>
                </div>
                <div class="d-flex flex-column text-start">
                    <span class="small fw-bold text-dark lh-sm text-truncate" style="max-width: 140px;">{{ auth()->user()->name }}</span>
                    <span class="text-muted" style="font-size: 0.7rem;">
                        <span class="badge {{ auth()->user()->isAdmin() ? 'bg-danger' : 'bg-warning text-dark' }} rounded-pill px-2 py-0.5" style="font-size: 0.65rem; font-weight: 700;">
                            {{ ucfirst(auth()->user()->role) }}
                        </span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- FLASH MESSAGES --}}
    @if(session()->hasAny(['success', 'error', 'info', 'warning']))
    <div class="px-3 px-md-4 pt-3" id="flash-wrap">
        @foreach(['success' => 'check-circle-fill', 'error' => 'exclamation-circle-fill', 'info' => 'info-circle-fill', 'warning' => 'exclamation-triangle-fill'] as $type => $icon)
            @if(session($type))
            <div class="alert alert-{{ $type === 'error' ? 'danger' : $type }} alert-dismissible fade show d-flex align-items-center gap-2 border-0 shadow-sm rounded-4" role="alert" style="backdrop-filter: blur(12px);">
                <i class="bi bi-{{ $icon }} flex-shrink-0 fs-5"></i>
                <span class="small fw-semibold">{{ session($type) }}</span>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
        @endforeach
    </div>
    @endif

    {{-- CONTENT --}}
    <div class="content-wrap">
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function openSidebar() {
        document.getElementById('sidebar').classList.add('show');
        document.getElementById('sidebar-overlay').classList.remove('d-none');
    }
    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('show');
        document.getElementById('sidebar-overlay').classList.add('d-none');
    }
    setTimeout(() => {
        document.querySelectorAll('#flash-wrap .alert').forEach(el => {
            try { bootstrap.Alert.getOrCreateInstance(el).close(); } catch(e) {}
        });
    }, 4500);
</script>
@stack('scripts')
</body>
</html>
