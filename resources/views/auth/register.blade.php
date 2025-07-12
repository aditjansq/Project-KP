<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Daftar Akun</title>

  <!-- Fonts & CSS -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />

  <style>
    :root {
      --color-original-blue: #4A90E2;
      --color-original-blue-hover: #357ABD;
      --color-body-bg: #f8f9fa;
      --color-motif-dots: #eaeaea;
      --color-text-dark: #343a40;
      --color-white: #ffffff;
      --color-shadow-container: rgba(0, 0, 0, 0.1);
      --color-border-default: #ced4da;
    }

    body {
      background-color: var(--color-body-bg);
      background-image: radial-gradient(var(--color-motif-dots) 1px, transparent 1px);
      background-size: 20px 20px;
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 2rem 1rem;
    }

    .register-container {
      position: relative;
      max-width: 400px;
      width: 100%;
      margin: auto;
      padding: 2.5rem;
      background: var(--color-white);
      border-radius: 1rem;
      overflow: hidden;
      box-shadow:
        0 4px 8px rgba(0, 0, 0, 0.05),
        0 -4px 8px rgba(0, 0, 0, 0.05),
        4px 0 8px rgba(0, 0, 0, 0.05),
        -4px 0 8px rgba(0, 0, 0, 0.05);
    }

    .register-container::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      height: 8px;
      width: 100%;
      background: var(--color-original-blue);
    }

    .register-container h4 {
      font-weight: 600;
      color: var(--color-original-blue);
      text-align: center;
      margin-bottom: 1rem;
      font-size: 1.6rem;
    }

    .form-label {
      color: var(--color-text-dark);
    }

    .form-control,
    .form-select,
    .btn {
      border-radius: 0.75rem;
    }

    .form-control {
      border: 1px solid var(--color-border-default);
      padding: 0.75rem 1rem;
    }

    .form-control:focus {
      border-color: var(--color-original-blue);
      box-shadow: 0 0 0 0.25rem rgba(74, 144, 226, 0.25);
    }

    .btn-primary {
      background-color: var(--color-original-blue);
      border-color: var(--color-original-blue);
      transition: 0.3s;
      padding: 0.75rem 1rem;
      font-weight: 500;
    }

    .btn-primary:hover {
      background-color: var(--color-original-blue-hover);
      border-color: var(--color-original-blue-hover);
    }

    .link {
      color: var(--color-original-blue);
      text-decoration: none;
      transition: 0.3s;
    }

    .link:hover {
      text-decoration: underline;
      color: var(--color-original-blue-hover);
    }

    .alert-danger {
      background-color: #f8d7da;
      color: #721c24;
      border-color: #f5c6cb;
      border-radius: 0.75rem;
      padding: 0.75rem 1.25rem;
      margin-bottom: 1.5rem;
      text-align: left;
      font-weight: 500;
    }

    .input-group-text {
      background-color: #f8f9fa;
      border: 1px solid var(--color-border-default);
      border-radius: 0.75rem 0 0 0.75rem;
      color: #000000; /* WARNA HITAM untuk icon */
    }

    .input-group .form-control {
      border-left: none;
      border-radius: 0 0.75rem 0.75rem 0;
    }

    .form-select {
      padding: 0.65rem 0.75rem;
    }
  </style>
</head>
<body class="min-vh-100 d-flex justify-content-center align-items-center">

  <div class="register-container">
    <h4>Daftar Akun</h4>

    @if ($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0 ps-3">
          @foreach ($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('register') }}" id="registerForm">
      @csrf

      <div class="mb-3">
        <label class="form-label">Nama</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-user"></i></span>
          <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Email</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-envelope"></i></span>
          <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">No HP</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-phone"></i></span>
          <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp') }}" required>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Password</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-lock"></i></span>
          <input type="password" name="password" class="form-control" required>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Konfirmasi Password</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-lock"></i></span>
          <input type="password" name="password_confirmation" class="form-control" required>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Pekerjaan</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-briefcase"></i></span>
          <select name="job" class="form-select" required>
            <option value="">-- Jenis User --</option>
            <option value="manajer" {{ old('job') == 'manajer' ? 'selected' : '' }}>Manajer</option>
            <option value="admin" {{ old('job') == 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="sales" {{ old('job') == 'sales' ? 'selected' : '' }}>Sales</option>
          </select>
        </div>
      </div>

      <button type="submit" id="submitBtn" class="btn btn-primary w-100 mt-2">Daftar Sekarang</button>

      <p class="text-center mt-3">
        Sudah punya akun? <a href="{{ route('login') }}" class="link">Login</a>
      </p>
    </form>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const form = document.getElementById('registerForm');
    const submitButton = document.getElementById('submitBtn');

    form.addEventListener('submit', function(e) {
      e.preventDefault();
      submitButton.disabled = true;
      submitButton.innerHTML = 'Sedang Memproses...';
      form.submit();
    });
  </script>
</body>
</html>
