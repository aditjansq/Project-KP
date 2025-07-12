<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Atur Ulang Kata Sandi</title>

  <!-- Bootstrap 5, Font, FontAwesome -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />

  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Poppins', sans-serif;
      margin: 0;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 1rem;
    }

    .reset-container {
      max-width: 400px;
      width: 100%;
      background: #fff;
      border-radius: 1rem;
      box-shadow:
        0 4px 12px rgba(0,0,0,0.1),
        0 -4px 12px rgba(0,0,0,0.05),
        4px 0 12px rgba(0,0,0,0.05),
        -4px 0 12px rgba(0,0,0,0.05);
      padding: 2.5rem 2rem;
      box-sizing: border-box;
    }

    .reset-container h4 {
      font-weight: 600;
      color: #4A90E2;
      text-align: center;
      margin-bottom: 2rem;
      font-size: 1.75rem;
    }

    .form-label {
      color: #343a40;
      font-weight: 500;
    }

    /* Styling input group icon */
    .input-group-text {
      background-color: #f8f9fa;
      border: 1px solid #ced4da;
      border-radius: 0.75rem 0 0 0.75rem;
      color: #000; /* icon hitam */
      font-size: 1.1rem;
      padding: 0.6rem 0.75rem;
    }

    /* Styling input with icon */
    .form-control {
      border-radius: 0 0.75rem 0.75rem 0;
      border-left: none;
      padding: 0.75rem 1rem;
      border: 1px solid #ced4da;
      font-size: 1rem;
      transition: border-color 0.3s, box-shadow 0.3s;
    }

    .form-control:focus {
      border-color: #4A90E2;
      box-shadow: 0 0 0 0.25rem rgba(74, 144, 226, 0.25);
      outline: none;
    }

    .btn-primary {
      background-color: #4A90E2;
      border-color: #4A90E2;
      border-radius: 0.75rem;
      font-weight: 600;
      padding: 0.75rem;
      font-size: 1.1rem;
      transition: background-color 0.3s;
    }

    .btn-primary:hover {
      background-color: #357ABD;
      border-color: #357ABD;
    }

    .alert {
      border-radius: 0.75rem;
      font-weight: 500;
      margin-bottom: 1.5rem;
      padding: 0.75rem 1.25rem;
    }

    .text-center a {
      color: #4A90E2;
      text-decoration: none;
      transition: color 0.3s;
    }

    .text-center a:hover {
      color: #357ABD;
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <div class="reset-container">
    <h4>Atur Ulang Kata Sandi</h4>

    @if ($errors->any())
      <div class="alert alert-danger">
        {{ $errors->first() }}
      </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}">
      @csrf
      <input type="hidden" name="token" value="{{ $token }}" />

      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-envelope"></i></span>
          <input type="email" name="email" id="email" class="form-control" required autofocus />
        </div>
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Kata Sandi Baru</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-lock"></i></span>
          <input type="password" name="password" id="password" class="form-control" required />
        </div>
      </div>

      <div class="mb-3">
        <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-lock"></i></span>
          <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required />
        </div>
      </div>

      <button type="submit" class="btn btn-primary w-100">Reset Kata Sandi</button>

      <div class="mt-3 text-center">
        <a href="{{ route('login') }}">Kembali ke Login</a>
      </div>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
