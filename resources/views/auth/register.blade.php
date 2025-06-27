<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center; /* Center horizontally */
            align-items: center; /* Center vertically */
            height: 100vh; /* Full viewport height */
            margin: 0;
        }

        .register-container {
            max-width: 400px;
            width: 100%;
            padding: 2rem;
            background: white;
            border-radius: 1rem;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .register-container h4 {
            font-weight: 600;
            color: #4A90E2;
            text-align: center;
        }

        .form-control, .form-select, .btn {
            border-radius: 0.75rem;
        }

        .btn-custom {
            background-color: #4A90E2;
            color: white;
            border-radius: 2rem;
            transition: 0.3s;
        }

        .btn-custom:hover {
            background-color: #357ABD;
        }

        .link {
            color: #4A90E2;
            text-decoration: none;
        }

        .link:hover {
            text-decoration: underline;
        }

        .alert-danger {
            border-radius: 1rem;
        }
    </style>
</head>
<body>

<div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="register-container">
        <h4 class="mb-4">Daftar Akun</h4>

        <!-- Pesan Error -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
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
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                <small class="text-muted">Nama tidak boleh mengandung angka.</small>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">No HP</label>
                <input type="text" name="no_hp" id="no_hp" class="form-control" value="{{ old('no_hp') }}" required>
                <small class="text-muted">Hanya angka yang diperbolehkan (contoh: 081374201899).</small>
            </div>

            <div class="mb-3">
                <label class="form-label">Pekerjaan</label>
                <select name="job" class="form-select" required>
                    <option value="">-- Jenis User --</option>
                    <option value="manajer" {{ old('job') == 'manajer' ? 'selected' : '' }}>Manajer</option>
                    <option value="admin" {{ old('job') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="sales" {{ old('job') == 'sales' ? 'selected' : '' }}>Sales</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <button type="submit" id="submitBtn" class="btn btn-custom w-100 mt-2">Daftar Sekarang</button>

            <p class="text-center mt-3">
                Sudah punya akun? <a href="{{ route('login') }}" class="link">Login</a>
            </p>
        </form>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- JavaScript untuk menonaktifkan tombol submit dan menangani form -->
<script>
    const form = document.getElementById('registerForm');
    const submitButton = document.getElementById('submitBtn');

    form.addEventListener('submit', function(e) {
        e.preventDefault(); // Mencegah form untuk langsung submit

        // Menonaktifkan tombol submit untuk mencegah pengiriman ganda
        submitButton.disabled = true;
        submitButton.innerHTML = 'Sedang Memproses...';  // Mengubah teks tombol menjadi 'Sedang Memproses...'

        // Kirimkan form setelah menonaktifkan tombol (tombol tetap nonaktif)
        form.submit(); // Proses pengiriman form ke server
    });
</script>

</body>
</html>
