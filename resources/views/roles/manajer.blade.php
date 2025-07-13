@extends('layouts.app')

@section('title', 'Dashboard Manajer')

@section('content')
<div class="container-fluid py-4 px-3 px-md-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-baseline gap-3">
            <h4 class="fw-bold text-dark mb-0">Dashboard Manajer</h4>
            <p class="text-muted small mb-0">Halaman utama khusus untuk Manajer.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-info border-0 shadow-sm rounded-3 mb-4 animate__animated animate__fadeInDown">
                <i class="bi bi-person-workspace me-2"></i>
                Selamat datang kembali, <strong>Manajer</strong>! Gunakan panel ini untuk **memonitor** dan **mengelola** data pengguna, mobil, dan transaksi.
            </div>
        </div>
    </div>

    {{-- Section Ringkasan Statistik --}}
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 mb-5">
        <div class="col animate__animated animate__fadeInUp animate__delay-0s">
            <div class="card card-summary shadow-lg border-0 rounded-4 h-100">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-uppercase text-muted fw-bold mb-1">Total Pengguna</p>
                        <h3 class="card-title fw-bold text-dark mb-0">{{ $totalUsers ?? 'N/A' }}</h3>
                    </div>
                    <div class="icon-wrap bg-primary-subtle text-primary">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 px-4 pb-3">
                    <a href="#user-management-section" class="text-primary fw-semibold small">Lihat & Kelola <i class="bi bi-arrow-right-short"></i></a>
                </div>
            </div>
        </div>
        <div class="col animate__animated animate__fadeInUp animate__delay-1s">
            <div class="card card-summary shadow-lg border-0 rounded-4 h-100">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-uppercase text-muted fw-bold mb-1">Total Mobil</p>
                        <h3 class="card-title fw-bold text-dark mb-0">{{ $totalMobil ?? 'N/A' }}</h3>
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
                        <p class="text-uppercase text-muted fw-bold mb-1">Transaksi Pembelian</p>
                        <h3 class="card-title fw-bold text-dark mb-0">{{ $totalTransaksiPembelian ?? 'N/A' }}</h3>
                    </div>
                    <div class="icon-wrap bg-warning-subtle text-warning">
                        <i class="bi bi-cart-fill"></i>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 px-4 pb-3">
                    <a href="{{ route('transaksi-pembelian.index') }}" class="text-warning fw-semibold small">Lihat Detail <i class="bi bi-arrow-right-short"></i></a>
                </div>
            </div>
        </div>
        <div class="col animate__animated animate__fadeInUp animate__delay-3s">
            <div class="card card-summary shadow-lg border-0 rounded-4 h-100">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-uppercase text-muted fw-bold mb-1">Transaksi Penjualan</p>
                        <h3 class="card-title fw-bold text-dark mb-0">{{ $totalTransaksiPenjualan ?? 'N/A' }}</h3>
                    </div>
                    <div class="icon-wrap bg-danger-subtle text-danger">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 px-4 pb-3">
                    <a href="{{ route('transaksi-penjualan.index') }}" class="text-danger fw-semibold small">Lihat Detail <i class="bi bi-arrow-right-short"></i></a>
                </div>
            </div>
        </div>
    </div>

    {{-- START: Bagian Tabel Pengguna --}}
    <div class="card border-0 shadow-xl rounded-4 animate__animated animate__fadeInUp mt-4" id="user-management-section">
        <div class="card-header bg-white p-4 border-bottom-0 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <div class="header-left mb-3 mb-md-0">
                <h5 class="card-title mb-0 text-dark fw-bold">Pengelolaan Pengguna Sistem</h5>
                <p class="card-text text-muted">Kelola semua informasi pengguna yang terdaftar langsung di sini.</p>
            </div>
            <div class="header-right d-flex flex-column flex-md-row align-items-md-center gap-3">
                <input type="text" id="userSearchInput" class="form-control rounded-3 shadow-sm" placeholder="Cari pengguna..." aria-label="Cari pengguna">
                <select id="userJobFilter" class="form-select rounded-3 shadow-sm">
                    <option value="">Semua Peran</option>
                    @foreach(['admin', 'manajer', 'sales'] as $job)
                        <option value="{{ $job }}">{{ ucfirst($job) }}</option>
                    @endforeach
                </select>
                <button id="resetUserFilters" class="btn btn-outline-secondary rounded-3 shadow-sm">Reset</button>
                {{-- Tombol New User sekarang akan memunculkan modal konfirmasi logout --}}
                <button type="button" class="btn btn-primary shadow-sm rounded-3 animate__animated animate__fadeInRight" data-bs-toggle="modal" data-bs-target="#confirmLogoutRegisterModal">
                    <i class="bi bi-person-plus-fill me-2"></i> New User (Logout & Register)
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            {{-- DIV ini yang membuat tabel responsif di mobile --}}
            <div class="table-responsive" id="userTableResponsiveContainer">
                <table class="table table-hover table-striped align-middle mb-0 custom-table" id="userTable">
                    <thead class="bg-gradient-primary text-white text-center align-middle">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Email</th>
                            <th scope="col">Peran</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                        <tr class="user-data-row" data-job="{{ $user->job }}">
                            <td class="text-center fw-bold">{{ $user->id }}</td>
                            <td class="text-start">{{ $user->name }}</td>
                            <td class="text-start">{{ $user->email }}</td>
                            <td class="text-center">
                                @php
                                    $jobBadgeClass = '';
                                    switch ($user->job) {
                                        case 'admin': $jobBadgeClass = 'danger'; break;
                                        case 'manajer': $jobBadgeClass = 'warning text-dark'; break;
                                        case 'sales': $jobBadgeClass = 'info'; break;
                                        default: $jobBadgeClass = 'secondary'; break;
                                    }
                                @endphp
                                <span class="badge bg-{{ $jobBadgeClass }} px-3 py-2 rounded-pill shadow-sm">
                                    {{ ucfirst($user->job) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center align-items-center btn-group-actions">
                                    <button class="btn btn-sm btn-outline-info action-btn" data-bs-toggle="modal" data-bs-target="#userDetailModal"
                                        data-id="{{ $user->id }}" data-name="{{ $user->name }}" data-email="{{ $user->email }}"
                                        data-job="{{ ucfirst($user->job) }}"
                                        data-created="{{ \Carbon\Carbon::parse($user->created_at)->format('d M Y H:i') }}"
                                        data-updated="{{ \Carbon\Carbon::parse($user->updated_at)->format('d M Y H:i') }}"
                                        title="Lihat Detail Pengguna">
                                        <i class="bi bi-info-circle"></i> <span class="d-none d-md-inline">Detail</span>
                                    </button>
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-outline-warning action-btn" title="Edit Data Pengguna">
                                        <i class="bi bi-pencil-square"></i> <span class="d-none d-md-inline">Edit</span>
                                    </a>
                                    <button class="btn btn-sm btn-outline-danger action-btn" data-bs-toggle="modal" data-bs-target="#userDeleteModal"
                                        data-id="{{ $user->id }}" data-name="{{ $user->name }}" title="Hapus Pengguna">
                                        <i class="bi bi-trash"></i> <span class="d-none d-md-inline">Hapus</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5 empty-state">
                                <i class="bi bi-info-circle-fill fs-3 mb-2 d-block"></i>
                                <p class="mb-1">Tidak ada pengguna ditemukan untuk pencarian atau filter Anda.</p>
                                <p class="mb-0">Coba kata kunci atau filter lain.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-light border-top-0 py-3 px-4 d-flex justify-content-between align-items-center rounded-bottom-4 gap-3">
            <small class="text-muted">Data pengguna diperbarui terakhir: {{ \Carbon\Carbon::now()->format('d M Y H:i') }} WIB</small>
            {{ $users->links('pagination::bootstrap-5') }}
        </div>
    </div>
    {{-- END: Bagian Tabel Pengguna --}}

    {{-- Modals for User Detail and Delete --}}
    {{-- Detail User Modal --}}
    <div class="modal fade animate__animated animate__fadeInUp animate__faster" id="userDetailModal" tabindex="-1" aria-labelledby="userDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-4 shadow-xl border-0">
                <div class="modal-header bg-gradient-primary-dark text-white border-0 rounded-top-4 p-3">
                    <h5 class="modal-title fw-bold fs-5" id="userDetailModalLabel"><i class="bi bi-person-fill me-2"></i> Detail Data Pengguna</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="section-card mb-4">
                        <h6 class="fw-bold mb-3 text-dark pb-2 border-bottom-dashed"><i class="bi bi-info-circle-fill me-2 text-primary"></i> Informasi Dasar Pengguna</h6>
                        <div class="row row-cols-1 row-cols-md-2 g-3">
                            <div class="col">
                                <div class="detail-item">
                                    <p class="detail-label"><i class="bi bi-tag me-2"></i>ID Pengguna:</p>
                                    <p class="detail-value" id="user-detail-id"></p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="detail-item">
                                    <p class="detail-label"><i class="bi bi-person-circle me-2"></i>Nama Lengkap:</p>
                                    <p class="detail-value" id="user-detail-name"></p>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="detail-item">
                                    <p class="detail-label"><i class="bi bi-envelope-fill me-2"></i>Email:</p>
                                    <p class="detail-value" id="user-detail-email"></p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="detail-item">
                                    <p class="detail-label"><i class="bi bi-person-badge-fill me-2"></i>Peran (Job):</p>
                                    <p class="detail-value" id="user-detail-job"></p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="detail-item">
                                    <p class="detail-label"><i class="bi bi-calendar-plus-fill me-2"></i>Tanggal Dibuat:</p>
                                    <p class="detail-value" id="user-detail-created"></p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="detail-item">
                                    <p class="detail-label"><i class="bi bi-calendar-check-fill me-2"></i>Terakhir Diperbarui:</p>
                                    <p class="detail-value" id="user-detail-updated"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3 bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-outline-secondary px-4 rounded-3" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete User Confirmation Modal --}}
    <div class="modal fade animate__animated animate__fadeInDown animate__faster" id="userDeleteModal" tabindex="-1" aria-labelledby="userDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-xl border-0">
                <div class="modal-header bg-danger text-white border-0 rounded-top-4 p-3">
                    <h5 class="modal-title fw-bold fs-5" id="userDeleteModalLabel"><i class="bi bi-exclamation-triangle-fill me-2"></i> Konfirmasi Hapus Pengguna</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="lead text-dark mb-3">Apakah Anda yakin ingin menghapus pengguna ini?</p>
                    <div class="alert alert-warning border-0 d-flex align-items-center gap-2 mb-3 rounded-3" role="alert">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        <div>
                            Data pengguna <strong id="user-to-delete-name"></strong> (<span id="user-to-delete-id"></span>) akan dihapus secara permanen. Tindakan ini tidak dapat dibatalkan.
                        </div>
                    </div>
                    <form id="userDeleteForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="user_id" id="delete-user-id">
                        <div class="d-flex justify-content-end gap-2 pt-3">
                            <button type="button" class="btn btn-outline-secondary px-4 rounded-3" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger px-4 rounded-3">Ya, Hapus</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- START: Konfirmasi Logout & Register Modal --}}
    <div class="modal fade animate__animated animate__fadeInUp animate__faster" id="confirmLogoutRegisterModal" tabindex="-1" aria-labelledby="confirmLogoutRegisterModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-xl border-0">
                <div class="modal-header bg-primary text-white border-0 rounded-top-4 p-3">
                    <h5 class="modal-title fw-bold fs-5" id="confirmLogoutRegisterModalLabel"><i class="bi bi-box-arrow-right me-2"></i> Konfirmasi Logout & Registrasi</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="lead text-dark mb-3">Anda akan **logout** dari akun Anda saat ini untuk membuat pengguna baru.</p>
                    <div class="alert alert-info border-0 d-flex align-items-center gap-2 mb-3 rounded-3" role="alert">
                        <i class="bi bi-info-circle-fill"></i>
                        <div>
                            **Panduan Setelah Logout:**
                            <p class="small mb-0">Setelah Anda logout, Anda akan diarahkan ke halaman login. Dari sana, Anda dapat menemukan tautan atau tombol untuk mendaftar sebagai pengguna baru.</p>
                        </div>
                    </div>
                    <p class="text-muted mb-0">Lanjutkan?</p>
                </div>
                <div class="modal-footer border-0 p-3 bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-outline-secondary px-4 rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary px-4 rounded-3" id="confirmLogoutButton">Lanjutkan Logout & Registrasi</button>
                </div>
            </div>
        </div>
    </div>
    {{-- END: Konfirmasi Logout & Register Modal --}}


    {{-- Section Tautan Cepat untuk Monitoring Data --}}
    <div class="card border-0 shadow-xl rounded-4 animate__animated animate__fadeInUp mb-4">
        <div class="card-header bg-white p-4 border-bottom-0">
            <h5 class="card-title mb-0 text-dark fw-bold">Akses Cepat Monitoring Data</h5>
            <p class="card-text text-muted">Arahkan ke modul monitoring data spesifik.</p>
        </div>
        <div class="card-body p-4 pt-0">
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                <div class="col">
                    {{-- Tautan ini sekarang bisa mengarah ke #user-management-section atau route('users.index') --}}
                    <a href="#user-management-section" class="quick-link-card d-block p-4 border rounded-3 shadow-sm text-decoration-none animate__animated animate__pulse animate__infinite" style="--animate-duration: 4s;">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-people-fill fs-2 text-primary me-3"></i>
                            <div>
                                <h6 class="mb-0 text-dark fw-bold">Monitor & Kelola Pengguna</h6>
                                <small class="text-muted">Lihat, awasi, tambah, edit, hapus data pengguna sistem.</small>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="{{ route('mobil.index') }}" class="quick-link-card d-block p-4 border rounded-3 shadow-sm text-decoration-none animate__animated animate__pulse animate__infinite" style="--animate-duration: 4s; animation-delay: 0.5s;">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-car-front-fill fs-2 text-success me-3"></i>
                            <div>
                                <h6 class="mb-0 text-dark fw-bold">Monitor Mobil</h6>
                                <small class="text-muted">Awasi daftar mobil yang tersedia.</small>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="{{ route('transaksi.index') }}" class="quick-link-card d-block p-4 border rounded-3 shadow-sm text-decoration-none animate__animated animate__pulse animate__infinite" style="--animate-duration: 4s; animation-delay: 1s;">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-receipt-cutoff fs-2 text-danger me-3"></i>
                            <div>
                                <h6 class="mb-0 text-dark fw-bold">Monitor Transaksi</h6>
                                <small class="text-muted">Awasi dan tinjau semua transaksi.</small>
                            </div>
                        </div>
                    </a>
                </div>
                 <div class="col">
                    <a href="{{ route('servis.index') }}" class="quick-link-card d-block p-4 border rounded-3 shadow-sm text-decoration-none animate__animated animate__pulse animate__infinite" style="--animate-duration: 4s; animation-delay: 1.5s;">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-tools fs-2 text-secondary me-3"></i>
                            <div>
                                <h6 class="mb-0 text-dark fw-bold">Monitor Servis</h6>
                                <small class="text-muted">Lihat dan awasi data layanan servis.</small>
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
        /* Padding and height will be set by the common rule below */
    }
    .btn-primary:hover {
        background: linear-gradient(45deg, #0b5ed7, #0d6efd);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    }
    .btn-outline-secondary {
        /* Padding and height will be set by the common rule below */
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

    /* Custom Table Styles */
    .custom-table {
        border-collapse: separate;
        border-spacing: 0;
        /* Hapus atau komentari baris ini jika masih tidak responsif */
        /* table-layout: fixed; */
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
        gap: 0.5rem; /* Mengelola jarak antar tombol secara konsisten */
    }

    /* Modal Styles */
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

    /* START: Styles to ensure consistent height and alignment for inputs, selects, and buttons */
    .card-header .header-right .form-control,
    .card-header .header-right .form-select,
    .card-header .header-right .btn {
        height: 38px; /* Fixed height for consistency */
        padding: 0.375rem 1rem; /* Adjust padding to center text vertically within 38px height */
        border-radius: 0.5rem !important; /* Force consistent rounding for all elements */
        box-sizing: border-box; /* Include padding and border in the height calculation */
        display: inline-flex; /* Use flex for vertical centering of content */
        align-items: center; /* Vertically center content */
        font-size: 0.9rem; /* Ensure consistent font size */
        white-space: nowrap; /* Prevent text from wrapping within the button */
    }

    /* Specific width adjustments for a cleaner look */
    .card-header .header-right #userSearchInput {
        max-width: 200px;
    }
    .card-header .header-right #userJobFilter {
        max-width: 150px;
    }

    /* Ensure buttons still apply their specific background/hover effects */
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
    /* END: Styles to ensure consistent height and alignment */

    /* Adjust header-right layout for small screens */
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
    }
</style>

<script>
    // Pastikan Anda menyertakan Bootstrap JS dan jQuery sebelum skrip ini
    document.addEventListener('DOMContentLoaded', function() {
        // --- Script untuk Modal Detail Pengguna ---
        const userDetailModal = document.getElementById('userDetailModal');
        if (userDetailModal) {
            userDetailModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const data = {
                    id: button.getAttribute('data-id'),
                    name: button.getAttribute('data-name'),
                    email: button.getAttribute('data-email'),
                    job: button.getAttribute('data-job'),
                    created: button.getAttribute('data-created'),
                    updated: button.getAttribute('data-updated')
                };
                document.getElementById('user-detail-id').textContent = data.id;
                document.getElementById('user-detail-name').textContent = data.name;
                document.getElementById('user-detail-email').textContent = data.email;
                document.getElementById('user-detail-job').textContent = data.job;
                document.getElementById('user-detail-created').textContent = data.created;
                document.getElementById('user-detail-updated').textContent = data.updated;
            });
        }

        // --- Script untuk Modal Konfirmasi Hapus Pengguna ---
        const userDeleteModal = document.getElementById('userDeleteModal');
        if (userDeleteModal) {
            userDeleteModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const userId = button.getAttribute('data-id');
                const userName = button.getAttribute('data-name');

                const deleteForm = document.getElementById('userDeleteForm');
                deleteForm.action = `/users/${userId}`; // Sesuaikan dengan route DELETE Anda

                document.getElementById('delete-user-id').value = userId;
                document.getElementById('user-to-delete-name').textContent = userName;
                document.getElementById('user-to-delete-id').textContent = userId;
            });
        }

        // --- Script untuk Konfirmasi Logout & Register Modal ---
        const confirmLogoutRegisterModal = document.getElementById('confirmLogoutRegisterModal');
        if (confirmLogoutRegisterModal) {
            const confirmLogoutButton = document.getElementById('confirmLogoutButton');
            confirmLogoutButton.addEventListener('click', function() {
                // Buat form logout secara dinamis atau pastikan form yang sudah ada disubmit
                const logoutForm = document.createElement('form');
                logoutForm.action = "{{ route('logout') }}"; // Sesuaikan dengan route logout Anda
                logoutForm.method = "POST";
                logoutForm.style.display = "none"; // Sembunyikan form

                // Tambahkan token CSRF
                const csrfTokenInput = document.createElement('input');
                csrfTokenInput.type = 'hidden';
                csrfTokenInput.name = '_token';
                csrfTokenInput.value = "{{ csrf_token() }}";
                logoutForm.appendChild(csrfTokenInput);

                document.body.appendChild(logoutForm);
                logoutForm.submit(); // Submit form logout
            });
        }


        // --- Fungsi Pencarian dan Filter Tabel Pengguna ---
        const userSearchInput = document.getElementById('userSearchInput');
        const userJobFilter = document.getElementById('userJobFilter');
        const resetUserFiltersBtn = document.getElementById('resetUserFilters');

        const userTable = document.getElementById('userTable');
        const userTableRows = userTable.querySelectorAll('tbody tr.user-data-row');
        const userTableResponsiveContainer = document.getElementById('userTableResponsiveContainer');

        // *** PERUBAHAN PENTING DI SINI ***
        // Hapus atau ubah fungsi scrollToRight agar tidak selalu menggulir ke paling kanan
        // Jika Anda ingin tabel selalu dimulai dari kiri (kolom pertama terlihat), gunakan:
        /*
        const scrollToLeft = () => {
            if (userTableResponsiveContainer) {
                userTableResponsiveContainer.scrollLeft = 0;
            }
        };
        scrollToLeft();
        window.addEventListener('resize', scrollToLeft);
        */
        // Atau, jika ingin membiarkan pengguna menggulir secara manual (rekomendasi):
        // HAPUS DUA BARIS INI:
        // scrollToRight();
        // window.addEventListener('resize', scrollToRight);


        function applyUserFiltersAndSearch() {
            const searchTerm = userSearchInput.value.toLowerCase().trim();
            const selectedJob = userJobFilter.value.toLowerCase().trim();

            let foundVisibleRows = false;

            userTableRows.forEach(row => {
                const job = row.getAttribute('data-job').toLowerCase();
                const textContent = row.textContent.toLowerCase();

                const matchesSearch = textContent.includes(searchTerm);
                const matchesJob = selectedJob === '' || job === selectedJob;

                if (matchesSearch && matchesJob) {
                    row.style.display = '';
                    foundVisibleRows = true;
                } else {
                    row.style.display = 'none';
                }
            });

            const emptyStateRow = userTable.querySelector('.empty-state');
            if (emptyStateRow) {
                if (foundVisibleRows) {
                    emptyStateRow.style.display = 'none';
                } else {
                    emptyStateRow.style.display = '';
                }
            } else if (!foundVisibleRows && userTable.querySelector('tbody')) {
                const newEmptyStateRow = document.createElement('tr');
                newEmptyStateRow.classList.add('empty-state');
                newEmptyStateRow.innerHTML = `
                    <td colspan="5" class="text-center text-muted py-5">
                        <i class="bi bi-info-circle-fill fs-3 mb-2 d-block"></i>
                        <p class="mb-1">Tidak ada pengguna ditemukan untuk pencarian atau filter Anda.</p>
                        <p class="mb-0">Coba kata kunci atau filter lain.</p>
                    </td>
                `;
                userTable.querySelector('tbody').appendChild(newEmptyStateRow);
            }
        }

        userSearchInput.addEventListener('keyup', applyUserFiltersAndSearch);
        userJobFilter.addEventListener('change', applyUserFiltersAndSearch);

        resetUserFiltersBtn.addEventListener('click', function() {
            userSearchInput.value = '';
            userJobFilter.value = '';
            applyUserFiltersAndSearch();
        });

        applyUserFiltersAndSearch(); // Initial application of filters
    });
</script>

{{-- Pastikan Anda sudah menyertakan Bootstrap JS dan jQuery --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
