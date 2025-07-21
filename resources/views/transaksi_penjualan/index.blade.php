@extends('layouts.app')

@section('title', 'Daftar Transaksi Penjualan')

@php
    $job = strtolower(auth()->user()->job ?? '');
@endphp

@section('content')
<head>
    {{-- Google Fonts Poppins (opsional, bisa dipertahankan jika diinginkan) --}}
    <link href="https://fonts.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    {{-- Animate.css (opsional, bisa dipertahankan untuk animasi) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    {{-- Font Awesome untuk ikon (pastikan sudah dimuat di layouts.app juga) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    {{-- Bootstrap CSS (sudah ada di layouts.app, jadi ini bisa dihapus jika sudah dimuat di sana) --}}
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-QWTKZyjpPEjGFoRxTtzg2RlQngQ1L+xWj7s1Q2uOQW+LpM3M4tWd2bL9R+N" crossorigin="anonymous"> --}}
    <style>
        /* Google Fonts - Poppins */
        @import url('https://fonts.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

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

        /* NEW/MODIFIED: Styling for Status Badges (outline style) */
        .status-badge, .metode-pembayaran-badge {
            padding: 0.5em 1em;
            border-radius: 0.5rem;
            font-weight: 600;
            display: inline-block;
            border: 1px solid transparent; /* Default transparent border */
            text-transform: capitalize; /* Ensure first letter is capitalized */
        }

        .status-badge.status-lunas {
            background-color: transparent !important;
            color: #28a745; /* Green */
            border-color: #28a745;
        }
        .status-badge.status-belum-lunas {
            background-color: transparent !important;
            color: #dc3545; /* Red */
            border-color: #dc3545;
        }
        .status-badge.status-dp {
            background-color: transparent !important;
            color: #ffc107; /* Yellow */
            border-color: #ffc107;
        }

        /* NEW: Styling for Metode Pembayaran Badges (outline style) */
        .metode-pembayaran-badge.metode-pembayaran-non-kredit {
            background-color: transparent !important;
            color: #17a2b8; /* Info Blue */
            border-color: #17a2b8;
        }
        .metode-pembayaran-badge.metode-pembayaran-kredit {
            background-color: transparent !important;
            color: #6f42c1; /* Purple (contoh, bisa disesuaikan) */
            border-color: #6f42c1;
        }
    </style>
</head>

<div class="container-fluid py-4 px-3 px-md-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8 mb-3 mb-md-0">
            <h4 class="text-dark fw-bold mb-0">Daftar Transaksi Penjualan</h4>
            <small class="text-secondary">Kelola semua informasi transaksi penjualan Anda dengan mudah.</small>
        </div>
        <div class="col-md-4 text-md-end">
            @if(in_array($job, ['admin']))
            <a href="{{ route('transaksi-penjualan.create') }}" class="btn btn-primary btn-lg shadow-lg rounded-pill animate__animated animate__fadeInRight">
                <i class="bi bi-plus-circle-fill me-2"></i> Tambah Transaksi Baru
            </a>
            @endif
        </div>
    </div>

    {{-- Filter and Search Section --}}
    <div class="filter-section animate__animated animate__fadeInUp">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="searchInput" class="form-label text-muted">Cari Transaksi</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                    {{-- Tambahkan value dari $search --}}
                    <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Cari berdasarkan kode, mobil, atau pembeli..." value="{{ $search ?? '' }}">
                </div>
            </div>
            <div class="col-md-3">
                <label for="statusFilter" class="form-label text-muted">Filter Status</label>
                {{-- Tambahkan selected dari $statusFilter --}}
                <select id="statusFilter" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="lunas" {{ ($statusFilter ?? '') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                    <option value="belum lunas" {{ ($statusFilter ?? '') == 'belum lunas' ? 'selected' : '' }}>Belum Lunas</option>
                    <option value="dp" {{ ($statusFilter ?? '') == 'dp' ? 'selected' : '' }}>DP</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="metodePembayaranFilter" class="form-label text-muted">Filter Metode Pembayaran</label>
                {{-- Tambahkan selected dari $metodePembayaranFilter --}}
                <select id="metodePembayaranFilter" class="form-select">
                    <option value="">Semua Metode</option>
                    <option value="non_kredit" {{ ($metodePembayaranFilter ?? '') == 'non_kredit' ? 'selected' : '' }}>Non-Kredit</option>
                    <option value="kredit" {{ ($metodePembayaranFilter ?? '') == 'kredit' ? 'selected' : '' }}>Kredit</option>
                </select>
            </div>
            <div class="col-md-2 text-end">
                <button id="applyFiltersBtn" class="btn btn-primary w-100 mb-2" style="display: none;">
                    <i class="bi bi-funnel-fill me-2"></i> Terapkan Filter
                </button>
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
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center">#</th>
                            <th scope="col" data-sort-type="text">Kode Transaksi</th>
                            <th scope="col" data-sort-type="mobil">Mobil</th>
                            <th scope="col" data-sort-type="text">Pembeli</th>
                            <th scope="col" data-sort-type="text">Metode Pembayaran</th>
                            <th scope="col" data-sort-type="numeric">Harga Negosiasi</th>
                            <th scope="col" data-sort-type="date">Tanggal Transaksi</th>
                            <th scope="col" data-sort-type="status">Status</th>
                            <th scope="col" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transaksis as $transaksi)
                            {{-- Data attributes ini tidak lagi digunakan untuk filtering JS, tapi bisa tetap ada untuk debugging atau keperluan lain --}}
                            <tr data-kode-transaksi="{{ strtolower($transaksi->kode_transaksi) }}"
                                data-mobil="{{ strtolower($transaksi->mobil->merek_mobil ?? '') }} {{ strtolower($transaksi->mobil->tipe_mobil ?? '') }} {{ strtolower($transaksi->mobil->tahun_pembuatan ?? '') }} {{ strtolower($transaksi->mobil->nomor_polisi ?? '') }}"
                                data-pembeli="{{ strtolower($transaksi->pembeli->nama ?? '') }}"
                                data-pembeli-telepon="{{ strtolower($transaksi->pembeli->no_telepon ?? '') }}"
                                data-status="{{ strtolower($transaksi->status) }}"
                                data-metode-pembayaran="{{ strtolower($transaksi->metode_pembayaran) }}">
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $transaksi->kode_transaksi }}</td>
                                <td>
                                    <strong>{{ $transaksi->mobil->merek_mobil ?? 'N/A' }} {{ $transaksi->mobil->tipe_mobil ?? 'N/A' }}</strong><br>
                                    <small class="text-muted">{{ $transaksi->mobil->tahun_pembuatan ?? 'N/A' }} | {{ $transaksi->mobil->nomor_polisi ?? 'N/A' }}</small>
                                </td>
                                <td>
                                    <strong>{{ $transaksi->pembeli->nama ?? 'N/A' }}</strong><br>
                                    <small class="text-muted">{{ $transaksi->pembeli->no_telepon ?? 'N/A' }}</small>
                                </td>
                                <td>
                                    @php
                                        $metodeClass = '';
                                        if (strtolower($transaksi->metode_pembayaran) === 'non_kredit') {
                                            $metodeClass = 'metode-pembayaran-non-kredit';
                                        } elseif (strtolower($transaksi->metode_pembayaran) === 'kredit') {
                                            $metodeClass = 'metode-pembayaran-kredit';
                                        }
                                    @endphp
                                    <span class="metode-pembayaran-badge {{ $metodeClass }}">{{ ucfirst(str_replace('_', ' ', $transaksi->metode_pembayaran)) }}</span>
                                </td>
                                <td>Rp{{ number_format($transaksi->harga_negosiasi, 0, ',', '.') }}</td>
                                <td>{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->translatedFormat('d M Y') }}</td>
                                <td>
                                    @php

                                        $statusClass = '';
                                        if (strtolower($transaksi->status) === 'lunas') {
                                            $statusClass = 'status-lunas';
                                        } elseif (strtolower($transaksi->status) === 'belum lunas') {
                                            $statusClass = 'status-belum-lunas';
                                        } elseif (strtolower($transaksi->status) === 'dp') {
                                            $statusClass = 'status-dp';
                                        }
                                    @endphp
                                    <span class="status-badge {{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $transaksi->status)) }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('transaksi-penjualan.show', $transaksi->id) }}" class="btn btn-custom-view">
                                            <i class="fas fa-eye me-1"></i> Lihat
                                        </a>
                                        @if(in_array($job, ['admin']))
                                        <a href="{{ route('transaksi-penjualan.edit', $transaksi->id) }}" class="btn btn-custom-edit">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </a>
                                        @endif
                                        {{-- Tombol Hapus Dihapus Sesuai Permintaan --}}
                                        {{-- <form action="{{ route('transaksi-penjualan.destroy', $transaksi->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?');" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-custom-delete">
                                                <i class="fas fa-trash me-1"></i> Hapus
                                            </button>
                                        </form> --}}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted empty-state"> {{-- Tambahkan class empty-state --}}
                                    <i class="bi bi-info-circle-fill fs-3 mb-2 d-block"></i>
                                    <p class="mb-1">Tidak ada hasil ditemukan untuk pencarian atau filter Anda.</p>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const metodePembayaranFilter = document.getElementById('metodePembayaranFilter');
        const applyFiltersBtn = document.getElementById('applyFiltersBtn');
        const resetFiltersBtn = document.getElementById('resetFiltersBtn');

        // Fungsi untuk menerapkan filter dengan memperbarui URL
        function applyFiltersToUrl() {
            const newUrl = new URL(window.location.href);
            newUrl.searchParams.delete('page'); // Reset page saat menerapkan filter baru

            if (searchInput.value) {
                newUrl.searchParams.set('search', searchInput.value);
            } else {
                newUrl.searchParams.delete('search');
            }

            if (statusFilter.value) {
                newUrl.searchParams.set('status', statusFilter.value);
            } else {
                newUrl.searchParams.delete('status');
            }

            if (metodePembayaranFilter.value) {
                newUrl.searchParams.set('metode_pembayaran', metodePembayaranFilter.value);
            } else {
                newUrl.searchParams.delete('metode_pembayaran');
            }

            window.location.href = newUrl.toString(); // Redirect ke URL baru
        }

        // Sembunyikan tombol "Terapkan Filter" karena filter akan diterapkan otomatis
        applyFiltersBtn.style.display = 'none';

        // Event listener untuk perubahan pada dropdown status
        statusFilter.addEventListener('change', applyFiltersToUrl);

        // Event listener untuk perubahan pada dropdown metode pembayaran
        metodePembayaranFilter.addEventListener('change', applyFiltersToUrl);

        // Event listener untuk input pencarian (saat menekan Enter)
        searchInput.addEventListener('keypress', function(event) {
            if (event.key === 'Enter') {
                applyFiltersToUrl();
            }
        });

        // Event listener untuk tombol "Reset Filter"
        resetFiltersBtn.addEventListener('click', function() {
            searchInput.value = '';
            statusFilter.value = '';
            metodePembayaranFilter.value = '';
            applyFiltersToUrl(); // Terapkan filter kosong untuk mereset
        });

        // --- Sorting Logic (tetap di sisi klien karena data sudah ada di halaman) ---
        document.querySelectorAll('th[data-sort-type]').forEach(header => {
            header.addEventListener('click', function() {
                const table = this.closest('table');
                const tbody = table.querySelector('tbody');
                // Hanya ambil baris yang terlihat (tidak disembunyikan oleh filter server-side)
                const rows = Array.from(tbody.querySelectorAll('tr:not(.empty-state)'));
                const columnIndex = Array.from(this.parentNode.children).indexOf(this);
                const sortType = this.dataset.sortType;
                const currentDirection = this.dataset.sortDirection || 'asc';
                const newDirection = currentDirection === 'asc' ? 'desc' : 'asc';

                // Remove sort direction classes and attributes from all headers
                document.querySelectorAll('th[data-sort-type]').forEach(th => {
                    th.classList.remove('asc', 'desc');
                    th.removeAttribute('data-sort-direction'); // Clear data-sort-direction
                });

                // Add new sort direction class and attribute to the clicked header
                this.classList.add(newDirection);
                this.dataset.sortDirection = newDirection;

                const statusOrder = {
                    'lunas': 3,
                    'dp': 2,
                    'belum lunas': 1
                };

                rows.sort((rowA, rowB) => {
                    const cellA = rowA.children[columnIndex];
                    const cellB = rowB.children[columnIndex];

                    let valA = cellA.textContent.trim();
                    let valB = cellB.textContent.trim();

                    let cellAValue, cellBValue;
                    switch (sortType) {
                        case 'numeric': // For 'Harga Negosiasi'
                            cellAValue = parseFloat(valA.replace(/[^0-9,-]+/g,"").replace(",", "."));
                            cellBValue = parseFloat(valB.replace(/[^0-9,-]+/g,"").replace(",", "."));
                            break;
                        case 'date': // For 'Tanggal'
                            const parseDate = (dateString) => {
                                const parts = dateString.split(' ');
                                const monthNames = {
                                    'Jan': 0, 'Feb': 1, 'Mar': 2, 'Apr': 3, 'Mei': 4, 'Jun': 5,
                                    'Jul': 6, 'Agu': 7, 'Sep': 8, 'Okt': 9, 'Nov': 10, 'Des': 11
                                };
                                const day = parseInt(parts[0]);
                                const month = monthNames[parts[1]];
                                const year = parseInt(parts[2]);
                                return new Date(year, month, day);
                            };
                            cellAValue = parseDate(valA);
                            cellBValue = parseDate(valB);
                            break;
                        case 'status': // For 'Status'
                            cellAValue = statusOrder[valA.toLowerCase()] || 0;
                            cellBValue = statusOrder[valB.toLowerCase()] || 0;
                            break;
                        case 'mobil': // For 'Mobil' column, sort by the first line (brand/type/year)
                            cellAValue = rowA.dataset.mobil.toLowerCase();
                            cellBValue = rowB.dataset.mobil.toLowerCase();
                            break;
                        default: // 'text' type or others
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

                // Re-append sorted rows to the table body
                rows.forEach(row => tbody.appendChild(row));
            });
        });
    });
</script>
@endsection
