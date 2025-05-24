@extends('layouts.app')

@section('title', 'Dashboard Staff Service')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-baseline gap-3">
            <h4 class="fw-bold text-dark mb-0">Dashboard Staff Service</h4>
            <p class="text-muted small mb-0">
                Kelola data mobil dan layanan pelanggan untuk meningkatkan kualitas service.
            </p>
        </div>
    </div>

    <div class="alert alert-info border-0 shadow-sm">
        <i class="bi bi-tools me-2"></i>
        Selamat datang, <strong>Staff Service</strong>! Gunakan halaman ini untuk memastikan semua layanan berjalan optimal.
    </div>

    {{-- Tambahkan statistik, status perbaikan, atau daftar tugas service di sini jika diperlukan --}}
    {{--
    <div class="row mt-3">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Jumlah Mobil dalam Perawatan</h6>
                    <h3>15 Unit</h3>
                </div>
            </div>
        </div>
        ...
    </div>
    --}}
</div>
@endsection
