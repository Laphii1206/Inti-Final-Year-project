<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TRB Auto Car Care - Admin Panel</title>
    
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
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f6f9;
        }

        /* Sidebar styling */
        .sidebar {
            width: 250px;
            background-color: #1E1F24;
            min-height: 100vh;
            color: rgba(255, 255, 255, 0.7);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
        }

        .sidebar-header {
            background-color: #131417;
            padding: 20px;
            text-align: center;
        }

        .sidebar-brand {
            font-weight: 800;
            font-size: 1.25rem;
            color: #ffffff;
            text-decoration: none;
        }

        .sidebar-nav {
            padding: 20px 0;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 12px 25px;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.3s;
            font-weight: 500;
        }

        .sidebar-link:hover, .sidebar-link.active {
            color: #ffffff;
            background-color: rgba(236, 31, 36, 0.1);
            border-left: 4px solid #EC1F24;
        }

        .sidebar-link i {
            margin-right: 15px;
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }

        /* Main content styling */
        .main-content {
            margin-left: 250px;
            min-height: 100vh;
        }

        .topbar {
            background-color: #161b22 !important;
            height: 60px;
            display: flex;
            align-items: center;
            padding: 0 30px;
            border-bottom: 1px solid #30363d !important;
            color: #c9d1d9;
        }

        .github-search {
            background-color: #0d1117;
            border: 1px solid #30363d;
            border-radius: 6px;
            color: #c9d1d9;
            padding: 4px 12px;
            font-size: 14px;
            width: 260px;
            transition: all 0.2s;
        }
        
        .github-search:focus {
            background-color: #0d1117;
            border-color: #58a6ff;
            outline: none;
            box-shadow: 0 0 0 3px rgba(88,166,255,0.3);
            color: #c9d1d9;
            width: 300px;
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

        .topbar-badge {
            background-color: #21262d !important;
            border: 1px solid #30363d !important;
            color: #c9d1d9 !important;
        }

        .content-body {
            padding: 30px;
        }

        /* Card styling */
        .card-stat {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02);
            transition: transform 0.3s;
        }

        .card-stat:hover {
            transform: translateY(-3px);
        }

        @media (max-width: 991.98px) {
            .sidebar {
                left: -250px;
                transition: left 0.3s;
            }
            .sidebar.active {
                left: 0;
            }
            .main-content {
                margin-left: 0;
            }
        }
        .sidebar {
            height: 100vh;
            overflow-y: auto;
        }
        @media (max-width: 991.98px) {
            .sidebar { z-index: 1050; box-shadow: 4px 0 24px rgba(0,0,0,.45); }
        }
        @media (max-width: 991.98px) {
            .sidebar {
                height: 100dvh !important;
                overflow-y: auto !important;
                z-index: 1050 !important;
            }
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('home') }}" class="sidebar-brand">
                <img src="{{ asset('images/logo-nav.png') }}" alt="TRB Auto Admin" style="height:64px;width:auto;">
            </a>
        </div>
        <div class="sidebar-nav">
            @if(auth()->user()->isMainAdmin())
            <a href="{{ route('admin.statistics.index') }}" class="sidebar-link {{ Request::is('admin/statistics*') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-line"></i> {{ __('admin.nav_statistics') }}
            </a>
            @endif
            <a href="{{ route('admin.bookings.index') }}" class="sidebar-link {{ Request::is('admin/bookings*') ? 'active' : '' }}">
                <i class="fa-solid fa-calendar-check"></i> {{ __('admin.nav_bookings') }}
            </a>
            <a href="{{ route('admin.services.index') }}" class="sidebar-link {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
                <i class="fa-solid fa-screwdriver-wrench"></i> {{ __('admin.nav_services') }}
            </a>
            @if(auth()->user()->isMainAdmin())
            <a href="{{ route('admin.branches.index') }}" class="sidebar-link {{ request()->routeIs('admin.branches.*') ? 'active' : '' }}">
                <i class="fa-solid fa-store"></i> {{ __('admin.nav_branches') }}
            </a>

            @endif

            <a href="{{ route('admin.reviews.index') }}" class="sidebar-link {{ Request::is('admin/reviews*') ? 'active' : '' }}">
                <i class="fa-solid fa-star"></i> {{ __('admin.nav_reviews') }}
            </a>
            @if(auth()->user()->isMainAdmin())
            <a href="{{ route('admin.support-tickets.index') }}" class="sidebar-link {{ Request::is('admin/support-tickets*') ? 'active' : '' }}">
                <i class="fa-solid fa-headset"></i> {{ __('admin.nav_tickets') }}
            </a>
            <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ Request::is('admin/users*') ? 'active' : '' }}">
                <i class="fa-solid fa-users-gear"></i> {{ __('admin.nav_users') }}
            </a>
            @endif
            <a href="{{ route('admin.memberships.index') }}" class="sidebar-link {{ Request::is('admin/memberships*') ? 'active' : '' }}">
                <i class="fa-solid fa-crown"></i> {{ __('admin.nav_memberships') }}
            </a>
            @if(auth()->user()->isMainAdmin())
            <a href="{{ route('admin.delete-requests.index') }}" class="sidebar-link {{ Request::is('admin/delete-requests*') ? 'active' : '' }}">
                <i class="fa-solid fa-user-xmark"></i> {{ __('admin.nav_delete_requests') }}
            </a>
            @endif
            <a href="{{ route('mechanic.checkin.scan') }}" class="sidebar-link">
                <i class="fa-solid fa-qrcode"></i> {{ __('admin.nav_qr_scanner') }}
            </a>
            
            <div class="border-top border-secondary my-3"></div>

            <a href="{{ route('home') }}" class="sidebar-link">
                <i class="fa-solid fa-house"></i> {{ __('admin.nav_home') }}
            </a>
            
            <form action="{{ route('logout') }}" method="POST" id="logout-form" class="d-none">
                @csrf
            </form>
            <a href="#" class="sidebar-link text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa-solid fa-right-from-bracket text-danger"></i> {{ __('admin.nav_logout') }}
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <header class="topbar d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <button class="btn btn-outline-secondary btn-sm d-lg-none me-3 border-0 nav-icon-btn" id="sidebarToggle">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <form class="d-none d-md-block">
                    <input type="text" class="form-control github-search" placeholder="{{ __('admin.top_search_ph') }}">
                </form>
            </div>
            <div class="d-flex align-items-center ms-auto gap-3">
                <span class="small d-none d-md-block" style="color: #8b949e;"><i class="fa-solid fa-circle-user text-brand me-1"></i> {{ __('admin.top_admin_panel') }}</span>
                <span class="badge topbar-badge px-3 py-2 d-none d-md-block">{{ __('admin.top_logged_in') }} {{ auth()->user()->name }}</span>
                
                @php
                    $unreadNotifications = auth()->user()->unreadNotifications->count() ?? 0;
                @endphp
                <a href="{{ route('notifications.index') }}" class="nav-icon-btn position-relative px-2">
                    <i class="fa-regular fa-bell fs-5"></i>
                    @if($unreadNotifications > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary" style="font-size: 0.65rem;">
                            {{ $unreadNotifications > 99 ? '99+' : $unreadNotifications }}
                        </span>
                    @endif
                </a>

                <!-- Avatar Dropdown -->
                <div class="dropdown">
                    <button class="avatar-dropdown-toggle" type="button" id="adminDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=0D8ABC&color=fff&size=64" alt="Avatar">
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="adminDropdown">
                        <li class="px-3 py-2 border-bottom mb-1">
                            <div class="fw-bold">{{ auth()->user()->name }}</div>
                            <div class="small text-muted">{{ auth()->user()->email }}</div>
                        </li>
                        <li><a class="dropdown-item" href="{{ route('home') }}"><i class="fa-solid fa-house me-2 text-muted"></i>{{ __('admin.top_view_site') }}</a></li>
                        <li><a class="dropdown-item" href="{{ route('profile.index') }}"><i class="fa-regular fa-user me-2 text-muted"></i>{{ __('admin.top_profile') }}</a></li>
                        <li><a class="dropdown-item" href="{{ route('settings') }}"><i class="fa-solid fa-gear me-2 text-muted"></i>{{ __('admin.top_settings') }}</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger"><i class="fa-solid fa-right-from-bracket me-2 text-danger"></i>{{ __('admin.top_signout') }}</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Content Body -->
        <main class="content-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm text-white bg-success" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm text-white bg-danger" role="alert">
                    <i class="fa-solid fa-circle-exclamation me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');

            if (toggle) {
                toggle.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                });
            }
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
</body>
</html>