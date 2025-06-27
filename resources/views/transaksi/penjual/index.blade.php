@extends('layouts.app')

@section('title', 'Daftar Transaksi Penjual')

@section('content')
<div class="container-fluid py-4 px-3 px-md-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8 mb-3 mb-md-0">
            <h4 class="text-dark fw-bold mb-0">Daftar Transaksi Penjual</h4>
            <small class="text-secondary">Informasi lengkap transaksi terkait penjual.</small>
        </div>
        <div class="col-md-4 text-md-end">
            {{-- Tombol Kembali atau Tambah Transaksi Penjual jika ada --}}
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary rounded-pill animate__animated animate__fadeInRight">
                <i class="bi bi-arrow-left me-2"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-xl rounded-4 animate__animated animate__fadeInUp">
        <div class="card-header bg-white p-4 border-bottom-0">
            <h5 class="card-title mb-0 text-dark fw-bold">Detail Transaksi Penjual</h5>
            <p class="card-text text-muted">Daftar transaksi di mana penjual terlibat.</p>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0 custom-table">
                    <thead class="bg-gradient-primary text-white text-center align-middle">
                        <tr>
                            <th scope="col" style="min-width: 150px;">Tanggal Transaksi</th>
                            <th scope="col" style="min-width: 180px;">Informasi Mobil</th>
                            <th scope="col" style="min-width: 150px;">Nama Penjual</th>
                            <th scope="col" style="min-width: 120px;">Total Harga</th>
                            {{-- Tambahkan kolom lain jika diperlukan --}}
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksiPenjual as $transaksi)
                        <tr>
                            <td class="text-center">{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d M Y') }}</td>
                            <td>
                                {{ $transaksi->mobil->merek_mobil ?? 'N/A' }}
                                {{ $transaksi->mobil->tipe_mobil ?? '' }}
                                - {{ $transaksi->mobil->nomor_polisi ?? 'N/A' }}
                            </td>
                            <td>{{ $transaksi->penjual->nama ?? 'N/A' }}</td>
                            <td class="text-end">Rp{{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-5 empty-state">
                                <i class="bi bi-info-circle-fill fs-3 mb-2 d-block"></i>
                                <p class="mb-1">Tidak ada transaksi penjual ditemukan.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-light border-top-0 py-3 px-4 d-flex justify-content-between align-items-center rounded-bottom-4 gap-3">
            <small class="text-muted">Data ditampilkan: {{ $transaksiPenjual->count() }} dari {{ $transaksiPenjual->total() }}</small>
            {{ $transaksiPenjual->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<style>
    body { background-color: #f8f9fa; font-family: 'Poppins', sans-serif; color: #343a40; }
    .container-fluid.py-4 { padding-top: 2.5rem !important; padding-bottom: 2.5rem !important; }
    .card { border-radius: 1rem !important; overflow: hidden; box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.09) !important; }
    .card-header { background-color: #ffffff; border-bottom: 1px solid #e9ecef; }
    .custom-table { border-collapse: separate; border-spacing: 0; table-layout: fixed; width: 100%; }
    .custom-table thead th { background: linear-gradient(90deg, #495057, #6c757d); color: #fff; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; padding: 1rem 0.8rem; border-bottom: none; white-space: nowrap; }
    .custom-table thead th:first-child { border-top-left-radius: 1rem; }
    .custom-table thead th:last-child { border-top-right-radius: 1rem; }
    .custom-table tbody tr { transition: all 0.2s ease; border-bottom: 1px solid #dee2e6; }
    .custom-table tbody tr:last-child { border-bottom: none; }
    .custom-table tbody tr:hover { background-color: #f1f3f5; box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.05); transform: translateY(-1px); }
    .custom-table tbody td { padding: 0.8rem; vertical-align: middle; font-size: 0.88rem; color: #495057; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .empty-state { background-color: #fefefe; color: #6c757d; font-style: italic; padding: 3rem !important; }
    .empty-state i { color: #adb5bd; }
    .btn-outline-secondary { border-color: #6c757d; color: #6c757d; transition: all 0.3s ease; }
    .btn-outline-secondary:hover { background-color: #6c757d; color: white; transform: translateY(-1px); box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
</style>
@endsection
