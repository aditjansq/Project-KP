@extends('layouts.app')

@section('title', 'Tambah Penjualan Mobil')

@php
    $job = strtolower(auth()->user()->job ?? '');
@endphp

@section('content')
<head>
    {{-- Animate.css --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    {{-- Font Awesome for icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    {{-- Select2 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        /* Custom styles for a more modern feel */
        body {
            background-color: #f0f2f5;
            padding-bottom: 70px;
        }

        .form-control,
        .form-select {
            border-color: #e0e6ed;
            transition: all 0.3s ease;
            background-color: #ffffff;
            border-radius: 0.5rem !important;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
            transform: translateY(-1px);
        }

        .card.shadow-xl {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08), 0 5px 15px rgba(0, 0, 0, 0.04) !important;
        }

        .section-panel {
            background-color: #ffffff;
            border: 1px solid #e0e6ed;
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .section-panel .section-header {
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 1rem;
            margin-bottom: 1.5rem;
        }

        .section-panel .section-header h5 {
            margin-bottom: 0;
            color: #343a40;
        }

        .pembayaran-item {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef !important;
            border-radius: 0.5rem !important;
            padding: 1rem;
        }

        .btn-primary.animate__pulse {
            transition: all 0.3s ease;
        }
        .btn-primary.animate__pulse:hover {
            transform: scale(1.02);
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.5) !important;
        }

        .summary-detail {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px dashed #e9ecef;
        }
        .summary-detail:last-child {
            border-bottom: none;
        }
        .summary-detail .label {
            font-weight: 500;
            color: #5a6b7d;
        }
        .summary-detail .value {
            font-weight: 600;
            color: #212529;
            text-align: right;
        }
        .summary-total {
            padding-top: 1rem;
            font-size: 1.15rem;
            border-top: 2px solid #0d6efd;
        }
        .summary-total .value {
            color: #0d6efd;
        }
        .summary-alert {
            font-size: 0.9rem;
            padding: 0.75rem 1rem;
            margin-top: 1rem;
        }

        /* Select2 Custom Styling */
        .select2-container {
            width: 100% !important;
            box-sizing: border-box;
        }
        .select2-container * {
            box-sizing: border-box;
        }
        .select2-container .select2-selection--single {
            height: calc(2.8rem + 2px);
            border: 1px solid #e0e6ed;
            border-radius: 0.5rem !important;
            display: flex;
            align-items: center;
            padding: 0.375rem 0.75rem;
            overflow: hidden;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: normal;
            padding-left: 0;
            color: #000000;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            flex-grow: 1;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: calc(2.8rem + 2px);
            right: 0.75rem;
            top: 0;
            display: flex;
            align-items: center;
            padding-left: 0.5rem;
        }

        .select2-dropdown {
            border: 1px solid #e0e6ed;
            border-radius: 0.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            /* --- Tambahan Z-index untuk memastikan dropdown di atas elemen lain --- */
            z-index: 1051 !important;
        }

        /* Styling untuk opsi dropdown */
        .select2-container--default .select2-results__option {
            color: #000000;
            padding: 8px 12px;
        }

        /* Styling untuk opsi yang disorot di dropdown (saat hover/fokus) */
        .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
            background-color: #0d6efd;
            color: #ffffff;
        }

        /* Styling untuk opsi yang sudah dipilih di dropdown (sudah dipilih) */
        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #e9ecef;
            color: #000000;
        }

        /* Fixed Bottom Action Bar */
        .fixed-bottom-action-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #ffffff;
            border-top: 1px solid #e9ecef;
            padding: 1rem 0;
            box-shadow: 0 -5px 15px rgba(0, 0, 0, 0.05);
            z-index: 1060;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .fixed-bottom-action-bar .btn {
            max-width: 500px;
            width: 75%;
        }
    </style>
</head>
<div class="container-fluid py-4 px-3 px-md-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8 mb-3 mb-md-0">
            <h4 class="text-dark fw-bold mb-0">Tambah Data Transaksi Penjualan Baru</h4>
            <small class="text-secondary">Silakan lengkapi form berikut dengan data yang benar.</small>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('transaksi-penjualan.index') }}" class="btn btn-outline-secondary rounded-pill animate__animated animate__fadeInRight">
                <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar Transaksi
            </a>
        </div>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger border-0 shadow-sm rounded-3 animate__animated animate__shakeX" role="alert">
        <h6 class="alert-heading fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i> Terjadi Kesalahan Input!</h6>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('transaksi-penjualan.store') }}" enctype="multipart/form-data" class="needs-validation" novalidate id="createForm">
        @csrf

        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-xl rounded-4 mb-4 animate__animated animate__fadeInLeft section-panel">
                    <div class="card-body">
                        <div class="section-header mb-4">
                            <h5 class="fw-bold text-dark"><i class="bi bi-car-front-fill me-2"></i> Informasi Penjualan Utama</h5>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="kode_transaksi_otomatis" class="form-label text-muted">Kode Transaksi</label>
                                <input type="text" id="kode_transaksi_otomatis" class="form-control" value="{{ $kode_transaksi_otomatis ?? 'Kode Otomatis' }}" readonly>
                                <small class="form-text text-muted">Kode transaksi akan dibuat secara otomatis oleh sistem.</small>
                            </div>

                            <div class="col-md-6">
                                <label for="tanggal_transaksi" class="form-label text-muted">Tanggal Transaksi</label>
                                <input type="date" id="tanggal_transaksi" name="tanggal_transaksi" class="form-control" value="{{ old('tanggal_transaksi', date('Y-m-d')) }}" required>
                                <div class="invalid-feedback">
                                    Tanggal transaksi wajib diisi.
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="mobil_id" class="form-label text-muted">Pilih Mobil</label>
                                <select id="mobil_id" name="mobil_id" class="form-select select2" required>
                                    <option value="">-- Pilih Mobil --</option>
                                    @foreach ($mobils as $mobil)
                                        <option value="{{ $mobil->id }}"
                                            data-harga="{{ $mobil->harga_mobil }}"
                                            data-merk="{{ $mobil->merek_mobil }}"
                                            data-model="{{ $mobil->tipe_mobil }}"
                                            data-tahun-pembuatan="{{ $mobil->tahun_pembuatan }}"
                                            data-nomor-polisi="{{ $mobil->nomor_polisi }}"
                                            {{ old('mobil_id') == $mobil->id ? 'selected' : '' }}>
                                            {{ $mobil->merek_mobil }} {{ $mobil->tipe_mobil }} ({{ $mobil->tahun_pembuatan }}) - {{ $mobil->nomor_polisi }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Mobil wajib dipilih.
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="pembeli_id" class="form-label text-muted">Pilih Pembeli</label>
                                <select id="pembeli_id" name="pembeli_id" class="form-select select2" required>
                                    <option value="">-- Pilih Pembeli --</option>
                                    @foreach ($pembelis as $pembeli)
                                        <option value="{{ $pembeli->id }}"
                                            data-nama="{{ $pembeli->nama }}"
                                            data-telepon="{{ $pembeli->no_telepon }}"
                                            {{ old('pembeli_id') == $pembeli->id ? 'selected' : '' }}>
                                            {{ $pembeli->nama }} ({{ $pembeli->no_telepon }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Pembeli wajib dipilih.
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="metode_pembayaran" class="form-label text-muted">Metode Pembayaran Utama</label>
                                <select id="metode_pembayaran" name="metode_pembayaran" class="form-select" required>
                                    <option value="">-- Pilih Metode --</option>
                                    <option value="non_kredit" {{ old('metode_pembayaran') == 'non_kredit' ? 'selected' : '' }}>Non-Kredit</option>
                                    <option value="kredit" {{ old('metode_pembayaran') == 'kredit' ? 'selected' : '' }}>Kredit</option>
                                </select>
                                <div class="invalid-feedback">
                                    Metode pembayaran wajib dipilih.
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="harga_negosiasi" class="form-label text-muted">Harga Negosiasi</label>
                                <input type="text" id="harga_negosiasi" name="harga_negosiasi" class="form-control"
                                    value="{{ old('harga_negosiasi', 0) }}"
                                    required min="0">
                                <div class="invalid-feedback">
                                    Harga negosiasi wajib diisi dan harus angka positif.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="kredit-details-section" class="card border-0 shadow-xl rounded-4 mb-4 animate__animated animate__fadeInLeft section-panel" style="display: none;">
                    <div class="card-body">
                        <div class="section-header mb-4">
                            <h5 class="fw-bold text-dark"><i class="bi bi-bank me-2"></i> Detail Kredit</h5>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="tempo" class="form-label text-muted">Tempo (Tahun)</label>
                                <input type="number" id="tempo" name="tempo" class="form-control" value="{{ old('tempo') }}" min="1" max="5">
                                <div class="invalid-feedback">
                                    Tempo wajib diisi dan minimal 1 tahun.
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="leasing" class="form-label text-muted">Leasing</label>
                                <input type="text" id="leasing" name="leasing" class="form-control" value="{{ old('leasing') }}">
                                <div class="invalid-feedback">
                                    Leasing wajib diisi.
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="angsuran_per_bulan" class="form-label text-muted">Angsuran Per Bulan</label>
                                <input type="text" id="angsuran_per_bulan" name="angsuran_per_bulan" class="form-control"
                                    value="{{ old('angsuran_per_bulan', 0) }}"
                                    required min="0">
                                <div class="invalid-feedback">
                                    Angsuran per bulan wajib diisi dan harus angka positif.
                                </div>
                            </div>
                            {{-- Inputan baru untuk Refund --}}
                            <div class="col-md-6">
                                <label for="refund" class="form-label text-muted">Jumlah Refund</label>
                                <input type="text" id="refund" name="refund" class="form-control"
                                    value="{{ old('refund', 0) }}"
                                    min="0">
                                <div class="invalid-feedback">
                                    Jumlah refund harus berupa angka positif.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-xl rounded-4 mb-4 animate__animated animate__fadeInLeft section-panel">
                    <div class="card-body">
                        <div class="section-header d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold text-dark mb-0"><i class="bi bi-cash-coin me-2"></i> Detail Pembayaran</h5>
                            <button type="button" id="addPembayaranBtn" class="btn btn-sm btn-outline-primary rounded-pill">
                                <i class="bi bi-plus-circle me-2"></i> Tambah Pembayaran
                            </button>
                        </div>
                        <div id="pembayaran-wrapper">
                            {{-- Initial payment item will be added by JavaScript --}}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-xl rounded-4 mb-4 animate__animated animate__fadeInRight section-panel sticky-top" style="top: 1.5rem;">
                    <div class="card-body">
                        <div class="section-header mb-4">
                            <h5 class="fw-bold text-dark"><i class="bi bi-calculator-fill me-2"></i> Ringkasan Transaksi</h5>
                        </div>
                        <div class="summary-detail">
                            <span class="label">Harga Dasar Mobil:</span>
                            <span class="value" id="summary-harga-mobil">Rp0</span>
                        </div>
                        <div class="summary-detail">
                            <span class="label">Harga Negosiasi:</span>
                            <span class="value" id="summary-harga-negosiasi">Rp0</span>
                        </div>
                        <div class="summary-detail">
                            <span class="label">Total Pembayaran Diterima:</span>
                            <span class="value" id="summary-total-pembayaran">Rp0</span>
                        </div>
                        <div class="summary-detail" id="summary-dp-section" style="display: none;">
                            <span class="label">Sisa DP (harus dibayar):</span>
                            <span class="value text-danger" id="summary-dp-calculated">Rp0</span>
                        </div>
                        <input type="hidden" id="hidden-dp-value" name="dp" value="0">
                        <div class="summary-detail summary-total">
                            <span class="label fw-bold">Sisa Pembayaran:</span>
                            <span class="value fw-bold" id="summary-sisa-pembayaran">Rp0</span>
                        </div>
                        <div class="summary-alert alert alert-warning text-center" role="alert" id="summary-status-alert">
                            Status: Belum Lunas
                        </div>

                        <div id="summary-kredit-details" style="display: none;">
                            <hr class="my-3">
                            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-info-circle me-2"></i> Ringkasan Kredit</h6>
                            <div class="summary-detail">
                                <span class="label">Tempo:</span>
                                <span class="value" id="summary-tempo-display">-</span>
                            </div>
                            <div class="summary-detail">
                                <span class="label">Angsuran Per Bulan:</span>
                                <span class="value" id="summary-angsuran-display">Rp0</span>
                            </div>
                            <div class="summary-detail" id="summary-refund-row" style="display: none;">
                                <span class="label">Jumlah Refund:</span>
                                <span class="value" id="summary-refund-display">Rp 0</span>
                            </div>
                            <div class="summary-detail">
                                <span class="label">Leasing:</span>
                                <span class="value" id="summary-leasing-display">-</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="fixed-bottom-action-bar animate__animated animate__fadeInUp">
    <button type="submit" form="createForm" class="btn btn-primary btn-lg rounded-pill animate__animated animate__pulse animate__infinite">
        <i class="bi bi-save me-2"></i> Simpan Data
    </button>
</div>

{{-- Confirm Delete Payment Modal --}}
<div class="modal fade" id="confirmDeletePaymentModal" tabindex="-1" aria-labelledby="confirmDeletePaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark" id="confirmDeletePaymentModalLabel">Konfirmasi Hapus Detail Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-0">
                <div class="text-center mb-3">
                    <i class="bi bi-exclamation-triangle-fill text-warning" style="font-size: 3rem;"></i>
                </div>
                <p class="text-muted text-center">Apakah Anda yakin ingin menghapus detail pembayaran ini? Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer border-0 pt-0 justify-content-center">
                <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger rounded-pill" id="confirmDeletePaymentBtn">Hapus</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script>
    // Utility function to format currency
    function formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
    }

    // Fungsi global untuk memformat input angka dengan pemisah ribuan saat diketik
    function formatNumberInput(inputElement) {
        let value = inputElement.value.replace(/\./g, ''); // Hapus titik yang ada
        if (value) {
            value = parseInt(value).toLocaleString('id-ID'); // Format sebagai mata uang ID
            inputElement.value = value;
        } else {
            inputElement.value = ''; // Jika kosong, biarkan kosong
        }
    }

    // Fungsi global untuk membersihkan angka dari pemisah ribuan sebelum digunakan dalam perhitungan
    function cleanNumber(value) {
        return parseFloat(String(value).replace(/\./g, '') || 0);
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Select2
        $('#mobil_id').select2({
            theme: "default",
            placeholder: "Pilih Mobil",
            allowClear: true,
            dropdownParent: $('body'), // Append dropdown to body to avoid z-index issues
            width: '100%'
        });
        $('#pembeli_id').select2({
            theme: "default",
            placeholder: "Pilih Pembeli",
            allowClear: true,
            dropdownParent: $('body'), // Append dropdown to body to avoid z-index issues
            width: '100%'
        });

        // Payment index counter
        let pembayaranIndex = 0;

        // Modal elements
        const confirmDeletePaymentModal = new bootstrap.Modal(document.getElementById('confirmDeletePaymentModal'));
        const confirmDeletePaymentBtn = document.getElementById('confirmDeletePaymentBtn');
        let itemToDelete = null;

        // Main calculation function
        function calculateSummary() {
            // Get selected car price
            const mobilElement = document.getElementById('mobil_id');
            const selectedMobil = mobilElement.options[mobilElement.selectedIndex];
            const hargaMobil = selectedMobil && selectedMobil.dataset.harga ?
                parseFloat(selectedMobil.dataset.harga) : 0;

            // Get negotiated price
            const hargaNegosiasi = cleanNumber(document.getElementById('harga_negosiasi').value);

            // Calculate total payments
            let totalPembayaran = 0;
            document.querySelectorAll('input[name$="[jumlah_pembayaran]"]').forEach(input => {
                totalPembayaran += cleanNumber(input.value);
            });

            // Get refund value
            const refund = cleanNumber(document.getElementById('refund').value);

            // Update summary displays
            document.getElementById('summary-harga-mobil').textContent = formatCurrency(hargaMobil);
            document.getElementById('summary-harga-negosiasi').textContent = formatCurrency(hargaNegosiasi);
            document.getElementById('summary-total-pembayaran').textContent = formatCurrency(totalPembayaran);

            // Handle credit/non-credit differences
            const metodePembayaran = document.getElementById('metode_pembayaran').value;
            const sisaPembayaranElement = document.getElementById('summary-sisa-pembayaran');
            const statusAlertElement = document.getElementById('summary-status-alert');

            if (metodePembayaran === 'kredit') {
                const dpCalculated = Math.max(hargaNegosiasi - totalPembayaran, 0);

                document.getElementById('summary-dp-section').style.display = 'flex';
                document.getElementById('summary-dp-calculated').textContent = formatCurrency(dpCalculated);
                document.getElementById('hidden-dp-value').value = dpCalculated;

                sisaPembayaranElement.textContent = formatCurrency(dpCalculated);
                sisaPembayaranElement.className = 'value fw-bold ' + (dpCalculated > 0 ? 'text-danger' : 'text-success');

                statusAlertElement.textContent = dpCalculated > 0 ?
                    'Status: DP Belum Lunas' : 'Status: DP Sudah Lunas';
                statusAlertElement.className = dpCalculated > 0 ?
                    'summary-alert alert alert-warning text-center' : 'summary-alert alert alert-success text-center';

                // Update credit details
                document.getElementById('summary-kredit-details').style.display = 'block';
                document.getElementById('summary-tempo-display').textContent =
                    (document.getElementById('tempo').value || '0') + ' Tahun';
                document.getElementById('summary-angsuran-display').textContent =
                    formatCurrency(cleanNumber(document.getElementById('angsuran_per_bulan').value));
                document.getElementById('summary-leasing-display').textContent =
                    document.getElementById('leasing').value || '-';

                document.getElementById('summary-refund-display').textContent = formatCurrency(refund);
                document.getElementById('summary-refund-row').style.display = 'flex';

            } else {
                document.getElementById('summary-dp-section').style.display = 'none';
                document.getElementById('summary-kredit-details').style.display = 'none';
                document.getElementById('summary-refund-row').style.display = 'none';


                const sisaPembayaran = hargaNegosiasi - totalPembayaran;
                sisaPembayaranElement.textContent = formatCurrency(sisaPembayaran);

                if (sisaPembayaran > 0) {
                    sisaPembayaranElement.className = 'value fw-bold text-danger';
                    statusAlertElement.textContent = 'Status: Belum Lunas';
                    statusAlertElement.className = 'summary-alert alert alert-danger text-center';
                } else if (sisaPembayaran < 0) {
                    sisaPembayaranElement.className = 'value fw-bold text-success';
                    statusAlertElement.textContent = 'Status: Pembayaran Melebihi Harga';
                    statusAlertElement.className = 'summary-alert alert alert-info text-center';
                } else {
                    sisaPembayaranElement.className = 'value fw-bold text-success';
                    statusAlertElement.textContent = 'Status: Lunas';
                    statusAlertElement.className = 'summary-alert alert alert-success text-center';
                }
            }
        }

        // Payment item management
        function addPaymentItem() {
            const wrapperElement = document.getElementById('pembayaran-wrapper');
            // Initial alert is removed from HTML, so this is no longer strictly needed but good for robustness
            const initialAlert = wrapperElement.querySelector('.alert-info');
            if (initialAlert) {
                initialAlert.remove();
            }

            const newItem = document.createElement('div');
            newItem.className = 'pembayaran-item mb-3 p-3 position-relative animate__animated animate__fadeIn';
            newItem.innerHTML = `
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label text-muted">Metode Pembayaran</label>
                        <select name="pembayaran[${pembayaranIndex}][metode_pembayaran_detail]" class="form-select payment-detail-input" required>
                            <option value="">-- Pilih Metode --</option>
                            <option value="cash">Cash</option>
                            <option value="transfer_bank">Transfer Bank</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted">Jumlah Pembayaran</label>
                        <input type="text" name="pembayaran[${pembayaranIndex}][jumlah_pembayaran]" class="form-control payment-detail-input"
                            value="0" required min="0">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted">Tanggal Pembayaran</label>
                        <input type="date" name="pembayaran[${pembayaranIndex}][tanggal_pembayaran]" class="form-control payment-detail-input" value="${new Date().toISOString().split('T')[0]}" required>
                    </div>
                    <div class="col-md-12 mt-3">
                        <label class="form-label text-muted">Keterangan</label>
                        <textarea name="pembayaran[${pembayaranIndex}][keterangan_pembayaran_detail]" class="form-control rounded-3 shadow-sm payment-detail-input" rows="2"></textarea>
                    </div>
                    <div class="col-md-12 mt-3">
                        <label class="form-label text-muted">File Bukti Pembayaran</label>
                        <input type="file" name="pembayaran[${pembayaranIndex}][bukti_pembayaran_detail]" class="form-control shadow-sm">
                        <small class="form-text text-muted">Unggah bukti pembayaran (maks. 2MB, format: JPG, JPEG, PNG, PDF).</small>
                    </div>
                    <div class="col-12 mt-2 text-end">
                        <button type="button" class="btn btn-danger btn-sm rounded-3 remove-btn">
                            <i class="fas fa-trash me-1"></i> Hapus
                        </button>
                    </div>
                </div>
            `;

            wrapperElement.appendChild(newItem);
            pembayaranIndex++;

            // Add event listeners to newly created inputs
            newItem.querySelectorAll('.payment-detail-input').forEach(input => {
                if (input.name.includes('[jumlah_pembayaran]')) {
                    input.addEventListener('input', function() {
                        formatNumberInput(this);
                        calculateSummary();
                    });
                    input.addEventListener('blur', function() {
                        if (!this.value) { this.value = '0'; }
                        calculateSummary();
                    });
                } else {
                    input.addEventListener('input', calculateSummary);
                }
            });

            // Add remove button functionality to show modal
            newItem.querySelector('.remove-btn').addEventListener('click', function() {
                itemToDelete = newItem;
                confirmDeletePaymentModal.show();
            });

            calculateSummary();
        }

        // Toggle credit details section
        function toggleKreditDetails() {
            const metodePembayaran = document.getElementById('metode_pembayaran').value;
            const kreditSection = document.getElementById('kredit-details-section');

            if (metodePembayaran === 'kredit') {
                kreditSection.style.display = 'block';
                document.getElementById('tempo').setAttribute('required', 'required');
                document.getElementById('leasing').setAttribute('required', 'required');
                document.getElementById('angsuran_per_bulan').setAttribute('required', 'required');
            } else {
                kreditSection.style.display = 'none';
                document.getElementById('tempo').removeAttribute('required');
                document.getElementById('leasing').removeAttribute('required');
                document.getElementById('angsuran_per_bulan').removeAttribute('required');
                // Clear values when hidden
                document.getElementById('tempo').value = '';
                document.getElementById('leasing').value = '';
                document.getElementById('angsuran_per_bulan').value = '0';
                document.getElementById('refund').value = '0';
            }

            calculateSummary();
        }

        // Centralized event listeners for static inputs
        const hargaNegosiasiInput = document.getElementById('harga_negosiasi');
        const tempoInput = document.getElementById('tempo');
        const leasingInput = document.getElementById('leasing');
        const angsuranPerBulanInput = document.getElementById('angsuran_per_bulan');
        const refundInput = document.getElementById('refund');
        const metodePembayaranSelect = document.getElementById('metode_pembayaran');
        const mobilSelect = document.getElementById('mobil_id');
        const pembeliSelect = document.getElementById('pembeli_id');


        // Event listeners for inputs that also need formatting
        if (hargaNegosiasiInput) {
            hargaNegosiasiInput.addEventListener('input', function() {
                formatNumberInput(this);
                calculateSummary();
            });
            hargaNegosiasiInput.addEventListener('blur', function() {
                if (!this.value) { this.value = '0'; }
                calculateSummary();
            });
        }
        if (angsuranPerBulanInput) {
            angsuranPerBulanInput.addEventListener('input', function() {
                formatNumberInput(this);
                calculateSummary();
            });
            angsuranPerBulanInput.addEventListener('blur', function() {
                if (!this.value) { this.value = '0'; }
                calculateSummary();
            });
        }
        if (refundInput) {
            refundInput.addEventListener('input', function() {
                formatNumberInput(this);
                calculateSummary();
            });
            refundInput.addEventListener('blur', function() {
                if (!this.value) { this.value = '0'; }
                calculateSummary();
            });
        }

        // Event listeners for inputs that only need summary calculation
        if (tempoInput) {
            tempoInput.addEventListener('input', calculateSummary);
        }
        if (leasingInput) {
            leasingInput.addEventListener('input', calculateSummary);
        }

        // Event listeners for select changes
        document.getElementById('addPembayaranBtn').addEventListener('click', addPaymentItem);
        if (metodePembayaranSelect) {
            metodePembayaranSelect.addEventListener('change', toggleKreditDetails);
        }
        if (mobilSelect) {
            $(mobilSelect).on('change', calculateSummary);
        }
        if (pembeliSelect) {
            $(pembeliSelect).on('change', calculateSummary);
        }

        // Event listener for confirm delete button in modal
        confirmDeletePaymentBtn.addEventListener('click', function() {
            if (itemToDelete) {
                itemToDelete.remove();
                calculateSummary();
                if (document.getElementById('pembayaran-wrapper').children.length === 0) {
                    const newEmptyAlert = document.createElement('div');
                    newEmptyAlert.classList.add('alert', 'alert-info', 'text-center', 'py-3', 'animate__animated', 'animate__fadeIn');
                    newEmptyAlert.innerHTML = `<i class="bi bi-info-circle-fill me-2"></i> Klik "Tambah Pembayaran" untuk menambahkan detail pembayaran.`;
                    document.getElementById('pembayaran-wrapper').appendChild(newEmptyAlert);
                }
                itemToDelete = null;
                confirmDeletePaymentModal.hide();
            }
        });


        // Initial setup on page load
        addPaymentItem(); // Add the first payment item automatically
        calculateSummary();
        toggleKreditDetails();

    // Form validation
    (function() {
        'use strict';
        var forms = document.querySelectorAll('.needs-validation');
        Array.prototype.slice.call(forms).forEach(function(form) {
            form.addEventListener('submit', function(event) {
                // Before submission, remove dots from number inputs to ensure backend receives clean numbers
                document.querySelectorAll(
                    'input[name="harga_negosiasi"], ' +
                    'input[name^="pembayaran"][name$="[jumlah_pembayaran]"], ' +
                    'input[name="angsuran_per_bulan"], ' +
                    'input[name="refund"]'
                ).forEach(input => {
                    input.value = input.value.replace(/\./g, '');
                });

                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();
});
</script>
@endsection
