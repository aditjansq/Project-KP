@extends('layouts.app')

@section('title', 'Transaksi')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-baseline flex-wrap gap-2">
            <h4 class="text-dark fw-bold mb-0">Transaksi</h4>
            <p class="text-muted small mb-0">Halaman untuk menampilkan riwayat transaksi pelanggan.</p>
        </div>
        {{-- Tombol aksi jika dibutuhkan --}}
        {{-- 
        <a href="{{ route('transaksi.create') }}" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-circle me-2"></i>Tambah Transaksi
        </a> 
        --}}
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="text-muted text-center py-4">
                <i class="bi bi-receipt-cutoff fs-1 d-block mb-3"></i>
                <p class="mb-0">Belum ada data transaksi yang tersedia.</p>
            </div>
        </div>
    </div>
</div>
@endsection
