@extends('layouts.app')

@section('title', 'Daftar Transaksi Pembeli')

@section('content')
<div class="container-fluid py-4 px-3 px-md-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6 mb-3 mb-md-0">
            <h4 class="text-dark fw-bold mb-0">Daftar Penjualan Mobil</h4>
            <small class="text-secondary">Detail transaksi Penjualan Mobil.</small>
        </div>
        <div class="col-md-6 text-md-end">
            {{-- Tombol Tambah Transaksi Pembeli Baru --}}
            <a href="{{ route('transaksi.pembeli.create') }}" class="btn btn-primary btn-lg shadow-lg rounded-pill animate__animated animate__fadeInRight">
                <i class="bi bi-plus-circle me-2"></i> Tambah Transaksi Pembeli Baru
            </a>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary rounded-pill animate__animated animate__fadeInRight ms-2">
                <i class="bi bi-arrow-left me-2"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-3 animate__animated animate__fadeInDown" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
    @endif

    <div class="card border-0 shadow-xl rounded-4 animate__animated animate__fadeInUp">
        <div class="card-header bg-white p-4 border-bottom-0 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <div class="header-left mb-3 mb-md-0">
                <h5 class="card-title mb-0 text-dark fw-bold">Detail Transaksi Penjualan Mobil</h5>
                <p class="card-text text-muted">Lihat Detail Transaksi, Angsuran, Edit Pembayaran.</p>
            </div>
            <div class="header-right d-flex flex-column flex-md-row align-items-md-center gap-3">
                <input type="text" id="searchInput" class="form-control rounded-pill shadow-sm" placeholder="Cari transaksi..." aria-label="Cari transaksi">

                {{-- Filter Tanggal Transaksi --}}
                @php
                    $today = date('Y-m-d');
                    $oneMonthAgo = date('Y-m-d', strtotime('-1 month'));
                @endphp
                <div class="d-flex flex-column flex-md-row gap-2">
                    <input type="date" id="startDateFilter" class="form-control rounded-pill shadow-sm" title="Dari Tanggal Transaksi" max="{{ $today }}" min="{{ $oneMonthAgo }}">
                    <input type="date" id="endDateFilter" class="form-control rounded-pill shadow-sm" title="Sampai Tanggal Transaksi" max="{{ $today }}" min="{{ $oneMonthAgo }}">
                </div>

                {{-- Filter Metode Pembayaran --}}
                <select id="metodePembayaranFilter" class="form-select rounded-pill shadow-sm">
                    <option value="">Semua Metode Pembayaran</option>
                    <option value="Transfer Bank">Transfer Bank</option>
                    <option value="Cash">Cash</option>
                    <option value="Kredit">Kredit</option>
                </select>

                {{-- Filter Status Transaksi --}}
                <select id="statusFilter" class="form-select rounded-pill shadow-sm">
                    <option value="">Semua Status</option>
                    {{-- Nilai yang sesuai dengan pilihan di form edit --}}
                    <option value="Lunas">Lunas</option>
                    <option value="Belum Lunas">Belum Lunas</option>
                    <option value="Menunggu Pembayaran">Menunggu Pembayaran</option>
                    <option value="Dibatalkan">Dibatalkan</option>
                </select>

                <button id="resetFilters" class="btn btn-outline-secondary rounded-pill shadow-sm">Reset</button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive" id="tableResponsiveContainer">
                <table class="table table-hover table-striped align-middle mb-0 custom-table" id="transaksiPembeliTable">
                    <thead class="bg-gradient-primary text-white text-center align-middle">
                        <tr>
                            <th scope="col" style="width: 160px;">Kode Transaksi</th>
                            <th scope="col" style="width: 120px;">Tanggal</th>
                            <th scope="col" style="width: 120px;">Jam</th>
                            <th scope="col" style="min-width: 280px;">Informasi Mobil</th>
                            <th scope="col" style="min-width: 180px;">Nama Pembeli</th>
                            <th scope="col" style="width: 150px;">Metode Pembayaran</th>
                            <th scope="col" style="width: 150px;">Modal</th>
                            <th scope="col" style="width: 150px;">Total Harga</th>
                            <th scope="col" style="width: 130px;">Status</th>
                            <th scope="col" style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksiPembeli as $transaksi)
                        <tr class="data-row"
                            data-kode="{{ strtolower($transaksi->kode_transaksi) }}"
                            data-tanggal="{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('Y-m-d') }}"
                            data-jam="{{ \Carbon\Carbon::parse($transaksi->created_at)->format('H:i') }}"
                            data-metode="{{ strtolower($transaksi->metode_pembayaran ?? '') }}"
                            data-status="{{ strtolower($transaksi->status_pembayaran ?? '') }}">
                            <td class="text-center fw-bold">{{ $transaksi->kode_transaksi }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d M Y') }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($transaksi->created_at)->format('H:i') }} WIB</td>
                            <td>
                                @php
                                    $mobilDisplay = '';
                                    if ($transaksi->mobil) {
                                        if ($transaksi->mobil->tahun_pembuatan) {
                                            $mobilDisplay .= $transaksi->mobil->tahun_pembuatan . ' ';
                                        }
                                        $mobilDisplay .= $transaksi->mobil->merek_mobil ?? 'N/A';
                                        if ($transaksi->mobil->tipe_mobil) {
                                            $mobilDisplay .= ' ' . $transaksi->mobil->tipe_mobil;
                                        }
                                        $mobilDisplay .= ' - ' . ($transaksi->mobil->nomor_polisi ?? 'N/A');
                                    } else {
                                        $mobilDisplay = 'Tidak Ada Mobil';
                                    }
                                @endphp
                                {{ $mobilDisplay }}
                            </td>
                            <td>{{ $transaksi->pembeli->nama ?? 'N/A' }}</td>
                            <td>
                                {{ $transaksi->metode_pembayaran ?? 'N/A' }}
                                @if ($transaksi->metode_pembayaran == 'Kredit' && $transaksi->tempo_angsuran)
                                    <br>
                                    <span class="installment-trigger text-primary text-decoration-underline"
                                          style="cursor: pointer;"
                                          data-bs-toggle="modal"
                                          data-bs-target="#installmentModal"
                                          data-total-harga="{{ $transaksi->total_harga }}"
                                          data-tempo-angsuran="{{ $transaksi->tempo_angsuran }}"
                                          data-kode-transaksi="{{ $transaksi->kode_transaksi }}"
                                          data-modal-biaya-servis="{{ $transaksi->modal ?? 0 }}" {{-- MODIFIED LINE --}}
                                          data-dp-amount="{{ $transaksi->dp_jumlah ?? 0 }}">
                                        {{ $transaksi->tempo_angsuran }} Tahun
                                    </span>
                                @endif
                            </td>
                            <td class="text-end">
                                @php
                                    $totalServisHarga = 0;
                                    // Pastikan relasi mobil dan servis dimuat
                                    if ($transaksi->mobil && $transaksi->mobil->servis) {
                                        foreach ($transaksi->mobil->servis as $servis) {
                                            $totalServisHarga += $servis->total_harga ?? 0;
                                        }
                                    }
                                @endphp
                                Rp{{ number_format($totalServisHarga, 0, ',', '.') }}
                            </td>
                            {{-- Menggunakan $transaksi->total_harga secara langsung --}}
                            <td class="text-end">
                                Rp{{ number_format($transaksi->total_harga, 0, ',', '.') }}
                            </td>
                            <td class="text-center">
                                @php
                                    $badgeClass = '';
                                    $statusText = $transaksi->status_pembayaran ?? 'Tidak Ada';
                                    switch (strtolower($statusText)) {
                                        case 'lunas': $badgeClass = 'bg-success'; break;
                                        case 'belum lunas': $badgeClass = 'bg-warning text-dark'; break;
                                        case 'menunggu pembayaran': $badgeClass = 'bg-info text-dark'; break;
                                        case 'dibatalkan': $badgeClass = 'bg-danger'; break;
                                        default: $badgeClass = 'bg-secondary'; break;
                                    }
                                @endphp
                                <span class="badge {{ $badgeClass }}">
                                    @if(strtolower($statusText) === 'menunggu pembayaran')
                                        Menunggu<br>Pembayaran
                                    @else
                                        {{ ucfirst($statusText) }}
                                    @endif
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                    {{-- Tombol Detail --}}
                                    <button type="button" class="btn btn-sm btn-outline-info action-btn" data-bs-toggle="modal" data-bs-target="#detailModal" data-id="{{ $transaksi->id }}" title="Lihat Detail Transaksi">
                                        <i class="bi bi-eye"></i>
                                    </button>

                                    {{-- Tombol Edit/Update Pembayaran hanya untuk Manajer dan Admin --}}
                                    @if(in_array(strtolower(auth()->user()->job ?? ''), ['manajer', 'admin']))
                                    <a href="{{ route('transaksi.pembeli.edit', $transaksi->id) }}" class="btn btn-sm btn-outline-warning action-btn" title="Update Pembayaran">
                                        <i class="bi bi-cash"></i>
                                    </a>
                                    @endif
                                </div>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted py-5 empty-state">
                                <i class="bi bi-info-circle-fill fs-3 mb-2 d-block"></i>
                                <p class="mb-1">Tidak ada transaksi pembeli ditemukan.</p>
                                <p class="mb-0">Coba kata kunci atau filter lain.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-light border-top-0 py-3 px-4 d-flex justify-content-between align-items-center rounded-bottom-4 gap-3">
            <small class="text-muted">Data ditampilkan: {{ $transaksiPembeli->count() }} dari {{ $transaksiPembeli->total() }}</small>
            {{ $transaksiPembeli->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

{{-- Detail Transaksi Modal --}}
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 shadow-lg border-0">
            <div class="modal-header bg-info text-white border-0 rounded-top-4 p-3">
                <h5 class="modal-title fw-bold fs-5" id="detailModalLabel"><i class="bi bi-file-text-fill me-2"></i> Detail Data Transaksi</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                {{-- MODIFIED: Added a clear header for transaction info --}}
                <h6 class="mb-3 fw-bold border-bottom pb-2">
                    <i class="bi bi-card-checklist me-2 text-primary"></i><span class="text-dark">Informasi Transaksi:</span>
                </h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <p class="mb-0 text-muted small">Kode Transaksi:</p>
                            <p class="mb-0 fw-bold fs-6" id="detail-kode"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <p class="mb-0 text-muted small">Tanggal Transaksi:</p>
                            <p class="mb-0 fw-bold fs-6" id="detail-tanggal"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <p class="mb-0 text-muted small">Jam Transaksi:</p>
                            <p class="mb-0 fw-bold fs-6" id="detail-jam"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <p class="mb-0 text-muted small">Metode Pembayaran:</p>
                            <p class="mb-0 fw-bold fs-6" id="detail-metode"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <p class="mb-0 text-muted small">Total Harga:</p>
                            <p class="mb-0 fw-bold fs-5 text-primary" id="detail-total"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <p class="mb-0 text-muted small">Status Pembayaran:</p>
                            <p class="mb-0 fw-bold fs-6" id="detail-status"></p>
                        </div>
                    </div>
                </div>

                <h6 class="mt-4 mb-3 fw-bold border-bottom pb-2">
                    <i class="bi bi-people-fill me-2 text-primary"></i><span class="text-dark">Detail Pihak Terkait:</span>
                </h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <p class="mb-0 text-muted small">Pembeli:</p>
                            <p class="mb-0 fw-bold" id="detail-pembeli"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <p class="mb-0 text-muted small">Alamat Pembeli:</p>
                            <p class="mb-0 fw-bold" id="detail-pembeli-alamat"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <p class="mb-0 text-muted small">Penjual:</p>
                            <p class="mb-0 fw-bold" id="detail-penjual"></p>
                        </div>
                    </div>
                </div>

                <h6 class="mt-4 mb-3 fw-bold border-bottom pb-2">
                    <i class="bi bi-car-front-fill me-2 text-primary"></i><span class="text-dark">Detail Mobil:</span>
                </h6>
                <div class="row g-3 mb-4">
                    {{-- Informasi Mobil is a combined field, not a simple label-value pair --}}
                    <div class="col-md-12">
                        <p class="mb-1 text-muted small">Informasi Mobil:</p>
                        <p class="mb-0 fw-bold" id="detail-mobil"></p>
                    </div>
                     <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <p class="mb-0 text-muted small">No. Plat:</p>
                            <p class="mb-0 fw-bold" id="detail-mobil-nomorpolisi"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <p class="mb-0 text-muted small">Jenis Mobil:</p>
                            <p class="mb-0 fw-bold" id="detail-mobil-jenis"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <p class="mb-0 text-muted small">Merek:</p>
                            <p class="mb-0 fw-bold" id="detail-mobil-merek"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <p class="mb-0 text-muted small">Model / Tipe:</p>
                            <p class="mb-0 fw-bold" id="detail-mobil-tipe"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <p class="mb-0 text-muted small">Tahun:</p>
                            <p class="mb-0 fw-bold" id="detail-mobil-tahun"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <p class="mb-0 text-muted small">Nomor Rangka:</p>
                            <p class="mb-0 fw-bold" id="detail-mobil-norangka"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <p class="mb-0 text-muted small">Nomor Mesin:</p>
                            <p class="mb-0 fw-bold" id="detail-mobil-nomesin"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <p class="mb-0 text-muted small">Warna:</p>
                            <p class="mb-0 fw-bold" id="detail-mobil-warna"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <p class="mb-0 text-muted small">Nomor BPKB:</p>
                            <p class="mb-0 fw-bold" id="detail-mobil-bpkb"></p>
                        </div>
                    </div>
                    {{-- Prices - Using flex for inline label-value with justified content --}}
                    <div class="col-md-12">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <p class="mb-0 text-muted small">Harga Mobil:</p>
                            <p class="mb-0 fw-bold fs-6 text-primary" id="detail-mobil-harga"></p>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <p class="mb-0 text-muted small">Total Harga Servis:</p>
                            <p class="mb-0 fw-bold fs-6 text-info" id="detail-servis-total-harga"></p>
                        </div>
                    </div>
                </div>

                <h6 class="mt-4 mb-3 fw-bold border-bottom pb-2">
                    <i class="bi bi-image-fill me-2 text-primary"></i><span class="text-dark">Bukti Pembayaran:</span>
                </h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-12 text-center">
                        <p class="mb-1 text-muted small">Gambar Bukti Pembayaran:</p>
                        <div id="bukti-pembayaran-container" class="d-none border rounded p-2 bg-light shadow-sm">
                            {{-- Klik gambar akan membuka modal imageViewerModal --}}
                            <a href="#" id="bukti-pembayaran-link" class="d-block">
                                <img id="detail-bukti-pembayaran" src="" alt="Bukti Pembayaran" class="img-fluid rounded" style="max-width: 250px; height: auto; cursor: zoom-in;">
                            </a>
                            <p class="text-muted small mt-2">Klik gambar untuk memperbesar</p>
                        </div>
                        <p id="no-bukti-pembayaran" class="text-muted small d-none py-3 border rounded bg-light">Tidak ada bukti pembayaran tersedia.</p>
                    </div>
                </div>

                <h6 class="mt-4 mb-3 fw-bold border-bottom pb-2">
                    <i class="bi bi-chat-left-text-fill me-2 text-primary"></i><span class="text-dark">Keterangan Tambahan:</span>
                </h6>
                <p id="detail-keterangan" class="text-muted small border rounded p-3 bg-light-subtle"></p>

            </div>
            <div class="modal-footer border-0 p-3 bg-light rounded-bottom-4">
                <button type="button" class="btn btn-outline-secondary px-4 rounded-pill" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="imageViewerModal" tabindex="-1" aria-labelledby="imageViewerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageViewerModalLabel">Detail Gambar Bukti Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-3">
                <img src="" id="largeImageView" class="img-fluid rounded" alt="Bukti Pembayaran Besar" style="max-height: 85vh; width: auto;">
            </div>
            <div class="modal-footer border-0 p-3 bg-light">
                <button type="button" class="btn btn-outline-secondary px-4 rounded-pill" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- MODIFIED: Modal Angsuran (Simplified UI with minimal colors and reduced padding) --}}
<div class="modal fade" id="installmentModal" tabindex="-1" aria-labelledby="installmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content rounded-4 shadow-lg">
            <div class="modal-header bg-primary text-white border-0 rounded-top-4 p-3">
                <h5 class="modal-title fw-bold fs-5" id="installmentModalLabel"><i class="bi bi-receipt-cutoff me-2"></i> Rincian Angsuran Kredit</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 bg-light-subtle">
                <div class="d-flex justify-content-between align-items-baseline mb-3 pb-2 border-bottom border-primary-subtle">
                    <p class="mb-0 text-muted small">Kode Transaksi:</p>
                    <p class="mb-0 text-secondary small" id="installment_kode_transaksi"></p>
                </div>

                <div class="row g-3 px-2">
                    <div class="col-12 d-flex justify-content-between align-items-center py-1">
                        <p class="mb-0 fw-bold text-muted small">Harga Pokok Mobil:</p>
                        <p class="mb-0 fw-bold fs-6 text-dark" id="installment_harga_mobil_pokok"></p>
                    </div>
                    <div class="col-12 d-flex justify-content-between align-items-center py-1">
                        <p class="mb-0 fw-bold text-muted small">Biaya Servis (Modal):</p>
                        <p class="mb-0 fw-bold fs-6 text-dark" id="installment_biaya_servis_modal"></p>
                    </div>
                    <div class="col-12 d-flex justify-content-between align-items-center py-1 border-top pt-3 mt-3 border-secondary-subtle">
                        <p class="mb-0 fw-bold text-muted small">Total Harga Transaksi (Mobil + Servis):</p>
                        <p class="mb-0 fw-bold fs-5 text-primary" id="installment_total_harga"></p>
                    </div>
                    <div class="col-12 d-flex justify-content-between align-items-center py-1" id="installment_dp_amount_row" style="display: none;">
                        <p class="mb-0 fw-bold text-muted small">Jumlah DP (Uang Muka):</p>
                        <p class="mb-0 fw-bold fs-5 text-success" id="installment_dp_amount"></p>
                    </div>
                    <div class="col-12 d-flex justify-content-between align-items-center py-1 border-top pt-3 mt-3 border-secondary-subtle">
                        <p class="mb-0 fw-bold text-muted small">Jumlah yang Diangsur:</p>
                        <p class="mb-0 fw-bold fs-5 text-danger" id="installment_jumlah_diangsur"></p>
                    </div>
                    <div class="col-12 d-flex justify-content-between align-items-center py-1">
                        <p class="mb-0 fw-bold text-muted small">Tempo Angsuran:</p>
                        <p class="mb-0 fw-bold fs-6 text-dark" id="installment_tempo_angsuran"></p>
                    </div>
                </div>

                <hr class="my-4 border-secondary border-2">

                <h6 class="fw-bold text-dark mb-3 text-center">Angsuran yang Harus Dibayar Per Bulan</h6>
                <div class="text-center p-4 bg-white rounded-3 shadow-sm border border-danger">
                    <p class="mb-1 text-danger small">Jumlah Angsuran Per Bulan:</p>
                    <p class="fs-1 fw-bolder text-danger mb-0" id="installment_per_month"></p>
                </div>
            </div>
            <div class="modal-footer border-0 rounded-bottom-4 py-3 d-flex justify-content-center">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>


<style>
    body { background-color: #f8f9fa; font-family: 'Poppins', sans-serif; color: #343a40; }
    .container-fluid.py-4 { padding-top: 2.5rem !important; padding-bottom: 2.5rem !important; }
    .card { border-radius: 1rem !important; overflow: hidden; box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.09) !important; }
    .card-header { background-color: #ffffff; border-bottom: 1px solid #e9ecef; }
    .custom-table { border-collapse: separate; border-spacing: 0; table-layout: fixed; width: 100%; }
    .custom-table thead th { background: linear-gradient(90deg, #495057, #6c757d); color: #fff; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; padding: 1rem 0.8rem; border-bottom: none; white-space: nowrap; }
    .custom-table thead th:last-child { border-top-right-radius: 1rem; }
    .custom-table thead th:first-child { border-top-left-radius: 1rem; }
    .custom-table tbody tr { transition: all 0.2s ease; border-bottom: 1px solid #dee2e6; }
    .custom-table tbody tr:last-child { border-bottom: none; }
    .custom-table tbody tr:hover { background-color: #f1f3f5; box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.05); transform: translateY(-1px); }
    /* Adjusted padding for longer rows */
    .custom-table tbody td { padding: 1.8rem 0.8rem; vertical-align: middle; font-size: 0.88rem; color: #495057; word-wrap: break-word; max-height: 100px; overflow-y: hidden; }
    /* IMPORTANT: Ensure table-responsive container allows horizontal scrolling */
    #tableResponsiveContainer { overflow-x: auto; }
    /* Specific column width adjustments for Transaksi Pembeli Index */
    .custom-table th:nth-child(1), .custom-table td:nth-child(1) { width: 160px; }
    .custom-table th:nth-child(2), .custom-table td:nth-child(2) { width: 120px; }
    .custom-table th:nth-child(3), .custom-table td:nth-child(3) { width: 120px; }
    .custom-table th:nth-child(4), .custom-table td:nth-child(4) { min-width: 280px; }
    .custom-table th:nth-child(5), .custom-table td:nth-child(5) { min-width: 180px; }
    .custom-table th:nth-child(6), .custom-table td:nth-child(6) { width: 150px; }
    .custom-table th:nth-child(7), .custom-table td:nth-child(7) { width: 150px; }
    .custom-table th:nth-child(8), .custom-table tbody td:nth-child(8) { width: 150px; }
    .custom-table th:nth-child(9), .custom-table td:nth-child(9) { width: 130px; }
    .custom-table th:nth-child(10), .custom-table td:nth-child(10) { width: 180px; }

    /* Modal styles */
    .modal-header.bg-info { background-color: #17a2b8 !important; }
    .btn-close-white { filter: invert(1) brightness(2); }
    .modal-body p.small { font-size: 0.85rem; }
    .modal-body p.fw-bold { font-size: 0.95rem; }
    .modal-body p.fs-5 { font-size: 1.1rem !important; }
    .modal-body p.fs-6 { font-size: 1rem !important; }

    .btn-group-actions .action-btn {
        margin: 0 2px;
        border-radius: 0.5rem;
        transition: all 0.2s ease;
    }
    .btn-group-actions .action-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    /* Style for the new installment trigger */
    .installment-trigger {
        text-decoration: underline;
        cursor: pointer;
        color: #0d6efd;
        font-weight: bold;
    }
    .installment-trigger:hover {
        color: #0a58ca;
    }

    /* Styles for the simplified installmentModal UI */
    .modal-body.bg-light-subtle {
        background-color: #fcfcfc !important;
    }
    /* Reduced padding for all py-1 rows in the installment modal */
    .modal-body .py-1 {
        padding-top: 0.25rem !important; /* Half of py-2 */
        padding-bottom: 0.25rem !important; /* Half of py-2 */
    }
    .modal-body .border-top {
        margin-top: 1rem !important;
        padding-top: 1rem !important;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function formatRupiah(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(amount);
    }

    $(document).ready(function() {
        // Handle Detail Transaksi Modal
        $('#detailModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var transaksiId = button.data('id');
            var modal = $(this);

            $.ajax({
                url: `/transaksi/${transaksiId}`,
                method: 'GET',
                success: function(data) {
                    console.log('Detail Transaksi:', data);

                    // Populate Transaction Info
                    modal.find('#detail-kode').text(data.kode_transaksi || 'N/A');
                    modal.find('#detail-tanggal').text(new Date(data.tanggal_transaksi).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) || 'N/A');
                    modal.find('#detail-jam').text(new Date(data.created_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) + ' WIB' || 'N/A');
                    modal.find('#detail-metode').text(data.metode_pembayaran || 'N/A');
                    modal.find('#detail-total').text(formatRupiah(data.total_harga || 0));
                    modal.find('#detail-status').text(data.status_pembayaran || 'N/A');

                    // Populate Pembeli Details
                    if (data.pembeli) {
                        modal.find('#detail-pembeli').text(data.pembeli.nama || 'N/A');
                        modal.find('#detail-pembeli-alamat').text(data.pembeli.alamat || 'N/A');
                    } else {
                        modal.find('#detail-pembeli').text('N/A');
                        modal.find('#detail-pembeli-alamat').text('N/A');
                    }

                    if (data.penjual) {
                        modal.find('#detail-penjual').text(data.penjual.nama || 'N/A');
                    } else {
                        modal.find('#detail-penjual').text('N/A');
                    }


                    // Populate Mobil Details
                    var mobilDisplay = '';
                    if (data.mobil) {
                        if (data.mobil.tahun_pembuatan) {
                            mobilDisplay += data.mobil.tahun_pembuatan + ' ';
                        }
                        mobilDisplay += data.mobil.merek_mobil || 'N/A';
                        if (data.mobil.tipe_mobil) {
                            mobilDisplay += ' ' + data.mobil.tipe_mobil;
                        }
                        mobilDisplay += ' - ' + (data.mobil.nomor_polisi || 'N/A');

                        modal.find('#detail-mobil').text(mobilDisplay);
                        modal.find('#detail-mobil-nomorpolisi').text(data.mobil.nomor_polisi || 'N/A');
                        modal.find('#detail-mobil-jenis').text(data.mobil.jenis_mobil || 'N/A');
                        modal.find('#detail-mobil-merek').text(data.mobil.merek_mobil || 'N/A');
                        modal.find('#detail-mobil-tipe').text(data.mobil.tipe_mobil || 'N/A');
                        modal.find('#detail-mobil-tahun').text(data.mobil.tahun_pembuatan || 'N/A');
                        modal.find('#detail-mobil-norangka').text(data.mobil.nomor_rangka || 'N/A');
                        modal.find('#detail-mobil-nomesin').text(data.mobil.nomor_mesin || 'N/A');
                        modal.find('#detail-mobil-warna').text(data.mobil.warna_mobil || 'N/A');
                        modal.find('#detail-mobil-bpkb').text(data.mobil.nomor_bpkb || 'N/A');
                        modal.find('#detail-mobil-harga').text(formatRupiah(data.mobil.harga_mobil || 0));
                    } else {
                        // Jika data.mobil null, set semua field ke 'N/A'
                        mobilDisplay = 'Tidak Ada Mobil';
                        modal.find('#detail-mobil').text(mobilDisplay);
                        modal.find('#detail-mobil-nomorpolisi').text('N/A');
                        modal.find('#detail-mobil-jenis').text('N/A');
                        modal.find('#detail-mobil-merek').text('N/A');
                        modal.find('#detail-mobil-tipe').text('N/A');
                        modal.find('#detail-mobil-tahun').text('N/A');
                        modal.find('#detail-mobil-norangka').text('N/A');
                        modal.find('#detail-mobil-nomesin').text('N/A');
                        modal.find('#detail-mobil-warna').text('N/A');
                        modal.find('#detail-mobil-bpkb').text('N/A');
                        modal.find('#detail-mobil-harga').text('N/A');
                    }

                    modal.find('#detail-servis-total-harga').text(formatRupiah(data.servis_total_harga_calculated || 0));

                    // Populate Bukti Pembayaran
                    const buktiPembayaranContainer = modal.find('#bukti-pembayaran-container');
                    const noBuktiPembayaran = modal.find('#no-bukti-pembayaran');
                    const detailBuktiPembayaranImg = modal.find('#detail-bukti-pembayaran');
                    const buktiPembayaranLink = modal.find('#bukti-pembayaran-link');
                    const largeImageView = $('#largeImageView');

                    if (data.bukti_pembayaran) {
                        const imageUrl = data.bukti_pembayaran;
                        detailBuktiPembayaranImg.attr('src', imageUrl);
                        buktiPembayaranLink.attr('href', '#').off('click').on('click', function(e) {
                            e.preventDefault();
                            largeImageView.attr('src', imageUrl);
                            $('#imageViewerModal').modal('show');
                        });
                        buktiPembayaranContainer.removeClass('d-none');
                        noBuktiPembayaran.addClass('d-none');
                    } else {
                        buktiPembayaranContainer.addClass('d-none');
                        noBuktiPembayaran.removeClass('d-none');
                    }

                    // Populate Keterangan
                    modal.find('#detail-keterangan').text(data.keterangan || 'Tidak ada keterangan tambahan.');
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching transaction details:", error);
                    console.error("Response Text:", xhr.responseText);
                    modal.find('.modal-body').html('<p class="text-danger text-center">Gagal memuat detail transaksi. Silakan coba lagi. Cek konsol browser untuk detail error.</p>');
                }
            });
        });

        // Handle Installment Modal
        $('#installmentModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var totalHargaTransaksi = parseFloat(button.data('total-harga'));
            var tempoAngsuran = parseInt(button.data('tempo-angsuran'));
            var kodeTransaksi = button.data('kode-transaksi');
            var biayaServisModal = parseFloat(button.data('modal-biaya-servis') || 0);
            var dpAmount = parseFloat(button.data('dp-amount') || 0);

            var modal = $(this);

            // 1. Kode Transaksi
            modal.find('#installment_kode_transaksi').text(kodeTransaksi);

            // 2. Harga Pokok Mobil
            var hargaPokokMobil = totalHargaTransaksi - biayaServisModal;
            modal.find('#installment_harga_mobil_pokok').text(formatRupiah(hargaPokokMobil));

            // 3. Biaya Servis (Modal)
            modal.find('#installment_biaya_servis_modal').text(formatRupiah(biayaServisModal));

            // 4. Total Harga Transaksi
            modal.find('#installment_total_harga').text(formatRupiah(totalHargaTransaksi));

            // 5. Jumlah DP (Uang Muka)
            if (dpAmount > 0) {
                modal.find('#installment_dp_amount_row').show();
                modal.find('#installment_dp_amount').text(formatRupiah(dpAmount));
            } else {
                modal.find('#installment_dp_amount_row').hide();
            }

            // 6. Jumlah yang Diangsur
            var jumlahDiangsur = totalHargaTransaksi - dpAmount;
            modal.find('#installment_jumlah_diangsur').text(formatRupiah(jumlahDiangsur));

            // 7. Tempo Angsuran
            modal.find('#installment_tempo_angsuran').text(tempoAngsuran + ' Tahun (' + (tempoAngsuran * 12) + ' Bulan)');

            // 8. Angsuran Per Bulan
            var angsuranPerBulan = jumlahDiangsur / (tempoAngsuran * 12);
            if (angsuranPerBulan < 0) {
                angsuranPerBulan = 0;
            }
            modal.find('#installment_per_month').text(formatRupiah(angsuranPerBulan));
        });

        // Filter and Search Logic
        function applyFiltersAndSearch() {
            const searchText = $('#searchInput').val().toLowerCase();
            const startDate = $('#startDateFilter').val();
            const endDate = $('#endDateFilter').val();
            const metodePembayaran = $('#metodePembayaranFilter').val().toLowerCase();
            const statusPembayaran = $('#statusFilter').val().toLowerCase();

            let hasVisibleRows = false;

            $('#transaksiPembeliTable tbody tr').each(function() {
                const row = $(this);
                if (row.hasClass('empty-state')) {
                    row.hide();
                    return true;
                }

                const kodeTransaksi = row.data('kode').toLowerCase();
                const tanggalTransaksi = row.data('tanggal');
                const metode = row.data('metode');
                const status = row.data('status');
                const pembeliNama = row.find('td:eq(4)').text().toLowerCase();
                const mobilInfo = row.find('td:eq(3)').text().toLowerCase();

                const matchesSearch = searchText === '' ||
                                      kodeTransaksi.includes(searchText) ||
                                      pembeliNama.includes(searchText) ||
                                      mobilInfo.includes(searchText);

                const matchesDate = (startDate === '' || tanggalTransaksi >= startDate) &&
                                    (endDate === '' || tanggalTransaksi <= endDate);

                const matchesMetode = metodePembayaran === '' || metode === metodePembayaran;
                const matchesStatus = statusPembayaran === '' || status === statusPembayaran;

                if (matchesSearch && matchesDate && matchesMetode && matchesStatus) {
                    row.show();
                    hasVisibleRows = true;
                } else {
                    row.hide();
                }
            });

            const emptyStateRow = $('#transaksiPembeliTable .empty-state');
            if (emptyStateRow.length) {
                if (hasVisibleRows) {
                    emptyStateRow.hide();
                } else {
                    emptyStateRow.find('td').attr('colspan', $('#transaksiPembeliTable th').length);
                    emptyStateRow.show();
                }
            } else if (!hasVisibleRows && $('#transaksiPembeliTable tbody').length) {
                const newEmptyStateRow = document.createElement('tr');
                newEmptyStateRow.classList.add('empty-state');
                newEmptyStateRow.innerHTML = `
                    <td colspan=\"${$('#transaksiPembeliTable th').length}\" class=\"text-center text-muted py-5\">
                        <i class=\"bi bi-info-circle-fill fs-3 mb-2 d-block\"></i>
                        <p class=\"mb-1\">Tidak ada transaksi pembeli ditemukan.</p>
                        <p class=\"mb-0\">Coba kata kunci atau filter lain.</p>
                    </td>
                `;
                $('#transaksiPembeliTable tbody').append(newEmptyStateRow);
            }
        }

        $('#searchInput').on('keyup', applyFiltersAndSearch);
        $('#startDateFilter').on('change', applyFiltersAndSearch);
        $('#endDateFilter').on('change', applyFiltersAndSearch);
        $('#metodePembayaranFilter').on('change', applyFiltersAndSearch);
        $('#statusFilter').on('change', applyFiltersAndSearch);

        applyFiltersAndSearch();
    });
</script>

@endsection
