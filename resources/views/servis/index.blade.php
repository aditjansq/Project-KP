@extends('layouts.app')

@section('title', 'Daftar Servis')

@section('content')
{{-- Impor Carbon Facade --}}
@php
    use Carbon\Carbon;
@endphp

<head>
    {{-- Google Fonts Poppins --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    {{-- Animate.css untuk animasi masuk --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    {{-- Font Awesome untuk ikon --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    {{-- ======================================================= --}}
    {{-- Bagian CSS Internal untuk Styling Halaman Servis (Meniru Penjual) --}}
    {{-- ======================================================= --}}
    <style>
        /* Google Fonts - Poppins */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        body {
            background-color: #f0f2f5; /* Latar belakang abu-abu muda untuk tampilan bersih */
            font-family: 'Poppins', sans-serif;
            color: #343a40;
            line-height: 1.6; /* Keterbacaan yang ditingkatkan */
        }

        .container-fluid.py-4 {
            padding-top: 2.5rem !important;
            padding-bottom: 2.5rem !important;
        }

        /* Ukuran Heading yang Konsisten */
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600; /* Semi-bold untuk heading */
            color: #343a40;
            margin-bottom: 0.5rem; /* Spasi yang konsisten di bawah heading */
        }
        h4.text-dark.fw-bold.mb-0 { /* Heading spesifik untuk judul halaman */
            font-size: 1.75rem; /* Sedikit lebih besar untuk judul utama */
            margin-bottom: 1.5rem !important;
        }
        h5.mb-0 {
            font-size: 1.25rem; /* Ukuran yang konsisten untuk judul bagian */
        }
        h6 {
            font-size: 1rem;
            font-weight: 600;
            margin-top: 1rem;
            margin-bottom: 0.75rem;
        }

        /* Gaya Tombol Utama - Servis Baru (Meniru Penjual) */
        .btn-primary {
            background-color: #0d6efd; /* Biru utama Bootstrap */
            border-color: #0d6efd;
            transition: all 0.3s ease;
            padding: 0.75rem 1.5rem; /* Padding yang konsisten untuk tombol utama */
            font-size: 1rem; /* Ukuran font yang konsisten */
            border-radius: 0.5rem; /* Border-radius standar */
        }
        .btn-primary:hover {
            background-color: #0b5ed7; /* Biru lebih gelap saat hover */
            border-color: #0a58ca;
            transform: translateY(-2px); /* Efek sedikit terangkat */
            box-shadow: 0 4px 10px rgba(0, 123, 255, 0.2); /* Bayangan lembut */
        }
        .btn-primary:active {
            transform: translateY(0);
            box-shadow: none;
        }
        /* Spesifik untuk tombol "Tambah Servis Baru" agar sesuai dengan desain sebelumnya */
        .btn-primary.btn-lg {
            padding: 0.85rem 1.8rem; /* Padding sedikit lebih besar */
            border-radius: 2rem; /* Bentuk pil */
            font-size: 1.05rem; /* Font sedikit lebih besar */
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.25);
        }

        /* Gaya Kartu (Meniru Penjual) */
        .card {
            border: none;
            border-radius: 1rem; /* Sudut lebih membulat */
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08); /* Bayangan lebih kuat untuk kartu */
        }

        .card-header {
            background-color: #ffffff;
            border-bottom: 1px solid #e9ecef;
            padding: 1.5rem; /* Padding yang konsisten */
            border-radius: 1rem 1rem 0 0;
        }

        .card-body {
            padding: 1.5rem; /* Padding yang konsisten */
        }

        .card-footer {
            background-color: #f8f9fa;
            border-top: 1px solid #e9ecef;
            padding: 1rem 1.5rem; /* Padding yang konsisten */
            border-radius: 0 0 1rem 1rem;
        }

        /* Gaya Tabel (Meniru Penjual) */
        .table {
            margin-bottom: 0; /* Hapus margin tabel default */
            width: 100%; /* Pastikan lebar penuh */
            border-collapse: collapse; /* Untuk batas yang bersih */
        }

        .table thead th {
            background-color: #e9ecef; /* Abu-abu muda untuk header tabel */
            color: #495057; /* Teks lebih gelap untuk header */
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
            padding: 1rem; /* Padding yang konsisten */
            vertical-align: middle;
            font-size: 0.9rem; /* Font sedikit lebih kecil untuk header tabel */
            cursor: pointer; /* Menunjukkan kolom yang dapat diurutkan */
            position: relative;
            padding-right: 25px; /* Sediakan ruang untuk ikon */
        }

        .table thead th:hover {
            background-color: #e2e6ea;
        }

        /* Selalu tampilkan kedua panah, sesuaikan opasitas/warna saat diurutkan */
        .table thead th[data-sort-type]::before,
        .table thead th[data-sort-type]::after {
            content: '';
            position: absolute;
            right: 10px;
            font-size: 0.7em;
            color: #adb5bd; /* Abu-abu samar */
            opacity: 0.5;
            transition: opacity 0.2s ease, color 0.2s ease;
        }

        .table thead th[data-sort-type]::before {
            content: '\25B2'; /* Panah atas */
            top: 35%; /* Sesuaikan posisi */
        }

        .table thead th[data-sort-type]::after {
            content: '\25BC'; /* Panah bawah */
            top: 65%; /* Sesuaikan posisi */
        }

        /* Sorot arah pengurutan aktif */
        .table thead th.asc::before {
            opacity: 1;
            color: #0d6efd; /* Sorot pengurutan aktif */
        }

        .table thead th.desc::after {
            opacity: 1;
            color: #0d6efd; /* Sorot pengurutan aktif */
        }

        /* Samarkan panah yang tidak aktif untuk kolom yang diurutkan */
        .table thead th.asc::after,
        .table thead th.desc::before {
            opacity: 0.2;
        }

        /* Atur ulang opasitas untuk kolom yang tidak diurutkan */
        .table thead th:not(.asc):not(.desc)::before,
        .table thead th:not(.asc):not(.desc)::after {
            opacity: 0.5;
            color: #adb5bd;
        }

        .table tbody tr {
            transition: all 0.2s ease-in-out;
        }

        .table tbody tr:hover {
            background-color: #f2f4f7; /* Sorotan lebih terang saat hover */
        }

        .table tbody td {
            padding: 1rem; /* Padding yang konsisten */
            vertical-align: middle;
            border-top: 1px solid #e9ecef;
            font-size: 0.9rem; /* Ukuran font yang konsisten untuk sel tabel */
        }

        /* Tombol Aksi Kustom (Meniru Penjual) */
        .btn-custom-detail, .btn-custom-edit {
            border: none;
            border-radius: 0.5rem;
            padding: 0.6rem 1.2rem; /* Padding yang konsisten untuk tombol aksi */
            font-size: 0.875rem; /* Ukuran font yang konsisten */
            transition: all 0.2s ease;
            display: inline-flex; /* Untuk perataan ikon */
            align-items: center; /* Pusatkan ikon dan teks secara vertikal */
            justify-content: center; /* Pusatkan konten secara horizontal */
            min-width: 90px; /* Lebar minimum untuk konsistensi */
        }

        .btn-custom-detail {
            background-color: #6c757d; /* Abu-abu untuk detail */
            color: #fff;
        }
        .btn-custom-detail:hover {
            background-color: #5a6268;
            transform: translateY(-1px);
            box-shadow: 0 2px 5px rgba(108, 117, 125, 0.3);
        }

        .btn-custom-edit {
            background-color: #ffc107; /* Kuning untuk edit */
            color: #343a40;
        }
        .btn-custom-edit:hover {
            background-color: #e0a800;
            transform: translateY(-1px);
            box-shadow: 0 2px 5px rgba(255, 193, 7, 0.3);
        }

        .btn-custom-detail i, .btn-custom-edit i {
            margin-right: 0.5rem; /* Spasi untuk ikon di tombol */
        }

        /* Gaya Paginasi (Meniru Penjual) */
        .pagination .page-item .page-link {
            border-radius: 0.5rem;
            margin: 0 0.25rem;
            color: #0d6efd;
            border: 1px solid #dee2e6;
            transition: all 0.2s ease;
            padding: 0.6rem 0.9rem; /* Padding yang konsisten */
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

        /* Gaya Bagian Filter (Meniru Penjual) */
        .filter-section {
            background-color: #ffffff;
            padding: 1.5rem;
            border-radius: 1rem;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08); /* Bayangan lebih kuat untuk kartu */
            margin-bottom: 2rem; /* Spasi yang konsisten di bawah bagian filter */
        }
        .form-label {
            font-size: 0.875rem; /* Font lebih kecil untuk label */
            font-weight: 500;
            color: #6c757d;
            margin-bottom: 0.5rem;
        }
        .input-group .form-control,
        .input-group .input-group-text {
            border-radius: 0.5rem; /* Sudut membulat untuk grup input */
            font-size: 1rem;
            padding: 0.75rem 1rem; /* Padding yang konsisten untuk input */
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

        /* Gaya Spesifik Modal (Meniru Penjual) */
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
            min-width: 120px; /* Sejajarkan label */
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

        /* Status Kosong (Meniru Penjual) */
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

        /* Utilitas untuk spasi dan perataan */
        .mb-1, .mb-2, .mb-3, .mb-4, .mb-5 { margin-bottom: var(--bs-spacing, 0.25rem) !important; }
        .py-4 { padding-top: 1.5rem !important; padding-bottom: 1.5rem !important; }
        .px-3 { padding-left: 1rem !important; padding-right: 1rem !important; }
        .px-md-4 { /* Untuk layar sedang dan lebih besar */
            padding-left: 1.5rem !important;
            padding-right: 1.5rem !important;
        }

        /* Bagian Info Berbatas (Meniru Penjual) */
        .info-section-bordered {
            border: 1px dashed #adb5bd; /* Batas putus-putus yang halus */
            border-radius: 0.75rem; /* Sudut membulat */
            padding: 1.5rem; /* Padding internal */
            margin-bottom: 1rem;
            background-color: #fcfdfe; /* Latar belakang sedikit off-white */
        }

        .info-section-bordered h6 class="text-primary" {
            margin-top: 0;
            margin-bottom: 1.5rem !important;
        }

        /* Header Modal untuk Modal Detail (Meniru Penjual) */
        .modal-header.bg-info {
            background-color: #17a2b8 !important; /* Biru info Bootstrap */
            background: linear-gradient(45deg, #17a2b8, #20c997) !important; /* Gradien untuk header modal */
            box-shadow: 0 5px 15px rgba(23, 162, 184, 0.2);
            color: white;
        }

        /* Gaya Peringatan (Sukses) - Disempurnakan (Meniru Penjual) */
        .alert-success {
            background-color: #eafaea; /* Hijau sangat muda */
            color: #218838; /* Hijau sukses standar */
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
            color: #218838; /* Jadikan tombol tutup hijau */
            opacity: 0.7;
        }
        .alert-success .btn-close:hover {
            opacity: 1;
        }

        /* Styling kustom untuk label dan nilai untuk tampilan minimalis di detail modal (Meniru Penjual) */
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
            font-weight: 400;
            font-size: 1rem;
            word-break: break-word;
            margin-bottom: 1rem;
        }

        /* Garis Horizontal di Modal - lebih ringan dan modern (Meniru Penjual) */
        hr {
            border-top: 1px solid #e9ecef;
            opacity: 0.7;
        }

        /* Gaya Modal Filter Utama (Meniru moreFiltersModal dari Daftar Mobil) */
        /* Menghapus styling .modal-header.bg-primary-gradient sesuai permintaan pengguna */
        .modal-body .form-control,
        .modal-body .form-select {
            border-radius: 0.5rem; /* Sudut membulat standar */
            border: 1px solid #dcdfe6; /* Batas lebih terang */
            padding: 0.75rem 1rem; /* Padding yang konsisten */
            font-size: 1rem;
            box-shadow: none; /* Tidak ada bayangan secara default */
            transition: all 0.2s ease-in-out;
        }
        .modal-body .form-control:focus,
        .modal-body .form-select:focus {
            border-color: #0d6efd; /* Warna fokus sesuai biru utama */
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25); /* Bayangan fokus */
        }
        .modal-footer.d-flex.justify-content-between {
            padding: 1.25rem 1.5rem; /* Padding yang konsisten */
            background-color: #f8f9fa; /* Latar belakang terang */
            border-radius: 0 0 1rem 1rem; /* Sudut bawah membulat */
            border-top: 1px solid #e9ecef; /* Batas atas */
        }
        .modal-footer .btn {
            border-radius: 2rem !important; /* Bentuk pil untuk tombol footer modal */
            padding: 0.6rem 1.5rem; /* Padding yang disesuaikan untuk bentuk pil */
            font-size: 0.9rem;
        }

        /* Perubahan: Atur ulang gaya backdrop modal untuk mengaktifkannya */
        /* .modal-backdrop {
            display: none !important;
        } */
    </style>
</head>

<div class="container-fluid py-4 px-3 px-md-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8 mb-3 mb-md-0">
            <h4 class="text-dark fw-bold mb-0">Daftar Data Servis</h4>
            <small class="text-secondary">Kelola semua informasi servis kendaraan Anda dengan mudah.</small>
        </div>
        <div class="col-md-4 text-md-end">
            {{-- Tombol Tambah Servis - Hanya untuk Admin --}}
            @if(strtolower(auth()->user()->job ?? '') === 'admin')
            <a href="{{ route('servis.create') }}" class="btn btn-primary btn-lg shadow-lg rounded-pill animate__animated animate__fadeInRight">
                <i class="bi bi-plus-circle me-2"></i> Tambah Servis Baru
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

    {{-- Bagian Filter dan Pencarian (Menyesuaikan dari Penjual) --}}
    <div class="filter-section animate__animated animate__fadeInUp">
        <div class="row g-3 align-items-end">
            <div class="col-md-10">
                <label for="searchInput" class="form-label text-muted">Cari Servis</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                    {{-- Input search sekarang akan mengirimkan ke URL --}}
                    <input type="text" id="searchInput" class="form-control border-start-0 rounded-end" placeholder="Cari berdasarkan kode servis, nopol, atau informasi mobil..." value="{{ request('search') }}">
                    {{-- Perubahan di sini: Menambahkan tombol reset di samping searchInput --}}
                    <button type="button" id="resetSearchBtn" class="btn btn-outline-secondary border-start-0 rounded-start-0 rounded-end">
                        <i class="bi bi-x-circle me-2"></i> Reset Cari
                    </button>
                    {{-- Akhir Perubahan --}}
                </div>
            </div>
            <div class="col-md-2 text-end">
                <button type="button" class="btn btn-outline-secondary w-100" data-bs-toggle="modal" data-bs-target="#mainFilterModal">
                    <i class="bi bi-funnel-fill me-2"></i> Filter Lainnya
                </button>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-xl rounded-4 animate__animated animate__fadeInUp">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-dark fw-bold">Data Servis</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive" id="tableResponsiveContainer">
                <table class="table table-hover table-striped align-middle" id="servisTable">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center">#</th>
                            <th scope="col" class="text-center" data-sort-type="text">Kode Servis</th>
                            <th scope="col" class="text-center" data-sort-type="date">Tanggal Servis</th>
                            <th scope="col" data-sort-type="text">Informasi Mobil</th>
                            <th scope="col" data-sort-type="text">Metode Pembayaran</th>
                            <th scope="col" class="text-end" data-sort-type="numeric">Harga Mobil Dibeli</th>
                            <th scope="col" class="text-end" data-sort-type="numeric">Biaya Perbaikan</th>
                            <th scope="col" class="text-end" data-sort-type="numeric">Modal Mobil</th>
                            <th scope="col" class="text-center" data-sort-type="text">Status</th>
                            <th scope="col" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($servis as $item)
                        <tr class="data-row"
                            {{-- Data attributes ini sekarang hanya untuk JS detail modal, bukan filter --}}
                            data-kode="{{ strtolower($item->kode_servis) }}"
                            data-mobil="{{ strtolower($item->mobil->nomor_polisi ?? '') }}"
                            data-status="{{ strtolower($item->status ?? 'null') }}"
                            data-tahunservis="{{ \Carbon\Carbon::parse($item->tanggal_servis)->year }}"
                            data-tanggal="{{ \Carbon\Carbon::parse($item->tanggal_servis)->format('Y-m-d') }}"
                            data-merek="{{ strtolower($item->mobil->merek_mobil ?? '') }}"
                            data-tipe="{{ strtolower($item->mobil->tipe_mobil ?? '') }}"
                            data-harga-dibeli="{{ $item->mobil->transaksiPembelian->sum('harga_beli_mobil_final') ?? 0 }}"
                            data-biaya-perbaikan="{{ $item->total_harga }}"
                            data-modal-mobil="{{ $item->total_biaya_keseluruhan }}">
                            <td class="text-center">{{ $loop->iteration + ($servis->currentPage() - 1) * $servis->perPage() }}</td>
                            <td class="text-center">{{ $item->kode_servis }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal_servis)->format('d M Y') }}</td>
                            <td class="text-break">
                                @php
                                    $mobilDisplay = '';
                                    if ($item->mobil->tahun_pembuatan) {
                                        $mobilDisplay .= $item->mobil->tahun_pembuatan . ' ';
                                    }
                                    $mobilDisplay .= $item->mobil->merek_mobil ?? 'N/A';
                                    if ($item->mobil->tipe_mobil) {
                                        $mobilDisplay .= ' ' . $item->mobil->tipe_mobil;
                                    }
                                    $mobilDisplay .= ' - ' . ($item->mobil->nomor_polisi ?? 'N/A');
                                @endphp
                                {{ $mobilDisplay }}
                            </td>
                            <td class="text-break">{{ $item->metode_pembayaran }}</td>
                            <td class="text-end">Rp{{ number_format($item->mobil->transaksiPembelian->sum('harga_beli_mobil_final') ?? 0, 0, ',', '.') }}</td>
                            <td class="text-end">Rp{{ number_format($item->total_harga, 0, ',', '.') }}</td>
                            <td class="text-end">Rp{{ number_format($item->total_biaya_keseluruhan, 0, ',', '.') }}</td>
                            <td class="text-center">
                                @php
                                    $badgeClass = '';
                                    $statusText = $item->status ?? 'Tidak Ada';
                                    switch (strtolower($item->status ?? 'null')) {
                                        case 'selesai': $badgeClass = 'bg-success'; break;
                                        case 'proses': $badgeClass = 'bg-primary'; break;
                                        case 'batal': $badgeClass = 'bg-danger'; break;
                                        case 'null': $badgeClass = 'bg-secondary'; break;
                                        default: $badgeClass = 'bg-secondary'; break;
                                    }
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ ucfirst($statusText) }}</span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="btn btn-custom-detail" data-bs-toggle="modal" data-bs-target="#detailModal" data-id="{{ $item->id }}" title="Lihat Detail Servis">
                                        <i class="fas fa-info-circle"></i> Detail
                                    </button>
                                    @if(strtolower(auth()->user()->job ?? '') === 'admin')
                                    <a href="{{ route('servis.edit', $item->id) }}" class="btn btn-custom-edit" title="Edit Servis">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4 text-muted empty-state">
                                <i class="bi bi-info-circle-fill mb-2 d-block"></i>
                                <p class="mb-1">Tidak ada data servis yang ditemukan.</p>
                                <p class="mb-0">Coba ubah filter atau tambahkan servis baru.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-light border-0 py-3 px-4 d-flex flex-column flex-md-row justify-content-between align-items-center rounded-bottom-4 gap-3">
            <small class="text-muted">Data diperbarui terakhir: {{ Carbon::now()->format('d M Y H:i') }} WIB</small>
            {{-- Tautan Paginasi (Pastikan $servis adalah instance Paginator) --}}
            {{ optional($servis)->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

{{-- Modal Detail Servis (Meniru struktur modal Penjual) --}}
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 shadow-lg border-0">
            <div class="modal-header bg-info text-white border-0 rounded-top-4 p-3">
                <h5 class="modal-title fw-bold fs-5" id="detailModalLabel"><i class="bi bi-file-text-fill me-2"></i> Detail Data Servis</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="info-section-bordered">
                            <h6 class="text-primary mb-3">Informasi Utama Servis</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label detail-label-new">Kode Servis:</label>
                                    <p class="form-control-plaintext detail-value-new" id="detail-kode"></p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label detail-label-new">Tanggal Servis:</label>
                                    <p class="form-control-plaintext detail-value-new" id="detail-tanggal"></p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label detail-label-new">Informasi Mobil:</label>
                                    <p class="form-control-plaintext detail-value-new" id="detail-mobil"></p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label detail-label-new">Metode Pembayaran:</label>
                                    <p class="form-control-plaintext detail-value-new" id="detail-metode"></p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label detail-label-new">Status Servis:</label>
                                    <p class="form-control-plaintext detail-value-new" id="detail-status"></p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label detail-label-new">Harga Mobil Dibeli:</label>
                                    <p class="form-control-plaintext detail-value-new" id="detail-harga-dibeli"></p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label detail-label-new">Biaya Perbaikan:</label>
                                    <p class="form-control-plaintext detail-value-new" id="detail-biaya-perbaikan"></p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label detail-label-new">Total Modal:</label>
                                    <p class="form-control-plaintext detail-value-new" id="detail-modal-mobil"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="info-section-bordered">
                    <h6 class="text-primary mb-3">Item Servis:</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Barang</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Harga Satuan</th>
                                    <th class="text-end">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody id="detail-items-body">
                                {{-- Item akan dimuat di sini oleh JS --}}
                            </tbody>
                        </table>
                    </div>
                    <p class="text-muted small mt-2">Daftar barang dan layanan yang digunakan dalam servis ini.</p>
                </div>

                <hr class="my-4">

                <div class="info-section-bordered">
                    <h6 class="text-primary mb-3">Informasi Sistem</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label detail-label-new">Dibuat Pada:</label>
                            <p class="form-control-plaintext detail-value-new" id="detail_created_at"></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label detail-label-new">Terakhir Diperbarui:</label>
                            <p class="form-control-plaintext detail-value-new" id="detail_updated_at"></p>
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

{{-- Modal Filter Utama (Diperbarui agar menyerupai moreFiltersModal dari Daftar Mobil) --}}
<div class="modal fade" id="mainFilterModal" tabindex="-1" aria-labelledby="mainFilterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 shadow-lg">
            <div class="modal-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <h5 class="modal-title fw-bold text-dark mb-0" id="mainFilterModalLabel"><i class="bi bi-funnel-fill me-2"></i> Filter Tambahan Servis</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <p class="text-muted mb-4">Pilih filter tambahan untuk menyaring data servis.</p>
                <div class="row g-3">
                    @php
                        $today = date('Y-m-d');
                        $minDateFilter = date('Y-m-d', strtotime('-5 years'));
                    @endphp
                    <div class="col-md-6">
                        <label for="startDateFilter" class="form-label text-muted">Dari Tanggal Servis:</label>
                        <input type="date" id="startDateFilter" name="start_date" class="form-control" max="{{ $today }}" min="{{ $minDateFilter }}" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-6">
                        <label for="endDateFilter" class="form-label text-muted">Sampai Tanggal Servis:</label>
                        <input type="date" id="endDateFilter" name="end_date" class="form-control" max="{{ $today }}" min="{{ $minDateFilter }}" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-6">
                        <label for="statusFilterModal" class="form-label text-muted">Status Servis:</label>
                        <select id="statusFilterModal" name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="proses" {{ request('status') == 'proses' ? 'selected' : '' }}>Proses</option>
                            <option value="batal" {{ request('status') == 'batal' ? 'selected' : '' }}>Batal</option>
                            <option value="null" {{ request('status') == 'null' ? 'selected' : '' }}>Tidak Ada</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="tahunServisFilterModal" class="form-label text-muted">Tahun Servis:</label>
                        <select id="tahunServisFilterModal" name="tahun_servis" class="form-select">
                            <option value="">Semua Tahun Servis</option>
                            @php
                                // Dapatkan tahun unik dari koleksi $servis yang diteruskan ke view
                                // Atau dari variabel $allMerek dan $allTipe jika sudah ada
                                $uniqueYears = optional($servis)->map(function($s) {
                                    return \Carbon\Carbon::parse($s->tanggal_servis)->year;
                                })->unique()->sortDesc();
                                // Alternatif: Jika ingin semua tahun yang ada di database, meskipun tidak ada di hasil paginasi saat ini:
                                // $allAvailableYears = \App\Models\Servis::selectRaw('YEAR(tanggal_servis) as year')->distinct()->pluck('year')->sortDesc();
                            @endphp
                            @foreach($uniqueYears as $year)
                                <option value="{{ $year }}" {{ request('tahun_servis') == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <hr class="my-4">

                <h6 class="mb-3 fw-bold text-dark">Filter Mobil:</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="mobilMerekFilterModal" class="form-label text-muted">Merek Mobil:</label>
                        <select id="mobilMerekFilterModal" name="mobil_merek" class="form-select">
                            <option value="">Semua Merek</option>
                            {{-- Opsi akan diisi oleh JavaScript atau dari PHP jika $allMerek dilewatkan --}}
                            @isset($allMerek)
                                @foreach($allMerek as $merek)
                                    <option value="{{ $merek }}" {{ request('mobil_merek') == $merek ? 'selected' : '' }}>{{ ucfirst($merek) }}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="mobilTipeFilterModal" class="form-label text-muted">Tipe Mobil:</label>
                        <select id="mobilTipeFilterModal" name="mobil_tipe" class="form-select">
                            <option value="">Semua Tipe</option>
                            {{-- Opsi akan diisi oleh JavaScript atau dari PHP jika $allTipe dilewatkan --}}
                            @isset($allTipe)
                                @foreach($allTipe as $tipe)
                                    <option value="{{ $tipe }}" {{ request('mobil_tipe') == $tipe ? 'selected' : '' }}>{{ ucfirst($tipe) }}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="mobilNopolFilterModal" class="form-label text-muted">Nomor Polisi:</label>
                        <input type="text" id="mobilNopolFilterModal" name="mobil_nopol" class="form-control" placeholder="Contoh: B 1234 CD" value="{{ request('mobil_nopol') }}">
                    </div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" id="resetAllFiltersBtn" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-counterclockwise me-2"></i> Reset Semua
                </button>
                <button type="button" id="applyAllFiltersBtn" class="btn btn-primary">
                    <i class="bi bi-check-circle me-2"></i> Terapkan Filter
                </button>
            </div>
        </div>
    </div>
</div>


{{-- Pastikan jQuery dan Bootstrap JS di-load di layout utama atau sebelum script ini --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Logika Modal (Detail Servis) ---
        const detailModalElement = document.getElementById('detailModal');
        const detailModal = new bootstrap.Modal(detailModalElement);

        if (detailModalElement) {
            detailModalElement.addEventListener('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');

                $.ajax({
                    url: '/servis/' + id,
                    method: 'GET',
                    success: function(data) {
                        $('#detail-kode').text(data.kode_servis || 'Tidak Tersedia');
                        $('#detail-tanggal').text(data.tanggal_servis ? moment(data.tanggal_servis).format('DD MMMM YYYY') : 'Tidak Tersedia');

                        let mobilInfoDetail = '';
                        if (data.mobil) {
                            if (data.mobil.tahun_pembuatan) {
                                mobilInfoDetail += data.mobil.tahun_pembuatan + ' ';
                            }
                            mobilInfoDetail += data.mobil.merek_mobil || 'N/A';
                            if (data.mobil.tipe_mobil) {
                                mobilInfoDetail += ' ' + data.mobil.tipe_mobil;
                            }
                            if (data.mobil.nomor_polisi) {
                                mobilInfoDetail += ' - ' + data.mobil.nomor_polisi;
                            }
                        } else {
                            mobilInfoDetail = 'Tidak Tersedia';
                        }
                        $('#detail-mobil').text(mobilInfoDetail.trim());

                        $('#detail-metode').text(data.metode_pembayaran || 'Tidak Tersedia');
                        $('#detail-status').text(data.status ? data.status.charAt(0).toUpperCase() + data.status.slice(1) : 'Tidak Ada');

                        $('#detail-harga-dibeli').text('Rp' + parseFloat(data.total_harga_mobil_dibeli || 0).toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 }));
                        $('#detail-biaya-perbaikan').text('Rp' + parseFloat(data.total_harga || 0).toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 }));
                        $('#detail-modal-mobil').text('Rp' + parseFloat(data.total_biaya_keseluruhan || 0).toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 }));

                        const itemsBody = document.getElementById('detail-items-body');
                        itemsBody.innerHTML = '';
                        let itemList = '';
                        if (data.items && data.items.length > 0) {
                            $.each(data.items, function(index, item) {
                                const hargaSatuanFormatted = parseFloat(item.item_price || 0).toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
                                const jumlahFormatted = parseFloat(item.item_total || 0).toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 });

                                itemList += `
                                    <tr>
                                        <td>${item.item_name || 'N/A'}</td>
                                        <td class="text-center">${item.item_qty || 0}</td>
                                        <td class="text-end">Rp${hargaSatuanFormatted}</td>
                                        <td class="text-end">Rp${jumlahFormatted}</td>
                                    </tr>
                                `;
                            });
                            itemsBody.innerHTML = itemList;
                        } else {
                            itemsBody.innerHTML = `<tr><td colspan="4" class="text-center text-muted">Tidak ada item servis.</td></tr>`;
                        }

                        $('#detail_created_at').text(data.created_at ? moment(data.created_at).format('DD MMMM YYYY HH:mm') : 'Tidak Tersedia');
                        $('#detail_updated_at').text(data.updated_at ? moment(data.updated_at).format('DD MMMM YYYY HH:mm') : 'Tidak Tersedia');
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error("Kesalahan AJAX:", textStatus, errorThrown);
                        console.error("Teks Respons:", jqXHR.responseText);
                        alert("Gagal mengambil data detail servis. Silakan cek konsol browser untuk kesalahan.");
                    }
                });
            });
        }

        // --- Logika Filter Utama (via URL) ---
        const mainFilterModalElement = document.getElementById('mainFilterModal');
        const mainFilterModal = new bootstrap.Modal(mainFilterModalElement, { backdrop: true });

        const searchInput = document.getElementById('searchInput');
        const startDateFilter = document.getElementById('startDateFilter');
        const endDateFilter = document.getElementById('endDateFilter');
        const statusFilterModal = document.getElementById('statusFilterModal');
        const tahunServisFilterModal = document.getElementById('tahunServisFilterModal');
        const mobilMerekFilterModal = document.getElementById('mobilMerekFilterModal');
        const mobilTipeFilterModal = document.getElementById('mobilTipeFilterModal');
        const mobilNopolFilterModal = document.getElementById('mobilNopolFilterModal');

        const applyAllFiltersBtn = document.getElementById('applyAllFiltersBtn');
        const resetAllFiltersBtn = document.getElementById('resetAllFiltersBtn');
        const resetSearchBtn = document.getElementById('resetSearchBtn'); // Tombol reset baru di samping search

        const servisTable = document.getElementById('servisTable');
        const tableResponsiveContainer = document.getElementById('tableResponsiveContainer');

        // Fungsi untuk mengarahkan ulang dengan parameter URL baru
        function redirectToFilteredUrl() {
            const url = new URL(window.location.href);

            // Bersihkan semua parameter filter lama sebelum menambahkan yang baru
            url.searchParams.delete('search');
            url.searchParams.delete('status');
            url.searchParams.delete('start_date');
            url.searchParams.delete('end_date');
            url.searchParams.delete('tahun_servis');
            url.searchParams.delete('mobil_merek');
            url.searchParams.delete('mobil_tipe');
            url.searchParams.delete('mobil_nopol');
            url.searchParams.delete('page'); // Selalu reset halaman ke 1 saat filter berubah

            // Tambahkan parameter filter baru dari input jika nilainya tidak kosong
            if (searchInput.value.trim() !== '') {
                url.searchParams.set('search', searchInput.value.trim());
            }
            if (statusFilterModal.value.trim() !== '') {
                url.searchParams.set('status', statusFilterModal.value.trim());
            }
            if (startDateFilter.value.trim() !== '') {
                url.searchParams.set('start_date', startDateFilter.value.trim());
            }
            if (endDateFilter.value.trim() !== '') {
                url.searchParams.set('end_date', endDateFilter.value.trim());
            }
            if (tahunServisFilterModal.value.trim() !== '') {
                url.searchParams.set('tahun_servis', tahunServisFilterModal.value.trim());
            }
            if (mobilMerekFilterModal.value.trim() !== '') {
                url.searchParams.set('mobil_merek', mobilMerekFilterModal.value.trim());
            }
            if (mobilTipeFilterModal.value.trim() !== '') {
                url.searchParams.set('mobil_tipe', mobilTipeFilterModal.value.trim());
            }
            if (mobilNopolFilterModal.value.trim() !== '') {
                url.searchParams.set('mobil_nopol', mobilNopolFilterModal.value.trim());
            }

            // Arahkan ulang ke URL baru
            window.location.href = url.toString();
        }

        // Event listeners untuk filter
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                redirectToFilteredUrl();
            }
        });

        applyAllFiltersBtn.addEventListener('click', function() {
            redirectToFilteredUrl();
        });

        resetAllFiltersBtn.addEventListener('click', function() {
            // Kosongkan semua nilai input filter secara manual (termasuk searchInput)
            searchInput.value = ''; // Ini penting untuk tombol reset di modal
            startDateFilter.value = '';
            endDateFilter.value = '';
            statusFilterModal.value = '';
            tahunServisFilterModal.value = '';
            mobilMerekFilterModal.value = '';
            mobilTipeFilterModal.value = '';
            mobilNopolFilterModal.value = '';

            // Setelah mengosongkan semua input, panggil fungsi untuk mengarahkan ulang
            redirectToFilteredUrl();
        });

        // Event listener untuk tombol reset di samping searchInput
        resetSearchBtn.addEventListener('click', function() {
            searchInput.value = ''; // Hanya mengosongkan search input
            redirectToFilteredUrl(); // Terapkan filter yang tersisa (jika ada) atau tampilkan semua
        });


        // Fungsi untuk mengisi nilai filter dari URL saat halaman dimuat
        function populateFiltersFromUrl() {
            const params = new URLSearchParams(window.location.search);

            searchInput.value = params.get('search') || '';
            startDateFilter.value = params.get('start_date') || '';
            endDateFilter.value = params.get('end_date') || '';
            statusFilterModal.value = params.get('status') || '';
            tahunServisFilterModal.value = params.get('tahun_servis') || '';
            mobilMerekFilterModal.value = params.get('mobil_merek') || '';
            mobilTipeFilterModal.value = params.get('mobil_tipe') || '';
            mobilNopolFilterModal.value = params.get('mobil_nopol') || '';
        }

        // Panggil saat DOMContentLoaded (saat halaman pertama kali dimuat)
        populateFiltersFromUrl();


        // --- Logika Pengurutan Tabel (tetap sisi klien karena data sudah ada di halaman) ---
        document.querySelectorAll('th[data-sort-type]').forEach(header => {
            header.addEventListener('click', function() {
                const table = this.closest('table');
                const tbody = table.querySelector('tbody');
                // Hanya ambil baris yang terlihat setelah filtering oleh backend (atau semua jika tidak ada filter)
                const rows = Array.from(tbody.querySelectorAll('tr.data-row:not(.empty-state)'));
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

                rows.sort((rowA, rowB) => {
                    let valA, valB;

                    if (sortType === 'date') {
                        valA = rowA.getAttribute('data-tanggal') || '';
                        valB = rowB.getAttribute('data-tanggal') || '';
                        const dateA = valA ? new Date(valA) : new Date(0);
                        const dateB = valB ? new Date(valB) : new Date(0);
                        let comparison = 0;
                        if (dateA > dateB) { comparison = 1; } else if (dateA < dateB) { comparison = -1; }
                        return newDirection === 'asc' ? comparison : -comparison;
                    } else if (sortType === 'numeric') {
                        const dataAttrMap = {
                            'Harga Mobil Dibeli': 'data-harga-dibeli',
                            'Biaya Perbaikan': 'data-biaya-perbaikan',
                            'Modal Mobil': 'data-modal-mobil'
                        };
                        const headerText = this.textContent.trim();
                        const dataAttributeName = dataAttrMap[headerText];

                        if (dataAttributeName) {
                            valA = parseFloat(rowA.getAttribute(dataAttributeName) || 0);
                            valB = parseFloat(rowB.getAttribute(dataAttributeName) || 0);
                        } else {
                            valA = parseFloat(rowA.children[columnIndex].textContent.trim().replace(/[^0-9.-]+/g,"") || 0);
                            valB = parseFloat(rowB.children[columnIndex].textContent.trim().replace(/[^0-9.-]+/g,"") || 0);
                        }
                    } else { // Tipe 'text'
                        const adjustedColumnIndex = columnIndex;
                        valA = rowA.children[adjustedColumnIndex].textContent.trim().toLowerCase();
                        valB = rowB.children[adjustedColumnIndex].textContent.trim().toLowerCase();
                    }

                    let comparison = 0;
                    if (valA > valB) { comparison = 1; } else if (valA < valB) { comparison = -1; }
                    return newDirection === 'asc' ? comparison : -comparison;
                });

                rows.forEach(row => tbody.appendChild(row));
                // Pastikan pesan "Tidak ada hasil" dihapus jika ada baris data setelah pengurutan
                const currentEmptyStateRow = servisTable.querySelector('.empty-state');
                if (rows.length > 0 && currentEmptyStateRow) {
                    currentEmptyStateRow.remove();
                } else if (rows.length === 0 && !currentEmptyStateRow) {
                     // Jika tidak ada baris setelah sort (karena tidak ada data sama sekali atau semua terfilter), tampilkan pesan kosong
                     const newEmptyStateRow = document.createElement('tr');
                     newEmptyStateRow.classList.add('empty-state');
                     newEmptyStateRow.innerHTML = `
                         <td colspan="10" class="text-center text-muted py-5">
                             <i class="bi bi-info-circle-fill fs-3 mb-2 d-block"></i>
                             <p class="mb-1">Tidak ada hasil ditemukan untuk pencarian atau filter Anda.</p>
                             <p class="mb-0">Coba kata kunci atau filter lain.</p>
                         </td>
                     `;
                     tbody.appendChild(newEmptyStateRow);
                }
            });
        });


        // --- Tambahan untuk mengatasi masalah header saat "back" di mobile ---
        const headerElement = document.querySelector('.container-fluid.py-4');

        function ensureHeaderPosition() {
            if (headerElement) {
                headerElement.style.display = 'none';
                headerElement.offsetHeight; // Memaksa reflow
                headerElement.style.display = '';
            }
        }

        ensureHeaderPosition();

        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                console.log('Halaman dimuat dari BFCache. Memastikan posisi header.');
                ensureHeaderPosition();
            } else {
                console.log('Halaman dimuat secara normal.');
            }
        });
    });
</script>
@endsection
