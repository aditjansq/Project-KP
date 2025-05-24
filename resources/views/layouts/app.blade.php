<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>@yield('title', 'Dashboard')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f8f9fa;
    }

    .sidebar {
      width: 240px;
      height: 100vh;
      background-color: #ffffff;
      border-right: 1px solid #dee2e6;
      position: fixed;
      top: 0;
      left: 0;
      display: flex;
      flex-direction: column;
      padding: 1rem;
    }

    .brand {
      font-weight: bold;
      font-size: 1.2rem;
      color: #000;
      margin-bottom: 2rem;
    }

    .nav-link {
      color: #495057;
      padding: 10px 15px;
      border-radius: 0.375rem;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .nav-link:hover, .nav-link.active {
      background-color: #e9ecef;
      color: #000;
    }

    .nav-link i {
      font-size: 1rem;
    }

    .user-info {
      margin-top: auto;
      padding-top: 1rem;
      border-top: 1px solid #dee2e6;
    }

    .user-avatar {
    width: 40px;
    height: 40px;
    aspect-ratio: 1 / 1;
    background-color: #6c757d;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: bold;
    margin-right: 10px;
    flex-shrink: 0;
    }


    .main-content {
      margin-left: 240px;
      padding: 2rem;
    }

    .logout-btn {
      padding: 0.5rem 1rem;
      font-size: 0.875rem;
    }

    .logout-btn i {
      margin-right: 6px;
    }
  </style>
</head>
<body>

<div class="d-flex">
  <!-- Sidebar -->
  <div class="sidebar">
    <div class="brand">
      <i class="bi bi-car-front-fill me-2"></i>Centra Mobiliondo
    </div>

    @php
      $job = strtolower(auth()->user()->job ?? '');
      $route = Route::currentRouteName();
    @endphp

    <ul class="nav flex-column">
      <li>
        <a href="{{ url('/dashboard/' . str_replace(' ', '-', $job)) }}"
           class="nav-link {{ request()->is('dashboard*') ? 'active' : '' }}">
          <i class="bi bi-house-door-fill"></i> <span>Dashboard</span>
        </a>
      </li>

      @if(in_array($job, ['manajer', 'divisi marketing', 'staff service', 'divisi finance']))
      <li>
        <a href="{{ route('mobil.index') }}"
           class="nav-link {{ str_starts_with($route, 'mobil') && $route !== 'mobil.create' ? 'active' : '' }}">
          <i class="bi bi-truck-front-fill"></i> <span>Mobil</span>
        </a>
      </li>
      <li>
        <a href="{{ route('mobil.create') }}"
           class="nav-link ps-4 {{ $route === 'mobil.create' ? 'active' : '' }}">
          <i class="bi bi-plus-circle"></i> <span>Tambah Mobil</span>
        </a>
      </li>
      @endif

      @if(in_array($job, ['manajer', 'divisi marketing', 'divisi finance']))
      <li>
        <a href="{{ route('pembeli.index') }}"
           class="nav-link {{ str_starts_with($route, 'pembeli') && $route !== 'pembeli.create' ? 'active' : '' }}">
          <i class="bi bi-people-fill"></i> <span>Pembeli</span>
        </a>
      </li>
      <li>
        <a href="{{ route('pembeli.create') }}"
           class="nav-link ps-4 {{ $route === 'pembeli.create' ? 'active' : '' }}">
          <i class="bi bi-person-plus-fill"></i> <span>Tambah Pembeli</span>
        </a>
      </li>
      @endif

      @if(in_array($job, ['manajer', 'divisi finance']))
      <li>
        <a href="{{ route('transaksi.index') }}"
           class="nav-link {{ str_starts_with($route, 'transaksi') ? 'active' : '' }}">
          <i class="bi bi-receipt"></i> <span>Transaksi</span>
        </a>
      </li>
      @endif
    </ul>

    <!-- User Info & Logout -->
    <div class="user-info mt-4">
      <div class="d-flex align-items-center mb-2">
        <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</div>
        <div>
          <div class="fw-semibold">{{ auth()->user()->name }}</div>
          <small class="text-muted">{{ auth()->user()->email }}</small>
        </div>
      </div>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-outline-danger w-100 logout-btn">
          <i class="bi bi-box-arrow-right"></i> Logout
        </button>
      </form>
    </div>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    @yield('content')
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
