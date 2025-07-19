@extends('layouts.app')

@section('title', 'Edit Transaksi Pembelian Mobil - ' . $transaksiPembelian->kode_transaksi)

@section('content')
@php
    use Illuminate\Support\Facades\Storage;
    use Carbon\Carbon;
@endphp

<head>
    {{-- Animate.css for subtle animations --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    {{-- Font Awesome for icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    {{-- Bootstrap Icons for general icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    {{-- Select2 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        /* Google Fonts - Poppins */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        body {
            background-color: #f0f2f5; /* A slightly darker background for dashboard feel */
            font-family: 'Poppins', sans-serif;
            color: #333;
            padding-bottom: 70px; /* Add padding to body to prevent fixed button from overlapping content */
        }

        .form-control,
        .form-select {
            border-color: #e0e6ed; /* A slightly softer border color */
            transition: all 0.3s ease; /* Smooth transition for focus effects */
            background-color: #ffffff; /* Ensure input background is white */
            border-radius: 0.5rem !important; /* Slightly rounded corners for inputs */
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #0d6efd; /* Bootstrap primary blue on focus */
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25); /* Standard Bootstrap focus shadow */
            transform: translateY(-1px); /* Slight lift effect on focus, less aggressive */
        }

        /* Enhanced shadow for main card and section panels */
        .card.shadow-xl {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08), 0 5px 15px rgba(0, 0, 0, 0.04) !important;
        }

        /* Styling for section panels */
        .section-panel {
            background-color: #ffffff;
            border: 1px solid #e0e6ed;
            border-radius: 0.75rem; /* Consistent rounded corners */
            padding: 1.5rem; /* More generous padding */
            margin-bottom: 2rem; /* Spacing between sections */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05); /* Subtle shadow for panels */
        }

        .section-panel .section-header {
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 1rem;
            margin-bottom: 1.5rem;
        }

        /* Adjusted header size and font-weight for section panels */
        .section-panel .section-header h5 {
            margin-bottom: 0;
            color: #343a40; /* Darker text for headers */
            font-weight: 600; /* Custom font-weight for "agak bold" (semibold) */
        }

        /* Light background for payment detail sections within their own panel */
        .pembayaran-item {
            background-color: #f8f9fa; /* A very light grey background */
            border: 1px solid #e9ecef !important; /* Softer border for these sub-sections */
            border-radius: 0.5rem !important; /* Consistent rounded corners for sub-items */
            padding: 1rem; /* Slightly less padding than main panels */
            position: relative; /* For positioning the remove button */
        }

        /* Subtle glow for the main submit button on hover */
        .btn-primary.animate__pulse {
            transition: all 0.3s ease;
        }
        .btn-primary.animate__pulse:hover {
            transform: scale(1.02);
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.5) !important; /* Stronger glow */
        }

        /* Styling for summary details */
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
            border-top: 2px solid #0d6efd; /* Stronger line for total */
        }
        .summary-total .value {
            color: #0d6efd; /* Primary color for total */
        }
        .summary-alert {
            font-size: 0.9rem;
            padding: 0.75rem 1rem;
            margin-top: 1rem;
        }

        /* Select2 Custom Styling to match Bootstrap 5 */
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

        /* Styling for dropdown options */
        .select2-container--default .select2-results__option {
            color: #000000; /* Warna teks untuk setiap opsi di dropdown */
            padding: 8px 12px; /* Sesuaikan padding jika diperlukan */
        }

        /* Styling for highlighted option in dropdown (on hover/focus) */
        .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
            background-color: #0d6efd; /* Warna latar belakang saat di-hover */
            color: #ffffff; /* Warna teks saat di-hover menjadi putih agar kontras */
        }

        /* Styling for selected option in dropdown (already chosen) */
        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #e9ecef; /* Warna latar belakang untuk opsi yang sudah dipilih */
            color: #000000; /* Warna teks untuk opsi yang sudah dipilih */
        }

        /* Custom styling for remove button */
        .btn-danger.remove-btn {
            background-color: #dc3545;
            border-color: #dc3545;
            color: white;
            transition: all 0.3s ease;
            position: absolute;
            top: 0.75rem; /* Adjust as needed */
            right: 0.75rem; /* Adjust as needed */
            font-size: 0.8rem; /* Smaller icon */
            padding: 0.25rem 0.5rem; /* Smaller padding */
            border-radius: 0.3rem; /* Slightly less rounded */
        }
        .btn-danger.remove-btn:hover {
            background-color: #c82333;
            border-color: #bd2130;
            transform: translateY(-1px);
            box-shadow: 0 2px 5px rgba(220, 53, 69, 0.3);
        }

        /* Fixed Bottom Action Bar */
        .fixed-bottom-action-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #ffffff; /* White background */
            border-top: 1px solid #e9ecef; /* Subtle border top */
            padding: 1rem 0; /* Vertical padding */
            box-shadow: 0 -5px 15px rgba(0, 0, 0, 0.05); /* Shadow to lift it */
            z-index: 1060; /* Ensure it's on top, above most content but below modals */
            display: flex; /* Use flex for centering content */
            justify-content: center;
            align-items: center;
        }

        /* Adjust button inside fixed bar */
        .fixed-bottom-action-bar .btn {
            max-width: 500px; /* Limit width on large screens for better aesthetics */
            width: 75%; /* Make it 75% width of its container */
        }
    </style>
</head>

<div class="container-fluid py-4 px-3 px-md-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8 mb-3 mb-md-0">
            <h4 class="text-dark fw-bold mb-0">Edit Data Transaksi Pembelian Mobil Baru</h4>
            <small class="text-secondary">Silakan perbarui form berikut dengan data yang benar.</small>
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

    <form action="{{ route('transaksi-pembelian.update', $transaksiPembelian->id) }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate id="editForm">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-lg-8">
                {{-- Bagian Informasi Utama Transaksi --}}
                <div class="card border-0 shadow-xl rounded-4 mb-4 animate__animated animate__fadeInLeft section-panel">
                    <div class="card-body">
                        <div class="section-header mb-4">
                            <h5 class="fw-bold text-dark"><i class="bi bi-car-front-fill me-2"></i> Informasi Pembelian Utama</h5>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="kode_transaksi" class="form-label text-muted">Kode Transaksi</label>
                                <input type="text" class="form-control" id="kode_transaksi" name="kode_transaksi" value="{{ old('kode_transaksi', $transaksiPembelian->kode_transaksi) }}" readonly required>
                                <div class="invalid-feedback">Kode transaksi wajib diisi.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="tanggal_transaksi" class="form-label text-muted">Tanggal Transaksi</label>
                                <input type="date" class="form-control" id="tanggal_transaksi" name="tanggal_transaksi" value="{{ old('tanggal_transaksi', Carbon::parse($transaksiPembelian->tanggal_transaksi)->format('Y-m-d')) }}" required>
                                <div class="invalid-feedback">Tanggal transaksi wajib diisi.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="mobil_id" class="form-label text-muted">Pilih Mobil</label>
                                <select class="form-select select2" id="mobil_id" name="mobil_id" required>
                                    <option value="">-- Pilih Mobil --</option>
                                    @foreach($mobils as $mobil)
                                        <option value="{{ $mobil->id }}"
                                            data-harga="{{ $mobil->harga_beli }}"
                                            data-merk="{{ $mobil->merek_mobil }}"
                                            data-model="{{ $mobil->tipe_mobil }}"
                                            data-tahun-pembuatan="{{ $mobil->tahun_pembuatan }}"
                                            data-nomor-polisi="{{ $mobil->nomor_polisi }}"
                                            {{ old('mobil_id', $transaksiPembelian->mobil_id) == $mobil->id ? 'selected' : '' }}>
                                            {{ $mobil->merek_mobil }} {{ $mobil->tipe_mobil }} ({{ $mobil->tahun_pembuatan }}) - {{ $mobil->nomor_polisi }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Mobil wajib dipilih.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="penjual_id" class="form-label text-muted">Pilih Penjual</label>
                                <select class="form-select select2" id="penjual_id" name="penjual_id" required>
                                    <option value="">-- Pilih Penjual --</option>
                                    @foreach($penjuals as $penjual)
                                        <option value="{{ $penjual->id }}"
                                            data-nama="{{ $penjual->nama }}"
                                            data-telepon="{{ $penjual->no_telepon }}"
                                            {{ old('penjual_id', $transaksiPembelian->penjual_id) == $penjual->id ? 'selected' : '' }}>
                                            {{ $penjual->nama }} ({{ $penjual->no_telepon }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Penjual wajib dipilih.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="harga_beli_mobil_final" class="form-label text-muted">Harga Beli Final</label>
                                <input type="text" id="harga_beli_mobil_final" name="harga_beli_mobil_final" class="form-control"
                                    value="{{ old('harga_beli_mobil_final') ? number_format(old('harga_beli_mobil_final'), 0, ',', '.') : number_format($transaksiPembelian->harga_beli_mobil_final, 0, ',', '.') }}"
                                    required min="0"
                                    placeholder="Contoh: 150.000.000"
                                    oninput="formatAndCalculateSummary(this)"
                                    onblur="if (!this.value) { this.value = '0'; calculateSummary(); }"
                                >
                                <div class="invalid-feedback">Harga beli final wajib diisi dan harus berupa angka.</div>
                                @error('harga_beli_mobil_final')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="keterangan" class="form-label text-muted">Keterangan Tambahan</label>
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="2">{{ old('keterangan', $transaksiPembelian->keterangan) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Bagian Detail Pembayaran --}}
                <div class="card border-0 shadow-xl rounded-4 mb-4 animate__animated animate__fadeInLeft section-panel">
                    <div class="card-body">
                        <div class="section-header d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold text-dark"><i class="bi bi-cash-coin me-2"></i> Detail Pembayaran</h5>
                            <button type="button" id="addPembayaranBtn" class="btn btn-sm btn-outline-primary rounded-pill">
                                <i class="bi bi-plus-circle me-2"></i> Tambah Pembayaran
                            </button>
                        </div>
                        <div id="pembayaran-wrapper">
                            {{-- Existing payment details will be loaded here by JavaScript --}}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                {{-- Ringkasan --}}
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
    <button type="submit" form="editForm" class="btn btn-primary btn-lg rounded-pill animate__animated animate__pulse animate__infinite">
        <i class="bi bi-save me-2"></i> Simpan Perubahan
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
        $('#mobil_id').select2({
            theme: "default",
            placeholder: "-- Pilih Mobil --",
            allowClear: true,
            dropdownParent: $('#mobil_id').parent(),
            width: '100%'
        });
        $('#penjual_id').select2({
            theme: "default",
            placeholder: "-- Pilih Penjual --",
            allowClear: true,
            dropdownParent: $('#penjual_id').parent(),
            width: '100%'
        });

        const pembayaranWrapper = document.getElementById('pembayaran-wrapper');
        const addPembayaranBtn = document.getElementById('addPembayaranBtn');
        const hargaBeliMobilFinalInput = document.getElementById('harga_beli_mobil_final');

        // Variable to store the payment item to be deleted
        let paymentItemToDelete = null;

        // Initialize Bootstrap Modal
        const confirmDeletePaymentModal = new bootstrap.Modal(document.getElementById('confirmDeletePaymentModal'));


        // Inisialisasi counter pembayaran. Ini penting agar indeks tidak tumpang tindih saat menambah item baru.
        // Gunakan jumlah detail pembayaran yang sudah ada + jumlah dari old() jika ada error validasi
        let paymentItemCounter = {{ $transaksiPembelian->detailPembayaran->count() }};
        @if(old('pembayaran_detail'))
            // Jika ada data old, pastikan counter mencakup indeks tertinggi dari old data
            const oldPaymentKeys = Object.keys(@json(old('pembayaran_detail')));
            if (oldPaymentKeys.length > 0) {
                const maxOldIndex = Math.max(...oldPaymentKeys.map(Number));
                if (maxOldIndex >= paymentItemCounter) {
                    paymentItemCounter = maxOldIndex + 1;
                }
            }
        @endif

        // --- GLOBAL FUNCTIONS ---

        // Main calculation function
        window.calculateSummary = function() {
            const hargaBeliFinalInput = document.getElementById('harga_beli_mobil_final').value;
            // Hapus semua karakter non-digit kecuali tanda minus di awal (untuk angka negatif jika diperlukan)
            const hargaBeliFinal = parseFloat(hargaBeliFinalInput.replace(/[^\d-]/g, '')) || 0;

            let totalPembayaran = 0;
            document.querySelectorAll('input[name^="pembayaran_detail"][name$="[jumlah_pembayaran]"]').forEach(input => {
                // Hapus semua karakter non-digit kecuali tanda minus di awal
                const valueWithoutNonDigits = input.value.replace(/[^\d-]/g, '');
                totalPembayaran += parseFloat(valueWithoutNonDigits) || 0;
            });

            // Update summary displays
            document.getElementById('summary-harga-mobil').textContent = formatCurrency(hargaBeliFinal);
            document.getElementById('summary-harga-beli-final').textContent = formatCurrency(hargaBeliFinal);
            document.getElementById('summary-total-pembayaran').textContent = formatCurrency(totalPembayaran);

            const sisaPembayaran = hargaBeliFinal - totalPembayaran;
            const sisaPembayaranElement = document.getElementById('summary-sisa-pembayaran');
            const statusAlertElement = document.getElementById('summary-status-alert');

            sisaPembayaranElement.textContent = formatCurrency(sisaPembayaran);

            // Update status based on sisaPembayaran
            if (sisaPembayaran > 0) {
                sisaPembayaranElement.className = 'value fw-bold text-danger';
                statusAlertElement.textContent = 'Status: Belum Lunas';
                statusAlertElement.className = 'summary-alert alert alert-danger text-center';
            } else if (sisaPembayaran < 0) {
                sisaPembayaranElement.className = 'value fw-bold text-info';
                statusAlertElement.textContent = 'Status: Pembayaran Melebihi Harga';
                statusAlertElement.className = 'summary-alert alert alert-info text-center';
            } else {
                sisaPembayaranElement.className = 'value fw-bold text-success';
                statusAlertElement.textContent = 'Status: Lunas';
                statusAlertElement.className = 'summary-alert alert alert-success text-center';
            }
        };

        // Format currency helper (optimized for real-time input)
        // Removes any decimal places (e.g., ,00)
        window.formatCurrency = function(amount) {
            if (isNaN(amount) || amount === null || typeof amount === 'undefined') {
                return 'Rp 0';
            }
            // Use Intl.NumberFormat and explicitly set minimum and maximum fraction digits to 0
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0,
            }).format(amount);
        };

        // Function to format the input field directly (adds dots)
        window.formatAndCalculateSummary = function(inputElement) {
            // Hapus semua karakter non-digit dari nilai input
            let rawValue = inputElement.value.replace(/[^\d]/g, '');
            let formattedValue = '';
            if (rawValue) {
                // Tambahkan titik sebagai pemisah ribuan
                formattedValue = rawValue.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }
            inputElement.value = formattedValue;
            calculateSummary(); // Call summary calculation after formatting
        };

        // Function to format payment amount input
        window.formatPaymentAmount = function(inputElement) {
            // Hapus semua karakter non-digit dari nilai input
            let rawValue = inputElement.value.replace(/[^\d]/g, '');
            let formattedValue = '';
            if (rawValue) {
                // Tambahkan titik sebagai pemisah ribuan
                formattedValue = rawValue.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }
            inputElement.value = formattedValue;
            calculateSummary(); // Call summary calculation after formatting
        };

        // --- PAYMENT ITEM MANAGEMENT ---
        function addPaymentItem(payment = null, isOld = false) {
            const currentPaymentIndex = paymentItemCounter++; // Use and increment counter

            const item = document.createElement('div');
            item.className = 'pembayaran-item mb-3 p-3 position-relative animate__animated animate__fadeIn';

            const paymentId = payment ? (payment.id || '') : '';
            const metodePembayaranDetail = payment ? (payment.metode_pembayaran || '') : '';
            const jumlahPembayaran = payment ? (payment.jumlah_pembayaran || 0) : 0;
            const tanggalPembayaran = payment ? (payment.tanggal_pembayaran ? new Date(payment.tanggal_pembayaran).toISOString().split('T')[0] : '') : new Date().toISOString().split('T')[0];
            const keteranganPembayaranDetail = payment ? (payment.keterangan_pembayaran_detail || '') : '';
            const buktiPembayaranDetail = payment ? (payment.bukti_pembayaran_detail || '') : '';
            const deleteFileChecked = isOld && payment && payment.delete_file_bukti == '1' ? 'checked' : '';


            // Helper function to clean and get the full URL for existing files
            const getCleanedBuktiPath = (path) => {
                if (!path) {
                    return '';
                }
                // If it's already a full URL (e.g., from old input for uploaded new file)
                if (path.startsWith('http')) {
                    // This might happen if validation fails and old input contains a temporary file URL
                    // Or if it was already stored with a full URL.
                    // We need to ensure no double //storage/ if it was formed from asset('storage/...')
                    return path.replace(/(https?:\/\/[^\/]+\/storage)\/\/storage\//, '$1/');
                }

                // If it's a relative path from the database, ensure it's correctly prefixed
                let cleanedPath = path;
                cleanedPath = cleanedPath.replace(/^\/storage\//, ''); // Remove leading '/storage/' if present
                cleanedPath = cleanedPath.replace(/^storage\//, '');   // Remove leading 'storage/' if present
                cleanedPath = cleanedPath.replace(/^public\//, '');    // Remove leading 'public/' if present

                // Ensure no double slash at the start of the path after cleaning
                cleanedPath = cleanedPath.replace(/^\/\//, '/');

                return `{{ url('storage') }}/${cleanedPath}`;
            };

            const cleanedBuktiPathForJs = getCleanedBuktiPath(buktiPembayaranDetail);

            item.innerHTML = `
                <input type="hidden" name="pembayaran_detail[${currentPaymentIndex}][id]" value="${paymentId}">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted">Metode Pembayaran</label>
                        <select name="pembayaran_detail[${currentPaymentIndex}][metode_pembayaran]" class="form-select payment-detail-input" required>
                            <option value="">-- Pilih Metode --</option>
                            <option value="Cash" ${metodePembayaranDetail === 'Cash' ? 'selected' : ''}>Cash</option>
                            <option value="Transfer Bank" ${metodePembayaranDetail === 'Transfer Bank' ? 'selected' : ''}>Transfer Bank</option>
                        </select>
                        <div class="invalid-feedback">Metode pembayaran wajib diisi.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Jumlah Pembayaran</label>
                        <input type="text" name="pembayaran_detail[${currentPaymentIndex}][jumlah_pembayaran]" class="form-control payment-detail-input payment-amount-input"
                            value="${formatRupiahUntukInput(jumlahPembayaran)}"
                            required min="0" placeholder="Contoh: 50.000.000"
                            oninput="formatPaymentAmount(this)"
                            onblur="if (!this.value) { this.value = '0'; calculateSummary(); }"
                        >
                        <div class="invalid-feedback">Jumlah pembayaran wajib diisi dan tidak boleh negatif.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Tanggal Pembayaran</label>
                        <input type="date" name="pembayaran_detail[${currentPaymentIndex}][tanggal_pembayaran]" class="form-control payment-detail-input" value="${tanggalPembayaran}" required>
                        <div class="invalid-feedback">Tanggal pembayaran wajib diisi.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Keterangan Detail (Opsional)</label>
                        <input type="text" name="pembayaran_detail[${currentPaymentIndex}][keterangan_pembayaran_detail]" class="form-control payment-detail-input" value="${keteranganPembayaranDetail}">
                    </div>
                    <div class="col-md-12 mt-3">
                        <label class="form-label text-muted">File Bukti Pembayaran (JPG, JPEG, PNG, PDF)</label>
                        <input type="file" name="pembayaran_detail[${currentPaymentIndex}][bukti_pembayaran_detail]" class="form-control shadow-sm" accept=".pdf,.jpg,.jpeg,.png">
                        <small class="form-text text-muted">Unggah bukti pembayaran (maks. 2MB, format: JPG, JPEG, PNG, PDF). Kosongkan jika tidak ingin mengubah.</small>
                        ${buktiPembayaranDetail ? `
                            <div class="mt-2">
                                <a href="${cleanedBuktiPathForJs}" target="_blank" class="btn btn-sm btn-outline-info rounded-pill">
                                    <i class="bi bi-file-earmark-arrow-down-fill me-1"></i> Lihat Bukti Saat Ini
                                </a>
                                <div class="form-check form-check-inline ms-3">
                                    <input class="form-check-input" type="checkbox" name="pembayaran_detail[${currentPaymentIndex}][delete_file_bukti]" id="delete_file_bukti_${currentPaymentIndex}" value="1" ${deleteFileChecked}>
                                    <label class="form-check-label text-danger" for="delete_file_bukti_${currentPaymentIndex}">Hapus File Ini</label>
                                </div>
                                <input type="hidden" name="pembayaran_detail[${currentPaymentIndex}][existing_bukti_pembayaran_detail]" value="${buktiPembayaranDetail}">
                            </div>
                        ` : ''}
                    </div>
                </div>
                <button type="button" class="btn btn-danger remove-btn">
                    <i class="fas fa-trash me-1"></i> Hapus
                </button>
            `;

            pembayaranWrapper.appendChild(item);

            // Add event listeners for new payment detail inputs
            item.querySelectorAll('.payment-detail-input').forEach(input => {
                // For amount inputs, use the specific formatting function
                if (input.classList.contains('payment-amount-input')) {
                    input.addEventListener('input', () => window.formatPaymentAmount(input));
                    input.addEventListener('blur', () => {
                        if (!input.value) { input.value = '0'; }
                        calculateSummary();
                    });
                } else {
                    input.addEventListener('input', calculateSummary);
                }
            });

            // Add remove button functionality with confirmation modal
            item.querySelector('.remove-btn').addEventListener('click', function() {
                paymentItemToDelete = item; // Store the item to be deleted
                confirmDeletePaymentModal.show(); // Show the confirmation modal
            });
        }

        // Helper for formatting numbers with dots (for input values)
        // Ensures no decimal places are kept
        window.formatRupiahUntukInput = function(angka) {
            // Pastikan input adalah string dan hapus semua karakter non-digit (termasuk titik dan koma)
            let number_string = String(angka).replace(/[^\d]/g, '');
            if (number_string === '') {
                return ''; // Return empty string if no digits
            }
            // Tambahkan titik sebagai pemisah ribuan
            return number_string.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        // PHP logic to prepare initialPembayarans for JavaScript
        // This handles old() input for existing items, preserving existing file paths
        @php
            $initialPembayaransData = [];
            // Prioritize old input if validation failed
            if (old('pembayaran_detail')) {
                foreach (old('pembayaran_detail') as $index => $oldPayment) {
                    $paymentData = $oldPayment;

                    // If 'id' exists, it's an existing payment being edited
                    if (isset($oldPayment['id']) && $oldPayment['id']) {
                        // Find the original payment detail by ID
                        $originalPayment = $transaksiPembelian->detailPembayaran->firstWhere('id', $oldPayment['id']);

                        if ($originalPayment) {
                            // If 'bukti_pembayaran_detail' in old() is empty AND the original had a file,
                            // it means no new file was uploaded OR validation failed for a new file.
                            // In this case, retain the original file path.
                            if (empty($oldPayment['bukti_pembayaran_detail']) && $originalPayment->bukti_pembayaran_detail) {
                                // Only use original if delete checkbox is NOT checked in old()
                                if (!isset($oldPayment['delete_file_bukti']) || $oldPayment['delete_file_bukti'] != '1') {
                                    $paymentData['bukti_pembayaran_detail'] = $originalPayment->bukti_pembayaran_detail;
                                } else {
                                    // If delete checkbox IS checked, explicitly set to empty for old() state
                                    $paymentData['bukti_pembayaran_detail'] = '';
                                }
                            }
                        }
                    }
                    // For new items (no 'id' or 'id' is empty), old() data is used as is.
                    // If a file was uploaded, old() might contain a temporary path.
                    // If validation failed for a new file, old() for that file input might be empty.

                    $initialPembayaransData[] = $paymentData;
                }
            } else {
                // If no old input, load from $transaksiPembelian
                foreach ($transaksiPembelian->detailPembayaran as $payment) {
                    $initialPembayaransData[] = $payment->toArray();
                }
            }
        @endphp

        const initialPembayarans = @json($initialPembayaransData);

        // Load existing payment details or previously entered details on page load
        if (initialPembayarans.length > 0) {
            initialPembayarans.forEach(payment => {
                addPaymentItem(payment, true); // Pass true to indicate it's from old data/existing
            });
        } else {
            // If no existing payments and no old input, show the "Add payment" alert
            const newEmptyAlert = document.createElement('div');
            newEmptyAlert.classList.add('alert', 'alert-info', 'text-center', 'py-3', 'animate__animated', 'animate__fadeIn');
            newEmptyAlert.innerHTML = `<i class="bi bi-info-circle-fill me-2"></i> Klik "Tambah Pembayaran" untuk menambahkan detail pembayaran.`;
            pembayaranWrapper.appendChild(newEmptyAlert);
        }

        // Event listener for "Tambah Pembayaran" button
        addPembayaranBtn.addEventListener('click', () => addPaymentItem());

        // Event listener for the "Hapus" button inside the confirmation modal
        document.getElementById('confirmDeletePaymentBtn').addEventListener('click', function() {
            if (paymentItemToDelete) {
                // Create a hidden input to mark this detail for deletion on submit
                const hiddenDeleteInput = document.createElement('input');
                hiddenDeleteInput.type = 'hidden';

                // Determine the correct name based on whether it's an existing or new item
                const paymentIdInput = paymentItemToDelete.querySelector('input[name$="[id]"]');
                if (paymentIdInput && paymentIdInput.value) {
                    // Existing payment detail, mark it for deletion by ID
                    hiddenDeleteInput.name = `pembayaran_detail[${Array.from(pembayaranWrapper.children).indexOf(paymentItemToDelete)}][id_to_delete]`;
                    hiddenDeleteInput.value = paymentIdInput.value;
                } else {
                    // New payment detail (not yet in DB), just mark for removal from array
                    // The index needs to be accurate at time of form submission, which is handled by Laravel's old()
                    // For newly added items that are deleted before submission, they won't be sent anyway.
                    // This case primarily handles if it was present in old() but is now being deleted.
                    hiddenDeleteInput.name = `pembayaran_detail[${Array.from(pembayaranWrapper.children).indexOf(paymentItemToDelete)}][delete]`;
                    hiddenDeleteInput.value = '1';
                }
                document.getElementById('editForm').appendChild(hiddenDeleteInput);

                paymentItemToDelete.remove();
                paymentItemToDelete = null; // Clear the stored item
                calculateSummary(); // Recalculate summary after deletion
                confirmDeletePaymentModal.hide(); // Hide the modal
            }
            // Check if all items are removed and show the prompt
            if (pembayaranWrapper.children.length === 0) {
                 const newEmptyAlert = document.createElement('div');
                newEmptyAlert.classList.add('alert', 'alert-info', 'text-center', 'py-3', 'animate__animated', 'animate__fadeIn');
                newEmptyAlert.innerHTML = `<i class="bi bi-info-circle-fill me-2"></i> Klik "Tambah Pembayaran" untuk menambahkan detail pembayaran.`;
                pembayaranWrapper.appendChild(newEmptyAlert);
            }
        });

        // Event listeners for main form inputs that affect summary
        // Note: For select2, use 'change' event on the original select element, not on select2's wrapper.
        // We already have oninput for harga_beli_mobil_final, so just ensure initial calculation.
        $('#mobil_id').on('change', calculateSummary);
        $('#penjual_id').on('change', calculateSummary);
        document.getElementById('tanggal_transaksi').addEventListener('change', calculateSummary);

        // Initial calculation on page load for harga_beli_mobil_final and existing payments
        const initialHargaBeliFinal = document.getElementById('harga_beli_mobil_final');
        if (initialHargaBeliFinal.value) {
            // Call formatAndCalculateSummary to apply formatting and then calculate
            formatAndCalculateSummary(initialHargaBeliFinal);
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
