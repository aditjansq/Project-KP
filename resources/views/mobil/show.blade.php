@extends('layouts.app')

@section('title', 'Detail Data Mobil')

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
            font-family: 'Poppins', sans-serif;
            color: #343a40;
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
        .status-tersedia { background-color: #d4edda; color: #155724; } /* Green */
        .status-terjual { background-color: #f8d7da; color: #721c24; } /* Red */
        .status-booking { background-color: #fff3cd; color: #856404; } /* Yellow */
        .status-perbaikan { background-color: #cce5ff; color: #004085; } /* Blue */

        .ketersediaan-badge {
            padding: 0.4em 0.8em;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.85em;
        }
        .ketersediaan-ada { background-color: #d4edda; color: #155724; }
        .ketersediaan-tidak-ada { background-color: #f8d7da; color: #721c24; }

        /* Badge for Kondisi Mobil */
        .kondisi-badge {
            padding: 0.4em 0.8em;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.85em;
        }
        .kondisi-baru { background-color: #28a745; color: white; } /* Green */
        .kondisi-bekas { background-color: #ffc107; color: #212529; } /* Yellow */


        .car-image-container {
            width: 100%;
            max-width: 400px; /* Max width for the image */
            height: auto;
            border-radius: 0.75rem;
            overflow: hidden;
            margin-bottom: 2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .car-image-container img {
            width: 100%;
            height: auto;
            display: block;
        }
        .no-image-placeholder {
            background-color: #e9ecef;
            color: #6c757d;
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.75rem;
            font-size: 1.1rem;
            text-align: center;
        }
    </style>
</head>

<div class="container-fluid py-4 px-3 px-md-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8 mb-3 mb-md-0">
            <h4 class="text-dark fw-bold mb-0">Detail Data Mobil</h4>
            <small class="text-secondary">Informasi lengkap mengenai mobil ini.</small>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('mobil.index') }}" class="btn btn-outline-secondary rounded-pill animate__animated animate__fadeInRight">
                <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar Mobil
            </a>
            <a href="{{ route('mobil.edit', $mobil->id) }}" class="btn btn-primary rounded-pill animate__animated animate__fadeInRight ms-2">
                <i class="bi bi-pencil-square me-2"></i> Edit Mobil
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-xl rounded-4 animate__animated animate__fadeInUp">
        <div class="card-body p-lg-5 p-md-4 p-3 card-detail">

            {{-- Bagian Gambar Mobil --}}
            <div class="d-flex justify-content-center mb-4">
                <div class="car-image-container">
                    @if ($mobil->gambar_mobil)
                        <img src="{{ Storage::url(str_replace('public/', '', $mobil->gambar_mobil)) }}" alt="Gambar Mobil {{ $mobil->merek_mobil }} {{ $mobil->tipe_mobil }}" class="img-fluid rounded-3">
                    @else
                        <div class="no-image-placeholder">
                            <i class="bi bi-image-fill me-2"></i> Tidak Ada Gambar Mobil
                        </div>
                    @endif
                </div>
            </div>

            {{-- Bagian Informasi Umum Mobil --}}
            <div class="section-title">
                <i class="bi bi-info-circle-fill"></i> Informasi Umum Mobil
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="detail-group">
                        <div class="detail-label">Kode Mobil:</div>
                        <div class="detail-value">{{ $mobil->kode_mobil ?? '-' }}</div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="detail-group">
                        <div class="detail-label">Jenis Mobil:</div>
                        <div class="detail-value">{{ ucfirst($mobil->jenis_mobil ?? '-') }}</div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="detail-group">
                        <div class="detail-label">Merek Mobil:</div>
                        <div class="detail-value">{{ $mobil->merek_mobil ?? '-' }}</div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="detail-group">
                        <div class="detail-label">Tipe Mobil:</div>
                        <div class="detail-value">{{ $mobil->tipe_mobil ?? '-' }}</div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="detail-group">
                        <div class="detail-label">Tahun Pembuatan:</div>
                        <div class="detail-value">{{ $mobil->tahun_pembuatan ?? '-' }}</div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="detail-group">
                        <div class="detail-label">Nomor Polisi:</div>
                        <div class="detail-value">{{ $mobil->nomor_polisi ?? '-' }}</div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="detail-group">
                        <div class="detail-label">Transmisi:</div>
                        <div class="detail-value">{{ ucfirst($mobil->transmisi ?? '-') }}</div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="detail-group">
                        <div class="detail-label">Warna:</div>
                        <div class="detail-value">{{ ucfirst($mobil->warna_mobil ?? '-') }}</div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="detail-group">
                        <div class="detail-label">Bahan Bakar:</div>
                        <div class="detail-value">{{ ucfirst($mobil->bahan_bakar ?? '-') }}</div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="detail-group">
                        <div class="detail-label">Harga Mobil:</div>
                        <div class="detail-value">Rp{{ number_format($mobil->harga_mobil ?? 0, 0, ',', '.') }}</div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="detail-group">
                        <div class="detail-label">Kondisi Mobil:</div>
                        <div class="detail-value">
                            @php
                                $kondisi = strtolower($mobil->status_mobil ?? ''); // Assuming 'status_mobil' from JSON maps to this
                                $kondisiClass = '';
                                if ($kondisi === 'baru') {
                                    $kondisiClass = 'kondisi-baru';
                                } elseif ($kondisi === 'bekas') {
                                    $kondisiClass = 'kondisi-bekas';
                                }
                            @endphp
                            <span class="kondisi-badge {{ $kondisiClass }}">{{ ucfirst($kondisi) ?: '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bagian Status & Ketersediaan --}}
            <div class="section-title mt-4">
                <i class="bi bi-check-circle-fill"></i> Status & Ketersediaan
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="detail-group">
                        <div class="detail-label">Status Penjualan:</div>
                        <div class="detail-value">
                            @php
                                $statusClass = '';
                                if ($mobil->status === 'tersedia') {
                                    $statusClass = 'status-tersedia';
                                } elseif ($mobil->status === 'terjual') {
                                    $statusClass = 'status-terjual';
                                } elseif ($mobil->status === 'booking') {
                                    $statusClass = 'status-booking';
                                } elseif ($mobil->status === 'perbaikan') {
                                    $statusClass = 'status-perbaikan';
                                }
                            @endphp
                            <span class="status-badge {{ $statusClass }}">{{ ucfirst($mobil->status ?? '-') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="detail-group">
                        <div class="detail-label">Ketersediaan Fisik:</div>
                        <div class="detail-value">
                            @php
                                $ketersediaanClass = '';
                                if ($mobil->ketersediaan === 'ada') {
                                    $ketersediaanClass = 'ketersediaan-ada';
                                } else {
                                    $ketersediaanClass = 'ketersediaan-tidak-ada';
                                }
                            @endphp
                            <span class="ketersediaan-badge {{ $ketersediaanClass }}">{{ ucfirst($mobil->ketersediaan ?? '-') }}</span>
                        </div>
                    </div>
                </div>
            </div>


            {{-- Bagian Informasi Dokumen & Riwayat --}}
            <div class="section-title mt-4">
                <i class="bi bi-file-earmark-text-fill"></i> Informasi Dokumen & Riwayat
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="detail-group">
                        <div class="detail-label">Nomor Rangka:</div>
                        <div class="detail-value">{{ $mobil->nomor_rangka ?? '-' }}</div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="detail-group">
                        <div class="detail-label">Nomor Mesin:</div>
                        <div class="detail-value">{{ $mobil->nomor_mesin ?? '-' }}</div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="detail-group">
                        <div class="detail-label">Nomor BPKB:</div>
                        <div class="detail-value">{{ $mobil->nomor_bpkb ?? '-' }}</div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="detail-group">
                        <div class="detail-label">Tanggal Masuk:</div>
                        <div class="detail-value">{{ Carbon::parse($mobil->tanggal_masuk)->translatedFormat('d F Y') ?? '-' }}</div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="detail-group">
                        <div class="detail-label">Masa Berlaku Pajak:</div>
                        <div class="detail-value">{{ Carbon::parse($mobil->masa_berlaku_pajak)->translatedFormat('d F Y') ?? '-' }}</div>
                    </div>
                </div>
            </div>

            {{-- Bagian Deskripsi --}}
            <div class="section-title mt-4">
                <i class="bi bi-journal-text"></i> Deskripsi
            </div>
            <div class="row">
                <div class="col-12 mb-3">
                    <div class="detail-group">
                        <div class="detail-label">Deskripsi Mobil:</div>
                        <div class="detail-value">
                            {{ $mobil->deskripsi ?? 'Tidak ada deskripsi.' }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bagian Timestamp --}}
            <div class="section-title mt-4">
                <i class="bi bi-clock-history"></i> Informasi Waktu
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="detail-group">
                        <div class="detail-label">Dibuat Pada:</div>
                        <div class="detail-value">{{ Carbon::parse($mobil->created_at)->translatedFormat('d F Y H:i:s') }} WIB</div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="detail-group">
                        <div class="detail-label">Diperbarui Pada:</div>
                        <div class="detail-value">{{ Carbon::parse($mobil->updated_at)->translatedFormat('d F Y H:i:s') }} WIB</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
