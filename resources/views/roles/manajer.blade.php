@extends('layouts.app')

@section('title', 'Dashboard Manajer')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-baseline gap-3">
            <h4 class="fw-bold text-dark mb-0">Dashboard Manajer</h4>
            <p class="text-muted small mb-0">Halaman utama khusus untuk Manajer.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-info border-0 shadow-sm">
                <i class="bi bi-person-workspace me-2"></i>
                Selamat datang kembali, <strong>Manajer</strong>! Gunakan panel ini untuk mengelola data mobil, pembeli, dan transaksi.
            </div>
        </div>
    </div>

    {{-- Contoh statistik atau komponen lain bisa ditambahkan di sini --}}
    {{-- 
    <div class="row mt-3">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Total Mobil</h6>
                    <h3>12</h3>
                </div>
            </div>
        </div>
        ...
    </div>
    --}}
</div>
@endsection
