@extends('layouts.app')

@section('title', 'Detail Transaksi Penjualan')

@section('content')
@php
    use Illuminate\Support\Facades\Storage;
    use Carbon\Carbon;
@endphp

<head>
    {{-- Google Fonts Poppins --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    {{-- Animate.css --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    {{-- Font Awesome for icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        body {
            background-color: #f0f2f5;
        }
        .card-detail {
            background-color: #ffffff;
            border-radius: 0.75rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08), 0 5px 15px rgba(0, 0, 0, 0.04);
            padding: 2.5rem;
        }
        .detail-group {
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px dashed #e9ecef;
        }
        .detail-group:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .detail-label {
            font-weight: 500;
            color: #5a6b7d;
            font-size: 0.95rem;
            margin-bottom: 0.25rem;
        }
        .detail-value {
            font-weight: 600;
            color: #212529;
            font-size: 1.05rem;
        }
        .section-title {
            font-weight: 700;
            color: #343a40;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #0d6efd;
            display: flex;
            align-items: center;
        }
        .section-title i {
            margin-right: 0.75rem;
            color: #0d6efd;
        }
        .status-badge {
            padding: 0.4em 0.8em;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.85em;
        }
        .status-lunas { background-color: #d4edda; color: #155724; } /* Green */
        .status-belum-lunas { background-color: #f8d7da; color: #721c24; } /* Red */
        .status-dp { background-color: #fff3cd; color: #856404; } /* Yellow */

        .payment-item {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1rem;
        }
        .payment-item:last-child {
            margin-bottom: 0;
        }
        .payment-item .detail-label, .payment-item .detail-value {
            font-size: 0.9rem;
        }
        .payment-item .detail-value {
            font-weight: 500;
        }
        .payment-item .detail-label {
            color: #6c757d;
        }
        .file-link {
            display: inline-flex;
            align-items: center;
            color: #0d6efd;
            text-decoration: none;
            font-weight: 500;
        }
        .file-link:hover {
            text-decoration: underline;
        }
        .file-icon {
            margin-right: 0.5rem;
        }
    </style>
</head>

<div class="container-fluid py-4 px-3 px-md-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8 mb-3 mb-md-0">
            <h4 class="text-dark fw-bold mb-0">Detail Transaksi Penjualan</h4>
            <small class="text-secondary">Informasi lengkap mengenai transaksi penjualan ini.</small>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('transaksi-penjualan.index') }}" class="btn btn-outline-secondary rounded-pill animate__animated animate__fadeInRight">
                <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar Transaksi
            </a>
            <a href="{{ route('transaksi-penjualan.edit', $transaksi_penjualan->id) }}" class="btn btn-primary rounded-pill animate__animated animate__fadeInRight ms-2">
                <i class="bi bi-pencil-square me-2"></i> Edit Transaksi
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-xl rounded-4 animate__animated animate__fadeInUp">
        <div class="card-body p-lg-5 p-md-4 p-3 card-detail">

            {{-- Bagian Informasi Umum Transaksi --}}
            <div class="section-title">
                <i class="bi bi-info-circle-fill"></i> Informasi Umum Transaksi
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="detail-group">
                        <div class="detail-label">Kode Transaksi:</div>
                        <div class="detail-value">{{ $transaksi_penjualan->kode_transaksi }}</div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="detail-group">
                        <div class="detail-label">Tanggal Transaksi:</div>
                        <div class="detail-value">{{ Carbon::parse($transaksi_penjualan->tanggal_transaksi)->translatedFormat('d F Y') }}</div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="detail-group">
                        <div class="detail-label">Metode Pembayaran Utama:</div>
                        <div class="detail-value">{{ ucfirst(str_replace('_', ' ', $transaksi_penjualan->metode_pembayaran)) }}</div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="detail-group">
                        <div class="detail-label">Harga Negosiasi:</div>
                        <div class="detail-value">Rp{{ number_format($transaksi_penjualan->harga_negosiasi, 0, ',', '.') }}</div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="detail-group">
                        <div class="detail-label">Status Pembayaran:</div>
                        <div class="detail-value">
                            @php
                                $statusClass = '';
                                if ($transaksi_penjualan->status === 'lunas') {
                                    $statusClass = 'status-lunas';
                                } elseif ($transaksi_penjualan->status === 'belum lunas') {
                                    $statusClass = 'status-belum-lunas';
                                } elseif ($transaksi_penjualan->status === 'dp') {
                                    $statusClass = 'status-dp';
                                }
                            @endphp
                            <span class="status-badge {{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $transaksi_penjualan->status)) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bagian Detail Mobil --}}
            <div class="section-title mt-4">
                <i class="bi bi-car-front-fill"></i> Detail Mobil
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="detail-group">
                        <div class="detail-label">Merk Mobil:</div>
                        <div class="detail-value">{{ $transaksi_penjualan->mobil->merek_mobil ?? '-' }}</div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="detail-group">
                        <div class="detail-label">Tipe Mobil:</div>
                        <div class="detail-value">{{ $transaksi_penjualan->mobil->tipe_mobil ?? '-' }}</div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="detail-group">
                        <div class="detail-label">Tahun Pembuatan:</div>
                        <div class="detail-value">{{ $transaksi_penjualan->mobil->tahun_pembuatan ?? '-' }}</div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="detail-group">
                        <div class="detail-label">Nomor Polisi:</div>
                        <div class="detail-value">{{ $transaksi_penjualan->mobil->nomor_polisi ?? '-' }}</div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="detail-group">
                        <div class="detail-label">Harga Dasar Mobil:</div>
                        <div class="detail-value">Rp{{ number_format($transaksi_penjualan->mobil->harga_mobil ?? 0, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>

            {{-- Bagian Detail Pembeli --}}
            <div class="section-title mt-4">
                <i class="bi bi-person-fill"></i> Detail Pembeli
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="detail-group">
                        <div class="detail-label">Nama Pembeli:</div>
                        <div class="detail-value">{{ $transaksi_penjualan->pembeli->nama ?? '-' }}</div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="detail-group">
                        <div class="detail-label">Nomor Telepon:</div>
                        <div class="detail-value">{{ $transaksi_penjualan->pembeli->no_telepon ?? '-' }}</div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="detail-group">
                        <div class="detail-label">Alamat:</div>
                        <div class="detail-value">{{ $transaksi_penjualan->pembeli->alamat ?? '-' }}</div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="detail-group">
                        <div class="detail-label">Pekerjaan:</div>
                        <div class="detail-value">{{ $transaksi_penjualan->pembeli->pekerjaan ?? '-' }}</div>
                    </div>
                </div>
            </div>

            {{-- Bagian Detail Kredit (Hanya tampil jika metode pembayaran adalah 'kredit') --}}
            @if ($transaksi_penjualan->metode_pembayaran === 'kredit' && $transaksi_penjualan->kreditDetail)
            <div class="section-title mt-4">
                <i class="bi bi-wallet-fill"></i> Detail Kredit
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="detail-group">
                        <div class="detail-label">DP (Down Payment):</div>
                        <div class="detail-value">Rp{{ number_format($transaksi_penjualan->kreditDetail->dp, 0, ',', '.') }}</div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="detail-group">
                        <div class="detail-label">Tempo (Bulan):</div>
                        <div class="detail-value">{{ $transaksi_penjualan->kreditDetail->tempo }} Bulan</div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="detail-group">
                        <div class="detail-label">Leasing:</div>
                        <div class="detail-value">{{ $transaksi_penjualan->kreditDetail->leasing }}</div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="detail-group">
                        <div class="detail-label">Angsuran Per Bulan:</div>
                        <div class="detail-value">Rp{{ number_format($transaksi_penjualan->kreditDetail->angsuran_per_bulan, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Bagian Detail Pembayaran --}}
            <div class="section-title mt-4">
                <i class="bi bi-cash-coin"></i> Detail Pembayaran
            </div>
            @forelse ($transaksi_penjualan->pembayaranDetails as $detail)
            <div class="payment-item mb-3">
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <div class="detail-label">Metode Pembayaran:</div>
                        <div class="detail-value">{{ ucfirst(str_replace('_', ' ', $detail->metode_pembayaran_detail)) }}</div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <div class="detail-label">Jumlah Pembayaran:</div>
                        <div class="detail-value">Rp{{ number_format($detail->jumlah_pembayaran, 0, ',', '.') }}</div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <div class="detail-label">Tanggal Pembayaran:</div>
                        <div class="detail-value">{{ Carbon::parse($detail->tanggal_pembayaran)->translatedFormat('d F Y') }}</div>
                    </div>
                    <div class="col-md-12 mb-2">
                        <div class="detail-label">Keterangan:</div>
                        <div class="detail-value">{{ $detail->keterangan_pembayaran_detail ?? '-' }}</div>
                    </div>
                    <div class="col-md-12">
                        <div class="detail-label">Bukti Pembayaran:</div>
                        <div class="detail-value">
                            @if ($detail->bukti_pembayaran_detail)
                                <a href="{{ Storage::url(str_replace('/storage/', '', $detail->bukti_pembayaran_detail)) }}" target="_blank" class="file-link">
                                    <i class="bi bi-file-earmark-fill file-icon"></i> Lihat Bukti
                                </a>
                            @else
                                Tidak ada bukti
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="alert alert-info text-center py-3">
                <i class="bi bi-info-circle-fill me-2"></i> Belum ada detail pembayaran untuk transaksi ini.
            </div>
            @endforelse

        </div>
    </div>
</div>
@endsection
