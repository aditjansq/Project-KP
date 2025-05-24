@extends('layouts.app')

@section('title', 'Dashboard Divisi Finance')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-baseline gap-3">
            <h4 class="fw-bold text-dark mb-0">Dashboard Divisi Finance</h4>
            <p class="text-muted small mb-0">Pantau dan kelola transaksi serta laporan keuangan dengan mudah.</p>
        </div>
    </div>

    <div class="alert alert-info border-0 shadow-sm">
        <i class="bi bi-cash-stack me-2"></i>
        Selamat datang, <strong>Divisi Finance</strong>! Gunakan dashboard ini untuk melihat riwayat transaksi, laporan, dan pengelolaan data keuangan.
    </div>

    {{-- Jika ingin menambahkan data ringkasan keuangan, grafik, atau laporan singkat, tempatkan di sini --}}
    {{-- 
    <div class="row mt-3">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Total Transaksi</h6>
                    <h3>Rp 150.000.000</h3>
                </div>
            </div>
        </div>
        ...
    </div>
    --}}
</div>
@endsection
