@extends('layouts.app')

@section('title', 'Edit Transaksi Penjual: ' . $transaksi->kode_transaksi)

@section('content')
<head>
    {{-- Select2 CSS for enhanced dropdowns --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    {{-- Animate.css for subtle animations --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    {{-- Bootstrap Icons for icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<div class="container-fluid py-4 px-3 px-md-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8 mb-3 mb-md-0">
            <h4 class="text-dark fw-bold mb-0">Edit Transaksi Penjual: {{ $transaksi->kode_transaksi }}</h4>
            <small class="text-secondary">Ubah detail transaksi untuk penjual ini.</small>
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

    @if ($errors->any())
    <div class="alert alert-danger border-0 shadow-sm rounded-3 animate__animated animate__shakeX" role="alert">
        <h6 class="alert-heading fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i> Terjadi Kesalahan Input:</h6>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
    @endif

    <div class="card border-0 shadow-xl rounded-4 animate__animated animate__fadeInUp">
        <div class="card-body p-lg-5 p-md-4 p-3">
            <form id="transaksiEditForm" action="{{ route('transaksi.penjual.update', $transaksi->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <h5 class="mb-4 fw-bold text-dark border-bottom pb-2">Informasi Transaksi</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="kode_transaksi" class="form-label text-muted">Kode Transaksi</label>
                        <input type="text" class="form-control form-control-lg bg-light rounded-pill shadow-sm" id="kode_transaksi" name="kode_transaksi" value="{{ old('kode_transaksi', $transaksi->kode_transaksi) }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label for="tanggal_transaksi" class="form-label text-muted">Tanggal Transaksi</label>
                        <input type="date" class="form-control form-control-lg rounded-pill shadow-sm @error('tanggal_transaksi') is-invalid @enderror" id="tanggal_transaksi" name="tanggal_transaksi" value="{{ old('tanggal_transaksi', \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('Y-m-d')) }}" required>
                        @error('tanggal_transaksi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="metode_pembayaran" class="form-label text-muted">Metode Pembayaran</label>
                        <select class="form-select form-select-lg rounded-pill shadow-sm @error('metode_pembayaran') is-invalid @enderror" id="metode_pembayaran" name="metode_pembayaran" required>
                            <option value="">Pilih Metode Pembayaran</option>
                            <option value="Cash" {{ old('metode_pembayaran', $transaksi->metode_pembayaran) == 'Cash' ? 'selected' : '' }}>Cash</option>
                            <option value="Transfer Bank" {{ old('metode_pembayaran', $transaksi->metode_pembayaran) == 'Transfer Bank' ? 'selected' : '' }}>Transfer Bank</option>
                            <option value="Kredit" {{ old('metode_pembayaran', $transaksi->metode_pembayaran) == 'Kredit' ? 'selected' : '' }}>Kredit</option>
                        </select>
                        @error('metode_pembayaran')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="status_pembayaran" class="form-label text-muted">Status Pembayaran</label>
                        <select class="form-select form-select-lg rounded-pill shadow-sm @error('status_pembayaran') is-invalid @enderror" id="status_pembayaran" name="status_pembayaran" required>
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

                    <div class="col-md-6" id="tempoAngsuranField" style="display: {{ old('metode_pembayaran', $transaksi->metode_pembayaran) == 'Kredit' ? 'block' : 'none' }};">
                        <label for="tempo_angsuran" class="form-label text-muted">Tempo Angsuran (Tahun)</label>
                        <input type="number" class="form-control form-control-lg rounded-pill shadow-sm @error('tempo_angsuran') is-invalid @enderror" id="tempo_angsuran" name="tempo_angsuran" value="{{ old('tempo_angsuran', $transaksi->tempo_angsuran) }}" min="1">
                        @error('tempo_angsuran')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6" id="dpAmountField" style="display: {{ old('metode_pembayaran', $transaksi->metode_pembayaran) == 'Kredit' ? 'block' : 'none' }};">
                        <label for="dp_jumlah" class="form-label text-muted">Jumlah DP (Uang Muka)</label>
                        <input type="text" class="form-control form-control-lg rounded-pill shadow-sm @error('dp_jumlah') is-invalid @enderror" id="dp_jumlah" name="dp_jumlah" value="{{ old('dp_jumlah', (int)$transaksi->dp_jumlah) }}">
                        <small class="form-text text-muted">Isi 0 jika tidak ada DP.</small>
                        @error('dp_jumlah')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="total_harga" class="form-label text-muted">Harga Beli Mobil</label>
                        <input type="text" class="form-control form-control-lg rounded-pill shadow-sm @error('total_harga') is-invalid @enderror" id="total_harga" name="total_harga" value="{{ old('total_harga', (int)$transaksi->total_harga) }}" required>
                        <small class="form-text text-muted">Masukkan harga beli mobil.</small>
                        @error('total_harga')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-3 mb-4"> {{-- New row for side-by-side layout --}}
                    {{-- Informasi Mobil - Sekarang di kiri --}}
                    <div class="col-md-6">
                        <h5 class="mb-4 fw-bold text-dark border-bottom pb-2 mt-5">Informasi Mobil</h5>
                        <div class="mb-3">
                            <label for="mobil_id" class="form-label text-muted">Pilih Mobil</label>
                            <select class="form-control select2 form-select-lg rounded-pill shadow-sm @error('mobil_id') is-invalid @enderror" id="mobil_id" name="mobil_id" style="width: 100%;" required>
                                <option value="">Cari atau Pilih Mobil</option>
                                @foreach($mobils as $m)
                                    <option value="{{ $m->id }}"
                                        data-merek="{{ $m->merek_mobil }}"
                                        data-tipe="{{ $m->tipe_mobil ?? '' }}"
                                        data-tahun="{{ $m->tahun_pembuatan ?? '' }}"
                                        data-nomorpolisi="{{ $m->nomor_polisi }}"
                                        data-warnamobil="{{ $m->warna_mobil ?? '' }}"
                                        data-jenis="{{ $m->jenis_mobil ?? '' }}"
                                        data-norangka="{{ $m->nomor_rangka ?? '' }}"
                                        data-nomesin="{{ $m->nomor_mesin ?? '' }}"
                                        {{ old('mobil_id', $transaksi->mobil_id) == $m->id ? 'selected' : '' }}>
                                        {{ $m->merek_mobil }} {{ $m->tipe_mobil }} ({{ $m->tahun_pembuatan }}) - {{ $m->nomor_polisi }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Pilih mobil dari daftar atau ketik untuk mencari.</small>
                            @error('mobil_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label class="form-label text-muted">Detail Mobil</label>
                            <div class="p-3 bg-light rounded-4 shadow-sm border border-secondary-subtle info-detail-block">
                                <div class="mb-1">
                                    <label class="form-label small text-secondary mb-0">No. Plat</label>
                                    <input type="text" class="form-control form-control-plaintext fw-bold" id="mobil_detail_nomorpolisi" value="{{ $transaksi->mobil->nomor_polisi ?? '' }}" readonly />
                                </div>
                                <div class="mb-1">
                                    <label class="form-label small text-secondary mb-0">Jenis Mobil</label>
                                    <input type="text" class="form-control form-control-plaintext" id="mobil_detail_jenis" value="{{ $transaksi->mobil->jenis_mobil ?? '' }}" readonly />
                                </div>
                                <div class="mb-1">
                                    <label class="form-label small text-secondary mb-0">Merek</label>
                                    <input type="text" class="form-control form-control-plaintext fw-bold" id="mobil_detail_merek" value="{{ $transaksi->mobil->merek_mobil ?? '' }}" readonly />
                                </div>
                                <div class="mb-1">
                                    <label class="form-label small text-secondary mb-0">Model / Tipe</label>
                                    <input type="text" class="form-control form-control-plaintext" id="mobil_detail_tipe" value="{{ $transaksi->mobil->tipe_mobil ?? '' }}" readonly />
                                </div>
                                <div class="mb-1">
                                    <label class="form-label small text-secondary mb-0">Tahun</label>
                                    <input type="text" class="form-control form-control-plaintext" id="mobil_detail_tahun" value="{{ $transaksi->mobil->tahun_pembuatan ?? '' }}" readonly />
                                </div>
                                <div class="mb-1">
                                    <label class="form-label small text-secondary mb-0">Nomor Rangka</label>
                                    <input type="text" class="form-control form-control-plaintext" id="mobil_detail_norangka" value="{{ $transaksi->mobil->nomor_rangka ?? '' }}" readonly />
                                </div>
                                <div class="mb-1">
                                    <label class="form-label small text-secondary mb-0">Nomor Mesin</label>
                                    <input type="text" class="form-control form-control-plaintext" id="mobil_detail_nomesin" value="{{ $transaksi->mobil->nomor_mesin ?? '' }}" readonly />
                                </div>
                                <div>
                                    <label class="form-label small text-secondary mb-0">Warna</label>
                                    <input type="text" class="form-control form-control-plaintext" id="mobil_detail_warna" value="{{ $transaksi->mobil->warna_mobil ?? '' }}" readonly />
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Informasi Penjual (counterparty) - Sekarang di kanan --}}
                    <div class="col-md-6">
                        <h5 class="mb-4 fw-bold text-dark border-bottom pb-2 mt-5">Informasi Penjual</h5>
                        <div class="mb-3">
                            <label for="penjual_id" class="form-label text-muted">Pilih Penjual</label>
                            <select class="form-control select2 form-select-lg rounded-pill shadow-sm @error('penjual_id') is-invalid @enderror" id="penjual_id" name="penjual_id" style="width: 100%;" required>
                                <option value="">Cari atau Pilih Penjual</option>
                                @foreach($penjuals as $p)
                                    <option value="{{ $p->id }}"
                                        data-nama="{{ $p->nama }}"
                                        data-notelepon="{{ $p->no_telepon ?? '' }}" {{-- Sesuai dengan struktur tabel `penjual` --}}
                                        data-alamat="{{ $p->alamat ?? '' }}"
                                        {{ old('penjual_id', $transaksi->penjual_id) == $p->id ? 'selected' : '' }}>
                                        {{ $p->nama }} (Telp: {{ $p->no_telepon }}) {{-- Sesuai dengan struktur tabel `penjual` --}}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Pilih penjual dari daftar atau ketik untuk mencari.</small>
                            @error('penjual_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label class="form-label text-muted">Informasi Penjual</label>
                            <div class="p-3 bg-light rounded-4 shadow-sm border border-secondary-subtle info-detail-block">
                                <div class="mb-1">
                                    <label class="form-label small text-secondary mb-0">Nama</label>
                                    <input type="text" class="form-control form-control-plaintext fw-bold" id="penjual_info_nama_detail" value="{{ $transaksi->penjual->nama ?? '' }}" readonly />
                                </div>
                                <div class="mb-1">
                                    <label class="form-label small text-secondary mb-0">Nomor Telepon</label>
                                    <input type="text" class="form-control form-control-plaintext" id="penjual_info_telepon_detail" value="{{ $transaksi->penjual->no_telepon ?? '' }}" readonly /> {{-- Sesuai dengan struktur tabel `penjual` --}}
                                </div>
                                <div>
                                    <label class="form-label small text-secondary mb-0">Alamat</label>
                                    <input type="text" class="form-control form-control-plaintext" id="penjual_info_alamat_detail" value="{{ $transaksi->penjual->alamat ?? '' }}" readonly />
                                </div>
                            </div>
                        </div>
                    </div>
                </div> {{-- End of new row --}}

                {{-- Bukti Pembayaran --}}
                <h5 class="mb-4 fw-bold text-dark border-bottom pb-2 mt-5">Bukti Pembayaran</h5>
                <div class="mb-3">
                    <label for="bukti_pembayaran" class="form-label text-muted">Upload Bukti Pembayaran (Opsional)</label>
                    <input class="form-control form-control-lg rounded-pill shadow-sm @error('bukti_pembayaran') is-invalid @enderror" type="file" id="bukti_pembayaran" name="bukti_pembayaran" accept="image/*">
                    <small class="form-text text-muted">Unggah gambar bukti pembayaran (JPG, PNG, JPEG).</small>
                    @error('bukti_pembayaran')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @if($transaksi->bukti_pembayaran)
                        <div class="mt-3">
                            <p class="mb-1">Bukti pembayaran saat ini:</p>
                            <img src="{{ asset('storage/' . $transaksi->bukti_pembayaran) }}" alt="Bukti Pembayaran" class="img-thumbnail" style="max-width: 200px;">
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" name="delete_bukti_pembayaran" id="delete_bukti_pembayaran">
                                <label class="form-check-label" for="delete_bukti_pembayaran">Hapus bukti pembayaran saat ini</label>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Keterangan Tambahan --}}
                <h5 class="mb-4 fw-bold text-dark border-bottom pb-2 mt-5">Keterangan Tambahan</h5>
                <div class="mb-3">
                    <label for="keterangan" class="form-label text-muted">Keterangan (Opsional)</label>
                    <textarea class="form-control form-control-lg rounded-3 shadow-sm @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="3" placeholder="Tambahkan catatan atau detail tambahan mengenai transaksi.">{{ old('keterangan', $transaksi->keterangan) }}</textarea>
                    @error('keterangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-5 d-flex justify-content-end gap-3">
                    <button type="button" class="btn btn-primary btn-lg px-4 rounded-pill shadow-sm animate__animated animate__pulse animate__infinite" id="submitBtn">
                        <i class="bi bi-arrow-clockwise me-2"></i> Perbarui Transaksi
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
                <h5 class="modal-title fw-bold" id="confirmationModalLabel">Konfirmasi Perubahan Transaksi</h5>
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
                                Metode Pembayaran: <span id="modal_metode_pembayaran" class="text-muted"></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Status Pembayaran: <span id="modal_status_pembayaran" class="text-muted"></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center" id="modal_tempo_row" style="display: none;">
                                Tempo Angsuran: <span id="modal_tempo_angsuran" class="text-muted"></span> Tahun
                            </li>
                            {{-- REMOVED: <li class="list-group-item d-flex justify-content-between align-items-center" id="modal_dp_row" style="display: none;"> --}}
                            {{-- REMOVED:    Jumlah DP: <span id="modal_dp_jumlah" class="text-muted"></span> --}}
                            {{-- REMOVED: </li> --}}
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Harga Beli: <span id="modal_total_harga" class="fw-bold text-success"></span>
                            </li>
                            {{-- Biaya Akuisisi dihapus karena tidak ada di edit.blade.php Anda --}}
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold text-dark">Detail Penjual:</h6>
                        <ul class="list-group list-group-flush border rounded-3 p-2">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Nama: <span id="modal_penjual_nama" class="fw-bold"></span>
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
                        </ul>
                    </div>

                    <div class="col-md-12 mt-3">
                        <p class="text-center fw-bold fs-3 text-success mb-0">Total Harga Pembelian:</p>
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
                <button type="button" class="btn btn-primary rounded-pill px-4" id="confirmSubmitBtn">Konfirmasi & Perbarui</button>
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
    .alert-success { background-color: #e6ffed; color: #155724; border: 1px solid #c3e6cb; border-radius: 0.75rem; padding: 1.25rem 1.75rem; }
    .alert-success .alert-heading { color: #28a745; font-size: 1.1rem; }
    .alert-success ul { padding-left: 25px; }
    .alert-success li { margin-bottom: 5px; }
    .btn-outline-secondary { border-color: #6c757d; color: #6c757d; transition: all 0.3s ease; }
    .btn-outline-secondary:hover { background-color: #6c757d; color: white; transform: translateY(-1px); box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .btn-primary { background: linear-gradient(45deg, #007bff, #0056b3); border: none; transition: all 0.3s ease; font-weight: 600; letter-spacing: 0.5px; box-shadow: 0 4px 10px rgba(0, 123, 255, 0.2); }
    .btn-primary:hover { background: linear-gradient(45deg, #0056b3, #007bff); transform: translateY(-2px); box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1); }
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
    .info-detail-block .form-label.small {
        font-size: 0.75rem;
        margin-bottom: 0.2rem;
        color: #6c757d;
    }
    /* Style for the total price block */
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
    /* Modal specific styles */
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cleave.js/1.6.0/cleave.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cleave.js/1.6.0/addons/cleave-phone.id.js"></script>

<script>
    // Fungsi untuk memformat angka menjadi Rupiah (tetap dipertahankan untuk input field)
    function formatRupiah(amount) {
        if (typeof amount === 'string') {
            amount = parseFloat(amount.replace(/[^0-9,-]/g, '').replace(',', '.'));
        }
        if (isNaN(amount)) {
            amount = 0;
        }
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(amount);
    }

    // Fungsi baru untuk mendapatkan nilai mentah (tanpa format Rupiah atau titik)
    function getRawNumber(formattedString) {
        if (typeof formattedString === 'string') {
            // Hapus 'Rp ', titik sebagai pemisah ribuan, dan koma sebagai desimal (jika ada)
            // Untuk Cleave.js, cukup hapus semua karakter non-digit
            return parseFloat(formattedString.replace(/[^0-9]/g, '')) || 0;
        }
        return formattedString; // Jika sudah number, langsung kembalikan
    }

    $(document).ready(function() {
        // Initialize Select2 for all .select2 elements
        $('.select2').select2({
            placeholder: $(this).data('placeholder') ? $(this).data('placeholder') : 'Pilih...',
            allowClear: true,
            theme: "bootstrap-5",
            width: 'resolve'
        });

        // Toggle Tempo Angsuran and DP Amount fields based on Metode Pembayaran
        $('#metode_pembayaran').on('change', function() {
            if ($(this).val() === 'Kredit') {
                $('#tempoAngsuranField').slideDown();
                $('#dpAmountField').slideDown();
                $('#tempo_angsuran').prop('required', true);
            } else {
                $('#tempoAngsuranField').slideUp();
                $('#dpAmountField').slideUp();
                $('#tempo_angsuran').prop('required', false).val('');
                $('#dp_jumlah').val('');
            }
        }).trigger('change'); // Trigger on load to set initial state

        // Auto-fill Informasi Penjual when a seller is selected
        $('#penjual_id').on('change', function() {
            var selectedOption = $(this).find(':selected');
            var namaPenjual = selectedOption.data('nama') || '';
            var noTeleponPenjual = selectedOption.data('notelepon') || '';
            var alamatPenjual = selectedOption.data('alamat') || '';

            $('#penjual_info_nama_detail').val(namaPenjual);
            $('#penjual_info_telepon_detail').val(noTeleponPenjual);
            $('#penjual_info_alamat_detail').val(alamatPenjual);
        }).trigger('change'); // Trigger on load to populate initial data

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

            $('#mobil_detail_nomorpolisi').val(nomorPolisi);
            $('#mobil_detail_jenis').val(jenisMobil);
            $('#mobil_detail_merek').val(merekMobil);
            $('#mobil_detail_tipe').val(tipeMobil);
            $('#mobil_detail_tahun').val(tahun);
            $('#mobil_detail_nomorrangka').val(nomorRangka);
            $('#mobil_detail_nomormesin').val(nomorMesin);
            $('#mobil_detail_warna').val(warnaMobil);
        }).trigger('change'); // Trigger on load to populate initial data

        // Handle form submission via confirmation modal
        $('#submitBtn').on('click', function(e) {
            e.preventDefault();

            // Validate form before showing modal
            const form = document.getElementById('transaksiEditForm');
            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                return;
            }
            form.classList.remove('was-validated');

            // Populate confirmation modal
            $('#modal_kode_transaksi').text($('#kode_transaksi').val());
            $('#modal_tanggal_transaksi').text(new Date($('#tanggal_transaksi').val()).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }));

            // Ambil nilai mentah dari total_harga dan dp_jumlah menggunakan fungsi getRawNumber
            const rawTotalHarga = getRawNumber($('#total_harga').val());
            const rawDpJumlah = getRawNumber($('#dp_jumlah').val());

            // Tampilkan total_harga di modal tanpa format Rupiah dan titik
            $('#modal_total_harga').text(rawTotalHarga); // Menampilkan angka mentah di sini
            $('#modal_final_transaksi_total').text(rawTotalHarga); // Menampilkan angka mentah di sini


            $('#modal_metode_pembayaran').text($('#metode_pembayaran option:selected').text());
            $('#modal_status_pembayaran').text($('#status_pembayaran option:selected').text());

            // Penjual details for modal
            const selectedPenjualOption = $('#penjual_id').find(':selected');
            const modalNamaPenjual = selectedPenjualOption.data('nama') || '';
            const modalNoTeleponPenjual = selectedPenjualOption.data('notelepon') || '';
            const modalAlamatPenjual = selectedPenjualOption.data('alamat') || '';

            $('#modal_penjual_nama').text(modalNamaPenjual);
            $('#modal_penjual_telepon').text(modalNoTeleponPenjual);
            $('#modal_penjual_alamat').text(modalAlamatPenjual);

            // Mobil details for modal
            $('#modal_mobil_nomorpolisi').text($('#mobil_detail_nomorpolisi').val());
            $('#modal_mobil_jenis').text($('#mobil_detail_jenis').val());
            $('#modal_mobil_merek').text($('#mobil_detail_merek').val());
            $('#modal_mobil_tipe').text($('#mobil_detail_tipe').val());
            $('#modal_mobil_tahun').text($('#mobil_detail_tahun').val());
            $('#modal_mobil_norangka').text($('#mobil_detail_norangka').val());
            $('#modal_mobil_nomesin').text($('#mobil_detail_nomesin').val());
            $('#modal_mobil_warna').text($('#mobil_detail_warna').val());

            if ($('#metode_pembayaran').val() === 'Kredit') {
                $('#modal_tempo_row').show();
                // Removed display of 'Jumlah DP' from modal as per previous request
                $('#modal_tempo_angsuran').text($('#tempo_angsuran').val());
            } else {
                $('#modal_tempo_row').hide();
            }

            $('#modal_keterangan').text($('#keterangan').val() || 'Tidak ada keterangan.');


            var confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
            confirmationModal.show();
        });

        // Menangani pengiriman form setelah konfirmasi
        $('#confirmSubmitBtn').on('click', function() {
            // Kirim form
            $('#transaksiEditForm').submit();
        });
    });
</script>
@endsection
