@extends('layouts.app')

@section('title', 'Dashboard Divisi Marketing')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-baseline gap-3">
            <h4 class="fw-bold text-dark mb-0">Dashboard Divisi Marketing</h4>
            <p class="text-muted small mb-0">
                Akses informasi mobil dan pembeli untuk menunjang aktivitas promosi dan penjualan.
            </p>
        </div>
    </div>

    <div class="alert alert-info border-0 shadow-sm">
        <i class="bi bi-bullseye me-2"></i>
        Selamat datang, <strong>Divisi Marketing</strong>! Gunakan fitur ini untuk memantau data mobil dan pembeli secara efisien.
    </div>

    {{-- Tempatkan grafik, statistik, atau data pemasaran di sini jika diperlukan --}}
    {{--
    <div class="row mt-3">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Total Mobil</h6>
                    <h3>120 Unit</h3>
                </div>
            </div>
        </div>
        ...
    </div>
    --}}
</div>
@endsection
