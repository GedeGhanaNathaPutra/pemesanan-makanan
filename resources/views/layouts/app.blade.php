<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'FoodOrder') – Pesan Makanan Online</title>

    <!-- Bootstrap 5.3 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Google Fonts: Outfit (Crystalline Display) & Plus Jakarta Sans (Clean Liquid UI) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            /* Liquid Glass Palette Tokens (Clean Light Mode) */
            --liquid-primary: #ff5722;
            --liquid-primary-glow: rgba(255, 87, 34, 0.35);
            --liquid-primary-light: rgba(255, 87, 34, 0.08);
            --liquid-amber: #f59e0b;
            --liquid-emerald: #10b981;
            --liquid-sky: #0284c7;
            --liquid-dark: #0f172a;
            --liquid-slate: #475569;
            --liquid-muted: #64748b;

            /* Liquid Glass Surface Tokens (Pristine Light Mode) */
            --glass-bg: rgba(255, 255, 255, 0.82);
            --glass-bg-card: rgba(255, 255, 255, 0.86);
            --glass-bg-hover: rgba(255, 255, 255, 0.96);
            --glass-bg-subtle: rgba(255, 255, 255, 0.55);
            --glass-border: rgba(255, 255, 255, 0.95);
            --glass-border-subtle: rgba(226, 232, 240, 0.8);

            /* Liquid Glass Depth Shadows */
            --glass-shadow: 0 16px 36px -12px rgba(15, 23, 42, 0.06), 0 0 0 1px rgba(255, 255, 255, 0.95), inset 0 1px 2px rgba(255, 255, 255, 1);
            --glass-shadow-hover: 0 24px 48px -12px rgba(15, 23, 42, 0.1), 0 0 0 1px rgba(255, 255, 255, 1), inset 0 1px 2px rgba(255, 255, 255, 1);
            --glass-shadow-inset: inset 0 2px 4px rgba(15, 23, 42, 0.03), inset 0 1px 2px rgba(255, 255, 255, 0.9);
            --glass-btn-primary-shadow: 0 10px 24px -4px rgba(234, 88, 12, 0.38), inset 0 1px 2px rgba(255, 255, 255, 0.6);
            --glass-btn-light-shadow: 0 6px 18px -3px rgba(15, 23, 42, 0.05), inset 0 1px 2px rgba(255, 255, 255, 0.95);
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #f8fafc;
            background-image:
                radial-gradient(at 0% 0%, rgba(255, 87, 34, 0.08) 0px, transparent 45%),
                radial-gradient(at 100% 0%, rgba(2, 132, 199, 0.07) 0px, transparent 45%),
                radial-gradient(at 50% 50%, rgba(245, 158, 11, 0.05) 0px, transparent 45%),
                radial-gradient(at 100% 100%, rgba(16, 185, 129, 0.07) 0px, transparent 45%);
            background-attachment: fixed;
            color: #0f172a;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        /* Ambient Liquid Glow Orbs (Fluid Refraction) */
        .liquid-ambient-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }
        .liquid-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.45;
            animation: liquidDrift 18s ease-in-out infinite alternate;
        }
        .orb-1 {
            width: 480px;
            height: 480px;
            background: linear-gradient(135deg, #ff5722, #f59e0b);
            top: -120px;
            right: 5%;
            animation-duration: 22s;
        }
        .orb-2 {
            width: 420px;
            height: 420px;
            background: linear-gradient(135deg, #0284c7, #38bdf8);
            bottom: 5%;
            left: -80px;
            animation-duration: 26s;
            animation-delay: -5s;
        }
        .orb-3 {
            width: 360px;
            height: 360px;
            background: linear-gradient(135deg, #10b981, #34d399);
            top: 45%;
            right: 15%;
            animation-duration: 20s;
            animation-delay: -10s;
        }
        @keyframes liquidDrift {
            0% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(30px, -20px) scale(1.08); }
            100% { transform: translate(-25px, 25px) scale(0.95); }
        }

        /* Typography */
        h1, h2, h3, h4, h5, h6,
        .navbar-brand,
        .btn,
        .fw-extrabold,
        .badge,
        .category-tile h6 {
            font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
            letter-spacing: -0.015em;
        }

        /* Liquid Glass Full-Width Navbar */
        .navbar-glass {
            background: rgba(255, 255, 255, 0.86);
            backdrop-filter: blur(24px) saturate(190%);
            -webkit-backdrop-filter: blur(24px) saturate(190%);
            border-bottom: 1px solid rgba(226, 232, 240, 0.9);
            border-top: none;
            border-left: none;
            border-right: none;
            border-radius: 0;
            padding: 0.8rem 0 !important;
            box-shadow: 0 4px 20px -5px rgba(15, 23, 42, 0.05), inset 0 1px 0 rgba(255, 255, 255, 0.95);
            transition: all 0.3s ease;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.35rem;
            color: #0f172a !important;
            letter-spacing: -0.02em;
            text-decoration: none;
        }
        .navbar-brand .brand-food {
            color: #0f172a !important;
        }
        .navbar-brand .brand-order {
            color: var(--liquid-primary) !important;
        }

        .nav-link {
            font-size: 0.92rem;
            font-weight: 600;
            color: #475569 !important;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            padding: 0.5rem 1.1rem !important;
            border-radius: 9999px;
        }
        .nav-link:hover {
            color: var(--liquid-primary) !important;
            background: rgba(255, 87, 34, 0.08);
            transform: translateY(-1px);
        }
        .nav-link.active-link {
            color: #ffffff !important;
            background: linear-gradient(135deg, #ff5722 0%, #ea580c 100%);
            box-shadow: 0 6px 18px -2px rgba(234, 88, 12, 0.4), inset 0 1px 1px rgba(255, 255, 255, 0.5);
            font-weight: 700;
        }

        /* Liquid Glass Navbar Cart Button */
        .navbar-cart-btn {
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(226, 232, 240, 0.95);
            border-radius: 9999px;
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.05), inset 0 1px 1px #ffffff;
            color: #1e293b;
            font-size: 0.875rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .navbar-cart-btn:hover {
            background: #ffffff;
            border-color: #fdba74;
            color: var(--liquid-primary);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px -3px rgba(234, 88, 12, 0.22), inset 0 1px 1px #ffffff;
        }
        .navbar-cart-btn.active-cart {
            background: rgba(255, 242, 237, 0.96);
            border-color: rgba(255, 87, 34, 0.6);
            color: var(--liquid-primary);
            box-shadow: 0 4px 16px rgba(234, 88, 12, 0.2), inset 0 1px 1px #ffffff;
        }
        .navbar-cart-icon-wrapper {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 242, 237, 0.95);
            color: var(--liquid-primary);
            box-shadow: 0 2px 6px rgba(234, 88, 12, 0.15);
            transition: transform 0.2s ease;
        }
        .navbar-cart-btn:hover .navbar-cart-icon-wrapper {
            transform: scale(1.08);
        }
        .navbar-cart-badge {
            background: linear-gradient(135deg, #ff5722 0%, #ea580c 100%);
            color: #ffffff;
            font-size: 0.7rem;
            font-weight: 800;
            padding: 0.18rem 0.55rem;
            border-radius: 9999px;
            box-shadow: 0 2px 8px rgba(234, 88, 12, 0.45);
            line-height: 1.1;
            letter-spacing: 0.2px;
        }

        /* Liquid Glass Buttons */
        .btn {
            border-radius: 9999px;
            font-weight: 700;
            letter-spacing: 0.01em;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
        }
        .btn:active {
            transform: translateY(1px) scale(0.98);
        }

        .btn-primary {
            background: linear-gradient(135deg, #ff5722 0%, #ea580c 100%);
            border: 1px solid rgba(255, 255, 255, 0.4);
            color: #ffffff;
            box-shadow: var(--glass-btn-primary-shadow);
            padding: 0.6rem 1.4rem;
        }
        .btn-primary:hover, .btn-primary:focus {
            background: linear-gradient(135deg, #ff6b3d 0%, #f4511e 100%) !important;
            border-color: rgba(255, 255, 255, 0.6) !important;
            color: #ffffff !important;
            transform: translateY(-2px);
            box-shadow: 0 14px 28px -4px rgba(234, 88, 12, 0.5), inset 0 1px 2px rgba(255, 255, 255, 0.7);
        }

        .btn-outline-primary {
            color: var(--liquid-primary);
            background: rgba(255, 255, 255, 0.65);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 87, 34, 0.35);
            box-shadow: var(--glass-btn-light-shadow);
            padding: 0.55rem 1.25rem;
        }
        .btn-outline-primary:hover, .btn-outline-primary:focus {
            background: rgba(255, 87, 34, 0.1);
            border-color: var(--liquid-primary);
            color: var(--liquid-primary);
            transform: translateY(-2px);
            box-shadow: 0 10px 22px -4px rgba(234, 88, 12, 0.25);
        }

        .btn-light {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            color: var(--liquid-dark);
            border: 1px solid rgba(255, 255, 255, 0.85);
            box-shadow: var(--glass-btn-light-shadow);
            padding: 0.55rem 1.25rem;
        }
        .btn-light:hover {
            background: rgba(255, 255, 255, 0.92);
            transform: translateY(-2px);
            box-shadow: 0 12px 24px -4px rgba(15, 23, 42, 0.1);
        }

        .text-primary { color: var(--liquid-primary) !important; }
        .bg-primary { background-color: var(--liquid-primary) !important; }
        .hover-primary { transition: color 0.2s ease; }
        .hover-primary:hover { color: var(--liquid-primary) !important; }

        /* Modern Cards & KPI Stat Cards */
        .p-3\.5 {
            padding: 1.25rem !important;
        }
        .stat-card {
            padding: 1.25rem 1.4rem;
            border: 1px solid rgba(226, 232, 240, 0.95);
            border-radius: 1rem;
            background: #ffffff;
            box-shadow: 0 1px 3px 0 rgba(15, 23, 42, 0.04), 0 1px 2px -1px rgba(15, 23, 42, 0.04);
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

        /* Liquid Glass Cards */
        .card {
            border: 1px solid var(--glass-border);
            border-radius: 1.5rem;
            background: var(--glass-bg-card);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            box-shadow: var(--glass-shadow);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .card-hover:hover {
            transform: translateY(-5px);
            background: var(--glass-bg-hover);
            box-shadow: var(--glass-shadow-hover);
            border-color: rgba(255, 255, 255, 0.95);
        }
        .card-img-top {
            object-fit: cover;
            border-radius: 1.4rem 1.4rem 0 0;
        }

        /* Liquid Glass Hero Showcase */
        .hero-section {
            background: linear-gradient(135deg, rgba(249, 87, 34, 0.94) 0%, rgba(234, 88, 12, 0.95) 50%, rgba(194, 65, 12, 0.92) 100%);
            backdrop-filter: blur(24px) saturate(190%);
            -webkit-backdrop-filter: blur(24px) saturate(190%);
            border-radius: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 24px 50px -10px rgba(234, 88, 12, 0.38), inset 0 1px 2px rgba(255, 255, 255, 0.6);
            margin: 1.5rem 0;
        }

        /* Frosted Glass Form Controls */
        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.68);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.75);
            border-radius: 1rem;
            box-shadow: var(--glass-shadow-inset);
            padding: 0.65rem 1rem;
            font-weight: 500;
            color: #0f172a;
            transition: all 0.25s ease;
        }
        .form-control::placeholder {
            color: #64748b;
            opacity: 0.9;
        }
        .form-control:focus, .form-select:focus {
            background: rgba(255, 255, 255, 0.95);
            border-color: rgba(255, 87, 34, 0.5);
            box-shadow: 0 0 0 4px rgba(255, 87, 34, 0.15), 0 8px 20px -4px rgba(234, 88, 12, 0.15);
        }

        .input-group-text {
            border: 1px solid rgba(255, 255, 255, 0.75);
            background: rgba(255, 255, 255, 0.68);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }

        /* Frosted Glass Status Badges */
        .badge {
            font-weight: 700;
            letter-spacing: 0.02em;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04), inset 0 1px 1px rgba(255, 255, 255, 0.8);
        }
        .badge-pending {
            background: rgba(254, 243, 199, 0.85) !important;
            color: #92400e !important;
            border: 1px solid rgba(252, 211, 77, 0.7);
        }
        .badge-diproses {
            background: rgba(224, 242, 254, 0.85) !important;
            color: #0369a1 !important;
            border: 1px solid rgba(125, 211, 252, 0.7);
        }
        .badge-dikirim {
            background: rgba(204, 251, 241, 0.85) !important;
            color: #0f766e !important;
            border: 1px solid rgba(94, 234, 212, 0.7);
        }
        .badge-selesai {
            background: rgba(220, 252, 231, 0.85) !important;
            color: #166534 !important;
            border: 1px solid rgba(134, 239, 172, 0.7);
        }
        .badge-dibatalkan {
            background: rgba(254, 226, 226, 0.85) !important;
            color: #991b1b !important;
            border: 1px solid rgba(252, 165, 165, 0.7);
        }

        /* Liquid Glass Category Tiles */
        .category-tile {
            border-radius: 1.5rem;
            background: rgba(255, 255, 255, 0.72);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.85);
            box-shadow: var(--glass-shadow);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            padding: 1.25rem;
        }
        .category-tile:hover {
            transform: translateY(-6px);
            background: rgba(255, 255, 255, 0.9);
            box-shadow: var(--glass-shadow-hover);
            border-color: rgba(255, 255, 255, 0.95);
        }
        .category-icon-glass {
            width: 58px;
            height: 58px;
            border-radius: 1.25rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 20px -3px rgba(0,0,0,0.08), inset 0 1px 2px rgba(255,255,255,0.8);
            border: 1px solid rgba(255, 255, 255, 0.65);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }

        /* Image hover zoom */
        .card-hover .object-fit-cover {
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .card-hover:hover .object-fit-cover {
            transform: scale(1.06);
        }

        /* Liquid Radio Card Selector */
        .payment-radio-card {
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 1.25rem;
            padding: 1rem;
            cursor: pointer;
            transition: all 0.25s ease;
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            box-shadow: var(--glass-btn-light-shadow);
        }
        .payment-radio-card:hover {
            border-color: #fdba74;
            transform: translateY(-2px);
            background: rgba(255, 255, 255, 0.9);
        }
        .btn-check:checked + .payment-radio-card {
            border-color: var(--liquid-primary);
            background: rgba(255, 242, 237, 0.88);
            box-shadow: 0 8px 20px -2px rgba(234, 88, 12, 0.25), inset 0 1px 2px rgba(255, 255, 255, 0.9);
        }

        /* Floating Cart for Mobile (Light Glass) */
        .mobile-floating-cart {
            position: fixed;
            bottom: 1.25rem;
            left: 1rem;
            right: 1rem;
            z-index: 1040;
            max-width: 540px;
            margin: 0 auto;
            border-radius: 1.5rem;
            background: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(24px) saturate(190%);
            -webkit-backdrop-filter: blur(24px) saturate(190%);
            border: 1px solid rgba(255, 255, 255, 0.95);
            box-shadow: 0 20px 48px -10px rgba(15, 23, 42, 0.16), inset 0 1px 2px rgba(255, 255, 255, 1);
            color: #0f172a;
        }

        /* Glass Modals */
        .modal-backdrop {
            z-index: 1050 !important;
        }
        .modal {
            z-index: 1055 !important;
        }
        .modal-content {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(28px) saturate(190%);
            -webkit-backdrop-filter: blur(28px) saturate(190%);
            border: 1px solid rgba(255, 255, 255, 0.95);
            box-shadow: 0 30px 60px -15px rgba(15, 23, 42, 0.16), inset 0 1px 2px rgba(255, 255, 255, 1);
        }

        /* Modern scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(148, 163, 184, 0.5);
            border-radius: 9999px;
            backdrop-filter: blur(4px);
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(100, 116, 139, 0.7);
        }

        /* Footer (Pristine Light Mode) */
        footer {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(24px) saturate(180%);
            -webkit-backdrop-filter: blur(24px) saturate(180%);
            color: #475569;
            border-top: 1px solid rgba(255, 255, 255, 0.95);
            box-shadow: 0 -12px 32px rgba(15, 23, 42, 0.03);
            position: relative;
            z-index: 1;
        }
        footer hr {
            border-color: rgba(226, 232, 240, 0.8);
        }
        footer .footer-text {
            color: #64748b;
        }
        footer .footer-link {
            color: #475569;
            text-decoration: none;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
        }
        footer .footer-link:hover {
            color: var(--liquid-primary);
            transform: translateX(3px);
        }
        footer .footer-badge {
            background: rgba(241, 245, 249, 0.9);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            color: #334155;
            border: 1px solid rgba(203, 213, 225, 0.7);
            font-size: 0.72rem;
            font-weight: 600;
            padding: 0.35rem 0.65rem;
            border-radius: 0.5rem;
        }
        footer .footer-social-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(226, 232, 240, 0.9);
            color: var(--liquid-primary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            text-decoration: none;
            box-shadow: 0 4px 10px rgba(15, 23, 42, 0.04);
        }
        footer .footer-social-btn:hover {
            background: var(--liquid-primary);
            color: #ffffff;
            border-color: var(--liquid-primary);
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(234, 88, 12, 0.35);
        }

        /* Touch targets */
        @media (max-width: 767.98px) {
            .btn, .nav-link, .dropdown-item {
                min-height: 42px;
                display: inline-flex;
                align-items: center;
            }
        }
    </style>
    @stack('styles')
</head>
<body class="d-flex flex-column min-vh-100 position-relative">

{{-- AMBIENT LIQUID GLASS ORBS (Background Refraction Depth) --}}
<div class="liquid-ambient-container" aria-hidden="true">
    <div class="liquid-orb orb-1"></div>
    <div class="liquid-orb orb-2"></div>
    <div class="liquid-orb orb-3"></div>
</div>

{{-- FULL-WIDTH LIQUID GLASS NAVBAR (Edge-to-Edge Desktop) --}}
<div class="sticky-top w-100" style="z-index: 1030;">
    <nav class="navbar navbar-expand-lg navbar-light navbar-glass w-100">
        <div class="container-fluid px-3 px-md-4 px-lg-5">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
                <span class="d-inline-flex align-items-center justify-content-center rounded-circle p-2" style="background: linear-gradient(135deg, #ff5722, #ea580c); box-shadow: 0 6px 14px rgba(234, 88, 12, 0.35), inset 0 1px 1px rgba(255,255,255,0.6);">
                    <i class="bi bi-bag-heart-fill fs-5 text-white"></i>
                </span>
                <span class="d-inline-flex align-items-center"><span class="brand-food text-dark">Food</span><span class="brand-order text-primary">Order</span></span>
            </a>

            <!-- Mobile Action Buttons -->
            <div class="d-flex align-items-center gap-2 d-lg-none ms-auto me-2">
                @php
                    $isCustomerOrGuest = !auth()->check() || (auth()->check() && auth()->user()->isCustomer());
                    $cartUrl = auth()->check() ? route('customer.cart.index') : route('login');
                    $cartCount = 0;
                    if (auth()->check() && auth()->user()->isCustomer()) {
                        $cartModel = auth()->user()->carts()->with('items')->first();
                        $cartCount = $cartModel?->items?->sum('quantity') ?? 0;
                    }
                @endphp
                @if($isCustomerOrGuest)
                    <a href="{{ $cartUrl }}" class="navbar-cart-btn position-relative d-inline-flex align-items-center justify-content-center p-2 {{ request()->routeIs('customer.cart.*') ? 'active-cart' : '' }}" aria-label="Keranjang Belanja" title="Keranjang Belanja">
                        <span class="navbar-cart-icon-wrapper" style="width: 32px; height: 32px;">
                            <i class="bi bi-cart3 fs-5"></i>
                        </span>
                        <span id="mobileNavCartBadge" class="position-absolute top-0 start-100 translate-middle navbar-cart-badge {{ $cartCount > 0 ? '' : 'd-none' }}">
                            {{ $cartCount }}
                        </span>
                    </a>
                @endif
            </div>

            <button class="navbar-toggler border-0 p-2" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="bi bi-list fs-4 text-dark"></i>
            </button>

            <div class="collapse navbar-collapse mt-3 mt-lg-0" id="mainNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 gap-1 ms-lg-3">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active-link' : '' }}" href="{{ route('home') }}">
                            <i class="bi bi-house me-1"></i>Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('restaurants*') ? 'active-link' : '' }}" href="{{ route('restaurants') }}">
                            <i class="bi bi-shop me-1"></i>Restoran
                        </a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-2 pt-2 pt-lg-0 border-top border-lg-0">
                    @if($isCustomerOrGuest)
                        <a href="{{ $cartUrl }}" class="navbar-cart-btn position-relative d-none d-lg-inline-flex align-items-center gap-2 px-3.5 py-2 {{ request()->routeIs('customer.cart.*') ? 'active-cart' : '' }}" title="Keranjang Belanja">
                            <span class="navbar-cart-icon-wrapper">
                                <i class="bi bi-cart3 fs-6"></i>
                            </span>
                            <span>Keranjang</span>
                            <span id="desktopNavCartBadge" class="navbar-cart-badge ms-0.5 {{ $cartCount > 0 ? '' : 'd-none' }}">{{ $cartCount }}</span>
                        </a>
                    @endif

                    @auth
                        <div class="dropdown w-100 w-lg-auto">
                            <button class="btn btn-light btn-sm dropdown-toggle w-100 d-flex align-items-center justify-content-between justify-content-lg-center gap-2 px-3 py-2" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="d-flex align-items-center gap-2">
                                    <i class="bi bi-person-circle fs-5" style="color: var(--liquid-primary)"></i>
                                    <span class="fw-semibold text-truncate" style="max-width: 140px;">{{ auth()->user()->name }}</span>
                                </span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end border-0 mt-2 py-2" style="border-radius: 1.25rem; background: rgba(255,255,255,0.92); backdrop-filter: blur(24px); border: 1px solid rgba(255,255,255,0.85); box-shadow: var(--glass-shadow-hover);">
                                <li class="px-3 py-1 text-muted small border-bottom mb-1">
                                    Signed in as <strong class="text-dark d-block text-truncate">{{ auth()->user()->email }}</strong>
                                </li>
                                @if(auth()->user()->isAdmin())
                                    <li><a class="dropdown-item py-2" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2" style="color: var(--liquid-primary)"></i>Admin Panel</a></li>
                                @elseif(auth()->user()->isOwner())
                                    <li><a class="dropdown-item py-2" href="{{ route('owner.dashboard') }}"><i class="bi bi-shop me-2" style="color: var(--liquid-primary)"></i>Owner Panel</a></li>
                                @else
                                    <li><a class="dropdown-item py-2" href="{{ route('customer.dashboard') }}"><i class="bi bi-grid me-2" style="color: var(--liquid-primary)"></i>Dashboard Saya</a></li>
                                    <li><a class="dropdown-item py-2" href="{{ route('customer.orders.index') }}"><i class="bi bi-bag-check me-2" style="color: var(--liquid-primary)"></i>Pesanan Saya</a></li>
                                    <li><a class="dropdown-item py-2" href="{{ route('customer.cart.index') }}"><i class="bi bi-cart3 me-2" style="color: var(--liquid-primary)"></i>Keranjang Belanja</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger py-2">
                                            <i class="bi bi-box-arrow-right me-2"></i>Keluar
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-light btn-sm px-3.5 py-2">
                            <i class="bi bi-box-arrow-in-right me-1"></i>Masuk
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-primary btn-sm px-3.5 py-2">
                            <i class="bi bi-person-plus me-1"></i>Daftar
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
</div>

{{-- MAIN CONTENT WRAPPER (Above ambient background) --}}
<main class="flex-grow-1">
    {{-- Toast Flash Alerts --}}
    @if(session('success') || session('error') || session('warning'))
    <div class="container-fluid px-3 px-md-4 px-lg-5 mt-3">
        @if(session('success'))
            <div class="alert alert-success d-flex align-items-center gap-2 border-0 rounded-4 py-3 px-4 shadow-sm" role="alert" style="background: rgba(220, 252, 231, 0.85); backdrop-filter: blur(16px); color: #166534; border: 1px solid rgba(134, 239, 172, 0.6);">
                <i class="bi bi-check-circle-fill fs-5"></i>
                <div class="fw-semibold">{{ session('success') }}</div>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger d-flex align-items-center gap-2 border-0 rounded-4 py-3 px-4 shadow-sm" role="alert" style="background: rgba(254, 226, 226, 0.85); backdrop-filter: blur(16px); color: #991b1b; border: 1px solid rgba(252, 165, 165, 0.6);">
                <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                <div class="fw-semibold">{{ session('error') }}</div>
            </div>
        @endif
        @if(session('warning'))
            <div class="alert alert-warning d-flex align-items-center gap-2 border-0 rounded-4 py-3 px-4 shadow-sm" role="alert" style="background: rgba(254, 243, 199, 0.85); backdrop-filter: blur(16px); color: #92400e; border: 1px solid rgba(252, 211, 77, 0.6);">
                <i class="bi bi-info-circle-fill fs-5"></i>
                <div class="fw-semibold">{{ session('warning') }}</div>
            </div>
        @endif
    </div>
    @endif

    @yield('content')
</main>

{{-- GLOBAL FLOATING CART BAR (Mobile & Desktop sticky bar for active cart) --}}
@auth
    @if(auth()->user()->isCustomer() && !request()->routeIs('customer.cart.*'))
        @php
            $globalCart = auth()->user()->carts()->with(['restaurant', 'items'])->first();
            $globalCartCount = $globalCart?->items?->sum('quantity') ?? 0;
            $globalCartTotal = $globalCart?->total ?? 0;
        @endphp
        <div id="globalFloatingCart" class="position-fixed bottom-0 start-50 translate-middle-x p-3 w-100 {{ $globalCartCount > 0 ? '' : 'd-none' }}" style="max-width: 560px; z-index: 1040;">
            <div class="card border-0 rounded-4 p-3 shadow-lg" style="background: rgba(255, 255, 255, 0.94); backdrop-filter: blur(24px) saturate(190%); border: 1px solid rgba(255, 255, 255, 0.95); box-shadow: 0 20px 48px -10px rgba(15, 23, 42, 0.16), inset 0 1px 2px rgba(255, 255, 255, 1);">
                <div class="d-flex align-items-center justify-content-between gap-3 text-dark">
                    <div class="d-flex align-items-center gap-3 min-width-0">
                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0 fw-bold fs-6" style="width: 44px; height: 44px; background: linear-gradient(135deg, #ff5722, #ea580c); color: #ffffff; box-shadow: 0 4px 12px rgba(234,88,12,0.4);">
                            <span id="floatingCartCountBadge">{{ $globalCartCount }}</span>
                        </div>
                        <div class="min-width-0">
                            <div class="small text-muted text-truncate" id="floatingCartRestoName">
                                {{ $globalCart?->restaurant?->name ?? 'Keranjang Belanja' }}
                            </div>
                            <div class="fw-extrabold fs-6" style="color: var(--liquid-primary)" id="floatingCartTotal">
                                Rp {{ number_format($globalCartTotal, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('customer.cart.index') }}" class="btn btn-primary btn-sm px-3.5 py-2 rounded-pill fw-bold d-inline-flex align-items-center gap-1.5 flex-shrink-0 shadow-sm">
                        <span>Checkout</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    @endif
@endauth

{{-- FOOTER (Clean Light Mode) --}}
<footer class="mt-5 py-5">
    <div class="container-fluid px-3 px-md-4 px-lg-5">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="rounded-circle d-inline-flex align-items-center justify-content-center p-2" style="background: linear-gradient(135deg, #ff5722, #ea580c); box-shadow: 0 4px 12px rgba(234,88,12,0.35);">
                        <i class="bi bi-bag-heart-fill fs-5 text-white"></i>
                    </span>
                    <span class="fs-4 fw-extrabold"><span class="brand-food text-dark">Food</span><span class="brand-order text-primary">Order</span></span>
                </div>
                <p class="small footer-text mb-3" style="line-height: 1.7; color: #64748b;">
                    Platform kurasi dan pemesanan makanan nusantara tercepat. Menghubungkan pecinta kuliner dengan mitra restoran terpercaya di seluruh Indonesia.
                </p>
                <div class="d-flex gap-2">
                    <a href="#" class="footer-social-btn" title="Instagram"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="footer-social-btn" title="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="footer-social-btn" title="Twitter / X"><i class="bi bi-twitter-x"></i></a>
                </div>
            </div>
            <div class="col-6 col-lg-2 offset-lg-1">
                <h6 class="fw-bold text-dark mb-3 small text-uppercase" style="letter-spacing: 1px;">Navigasi</h6>
                <ul class="list-unstyled small mb-0 d-flex flex-column gap-2.5">
                    <li><a href="{{ route('home') }}" class="footer-link"><i class="bi bi-chevron-right me-1 small opacity-50"></i>Beranda</a></li>
                    <li><a href="{{ route('restaurants') }}" class="footer-link"><i class="bi bi-chevron-right me-1 small opacity-50"></i>Daftar Restoran</a></li>
                    <li><a href="{{ route('customer.cart.index') }}" class="footer-link"><i class="bi bi-chevron-right me-1 small opacity-50"></i>Keranjang</a></li>
                </ul>
            </div>
            <div class="col-6 col-lg-2">
                <h6 class="fw-bold text-dark mb-3 small text-uppercase" style="letter-spacing: 1px;">Mitra</h6>
                <ul class="list-unstyled small mb-0 d-flex flex-column gap-2.5">
                    <li><a href="{{ route('login') }}" class="footer-link"><i class="bi bi-chevron-right me-1 small opacity-50"></i>Portal Mitra Restoran</a></li>
                    <li><a href="{{ route('register') }}" class="footer-link"><i class="bi bi-chevron-right me-1 small opacity-50"></i>Daftar Jadi Mitra</a></li>
                    <li><a href="{{ route('admin.dashboard') }}" class="footer-link"><i class="bi bi-chevron-right me-1 small opacity-50"></i>Administrator</a></li>
                </ul>
            </div>
            <div class="col-lg-3">
                <h6 class="fw-bold text-dark mb-3 small text-uppercase" style="letter-spacing: 1px;">Metode Pembayaran</h6>
                <div class="d-flex flex-wrap gap-1.5 mb-3">
                    <span class="footer-badge">BCA Virtual Account</span>
                    <span class="footer-badge">Mandiri VA</span>
                    <span class="footer-badge">QRIS Instan</span>
                    <span class="footer-badge">GoPay</span>
                    <span class="footer-badge">OVO</span>
                    <span class="footer-badge">COD (Tunai)</span>
                </div>
                <small class="text-muted" style="font-size: 0.75rem;">
                    <i class="bi bi-shield-lock-fill text-success me-1"></i>Transaksi terenkripsi aman 256-bit
                </small>
            </div>
        </div>
        <hr class="my-4">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 small text-muted">
            <span>&copy; {{ date('Y') }} <strong class="text-dark">FoodOrder Indonesia</strong>. Hak Cipta Dilindungi.</span>
            <span class="text-muted">Dibangun dengan Laravel 12 &amp; Liquid Glassmorphism</span>
        </div>
    </div>
</footer>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Ensure all modals attach to document.body to prevent stacking context & backdrop trapping bugs
    document.addEventListener('show.bs.modal', function (event) {
        const modal = event.target;
        if (modal && modal.parentElement !== document.body) {
            document.body.appendChild(modal);
        }
    });

    // Global cart sync helper across all customer views
    window.updateGlobalCart = function(count, total, restoName) {
        const mBadge = document.getElementById('mobileNavCartBadge');
        const dBadge = document.getElementById('desktopNavCartBadge');
        const fCart = document.getElementById('globalFloatingCart');
        const fCount = document.getElementById('floatingCartCountBadge');
        const fTotal = document.getElementById('floatingCartTotal');
        const fResto = document.getElementById('floatingCartRestoName');

        if (mBadge) {
            mBadge.textContent = count;
            mBadge.classList.toggle('d-none', count <= 0);
        }
        if (dBadge) {
            dBadge.textContent = count;
            dBadge.classList.toggle('d-none', count <= 0);
        }
        if (fCart) {
            if (count > 0) {
                fCart.classList.remove('d-none');
                if (fCount) fCount.textContent = count;
                if (fTotal) fTotal.textContent = 'Rp ' + Number(total).toLocaleString('id-ID');
                if (fResto && restoName) fResto.textContent = restoName;
            } else {
                fCart.classList.add('d-none');
            }
        }
    };
</script>
@stack('scripts')
</body>
</html>
