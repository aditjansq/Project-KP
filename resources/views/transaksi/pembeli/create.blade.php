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
            <h4 class="text-dark fw-bold mb-0">Tambah Transaksi Penjualan Mobil Baru</h4>
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

                    <div class="col-md-6">
                        <label for="kode_transaksi" class="form-label text-muted">Kode Transaksi</label>
                        <input type="text" class="form-control form-control-lg bg-light rounded-pill shadow-sm" name="kode_transaksi" id="kode_transaksi" value="{{ $kode_transaksi }}" readonly />
                    </div>


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


                        <div class="mt-4"> {{-- Tambahkan margin-top untuk pemisah --}}
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
                                <div>
                                    <label class="form-label small text-secondary mb-0">Harga Mobil</label>
                                    <input type="text" class="form-control form-control-plaintext fw-bold text-success" id="mobil_detail_harga_display" readonly />
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Bagian Pembeli (Kolom Kanan) --}}
                    <div class="col-md-6">

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
                                    {{ $pembeli->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('pembeli_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror


                        <div class="mt-4"> {{-- Tambahkan margin-top untuk pemisah --}}
                            <label class="form-label text-muted">Informasi Pembeli</label>
                            <div class="p-3 bg-light rounded-4 shadow-sm border border-secondary-subtle info-detail-block">
                                <div class="mb-1">
                                    <label class="form-label small text-secondary mb-0">Nama & Email</label>
                                    <input type="text" class="form-control form-control-plaintext fw-bold" id="pembeli_info_nama_email" readonly />
                                </div>
                                <div class="mb-1">
                                    <label class="form-label small text-secondary mb-0">Nomor Telepon</label>
                                    <input type="text" class="form-control form-control-plaintext" id="pembeli_info_telepon" readonly />
                                </div>
                                <div>
                                    <label class="form-label small text-secondary mb-0">Alamat</label>
                                    <input type="text" class="form-control form-control-plaintext" id="pembeli_info_alamat" readonly />
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-6">
                        <label for="metode_pembayaran" class="form-label text-muted">Pilih Metode Pembayaran</label>
                        <select class="form-control select2 form-select-lg rounded-pill shadow-sm @error('metode_pembayaran') is-invalid @enderror" id="metode_pembayaran" name="metode_pembayaran" style="width: 100%" required>
                            <option value="">Pilih Metode</option>
                            <option value="Transfer Bank" {{ old('metode_pembayaran') == 'Transfer Bank' ? 'selected' : '' }}>Transfer Bank</option>
                            <option value="Cash" {{ old('metode_pembayaran') == 'Cash' ? 'selected' : '' }}>Cash</option>
                            <option value="Kredit" {{ old('metode_pembayaran') == 'Kredit' ? 'selected' : '' }}>Kredit</option>
                        </select>
                        @error('metode_pembayaran')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                                        {{-- NEW: Tempo Angsuran (Hidden by default) --}}
                    <div class="col-md-6" id="tempoAngsuranGroup" style="display: none;">
                        <label for="tempo_angsuran" class="form-label text-muted">Tempo Angsuran (Tahun)</label>
                        <select class="form-select form-select-lg rounded-pill shadow-sm @error('tempo_angsuran') is-invalid @enderror" id="tempo_angsuran" name="tempo_angsuran">
                            <option value="">-- Pilih Tempo Angsuran --</option>
                            @for ($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" {{ old('tempo_angsuran') == $i ? 'selected' : '' }}>{{ $i }} Tahun</option>
                            @endfor
                        </select>
                        @error('tempo_angsuran')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="col-md-12 mt-4">
                        <h5 class="mb-4 fw-bold text-dark border-bottom pb-2">Riwayat Servis Mobil</h5>
                        <div id="riwayat_servis_container">
                            <p class="text-muted text-center">Pilih mobil untuk melihat riwayat servis.</p>
                            {{-- Riwayat servis akan dimuat di sini melalui AJAX --}}
                        </div>
                    </div>

                    {{-- Input for Total Biaya Servis (Modal) --}}
                    <div class="col-md-6">
                        <label class="form-label text-muted">Total Biaya Servis (Modal)</label>
                        <div class="p-3 bg-light rounded-4 shadow-sm border border-info d-flex align-items-center info-total-price-block w-100">
                            <input type="hidden" name="modal" id="modal_hidden" value="{{ old('modal', 0) }}">
                            <input type="text" class="form-control-plaintext text-center fw-bold fs-4 text-info" id="modal_display" value="Rp 0" readonly /> {{-- Changed text-primary to text-info --}}
                        </div>
                    </div>
                    {{-- Input for Total Harga (Harga Mobil) --}}
                    <div class="col-md-6">
                        <label class="form-label text-muted">Harga Mobil</label>
                        <div class="p-3 bg-light rounded-4 shadow-sm border border-primary d-flex align-items-center info-total-price-block w-100">
                            <input type="hidden" name="total_harga" id="total_harga_hidden" value="{{ old('total_harga', 0) }}">
                            <input type="text" class="form-control-plaintext text-center fw-bold fs-4 text-primary" id="total_harga_display" value="Rp 0" readonly />
                        </div>
                    </div>


                    {{-- PERBAIKAN: Pindahkan mt-4 ke sini, di div pembungkus label dan textarea Keterangan --}}
                    <div class="col-md-6 mt-5"> {{-- Changed mt-4 to mt-5 --}}
                        <label for="keterangan" class="form-label text-muted">Keterangan (Opsional)</label>
                        <textarea class="form-control form-control-lg rounded-3 shadow-sm @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="3" placeholder="Masukkan detail transaksi tambahan...">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Hidden input for status_pembayaran --}}
                    <input type="hidden" name="status_pembayaran" id="transaksi_status_pembayaran" value="Menunggu Pembayaran">

                    {{-- NEW: Hidden input to store the selected servis_id (latest one, if multiple) --}}
                    <input type="hidden" name="servis_id" id="servis_id_hidden" value="">

                </div>


                <div class="mt-5 d-flex justify-content-end gap-3">
                    <button type="button" class="btn btn-success btn-lg px-4 rounded-pill shadow-sm animate__animated animate__pulse animate__infinite" id="confirmSaveBtn">
                        <i class="bi bi-save me-2"></i> Simpan Transaksi Pembeli
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

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
                            {{-- NEW: Tempo Angsuran in Modal --}}
                            <li class="list-group-item d-flex justify-content-between align-items-center" id="modal_tempo_angsuran_list_item" style="display: none;">
                                Tempo Angsuran: <span id="modal_tempo_angsuran" class="text-muted"></span>
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
                                Harga Mobil: <span id="modal_mobil_harga_display" class="fw-bold text-success"></span>
                            </li>
                        </ul>
                    </div>

                    {{-- NEW: Riwayat Servis Mobil in Modal --}}
                    <div class="col-md-12 mt-3">
                        <h6 class="fw-bold text-dark">Riwayat Servis Mobil:</h6>
                        <div id="modal_riwayat_servis_container">
                            {{-- Service history content will be copied here by JavaScript --}}
                        </div>
                    </div>

                    <div class="col-md-12 mt-3">
                        <p class="text-center fw-bold fs-3 text-info mb-0">Total Biaya Servis (Modal):</p>
                        <p class="text-center fw-bold fs-2 text-info" id="modal_servis_total_harga"></p>
                    </div>

                    <div class="col-md-12 mt-3">
                        <p class="text-center fw-bold fs-3 text-success mb-0">Total Harga Transaksi:</p>
                        <p class="text-center fw-bold fs-2 text-success" id="modal_final_transaksi_total"></p>
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
    /* Style for Riwayat Servis sections */
    .riwayat-servis-card {
        border: 1px solid #e0e0e0;
        border-radius: 0.75rem;
        margin-bottom: 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .riwayat-servis-card .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e0e0e0;
        font-weight: bold;
        padding: 0.75rem 1.25rem;
        border-top-left-radius: 0.75rem;
        border-top-right-radius: 0.75rem;
    }
    .riwayat-servis-card .card-body {
        padding: 1.25rem;
    }
    .riwayat-servis-card .list-group-item {
        border: none;
        padding-top: 0.25rem;
        padding-bottom: 0.25rem;
    }
    .riwayat-servis-card .item-list {
        margin-top: 1rem;
        border-top: 1px dashed #e9ecef;
        padding-top: 0.75rem;
    }
    .riwayat-servis-card .item-list ul {
        list-style: none;
        padding-left: 0;
        margin-bottom: 0;
    }
    .riwayat-servis-card .item-list li {
        margin-bottom: 0.25rem;
        font-size: 0.875rem;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Global variable to store total service cost
    window.currentServiceTotal = 0;
    window.currentCarPrice = 0; // NEW: Global variable to store car price
    window.currentServiceId = null; // Global variable to store the ID of the latest service

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

        // Function to update car price display and hidden input
        function updateCarPriceDisplay() {
            var selectedOption = $('#mobil_id').find(':selected');
            var carPriceData = selectedOption.data('harga'); // Get the data-harga attribute value
            window.currentCarPrice = parseFloat(carPriceData) || 0; // Parse it to float, default to 0 if invalid

            console.log('--- Debugging Harga Mobil Display ---');
            console.log('Selected option data-harga:', carPriceData);
            console.log('Parsed window.currentCarPrice:', window.currentCarPrice);
            console.log('Formatted Harga Mobil:', formatRupiah(window.currentCarPrice));

            $('#mobil_detail_harga_display').val(formatRupiah(window.currentCarPrice));
            $('#total_harga_hidden').val(window.currentCarPrice); // total_harga in DB is car price
            $('#total_harga_display').val(formatRupiah(window.currentCarPrice)); // <--- THIS IS THE LINE THAT UPDATES THE DISPLAY
            console.log('Value set to #total_harga_display:', $('#total_harga_display').val());
            console.log('-----------------------------------');
        }

        // Function to update service modal display and hidden input
        function updateServiceModalDisplay() {
            $('#modal_display').val(formatRupiah(window.currentServiceTotal));
            $('#modal_hidden').val(window.currentServiceTotal); // modal in DB is service total
            $('#servis_id_hidden').val(window.currentServiceId); // Update hidden servis_id input
        }

        // Function to fetch and display service history
        function fetchServiceHistory(mobilId) {
            var $container = $('#riwayat_servis_container');
            $container.html('<p class="text-muted text-center"><i class="bi bi-arrow-clockwise fa-spin me-2"></i> Memuat riwayat servis...</p>');

            window.currentServiceTotal = 0; // Reset service total before fetching
            window.currentServiceId = null; // Reset service ID before fetching

            if (!mobilId) {
                $container.html('<p class="text-muted text-center">Pilih mobil untuk melihat riwayat servis.</p>');
                updateServiceModalDisplay(); // Update modal display if no car selected (service total is 0)
                return;
            }

            // Replace with your actual API endpoint for fetching service history
            var apiUrl = `/api/mobil/${mobilId}/servis-history`; // Example endpoint

            $.ajax({
                url: apiUrl,
                method: 'GET',
                success: function(response) {
                    if (response.length > 0) {
                        var historyHtml = '';
                        var totalSumOfServices = 0; // Initialize sum for all services

                        // Loop through all services to sum their total_harga
                        response.forEach(function(servis) {
                            totalSumOfServices += parseFloat(servis.total_harga) || 0;
                            // Capture the ID of the latest service (assuming response is sorted by date desc)
                            // This logic assumes the API returns services sorted by date in descending order.
                            if (window.currentServiceId === null) { // Only set the first one encountered (latest)
                                window.currentServiceId = servis.id;
                            }

                            historyHtml += `
                                <div class="card riwayat-servis-card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        Servis #${servis.kode_servis}
                                        <span class="badge bg-${servis.status === 'selesai' ? 'success' : 'warning'}">${servis.status.toUpperCase()}</span>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                Tanggal: <span>${servis.tanggal_servis}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                Metode Pembayaran: <span>${servis.metode_pembayaran}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                Total Harga Servis: <span class="fw-bold">${formatRupiah(servis.total_harga)}</span>
                                            </li>
                                        </ul>
                                        `;
                            if (servis.items && servis.items.length > 0) {
                                historyHtml += `
                                        <div class="item-list mt-3">
                                            <p class="fw-bold mb-1">Rincian Item:</p>
                                            <ul class="list-group list-group-flush">
                                `;
                                servis.items.forEach(function(item) {
                                    historyHtml += `
                                                <li class="list-group-item ps-3">
                                                    ${item.item_name} (${item.item_qty}x) - ${formatRupiah(item.item_price)}
                                                    ${item.item_discount > 0 ? ` (Diskon ${item.item_discount}%)` : ''}
                                                    <span class="float-end fw-bold">${formatRupiah(item.item_total)}</span>
                                                </li>
                                    `;
                                });
                                historyHtml += `
                                            </ul>
                                        </div>
                                `;
                            } else {
                                historyHtml += `<p class="text-muted mt-3 mb-0">Tidak ada item servis terdaftar.</p>`;
                            }
                            historyHtml += `
                                    </div>
                                </div>
                            `;
                        });
                        window.currentServiceTotal = totalSumOfServices; // Set global total to the sum
                        $container.html(historyHtml);
                    } else {
                        $container.html('<p class="text-muted text-center">Tidak ada riwayat servis untuk mobil ini.</p>');
                    }
                    updateServiceModalDisplay(); // Call update service modal display after service history is loaded and total calculated
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching service history:", error);
                    $container.html('<p class="text-danger text-center"><i class="bi bi-exclamation-triangle me-2"></i> Gagal memuat riwayat servis. Silakan coba lagi.</p>');
                    updateServiceModalDisplay(); // Ensure modal is updated even on error (service total remains 0)
                }
            });
        }

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
            var mobilId = selectedOption.val(); // Get the selected mobil_id

            // Populate individual detail fields for Mobil
            $('#mobil_detail_nomorpolisi').val(nomorPolisi);
            $('#mobil_detail_jenis').val(jenisMobil);
            $('#mobil_detail_merek').val(merekMobil);
            $('#mobil_detail_tipe').val(tipeMobil);
            $('#mobil_detail_tahun').val(tahun);
            $('#mobil_detail_norangka').val(nomorRangka);
            $('#mobil_detail_nomesin').val(nomorMesin);
            $('#mobil_detail_warna').val(warnaMobil);

            updateCarPriceDisplay(); // Update car price display and hidden input
            fetchServiceHistory(mobilId);

        }).trigger('change'); // Trigger change on load to populate initial data

        // Auto-fill Informasi Pembeli when a buyer is selected
        $('#pembeli_id').on('change', function() {
            var selectedOption = $(this).find(':selected');
            var namaPembeli = selectedOption.data('nama') || '';
            var emailPembeli = selectedOption.data('email') || '';
            var noTeleponPembeli = selectedOption.data('notelepon') || '';
            var alamatPembeli = selectedOption.data('alamat') || '';

            // Populate individual detail fields for Pembeli
            $('#pembeli_info_nama_email').val(namaPembeli);
            $('#pembeli_info_telepon').val(noTeleponPembeli || '');
            $('#pembeli_info_alamat').val(alamatPembeli || '');

        }).trigger('change'); // Trigger change on load to populate initial data

        // Logic for showing/hiding Tempo Angsuran based on Metode Pembayaran
        $('#metode_pembayaran').on('change', function() {
            if ($(this).val() === 'Kredit') {
                $('#tempoAngsuranGroup').slideDown(); // Show with animation
                $('#tempo_angsuran').prop('required', true); // Make required
            } else {
                $('#tempoAngsuranGroup').slideUp(); // Hide with animation
                $('#tempo_angsuran').prop('required', false); // Not required
                $('#tempo_angsuran').val(''); // Clear selected value
            }
        }).trigger('change'); // Trigger on load to set initial state


        // Handle confirmation modal
        $('#confirmSaveBtn').on('click', function() {
            // Validate form before opening modal
            if (!$('#transaksiForm')[0].checkValidity()) {
                $('#transaksiForm')[0].reportValidity();
                return;
            }

            // Populate modal with current form data
            $('#modal_kode_transaksi').text($('#kode_transaksi').val());
            $('#modal_tanggal_transaksi').text($('#tanggal_transaksi').val());
            $('#modal_metode_pembayaran').text($('#metode_pembayaran option:selected').text());
            $('#modal_keterangan').text($('#keterangan').val() || '-');
            $('#modal_status_pembayaran_display').text($('#transaksi_status_pembayaran').val());

            // Populate Tempo Angsuran in modal
            var selectedMetode = $('#metode_pembayaran').val();
            var tempoAngsuranVal = $('#tempo_angsuran').val();
            if (selectedMetode === 'Kredit' && tempoAngsuranVal) {
                $('#modal_tempo_angsuran').text(tempoAngsuranVal + ' Tahun');
                $('#modal_tempo_angsuran_list_item').show(); // Show the list item
            } else {
                $('#modal_tempo_angsuran_list_item').hide(); // Hide the list item
                $('#modal_tempo_angsuran').text(''); // Clear content
            }

            // Mobil details for modal
            $('#modal_mobil_nomorpolisi').text($('#mobil_detail_nomorpolisi').val());
            $('#modal_mobil_jenis').text($('#mobil_detail_jenis').val());
            $('#modal_mobil_merek').text($('#mobil_detail_merek').val());
            $('#modal_mobil_tipe').text($('#mobil_detail_tipe').val());
            $('#modal_mobil_tahun').text($('#mobil_detail_tahun').val());
            $('#modal_mobil_norangka').text($('#mobil_detail_norangka').val());
            $('#modal_mobil_nomesin').text($('#mobil_detail_nomesin').val());
            $('#modal_mobil_warna').text($('#mobil_detail_warna').val());
            $('#modal_mobil_harga_display').text(formatRupiah(window.currentCarPrice)); // Display actual car price

            // Pembeli details for modal (using data attributes for full info)
            var selectedPembeliOption = $('#pembeli_id').find(':selected');
            var modalNamaPembeli = selectedPembeliOption.data('nama') || '';
            var modalEmailPembeli = selectedPembeliOption.data('email') || '';
            $('#modal_pembeli_nama_email').text(modalNamaPembeli + (modalEmailPembeli ? ' (' + modalEmailPembeli + ')' : ''));
            $('#modal_pembeli_telepon').text(selectedPembeliOption.data('notelepon') || '');
            $('#modal_pembeli_alamat').text(selectedPembeliOption.data('alamat') || '');

            // Copy service history content to modal
            $('#modal_riwayat_servis_container').html($('#riwayat_servis_container').html());

            // Populate service total and final transaction total in modal
            $('#modal_servis_total_harga').text(formatRupiah(window.currentServiceTotal));
            var finalTransactionTotal = window.currentCarPrice + window.currentServiceTotal;
            $('#modal_final_transaksi_total').text(formatRupiah(finalTransactionTotal));


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
