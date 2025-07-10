@extends('layouts.app')

@section('title', 'Tambah Pembeli')

@section('content')
<head>
    {{-- Animate.css for subtle animations --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    {{-- Font Awesome untuk ikon file --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<div class="container-fluid py-4 px-3 px-md-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8 mb-3 mb-md-0">
            <h4 class="text-dark fw-bold mb-0">Tambah Data Pembeli Baru</h4>
            <small class="text-secondary">Silakan lengkapi form berikut dengan data yang benar.</small>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('pembeli.index') }}" class="btn btn-outline-secondary rounded-pill animate__animated animate__fadeInRight">
                <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar Pembeli
            </a>
        </div>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger border-0 shadow-sm rounded-3 animate__animated animate__shakeX" role="alert">
        <h6 class="alert-heading fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i> Terjadi Kesalahan Input!</h6>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card border-0 shadow-xl rounded-4 animate__animated animate__fadeInUp">
        <div class="card-body p-lg-5 p-md-4 p-3">
            <form method="POST" action="{{ route('pembeli.store') }}" enctype="multipart/form-data">
                @csrf

                <h5 class="mb-4 fw-bold text-dark border-bottom pb-2">Informasi Pembeli</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="kode_pembeli" class="form-label text-muted">Kode Pembeli</label>
                        <input type="text" id="kode_pembeli" name="kode_pembeli" class="form-control form-control-lg bg-light-subtle rounded-pill border-0 shadow-sm" value="{{ $newCode }}" readonly>
                        @error('kode_pembeli')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="nama" class="form-label text-muted">Nama Pembeli</label>
                        <input type="text" id="nama" name="nama" class="form-control form-control-lg rounded-pill shadow-sm" value="{{ old('nama') }}" placeholder="Masukkan nama pembeli" required
                               minlength="3"
                               pattern="[A-Za-z\s]+"
                               title="Nama pembeli harus terdiri dari huruf dan minimal 3 karakter">
                        @error('nama')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="tanggal_lahir" class="form-label text-muted">Tanggal Lahir</label>
                        <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="form-control form-control-lg rounded-pill shadow-sm" value="{{ old('tanggal_lahir') }}" max="{{ date('Y-m-d') }}" required>
                        @error('tanggal_lahir')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="no_telepon" class="form-label text-muted">No. Telepon</label>
                        <input type="text" id="no_telepon" name="no_telepon" class="form-control form-control-lg rounded-pill shadow-sm" value="{{ old('no_telepon') }}" placeholder="Masukkan nomor telepon"
                               pattern="\d{10,15}"
                               title="No Telepon harus berisi angka dan terdiri dari 10 hingga 15 digit.">
                        @error('no_telepon')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="alamat" class="form-label text-muted">Alamat</label>
                        <textarea id="alamat" name="alamat" class="form-control form-control-lg rounded-3 shadow-sm" rows="3" placeholder="Masukkan alamat lengkap" required
                                 minlength="4"
                                 title="Alamat harus memiliki minimal 4 karakter">{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="pekerjaan" class="form-label text-muted">Pekerjaan</label>
                        <input type="text" id="pekerjaan" name="pekerjaan" class="form-control form-control-lg rounded-pill shadow-sm" value="{{ old('pekerjaan') }}" placeholder="Masukkan pekerjaan (cth: Wiraswasta, Pegawai Swasta)"
                               pattern="[A-Za-z\s]{4,}"
                               title="Pekerjaan harus terdiri dari minimal 4 karakter yang hanya berisi huruf">
                        @error('pekerjaan')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <h5 class="mb-4 fw-bold text-dark border-bottom pb-2 mt-5">Dokumen Pendukung (Opsional)</h5>
                <div class="row g-3 mb-4">
                    {{-- KTP Pasangan --}}
                    <div class="col-md-6">
                        <label for="ktp_pasangan" class="form-label text-muted">Upload KTP Suami/Istri (jika ada)</label>
                        <input type="file" name="ktp_pasangan" id="ktp_pasangan" class="form-control form-control-lg rounded-pill shadow-sm" accept=".jpeg,.png,.pdf">
                        <div id="ktp_pasangan_preview" class="mt-2 preview-container"></div>
                        @error('ktp_pasangan')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Kartu Keluarga --}}
                    <div class="col-md-6">
                        <label for="kartu_keluarga" class="form-label text-muted">Upload Kartu Keluarga</label>
                        {{-- Menghapus atribut 'required' --}}
                        <input type="file" name="kartu_keluarga" id="kartu_keluarga" class="form-control form-control-lg rounded-pill shadow-sm" accept=".jpeg,.png,.pdf">
                        <div id="kartu_keluarga_preview" class="mt-2 preview-container"></div>
                        @error('kartu_keluarga')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Slip Gaji --}}
                    <div class="col-md-6">
                        <label for="slip_gaji" class="form-label text-muted">Upload Slip Gaji</label>
                        {{-- Menghapus atribut 'required' --}}
                        <input type="file" name="slip_gaji" id="slip_gaji" class="form-control form-control-lg rounded-pill shadow-sm" accept=".jpeg,.png,.pdf">
                        <div id="slip_gaji_preview" class="mt-2 preview-container"></div>
                        @error('slip_gaji')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-5 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary btn-lg px-5 rounded-pill shadow-sm animate__animated animate__pulse animate__infinite">
                        <i class="bi bi-save me-2"></i> Simpan Data
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
        flex-direction: column; /* Changed to column for better stacking of icon/link */
        justify-content: center;
        align-items: center;
        background-color: #f9f9f9;
        margin-top: 8px;
        min-height: 90px; /* Added min-height for consistent display */
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
        flex-grow: 1; /* Allow content to grow */
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .preview-container .file-icon-preview i {
        font-size: 3em;
    }

    .preview-container .file-icon-preview span {
        display: block;
        font-size: 0.8em;
        margin-top: 5px;
        word-break: break-all;
    }

    .preview-link {
        display: block;
        width: 100%; /* Ensure link takes full width of container */
        padding: 10px;
        text-align: center;
        font-size: 0.9em;
        background-color: #e9ecef;
        border-top: 1px solid #e0e0e0;
        text-decoration: none;
        color: #007bff;
        transition: background-color 0.2s ease;
    }
    .preview-link:hover {
        background-color: #d1e7dd; /* Warna hijau lembut saat hover */
        color: #0f5132;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInputs = ['ktp_pasangan', 'kartu_keluarga', 'slip_gaji'];

        fileInputs.forEach(inputId => {
            const inputElement = document.getElementById(inputId);
            const previewContainer = document.getElementById(`${inputId}_preview`);

            // Initial check for existing files (if this were an edit page)
            // For a create page, it will always be empty initially.
            previewContainer.innerHTML = '<div class="file-icon-preview"><i class="fas fa-upload text-muted"></i><span class="text-muted small">Pilih file...</span></div>';


            inputElement.addEventListener('change', function() {
                previewContainer.innerHTML = ''; // Clear previous preview

                const file = this.files[0];
                if (file) {
                    const fileType = file.type;
                    const fileName = file.name;

                    if (fileType.startsWith('image/')) {
                        // Image preview
                        const img = document.createElement('img');
                        img.src = URL.createObjectURL(file);
                        img.alt = fileName;
                        previewContainer.appendChild(img);
                    } else if (fileType === 'application/pdf') {
                        // PDF preview (icon and link)
                        const iconHtml = `
                            <div class="file-icon-preview">
                                <i class="fas fa-file-pdf"></i>
                                <span>${fileName}</span>
                            </div>
                        `;
                        previewContainer.innerHTML = iconHtml;
                    } else {
                        // Other file types (generic icon)
                        const iconHtml = `
                            <div class="file-icon-preview">
                                <i class="fas fa-file"></i>
                                <span>${fileName}</span>
                            </div>
                        `;
                        previewContainer.innerHTML = iconHtml;
                    }
                    // Add "Lihat Dokumen" link for all uploaded files
                    const link = document.createElement('a');
                    link.href = URL.createObjectURL(file);
                    link.target = '_blank';
                    link.className = 'preview-link';
                    link.textContent = 'Lihat Dokumen';
                    previewContainer.appendChild(link);

                } else {
                    // If no file is selected (e.g., user cancels file selection)
                    previewContainer.innerHTML = '<div class="file-icon-preview"><i class="fas fa-upload text-muted"></i><span class="text-muted small">Pilih file...</span></div>';
                }
            });
        });
    });
</script>
@endsection
