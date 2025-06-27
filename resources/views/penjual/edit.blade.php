@extends('layouts.app')

@section('title', 'Edit Penjual')

@section('content')
<head>
    {{-- Animate.css for subtle animations --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>
<div class="container-fluid py-4 px-3 px-md-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8 mb-3 mb-md-0">
            <h4 class="text-dark fw-bold mb-0">Edit Data Penjual</h4>
            <small class="text-secondary">Perbarui informasi penjual di formulir di bawah ini.</small>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('penjual.index') }}" class="btn btn-outline-secondary rounded-pill animate__animated animate__fadeInRight">
                <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar Penjual
            </a>
        </div>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger border-0 shadow-sm rounded-3 animate__animated animate__shakeX" role="alert">
        <h6 class="alert-heading fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i> Terjadi Kesalahan Input:</h6>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card border-0 shadow-xl rounded-4 animate__animated animate__fadeInUp">
        <div class="card-body p-lg-5 p-md-4 p-3">
            <form method="POST" action="{{ route('penjual.update', $penjual->id) }}" novalidate>
                @csrf
                @method('PUT')

                @php
                    $old = fn($field) => old($field, $penjual->$field);
                @endphp

                <h5 class="mb-4 fw-bold text-dark border-bottom pb-2">Informasi Penjual</h5>
                <div class="row g-3 mb-4"> {{-- Added g-3 for consistent spacing --}}
                    <div class="col-md-6">
                        <label for="kode_penjual" class="form-label text-muted">Kode Penjual</label>
                        <input type="text" name="kode_penjual" id="kode_penjual" class="form-control form-control-lg bg-light-subtle rounded-pill border-0 shadow-sm" value="{{ $penjual->kode_penjual }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label for="nama" class="form-label text-muted">Nama Penjual</label>
                        <input type="text" name="nama" id="nama" class="form-control form-control-lg rounded-pill shadow-sm" value="{{ $old('nama') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="tanggal_lahir" class="form-label text-muted">Tanggal Lahir</label>
                        {{-- Set max attribute to today's date to prevent future dates --}}
                        <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control form-control-lg rounded-pill shadow-sm" value="{{ $old('tanggal_lahir') }}" max="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="no_telepon" class="form-label text-muted">No. Telepon</label>
                        <input type="text" name="no_telepon" id="no_telepon" class="form-control form-control-lg rounded-pill shadow-sm" value="{{ $old('no_telepon') }}">
                    </div>
                    <div class="col-md-12">
                        <label for="alamat" class="form-label text-muted">Alamat</label>
                        <textarea name="alamat" id="alamat" class="form-control form-control-lg rounded-3 shadow-sm" rows="3" required>{{ $old('alamat') }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label for="pekerjaan" class="form-label text-muted">Pekerjaan</label>
                        <input type="text" name="pekerjaan" id="pekerjaan" class="form-control form-control-lg rounded-pill shadow-sm" value="{{ $old('pekerjaan') }}">
                    </div>
                </div>

                <div class="mt-5 d-flex justify-content-end"> {{-- Menghapus gap-3 dan hanya menyisakan tombol Update Data --}}
                    <button type="submit" class="btn btn-success btn-lg px-4 rounded-pill shadow-sm animate__animated animate__pulse animate__infinite">
                        <i class="bi bi-save me-2"></i>Update Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Add custom styles here for consistency */
    body {
        background-color: #f8f9fa;
        font-family: 'Poppins', sans-serif;
        color: #343a40;
    }

    .container-fluid.py-4 {
        padding-top: 2.5rem !important;
        padding-bottom: 2.5rem !important;
    }

    .form-label {
        font-size: 0.9rem;
        font-weight: 500;
        color: #555;
    }

    .form-control-lg, .form-select-lg {
        padding: 0.75rem 1.25rem;
        border-radius: 0.75rem !important;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        border: 1px solid #dee2e6; /* subtle border */
    }

    /* Textarea doesn't get pill shape */
    textarea.form-control-lg {
        border-radius: 0.75rem !important;
    }

    .form-control-lg:focus, .form-select-lg:focus, textarea.form-control:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13,110,253,.25), 0 2px 8px rgba(0,0,0,0.1);
    }

    .bg-light-subtle {
        background-color: #f8f9fa !important;
    }

    /* Card Styling */
    .card {
        border-radius: 1rem !important;
        overflow: hidden;
        box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.09) !important;
    }

    /* Alert Styling */
    .alert-danger {
        background-color: #fef2f2;
        color: #721c24;
        border: 1px solid #f5c6cb;
        border-radius: 0.75rem;
        padding: 1.25rem 1.75rem;
    }
    .alert-danger .alert-heading {
        color: #dc3545;
        font-size: 1.1rem;
    }
    .alert-danger ul {
        padding-left: 25px;
    }
    .alert-danger li {
        margin-bottom: 5px;
    }

    /* Buttons */
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
        transition: all 0.3s ease;
    }
    .btn-outline-secondary:hover {
        background-color: #6c757d;
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .btn-success {
        background: linear-gradient(45deg, #28a745, #218838);
        border: none;
        transition: all 0.3s ease;
        font-weight: 600;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 10px rgba(40, 167, 69, 0.2);
    }
    .btn-success:hover {
        background: linear-gradient(45deg, #218838, #28a745);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    }
    .animate__pulse {
        animation-duration: 2s; /* Increase animation duration */
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .btn-lg {
            width: 100%;
            margin-bottom: 1rem;
        }
        .d-flex.justify-content-end.gap-3 {
            flex-direction: column;
            gap: 1rem;
        }
        .d-flex.justify-content-between { /* Adjust for the row with back/update buttons */
            flex-direction: column-reverse; /* Put back button on top, then update */
            gap: 1rem;
        }
    }
</style>

<!-- Include Bootstrap JS (Bundled with Popper for modals/dropdowns) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
