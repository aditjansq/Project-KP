@extends('layouts.app')

@section('title', 'Laporan Mobil Dibeli')

@push('styles')
{{-- Memuat Font Awesome 6 dari CDN --}}
{{-- Penting: Jika Anda ingin menggunakan kelas `fa-solid fa-file`, Anda harus memuat Font Awesome 6. --}}
{{-- URL ini dapat berubah, selalu cek di fontawesome.com atau cdnjs.com untuk versi terbaru jika ada masalah. --}}
    {{-- Google Fonts Poppins --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    {{-- Animate.css untuk animasi masuk (opsional, bisa dihapus jika tidak dibutuhkan) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    {{-- Font Awesome untuk ikon --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

    body {
        font-family: 'Poppins', sans-serif; /* Menggunakan font Inter */
        background-color: #f0f2f5; /* Latar belakang yang lebih terang */
        color: #344767; /* Warna teks default */
    }

    .container-fluid {
        padding-top: 2.5rem; /* Padding atas-bawah */
        padding-bottom: 2.5rem;
    }

    .card {
        border: none;
        border-radius: 0.75rem; /* Sudut membulat yang lebih besar */
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); /* Bayangan yang lebih jelas */
        overflow: hidden;
        background-color: #ffffff;
    }

    .card-header {
        background-color: #ffffff;
        border-bottom: 1px solid #e9ecef;
        padding: 1.5rem 2rem; /* Padding yang lebih simetris */
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 600; /* Medium bold */
        color: #344767;
        font-size: 1.125rem; /* Ukuran font judul header */
    }

    .card-title-small { /* Untuk teks "Kelola semua..." */
        font-size: 0.875rem; /* Ukuran font lebih kecil */
        color: #67748e; /* Warna abu-abu yang lebih lembut */
    }

    .table {
        width: 100%;
        margin-bottom: 0;
        border-collapse: collapse; /* Pastikan collapse */
    }

    .table thead th {
        background-color: #2196F3; /* Warna biru cerah untuk header */
        color: white;
        text-align: left;
        padding: 1rem 1.5rem; /* Padding yang seragam */
        border-bottom: none;
        font-weight: 600;
        font-size: 0.875rem; /* Ukuran font header tabel */
        letter-spacing: 0.05em; /* Jarak antar huruf */
        text-transform: uppercase; /* Huruf kapital semua */
        vertical-align: middle;
    }

    .table tbody tr {
        transition: background-color 0.2s ease-in-out; /* Transisi halus untuk hover */
    }

    .table tbody tr:nth-of-type(odd) { /* Odd baris putih */
        background-color: #ffffff;
    }
    .table tbody tr:nth-of-type(even) { /* Even baris abu-abu muda */
        background-color: #fdfdfd;
    }

    .table tbody tr:hover {
        background-color: #e3f2fd; /* Warna biru muda saat hover */
        cursor: pointer;
    }

    .table tbody td {
        padding: 1rem 1.5rem; /* Padding seragam */
        border-top: 1px solid #e9ecef; /* Border atas yang halus */
        vertical-align: middle;
        font-size: 0.875rem; /* Ukuran font isi tabel */
        color: #67748e; /* Warna teks isi tabel */
    }

    .table tfoot th,
    .table tfoot td {
        background-color: #e9ecef;
        font-weight: 700;
        padding: 1rem 1.5rem;
        border-top: 2px solid #ced4da; /* Border atas lebih tebal */
        color: #344767;
        font-size: 0.9375rem; /* Ukuran font footer */
    }

    .text-end { text-align: right; }

    .btn-danger {
        background-color: #f44336; /* Warna merah yang lebih standar */
        border-color: #f44336;
        border-radius: 0.5rem; /* Sudut membulat tombol */
        padding: 0.625rem 1.25rem; /* Padding tombol */
        font-size: 0.875rem;
        transition: all 0.3s ease;
        display: inline-flex; /* Untuk ikon dan teks sejajar */
        align-items: center;
        font-weight: 500;
        box-shadow: 0 2px 4px rgba(244, 67, 54, 0.2); /* Shadow untuk tombol */
    }

    .btn-danger i {
        margin-right: 0.5rem; /* Jarak antara ikon dan teks */
    }

    .btn-danger:hover {
        background-color: #d32f2f;
        border-color: #d32f2f;
        transform: translateY(-2px); /* Efek sedikit naik */
        box-shadow: 0 4px 8px rgba(244, 67, 54, 0.3); /* Shadow lebih besar saat hover */
    }

    .alert-info {
        background-color: #e0f7fa; /* Warna info yang lebih lembut */
        border-color: #b2ebf2;
        color: #00796b; /* Warna teks yang kontras */
        border-radius: 0.5rem;
        padding: 1.5rem;
        margin-top: 1.5rem;
        font-size: 1rem;
        font-weight: 500;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4 px-3 px-md-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6 mb-3 mb-md-0">
            <h4 class="text-dark fw-bold mb-0">Laporan Mobil Dibeli</h4>
            <small class="text-secondary card-title-small">Kelola semua informasi transaksi pembelian mobil Anda dengan mudah.</small>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <a href="{{ route('laporan.mobil_dibeli.pdf') }}" class="btn btn-danger">
                {{-- Menggunakan kelas Font Awesome 6 yang benar: fa-solid fa-file-pdf --}}
                <i class="fa-solid fa-file-pdf"></i> Ekspor ke PDF
            </a>
        </div>

        <div class="card-body p-0"> {{-- Hapus padding default card-body untuk tabel full-width --}}
            @if (isset($mobilDibeli) && $mobilDibeli->count() > 0)
                <div class="table-responsive">
                    <table class="table" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Kode Trx</th>
                                <th>Merek</th>
                                <th>Type</th>
                                <th>Tahun</th>
                                <th>Warna</th>
                                <th>Nomor Polisi</th>
                                <th>Tgl Beli</th>
                                <th class="text-end">Harga Dibeli</th>
                                <th class="text-end">Total Servis</th>
                                <th class="text-end">Modal Mobil</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mobilDibeli as $index => $pembelian)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $pembelian->kode_transaksi ?? 'N/A' }}</td>
                                <td>{{ $pembelian->mobil->merek_mobil ?? 'N/A' }}</td>
                                <td>{{ $pembelian->mobil->tipe_mobil ?? 'N/A' }}</td>
                                <td>{{ $pembelian->mobil->tahun_pembuatan ?? 'N/A' }}</td>
                                <td>{{ $pembelian->mobil->warna_mobil ?? 'N/A' }}</td>
                                <td>{{ $pembelian->mobil->nomor_polisi ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($pembelian->tanggal_transaksi)->format('d/m/Y') }}</td>
                                <td class="text-end">Rp {{ number_format($pembelian->harga_beli_mobil_final, 0, ',', '.') }}</td>
                                <td class="text-end">
                                    Rp {{ number_format($pembelian->mobil->servis->sum('total_harga'), 0, ',', '.') }}
                                </td>
                                <td class="text-end">
                                    @php
                                        $totalBiayaServis = $pembelian->mobil->servis->sum('total_biaya_keseluruhan');
                                        $modalMobil = $pembelian->harga_beli_mobil_final + $totalBiayaServis;
                                    @endphp
                                    Rp {{ number_format($modalMobil, 0, ',', '.') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="8" class="text-end">Total Pembelian:</th>
                                <th class="text-end">Rp {{ number_format($mobilDibeli->sum('harga_beli_mobil_final'), 0, ',', '.') }}</th>
                                <th class="text-end">
                                    Rp {{ number_format($mobilDibeli->sum(function($p) { return $p->mobil->servis->sum('total_harga'); }), 0, ',', '.') }}
                                </th>
                                <th class="text-end">
                                    @php
                                        $totalModalKeseluruhan = $mobilDibeli->sum(function($p) {
                                            $totalBiayaServis = $p->mobil->servis->sum('total_biaya_keseluruhan');
                                            return $p->harga_beli_mobil_final + $totalBiayaServis;
                                        });
                                    @endphp
                                    Rp {{ number_format($totalModalKeseluruhan, 0, ',', '.') }}
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="alert alert-info text-center" role="alert">
                    Tidak ada data mobil dibeli yang tersedia saat ini.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
            },
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "columnDefs": [
                { "width": "3%", "targets": 0 },
                { "width": "8%", "targets": 1 },
                { "width": "10%", "targets": 2 },
                { "width": "10%", "targets": 3 },
                { "width": "5%", "targets": 4 },
                { "width": "7%", "targets": 5 },
                { "width": "10%", "targets": 6 },
                { "width": "8%", "targets": 7 },
                { "width": "11%", "targets": 8, "className": "text-end" },
                { "width": "11%", "targets": 9, "className": "text-end" },
                { "width": "12%", "targets": 10, "className": "text-end" }
            ]
        });
    });
</script>
@endpush
