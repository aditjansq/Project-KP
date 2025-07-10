@extends('layouts.app')

@section('title', 'Edit Pembeli')

@section('content')
{{-- Pindahkan pernyataan use ke sini, di awal file --}}
@php
    use Illuminate\Support\Facades\Storage;
@endphp

<head>
    {{-- Animate.css for subtle animations --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    {{-- Font Awesome untuk ikon file --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<div class="container-fluid py-4 px-3 px-md-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8 mb-3 mb-md-0">
            <h4 class="text-dark fw-bold mb-0">Edit Data Pembeli</h4>
            <small class="text-secondary">Perbarui informasi pembeli di formulir di bawah ini.</small>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('pembeli.index') }}" class="btn btn-outline-secondary rounded-pill animate__animated animate__fadeInRight">
                <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar Pembeli
            </a>
        </div>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger border-0 shadow-sm rounded-3 animate__animated animate__shakeX" role="alert">
        <h6 class="alert-heading fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i> Terjadi Kesalahan Input:</h6>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card border-0 shadow-xl rounded-4 animate__animated animate__fadeInUp">
        <div class="card-body p-lg-5 p-md-4 p-3">
            <form method="POST" action="{{ route('pembeli.update', $pembeli->id) }}" novalidate enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @php
                    $old = fn($field) => old($field, $pembeli->$field);
                @endphp

                <h5 class="mb-4 fw-bold text-dark border-bottom pb-2">Informasi Pembeli</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="kode_pembeli" class="form-label text-muted">Kode Pembeli</label>
                        <input type="text" name="kode_pembeli" id="kode_pembeli" class="form-control form-control-lg bg-light-subtle rounded-pill border-0 shadow-sm" value="{{ $pembeli->kode_pembeli }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label for="nama" class="form-label text-muted">Nama Pembeli</label>
                        <input type="text" name="nama" id="nama" class="form-control form-control-lg rounded-pill shadow-sm" value="{{ $old('nama') }}" required>
                        @error('nama')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="tanggal_lahir" class="form-label text-muted">Tanggal Lahir</label>
                        @php
                            // Dapatkan nilai tanggal lahir, prioritaskan input lama, lalu data dari model pembeli
                            $dateValue = old('tanggal_lahir', $pembeli->tanggal_lahir ?? '');

                            // Format tanggal jika nilai ada dan bukan string kosong
                            if ($dateValue) {
                                try {
                                    // Parse nilai dan format ke 'YYYY-MM-DD'
                                    $dateValue = \Carbon\Carbon::parse($dateValue)->format('Y-m-d');
                                } catch (\Exception $e) {
                                    // Jika gagal parsing (misal, format tanggal salah), set nilai menjadi kosong
                                    $dateValue = '';
                                }
                            }
                        @endphp
                        <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control form-control-lg rounded-pill shadow-sm" value="{{ $dateValue }}" max="{{ date('Y-m-d') }}" required>
                        @error('tanggal_lahir')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="no_telepon" class="form-label text-muted">No. Telepon</label>
                        <input type="text" name="no_telepon" id="no_telepon" class="form-control form-control-lg rounded-pill shadow-sm" value="{{ $old('no_telepon') }}">
                        @error('no_telepon')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-12">
                        <label for="alamat" class="form-label text-muted">Alamat</label>
                        <textarea name="alamat" id="alamat" class="form-control form-control-lg rounded-3 shadow-sm" rows="3" required>{{ $old('alamat') }}</textarea>
                        @error('alamat')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="pekerjaan" class="form-label text-muted">Pekerjaan</label>
                        <input type="text" name="pekerjaan" id="pekerjaan" class="form-control form-control-lg rounded-pill shadow-sm" value="{{ $old('pekerjaan') }}">
                        @error('pekerjaan')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <h5 class="mb-4 fw-bold text-dark border-bottom pb-2 mt-5">Dokumen Pendukung</h5>
                <div class="row g-3 mb-4">
                    {{-- KTP Pasangan --}}
                    <div class="col-md-6">
                        <label for="ktp_pasangan" class="form-label text-muted">Upload KTP Suami/Istri (jika ada)</label>
                        <input type="file" name="ktp_pasangan" id="ktp_pasangan" class="form-control form-control-lg rounded-pill shadow-sm" accept=".jpeg,.png,.pdf">
                        <div id="ktp_pasangan_preview" class="mt-2 preview-container">
                            @if ($pembeli->ktp_pasangan)
                                @php
                                    $fileExtension = pathinfo($pembeli->ktp_pasangan, PATHINFO_EXTENSION);
                                    $fileName = basename($pembeli->ktp_pasangan);
                                    $fileUrl = Storage::url($pembeli->ktp_pasangan);
                                @endphp
                                @if (in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png']))
                                    <img src="{{ $fileUrl }}" alt="{{ $fileName }}">
                                @else
                                    <div class="file-icon-preview">
                                        <i class="fas fa-file-pdf"></i>
                                        <span>{{ $fileName }}</span>
                                    </div>
                                @endif
                                <a href="{{ $fileUrl }}" target="_blank" class="preview-link">Lihat Dokumen</a>
                                {{-- Menampilkan Nama File Saja --}}
                                <small class="text-muted text-center mt-1" style="font-size: 0.75em; word-break: break-all; padding: 0 5px;">
                                    Nama File: `{{ basename($pembeli->ktp_pasangan) }}`
                                </small>
                            @endif
                        </div>
                        @error('ktp_pasangan')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Kartu Keluarga --}}
                    <div class="col-md-6">
                        <label for="kartu_keluarga" class="form-label text-muted">Upload Kartu Keluarga</label>
                        <input type="file" name="kartu_keluarga" id="kartu_keluarga" class="form-control form-control-lg rounded-pill shadow-sm" accept=".jpeg,.png,.pdf">
                        <div id="kartu_keluarga_preview" class="mt-2 preview-container">
                            @if ($pembeli->kartu_keluarga)
                                @php
                                    $fileExtension = pathinfo($pembeli->kartu_keluarga, PATHINFO_EXTENSION);
                                    $fileName = basename($pembeli->kartu_keluarga);
                                    $fileUrl = Storage::url($pembeli->kartu_keluarga);
                                @endphp
                                @if (in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png']))
                                    <img src="{{ $fileUrl }}" alt="{{ $fileName }}">
                                @else
                                    <div class="file-icon-preview">
                                        <i class="fas fa-file-pdf"></i>
                                        <span>{{ $fileName }}</span>
                                    </div>
                                @endif
                                <a href="{{ $fileUrl }}" target="_blank" class="preview-link">Lihat Dokumen</a>
                                {{-- Menampilkan Nama File Saja --}}
                                <small class="text-muted text-center mt-1" style="font-size: 0.75em; word-break: break-all; padding: 0 5px;">
                                    Nama File: `{{ basename($pembeli->kartu_keluarga) }}`
                                </small>
                            @endif
                        </div>
                        @error('kartu_keluarga')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Slip Gaji --}}
                    <div class="col-md-6">
                        <label for="slip_gaji" class="form-label text-muted">Upload Slip Gaji</label>
                        <input type="file" name="slip_gaji" id="slip_gaji" class="form-control form-control-lg rounded-pill shadow-sm" accept=".jpeg,.png,.pdf">
                        <div id="slip_gaji_preview" class="mt-2 preview-container">
                            @if ($pembeli->slip_gaji)
                                @php
                                    $fileExtension = pathinfo($pembeli->slip_gaji, PATHINFO_EXTENSION);
                                    $fileName = basename($pembeli->slip_gaji);
                                    $fileUrl = Storage::url($pembeli->slip_gaji);
                                @endphp
                                @if (in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png']))
                                    <img src="{{ $fileUrl }}" alt="{{ $fileName }}">
                                @else
                                    <div class="file-icon-preview">
                                        <i class="fas fa-file-pdf"></i>
                                        <span>{{ $fileName }}</span>
                                    </div>
                                @endif
                                <a href="{{ $fileUrl }}" target="_blank" class="preview-link">Lihat Dokumen</a>
                                {{-- Menampilkan Nama File Saja --}}
                                <small class="text-muted text-center mt-1" style="font-size: 0.75em; word-break: break-all; padding: 0 5px;">
                                    Nama File: `{{ basename($pembeli->slip_gaji) }}`
                                </small>
                            @endif
                        </div>
                        @error('slip_gaji')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-5 d-flex justify-content-end">
                    <button type="submit" class="btn btn-success btn-lg px-4 rounded-pill shadow-sm animate__animated animate__pulse animate__infinite">
                        <i class="bi bi-save me-2"></i>Update Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Styling yang sudah ada */
    body {
        background-color: #f8f9fa;
        font-family: 'Poppins', sans-serif;
        color: #343a40;
    }

    .container-fluid.py-4 {
        padding-top: 2.5rem !important;
        padding-bottom: 2.5rem !important;
    }

    .form-label {
        font-size: 0.9rem;
        font-weight: 500;
        color: #555;
    }

    .form-control-lg, .form-select-lg {
        padding: 0.75rem 1.25rem;
        border-radius: 0.75rem !important;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        border: 1px solid #dee2e6; /* subtle border */
    }

    textarea.form-control-lg {
        border-radius: 0.75rem !important;
    }

    .form-control-lg:focus, .form-select-lg:focus, textarea.form-control:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13,110,253,.25), 0 2px 8px rgba(0,0,0,0.1);
    }

    input[type="file"].form-control-lg {
        height: auto;
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
    }

    .bg-light-subtle {
        background-color: #f8f9fa !important;
    }

    .card {
        border-radius: 1rem !important;
        overflow: hidden;
        box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.09) !important;
    }

    .alert-danger {
        background-color: #fef2f2;
        color: #721c24;
        border: 1px solid #f5c6cb;
        border-radius: 0.75rem;
        padding: 1.25rem 1.75rem;
    }
    .alert-danger .alert-heading {
        color: #dc3545;
        font-size: 1.1rem;
    }
    .alert-danger ul {
        padding-left: 25px;
    }
    .alert-danger li {
        margin-bottom: 5px;
    }

    .btn-primary {
        background: linear-gradient(45deg, #0d6efd, #0b5ed7);
        border: none;
        transition: all 0.3s ease;
        font-weight: 600;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 10px rgba(13, 110, 253, 0.2);
    }
    .btn-primary:hover {
        background: linear-gradient(45deg, #0b5ed7, #0d6efd);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    }

    .btn-outline-secondary {
        border-color: #6c757d;
        color: #6c757d;
        transition: all 0.3s ease;
    }
    .btn-outline-secondary:hover {
        background-color: #6c757d;
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .btn-success {
        background: linear-gradient(45deg, #28a745, #218838);
        border: none;
        transition: all 0.3s ease;
        font-weight: 600;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 10px rgba(40, 167, 69, 0.2);
    }
    .btn-success:hover {
        background: linear-gradient(45deg, #218838, #28a745);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    }
    .animate__pulse {
        animation-duration: 2s;
    }

    @media (max-width: 768px) {
        .btn-lg {
            width: 100%;
            margin-bottom: 1rem;
        }
        .d-flex.justify-content-end.gap-3 {
            flex-direction: column;
            gap: 1rem;
        }
        .d-flex.justify-content-between {
            flex-direction: column-reverse;
            gap: 1rem;
        }
    }

    /* Style untuk Preview File */
    .preview-container {
        width: 100%;
        max-width: 200px; /* Lebar maksimum untuk pratinjau */
        height: auto;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        overflow: hidden;
        display: flex;
        flex-direction: column; /* Mengubah arah menjadi kolom untuk ikon dan tautan */
        justify-content: center;
        align-items: center;
        background-color: #f9f9f9;
        margin-top: 8px;
        min-height: 100px; /* Tinggi minimum agar konsisten */
    }

    .preview-container img {
        max-width: 100%;
        max-height: 150px; /* Tinggi maksimum untuk gambar */
        display: block;
        object-fit: contain;
    }

    .preview-container .file-icon-preview {
        padding: 20px;
        text-align: center;
        color: #6c757d;
        flex-grow: 1; /* Agar mengambil ruang yang tersedia */
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .preview-container .file-icon-preview i {
        font-size: 3em;
        margin-bottom: 5px;
    }

    .preview-container .file-icon-preview span {
        display: block;
        font-size: 0.8em;
        word-break: break-all;
    }

    .preview-link {
        display: block;
        width: 100%;
        padding: 10px;
        text-align: center;
        font-size: 0.9em;
        background-color: #e9ecef;
        border-top: 1px solid #e0e0e0;
        text-decoration: none;
        color: #007bff;
    }

    .preview-link:hover {
        background-color: #dee2e6;
        color: #0056b3;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInputs = ['ktp_pasangan', 'kartu_keluarga', 'slip_gaji'];

        fileInputs.forEach(inputId => {
            const inputElement = document.getElementById(inputId);
            const previewContainer = document.getElementById(`${inputId}_preview`);

            inputElement.addEventListener('change', function() {
                previewContainer.innerHTML = ''; // Bersihkan pratinjau sebelumnya

                const file = this.files[0];
                if (file) {
                    const fileType = file.type;
                    const fileName = file.name;

                    if (fileType.startsWith('image/')) {
                        // Pratinjau Gambar
                        const img = document.createElement('img');
                        img.src = URL.createObjectURL(file);
                        img.alt = fileName;
                        previewContainer.appendChild(img);
                    } else if (fileType === 'application/pdf') {
                        // Pratinjau PDF (ikon dan tautan)
                        const iconHtml = `
                            <div class="file-icon-preview">
                                <i class="fas fa-file-pdf"></i>
                                <span>${fileName}</span>
                            </div>
                            <a href="${URL.createObjectURL(file)}" target="_blank" class="preview-link">Lihat PDF</a>
                        `;
                        previewContainer.innerHTML = iconHtml;
                    } else {
                        // Jenis file lain (ikon umum)
                        const iconHtml = `
                            <div class="file-icon-preview">
                                <i class="fas fa-file"></i>
                                <span>${fileName}</span>
                            </div>
                        `;
                        previewContainer.innerHTML = iconHtml;
                    }
                    // Tambahkan display path untuk file yang baru diupload
                    const pathDisplay = document.createElement('small');
                    pathDisplay.className = 'text-muted text-center mt-1';
                    pathDisplay.style.cssText = 'font-size: 0.75em; word-break: break-all; padding: 0 5px;';
                    pathDisplay.innerHTML = `Nama File: \`${fileName}\``; // Untuk file baru, tampilkan namanya saja
                    previewContainer.appendChild(pathDisplay);

                } else {
                    // Jika file dihapus dari input, tampilkan pratinjau file yang sudah ada (jika ada)
                    // Atau kosongkan jika tidak ada file lama
                    // Ini memerlukan data lama dari backend, yang saat ini tidak mudah diakses oleh JS langsung dari `old()`
                    // Untuk kesederhanaan, jika tidak ada file baru dipilih, preview dikosongkan.
                    // Jika ada file lama, itu akan dirender ulang dari Blade saat halaman dimuat.
                }
            });
        });

        // Fungsi helper untuk mendapatkan nama file dasar dari path
        function basename(path) {
            return path.split('/').reverse()[0];
        }
    });
</script>
@endsection
