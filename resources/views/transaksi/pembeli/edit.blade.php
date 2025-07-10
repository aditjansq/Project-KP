@extends('layouts.app')

@section('title', 'Edit Transaksi Pembeli: ' . $transaksi->kode_transaksi)

@section('content')
<head>
    {{-- Select2 CSS untuk dropdown yang lebih baik --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    {{-- Animate.css untuk animasi halus --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    {{-- Bootstrap Icons untuk ikon PDF --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<div class="container-fluid py-4 px-3 px-md-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8 mb-3 mb-md-0">
            <h4 class="text-dark fw-bold mb-0">Edit Transaksi Pembeli: {{ $transaksi->kode_transaksi }}</h4>
            <small class="text-secondary">Ubah detail transaksi untuk pembeli ini.</small>
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
            <form id="transaksiEditForm" method="POST" action="{{ route('transaksi.pembeli.update', $transaksi->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                {{-- Bagian Informasi Transaksi Utama --}}
                <h5 class="mb-4 fw-bold text-dark border-bottom pb-2">Informasi Transaksi</h5>
                <div class="p-4 mb-4 bg-read-only rounded-4 shadow-sm border border-primary-subtle">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="kode_transaksi" class="form-label text-muted">Kode Transaksi</label>
                            <input type="text" class="form-control form-control-lg bg-light rounded-pill shadow-sm" name="kode_transaksi" id="kode_transaksi" value="{{ old('kode_transaksi', $transaksi->kode_transaksi) }}" readonly />
                        </div>

                        <div class="col-md-6">
                            @php
                                $today = date('Y-m-d');
                                $threeDaysAgo = date('Y-m-d', strtotime('-3 days'));
                            @endphp
                            <label for="tanggal_transaksi" class="form-label text-muted">Tanggal Transaksi</label>
                            <input type="date" class="form-control form-control-lg bg-light rounded-pill shadow-sm @error('tanggal_transaksi') is-invalid @enderror" id="tanggal_transaksi" value="{{ old('tanggal_transaksi', \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('Y-m-d')) }}" min="{{ $threeDaysAgo }}" max="{{ $today }}" readonly>
                            <input type="hidden" name="tanggal_transaksi" value="{{ old('tanggal_transaksi', \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('Y-m-d')) }}">
                            @error('tanggal_transaksi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="jam_transaksi" class="form-label text-muted">Jam Transaksi (Waktu Dibuat)</label>
                            <input type="text" class="form-control form-control-lg bg-light rounded-pill shadow-sm" id="jam_transaksi" value="{{ \Carbon\Carbon::parse($transaksi->created_at)->format('H:i:s') }}" readonly>
                        </div>

                        <div class="col-md-6">
                            <label for="metode_pembayaran_display" class="form-label text-muted">Metode Pembayaran</label>
                            <input type="text" class="form-control form-control-lg bg-light rounded-pill shadow-sm" id="metode_pembayaran_display" value="{{ old('metode_pembayaran', $transaksi->metode_pembayaran) }}" readonly>
                            <input type="hidden" name="metode_pembayaran" value="{{ old('metode_pembayaran', $transaksi->metode_pembayaran) }}">
                        </div>

                        <div class="col-md-6">
                            <label for="keterangan" class="form-label text-muted">Keterangan (Opsional)</label>
                            <textarea class="form-control form-control-lg rounded-3 shadow-sm @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="3" placeholder="Masukkan detail transaksi tambahan..." readonly>{{ old('keterangan', $transaksi->keterangan) }}</textarea>
                            <input type="hidden" name="keterangan" value="{{ old('keterangan', $transaksi->keterangan) }}">
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div> {{-- End of Informasi Transaksi wrapper div --}}

                <div class="col-md-12 mb-4 mt-4">
                    <label class="form-label text-muted" id="nominal_label">Nominal yang harus dibayar</label>
                    {{-- Default classes for "not Lunas" state --}}
                    <div class="p-3 bg-light rounded-4 shadow-sm d-flex align-items-center info-total-price-block w-100 border border-success animate-glow-green justify-content-center" id="total_harga_block">
                        {{-- Icon for "Lunas" state, initially hidden --}}
                        <i class="bi bi-check-circle-fill me-2 fs-3 text-success" id="lunas_icon" style="display: none;"></i>
                        <input type="hidden" name="total_harga" id="total_harga_hidden" value="{{ old('total_harga', $transaksi->total_harga) }}">
                        <input type="text" class="form-control-plaintext text-center fw-bold fs-3 text-success animate-blink-text" id="total_harga_display" value="Rp 0" readonly />
                    </div>
                </div>

                {{-- Bagian Update Pembayaran (Fokus Utama untuk Edit) --}}
                <h5 class="mb-4 fw-bold text-dark border-bottom pb-2 mt-5">Update Status & Bukti Pembayaran</h5>
                <div class="p-4 mb-4 bg-payment-update-active rounded-4 shadow-sm border border-info-subtle animate-glow-blue">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="status_pembayaran" class="form-label text-muted">Status Pembayaran</label>
                            <select class="form-control select2 form-select-lg rounded-pill shadow-sm @error('status_pembayaran') is-invalid @enderror" id="status_pembayaran" name="status_pembayaran" style="width: 100%" required>
                                <option value="">Pilih Status Pembayaran</option>
                                <option value="Lunas" {{ old('status_pembayaran', $transaksi->status_pembayaran) == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                                <option value="Belum Lunas" {{ old('status_pembayaran', $transaksi->status_pembayaran) == 'Belum Lunas' ? 'selected' : '' }}>Belum Lunas</option>
                                <option value="Menunggu Pembayaran" {{ old('status_pembayaran', $transaksi->status_pembayaran) == 'Menunggu Pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                                <option value="Dibatalkan" {{ old('status_pembayaran', $transaksi->status_pembayaran) == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                            @error('status_pembayaran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Input DP --}}
                        <div class="col-md-6" id="dp_amount_group" style="display: none;">
                            <label for="dp_amount" class="form-label text-muted">Jumlah DP</label>
                            <input type="number" class="form-control form-control-lg rounded-pill shadow-sm @error('dp_jumlah') is-invalid @enderror" id="dp_amount" name="dp_jumlah" value="{{ old('dp_jumlah', $transaksi->dp_jumlah ?? '') }}" placeholder="Masukkan jumlah DP" min="0">
                            @error('dp_jumlah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="bukti_pembayaran" class="form-label text-muted">Unggah Bukti Pembayaran (Opsional)</label>
                            <input type="file" class="form-control form-control-lg rounded-pill shadow-sm @error('bukti_pembayaran') is-invalid @enderror" id="bukti_pembayaran" name="bukti_pembayaran" accept="image/*,application/pdf">
                            @error('bukti_pembayaran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            {{-- Area Pratinjau File --}}
                            <div id="bukti_pembayaran_preview_area" class="mt-2 p-2 border rounded-3 bg-light text-center">
                                <p class="text-muted mb-0" id="bukti_pembayaran_placeholder_text" style="{{ $transaksi->bukti_pembayaran ? 'display: none;' : 'display: block;' }}">Tidak ada pratinjau file.</p>

                                <div id="existing_preview_container" style="{{ $transaksi->bukti_pembayaran ? 'display: block;' : 'display: none;' }}">
                                    @if ($transaksi->bukti_pembayaran)
                                        @php
                                            $extension = pathinfo($transaksi->bukti_pembayaran, PATHINFO_EXTENSION);
                                            $fileUrl = asset('storage/' . $transaksi->bukti_pembayaran);
                                            $fileName = basename($transaksi->bukti_pembayaran);
                                        @endphp
                                        @if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']))
                                            <img id="bukti_pembayaran_preview_img_existing" src="{{ $fileUrl }}" alt="Pratinjau Bukti Pembayaran" class="img-fluid rounded" style="max-height: 150px;" onerror="this.onerror=null;this.src='https://placehold.co/150x150/e9ecef/6c757d?text=Gambar+Tidak+Ditemukan';">
                                            <p class="mt-2 mb-0 text-muted small" id="bukti_pembayaran_file_info_existing">File saat ini: <a href="{{ $fileUrl }}" target="_blank" download="{{ $fileName }}" class="text-decoration-none fw-bold">{{ $fileName }}</a> (Klik untuk melihat/mengunduh)</p>
                                        @elseif (strtolower($extension) == 'pdf')
                                            <i class="bi bi-file-earmark-pdf-fill text-danger" style="font-size: 3rem;"></i>
                                            <p class="mt-2 mb-0 text-muted small">File saat ini: <a href="{{ $fileUrl }}" target="_blank" download="{{ $fileName }}" class="text-decoration-none fw-bold">{{ $fileName }}</a> (Klik untuk melihat/mengunduh)</p>
                                        @else
                                            <i class="bi bi-file-earmark text-secondary" style="font-size: 3rem;"></i>
                                            <p class="mt-2 mb-0 text-muted small">File saat ini: <a href="{{ $fileUrl }}" target="_blank" download="{{ $fileName }}" class="text-decoration-none fw-bold">{{ $fileName }}</a> (Klik untuk melihat/mengunduh)</p>
                                        @endif
                                    @endif
                                </div>

                                <img id="bukti_pembayaran_preview_img_new" src="#" alt="Pratinjau Bukti Pembayaran" class="img-fluid rounded" style="max-height: 150px; display: none;" onerror="this.onerror=null;this.src='https://placehold.co/150x150/e9ecef/6c757d?text=Error';">
                                <p class="mt-2 mb-0 text-muted small" id="bukti_pembayaran_file_info_new" style="display: none;"></p>
                                <div id="bukti_pembayaran_pdf_preview_new" style="display: none;">
                                    <i class="bi bi-file-earmark-pdf-fill text-danger" style="font-size: 3rem;"></i>
                                    <p class="mt-2 mb-0 text-muted small" id="bukti_pembayaran_pdf_filename_new"></p>
                                </div>
                                <div id="bukti_pembayaran_generic_preview_new" style="display: none;">
                                    <i class="bi bi-file-earmark text-secondary" style="font-size: 3rem;"></i>
                                    <p class="mt-2 mb-0 text-muted small" id="bukti_pembayaran_generic_filename_new"></p>
                                </div>
                            </div>
                            <input type="hidden" name="existing_bukti_pembayaran" value="{{ $transaksi->bukti_pembayaran }}">
                        </div>
                    </div>
                </div> {{-- End of Update Pembayaran wrapper div --}}

                {{-- Bagian Informasi Pihak Terkait (Non-Editable) --}}
                <h5 class="mb-4 fw-bold text-dark border-bottom pb-2 mt-5">Informasi Pihak Terkait (Non-Editable)</h5>
                <div class="p-4 mb-4 bg-read-only rounded-4 shadow-sm border border-secondary-subtle">
                    <div class="row g-3">
                        {{-- Informasi Mobil (Kolom Kiri - Non-Editable) --}}
                        <div class="col-md-6">
                            <label for="mobil_info_display" class="form-label text-muted">Informasi Mobil</label>
                            <input type="text" class="form-control form-control-lg bg-light rounded-pill shadow-sm" id="mobil_info_display"
                                   value="{{ $transaksi->mobil->tahun_pembuatan ?? '' }} {{ $transaksi->mobil->merek_mobil ?? '' }} {{ $transaksi->mobil->tipe_mobil ?? '' }} - {{ $transaksi->mobil->nomor_polisi ?? '' }}" readonly>
                            <input type="hidden" name="mobil_id" value="{{ old('mobil_id', $transaksi->mobil_id) }}">

                            <div class="mt-4">
                                <label class="form-label text-muted">Detail Mobil</label>
                                <div class="p-3 bg-light rounded-4 shadow-sm border border-secondary-subtle info-detail-block">
                                    <div class="mb-1">
                                        <label class="form-label small text-secondary mb-0">No. Plat</label>
                                        <input type="text" class="form-control form-control-plaintext fw-bold" id="mobil_detail_nomorpolisi" value="{{ $transaksi->mobil->nomor_polisi ?? 'N/A' }}" readonly />
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label small text-secondary mb-0">Jenis Mobil</label>
                                        <input type="text" class="form-control form-control-plaintext" id="mobil_detail_jenis" value="{{ $transaksi->mobil->jenis_mobil ?? 'N/A' }}" readonly />
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label small text-secondary mb-0">Merek</label>
                                        <input type="text" class="form-control form-control-plaintext" id="mobil_detail_merek" value="{{ $transaksi->mobil->merek_mobil ?? 'N/A' }}" readonly />
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label small text-secondary mb-0">Model / Tipe</label>
                                        <input type="text" class="form-control form-control-plaintext" id="mobil_detail_tipe" value="{{ $transaksi->mobil->tipe_mobil ?? 'N/A' }}" readonly />
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label small text-secondary mb-0">Tahun</label>
                                        <input type="text" class="form-control form-control-plaintext" id="mobil_detail_tahun" value="{{ $transaksi->mobil->tahun_pembuatan ?? 'N/A' }}" readonly />
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label small text-secondary mb-0">Nomor Rangka</label>
                                        <input type="text" class="form-control form-control-plaintext" id="mobil_detail_nomorrangka" value="{{ $transaksi->mobil->nomor_rangka ?? 'N/A' }}" readonly />
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label small text-secondary mb-0">Nomor Mesin</label>
                                        <input type="text" class="form-control form-control-plaintext" id="mobil_detail_nomormesin" value="{{ $transaksi->mobil->nomor_mesin ?? 'N/A' }}" readonly />
                                    </div>
                                    <div>
                                        <label class="form-label small text-secondary mb-0">Warna</label>
                                        <input type="text" class="form-control form-control-plaintext" id="mobil_detail_warna" value="{{ $transaksi->mobil->warna ?? 'N/A' }}" readonly />
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Informasi Pembeli (Kolom Kanan - Non-Editable) --}}
                        <div class="col-md-6">
                            <label for="pembeli_info_display" class="form-label text-muted">Informasi Pembeli</label>
                            <input type="text" class="form-control form-control-lg bg-light rounded-pill shadow-sm" id="pembeli_info_display"
                                   value="{{ $transaksi->pembeli->nama ?? '' }} - {{ $transaksi->pembeli->email ?? '' }}" readonly>
                            <input type="hidden" name="pembeli_id" value="{{ old('pembeli_id', $transaksi->pembeli_id) }}">

                            <div class="mt-4">
                                <label class="form-label text-muted">Detail Pembeli</label>
                                <div class="p-3 bg-light rounded-4 shadow-sm border border-secondary-subtle info-detail-block">
                                    <div class="mb-1">
                                        <label class="form-label small text-secondary mb-0">Nama & Email</label>
                                        <input type="text" class="form-control form-control-plaintext fw-bold" id="pembeli_info_nama_email_detail" readonly />
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label small text-secondary mb-0">Nomor Telepon</label>
                                        <input type="text" class="form-control form-control-plaintext" id="pembeli_info_telepon_detail" readonly />
                                    </div>
                                    <div>
                                        <label class="form-label small text-secondary mb-0">Alamat</label>
                                        <input type="text" class="form-control form-control-plaintext" id="pembeli_info_alamat_detail" readonly />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> {{-- End of Informasi Pihak Terkait wrapper div --}}

                <div class="mt-5 d-flex justify-content-end gap-3">
                    <button type="button" class="btn btn-success btn-lg px-4 rounded-pill shadow-sm animate__animated animate__pulse animate__infinite" id="confirmUpdateBtn">
                        <i class="bi bi-save me-2"></i> Update Transaksi Pembeli
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
                <h5 class="modal-title fw-bold" id="confirmationModalLabel">Konfirmasi Update Transaksi Pembelian</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="lead text-center mb-4">Mohon periksa kembali detail transaksi yang akan diubah:</p>
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
                                Jam Transaksi: <span id="modal_jam_transaksi" class="text-muted"></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Metode Pembayaran: <span id="modal_metode_pembayaran" class="text-muted"></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Status Pembayaran: <span id="modal_status_pembayaran" class="text-muted"></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center" id="modal_dp_amount_item" style="display: none;">
                                Jumlah DP: <span id="modal_dp_amount" class="text-muted"></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Bukti Pembayaran: <span id="modal_bukti_pembayaran" class="text-muted"></span>
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
                <button type="button" class="btn btn-primary rounded-pill px-4" id="confirmSubmitBtn">Konfirmasi & Update</button>
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
    .form-control[readonly] { background-color: #e9ecef; opacity: 0.8; border-color: #ced4da; }
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
    /* Select2 specific styles (only apply to elements with select2 class) */
    .select2-container--bootstrap-5 .select2-selection { border-radius: 0.75rem !important; height: calc(2.8rem + 2px); padding: 0.75rem 1.25rem; display: flex; align-items: center; border: 1px solid #dee2e6; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
    .select2-container--bootstrap-5.select2-container--focus .select2-selection, .select2-container--bootstrap-5.select2-container--open .select2-selection { border-color: #86b7fe; box-shadow: 0 0 0 0.25rem rgba(13,110,253,.25), 0 2px 8px rgba(0,0,0,0.1); }
    .select2-container--bootstrap-5 .select2-selection__arrow { height: 100%; display: flex; align-items: center; padding-right: 0.75rem; }
    .select2-container--bootstrap-5 .select2-selection__placeholder { color: #6c757d; line-height: 1.5; }
    .select2-container--bootstrap-5 .select2-selection__rendered { color: #495057; line-height: 1.5; padding-left: 0; }
    .select2-container--bootstrap-5 .select2-dropdown { border-radius: 0.75rem; border: 1px solid #dee2e6; box-shadow: 0 5px 15px rgba(0,0,0,0.1); z-index: 1056; }
    .select2-container--bootstrap-5 .select2-results__option { padding: 0.75rem 1.25rem; font-size: 0.9rem; }
    .select2-container--bootstrap-5 .select2-results__option--highlighted.select2-results__option--selectable { background-color: #0d6efd; color: white; }
    .select2-container--bootstrap-5 .select2-results__option--selected { background-color: #e9ecef; color: #495057; }
    .info-detail-block { margin-top: 0.5rem; margin-bottom: 0.5rem; }
    .info-detail-block .form-control-plaintext { padding: 0.75rem; border-radius: 0.5rem; background-color: #f1f3f5; border: 1px solid #e2e6ea; height: auto; min-height: calc(2.8rem + 2px); }
    .info-detail-block .form-control-plaintext.fw-bold { background-color: #e9ecef; }
    .info-detail-block .form-label.small { font-size: 0.75rem; margin-bottom: 0.2rem; color: #6c757d; }
    .info-total-price-block { min-height: calc(2.8rem + 2px); height: 100%; }
    .info-total-price-block .form-control-plaintext { background-color: transparent; border: none; padding: 0; /* Remove default padding from form-control-plaintext */ text-align: center; width: 100%; } /* Center the text within its available space */
    .modal-header.bg-primary { background-color: #0d6efd !important; }
    .btn-close-white { filter: invert(1) brightness(2); }
    .list-group-item { font-size: 0.95rem; padding: 0.75rem 1rem; }
    .list-group-item span { font-weight: 500; }
    @media (max-width: 768px) { .btn-lg { width: 100%; margin-bottom: 1rem; } .d-flex.justify-content-end.gap-3 { flex-direction: column; gap: 1rem; } }

    /* Keyframes for glowing animation */
    @keyframes glow-green {
        0%, 100% {
            box-shadow: 0 0 0px rgba(40, 167, 69, 0.4), 0 4px 10px rgba(0,0,0,0.05);
        }
        50% {
            box-shadow: 0 0 15px rgba(40, 167, 69, 0.8), 0 4px 20px rgba(0,0,0,0.1);
        }
    }

    @keyframes glow-blue {
        0%, 100% {
            box-shadow: 0 0 0px rgba(23, 162, 184, 0.4), 0 4px 10px rgba(0,0,0,0.05);
        }
        50% {
            box-shadow: 0 0 15px rgba(23, 162, 184, 0.8), 0 4px 20px rgba(0,0,0,0.1);
        }
    }

    .animate-glow-green {
        animation: glow-green 2s infinite alternate;
    }

    .animate-glow-blue {
        animation: glow-blue 2s infinite alternate;
    }

    /* Custom background for read-only sections */
    .bg-read-only {
        background-color: #eff2f5 !important;
    }

    /* Ensure form controls inside bg-read-only also match or are clearly read-only */
    .bg-read-only .form-control[readonly],
    .bg-read-only .form-select[readonly],
    .bg-read-only .form-control.bg-light {
        background-color: #e9ecef !important;
    }

    /* Custom background for the "Update Pembayaran" section */
    .bg-payment-update-active {
        background-color: #ffffff !important;
    }

    /* Keyframes for blinking text animation */
    @keyframes blink-text {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.4; }
    }

    .animate-blink-text {
        animation: blink-text 1.2s infinite alternate;
    }

    /* NEW: Class for "Lunas" total price background with subtle green */
    .bg-paid-green {
        background-color: #d4edda !important; /* A light, subtle green (similar to Bootstrap's alert-success background) */
        border-color: #28a745 !important; /* Keep original success color for border */
        border-width: 2px !important;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi Select2 untuk dropdown status pembayaran
        $('#status_pembayaran').select2({
            theme: "bootstrap-5",
            placeholder: 'Pilih Status Pembayaran',
            allowClear: true
        });

        // Fungsi untuk memformat angka sebagai Rupiah Indonesia
        function formatRupiah(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(amount);
        }

        // Variabel untuk menyimpan total harga asli dari database.
        // Ini penting karena kita akan menghitung ulang total harga berdasarkan DP.
        const originalTotalHarga = parseFloat($('#total_harga_hidden').val()) || 0;

        // Fungsi untuk menghitung dan memperbarui tampilan total harga
        function updateTotalPriceDisplay() {
            let currentTotal = originalTotalHarga;
            const dpAmount = parseFloat($('#dp_amount').val()) || 0;

            const metodePembayaran = $('#metode_pembayaran_display').val();

            // Hanya kurangi total jika metode pembayaran adalah 'Kredit'
            // dan DP dimasukkan.
            if (metodePembayaran === 'Kredit') {
                currentTotal = originalTotalHarga - dpAmount;
                // Pastikan total tidak kurang dari nol jika DP melebihi total harga asli
                if (currentTotal < 0) {
                    currentTotal = 0;
                }
            }

            $('#total_harga_display').val(formatRupiah(currentTotal));
            $('#total_harga_hidden').val(currentTotal); // Perbarui juga nilai di input tersembunyi
        }

        // Fungsi untuk menampilkan/menyembunyikan input DP
        function toggleDpInput() {
            var metodePembayaran = $('#metode_pembayaran_display').val();
            if (metodePembayaran === 'Kredit') {
                $('#dp_amount_group').show();
            } else {
                $('#dp_amount_group').hide();
                $('#dp_amount').val(''); // Kosongkan nilai DP jika disembunyikan
            }
            updateTotalPriceDisplay(); // Hitung ulang total harga saat visibilitas input DP berubah
        }

        // Fungsi untuk mengubah teks label nominal, tampilan total harga, dan ikon berdasarkan status pembayaran
        function updateNominalLabelAndDisplay() {
            const statusPembayaran = $('#status_pembayaran').val();
            const nominalLabel = $('#nominal_label');
            const totalHargaDisplay = $('#total_harga_display');
            const totalHargaBlock = $('#total_harga_block');
            const lunasIcon = $('#lunas_icon'); // Dapatkan elemen ikon

            // Setel ulang semua kelas dinamis terlebih dahulu untuk menghindari konflik
            totalHargaBlock.removeClass('animate-glow-green bg-light border border-success bg-paid-green justify-content-start border-danger');
            totalHargaDisplay.removeClass('animate-blink-text text-success text-dark text-danger text-muted');
            lunasIcon.hide(); // Selalu sembunyikan secara default, lalu tampilkan jika Lunas

            if (statusPembayaran === 'Lunas') {
                nominalLabel.text('Nominal telah dibayar');
                totalHargaDisplay.addClass('text-dark');
                totalHargaBlock.addClass('bg-paid-green justify-content-start');
                lunasIcon.show();
            } else if (statusPembayaran === 'Dibatalkan') {
                nominalLabel.text('Transaksi dibatalkan');
                totalHargaDisplay.addClass('text-danger'); // Teks merah
                totalHargaBlock.addClass('bg-light border border-danger justify-content-center'); // Latar belakang terang, border merah
            } else { // 'Belum Lunas', 'Menunggu Pembayaran'
                nominalLabel.text('Nominal yang harus dibayar');
                totalHargaDisplay.addClass('animate-blink-text text-success'); // Teks hijau berkedip
                totalHargaBlock.addClass('animate-glow-green bg-light border border-success justify-content-center'); // Blok hijau bercahaya
            }
        }

        // Panggil toggleDpInput dan updateNominalLabelAndDisplay saat halaman dimuat
        toggleDpInput();
        updateNominalLabelAndDisplay(); // Panggil saat inisialisasi untuk mengatur label dan tampilan awal

        // Dengarkan perubahan pada dropdown status_pembayaran
        $('#status_pembayaran').on('change', function() {
            updateNominalLabelAndDisplay(); // Panggil saat status berubah
        });

        // Dengarkan perubahan pada input dp_amount
        $('#dp_amount').on('input', function() {
            updateTotalPriceDisplay(); // Panggil saat nilai DP berubah
        });


        // Auto-fill Informasi Mobil saat halaman dimuat (untuk data yang sudah ada)
        (function() {
            var mobilSelectedId = $('input[name="mobil_id"]').val();
            if (mobilSelectedId) {
                // Dalam aplikasi nyata, Anda akan mengambil data ini melalui AJAX
                // Untuk demonstrasi, menggunakan nilai hardcode dari skrip asli
                var selectedMobilData = {
                    nomorpolisi: "{{ $transaksi->mobil->nomor_polisi ?? 'N/A' }}",
                    jenis: "{{ $transaksi->mobil->jenis_mobil ?? 'N/A' }}",
                    merek: "{{ $transaksi->mobil->merek_mobil ?? 'N/A' }}",
                    tipe: "{{ $transaksi->mobil->tipe_mobil ?? 'N/A' }}",
                    tahun: "{{ $transaksi->mobil->tahun_pembuatan ?? 'N/A' }}",
                    nomorrangka: "{{ $transaksi->mobil->nomor_rangka ?? 'N/A' }}",
                    nomormesin: "{{ $transaksi->mobil->nomor_mesin ?? 'N/A' }}",
                    warna: "{{ $transaksi->mobil->warna ?? 'N/A' }}",
                };

                $('#mobil_detail_nomorpolisi').val(selectedMobilData.nomorpolisi);
                $('#mobil_detail_jenis').val(selectedMobilData.jenis);
                $('#mobil_detail_merek').val(selectedMobilData.merek);
                $('#mobil_detail_tipe').val(selectedMobilData.tipe);
                $('#mobil_detail_tahun').val(selectedMobilData.tahun);
                $('#mobil_detail_nomorrangka').val(selectedMobilData.nomorrangka);
                $('#mobil_detail_nomormesin').val(selectedMobilData.nomormesin);
                $('#mobil_detail_warna').val(selectedMobilData.warna);
            }
        })();


        // Auto-fill Informasi Pembeli saat halaman dimuat (untuk data yang sudah ada)
        (function() {
            var pembeliSelectedId = $('input[name="pembeli_id"]').val();
            if (pembeliSelectedId) {
                var selectedPembeliData = {
                    nama: "{{ $transaksi->pembeli->nama ?? '' }}",
                    email: "{{ $transaksi->pembeli->email ?? '' }}",
                    notelepon: "{{ $transaksi->pembeli->no_telepon ?? '' }}",
                    alamat: "{{ $transaksi->pembeli->alamat ?? '' }}"
                };

                $('#pembeli_info_nama_email_detail').val(selectedPembeliData.nama + (selectedPembeliData.email ? ' (' + selectedPembeliData.email + ')' : ''));
                $('#pembeli_info_telepon_detail').val(selectedPembeliData.notelepon || '');
                $('#pembeli_info_alamat_detail').val(selectedPembeliData.alamat ? 'Alamat: ' + selectedPembeliData.alamat : '');
            }
        })();


        // Pembaruan tampilan total harga saat halaman dimuat
        updateTotalPriceDisplay();

        // --- Logika Pratinjau File ---
        const buktiPembayaranInput = document.getElementById('bukti_pembayaran');
        const buktiPembayaranPlaceholderText = document.getElementById('bukti_pembayaran_placeholder_text');
        const existingPreviewContainer = document.getElementById('existing_preview_container');
        const newImgPreview = document.getElementById('bukti_pembayaran_preview_img_new');
        const newFileInfo = document.getElementById('bukti_pembayaran_file_info_new');
        const newPdfPreview = document.getElementById('bukti_pembayaran_pdf_preview_new');
        const newGenericPreview = document.getElementById('bukti_pembayaran_generic_preview_new');
        const existingBuktiPathOnLoad = document.querySelector('input[name="existing_bukti_pembayaran"]').value;

        // Fungsi untuk menyembunyikan semua elemen pratinjau (baru dan yang sudah ada)
        function resetAllPreviews() {
            buktiPembayaranPlaceholderText.style.display = 'none';
            existingPreviewContainer.style.display = 'none';
            newImgPreview.style.display = 'none';
            newImgPreview.src = '#';
            newFileInfo.style.display = 'none';
            newFileInfo.innerHTML = '';
            newPdfPreview.style.display = 'none';
            newPdfPreview.innerHTML = '';
            newGenericPreview.style.display = 'none';
            newGenericPreview.innerHTML = '';
        }

        // Fungsi untuk memperbarui tampilan pratinjau file berdasarkan input
        function updateFilePreview() {
            resetAllPreviews();

            const file = buktiPembayaranInput.files[0];

            if (file) {
                const fileType = file.type;
                const fileName = file.name;
                const fileUrl = URL.createObjectURL(file);

                if (fileType.startsWith('image/')) {
                    newImgPreview.style.display = 'block';
                    newImgPreview.src = fileUrl;
                    newFileInfo.style.display = 'block';
                    newFileInfo.innerHTML = `File baru: <a href="${fileUrl}" target="_blank" download="${fileName}" class="text-decoration-none fw-bold">${fileName}</a> (Klik untuk melihat/mengunduh)`;
                } else if (fileType === 'application/pdf') {
                    newPdfPreview.style.display = 'block';
                    newPdfPreview.innerHTML = `<i class="bi bi-file-earmark-pdf-fill text-danger" style="font-size: 3rem;"></i>
                                                <p class="mt-2 mb-0 text-muted small">File baru: <a href="${fileUrl}" target="_blank" download="${fileName}" class="text-decoration-none fw-bold">${fileName}</a> (Klik untuk melihat/mengunduh)</p>`;
                } else {
                    newGenericPreview.style.display = 'block';
                    newGenericPreview.innerHTML = `<i class="bi bi-file-earmark text-secondary" style="font-size: 3rem;"></i>
                                                    <p class="mt-2 mb-0 text-muted small">File baru: <a href="${fileUrl}" target="_blank" download="${fileName}" class="text-decoration-none fw-bold">${fileName}</a> (Klik untuk melihat/mengunduh)</p>`;
                }
            } else {
                if (existingBuktiPathOnLoad) {
                    existingPreviewContainer.style.display = 'block';
                } else {
                    buktiPembayaranPlaceholderText.style.display = 'block';
                }
            }
        }

        updateFilePreview();
        buktiPembayaranInput.addEventListener('change', updateFilePreview);

        // --- Logika Modal Konfirmasi ---
        $('#confirmUpdateBtn').on('click', function() {
            if (!$('#transaksiEditForm')[0].checkValidity()) {
                $('#transaksiEditForm')[0].reportValidity();
                return;
            }

            // Mengisi modal dengan data form saat ini
            $('#modal_kode_transaksi').text($('#kode_transaksi').val());
            $('#modal_tanggal_transaksi').text(moment($('#tanggal_transaksi').val()).format('DD MMMMYYYY'));
            $('#modal_jam_transaksi').text($('#jam_transaksi').val());
            $('#modal_metode_pembayaran').text($('#metode_pembayaran_display').val());
            $('#modal_total_harga').text($('#total_harga_display').val()); // Gunakan nilai yang sedang ditampilkan (sudah dikurangi DP)
            $('#modal_keterangan').text($('#keterangan').val() || '-');
            $('#modal_status_pembayaran').text($('#status_pembayaran option:selected').text());

            // Menangani DP untuk modal
            var metodePembayaran = $('#metode_pembayaran_display').val();
            // Periksa apakah input DP terlihat dan memiliki nilai
            if (metodePembayaran === 'Kredit' && $('#dp_amount').val()) {
                $('#modal_dp_amount_item').show();
                $('#modal_dp_amount').text(formatRupiah(parseFloat($('#dp_amount').val()) || 0));
            } else {
                $('#modal_dp_amount_item').hide();
                $('#modal_dp_amount').text('');
            }

            // Menangani Bukti Pembayaran untuk modal
            let modalBuktiPembayaranText = 'Tidak ada';
            if (buktiPembayaranInput.files.length > 0) {
                modalBuktiPembayaranText = 'File baru: ' + buktiPembayaranInput.files[0].name;
            } else if (existingBuktiPathOnLoad) {
                modalBuktiPembayaranText = 'File yang ada: ' + basename(existingBuktiPathOnLoad);
            }
            $('#modal_bukti_pembayaran').text(modalBuktiPembayaranText);

            // Detail Mobil untuk modal
            // Pastikan data ini diambil langsung dari nilai input di halaman, bukan placeholder
            $('#modal_mobil_merek_tipe').text($('#mobil_detail_merek').val() + ' ' + $('#mobil_detail_tipe').val());
            $('#modal_mobil_tahun').text($('#mobil_detail_tahun').val());
            $('#modal_mobil_nomorpolisi').text($('#mobil_detail_nomorpolisi').val());
            $('#modal_mobil_warna').text($('#mobil_detail_warna').val());
            $('#modal_mobil_jenis').text($('#mobil_detail_jenis').val());
            $('#modal_mobil_nomorrangka').text($('#mobil_detail_nomorrangka').val());
            $('#modal_mobil_nomormesin').text($('#mobil_detail_nomormesin').val());

            // Detail Pembeli untuk modal
            // Pastikan data ini diambil langsung dari nilai input di halaman, bukan placeholder
            $('#modal_pembeli_nama_email').text($('#pembeli_info_nama_email_detail').val());
            $('#modal_pembeli_telepon').text($('#pembeli_info_telepon_detail').val());
            $('#modal_pembeli_alamat').text($('#pembeli_info_alamat_detail').val());

            var confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
            confirmationModal.show();
        });

        // Menangani pengiriman form setelah konfirmasi
        $('#confirmSubmitBtn').on('click', function() {
            const form = document.getElementById('transaksiEditForm');
            const formData = new FormData(form);

            // Kirim form
            form.submit();
        });
    });

    // Fungsi helper untuk mendapatkan nama file dasar dari path
    function basename(path) {
        return path.split('/').reverse()[0];
    }
</script>
@endsection
