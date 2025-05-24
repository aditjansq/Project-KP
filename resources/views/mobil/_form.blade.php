@extends('layouts.app')

@section('title', isset($mobil) ? 'Edit Mobil' : 'Tambah Mobil')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col">
            <h4 class="fw-bold text-dark">{{ isset($mobil) ? 'Edit Mobil' : 'Tambah Mobil' }}</h4>
            <p class="text-muted mb-0">Silakan lengkapi form berikut dengan data yang benar.</p>
        </div>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger border-0 shadow-sm">
        <strong>Terjadi kesalahan:</strong>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
            <li><i class="bi bi-exclamation-circle me-1"></i>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ isset($mobil) ? route('mobil.update', $mobil->id) : route('mobil.store') }}">
                @csrf
                @if(isset($mobil)) @method('PUT') @endif

                @php
                    $old = fn($name) => old($name, $mobil->$name ?? '');
                @endphp

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tipe Mobil</label>
                        <input type="text" name="tipe_mobil" class="form-control" value="{{ $old('tipe_mobil') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Merek Mobil</label>
                        <input type="text" name="merek_mobil" class="form-control" value="{{ $old('merek_mobil') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tahun Pembuatan</label>
                        <input type="number" name="tahun_pembuatan" class="form-control" min="1900" max="2155" value="{{ $old('tahun_pembuatan') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Warna Mobil</label>
                        <input type="text" name="warna_mobil" class="form-control" value="{{ $old('warna_mobil') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Harga Mobil</label>
                        <input type="number" name="harga_mobil" class="form-control" value="{{ $old('harga_mobil') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Bahan Bakar</label>
                        <input type="text" name="bahan_bakar" class="form-control" value="{{ $old('bahan_bakar') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Nomor Polisi</label>
                        <input type="text" name="nomor_polisi" class="form-control" value="{{ $old('nomor_polisi') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Nomor Rangka</label>
                        <input type="text" name="nomor_rangka" class="form-control" value="{{ $old('nomor_rangka') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Nomor Mesin</label>
                        <input type="text" name="nomor_mesin" class="form-control" value="{{ $old('nomor_mesin') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Nomor BPKB</label>
                        <input type="text" name="nomor_bpkb" class="form-control" value="{{ $old('nomor_bpkb') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tanggal Masuk</label>
                        <input type="date" name="tanggal_masuk" class="form-control" value="{{ $old('tanggal_masuk') }}" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Status Mobil</label>
                        <select name="status_mobil" class="form-select" required>
                            <option value="baru" {{ $old('status_mobil') == 'baru' ? 'selected' : '' }}>Baru</option>
                            <option value="bekas" {{ $old('status_mobil') == 'bekas' ? 'selected' : '' }}>Bekas</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Stok</label>
                        <select name="stok" class="form-select" required>
                            <option value="ada" {{ $old('stok') == 'ada' ? 'selected' : '' }}>Ada</option>
                            <option value="tidak" {{ $old('stok') == 'tidak' ? 'selected' : '' }}>Tidak</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-between">
                    <a href="{{ route('mobil.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Kembali
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save me-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
