@extends('layouts.app')

@section('title', 'Dashboard Sales')

@section('content')
<div class="container-fluid py-4 px-3 px-md-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-baseline gap-3">
            <h4 class="fw-bold text-dark mb-0">Dashboard Sales</h4>
            <p class="text-muted small mb-0">Halaman utama khusus untuk Sales.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-info border-0 shadow-sm rounded-3 mb-4 animate__animated animate__fadeInDown">
                <i class="bi bi-person-badge-fill me-2"></i>
                Selamat datang kembali, <strong>Sales</strong>! Anda dapat **memantau transaksi penjualan dan ketersediaan mobil**
            </div>
        </div>
    </div>

    {{-- Section Ringkasan Statistik --}}
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-5">
        <div class="col animate__animated animate__fadeInUp animate__delay-1s">
            <div class="card card-summary shadow-lg border-0 rounded-4 h-100">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-uppercase text-muted fw-bold mb-1">Total Mobil Tersedia</p>
                        <h3 class="card-title fw-bold text-dark mb-0">{{ $totalMobilTersedia ?? 'N/A' }}</h3>
                    </div>
                    <div class="icon-wrap bg-success-subtle text-success">
                        <i class="bi bi-car-front-fill"></i>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 px-4 pb-3">
                    <a href="{{ route('mobil.index') }}" class="text-success fw-semibold small">Lihat Detail <i class="bi bi-arrow-right-short"></i></a>
                </div>
            </div>
        </div>
        <div class="col animate__animated animate__fadeInUp animate__delay-2s">
            <div class="card card-summary shadow-lg border-0 rounded-4 h-100">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-uppercase text-muted fw-bold mb-1">Total Mobil Terjual</p>
                        <h3 class="card-title fw-bold text-dark mb-0">{{ $totalMobilTerjual ?? 'N/A' }}</h3>
                    </div>
                    <div class="icon-wrap bg-warning-subtle text-warning">
                        <i class="bi bi-cart-fill"></i>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 px-4 pb-3">
                    <a href="{{ route('transaksi-penjualan.index') }}" class="text-warning fw-semibold small">Lihat Detail <i class="bi bi-arrow-right-short"></i></a>
                </div>
            </div>
        </div>
        <div class="col animate__animated animate__fadeInUp animate__delay-3s">
            <div class="card card-summary shadow-lg border-0 rounded-4 h-100">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-uppercase text-muted fw-bold mb-1">Total Pembelian Mobil</p>
                        <h3 class="card-title fw-bold text-dark mb-0">{{ $totalTransaksiPembelian ?? 'N/A' }}</h3>
                    </div>
                    <div class="icon-wrap bg-danger-subtle text-danger">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 px-4 pb-3">
                    <a href="{{ route('transaksi-pembelian.index') }}" class="text-danger fw-semibold small">Lihat Detail <i class="bi bi-arrow-right-short"></i></a>
                </div>
            </div>
        </div>
    </div>

    {{-- Section Chart Mobil (dipindahkan ke baris kedua, paling kiri) --}}
    <div class="row mb-5">
        <div class="col-lg-6 col-md-8 animate__animated animate__fadeInUp animate__delay-4s">
            <div class="card border-0 shadow-xl rounded-4">
                <div class="card-header bg-white p-4 border-bottom-0">
                    <h5 class="card-title mb-0 text-dark fw-bold">Status Ketersediaan Mobil</h5>
                    <p class="card-text text-muted">Distribusi mobil berdasarkan status ketersediaan.</p>
                </div>
                <div class="card-body p-4 d-flex justify-content-center">
                    <canvas id="mobilStatusChart" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Section Tautan Cepat untuk Pengelolaan Data --}}
    <div class="card border-0 shadow-xl rounded-4 animate__animated animate__fadeInUp mb-4">
        <div class="card-header bg-white p-4 border-bottom-0">
            <h5 class="card-title mb-0 text-dark fw-bold">Akses Cepat Pengelolaan Data</h5>
            <p class="card-text text-muted">Arahkan ke modul pengelolaan data spesifik.</p>
        </div>
        <div class="card-body p-4 pt-0">
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                <div class="col">
                    <a href="{{ route('mobil.index') }}" class="quick-link-card d-block p-4 border rounded-3 shadow-sm text-decoration-none animate__animated animate__pulse animate__infinite" style="--animate-duration: 4s; animation-delay: 0.5s;">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-car-front-fill fs-2 text-success me-3"></i>
                            <div>
                                <h6 class="mb-0 text-dark fw-bold">Daftar Mobil</h6>
                                <small class="text-muted">Lihat daftar mobil yang tersedia.</small>
                            </div>
                        </div>
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
    body {
        background-color: #f8f9fa;
        font-family: 'Poppins', sans-serif;
        color: #343a40;
    }

    .container-fluid.py-4 {
        padding-top: 2.5rem !important;
        padding-bottom: 2.5rem !important;
    }

    /* Card Summary */
    .card-summary {
        border-radius: 1rem !important;
        background-color: #ffffff;
        transition: all 0.3s ease;
        overflow: hidden;
    }
    .card-summary:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 2rem rgba(0,0,0,0.15) !important;
    }
    .card-summary .icon-wrap {
        font-size: 2.5rem;
        padding: 1rem;
        border-radius: 1rem;
        line-height: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 70px;
        height: 70px;
    }
    .card-summary .bg-primary-subtle { background-color: #cfe2ff !important; }
    .card-summary .text-primary { color: #0d6efd !important; }
    .card-summary .bg-success-subtle { background-color: #d1e7dd !important; }
    .card-summary .text-success { color: #198754 !important; }
    .card-summary .bg-warning-subtle { background-color: #fff3cd !important; }
    .card-summary .text-warning { color: #ffc107 !important; }
    .card-summary .bg-danger-subtle { background-color: #f8d7da !important; }
    .card-summary .text-danger { color: #dc3545 !important; }

    /* Quick Link Card */
    .quick-link-card {
        background-color: #ffffff;
        transition: all 0.2s ease;
        border: 1px solid #e9ecef;
    }
    .quick-link-card:hover {
        background-color: #f8f9fa;
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.08) !important;
        border-color: #0d6efd; /* Highlight border on hover */
    }
    .quick-link-card .text-dark {
        font-weight: 600;
    }
    .quick-link-card .text-muted {
        font-size: 0.85rem;
    }
    .quick-link-card i {
        min-width: 40px; /* Ensure icon has enough space */
    }

    /* General Card Styling */
    .card {
        border-radius: 1rem !important;
        overflow: hidden;
        box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.09) !important;
    }

    .card-header {
        background-color: #ffffff;
        border-bottom: 1px solid #e9ecef;
    }

    /* Primary Button Style */
    .btn-primary {
        background: linear-gradient(45deg, #0d6efd, #0b5ed7);
        border: none;
        transition: all 0.3s ease;
        font-weight: 600;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 10px rgba(13, 110, 253, 0.2);
        border-radius: 0.5rem;
    }
    .btn-primary:hover {
        background: linear-gradient(45deg, #0b5ed7, #0d6efd);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    }
    .btn-outline-secondary {
        border-radius: 0.5rem;
    }

    /* Alert Styling */
    .alert-info {
        background-color: #e0f2f7; /* Light blue */
        color: #0c5460; /* Darker blue */
        border-color: #bee5eb;
    }
    .alert-info i {
        color: #0d6efd; /* Primary blue icon */
    }

    /* Custom Animations (if using Animate.css) */
    .animate__animated {
        animation-duration: 0.8s;
    }
    .animate__fadeInDown {
        animation-name: fadeInDown;
    }
    .animate__fadeInUp {
        animation-name: fadeInUp;
    }
    .animate__pulse {
        animation-name: pulse;
        animation-timing-function: ease-in-out;
    }

    /* Custom Table Styles (Not directly used in this view, but kept for consistency if needed elsewhere) */
    .custom-table {
        border-collapse: separate;
        border-spacing: 0;
        table-layout: fixed;
        width: 100%;
        font-size: 0.9rem;
    }
    .custom-table thead {
        background: linear-gradient(45deg, #0d6efd, #0b5ed7);
        color: white;
    }
    .custom-table th, .custom-table td {
        padding: 0.85rem 1.25rem;
        border-bottom: 1px solid #e9ecef;
    }
    .custom-table th:first-child,
    .custom-table td:first-child {
        border-left: 1px solid #e9ecef;
    }
    .custom-table th:last-child,
    .custom-table td:last-child {
        border-right: 1px solid #e9ecef;
    }
    .custom-table tbody tr:last-child td {
        border-bottom: none;
    }
    .custom-table tbody tr:hover {
        background-color: #f2f6fc;
        transform: scale(1.005);
        transition: all 0.15s ease-in-out;
    }
    .custom-table .action-btn {
        font-size: 0.8rem;
        padding: 0.4rem 0.75rem;
        border-radius: 0.5rem;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        transition: all 0.2s ease;
    }
    .action-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .btn-group-actions {
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    /* Modal Styles (Not directly used in this view, but kept for consistency if needed elsewhere) */
    .modal-header.bg-gradient-primary-dark {
        background: linear-gradient(45deg, #0d6efd, #0b5ed7);
        border-radius: 0.9rem 0.9rem 0 0 !important;
    }
    .modal-header.bg-danger {
        background-color: #dc3545 !important;
    }
    .modal-content {
        border: none;
    }
    .detail-item {
        margin-bottom: 1rem;
    }
    .detail-label {
        font-weight: 600;
        color: #6c757d;
        margin-bottom: 0.25rem;
        display: flex;
        align-items: center;
    }
    .detail-value {
        font-weight: 500;
        color: #212529;
        font-size: 1.05rem;
        word-break: break-all;
    }
    .border-bottom-dashed {
        border-bottom: 1px dashed #dee2e6;
        padding-bottom: 0.5rem;
    }
    .empty-state {
        color: #6c757d;
        font-style: italic;
    }
    .pagination-container {
        display: flex;
        justify-content: center;
        margin-top: 1.5rem;
    }

    /* Form elements (Not directly used in this view, but kept for consistency if needed elsewhere) */
    .form-control,
    .form-select,
    .btn {
        height: 38px;
        padding: 0.375rem 1rem;
        border-radius: 0.5rem !important;
        box-sizing: border-box;
        display: inline-flex;
        align-items: center;
        font-size: 0.9rem;
        white-space: nowrap;
    }

    /* Adjust header-right layout for small screens (Not directly used in this view, but kept for consistency if needed elsewhere) */
    @media (max-width: 768px) {
        .card-header .header-right {
            width: 100%;
            flex-direction: column;
            align-items: stretch;
        }
        .card-header .header-right .form-control,
        .card-header .header-right .form-select,
        .card-header .header-right .btn {
            max-width: 100%;
            margin-bottom: 0.75rem;
        }
        .card-header .header-right .btn:last-child {
            margin-bottom: 0;
        }
    }
</style>

{{-- Pastikan Anda sudah menyertakan Bootstrap JS dan jQuery --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
{{-- Sertakan Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Data dari PHP
        const totalMobilTersedia = {{ $totalMobilTersedia ?? 0 }};
        const totalMobilTerjual = {{ $totalMobilTerjual ?? 0 }};

        // Konfigurasi Chart.js
        const ctx = document.getElementById('mobilStatusChart').getContext('2d');
        const mobilStatusChart = new Chart(ctx, {
            type: 'pie', // Tipe chart lingkaran
            data: {
                labels: ['Tersedia / Servis', 'Terjual'],
                datasets: [{
                    data: [totalMobilTersedia, totalMobilTerjual],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.8)', // Warna untuk Tersedia / Servis
                        'rgba(255, 99, 132, 0.8)'  // Warna untuk Terjual
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom', // Posisi legenda di bawah chart
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed !== null) {
                                    label += context.parsed;
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
