<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard - Centra Mobilindo</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      overflow-x: hidden;
      background-color: #121212;
      color: #fff;
    }

    .sidebar {
      width: 260px;
      background-color: #1e1e1e;
      transition: width 0.3s;
      height: 100vh;
      position: fixed;
      z-index: 1000;
    }

    .sidebar.collapsed {
      width: 72px;
    }

    .sidebar .nav-link {
      color: #ccc;
      display: flex;
      align-items: center;
      padding: 12px 18px;
      gap: 12px;
      transition: background-color 0.2s ease;
      border-bottom: 1px solid #333;
    }

    .sidebar .nav-link:hover {
      background-color: #2a2a2a;
      color: #fff;
    }

    .sidebar.collapsed .nav-link span,
    .sidebar.collapsed .sidebar-title span,
    .sidebar.collapsed .user-details {
      display: none;
    }

    .sidebar .sidebar-title {
      font-size: 1.1rem;
      font-weight: 600;
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 16px 18px;
      border-bottom: 1px solid #333;
    }

    .sidebar .toggle-btn {
      background: transparent;
      border: none;
      color: #aaa;
      font-size: 18px;
      cursor: pointer;
    }

    .sidebar .nav-bottom {
      margin-top: auto;
      padding: 1rem 1rem;
      border-top: 1px solid #333;
    }

    .main-content {
      margin-left: 260px;
      transition: margin-left 0.3s;
      padding: 2rem;
    }

    .collapsed + .main-content {
      margin-left: 72px;
    }

    .user-info {
      display: flex;
      align-items: center;
      color: #ccc;
    }

    .user-info .avatar {
      width: 34px;
      height: 34px;
      background-color: #333;
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
      color: #fff;
      font-weight: bold;
      margin-right: 10px;
    }

    .card {
      background-color: #1e1e1e;
      border: 1px solid #333;
    }

    /* Hide logout text on collapsed sidebar */
    .sidebar.collapsed .logout-text {
      display: none;
    }

    .logout-text {
      display: inline-block;
    }

    @media (max-width: 768px) {
      .sidebar {
        position: absolute;
        height: 100%;
      }

      .main-content {
        margin-left: 72px;
        padding: 1rem;
      }
    }
  </style>
</head>
<body>
  <div class="d-flex">
    <!-- Sidebar -->
    <div id="sidebar" class="sidebar d-flex flex-column">
      <div class="sidebar-title">
        <span><i class="bi bi-car-front-fill me-2"></i>Cv. Centra Mobilindo</span>
        <button class="toggle-btn" onclick="toggleSidebar()" title="Toggle Sidebar">
          <i class="bi bi-chevron-left" id="toggleIcon"></i>
        </button>
      </div>

      <ul class="nav flex-column">
        <li><a href="#" class="nav-link"><i class="bi bi-house-door"></i> <span>Dashboard</span></a></li>
        <li><a href="#" class="nav-link"><i class="bi bi-clock-history"></i> <span>Login Logs</span></a></li>
        <li><a href="#" class="nav-link"><i class="bi bi-folder2"></i> <span>Data Mobil</span></a></li>
      </ul>

      <div class="nav-bottom">
        <div class="user-info mb-2">
          <div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
          <div class="user-details">
            <strong>{{ auth()->user()->name }}</strong><br>
            <small>{{ auth()->user()->email }}</small>
          </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="btn btn-outline-danger btn-sm w-100 d-flex align-items-center justify-content-center gap-2">
            <i class="bi bi-box-arrow-right"></i> <span class="logout-text">Logout</span>
          </button>
        </form>
      </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
      <h3 class="mb-4">Dashboard</h3>

      <div class="row">
        <div class="col-md-6 col-lg-4 mb-3">
          <div class="card shadow-sm">
            <div class="card-body">
              <h5 class="card-title">Nama Pengguna</h5>
              <p class="card-text">{{ auth()->user()->name }}</p>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-4 mb-3">
          <div class="card shadow-sm">
            <div class="card-body">
              <h5 class="card-title">Email</h5>
              <p class="card-text">{{ auth()->user()->email }}</p>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-4 mb-3">
          <div class="card shadow-sm">
            <div class="card-body">
              <h5 class="card-title">Login Terakhir</h5>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      const icon = document.getElementById('toggleIcon');
      sidebar.classList.toggle('collapsed');
      document.querySelector('.main-content').classList.toggle('collapsed');
      icon.classList.toggle('bi-chevron-left');
      icon.classList.toggle('bi-chevron-right');
    }
  </script>
</body>
</html>
