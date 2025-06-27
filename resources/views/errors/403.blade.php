<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Ditolak - 403</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }
        .container {
            max-width: 600px;
            margin-top: 100px;
            text-align: center;
        }
        .error-title {
            font-size: 4rem;
            font-weight: bold;
        }
        .error-message {
            font-size: 1.5rem;
            color: #6c757d;
        }
        .btn-custom {
            background-color: #007bff;
            color: white;
            border-radius: 2rem;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="error-title">403</h1>
    <p class="error-message">Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman ini.</p>
    <a href="{{ url('/') }}" class="btn btn-custom mt-3">Kembali ke Beranda</a>
</div>

</body>
</html>
