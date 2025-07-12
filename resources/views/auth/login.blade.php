<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Masuk</title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Font Awesome for Icons -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />

  <style>
    :root {
      --color-original-blue: #4A90E2;
      --color-original-blue-hover: #357ABD;
      --color-body-bg: #f8f9fa; /* ← Background body yang lebih soft */
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
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
      overflow: hidden;
      box-sizing: border-box;
    }

    .login-container {
      position: relative;
      max-width: 400px;
      width: 100%;
      padding: 2.5rem;
      background: var(--color-white); /* Tetap putih */
      border-radius: 1rem;
      overflow: hidden;
      box-shadow:
        0 4px 8px rgba(0, 0, 0, 0.05),
        0 -4px 8px rgba(0, 0, 0, 0.05),
        4px 0 8px rgba(0, 0, 0, 0.05),
        -4px 0 8px rgba(0, 0, 0, 0.05);
    }

    .login-container::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      height: 8px;
      width: 100%;
      background: var(--color-original-blue);
    }

    .login-container h4 {
      font-weight: 600;
      color: var(--color-original-blue);
      text-align: center;
      margin-bottom: 1rem;
      font-size: 1.6rem;
    }

    .welcome-text {
      text-align: center;
      font-size: 0.95rem;
      color: var(--color-text-dark);
      margin-bottom: 1.5rem;
      line-height: 1.6;
    }

    .form-label {
      color: var(--color-text-dark);
    }

    .form-control,
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

    .text-center.mt-3,
    .text-center.mt-2 {
      font-size: 0.95rem;
      color: var(--color-text-dark);
    }

    .alert-danger {
      background-color: #f8d7da;
      color: #721c24;
      border-color: #f5c6cb;
      border-radius: 0.75rem;
      padding: 0.75rem 1.25rem;
      margin-bottom: 1.5rem;
      text-align: center;
      font-weight: 500;
    }

    .input-group-text {
      background-color: #f8f9fa;
      border: 1px solid var(--color-border-default);
      border-radius: 0.75rem 0 0 0.75rem;
    }

    .input-group .form-control {
      border-left: none;
      border-radius: 0 0.75rem 0.75rem 0;
    }
  </style>
</head>
<body>

  <div class="login-container">
    <h4 class="mb-2">Masuk ke Akun Anda</h4>

    <div class="welcome-text">
      Selamat datang di <strong>SIMPEMO</strong><br>
      (Sistem Informasi Pendataan Mobil).<br>
      Silakan login untuk mengakses sistem.
    </div>

    @if ($errors->any())
      <div class="alert alert-danger">
        <strong>Oops!</strong> {{ $errors->first() }}
      </div>
    @endif

    <form method="POST" action="/login">
      @csrf

      <!-- Email -->
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-envelope"></i></span>
          <input type="email" name="email" id="email" class="form-control" placeholder="contoh@email.com" value="{{ old('email') }}" required autofocus>
        </div>
      </div>

      <!-- Password -->
      <div class="mb-3">
        <label for="password" class="form-label">Kata Sandi</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-lock"></i></span>
          <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
        </div>
      </div>

      <button type="submit" class="btn btn-primary w-100">Login</button>

      <div class="mt-3 text-center">
        <a href="{{ route('password.request') }}" class="link">Lupa password?</a>
      </div>
      <div class="mt-2 text-center">
        <span>Belum punya akun? <a href="{{ route('register') }}" class="link">Daftar</a></span>
      </div>
    </form>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
