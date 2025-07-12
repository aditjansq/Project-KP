@extends('layouts.app')

@section('title', 'Edit Transaksi Penjualan')

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
            background-color: #f0f2f5; /* A slightly darker background for dashboard feel */
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

        .section-panel .section-header h5 {
            margin-bottom: 0;
            color: #343a40; /* Darker text for headers */
        }

        /* Light background for payment detail sections within their own panel */
        .pembayaran-item {
            background-color: #f8f9fa; /* A very light grey background */
            border: 1px solid #e9ecef !important; /* Softer border for these sub-sections */
            border-radius: 0.5rem !important; /* Consistent rounded corners for sub-items */
            padding: 1rem; /* Slightly less padding than main panels */
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
        .select2-container .select2-selection--single {
            height: calc(2.8rem + 2px); /* Match Bootstrap form-control-lg height */
            border: 1px solid #e0e6ed;
            border-radius: 0.5rem !important;
            display: flex;
            align-items: center;
        }

        .select2-container .select2-selection--single .select2-selection__rendered {
            line-height: 1.5;
            padding-left: 0.75rem;
            color: #495057;
        }

        .select2-container .select2-selection--single .select2-selection__arrow {
            height: calc(2.8rem + 2px);
            width: 20px;
            right: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #888 transparent transparent transparent;
            border-width: 5px 4px 0 4px;
        }

        .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
            border-color: transparent transparent #888 transparent;
            border-width: 0 4px 5px 4px;
        }

        .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
            background-color: #0d6efd !important;
            color: white;
        }

        .select2-container--default .select2-results__option--selected {
            background-color: #e9ecef !important;
            color: #343a40;
        }

        .select2-dropdown {
            border: 1px solid #e0e6ed;
            border-radius: 0.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden; /* Ensures rounded corners are visible */
        }

        .select2-search input {
            border-radius: 0.5rem !important;
            border-color: #e0e6ed !important;
        }
        .select2-search input:focus {
            border-color: #0d6efd !important;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25) !important;
        }

        /* Custom styling for remove button */
        .btn-danger.remove-btn {
            background-color: #dc3545;
            border-color: #dc3545;
            color: white;
            transition: all 0.2s ease;
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

        /* Hide the original button's bottom margin to prevent extra space when fixed button is active */
        .hidden-original-button-margin {
            margin-bottom: 0 !important;
        }
    </style>
</head>
<div class="container-fluid py-4 px-3 px-md-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8 mb-3 mb-md-0">
            <h4 class="text-dark fw-bold mb-0">Edit Data Transaksi Penjualan</h4>
            <small class="text-secondary">Silakan perbarui form berikut dengan data yang benar.</small>
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

    <form method="POST" action="{{ route('transaksi-penjualan.update', $transaksi_penjualan->id) }}" enctype="multipart/form-data" class="needs-validation" novalidate id="editForm">
        @csrf
        @method('PUT') {{-- Penting untuk metode UPDATE --}}

        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-xl rounded-4 mb-4 animate__animated animate__fadeInLeft section-panel">
                    <div class="card-body">
                        <div class="section-header mb-4">
                            <h5 class="fw-bold text-dark"><i class="bi bi-car-front-fill me-2"></i> Informasi Penjualan Utama</h5>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="kode_transaksi" class="form-label text-muted">Kode Transaksi</label>
                                <input type="text" class="form-control" id="kode_transaksi" name="kode_transaksi" value="{{ $transaksi_penjualan->kode_transaksi }}" readonly>
                                <small class="form-text text-muted">Kode transaksi otomatis dihasilkan.</small>
                            </div>
                            <div class="col-md-6">
                                <label for="tanggal_transaksi" class="form-label text-muted">Tanggal Transaksi</label>
                                <input type="date" class="form-control" id="tanggal_transaksi" name="tanggal_transaksi" value="{{ old('tanggal_transaksi', \Carbon\Carbon::parse($transaksi_penjualan->tanggal_transaksi)->format('Y-m-d')) }}" required>
                                <div class="invalid-feedback">
                                    Tanggal transaksi wajib diisi.
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="mobil_id" class="form-label text-muted">Pilih Mobil</label>
                                <select class="form-select select2" id="mobil_id" name="mobil_id" required>
                                    <option value="">-- Pilih Mobil --</option>
                                    @foreach ($mobils as $mobil)
                                        <option value="{{ $mobil->id }}"
                                            data-harga="{{ $mobil->harga_mobil }}"
                                            data-merk="{{ $mobil->merek_mobil }}"
                                            data-model="{{ $mobil->tipe_mobil }}"
                                            data-tahun-pembuatan="{{ $mobil->tahun_pembuatan }}"
                                            data-nomor-polisi="{{ $mobil->nomor_polisi }}"
                                            {{ old('mobil_id', $transaksi_penjualan->mobil_id) == $mobil->id ? 'selected' : '' }}>
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
                                <select class="form-select select2" id="pembeli_id" name="pembeli_id" required>
                                    <option value="">-- Pilih Pembeli --</option>
                                    @foreach ($pembelis as $pembeli)
                                        <option value="{{ $pembeli->id }}"
                                            data-nama="{{ $pembeli->nama }}"
                                            data-telepon="{{ $pembeli->no_telepon }}"
                                            {{ old('pembeli_id', $transaksi_penjualan->pembeli_id) == $pembeli->id ? 'selected' : '' }}>
                                            {{ $pembeli->nama }} ({{ $pembeli->no_telepon }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Pembeli wajib dipilih.
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="harga_mobil_display" class="form-label text-muted">Harga Dasar Mobil</label>
                                <input type="text" class="form-control" id="harga_mobil_display" value="Rp{{ number_format($transaksi_penjualan->mobil->harga_mobil ?? 0, 0, ',', '.') }}" readonly>
                                <small class="form-text text-muted">Harga dasar dari mobil yang dipilih.</small>
                            </div>
                            <div class="col-md-6">
                                <label for="harga_negosiasi" class="form-label text-muted">Harga Negosiasi</label>
                                <input type="number" class="form-control" id="harga_negosiasi" name="harga_negosiasi" value="{{ old('harga_negosiasi', $transaksi_penjualan->harga_negosiasi) }}" required min="0">
                                <div class="invalid-feedback">
                                    Harga negosiasi wajib diisi dan harus angka positif.
                                </div>
                                <small class="form-text text-muted">Harga akhir setelah negosiasi dengan pembeli.</small>
                            </div>
                            <div class="col-md-12">
                                <label for="metode_pembayaran" class="form-label text-muted">Metode Pembayaran Utama</label>
                                <select class="form-select" id="metode_pembayaran" name="metode_pembayaran" required>
                                    <option value="">-- Pilih Metode Pembayaran --</option>
                                    <option value="non_kredit" {{ old('metode_pembayaran', $transaksi_penjualan->metode_pembayaran) == 'non_kredit' ? 'selected' : '' }}>Non-Kredit</option>
                                    <option value="kredit" {{ old('metode_pembayaran', $transaksi_penjualan->metode_pembayaran) == 'kredit' ? 'selected' : '' }}>Kredit</option>
                                </select>
                                <div class="invalid-feedback">
                                    Metode pembayaran wajib dipilih.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kredit Details Section (Conditional) --}}
                <div id="kreditDetailsSection" class="card border-0 shadow-xl rounded-4 mb-4 animate__animated animate__fadeInLeft section-panel" style="display: {{ old('metode_pembayaran', $transaksi_penjualan->metode_pembayaran) == 'kredit' ? 'block' : 'none' }};">
                    <div class="card-body">
                        <div class="section-header mb-4">
                            <h5 class="fw-bold text-dark"><i class="bi bi-bank me-2"></i> Detail Kredit</h5>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="tempo" class="form-label text-muted">Tempo (Tahun)</label>
                                <input type="number" class="form-control" id="tempo" name="tempo" value="{{ old('tempo', $transaksi_penjualan->kreditDetail->tempo ?? 1) }}" min="1">
                                <div class="invalid-feedback">
                                    Tempo wajib diisi dan minimal 1 bulan.
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="leasing" class="form-label text-muted">Leasing</label>
                                <input type="text" class="form-control" id="leasing" name="leasing" value="{{ old('leasing', $transaksi_penjualan->kreditDetail->leasing ?? '') }}">
                                <div class="invalid-feedback">
                                    Leasing wajib diisi.
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="angsuran_per_bulan" class="form-label text-muted">Angsuran Per Bulan</label>
                                <input type="number" class="form-control" id="angsuran_per_bulan" name="angsuran_per_bulan" value="{{ old('angsuran_per_bulan', $transaksi_penjualan->kreditDetail->angsuran_per_bulan ?? 0) }}" min="0">
                                <div class="invalid-feedback">
                                    Angsuran per bulan wajib diisi dan harus angka positif.
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="refund" class="form-label text-muted">Jumlah Refund</label>
                                <input type="number" id="refund" name="refund" class="form-control" value="{{ old('refund', $transaksi_penjualan->kreditDetail->refund ?? 0) }}" min="0">
                                <div class="invalid-feedback">
                                    Jumlah refund harus berupa angka positif.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Payment Details Section --}}
                <div class="card border-0 shadow-xl rounded-4 mb-4 animate__animated animate__fadeInLeft section-panel">
                    <div class="card-body">
                        <div class="section-header d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold text-dark mb-0"><i class="bi bi-cash-coin me-2"></i> Detail Pembayaran</h5>
                            <button type="button" id="addPembayaranBtn" class="btn btn-sm btn-outline-primary rounded-pill">
                                <i class="bi bi-plus-circle me-2"></i> Tambah Pembayaran
                            </button>
                        </div>
                        <div id="pembayaranDetailsWrapper">
                            {{-- Payment details will be dynamically added here by JavaScript --}}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                {{-- Summary Card --}}
                <div class="card border-0 shadow-xl rounded-4 mb-4 animate__animated animate__fadeInRight section-panel sticky-top" style="top: 1.5rem;">
                    <div class="card-body">
                        <div class="section-header mb-4">
                            <h5 class="fw-bold text-dark"><i class="bi bi-calculator-fill me-2"></i> Ringkasan Transaksi</h5>
                        </div>
                        <div class="summary-detail">
                            <span class="label">Harga Dasar Mobil:</span>
                            <span class="value" id="summaryHargaDasar">Rp{{ number_format($transaksi_penjualan->mobil->harga_mobil ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="summary-detail">
                            <span class="label">Harga Negosiasi:</span>
                            <span class="value" id="summaryHargaNegosiasi">Rp0</span> {{-- Diubah agar diisi oleh JS secara realtime --}}
                        </div>
                        <div class="summary-detail">
                            <span class="label">Total Pembayaran Diterima:</span>
                            <span class="value" id="summaryTotalPembayaran">Rp{{ number_format($transaksi_penjualan->pembayaranDetails->sum('jumlah_pembayaran'), 0, ',', '.') }}</span>
                        </div>
                         {{-- DP Section (for kredit only) --}}
                        <div class="summary-detail" id="summaryDpSection" style="display: none;">
                            <span class="label">Sisa DP (harus dibayar):</span>
                            <span class="value text-danger" id="summaryDisplayDpCalculated">Rp0</span>
                        </div>
                        <input type="hidden" id="hidden_dp_value" name="dp" value="0">
                        <div class="summary-detail summary-total">
                            <span class="label fw-bold">Sisa Pembayaran:</span>
                            <span class="value fw-bold text-danger" id="summarySisaPembayaran">Rp0</span>
                        </div>
                        <div class="summary-alert alert alert-warning text-center" role="alert" id="paymentStatusAlert">
                            Status: Belum Lunas
                        </div>

                        {{-- Kredit Details Summary --}}
                        <div id="summaryKreditDetails" style="display: {{ old('metode_pembayaran', $transaksi_penjualan->metode_pembayaran) == 'kredit' ? 'block' : 'none' }};">
                            <hr class="my-3">
                            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-info-circle me-2"></i> Ringkasan Kredit</h6>
                            <div class="summary-detail">
                                <span class="label">Tempo:</span>
                                <span class="value" id="summaryTempo">-</span>
                            </div>
                            <div class="summary-detail">
                                <span class="label">Angsuran Per Bulan:</span>
                                <span class="value" id="summaryAngsuranPerBulan">Rp0</span>
                            </div>
                            <div class="summary-detail" id="summary-refund-row" style="display: none;"> {{-- Awalnya hidden --}}
                                <span class="label">Refund:</span>
                                <span class="value" id="summary-refund">Rp0</span>
                            </div>
                            <div class="summary-detail">
                                <span class="label">Leasing:</span>
                                <span class="value" id="summaryLeasing">-</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- Fixed bottom action bar (now the primary submit button) --}}
<div class="fixed-bottom-action-bar animate__animated animate__fadeInUp" id="fixedActionBar">
    <button type="submit" form="editForm" class="btn btn-primary btn-lg rounded-pill animate__animated animate__pulse animate__infinite" style="--animate-duration: 2s;">
        <i class="bi bi-save me-2"></i> Perbarui Transaksi
    </button>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

{{-- Bootstrap Bundle with Popper --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
{{-- Select2 JS --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    let pembayaranIndex = 0; // Mulai dari 0 karena semua item akan ditambahkan via JS

    // PHP logic to prepare initialPembayarans for JavaScript
    // This handles old() input for existing items, preserving existing file paths
    @php
        $initialPembayaransData = [];
        if (old('pembayaran')) {
            foreach (old('pembayaran') as $index => $oldPayment) {
                $paymentData = $oldPayment;
                // If 'id' exists, it's an existing payment being edited
                if (isset($oldPayment['id']) && $oldPayment['id']) {
                    // Find the original payment detail by ID
                    $originalPayment = $transaksi_penjualan->pembayaranDetails->firstWhere('id', $oldPayment['id']);

                    if ($originalPayment) {
                        // If 'bukti_pembayaran_detail' in old() is empty AND the original had a file,
                        // use the original file path. This covers validation failures for new files.
                        // If a new valid file was uploaded, old() would contain its temporary path.
                        if (empty($oldPayment['bukti_pembayaran_detail']) && $originalPayment->bukti_pembayaran_detail) {
                            $paymentData['bukti_pembayaran_detail'] = $originalPayment->bukti_pembayaran_detail;
                        }
                    }
                }
                $initialPembayaransData[] = $paymentData;
            }
        } else {
            $initialPembayaransData = $transaksi_penjualan->pembayaranDetails->toArray();
        }
    @endphp

    const initialTransaksi = @json($transaksi_penjualan);
    const initialPembayarans = @json($initialPembayaransData); // Menggunakan data yang sudah diproses PHP

    const addPembayaranBtn = document.getElementById('addPembayaranBtn');
    const pembayaranDetailsWrapper = document.getElementById('pembayaranDetailsWrapper');
    const metodePembayaranSelect = document.getElementById('metode_pembayaran');
    const kreditDetailsSection = document.getElementById('kreditDetailsSection');

    // Summary elements
    const summaryHargaDasar = document.getElementById('summaryHargaDasar');
    const summaryHargaNegosiasi = document.getElementById('summaryHargaNegosiasi');
    const summaryTotalPembayaran = document.getElementById('summaryTotalPembayaran');
    const summarySisaPembayaran = document.getElementById('summarySisaPembayaran');
    const paymentStatusAlert = document.getElementById('paymentStatusAlert');

    // Kredit Summary elements
    const summaryKreditDetails = document.getElementById('summaryKreditDetails');
    const summaryTempo = document.getElementById('summaryTempo');
    const summaryAngsuranPerBulan = document.getElementById('summaryAngsuranPerBulan');
    const summaryLeasing = document.getElementById('summaryLeasing');
    const summaryDpSection = document.getElementById('summaryDpSection');
    const summaryDisplayDpCalculated = document.getElementById('summaryDisplayDpCalculated');
    const hiddenDpValue = document.getElementById('hidden_dp_value');
    const summaryRefundRow = document.getElementById('summary-refund-row');
    const summaryRefund = document.getElementById('summary-refund');


    // Input elements for calculation
    const mobilIdSelect = document.getElementById('mobil_id');
    const pembeliIdSelect = document.getElementById('pembeli_id');
    const hargaNegosiasiInput = document.getElementById('harga_negosiasi');
    const tempoInput = document.getElementById('tempo');
    const angsuranPerBulanInput = document.getElementById('angsuran_per_bulan');
    const leasingInput = document.getElementById('leasing');
    const refundInput = document.getElementById('refund');


    const fixedActionBar = document.getElementById('fixedActionBar');


    // Utility function to format currency
    function formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
    }

    // Fungsi untuk memeriksa apakah pilihan utama sudah dibuat
    function checkMainSelectionsMade() {
        const isMobilSelected = mobilIdSelect.value !== '' && mobilIdSelect.value !== null;
        const isPembeliSelected = pembeliIdSelect.value !== '' && pembeliIdSelect.value !== null;
        const isMetodePembayaranSelected = metodePembayaranSelect.value !== '' && metodePembayaranSelect.value !== null;
        return isMobilSelected && isPembeliSelected && isMetodePembayaranSelected;
    }

    // Function to calculate and update summary
    function calculateSummary() {
        const mainSelectionsAreMade = checkMainSelectionsMade();

        if (!mainSelectionsAreMade) {
            // Reset semua display jika pilihan utama belum lengkap
            summaryHargaDasar.textContent = formatCurrency(0);
            summaryHargaNegosiasi.textContent = formatCurrency(0);
            summaryTotalPembayaran.textContent = formatCurrency(0);
            summarySisaPembayaran.textContent = formatCurrency(0);
            paymentStatusAlert.classList.add('d-none'); // Sembunyikan alert
            summaryDpSection.style.display = 'none'; // Sembunyikan DP
            summaryKreditDetails.style.display = 'none'; // Sembunyikan ringkasan kredit
            summaryRefundRow.style.display = 'none';


            return; // Hentikan fungsi jika pilihan utama belum lengkap
        }

        let selectedMobilHarga = 0;
        const selectedOptionMobil = mobilIdSelect.options[mobilIdSelect.selectedIndex];
        if (selectedOptionMobil && selectedOptionMobil.dataset.harga) {
            selectedMobilHarga = parseFloat(selectedOptionMobil.dataset.harga);
        }
        summaryHargaDasar.textContent = formatCurrency(selectedMobilHarga);


        const hargaNegosiasi = parseFloat(hargaNegosiasiInput.value) || 0;
        summaryHargaNegosiasi.textContent = formatCurrency(hargaNegosiasi);


        let totalPembayaranDiterimaFromDetails = 0;
        // Sum up all individual payments from the detail wrapper
        document.querySelectorAll('[name^="pembayaran"][name$="[jumlah_pembayaran]"]').forEach(input => {
            totalPembayaranDiterimaFromDetails += parseFloat(input.value) || 0;
        });
        summaryTotalPembayaran.textContent = formatCurrency(totalPembayaranDiterimaFromDetails);
        const refund = parseFloat(refundInput.value) || 0;


        let dpCalculated = 0;
        if (metodePembayaranSelect.value === 'kredit') {
            dpCalculated = hargaNegosiasi - totalPembayaranDiterimaFromDetails;
            if (dpCalculated < 0) dpCalculated = 0; // Pastikan DP tidak negatif

            summaryDpSection.style.display = 'flex'; // Tampilkan section DP
            summaryDisplayDpCalculated.textContent = formatCurrency(dpCalculated);
            hiddenDpValue.value = dpCalculated; // Simpan nilai DP yang dihitung ke hidden input
        } else {
            summaryDpSection.style.display = 'none'; // Sembunyikan section DP
            hiddenDpValue.value = 0;
        }

        // Sisa pembayaran (sebelum dipertimbangkan kredit)
        const sisaPembayaranAwal = hargaNegosiasi - totalPembayaranDiterimaFromDetails;
        let finalSisaPembayaran = sisaPembayaranAwal;

        // Logika untuk status pembayaran dan sisa pembayaran
        if (metodePembayaranSelect.value === 'kredit') {
            if (dpCalculated > 0) {
                summarySisaPembayaran.textContent = formatCurrency(dpCalculated); // Sisa pembayaran adalah sisa DP
                summarySisaPembayaran.classList.remove('text-success', 'text-primary');
                summarySisaPembayaran.classList.add('text-danger');
                paymentStatusAlert.textContent = "Pembayaran DP belum lunas.";
                paymentStatusAlert.className = 'alert alert-warning summary-alert';
            } else {
                summarySisaPembayaran.textContent = formatCurrency(0); // DP sudah lunas
                summarySisaPembayaran.classList.remove('text-danger', 'text-primary');
                summarySisaPembayaran.classList.add('text-success');
                paymentStatusAlert.textContent = "DP sudah lunas. Sisa pembayaran akan dicover oleh kredit.";
                paymentStatusAlert.className = 'alert alert-success summary-alert';
            }
        } else {
            // Untuk non-kredit, ini adalah sisa total
            summarySisaPembayaran.textContent = formatCurrency(finalSisaPembayaran);
            if (finalSisaPembayaran > 0) {
                summarySisaPembayaran.classList.remove('text-success', 'text-primary');
                summarySisaPembayaran.classList.add('text-danger');
                paymentStatusAlert.textContent = "Pembayaran belum lunas.";
                paymentStatusAlert.className = 'alert alert-danger summary-alert';
            } else if (finalSisaPembayaran < 0) {
                summarySisaPembayaran.classList.remove('text-danger', 'text-primary');
                summarySisaPembayaran.classList.add('text-success');
                paymentStatusAlert.textContent = "Pembayaran melebihi harga negosiasi. Kembalian: " + formatCurrency(Math.abs(finalSisaPembayaran));
                paymentStatusAlert.className = 'alert alert-info summary-alert';
            } else {
                summarySisaPembayaran.classList.remove('text-danger', 'text-info');
                summarySisaPembayaran.classList.add('text-success');
                paymentStatusAlert.textContent = "Pembayaran sudah lunas.";
                paymentStatusAlert.className = 'alert alert-success summary-alert';
            }
        }
        paymentStatusAlert.classList.remove('d-none');

        // Ringkasan Kredit (jika metode pembayaran adalah kredit)
        if (metodePembayaranSelect.value === 'kredit') {
            summaryKreditDetails.style.display = 'block';
            const tempoInYears = parseInt(tempoInput.value) || 0;
            const angsuranPerBulan = parseFloat(angsuranPerBulanInput.value) || 0;
            const leasingValue = leasingInput.value || '-';

            summaryTempo.textContent = `${tempoInYears} Tahun`;
            summaryAngsuranPerBulan.textContent = formatCurrency(angsuranPerBulan);
            summaryLeasing.textContent = leasingValue;

            summaryRefundRow.style.display = 'flex'; // Tampilkan baris refund
            summaryRefund.textContent = formatCurrency(refund); // Tampilkan nilai refund
        } else {
            summaryKreditDetails.style.display = 'none';
            summaryRefundRow.style.display = 'none';

        }
    }

    // Fungsi untuk menampilkan/menyembunyikan detail kredit dan mengatur atribut required
    function toggleKreditDetails() {
        const mainSelectionsAreMade = checkMainSelectionsMade();

        if (mainSelectionsAreMade && metodePembayaranSelect.value === 'kredit') {
            kreditDetailsSection.style.display = 'block';
            kreditDetailsSection.classList.add('animate__fadeInLeft');
            tempoInput.setAttribute('required', 'required');
            leasingInput.setAttribute('required', 'required');
            angsuranPerBulanInput.setAttribute('required', 'required');
        } else {
            kreditDetailsSection.style.display = 'none';
            kreditDetailsSection.classList.remove('animate__fadeInLeft');
            tempoInput.removeAttribute('required');
            leasingInput.removeAttribute('required');
            angsuranPerBulanInput.removeAttribute('required');
            // Clear kredit fields if hidden
            tempoInput.value = '';
            angsuranPerBulanInput.value = '';
            leasingInput.value = '';
            refundInput.value = 0; // <--- TAMBAHKAN BARIS INI

        }
        calculateSummary(); // Recalculate summary after method change
    }

    // Fungsi untuk menambahkan item pembayaran detail
    function addPaymentItem(payment = null) {
        // Hapus alert "Klik Tambah Pembayaran" jika ada
        const existingAlert = pembayaranDetailsWrapper.querySelector('.alert-info');
        if (existingAlert) {
            existingAlert.remove();
        }

        const item = document.createElement('div');
        item.className = 'pembayaran-item mb-3 p-3 position-relative animate__animated animate__fadeIn';

        const currentPaymentIndex = pembayaranIndex;
        pembayaranIndex++;

        const paymentId = payment ? (payment.id || '') : '';
        const metodePembayaranDetail = payment ? (payment.metode_pembayaran_detail || '') : '';
        const jumlahPembayaran = payment ? (payment.jumlah_pembayaran || 0) : 0;
        const tanggalPembayaran = payment ? (payment.tanggal_pembayaran ? new Date(payment.tanggal_pembayaran).toISOString().split('T')[0] : '') : new Date().toISOString().split('T')[0];
        const keteranganPembayaranDetail = payment ? (payment.keterangan_pembayaran_detail || '') : '';
        const buktiPembayaranDetail = payment ? (payment.bukti_pembayaran_detail || '') : '';

        // Clean the path for dynamic JavaScript rendering
        const cleanedBuktiPathForJs = buktiPembayaranDetail.replace('public/', '').replace('storage/', '');

        item.innerHTML = `
            <div class="row g-3">
                <input type="hidden" name="pembayaran[${currentPaymentIndex}][id]" value="${paymentId}">
                <div class="col-md-4">
                    <label class="form-label text-muted">Metode Pembayaran</label>
                    <select name="pembayaran[${currentPaymentIndex}][metode_pembayaran_detail]" class="form-select payment-detail-input" required>
                        <option value="">-- Pilih Metode --</option>
                        <option value="cash" ${metodePembayaranDetail === 'cash' ? 'selected' : ''}>Cash</option>
                        <option value="transfer_bank" ${metodePembayaranDetail === 'transfer_bank' ? 'selected' : ''}>Transfer Bank</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label text-muted">Jumlah Pembayaran</label>
                    <input type="number" name="pembayaran[${currentPaymentIndex}][jumlah_pembayaran]" class="form-control payment-detail-input" value="${jumlahPembayaran}" required step="0.01" min="0">
                </div>
                <div class="col-md-4">
                    <label class="form-label text-muted">Tanggal Pembayaran</label>
                    <input type="date" name="pembayaran[${currentPaymentIndex}][tanggal_pembayaran]" class="form-control payment-detail-input" value="${tanggalPembayaran}" required>
                </div>
                <div class="col-md-12 mt-3">
                    <label class="form-label text-muted">Keterangan</label>
                    <textarea name="pembayaran[${currentPaymentIndex}][keterangan_pembayaran_detail]" class="form-control rounded-3 shadow-sm payment-detail-input" rows="2">${keteranganPembayaranDetail}</textarea>
                </div>
                <div class="col-md-12 mt-3">
                    <label class="form-label text-muted">File Bukti Pembayaran (JPG, JPEG, PNG, PDF)</label>
                    <input type="file" name="pembayaran[${currentPaymentIndex}][bukti_pembayaran_detail]" class="form-control shadow-sm">
                    <small class="form-text text-muted">Unggah bukti pembayaran (maks. 2MB, format: JPG, JPEG, PNG, PDF).</small>
                    ${buktiPembayaranDetail ? `
                        <div class="mt-2">
                            <a href="{{ url('storage') }}/${cleanedBuktiPathForJs}" target="_blank" class="btn btn-sm btn-outline-info rounded-pill">
                                <i class="bi bi-file-earmark-arrow-down-fill me-1"></i> Lihat Bukti Saat Ini
                            </a>
                            <div class="form-check form-check-inline ms-3">
                                <input class="form-check-input" type="checkbox" name="pembayaran[${currentPaymentIndex}][delete_file_bukti]" id="delete_file_bukti_${currentPaymentIndex}" value="1">
                                <label class="form-check-label text-danger" for="delete_file_bukti_${currentPaymentIndex}">Hapus File Ini</label>
                            </div>
                            <input type="hidden" name="pembayaran[${currentPaymentIndex}][existing_bukti_pembayaran_detail]" value="${buktiPembayaranDetail}">
                        </div>
                    ` : ''}
                </div>
                <div class="col-12 mt-2 text-end">
                    <button type="button" class="btn btn-danger btn-sm rounded-3 remove-btn">
                        <i class="fas fa-trash me-1"></i> Hapus
                    </button>
                </div>
            </div>
        `;

        pembayaranDetailsWrapper.appendChild(item);

        // Add event listeners for new payment detail inputs
        item.querySelectorAll('.payment-detail-input').forEach(input => {
            input.addEventListener('input', calculateSummary);
        });

        // Add event listener for remove button
        item.querySelector('.remove-btn').addEventListener('click', () => {
            item.remove();
            calculateSummary(); // Recalculate summary after removing an item
            // Show empty state message if no payment items left
            if (pembayaranDetailsWrapper.children.length === 0) {
                const newEmptyAlert = document.createElement('div');
                newEmptyAlert.classList.add('alert', 'alert-info', 'text-center', 'py-3', 'animate__animated', 'animate__fadeIn');
                newEmptyAlert.innerHTML = `<i class="bi bi-info-circle-fill me-2"></i> Klik "Tambah Pembayaran" untuk menambahkan detail pembayaran.`;
                pembayaranDetailsWrapper.appendChild(newEmptyAlert);
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Select2
        $('#mobil_id').select2({
            theme: "default",
            placeholder: "Pilih Mobil",
            allowClear: true,
            dropdownParent: $('#mobil_id').parent(),
            width: '100%'
        });
        $('#pembeli_id').select2({
            theme: "default",
            placeholder: "Pilih Pembeli",
            allowClear: true,
            dropdownParent: $('#pembeli_id').parent(),
            width: '100%'
        });

        // Populate existing payments (untuk edit page)
        if (initialPembayarans && initialPembayarans.length > 0) {
            initialPembayarans.forEach(payment => {
                addPaymentItem(payment);
            });
            // Update the index to avoid conflicts with existing items if new items are added
            pembayaranIndex = initialPembayarans.length;
        } else {
            // Jika tidak ada pembayaran awal, tampilkan pesan kosong
            if (pembayaranDetailsWrapper.children.length === 0) {
                 const newEmptyAlert = document.createElement('div');
                newEmptyAlert.classList.add('alert', 'alert-info', 'text-center', 'py-3', 'animate__animated', 'animate__fadeIn');
                newEmptyAlert.innerHTML = `<i class="bi bi-info-circle-fill me-2"></i> Klik "Tambah Pembayaran" untuk menambahkan detail pembayaran.`;
                pembayaranDetailsWrapper.appendChild(newEmptyAlert);
            }
        }

        // Add button listener
        addPembayaranBtn.addEventListener('click', () => addPaymentItem());

        // Event listeners untuk input utama
        mobilIdSelect.addEventListener('change', calculateSummary);
        pembeliIdSelect.addEventListener('change', calculateSummary);
        metodePembayaranSelect.addEventListener('change', toggleKreditDetails);
        hargaNegosiasiInput.addEventListener('input', calculateSummary);
        tempoInput.addEventListener('input', calculateSummary);
        angsuranPerBulanInput.addEventListener('input', calculateSummary);
        leasingInput.addEventListener('input', calculateSummary);
        refundInput.addEventListener('input', calculateSummary); // <--- TAMBAHKAN BARIS INI


        // Initial calculation on page load
        toggleKreditDetails();

        // Fixed button visibility logic
        fixedActionBar.classList.remove('d-none');
        fixedActionBar.classList.add('animate__fadeInUp');

        // Form validation (Bootstrap 5)
        var forms = document.querySelectorAll('.needs-validation');
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
    });
</script>
@endsection
