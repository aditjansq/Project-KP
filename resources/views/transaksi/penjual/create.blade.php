@extends('layouts.app')

@section('title', 'Tambah Transaksi Penjual Baru')

@section('content')
<head>
    {{-- Select2 CSS for enhanced dropdowns --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    {{-- Animate.css for subtle animations --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>
<div class="container-fluid py-4 px-3 px-md-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8 mb-3 mb-md-0">
            <h4 class="text-dark fw-bold mb-0">Tambah Transaksi Penjual Baru</h4>
            <small class="text-secondary">Isi detail transaksi untuk penjual.</small>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('transaksi.penjual.index') }}" class="btn btn-outline-secondary rounded-pill animate__animated animate__fadeInRight">
                <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar Transaksi Penjual
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
            <form method="POST" action="{{ route('transaksi.penjual.store') }}">
                @csrf

                <h5 class="mb-4 fw-bold text-dark border-bottom pb-2">Informasi Transaksi</h5>
                <div class="row g-3 mb-4">
                    <!-- Kode Transaksi (Readonly) -->
                    <div class="col-md-6">
                        <label for="kode_transaksi" class="form-label text-muted">Kode Transaksi</label>
                        <input type="text" class="form-control form-control-lg bg-light-subtle rounded-pill border-0 shadow-sm" name="kode_transaksi" id="kode_transaksi" value="{{ $kode_transaksi }}" readonly />
                    </div>

                    <!-- Tanggal Transaksi -->
                    <div class="col-md-6">
                        <label for="tanggal_transaksi" class="form-label text-muted">Tanggal Transaksi</label>
                        <input type="date" class="form-control form-control-lg rounded-pill shadow-sm @error('tanggal_transaksi') is-invalid @enderror" id="tanggal_transaksi" name="tanggal_transaksi" value="{{ old('tanggal_transaksi', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}" required>
                        @error('tanggal_transaksi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Pilih Mobil (Select2) -->
                    <div class="col-md-6">
                        <label for="mobil_id" class="form-label text-muted">Pilih Mobil</label>
                        <select class="form-control select2 form-select-lg rounded-pill shadow-sm @error('mobil_id') is-invalid @enderror" name="mobil_id" id="mobil_id" style="width: 100%" required>
                            <option value="">Pilih Mobil (Tahun - Merek - Tipe - No. Polisi)</option>
                            @foreach($mobils as $mobil)
                                <option value="{{ $mobil->id }}" {{ old('mobil_id') == $mobil->id ? 'selected' : '' }}>
                                    {{ $mobil->tahun_pembuatan ?? '' }} {{ $mobil->merek_mobil }} {{ $mobil->tipe_mobil ?? '' }} - {{ $mobil->nomor_polisi }}
                                </option>
                            @endforeach
                        </select>
                        @error('mobil_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Pilih Penjual (Select2) -->
                    <div class="col-md-6">
                        <label for="penjual_id" class="form-label text-muted">Pilih Penjual</label>
                        <select class="form-control select2 form-select-lg rounded-pill shadow-sm @error('penjual_id') is-invalid @enderror" name="penjual_id" id="penjual_id" style="width: 100%" required>
                            <option value="">Pilih Penjual (Nama - Email)</option>
                            @foreach($penjuals as $penjual)
                                <option value="{{ $penjual->id }}" {{ old('penjual_id') == $penjual->id ? 'selected' : '' }}>
                                    {{ $penjual->nama }} ({{ $penjual->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('penjual_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Total Harga -->
                    <div class="col-md-6">
                        <label for="total_harga" class="form-label text-muted">Total Harga</label>
                        <input type="number" class="form-control form-control-lg rounded-pill shadow-sm @error('total_harga') is-invalid @enderror" id="total_harga" name="total_harga" value="{{ old('total_harga', 0) }}" min="0" required>
                        @error('total_harga')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <!-- Button Simpan -->
                <div class="mt-5 d-flex justify-content-end gap-3">
                    <button type="submit" class="btn btn-success btn-lg px-4 rounded-pill shadow-sm animate__animated animate__pulse animate__infinite">
                        <i class="bi bi-save me-2"></i> Simpan Transaksi Penjual
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    body { background-color: #f8f9fa; font-family: 'Poppins', sans-serif; color: #343a40; }
    .container-fluid.py-4 { padding-top: 2.5rem !important; padding-bottom: 2.5rem !important; }
    .form-label { font-size: 0.9rem; font-weight: 500; color: #555; }
    .form-control-lg, .form-select-lg { padding: 0.75rem 1.25rem; border-radius: 0.75rem !important; box-shadow: 0 2px 5px rgba(0,0,0,0.05); border: 1px solid #dee2e6; }
    .form-control-lg:focus, .form-select-lg:focus { border-color: #86b7fe; box-shadow: 0 0 0 0.25rem rgba(13,110,253,.25), 0 2px 8px rgba(0,0,0,0.1); }
    .bg-light-subtle { background-color: #f8f9fa !important; }
    .card { border-radius: 1rem !important; overflow: hidden; box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.09) !important; }
    .alert-danger { background-color: #fef2f2; color: #721c24; border: 1px solid #f5c6cb; border-radius: 0.75rem; padding: 1.25rem 1.75rem; }
    .alert-danger .alert-heading { color: #dc3545; font-size: 1.1rem; }
    .alert-danger ul { padding-left: 25px; }
    .alert-danger li { margin-bottom: 5px; }
    .btn-outline-secondary { border-color: #6c757d; color: #6c757d; transition: all 0.3s ease; }
    .btn-outline-secondary:hover { background-color: #6c757d; color: white; transform: translateY(-1px); box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .btn-success { background: linear-gradient(45deg, #28a745, #218838); border: none; transition: all 0.3s ease; font-weight: 600; letter-spacing: 0.5px; box-shadow: 0 4px 10px rgba(40, 167, 69, 0.2); }
    .btn-success:hover { background: linear-gradient(45deg, #218838, #28a745); transform: translateY(-2px); box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1); }
    .animate__pulse { animation-duration: 2s; }
    /* Select2 Custom Styling */
    .select2-container--bootstrap-5 .select2-selection { border-radius: 0.75rem !important; height: calc(2.8rem + 2px); padding-top: 0.75rem; padding-bottom: 0.75rem; display: flex; align-items: center; border: 1px solid #dee2e6; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
    .select2-container--bootstrap-5.select2-container--focus .select2-selection, .select2-container--bootstrap-5.select2-container--open .select2-selection { border-color: #86b7fe; box-shadow: 0 0 0 0.25rem rgba(13,110,253,.25), 0 2px 8px rgba(0,0,0,0.1); }
    .select2-container--bootstrap-5 .select2-selection__arrow { height: 100%; display: flex; align-items: center; padding-right: 0.75rem; }
    .select2-container--bootstrap-5 .select2-selection__placeholder { color: #6c757d; line-height: 1.5; }
    .select2-container--bootstrap-5 .select2-selection__rendered { color: #495057; line-height: 1.5; padding-left: 1.25rem; }
    .select2-container--bootstrap-5 .select2-dropdown { border-radius: 0.75rem; border: 1px solid #dee2e6; box-shadow: 0 5px 15px rgba(0,0,0,0.1); z-index: 1056; }
    .select2-container--bootstrap-5 .select2-results__option { padding: 0.75rem 1.25rem; font-size: 0.9rem; }
    .select2-container--bootstrap-5 .select2-results__option--highlighted.select2-results__option--selectable { background-color: #0d6efd; color: white; }
    .select2-container--bootstrap-5 .select2-results__option--selected { background-color: #e9ecef; color: #495057; }
    @media (max-width: 768px) { .btn-lg { width: 100%; margin-bottom: 1rem; } .d-flex.justify-content-end.gap-3 { flex-direction: column; gap: 1rem; } }
</style>

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Include Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- Include Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#mobil_id').select2({
            theme: "bootstrap-5",
            placeholder: 'Pilih mobil...',
            allowClear: true
        });
        $('#penjual_id').select2({
            theme: "bootstrap-5",
            placeholder: 'Pilih penjual...',
            allowClear: true
        });
    });
</script>
@endsection
