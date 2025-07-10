<!-- Brand -->
<h4 class="mb-4 text-center fw-bold">MyApp</h4>

<!-- User Info -->
<div class="user-info mb-4">
  <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'User') }}" alt="Avatar">
  <div>
    <strong>{{ auth()->user()->name ?? 'Pengguna' }}</strong><br>
    <small class="text-muted">{{ auth()->user()->job ?? 'Posisi Tidak Diketahui' }}</small>
  </div>
</div>

<!-- Navigation -->
<ul class="nav flex-column">
  <li class="nav-item">
    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
      <i class="bi bi-house-door-fill"></i> Dashboard
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{ request()->routeIs('servis.*') ? 'active' : '' }}" href="{{ route('servis.index') }}">
      <i class="bi bi-tools"></i> Servis
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{ request()->routeIs('mobil.*') ? 'active' : '' }}" href="{{ route('mobil.index') }}">
      <i class="bi bi-car-front"></i> Mobil
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{ request()->routeIs('transaksi_pembelian.*') ? 'active' : '' }}" href="{{ route('transaksi-pembelian.index') }}">
      <i class="bi bi-currency-dollar"></i> Transaksi Pembelian
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{ request()->routeIs('transaksi.penjual.*') ? 'active' : '' }}" href="{{ route('transaksi.penjual.index') }}">
      <i class="bi bi-cash-coin"></i> Transaksi Penjualan
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{ request()->routeIs('pembeli.*') ? 'active' : '' }}" href="{{ route('pembeli.index') }}">
      <i class="bi bi-people"></i> Pembeli
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{ request()->routeIs('penjual.*') ? 'active' : '' }}" href="{{ route('penjual.index') }}">
      <i class="bi bi-shop"></i> Penjual
    </a>
  </li>

  <!-- Submenu Laporan -->
  <li class="nav-item">
    <a class="nav-link d-flex justify-content-between align-items-center {{ request()->routeIs('laporan.*') ? '' : 'collapsed' }}" data-bs-toggle="collapse" href="#laporanMenu" role="button" aria-expanded="{{ request()->routeIs('laporan.*') ? 'true' : 'false' }}">
      <span><i class="bi bi-bar-chart-line"></i> Laporan</span>
      <i class="bi bi-chevron-down"></i>
    </a>
    <div class="collapse {{ request()->routeIs('laporan.*') ? 'show' : '' }}" id="laporanMenu">
      <ul class="nav flex-column ms-3 mt-2">
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('laporan.mobil_terjual') ? 'active' : '' }}" href="{{ route('laporan.mobil_terjual') }}">
            <i class="bi bi-dot"></i> Mobil Terjual
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('laporan.mobil_dibeli') ? 'active' : '' }}" href="{{ route('laporan.mobil_dibeli') }}">
            <i class="bi bi-dot"></i> Mobil Dibeli
          </a>
        </li>
      </ul>
    </div>
  </li>

  <!-- Settings -->
  <li class="nav-item">
    <a class="nav-link {{ request()->routeIs('settings') ? 'active' : '' }}" href="{{ route('settings') }}">
      <i class="bi bi-gear-fill"></i> Pengaturan Akun
    </a>
  </li>

  <!-- Logout -->
  <li class="nav-item mt-3">
    <a class="nav-link text-danger" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
      <i class="bi bi-box-arrow-right"></i> Keluar
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
      @csrf
    </form>
  </li>
</ul>
