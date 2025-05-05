<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }

        .otp-container {
            max-width: 400px;
            margin: 5% auto;
            padding: 2rem;
            background: white;
            border-radius: 1rem;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .otp-container h4 {
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

        .alert {
            border-radius: 0.75rem;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="otp-container">
            <h4 class="mb-4 text-center">Verifikasi OTP Anda</h4>

            <!-- Menampilkan Pesan Sukses -->
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Menampilkan Pesan Error -->
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form OTP -->
            <form action="{{ route('otp.verify') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="otp" class="form-label">Kode OTP</label>
                    <input type="text" id="otp" name="otp" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Verifikasi</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS (Opsional, hanya jika Anda butuh interaksi JS) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
