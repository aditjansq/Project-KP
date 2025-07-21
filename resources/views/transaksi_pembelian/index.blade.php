@extends('layouts.app')

@section('title', 'Daftar Transaksi Pembelian Mobil')

@section('content')
{{-- Import Carbon untuk format tanggal --}}
@php
    use Carbon\Carbon;
    $job = strtolower(auth()->user()->job ?? '');
@endphp

<head>
    {{-- Tambahkan Google Fonts Poppins jika belum ada di layout utama --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    {{-- Animate.css for subtle animations (pastikan ini di-link) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    {{-- Font Awesome for icons (used for file types) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    {{-- Bootstrap Icons for general icons (already there) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

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

        /* Primary Button Style - New Transaction */
        .btn-primary {
            background-color: #0d6efd; /* Bootstrap primary blue */
            border-color: #0d6efd;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #0b5ed7; /* Darker blue on hover */
            border-color: #0a58ca;
            transform: translateY(-2px); /* Slight lift effect */
            box-shadow: 0 4px 10px rgba(0, 123, 255, 0.2); /* Soft shadow */
        }
        .btn-primary:active {
            transform: translateY(0);
            box-shadow: none;
        }

        /* Card Styling */
        .card {
            border: none;
            border-radius: 1rem; /* More rounded corners */
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08); /* Stronger shadow for cards */
        }

        .card-header {
            background-color: #ffffff;
            border-bottom: 1px solid #e9ecef;
            padding: 1.5rem;
            border-radius: 1rem 1rem 0 0;
        }

        .card-body {
            padding: 1.5rem;
        }

        .card-footer {
            background-color: #f8f9fa;
            border-top: 1px solid #e9ecef;
            padding: 1.5rem;
            border-radius: 0 0 1rem 1rem;
        }

        /* Table Styling */
        .table {
            margin-bottom: 0; /* Remove default table margin */
        }

        .table thead th {
            background-color: #e9ecef; /* Light gray for table header */
            color: #495057; /* Darker text for header */
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
            padding: 1rem;
            vertical-align: middle;
            cursor: pointer; /* Indicate sortable columns */
            position: relative;
            padding-right: 25px; /* Make space for icons */
        }

        .table thead th:hover {
            background-color: #e2e6ea;
        }

        /* Always show both arrows, adjust opacity/color on sort */
        .table thead th[data-sort-type]::before,
        .table thead th[data-sort-type]::after {
            content: '';
            position: absolute;
            right: 10px;
            font-size: 0.7em;
            color: #adb5bd; /* Subtle grey */
            opacity: 0.5;
            transition: opacity 0.2s ease, color 0.2s ease;
        }

        .table thead th[data-sort-type]::before {
            content: '\25B2'; /* Up arrow */
            top: 35%; /* Adjust position */
        }

        .table thead th[data-sort-type]::after {
            content: '\25BC'; /* Down arrow */
            top: 65%; /* Adjust position */
        }

        /* Highlight active sort direction */
        .table thead th.asc::before {
            opacity: 1;
            color: #0d6efd; /* Highlight active sort */
        }

        .table thead th.desc::after {
            opacity: 1;
            color: #0d6efd; /* Highlight active sort */
        }

        /* Fade out the non-active arrow for the sorted column */
        .table thead th.asc::after,
        .table thead th.desc::before {
            opacity: 0.2;
        }

        /* Reset opacity for non-sorted columns */
        .table thead th:not(.asc):not(.desc)::before,
        .table thead th:not(.asc):not(.desc)::after {
            opacity: 0.5;
            color: #adb5bd;
        }

        .table tbody tr {
            transition: all 0.2s ease-in-out;
        }

        .table tbody tr:hover {
            background-color: #f2f4f7; /* Lighter highlight on hover */
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-top: 1px solid #e9ecef;
        }

        /* Custom Action Buttons */
        .btn-custom-edit {
            background-color: #ffc107; /* Yellow for edit */
            color: #343a40;
            border: none;
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }
        .btn-custom-edit:hover {
            background-color: #e0a800;
            transform: translateY(-1px);
            box-shadow: 0 2px 5px rgba(255, 193, 7, 0.3);
        }

        .btn-custom-delete {
            background-color: #dc3545; /* Red for delete */
            color: #fff;
            border: none;
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }
        .btn-custom-delete:hover {
            background-color: #c82333;
            transform: translateY(-1px);
            box-shadow: 0 2px 5px rgba(220, 53, 69, 0.3);
        }

        .btn-custom-view { /* New style for view button */
            background-color: #17a2b8; /* Info blue for view */
            color: #fff;
            border: none;
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }
        .btn-custom-view:hover {
            background-color: #138496;
            transform: translateY(-1px);
            box-shadow: 0 2px 5px rgba(23, 162, 184, 0.3);
        }

        /* Pagination Styling */
        .pagination .page-item .page-link {
            border-radius: 0.5rem;
            margin: 0 0.25rem;
            color: #0d6efd;
            border: 1px solid #dee2e6;
            transition: all 0.2s ease;
        }
        .pagination .page-item.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: #fff;
            box-shadow: 0 2px 5px rgba(0, 123, 255, 0.2);
        }
        .pagination .page-item .page-link:hover {
            background-color: #e9ecef;
            border-color: #dee2e6;
        }

        /* Filter and Search Bar */
        .filter-section {
            background-color: #ffffff;
            padding: 1.5rem;
            border-radius: 1rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }
        .form-control, .form-select {
            border-radius: 0.5rem;
            border-color: #ced4da;
        }
        .form-control:focus, .form-select:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
        }
        .btn-outline-secondary {
            border-radius: 0.5rem;
        }

        /* Empty State */
        .empty-state {
            color: #6c757d;
        }
        .empty-state i {
            font-size: 3rem;
            color: #adb5bd;
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

        /* Custom styling for status payment */
        .status-payment {
            padding: 0.5em 1em;
            border-radius: 0.5rem;
            font-weight: 600;
            display: inline-block; /* Make it inline-block for better spacing */
            border: 1px solid transparent; /* Default transparent border */
        }
        .status-payment.lunas {
            background-color: transparent !important;
            color: #28a745; /* Green for Lunas */
            border-color: #28a745;
        }
        .status-payment.belum-lunas {
            background-color: transparent !important;
            color: #dc3545; /* Red for Belum Lunas */
            border-color: #dc3545;
        }
        .status-payment.dp {
            background-color: transparent !important;
            color: #ffc107; /* Yellow for DP */
            border-color: #ffc107;
        }
    </style>
</head>

<div class="container-fluid py-4 px-3 px-md-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6 mb-3 mb-md-0">
            <h4 class="text-dark fw-bold mb-0">Daftar Transaksi Pembelian Mobil</h4>
            <small class="text-secondary">Kelola semua informasi transaksi pembelian mobil Anda dengan mudah.</small>
        </div>
        <div class="col-md-6 text-md-end">
            @if(in_array($job, ['admin']))
            <a href="{{ route('transaksi-pembelian.create') }}" class="btn btn-primary btn-lg shadow-lg rounded-pill animate__animated animate__fadeInRight">
                <i class="bi bi-plus-circle me-2"></i> Tambah Transaksi Baru
            </a>
            @endif
        </div>
    </div>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-3 animate__animated animate__fadeInDown" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
    @endif

    {{-- Filter and Search Section --}}
    <div class="filter-section animate__animated animate__fadeInUp">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="searchInput" class="form-label text-muted">Cari Transaksi</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                    {{-- Nilai input diambil dari parameter URL 'search' --}}
                    <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Cari berdasarkan kode, mobil, atau penjual..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <label for="statusFilter" class="form-label text-muted">Filter Status Pembayaran</label>
                {{-- Nilai select diambil dari parameter URL 'status' --}}
                <select id="statusFilter" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                    <option value="sebagian dibayar" {{ request('status') == 'sebagian dibayar' ? 'selected' : '' }}>Sebagian Dibayar</option>
                    {{-- <option value="belum dibayar" {{ request('status') == 'belum dibayar' ? 'selected' : '' }}>Belum Dibayar</option> --}}
                    {{-- Tambahkan 'dp' jika status 'dp' memang ada di database --}}
                    {{-- <option value="dp" {{ request('status') == 'dp' ? 'selected' : '' }}>DP</option> --}}
                </select>
            </div>
            <div class="col-md-3">
                <label for="tahunFilter" class="form-label text-muted">Filter Tahun Mobil</label>
                {{-- Nilai select diambil dari parameter URL 'year' --}}
                <select id="tahunFilter" class="form-select">
                    <option value="">Semua Tahun</option>
                    @php
                        $currentYear = Carbon::now()->year;
                        // Asumsi data mobil paling lama 2010. Sesuaikan jika perlu.
                        for ($year = $currentYear; $year >= 2010; $year--) {
                            $selected = (request('year') == $year) ? 'selected' : '';
                            echo "<option value='{$year}' {$selected}>{$year}</option>";
                        }
                    @endphp
                </select>
            </div>
            <div class="col-md-2 text-end">
                <button id="resetFiltersBtn" class="btn btn-outline-secondary w-100">
                    <i class="bi bi-arrow-counterclockwise me-2"></i> Reset Filter
                </button>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-xl rounded-4 animate__animated animate__fadeInUp">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-dark fw-bold">Data Transaksi</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive" id="tableResponsiveContainer">
                <table class="table table-hover table-striped align-middle mb-0 custom-table" id="transaksiTable">
                    <thead class="text-center align-middle">
                        <tr>
                            <th scope="col" style="min-width: 50px;">#</th>
                            <th scope="col" style="min-width: 150px;" data-sort-type="text">Kode Transaksi</th>
                            <th scope="col" style="min-width: 120px;" data-sort-type="date">Tanggal</th>
                            <th scope="col" style="min-width: 180px;" data-sort-type="mobil">Mobil</th>
                            <th scope="col" style="min-width: 180px;" data-sort-type="text">Penjual</th>
                            <th scope="col" style="min-width: 150px;" data-sort-type="numeric">Harga Final</th>
                            <th scope="col" style="min-width: 150px;" data-sort-type="status">Status Pembayaran</th>
                            <th scope="col" style="min-width: 150px;" data-sort-type="text">Dibuat Oleh</th>
                            <th scope="col" style="min-width: 120px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksis as $transaksi)
                            <tr class="data-row">
                                <td class="text-center">{{ $loop->iteration + ($transaksis->currentPage() - 1) * $transaksis->perPage() }}</td>
                                <td class="text-center fw-bold" data-label="Kode Transaksi">{{ $transaksi->kode_transaksi }}</td>
                                <td class="text-center" data-label="Tanggal">{{ Carbon::parse($transaksi->tanggal_transaksi)->translatedFormat('d M Y') }}</td>
                                <td data-label="Mobil">
                                    <strong>{{ $transaksi->mobil->merek_mobil ?? 'N/A' }} {{ $transaksi->mobil->tipe_mobil ?? 'N/A' }}</strong><br>
                                    <small class="text-muted">{{ $transaksi->mobil->tahun_pembuatan ?? 'N/A' }} | {{ $transaksi->mobil->nomor_polisi ?? 'N/A' }}</small>
                                </td>
                                <td data-label="Penjual">
                                    <strong>{{ $transaksi->penjual->nama ?? 'N/A' }}</strong><br>
                                    <small class="text-muted">{{ $transaksi->penjual->no_telepon ?? 'N/A' }}</small>
                                </td>
                                <td data-label="Harga Final">Rp{{ number_format($transaksi->harga_beli_mobil_final, 0, ',', '.') }}</td>
                                <td class="text-center" data-label="Status Pembayaran">
                                    @php
                                        $statusClass = '';
                                        $displayText = '';
                                        if (strtolower($transaksi->status_pembayaran) === 'lunas') {
                                            $statusClass = 'status-payment lunas';
                                            $displayText = 'Lunas';
                                        } elseif (strtolower($transaksi->status_pembayaran) === 'sebagian dibayar') {
                                            $statusClass = 'status-payment belum-lunas'; // Anda bisa membuat kelas baru jika ingin warna berbeda untuk 'sebagian dibayar'
                                            $displayText = 'Sebagian Dibayar';
                                        } elseif (strtolower($transaksi->status_pembayaran) === 'dp') {
                                            $statusClass = 'status-payment dp';
                                            $displayText = 'DP';
                                        } else {
                                            // Asumsi 'Belum Dibayar' atau status lain akan masuk sini
                                            $statusClass = 'status-payment belum-lunas'; // Default ke 'belum-lunas' jika belum ada kelas spesifik
                                            $displayText = ucfirst(str_replace('_', ' ', $transaksi->status_pembayaran));
                                        }
                                    @endphp
                                    <span class="{{ $statusClass }}">{{ $displayText }}</span>
                                </td>
                                <td data-label="Dibuat Oleh">{{ $transaksi->user->name ?? 'Sistem' }}</td>
                                <td class="text-center" data-label="Aksi">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('transaksi-pembelian.show', $transaksi->id) }}" class="btn btn-custom-view">
                                            <i class="fas fa-eye me-1"></i> Lihat
                                        </a>
                                        @if(in_array($job, ['admin']))
                                        <a href="{{ route('transaksi-pembelian.edit', $transaksi->id) }}" class="btn btn-custom-edit">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </a>
                                        {{-- Jika Anda ingin menambahkan delete button, tambahkan di sini --}}
                                        {{-- <form action="{{ route('transaksi-pembelian.destroy', $transaksi->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-custom-delete">
                                                <i class="fas fa-trash-alt me-1"></i> Hapus
                                            </button>
                                        </form> --}}
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted empty-state-message">
                                    <i class="bi bi-info-circle-fill fs-3 mb-2 d-block"></i>
                                    <p class="mb-1">Tidak ada data transaksi pembelian yang tersedia dengan filter ini.</p>
                                    <p class="mb-0">Coba kata kunci atau filter lain.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-light border-0 py-3 px-4 d-flex flex-column flex-md-row justify-content-between align-items-center rounded-bottom-4 gap-3">
            <small class="text-muted">Data diperbarui terakhir: {{ \Carbon\Carbon::now()->format('d M Y H:i') }} WIB</small>
            {{-- Pagination Links (Pastikan $transaksis adalah instance Paginator) --}}
            {{ optional($transaksis)->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
{{-- Bootstrap Bundle with Popper --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const tahunFilter = document.getElementById('tahunFilter');
        const resetFiltersBtn = document.getElementById('resetFiltersBtn');

        // Fungsi untuk mengupdate URL berdasarkan filter dan melakukan redirect
        function updateUrlAndRedirect() {
            const currentUrl = new URL(window.location.href);
            const params = currentUrl.searchParams;

            // Hapus semua parameter filter yang mungkin ada sebelumnya
            params.delete('search');
            params.delete('status');
            params.delete('year');
            params.delete('page'); // Hapus parameter halaman saat filter diubah agar kembali ke halaman 1

            // Tambahkan parameter baru jika ada nilainya
            if (searchInput.value) {
                params.set('search', searchInput.value);
            }
            if (statusFilter.value) {
                params.set('status', statusFilter.value);
            }
            if (tahunFilter.value) {
                params.set('year', tahunFilter.value);
            }

            // Arahkan browser ke URL baru
            window.location.href = currentUrl.toString();
        }

        // Event listeners
        // Gunakan event 'change' untuk dropdown dan 'keyup' untuk search input
        // Untuk search input, Anda bisa mempertimbangkan 'input' atau 'change' tergantung preferensi
        searchInput.addEventListener('change', updateUrlAndRedirect); // Ganti keyup ke change untuk performa lebih baik (tidak setiap ketikan)
        statusFilter.addEventListener('change', updateUrlAndRedirect);
        tahunFilter.addEventListener('change', updateUrlAndRedirect);

        resetFiltersBtn.addEventListener('click', function() {
            searchInput.value = '';
            statusFilter.value = '';
            tahunFilter.value = '';
            updateUrlAndRedirect(); // Panggil fungsi untuk mereset URL
        });

        // Sorting logic (tetap di sisi klien jika Anda tidak ingin server-side sorting untuk ini)
        // Saya mempertahankan logika sorting yang sudah ada di sini, tapi ingat bahwa ini hanya menyortir data yang ada di halaman saat ini.
        // Untuk data yang banyak, sorting di sisi server (dengan parameter URL juga) akan lebih baik.
        document.querySelectorAll('th[data-sort-type]').forEach(header => {
            header.addEventListener('click', function() {
                const table = this.closest('table');
                const tbody = table.querySelector('tbody');
                // Hanya ambil baris yang visible (tidak didisplay:none oleh JS sebelumnya jika ada)
                const rows = Array.from(tbody.querySelectorAll('tr.data-row:not(.empty-state-message)'));
                const columnIndex = Array.from(this.parentNode.children).indexOf(this);
                const sortType = this.dataset.sortType;
                const currentDirection = this.dataset.sortDirection || 'asc';
                const newDirection = currentDirection === 'asc' ? 'desc' : 'asc';

                // Remove sort direction classes and attributes from all headers
                document.querySelectorAll('th[data-sort-type]').forEach(th => {
                    th.classList.remove('asc', 'desc');
                    th.removeAttribute('data-sort-direction');
                });

                // Add new sort direction class and attribute to the clicked header
                this.classList.add(newDirection);
                this.dataset.sortDirection = newDirection;

                const statusOrder = {
                    'lunas': 3,
                    'dp': 2,
                    'sebagian dibayar': 1,
                    'belum dibayar': 0 // Tambahkan 'belum dibayar'
                };

                rows.sort((rowA, rowB) => {
                    const cellA = rowA.children[columnIndex];
                    const cellB = rowB.children[columnIndex];

                    let valA = cellA.textContent.trim();
                    let valB = cellB.textContent.trim();

                    let cellAValue, cellBValue;
                    switch (sortType) {
                        case 'numeric':
                            // Handle 'Rp' prefix and thousands separator
                            cellAValue = parseFloat(valA.replace(/Rp|\./g, "").replace(",", "."));
                            cellBValue = parseFloat(valB.replace(/Rp|\./g, "").replace(",", "."));
                            break;
                        case 'date':
                            const parseDate = (dateString) => {
                                const parts = dateString.split(' ');
                                const day = parseInt(parts[0]);
                                // Pastikan nama bulan sesuai dengan format Carbon::translatedFormat('d M Y')
                                const monthNames = {
                                    'Jan': 0, 'Feb': 1, 'Mar': 2, 'Apr': 3, 'Mei': 4, 'Jun': 5,
                                    'Jul': 6, 'Agu': 7, 'Sep': 8, 'Okt': 9, 'Nov': 10, 'Des': 11
                                };
                                const month = monthNames[parts[1]];
                                const year = parseInt(parts[2]);
                                return new Date(year, month, day);
                            };
                            cellAValue = parseDate(valA);
                            cellBValue = parseDate(valB);
                            break;
                        case 'status':
                             // Dapatkan teks status dari kolom dan konversi ke lowercase untuk perbandingan
                             const statusTextA = cellA.querySelector('.status-payment') ? cellA.querySelector('.status-payment').textContent.trim().toLowerCase() : '';
                             const statusTextB = cellB.querySelector('.status-payment') ? cellB.querySelector('.status-payment').textContent.trim().toLowerCase() : '';
                             cellAValue = statusOrder[statusTextA] || 0;
                             cellBValue = statusOrder[statusTextB] || 0;
                            break;
                        case 'mobil':
                            // Gunakan data-label untuk mobil karena data- attributes tidak lagi ada di <tr>
                            const mobilDataA = rowA.querySelector('td[data-label="Mobil"] strong').textContent.trim() + ' ' + rowA.querySelector('td[data-label="Mobil"] small').textContent.trim();
                            const mobilDataB = rowB.querySelector('td[data-label="Mobil"] strong').textContent.trim() + ' ' + rowB.querySelector('td[data-label="Mobil"] small').textContent.trim();
                            cellAValue = mobilDataA.toLowerCase();
                            cellBValue = mobilDataB.toLowerCase();
                            break;
                        default:
                            cellAValue = valA.toLowerCase();
                            cellBValue = valB.toLowerCase();
                            break;
                    }

                    let comparison = 0;
                    if (cellAValue > cellBValue) {
                        comparison = 1;
                    } else if (cellAValue < cellBValue) {
                        comparison = -1;
                    }

                    return newDirection === 'asc' ? comparison : -comparison;
                });

                rows.forEach(row => tbody.appendChild(row));
                // Pastikan pesan "Tidak ada data" dihapus jika sorting mengembalikan data
                const emptyStateRow = tbody.querySelector('.empty-state-message');
                if (emptyStateRow && rows.length > 0) {
                    emptyStateRow.remove();
                }
            });
        });

        // Scroll to right if table is wider than container
        const scrollToRight = () => {
            const tableResponsiveContainer = document.getElementById('tableResponsiveContainer');
            if (tableResponsiveContainer) {
                if (tableResponsiveContainer.scrollWidth > tableResponsiveContainer.clientWidth) {
                    tableResponsiveContainer.scrollLeft = tableResponsiveContainer.scrollWidth;
                }
            }
        };

        scrollToRight();
        window.addEventListener('resize', scrollToRight);
    });
</script>
@endsection
