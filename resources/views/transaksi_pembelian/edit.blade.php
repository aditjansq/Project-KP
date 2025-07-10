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
    {{-- Font Awesome untuk ikon file --}}
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

        /* Hide the original button's bottom margin to prevent extra space when fixed button is active */
        .hidden-original-button-margin {
            margin-bottom: 0 !important;
        }
    </style>
</head>

<div class="container-fluid py-4 px-3 px-md-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8 mb-3 mb-md-0">
            <h4 class="text-dark fw-bold mb-0">Edit Data Transaksi Pembelian Mobil</h4>
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
                            {{-- Using h5, font-weight controlled by CSS --}}
                            <h5 class="text-dark"><i class="bi bi-info-circle-fill me-2"></i> Informasi Utama Transaksi</h5>
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
                                <label for="mobil_id" class="form-label text-muted">Mobil</label>
                                <select class="form-select select2" id="mobil_id" name="mobil_id" required>
                                    <option value="">-- Pilih Mobil --</option>
                                    @foreach($mobils as $mobil)
                                        <option value="{{ $mobil->id }}" {{ old('mobil_id', $transaksiPembelian->mobil_id) == $mobil->id ? 'selected' : '' }}>
                                            {{ $mobil->merek_mobil }} {{ $mobil->tipe_mobil }} ({{ $mobil->tahun_pembuatan }}) {{ $mobil->nomor_polisi }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Mobil wajib dipilih.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="penjual_id" class="form-label text-muted">Penjual</label>
                                <select class="form-select select2" id="penjual_id" name="penjual_id" required>
                                    <option value="">-- Pilih Penjual --</option>
                                    @foreach($penjuals as $penjual)
                                        <option value="{{ $penjual->id }}" {{ old('penjual_id', $transaksiPembelian->penjual_id) == $penjual->id ? 'selected' : '' }}>
                                            {{ $penjual->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Penjual wajib dipilih.</div>
                            </div>
                            <div class="col-md-12">
                                <label for="harga_beli_mobil_final" class="form-label text-muted">Harga Beli Mobil Final</label>
                                <input type="number" class="form-control" id="harga_beli_mobil_final" name="harga_beli_mobil_final" value="{{ old('harga_beli_mobil_final', $transaksiPembelian->harga_beli_mobil_final) }}" required min="0">
                                <div class="invalid-feedback">Harga beli mobil final wajib diisi dan tidak boleh negatif.</div>
                            </div>
                            <div class="col-md-12">
                                <label for="keterangan" class="form-label text-muted">Keterangan (Opsional)</label>
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="3">{{ old('keterangan', $transaksiPembelian->keterangan) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Bagian Detail Pembayaran --}}
                <div class="card border-0 shadow-xl rounded-4 mb-4 animate__animated animate__fadeInLeft section-panel">
                    <div class="card-body">
                        <div class="section-header d-flex justify-content-between align-items-center mb-4">
                            {{-- Updated icon for Detail Pembayaran --}}
                            <h5 class="text-dark mb-0"><i class="bi bi-cash-coin me-2"></i> Detail Pembayaran</h5>
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
                {{-- Ringkasan --}}
                <div class="card border-0 shadow-xl rounded-4 mb-4 animate__animated animate__fadeInRight section-panel sticky-top" style="top: 1.5rem;">
                    <div class="card-body">
                        <div class="section-header mb-4">
                            {{-- Using h5, font-weight controlled by CSS, and updated icon --}}
                            <h5 class="text-dark"><i class="bi bi-calculator-fill me-2"></i> Ringkasan Transaksi</h5>
                        </div>
                        <div class="summary-detail">
                            <span class="label">Total Harga Mobil:</span>
                            <span class="value" id="summaryHargaMobil">Rp 0</span>
                        </div>
                        <div class="summary-detail">
                            <span class="label">Total Pembayaran Diterima:</span>
                            <span class="value" id="summaryTotalPembayaran">Rp 0</span>
                        </div>
                        <div class="summary-detail summary-total">
                            <span class="label fw-bold">Sisa Pembayaran:</span>
                            <span class="value" id="summarySisaPembayaran">Rp 0</span>
                        </div>
                        {{-- Updated Status Pembayaran UI --}}
                        <div class="summary-alert alert mt-3 text-center" role="alert" id="summaryStatusPembayaranContainer">
                            Status: <span id="summaryStatusPembayaran">Belum Diketahui</span>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Fixed Bottom Action Bar --}}
        <div class="fixed-bottom-action-bar animate__animated animate__fadeInUp">
            <button type="submit" class="btn btn-primary btn-lg rounded-pill animate__pulse">
                <i class="bi bi-save me-2"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>

{{-- jQuery (needed for Select2) --}}
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
{{-- Bootstrap Bundle with Popper --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
{{-- Select2 JS --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi Select2
        $('#mobil_id').select2({
            theme: "default",
            placeholder: "Pilih Mobil",
            allowClear: true,
            dropdownParent: $('#mobil_id').parent(),
            width: '100%'
        });
        $('#penjual_id').select2({
            theme: "default",
            placeholder: "Pilih Penjual",
            allowClear: true,
            dropdownParent: $('#penjual_id').parent(),
            width: '100%'
        });

        const pembayaranDetailsWrapper = document.getElementById('pembayaranDetailsWrapper');
        const addPembayaranBtn = document.getElementById('addPembayaranBtn');
        const hargaBeliMobilFinalInput = document.getElementById('harga_beli_mobil_final');
        const mobilIdSelect = document.getElementById('mobil_id');
        const penjualIdSelect = document.getElementById('penjual_id');

        const summaryHargaMobil = document.getElementById('summaryHargaMobil');
        const summaryTotalPembayaran = document.getElementById('summaryTotalPembayaran');
        const summarySisaPembayaran = document.getElementById('summarySisaPembayaran');
        const summaryStatusPembayaran = document.getElementById('summaryStatusPembayaran');
        const summaryStatusPembayaranContainer = document.getElementById('summaryStatusPembayaranContainer');

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

        // Fungsi untuk menghitung ringkasan
        function calculateSummary() {
            let totalHargaMobil = parseFloat(hargaBeliMobilFinalInput.value) || 0;
            let totalPembayaranDiterima = 0;

            // Iterasi melalui semua input jumlah_pembayaran yang ada di wrapper
            document.querySelectorAll('[name^="pembayaran_detail"][name$="[jumlah_pembayaran]"]').forEach(input => {
                totalPembayaranDiterima += parseFloat(input.value) || 0;
            });

            let sisaPembayaran = totalHargaMobil - totalPembayaranDiterima;
            let statusPembayaranText = '';
            let statusClass = '';

            if (totalPembayaranDiterima >= totalHargaMobil && totalHargaMobil > 0) {
                statusPembayaranText = 'Lunas';
                statusClass = 'alert-success';
            } else if (totalPembayaranDiterima > 0 && totalPembayaranDiterima < totalHargaMobil) {
                statusPembayaranText = 'Sebagian Dibayar';
                statusClass = 'alert-warning';
            } else {
                statusPembayaranText = 'Belum Dibayar';
                statusClass = 'alert-danger';
            }

            summaryHargaMobil.textContent = formatRupiah(totalHargaMobil);
            summaryTotalPembayaran.textContent = formatRupiah(totalPembayaranDiterima);
            summarySisaPembayaran.textContent = formatRupiah(sisaPembayaran);
            summaryStatusPembayaran.textContent = statusPembayaranText;

            // Update class pada container status
            summaryStatusPembayaranContainer.className = 'summary-alert alert mt-3 text-center ' + statusClass;
        }

        // Fungsi format Rupiah
        function formatRupiah(angka) {
            let reverse = angka.toString().split('').reverse().join('');
            let ribuan = reverse.match(/\d{1,3}/g);
            let result = ribuan.join('.').split('').reverse().join('');
            return 'Rp ' + result;
        }

        // Fungsi untuk menambahkan item pembayaran baru
        function addPaymentItem(payment = null) {
            // Hapus alert "Klik Tambah Pembayaran" jika ada
            const existingAlert = pembayaranDetailsWrapper.querySelector('.alert-info');
            if (existingAlert) {
                existingAlert.remove();
            }

            const item = document.createElement('div');
            item.className = 'pembayaran-item mb-3 p-3 position-relative animate__animated animate__fadeIn';

            const currentPaymentIndex = paymentItemCounter++; // Gunakan dan tingkatkan counter

            const paymentId = payment ? (payment.id || '') : '';
            const metodePembayaranDetail = payment ? (payment.metode_pembayaran || '') : '';
            const jumlahPembayaran = payment ? (payment.jumlah_pembayaran || 0) : 0;
            const tanggalPembayaran = payment ? (payment.tanggal_pembayaran ? new Date(payment.tanggal_pembayaran).toISOString().split('T')[0] : '') : new Date().toISOString().split('T')[0];
            const keteranganPembayaranDetail = payment ? (payment.keterangan_pembayaran_detail || '') : '';
            const buktiPembayaranDetail = payment ? (payment.bukti_pembayaran_detail || '') : '';

            // START REVISI JAVASCRIPT
            const cleanedBuktiPathForJs = (() => {
                if (!buktiPembayaranDetail) {
                    return ''; // Tangani jika kosong atau null
                }
                if (buktiPembayaranDetail.startsWith('http')) {
                    // Jika sudah berupa URL HTTP, periksa dan perbaiki masalah duplikasi //storage/
                    return buktiPembayaranDetail.replace(/(https?:\/\/[^\/]+\/storage)\/\/storage\//, '$1/');
                }

                // Jika bukan URL lengkap (masih berupa path relatif dari database)
                // Bersihkan semua kemungkinan prefiks yang tidak diinginkan
                let path = buktiPembayaranDetail;
                path = path.replace(/^\/storage\//, ''); // Hapus '/storage/' di awal
                path = path.replace(/^storage\//, '');   // Hapus 'storage/' di awal
                path = path.replace(/^public\//, '');    // Hapus 'public/' di awal

                // Pastikan tidak ada double slash di awal path setelah pembersihan
                path = path.replace(/^\/\//, '/');

                return `{{ url('storage') }}/${path}`;
            })();
            // END REVISI JAVASCRIPT


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
                        <input type="number" name="pembayaran_detail[${currentPaymentIndex}][jumlah_pembayaran]" class="form-control payment-detail-input" value="${jumlahPembayaran}" required min="0">
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
                        <small class="form-text text-muted">Unggah bukti pembayaran (maks. 2MB, format: JPG, JPEG, PNG, PDF).</small>
                        ${buktiPembayaranDetail ? `
                            <div class="mt-2">
                                <a href="${cleanedBuktiPathForJs}" target="_blank" class="btn btn-sm btn-outline-info rounded-pill">
                                    <i class="bi bi-file-earmark-arrow-down-fill me-1"></i> Lihat Bukti Saat Ini
                                </a>
                                <div class="form-check form-check-inline ms-3">
                                    <input class="form-check-input" type="checkbox" name="pembayaran_detail[${currentPaymentIndex}][delete_file_bukti]" id="delete_file_bukti_${currentPaymentIndex}" value="1">
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

            pembayaranDetailsWrapper.appendChild(item);

            // Add event listeners for new payment detail inputs
            item.querySelectorAll('.payment-detail-input').forEach(input => {
                input.addEventListener('input', calculateSummary);
            });

            // Add event listener for remove button
            item.querySelector('.remove-btn').addEventListener('click', () => {
                item.remove();
                checkEmptyPaymentItems(); // Panggil fungsi ini setelah menghapus item
                calculateSummary(); // Recalculate summary after removing an item
            });
        }

        // Fungsi untuk memeriksa apakah tidak ada item pembayaran
        function checkEmptyPaymentItems() {
            if (pembayaranDetailsWrapper.children.length === 0) {
                const newEmptyAlert = document.createElement('div');
                newEmptyAlert.classList.add('alert', 'alert-info', 'text-center', 'py-3', 'animate__animated', 'animate__fadeIn');
                newEmptyAlert.innerHTML = `<i class="bi bi-info-circle-fill me-2"></i> Klik "Tambah Pembayaran" untuk menambahkan detail pembayaran.`;
                pembayaranDetailsWrapper.appendChild(newEmptyAlert);
            }
        }

        // PHP logic to prepare initialPembayarans for JavaScript
        // This handles old() input for existing items, preserving existing file paths
        @php
            $initialPembayaransData = [];
            if (old('pembayaran_detail')) {
                foreach (old('pembayaran_detail') as $index => $oldPayment) {
                    $paymentData = $oldPayment;
                    // If 'id' exists, it's an existing payment being edited
                    if (isset($oldPayment['id']) && $oldPayment['id']) {
                        // Find the original payment detail by ID
                        $originalPayment = $transaksiPembelian->detailPembayaran->firstWhere('id', $oldPayment['id']);

                        if ($originalPayment) {
                            // If 'bukti_pembayaran_detail' in old() is empty AND the original had a file,
                            // use the original file path. This covers validation failures for new files.
                            // If a new valid file was uploaded, old() would contain its temporary path.
                            if (empty($oldPayment['bukti_pembayaran_detail']) && $originalPayment->bukti_pembayaran_detail) {
                                // START REVISI PHP
                                $cleanedPath = $originalPayment->bukti_pembayaran_detail;
                                if (str_starts_with($cleanedPath, '/storage/')) {
                                    $cleanedPath = substr($cleanedPath, strlen('/storage/'));
                                } else if (str_starts_with($cleanedPath, 'storage/')) {
                                    $cleanedPath = substr($cleanedPath, strlen('storage/'));
                                } else if (str_starts_with($cleanedPath, 'public/')) {
                                    $cleanedPath = substr($cleanedPath, strlen('public/'));
                                }
                                $paymentData['bukti_pembayaran_detail'] = Storage::url($cleanedPath); // Gunakan Storage::url() untuk path yang bisa diakses
                                // END REVISI PHP
                            }
                        }
                    }
                    $initialPembayaransData[] = $paymentData;
                }
            } else {
                // Jika tidak ada old data, gunakan data dari model
                foreach ($transaksiPembelian->detailPembayaran as $detail) {
                    $paymentData = $detail->toArray();
                    if ($detail->bukti_pembayaran_detail) {
                        // START REVISI PHP
                        $cleanedPath = $detail->bukti_pembayaran_detail;
                        if (str_starts_with($cleanedPath, '/storage/')) {
                            $cleanedPath = substr($cleanedPath, strlen('/storage/'));
                        } else if (str_starts_with($cleanedPath, 'storage/')) {
                            $cleanedPath = substr($cleanedPath, strlen('storage/'));
                        } else if (str_starts_with($cleanedPath, 'public/')) {
                            $cleanedPath = substr($cleanedPath, strlen('public/'));
                        }
                        $paymentData['bukti_pembayaran_detail'] = Storage::url($cleanedPath);
                        // END REVISI PHP
                    }
                    $initialPembayaransData[] = $paymentData;
                }
            }
        @endphp

        const initialPembayarans = @json($initialPembayaransData);

        // Populate existing payments (untuk edit page)
        if (initialPembayarans && initialPembayarans.length > 0) {
            initialPembayarans.forEach(payment => {
                addPaymentItem(payment);
            });
        } else {
            // Jika tidak ada pembayaran awal, tampilkan pesan kosong
            checkEmptyPaymentItems();
        }

        // Add button listener
        addPembayaranBtn.addEventListener('click', () => addPaymentItem());

        // Event listeners untuk input utama
        mobilIdSelect.addEventListener('change', calculateSummary);
        penjualIdSelect.addEventListener('change', calculateSummary);
        hargaBeliMobilFinalInput.addEventListener('input', calculateSummary);

        // Initial calculation on page load
        calculateSummary();

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
