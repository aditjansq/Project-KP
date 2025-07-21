@extends('layouts.app')

@section('title', 'Daftar Data Mobil')

@section('content')
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

        /* Primary Button Style - New Car */
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
        /* New badge styles for 'baru' and 'bekas' */
        .badge.bg-primary-dark { /* Example for 'baru' */
            background-color: #007bff !important; /* Bootstrap primary */
            color: white;
        }
        .badge.bg-secondary-light { /* Example for 'bekas' */
            background-color: #6c757d !important; /* Bootstrap secondary */
            color: white;
        }

        /* Ketersediaan Badges */
        .badge.ketersediaan-ada {
            background-color: #28a745 !important; /* Hijau */
            color: white;
        }
        .badge.ketersediaan-terjual {
            background-color: #17a2b8 !important; /* Biru muda/cyan */
            color: white;
        }
    </style>
</head>

<div class="container-fluid py-4 px-3 px-md-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6 mb-3 mb-md-0">
            <h4 class="text-dark fw-bold mb-0">Daftar Data Mobil</h4>
            <small class="text-secondary">Kelola semua informasi mobil Anda dengan mudah.</small>
        </div>
        <div class="col-md-6 text-md-end">
            @php
                // Pastikan variabel $job tersedia atau ambil langsung dari session
                $job = strtolower(auth()->user()->job ?? '');
            @endphp

            {{-- Tombol "Tambah Mobil Baru" hanya ditampilkan untuk admin --}}
            @if(in_array($job, ['admin']))
            <a href="{{ route('mobil.create') }}" class="btn btn-primary btn-lg shadow-lg rounded-pill animate__animated animate__fadeInRight">
                <i class="bi bi-plus-circle me-2"></i> Tambah Mobil Baru
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

    {{-- Filter and Search Section (Diubah menjadi Form) --}}
    <div class="filter-section animate__animated animate__fadeInUp">
        {{-- Form ini akan menangani semua filter dan pencarian --}}
        <form action="{{ route('mobil.index') }}" method="GET" id="filterForm">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="searchInput" class="form-label text-muted">Cari Mobil</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                        {{-- Tambahkan name="search" dan value dari request --}}
                        <input type="text" name="search" id="searchInput" class="form-control border-start-0"
                               placeholder="Cari berdasarkan merek, model, nopol..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2"> {{-- Changed from col-md-3 to col-md-2 --}}
                    <label for="merekMobilFilter" class="form-label text-muted">Merek</label>
                    {{-- Tambahkan name="merekMobilFilter" dan value dari request --}}
                    <select name="merekMobilFilter" id="merekMobilFilter" class="form-select">
                        <option value="">Semua Merek</option>
                        @php
                            $uniqueMerek = $mobils->pluck('merek_mobil')->unique()->sort();
                        @endphp
                        @foreach ($uniqueMerek as $merek)
                            <option value="{{ $merek }}" {{ request('merekMobilFilter') == $merek ? 'selected' : '' }}>{{ $merek }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- Ketersediaan filter utama --}}
                <div class="col-md-2"> {{-- Changed from col-md-3 to col-md-2 --}}
                    <label for="ketersediaanMobilFilter" class="form-label text-muted">Ketersediaan</label>
                    <select name="ketersediaanFilter" id="ketersediaanMobilFilter" class="form-select">
                        <option value="">Semua Ketersediaan</option>
                        <option value="ada" {{ request('ketersediaanFilter') == 'ada' ? 'selected' : '' }}>Ada</option>
                        <option value="terjual" {{ request('ketersediaanFilter') == 'terjual' ? 'selected' : '' }}>Terjual</option>
                    </select>
                </div>
                <div class="col-md-2 text-end"> {{-- Added a new column for Reset button --}}
                    <label class="form-label text-muted">&nbsp;</label> {{-- Placeholder to align with other labels --}}
                    <button type="button" id="resetMainFiltersBtn" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-arrow-counterclockwise me-2"></i> Reset
                    </button>
                </div>
                <div class="col-md-2 text-end">
                    <label class="form-label text-muted">&nbsp;</label> {{-- Placeholder to align with other labels --}}
                    <button type="button" class="btn btn-outline-secondary w-100" data-bs-toggle="modal" data-bs-target="#moreFiltersModal">
                        <i class="bi bi-funnel me-2"></i> Filter Lainnya
                    </button>
                </div>
            </div>
            {{-- Tambahkan input hidden untuk filter modal agar nilai mereka ikut terkirim saat form utama disubmit --}}
            <input type="hidden" name="transmisiFilter" id="hiddenTransmisiFilter" value="{{ request('transmisiFilter') }}">
            <input type="hidden" name="ketersediaanFilter" id="hiddenKetersediaanFilter" value="{{ request('ketersediaanFilter') }}">
            <input type="hidden" name="jenisMobilFilter" id="hiddenJenisMobilFilter" value="{{ request('jenisMobilFilter') }}">
            <input type="hidden" name="warnaMobilFilter" id="hiddenWarnaMobilFilter" value="{{ request('warnaMobilFilter') }}">
            <input type="hidden" name="bahanBakarFilter" id="hiddenBahanBakarFilter" value="{{ request('bahanBakarFilter') }}">
            {{-- Hidden input untuk filter tahun yang kini berada di modal --}}
            <input type="hidden" name="tahunPembuatanFilter" id="hiddenTahunPembuatanFilter" value="{{ request('tahunPembuatanFilter') }}">
        </form>
    </div>

    <div class="card border-0 shadow-xl rounded-4 animate__animated animate__fadeInUp">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-dark fw-bold">Data Mobil</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive" id="tableResponsiveContainer">
                <table class="table table-hover table-striped align-middle mb-0" id="mobilTable">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center" style="min-width: 50px;">#</th>
                            <th scope="col" style="min-width: 150px;" data-sort-type="text">Merek</th>
                            <th scope="col" style="min-width: 150px;" data-sort-type="text">Model</th>
                            <th scope="col" style="min-width: 100px;" data-sort-type="numeric">Tahun</th>
                            <th scope="col" style="min-width: 150px;" data-sort-type="text">Nopol</th>
                            <th scope="col" style="min-width: 120px;" data-sort-type="text">Transmisi</th>
                            <th scope="col" style="min-width: 120px;" data-sort-type="text">Warna</th>
                            <th scope="col" style="min-width: 120px;" data-sort-type="text">Bahan Bakar</th>
                            <th scope="col" style="min-width: 150px;" data-sort-type="numeric">Harga</th>
                            <th scope="col" style="min-width: 120px;" data-sort-type="status">Status</th>
                            <th scope="col" style="min-width: 120px;" data-sort-type="ketersediaan">Ketersediaan</th>
                            <th scope="col" class="text-center" style="min-width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($mobils as $mobil)
                            <tr class="data-row">
                                <td class="text-center">{{ $loop->iteration + ($mobils->currentPage() - 1) * $mobils->perPage() }}</td> {{-- Perbaiki nomor iterasi untuk pagination --}}
                                <td>{{ $mobil->merek_mobil }}</td>
                                <td>{{ $mobil->tipe_mobil }}</td>
                                <td>{{ $mobil->tahun_pembuatan }}</td>
                                <td>{{ $mobil->nomor_polisi }}</td>
                                <td>{{ ucfirst($mobil->transmisi) }}</td>
                                <td>{{ ucfirst($mobil->warna_mobil) }}</td>
                                <td>{{ ucfirst($mobil->bahan_bakar) }}</td>
                                <td>Rp{{ number_format($mobil->harga_mobil, 0, ',', '.') }}</td>
                                <td>
                                    @php
                                        $statusClass = '';
                                        if ($mobil->status_mobil === 'tersedia') {
                                            $statusClass = 'bg-success';
                                        } elseif ($mobil->status_mobil === 'terjual') {
                                            $statusClass = 'bg-danger';
                                        } elseif ($mobil->status_mobil === 'booking') {
                                            $statusClass = 'bg-warning';
                                        } elseif ($mobil->status_mobil === 'perbaikan') {
                                            $statusClass = 'bg-info text-dark';
                                        } elseif ($mobil->status_mobil === 'baru') {
                                            $statusClass = 'bg-primary-dark';
                                        } elseif ($mobil->status_mobil === 'bekas') {
                                            $statusClass = 'bg-secondary-light';
                                        } elseif ($mobil->status_mobil === 'lunas') {
                                            $statusClass = 'bg-success';
                                        } elseif ($mobil->status_mobil === 'belum lunas') {
                                            $statusClass = 'bg-warning text-dark';
                                        } elseif ($mobil->status_mobil === 'menunggu pembayaran') {
                                            $statusClass = 'bg-info text-dark';
                                        } elseif ($mobil->status_mobil === 'dibatalkan') {
                                            $statusClass = 'bg-danger';
                                        }
                                    @endphp
                                    <span class="badge {{ $statusClass }}">{{ ucfirst($mobil->status_mobil) }}</span>
                                </td>
                                <td>
                                    @php
                                        $ketersediaanClass = '';
                                        if ($mobil->ketersediaan === 'ada') {
                                            $ketersediaanClass = 'ketersediaan-ada';
                                        } else if ($mobil->ketersediaan === 'terjual') {
                                            $ketersediaanClass = 'ketersediaan-terjual';
                                        }
                                    @endphp
                                    <span class="badge {{ $ketersediaanClass }}">{{ ucfirst($mobil->ketersediaan) }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('mobil.show', $mobil->id) }}" class="btn btn-custom-view">
                                            <i class="fas fa-eye me-1"></i> Lihat
                                        </a>
                                        @if(in_array($job, ['admin']))
                                        <a href="{{ route('mobil.edit', $mobil->id) }}" class="btn btn-custom-edit">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center py-4 text-muted">
                                    <i class="bi bi-info-circle-fill fs-3 mb-2 d-block"></i>
                                    <p class="mb-1">Tidak ada data mobil yang tersedia.</p>
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
            {{-- Pagination Links (Pastikan $mobils adalah instance Paginator) --}}
            {{-- $mobils->links() sudah otomatis menyertakan query params karena appends() di controller --}}
            {{ optional($mobils)->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

{{-- Modal for More Filters --}}
<div class="modal fade" id="moreFiltersModal" tabindex="-1" aria-labelledby="moreFiltersModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark" id="moreFiltersModalLabel">Filter Tambahan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-0">
                <p class="text-muted mb-4">Pilih filter tambahan untuk menyaring data mobil.</p>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="transmisiFilterModal" class="form-label text-muted">Transmisi</label>
                        {{-- Tambahkan name="transmisiFilter" dan value dari request --}}
                        <select name="transmisiFilter" id="transmisiFilterModal" class="form-select">
                            <option value="">Semua Transmisi</option>
                            <option value="manual" {{ request('transmisiFilter') == 'manual' ? 'selected' : '' }}>Manual</option>
                            <option value="matic" {{ request('transmisiFilter') == 'matic' ? 'selected' : '' }}>Matic</option>
                        </select>
                    </div>
                    {{-- Ketersediaan filter di modal --}}
                    <div class="col-md-6">
                        <label for="ketersediaanFilterModal" class="form-label text-muted">Ketersediaan</label>
                        {{-- Tambahkan name="ketersediaanFilter" dan value dari request --}}
                        <select name="ketersediaanFilter" id="ketersediaanFilterModal" class="form-select">
                            <option value="">Semua Ketersediaan</option>
                            <option value="ada" {{ request('ketersediaanFilter') == 'ada' ? 'selected' : '' }}>Ada</option>
                            <option value="terjual" {{ request('ketersediaanFilter') == 'terjual' ? 'selected' : '' }}>Terjual</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="jenisMobilFilterModal" class="form-label text-muted">Jenis</label>
                        {{-- Tambahkan name="jenisMobilFilter" dan value dari request --}}
                        <select name="jenisMobilFilter" id="jenisMobilFilterModal" class="form-select">
                            <option value="">Semua Jenis</option>
                            <option value="suv" {{ request('jenisMobilFilter') == 'suv' ? 'selected' : '' }}>SUV</option>
                            <option value="mpv" {{ request('jenisMobilFilter') == 'mpv' ? 'selected' : '' }}>MPV</option>
                            <option value="sedan" {{ request('jenisMobilFilter') == 'sedan' ? 'selected' : '' }}>Sedan</option>
                            <option value="hatchback" {{ request('jenisMobilFilter') == 'hatchback' ? 'selected' : '' }}>Hatchback</option>
                            <option value="sport" {{ request('jenisMobilFilter') == 'sport' ? 'selected' : '' }}>Sport</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="warnaMobilFilterModal" class="form-label text-muted">Warna</label>
                        {{-- Tambahkan name="warnaMobilFilter" dan value dari request --}}
                        <select name="warnaMobilFilter" id="warnaMobilFilterModal" class="form-select">
                            <option value="">Semua Warna</option>
                            @php
                                $uniqueWarna = $mobils->pluck('warna_mobil')->unique()->sort();
                            @endphp
                            @foreach ($uniqueWarna as $warna)
                                <option value="{{ $warna }}" {{ request('warnaMobilFilter') == $warna ? 'selected' : '' }}>{{ ucfirst($warna) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="bahanBakarFilterModal" class="form-label text-muted">Bahan Bakar</label>
                        {{-- Tambahkan name="bahanBakarFilter" dan value dari request --}}
                        <select name="bahanBakarFilter" id="bahanBakarFilterModal" class="form-select">
                            <option value="">Semua Bahan Bakar</option>
                            <option value="bensin" {{ request('bahanBakarFilter') == 'bensin' ? 'selected' : '' }}>Bensin</option>
                            <option value="diesel" {{ request('bahanBakarFilter') == 'diesel' ? 'selected' : '' }}>Diesel</option>
                            <option value="listrik" {{ request('bahanBakarFilter') == 'listrik' ? 'selected' : '' }}>Listrik</option>
                        </select>
                    </div>
                    {{-- Filter Tahun Pembuatan dipindahkan ke sini --}}
                    <div class="col-md-6">
                        <label for="tahunPembuatanFilterModal" class="form-label text-muted">Tahun</label>
                        <select name="tahunPembuatanFilter" id="tahunPembuatanFilterModal" class="form-select">
                            <option value="">Semua Tahun</option>
                            @php
                                $uniqueTahun = $mobils->pluck('tahun_pembuatan')->unique()->sortDesc();
                            @endphp
                            @foreach ($uniqueTahun as $tahun)
                                <option value="{{ $tahun }}" {{ request('tahunPembuatanFilter') == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="nopolFilterModal" class="form-label text-muted">Nomor Polisi</label>
                        <input type="text" id="nopolFilterModal" class="form-control" placeholder="Cari Nopol..." value="{{ request('search') }}">
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0 d-flex justify-content-end"> {{-- Removed reset button from modal footer --}}
                <button type="button" id="applyAllFiltersBtn" class="btn btn-primary rounded-pill">
                    <i class="bi bi-check-circle me-2"></i> Terapkan Filter
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Bootstrap Bundle with Popper --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterForm = document.getElementById('filterForm');
        const searchInput = document.getElementById('searchInput'); // Main search input
        const merekFilter = document.getElementById('merekMobilFilter'); // Main merek filter
        const ketersediaanMobilFilter = document.getElementById('ketersediaanMobilFilter'); // Main ketersediaan filter

        // Modal filter elements
        const transmisiFilterModal = document.getElementById('transmisiFilterModal');
        const ketersediaanFilterModal = document.getElementById('ketersediaanFilterModal');
        const jenisFilterModalModal = document.getElementById('jenisMobilFilterModal');
        const warnaFilterModal = document.getElementById('warnaMobilFilterModal');
        const bahanBakarFilterModal = document.getElementById('bahanBakarFilterModal');
        const tahunPembuatanFilterModal = document.getElementById('tahunPembuatanFilterModal'); // Filter tahun kini di modal
        const nopolFilterModal = document.getElementById('nopolFilterModal');

        const hiddenTransmisiFilter = document.getElementById('hiddenTransmisiFilter');
        const hiddenKetersediaanFilter = document.getElementById('hiddenKetersediaanFilter');
        const hiddenJenisMobilFilter = document.getElementById('hiddenJenisMobilFilter');
        const hiddenWarnaMobilFilter = document.getElementById('hiddenWarnaMobilFilter');
        const hiddenBahanBakarFilter = document.getElementById('hiddenBahanBakarFilter');
        const hiddenTahunPembuatanFilter = document.getElementById('hiddenTahunPembuatanFilter'); // Hidden input untuk tahun

        const resetMainFiltersBtn = document.getElementById('resetMainFiltersBtn'); // Changed ID to reflect main button
        const applyAllFiltersBtn = document.getElementById('applyAllFiltersBtn');
        const moreFiltersModalInstance = new bootstrap.Modal(document.getElementById('moreFiltersModal'));

        // Fungsi untuk meng-submit form filter (digunakan saat Apply Filters di modal atau perubahan filter utama)
        function submitFilterForm() {
            // Salin nilai dari modal filter ke input hidden
            hiddenTransmisiFilter.value = transmisiFilterModal.value;
            hiddenKetersediaanFilter.value = ketersediaanFilterModal.value;
            hiddenJenisMobilFilter.value = jenisFilterModalModal.value;
            hiddenWarnaMobilFilter.value = warnaFilterModal.value;
            hiddenBahanBakarFilter.value = bahanBakarFilterModal.value;
            hiddenTahunPembuatanFilter.value = tahunPembuatanFilterModal.value;

            // Pastikan nilai dari filter utama juga diset ke hidden input
            if (ketersediaanMobilFilter.value !== '') { // Hanya salin jika ada nilai di filter utama
                hiddenKetersediaanFilter.value = ketersediaanMobilFilter.value;
            }

            filterForm.submit(); // Submit form GET
        }

        // Event listener untuk input pencarian utama (ketika user menekan Enter)
        searchInput.addEventListener('keyup', function(event) {
            if (event.key === 'Enter') {
                submitFilterForm();
            }
        });

        // Event listener untuk filter dropdown utama
        merekFilter.addEventListener('change', submitFilterForm);
        ketersediaanMobilFilter.addEventListener('change', submitFilterForm);

        // Event listener untuk tombol "Terapkan Filter" di modal
        applyAllFiltersBtn.addEventListener('click', function() {
            moreFiltersModalInstance.hide(); // Sembunyikan modal, submitForm akan dipanggil di hidden.bs.modal
        });

        // Event listener untuk tombol "Reset" di baris utama
        resetMainFiltersBtn.addEventListener('click', function() {
            // Reset main filters UI
            searchInput.value = '';
            merekFilter.value = '';
            ketersediaanMobilFilter.value = '';

            // Reset modal filters UI
            transmisiFilterModal.value = '';
            ketersediaanFilterModal.value = '';
            jenisFilterModalModal.value = '';
            warnaFilterModal.value = '';
            bahanBakarFilterModal.value = '';
            tahunPembuatanFilterModal.value = '';
            nopolFilterModal.value = ''; // Sinkronkan input nopol di modal

            // Reset hidden inputs (yang akan digunakan form saat disubmit jika kita pakai submitFilterForm)
            hiddenTransmisiFilter.value = '';
            hiddenKetersediaanFilter.value = '';
            hiddenJenisMobilFilter.value = '';
            hiddenWarnaMobilFilter.value = '';
            hiddenBahanBakarFilter.value = '';
            hiddenTahunPembuatanFilter.value = '';

            // Langsung arahkan ke URL dasar untuk membersihkan semua parameter filter dari URL
            window.location.href = "{{ route('mobil.index') }}";
        });

        // Sinkronisasi nilai filter modal saat modal dibuka
        document.getElementById('moreFiltersModal').addEventListener('show.bs.modal', function () {
            transmisiFilterModal.value = hiddenTransmisiFilter.value;
            ketersediaanFilterModal.value = hiddenKetersediaanFilter.value;
            jenisFilterModalModal.value = hiddenJenisMobilFilter.value;
            warnaFilterModal.value = hiddenWarnaMobilFilter.value;
            bahanBakarFilterModal.value = hiddenBahanBakarFilter.value;
            tahunPembuatanFilterModal.value = hiddenTahunPembuatanFilter.value; // Sinkronkan tahun
            nopolFilterModal.value = searchInput.value; // Sinkronkan nopol dari search utama
        });

        // Submit form setelah modal disembunyikan (ini akan bekerja jika applyAllFiltersBtn diklik)
        document.getElementById('moreFiltersModal').addEventListener('hidden.bs.modal', function () {
            // Pastikan nilai searchInput utama disinkronkan kembali dari modal nopol input
            searchInput.value = nopolFilterModal.value;
            submitFilterForm();
        });


        // Logika Sorting
        document.querySelectorAll('th[data-sort-type]').forEach(header => {
            header.addEventListener('click', function() {
                const table = this.closest('table');
                const tbody = table.querySelector('tbody');
                const rows = Array.from(tbody.querySelectorAll('tr'));
                const dataRows = rows.filter(row => !row.classList.contains('empty-state-message'));

                const columnIndex = Array.from(this.parentNode.children).indexOf(this);
                const sortType = this.dataset.sortType;
                const currentDirection = this.dataset.sortDirection || 'asc';
                const newDirection = currentDirection === 'asc' ? 'desc' : 'asc';

                document.querySelectorAll('th[data-sort-type]').forEach(th => {
                    th.classList.remove('asc', 'desc');
                    th.removeAttribute('data-sort-direction');
                });

                this.classList.add(newDirection);
                this.dataset.sortDirection = newDirection;

                const statusOrder = {
                    'tersedia': 7,
                    'baru': 6,
                    'booking': 5,
                    'perbaikan': 4,
                    'bekas': 3,
                    'terjual': 2,
                    'tidak ada': 1,
                    'lunas': 7,
                    'belum lunas': 4,
                    'menunggu pembayaran': 5,
                    'dibatalkan': 1
                };
                const ketersediaanOrder = {
                    'ada': 2,
                    'terjual': 1,
                };

                dataRows.sort((rowA, rowB) => {
                    const cellA = rowA.children[columnIndex];
                    const cellB = rowB.children[columnIndex];

                    let valA = cellA.textContent.trim();
                    let valB = cellB.textContent.trim();

                    let cellAValue, cellBValue;
                    switch (sortType) {
                        case 'numeric': // For 'Tahun' and 'Harga'
                            cellAValue = parseFloat(valA.replace(/[^0-9,-]+/g,"").replace(",", "."));
                            cellBValue = parseFloat(valB.replace(/[^0-9,-]+/g,"").replace(",", "."));
                            break;
                        case 'status': // For 'Status'
                            cellAValue = statusOrder[valA.toLowerCase()] || 0;
                            cellBValue = statusOrder[valB.toLowerCase()] || 0;
                            break;
                        case 'ketersediaan': // For 'Ketersediaan'
                            cellAValue = ketersediaanOrder[valA.toLowerCase()] || 0;
                            cellBValue = ketersediaanOrder[valB.toLowerCase()] || 0;
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

                let emptyStateRow = tbody.querySelector('.empty-state-message');
                if (emptyStateRow) {
                    emptyStateRow.remove();
                }
                dataRows.forEach(row => tbody.appendChild(row));

                if (dataRows.length === 0) {
                     emptyStateRow = document.createElement('tr');
                     emptyStateRow.classList.add('empty-state-message');
                     emptyStateRow.innerHTML = `
                         <td colspan="12" class="text-center py-4 text-muted">
                             <i class="bi bi-info-circle-fill fs-3 mb-2 d-block"></i>
                             <p class="mb-1">Tidak ada data mobil yang tersedia.</p>
                             <p class="mb-0">Coba kata kunci atau filter lain.</p>
                         </td>
                     `;
                     tbody.appendChild(emptyStateRow);
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

        // Inisialisasi awal nilai input dari URL saat halaman dimuat
        searchInput.value = new URLSearchParams(window.location.search).get('search') || '';
        merekFilter.value = new URLSearchParams(window.location.search).get('merekMobilFilter') || '';
        ketersediaanMobilFilter.value = new URLSearchParams(window.location.search).get('ketersediaanFilter') || '';

        // Inisialisasi nilai input hidden modal dari URL
        hiddenTransmisiFilter.value = new URLSearchParams(window.location.search).get('transmisiFilter') || '';
        hiddenKetersediaanFilter.value = new URLSearchParams(window.location.search).get('ketersediaanFilter') || '';
        hiddenJenisMobilFilter.value = new URLSearchParams(window.location.search).get('jenisMobilFilter') || '';
        hiddenWarnaMobilFilter.value = new URLSearchParams(window.location.search).get('warnaMobilFilter') || '';
        hiddenBahanBakarFilter.value = new URLSearchParams(window.location.search).get('bahanBakarFilter') || '';
        hiddenTahunPembuatanFilter.value = new URLSearchParams(window.location.search).get('tahunPembuatanFilter') || ''; // Inisialisasi hidden tahun
    });
</script>
@endsection
