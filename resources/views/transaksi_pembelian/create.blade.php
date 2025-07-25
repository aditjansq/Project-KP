@extends('layouts.app')

@section('title', 'Tambah Transaksi Pembelian Mobil')

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
            width: 100% !important; /* Ensure it takes full width of its parent column */
        }
        .select2-container .select2-selection--single {
            height: calc(2.8rem + 2px); /* Maintain Bootstrap's form-control height */
            border: 1px solid #e0e6ed;
            border-radius: 0.5rem !important;
            display: flex;
            align-items: center;
            padding: 0.375rem 0.75rem; /* Add padding for better visual */
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: normal; /* Ensure text is vertically centered */
            padding-left: 0; /* Remove default Select2 padding if `padding` is added to parent */
            color: #000000; /* Mengubah warna teks di input Select2 menjadi hitam */
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: calc(2.8rem + 2px);
            right: 0.75rem;
            top: 0; /* Align arrow to top of the container */
            display: flex; /* Use flex to center arrow vertically */
            align-items: center; /* Vertically center arrow */
        }

        .select2-dropdown {
            border: 1px solid #e0e6ed;
            border-radius: 0.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        /* NEW: Styling for dropdown options */
        .select2-container--default .select2-results__option {
            color: #000000; /* Warna teks untuk setiap opsi di dropdown */
            padding: 8px 12px; /* Sesuaikan padding jika diperlukan */
        }

        /* NEW: Styling for highlighted option in dropdown (on hover/focus) */
        .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
            background-color: #0d6efd; /* Warna latar belakang saat di-hover */
            color: #ffffff; /* Warna teks saat di-hover menjadi putih agar kontras */
        }

        /* NEW: Styling for selected option in dropdown (already chosen) */
        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #e9ecef; /* Warna latar belakang untuk opsi yang sudah dipilih */
            color: #000000; /* Warna teks untuk opsi yang sudah dipilih */
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
            <h4 class="text-dark fw-bold mb-0">Tambah Data Transaksi Pembelian Mobil Baru</h4>
            <small class="text-secondary">Silakan lengkapi form berikut dengan data yang benar.</small>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('transaksi-pembelian.index') }}" class="btn btn-outline-secondary rounded-pill animate__animated animate__fadeInRight">
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

    <form method="POST" action="{{ route('transaksi-pembelian.store') }}" enctype="multipart/form-data" class="needs-validation" novalidate id="createForm">
        @csrf

        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-xl rounded-4 mb-4 animate__animated animate__fadeInLeft section-panel">
                    <div class="card-body">
                        <div class="section-header mb-4">
                            <h5 class="fw-bold text-dark"><i class="bi bi-car-front-fill me-2"></i> Informasi Pembelian Utama</h5>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="kode_transaksi_otomatis" class="form-label text-muted">Kode Transaksi</label>
                                <input type="text" id="kode_transaksi_otomatis" name="kode_transaksi" class="form-control" value="{{ $kode_transaksi ?? 'Kode Otomatis' }}" readonly>
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
                                            data-harga="{{ $mobil->harga_beli }}"
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
                                <label for="penjual_id" class="form-label text-muted">Pilih Penjual</label>
                                <select id="penjual_id" name="penjual_id" class="form-select select2" required>
                                    <option value="">-- Pilih Penjual --</option>
                                    @foreach ($penjuals as $penjual)
                                        <option value="{{ $penjual->id }}"
                                            data-nama="{{ $penjual->nama }}"
                                            data-telepon="{{ $penjual->no_telepon }}"
                                            {{ old('penjual_id') == $penjual->id ? 'selected' : '' }}>
                                            {{ $penjual->nama }} ({{ $penjual->no_telepon }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Penjual wajib dipilih.
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="harga_beli_final" class="form-label text-muted">Harga Beli Final</label>
                                <input type="text" id="harga_beli_final" name="harga_beli_mobil_final" class="form-control"
                                    value="{{ old('harga_beli_mobil_final') ? number_format(old('harga_beli_mobil_final'), 0, ',', '.') : '' }}"
                                    required min="0"
                                    placeholder="Contoh: 150.000.000"
                                    oninput="formatAndCalculateSummary(this)"
                                    onblur="if (!this.value) { this.value = '0'; calculateSummary(); }"
                                >
                                <div class="invalid-feedback">
                                    Harga beli final wajib diisi dan harus berupa angka.
                                </div>
                                @error('harga_beli_mobil_final')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="keterangan" class="form-label text-muted">Keterangan Tambahan</label>
                                <textarea id="keterangan" name="keterangan" class="form-control" rows="2">{{ old('keterangan') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-xl rounded-4 mb-4 animate__animated animate__fadeInLeft section-panel">
                    <div class="card-body">
                        <div class="section-header d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold text-dark"><i class="bi bi-cash-coin me-2"></i> Detail Pembayaran</h5>
                            <button type="button" id="addPembayaranBtn" class="btn btn-sm btn-outline-primary rounded-pill">
                                <i class="bi bi-plus-circle me-2"></i> Tambah Pembayaran
                            </button>
                        </div>
                        <div id="pembayaran-wrapper">
                            <div class="pembayaran-item mb-3 p-3 position-relative animate__animated animate__fadeIn">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label text-muted">Metode Pembayaran</label>
                                        <select name="pembayaran_detail[0][metode_pembayaran]" class="form-select payment-detail-input" required>
                                            <option value="">-- Pilih Metode --</option>
                                            <option value="Cash">Cash</option>
                                            <option value="Transfer Bank">Transfer Bank</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label text-muted">Jumlah Pembayaran</label>
                                        <input type="text" name="pembayaran_detail[0][jumlah_pembayaran]" class="form-control payment-detail-input payment-amount-input"
                                            value="{{ old('pembayaran_detail.0.jumlah_pembayaran') ? number_format(old('pembayaran_detail.0.jumlah_pembayaran'), 0, ',', '.') : '' }}"
                                            required min="0" placeholder="Contoh: 50.000.000"
                                            oninput="formatPaymentAmount(this)"
                                            onblur="if (!this.value) { this.value = '0'; calculateSummary(); }"
                                        >
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label text-muted">Tanggal Pembayaran</label>
                                        <input type="date" name="pembayaran_detail[0][tanggal_pembayaran]" class="form-control payment-detail-input" value="{{ date('Y-m-d') }}" required>
                                    </div>
                                    <div class="col-md-12 mt-3">
                                        <label class="form-label text-muted">Keterangan</label>
                                        <textarea name="pembayaran_detail[0][keterangan_pembayaran_detail]" class="form-control rounded-3 shadow-sm payment-detail-input" rows="2"></textarea>
                                    </div>
                                    <div class="col-md-12 mt-3">
                                        <label class="form-label text-muted">File Bukti Pembayaran</label>
                                        <input type="file" name="pembayaran_detail[0][bukti_pembayaran_detail]" class="form-control shadow-sm">
                                    </div>
                                    <div class="col-12 mt-2 text-end">
                                        <button type="button" class="btn btn-danger btn-sm rounded-3 remove-btn"
                                                data-bs-toggle="modal" data-bs-target="#confirmDeletePaymentModal">
                                            <i class="fas fa-trash me-1"></i> Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-xl rounded-4 mb-4 animate__animated animate__fadeInRight section-panel sticky-top" style="top: 1.5rem;">
                    <div class="card-body">
                        <div class="section-header mb-4">
                            <h5 class="fw-bold text-dark"><i class="bi bi-calculator-fill me-2"></i> Ringkasan Transaksi</h4>
                        </div>
                        <div class="summary-detail">
                            <span class="label">Harga Beli Mobil:</span>
                            <span class="value" id="summary-harga-mobil">Rp0</span>
                        </div>
                        <div class="summary-detail">
                            <span class="label">Harga Beli Final:</span>
                            <span class="value" id="summary-harga-beli-final">Rp0</span>
                        </div>
                        <div class="summary-detail">
                            <span class="label">Total Pembayaran Diberikan:</span>
                            <span class="value" id="summary-total-pembayaran">Rp0</span>
                        </div>
                        <div class="summary-detail summary-total">
                            <span class="label fw-bold">Sisa Pembayaran:</span>
                            <span class="value fw-bold" id="summary-sisa-pembayaran">Rp0</span>
                        </div>
                        <div class="summary-alert alert alert-warning text-center" role="alert" id="summary-status-alert">
                            Status: Belum Lunas
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Select2
        $('#mobil_id').select2();
        $('#penjual_id').select2();

        // Payment index counter
        let pembayaranIndex = 1;

        // Variable to store the payment item to be deleted
        let paymentItemToDelete = null;

        // Initialize Bootstrap Modal
        const confirmDeletePaymentModal = new bootstrap.Modal(document.getElementById('confirmDeletePaymentModal'));

        // --- GLOBAL FUNCTIONS ---

        // Main calculation function
        window.calculateSummary = function() {
            // Get selected car price (assuming harga_beli from mobil_id)
            const mobilElement = document.getElementById('mobil_id');
            const selectedMobil = mobilElement.options[mobilElement.selectedIndex];
            const hargaMobil = selectedMobil && selectedMobil.dataset.harga ?
                parseFloat(selectedMobil.dataset.harga) : 0;

            // Get final purchase price
            const hargaBeliFinalInput = document.getElementById('harga_beli_final').value;
            const hargaBeliFinal = parseFloat(hargaBeliFinalInput.replace(/\./g, '')) || 0;

            // Calculate total payments
            let totalPembayaran = 0;
            // Iterate over all payment amount input fields
            document.querySelectorAll('input[name^="pembayaran_detail"][name$="[jumlah_pembayaran]"]').forEach(input => {
                // Get the value and remove dots before parsing to float
                const valueWithoutDots = input.value.replace(/\./g, '');
                totalPembayaran += parseFloat(valueWithoutDots) || 0;
            });

            // Update summary displays
            document.getElementById('summary-harga-mobil').textContent = formatCurrency(hargaMobil);
            document.getElementById('summary-harga-beli-final').textContent = formatCurrency(hargaBeliFinal);
            document.getElementById('summary-total-pembayaran').textContent = formatCurrency(totalPembayaran);

            const sisaPembayaran = hargaBeliFinal - totalPembayaran;
            const sisaPembayaranElement = document.getElementById('summary-sisa-pembayaran');
            const statusAlertElement = document.getElementById('summary-status-alert');

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

        // Format currency helper (optimized for real-time input)
        window.formatCurrency = function(amount) {
            if (isNaN(amount) || amount === null || typeof amount === 'undefined') {
                return 'Rp0';
            }
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0,
            }).format(amount);
        };

        // Function to format the input field directly (adds dots)
        window.formatAndCalculateSummary = function(inputElement) {
            let rawValue = inputElement.value.replace(/\D/g, ''); // Remove all non-digits
            let formattedValue = '';
            if (rawValue) {
                formattedValue = rawValue.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }
            inputElement.value = formattedValue;
            calculateSummary(); // Call summary calculation after formatting
        };

        // Function to format payment amount input
        window.formatPaymentAmount = function(inputElement) {
            let rawValue = inputElement.value.replace(/\D/g, ''); // Remove all non-digits
            let formattedValue = '';
            if (rawValue) {
                formattedValue = rawValue.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }
            inputElement.value = formattedValue;
            calculateSummary(); // Call summary calculation after formatting
        };

        // --- PAYMENT ITEM MANAGEMENT ---
        function addPaymentItem() {
            const wrapperElement = document.getElementById('pembayaran-wrapper');
            const newItem = document.createElement('div');
            newItem.className = 'pembayaran-item mb-3 p-3 position-relative animate__animated animate__fadeIn';
            newItem.innerHTML = `
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label text-muted">Metode Pembayaran</label>
                        <select name="pembayaran_detail[${pembayaranIndex}][metode_pembayaran]" class="form-select payment-detail-input" required>
                            <option value="">-- Pilih Metode --</option>
                            <option value="Cash">Cash</option>
                            <option value="Transfer Bank">Transfer Bank</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted">Jumlah Pembayaran</label>
                        <input type="text" name="pembayaran_detail[${pembayaranIndex}][jumlah_pembayaran]" class="form-control payment-detail-input payment-amount-input" required min="0" placeholder="Contoh: 50.000.000"
                            oninput="formatPaymentAmount(this)"
                            onblur="if (!this.value) { this.value = '0'; calculateSummary(); }"
                        >
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted">Tanggal Pembayaran</label>
                        <input type="date" name="pembayaran_detail[${pembayaranIndex}][tanggal_pembayaran]" class="form-control payment-detail-input" value="${new Date().toISOString().split('T')[0]}" required>
                    </div>
                    <div class="col-md-12 mt-3">
                        <label class="form-label text-muted">Keterangan</label>
                        <textarea name="pembayaran_detail[${pembayaranIndex}][keterangan_pembayaran_detail]" class="form-control rounded-3 shadow-sm payment-detail-input" rows="2"></textarea>
                    </div>
                    <div class="col-md-12 mt-3">
                        <label class="form-label text-muted">File Bukti Pembayaran</label>
                        <input type="file" name="pembayaran_detail[${pembayaranIndex}][bukti_pembayaran_detail]" class="form-control shadow-sm">
                    </div>
                    <div class="col-12 mt-2 text-end">
                        <button type="button" class="btn btn-danger btn-sm rounded-3 remove-btn"
                                data-bs-toggle="modal" data-bs-target="#confirmDeletePaymentModal">
                            <i class="fas fa-trash me-1"></i> Hapus
                        </button>
                    </div>
                </div>
            `;

            wrapperElement.appendChild(newItem);
            pembayaranIndex++;

            // Event listeners for the newly added inputs
            newItem.querySelectorAll('.payment-detail-input:not(.payment-amount-input)').forEach(input => {
                 input.addEventListener('input', calculateSummary);
            });

            // Add remove button functionality with confirmation modal
            newItem.querySelector('.remove-btn').addEventListener('click', function() {
                paymentItemToDelete = newItem; // Store the item to be deleted
            });

            calculateSummary();
        }

        // Add event listeners for static elements on initial load
        document.getElementById('addPembayaranBtn').addEventListener('click', addPaymentItem);

        // Attach click listener to existing "Hapus" buttons for the first item
        document.querySelectorAll('.pembayaran-item .remove-btn').forEach(button => {
            button.addEventListener('click', function() {
                paymentItemToDelete = this.closest('.pembayaran-item');
            });
        });

        // Event listener for the "Hapus" button inside the confirmation modal
        document.getElementById('confirmDeletePaymentBtn').addEventListener('click', function() {
            if (paymentItemToDelete) {
                paymentItemToDelete.remove();
                paymentItemToDelete = null; // Clear the stored item
                calculateSummary(); // Recalculate summary after deletion
                confirmDeletePaymentModal.hide(); // Hide the modal
            }
        });


        // Initial setup for the first payment amount input (index 0)
        // Ensure initial formatting is applied if old value exists
        const initialPaymentAmountInput = document.querySelector('input[name="pembayaran_detail[0][jumlah_pembayaran]"]');
        if (initialPaymentAmountInput && initialPaymentAmountInput.value) {
            formatPaymentAmount(initialPaymentAmountInput);
        } else if (initialPaymentAmountInput) {
             // If no old value, ensure summary still calculates correctly based on 0
             initialPaymentAmountInput.value = '0'; // Set to '0' initially
             calculateSummary();
        }


        // Event listener for main form inputs that affect summary
        document.getElementById('mobil_id').addEventListener('change', calculateSummary);
        document.getElementById('penjual_id').addEventListener('change', calculateSummary);
        document.getElementById('tanggal_transaksi').addEventListener('change', calculateSummary);

        // Initial calculation on page load for harga_beli_final
        const hargaBeliFinalInput = document.getElementById('harga_beli_final');
        if (hargaBeliFinalInput.value) {
            formatAndCalculateSummary(hargaBeliFinalInput);
        } else {
            calculateSummary(); // Perform initial summary calculation if no old value
        }
    });

    // Form validation
    (function() {
        'use strict';
        var forms = document.querySelectorAll('.needs-validation');
        Array.prototype.slice.call(forms).forEach(function(form) {
            form.addEventListener('submit', function(event) {
                // Before submission, remove dots from number inputs to ensure backend receives clean numbers
                document.querySelectorAll('input[name="harga_beli_mobil_final"], input[name^="pembayaran_detail"][name$="[jumlah_pembayaran]"]').forEach(input => {
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
</script>
@endsection
