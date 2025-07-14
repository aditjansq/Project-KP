@php
    $job = strtolower(auth()->user()->job ?? '');
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Dashboard')</title>

    {{-- Font Poppins --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

    {{-- @stack('styles') --}}

    <style>
        :root {
            /* --- Main App Colors (Adjusted for Dark Theme Consistency) --- */
            --primary-color: #0d6efd; /* Vibrant Blue for accents (Bootstrap primary) */
            --primary-color-rgb: 13, 110, 253; /* RGB for rgba use */
            --secondary-app-color: #bdc3c7; /* Lighter gray for secondary elements (used for default icon color, etc.) */
            --bg-light: #f8f9fa; /* General light background for body (main content area) */
            --border-color: #e0e0e0; /* General border color */
            --text-app-dark: #f0f0f0; /* General light text for dark themes (used for brand, active text) */
            --text-light: #fff; /* White text */
            --user-role-color: #D0D0D0; /* Warna terang untuk teks peran pengguna di sidebar gelap */

            /* --- Sidebar Specific Colors (Dark Theme - Elegant) --- */
            --sidebar-bg-light: #0D0D0D; /* Warna latar belakang sidebar lebih gelap */
            --sidebar-brand-text: #F0F0F0; /* Sedikit off-white untuk teks merek */
            --sidebar-link-text: #B0B0B0; /* Abu-abu terang kusam untuk tautan */
            --sidebar-icon-color: #CDCDCD; /* Abu-abu sedikit lebih terang untuk ikon */
            --sidebar-active-bg: #004085; /* Biru tua elegan untuk latar belakang aktif */
            --sidebar-active-text: var(--text-light); /* Putih untuk teks aktif */
            --sidebar-hover-bg: rgba(255, 255, 255, 0.05); /* Sedikit nuansa putih untuk hover */
            --sidebar-light-tint: rgba(255, 255, 255, 0.03); /* Nuansa yang hampir tidak terlihat untuk latar belakang info pengguna */
            --sidebar-border-color: rgba(255, 255, 255, 0.1); /* Batas putih halus untuk pemisahan */

            /* Sidebar Widths for responsiveness */
            --sidebar-width-expanded: 250px;
            --sidebar-width-collapsed: 70px;

            /* Mapping old variables to new ones for consistency */
            --primary-app-color: var(--primary-color);
            --text-dark: var(--sidebar-link-text); /* Used for non-active text on dark sidebar */
            --primary-light: var(--sidebar-light-tint); /* Used for user info background */
            --secondary-color: var(--sidebar-icon-color); /* Used for default icon color */
            --hover-light: var(--sidebar-hover-bg); /* Used for link hover background */
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-light);
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            color: var(--text-app-dark);
            overflow-x: hidden; /* Prevent horizontal scroll when sidebar collapses */
        }

        /* Simple Scrollbar Design for Webkit browsers (Chrome, Safari, Edge) */
        ::-webkit-scrollbar {
            width: 8px; /* Width for vertical scrollbar */
            height: 8px; /* Height for horizontal scrollbar */
        }

        ::-webkit-scrollbar-track {
            background: #2A2A2A; /* Darker background for the track, complements dark sidebar */
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: #6C757D; /* Medium grey for the thumb, subtle and cool */
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #868E96; /* Slightly lighter grey on hover for interaction */
        }


        /* --- Sidebar Desktop Styles --- */
        .sidebar-desktop {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width-expanded); /* Use variable for width */
            height: 100vh;
            background: var(--sidebar-bg-light);
            border-right: 1px solid var(--sidebar-border-color);
            box-shadow: 2px 0 10px rgba(0,0,0,0.08);
            padding: 0.5rem 0;
            overflow-y: auto;
            z-index: 1045;
            display: flex;
            flex-direction: column;
            font-size: 0.95rem;
            transition: width 0.3s ease-in-out;
        }

        .sidebar-desktop.collapsed {
            width: var(--sidebar-width-collapsed); /* Use variable for collapsed width */
        }

        .sidebar-desktop .brand {
            font-weight: 700;
            font-size: 1.4rem;
            margin-top: 2rem;
            margin-bottom: 1.5rem;
            color: var(--sidebar-brand-text);
            padding: 0 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            justify-content: flex-start;
        }

        .sidebar-desktop .brand i {
            display: none;
        }

        .sidebar-desktop.collapsed .brand {
            justify-content: center;
            padding: 0 0.5rem;
        }

        .sidebar-desktop.collapsed .brand span {
            display: none;
        }

        /* User Info Styling */
        .sidebar-desktop .user-info {
            display: flex;
            align-items: center;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--sidebar-border-color);
            background-color: var(--sidebar-light-tint);
            border-radius: 0.5rem;
            margin: 0 0.75rem 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            transition: all 0.3s ease-in-out;
            flex-shrink: 0;
        }

        .sidebar-desktop.collapsed .user-info {
            flex-direction: column;
            padding: 0.75rem 0.5rem;
            margin: 0 0.5rem 1rem;
            text-align: center;
            justify-content: center;
        }

        .sidebar-desktop .user-info .avatar-container {
            position: relative;
            margin-right: 15px;
            flex-shrink: 0;
        }

        .sidebar-desktop.collapsed .user-info .avatar-container {
            margin-right: 0;
            margin-bottom: 5px;
        }

        .sidebar-desktop .user-info .avatar-container img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary-color);
            padding: 2px;
        }

        .sidebar-desktop.collapsed .user-info .avatar-container img {
            width: 40px;
            height: 40px;
        }

        .online-dot {
            position: absolute;
            bottom: 0px;
            right: 0px;
            width: 12px;
            height: 12px;
            background-color: #28a745;
            border-radius: 50%;
            border: 2px solid var(--text-light);
        }

        .sidebar-desktop.collapsed .online-dot {
            width: 10px;
            height: 10px;
            border: 1.5px solid var(--text-light);
        }

        .sidebar-desktop .user-info .details {
            font-size: 1rem;
            color: var(--sidebar-link-text);
            flex-grow: 1;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-desktop.collapsed .user-info .details {
            display: none;
        }

        .sidebar-desktop .user-info .details div:first-child {
            font-weight: 600;
            font-size: 1.1rem;
            line-height: 1.2;
            color: var(--text-app-dark);
        }
        .sidebar-desktop .user-info .details div:last-child {
            color: var(--user-role-color);
            font-size: 0.9rem;
            line-height: 1.2;
        }

        /* Nav Links */
        .sidebar-desktop .nav {
            padding: 0 0.75rem;
        }

        .sidebar-desktop .nav-item {
            margin-bottom: 0.25rem;
        }

        .sidebar-desktop .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.6rem 1rem;
            border-radius: 0.375rem;
            color: var(--sidebar-link-text);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            user-select: none;
            font-weight: 500;
            transition: background-color 0.2s, color 0.2s, box-shadow 0.2s, padding 0.3s ease-in-out;
            justify-content: flex-start;
        }

        .sidebar-desktop.collapsed .nav-link {
            justify-content: center;
            padding: 0.6rem 0.5rem;
            text-align: center;
        }

        .sidebar-desktop .nav-link i {
            font-size: 1.2rem;
            min-width: 24px;
            text-align: center;
            flex-shrink: 0;
            color: var(--sidebar-icon-color);
        }

        .sidebar-desktop.collapsed .nav-link span {
            display: none;
        }

        .sidebar-desktop .nav-link.active {
            background-color: var(--sidebar-active-bg);
            color: var(--sidebar-active-text);
            box-shadow: none; /* Removed box-shadow for consistency */
            padding: 0.6rem 1rem; /* Ensure consistent padding for active state */
        }
        .sidebar-desktop.collapsed .nav-link.active {
            box-shadow: none;
            padding: 0.6rem 0.5rem; /* Ensure consistent padding for active collapsed state */
        }

        .sidebar-desktop .nav-link.active i {
            color: var(--sidebar-active-text);
        }

        .sidebar-desktop .nav-link:hover {
            background-color: var(--sidebar-hover-bg);
            color: var(--sidebar-link-text);
        }
        .sidebar-desktop .nav-link:hover i {
             color: var(--primary-color);
        }
        .sidebar-desktop .nav-link.active:hover {
            background-color: var(--sidebar-active-bg);
            color: var(--sidebar-active-text);
        }

        /* Submenu styles */
        .sidebar-desktop .nav .submenu {
            padding-left: 0;
            list-style: none;
            overflow: hidden;
        }

        .sidebar-desktop .nav .submenu a {
            padding-left: 2.25rem;
            font-size: 0.88rem;
            color: var(--sidebar-link-text);
            background-color: #2A2A2A;
            display: flex;
            align-items: center;
            margin-bottom: 0.25rem;
            gap: 0.5rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            transition: background-color 0.2s, color 0.2s, box-shadow 0.2s, padding 0.3s ease-in-out;
            border-radius: 0.375rem;
        }

        .sidebar-desktop.collapsed .nav .submenu {
            display: none;
        }
        .sidebar-desktop.collapsed .nav .submenu a {
            padding-left: 0.5rem;
            justify-content: center;
        }
        .sidebar-desktop.collapsed .nav .submenu a span {
            display: none;
        }

        .sidebar-desktop .nav .submenu li:last-child a {
            margin-bottom: 0;
        }

        .sidebar-desktop .nav .submenu a i {
            font-size: 1rem;
            min-width: 20px;
            text-align: center;
            flex-shrink: 0;
            color: var(--sidebar-icon-color);
        }

        .sidebar-desktop .nav .submenu a:hover {
            background-color: #3A3A3A;
            color: #ffffff;
        }
        .sidebar-desktop .nav .submenu a:hover i {
            color: #8fd3f4;
        }
        .sidebar-desktop .nav .submenu a.active {
            background-color: var(--sidebar-active-bg);
            color: #ffffff;
            font-weight: 600;
            box-shadow: none; /* Removed box-shadow for consistency */
            padding-left: 2.25rem; /* Ensure consistent padding for active submenu */
        }
        .sidebar-desktop .nav .submenu a.active i {
            color: var(--primary-color);
        }

        /* Menu Section Label Styles */
        .menu-section-label {
            font-size: 0.7rem;
            color: #8ac0ff; /* Changed color to light blue */
            font-weight: 600;
            text-transform: uppercase;
            padding: 0.75rem 1.25rem 0.25rem 1.25rem;
            margin-top: 0.5rem; /* Reduced top margin as requested */
            margin-bottom: 0.25rem; /* Reduced for less space below */
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-desktop.collapsed .menu-section-label {
            display: none;
        }

        .offcanvas .menu-section-label {
            padding: 1rem 1.25rem 0.5rem 1.25rem;
            margin-top: 0.5rem; /* Consistent with desktop */
            margin-bottom: 0.25rem; /* Consistent with desktop */
        }

        /* --- Toggle Button for Desktop Sidebar --- */
        .sidebar-toggle-desktop {
            position: fixed;
            top: 100px;
            left: calc(var(--sidebar-width-expanded) + 10px); /* Position relative to sidebar when expanded */
            background-color: var(--primary-color);
            color: var(--text-light);
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            z-index: 1046;
            transition: transform 0.3s ease-in-out, left 0.3s ease-in-out;
        }

        .sidebar-desktop.collapsed + .sidebar-toggle-desktop {
            left: calc(var(--sidebar-width-collapsed) + 10px); /* Position relative to sidebar when collapsed */
            transform: rotate(180deg);
        }

        /* --- Main Content & Header Adjustments --- */
        #pageHeader {
            padding: 1rem;
            background: white;
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 1000;
            transition: padding-left 0.3s ease-in-out;
        }

        #mainContent {
            margin-top: 1.5rem; /* Increased top margin for more space */
            margin-bottom: 1.5rem; /* Increased bottom margin for more space */
            margin-left: 0.5rem;
            margin-right: 0.5rem;
            padding: 1rem;
            flex-grow: 1;
            min-height: calc(100vh - 70px);
            user-select: none;
            display: flex;
            flex-direction: column;
            background-color: var(--text-light);
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            border-radius: 0.75rem;
            transition: margin-left 0.3s ease-in-out;
        }

        /* Desktop specific styles for header and main content */
        @media (min-width: 992px) {
            #pageHeader.expanded-sidebar {
                padding-left: calc(var(--sidebar-width-expanded) + 1.5rem);
            }
            #pageHeader.collapsed-sidebar {
                padding-left: calc(var(--sidebar-width-collapsed) + 1.5rem);
            }

            #mainContent.expanded-sidebar {
                margin-left: calc(var(--sidebar-width-expanded) + 1.5rem);
                margin-right: 1.5rem;
                margin-top: 1.5rem; /* Ensure consistent top margin */
                margin-bottom: 1.5rem; /* Ensure consistent bottom margin */
            }
            #mainContent.collapsed-sidebar {
                margin-left: calc(var(--sidebar-width-collapsed) + 1.5rem);
                margin-right: 1.5rem;
                margin-top: 1.5rem; /* Ensure consistent top margin */
                margin-bottom: 1.5rem; /* Ensure consistent bottom margin */
            }
        }

        /* --- Mobile Specific Styles (Offcanvas) --- */
        @media (max-width: 991.98px) {
            .sidebar-desktop {
                display: none;
            }
            .sidebar-toggle-desktop {
                display: none;
            }
            #pageHeader, #mainContent {
                margin-left: 0.5rem;
                margin-right: 0.5rem;
                padding-left: 1rem;
            }
            #pageHeader.expanded-sidebar,
            #pageHeader.collapsed-sidebar,
            #mainContent.expanded-sidebar,
            #mainContent.collapsed-sidebar {
                margin-left: 0.5rem;
                margin-right: 0.5rem;
                padding-left: 1rem;
            }
            #mainContent {
                margin-top: 1rem; /* Slightly less for mobile devices */
                margin-bottom: 1rem; /* Slightly less for mobile devices */
            }
        }

        /* Navbar Toggler for Mobile */
        .navbar-toggler {
            font-size: 1.25rem;
            padding: 0.25rem;
            border: none;
            background: none;
            color: var(--primary-app-color);
        }

        @media (max-width: 576px) {
            .page-title {
                display: none;
            }
        }

        /* --- Offcanvas Specific Styles --- */
        .offcanvas-backdrop.show {
            opacity: 0.5;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-backdrop.show {
            opacity: 0.5;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .offcanvas {
            width: 280px;
            background-color: var(--sidebar-bg-light);
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            border-right: 1px solid var(--sidebar-border-color);
            z-index: 1045;
        }
        .offcanvas-header {
            padding: 0.5rem 1.25rem;
            border-bottom: 1px solid var(--sidebar-border-color);
            justify-content: space-between;
            align-items: center;
            background-color: var(--sidebar-light-tint);
        }
        .offcanvas-header .offcanvas-title {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--sidebar-brand-text);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .offcanvas-header .offcanvas-title i {
            display: none;
        }
        /* Style for the custom close button using Bootstrap Icons */
        .offcanvas-header .btn-close-custom {
            background-color: transparent;
            border: 0;
            padding: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 1; /* Ensure it's fully visible */
        }

        .offcanvas-header .btn-close-custom i.bi-x-lg {
            font-size: 1.5rem; /* Adjust size as needed */
            color: #dc3545; /* Red color */
            line-height: 1; /* Ensure proper vertical alignment */
        }

        /* Remove default Bootstrap btn-close styles that might conflict if not using .btn-close-custom everywhere */
        .offcanvas-header .btn-close {
            background-image: none;
            filter: none;
            opacity: 1;
        }

        .offcanvas-body {
            padding: 0;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            background: var(--sidebar-bg-light);
        }

        /* Offcanvas User Info */
        .offcanvas .user-info {
            display: flex;
            align-items: center;
            padding: 1.25rem 1.25rem;
            border-bottom: 1px solid var(--sidebar-border-color);
            background-color: var(--sidebar-light-tint);
            margin-bottom: 1rem;
            border-radius: 0;
            margin-left: 0;
            margin-right: 0;
        }
        .offcanvas .user-info .avatar-container {
            position: relative;
            margin-right: 15px;
        }
        .offcanvas .user-info .avatar-container img {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--primary-color);
            padding: 2px;
        }
        .offcanvas .user-info .details {
            font-size: 1rem;
            color: var(--sidebar-link-text);
            flex-grow: 1;
        }
        .offcanvas .user-info .details div:first-child {
            font-weight: 600;
            font-size: 1.1rem;
            line-height: 1.2;
            color: var(--text-app-dark);
        }
        .offcanvas .user-info .details div:last-child {
            color: var(--user-role-color);
            font-size: 0.9rem;
            line-height: 1.2;
        }

        /* Offcanvas Nav Links */
        .offcanvas .nav {
            padding: 0;
            margin: 0 0.75rem;
        }
        .offcanvas .nav-item {
            margin-bottom: 0.25rem;
        }
        .offcanvas .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            border-radius: 0.375rem;
            color: var(--sidebar-link-text);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            user-select: none;
            font-weight: 500;
            transition: background-color 0.2s, color 0.2s, box-shadow 0.2s;
        }
        .offcanvas .nav-link i {
            font-size: 1.3rem;
            min-width: 28px;
            text-align: center;
            flex-shrink: 0;
            color: var(--sidebar-icon-color);
        }
        .offcanvas .nav-link.active {
            background-color: var(--sidebar-active-bg);
            color: var(--sidebar-active-text);
            box-shadow: none;
            padding: 0.75rem 1rem; /* Ensure consistent padding for active state */
        }
        .offcanvas .nav-link.active i {
            color: var(--sidebar-active-text);
        }
        .offcanvas .nav-link:hover {
            background-color: var(--sidebar-hover-bg);
            color: var(--sidebar-link-text);
        }
        .offcanvas .nav-link:hover i {
            color: var(--primary-color);
        }
        .offcanvas .nav-link.active:hover {
            background-color: #0b5ed7;
            color: var(--sidebar-active-text);
        }

        /* Offcanvas Submenu */
        .offcanvas .nav .submenu {
            padding-left: 0;
            list-style: none;
        }
        .offcanvas .nav .submenu a {
            padding-left: 2.5rem;
            font-size: 0.88rem;
            color: var(--sidebar-link-text);
            background-color: #2A2A2A;
            margin-bottom: 0.25rem;
            gap: 0.5rem;
            transition: background-color 0.2s, color 0.2s, box-shadow 0.2s;
            border-radius: 0.375rem;
        }
        .offcanvas .nav .submenu li:last-child a {
            margin-bottom: 0;
        }
        .offcanvas .nav .submenu a i {
            font-size: 1rem;
            min-width: 20px;
            text-align: center;
            flex-shrink: 0;
            color: var(--sidebar-icon-color);
        }
        .offcanvas .nav .submenu a:hover {
            background-color: #3A3A3A;
            color: #ffffff;
        }
        .offcanvas .nav .submenu a:hover i {
            color: #8fd3f4;
        }
        .offcanvas .nav .submenu a.active {
            background-color: var(--sidebar-active-bg);
            color: #ffffff;
            font-weight: 600;
            box-shadow: none; /* Removed box-shadow for consistency */
            padding-left: 2.5rem; /* Ensure consistent padding for active submenu */
        }
        .offcanvas .nav .submenu a.active i {
            color: var(--primary-color);
        }

        /* Submenu Arrow Rotation for "Laporan" */
        .sidebar-desktop .nav-link .submenu-arrow,
        .offcanvas .nav-link .submenu-arrow {
            transition: transform 0.3s ease-in-out;
        }

        .sidebar-desktop .nav-link.collapsed .submenu-arrow,
        .offcanvas .nav-link.collapsed .submenu-arrow {
            transform: rotate(0deg); /* Down arrow for collapsed state */
        }

        .sidebar-desktop .nav-link:not(.collapsed) .submenu-arrow,
        .offcanvas .nav-link:not(.collapsed) .submenu-arrow {
            transform: rotate(180deg); /* Up arrow for expanded state */
        }


    </style>
</head>
<body>

    <header id="pageHeader">
        <div class="container-fluid">
            <div class="d-flex align-items-center">
                <button class="navbar-toggler d-lg-none me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSidebar" aria-controls="offcanvasSidebar" aria-label="Toggle navigation">
                    <i class="bi bi-list"></i>
                </button>
                <h1 class="mb-0 fs-4 text-start text-dark flex-grow-1 page-title">@yield('title', 'Dashboard')</h1>
                <button class="btn btn-outline-danger btn-sm ms-auto" id="logoutBtn" data-bs-toggle="modal" data-bs-target="#logoutConfirmModal" aria-label="Logout">
                    <i class="bi bi-box-arrow-right"></i> <span class="d-none d-sm-inline">Keluar</span>
                </button>
            </div>
        </div>

        {{-- Hidden logout form --}}
        <form
            id="logout-form"
            action="{{ route('logout') }}"
            method="POST"
            class="d-none"
        >
            @csrf
        </form>
    </header>

    {{-- Desktop Sidebar --}}
    <nav class="sidebar-desktop" id="sidebarDesktop" aria-label="Sidebar navigation">
        <div class="brand">
            <span>Centra Mobilindo</span>
        </div>

        <div class="user-info">
            <div class="avatar-container">
                <img
                    src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Pengguna') }}&background=0D6EFD&color=fff"
                    alt="Avatar"
                    loading="lazy"
                />
                <span class="online-dot"></span>
            </div>
            <div class="details">
                <div><strong>{{ auth()->user()->name ?? 'Pengguna' }}</strong></div>
                <div class="small" style="color: var(--user-role-color);">{{ auth()->user()->job ?? 'Posisi' }}</div>
            </div>
        </div>

        <ul class="nav flex-column">
            <h6 class="menu-section-label">Utama</h6>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-house-door-fill"></i> <span>Dashboard</span>
                </a>
            </li>
            <h6 class="menu-section-label">Mobil</h6>
            @if(in_array($job, ['admin','manajer']))
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('servis.*') ? 'active' : '' }}" href="{{ route('servis.index') }}">
                    <i class="bi bi-tools"></i> <span>Servis</span>
                </a>
            </li>
            @endif

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('mobil.*') ? 'active' : '' }}" href="{{ route('mobil.index') }}">
                    <i class="bi bi-car-front-fill"></i> <span>Mobil</span>
                </a>
            </li>
            @if(in_array($job, ['admin','manajer']))
            <h6 class="menu-section-label">Transaksi</h6>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('transaksi-pembelian.*') ? 'active' : '' }}" href="{{ route('transaksi-pembelian.index') }}">
                    <i class="bi bi-cart-check-fill"></i> <span>Transaksi Pembelian</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('transaksi-penjualan.*') ? 'active' : '' }}" href="{{ route('transaksi-penjualan.index') }}">
                    <i class="bi bi-cash-coin"></i> <span>Transaksi Penjualan</span>
                </a>
            </li>
            @endif
            @if(in_array($job, ['admin']))
            <h6 class="menu-section-label">Pihak Terkait</h6>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('pembeli.*') ? 'active' : '' }}" href="{{ route('pembeli.index') }}">
                    <i class="bi bi-person-fill"></i> <span>Pembeli</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('penjual.*') ? 'active' : '' }}" href="{{ route('penjual.index') }}">
                    <i class="bi bi-shop"></i> <span>Penjual</span>
                </a>
            </li>
            @endif
            @if(in_array($job, ['admin', 'manajer']))
            <h6 class="menu-section-label">Laporan</h6>
            <li class="nav-item">
                <a
                    class="nav-link d-flex justify-content-between align-items-center {{ request()->routeIs('laporan.*') ? '' : 'collapsed' }}"
                    data-bs-toggle="collapse"
                    href="#submenuLaporanDesktop"
                    role="button"
                    aria-expanded="{{ request()->routeIs('laporan.*') ? 'true' : 'false' }}"
                    aria-controls="submenuLaporanDesktop"
                >
                    <span><i class="bi bi-bar-chart-fill"></i> <span>Laporan</span></span>
                    <i class="bi bi-chevron-down ms-auto submenu-arrow"></i>
                </a>
                <div class="collapse {{ request()->routeIs('laporan.*') ? 'show' : '' }}" id="submenuLaporanDesktop">
                    <ul class="nav flex-column ps-3 submenu">
                        <li><a class="nav-link {{ request()->routeIs('laporan.mobil_terjual') ? 'active' : '' }}" href="{{ route('laporan.mobil_terjual') }}"><i class="bi bi-graph-up-arrow"></i> <span>Mobil Terjual</span></a></li>
                        <li><a class="nav-link {{ request()->routeIs('laporan.mobil_dibeli') ? 'active' : '' }}" href="{{ route('laporan.mobil_dibeli') }}"><i class="bi bi-graph-down-arrow"></i> <span>Mobil Dibeli</span></a></li>
                    </ul>
                </div>
            </li>
            @endif

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('settings') ? 'active' : '' }}" href="{{ route('settings') }}">
                    <i class="bi bi-gear-fill"></i> <span>Pengaturan</span>
                </a>
            </li>
        </ul>
    </nav>

    {{-- Desktop Sidebar Toggle Button (moved outside) --}}
    <button class="sidebar-toggle-desktop" id="sidebarToggleDesktop" aria-label="Toggle sidebar">
        <i class="bi bi-chevron-left"></i>
    </button>

    {{-- Offcanvas Sidebar for Mobile --}}
    <div
        class="offcanvas offcanvas-start"
        tabindex="-1"
        id="offcanvasSidebar"
        aria-labelledby="offcanvasSidebarLabel"
        data-bs-backdrop="static"
        data-bs-scroll="true"
    >
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasSidebarLabel">
                Centra Mobilindo
            </h5>
            <button
                type="button"
                class="btn-close-custom" {{-- Changed class to custom --}}
                data-bs-dismiss="offcanvas"
                aria-label="Tutup"
            >
                <i class="bi bi-x-lg"></i> {{-- Changed icon to bi-x-lg --}}
            </button>
        </div>
        <div class="offcanvas-body">
            <div class="user-info d-flex align-items-center">
                <div class="avatar-container">
                    <img
                        src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Pengguna') }}&background=0D6EFD&color=fff"
                        alt="Avatar"
                        class="rounded-circle"
                        loading="lazy"
                    />
                    <span class="online-dot"></span>
                </div>
                <div class="ms-2 details">
                    <div><strong>{{ auth()->user()->name ?? 'Pengguna' }}</strong></div>
                    <div class="small" style="color: var(--user-role-color);">{{ auth()->user()->job ?? 'Posisi' }}</div>
                </div>
            </div>

            <ul class="nav flex-column">
                <h6 class="menu-section-label">Utama</h6>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-house-door-fill"></i> <span>Dashboard</span>
                    </a>
                </li>
                <h6 class="menu-section-label">Mobil</h6>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('servis.*') ? 'active' : '' }}" href="{{ route('servis.index') }}">
                        <i class="bi bi-tools"></i> <span>Servis</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('mobil.*') ? 'active' : '' }}" href="{{ route('mobil.index') }}">
                        <i class="bi bi-car-front-fill"></i> <span>Mobil</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('transaksi-pembelian.*') ? 'active' : '' }}" href="{{ route('transaksi-pembelian.index') }}">
                        <i class="bi bi-cart-check-fill"></i> <span>Transaksi Pembelian</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('transaksi-penjualan.*') ? 'active' : '' }}" href="{{ route('transaksi-penjualan.index') }}">
                        <i class="bi bi-cash-coin"></i> <span>Transaksi Penjualan</span>
                    </a>
                </li>
                <h6 class="menu-section-label">Pihak Terkait</h6>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('pembeli.*') ? 'active' : '' }}" href="{{ route('pembeli.index') }}">
                        <i class="bi bi-person-fill"></i> <span>Pembeli</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('penjual.*') ? 'active' : '' }}" href="{{ route('penjual.index') }}">
                        <i class="bi bi-shop"></i> <span>Penjual</span>
                    </a>
                </li>

                <h6 class="menu-section-label">Laporan</h6>
                <li>
                    <a
                        class="nav-link d-flex justify-content-between align-items-center {{ request()->routeIs('laporan.*') ? '' : 'collapsed' }}"
                        data-bs-toggle="collapse"
                        href="#submenuLaporanMobile"
                        role="button"
                        aria-expanded="{{ request()->routeIs('laporan.*') ? 'true' : 'false' }}"
                        aria-controls="submenuLaporanMobile"
                    >
                        <span><i class="bi bi-bar-chart-fill"></i> <span>Laporan</span></span>
                        <i class="bi bi-chevron-down ms-auto submenu-arrow"></i>
                    </a>
                    <div
                        class="collapse {{ request()->routeIs('laporan.*') ? 'show' : '' }}"
                        id="submenuLaporanMobile"
                    >
                        <ul class="nav flex-column ps-3 submenu">
                            <li><a class="nav-link {{ request()->routeIs('laporan.mobil_terjual') ? 'active' : '' }}" href="{{ route('laporan.mobil_terjual') }}"><i class="bi bi-graph-up-arrow"></i> <span>Mobil Terjual</span></a></li>
                            <li><a class="nav-link {{ request()->routeIs('laporan.mobil_dibeli') ? 'active' : '' }}" href="{{ route('laporan.mobil_dibeli') }}"><i class="bi bi-graph-down-arrow"></i> <span>Mobil Dibeli</span></a></li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('settings') ? 'active' : '' }}" href="{{ route('settings') }}">
                        <i class="bi bi-gear-fill"></i> <span>Pengaturan</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    {{-- Main Content --}}
    <main class="main-content" id="mainContent">
        @yield('content')
    </main>

    {{-- Logout Confirmation Modal --}}
    <div class="modal fade" id="logoutConfirmModal" tabindex="-1" aria-labelledby="logoutConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutConfirmModalLabel">Konfirmasi Keluar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin keluar dari akun ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmLogoutBtn">Keluar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Bootstrap Bundle JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarDesktop = document.getElementById('sidebarDesktop');
            const sidebarToggleDesktop = document.getElementById('sidebarToggleDesktop');
            const mainContent = document.getElementById('mainContent');
            const pageHeader = document.getElementById('pageHeader');

            // Function to set the sidebar and content state
            function setSidebarState(isCollapsed) {
                if (isCollapsed) {
                    sidebarDesktop.classList.add('collapsed');
                    mainContent.classList.remove('expanded-sidebar');
                    mainContent.classList.add('collapsed-sidebar');
                    pageHeader.classList.remove('expanded-sidebar');
                    pageHeader.classList.add('collapsed-sidebar');
                } else {
                    sidebarDesktop.classList.remove('collapsed');
                    mainContent.classList.remove('collapsed-sidebar');
                    mainContent.classList.add('expanded-sidebar');
                    pageHeader.classList.remove('collapsed-sidebar');
                    pageHeader.classList.add('expanded-sidebar');
                }
            }

            // Set initial state based on localStorage or default (desktop: open, mobile: offcanvas)
            function initializeSidebar() {
                if (window.innerWidth >= 992) { // Desktop view
                    const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                    setSidebarState(isCollapsed);
                } else { // Mobile view
                    // Ensure desktop classes are removed for mobile
                    sidebarDesktop.classList.remove('collapsed');
                    mainContent.classList.remove('collapsed-sidebar', 'expanded-sidebar');
                    pageHeader.classList.remove('collapsed-sidebar', 'expanded-sidebar');
                }
            }

            // Call on load
            initializeSidebar();

            // Handle resize to adjust sidebar state
            window.addEventListener('resize', initializeSidebar);

            // Toggle sidebar on desktop
            if (sidebarToggleDesktop) {
                sidebarToggleDesktop.addEventListener('click', function() {
                    const isCollapsed = !sidebarDesktop.classList.contains('collapsed'); // Determine new state
                    setSidebarState(isCollapsed);
                    localStorage.setItem('sidebarCollapsed', isCollapsed); // Save preference
                });
            }

            // Logout button functionality
            document.getElementById('logoutBtn').addEventListener('click', () => {
                // This button triggers the modal, no direct logout here
            });

            // Event listener for 'Keluar' button in modal
            document.getElementById('confirmLogoutBtn').addEventListener('click', () => {
                document.getElementById('logout-form').submit();
            });
        });
    </script>
</body>
</html>
