<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --sidebar-width-lg: 260px; /* Lebar sidebar di desktop */
            --sidebar-width-sm: 80px;  /* Lebar sidebar di mobile (hanya ikon) */
            --sidebar-bg: #222B40;
            --text-color-light: #F0F2F5;
            --text-color-muted: #A0A4B3;
            --hover-bg: rgba(255, 255, 255, 0.08);
            --active-bg: #3A4762;
            --active-indicator: #00A2E8;
            --border-color: rgba(255, 255, 255, 0.1);
            --main-bg: #EAEFF4;
            --link-icon-size: 1.1rem;
        }

        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif; /* Changed to Poppins */
            background-color: var(--main-bg);
            overflow-x: hidden; /* Prevent horizontal scrollbar on the entire page */
        }

        /* Main container for flexbox layout */
        .main-layout-container {
            display: flex;
            flex-direction: row;
            min-height: 100vh; /* Ensure it takes full viewport height */
            width: 100%; /* Take full width */
        }

        /* Sidebar Styling */
        .sidebar {
            width: var(--sidebar-width-lg);
            min-width: var(--sidebar-width-lg); /* Ensure minimum width */
            height: 100vh; /* Full viewport height */
            background-color: var(--sidebar-bg);
            position: fixed; /* Sidebar remains fixed */
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            padding: 20px 15px;
            z-index: 1000;
            transition: width 0.3s ease;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            overflow-y: auto; /* Vertical scroll for the entire sidebar */
            overflow-x: hidden; /* Ensure NO horizontal scroll in sidebar */
        }

        .brand {
            font-weight: 700;
            font-size: 1.3rem !important; /* Smaller font size and stronger */
            color: var(--text-color-light);
            margin-bottom: 2.5rem;
            text-align: center;
            letter-spacing: 0.5px;
            text-shadow: 0 1px 2px rgba(0,0,0,0.2);
            white-space: nowrap !important; /* Prevent text from wrapping to a new line and stronger */
            overflow: hidden; /* Hide overflowing content */
            text-overflow: ellipsis; /* Add ellipsis if text is truncated */
            flex-shrink: 0; /* Prevent brand element from shrinking in flex container */
            min-width: 0; /* Allow overflow: hidden to work on flex item */
        }

        /* User Info (Dropdown container) */
        .user-info {
            position: relative; /* Important for dropdown-menu positioning */
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--border-color);
        }

        /* User Info Dropdown Toggle */
        .user-info .dropdown-toggle {
            display: flex; /* Use flexbox for layout */
            align-items: center; /* Vertically center items */
            gap: 15px; /* Spacing between avatar, details, and settings icon */
            padding: 1rem; /* Padding for clickable area */
            background-color: #374151; /* Profile block background color */
            border-radius: 0.75rem;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
            color: var(--text-color-light);
            text-decoration: none;
            cursor: pointer;
            width: 100%; /* Full width */
            border: none;
            text-align: left;
            transition: background-color 0.2s ease-in-out;
        }

        .user-info .dropdown-toggle:hover {
            background-color: var(--hover-bg);
        }

        /* Hide default Bootstrap Dropdown arrow, will be replaced with JS if needed */
        .user-info .dropdown-toggle::after {
            display: inline-block;
            margin-left: auto; /* Push arrow to the right */
            vertical-align: 0.255em;
            content: "";
            border-top: 0.3em solid;
            border-right: 0.3em solid transparent;
            border-bottom: 0;
            border-left: 0.3em solid transparent;
            transition: transform 0.2s ease-in-out;
        }

        .user-info .dropdown-toggle[aria-expanded="true"]::after {
            transform: rotate(-180deg); /* Rotate arrow when open */
        }

        .user-avatar {
            width: 48px;
            height: 48px;
            background-color: #00A2E8;
            color: #ffffff;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 1.4rem;
            flex-shrink: 0;
            position: relative; /* Added for positioning the status indicator */
        }

        /* Status indicator for online/offline */
        .status-indicator {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 2px solid var(--sidebar-bg); /* Border color matching sidebar background */
            background-color: #ccc; /* Default (offline) color */
            transition: background-color 0.3s ease;
        }

        .status-indicator.online {
            background-color: #28a745; /* Green for online */
        }

        .status-indicator.offline {
            background-color: #6c757d; /* Grey for offline */
        }


        .user-info .user-details {
            flex-grow: 1;
            overflow: hidden; /* Important for ellipsis */
        }

        .user-info .fw-semibold {
            font-size: 0.95rem;
            color: #C0C4D3;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 0;
        }

        .user-info .text-muted { /* This targets the 'Posisi' text */
            color: #8BC34A !important; /* Changed to a shade of green with !important */
            font-size: 0.75rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 0;
        }

        /* Dropdown Menu Styling */
        .user-info .dropdown-menu {
            background-color: var(--sidebar-bg);
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            padding: 0.5rem 0;
            min-width: 100%; /* So dropdown width matches toggle */
            z-index: 1051; /* Higher than modal backdrop */
            /* Position properties will be set by JavaScript */
        }

        .user-info .dropdown-item {
            color: var(--text-color-light);
            padding: 0.75rem 1.5rem;
            font-size: 0.9rem;
            transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-info .dropdown-item:hover,
        .user-info .dropdown-item:active {
            background-color: var(--hover-bg);
            color: white;
        }

        .user-info .dropdown-item i {
            font-size: var(--link-icon-size);
        }

        .user-info .dropdown-divider {
            border-top-color: rgba(255, 255, 255, 0.15);
            margin: 0.5rem 0;
        }


        /* Navigation Links */
        .nav-link {
            color: var(--text-color-muted);
            padding: 10px 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.9rem;
            text-decoration: none;
            transition: all 0.2s ease;
            border-radius: 6px;
            margin-bottom: 6px;
            position: relative;
            overflow: hidden; /* Important for ellipsis text */
        }

        .nav-link:hover {
            background-color: var(--hover-bg);
            color: var(--text-color-light);
            transform: translateX(2px);
        }

        .nav-link.active {
            background-color: var(--active-bg);
            color: var(--text-color-light);
            font-weight: 600;
            transform: translateX(0);
        }

        .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            height: 70%;
            width: 3px;
            background-color: var(--active-indicator);
            border-radius: 2px;
            opacity: 1;
            transition: all 0.2s ease;
        }

        .nav-link i:first-child {
            font-size: var(--link-icon-size);
            flex-shrink: 0;
        }

        .nav-link span {
            flex-grow: 1;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .nav-link .ms-auto {
            margin-left: auto !important;
            flex-shrink: 0;
            font-size: 1rem;
            padding-left: 5px;
            line-height: 1;
        }

        /* Submenu Items */
        .nav-item .collapse .nav-link {
            padding-left: 40px;
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.5);
            border-radius: 5px;
            gap: 8px;
        }

        .nav-item .collapse .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.05);
            color: #ffffff;
            transform: translateX(1px);
        }

        .nav-item .collapse .nav-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            font-weight: 500;
        }

        .nav-item .collapse .nav-link i {
            font-size: 0.9rem;
        }

        /* Nav container inside sidebar - ensure it doesn't try to scroll itself horizontally */
        .sidebar .nav {
            flex-grow: 1;
            padding-right: 8px; /* For scrollbar visual space */
            margin-bottom: 1.5rem;
            min-height: 0;
            overflow: visible; /* Important: so submenu content extends downwards */
            /* overflow-y and overflow-x are controlled by .sidebar parent */
        }

        /* Custom Scrollbar for Sidebar */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.15);
            border-radius: 10px;
            border: 2px solid transparent;
            background-clip: padding-box;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background-color: rgba(255, 255, 255, 0.3);
        }

        /* Main Content Area */
        .main-content {
            flex-grow: 1;
            overflow-y: auto;
            /* Add margin-left to give space for the sidebar */
            margin-left: var(--sidebar-width-lg);
            width: calc(100% - var(--sidebar-width-lg)); /* Ensure it takes remaining width */
            height: 100vh; /* Full viewport height for scrollbar to work */
            background-color: var(--main-bg);
            transition: margin-left 0.3s ease, width 0.3s ease;
            display: flex;
            flex-direction: column;
            padding: 1rem; /* Padding around the white content area */
        }

        /* Style for the main content container, to have a white background, rounded, and shadow */
        .main-content-wrapper {
            background-color: #ffffff;
            border-radius: 1rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            padding: 2rem; /* Internal padding for content inside */
            flex-grow: 1; /* Ensure it fills available space */
            overflow-y: auto; /* If content inside is too long */
        }


        /* Custom Scrollbar for Main Content */
        .main-content::-webkit-scrollbar {
            width: 8px;
        }

        .main-content::-webkit-scrollbar-track {
            background: transparent;
        }

        .main-content::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            border: 2px solid transparent;
            background-clip: padding-box;
        }

        .main-content::-webkit-scrollbar-thumb:hover {
            background-color: rgba(0, 0, 0, 0.35);
        }

        /* Modal z-index Fix */
        .modal-backdrop.show {
            z-index: 1040 !important;
        }
        .modal-dialog {
            z-index: 1050 !important;
        }

        /* Table responsiveness (unchanged, good as is) */
        .table-responsive {
            overflow-x: auto;
        }

        /* Adjustments for small screens (Mobile) */
        @media (max-width: 768px) {
            .main-layout-container {
                flex-direction: column; /* Sidebar and content stack in mobile */
            }
            html, body {
                padding-left: 0; /* On mobile, sidebar is no longer fixed, so no need for body padding */
                display: block; /* Change to block so sidebar and content are stacked */
            }
            .sidebar {
                width: 100%; /* Sidebar takes full width on mobile */
                height: auto; /* Height adjusts to content */
                position: relative; /* No longer fixed */
                padding: 1rem;
                flex-direction: row; /* Sidebar items layout horizontally */
                flex-wrap: wrap; /* Allow items to wrap */
                justify-content: center; /* Center items */
                border-bottom: 1px solid var(--border-color);
            }
            .sidebar .brand {
                width: 100%; /* Brand takes full width */
                margin-bottom: 1rem;
                text-align: center; /* Ensure it's centered on mobile */
                white-space: nowrap !important; /* Keep nowrap on mobile too and stronger */
                overflow: hidden; /* Keep hidden on mobile too */
                text-overflow: ellipsis; /* Keep ellipsis on mobile too */
            }
            .sidebar .user-info {
                width: auto; /* Let its width adjust to content */
                margin-bottom: 1rem;
                padding-bottom: 0;
                border-bottom: none;
                flex-basis: 100%; /* Ensure user info dropdown takes full row */
            }
            .sidebar .user-info .dropdown-toggle {
                width: auto; /* Adjust width to content */
                padding: 0.5rem 1rem; /* Reduce padding */
                margin: 0 auto; /* Center toggle */
            }
            .sidebar .user-avatar {
                width: 38px;
                height: 38px;
                font-size: 1.1rem;
                margin-right: 0.75rem; /* Give some space */
            }
            .sidebar .user-info .user-details {
                display: block; /* Show user details again */
                flex-grow: 1; /* Allow it to fill space */
            }
            .sidebar .dropdown-toggle::after {
                display: inline-block; /* Show dropdown arrow again */
            }

            .sidebar .nav {
                flex-direction: row; /* Menu items side by side on mobile */
                flex-wrap: wrap; /* Allow items to wrap */
                justify-content: center; /* Center items */
                margin-bottom: 0;
                padding-right: 0;
                width: 100%; /* Take full width */
            }
            .sidebar .nav-item {
                flex-basis: 48%; /* Two items per row */
                margin-right: 2%; /* Space between items */
                margin-bottom: 1rem;
            }
            .sidebar .nav-item:nth-child(2n) {
                margin-right: 0; /* Remove margin on even items */
            }
            .sidebar .nav-link {
                justify-content: center; /* Center icon and text */
                flex-direction: column; /* Icon above text */
                padding: 0.75rem 0.5rem;
                font-size: 0.8rem;
                text-align: center;
                height: 100%; /* Ensure consistent height */
            }
            .sidebar .nav-link i {
                margin-right: 0; /* Remove icon margin */
                margin-bottom: 0.5rem; /* Add space to text */
                font-size: 1.2rem;
            }
            .sidebar .nav-link span {
                white-space: normal; /* Allow text to wrap */
            }
            .sidebar .nav-link .ms-auto {
                display: none; /* Hide dropdown chevron in mobile main menu */
            }
            .sidebar .nav-item .collapse .nav-link {
                padding-left: 1rem; /* Reduce submenu indentation */
                font-size: 0.75rem;
            }
            .sidebar .nav-item .collapse .nav-link i {
                font-size: 0.8rem;
            }

            .main-content {
                margin-left: 0; /* No left margin on mobile */
                width: 100%; /* Full width */
                height: auto; /* Auto height */
                padding: 1rem;
                margin: 0; /* Remove margin on mobile */
                border-radius: 0; /* Remove border-radius on mobile if not desired */
                box-shadow: none; /* Remove shadow on mobile */
            }
            .main-content-wrapper {
                border-radius: 0; /* Remove border-radius on mobile */
                box-shadow: none; /* Remove shadow on mobile */
            }
            /* User info dropdown position on mobile */
            .user-info .dropdown-menu {
                left: 50% !important; /* Center dropdown */
                transform: translateX(-50%) translateY(5px) !important; /* Shift to center and give some space */
                width: auto !important; /* Let width adjust to content */
                min-width: 150px; /* Minimum width */
            }
        }
    </style>
</head>
<body>

<div class="main-layout-container">
    <div class="sidebar" id="sidebar">
        <div class="brand">
            Centra Mobilindo
        </div>

        <div class="dropdown user-info">
            <a class="dropdown-toggle" href="#" role="button" id="userInfoDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    <div class="status-indicator" id="statusIndicator"></div>
                </div>
                <div class="user-details">
                    <div class="fw-semibold">{{ auth()->user()->name }}</div>
                    <div class="text-muted">{{ auth()->user()->job ?? 'Posisi' }}</div>
                </div>
            </a>
            <ul class="dropdown-menu" aria-labelledby="userInfoDropdown">
                <li>
                    <a class="dropdown-item" href="{{ route('settings') }}">
                        <i class="bi bi-gear-fill"></i> Pengaturan
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </li>
            </ul>
        </div>

        @php
            $job = strtolower(auth()->user()->job ?? '');
            $route = Route::currentRouteName();
        @endphp

        <ul class="nav flex-column flex-grow-1">
            <li>
                <a href="{{ url('/dashboard/' . str_replace(' ', '-', $job)) }}" class="nav-link {{ request()->is('dashboard*') ? 'active' : '' }}">
                    <i class="bi bi-house-door-fill"></i> <span>Dashboard</span>
                </a>
            </li>

            {{-- Mobil (Daftar Mobil, Tambah Mobil) - Sales, Admin, & Manajer --}}
            @if(in_array($job, ['manajer', 'admin', 'sales']))
            <li class="nav-item">
                <a class="nav-link"
                   data-bs-toggle="collapse" href="#mobilSubmenu" role="button"
                   aria-expanded="{{ str_starts_with($route, 'mobil') ? 'true' : 'false' }}" aria-controls="mobilSubmenu">
                    <i class="bi bi-truck-front-fill"></i> <span>Mobil</span> <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <div class="collapse {{ str_starts_with($route, 'mobil') ? 'show' : '' }}" id="mobilSubmenu">
                    <a href="{{ route('mobil.index') }}" class="nav-link {{ $route === 'mobil.index' || str_starts_with($route, 'mobil.edit') ? 'active' : '' }}">
                        <i class="bi bi-list-ul"></i> <span>Daftar Mobil</span>
                    </a>
                    {{-- Tambah Mobil hanya untuk Admin --}}
                    @if(in_array($job, ['admin']))
                    <a href="{{ route('mobil.create') }}" class="nav-link {{ $route === 'mobil.create' ? 'active' : '' }}">
                        <i class="bi bi-plus-circle"></i> <span>Tambah Mobil</span>
                    </a>
                    @endif
                </div>
            </li>
            @endif

            {{-- Pembeli (Daftar Pembeli, Tambah Pembeli) - Admin --}}
            @if(in_array($job, ['admin']))
            <li class="nav-item">
                <a class="nav-link"
                   data-bs-toggle="collapse" href="#pembeliSubmenu" role="button"
                   aria-expanded="{{ str_starts_with($route, 'pembeli') ? 'true' : 'false' }}" aria-controls="pembeliSubmenu">
                    <i class="bi bi-people-fill"></i> <span>Pembeli</span> <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <div class="collapse {{ str_starts_with($route, 'pembeli') ? 'show' : '' }}" id="pembeliSubmenu">
                    <a href="{{ route('pembeli.index') }}" class="nav-link {{ $route === 'pembeli.index' || str_starts_with($route, 'pembeli.edit') ? 'active' : '' }}">
                        <i class="bi bi-list-ul"></i> <span>Daftar Pembeli</span>
                    </a>
                    <a href="{{ route('pembeli.create') }}" class="nav-link {{ $route === 'pembeli.create' ? 'active' : '' }}">
                        <i class="bi bi-person-plus-fill"></i> <span>Tambah Pembeli</span>
                    </a>
                </div>
            </li>
            @endif

            {{-- Penjual (Daftar Penjual, Tambah Penjual) - Admin --}}
            @if(in_array($job, ['admin']))
            <li class="nav-item">
                <a class="nav-link"
                   data-bs-toggle="collapse" href="#penjualSubmenu" role="button"
                   aria-expanded="{{ str_starts_with($route, 'penjual') ? 'true' : 'false' }}" aria-controls="penjualSubmenu">
                    <i class="bi bi-person-plus-fill"></i> <span>Penjual</span> <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <div class="collapse {{ str_starts_with($route, 'penjual') ? 'show' : '' }}" id="penjualSubmenu">
                    <a href="{{ route('penjual.index') }}" class="nav-link {{ $route === 'penjual.index' || str_starts_with($route, 'penjual.edit') ? 'active' : '' }}">
                        <i class="bi bi-person-fill"></i> <span>Daftar Penjual</span>
                    </a>
                    <a href="{{ route('penjual.create') }}" class="nav-link {{ $route === 'penjual.create' ? 'active' : '' }}">
                        <i class="bi bi-person-plus-fill"></i> <span>Tambah Penjual</span>
                    </a>
                </div>
            </li>
            @endif

            {{-- Transaksi (Daftar Transaksi) - Manajer & Admin --}}
            @if(in_array($job, ['manajer', 'admin']))
            <li class="nav-item">
                <a class="nav-link"
                   data-bs-toggle="collapse" href="#transaksiSubmenu" role="button"
                   aria-expanded="{{ str_starts_with($route, 'transaksi') ? 'true' : 'false' }}" aria-controls="transaksiSubmenu">
                    <i class="bi bi-receipt"></i> <span>Transaksi</span> <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <div class="collapse {{ str_starts_with($route, 'transaksi') ? 'show' : '' }}" id="transaksiSubmenu">
                    {{-- Submenu Daftar Transaksi --}}
                    <a href="{{ route('transaksi.index') }}" class="nav-link {{ $route === 'transaksi.index' ? 'active' : '' }}">
                        <i class="bi bi-list-ul"></i> <span>Daftar Transaksi Umum</span>
                    </a>
                    {{-- Submenu Transaksi Pembeli --}}
                    <a href="{{ route('transaksi.pembeli.index') }}" class="nav-link {{ str_starts_with($route, 'transaksi.pembeli') ? 'active' : '' }}">
                        <i class="bi bi-person-fill"></i> <span>Penjualan Mobil</span>
                    </a>
                    {{-- Submenu Transaksi Penjual --}}
                    <a href="{{ route('transaksi-pembelian.index') }}" class="nav-link {{ str_starts_with($route, 'transaksi-pembelian') ? 'active' : '' }}">
                        <i class="bi bi-person-circle"></i> <span>Pembelian Mobil</span>
                    </a>
                </div>
            </li>
            @endif


            {{-- Servis (Daftar Servis, Tambah Servis) - Manajer & Admin --}}
            @if(in_array($job, ['manajer', 'admin']))
            <li class="nav-item">
                <a class="nav-link"
                   data-bs-toggle="collapse" href="#servisSubmenu" role="button"
                   aria-expanded="{{ str_starts_with($route, 'servis') ? 'true' : 'false' }}" aria-controls="servisSubmenu">
                    <i class="bi bi-wrench"></i> <span>Servis</span> <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <div class="collapse {{ str_starts_with($route, 'servis') ? 'show' : '' }}" id="servisSubmenu">
                    <a href="{{ route('servis.index') }}" class="nav-link {{ $route === 'servis.index' || str_starts_with($route, 'servis.edit') ? 'active' : '' }}">
                        <i class="bi bi-list-ul"></i> <span>Daftar Servis</span>
                    </a>
                    {{-- Tambah Servis HANYA untuk Admin --}}
                    @if($job === 'admin') {{-- Perubahan ada di sini: hanya 'admin' --}}
                    <a href="{{ route('servis.create') }}" class="nav-link {{ $route === 'servis.create' ? 'active' : '' }}">
                        <i class="bi bi-plus-circle"></i> <span>Tambah Servis</span>
                    </a>
                    @endif
                </div>
            </li>
            @endif

            {{-- Laporan (Mobil Terjual, Mobil Dibeli) - Manajer & Admin --}}
            @if(in_array($job, ['manajer', 'admin']))
            <li class="nav-item">
                <a class="nav-link"
                   data-bs-toggle="collapse" href="#laporanSubmenu" role="button"
                   aria-expanded="{{ str_starts_with($route, 'laporan') ? 'true' : 'false' }}" aria-controls="laporanSubmenu">
                    <i class="bi bi-file-earmark-bar-graph"></i> <span>Laporan</span> <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <div class="collapse {{ str_starts_with($route, 'laporan') ? 'show' : '' }}" id="laporanSubmenu">
                    <a href="{{ route('laporan.mobil_terjual') }}" class="nav-link {{ $route === 'laporan.mobil_terjual' ? 'active' : '' }}">
                        <i class="bi bi-currency-dollar"></i> <span>Mobil Terjual</span>
                    </a>
                    <a href="{{ route('laporan.mobil_dibeli') }}" class="nav-link {{ $route === 'laporan.mobil_dibeli' ? 'active' : '' }}">
                        <i class="bi bi-cart-plus"></i> <span>Mobil Dibeli</span>
                    </a>
                </div>
            </li>
            @endif
        </ul>
    </div>

    <div class="main-content" id="main-content">
        <div class="main-content-wrapper">
            @yield('content')
        </div>
    </div>
</div>

<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logoutModalLabel">Konfirmasi Logout</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin keluar?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-danger">Logout</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function updateChevronIcon(collapseElement) {
            const toggleLink = document.querySelector(`a[href="#${collapseElement.id}"]`);
            if (toggleLink) {
                const icon = toggleLink.querySelector('i.bi-chevron-down, i.bi-chevron-up');
                if (icon) {
                    if (collapseElement.classList.contains('show')) {
                        icon.classList.remove('bi-chevron-down');
                        icon.classList.add('bi-chevron-up');
                    } else {
                        icon.classList.remove('bi-chevron-up');
                        icon.classList.add('bi-chevron-down');
                    }
                }
            }
        }

        document.querySelectorAll('.collapse').forEach((collapseElement) => {
            collapseElement.addEventListener('shown.bs.collapse', function() {
                updateChevronIcon(this);
            });
            collapseElement.addEventListener('hidden.bs.collapse', function() {
                updateChevronIcon(this);
            });
            // Initial check for chevron icon on page load if submenu is already open
            updateChevronIcon(collapseElement);
        });

        document.querySelectorAll('.nav-item a[data-bs-toggle="collapse"]').forEach((toggleLink) => {
            toggleLink.addEventListener('click', function (event) {
                const targetCollapseId = this.getAttribute('href');
                const targetCollapseElement = document.querySelector(targetCollapseId);

                // Close other open submenus if they are not the target
                document.querySelectorAll('.nav-item .collapse.show').forEach((openCollapseElement) => {
                    if (openCollapseElement !== targetCollapseElement) {
                        const bsCollapse = bootstrap.Collapse.getInstance(openCollapseElement);
                        if (bsCollapse) {
                            bsCollapse.hide();
                        }
                    }
                });
            });
        });

        // Ensure dropdown menu does not get hidden by overflow in sidebar on mobile
        const userInfoDropdown = document.getElementById('userInfoDropdown');
        if (userInfoDropdown) {
            userInfoDropdown.addEventListener('shown.bs.dropdown', function () {
                const dropdownMenu = this.nextElementSibling;
                const toggleRect = userInfoDropdown.getBoundingClientRect();
                const sidebarRect = document.getElementById('sidebar').getBoundingClientRect();
            });
        }

        // Live user status (you might have this in another script, but I'll put a placeholder)
        function updateStatusIndicator() {
            const statusIndicator = document.getElementById('statusIndicator');
            if (statusIndicator) {
                if ({{ auth()->check() ? 'true' : 'false' }}) {
                    statusIndicator.classList.add('online');
                    statusIndicator.classList.remove('offline');
                } else {
                    statusIndicator.classList.add('offline');
                    statusIndicator.classList.remove('online');
                }
            }
        }
        updateStatusIndicator();
    });
</script>
</body>
</html>
