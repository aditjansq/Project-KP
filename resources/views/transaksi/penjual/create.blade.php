@extends('layouts.app')

@section('title', 'Tambah Transaksi Pembelian Mobil (Dari Penjual)')

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
            <h4 class="text-dark fw-bold mb-0">Tambah Transaksi Pembelian Mobil</h4>
            <small class="text-secondary">Isi detail transaksi pembelian mobil dari penjual.</small>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('transaksi.penjual.index') }}" class="btn btn-outline-secondary rounded-pill animate__animated animate__fadeInRight">
                <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar Transaksi Penjual
            </a>
        </div>
    </div>

    @if (session('success'))
    <div class="alert alert-success border-0 shadow-sm rounded-3 animate__animated animate__fadeInDown" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger border-0 shadow-sm rounded-3 animate__animated animate__shakeX" role="alert">
        <h6 class="alert-heading fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i> Terjadi Kesalahan:</h6>
        <p class="mb-0">{{ session('error') }}</p>
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
            {{-- Tambahkan enctype="multipart/form-data" untuk upload file --}}
            <form id="transaksiForm" method="POST" action="{{ route('transaksi.penjual.store') }}" enctype="multipart/form-data">
                @csrf

                <h5 class="mb-4 fw-bold text-dark border-bottom pb-2">Informasi Transaksi</h5>
                <div class="row g-3 mb-4">

                    <div class="col-md-6">
                        <label for="kode_transaksi" class="form-label text-muted">Kode Transaksi Pembelian</label>
                        <input type="text" class="form-control form-control-lg bg-light rounded-pill shadow-sm" name="kode_transaksi" id="kode_transaksi" value="{{ $kode_transaksi }}" readonly />
                    </div>

                    <div class="col-md-6">
                        @php
                            $today = date('Y-m-d');
                            $threeDaysAgo = date('Y-m-d', strtotime('-3 days'));
                        @endphp
                        <label for="tanggal_transaksi" class="form-label text-muted">Tanggal Transaksi Pembelian</label>
                        <input type="date" class="form-control form-control-lg rounded-pill shadow-sm @error('tanggal_transaksi') is-invalid @enderror" id="tanggal_transaksi" name="tanggal_transaksi" value="{{ old('tanggal_transaksi', $today) }}" min="{{ $threeDaysAgo }}" max="{{ $today }}" required>
                        @error('tanggal_transaksi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Bagian Mobil (Kolom Kiri) --}}
                    <div class="col-md-6">
                        <label for="mobil_id" class="form-label text-muted">Pilih Mobil Yang Akan Dibeli</label>
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
                                    data-jenis="{{ $mobil->jenis_mobil ?? '' }}"
                                    data-norangka="{{ $mobil->nomor_rangka ?? '' }}"
                                    data-nomesin="{{ $mobil->nomor_mesin ?? '' }}"
                                    {{ old('mobil_id') == $mobil->id ? 'selected' : '' }}>
                                    {{ $mobil->tahun_pembuatan ?? '' }} {{ $mobil->merek_mobil }} {{ $mobil->tipe_mobil ?? '' }} - {{ $mobil->nomor_polisi }}
                                </option>
                            @endforeach
                        </select>
                        @error('mobil_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <div class="mt-4">
                            <label class="form-label text-muted">Detail Mobil</label>
                            <div class="p-3 bg-light rounded-4 shadow-sm border border-secondary-subtle info-detail-block">
                                <div class="mb-1">
                                    <label class="form-label small text-secondary mb-0">No. Plat</label>
                                    <input type="text" class="form-control form-control-plaintext fw-bold" id="mobil_detail_nomorpolisi" readonly />
                                </div>
                                <div class="mb-1">
                                    <label class="form-label small text-secondary mb-0">Jenis Mobil</label>
                                    <input type="text" class="form-control form-control-plaintext" id="mobil_detail_jenis" readonly />
                                </div>
                                <div class="mb-1">
                                    <label class="form-label small text-secondary mb-0">Merek</label>
                                    <input type="text" class="form-control form-control-plaintext fw-bold" id="mobil_detail_merek" readonly />
                                </div>
                                <div class="mb-1">
                                    <label class="form-label small text-secondary mb-0">Model / Tipe</label>
                                    <input type="text" class="form-control form-control-plaintext" id="mobil_detail_tipe" readonly />
                                </div>
                                <div class="mb-1">
                                    <label class="form-label small text-secondary mb-0">Tahun</label>
                                    <input type="text" class="form-control form-control-plaintext" id="mobil_detail_tahun" readonly />
                                </div>
                                <div class="mb-1">
                                    <label class="form-label small text-secondary mb-0">Nomor Rangka</label>
                                    <input type="text" class="form-control form-control-plaintext" id="mobil_detail_norangka" readonly />
                                </div>
                                <div class="mb-1">
                                    <label class="form-label small text-secondary mb-0">Nomor Mesin</label>
                                    <input type="text" class="form-control form-control-plaintext" id="mobil_detail_nomesin" readonly />
                                </div>
                                <div class="mb-1">
                                    <label class="form-label small text-secondary mb-0">Warna</label>
                                    <input type="text" class="form-control form-control-plaintext" id="mobil_detail_warna" readonly />
                                </div>
                                {{-- Input for Harga Beli Mobil (Target Total) --}}
                                <div>
                                    <label class="form-label small text-secondary mb-0">Harga Beli Mobil (Target Total)</label>
                                    {{-- **REVISI PENTING DI SINI**: Mengubah name menjadi harga_beli_mobil_final --}}
                                    <input type="text" class="form-control form-control-lg rounded-pill shadow-sm @error('harga_beli_mobil_final') is-invalid @enderror" id="harga_beli_mobil_total" name="harga_beli_mobil_final" value="{{ old('harga_beli_mobil_final', 0) }}" placeholder="Masukkan total harga beli mobil" required>
                                    @error('harga_beli_mobil_final')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Bagian Penjual (Kolom Kanan) --}}
                    <div class="col-md-6">
                        <label for="penjual_id" class="form-label text-muted">Pilih Penjual</label>
                        <select class="form-control select2 form-select-lg rounded-pill shadow-sm @error('penjual_id') is-invalid @enderror" name="penjual_id" id="penjual_id" style="width: 100%" required>
                            <option value="">Pilih Penjual (Nama)</option>
                            @foreach($penjuals as $penjual)
                                <option
                                    value="{{ $penjual->id }}"
                                    data-nama="{{ $penjual->nama }}"
                                    data-email="{{ $penjual->email }}"
                                    data-notelepon="{{ $penjual->no_telepon ?? '' }}"
                                    data-alamat="{{ $penjual->alamat ?? '' }}"
                                    {{ old('penjual_id') == $penjual->id ? 'selected' : '' }}>
                                    {{ $penjual->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('penjual_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <div class="mt-4">
                            <label class="form-label text-muted">Informasi Penjual</label>
                            <div class="p-3 bg-light rounded-4 shadow-sm border border-secondary-subtle info-detail-block">
                                <div class="mb-1">
                                    <label class="form-label small text-secondary mb-0">Nama & Email</label>
                                    <input type="text" class="form-control form-control-plaintext fw-bold" id="penjual_info_nama_email" readonly />
                                </div>
                                <div class="mb-1">
                                    <label class="form-label small text-secondary mb-0">Nomor Telepon</label>
                                    <input type="text" class="form-control form-control-plaintext" id="penjual_info_telepon" readonly />
                                </div>
                                <div>
                                    <label class="form-label small text-secondary mb-0">Alamat</label>
                                    <input type="text" class="form-control form-control-plaintext" id="penjual_info_alamat" readonly />
                                </div>
                            </div>
                        </div>

                        {{-- Bagian Metode Pembayaran Dinamis --}}
                        <div class="mt-4">
                            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">Detail Pembayaran</h6>
                            <div id="paymentMethodsContainer">
                                {{-- Initial payment method row will be added by JS --}}
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm rounded-pill mt-2 animate__animated animate__fadeInUp" id="addPaymentMethodBtn">
                                <i class="bi bi-plus-circle me-2"></i> Tambah Metode Pembayaran Lain
                            </button>

                            <div class="mt-3 p-3 bg-light rounded-4 shadow-sm border border-info d-flex flex-column info-total-price-block">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label text-muted mb-0">Total Pembayaran Terisi:</label>
                                    <input type="text" class="form-control-plaintext text-end fw-bold fs-5 text-dark" id="total_payment_filled_display" value="Rp 0" readonly />
                                    {{-- Hidden input ini sekarang tidak lagi digunakan untuk harga_beli_mobil_final,
                                         tapi tetap digunakan untuk menyimpan total pembayaran terisi --}}
                                    <input type="hidden" id="total_payment_filled_hidden" name="total_payment_filled_hidden" value="0">
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="form-label text-muted mb-0">Sisa / Kelebihan:</label>
                                    <input type="text" class="form-control-plaintext text-end fw-bold fs-5" id="payment_balance_display" value="Rp 0" readonly />
                                    <input type="hidden" id="payment_balance_hidden" name="payment_balance_hidden" value="0">
                                </div>
                                <div id="payment_balance_warning" class="alert alert-warning border-0 p-2 mt-2 animate__animated animate__shakeX" style="display: none;">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i> Total pembayaran belum sesuai dengan harga beli mobil!
                                </div>
                            </div>
                        </div>

                        {{-- Tambah input untuk Bukti Pembayaran File --}}
                        <div class="mt-4">
                            <label for="bukti_pembayaran_file" class="form-label text-muted">Upload Bukti Pembayaran (Opsional)</label>
                            <input type="file" class="form-control form-control-lg rounded-pill shadow-sm @error('bukti_pembayaran_file') is-invalid @enderror" id="bukti_pembayaran_file" name="bukti_pembayaran_file">
                            <small class="form-text text-muted">File: JPEG, PNG, PDF (Max 2MB)</small>
                            @error('bukti_pembayaran_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mt-3">
                            <label class="form-label text-muted">Total Harga Pembelian (Final)</label>
                            <div class="p-3 bg-light rounded-4 shadow-sm border border-primary d-flex align-items-center info-total-price-block w-100">
                                {{-- Hidden input ini sekarang tidak lagi diperlukan karena input utama harga_beli_mobil_total sudah bernama harga_beli_mobil_final --}}
                                {{-- <input type="hidden" name="harga_beli_mobil_final" id="harga_beli_mobil_final_hidden" value="{{ old('harga_beli_mobil_final', 0) }}"> --}}
                                <input type="text" class="form-control-plaintext text-center fw-bold fs-4 text-primary" id="final_total_purchase_display" value="Rp 0" readonly />
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 mt-5">
                        <label for="keterangan" class="form-label text-muted">Keterangan (Opsional)</label>
                        <textarea class="form-control form-control-lg rounded-3 shadow-sm @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="3" placeholder="Masukkan detail transaksi tambahan...">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <input type="hidden" name="status_pembayaran" id="transaksi_status_pembayaran" value="Menunggu Pembayaran">
                </div>

                <div class="mt-5 d-flex justify-content-end gap-3">
                    <button type="button" class="btn btn-success btn-lg px-4 rounded-pill shadow-sm animate__animated animate__pulse animate__infinite" id="confirmSaveBtn">
                        <i class="bi bi-save me-2"></i> Simpan Transaksi Pembelian
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Confirmation Modal --}}
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 shadow-lg">
            <div class="modal-header bg-primary text-white border-0 rounded-top-4">
                <h5 class="modal-title fw-bold" id="confirmationModalLabel">Konfirmasi Transaksi Pembelian Mobil</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="lead text-center mb-4">Mohon periksa kembali detail transaksi pembelian berikut sebelum menyimpan:</p>
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
                                Status Pembayaran: <span id="modal_status_pembayaran_display" class="text-muted"></span>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold text-dark">Detail Penjual:</h6>
                        <ul class="list-group list-group-flush border rounded-3 p-2">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Nama: <span id="modal_penjual_nama_email" class="fw-bold"></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Telepon: <span id="modal_penjual_telepon" class="text-muted"></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Alamat: <span id="modal_penjual_alamat" class="text-muted"></span>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-12 mt-3">
                        <h6 class="fw-bold text-dark">Detail Mobil Yang Dibeli:</h6>
                        <ul class="list-group list-group-flush border rounded-3 p-2">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                No. Plat: <span id="modal_mobil_nomorpolisi" class="fw-bold"></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Jenis Mobil: <span id="modal_mobil_jenis" class="text-muted"></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Merek: <span id="modal_mobil_merek" class="text-muted"></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Tipe: <span id="modal_mobil_tipe" class="text-muted"></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Tahun: <span id="modal_mobil_tahun" class="text-muted"></span>
                             </li>
                             <li class="list-group-item d-flex justify-content-between align-items-center">
                                Nomor Rangka: <span id="modal_mobil_norangka" class="text-muted"></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Nomor Mesin: <span id="modal_mobil_nomesin" class="text-muted"></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Warna: <span id="modal_mobil_warna" class="text-muted"></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Harga Beli Mobil (Target): <span id="modal_harga_beli_mobil_target" class="fw-bold text-success"></span>
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-12 mt-3">
                        <h6 class="fw-bold text-dark">Detail Pembayaran:</h6>
                        <ul class="list-group list-group-flush border rounded-3 p-2" id="modal_payment_details">
                            {{-- Payment details will be appended here by JS --}}
                        </ul>
                    </div>

                    <div class="col-md-12 mt-3">
                         <div class="d-flex justify-content-center mb-2">
                            <h6 class="fw-bold text-dark mb-0">Bukti Pembayaran:</h6>
                        </div>
                        <div class="text-center border rounded-3 p-2 bg-light-subtle">
                            <span id="modal_bukti_pembayaran_filename" class="text-muted">Tidak ada file diupload.</span>
                        </div>
                    </div>

                    <div class="col-md-12 mt-3">
                        <p class="text-center fw-bold fs-3 text-dark mb-0">Total Pembayaran Terisi:</p>
                        <p class="text-center fw-bold fs-2 text-dark" id="modal_total_payment_filled"></p>
                        <p class="text-center fw-bold fs-3 text-secondary mb-0">Sisa / Kelebihan:</p>
                        <p class="text-center fw-bold fs-2" id="modal_payment_balance_final"></p>
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
    .container-fluid py-4 { padding-top: 2.5rem !important; padding-bottom: 2.5rem !important; }
    .form-label { font-size: 0.9rem; font-weight: 500; color: #555; }
    .form-control-lg, .form-select-lg { padding: 0.75rem 1.25rem; border-radius: 0.75rem !important; box-shadow: 0 2px 5px rgba(0,0,0,0.05); border: 1px solid #dee2e6; }
    .form-control-lg:focus, .form-select-lg:focus { border-color: #86b7fe; box-shadow: 0 0 0 0.25rem rgba(13,110,253,.25), 0 2px 8px rgba(0,0,0,0.1), 0 2px 8px rgba(0,0,0,0.1); }
    .form-control[readonly] {
        background-color: #e9ecef;
        opacity: 0.8;
        border-color: #ced4da;
    }
    .card { border-radius: 1rem !important; overflow: hidden; box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.09) !important; }
    .alert-danger { background-color: #fef2f2; color: #721c24; border: 1px solid #f5c6cb; border-radius: 0.75rem; padding: 1.25rem 1.75rem; }
    .alert-danger .alert-heading { color: #dc3545; font-size: 1.1rem; }
    .alert-danger ul { padding-left: 25px; }
    .alert-danger li { margin-bottom: 5px; }
    .alert-warning { background-color: #fff3cd; color: #664d03; border: 1px solid #ffecb5; border-radius: 0.75rem; padding: 1rem; }
    .alert-success { background-color: #d1e7dd; color: #0f5132; border: 1px solid #badbcc; border-radius: 0.75rem; padding: 1.25rem 1.75rem; }
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
    .info-detail-block {
        margin-top: 0.5rem;
        margin-bottom: 0.5rem;
    }
    .info-detail-block .form-control-plaintext {
        padding-left: 0.75rem;
        padding-right: 0.75rem;
        border-radius: 0.5rem;
        background-color: #f1f3f5;
        border: 1px solid #e2e6ea;
        height: auto;
        min-height: calc(2.8rem + 2px);
    }
    .info-detail-block .form-control-plaintext.fw-bold {
        background-color: #e9ecef;
    }
    .info-total-price-block {
        min-height: calc(2.8rem + 2px);
        height: 100%;
    }
    .info-total-price-block .form-control-plaintext {
        background-color: transparent;
        border: none;
        padding: 0;
        text-align: center;
        width: 100%;
    }
    .modal-header.bg-primary {
        background-color: #0d6efd !important;
    }
    .btn-close-white {
        filter: invert(1) brightness(2);
    }
    .list-group-item {
        font-size: 0.95rem;
        padding: 0.75rem 1rem;
    }
    .list-group-item span {
        font-weight: 500;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Global variables
    window.currentHargaBeli = 0;
    let paymentMethodCounter = 0; // To keep track of dynamic payment rows

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Select2 for static dropdowns
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

        // Function to format number as Indonesian Rupiah
        function formatRupiah(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(amount);
        }

        // Function to remove Rupiah formatting and convert to float
        function parseRupiah(rupiahString) {
            return parseFloat(rupiahString.replace(/[^0-9,-]/g, '').replace(',', '.')) || 0;
        }

        // Function to update total payments filled and balance
        function updateTotalPaymentsAndBalance() {
            let totalPayments = 0;
            $('.payment-amount-input').each(function() {
                totalPayments += parseRupiah($(this).val());
            });

            window.currentTotalPaymentsFilled = totalPayments; // Store in global for modal
            let balance = window.currentHargaBeli - totalPayments;

            $('#total_payment_filled_display').val(formatRupiah(totalPayments));
            $('#total_payment_filled_hidden').val(totalPayments);

            $('#payment_balance_display').val(formatRupiah(balance));
            $('#payment_balance_hidden').val(balance);

            // Update status pembayaran based on balance
            if (balance === 0) {
                $('#transaksi_status_pembayaran').val('Sudah Lunas');
            } else if (totalPayments > 0 && balance > 0) {
                 $('#transaksi_status_pembayaran').val('Menunggu Pelunasan');
            } else { // Ini termasuk kasus totalPayments === 0 dan balance > 0 (belum ada pembayaran sama sekali)
                $('#transaksi_status_pembayaran').val('Menunggu Pembayaran');
            }

            if (totalPayments !== window.currentHargaBeli) {
                $('#payment_balance_warning').slideDown();
                $('#payment_balance_display').removeClass('text-success text-danger').addClass(balance > 0 ? 'text-danger' : 'text-success');
            } else {
                $('#payment_balance_warning').slideUp();
                $('#payment_balance_display').removeClass('text-success text-danger').addClass('text-success');
            }
        }

        // Function to add a new payment method row
        function addPaymentMethodRow(metode = '', jumlah = 0) {
            const container = $('#paymentMethodsContainer');
            const newIndex = paymentMethodCounter++;

            const newRow = `
                <div class="row g-2 mb-3 payment-method-row animate__animated animate__fadeIn" id="payment-method-row-${newIndex}">
                    <div class="col-md-6">
                        <label for="payments_${newIndex}_metode" class="form-label text-muted visually-hidden">Metode Pembayaran</label>
                        <select class="form-control select2 payment-method-select form-select-lg rounded-pill shadow-sm" name="payments[${newIndex}][metode]" id="payments_${newIndex}_metode" style="width: 100%" required>
                            {{-- **REVISI PENTING DI SINI**: Tambahkan selected disabled untuk opsi placeholder --}}
                            <option value="" selected disabled>Pilih Metode</option>
                            <option value="Cash">Cash</option>
                            <option value="Transfer Bank">Transfer Bank</option>
                        </select>
                    </div>
                    <div class="col-md-6 d-flex align-items-center">
                        <label for="payments_${newIndex}_jumlah" class="form-label text-muted visually-hidden">Jumlah Pembayaran</label>
                        <input type="text" class="form-control payment-amount-input form-control-lg rounded-pill shadow-sm me-2" name="payments[${newIndex}][jumlah]" id="payments_${newIndex}_jumlah" value="${formatRupiah(jumlah)}" required placeholder="Jumlah Pembayaran">
                        <button type="button" class="btn btn-danger btn-sm rounded-circle remove-payment-method-btn" style="width: 38px; height: 38px;"><i class="bi bi-x"></i></button>
                    </div>
                </div>
            `;
            container.append(newRow);

            // Initialize Select2 for the new row's dropdown
            $(`#payments_${newIndex}_metode`).select2({
                theme: "bootstrap-5",
                placeholder: 'Pilih Metode',
                allowClear: true
            }).val(metode).trigger('change'); // Set selected value if provided

            // Attach event listeners for the new row's amount input
            $(`#payments_${newIndex}_jumlah`).on('input', function() {
                let value = $(this).val();
                value = value.replace(/[^0-9]/g, '');
                if (value) {
                    $(this).val(formatRupiah(parseInt(value)));
                } else {
                    $(this).val('Rp 0');
                }
                updateTotalPaymentsAndBalance();
            });

            // Show remove button only if there's more than one payment row
            if ($('.payment-method-row').length > 1) {
                $('.remove-payment-method-btn').show();
            } else {
                $('.remove-payment-method-btn').hide();
            }
        }

        // Initial payment row setup: Add one row by default
        addPaymentMethodRow();

        // Event listener for "Add Payment Method" button
        $('#addPaymentMethodBtn').on('click', function() {
            addPaymentMethodRow();
        });

        // Event listener for "Remove Payment Method" button (delegated)
        $('#paymentMethodsContainer').on('click', '.remove-payment-method-btn', function() {
            $(this).closest('.payment-method-row').remove();
            updateTotalPaymentsAndBalance();
            // Hide remove button if only one payment row remains
            if ($('.payment-method-row').length === 1) {
                $('.remove-payment-method-btn').hide();
            }
        });


        // Event listener for Harga Beli Mobil total input
        $('#harga_beli_mobil_total').on('input', function() {
            let value = $(this).val();
            value = value.replace(/[^0-9]/g, '');
            if (value) {
                $(this).val(formatRupiah(parseInt(value)));
            } else {
                $(this).val('Rp 0');
            }
            window.currentHargaBeli = parseRupiah($(this).val());
            // Input utama sekarang bernama harga_beli_mobil_final, jadi tidak perlu hidden input terpisah
            // $('#harga_beli_mobil_final_hidden').val(window.currentHargaBeli); // Baris ini dihapus
            $('#final_total_purchase_display').val(formatRupiah(window.currentHargaBeli)); // Update final display
            updateTotalPaymentsAndBalance();
        });

        // Auto-fill Informasi Mobil when a car is selected
        $('#mobil_id').on('change', function() {
            var selectedOption = $(this).find(':selected');
            var nomorPolisi = selectedOption.data('nomorpolisi') || '';
            var jenisMobil = selectedOption.data('jenis') || '';
            var merekMobil = selectedOption.data('merek') || '';
            var tipeMobil = selectedOption.data('tipe') || '';
            var tahun = selectedOption.data('tahun') || '';
            var nomorRangka = selectedOption.data('norangka') || '';
            var nomorMesin = selectedOption.data('nomesin') || '';
            var warnaMobil = selectedOption.data('warnamobil') || '';
            var hargaMobil = selectedOption.data('harga') || 0;

            $('#mobil_detail_nomorpolisi').val(nomorPolisi);
            $('#mobil_detail_jenis').val(jenisMobil);
            $('#mobil_detail_merek').val(merekMobil);
            $('#mobil_detail_tipe').val(tipeMobil);
            $('#mobil_detail_tahun').val(tahun);
            $('#mobil_detail_norangka').val(nomorRangka);
            $('#mobil_detail_nomesin').val(nomorMesin);
            $('#mobil_detail_warna').val(warnaMobil);

            $('#harga_beli_mobil_total').val(formatRupiah(hargaMobil));
            window.currentHargaBeli = hargaMobil; // Set current harga beli
            // Input utama sekarang bernama harga_beli_mobil_final
            // $('#harga_beli_mobil_final_hidden').val(window.currentHargaBeli); // Baris ini dihapus
            $('#final_total_purchase_display').val(formatRupiah(window.currentHargaBeli));
            updateTotalPaymentsAndBalance(); // Recalculate based on new harga beli
        }).trigger('change'); // Trigger on load for initial data

        // Auto-fill Informasi Penjual when a seller is selected
        $('#penjual_id').on('change', function() {
            var selectedOption = $(this).find(':selected');
            var namaPenjual = selectedOption.data('nama') || '';
            var emailPenjual = selectedOption.data('email') || '';
            var noTeleponPenjual = selectedOption.data('notelepon') || '';
            var alamatPenjual = selectedOption.data('alamat') || '';

            $('#penjual_info_nama_email').val(namaPenjual + (emailPenjual ? ' (' + emailPenjual + ')' : ''));
            $('#penjual_info_telepon').val(noTeleponPenjual || '');
            $('#penjual_info_alamat').val(alamatPenjual || '');
        }).trigger('change'); // Trigger on load for initial data

        // Handle confirmation modal
        $('#confirmSaveBtn').on('click', function() {
            if (!$('#transaksiForm')[0].checkValidity()) {
                $('#transaksiForm')[0].reportValidity();
                return;
            }

            $('#modal_kode_transaksi').text($('#kode_transaksi').val());
            $('#modal_tanggal_transaksi').text($('#tanggal_transaksi').val());
            $('#modal_keterangan').text($('#keterangan').val() || '-');
            $('#modal_status_pembayaran_display').text($('#transaksi_status_pembayaran').val()); // This already updates dynamically

            $('#modal_mobil_nomorpolisi').text($('#mobil_detail_nomorpolisi').val());
            $('#modal_mobil_jenis').text($('#mobil_detail_jenis').val());
            $('#modal_mobil_merek').text($('#mobil_detail_merek').val());
            $('#modal_mobil_tipe').text($('#mobil_detail_tipe').val());
            $('#modal_mobil_tahun').text($('#mobil_detail_tahun').val());
            $('#modal_mobil_norangka').text($('#mobil_detail_norangka').val());
            $('#modal_mobil_nomesin').text($('#mobil_detail_nomesin').val());
            $('#modal_mobil_warna').text($('#mobil_detail_warna').val());
            $('#modal_harga_beli_mobil_target').text(formatRupiah(window.currentHargaBeli));

            var selectedPenjualOption = $('#penjual_id').find(':selected');
            var modalNamaPenjual = selectedPenjualOption.data('nama') || '';
            var modalEmailPenjual = selectedPenjualOption.data('email') || '';
            $('#modal_penjual_nama_email').text(modalNamaPenjual + (modalEmailPenjual ? ' (' + modalEmailPenjual + ')' : ''));
            $('#modal_penjual_telepon').text(selectedPenjualOption.data('notelepon') || '');
            $('#modal_penjual_alamat').text(selectedPenjualOption.data('alamat') || '');

            // Populate payment details in modal
            $('#modal_payment_details').empty(); // Clear previous entries
            $('.payment-method-row').each(function(index) {
                const metode = $(this).find('.payment-method-select option:selected').text();
                const jumlah = $(this).find('.payment-amount-input').val();
                if (metode && parseRupiah(jumlah) > 0) {
                    $('#modal_payment_details').append(`
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            ${metode}: <span class="fw-bold">${jumlah}</span>
                        </li>
                    `);
                }
            });

            // Populate Bukti Pembayaran file name
            const buktiPembayaranFile = $('#bukti_pembayaran_file')[0].files[0];
            if (buktiPembayaranFile) {
                $('#modal_bukti_pembayaran_filename').text(buktiPembayaranFile.name);
            } else {
                $('#modal_bukti_pembayaran_filename').text('Tidak ada file diupload.');
            }


            $('#modal_total_payment_filled').text(formatRupiah(window.currentTotalPaymentsFilled));
            const finalBalance = window.currentHargaBeli - window.currentTotalPaymentsFilled;
            $('#modal_payment_balance_final').text(formatRupiah(finalBalance));
            if (finalBalance === 0) {
                $('#modal_payment_balance_final').removeClass('text-danger text-success').addClass('text-success');
            } else {
                $('#modal_payment_balance_final').removeClass('text-danger text-success').addClass('text-danger');
            }

            var confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
            confirmationModal.show();
        });

        $('#confirmSubmitBtn').on('click', function() {
            $('#transaksiForm').submit();
        });

        // Ensure initial calculation runs
        updateTotalPaymentsAndBalance();
    });
</script>
@endsection
