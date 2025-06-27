@extends('layouts.app')

@section('title', 'Tambah Transaksi Pembeli Baru')

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
            <h4 class="text-dark fw-bold mb-0">Tambah Transaksi Pembeli Baru</h4>
            <small class="text-secondary">Isi detail transaksi untuk pembeli.</small>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('transaksi.pembeli.index') }}" class="btn btn-outline-secondary rounded-pill animate__animated animate__fadeInRight">
                <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar Transaksi Pembeli
            </a>
        </div>
    </div>

    @if (session('success'))
    <div class="alert alert-success border-0 shadow-sm rounded-3 animate__animated animate__fadeInDown" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
    @endif

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
            <form id="transaksiForm" method="POST" action="{{ route('transaksi.pembeli.store') }}">
                @csrf

                <h5 class="mb-4 fw-bold text-dark border-bottom pb-2">Informasi Transaksi</h5>
                <div class="row g-3 mb-4">
                    <!-- Kode Transaksi (Readonly) -->
                    <div class="col-md-6">
                        <label for="kode_transaksi" class="form-label text-muted">Kode Transaksi</label>
                        <input type="text" class="form-control form-control-lg bg-light rounded-pill shadow-sm" name="kode_transaksi" id="kode_transaksi" value="{{ $kode_transaksi }}" readonly />
                    </div>

                    <!-- Tanggal Transaksi -->
                    <div class="col-md-6">
                        @php
                            $today = date('Y-m-d');
                            $threeDaysAgo = date('Y-m-d', strtotime('-3 days'));
                        @endphp
                        <label for="tanggal_transaksi" class="form-label text-muted">Tanggal Transaksi</label>
                        {{-- Set min attribute to 3 days ago and max to today's date --}}
                        <input type="date" class="form-control form-control-lg rounded-pill shadow-sm @error('tanggal_transaksi') is-invalid @enderror" id="tanggal_transaksi" name="tanggal_transaksi" value="{{ old('tanggal_transaksi', $today) }}" min="{{ $threeDaysAgo }}" max="{{ $today }}" required>
                        @error('tanggal_transaksi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Bagian Mobil (Kolom Kiri) --}}
                    <div class="col-md-6">
                        <!-- Pilih Mobil (Select2) -->
                        <label for="mobil_id" class="form-label text-muted">Pilih Mobil</label>
                        <select class="form-control select2 form-select-lg rounded-pill shadow-sm @error('mobil_id') is-invalid @enderror" name="mobil_id" id="mobil_id" style="width: 100%" required>
                            <option value="">Pilih Mobil (Tahun - Merek - Tipe - No. Polisi)</option>
                            @foreach($mobils as $mobil)
                                <option
                                    value="{{ $mobil->id }}"
                                    data-merek="{{ $mobil->merek_mobil }}"
                                    data-tipe="{{ $mobil->tipe_mobil ?? '' }}"
                                    data-transmisi="{{ $mobil->transmisi ?? '' }}"
                                    data-tahun="{{ $mobil->tahun_pembuatan ?? '' }}"
                                    data-nomorpolisi="{{ $mobil->nomor_polisi }}"
                                    data-warnamobil="{{ $mobil->warna_mobil ?? '' }}"
                                    data-harga="{{ $mobil->harga_mobil ?? 0 }}"
                                    {{ old('mobil_id') == $mobil->id ? 'selected' : '' }}>
                                    {{ $mobil->tahun_pembuatan ?? '' }} {{ $mobil->merek_mobil }} {{ $mobil->tipe_mobil ?? '' }} - {{ $mobil->nomor_polisi }}
                                </option>
                            @endforeach
                        </select>
                        @error('mobil_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <!-- Informasi Mobil (Auto Fill) - UI Perbaikan -->
                        <div class="mt-4"> {{-- Tambahkan margin-top untuk pemisah --}}
                            <label class="form-label text-muted">Detail Mobil</label>
                            <div class="p-3 bg-light rounded-4 shadow-sm border border-secondary-subtle info-detail-block">
                                <div class="mb-1"> {{-- Adjusted margin --}}
                                    <label class="form-label small text-secondary mb-0">Merek</label> {{-- Changed to text-secondary --}}
                                    <input type="text" class="form-control form-control-plaintext fw-bold" id="mobil_detail_merek" readonly />
                                </div>
                                <div class="mb-1"> {{-- Adjusted margin --}}
                                    <label class="form-label small text-secondary mb-0">Model / Tipe</label> {{-- Changed to text-secondary --}}
                                    <input type="text" class="form-control form-control-plaintext" id="mobil_detail_tipe" readonly />
                                </div>
                                <div class="mb-1"> {{-- Adjusted margin --}}
                                    <label class="form-label small text-secondary mb-0">Transmisi</label> {{-- Changed to text-secondary --}}
                                    <input type="text" class="form-control form-control-plaintext" id="mobil_detail_transmisi" readonly />
                                </div>
                                <div class="mb-1"> {{-- Adjusted margin --}}
                                    <label class="form-label small text-secondary mb-0">Tahun</label> {{-- Changed to text-secondary --}}
                                    <input type="text" class="form-control form-control-plaintext" id="mobil_detail_tahun" readonly />
                                </div>
                                <div class="mb-1"> {{-- Adjusted margin --}}
                                    <label class="form-label small text-secondary mb-0">No. Plat</label> {{-- Changed to text-secondary --}}
                                    <input type="text" class="form-control form-control-plaintext" id="mobil_detail_nomorpolisi" readonly />
                                </div>
                                <div>
                                    <label class="form-label small text-secondary mb-0">Warna</label> {{-- Changed to text-secondary --}}
                                    <input type="text" class="form-control form-control-plaintext" id="mobil_detail_warna" readonly />
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Bagian Pembeli (Kolom Kanan) --}}
                    <div class="col-md-6">
                        <!-- Pilih Pembeli (Select2) -->
                        <label for="pembeli_id" class="form-label text-muted">Pilih Pembeli</label>
                        <select class="form-control select2 form-select-lg rounded-pill shadow-sm @error('pembeli_id') is-invalid @enderror" name="pembeli_id" id="pembeli_id" style="width: 100%" required>
                            <option value="">Pilih Pembeli (Nama)</option>
                            @foreach($pembelis as $pembeli)
                                <option
                                    value="{{ $pembeli->id }}"
                                    data-nama="{{ $pembeli->nama }}"
                                    data-email="{{ $pembeli->email }}"
                                    data-notelepon="{{ $pembeli->no_telepon ?? '' }}"
                                    data-alamat="{{ $pembeli->alamat ?? '' }}"
                                    {{ old('pembeli_id') == $pembeli->id ? 'selected' : '' }}>
                                    {{ $pembeli->nama }} {{-- Tanda kurung () dan email dihapus dari tampilan dropdown --}}
                                </option>
                            @endforeach
                        </select>
                        @error('pembeli_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <!-- Informasi Pembeli (Auto Fill) - UI Perbaikan -->
                        <div class="mt-4"> {{-- Tambahkan margin-top untuk pemisah --}}
                            <label class="form-label text-muted">Informasi Pembeli</label>
                            <div class="p-3 bg-light rounded-4 shadow-sm border border-secondary-subtle info-detail-block">
                                <div class="mb-1"> {{-- Adjusted margin --}}
                                    <label class="form-label small text-secondary mb-0">Nama & Email</label> {{-- Changed to text-secondary --}}
                                    <input type="text" class="form-control form-control-plaintext fw-bold" id="pembeli_info_nama_email" readonly />
                                </div>
                                <div class="mb-1"> {{-- Adjusted margin --}}
                                    <label class="form-label small text-secondary mb-0">Nomor Telepon</label> {{-- Changed to text-secondary --}}
                                    <input type="text" class="form-control form-control-plaintext" id="pembeli_info_telepon" readonly />
                                </div>
                                <div>
                                    <label class="form-label small text-secondary mb-0">Alamat</label> {{-- Changed to text-secondary --}}
                                    <input type="text" class="form-control form-control-plaintext" id="pembeli_info_alamat" readonly />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Metode Pembayaran -->
                    <div class="col-md-6">
                        <label for="metode_pembayaran" class="form-label text-muted">Pilih Metode Pembayaran</label>
                        <select class="form-control select2 form-select-lg rounded-pill shadow-sm @error('metode_pembayaran') is-invalid @enderror" id="metode_pembayaran" name="metode_pembayaran" style="width: 100%" required> {{-- Added select2 class and style --}}
                            <option value="">Pilih Metode</option>
                            <option value="Transfer Bank" {{ old('metode_pembayaran') == 'Transfer Bank' ? 'selected' : '' }}>Transfer Bank</option>
                            <option value="Cash" {{ old('metode_pembayaran') == 'Cash' ? 'selected' : '' }}>Cash</option>
                            <option value="Kartu Kredit" {{ old('metode_pembayaran') == 'Kartu Kredit' ? 'selected' : '' }}>Kartu Kredit</option>
                            <option value="Debit Card" {{ old('metode_pembayaran') == 'Debit Card' ? 'selected' : '' }}>Debit Card</option>
                            <option value="BCA Virtual Account" {{ old('metode_pembayaran') == 'BCA Virtual Account' ? 'selected' : '' }}>BCA Virtual Account</option>
                        </select>
                        @error('metode_pembayaran')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Input Diskon (Persen) -->
                    <div class="col-md-6">
                        <label for="diskon_persen" class="form-label text-muted">Diskon (%)</label>
                        <input type="number" class="form-control form-control-lg rounded-pill shadow-sm @error('diskon_persen') is-invalid @enderror" id="diskon_persen" name="diskon_persen" value="{{ old('diskon_persen', 0) }}" min="0" max="100">
                        @error('diskon_persen')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Total Harga (Otomatis dari Harga Mobil - Diskon) - UI Perbaikan -->
                    {{-- Diubah menjadi col-md-12 untuk mengisi penuh lebar --}}
                    <div class="col-md-12 mb-4">
                        <label class="form-label text-muted">Nominal yang harus dibayar</label>
                        {{-- Tambahkan w-100 pada div ini untuk memastikan memenuhi lebar --}}
                        <div class="p-3 bg-light rounded-4 shadow-sm border border-primary d-flex align-items-center info-total-price-block w-100">
                            {{-- Input tersembunyi untuk menyimpan nilai numerik total_harga --}}
                            <input type="hidden" name="total_harga" id="total_harga_hidden" value="{{ old('total_harga', 0) }}">
                            {{-- Input teks untuk menampilkan total_harga dengan format Rupiah --}}
                            <input type="text" class="form-control-plaintext text-center fw-bold fs-4 text-primary" id="total_harga_display" value="Rp 0" readonly />
                        </div>
                    </div>

                    <!-- Keterangan -->
                    {{-- Diubah menjadi col-md-6 untuk setengah lebar --}}
                    <div class="col-md-6">
                        <label for="keterangan" class="form-label text-muted">Keterangan (Opsional)</label>
                        <textarea class="form-control form-control-lg rounded-3 shadow-sm @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="3" placeholder="Masukkan detail transaksi tambahan...">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Hidden input for status_pembayaran --}}
                    {{-- Diperbarui: name="status" menjadi name="status_pembayaran" dan nilai defaultnya --}}
                    <input type="hidden" name="status_pembayaran" id="transaksi_status_pembayaran" value="Menunggu Pembayaran">

                </div>

                <!-- Button Simpan - Ganti menjadi trigger modal -->
                <div class="mt-5 d-flex justify-content-end gap-3">
                    <button type="button" class="btn btn-success btn-lg px-4 rounded-pill shadow-sm animate__animated animate__pulse animate__infinite" id="confirmSaveBtn">
                        <i class="bi bi-save me-2"></i> Simpan Transaksi Pembeli
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 shadow-lg">
            <div class="modal-header bg-primary text-white border-0 rounded-top-4">
                <h5 class="modal-title fw-bold" id="confirmationModalLabel">Konfirmasi Transaksi Pembelian</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="lead text-center mb-4">Mohon periksa kembali detail transaksi berikut sebelum menyimpan:</p>
                <div class="row g-3">
                    <div class="col-md-6">
                        <h6 class="fw-bold text-dark">Informasi Transaksi:</h6>
                        <ul class="list-group list-group-flush border rounded-3 p-2">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Kode Transaksi: <span id="modal_kode_transaksi" class="fw-bold text-primary"></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Tanggal Transaksi: <span id="modal_tanggal_transaksi" class="text-muted"></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Metode Pembayaran: <span id="modal_metode_pembayaran" class="text-muted"></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Diskon: <span id="modal_diskon_persen" class="text-muted"></span>
                            </li>
                            {{-- Menambahkan status_pembayaran ke modal --}}
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Status Pembayaran: <span id="modal_status_pembayaran_display" class="text-muted"></span>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold text-dark">Detail Pembeli:</h6>
                        <ul class="list-group list-group-flush border rounded-3 p-2">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Nama: <span id="modal_pembeli_nama_email" class="fw-bold"></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Telepon: <span id="modal_pembeli_telepon" class="text-muted"></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Alamat: <span id="modal_pembeli_alamat" class="text-muted"></span>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-12 mt-3">
                        <h6 class="fw-bold text-dark">Detail Mobil:</h6>
                        <ul class="list-group list-group-flush border rounded-3 p-2">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Merek & Tipe: <span id="modal_mobil_merek_tipe" class="fw-bold"></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Transmisi: <span id="modal_mobil_transmisi" class="text-muted"></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Tahun: <span id="modal_mobil_tahun" class="text-muted"></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                No. Plat: <span id="modal_mobil_nomorpolisi" class="text-muted"></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Warna: <span id="modal_mobil_warna" class="text-muted"></span>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-12 mt-3">
                        <p class="text-center fw-bold fs-3 text-success mb-0">Total yang Dibayar:</p>
                        <p class="text-center fw-bold fs-2 text-success" id="modal_total_harga"></p>
                    </div>
                     <div class="col-md-12">
                        <h6 class="fw-bold text-dark">Keterangan:</h6>
                        <p id="modal_keterangan" class="text-muted border rounded-3 p-2 bg-light-subtle"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-center border-0 rounded-bottom-4 py-3 gap-3">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary rounded-pill px-4" id="confirmSubmitBtn">Konfirmasi & Simpan</button>
            </div>
        </div>
    </div>
</div>


<style>
    body { background-color: #f8f9fa; font-family: 'Poppins', sans-serif; color: #343a40; }
    .container-fluid.py-4 { padding-top: 2.5rem !important; padding-bottom: 2.5rem !important; }
    .form-label { font-size: 0.9rem; font-weight: 500; color: #555; }
    .form-control-lg, .form-select-lg { padding: 0.75rem 1.25rem; border-radius: 0.75rem !important; box-shadow: 0 2px 5px rgba(0,0,0,0.05); border: 1px solid #dee2e6; }
    .form-control-lg:focus, .form-select-lg:focus { border-color: #86b7fe; box-shadow: 0 0 0 0.25rem rgba(13,110,253,.25), 0 2px 8px rgba(0,0,0,0.1); }
    /* Adjusted styling for readonly inputs for better visibility */
    .form-control[readonly] {
        background-color: #e9ecef; /* Slightly darker background to indicate readonly */
        opacity: 0.8; /* Subtle opacity for visual distinction */
        border-color: #ced4da; /* Ensure a visible border */
    }
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
    /* Custom styles for info detail blocks */
    .info-detail-block {
        margin-top: 0.5rem; /* Reduce top margin for the block itself */
        margin-bottom: 0.5rem; /* Add bottom margin for separation */
    }
    .info-detail-block .form-control-plaintext {
        padding-left: 0.75rem; /* Add some left padding */
        padding-right: 0.75rem; /* Add some right padding */
        border-radius: 0.5rem; /* Slightly less rounded than main inputs */
        background-color: #f1f3f5; /* A subtle background for each item */
        border: 1px solid #e2e6ea; /* A subtle border */
        height: auto; /* Allow height to adjust to content */
        min-height: calc(2.8rem + 2px); /* Maintain minimum height */
    }
    .info-detail-block .form-control-plaintext.fw-bold {
        background-color: #e9ecef; /* Slightly different for bolded info */
    }
    .info-detail-block .form-label.small {
        font-size: 0.75rem; /* Smaller label for details */
        margin-bottom: 0.2rem; /* Reduced margin below label */
        color: #6c757d; /* Lighter color for sub-labels */
    }
    /* Style for the total price block */
    .info-total-price-block {
        min-height: calc(2.8rem + 2px); /* Match height of form-control-lg */
        height: 100%; /* Ensure it fills the space */
    }
    .info-total-price-block .form-control-plaintext {
        background-color: transparent; /* No background for this specific plaintext input */
        border: none; /* No border for this specific plaintext input */
        padding: 0;
        text-align: center;
        width: 100%;
    }
    /* Modal specific styles */
    .modal-header.bg-primary {
        background-color: #0d6efd !important;
    }
    .btn-close-white {
        filter: invert(1) brightness(2); /* Makes the close button white */
    }
    .list-group-item {
        font-size: 0.95rem;
        padding: 0.75rem 1rem;
    }
    .list-group-item span {
        font-weight: 500;
    }
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
        $('#pembeli_id').select2({
            theme: "bootstrap-5",
            placeholder: 'Pilih pembeli...',
            allowClear: true
        });
        // Initialize Select2 for metode_pembayaran
        $('#metode_pembayaran').select2({
            theme: "bootstrap-5",
            placeholder: 'Pilih Metode',
            allowClear: true
        });


        // Function to format number as Indonesian Rupiah
        function formatRupiah(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(amount);
        }

        // Function to calculate and update total price with percentage discount
        function updateTotalPrice() {
            var selectedOption = $('#mobil_id').find(':selected');
            var basePrice = parseFloat(selectedOption.data('harga')) || 0;
            var diskonPersen = parseFloat($('#diskon_persen').val()) || 0;

            // Ensure discount percentage is between 0 and 100
            if (diskonPersen < 0) diskonPersen = 0;
            if (diskonPersen > 100) diskonPersen = 100;
            $('#diskon_persen').val(diskonPersen); // Update input field if value was out of bounds

            var discountAmount = (basePrice * diskonPersen) / 100;
            var finalPrice = basePrice - discountAmount;

            if (finalPrice < 0) {
                finalPrice = 0; // Prevent negative price
            }

            // Set hidden input for submission
            $('#total_harga_hidden').val(finalPrice);
            // Set display input with formatted Rupiah
            $('#total_harga_display').val(formatRupiah(finalPrice));
        }

        // Auto-fill Informasi Mobil when a car is selected
        $('#mobil_id').on('change', function() {
            var selectedOption = $(this).find(':selected');
            var merekMobil = selectedOption.data('merek') || '';
            var tipeMobil = selectedOption.data('tipe') || '';
            var transmisi = selectedOption.data('transmisi') || '';
            var tahun = selectedOption.data('tahun') || '';
            var nomorPolisi = selectedOption.data('nomorpolisi') || '';
            var warnaMobil = selectedOption.data('warnamobil') || '';

            // Populate individual detail fields for Mobil
            $('#mobil_detail_merek').val(merekMobil);
            $('#mobil_detail_tipe').val(tipeMobil);
            $('#mobil_detail_transmisi').val(transmisi);
            $('#mobil_detail_tahun').val(tahun);
            $('#mobil_detail_nomorpolisi').val(nomorPolisi);
            $('#mobil_detail_warna').val(warnaMobil);

            updateTotalPrice(); // Call to update total price after car selection

        }).trigger('change'); // Trigger on load to populate if old('mobil_id') exists

        // Auto-fill Informasi Pembeli when a buyer is selected
        $('#pembeli_id').on('change', function() {
            var selectedOption = $(this).find(':selected');
            var namaPembeli = selectedOption.data('nama') || '';
            var emailPembeli = selectedOption.data('email') || '';
            var noTeleponPembeli = selectedOption.data('notelepon') || '';
            var alamatPembeli = selectedOption.data('alamat') || '';

            // Populate individual detail fields for Pembeli
            $('#pembeli_info_nama_email').val(namaPembeli); // Hanya menampilkan nama tanpa email di input detail
            $('#pembeli_info_telepon').val(noTeleponPembeli || '');
            $('#pembeli_info_alamat').val(alamatPembeli ? 'Alamat: ' + alamatPembeli : '');

        }).trigger('change'); // Trigger on load to populate if old('pembeli_id') exists

        // Recalculate total price when discount percentage changes
        $('#diskon_persen').on('input', updateTotalPrice);

        // Initial calculation on page load
        updateTotalPrice();


        // Handle confirmation modal
        $('#confirmSaveBtn').on('click', function() {
            // Validate form before opening modal (optional, but good practice)
            if (!$('#transaksiForm')[0].checkValidity()) {
                $('#transaksiForm')[0].reportValidity();
                return; // Stop if form is invalid
            }

            // Populate modal with current form data
            $('#modal_kode_transaksi').text($('#kode_transaksi').val());
            $('#modal_tanggal_transaksi').text($('#tanggal_transaksi').val());
            $('#modal_metode_pembayaran').text($('#metode_pembayaran option:selected').text());
            $('#modal_diskon_persen').text($('#diskon_persen').val() + '%');
            $('#modal_total_harga').text($('#total_harga_display').val());
            $('#modal_keterangan').text($('#keterangan').val() || '-');
            $('#modal_status_pembayaran_display').text($('#transaksi_status_pembayaran').val()); // Ambil nilai dari hidden input


            // Mobil details
            $('#modal_mobil_merek_tipe').text($('#mobil_detail_merek').val() + ' ' + $('#mobil_detail_tipe').val());
            $('#modal_mobil_transmisi').text($('#mobil_detail_transmisi').val());
            $('#modal_mobil_tahun').text($('#mobil_detail_tahun').val());
            $('#modal_mobil_nomorpolisi').text($('#mobil_detail_nomorpolisi').val());
            $('#modal_mobil_warna').text($('#mobil_detail_warna').val());

            // Pembeli details for modal (using data attributes for full info)
            var selectedPembeliOption = $('#pembeli_id').find(':selected');
            var modalNamaPembeli = selectedPembeliOption.data('nama') || '';
            var modalEmailPembeli = selectedPembeliOption.data('email') || '';
            $('#modal_pembeli_nama_email').text(modalNamaPembeli + (modalEmailPembeli ? ' (' + modalEmailPembeli + ')' : ''));
            $('#modal_pembeli_telepon').text(selectedPembeliOption.data('notelepon') || '');
            $('#modal_pembeli_alamat').text(selectedPembeliOption.data('alamat') || '');

            // Show the modal
            var confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
            confirmationModal.show();
        });

        // Handle form submission after confirmation
        $('#confirmSubmitBtn').on('click', function() {
            // Submit the form
            $('#transaksiForm').submit();
        });
    });
</script>
@endsection
