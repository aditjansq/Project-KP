@extends('layouts.app')

@section('title', 'Detail Transaksi Pembelian Mobil - ' . $transaksiPembelian->kode_transaksi)

@section('content')
{{-- Import Carbon untuk format tanggal --}}
@php
    use Carbon\Carbon;
@endphp

<head>
    {{-- Tambahkan Google Fonts Poppins jika belum ada di layout utama --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    {{-- Animate.css for subtle animations (pastikan ini di-link) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    {{-- Font Awesome for icons (used for file types) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    {{-- Bootstrap Icons for general icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<div class="container-fluid py-4 px-3 px-md-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8 mb-3 mb-md-0">
            <h4 class="text-dark fw-bold mb-0">Detail Transaksi Pembelian Mobil</h4>
            <small class="text-secondary">Informasi lengkap mengenai transaksi pembelian mobil.</small>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('transaksi-pembelian.index') }}" class="btn btn-outline-secondary rounded-pill animate__animated animate__fadeInRight">
                <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar Transaksi
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-xl rounded-4 animate__animated animate__fadeInUp">
        <div class="card-header bg-white p-4 border-bottom-0">
            <h5 class="card-title mb-0 text-dark fw-bold">Detail Transaksi: {{ $transaksiPembelian->kode_transaksi }}</h5>
            <p class="card-text text-muted">Ringkasan informasi utama dan detail pembayaran.</p>
        </div>
        <div class="card-body p-lg-5 p-md-4 p-3">
            <div class="info-section-bordered mb-4">
                <h6 class="text-primary mb-3"><i class="bi bi-info-circle me-2"></i>Informasi Transaksi Utama</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label detail-label-new">Kode Transaksi:</label>
                        <p class="form-control-plaintext detail-value-new">{{ $transaksiPembelian->kode_transaksi }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label detail-label-new">Tanggal Transaksi:</label>
                        <p class="form-control-plaintext detail-value-new">{{ Carbon::parse($transaksiPembelian->tanggal_transaksi)->format('d M Y') }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label detail-label-new">Mobil:</label>
                        <p class="form-control-plaintext detail-value-new">{{ $transaksiPembelian->mobil->merek_mobil ?? $transaksiPembelian->mobil->nama_mobil ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label detail-label-new">Penjual:</label>
                        <p class="form-control-plaintext detail-value-new">{{ $transaksiPembelian->penjual->nama ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label detail-label-new">Harga Beli Final:</label>
                        <p class="form-control-plaintext detail-value-new">Rp {{ number_format($transaksiPembelian->harga_beli_mobil_final, 0, ',', '.') }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label detail-label-new">Status Pembayaran:</label>
                        <p class="form-control-plaintext detail-value-new">
                            <span class="badge
                                @if($transaksiPembelian->status_pembayaran == 'Lunas') bg-success
                                @elseif($transaksiPembelian->status_pembayaran == 'Sebagian Dibayar') bg-warning text-dark
                                @else bg-danger
                                @endif">
                                {{ $transaksiPembelian->status_pembayaran }}
                            </span>
                        </p>
                    </div>
                    <div class="col-12">
                        <label class="form-label detail-label-new">Dibuat Oleh:</label>
                        <p class="form-control-plaintext detail-value-new">{{ $transaksiPembelian->user->name ?? 'N/A' }}</p>
                    </div>
                    @if($transaksiPembelian->keterangan)
                        <div class="col-12">
                            <label class="form-label detail-label-new">Keterangan Transaksi:</label>
                            <p class="form-control-plaintext detail-value-new">{{ $transaksiPembelian->keterangan }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="info-section-bordered">
                <h6 class="text-primary mb-3"><i class="bi bi-cash-coin me-2"></i>Detail Pembayaran</h6>
                @if($transaksiPembelian->detailPembayaran->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 custom-table-detail">
                            <thead class="text-center align-middle">
                                <tr>
                                    <th scope="col" style="min-width: 120px;">Metode</th>
                                    <th scope="col" style="min-width: 120px;">Jumlah</th>
                                    <th scope="col" style="min-width: 120px;">Tanggal Bayar</th>
                                    <th scope="col" style="min-width: 180px;">Keterangan</th>
                                    <th scope="col" style="min-width: 100px;">Bukti</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalDibayar = 0; // Inisialisasi total pembayaran
                                @endphp
                                @foreach($transaksiPembelian->detailPembayaran as $detail)
                                    <tr>
                                        <td class="text-break" data-label="Metode">{{ $detail->metode_pembayaran }}</td>
                                        <td class="text-end" data-label="Jumlah">Rp {{ number_format($detail->jumlah_pembayaran, 0, ',', '.') }}</td>
                                        <td class="text-center" data-label="Tanggal Bayar">{{ Carbon::parse($detail->tanggal_pembayaran)->format('d M Y') }}</td>
                                        <td class="text-break" data-label="Keterangan">{{ $detail->keterangan_pembayaran_detail ?? '-' }}</td>
                                        <td class="text-center" data-label="Bukti">
                                            @if($detail->bukti_pembayaran_detail)
                                                @php
                                                    $fileExtension = pathinfo($detail->bukti_pembayaran_detail, PATHINFO_EXTENSION);
                                                    // Bersihkan path untuk memastikan tidak ada awalan 'storage/' ganda
                                                    $cleanedPathForUrl = ltrim($detail->bukti_pembayaran_detail, '/'); // Hapus slash di awal jika ada
                                                    if (Str::startsWith($cleanedPathForUrl, 'storage/')) {
                                                        $cleanedPathForUrl = Str::after($cleanedPathForUrl, 'storage/');
                                                    }
                                                @endphp
                                                <a href="{{ Storage::url($cleanedPathForUrl) }}" target="_blank"
                                                    class="btn btn-sm btn-outline-info action-btn" title="Lihat Bukti Pembayaran">
                                                    @if (in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png', 'gif']))
                                                        <i class="bi bi-image"></i>
                                                    @elseif (strtolower($fileExtension) == 'pdf')
                                                        <i class="bi bi-file-earmark-pdf"></i>
                                                    @else
                                                        <i class="bi bi-file-earmark"></i>
                                                    @endif
                                                    <span class="d-none d-md-inline">Lihat</span>
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                    @php
                                        $totalDibayar += $detail->jumlah_pembayaran; // Tambahkan ke total
                                    @endphp
                                @endforeach
                                {{-- Baris total pembayaran --}}
                                <tr class="table-info fw-bold">
                                    <td colspan="2" class="py-2 px-3 text-start">Total Pembayaran Diterima:</td>
                                    <td colspan="3" class="py-2 px-3 text-end">Rp {{ number_format($totalDibayar, 0, ',', '.') }}</td>
                                </tr>
                                <tr class="table-primary fw-bold">
                                    <td colspan="2" class="py-2 px-3 text-start">Sisa Pembayaran:</td>
                                    <td colspan="3" class="py-2 px-3 text-end">
                                        Rp {{ number_format($transaksiPembelian->harga_beli_mobil_final - $totalDibayar, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-3">Belum ada detail pembayaran untuk transaksi ini.</p>
                @endif
            </div>
        </div>
        <div class="card-footer bg-light border-top-0 py-3 px-4 d-flex justify-content-center align-items-center rounded-bottom-4">
            {{-- Tombol "Kembali ke Daftar Transaksi" telah dihapus --}}
        </div>
    </div>
</div>

<style>
    /* Google Fonts - Poppins */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

    body {
        background-color: #f0f2f5; /* Light gray background for a clean feel */
        font-family: 'Poppins', sans-serif;
        color: #343a40;
    }

    .container-fluid.py-4 {
        padding-top: 2.5rem !important;
        padding-bottom: 2.5rem !important;
    }

    /* Primary Button Style - Soft Blue Gradient */
    .btn-primary {
        background: linear-gradient(45deg, #4a90e2, #6aafff); /* Softer blue gradient */
        border: none;
        transition: all 0.3s ease;
        font-weight: 600;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 12px rgba(74, 144, 226, 0.3); /* Softer, larger shadow */
    }
    .btn-primary:hover {
        background: linear-gradient(45deg, #6aafff, #4a90e2);
        transform: translateY(-3px); /* More pronounced lift on hover */
        box-shadow: 0 6px 16px rgba(74, 144, 226, 0.4);
    }
    .btn-primary.rounded-pill {
        border-radius: 50px !important; /* Fully rounded for sleek look */
    }

    /* Card Styling - Elevated and Smooth */
    .card {
        border-radius: 1.25rem !important; /* Even more rounded corners */
        overflow: hidden;
        box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.15) !important; /* Deeper, softer shadow */
        background-color: #ffffff;
    }

    .card-header {
        background-color: #ffffff;
        border-bottom: 1px solid #e9ecef;
        padding: 1.5rem 2rem; /* More generous padding */
    }

    /* Alert Styling (Success) - Refined */
    .alert-success {
        background-color: #eafaea; /* Very light green */
        color: #218838; /* Standard success green */
        border: 1px solid #28a745;
        padding: 1rem 1.5rem;
        border-radius: 0.75rem;
        box-shadow: 0 4px 10px rgba(40, 167, 69, 0.1);
    }
    .alert-success .alert-heading {
        color: #28a745;
        font-weight: 600;
    }
    .alert-success .btn-close {
        font-size: 0.9rem;
        color: #218838; /* Make close button green */
        opacity: 0.7;
    }
    .alert-success .btn-close:hover {
        opacity: 1;
    }

    /* Table Styling - Modern & Clean (for detail table) */
    .custom-table-detail {
        width: 100%;
        border-collapse: separate; /* Use separate for rounded corners */
        border-spacing: 0;
    }

    .custom-table-detail thead th {
        background-color: #f8f9fa; /* Lighter background for header */
        color: #6c757d;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.78rem; /* Slightly larger font for header */
        padding: 1.1rem 1rem; /* More padding */
        border-bottom: 1px solid #e0e6ed; /* Softer border below header */
        white-space: nowrap;
    }

    .custom-table-detail tbody tr {
        transition: background-color 0.2s ease;
        border-bottom: 1px solid #f2f4f6; /* Very subtle border between rows */
    }

    .custom-table-detail tbody tr:last-child {
        border-bottom: none;
    }

    .custom-table-detail tbody tr:hover {
        background-color: #fcfdff; /* Even lighter hover effect */
    }

    .custom-table-detail tbody td {
        padding: 0.9rem 1rem; /* Generous padding */
        vertical-align: middle;
        font-size: 0.9rem; /* Slightly larger body font */
        color: #495057;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Action buttons group */
    .btn-group-actions {
        gap: 0.4rem; /* Smaller gap */
    }

    .action-btn {
        font-size: 0.75rem; /* Smaller font for action buttons */
        padding: 0.4rem 0.7rem;
        border-radius: 0.5rem; /* More rounded action buttons */
        font-weight: 500;
        transition: all 0.2s ease;
        white-space: nowrap;
    }
    .action-btn.btn-outline-info {
        color: #0dcaf0;
        border-color: #0dcaf0;
    }
    .action-btn.btn-outline-info:hover {
        background-color: #0dcaf0;
        color: white;
    }
    .action-btn.btn-outline-warning {
        color: #ffc107;
        border-color: #ffc107;
    }
    .action-btn.btn-outline-warning:hover {
        background-color: #ffc107;
        color: white;
    }
    .action-btn.btn-outline-danger {
        color: #dc3545;
        border-color: #dc3545;
    }
    .action-btn.btn-outline-danger:hover {
        background-color: #dc3545;
        color: white;
    }

    /* Empty state styling */
    .empty-state {
        background-color: #fdfdfd;
        color: #8c98a4; /* Softer gray for empty state text */
        font-style: normal; /* Remove italic */
        padding: 4rem !important; /* More vertical padding */
    }
    .empty-state i {
        color: #c0c8d0; /* Lighter icon color */
        margin-bottom: 0.75rem !important; /* More space below icon */
    }
    .empty-state p {
        font-size: 1.05rem; /* Slightly larger text */
    }

    /* Responsive adjustments for header filters */
    @media (max-width: 991.98px) { /* Adjust breakpoint for larger tablets/small desktops */
        .card-header .header-right {
            flex-direction: column; /* Stack all filter elements */
            align-items: stretch; /* Stretch to full width */
            gap: 1rem; /* More space between elements */
            margin-top: 1rem; /* Space from left header */
        }
        .card-header .header-right .form-control,
        .card-header .header-right .form-select,
        .card-header .header-right .btn {
            max-width: 100%; /* Full width for all */
            margin-bottom: 0; /* Remove individual margins */
        }
    }

    @media (min-width: 768px) { /* Medium and up screens */
        .header-right .search-input-desktop {
            max-width: 300px; /* Lebar maksimal yang bagus untuk input pencarian di desktop */
            width: 100%; /* Memastikan menggunakan lebar yang tersedia hingga max-width */
        }
        .header-right {
            justify-content: flex-end; /* Memastikan elemen rata kanan */
        }
    }

    /* Badge styling for status */
    .badge {
        padding: 0.5em 0.8em;
        border-radius: 0.5rem;
        font-weight: 600;
        font-size: 0.75em;
    }
    .badge.bg-success {
        background-color: #28a745 !important;
        color: white;
    }
    .badge.bg-warning {
        background-color: #ffc107 !important;
        color: #212529; /* Dark text for warning */
    }
    .badge.bg-danger {
        background-color: #dc3545 !important;
        color: white;
    }

    /* Custom styling for labels and values for minimalist look */
    .detail-label-new {
        font-weight: 500;
        color: #495057;
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
        display: block;
    }

    .detail-value-new {
        padding: 0.25rem 0;
        background-color: transparent;
        border: none;
        display: block;
        color: #212529;
        font-weight: 600;
        font-size: 1rem;
        word-break: break-word;
        margin-bottom: 1rem;
    }

    /* New style for bordered section */
    .info-section-bordered {
        border: 1px dashed #adb5bd; /* A subtle dashed border */
        border-radius: 0.75rem; /* Rounded corners */
        padding: 1.5rem; /* Internal padding */
        margin-bottom: 1.5rem; /* Space below section */
        background-color: #fcfdfe; /* Slightly off-white background */
    }

    /* Adjust margin for h6 inside this bordered section */
    .info-section-bordered h6.text-primary {
        margin-top: 0;
        margin-bottom: 1.5rem !important;
        font-size: 1.25rem; /* Slightly larger heading */
        font-weight: 600;
    }

    /* Table for payment details */
    .custom-table-detail thead th {
        background-color: #e9ecef; /* Lighter header for detail table */
        color: #495057;
    }
    .custom-table-detail tbody tr:nth-of-type(odd) {
        background-color: #fdfdfd; /* Zebra striping */
    }
    .custom-table-detail tbody tr:nth-of-type(even) {
        background-color: #ffffff;
    }

    /* Total and remaining rows styling */
    .custom-table-detail .table-info {
        background-color: #e0f7fa !important; /* Light blue for total */
        color: #007bff;
    }
    .custom-table-detail .table-primary {
        background-color: #e6f7ff !important; /* Even lighter blue for remaining */
        color: #007bff;
    }
    .custom-table-detail .table-info td,
    .custom-table-detail .table-primary td {
        border-top: 1px solid #b3e5fc; /* Stronger border for totals */
        font-size: 1rem;
    }
</style>

{{-- Pastikan jQuery dan Bootstrap JS di-load di layout utama atau sebelum script ini --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
