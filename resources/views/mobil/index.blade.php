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

    {{-- Filter and Search Section --}}
    <div class="filter-section animate__animated animate__fadeInUp">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="searchInput" class="form-label text-muted">Cari Mobil</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Cari berdasarkan merek, model, nopol...">
                </div>
            </div>
            <div class="col-md-2">
                <label for="merekFilter" class="form-label text-muted">Merek</label>
                <select id="merekFilter" class="form-select">
                    <option value="">Semua Merek</option>
                    @foreach ($mobils->unique('merek_mobil') as $mobil)
                        <option value="{{ $mobil->merek_mobil }}">{{ $mobil->merek_mobil }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="tahunFilter" class="form-label text-muted">Tahun</label>
                <select id="tahunFilter" class="form-select">
                    <option value="">Semua Tahun</option>
                    @foreach ($mobils->unique('tahun_pembuatan')->sortByDesc('tahun_pembuatan') as $mobil)
                        <option value="{{ $mobil->tahun_pembuatan }}">{{ $mobil->tahun_pembuatan }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="statusFilter" class="form-label text-muted">Status</label>
                <select id="statusFilter" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="tersedia">Tersedia</option>
                    <option value="terjual">Terjual</option>
                    <option value="booking">Booking</option>
                    <option value="perbaikan">Perbaikan</option>
                    <option value="baru">Baru</option> {{-- Ditambahkan --}}
                    <option value="bekas">Bekas</option> {{-- Ditambahkan --}}
                </select>
            </div>
            <div class="col-md-2 text-end">
                <button type="button" class="btn btn-outline-secondary w-100" data-bs-toggle="modal" data-bs-target="#moreFiltersModal">
                    <i class="bi bi-funnel me-2"></i> Filter Lainnya
                </button>
            </div>
        </div>
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
                            <tr class="data-row"
                                data-merek="{{ strtolower($mobil->merek_mobil) }}"
                                data-model="{{ strtolower($mobil->tipe_mobil) }}"
                                data-tahun="{{ $mobil->tahun_pembuatan }}"
                                data-nopol="{{ strtolower($mobil->nomor_polisi) }}"
                                data-transmisi="{{ strtolower($mobil->transmisi) }}"
                                data-status="{{ strtolower($mobil->status_mobil) }}"
                                data-ketersediaan="{{ strtolower($mobil->ketersediaan) }}"
                                data-jenis="{{ strtolower($mobil->jenis_mobil) }}"
                                data-warna="{{ strtolower($mobil->warna_mobil) }}"
                                data-bahan-bakar="{{ strtolower($mobil->bahan_bakar) }}">
                                <td class="text-center">{{ $loop->iteration }}</td>
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
                                            $statusClass = 'bg-info text-dark'; // Changed for visibility
                                        } elseif ($mobil->status_mobil === 'baru') { // Ditambahkan
                                            $statusClass = 'bg-primary-dark'; // Ditambahkan
                                        } elseif ($mobil->status_mobil === 'bekas') { // Ditambahkan
                                            $statusClass = 'bg-secondary-light'; // Ditambahkan
                                        }
                                    @endphp
                                    <span class="badge {{ $statusClass }}">{{ ucfirst($mobil->status_mobil) }}</span>
                                </td>
                                <td>
                                    @php
                                        $ketersediaanClass = '';
                                        if ($mobil->ketersediaan === 'ada') {
                                            $ketersediaanClass = 'bg-success';
                                        } else {
                                            $ketersediaanClass = 'bg-danger';
                                        }
                                    @endphp
                                    <span class="badge {{ $ketersediaanClass }}">{{ ucfirst($mobil->ketersediaan) }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('mobil.show', $mobil->id) }}" class="btn btn-custom-view">
                                            <i class="fas fa-eye me-1"></i> Lihat
                                        </a>
                                        <a href="{{ route('mobil.edit', $mobil->id) }}" class="btn btn-custom-edit">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </a>
                                        {{-- Tombol Hapus Dihapus Sesuai Permintaan --}}
                                        {{-- <form action="{{ route('mobil.destroy', $mobil->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data mobil ini?');" class="d-inline">
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
                        <select id="transmisiFilterModal" class="form-select">
                            <option value="">Semua Transmisi</option>
                            <option value="manual">Manual</option>
                            <option value="automatic">Automatic</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="ketersediaanFilterModal" class="form-label text-muted">Ketersediaan</label>
                        <select id="ketersediaanFilterModal" class="form-select">
                            <option value="">Semua Ketersediaan</option>
                            <option value="ada">Ada</option>
                            <option value="tidak ada">Tidak Ada</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="jenisFilterModal" class="form-label text-muted">Jenis</label>
                        <select id="jenisFilterModal" class="form-select">
                            <option value="">Semua Jenis</option>
                            <option value="suv">SUV</option>
                            <option value="mpv">MPV</option>
                            <option value="sedan">Sedan</option>
                            <option value="hatchback">Hatchback</option>
                            <option value="sport">Sport</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="warnaFilterModal" class="form-label text-muted">Warna</label>
                        <select id="warnaFilterModal" class="form-select">
                            <option value="">Semua Warna</option>
                            @foreach ($mobils->unique('warna') as $mobil)
                                <option value="{{ $mobil->warna_mobil }}">{{ ucfirst($mobil->warna_mobil) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="bahanBakarFilterModal" class="form-label text-muted">Bahan Bakar</label>
                        <select id="bahanBakarFilterModal" class="form-select">
                            <option value="">Semua Bahan Bakar</option>
                            <option value="bensin">Bensin</option>
                            <option value="diesel">Diesel</option>
                            <option value="listrik">Listrik</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="nopolFilterModal" class="form-label text-muted">Nomor Polisi</label>
                        <input type="text" id="nopolFilterModal" class="form-control" placeholder="Cari Nopol...">
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0 d-flex justify-content-between">
                <button type="button" id="resetAllFiltersBtn" class="btn btn-outline-secondary rounded-pill">
                    <i class="bi bi-arrow-counterclockwise me-2"></i> Reset Semua
                </button>
                <button type="button" id="applyAllFiltersBtn" class="btn btn-primary rounded-pill">
                    <i class="bi bi-check-circle me-2"></i> Terapkan Filter
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Bootstrap Bundle with Popper --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const merekFilter = document.getElementById('merekFilter');
        const tahunFilter = document.getElementById('tahunFilter');
        const statusFilter = document.getElementById('statusFilter');

        // Modal filters
        const transmisiFilterModal = document.getElementById('transmisiFilterModal');
        const ketersediaanFilterModal = document.getElementById('ketersediaanFilterModal');
        const jenisFilterModal = document.getElementById('jenisFilterModal');
        const warnaFilterModal = document.getElementById('warnaFilterModal');
        const bahanBakarFilterModal = document.getElementById('bahanBakarFilterModal');
        const nopolFilterModal = document.getElementById('nopolFilterModal');

        const resetAllFiltersBtn = document.getElementById('resetAllFiltersBtn');
        const applyAllFiltersBtn = document.getElementById('applyAllFiltersBtn');
        const mobilTableBody = document.querySelector('#mobilTable tbody');
        const allRows = Array.from(mobilTableBody.querySelectorAll('tr.data-row')); // Get all data rows initially

        // Bootstrap Modal Instance
        const moreFiltersModal = new bootstrap.Modal(document.getElementById('moreFiltersModal'));


        // Function to apply all filters (main and modal)
        function applyFiltersAndSearch() {
            const searchTerm = searchInput.value.toLowerCase();
            const selectedMerek = merekFilter.value.toLowerCase();
            const selectedTahun = tahunFilter.value;
            const selectedStatus = statusFilter.value.toLowerCase();

            // Get values from modal filters
            const selectedTransmisi = transmisiFilterModal.value.toLowerCase();
            const selectedKetersediaan = ketersediaanFilterModal.value.toLowerCase();
            const selectedJenis = jenisFilterModal.value.toLowerCase();
            const selectedWarna = warnaFilterModal.value.toLowerCase();
            const selectedBahanBakar = bahanBakarFilterModal.value.toLowerCase();
            const selectedNopol = nopolFilterModal.value.toLowerCase();

            let foundVisibleRows = false;

            allRows.forEach(row => {
                const merek = row.dataset.merek;
                const model = row.dataset.model;
                const tahun = row.dataset.tahun;
                const nopol = row.dataset.nopol;
                const transmisi = row.dataset.transmisi;
                const status = row.dataset.status;
                const ketersediaan = row.dataset.ketersediaan;
                const jenis = row.dataset.jenis;
                const warna = row.dataset.warna;
                const bahanBakar = row.dataset.bahanBakar;

                const matchesSearch = merek.includes(searchTerm) ||
                                      model.includes(searchTerm) ||
                                      nopol.includes(searchTerm);

                const matchesMerek = selectedMerek === '' || merek === selectedMerek;
                const matchesTahun = selectedTahun === '' || tahun === selectedTahun;
                const matchesStatus = selectedStatus === '' || status === selectedStatus;

                // Match with modal filters
                const matchesTransmisi = selectedTransmisi === '' || transmisi === selectedTransmisi;
                const matchesKetersediaan = selectedKetersediaan === '' || ketersediaan === selectedKetersediaan;
                const matchesJenis = selectedJenis === '' || jenis === selectedJenis;
                const matchesWarna = selectedWarna === '' || warna === selectedWarna;
                const matchesBahanBakar = selectedBahanBakar === '' || bahanBakar === selectedBahanBakar;
                const matchesNopol = selectedNopol === '' || nopol.includes(selectedNopol);

                if (matchesSearch && matchesMerek && matchesTahun && matchesStatus &&
                    matchesTransmisi && matchesKetersediaan && matchesJenis && matchesWarna && matchesBahanBakar && matchesNopol) {
                    row.style.display = ''; // Show row
                    foundVisibleRows = true;
                } else {
                    row.style.display = 'none'; // Hide row
                }
            });

            // Handle empty state message
            let emptyStateRow = mobilTableBody.querySelector('.empty-state-message');
            if (!foundVisibleRows) {
                if (!emptyStateRow) {
                    emptyStateRow = document.createElement('tr');
                    emptyStateRow.classList.add('empty-state-message');
                    emptyStateRow.innerHTML = `
                        <td colspan="12" class="text-center py-4 text-muted">
                            <i class="bi bi-info-circle-fill fs-3 mb-2 d-block"></i>
                            <p class="mb-1">Tidak ada data mobil yang tersedia dengan filter ini.</p>
                            <p class="mb-0">Coba kata kunci atau filter lain.</p>
                        </td>
                    `;
                    mobilTableBody.appendChild(emptyStateRow);
                } else {
                    emptyStateRow.style.display = ''; // Show existing empty state
                }
            } else {
                if (emptyStateRow) {
                    emptyStateRow.style.display = 'none'; // Hide empty state if results are found
                }
            }
        }

        // Event listeners for main filters
        searchInput.addEventListener('keyup', applyFiltersAndSearch);
        merekFilter.addEventListener('change', applyFiltersAndSearch);
        tahunFilter.addEventListener('change', applyFiltersAndSearch);
        statusFilter.addEventListener('change', applyFiltersAndSearch);

        // Event listener for "Apply Filters" button inside modal
        applyAllFiltersBtn.addEventListener('click', function() {
            applyFiltersAndSearch();
            moreFiltersModal.hide(); // Hide the modal after applying
        });

        // Event listener for "Reset All Filters" button inside modal
        resetAllFiltersBtn.addEventListener('click', function() {
            // Reset main filters
            searchInput.value = '';
            merekFilter.value = '';
            tahunFilter.value = '';
            statusFilter.value = '';

            // Reset modal filters
            transmisiFilterModal.value = '';
            ketersediaanFilterModal.value = '';
            jenisFilterModal.value = '';
            warnaFilterModal.value = '';
            bahanBakarFilterModal.value = '';
            nopolFilterModal.value = '';

            applyFiltersAndSearch(); // Apply filters with cleared values
            moreFiltersModal.hide(); // Hide the modal after resetting
        });

        // Event listener for when the modal is shown, to sync current main filter values to modal filters
        document.getElementById('moreFiltersModal').addEventListener('show.bs.modal', function () {
            // Sync current main filter values to modal filters (if needed, though for select it's usually handled by initial load)
            // For text inputs like nopol, ensure the current value is reflected in the modal when opened
            nopolFilterModal.value = nopolFilterModal.value; // This ensures it keeps its value if modal is closed and reopened without applying
        });


        // Initial setup on page load
        applyFiltersAndSearch(); // Apply all filters initially

        // Sorting logic (remains the same)
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
                    'tersedia': 7, // Disesuaikan, tambahkan prioritas baru
                    'baru': 6,
                    'booking': 5,
                    'perbaikan': 4,
                    'bekas': 3,
                    'terjual': 2,
                    'tidak ada': 1
                };
                const ketersediaanOrder = {
                    'ada': 2,
                    'tidak ada': 1
                };

                rows.sort((rowA, rowB) => {
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
