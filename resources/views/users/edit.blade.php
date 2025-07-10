@extends('layouts.app')

@section('title', 'Edit Pengguna')

@section('content')
<div class="container-fluid py-4 px-3 px-md-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-baseline gap-3">
            <h4 class="fw-bold text-dark mb-0">Edit Data Pengguna</h4>
            <p class="text-muted small mb-0">Perbarui informasi pengguna yang terdaftar.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-3 animate__animated animate__fadeInDown" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 rounded-3 animate__animated animate__fadeInDown" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    <div class="card border-0 shadow-xl rounded-4 animate__animated animate__fadeInUp">
        <div class="card-header bg-white p-4 border-bottom-0">
            <h5 class="card-title mb-0 text-dark fw-bold">Form Edit Pengguna</h5>
            <p class="card-text text-muted">Isi kolom di bawah untuk memperbarui data pengguna.</p>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="name" class="form-label text-dark fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" class="form-control rounded-3 shadow-sm @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required aria-describedby="nameHelp">
                    <div id="nameHelp" class="form-text text-muted">Masukkan nama lengkap pengguna.</div>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="form-label text-dark fw-semibold">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control rounded-3 shadow-sm @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required aria-describedby="emailHelp">
                    <div id="emailHelp" class="form-text text-muted">Gunakan format email yang valid.</div>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="job" class="form-label text-dark fw-semibold">Peran (Job) <span class="text-danger">*</span></label>
                    <select class="form-select rounded-3 shadow-sm @error('job') is-invalid @enderror" id="job" name="job" required aria-describedby="jobHelp">
                        <option value="">Pilih Peran</option>
                        <option value="admin" {{ old('job', $user->job) == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="manajer" {{ old('job', $user->job) == 'manajer' ? 'selected' : '' }}>Manajer</option>
                        <option value="sales" {{ old('job', $user->job) == 'sales' ? 'selected' : '' }}>Sales</option>
                    </select>
                    <div id="jobHelp" class="form-text text-muted">Pilih peran pengguna dalam sistem.</div>
                    @error('job')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label text-dark fw-semibold">Kata Sandi Baru (Opsional)</label>
                    <input type="password" class="form-control rounded-3 shadow-sm @error('password') is-invalid @enderror" id="password" name="password" aria-describedby="passwordHelp">
                    <div id="passwordHelp" class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah kata sandi.</div>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label text-dark fw-semibold">Konfirmasi Kata Sandi Baru</label>
                    <input type="password" class="form-control rounded-3 shadow-sm" id="password_confirmation" name="password_confirmation" aria-describedby="passwordConfirmationHelp">
                    <div id="passwordConfirmationHelp" class="form-text text-muted">Masukkan kembali kata sandi baru untuk konfirmasi.</div>
                </div>

                <div class="d-flex justify-content-end gap-2 pt-3">
                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary px-4 rounded-pill">
                        <i class="bi bi-arrow-left me-2"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary px-4 rounded-pill animate__animated animate__fadeInRight">
                        <i class="bi bi-save-fill me-2"></i> Perbarui Pengguna
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    body {
        background-color: #f8f9fa;
        font-family: 'Poppins', sans-serif;
        color: #343a40;
    }

    .container-fluid.py-4 {
        padding-top: 2.5rem !important;
        padding-bottom: 2.5rem !important;
    }

    .card {
        border-radius: 1rem !important;
        overflow: hidden;
        box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.09) !important;
    }

    .card-header {
        background-color: #ffffff;
        border-bottom: 1px solid #e9ecef;
    }

    .form-control, .form-select {
        padding: 0.75rem 1.25rem;
        border: 1px solid #ced4da;
        transition: all 0.3s ease;
    }
    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    .form-label {
        font-size: 0.95rem;
        margin-bottom: 0.5rem;
    }

    .btn-primary {
        background: linear-gradient(45deg, #0d6efd, #0b5ed7);
        border: none;
        transition: all 0.3s ease;
        font-weight: 600;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 10px rgba(13, 110, 253, 0.2);
    }
    .btn-primary:hover {
        background: linear-gradient(45deg, #0b5ed7, #0d6efd);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    }

    .btn-outline-secondary {
        border-color: #6c757d;
        color: #6c757d;
    }
    .btn-outline-secondary:hover {
        background-color: #6c757d;
        color: #fff;
        box-shadow: 0 4px 10px rgba(108, 117, 125, 0.2);
    }

    .alert {
        border-radius: 0.75rem !important;
        font-size: 0.95rem;
    }
    .alert-success {
        background-color: #d1e7dd;
        color: #0f5132;
        border-color: #badbcc;
    }
    .alert-danger {
        background-color: #f8d7da;
        color: #842029;
        border-color: #f5c2c7;
    }
</style>
@endsection
