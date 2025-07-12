<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Reset Kata Sandi</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

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

    .form-control {
      border-radius: 0.75rem;
      border: 1px solid #ced4da;
      padding: 0.75rem 1rem;
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
    <h4>Reset Kata Sandi</h4>

    @if (session('status'))
      <div class="alert alert-success">
        {{ session('status') }}
      </div>
    @endif

    @if ($errors->any())
      <div class="alert alert-danger">
        {{ $errors->first() }}
      </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
      @csrf

      <div class="mb-3">
        <label for="email" class="form-label">Alamat Email</label>
        <input type="email" name="email" id="email" class="form-control" required autofocus />
      </div>

      <button type="submit" class="btn btn-primary w-100">Kirim Link Reset</button>

      <div class="mt-3 text-center">
        <a href="{{ route('login') }}">Kembali ke Login</a>
      </div>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
