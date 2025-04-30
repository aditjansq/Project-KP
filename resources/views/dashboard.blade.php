<!doctype html>
<html lang="en" data-bs-theme="auto">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
  </head>
  <body>
    <div class="d-flex">
      <!-- Sidebar -->
      <div class="d-flex flex-column flex-shrink-0 p-3 bg-body-tertiary" style="width: 280px; height: 100vh;">
        <a href="#" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
          <span class="fs-4">Centra Mobilindo</span>
        </a>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
          <li>
            <button class="nav-link active w-100 text-start" id="dashboard-tab" onclick="showSection('dashboard')">
              Dashboard
            </button>
          </li>
          <li>
            <button class="nav-link w-100 text-start" id="logs-tab" onclick="showSection('logs')">
              Login Logs
            </button>
          </li>
        </ul>
        <hr>
        <div class="dropdown">
          <a href="#" class="d-flex align-items-center link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}" alt="" width="32" height="32" class="rounded-circle me-2">
            <strong>{{ auth()->user()->name }}</strong>
          </a>
          <ul class="dropdown-menu text-small shadow">
            <li><span class="dropdown-item-text">Posisi: <strong>{{ auth()->user()->job }}</strong></span></li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="dropdown-item" type="submit">Sign out</button>
              </form>
            </li>
          </ul>
        </div>
      </div>

      <!-- Main Content -->
      <div class="p-4" style="flex-grow: 1;">
        <div class="container">

          <!-- Dashboard Section -->
          <div id="dashboard-section">
            <div class="card mb-4">
              <div class="card-body">
                <h4 class="card-title">Informasi Pengguna</h4>
                <p><strong>Nama:</strong> {{ auth()->user()->name }}</p>
                <p><strong>Posisi:</strong> {{ auth()->user()->job }}</p>
                <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
              </div>
            </div>
          </div>

          <!-- Logs Section -->
          <div id="logs-section" style="display: none;">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Log Aktivitas Login</h4>
                @if(count($loginLogs) > 0)
                <ul class="list-group">
                  @foreach($loginLogs as $log)
                    <li class="list-group-item">
                      <strong>Login At:</strong> {{ $log->login_at }} <br>
                      <strong>IP Address:</strong> {{ $log->ip_address }} <br>
                      <strong>User Agent:</strong> {{ $log->user_agent }}
                    </li>
                  @endforeach
                </ul>
                @else
                  <p class="text-muted">Belum ada aktivitas login.</p>
                @endif
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Section Switcher Script -->
    <script>
      function showSection(section) {
        document.getElementById('dashboard-section').style.display = section === 'dashboard' ? 'block' : 'none';
        document.getElementById('logs-section').style.display = section === 'logs' ? 'block' : 'none';

        // Toggle active class on sidebar
        document.getElementById('dashboard-tab').classList.toggle('active', section === 'dashboard');
        document.getElementById('logs-tab').classList.toggle('active', section === 'logs');
      }
    </script>
  </body>
</html>
