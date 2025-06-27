@extends('layouts.app')

@section('title', 'Data Mobil')

@section('content')
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

    <div class="card border-0 shadow-xl rounded-4 animate__animated animate__fadeInUp">
        <div class="card-header bg-white p-4 border-bottom-0 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <div class="header-left mb-3 mb-md-0">
                <h5 class="card-title mb-0 text-dark fw-bold">Daftar Mobil</h5>
                <p class="card-text text-muted">Informasi lengkap mengenai mobil yang tersedia.</p>
            </div>
            <div class="header-right d-flex flex-column flex-md-row align-items-md-center gap-3">
                <input type="text" id="searchInput" class="form-control rounded-pill shadow-sm" placeholder="Cari mobil..." aria-label="Cari mobil">

                {{-- Filter Merek --}}
                <select id="merekFilter" class="form-select rounded-pill shadow-sm">
                    <option value="">Semua Merek</option>
                    {{-- Dynamically populate with unique merek_mobil from $mobils, sorted alphabetically --}}
                    @foreach($mobils->unique('merek_mobil')->sortBy('merek_mobil') as $mobil)
                        <option value="{{ $mobil->merek_mobil }}">{{ $mobil->merek_mobil }}</option>
                    @endforeach
                </select>

                {{-- Filter Tahun --}}
                <select id="tahunFilter" class="form-select rounded-pill shadow-sm">
                    <option value="">Semua Tahun</option>
                    {{-- Dynamically populate with unique tahun_pembuatan from $mobils --}}
                    @foreach($mobils->unique('tahun_pembuatan')->sortByDesc('tahun_pembuatan') as $mobil)
                        <option value="{{ $mobil->tahun_pembuatan }}">{{ $mobil->tahun_pembuatan }}</option>
                    @endforeach
                </select>

                {{-- Filter Transmisi (Baru Ditambahkan) --}}
                <select id="transmisiFilter" class="form-select rounded-pill shadow-sm">
                    <option value="">Semua Transmisi</option>
                    @foreach($mobils->unique('transmisi')->sortBy('transmisi') as $mobil)
                        <option value="{{ $mobil->transmisi }}">{{ ucfirst($mobil->transmisi) }}</option>
                    @endforeach
                </select>

                {{-- Filter Status --}}
                <select id="statusFilter" class="form-select rounded-pill shadow-sm">
                    <option value="">Semua Status</option>
                    {{-- Dynamically populate with unique status_mobil from $mobils --}}
                    @foreach($mobils->unique('status_mobil') as $mobil)
                        <option value="{{ $mobil->status_mobil }}">{{ ucfirst($mobil->status_mobil) }}</option>
                    @endforeach
                </select>

                {{-- Filter Ketersediaan --}}
                <select id="ketersediaanFilter" class="form-select rounded-pill shadow-sm">
                    <option value="">Semua Ketersediaan</option>
                    <option value="ada">Ada</option>
                    <option value="tidak">Tidak ada</option>
                    <option value="servis">Servis</option>
                </select>

                <button id="resetFilters" class="btn btn-outline-secondary rounded-pill shadow-sm">Reset</button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive" id="tableResponsiveContainer">
                <table class="table table-hover table-striped align-middle mb-0 custom-table" id="mobilTable">
                    <thead class="bg-gradient-primary text-white text-center align-middle">
                        <tr>
                            <th scope="col">Kode</th>
                            <th scope="col">Jenis</th>
                            <th scope="col">Merek</th>
                            <th scope="col">Tipe</th>
                            <th scope="col">Transmisi</th> {{-- Kolom Transmisi Baru --}}
                            <th scope="col">Tahun</th>
                            <th scope="col">Warna</th>
                            <th scope="col">Bahan Bakar</th>
                            <th scope="col">No. Polisi</th>
                            <th scope="col">Tgl. Masuk</th>
                            <th scope="col">Status</th>
                            <th scope="col">Ketersediaan</th>
                            <th scope="col">Harga</th>
                            {{-- Kolom Aksi hanya tampil jika bukan sales --}}
                            @if(!in_array($job, ['sales']))
                            <th scope="col">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($mobils as $mobil)
                        <tr class="data-row"
                            data-jenis="{{ $mobil->jenis_mobil }}"
                            data-merek="{{ $mobil->merek_mobil }}"
                            data-transmisi="{{ $mobil->transmisi }}" {{-- Tambahkan data-transmisi --}}
                            data-status="{{ $mobil->status_mobil }}"
                            data-tahun="{{ $mobil->tahun_pembuatan }}"
                            data-bahanbakar="{{ $mobil->bahan_bakar }}"
                            data-ketersediaan="{{ strtolower($mobil->ketersediaan) }}">
                            <td class="text-center fw-bold">{{ $mobil->kode_mobil }}</td>
                            <td class="text-center">{{ $mobil->jenis_mobil }}</td>
                            <td class="text-center">{{ $mobil->merek_mobil }}</td>
                            <td class="text-center">{{ $mobil->tipe_mobil }}</td>
                            <td class="text-center">{{ ucfirst($mobil->transmisi) }}</td> {{-- Tampilkan data transmisi --}}
                            <td class="text-center">{{ $mobil->tahun_pembuatan }}</td>
                            <td class="text-center">{{ $mobil->warna_mobil }}</td>
                            <td class="text-center">{{ ucfirst($mobil->bahan_bakar) }}</td>
                            <td class="text-center">{{ $mobil->nomor_polisi }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($mobil->tanggal_masuk)->format('d M Y') }}</td>
                            <td class="text-center">
                                @php
                                    $statusBadgeClass = '';
                                    switch ($mobil->status_mobil) {
                                        case 'tersedia':
                                            $statusBadgeClass = 'success';
                                            break;
                                        case 'baru':
                                            $statusBadgeClass = 'info';
                                            break;
                                        case 'bekas':
                                            $statusBadgeClass = 'secondary';
                                            break;
                                        default: // Untuk 'terjual' atau status lain yang tidak terduga
                                            $statusBadgeClass = 'danger';
                                            break;
                                    }
                                @endphp
                                <span class="badge bg-{{ $statusBadgeClass }} px-3 py-2 rounded-pill shadow-sm">
                                    {{ ucfirst($mobil->status_mobil) }}
                                </span>
                            </td>
                            <td class="text-center">
                                @php
                                    $availabilityBadgeClass = '';
                                    $availabilityText = '';
                                    $ketersediaan_lower = strtolower($mobil->ketersediaan);
                                    if ($ketersediaan_lower === 'ada') {
                                        $availabilityBadgeClass = 'success';
                                        $availabilityText = 'Ada';
                                    } elseif ($ketersediaan_lower === 'tidak') {
                                        $availabilityBadgeClass = 'danger';
                                        $availabilityText = 'Tidak ada';
                                    } elseif ($ketersediaan_lower === 'servis') {
                                        $availabilityBadgeClass = 'warning text-dark';
                                        $availabilityText = 'Servis';
                                    } else {
                                        $availabilityBadgeClass = 'secondary';
                                        $availabilityText = 'Tidak diketahui';
                                    }
                                @endphp
                                <span class="badge bg-{{ $availabilityBadgeClass }} px-3 py-2 rounded-pill shadow-sm">
                                    {{ $availabilityText }}
                                </span>
                            </td>

                            <td class="text-start text-success fw-bold">Rp {{ number_format($mobil->harga_mobil, 0, ',', '.') }}</td>
                            {{-- Kolom Aksi hanya tampil jika bukan sales --}}
                            @if(!in_array($job, ['sales']))
                            <td class="text-center">
                                <div class="d-flex justify-content-center align-items-center btn-group-actions">
                                    <button class="btn btn-sm btn-outline-info me-2 action-btn" data-bs-toggle="modal" data-bs-target="#detailModal"
                                        data-kode="{{ $mobil->kode_mobil }}"
                                        data-jenis="{{ $mobil->jenis_mobil }}"
                                        data-merek="{{ $mobil->merek_mobil }}"
                                        data-tipe="{{ $mobil->tipe_mobil }}"
                                        data-transmisi="{{ $mobil->transmisi }}" {{-- Tambahkan data-transmisi --}}
                                        data-tahun="{{ $mobil->tahun_pembuatan }}"
                                        data-warna="{{ $mobil->warna_mobil }}"
                                        data-bahanbakar="{{ ucfirst($mobil->bahan_bakar) }}"
                                        data-nopolisi="{{ $mobil->nomor_polisi }}"
                                        data-harga="Rp {{ number_format($mobil->harga_mobil, 0, ',', '.') }}"
                                        data-tanggalmasuk="{{ \Carbon\Carbon::parse($mobil->tanggal_masuk)->format('d M Y') }}"
                                        data-status="{{ ucfirst($mobil->status_mobil) }}"
                                        data-ketersediaan="{{ $mobil->ketersediaan }}"
                                        data-rangka="{{ $mobil->nomor_rangka }}"
                                        data-mesin="{{ $mobil->nomor_mesin }}"
                                        data-bpkb="{{ $mobil->nomor_bpkb }}"
                                        data-masapajak="{{ \Carbon\Carbon::parse($mobil->masa_berlaku_pajak)->format('d M Y') }}"
                                        title="Lihat Detail Mobil">
                                        <i class="bi bi-info-circle"></i> <span class="d-none d-md-inline">Detail</span>
                                    </button>
                                    {{-- Tombol Edit hanya untuk Admin --}}
                                    @if(in_array($job, ['admin']))
                                    <a href="{{ route('mobil.edit', $mobil->id) }}" class="btn btn-sm btn-outline-warning action-btn" title="Edit Data Mobil">
                                        <i class="bi bi-pencil-square"></i> <span class="d-none d-md-inline">Edit</span>
                                    </a>
                                    @endif
                                </div>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ !in_array($job, ['sales']) ? '14' : '13' }}" class="text-center text-muted py-5 empty-state"> <i class="bi bi-info-circle-fill fs-3 mb-2 d-block"></i> {{-- Perbarui colspan --}}
                                <p class="mb-1">Tidak ada hasil ditemukan untuk pencarian atau filter Anda.</p>
                                <p class="mb-0">Coba kata kunci atau filter lain.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-light border-top-0 py-3 px-4 d-flex justify-content-between align-items-center rounded-bottom-4 gap-3">
            <small class="text-muted">Data diperbarui terakhir: {{ \Carbon\Carbon::now()->format('d M Y H:i') }} WIB</small>
            {{-- Pagination Links --}}
            {{ $mobils->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg"> <div class="modal-content rounded-4 shadow-lg border-0">
            <div class="modal-header bg-info text-white border-0 rounded-top-4 p-3">
                <h5 class="modal-title fw-bold fs-5" id="detailModalLabel"><i class="bi bi-car-front-fill me-2"></i> Detail Data Mobil</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <p class="mb-1 text-muted small">Kode Mobil:</p>
                        <p class="mb-0 fw-bold" id="detail-kode"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p class="mb-1 text-muted small">Jenis Mobil:</p>
                        <p class="mb-0 fw-bold" id="detail-jenis"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p class="mb-1 text-muted small">Merek Mobil:</p>
                        <p class="mb-0 fw-bold" id="detail-merek"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p class="mb-1 text-muted small">Tipe Mobil:</p>
                        <p class="mb-0 fw-bold" id="detail-tipe"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p class="mb-1 text-muted small">Transmisi:</p> {{-- Detail Transmisi Baru --}}
                        <p class="mb-0 fw-bold" id="detail-transmisi"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p class="mb-1 text-muted small">Tahun Pembuatan:</p>
                        <p class="mb-0 fw-bold" id="detail-tahun"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p class="mb-1 text-muted small">Warna Mobil:</p>
                        <p class="mb-0 fw-bold" id="detail-warna"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p class="mb-1 text-muted small">Bahan Bakar:</p>
                        <p class="mb-0 fw-bold" id="detail-bahanbakar"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p class="mb-1 text-muted small">Nomor Polisi:</p>
                        <p class="mb-0 fw-bold" id="detail-nopolisi"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p class="mb-1 text-muted small">Tanggal Masuk:</p>
                        <p class="mb-0 fw-bold" id="detail-tanggalmasuk"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p class="mb-1 text-muted small">Status Mobil:</p>
                        <p class="mb-0 fw-bold" id="detail-status"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p class="mb-1 text-muted small">Ketersediaan:</p>
                        <p class="mb-0 fw-bold" id="detail-ketersediaan"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p class="mb-1 text-muted small">Harga Mobil:</p>
                        <p class="mb-0 fw-bold text-success" id="detail-harga"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p class="mb-1 text-muted small">No. Rangka:</p>
                        <p class="mb-0 fw-bold" id="detail-rangka"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p class="mb-1 text-muted small">No. Mesin:</p>
                        <p class="mb-0 fw-bold" id="detail-mesin"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p class="mb-1 text-muted small">No. BPKB:</p>
                        <p class="mb-0 fw-bold" id="detail-bpkb"></p>
                    </div>
                    {{-- Menambahkan Masa Berlaku Pajak di sini --}}
                    <div class="col-md-6 mb-3">
                        <p class="mb-1 text-muted small">Masa Berlaku Pajak:</p>
                        <p class="mb-0 fw-bold" id="detail-masapajak"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 p-3 bg-light rounded-bottom-4">
                <button type="button" class="btn btn-outline-secondary px-4 rounded-pill" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Google Fonts - Poppins */
    /* @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap'); Remove if already in main layout */

    body {
        background-color: #f8f9fa; /* Lighter, more neutral background */
        font-family: 'Poppins', sans-serif; /* Changed to Poppins for consistency */
        color: #343a40;
    }

    .container-fluid.py-4 {
        padding-top: 2.5rem !important;
        padding-bottom: 2.5rem !important;
    }

    /* Primary Button Style */
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

    /* Card Styling */
    .card {
        border-radius: 1rem !important; /* More rounded corners */
        overflow: hidden;
        box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.09) !important; /* Stronger, softer shadow */
    }

    .card-header {
        background-color: #ffffff;
        border-bottom: 1px solid #e9ecef;
    }

    /* Table Styling */
    .custom-table {
        border-collapse: separate;
        border-spacing: 0;
        table-layout: fixed; /* Added to fix column widths */
        width: 100%; /* Ensure table takes full width */
    }

    .custom-table thead th {
        background: linear-gradient(90deg, #495057, #6c757d); /* Darker gradient for header */
        color: #fff;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem; /* Slightly smaller font for header */
        padding: 1rem 0.8rem; /* More padding */
        border-bottom: none; /* Remove default border */
        white-space: nowrap; /* Prevent wrapping in headers */
    }

    .custom-table thead th:first-child {
        border-top-left-radius: 1rem;
    }

    .custom-table thead th:last-child {
        border-top-right-radius: 1rem;
    }

    .custom-table tbody tr {
        transition: all 0.2s ease;
        border-bottom: 1px solid #dee2e6; /* Subtle border between rows */
    }

    .custom-table tbody tr:last-child {
        border-bottom: none;
    }

    .custom-table tbody tr:hover {
        background-color: #f1f3f5; /* More distinct hover */
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.05); /* Lift effect on hover */
        transform: translateY(-1px);
    }

    .custom-table tbody td {
        padding: 0.8rem; /* Consistent padding */
        vertical-align: middle;
        font-size: 0.88rem;
        color: #495057;
        white-space: nowrap; /* Prevent content wrapping in cells */
        overflow: hidden; /* Hide overflowing content in cells */
        text-overflow: ellipsis; /* Add ellipsis for overflowing text */
    }

    /* Specific column width adjustments - these will be overridden by responsive design for smaller screens */
    .custom-table th:nth-child(1), .custom-table td:nth-child(1) { width: 100px; } /* Kode */
    .custom-table th:nth-child(2), .custom-table td:nth-child(2) { width: 100px; } /* Jenis Mobil */
    .custom-table th:nth-child(3), .custom-table td:nth-child(3) { width: 100px; } /* Merek */
    .custom-table th:nth-child(4), .custom-table td:nth-child(4) { width: 120px; } /* Tipe */
    .custom-table th:nth-child(5), .custom-table td:nth-child(5) { width: 100px; } /* Transmisi - New */
    .custom-table th:nth-child(6), .custom-table td:nth-child(6) { width: 80px; } /* Tahun */
    .custom-table th:nth-child(7), .custom-table td:nth-child(7) { width: 90px; } /* Warna */
    .custom-table th:nth-child(8), .custom-table td:nth-child(8) { width: 110px; } /* Bahan Bakar */
    .custom-table th:nth-child(9), .custom-table td:nth-child(9) { width: 120px; } /* No. Polisi */
    .custom-table th:nth-child(10), .custom-table td:nth-child(10) { width: 120px; } /* Tgl. Masuk */
    .custom-table th:nth-child(11), .custom-table td:nth-child(11) { width: 100px; } /* Status */
    .custom-table th:nth-child(12), .custom-table td:nth-child(12) { width: 140px; } /* Ketersediaan */
    .custom-table th:nth-child(13), .custom-table td:nth-child(13) { width: 150px; } /* Harga */
    .custom-table th:nth-child(14), .custom-table td:nth-child(14) { width: 280px; } /* Aksi */


    .text-muted-header {
        color: rgba(255, 255, 255, 0.7) !important; /* Slightly faded header text for less important columns */
        font-weight: 500 !important;
    }

    /* Badge styling */
    .badge {
        font-size: 0.75em; /* Slightly larger badge font */
        font-weight: 600;
        letter-spacing: 0.2px;
        padding: 0.5em 0.9em;
        vertical-align: middle;
    }

    /* Action buttons group */
    .btn-group-actions {
        gap: 0.5rem;
    }

    .action-btn {
        font-size: 0.8rem;
        padding: 0.4rem 0.7rem;
        border-radius: 0.5rem;
        transition: all 0.2s ease;
        white-space: nowrap; /* Prevent button text wrapping */
    }

    .action-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    /* Empty state styling */
    .empty-state {
        background-color: #fefefe;
        color: #6c757d;
        font-style: italic;
        padding: 3rem !important;
    }
    .empty-state i {
        color: #adb5bd;
    }

    /* Modal enhancements */
    .modal-header {
        border-bottom: none;
    }
    .modal-content {
        border: none;
    }
    .modal-body p.lead {
        font-size: 1.15rem;
        font-weight: 500;
    }
    .modal-body .alert-warning {
        background-color: #fff8eb; /* Softer warning background */
        border-left: 5px solid #ffc107;
        color: #6a4000;
        align-items: center;
        border-radius: 0.75rem;
    }
    .modal-body .alert-warning i {
        color: #ffc107;
    }
    .modal-footer {
        background-color: #f8f9fa;
        border-top: 1px solid #e9ecef;
        padding-top: 1rem;
        padding-bottom: 1rem;
    }
    .modal-footer .btn {
        font-weight: 500;
    }

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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Modal Logic (Delete) ---
        // Block for delete modal has been removed as per user request.

        // --- Script untuk Modal Detail Mobil ---
        const detailModal = document.getElementById('detailModal');
        if (detailModal) {
            detailModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget; // Tombol Detail yang diklik

                // Ambil semua data mobil dari atribut data-* tombol
                const data = {
                    kode: button.getAttribute('data-kode'),
                    jenis: button.getAttribute('data-jenis'),
                    merek: button.getAttribute('data-merek'),
                    tipe: button.getAttribute('data-tipe'),
                    transmisi: button.getAttribute('data-transmisi'), // Ambil data transmisi
                    tahun: button.getAttribute('data-tahun'),
                    warna: button.getAttribute('data-warna'),
                    bahanbakar: button.getAttribute('data-bahanbakar'),
                    nopolisi: button.getAttribute('data-nopolisi'),
                    harga: button.getAttribute('data-harga'),
                    tanggalmasuk: button.getAttribute('data-tanggalmasuk'),
                    status: button.getAttribute('data-status'),
                    ketersediaan: button.getAttribute('data-ketersediaan'),
                    rangka: button.getAttribute('data-rangka'),
                    mesin: button.getAttribute('data-mesin'),
                    bpkb: button.getAttribute('data-bpkb'),
                    masapajak: button.getAttribute('data-masapajak')
                };

                // Isi elemen-elemen di dalam modal dengan data yang diambil
                document.getElementById('detail-kode').textContent = data.kode;
                document.getElementById('detail-jenis').textContent = data.jenis;
                document.getElementById('detail-merek').textContent = data.merek;
                document.getElementById('detail-tipe').textContent = data.tipe;
                document.getElementById('detail-transmisi').textContent = data.transmisi; // Tampilkan di modal
                document.getElementById('detail-tahun').textContent = data.tahun;
                document.getElementById('detail-warna').textContent = data.warna;
                document.getElementById('detail-bahanbakar').textContent = data.bahanbakar;
                document.getElementById('detail-nopolisi').textContent = data.nopolisi;
                document.getElementById('detail-tanggalmasuk').textContent = data.tanggalmasuk;
                document.getElementById('detail-status').textContent = data.status;
                document.getElementById('detail-ketersediaan').textContent = data.ketersediaan;
                document.getElementById('detail-harga').textContent = data.harga;
                document.getElementById('detail-rangka').textContent = data.rangka;
                document.getElementById('detail-mesin').textContent = data.mesin;
                document.getElementById('detail-bpkb').textContent = data.bpkb;
                document.getElementById('detail-masapajak').textContent = data.masapajak;
            });
        }

        // --- Fungsi Pencarian dan Filter Tabel ---
        const searchInput = document.getElementById('searchInput');
        const merekFilter = document.getElementById('merekFilter');
        const tahunFilter = document.getElementById('tahunFilter');
        const transmisiFilter = document.getElementById('transmisiFilter'); // Filter Transmisi
        const statusFilter = document.getElementById('statusFilter');
        const ketersediaanFilter = document.getElementById('ketersediaanFilter');
        const resetFiltersBtn = document.getElementById('resetFilters');

        const mobilTable = document.getElementById('mobilTable');
        const tableRows = mobilTable.querySelectorAll('tbody tr.data-row');
        const tableResponsiveContainer = document.getElementById('tableResponsiveContainer');

        const scrollToRight = () => {
            if (tableResponsiveContainer) {
                tableResponsiveContainer.scrollLeft = tableResponsiveContainer.scrollWidth;
            }
        };

        // Scroll to the right on load and resize if table is wider than container
        scrollToRight();
        window.addEventListener('resize', scrollToRight);

        function applyFiltersAndSearch() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            const selectedMerek = merekFilter.value.toLowerCase().trim();
            const selectedTahun = tahunFilter.value.toLowerCase().trim();
            const selectedTransmisi = transmisiFilter.value.toLowerCase().trim(); // Ambil nilai filter transmisi
            const selectedStatus = statusFilter.value.toLowerCase().trim();
            const selectedKetersediaan = ketersediaanFilter.value.toLowerCase().trim();

            let foundVisibleRows = false;

            tableRows.forEach(row => {
                const jenis = row.getAttribute('data-jenis').toLowerCase();
                const merek = row.getAttribute('data-merek').toLowerCase();
                const transmisi = row.getAttribute('data-transmisi').toLowerCase(); // Ambil data transmisi dari baris
                const status = row.getAttribute('data-status').toLowerCase();
                const tahun = row.getAttribute('data-tahun').toLowerCase();
                const bahanbakar = row.getAttribute('data-bahanbakar').toLowerCase();
                const ketersediaan = row.getAttribute('data-ketersediaan').toLowerCase();
                const textContent = row.textContent.toLowerCase();

                const matchesSearch = textContent.includes(searchTerm);
                const matchesMerek = selectedMerek === '' || merek === selectedMerek;
                const matchesTahun = selectedTahun === '' || tahun === selectedTahun;
                const matchesTransmisi = selectedTransmisi === '' || transmisi === selectedTransmisi; // Filter transmisi
                const matchesStatus = selectedStatus === '' || status === selectedStatus;
                const matchesKetersediaan = selectedKetersediaan === '' || ketersediaan === selectedKetersediaan;

                if (matchesSearch && matchesMerek && matchesTahun && matchesTransmisi && matchesStatus && matchesKetersediaan) {
                    row.style.display = '';
                    foundVisibleRows = true;
                } else {
                    row.style.display = 'none';
                }
            });

            const emptyStateRow = mobilTable.querySelector('.empty-state');
            if (emptyStateRow) {
                if (foundVisibleRows) {
                    emptyStateRow.style.display = 'none';
                } else {
                    emptyStateRow.style.display = '';
                }
            }
        }

        searchInput.addEventListener('keyup', applyFiltersAndSearch);
        merekFilter.addEventListener('change', applyFiltersAndSearch);
        tahunFilter.addEventListener('change', applyFiltersAndSearch);
        transmisiFilter.addEventListener('change', applyFiltersAndSearch); // Event listener untuk filter transmisi
        statusFilter.addEventListener('change', applyFiltersAndSearch);
        ketersediaanFilter.addEventListener('change', applyFiltersAndSearch);

        resetFiltersBtn.addEventListener('click', function() {
            searchInput.value = '';
            merekFilter.value = '';
            tahunFilter.value = '';
            transmisiFilter.value = ''; // Reset filter transmisi
            statusFilter.value = '';
            ketersediaanFilter.value = '';
            applyFiltersAndSearch();
        });

        // Initial application of filters in case there are pre-filled values or on page load
        applyFiltersAndSearch();
    });
</script>
@endsection
