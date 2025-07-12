<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Verifikasi OTP</title>

  <!-- Bootstrap 5 CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />

  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Poppins', sans-serif;
      margin: 0;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 1rem;
    }

    .otp-container {
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

    .otp-container h4 {
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

    .input-group-text {
      background-color: #f8f9fa;
      border: 1px solid #ced4da;
      border-radius: 0.75rem 0 0 0.75rem;
      color: #000; /* icon warna hitam */
      font-size: 1.1rem;
      padding: 0.6rem 0.75rem;
    }

    .form-control {
      border-radius: 0 0.75rem 0.75rem 0;
      border-left: none;
      padding: 0.75rem 1rem;
      border: 1px solid #ced4da;
      font-size: 1rem;
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
  </style>
</head>
<body>

  <div class="otp-container">
    <h4>Verifikasi OTP Anda</h4>

    @if(session('success'))
      <div class="alert alert-success">
        {{ session('success') }}
      </div>
    @endif

    @if($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0 ps-3">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('otp.verify') }}" method="POST">
      @csrf

      <div class="mb-4">
        <label for="otp" class="form-label">Kode OTP</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-lock"></i></span>
          <input
            type="text"
            id="otp"
            name="otp"
            class="form-control"
            placeholder="Masukkan kode OTP"
            required
            maxlength="6"
            pattern="\d{6}"
            title="Masukkan 6 digit angka OTP"
            inputmode="numeric"
            autocomplete="one-time-code"
            autofocus
          />
        </div>
      </div>

      <button type="submit" class="btn btn-primary w-100">Verifikasi</button>
    </form>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
