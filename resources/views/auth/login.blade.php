<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }

        .login-container {
            max-width: 400px;
            margin: 5% auto;
            padding: 2rem;
            background: white;
            border-radius: 1rem;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .login-container h4 {
            font-weight: 600;
            color: #4A90E2;
        }

        .form-control, .btn {
            border-radius: 0.75rem;
        }

        .btn-primary {
            background-color: #4A90E2;
            border-color: #4A90E2;
            transition: 0.3s;
        }

        .btn-primary:hover {
            background-color: #357ABD;
            border-color: #357ABD;
        }

        .link {
            color: #4A90E2;
            text-decoration: none;
        }

        .link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="login-container">
            <h4 class="mb-4 text-center">Masuk ke Akun Anda</h4>

            <!-- Pesan Error -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>{{ $errors->first() }}</strong>
                </div>
            @endif

            <form method="POST" action="/login">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Kata Sandi</label>
                    <input type="password" name="password" id="password" class="form-control" required>
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
    </div>

    <!-- Bootstrap JS (Opsional, hanya jika Anda butuh interaksi JS) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
