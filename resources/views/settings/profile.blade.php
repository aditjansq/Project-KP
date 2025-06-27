@extends('layouts.app')

@section('title', 'Pengaturan Akun')

@section('content')
<style>
    /* Custom Styles for Settings Page */
    body {
        background-color: #f0f2f5; /* Light gray background for a clean look */
        font-family: 'Poppins', sans-serif; /* Ensure Poppins is used */
        color: #333;
    }

    .container.py-4 {
        padding-top: 3rem !important;
        padding-bottom: 3rem !important;
    }

    .header-section {
        background-color: #ffffff;
        padding: 2.5rem;
        border-radius: 1.5rem;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 2.5rem;
        text-align: center;
    }

    .header-section h4 {
        font-weight: 700;
        color: #222B40;
        margin-bottom: 0.5rem;
        font-size: 2.2rem;
    }

    .header-section p {
        color: #6c757d;
        font-size: 1rem;
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* Card Styling */
    .settings-card {
        border: none;
        border-radius: 1.5rem; /* More rounded corners */
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08); /* Soft, prominent shadow */
        overflow: hidden; /* Ensure content respects border-radius */
    }

    .card-body {
        padding: 3rem; /* Generous padding inside the card */
    }

    /* Form Labels & Inputs */
    .form-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    .form-control {
        border: 1px solid #ced4da;
        border-radius: 0.75rem; /* Rounded input fields */
        padding: 0.85rem 1.25rem; /* Comfortable padding */
        font-size: 0.9rem;
        transition: all 0.3s ease;
        background-color: #fdfdfe;
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
        background-color: #ffffff;
    }

    .form-control:disabled,
    .form-control[readonly] {
        background-color: #e9ecef; /* Distinct background for disabled fields */
        color: #6c757d;
        cursor: not-allowed;
        opacity: 0.9;
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.075);
    }

    /* Input group for icons */
    .input-group-icon {
        position: relative;
    }

    .input-group-icon .form-control {
        padding-left: 3rem; /* Make space for the icon */
    }

    .input-group-icon .input-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
        font-size: 1.1rem;
        z-index: 5;
    }

    /* Buttons */
    .btn-custom {
        padding: 0.85rem 1.75rem;
        border-radius: 2rem; /* Pill-shaped buttons */
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .btn-secondary-custom {
        background-color: #6c757d;
        border-color: #6c757d;
        color: #fff;
    }

    .btn-secondary-custom:hover {
        background-color: #5a6268;
        border-color: #545b62;
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    .btn-success-custom {
        background-color: #28a745;
        border-color: #28a745;
        color: #fff;
    }

    .btn-success-custom:hover {
        background-color: #218838;
        border-color: #1e7e34;
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(40, 167, 69, 0.25);
    }

    /* Alerts */
    .alert {
        border-radius: 0.75rem;
        padding: 1.25rem 1.5rem;
        margin-bottom: 2rem;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }

    .alert .bi {
        font-size: 1.5rem;
        margin-right: 1rem;
    }

    .alert-success {
        background-color: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
    }

    .alert-danger {
        background-color: #f8d7da;
        border-color: #f5c6cb;
        color: #721c24;
    }

    .alert-success .btn-close, .alert-danger .btn-close {
        color: inherit;
        opacity: 0.8;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .header-section {
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        .header-section h4 {
            font-size: 1.8rem;
        }
        .header-section p {
            font-size: 0.9rem;
        }
        .card-body {
            padding: 1.5rem;
        }
        .form-control {
            padding: 0.75rem 1rem;
            font-size: 0.85rem;
        }
        .input-group-icon .form-control {
            padding-left: 2.5rem;
        }
        .input-group-icon .input-icon {
            left: 0.75rem;
            font-size: 1rem;
        }
        .btn-custom {
            padding: 0.75rem 1.25rem;
            font-size: 0.85rem;
        }
        .alert {
            padding: 1rem;
            font-size: 0.85rem;
        }
        .alert .bi {
            font-size: 1.2rem;
            margin-right: 0.75rem;
        }
    }
</style>

<div class="container py-4">
    <div class="header-section">
        <h4 class="fw-bold">Pengaturan Akun</h4>
        <p class="text-muted mb-0">Kelola informasi pribadi dan keamanan akun Anda di sini.</p>
    </div>

    <!-- Display Success Message -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show animate__animated animate__fadeInDown" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Display Error Messages -->
    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show animate__animated animate__fadeInDown" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <div>
            @foreach ($errors->all() as $error)
            <p class="mb-0">{{ $error }}</p>
            @endforeach
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Account Settings Form Card -->
    <div class="card settings-card">
        <div class="card-body">
            <form method="POST" action="{{ route('settings.update') }}">
                @csrf

                <div class="row">
                    <!-- User Name (Disabled) -->
                    <div class="col-md-6 mb-4">
                        <label for="name" class="form-label">Nama</label>
                        <div class="input-group-icon">
                            <i class="bi bi-person-fill input-icon"></i>
                            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}" disabled>
                        </div>
                    </div>

                    <!-- Email (Disabled) -->
                    <div class="col-md-6 mb-4">
                        <label for="email" class="form-label">Email</label>
                        <div class="input-group-icon">
                            <i class="bi bi-envelope-fill input-icon"></i>
                            <input type="email" id="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email) }}" disabled>
                        </div>
                    </div>

                    <!-- Phone Number (Disabled) -->
                    <div class="col-md-6 mb-4">
                        <label for="no_hp" class="form-label">No HP</label>
                        <div class="input-group-icon">
                            <i class="bi bi-phone-fill input-icon"></i>
                            <input type="text" id="no_hp" name="no_hp" class="form-control" value="{{ old('no_hp', auth()->user()->no_hp) }}" disabled>
                        </div>
                    </div>

                    <!-- Job (Disabled) -->
                    <div class="col-md-6 mb-4">
                        <label for="job" class="form-label">Pekerjaan</label>
                        <div class="input-group-icon">
                            <i class="bi bi-briefcase-fill input-icon"></i>
                            <input type="text" id="job" name="job" class="form-control" value="{{ old('job', auth()->user()->job) }}" disabled>
                        </div>
                    </div>

                    <div class="col-12"><hr class="my-4"></div>

                    <!-- Current Password -->
                    <div class="col-md-6 mb-4">
                        <label for="current_password" class="form-label">Kata Sandi Saat Ini</label>
                        <div class="input-group-icon">
                            <i class="bi bi-lock-fill input-icon"></i>
                            <input type="password" id="current_password" name="current_password" class="form-control" placeholder="Masukkan kata sandi saat ini" required>
                        </div>
                    </div>

                    <!-- New Password -->
                    <div class="col-md-6 mb-4">
                        <label for="password" class="form-label">Password Baru (Opsional)</label>
                        <div class="input-group-icon">
                            <i class="bi bi-key-fill input-icon"></i>
                            <input type="password" id="password" name="password" class="form-control" placeholder="Biarkan kosong jika tidak ingin mengubah">
                        </div>
                    </div>

                    <!-- Confirm New Password -->
                    <div class="col-md-6 mb-4">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                        <div class="input-group-icon">
                            <i class="bi bi-key-fill input-icon"></i>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Konfirmasi password baru">
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4 pt-3">
                    <a href="{{ route('dashboard') }}" class="btn btn-custom btn-secondary-custom">
                        <i class="bi bi-arrow-left me-2"></i>Kembali
                    </a>
                    <button type="submit" class="btn btn-custom btn-success-custom">
                        <i class="bi bi-save me-2"></i>Perbarui Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
