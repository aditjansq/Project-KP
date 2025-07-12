@extends('layouts.app')

@section('title', 'Daftar Transaksi Pembelian Mobil')

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
            color: white;
            font-weight: 600;
            display: inline-block; /* Make it inline-block for better spacing */
        }
        .status-payment.lunas {
            background-color: #28a745; /* Green for Lunas */
        }
        .status-payment.belum-lunas {
            background-color: #dc3545; /* Red for Belum Lunas */
        }
        .status-payment.dp {
            background-color: #ffc107; /* Yellow for DP */
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
            <a href="{{ route('transaksi-pembelian.create') }}" class="btn btn-primary btn-lg shadow-lg rounded-pill animate__animated animate__fadeInRight">
                <i class="bi bi-plus-circle me-2"></i> Tambah Transaksi Baru
            </a>
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
                    <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Cari berdasarkan kode, mobil, atau penjual...">
                </div>
            </div>
            <div class="col-md-3">
                <label for="statusFilter" class="form-label text-muted">Filter Status Pembayaran</label>
                <select id="statusFilter" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="lunas">Lunas</option>
                    <option value="belum lunas">Belum Lunas</option>
                    <option value="dp">DP</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="tahunFilter" class="form-label text-muted">Filter Tahun Mobil</label>
                <select id="tahunFilter" class="form-select">
                    <option value="">Semua Tahun</option>
                    @php
                        $currentYear = Carbon::now()->year;
                        for ($year = $currentYear; $year >= 2018; $year--) {
                            echo "<option value='{$year}'>{$year}</option>";
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
                            <tr class="data-row"
                                data-kode-transaksi="{{ strtolower($transaksi->kode_transaksi) }}"
                                data-mobil-merek="{{ strtolower($transaksi->mobil->merek_mobil ?? '') }}"
                                data-mobil-tipe="{{ strtolower($transaksi->mobil->tipe_mobil ?? '') }}"
                                data-mobil-tahun="{{ $transaksi->mobil->tahun_pembuatan ?? '' }}"
                                data-penjual="{{ strtolower($transaksi->penjual->nama ?? '') }}"
                                data-status="{{ strtolower($transaksi->status) }}">
                                <td class="text-center">{{ $loop->iteration }}</td>
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
                                        if ($transaksi->status === 'lunas') {
                                            $statusClass = 'status-payment lunas'; // Add class for styling
                                        } elseif ($transaksi->status === 'belum lunas') {
                                            $statusClass = 'status-payment belum-lunas'; // Add class for styling
                                        } elseif ($transaksi->status === 'dp') {
                                            $statusClass = 'status-payment dp '; // Add class for styling
                                        }
                                    @endphp
                                    <span class="{{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $transaksi->status_pembayaran)) }}</span>
                                </td>
                                <td data-label="Dibuat Oleh">{{ $transaksi->user->name ?? 'Sistem' }}</td>
                                <td class="text-center" data-label="Aksi">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('transaksi-pembelian.show', $transaksi->id) }}" class="btn btn-custom-view">
                                            <i class="fas fa-eye me-1"></i> Lihat
                                        </a>
                                        <a href="{{ route('transaksi-pembelian.edit', $transaksi->id) }}" class="btn btn-custom-edit">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </a>
                                        {{-- Tombol Hapus Dihapus --}}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">Tidak ada data transaksi pembelian yang tersedia.</td>
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
        const tahunFilter = document.getElementById('tahunFilter'); // New filter for tahun
        const resetFiltersBtn = document.getElementById('resetFiltersBtn');
        const transaksiTableBody = document.querySelector('#transaksiTable tbody');
        const rows = Array.from(transaksiTableBody.querySelectorAll('tr.data-row')); // Get all data rows initially

        function applyFiltersAndSearch() {
            const searchTerm = searchInput.value.toLowerCase();
            const selectedStatus = statusFilter.value.toLowerCase();
            const selectedTahun = tahunFilter.value; // Get selected year
            let foundVisibleRows = false;

            rows.forEach(row => {
                const kodeTransaksi = row.dataset.kodeTransaksi;
                const mobilMerek = row.dataset.mobilMerek;
                const mobilTipe = row.dataset.mobilTipe;
                const mobilTahun = row.dataset.mobilTahun; // Get mobil tahun from dataset
                const penjual = row.dataset.penjual;
                const status = row.dataset.status;

                const matchesSearch = kodeTransaksi.includes(searchTerm) ||
                                      mobilMerek.includes(searchTerm) ||
                                      mobilTipe.includes(searchTerm) ||
                                      penjual.includes(searchTerm);

                const matchesStatus = selectedStatus === '' || status === selectedStatus;
                const matchesTahun = selectedTahun === '' || mobilTahun === selectedTahun; // Match by year

                if (matchesSearch && matchesStatus && matchesTahun) {
                    row.style.display = ''; // Show row
                    foundVisibleRows = true;
                } else {
                    row.style.display = 'none'; // Hide row
                }
            });

            // Handle empty state message
            let emptyStateRow = transaksiTableBody.querySelector('.empty-state-message');
            if (!foundVisibleRows) {
                if (!emptyStateRow) {
                    emptyStateRow = document.createElement('tr');
                    emptyStateRow.classList.add('empty-state-message');
                    emptyStateRow.innerHTML = `
                        <td colspan="9" class="text-center py-4 text-muted">
                            <i class="bi bi-info-circle-fill fs-3 mb-2 d-block"></i>
                            <p class="mb-1">Tidak ada data transaksi pembelian yang tersedia dengan filter ini.</p>
                            <p class="mb-0">Coba kata kunci atau filter lain.</p>
                        </td>
                    `;
                    transaksiTableBody.appendChild(emptyStateRow);
                } else {
                    emptyStateRow.style.display = ''; // Show existing empty state
                }
            } else {
                if (emptyStateRow) {
                    emptyStateRow.style.display = 'none'; // Hide empty state if results are found
                }
            }
        }

        // Attach event listeners for filtering and searching
        searchInput.addEventListener('keyup', applyFiltersAndSearch);
        statusFilter.addEventListener('change', applyFiltersAndSearch);
        tahunFilter.addEventListener('change', applyFiltersAndSearch); // Add listener for tahun filter
        resetFiltersBtn.addEventListener('click', function() {
            searchInput.value = '';
            statusFilter.value = '';
            tahunFilter.value = ''; // Reset tahun filter
            applyFiltersAndSearch();
        });

        // Initial application of filters on page load
        applyFiltersAndSearch();

        // Sorting logic
        document.querySelectorAll('th[data-sort-type]').forEach(header => {
            header.addEventListener('click', function() {
                const table = this.closest('table');
                const tbody = table.querySelector('tbody');
                const rows = Array.from(tbody.querySelectorAll('tr.data-row')); // Exclude non-data rows
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
                    'belum lunas': 1
                };

                rows.sort((rowA, rowB) => {
                    const cellA = rowA.children[columnIndex];
                    const cellB = rowB.children[columnIndex];

                    let valA = cellA.textContent.trim();
                    let valB = cellB.textContent.trim();

                    let cellAValue, cellBValue;
                    switch (sortType) {
                        case 'numeric': // For 'Harga Final'
                            cellAValue = parseFloat(valA.replace(/[^0-9,-]+/g,"").replace(",", "."));
                            cellBValue = parseFloat(valB.replace(/[^0-9,-]+/g,"").replace(",", "."));
                            break;
                        case 'date': // For 'Tanggal'
                            const parseDate = (dateString) => {
                                const parts = dateString.split(' ');
                                const day = parseInt(parts[0]);
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
                        case 'status': // For 'Status Pembayaran'
                            cellAValue = statusOrder[valA.toLowerCase()] || 0;
                            cellBValue = statusOrder[valB.toLowerCase()] || 0;
                            break;
                        case 'mobil': // For 'Mobil' column (sort by merek then tipe)
                            const mobilA = rowA.dataset.mobilMerek + ' ' + rowA.dataset.mobilTipe;
                            const mobilB = rowB.dataset.mobilMerek + ' ' + rowB.dataset.mobilTipe;
                            cellAValue = mobilA.toLowerCase();
                            cellBValue = mobilB.toLowerCase();
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
