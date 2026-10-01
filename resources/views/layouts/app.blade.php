<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'TRB Auto Car Care') }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom Theme CSS -->
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}?v=2.0">
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* GitHub-style Navbar */
        .navbar-custom {
            background-color: #161b22 !important;
            border-bottom: 1px solid #30363d !important;
            padding-top: 0.4rem !important;
            padding-bottom: 0.4rem !important;
        }

        .navbar-custom .nav-link {
            color: #c9d1d9 !important;
            font-weight: 500;
            font-size: 0.95rem;
            transition: color 0.2s;
        }

        .navbar-custom .nav-link:hover, 
        .navbar-custom .nav-link.active {
            color: #ffffff !important;
        }

        .navbar-custom .navbar-brand {
            font-weight: 600;
            color: #ffffff !important;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
        }

        .logo-wrapper {
            background-color: #ffffff;
            border-radius: 50px;
            padding: 2px 8px;
            display: inline-flex;
            align-items: center;
            margin-right: 8px;
        }
        
        .logo-wrapper .text-brand {
            margin: 0 !important;
        }

        .github-search {
            background-color: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.16);
            border-radius: 50px;
            color: #ffffff;
            padding: 6px 16px;
            font-size: 0.88rem;
            width: 230px;
            transition: all 0.25s ease;
        }
        
        .github-search:focus {
            background-color: rgba(255, 255, 255, 0.12);
            border-color: #EC1F24;
            outline: none;
            box-shadow: 0 0 0 3px rgba(236, 31, 36, 0.3);
            color: #ffffff;
            width: 270px;
        }

        /* VIP Reward Navbar Chip Badge */
        .vip-reward-pill {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.18) 0%, rgba(217, 119, 6, 0.28) 100%);
            border: 1px solid rgba(245, 158, 11, 0.5);
            color: #fbbf24 !important;
            padding: 5px 12px;
            border-radius: 999px;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 0 15px rgba(245, 158, 11, 0.12);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .vip-reward-pill:hover {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.3) 0%, rgba(217, 119, 6, 0.45) 100%);
            border-color: #fbbf24;
            color: #ffffff !important;
            transform: translateY(-1.5px);
            box-shadow: 0 4px 18px rgba(245, 158, 11, 0.3);
        }
        .vip-reward-pill .pts-badge {
            background: #f59e0b;
            color: #09090b;
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.02em;
        }
        
        .github-search::placeholder {
            color: #8b949e;
        }

        .avatar-dropdown-toggle {
            padding: 0;
            background: transparent;
            border: none;
        }
        
        .avatar-dropdown-toggle::after {
            display: none;
        }
        
        .avatar-dropdown-toggle img {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: 1px solid #30363d;
        }

        .nav-icon-btn {
            color: #c9d1d9;
            text-decoration: none;
            transition: color 0.2s;
            display: flex;
            align-items: center;
        }
        
        .nav-icon-btn:hover {
            color: #ffffff;
        }

        /* ── Services Mega-Dropdown (SaaS Brand Tile Showcase) ── */
        .services-mega-menu {
            width: 680px !important;
            padding: 0 !important;
            background: #1c2128 !important;
            border: 1px solid #30363d !important;
            border-radius: 14px !important;
            box-shadow: 0 24px 64px rgba(0,0,0,0.7) !important;
            left: 0 !important;
            top: 100% !important;
            margin-top: 12px !important;
            transform: none !important;
            overflow: hidden;
        }

        .mega-cat-tab {
            padding: 10px 14px !important;
            border: 1px solid transparent;
            border-radius: 10px !important;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .mega-cat-tab:hover, .mega-cat-tab.active {
            background: rgba(236, 31, 36, 0.12);
            border-color: rgba(236, 31, 36, 0.3);
        }
        .mega-cat-tab .mega-tab-icon {
            background: #22272e;
            color: #8b949e;
            transition: all 0.2s ease;
        }
        .mega-cat-tab:hover .mega-tab-icon, .mega-cat-tab.active .mega-tab-icon {
            background: #EC1F24;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(236,31,36,0.4);
        }
        .mega-cat-tab:hover .mega-arrow, .mega-cat-tab.active .mega-arrow {
            color: #EC1F24 !important;
            opacity: 1 !important;
            transform: translateX(3px);
        }
        .mega-arrow {
            transition: all 0.2s ease;
        }

        .brand-tile-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .brand-tile-card:hover {
            background: rgba(236, 31, 36, 0.12);
            border-color: rgba(236, 31, 36, 0.4);
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.4);
        }
        .brand-tile-card:hover span {
            color: #ff4a4e !important;
        }

        /* Rewards nav link glow */
        .nav-rewards-link {
            color: #fbbf24 !important;
            position: relative;
        }
        .nav-rewards-link:hover {
            color: #fde68a !important;
        }

        /* Footer styling */
        .footer-custom {
            background-color: #33343C;
            color: rgba(255, 255, 255, 0.7);
        }

        .footer-custom h5 {
            color: #ffffff;
            font-weight: 700;
        }

        .footer-custom a {
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-custom a:hover {
            color: #EC1F24;
        }

        .footer-bottom {
            background-color: #1E1F24;
            color: rgba(255, 255, 255, 0.4);
            font-size: 0.85rem;
        }
        @media (max-width: 991.98px) {
            .services-mega-menu {
                width: 100% !important;
                min-width: 100% !important;
                position: static !important;
                transform: none !important;
                margin-top: 8px !important;
                box-shadow: none !important;
                max-height: 70vh;
                overflow-y: auto;
            }
            .services-mega-menu .row > [class*="col-"] {
                flex: 0 0 100% !important;
                max-width: 100% !important;
                width: 100% !important;
            }
            .services-mega-menu .border-end {
                border-right: 0 !important;
                border-bottom: 1px solid rgba(255,255,255,.1) !important;
            }
            #navbarContent .dropdown-menu {
                position: static !important;
                width: 100% !important;
                max-width: 100% !important;
                float: none;
            }
        }
        @media (max-width: 991.98px) {
            #navbarContent .dropdown-menu,
            .dropdown-menu[aria-labelledby="notificationDropdown"] {
                position: static !important;
                inset: auto !important;
                transform: none !important;
                width: 100% !important;
                max-width: 100% !important;
                float: none !important;
            }
        }

        /* Bootstrap 5.3 Dark Mode Tooltip & Popover Bug Fix */
        .tooltip {
            --bs-tooltip-bg: #111827 !important;
            --bs-tooltip-color: #ffffff !important;
            --bs-tooltip-opacity: 1 !important;
            z-index: 1080 !important;
        }
        .tooltip .tooltip-inner {
            background-color: #111827 !important;
            color: #ffffff !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.6) !important;
            padding: 8px 12px !important;
            border-radius: 8px !important;
            font-size: 0.8rem !important;
            font-weight: 500 !important;
        }
        .tooltip.bs-tooltip-top .tooltip-arrow::before,
        .tooltip.bs-tooltip-auto[data-popper-placement^="top"] .tooltip-arrow::before {
            border-top-color: #111827 !important;
        }
        .tooltip.bs-tooltip-bottom .tooltip-arrow::before,
        .tooltip.bs-tooltip-auto[data-popper-placement^="bottom"] .tooltip-arrow::before {
            border-bottom-color: #111827 !important;
        }
    </style>
    @yield('styles')
    <style>
        @media (max-width: 768px) {
            body { overflow-x: hidden; }
            img { max-width: 100%; height: auto; }
        }

        /* Mobile-Only Bottom Navigation Bar (Hidden on Desktop >= 992px) */
        @media (max-width: 991.98px) {
            body {
                padding-bottom: 78px !important;
            }
            .whatsapp-float {
                bottom: 88px !important;
                right: 20px !important;
                width: 52px !important;
                height: 52px !important;
                font-size: 26px !important;
            }

            .app-bottom-nav {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                background: rgba(255, 255, 255, 0.96);
                backdrop-filter: blur(16px);
                -webkit-backdrop-filter: blur(16px);
                border-top: 1px solid rgba(0, 0, 0, 0.08);
                box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.06);
                z-index: 1040;
                padding: 6px 8px env(safe-area-inset-bottom, 6px);
            }

            [data-bs-theme="dark"] .app-bottom-nav,
            body.landing-dark .app-bottom-nav {
                background: rgba(18, 22, 30, 0.96);
                border-top: 1px solid rgba(255, 255, 255, 0.1);
                box-shadow: 0 -4px 24px rgba(0, 0, 0, 0.35);
            }

            .bottom-nav-container {
                display: flex;
                justify-content: space-around;
                align-items: center;
                max-width: 500px;
                margin: 0 auto;
            }

            .bottom-nav-item {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                text-decoration: none;
                color: #64748B;
                padding: 4px 6px;
                border-radius: 12px;
                flex: 1;
                transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            }

            [data-bs-theme="dark"] .bottom-nav-item,
            body.landing-dark .bottom-nav-item {
                color: #94A3B8;
            }

            .bottom-nav-icon {
                font-size: 1.25rem;
                margin-bottom: 2px;
                position: relative;
                display: flex;
                align-items: center;
                justify-content: center;
                height: 26px;
                transition: transform 0.2s ease;
            }

            .bottom-nav-avatar {
                width: 24px;
                height: 24px;
                border-radius: 50%;
                object-fit: cover;
                border: 1.5px solid currentColor;
            }

            .bottom-nav-label {
                font-size: 0.68rem;
                font-weight: 600;
                letter-spacing: 0.01em;
                line-height: 1;
                transition: color 0.2s ease, font-weight 0.2s ease;
            }

            .bottom-nav-item:hover {
                color: #0F2557;
            }

            [data-bs-theme="dark"] .bottom-nav-item:hover,
            body.landing-dark .bottom-nav-item:hover {
                color: #ffffff;
            }

            .bottom-nav-item.active {
                color: #EC1F24 !important;
            }

            .bottom-nav-item.active .bottom-nav-icon {
                transform: translateY(-2px);
            }

            .bottom-nav-item.active .bottom-nav-label {
                font-weight: 800;
            }
        }
    </style>
</head>
<body>

    <!-- Sticky Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom py-2">
        <div class="container-fluid px-lg-4">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('images/logo-nav.png') }}" alt="TRB Auto Car Care" style="height:48px;width:auto;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-2 d-none d-lg-flex">
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('home') ? 'active' : '' }}" href="{{ route('home') }}">{{ __('landing.nav_home') }}</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ Route::is('bookings.*') ? 'active' : '' }}" href="#" id="navbarBookingsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ __('landing.nav_booking') }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark shadow-sm rounded-3 mt-2" aria-labelledby="navbarBookingsDropdown" style="background:#1c2128; border:1px solid #30363d;">
                            <li><a class="dropdown-item py-2 {{ Route::is('bookings.create') ? 'active' : '' }}" href="{{ route('bookings.create') }}"><i class="fa-solid fa-calendar-plus me-2 text-red"></i> {{ __('landing.nav_new_booking') }}</a></li>
                            @if(auth()->check() && auth()->user()->isCustomer())
                            <li><a class="dropdown-item py-2 {{ Route::is('bookings.index') ? 'active' : '' }}" href="{{ route('bookings.index') }}"><i class="fa-solid fa-list-check me-2 text-warning"></i> {{ __('landing.nav_my_bookings') }}</a></li>
                            @endif
                        </ul>
                    </li>

                    {{-- Services Mega-Dropdown (Sleek Split Panel Style) --}}
                    @php
                    $megaCatalog = [
                        'tyres' => [
                            'title' => __('landing.nav_svc_tyres'),
                            'desc'  => __('landing.nav_svc_tyres_desc'),
                            'icon'  => 'fa-solid fa-circle',
                            'slug'  => 'tyres',
                            'brands' => [
                                ['name' => 'Continental', 'bg' => '#ffa500', 'color' => '#000000'],
                                ['name' => 'Michelin', 'bg' => '#003399', 'color' => '#ffcc00'],
                                ['name' => 'Bridgestone', 'bg' => '#e60012', 'color' => '#ffffff'],
                                ['name' => 'Goodyear', 'bg' => '#00447c', 'color' => '#ffd100'],
                                ['name' => 'Pirelli', 'bg' => '#ffd100', 'color' => '#d5001c'],
                                ['name' => 'Dunlop', 'bg' => '#ffff00', 'color' => '#000000'],
                            ]
                        ],
                        'maintenance' => [
                            'title' => __('landing.nav_svc_maintenance'),
                            'desc'  => __('landing.nav_svc_maintenance_desc'),
                            'icon'  => 'fa-solid fa-wrench',
                            'slug'  => 'maintenance',
                            'brands' => [
                                ['name' => 'Bosch', 'bg' => '#ea0016', 'color' => '#ffffff'],
                                ['name' => 'Castrol', 'bg' => '#008000', 'color' => '#ffffff'],
                                ['name' => 'Shell Helix', 'bg' => '#fbce07', 'color' => '#dd1d21'],
                                ['name' => 'Mobil 1', 'bg' => '#002c6c', 'color' => '#e60012'],
                                ['name' => 'Motul', 'bg' => '#e60012', 'color' => '#ffffff'],
                                ['name' => 'Petronas', 'bg' => '#00a19c', 'color' => '#ffffff'],
                            ]
                        ],
                        'tinting-films' => [
                            'title' => __('landing.nav_svc_tinting'),
                            'desc'  => __('landing.nav_svc_tinting_desc'),
                            'icon'  => 'fa-solid fa-film',
                            'slug'  => 'tinting-films',
                            'brands' => [
                                ['name' => 'ClearShield', 'bg' => '#800000', 'color' => '#ffffff'],
                                ['name' => '3M', 'bg' => '#ff0000', 'color' => '#ffffff'],
                                ['name' => 'V-Kool', 'bg' => '#222222', 'color' => '#d4af37'],
                                ['name' => 'LLumar', 'bg' => '#002060', 'color' => '#ffffff'],
                                ['name' => 'Solar Gard', 'bg' => '#f2a900', 'color' => '#000000'],
                                ['name' => 'Ray-Ban Auto', 'bg' => '#c00000', 'color' => '#ffffff'],
                            ]
                        ],
                        'dashcams' => [
                            'title' => __('landing.nav_svc_dashcams'),
                            'desc'  => __('landing.nav_svc_dashcams_desc'),
                            'icon'  => 'fa-solid fa-video',
                            'slug'  => 'dashcams',
                            'brands' => [
                                ['name' => '70mai', 'bg' => '#333333', 'color' => '#d4af37'],
                                ['name' => 'DDPAI', 'bg' => '#cc0000', 'color' => '#ffffff'],
                                ['name' => 'Thinkware', 'bg' => '#0066cc', 'color' => '#ffffff'],
                                ['name' => 'Garmin', 'bg' => '#000000', 'color' => '#0099ff'],
                                ['name' => 'BlackVue', 'bg' => '#1a1a1a', 'color' => '#ffffff'],
                                ['name' => 'Mio', 'bg' => '#800000', 'color' => '#ffffff'],
                            ]
                        ],
                        'wipers' => [
                            'title' => __('landing.nav_svc_wipers'),
                            'desc'  => __('landing.nav_svc_wipers_desc'),
                            'icon'  => 'fa-solid fa-droplet',
                            'slug'  => 'wipers',
                            'brands' => [
                                ['name' => 'Bosch', 'bg' => '#ea0016', 'color' => '#ffffff'],
                                ['name' => 'PIAA', 'bg' => '#222222', 'color' => '#ffffff'],
                                ['name' => 'Michelin Wiper', 'bg' => '#004b87', 'color' => '#f2a900'],
                                ['name' => 'Denso', 'bg' => '#d5001c', 'color' => '#ffffff'],
                                ['name' => 'Valeo', 'bg' => '#00843d', 'color' => '#ffffff'],
                                ['name' => 'Trico', 'bg' => '#ff0000', 'color' => '#ffffff'],
                            ]
                        ],
                        'car-mats' => [
                            'title' => __('landing.nav_svc_carmats'),
                            'desc'  => __('landing.nav_svc_carmats_desc'),
                            'icon'  => 'fa-solid fa-border-all',
                            'slug'  => 'car-mats',
                            'brands' => [
                                ['name' => 'Trapo', 'bg' => '#1a3300', 'color' => '#99cc00'],
                                ['name' => '3M Mats', 'bg' => '#ff0000', 'color' => '#ffffff'],
                                ['name' => 'Dodomat', 'bg' => '#222222', 'color' => '#ffffff'],
                                ['name' => 'Carmat.my', 'bg' => '#004080', 'color' => '#ffffff'],
                                ['name' => 'Enzo', 'bg' => '#ff6600', 'color' => '#ffffff'],
                                ['name' => 'Maxpider', 'bg' => '#cc0000', 'color' => '#ffff00'],
                            ]
                        ]
                    ];
                    @endphp
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ Route::is('products.index') || Route::is('services.show') ? 'active' : '' }}"
                           href="#" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside"
                           aria-expanded="false">
                            {{ __('landing.nav_services') }}
                        </a>
                        <div class="dropdown-menu services-mega-menu shadow-lg border-0 p-0">
                            <div class="row g-0">
                                {{-- Left Column: Categories --}}
                                <div class="col-5 p-4 border-end border-secondary border-opacity-25 d-flex flex-column justify-content-between" style="background: #161b22;">
                                    <div>
                                        <div class="text-uppercase small fw-bold text-muted mb-3 pb-2 border-bottom border-secondary border-opacity-25 px-2" style="font-size: 0.72rem; letter-spacing: 1px;">{{ __('landing.nav_categories') }}</div>
                                        <div class="d-flex flex-column gap-2">
                                            @foreach($megaCatalog as $catKey => $catData)
                                            <div class="mega-cat-tab d-flex align-items-center gap-3 {{ $loop->first ? 'active' : '' }}"
                                                 data-target="panel-{{ $catKey }}"
                                                 onclick="window.location.href='{{ route('services.show', $catData['slug']) }}'">
                                                <div class="mega-tab-icon rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px;">
                                                    <i class="{{ $catData['icon'] }} fs-6"></i>
                                                </div>
                                                <div class="overflow-hidden">
                                                    <div class="fw-bold text-white mb-0 text-truncate" style="font-size: 0.85rem;">{{ $catData['title'] }}</div>
                                                    <div class="text-muted text-truncate" style="font-size: 0.72rem;">{{ $catData['desc'] }}</div>
                                                </div>
                                                <i class="fa-solid fa-chevron-right ms-auto small text-muted opacity-50 mega-arrow"></i>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="mt-4 pt-3 border-top border-secondary border-opacity-10 px-2">
                                        <a href="{{ route('products.index') }}" class="small fw-bold text-brand text-decoration-none d-flex align-items-center gap-1">
                                            {{ __('landing.nav_view_all_services') }} <i class="fa-solid fa-arrow-right small"></i>
                                        </a>
                                    </div>
                                </div>

                                {{-- Right Column: Brand Showcase (Logo + Name Only) --}}
                                <div class="col-7 p-4" style="background: #1c2128;">
                                    @foreach($megaCatalog as $catKey => $catData)
                                    <div class="mega-content-panel {{ $loop->first ? 'd-block' : 'd-none' }}" id="panel-{{ $catKey }}">
                                        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom border-secondary border-opacity-25">
                                            <h6 class="fw-bold text-white mb-0" style="font-size: 0.95rem;">
                                                <i class="{{ $catData['icon'] }} text-brand me-2"></i>{{ __('landing.nav_featured_brands') }}
                                            </h6>
                                            <span class="small fw-semibold text-brand text-decoration-none" style="cursor:pointer;"
                                                  onclick="event.stopPropagation(); window.location.href='{{ route('services.show', $catData['slug']) }}';">
                                                {{ __('landing.nav_explore_category') }} <i class="fa-solid fa-arrow-up-right-from-square ms-1" style="font-size: 0.7rem;"></i>
                                            </span>
                                        </div>
                                        <div class="row g-3">
                                            @foreach($catData['brands'] as $brand)
                                            <div class="col-4">
                                                <div class="brand-tile-card d-flex flex-column align-items-center justify-content-center p-3 rounded-4 h-100"
                                                     style="cursor:pointer;"
                                                     onclick="event.stopPropagation(); window.location.href='{{ route('services.show', $catData['slug']) }}?brand={{ urlencode($brand['name']) }}';">
                                                    <div class="brand-logo-box mb-2 d-flex align-items-center justify-content-center overflow-hidden" style="width: 100%; height: 50px;">
                                                        @php
                                                            $imgSlug = Str::slug($brand['name']);
                                                            $customMap = [
                                                                'solar-gard' => 'solar gard.png',
                                                                'ray-ban-auto' => 'ray-ban.png',
                                                                'dodo-mat' => 'dodomat.jpg',
                                                                'carmatmy' => 'carmat.png',
                                                                'garmin' => 'garmin.jpg',
                                                                'trapo' => 'trapo.jpg',
                                                                '3m-care' => '3M.png',
                                                            ];
                                                            
                                                            $mappedName = $customMap[$imgSlug] ?? ($imgSlug . '.png');
                                                            $imgPath = 'images/brands/' . $mappedName;
                                                            $hasImg  = file_exists(public_path($imgPath));
                                                            
                                                            if (!$hasImg) {
                                                                $imgPathJpg = 'images/brands/' . $imgSlug . '.jpg';
                                                                if (file_exists(public_path($imgPathJpg))) {
                                                                    $imgPath = $imgPathJpg;
                                                                    $hasImg = true;
                                                                }
                                                            }

                                                            $bName   = $brand['name'];
                                                            $words   = preg_split("/\s+|-/", $bName);
                                                            if (count($words) >= 2) {
                                                                $abbr = strtoupper(substr($words[0],0,1) . substr($words[1],0,1));
                                                            } else {
                                                                $abbr = strtoupper(substr($bName, 0, strlen($bName) <= 3 ? strlen($bName) : 2));
                                                            }
                                                        @endphp
                                                        @if($hasImg)
                                                            <img src="{{ asset($imgPath) }}" alt="{{ $bName }}" class="img-fluid rounded-2" style="max-height: 44px; max-width: 95%; object-fit: contain;">
                                                        @else
                                                            <span class="fw-bolder user-select-none" style="color: {{ $brand['color'] }}; font-size: 1.35rem; font-family: 'Inter', sans-serif; letter-spacing: -0.5px;">
                                                                {{ $abbr }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <span class="fw-bold text-white small text-center text-truncate w-100" style="font-size: 0.78rem; letter-spacing: 0.3px;">
                                                        {{ $bName }}
                                                    </span>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </li>

                    {{-- Rewards (Mobile collapsed menu only) --}}
                    @auth
                        @if(auth()->user()->isCustomer())
                            <li class="nav-item d-lg-none my-2">
                                <a class="vip-reward-pill w-100 justify-content-center py-2" href="{{ route('rewards.index') }}">
                                    <i class="fa-solid fa-crown text-warning"></i>
                                    <span class="ms-1">{{ __('landing.nav_rewards') }}</span>
                                    <span class="pts-badge ms-2">{{ number_format(auth()->user()->membership?->reward_points ?? 0) }} PTS</span>
                                </a>
                            </li>
                        @endif
                    @endauth

                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('help-centre.index') ? 'active' : '' }}" href="{{ route('help-centre.index') }}">{{ __('landing.nav_help') }}</a>
                    </li>
                </ul>

                <!-- Search Box -->
                <form class="me-3 d-none d-lg-block" action="{{ route('search') }}" method="GET">
                    <input type="text" name="q" class="form-control github-search"
                           placeholder="{{ __('landing.nav_search_placeholder') }}"
                           value="{{ request('q') }}"
                           aria-label="Search services">
                </form>

                <div class="d-none d-lg-flex align-items-center gap-3">
                    @auth
                        @php
                            $unreadNotifications = auth()->user()->unreadNotifications->count();
                            $userRewardPts = auth()->user()->membership?->reward_points ?? 0;
                        @endphp

                        @if(auth()->user()->isCustomer())
                            {{-- Desktop Sleek VIP Rewards Badge --}}
                            <a href="{{ route('rewards.index') }}" class="vip-reward-pill d-none d-lg-inline-flex align-items-center"
                               data-bs-toggle="tooltip" title="{{ __('landing.nav_rewards_tooltip') }}">
                                <i class="fa-solid fa-crown text-warning"></i>
                                <span class="d-none d-xl-inline ms-1">{{ __('landing.nav_rewards') }}</span>
                                <span class="pts-badge ms-2">{{ number_format($userRewardPts) }} PTS</span>
                            </a>
                        @endif

                        {{-- Language Switcher (User) --}}
                        <div class="dropdown">
                            <a class="nav-icon-btn dropdown-toggle d-flex align-items-center gap-1" href="#"
                               role="button" data-bs-toggle="dropdown" style="text-decoration:none;"
                               data-bs-toggle="tooltip" title="{{ __('account.settings_label_lang') }}">
                                <i class="fa-solid fa-globe fs-6"></i>
                                <span class="d-none d-xl-inline small">{{ strtoupper(app()->getLocale()) }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                <li>
                                    <form action="{{ route('lang.switch','en') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">{{ __('account.settings_lang_en') }}</button>
                                    </form>
                                </li>
                                <li>
                                    <form action="{{ route('lang.switch','ms') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">{{ __('account.settings_lang_ms') }}</button>
                                    </form>
                                </li>
                                <li>
                                    <form action="{{ route('lang.switch','zh') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">{{ __('account.settings_lang_zh') }}</button>
                                    </form>
                                </li>
                                <li>
                                    <form action="{{ route('lang.switch','ta') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">{{ __('account.settings_lang_ta') }}</button>
                                    </form>
                                </li>
                            </ul>
                        </div>

                        {{-- Notifications Dropdown --}}
                        <div class="dropdown">
                            <a href="#" class="nav-icon-btn position-relative px-1 dropdown-toggle"
                               id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"
                               style="text-decoration:none;">
                                <i class="fa-regular fa-bell fs-5"></i>
                                @if($unreadNotifications > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                                        {{ $unreadNotifications > 99 ? '99+' : $unreadNotifications }}
                                    </span>
                                @endif
                            </a>
                            <div class="dropdown-menu dropdown-menu-end shadow border rounded-4 p-0 mt-2" aria-labelledby="notificationDropdown" style="width: 320px; max-width: 90vw;">
                                <div class="p-3 bg-light border-bottom d-flex justify-content-between align-items-center">
                                    <h6 class="fw-bold mb-0">{{ __('account.profile_sidebar_notifications') }}</h6>
                                    @if($unreadNotifications > 0)
                                        <span class="badge bg-brand text-white rounded-pill">{{ $unreadNotifications }} {{ __('landing.help_badge_new') }}</span>
                                    @endif
                                </div>
                                <div class="list-group list-group-flush" style="max-height: 300px; overflow-y: auto;">
                                    @php
                                        $recentNotifs = auth()->user()->notifications()->latest()->take(5)->get();
                                    @endphp
                                    @if($recentNotifs->count() > 0)
                                        @foreach($recentNotifs as $n)
                                            @php $fNotif = \App\Helpers\NotificationHelper::format($n); @endphp
                                            <a href="{{ route('notifications.index') }}" class="list-group-item list-group-item-action p-3 text-start border-bottom {{ empty($n->read_at) ? 'bg-light fw-semibold' : '' }}">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <span class="small fw-bold text-brand text-truncate" style="max-width: 180px;">{{ $fNotif['title'] }}</span>
                                                    <small class="text-muted" style="font-size: 0.7rem;">{{ $n->created_at->diffForHumans() }}</small>
                                                </div>
                                                <p class="mb-0 small text-secondary fw-normal text-truncate">{{ $fNotif['message'] }}</p>
                                            </a>
                                        @endforeach
                                    @else
                                        <div class="p-4 text-center">
                                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-2 border" style="width: 52px; height: 52px;">
                                                <i class="fa-regular fa-bell-slash text-muted fs-5 opacity-75"></i>
                                            </div>
                                            <h6 class="fw-bold text-secondary small mb-1">{{ __('landing.nav_notif_none') }}</h6>
                                            <p class="text-muted mb-0" style="font-size: 0.75rem;">{{ __('landing.nav_notif_none_desc') }}</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-2 text-center bg-light border-top">
                                    <a href="{{ route('notifications.index') }}" class="text-decoration-none small fw-bold text-brand d-block py-1">{{ __('landing.nav_view_all_notifs') }}</a>
                                </div>
                            </div>
                        </div>

                        {{-- Avatar Dropdown --}}
                        <div class="dropdown">
                            <button class="avatar-dropdown-toggle" type="button" id="userDropdown"
                                    data-bs-toggle="dropdown" aria-expanded="false"
                                    title="{{ auth()->user()->name }}"
                                    data-bs-placement="bottom">
                                <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="rounded-circle object-fit-cover" style="width: 32px; height: 32px;">
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="userDropdown" style="min-width:210px;">
                                <li class="px-3 py-2 border-bottom mb-1">
                                    <div class="fw-bold">{{ auth()->user()->name }}</div>
                                    <div class="small text-muted">{{ auth()->user()->email }}</div>
                                </li>

                                @if(auth()->user()->isAdmin())
                                    <li><a class="dropdown-item" href="{{ route(auth()->user()->isMainAdmin() ? 'admin.statistics.index' : 'admin.bookings.index') }}"><i class="fa-solid fa-gauge me-2 text-muted"></i>{{ __('landing.nav_admin_dashboard') }}</a></li>
                                @elseif(auth()->user()->isMechanic())
                                    <li><a class="dropdown-item" href="{{ route('mechanic.dashboard') }}"><i class="fa-solid fa-wrench me-2 text-muted"></i>{{ __('landing.nav_mechanic_portal') }}</a></li>
                                @else
                                    <li><a class="dropdown-item" href="{{ route('profile.index') }}"><i class="fa-regular fa-user me-2 text-muted"></i>{{ __('landing.nav_profile') }}</a></li>
                                    <li><a class="dropdown-item" href="{{ route('bookings.index') }}"><i class="fa-solid fa-calendar-check me-2 text-muted"></i>{{ __('account.profile_sidebar_my_bookings') }}</a></li>
                                    <li><a class="dropdown-item" href="{{ route('cars.index') }}"><i class="fa-solid fa-car me-2 text-muted"></i>{{ __('account.profile_sidebar_my_cars') }}</a></li>
                                    @if(auth()->check() && auth()->user()->isCustomer())
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center justify-content-between" href="{{ route('rewards.index') }}">
                                                <span><i class="fa-solid fa-crown me-2 text-warning"></i>{{ __('landing.nav_rewards') }}</span>
                                                <span class="badge bg-warning text-dark rounded-pill" style="font-size:0.68rem;">{{ number_format(auth()->user()->membership?->reward_points ?? 0) }} PTS</span>
                                            </a>
                                        </li>
                                        <li><a class="dropdown-item" href="{{ route('vouchers.index') }}"><i class="fa-solid fa-ticket me-2 text-muted"></i>{{ __('landing.nav_vouchers') }}</a></li>
                                        <li><a class="dropdown-item" href="{{ route('favourites.index') }}"><i class="fa-solid fa-heart me-2 text-muted"></i>{{ __('account.profile_sidebar_my_likes') }}</a></li>
                                    @endif
                                @endif

                                @if(!auth()->user()->isCustomer())
                                    <li><a class="dropdown-item" href="{{ route('profile.index') }}"><i class="fa-regular fa-user me-2 text-muted"></i>{{ __('landing.nav_profile') }}</a></li>
                                @endif
                                <li><a class="dropdown-item" href="{{ route('settings') }}"><i class="fa-solid fa-gear me-2 text-muted"></i>{{ __('landing.nav_settings') }}</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fa-solid fa-right-from-bracket me-2 text-danger"></i>{{ __('landing.nav_signout') }}
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        {{-- Guest: Language Switcher + Login + Register --}}
                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center gap-1 text-white opacity-75"
                               href="#" role="button" data-bs-toggle="dropdown" style="text-decoration:none;">
                                <i class="fa-solid fa-globe"></i> {{ strtoupper(app()->getLocale()) }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                <li>
                                    <form action="{{ route('lang.switch','en') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">{{ __('account.settings_lang_en') }}</button>
                                    </form>
                                </li>
                                <li>
                                    <form action="{{ route('lang.switch','ms') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">{{ __('account.settings_lang_ms') }}</button>
                                    </form>
                                </li>
                                <li>
                                    <form action="{{ route('lang.switch','zh') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">{{ __('account.settings_lang_zh') }}</button>
                                    </form>
                                </li>
                                <li>
                                    <form action="{{ route('lang.switch','ta') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">{{ __('account.settings_lang_ta') }}</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                        <a href="{{ route('login') }}" class="nav-link px-2">{{ __('landing.nav_signin') }}</a>
                        <a href="{{ route('register') }}" class="btn btn-outline-light btn-sm px-3 ms-2 rounded-2"
                           style="border-color: rgba(255,255,255,0.2) !important;">{{ __('landing.nav_signup') }}</a>
                    @endauth
                </div>

                {{-- MOBILE EXCLUSIVE: High-End TRB Racing & Automotive Settings Drawer --}}
                <div class="d-lg-none w-100 pt-2 pb-4 px-1">
                    @auth
                        {{-- 1. VIP User Club Header Card --}}
                        <div class="p-3 mb-4 rounded-4 border d-flex align-items-center justify-content-between shadow" style="background: linear-gradient(145deg, #161b22 0%, #1e252e 100%); border-color: rgba(230, 0, 18, 0.25) !important;">
                            <div class="d-flex align-items-center gap-3 overflow-hidden">
                                <div class="position-relative flex-shrink-0">
                                    <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="rounded-circle object-fit-cover shadow-sm" style="width: 48px; height: 48px; border: 2px solid #e60012;">
                                    @if(auth()->user()->isCustomer())
                                        <span class="position-absolute bottom-0 end-0 rounded-circle bg-warning d-flex align-items-center justify-content-center shadow-sm" style="width: 18px; height: 18px; font-size: 0.55rem; color: #000;">
                                            <i class="fa-solid fa-crown"></i>
                                        </span>
                                    @endif
                                </div>
                                <div class="overflow-hidden">
                                    <div class="fw-bold text-white text-truncate fs-6 mb-0" style="letter-spacing: 0.3px;">{{ auth()->user()->name }}</div>
                                    <div class="text-muted text-truncate" style="font-size: 0.72rem; letter-spacing: 0.2px;">{{ auth()->user()->email }}</div>
                                </div>
                            </div>
                            <a href="{{ route('profile.index') }}" class="btn btn-sm rounded-pill px-3 py-1.5 fw-bold flex-shrink-0 d-inline-flex align-items-center gap-1 shadow-sm" style="background: rgba(245, 158, 11, 0.15); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.35); font-size: 0.75rem;">
                                <span>{{ __('landing.nav_profile') }}</span> <i class="fa-solid fa-chevron-right" style="font-size: 0.65rem;"></i>
                            </a>
                        </div>
                    @endauth

                    {{-- 2. System Language Switcher (Sleek 2x2 Grid using exact Desktop i18n logic) --}}
                    <div class="d-flex align-items-center justify-content-between mb-2 px-1">
                        <span class="text-uppercase fw-bold text-muted" style="font-size: 0.7rem; letter-spacing: 1px;"><i class="fa-solid fa-globe text-brand me-1.5"></i>{{ __('account.settings_label_lang') }}</span>
                        <span class="small text-muted" style="font-size: 0.7rem;">{{ strtoupper(app()->getLocale()) }}</span>
                    </div>
                    <div class="d-grid gap-2 mb-4" style="grid-template-columns: repeat(2, 1fr);">
                        <form action="{{ route('lang.switch','en') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="btn w-100 rounded-3 py-2.5 px-2 fw-bold transition-all d-flex align-items-center justify-content-center text-truncate {{ app()->getLocale() == 'en' ? 'text-white shadow-sm' : 'text-muted' }}" style="font-size: 0.8rem; border: 1px solid {{ app()->getLocale() == 'en' ? '#e60012' : 'rgba(255,255,255,0.08)' }}; background: {{ app()->getLocale() == 'en' ? 'linear-gradient(135deg, #e60012 0%, #b8000e 100%)' : '#14181f' }};">
                                {{ __('account.settings_lang_en') }}
                            </button>
                        </form>
                        <form action="{{ route('lang.switch','ms') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="btn w-100 rounded-3 py-2.5 px-2 fw-bold transition-all d-flex align-items-center justify-content-center text-truncate {{ app()->getLocale() == 'ms' ? 'text-white shadow-sm' : 'text-muted' }}" style="font-size: 0.8rem; border: 1px solid {{ app()->getLocale() == 'ms' ? '#e60012' : 'rgba(255,255,255,0.08)' }}; background: {{ app()->getLocale() == 'ms' ? 'linear-gradient(135deg, #e60012 0%, #b8000e 100%)' : '#14181f' }};">
                                {{ __('account.settings_lang_ms') }}
                            </button>
                        </form>
                        <form action="{{ route('lang.switch','zh') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="btn w-100 rounded-3 py-2.5 px-2 fw-bold transition-all d-flex align-items-center justify-content-center text-truncate {{ app()->getLocale() == 'zh' ? 'text-white shadow-sm' : 'text-muted' }}" style="font-size: 0.8rem; border: 1px solid {{ app()->getLocale() == 'zh' ? '#e60012' : 'rgba(255,255,255,0.08)' }}; background: {{ app()->getLocale() == 'zh' ? 'linear-gradient(135deg, #e60012 0%, #b8000e 100%)' : '#14181f' }};">
                                {{ __('account.settings_lang_zh') }}
                            </button>
                        </form>
                        <form action="{{ route('lang.switch','ta') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="btn w-100 rounded-3 py-2.5 px-2 fw-bold transition-all d-flex align-items-center justify-content-center text-truncate {{ app()->getLocale() == 'ta' ? 'text-white shadow-sm' : 'text-muted' }}" style="font-size: 0.8rem; border: 1px solid {{ app()->getLocale() == 'ta' ? '#e60012' : 'rgba(255,255,255,0.08)' }}; background: {{ app()->getLocale() == 'ta' ? 'linear-gradient(135deg, #e60012 0%, #b8000e 100%)' : '#14181f' }};">
                                {{ __('account.settings_lang_ta') }}
                            </button>
                        </form>
                    </div>

                    {{-- 3. Account & Support Unified Group Box (App-Style Rounded List) --}}
                    <div class="d-flex align-items-center justify-content-between mb-2 px-1">
                        <span class="text-uppercase fw-bold text-muted" style="font-size: 0.7rem; letter-spacing: 1px;"><i class="fa-solid fa-sliders text-brand me-1.5"></i>{{ __('landing.nav_system_support') }}</span>
                    </div>
                    <div class="d-flex flex-column gap-2.5 mb-4">
                        {{-- Card 1: Help Centre --}}
                        <a href="{{ route('help-centre.index') }}" class="d-flex align-items-center justify-content-between p-3 rounded-4 text-decoration-none transition-all border shadow-sm" style="background: #161b22; border-color: rgba(255, 255, 255, 0.08) !important; padding: 16px 18px !important;">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: rgba(230, 0, 18, 0.15); color: #e60012;">
                                    <i class="fa-solid fa-headset fs-5"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-white mb-1" style="font-size: 0.94rem;">{{ __('landing.nav_help') }}</div>
                                    <div class="text-muted" style="font-size: 0.74rem;">{{ __('landing.nav_help_desc') }}</div>
                                </div>
                            </div>
                            <i class="fa-solid fa-chevron-right text-muted opacity-50 small ms-2"></i>
                        </a>

                        @auth
                        {{-- Card 2: Notifications --}}
                        <a href="{{ route('notifications.index') }}" class="d-flex align-items-center justify-content-between p-3 rounded-4 text-decoration-none transition-all border shadow-sm" style="background: #161b22; border-color: rgba(255, 255, 255, 0.08) !important; padding: 16px 18px !important;">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: rgba(245, 158, 11, 0.15); color: #f59e0b;">
                                    <i class="fa-solid fa-bell fs-5"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-white mb-1 d-flex align-items-center gap-2" style="font-size: 0.94rem;">
                                        {{ __('account.profile_sidebar_notifications') }}
                                        @if(auth()->user()->unreadNotifications->count() > 0)
                                            <span class="badge bg-danger rounded-pill px-2 py-0.5 fw-bold" style="font-size: 0.65rem;">{{ auth()->user()->unreadNotifications->count() }} {{ __('landing.help_badge_new') }}</span>
                                        @endif
                                    </div>
                                    <div class="text-muted" style="font-size: 0.74rem;">{{ __('landing.nav_notifications_desc') }}</div>
                                </div>
                            </div>
                            <i class="fa-solid fa-chevron-right text-muted opacity-50 small ms-2"></i>
                        </a>

                        {{-- Card 3: Settings --}}
                        <a href="{{ route('settings') }}" class="d-flex align-items-center justify-content-between p-3 rounded-4 text-decoration-none transition-all border shadow-sm" style="background: #161b22; border-color: rgba(255, 255, 255, 0.08) !important; padding: 16px 18px !important;">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: rgba(255, 255, 255, 0.08); color: #c9d1d9;">
                                    <i class="fa-solid fa-gear fs-5"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-white mb-1" style="font-size: 0.94rem;">{{ __('landing.nav_settings') }}</div>
                                    <div class="text-muted" style="font-size: 0.74rem;">{{ __('landing.nav_settings_desc') }}</div>
                                </div>
                            </div>
                            <i class="fa-solid fa-chevron-right text-muted opacity-50 small ms-2"></i>
                        </a>

                        @if(auth()->user()->isAdmin())
                        {{-- Card 4: Admin Dashboard --}}
                        <a href="{{ route(auth()->user()->isMainAdmin() ? 'admin.statistics.index' : 'admin.bookings.index') }}" class="d-flex align-items-center justify-content-between p-3 rounded-4 text-decoration-none transition-all border shadow-sm" style="background: #161b22; border-color: rgba(255, 255, 255, 0.08) !important; padding: 16px 18px !important;">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: rgba(13, 110, 253, 0.15); color: #3d8bfd;">
                                    <i class="fa-solid fa-gauge fs-5"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-white mb-1" style="font-size: 0.94rem;">{{ __('landing.nav_admin_dashboard') }}</div>
                                    <div class="text-muted" style="font-size: 0.74rem;">{{ __('landing.nav_admin_desc') }}</div>
                                </div>
                            </div>
                            <i class="fa-solid fa-chevron-right text-muted opacity-50 small ms-2"></i>
                        </a>
                        @elseif(auth()->user()->isMechanic())
                        {{-- Card 5: Mechanic Portal --}}
                        <a href="{{ route('mechanic.dashboard') }}" class="d-flex align-items-center justify-content-between p-3 rounded-4 text-decoration-none transition-all border shadow-sm" style="background: #161b22; border-color: rgba(255, 255, 255, 0.08) !important; padding: 16px 18px !important;">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: rgba(245, 158, 11, 0.15); color: #f59e0b;">
                                    <i class="fa-solid fa-wrench fs-5"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-white mb-1" style="font-size: 0.94rem;">{{ __('landing.nav_mechanic_portal') }}</div>
                                    <div class="text-muted" style="font-size: 0.74rem;">{{ __('landing.nav_mechanic_desc') }}</div>
                                </div>
                            </div>
                            <i class="fa-solid fa-chevron-right text-muted opacity-50 small ms-2"></i>
                        </a>
                        @endif
                        @endauth
                    </div>

                    {{-- 4. Sign Out / Guest Action --}}
                    @auth
                    <form action="{{ route('logout') }}" method="POST" class="w-100">
                        @csrf
                        <button type="submit" class="btn w-100 rounded-4 py-3 fw-bold text-danger d-flex align-items-center justify-content-center gap-2 border shadow-sm transition-all" style="background: #161b22; border-color: rgba(230, 0, 18, 0.35) !important; font-size: 0.9rem;">
                            <i class="fa-solid fa-right-from-bracket"></i> {{ __('landing.nav_signout') }}
                        </button>
                    </form>
                    @else
                    <div class="d-flex flex-column gap-2.5 mt-2">
                        <a href="{{ route('login') }}" class="btn w-100 rounded-4 py-3 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2 text-white" style="background: linear-gradient(135deg, #e60012 0%, #b8000e 100%); border: none;">
                            <i class="fa-solid fa-right-to-bracket"></i> {{ __('landing.nav_login') }}
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-outline-light w-100 rounded-4 py-3 fw-bold d-flex align-items-center justify-content-center gap-2" style="border-color: rgba(255, 255, 255, 0.2) !important;">
                            <i class="fa-solid fa-user-plus"></i> {{ __('landing.nav_register') }}
                        </a>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @if(session('success'))
            <div class="container mt-4">
                <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 bg-success text-white" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif
        
        @if(session('error'))
            <div class="container mt-4">
                <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 bg-danger text-white" role="alert">
                    <i class="fa-solid fa-circle-exclamation me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="container mt-4">
                <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 bg-danger bg-opacity-10 text-danger border-start border-danger border-4" role="alert">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fa-solid fa-triangle-exclamation me-2 fs-5"></i>
                        <strong class="mb-0">{{ __('landing.nav_errors_title') }}</strong>
                    </div>
                    <ul class="mb-0 small">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        @hasSection('breadcrumb')
            <div class="container pt-3">
                @yield('breadcrumb')
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer-custom pt-5 mt-5">
        <div class="container pb-4">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <h4 class="text-white mb-3"><span class="text-brand">TRB</span> Auto Car Care</h4>
                    <p class="small text-white-50">{{ __('landing.footer_desc') }}</p>
                </div>
                <div class="col-lg-4 col-md-6">
                    <h5 class="mb-3">{{ __('landing.footer_contact') }}</h5>
                    <ul class="list-unstyled small text-white-50 d-flex flex-column gap-3">
                        <li><i class="fa-solid fa-location-dot text-brand me-2"></i>70, Jalan PU 7/3, Taman Puchong Utama, 47100 Puchong, Selangor</li>
                        <li><a href="mailto:trbautocarcare@gmail.com" class="text-white-50 text-decoration-none"><i class="fa-solid fa-envelope text-brand me-2"></i>trbautocarcare@gmail.com</a></li>
                        <li><a href="tel:+60173673385" class="text-white-50 text-decoration-none"><i class="fa-solid fa-phone text-brand me-2"></i>017-367 3385</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-12 mt-4 mt-lg-0">
                    <h5 class="mb-3">{{ __('landing.footer_quicklinks') }}</h5>
                    <ul class="list-unstyled small d-flex flex-column gap-2">
                        <li><a href="#services">{{ __('landing.footer_maintenance') }}</a></li>
                        <li><a href="#services">{{ __('landing.footer_tyres') }}</a></li>
                        <li><a href="#services">{{ __('landing.footer_tinting') }}</a></li>
                        <li><a href="{{ route('legal.privacy') }}">{{ __('landing.footer_privacy') }}</a></li>
                        <li><a href="{{ route('legal.returns') }}">{{ __('landing.footer_returns') }}</a></li>
                        <li><a href="{{ route('legal.terms') }}">{{ __('landing.footer_terms') }}</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="footer-bottom py-3 text-center">
            <div class="container">
                <p class="mb-0">{{ __('landing.footer_copyright') }}</p>
            </div>
        </div>
    </footer>

    <!-- Mobile-Only Bottom Navigation Bar (Hidden on Desktop via d-lg-none) -->
    <nav class="app-bottom-nav d-lg-none">
        <div class="bottom-nav-container">
            <a href="{{ route('home') }}" class="bottom-nav-item {{ Route::is('home') ? 'active' : '' }}">
                <div class="bottom-nav-icon">
                    <i class="fa-solid fa-house"></i>
                </div>
                <span class="bottom-nav-label">{{ __('landing.nav_home') }}</span>
            </a>
            <a href="{{ route('products.index') }}" class="bottom-nav-item {{ Route::is('products.*') || Route::is('services.*') ? 'active' : '' }}">
                <div class="bottom-nav-icon">
                    <i class="fa-solid fa-screwdriver-wrench"></i>
                </div>
                <span class="bottom-nav-label">{{ __('landing.nav_services') }}</span>
            </a>
            <a href="#" data-bs-toggle="modal" data-bs-target="#mobileBookingsModal" class="bottom-nav-item {{ Route::is('bookings.*') ? 'active' : '' }}">
                <div class="bottom-nav-icon">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
                <span class="bottom-nav-label">{{ __('landing.nav_booking') }}</span>
            </a>
            <a href="{{ auth()->check() && auth()->user()->isCustomer() ? route('rewards.index') : route('login') }}" class="bottom-nav-item {{ Route::is('rewards.*') || Route::is('vouchers.*') ? 'active' : '' }}">
                <div class="bottom-nav-icon">
                    <i class="fa-solid fa-gift"></i>
                </div>
                <span class="bottom-nav-label">{{ __('landing.nav_rewards') }}</span>
            </a>
            @php
                $accountUrl = route('login');
                if (auth()->check()) {
                    if (auth()->user()->isCustomer()) {
                        $accountUrl = route('profile.index');
                    } elseif (auth()->user()->isAdmin()) {
                        $accountUrl = route(auth()->user()->isMainAdmin() ? 'admin.statistics.index' : 'admin.bookings.index');
                    } elseif (auth()->user()->isMechanic()) {
                        $accountUrl = route('mechanic.dashboard');
                    }
                }
                $isAccountActive = Route::is('profile.*') || Route::is('settings*') || Route::is('login') || Route::is('register') || Route::is('admin.*') || Route::is('mechanic.*');
            @endphp
            <a href="{{ $accountUrl }}" class="bottom-nav-item {{ $isAccountActive ? 'active' : '' }}">
                <div class="bottom-nav-icon">
                    @if(auth()->check() && auth()->user()->avatar_url)
                        <img src="{{ auth()->user()->avatar_url }}" alt="User" class="bottom-nav-avatar">
                    @else
                        <i class="fa-solid fa-user"></i>
                    @endif
                </div>
                <span class="bottom-nav-label">{{ __('landing.nav_account') }}</span>
            </a>
        </div>
    </nav>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialise Bootstrap tooltips & Mega Menu Switcher
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipEls = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipEls.forEach(function (el) {
                new bootstrap.Tooltip(el, { trigger: 'hover' });
            });

            // Mega Menu Split Panel Switcher
            const megaTabs = document.querySelectorAll('.mega-cat-tab');
            const megaPanels = document.querySelectorAll('.mega-content-panel');
            megaTabs.forEach(tab => {
                tab.addEventListener('mouseenter', function() {
                    megaTabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    const target = this.getAttribute('data-target');
                    megaPanels.forEach(panel => {
                        if (panel.id === target) {
                            panel.classList.remove('d-none');
                            panel.classList.add('d-block');
                        } else {
                            panel.classList.add('d-none');
                            panel.classList.remove('d-block');
                        }
                    });
                });
            });
        });

        // Global Client-Side Image Compression Engine
        window.compressImageFile = async function(file, quality = 0.8, maxDim = 1920) {
            if (!file || !file.type.startsWith('image/') || file.type === 'image/gif' || file.size <= 250 * 1024) {
                return file;
            }
            return new Promise((resolve) => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const img = new Image();
                    img.onload = () => {
                        const canvas = document.createElement('canvas');
                        let width = img.width, height = img.height;
                        if (width > maxDim || height > maxDim) {
                            if (width > height) { height = Math.round((height * maxDim) / width); width = maxDim; }
                            else { width = Math.round((width * maxDim) / height); height = maxDim; }
                        }
                        canvas.width = width; canvas.height = height;
                        const ctx = canvas.getContext('2d');
                        ctx.drawImage(img, 0, 0, width, height);
                        canvas.toBlob((blob) => {
                            if (!blob || blob.size >= file.size) { resolve(file); return; }
                            const newFile = new File([blob], file.name.replace(/\.[^/.]+$/, ".jpg"), {
                                type: 'image/jpeg', lastModified: Date.now()
                            });
                            newFile.originalSize = file.size;
                            resolve(newFile);
                        }, 'image/jpeg', quality);
                    };
                    img.onerror = () => resolve(file);
                    img.src = e.target.result;
                };
                reader.onerror = () => resolve(file);
                reader.readAsDataURL(file);
            });
        };
    </script>
    @yield('scripts')
    @stack('scripts')
    <!-- Mobile Bookings Action Sheet Modal -->
    <div class="modal fade" id="mobileBookingsModal" tabindex="-1" aria-labelledby="mobileBookingsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-bottom modal-dialog-centered d-lg-none" style="margin: 0; display: flex; align-items: flex-end; min-height: 100%; pointer-events: none;">
            <div class="modal-content rounded-top-4 border-0 pb-4 shadow-lg" style="background: #16181d; border-top: 2px solid #e5322d !important; width: 100%; pointer-events: auto;">
                <div class="modal-header border-0 pb-1 justify-content-center position-relative">
                    <div style="width: 40px; height: 4px; background: rgba(255,255,255,0.2); border-radius: 99px; position: absolute; top: 12px;"></div>
                    <h5 class="modal-title fw-bold text-white mt-3 fs-6" id="mobileBookingsModalLabel"><i class="fa-solid fa-calendar-check text-red me-2"></i>{{ __('landing.nav_modal_action_title') }}</h5>
                    <button type="button" class="btn-close btn-close-white position-absolute end-0 top-0 mt-3 me-3" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4 pt-2 pb-1">
                    <p class="text-white-50 small mb-3 text-center">{{ __('landing.nav_modal_action_sub') }}</p>
                    <div class="d-flex flex-column gap-3">
                        <a href="{{ route('bookings.create') }}" class="btn btn-danger d-flex align-items-center justify-content-between p-3 rounded-3 shadow text-decoration-none" style="background: linear-gradient(135deg, #e5322d 0%, #b81d18 100%); border: none;">
                            <div class="d-flex align-items-center gap-3 text-start">
                                <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                                    <i class="fa-solid fa-plus fs-5 text-white"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-white fs-6">{{ __('landing.nav_new_booking') }}</div>
                                    <div class="text-white-50" style="font-size: 0.75rem;">{{ __('landing.nav_new_booking_desc') }}</div>
                                </div>
                            </div>
                            <i class="fa-solid fa-chevron-right text-white opacity-75"></i>
                        </a>

                        @if(auth()->check() && auth()->user()->isCustomer())
                        <a href="{{ route('bookings.index') }}" class="btn btn-dark d-flex align-items-center justify-content-between p-3 rounded-3 text-decoration-none" style="background: #22262e; border: 1px solid rgba(255,255,255,0.1);">
                            <div class="d-flex align-items-center gap-3 text-start">
                                <div class="bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                                    <i class="fa-solid fa-list-check fs-5 text-warning"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-white fs-6">{{ __('landing.nav_my_bookings') }}</div>
                                    <div class="text-white-50" style="font-size: 0.75rem;">{{ __('landing.nav_my_bookings_desc') }}</div>
                                </div>
                            </div>
                            <i class="fa-solid fa-chevron-right text-white-50"></i>
                        </a>
                        @else
                        <a href="{{ route('login') }}" class="btn btn-dark d-flex align-items-center justify-content-between p-3 rounded-3 text-decoration-none" style="background: #22262e; border: 1px solid rgba(255,255,255,0.1);">
                            <div class="d-flex align-items-center gap-3 text-start">
                                <div class="bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                                    <i class="fa-solid fa-user-lock fs-5 text-warning"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-white fs-6">{{ __('landing.nav_modal_login_title') }}</div>
                                    <div class="text-white-50" style="font-size: 0.75rem;">{{ __('landing.nav_modal_login_desc') }}</div>
                                </div>
                            </div>
                            <i class="fa-solid fa-chevron-right text-white-50"></i>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
