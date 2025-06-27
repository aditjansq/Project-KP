@extends('layouts.app')

@section('title', 'Daftar Transaksi Pembeli')

@section('content')
<div class="container-fluid py-4 px-3 px-md-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6 mb-3 mb-md-0">
            <h4 class="text-dark fw-bold mb-0">Daftar Transaksi Pembeli</h4>
            <small class="text-secondary">Informasi lengkap transaksi terkait pembeli.</small>
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
                <h5 class="card-title mb-0 text-dark fw-bold">Detail Transaksi Pembeli</h5>
                <p class="card-text text-muted">Daftar transaksi di mana pembeli terlibat.</p>
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
                    <option value="Kartu Kredit">Kartu Kredit</option>
                    <option value="Debit Card">Debit Card</option>
                    <option value="BCA Virtual Account">BCA Virtual Account</option>
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
                            <th scope="col" style="width: 120px;">Tanggal</th> {{-- Diubah: Tanggal Transaksi -> Tanggal --}}
                            <th scope="col" style="width: 120px;">Jam</th> {{-- Diubah: Jam Transaksi -> Jam --}}
                            <th scope="col" style="min-width: 280px;">Informasi Mobil</th>
                            <th scope="col" style="min-width: 180px;">Nama Pembeli</th>
                            <th scope="col" style="width: 150px;">Metode Pembayaran</th>
                            <th scope="col" style="width: 100px;">Diskon (%)</th>
                            <th scope="col" style="width: 150px;">Total Harga</th>
                            <th scope="col" style="min-width: 280px;">Keterangan</th>
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
                            <td>{{ $transaksi->metode_pembayaran ?? 'N/A' }}</td>
                            <td class="text-center">{{ $transaksi->diskon_persen ?? 0 }}%</td>
                            <td class="text-end">Rp{{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                            <td>{{ $transaksi->keterangan ?? '-' }}</td>
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
                                <div class="d-flex justify-content-center align-items-center btn-group-actions">
                                    <button class="btn btn-sm btn-outline-info action-btn" data-bs-toggle="modal" data-bs-target="#detailModal" data-id="{{ $transaksi->id }}" title="Lihat Detail Transaksi">
                                        <i class="bi bi-info-circle"></i>
                                    </button>
                                    {{-- Tombol Edit hanya untuk Manajer dan Admin --}}
                                    @if(in_array(strtolower(auth()->user()->job ?? ''), ['manajer', 'admin']))
                                    <a href="{{ route('transaksi.pembeli.edit', $transaksi->id) }}" class="btn btn-sm btn-outline-warning action-btn ms-2" title="Edit Transaksi">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            {{-- Updated colspan based on new column count (11 columns) --}}
                            <td colspan="11" class="text-center text-muted py-5 empty-state">
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
                <div class="row g-3 mb-4 border-bottom pb-3">
                    <div class="col-md-6">
                        <p class="mb-1 text-muted small"><i class="bi bi-qr-code me-2"></i>Kode Transaksi:</p>
                        <p class="mb-0 fw-bold fs-6" id="detail-kode"></p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1 text-muted small"><i class="bi bi-calendar-event me-2"></i>Tanggal Transaksi:</p>
                        <p class="mb-0 fw-bold fs-6" id="detail-tanggal"></p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1 text-muted small"><i class="bi bi-clock me-2"></i>Jam Transaksi:</p>
                        <p class="mb-0 fw-bold fs-6" id="detail-jam"></p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1 text-muted small"><i class="bi bi-cash-stack me-2"></i>Metode Pembayaran:</p>
                        <p class="mb-0 fw-bold fs-6" id="detail-metode"></p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1 text-muted small"><i class="bi bi-percent me-2"></i>Diskon (%):</p>
                        <p class="mb-0 fw-bold fs-6" id="detail-diskon"></p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1 text-muted small"><i class="bi bi-tag-fill me-2"></i>Total Harga:</p>
                        <p class="mb-0 fw-bold fs-5 text-primary" id="detail-total"></p>
                    </div>
                    <div class="col-md-12">
                        <p class="mb-1 text-muted small"><i class="bi bi-info-circle me-2"></i>Status Pembayaran:</p>
                        <p class="mb-0 fw-bold fs-6" id="detail-status"></p>
                    </div>
                </div>

                <h6 class="mt-4 mb-3 fw-bold text-dark border-bottom pb-2"><i class="bi bi-people-fill me-2"></i>Detail Pihak Terkait:</h6>
                <div class="row g-3 mb-4 border-bottom pb-3">
                    <div class="col-md-6">
                        <p class="mb-1 text-muted small">Pembeli:</p>
                        <p class="mb-0 fw-bold" id="detail-pembeli"></p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1 text-muted small">Penjual:</p>
                        <p class="mb-0 fw-bold" id="detail-penjual"></p>
                    </div>
                </div>

                <h6 class="mt-4 mb-3 fw-bold text-dark border-bottom pb-2"><i class="bi bi-car-front-fill me-2"></i>Detail Mobil:</h6>
                <div class="row g-3 mb-4 border-bottom pb-3">
                    <div class="col-md-12">
                        <p class="mb-1 text-muted small">Informasi Mobil:</p>
                        <p class="mb-0 fw-bold" id="detail-mobil"></p>
                    </div>
                     <div class="col-md-6">
                        <p class="mb-1 text-muted small">Transmisi:</p>
                        <p class="mb-0 fw-bold" id="detail-mobil-transmisi"></p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1 text-muted small">Warna:</p>
                        <p class="mb-0 fw-bold" id="detail-mobil-warna"></p>
                    </div>
                </div>

                <h6 class="mt-4 mb-3 fw-bold text-dark border-bottom pb-2"><i class="bi bi-image-fill me-2"></i>Bukti Pembayaran:</h6>
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

                <h6 class="mt-4 mb-3 fw-bold text-dark border-bottom pb-2"><i class="bi bi-chat-left-text-fill me-2"></i>Keterangan Tambahan:</h6>
                <p id="detail-keterangan" class="text-muted small border rounded p-3 bg-light-subtle"></p>

            </div>
            <div class="modal-footer border-0 p-3 bg-light rounded-bottom-4">
                <button type="button" class="btn btn-outline-secondary px-4 rounded-pill" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Image Viewer Modal (for enlarged image) -->
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


<style>
    body { background-color: #f8f9fa; font-family: 'Poppins', sans-serif; color: #343a40; }
    .container-fluid.py-4 { padding-top: 2.5rem !important; padding-bottom: 2.5rem !important; }
    .card { border-radius: 1rem !important; overflow: hidden; box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.09) !important; }
    .card-header { background-color: #ffffff; border-bottom: 1px solid #e9ecef; }
    .custom-table { border-collapse: separate; border-spacing: 0; table-layout: fixed; width: 100%; }
    .custom-table thead th { background: linear-gradient(90deg, #495057, #6c757d); color: #fff; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; padding: 1rem 0.8rem; border-bottom: none; white-space: nowrap; }
    .custom-table thead th:first-child { border-top-left-radius: 1rem; }
    .custom-table thead th:last-child { border-top-right-radius: 1rem; }
    .custom-table tbody tr { transition: all 0.2s ease; border-bottom: 1px solid #dee2e6; }
    .custom-table tbody tr:last-child { border-bottom: none; }
    .custom-table tbody tr:hover { background-color: #f1f3f5; box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.05); transform: translateY(-1px); }
    /* Adjusted padding for longer rows */
    .custom-table tbody td {
        padding: 1.8rem 0.8rem; /* Keep existing padding */
        vertical-align: middle;
        font-size: 0.88rem;
        color: #495057;
        /* Remove white-space: nowrap to allow wrapping */
        /* Remove overflow: hidden and text-overflow: ellipsis to show full content */
        word-wrap: break-word; /* Allow long words to break and wrap */
        max-height: 100px; /* Optional: Set max height and add overflow-y: auto if desired */
        overflow-y: hidden; /* Hide vertical overflow unless max-height is active */
    }

    /* IMPORTANT: Ensure table-responsive container allows horizontal scrolling */
    #tableResponsiveContainer {
        overflow-x: auto; /* Memastikan scrollbar horizontal muncul */
    }

    /* Specific column width adjustments for Transaksi Pembeli Index */
    .custom-table th:nth-child(1), .custom-table td:nth-child(1) { width: 160px; } /* Kode Transaksi */
    .custom-table th:nth-child(2), .custom-table td:nth-child(2) { width: 120px; } /* Tanggal Transaksi */
    .custom-table th:nth-child(3), .custom-table td:nth-child(3) { width: 120px; } /* Jam Transaksi - Disesuaikan */
    .custom-table th:nth-child(4), .custom-table td:nth-child(4) { min-width: 280px; } /* Informasi Mobil - Increased min-width */
    .custom-table th:nth-child(5), .custom-table td:nth-child(5) { min-width: 180px; } /* Nama Pembeli */
    .custom-table th:nth-child(6), .custom-table td:nth-child(6) { width: 150px; } /* Metode Pembayaran */
    .custom-table th:nth-child(7), .custom-table td:nth-child(7) { width: 100px; } /* Diskon (%) */
    .custom-table th:nth-child(8), .custom-table td:nth-child(8) { width: 150px; } /* Total Harga - Diubah */
    .custom-table th:nth-child(9), .custom-table td:nth-child(9) { min-width: 280px; } /* Keterangan - Increased min-width */
    .custom-table th:nth-child(10), .custom-table td:nth-child(10) { width: 130px; } /* Status - Diubah */
    .custom-table th:nth-child(11), .custom-table td:nth-child(11) { width: 180px; } /* Aksi */


    .empty-state { background-color: #fefefe; color: #6c757d; font-style: italic; padding: 3rem !important; }
    .empty-state i { color: #adb5bd; }
    .btn-outline-secondary { border-color: #6c757d; color: #6c757d; transition: all 0.3s ease; }
    .btn-outline-secondary:hover { background-color: #6c757d; color: white; transform: translateY(-1px); box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    /* Primary Button Style (added for consistency) */
    .btn-primary {
        background: linear-gradient(45deg, #0d6efd, #0b5ed7);
        border: none;
        transition: all 0.3s ease;
        font-weight: 600;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 10px rgba(13, 110, 253, 0.2);
    }
    .btn-primary:hover {
        background: linear-gradient(45deg, #0b5ed7, #0d6efd);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    }

    /* Modal enhancements */
    .modal-header { border-bottom: none; }
    .modal-content { border: none; }
    .modal-body p.lead { font-size: 1.15rem; font-weight: 500; }
    .modal-body .alert-warning { background-color: #fff8eb; border-left: 5px solid #ffc107; color: #6a4000; align-items: center; border-radius: 0.75rem; }
    .modal-body .alert-warning i { color: #ffc107; }
    .modal-footer { background-color: #f8f9fa; border-top: 1px solid #e9ecef; padding-top: 1rem; padding-bottom: 1rem; }
    .modal-footer .btn { font-weight: 500; }

    /* Search input styling */
    #searchInput {
        max-width: 300px;
        border: 1px solid #ced4da;
        padding: 0.65rem 1rem;
    }
    #searchInput:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13,110,253,.25);
    }

    /* Filter select styling */
    .card-header .form-select {
        max-width: 200px; /* Limit width of dropdowns */
        border: 1px solid #ced4da;
        padding: 0.65rem 1rem;
        font-size: 0.9rem;
        border-radius: 2rem; /* Pill shape for selects too */
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }
    .card-header .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13,110,253,.25);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .card-header .header-right {
            width: 100%; /* Take full width */
            flex-direction: column; /* Stack filter elements vertically */
            align-items: stretch; /* Stretch items to fill width */
        }
        .card-header .header-right .form-control,
        .card-header .header-right .form-select,
        .card-header .header-right .btn {
            max-width: 100%; /* Make them full width */
            margin-bottom: 0.75rem; /* Add spacing between stacked elements */
        }
        .card-header .header-right .btn:last-child {
            margin-bottom: 0; /* No margin on the last button */
        }
        .btn-group-actions {
            flex-direction: column;
            gap: 0.25rem;
        }
        .action-btn {
            width: 100%;
        }
    }
</style>

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Include Moment.js for date formatting in modals -->
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<!-- Include Bootstrap JS (Bundled with Popper for modals/dropdowns) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Modal Logic (Detail) ---
        const detailModalElement = document.getElementById('detailModal');
        // We will store the last fetched data here to prevent re-fetching when re-showing modal
        let lastFetchedTransaksiData = null;

        if (detailModalElement) {
            detailModalElement.addEventListener('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // Tombol yang diklik

                // Cek apakah modal dipicu oleh tombol (bukan re-show otomatis)
                if (button.length > 0) {
                    var id = button.data('id'); // ID transaksi dari data-id tombol

                    // Ambil data transaksi berdasarkan ID
                    $.ajax({
                        url: '/transaksi/' + id,
                        method: 'GET',
                        success: function(data) {
                            lastFetchedTransaksiData = data; // Simpan data yang baru diambil
                            populateDetailModalData(data);

                            // Tangani klik pada gambar untuk membuka modal penampil gambar
                            $('#bukti-pembayaran-link').off('click').on('click', function(e) {
                                e.preventDefault();
                                $('#largeImageView').attr('src', data.bukti_pembayaran);

                                // Sembunyikan modal detail saat membuka modal gambar
                                const detailModalInstance = bootstrap.Modal.getInstance(detailModalElement);
                                if (detailModalInstance) {
                                    detailModalInstance.hide();
                                }

                                // Tampilkan modal gambar
                                const imageViewerModal = new bootstrap.Modal(document.getElementById('imageViewerModal'));
                                imageViewerModal.show();
                            });
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.error("AJAX Error:", textStatus, errorThrown);
                            console.error("Response Text:", jqXHR.responseText);
                            alert("Gagal mengambil data detail transaksi. Silakan cek konsol browser untuk error.");
                        }
                    });
                } else {
                    // If the modal is re-shown automatically (e.g., after the image modal is closed)
                    // and there's last fetched data, use that data to populate the modal.
                    if (lastFetchedTransaksiData) {
                        populateDetailModalData(lastFetchedTransaksiData);
                    }
                }
            });
        }

        // Function to populate detail modal data
        function populateDetailModalData(data) {
            $('#detail-kode').text(data.kode_transaksi);
            $('#detail-tanggal').text(moment(data.tanggal_transaksi).format('DD MMMMYYYY'));
            $('#detail-jam').text(moment(data.created_at).format('HH:i') + ' WIB'); // Using created_at for time, adding WIB
            $('#detail-metode').text(data.metode_pembayaran || 'N/A');
            $('#detail-diskon').text((data.diskon_persen || 0) + '%');
            $('#detail-total').text('Rp' + parseFloat(data.total_harga).toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 }));
            $('#detail-status').text(data.status_pembayaran ? data.status_pembayaran.charAt(0).toUpperCase() + data.status_pembayaran.slice(1) : 'Tidak Ada');
            $('#detail-keterangan').text(data.keterangan || '-');

            $('#detail-pembeli').text(data.pembeli ? data.pembeli.nama + (data.pembeli.email ? ' (' + data.pembeli.email + ')' : '') : 'N/A');
            $('#detail-penjual').text(data.penjual ? data.penjual.nama + (data.penjual.email ? ' (' + data.penjual.email + ')' : '') : 'N/A');

            let mobilInfoDetail = '';
            if (data.mobil) {
                if (data.mobil.tahun_pembuatan) {
                    mobilInfoDetail += data.mobil.tahun_pembuatan + ' ';
                }
                mobilInfoDetail += data.mobil.merek_mobil || 'N/A';
                if (data.mobil.tipe_mobil) {
                    mobilInfoDetail += ' ' + data.mobil.tipe_mobil;
                }
                mobilInfoDetail += ' - ' + (data.mobil.nomor_polisi ?? 'N/A');
            } else {
                mobilInfoDetail = 'Tidak Ada Mobil';
            }
            $('#detail-mobil').text(mobilInfoDetail.trim());
            $('#detail-mobil-transmisi').text(data.mobil ? (data.mobil.transmisi || 'N/A') : 'N/A');
            $('#detail-mobil-warna').text(data.mobil ? (data.mobil.warna_mobil || 'N/A') : 'N/A');

            const buktiPembayaranContainer = $('#bukti-pembayaran-container');
            const noBuktiPembayaran = $('#no-bukti-pembayaran');
            const detailBuktiPembayaranImg = $('#detail-bukti-pembayaran');

            if (data.bukti_pembayaran) {
                detailBuktiPembayaranImg.attr('src', data.bukti_pembayaran);
                buktiPembayaranContainer.removeClass('d-none');
                noBuktiPembayaran.addClass('d-none');
            } else {
                buktiPembayaranContainer.addClass('d-none');
                noBuktiPembayaran.removeClass('d-none');
                detailBuktiPembayaranImg.attr('src', '');
            }
        }

        // --- Fix for Image Modal Backdrop ---
        const imageViewerModalElement = document.getElementById('imageViewerModal');
        if (imageViewerModalElement) {
            imageViewerModalElement.addEventListener('hidden.bs.modal', function () {
                // Remove all modal backdrops that might be left behind
                const backdrops = document.querySelectorAll('.modal-backdrop');
                backdrops.forEach(backdrop => backdrop.remove());

                document.body.style.overflow = '';
                document.body.style.paddingRight = '';

                // Re-show the detail modal after the image modal is closed
                const detailModalInstance = bootstrap.Modal.getInstance(detailModalElement);
                if (detailModalInstance) {
                    detailModalInstance.show();
                }
            });
        }

        // --- Table Search and Filter Logic ---
        const searchInput = document.getElementById('searchInput');
        const startDateFilter = document.getElementById('startDateFilter');
        const endDateFilter = document.getElementById('endDateFilter');
        const metodePembayaranFilter = document.getElementById('metodePembayaranFilter');
        const statusFilter = document.getElementById('statusFilter');
        const resetFiltersBtn = document.getElementById('resetFilters');

        const transaksiPembeliTable = document.getElementById('transaksiPembeliTable');
        const tableRows = transaksiPembeliTable.querySelectorAll('tbody tr.data-row');
        const tableResponsiveContainer = document.getElementById('tableResponsiveContainer');

        const scrollToRight = () => {
            if (tableResponsiveContainer) {
                tableResponsiveContainer.scrollLeft = tableResponsiveContainer.scrollWidth;
            }
        };

        // Removed auto-scroll on load and resize, as it might be undesirable.
        // If the user wants it, they can request it again.
        // scrollToRight();
        // window.addEventListener('resize', scrollToRight);

        function applyFiltersAndSearch() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            const selectedMetodePembayaran = metodePembayaranFilter.value.toLowerCase().trim();
            const selectedStatus = statusFilter.value.toLowerCase().trim();
            const selectedStartDate = startDateFilter.value;
            const selectedEndDate = startDateFilter.value; // Corrected: should be endDateFilter.value

            let foundVisibleRows = false;

            tableRows.forEach(row => {
                const kode = row.getAttribute('data-kode');
                const tanggalTransaksiRow = row.getAttribute('data-tanggal');
                const jamTransaksiRow = row.getAttribute('data-jam');
                const metode = row.getAttribute('data-metode');
                const status = row.getAttribute('data-status');

                const rowTextContent = row.textContent.toLowerCase();
                const matchesSearch = rowTextContent.includes(searchTerm);

                const matchesMetode = selectedMetodePembayaran === '' || metode === selectedMetodePembayaran;
                const matchesStatus = selectedStatus === '' || status === selectedStatus;

                let matchesDateRange = true;
                if (selectedStartDate && tanggalTransaksiRow < selectedStartDate) {
                    matchesDateRange = false;
                }
                if (selectedEndDate && tanggalTransaksiRow > selectedEndDate) {
                    matchesDateRange = false;
                }

                if (matchesSearch && matchesMetode && matchesStatus && matchesDateRange) {
                    row.style.display = '';
                    foundVisibleRows = true;
                } else {
                    row.style.display = 'none';
                }
            });

            const emptyStateRow = transaksiPembeliTable.querySelector('.empty-state');
            if (emptyStateRow) {
                if (foundVisibleRows) {
                    emptyStateRow.style.display = 'none';
                } else {
                    emptyStateRow.style.display = '';
                }
            } else if (!foundVisibleRows && transaksiPembeliTable.querySelector('tbody')) {
                const newEmptyStateRow = document.createElement('tr');
                newEmptyStateRow.classList.add('empty-state');
                newEmptyStateRow.innerHTML = `
                    <td colspan="11" class="text-center text-muted py-5">
                        <i class="bi bi-info-circle-fill fs-3 mb-2 d-block"></i>
                        <p class="mb-1">Tidak ada transaksi pembeli ditemukan.</p>
                        <p class="mb-0">Coba kata kunci atau filter lain.</p>
                    </td>
                `;
                transaksiPembeliTable.querySelector('tbody').appendChild(newEmptyStateRow);
            }
        }

        searchInput.addEventListener('keyup', applyFiltersAndSearch);
        startDateFilter.addEventListener('change', applyFiltersAndSearch);
        endDateFilter.addEventListener('change', applyFiltersAndSearch);
        metodePembayaranFilter.addEventListener('change', applyFiltersAndSearch);
        statusFilter.addEventListener('change', applyFiltersAndSearch);

        resetFiltersBtn.addEventListener('click', function() {
            searchInput.value = '';
            startDateFilter.value = '';
            endDateFilter.value = '';
            metodePembayaranFilter.value = '';
            statusFilter.value = '';
            applyFiltersAndSearch();
        });

        applyFiltersAndSearch();
    });
</script>
@endsection
