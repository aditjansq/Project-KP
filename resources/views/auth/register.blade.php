<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Akun</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Style tambahan -->
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
            font-family: 'Segoe UI', sans-serif;
        }

        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        }

        .form-control, .form-select {
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

        h4 {
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card p-4">
                <div class="card-body">
                    <h4 class="text-center mb-4">Daftar Akun</h4>

                    @if ($errors->any())
                        <div class="alert alert-danger rounded">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">No HP</label>
                            <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Pekerjaan</label>
                            <select name="job" class="form-select" required>
                                <option value="">-- Pilih Job --</option>
                                <option value="manajer" {{ old('job') == 'manajer' ? 'selected' : '' }}>Manajer</option>
                                <option value="divisi marketing" {{ old('job') == 'divisi marketing' ? 'selected' : '' }}>Divisi Marketing</option>
                                <option value="staff service" {{ old('job') == 'staff service' ? 'selected' : '' }}>Staff Service</option>
                                <option value="divisi finance" {{ old('job') == 'divisi finance' ? 'selected' : '' }}>Divisi Finance</option>
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

                        <button type="submit" class="btn btn-custom w-100 mt-2">Daftar Sekarang</button>

                        <p class="text-center mt-3">
                            Sudah punya akun? <a href="{{ route('login') }}">Login</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
