@extends('layouts.app')

@section('title', 'Daftar Pembeli')

@section('content')
{{-- Import Carbon Facade dan Storage Facade --}}
@php
    use Carbon\Carbon;
    use Illuminate\Support\Facades\Storage;

    $job = strtolower(auth()->user()->job ?? '');
@endphp

<head>
    {{-- Google Fonts Poppins --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    {{-- Animate.css untuk animasi masuk (opsional, bisa dihapus jika tidak dibutuhkan) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    {{-- Font Awesome untuk ikon --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    {{-- ======================================================= --}}
    {{-- Bagian CSS Internal untuk Styling Halaman Pembeli --}}
    {{-- ======================================================= --}}
    <style>
        /* Google Fonts - Poppins */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        body {
            background-color: #f0f2f5; /* Light gray background for a clean feel */
            font-family: 'Poppins', sans-serif;
            color: #343a40;
            line-height: 1.6; /* Improved readability */
        }

        .container-fluid.py-4 {
            padding-top: 2.5rem !important;
            padding-bottom: 2.5rem !important;
        }

        /* Consistent Heading Sizes */
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600; /* Semi-bold for headings */
            color: #343a40;
            margin-bottom: 0.5rem; /* Consistent spacing below headings */
        }
        h4.text-dark.fw-bold.mb-0 { /* Specific heading for page title */
            font-size: 1.75rem; /* Slightly larger for main title */
            margin-bottom: 1.5rem !important;
        }
        h5.mb-0 {
            font-size: 1.25rem; /* Consistent size for section titles */
        }
        h6 {
            font-size: 1rem;
            font-weight: 600;
            margin-top: 1rem;
            margin-bottom: 0.75rem;
        }

        /* Primary Button Style - New Pembeli */
        .btn-primary {
            background-color: #0d6efd; /* Bootstrap primary blue */
            border-color: #0d6efd;
            transition: all 0.3s ease;
            padding: 0.75rem 1.5rem; /* Consistent padding for primary button */
            font-size: 1rem; /* Consistent font size */
            border-radius: 0.5rem; /* Standard border-radius */
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
        /* Specific for the "Tambah Pembeli Baru" button to match previous design */
        .btn-primary.btn-lg {
            padding: 0.85rem 1.8rem; /* Slightly larger padding */
            border-radius: 2rem; /* Pill shape */
            font-size: 1.05rem; /* Slightly larger font */
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.25);
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
            padding: 1.5rem; /* Consistent padding */
            border-radius: 1rem 1rem 0 0;
        }

        .card-body {
            padding: 1.5rem; /* Consistent padding */
        }

        .card-footer {
            background-color: #f8f9fa;
            border-top: 1px solid #e9ecef;
            padding: 1rem 1.5rem; /* Consistent padding */
            border-radius: 0 0 1rem 1rem;
        }

        /* Table Styling */
        .table {
            margin-bottom: 0; /* Remove default table margin */
            width: 100%; /* Ensure full width */
            border-collapse: collapse; /* For clean borders */
        }

        .table thead th {
            background-color: #e9ecef; /* Light gray for table header */
            color: #495057; /* Darker text for header */
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
            padding: 1rem; /* Consistent padding */
            vertical-align: middle;
            font-size: 0.9rem; /* Slightly smaller font for table headers */
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
            padding: 1rem; /* Consistent padding */
            vertical-align: middle;
            border-top: 1px solid #e9ecef;
            font-size: 0.9rem; /* Consistent font size for table cells */
        }

        /* Custom Action Buttons */
        .btn-custom-detail, .btn-custom-edit, .btn-custom-delete {
            border: none;
            border-radius: 0.5rem;
            padding: 0.6rem 1.2rem; /* Consistent padding for action buttons */
            font-size: 0.875rem; /* Consistent font size */
            transition: all 0.2s ease;
            display: inline-flex; /* For icon alignment */
            align-items: center; /* Center icon and text vertically */
            justify-content: center; /* Center content horizontally */
            min-width: 90px; /* Minimum width for consistency */
        }

        .btn-custom-detail {
            background-color: #6c757d; /* Grey for detail */
            color: #fff;
        }
        .btn-custom-detail:hover {
            background-color: #5a6268;
            transform: translateY(-1px);
            box-shadow: 0 2px 5px rgba(108, 117, 125, 0.3);
        }

        .btn-custom-edit {
            background-color: #ffc107; /* Yellow for edit */
            color: #343a40;
        }
        .btn-custom-edit:hover {
            background-color: #e0a800;
            transform: translateY(-1px);
            box-shadow: 0 2px 5px rgba(255, 193, 7, 0.3);
        }

        /* This is now unused as delete button is removed. Keep if you might re-add. */
        /* .btn-custom-delete {
            background-color: #dc3545;
            color: #fff;
        }
        .btn-custom-delete:hover {
            background-color: #c82333;
            transform: translateY(-1px);
            box-shadow: 0 2px 5px rgba(220, 53, 69, 0.3);
        } */

        .btn-custom-detail i, .btn-custom-edit i { /* Removed .btn-custom-delete i */
            margin-right: 0.5rem; /* Spacing for icons in buttons */
        }

        /* Pagination Styling */
        .pagination .page-item .page-link {
            border-radius: 0.5rem;
            margin: 0 0.25rem;
            color: #0d6efd;
            border: 1px solid #dee2e6;
            transition: all 0.2s ease;
            padding: 0.6rem 0.9rem; /* Consistent padding */
            font-size: 0.9rem;
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

        /* Filter Section Styling */
        .filter-section {
            background-color: #ffffff;
            padding: 1.5rem;
            border-radius: 1rem;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08); /* Stronger shadow for cards */
            margin-bottom: 2rem; /* Consistent spacing below filter section */
        }
        .form-label {
            font-size: 0.875rem; /* Smaller font for labels */
            font-weight: 500;
            color: #6c757d;
            margin-bottom: 0.5rem;
        }
        .input-group .form-control,
        .input-group .input-group-text {
            border-radius: 0.5rem; /* Rounded corners for input group */
            font-size: 1rem;
            padding: 0.75rem 1rem; /* Consistent padding for inputs */
        }
        .input-group .input-group-text {
            background-color: #ffffff;
            border-right: none;
        }
        .input-group .form-control {
            border-left: none;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #0d6efd;
        }
        .btn-outline-secondary {
            border-radius: 0.5rem;
            font-size: 1rem;
            padding: 0.75rem 1rem;
            color: #6c757d;
            border-color: #6c757d;
        }
        .btn-outline-secondary:hover {
            background-color: #6c757d;
            color: #fff;
        }

        /* Modal Specific Styling */
        .modal-content {
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            font-family: 'Poppins', sans-serif;
        }
        .modal-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
            padding: 1.5rem;
            border-radius: 1rem 1rem 0 0;
        }
        .modal-title {
            font-weight: 600;
            color: #343a40;
            font-size: 1.5rem;
        }
        .modal-body {
            padding: 2rem;
            font-size: 0.95rem;
        }
        .modal-body strong {
            color: #495057;
            display: inline-block;
            min-width: 120px; /* Align labels */
            margin-right: 1rem;
            font-weight: 500;
        }
        .modal-footer {
            border-top: 1px solid #e9ecef;
            padding: 1.25rem 1.5rem;
            background-color: #f8f9fa;
            border-radius: 0 0 1rem 1rem;
        }
        .modal-footer .btn {
            padding: 0.6rem 1.2rem;
            font-size: 0.9rem;
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
        .empty-state p {
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }

        /* Utilities for spacing and alignment */
        .mb-1, .mb-2, .mb-3, .mb-4, .mb-5 { margin-bottom: var(--bs-spacing, 0.25rem) !important; }
        .py-4 { padding-top: 1.5rem !important; padding-bottom: 1.5rem !important; }
        .px-3 { padding-left: 1rem !important; padding-right: 1rem !important; }
        .px-md-4 { /* For medium and up screens */
            padding-left: 1.5rem !important;
            padding-right: 1.5rem !important;
        }

        /* Document Preview Styles */
        .document-preview-modal-v2 {
            width: 100%;
            max-width: 180px;
            height: 150px;
            border: 1px solid #e0e9f4; /* Subtle border */
            border-radius: 0.75rem;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            background-color: #fcfcfc; /* Subtle background */
            box-shadow: 0 0.1rem 0.3rem rgba(0, 0, 0, 0.05); /* Faint shadow */
            transition: all 0.25s ease-in-out;
            cursor: pointer; /* Indicate clickable */
        }

        .document-preview-modal-v2:hover {
            transform: translateY(-2px); /* Slight lift */
            box-shadow: 0 0.3rem 0.6rem rgba(0,0,0,0.1); /* More pronounced shadow */
            border-color: #a2d2ff; /* Highlight border with light blue */
        }

        .document-preview-modal-v2 img {
            max-width: 100%;
            max-height: 100px;
            display: block;
            object-fit: contain;
            padding: 8px;
        }

        .document-preview-modal-v2 .file-icon-preview-v2 {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: #6c757d;
            padding: 10px;
        }

        .document-preview-modal-v2 .file-icon-preview-v2 i {
            font-size: 3em;
            margin-bottom: 5px;
            color: #0d6efd;
        }

        .document-preview-modal-v2 .file-icon-preview-v2 span {
            display: block;
            font-size: 0.7em;
            font-weight: 500;
            word-break: break-all;
            color: #495057;
        }

        .document-preview-modal-v2 .preview-link-v2 {
            display: block;
            width: 100%;
            padding: 8px 0;
            text-align: center;
            font-size: 0.8em;
            background-color: transparent;
            border-top: none;
            text-decoration: none;
            color: #0d6efd; /* Primary link color */
            font-weight: 600;
            transition: text-decoration 0.2s ease, background-color 0.2s ease;
        }

        .document-preview-modal-v2 .preview-link-v2:hover {
            background-color: rgba(13, 110, 253, 0.08); /* Slightly more prominent background on hover */
            text-decoration: underline;
        }

        /* No document state */
        .document-preview-modal-v2 .no-document-v2 {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: #adb5bd;
            font-style: italic;
            padding: 10px;
        }

        .document-preview-modal-v2 .no-document-v2 i {
            font-size: 2.5em;
            margin-bottom: 5px;
            color: #ced4da;
        }

        /* Info section bordered */
        .info-section-bordered {
            border: 1px dashed #adb5bd; /* A subtle dashed border */
            border-radius: 0.75rem; /* Rounded corners */
            padding: 1.5rem; /* Internal padding */
            margin-bottom: 1rem;
            background-color: #fcfdfe; /* Slightly off-white background */
        }

        .info-section-bordered h6 class="text-primary" {
            margin-top: 0;
            margin-bottom: 1.5rem !important;
        }

        /* Full-screen image preview modal */
        #imagePreviewModal .modal-content {
            background-color: transparent !important; /* Fully transparent */
            box-shadow: none !important;
        }

        #imagePreviewModal .modal-header {
            position: absolute;
            top: 0;
            right: 0;
            z-index: 1050; /* Above the image */
            background: transparent !important; /* No background */
            border-bottom: none !important;
        }

        #imagePreviewModal .modal-body {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0;
            height: 100%;
            width: 100%;
        }

        #imagePreviewModal .modal-dialog {
            margin: 0;
        }

        /* Modal header for detail modal */
        .modal-header.bg-info {
            background-color: #17a2b8 !important; /* Bootstrap info blue */
            background: linear-gradient(45deg, #17a2b8, #20c997) !important; /* Gradient for modal header */
            box-shadow: 0 5px 15px rgba(23, 162, 184, 0.2);
            color: white;
        }
    </style>
</head>

<div class="container-fluid py-4 px-3 px-md-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8 mb-3 mb-md-0">
            <h4 class="text-dark fw-bold mb-0">Daftar Pembeli</h4>
            <small class="text-secondary">Kelola semua informasi pembeli Anda dengan mudah.</small>
        </div>
        <div class="col-md-4 text-md-end">
            @if(in_array($job, ['admin']))
            <a href="{{ route('pembeli.create') }}" class="btn btn-primary btn-lg shadow-lg rounded-pill animate__animated animate__fadeInRight">
                <i class="bi bi-person-plus-fill me-2"></i> Tambah Pembeli Baru
            </a>
            @endif
        </div>
    </div>

    {{-- Filter and Search Section --}}
    <div class="filter-section animate__animated animate__fadeInUp">
        <div class="row g-3 align-items-end">
            <div class="col-md-10"> {{-- Diubah ke col-md-10 untuk lebar penuh --}}
                <label for="searchInput" class="form-label text-muted">Cari Pembeli</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                    {{-- Tambahkan value dari $search --}}
                    <input type="text" id="searchInput" class="form-control border-start-0 rounded-end" placeholder="Cari berdasarkan kode, nama, atau nomor telepon..." value="{{ $search ?? '' }}">
                </div>
            </div>
            {{-- Filter Pekerjaan Dihapus --}}
            <div class="col-md-2 text-end"> {{-- Diubah ke col-md-2 dan offset dihapus --}}
                <button id="resetFiltersBtn" class="btn btn-outline-secondary w-100">
                    <i class="bi bi-arrow-counterclockwise me-2"></i> Reset Filter
                </button>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-xl rounded-4 animate__animated animate__fadeInUp">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-dark fw-bold">Data Pembeli</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle" id="pembeliTable">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center">#</th>
                            <th scope="col" data-sort-type="text">Kode Pembeli</th>
                            <th scope="col" data-sort-type="text">Nama</th>
                            <th scope="col" data-sort-type="date">Tanggal Lahir</th>
                            <th scope="col" data-sort-type="text">Pekerjaan</th>
                            <th scope="col" data-sort-type="text">Alamat</th>
                            <th scope="col" data-sort-type="text">No. Telepon</th>
                            {{-- Kolom Email Dihapus --}}
                            <th scope="col" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pembelis as $pembeli)
                            <tr class="data-row"
                                data-id="{{ $pembeli->id }}"
                                data-kode-pembeli="{{ strtolower($pembeli->kode_pembeli) }}"
                                data-nama="{{ strtolower($pembeli->nama) }}"
                                {{-- Gunakan optional() untuk menghindari error jika tanggal_lahir NULL --}}
                                {{-- Format Y-m-d untuk sorting JS yang optimal --}}
                                data-tanggal-lahir-iso="{{ optional($pembeli->tanggal_lahir)->format('Y-m-d') }}"
                                {{-- Format d M Y untuk tampilan dan detail modal --}}
                                data-tanggal-lahir-formatted="{{ optional($pembeli->tanggal_lahir)->translatedFormat('d M Y') }}"
                                data-pekerjaan="{{ strtolower($pembeli->pekerjaan) }}"
                                data-alamat="{{ strtolower($pembeli->alamat) }}"
                                data-no-telepon="{{ strtolower($pembeli->no_telepon) }}"
                                {{-- Email Dihapus dari data-* --}}
                                {{-- Dokumen --}}
                                data-ktp-pasangan="{{ $pembeli->ktp_pasangan ? Storage::url($pembeli->ktp_pasangan) : '' }}"
                                data-kartu-keluarga="{{ $pembeli->kartu_keluarga ? Storage::url($pembeli->kartu_keluarga) : '' }}"
                                data-slip-gaji="{{ $pembeli->slip_gaji ? Storage::url($pembeli->slip_gaji) : '' }}"
                                {{-- Created/Updated At --}}
                                data-created-at="{{ optional($pembeli->created_at)->format('d M Y, H:i') }}"
                                data-updated-at="{{ optional($pembeli->updated_at)->format('d M Y, H:i') }}">
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $pembeli->kode_pembeli }}</td>
                                <td>{{ $pembeli->nama }}</td>
                                {{-- Tampilkan tanggal dalam format yang diinginkan (contoh: 06 Apr 1992) --}}
                                <td>{{ optional($pembeli->tanggal_lahir)->translatedFormat('d M Y') ?: 'N/A' }}</td>
                                <td>{{ $pembeli->pekerjaan }}</td>
                                <td>{{ $pembeli->alamat }}</td>
                                <td>{{ $pembeli->no_telepon }}</td>
                                {{-- Kolom Email Dihapus --}}
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button type="button" class="btn btn-custom-detail view-detail-btn" data-bs-toggle="modal" data-bs-target="#detailPembeliModal"
                                            data-pembeli="{{ json_encode([
                                                'id' => $pembeli->id,
                                                'kode_pembeli' => $pembeli->kode_pembeli,
                                                'nama' => $pembeli->nama,
                                                'tanggal_lahir' => optional($pembeli->tanggal_lahir)->format('Y-m-d'), // Kirim Y-m-d ke JS untuk parsing Date
                                                'pekerjaan' => $pembeli->pekerjaan,
                                                'alamat' => $pembeli->alamat,
                                                'no_telepon' => $pembeli->no_telepon,
                                                // 'email' => $pembeli->email ?? '', // Email Dihapus dari JSON
                                                'ktp_pasangan' => $pembeli->ktp_pasangan ? Storage::url($pembeli->ktp_pasangan) : null,
                                                'kartu_keluarga' => $pembeli->kartu_keluarga ? Storage::url($pembeli->kartu_keluarga) : null,
                                                'slip_gaji' => $pembeli->slip_gaji ? Storage::url($pembeli->slip_gaji) : null,
                                                'created_at' => optional($pembeli->created_at)->format('d M Y, H:i'),
                                                'updated_at' => optional($pembeli->updated_at)->format('d M Y, H:i'),
                                            ]) }}">
                                            <i class="fas fa-info-circle"></i> Detail
                                        </button>
                                        @if(in_array($job, ['admin']))
                                        <a href="{{ route('pembeli.edit', $pembeli->id) }}" class="btn btn-custom-edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        @endif
                                        {{-- Tombol Hapus Dihapus --}}
                                        {{--
                                        <form action="{{ route('pembeli.destroy', $pembeli->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pembeli ini?');" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-custom-delete">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                        --}}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted empty-state"> {{-- Colspan disesuaikan (8 kolom asli - 1 email - 1 hapus = 6) --}}
                                    <i class="bi bi-info-circle-fill mb-2 d-block"></i>
                                    <p class="mb-1">Tidak ada data pembeli yang ditemukan.</p>
                                    <p class="mb-0">Coba ubah filter atau tambahkan pembeli baru.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-light border-0 py-3 px-4 d-flex flex-column flex-md-row justify-content-between align-items-center rounded-bottom-4 gap-3">
            <small class="text-muted">Data diperbarui terakhir: {{ Carbon::now()->format('d M Y H:i') }} WIB</small>
            {{-- Pagination Links (Pastikan $pembelis adalah instance Paginator) --}}
            {{ optional($pembelis)->appends(request()->query())->links('pagination::bootstrap-5') }} {{-- Tambahkan appends() --}}
        </div>
    </div>
</div>

<div class="modal fade" id="detailPembeliModal" tabindex="-1" aria-labelledby="detailPembeliModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 shadow-lg border-0">
            <div class="modal-header bg-info text-white border-0 rounded-top-4 p-3">
                <h5 class="modal-title fw-bold fs-5" id="detailPembeliModalLabel"><i class="bi bi-person-lines-fill me-2"></i> Detail Data Pembeli: <span id="modalPembeliNama"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="info-section-bordered">
                            <h6 class="text-primary mb-3">Informasi Utama</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label detail-label-new">ID Pembeli:</label>
                                    <p class="form-control-plaintext detail-value-new" id="modalPembeliId"></p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label detail-label-new">Kode Pembeli:</label>
                                    <p class="form-control-plaintext detail-value-new" id="modalPembeliKodePembeli"></p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label detail-label-new">Nama Lengkap:</label>
                                    <p class="form-control-plaintext detail-value-new" id="modalPembeliNamaDetail"></p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label detail-label-new">Tanggal Lahir:</label>
                                    <p class="form-control-plaintext detail-value-new" id="modalPembeliTanggalLahir"></p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label detail-label-new">Pekerjaan:</label>
                                    <p class="form-control-plaintext detail-value-new" id="modalPembeliPekerjaan"></p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label detail-label-new">No. Telepon:</label>
                                    <p class="form-control-plaintext detail-value-new" id="modalPembeliNoTelepon"></p>
                                </div>
                                <div class="col-12">
                                    <label class="form-label detail-label-new">Alamat:</label>
                                    <p class="form-control-plaintext detail-value-new" id="modalPembeliAlamat"></p>
                                </div>
                                {{-- Baris Email Dihapus --}}
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="info-section-bordered">
                    <h6 class="text-primary mb-4">Dokumen Pendukung</h6>
                    <div class="row g-3 g-md-4 justify-content-center">
                        {{-- KTP Pasangan --}}
                        <div class="col-sm-6 col-md-4 d-flex justify-content-center">
                            <div>
                                <p class="mb-2 text-muted small text-center">KTP Suami/Istri:</p>
                                <div id="detail_ktp_pasangan_preview" class="document-preview-modal-v2"></div>
                            </div>
                        </div>
                        {{-- Kartu Keluarga --}}
                        <div class="col-sm-6 col-md-4 d-flex justify-content-center">
                            <div>
                                <p class="mb-2 text-muted small text-center">Kartu Keluarga:</p>
                                <div id="detail_kartu_keluarga_preview" class="document-preview-modal-v2"></div>
                            </div>
                        </div>
                        {{-- Slip Gaji --}}
                        <div class="col-sm-6 col-md-4 d-flex justify-content-center">
                            <div>
                                <p class="mb-2 text-muted small text-center">Slip Gaji:</p>
                                <div id="detail_slip_gaji_preview" class="document-preview-modal-v2"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="info-section-bordered">
                    <h6 class="text-primary mb-3">Informasi Sistem</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label detail-label-new">Dibuat Pada:</label>
                            <p class="form-control-plaintext detail-value-new" id="modalPembeliCreatedAt"></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label detail-label-new">Terakhir Diperbarui:</label>
                            <p class="form-control-plaintext detail-value-new" id="modalPembeliUpdatedAt"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 p-3 bg-light rounded-bottom-4">
                <button type="button" class="btn btn-outline-secondary px-4 rounded" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- NEW MODAL FOR FULL-SCREEN IMAGE PREVIEW --}}
<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen d-flex justify-content-center align-items-center">
        <div class="modal-content bg-transparent border-0 rounded-0">
            <div class="modal-header border-0 pb-0 pt-3 px-4">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 d-flex justify-content-center align-items-center">
                <img src="" id="fullImagePreview" class="img-fluid" style="max-height: 90vh; max-width: 90vw; object-fit: contain;" alt="Full Image Preview">
            </div>
        </div>
    </div>
</div>


{{-- Bootstrap Bundle with Popper (pastikan ini di-link jika belum ada di layout utama) --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

{{-- ======================================================= --}}
{{-- Bagian JavaScript Internal untuk Fungsionalitas Halaman Pembeli --}}
{{-- ======================================================= --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const detailPembeliModalElement = document.getElementById('detailPembeliModal');
        const imagePreviewModalElement = document.getElementById('imagePreviewModal');

        // Get Bootstrap modal instances
        const detailPembeliModal = new bootstrap.Modal(detailPembeliModalElement);
        const imagePreviewModal = new bootstrap.Modal(imagePreviewModalElement);

        // --- Fungsi untuk Menampilkan Pratinjau Dokumen ---
        // Digunakan untuk KTP Pasangan, Kartu Keluarga, dan Slip Gaji
        function populateDocumentPreview(fileUrl, previewId) {
            const previewContainer = document.getElementById(previewId);
            if (!previewContainer) {
                console.warn(`Preview container with ID '${previewId}' not found. Skipping population.`);
                return;
            }

            previewContainer.innerHTML = ''; // Hapus konten sebelumnya

            if (fileUrl) {
                const fileName = fileUrl.split('/').pop().split('?')[0];
                const fileExtension = fileName.split('.').pop().toLowerCase();

                const clickableWrapper = document.createElement('div');
                clickableWrapper.className = 'document-preview-content';
                clickableWrapper.style.flexGrow = '1';
                clickableWrapper.style.display = 'flex';
                clickableWrapper.style.flexDirection = 'column';
                clickableWrapper.style.justifyContent = 'center';
                clickableWrapper.style.alignItems = 'center';
                clickableWrapper.style.textAlign = 'center';
                clickableWrapper.style.padding = '10px';

                // Jika file adalah gambar, buat dapat diklik untuk pratinjau penuh
                if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
                    clickableWrapper.style.cursor = 'pointer';
                    clickableWrapper.setAttribute('data-image-src', fileUrl);

                    // Tambahkan event listener untuk membuka modal pratinjau gambar
                    clickableWrapper.addEventListener('click', function() {
                        detailPembeliModal.hide(); // Sembunyikan modal detail dulu
                        document.getElementById('fullImagePreview').src = fileUrl;
                        imagePreviewModal.show(); // Lalu tampilkan modal pratinjau gambar
                    });

                    const img = document.createElement('img');
                    img.src = fileUrl;
                    img.alt = fileName;
                    clickableWrapper.appendChild(img);

                    const fileNameSpan = document.createElement('span');
                    fileNameSpan.textContent = fileName;
                    fileNameSpan.style.fontSize = '0.7em';
                    fileNameSpan.style.fontWeight = '500';
                    fileNameSpan.style.wordBreak = 'break-all';
                    fileNameSpan.style.color = '#495057';
                    clickableWrapper.appendChild(fileNameSpan);

                    previewContainer.appendChild(clickableWrapper);

                } else {
                    // Untuk jenis file lain (misalnya PDF)
                    clickableWrapper.innerHTML = `<div class="file-icon-preview-v2"><i class="fas fa-file-${fileExtension === 'pdf' ? 'pdf' : 'alt'}"></i><span>${fileName}</span></div>`;
                    const link = document.createElement('a');
                    link.href = fileUrl;
                    link.target = '_blank';
                    link.className = 'preview-link-v2';
                    link.textContent = 'Lihat Dokumen';

                    previewContainer.appendChild(clickableWrapper);
                    previewContainer.appendChild(link);
                }
            } else {
                // Tampilkan pesan jika tidak ada dokumen
                previewContainer.innerHTML = '<div class="no-document-v2"><i class="fas fa-file-excel"></i><span class="text-muted">Tidak Ada Dokumen</span></div>';
            }
        }

        // --- Logika Modal Detail Pembeli ---
        if (detailPembeliModalElement) {
            detailPembeliModalElement.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget; // Tombol yang memicu modal
                const pembeliData = JSON.parse(button.getAttribute('data-pembeli')); // Dapatkan data dari atribut data-pembeli

                // Isi bidang teks
                document.getElementById('modalPembeliId').textContent = pembeliData.id;
                document.getElementById('modalPembeliKodePembeli').textContent = pembeliData.kode_pembeli.toUpperCase();
                document.getElementById('modalPembeliNama').textContent = pembeliData.nama.replace(/\b\w/g, s => s.toUpperCase());
                document.getElementById('modalPembeliNamaDetail').textContent = pembeliData.nama.replace(/\b\w/g, s => s.toUpperCase());

                // Format tanggal lahir untuk modal detail (contoh: 06 April 1992)
                if (pembeliData.tanggal_lahir) {
                    const date = new Date(pembeliData.tanggal_lahir);
                    document.getElementById('modalPembeliTanggalLahir').textContent = date.toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: 'long',
                        year: 'numeric'
                    });
                } else {
                    document.getElementById('modalPembeliTanggalLahir').textContent = 'Tidak Tersedia';
                }

                document.getElementById('modalPembeliPekerjaan').textContent = pembeliData.pekerjaan.replace(/\b\w/g, s => s.toUpperCase());
                document.getElementById('modalPembeliAlamat').textContent = pembeliData.alamat.replace(/\b\w/g, s => s.toUpperCase());
                document.getElementById('modalPembeliNoTelepon').textContent = pembeliData.no_telepon;
                // document.getElementById('modalPembeliEmail').textContent = pembeliData.email || 'Tidak Tersedia'; // Baris Email Dihapus
                document.getElementById('modalPembeliCreatedAt').textContent = pembeliData.created_at || 'Tidak Tersedia';
                document.getElementById('modalPembeliUpdatedAt').textContent = pembeliData.updated_at || 'Tidak Tersedia';

                // Isi pratinjau dokumen
                populateDocumentPreview(pembeliData.ktp_pasangan, 'detail_ktp_pasangan_preview');
                populateDocumentPreview(pembeliData.kartu_keluarga, 'detail_kartu_keluarga_preview');
                populateDocumentPreview(pembeliData.slip_gaji, 'detail_slip_gaji_preview');
            });
        }

        // --- Logika Modal Pratinjau Gambar Penuh ---
        if (imagePreviewModalElement) {
            // Ketika modal pratinjau gambar sepenuhnya disembunyikan, tampilkan kembali modal detail
            imagePreviewModalElement.addEventListener('hidden.bs.modal', function () {
                document.getElementById('fullImagePreview').src = ''; // Bersihkan sumber gambar
                detailPembeliModal.show(); // Tampilkan kembali modal detail
            });
        }

        // --- Logika Filter dan Pencarian BERDASARKAN URL ---
        const searchInput = document.getElementById('searchInput');
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

            window.location.href = newUrl.toString(); // Redirect ke URL baru
        }

        // Event listener untuk input pencarian (saat menekan Enter)
        searchInput.addEventListener('keypress', function(event) {
            if (event.key === 'Enter') {
                applyFiltersToUrl();
            }
        });

        // Event listener untuk tombol "Reset Filter"
        resetFiltersBtn.addEventListener('click', function() {
            searchInput.value = '';
            applyFiltersToUrl(); // Terapkan filter kosong untuk mereset
        });

        // --- Logika Pengurutan Tabel (tetap di sisi klien karena data yang difilter sudah ada di halaman) ---
        // Perhatikan: Jika Anda memfilter dengan server, sorting ini hanya akan mengurutkan data yang *sudah ada* di halaman saat ini.
        // Untuk sorting yang mencakup semua data, sorting juga harus dilakukan di server (controller).
        document.querySelectorAll('th[data-sort-type]').forEach(header => {
            header.addEventListener('click', function() {
                const table = this.closest('table');
                const tbody = table.querySelector('tbody');
                const rows = Array.from(tbody.querySelectorAll('tr.data-row')); // Hanya urutkan baris data
                const columnIndex = Array.from(this.parentNode.children).indexOf(this);
                const sortType = this.dataset.sortType;
                const currentDirection = this.dataset.sortDirection || 'asc';
                const newDirection = currentDirection === 'asc' ? 'desc' : 'asc';

                // Hapus kelas dan atribut arah pengurutan dari semua header
                document.querySelectorAll('th[data-sort-type]').forEach(th => {
                    th.classList.remove('asc', 'desc');
                    th.removeAttribute('data-sort-direction'); // Hapus data-sort-direction
                });

                // Tambahkan kelas dan atribut arah pengurutan baru ke header yang diklik
                this.classList.add(newDirection);
                this.dataset.sortDirection = newDirection;

                rows.sort((rowA, rowB) => {
                    let valA, valB;

                    if (sortType === 'date') {
                        // Untuk mengurutkan tanggal, gunakan format ISO (YYYY-MM-DD) dari data-tanggal-lahir-iso
                        valA = rowA.dataset.tanggalLahirIso || '';
                        valB = rowB.dataset.tanggalLahirIso || '';

                        // Konversi ke objek Date untuk perbandingan yang akurat
                        const dateA = valA ? new Date(valA) : new Date(0); // Gunakan epoch untuk tanggal null/tidak valid
                        const dateB = valB ? new Date(valB) : new Date(0);

                        let comparison = 0;
                        if (dateA > dateB) {
                            comparison = 1;
                        } else if (dateA < dateB) {
                            comparison = -1;
                        }
                        return newDirection === 'asc' ? comparison : -comparison;

                    } else if (sortType === 'numeric') {
                        // Tidak ada kolom numerik di sini yang perlu sorting kustom, tapi tetap pertahankan jika ada.
                        valA = parseFloat(rowA.children[columnIndex].textContent.trim().replace(/[^0-9.-]+/g,""));
                        valB = parseFloat(rowB.children[columnIndex].textContent.trim().replace(/[^0-9.-]+/g,""));
                    } else { // 'text' type
                        valA = rowA.children[columnIndex].textContent.trim().toLowerCase();
                        valB = rowB.children[columnIndex].textContent.trim().toLowerCase();
                    }

                    let comparison = 0;
                    if (valA > valB) {
                        comparison = 1;
                    } else if (valA < valB) {
                        comparison = -1;
                    }

                    return newDirection === 'asc' ? comparison : -comparison;
                });

                // Tambahkan kembali baris yang sudah diurutkan ke body tabel
                rows.forEach(row => tbody.appendChild(row));
            });
        });
    });
</script>
@endsection
