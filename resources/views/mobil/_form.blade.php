@extends('layouts.app')

@section('title', isset($mobil) ? 'Edit Mobil' : 'Tambah Mobil')

@section('content')
<div class="container-fluid py-4 px-3 px-md-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8 mb-3 mb-md-0">
            <h4 class="text-dark fw-bold mb-0">{{ isset($mobil) ? 'Edit Data Mobil' : 'Tambah Data Mobil Baru' }}</h4>
            <small class="text-secondary">Silakan lengkapi form berikut dengan data yang benar.</small>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('mobil.index') }}" class="btn btn-outline-secondary rounded-pill animate__animated animate__fadeInRight">
                <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar Mobil
            </a>
        </div>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger border-0 shadow-sm rounded-3 animate__animated animate__fadeInDown" role="alert">
        <h6 class="alert-heading fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i> Terjadi Kesalahan Input!</h6>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card border-0 shadow-xl rounded-4 animate__animated animate__fadeInUp">
        <div class="card-body p-4 p-md-5"> {{-- Increased padding for larger screens --}}
            <form method="POST" action="{{ isset($mobil) ? route('mobil.update', $mobil->id) : route('mobil.store') }}">
                @csrf
                @if(isset($mobil)) @method('PUT') @endif

                @php
                    // Helper function to get old input or existing model data directly
                    // This assumes that $mobil->nomor_polisi holds the numeric part (e.g., "1234")
                    // and $mobil->nomor_polisi_selanjutnya holds the suffix (e.g., "ABC").
                    // $mobil->kode_wilayah holds the region code (e.g., "BA").
                    $old = fn($name) => old($name, $mobil->$name ?? '');
                @endphp

                <div class="row g-4"> {{-- Increased gutter spacing for better visual separation --}}

                    <div class="col-12 col-md-4">
                        <label for="jenis_mobil" class="form-label mb-1 fw-semibold">Jenis Mobil</label>
                        <select id="jenis_mobil" name="jenis_mobil" class="form-select form-select-lg rounded-3" required>
                            <option value="" disabled {{ !isset($mobil) ? 'selected' : '' }}>Pilih Jenis Mobil</option>
                            <option value="Sedan" {{ $old('jenis_mobil') == 'Sedan' ? 'selected' : '' }}>Sedan</option>
                            <option value="Hatchback" {{ $old('jenis_mobil') == 'Hatchback' ? 'selected' : '' }}>Hatchback</option>
                            <option value="SUV" {{ $old('jenis_mobil') == 'SUV' ? 'selected' : '' }}>SUV</option>
                            <option value="MPV" {{ $old('jenis_mobil') == 'MPV' ? 'selected' : '' }}>MPV</option>
                            <option value="Crossover" {{ $old('jenis_mobil') == 'Crossover' ? 'selected' : '' }}>Crossover</option>
                            <option value="Coupe" {{ $old('jenis_mobil') == 'Coupe' ? 'selected' : '' }}>Coupe</option>
                            <option value="Convertible" {{ $old('jenis_mobil') == 'Convertible' ? 'selected' : '' }}>Convertible</option>
                            <option value="Sport Car" {{ $old('jenis_mobil') == 'Sport Car' ? 'selected' : '' }}>Sport Car</option>
                            <option value="Truck" {{ $old('jenis_mobil') == 'Truck' ? 'selected' : '' }}>Truck</option>
                            <option value="Minivan" {{ $old('jenis_mobil') == 'Minivan' ? 'selected' : '' }}>Minivan</option>
                            <option value="Electric Vehicle" {{ $old('jenis_mobil') == 'Electric Vehicle' ? 'selected' : '' }}>Electric Vehicle</option>
                            <option value="Hybrid" {{ $old('jenis_mobil') == 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                            <option value="Pickup" {{ $old('jenis_mobil') == 'Pickup' ? 'selected' : '' }}>Pickup</option>
                            <option value="Wagon" {{ $old('jenis_mobil') == 'Wagon' ? 'selected' : '' }}>Wagon</option>
                            <option value="City Car" {{ $old('jenis_mobil') == 'City Car' ? 'selected' : '' }}>City Car</option>
                            <option value="Off-road Vehicle" {{ $old('jenis_mobil') == 'Off-road Vehicle' ? 'selected' : '' }}>Off-road Vehicle</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="merek_mobil" class="form-label mb-1 fw-semibold">Merek Mobil</label>
                        <select id="merek_mobil" name="merek_mobil" class="form-select form-select-lg rounded-3" required>
                            <option value="" disabled {{ !isset($mobil) ? 'selected' : '' }}>Pilih Merek Mobil</option>
                            <option value="Toyota" {{ $old('merek_mobil') == 'Toyota' ? 'selected' : '' }}>Toyota</option>
                            <option value="Daihatsu" {{ $old('merek_mobil') == 'Daihatsu' ? 'selected' : '' }}>Daihatsu</option>
                            <option value="Honda" {{ $old('merek_mobil') == 'Honda' ? 'selected' : '' }}>Honda</option>
                            <option value="Mitsubishi" {{ $old('merek_mobil') == 'Mitsubishi' ? 'selected' : '' }}>Mitsubishi</option>
                            <option value="Suzuki" {{ $old('merek_mobil') == 'Suzuki' ? 'selected' : '' }}>Suzuki</option>
                            <option value="Hyundai" {{ $old('merek_mobil') == 'Hyundai' ? 'selected' : '' }}>Hyundai</option>
                            <option value="Wuling" {{ $old('merek_mobil') == 'Wuling' ? 'selected' : '' }}>Wuling</option>
                            <option value="Datsun" {{ $old('merek_mobil') == 'Datsun' ? 'selected' : '' }}>Datsun</option>
                            <option value="Kia" {{ $old('merek_mobil') == 'Kia' ? 'selected' : '' }}>Kia</option>
                            <option value="Mazda" {{ $old('merek_mobil') == 'Mazda' ? 'selected' : '' }}>Mazda</option>
                            <option value="Isuzu" {{ $old('merek_mobil') == 'Isuzu' ? 'selected' : '' }}>Isuzu</option>
                            <option value="Mercedes Benz" {{ $old('merek_mobil') == 'Mercedes Benz' ? 'selected' : '' }}>Mercedes Benz</option>
                            <option value="Nissan" {{ $old('merek_mobil') == 'Nissan' ? 'selected' : '' }}>Nissan</option>
                            <option value="Ford" {{ $old('merek_mobil') == 'Ford' ? 'selected' : '' }}>Ford</option>
                            {{-- Anda bisa menambahkan merek lain di sini jika diperlukan --}}
                        </select>
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="tipe_mobil" class="form-label mb-1 fw-semibold">Tipe Mobil</label>
                        <input type="text" id="tipe_mobil" name="tipe_mobil" class="form-control form-control-lg rounded-3" value="{{ $old('tipe_mobil') }}" placeholder="Masukkan tipe mobil (cth: Avanza, CR-V)" required>
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="tahun_pembuatan" class="form-label mb-1 fw-semibold">Tahun Pembuatan</label>
                        <input type="number" id="tahun_pembuatan" name="tahun_pembuatan" class="form-control form-control-lg rounded-3"
                                min="{{ \Carbon\Carbon::now()->subYears(5)->year }}" {{-- Minimal 5 tahun dari sekarang --}}
                                max="{{ \Carbon\Carbon::now()->year }}" {{-- Tidak boleh kedepan dari tahun sekarang --}}
                                value="{{ $old('tahun_pembuatan') }}"
                                placeholder="Tahun produksi"
                                required>
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="warna_mobil" class="form-label mb-1 fw-semibold">Warna Mobil</label>
                        <select id="warna_mobil" name="warna_mobil" class="form-select form-select-lg rounded-3" required>
                            <option value="" disabled {{ !isset($mobil) ? 'selected' : '' }}>Pilih Warna Mobil</option>
                            <option value="Putih" {{ $old('warna_mobil') == 'Putih' ? 'selected' : '' }}>Putih</option>
                            <option value="Hitam" {{ $old('warna_mobil') == 'Hitam' ? 'selected' : '' }}>Hitam</option>
                            <option value="Silver" {{ $old('warna_mobil') == 'Silver' ? 'selected' : '' }}>Silver</option>
                            <option value="Abu-abu" {{ $old('warna_mobil') == 'Abu-abu' ? 'selected' : '' }}>Abu-abu</option>
                            <option value="Merah" {{ $old('warna_mobil') == 'Merah' ? 'selected' : '' }}>Merah</option>
                            <option value="Biru" {{ $old('warna_mobil') == 'Biru' ? 'selected' : '' }}>Biru</option>
                            <option value="Cokelat" {{ $old('warna_mobil') == 'Cokelat' ? 'selected' : '' }}>Cokelat</option>
                            <option value="Kuning" {{ $old('warna_mobil') == 'Kuning' ? 'selected' : '' }}>Kuning</option>
                            <option value="Hijau" {{ $old('warna_mobil') == 'Hijau' ? 'selected' : '' }}>Hijau</option>
                            <option value="Oranye" {{ $old('warna_mobil') == 'Oranye' ? 'selected' : '' }}>Oranye</option>
                            <option value="Gold" {{ $old('warna_mobil') == 'Gold' ? 'selected' : '' }}>Gold</option>
                            <option value="Beige" {{ $old('warna_mobil') == 'Beige' ? 'selected' : '' }}>Beige</option>
                            <option value="Merah Marun" {{ $old('warna_mobil') == 'Merah Marun' ? 'selected' : '' }}>Merah Marun</option>
                            <option value="Lainnya" {{ $old('warna_mobil') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="harga_mobil" class="form-label mb-1 fw-semibold">Harga Mobil (IDR)</label>
                        <input type="number" name="harga_mobil" class="form-control form-control-lg rounded-3" id="harga_mobil" min="0"
                            value="{{ old('harga_mobil', isset($mobil) ? intval($mobil->harga_mobil) : '') }}"
                            placeholder="Contoh: 150000000" required>
                        @error('harga_mobil')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>


                    <div class="col-12 col-md-4">
                        <label for="bahan_bakar" class="form-label mb-1 fw-semibold">Bahan Bakar</label>
                        <select id="bahan_bakar" name="bahan_bakar" class="form-select form-select-lg rounded-3" required>
                            <option value="" disabled {{ !isset($mobil) ? 'selected' : '' }}>Pilih Jenis Bahan Bakar</option>
                            <option value="Bensin" {{ $old('bahan_bakar') == 'Bensin' ? 'selected' : '' }}>Bensin</option>
                            <option value="Diesel" {{ $old('bahan_bakar') == 'Diesel' ? 'selected' : '' }}>Diesel</option>
                            <option value="Listrik" {{ $old('bahan_bakar') == 'Listrik' ? 'selected' : '' }}>Listrik</option>
                            <option value="LPG" {{ $old('bahan_bakar') == 'LPG' ? 'selected' : '' }}>LPG</option>
                            <option value="CNG" {{ $old('bahan_bakar') == 'CNG' ? 'selected' : '' }}>CNG</option>
                        </select>
                    </div>

                    {{-- Pastikan ini berada dalam struktur kolom yang tepat, misalnya di bawah 'bahan_bakar' --}}
                    <div class="col-12 col-md-4">
                        <label for="transmisi" class="form-label mb-1 fw-semibold">Transmisi</label>
                        <select name="transmisi" class="form-select select2-custom rounded-3" id="transmisi" required>
                            <option value="" disabled {{ !isset($mobil) ? 'selected' : '' }}>Pilih Tipe Transmisi</option>
                            <option value="manual" {{ (isset($mobil) && $mobil->transmisi == 'manual') || old('transmisi') == 'manual' ? 'selected' : '' }}>Manual</option>
                            <option value="matic" {{ (isset($mobil) && $mobil->transmisi == 'matic') || old('transmisi') == 'matic' ? 'selected' : '' }}>Matic</option>
                        </select>
                        @error('transmisi')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Moved Nomor Polisi section to be next to Bahan Bakar --}}
                    <div class="col-12 col-md-4"> {{-- Adjusted column size to fit beside col-md-4 (4+4+4 = 12, or 4+8=12 on smaller screens) --}}
                        <label class="form-label mb-1 fw-semibold">Nomor Polisi</label>
                        <div class="row g-2"> {{-- Inner row for the three parts --}}
                            <div class="col-12 col-sm-4"> {{-- Adjusted to col-4 for better distribution on sm+ --}}
                                <select name="kode_wilayah" class="form-select select2-custom rounded-3" required>
                                    <option value="" disabled {{ !isset($mobil) ? 'selected' : '' }}>Pilih Kode Wilayah</option>
                                    <optgroup label="Sumatra">
                                        <option value="BA" {{ (isset($mobil) && $mobil->kode_wilayah == 'BA') || old('kode_wilayah') == 'BA' ? 'selected' : '' }}>BA - Sumatera Barat</option>
                                        <option value="BB" {{ (isset($mobil) && $mobil->kode_wilayah == 'BB') || old('kode_wilayah') == 'BB' ? 'selected' : '' }}>BB - Sumatera Utara (Barat)</option>
                                        <option value="BD" {{ (isset($mobil) && $mobil->kode_wilayah == 'BD') || old('kode_wilayah') == 'BD' ? 'selected' : '' }}>BD - Bengkulu</option>
                                        <option value="BE" {{ (isset($mobil) && $mobil->kode_wilayah == 'BE') || old('kode_wilayah') == 'BE' ? 'selected' : '' }}>BE - Lampung</option>
                                        <option value="BG" {{ (isset($mobil) && $mobil->kode_wilayah == 'BG') || old('kode_wilayah') == 'BG' ? 'selected' : '' }}>BG - Sumatera Selatan</option>
                                        <option value="BH" {{ (isset($mobil) && $mobil->kode_wilayah == 'BH') || old('kode_wilayah') == 'BH' ? 'selected' : '' }}>BH - Jambi</option>
                                        <option value="BK" {{ (isset($mobil) && $mobil->kode_wilayah == 'BK') || old('kode_wilayah') == 'BK' ? 'selected' : '' }}>BK - Sumatera Utara (Timur)</option>
                                        <option value="BL" {{ (isset($mobil) && $mobil->kode_wilayah == 'BL') || old('kode_wilayah') == 'BL' ? 'selected' : '' }}>BL - Aceh</option>
                                        <option value="BM" {{ (isset($mobil) && $mobil->kode_wilayah == 'BM') || old('kode_wilayah') == 'BM' ? 'selected' : '' }}>BM - Riau</option>
                                        <option value="BN" {{ (isset($mobil) && $mobil->kode_wilayah == 'BN') || old('kode_wilayah') == 'BN' ? 'selected' : '' }}>BN - Bangka Belitung</option>
                                        <option value="BP" {{ (isset($mobil) && $mobil->kode_wilayah == 'BP') || old('kode_wilayah') == 'BP' ? 'selected' : '' }}>BP - Kepulauan Riau</option>
                                    </optgroup>
                                    <optgroup label="Jawa">
                                        <option value="A" {{ (isset($mobil) && $mobil->kode_wilayah == 'A') || old('kode_wilayah') == 'A' ? 'selected' : '' }}>A - Banten</option>
                                        <option value="B" {{ (isset($mobil) && $mobil->kode_wilayah == 'B') || old('kode_wilayah') == 'B' ? 'selected' : '' }}>B - DKI Jakarta</option>
                                        <option value="D" {{ (isset($mobil) && $mobil->kode_wilayah == 'D') || old('kode_wilayah') == 'D' ? 'selected' : '' }}>D - Jawa Barat (Bandung & sekitarnya)</option>
                                        <option value="E" {{ (isset($mobil) && $mobil->kode_wilayah == 'E') || old('kode_wilayah') == 'E' ? 'selected' : '' }}>E - Jawa Barat (Cirebon & sekitarnya)</option>
                                        <option value="F" {{ (isset($mobil) && $mobil->kode_wilayah == 'F') || old('kode_wilayah') == 'F' ? 'selected' : '' }}>F - Jawa Barat (Bogor & sekitarnya)</option>
                                        <option value="G" {{ (isset($mobil) && $mobil->kode_wilayah == 'G') || old('kode_wilayah') == 'G' ? 'selected' : '' }}>G - Jawa Tengah (Pekalongan & sekitarnya)</option>
                                        <option value="H" {{ (isset($mobil) && $mobil->kode_wilayah == 'H') || old('kode_wilayah') == 'H' ? 'selected' : '' }}>H - Jawa Tengah (Semarang & sekitarnya)</option>
                                        <option value="K" {{ (isset($mobil) && $mobil->kode_wilayah == 'K') || old('kode_wilayah') == 'K' ? 'selected' : '' }}>K - Jawa Tengah (Pati & sekitarnya)</option>
                                        <option value="AD" {{ (isset($mobil) && $mobil->kode_wilayah == 'AD') || old('kode_wilayah') == 'AD' ? 'selected' : '' }}>AD - Jawa Tengah (Surakarta & sekitarnya)</option>
                                        <option value="R" {{ (isset($mobil) && $mobil->kode_wilayah == 'R') || old('kode_wilayah') == 'R' ? 'selected' : '' }}>R - Jawa Tengah (Banyumas & sekitarnya)</option>
                                        <option value="AA" {{ (isset($mobil) && $mobil->kode_wilayah == 'AA') || old('kode_wilayah') == 'AA' ? 'selected' : '' }}>AA - Jawa Tengah (Kedu & sekitarnya)</option>
                                        <option value="AB" {{ (isset($mobil) && $mobil->kode_wilayah == 'AB') || old('kode_wilayah') == 'AB' ? 'selected' : '' }}>AB - Yogyakarta</option>
                                        <option value="L" {{ (isset($mobil) && $mobil->kode_wilayah == 'L') || old('kode_wilayah') == 'L' ? 'selected' : '' }}>L - Jawa Timur (Surabaya)</option>
                                        <option value="N" {{ (isset($mobil) && $mobil->kode_wilayah == 'N') || old('kode_wilayah') == 'N' ? 'selected' : '' }}>N - Jawa Timur (Malang & sekitarnya)</option>
                                        <option value="P" {{ (isset($mobil) && $mobil->kode_wilayah == 'P') || old('kode_wilayah') == 'P' ? 'selected' : '' }}>P - Jawa Timur (Besuki & sekitarnya)</option>
                                        <option value="W" {{ (isset($mobil) && $mobil->kode_wilayah == 'W') || old('kode_wilayah') == 'W' ? 'selected' : '' }}>W - Jawa Timur (Sidoarjo & Gresik)</option>
                                        <option value="AE" {{ (isset($mobil) && $mobil->kode_wilayah == 'AE') || old('kode_wilayah') == 'AE' ? 'selected' : '' }}>AE - Jawa Timur (Madiun & sekitarnya)</option>
                                        <option value="AG" {{ (isset($mobil) && $mobil->kode_wilayah == 'AG') || old('kode_wilayah') == 'AG' ? 'selected' : '' }}>AG - Jawa Timur (Kediri & sekitarnya)</option>
                                    </optgroup>
                                    <optgroup label="Bali & Nusa Tenggara">
                                        <option value="DK" {{ (isset($mobil) && $mobil->kode_wilayah == 'DK') || old('kode_wilayah') == 'DK' ? 'selected' : '' }}>DK - Bali</option>
                                        <option value="DR" {{ (isset($mobil) && $mobil->kode_wilayah == 'DR') || old('kode_wilayah') == 'DR' ? 'selected' : '' }}>DR - Nusa Tenggara Barat (Lombok)</option>
                                        <option value="ED" {{ (isset($mobil) && $mobil->kode_wilayah == 'ED') || old('kode_wilayah') == 'ED' ? 'selected' : '' }}>ED - Nusa Tenggara Barat (Sumbawa)</option>
                                        <option value="EA" {{ (isset($mobil) && $mobil->kode_wilayah == 'EA') || old('kode_wilayah') == 'EA' ? 'selected' : '' }}>EA - Nusa Tenggara Barat (Pulau Sumbawa)</option>
                                        <option value="DH" {{ (isset($mobil) && $mobil->kode_wilayah == 'DH') || old('kode_wilayah') == 'DH' ? 'selected' : '' }}>DH - Nusa Tenggara Timur (Timor)</option>
                                        <option value="EB" {{ (isset($mobil) && $mobil->kode_wilayah == 'EB') || old('kode_wilayah') == 'EB' ? 'selected' : '' }}>EB - Nusa Tenggara Timur (Flores)</option>
                                        <option value="DC" {{ (isset($mobil) && $mobil->kode_wilayah == 'DC') || old('kode_wilayah') == 'DC' ? 'selected' : '' }}>DC - Sulawesi Barat</option>
                                    </optgroup>
                                    <optgroup label="Kalimantan">
                                        <option value="KB" {{ (isset($mobil) && $mobil->kode_wilayah == 'KB') || old('kode_wilayah') == 'KB' ? 'selected' : '' }}>KB - Kalimantan Barat</option>
                                        <option value="DA" {{ (isset($mobil) && $mobil->kode_wilayah == 'DA') || old('kode_wilayah') == 'DA' ? 'selected' : '' }}>DA - Kalimantan Selatan</option>
                                        <option value="KH" {{ (isset($mobil) && $mobil->kode_wilayah == 'KH') || old('kode_wilayah') == 'KH' ? 'selected' : '' }}>KH - Kalimantan Tengah</option>
                                        <option value="KT" {{ (isset($mobil) && $mobil->kode_wilayah == 'KT') || old('kode_wilayah') == 'KT' ? 'selected' : '' }}>KT - Kalimantan Timur</option>
                                        <option value="KU" {{ (isset($mobil) && $mobil->kode_wilayah == 'KU') || old('kode_wilayah') == 'KU' ? 'selected' : '' }}>KU - Kalimantan Utara</option>
                                    </optgroup>
                                    <optgroup label="Sulawesi">
                                        <option value="DD" {{ (isset($mobil) && $mobil->kode_wilayah == 'DD') || old('kode_wilayah') == 'DD' ? 'selected' : '' }}>DD - Sulawesi Selatan</option>
                                        <option value="DN" {{ (isset($mobil) && $mobil->kode_wilayah == 'DN') || old('kode_wilayah') == 'DN' ? 'selected' : '' }}>DN - Sulawesi Tengah</option>
                                        <option value="DT" {{ (isset($mobil) && $mobil->kode_wilayah == 'DT') || old('kode_wilayah') == 'DT' ? 'selected' : '' }}>DT - Sulawesi Tenggara</option>
                                        <option value="DL" {{ (isset($mobil) && $mobil->kode_wilayah == 'DL') || old('kode_wilayah') == 'DL' ? 'selected' : '' }}>DL - Sulawesi Utara (Minahasa & sekitarnya)</option>
                                        <option value="DM" {{ (isset($mobil) && $mobil->kode_wilayah == 'DM') || old('kode_wilayah') == 'DM' ? 'selected' : '' }}>DM - Gorontalo</option>
                                    </optgroup>
                                    <optgroup label="Maluku & Papua">
                                        <option value="DE" {{ (isset($mobil) && $mobil->kode_wilayah == 'DE') || old('kode_wilayah') == 'DE' ? 'selected' : '' }}>DE - Maluku</option>
                                        <option value="DG" {{ (isset($mobil) && $mobil->kode_wilayah == 'DG') || old('kode_wilayah') == 'DG' ? 'selected' : '' }}>DG - Maluku Utara</option>
                                        <option value="PA" {{ (isset($mobil) && $mobil->kode_wilayah == 'PA') || old('kode_wilayah') == 'PA' ? 'selected' : '' }}>PA - Papua</option>
                                        <option value="PB" {{ (isset($mobil) && $mobil->kode_wilayah == 'PB') || old('kode_wilayah') == 'PB' ? 'selected' : '' }}>PB - Papua Barat</option>
                                    </optgroup>
                                </select>
                            </div>

                            {{-- Hidden input to store the full combined license plate number --}}
                            <input type="hidden" name="nomor_polisi" value="{{ (isset($mobil) ? $mobil->nomor_polisi : old('nomor_polisi')) }}">

                            <div class="col-12 col-sm-4"> {{-- Display for the combined license plate number --}}
                                <span id="display_nomor_polisi" class="form-control form-control-lg rounded-3" style="background-color: #e9ecef; display: flex; align-items: center; justify-content: flex-start;">
                                    {{ (isset($mobil) ? $mobil->nomor_polisi : old('nomor_polisi')) }}
                                </span>
                            </div>

                            <div class="col-12 col-sm-4"> {{-- Suffix part (user editable) --}}
                                <input type="text" name="nomor_polisi_selanjutnya" class="form-control form-control-lg rounded-3" value="{{ (isset($mobil) ? $mobil->nomor_polisi_selanjutnya : old('nomor_polisi_selanjutnya')) }}" placeholder="Nomor + Huruf Akhir" required>
                            </div>
                        </div>
                        @error('nomor_polisi')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-4"> {{-- Adjusted to col-12 on small screens, col-md-4 on medium and up --}}
                        <label for="nomor_rangka" class="form-label mb-1 fw-semibold">Nomor Rangka</label>
                        <input type="text" id="nomor_rangka" name="nomor_rangka" class="form-control form-control-lg rounded-3" value="{{ $old('nomor_rangka') }}" placeholder="Masukkan nomor rangka" required>
                    </div>

                    <div class="col-12 col-md-4"> {{-- Adjusted to col-12 on small screens, col-md-4 on medium and up --}}
                        <label for="nomor_mesin" class="form-label mb-1 fw-semibold">Nomor Mesin</label>
                        <input type="text" id="nomor_mesin" name="nomor_mesin" class="form-control form-control-lg rounded-3" value="{{ $old('nomor_mesin') }}" placeholder="Masukkan nomor mesin" required>
                    </div>

                    <div class="col-12 col-md-4"> {{-- Adjusted to col-12 on small screens, col-md-4 on medium and up --}}
                        <label for="nomor_bpkb" class="form-label mb-1 fw-semibold">Nomor BPKB</label>
                        <input type="text" id="nomor_bpkb" name="nomor_bpkb" class="form-control form-control-lg rounded-3" value="{{ $old('nomor_bpkb') }}" placeholder="Masukkan nomor BPKB" required>
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="tanggal_masuk" class="form-label mb-1 fw-semibold">Tanggal Masuk</label>
                        <input type="date" id="tanggal_masuk" name="tanggal_masuk" class="form-control form-control-lg rounded-3"
                               value="{{ \Carbon\Carbon::parse($old('tanggal_masuk'))->format('Y-m-d') }}"
                               min="{{ \Carbon\Carbon::today()->subMonths(6)->toDateString() }}" {{-- Allow last 6 months --}}
                               max="{{ \Carbon\Carbon::today()->toDateString() }}"
                               required>
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="masa_berlaku_pajak" class="form-label mb-1 fw-semibold">Masa Berlaku Pajak</label>
                        <input type="date" id="masa_berlaku_pajak" name="masa_berlaku_pajak" class="form-control form-control-lg rounded-3"
                               value="{{ \Carbon\Carbon::parse($old('masa_berlaku_pajak'))->format('Y-m-d') }}"
                               min="{{ \Carbon\Carbon::today()->toDateString() }}" {{-- Must be today or future --}}
                               required>
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="status_mobil" class="form-label mb-1 fw-semibold">Status Mobil</label>
                        <select id="status_mobil" name="status_mobil" class="form-select form-select-lg rounded-3" required>
                            <option value="bekas" {{ $old('status_mobil') == 'bekas' ? 'selected' : '' }}>Bekas</option>
                            <option value="baru" {{ $old('status_mobil') == 'baru' ? 'selected' : '' }}>Baru</option>
                        </select>
                    </div>

                    {{-- Changed ketersediaan back to a dropdown --}}
                    <div class="col-12 col-md-4">
                        <label for="ketersediaan" class="form-label mb-1 fw-semibold">Ketersediaan</label>
                        <select id="ketersediaan" name="ketersediaan" class="form-select form-select-lg rounded-3">
                            <option value="ada" {{ $old('ketersediaan') == 'ada' ? 'selected' : '' }}>Ada</option>
                            <option value="tidak" {{ $old('ketersediaan') == 'tidak' ? 'selected' : '' }}>Tidak ada</option>
                            <option value="servis" {{ $old('ketersediaan') == 'servis' ? 'selected' : '' }}>Sedang Servis</option>
                            <option value="terjual" {{ $old('ketersediaan') == 'terjual' ? 'selected' : '' }}>Terjual</option>
                        </select>
                    </div>
                </div>

                <div class="mt-5 d-flex justify-content-end"> {{-- Mengubah menjadi justify-content-end dan menghapus gap-3 --}}
                    <button type="submit" class="btn btn-primary btn-lg px-5 rounded-pill shadow-sm animate__animated animate__pulse animate__infinite">
                        <i class="bi bi-save me-2"></i> Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f0f2f5; /* Light grey background */
        color: #333;
    }

    .container-fluid.py-4 {
        padding-top: 2.5rem !important;
        padding-bottom: 2.5rem !important;
    }

    /* Card Styling */
    .card {
        border-radius: 1.25rem !important; /* More rounded */
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.08) !important; /* Softer, larger shadow */
        background-color: #ffffff;
    }

    .card-body {
        padding: 2.5rem !important; /* More internal space */
    }

    /* Headings and Text */
    h4.fw-bold {
        color: #212529; /* Darker heading */
        font-weight: 700 !important;
    }

    small.text-secondary {
        color: #6c757d !important;
        font-size: 0.9rem;
    }

    /* Form Labels */
    .form-label.fw-semibold {
        color: #495057;
        font-size: 0.95rem;
        margin-bottom: 0.3rem;
    }

    /* Form Controls */
    .form-control-lg, .form-select-lg {
        height: calc(2.8rem + 2px); /* Standard large input height */
        padding: 0.75rem 1.25rem;
        border-radius: 0.75rem; /* Rounded-3 for form controls */
        border-color: #ced4da;
        font-size: 1rem;
        transition: all 0.2s ease-in-out;
    }

    .form-control-lg:focus, .form-select-lg:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        outline: 0;
    }

    /* Placeholder Styling */
    .form-control::placeholder {
        color: #adb5bd;
        opacity: 1; /* Firefox fix */
    }

    /* Alert Styling */
    .alert-danger {
        background-color: #fef2f2; /* Lighter red background */
        color: #8b0000; /* Darker red text */
        border: 1px solid #dc3545; /* Red border */
        padding: 1rem 1.5rem;
    }
    .alert-danger .alert-heading {
        color: #dc3545;
    }
    .alert-danger ul {
        list-style: none;
        padding-left: 0;
    }
    .alert-danger li {
        margin-bottom: 0.25rem;
    }
    .alert-danger li:last-child {
        margin-bottom: 0;
    }

    /* Buttons */
    .btn-primary {
        background: linear-gradient(45deg, #0d6efd, #0b5ed7);
        border: none;
        font-weight: 600;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 10px rgba(13, 110, 253, 0.2);
        transition: all 0.3s ease;
    }
    .btn-primary:hover {
        background: linear-gradient(45deg, #0b5ed7, #0d6efd);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(13, 110, 253, 0.3);
    }
    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
        color: white;
        font-weight: 600;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #545b62;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }
    .btn-outline-secondary {
        border-color: #6c757d;
        color: #6c757d;
        /* font-weight: 600; */
        transition: all 0.3s ease;
    }
    .btn-outline-secondary:hover {
        background-color: #6c757d;
        color: white;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    /* Responsive adjustments for select inputs within Nomor Polisi */
    @media (max-width: 575.98px) { /* Extra small devices */
        .col-sm-3, .col-sm-5, .col-sm-4 {
            width: 100%;
            margin-bottom: 0.5rem; /* Add spacing between stacked inputs */
        }
        .col-sm-4.mt-2.mt-sm-0 { /* Adjust margin for the last part if stacked */
            margin-top: 0.5rem !important;
        }
        .row.g-2 > div:last-child {
            margin-bottom: 0;
        }
    }

    /* Select2 Custom Styles */
    .select2-container--default .select2-selection--single {
        height: 48px !important; /* Ensure height matches other form-control-lg inputs */
        border: 1px solid #ced4da !important; /* Standard Bootstrap border color */
        border-radius: 0.75rem !important; /* Matches rounded-3 class */
        background-color: #fff !important; /* Explicitly set white background for clear contrast */
        display: flex !important; /* Use flexbox for better vertical alignment of content */
        align-items: center !important; /* Vertically center the text and icons */
        padding: 0 1.25rem !important; /* Match form-control-lg padding */
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        flex-grow: 1; /* Allow the text area to expand and take available space */
        line-height: normal !important; /* Reset line-height, as flexbox handles vertical alignment */
        padding: 0 !important; /* Remove individual padding, parent flex handles it */
        margin: 0 !important; /* Ensure no extra margin */
        color: #212529 !important; /* Ensure text color is dark for readability (Bootstrap default) */
        font-size: 1rem !important; /* Match the font size of other form controls */
        display: flex; /* Enable flex for inner content */
        align-items: center; /* Center placeholder text */
    }

    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #adb5bd !important; /* Style for placeholder */
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: auto !important;
        position: absolute !important;
        right: 1.25rem !important; /* Position to match padding */
        top: 50% !important;
        transform: translateY(-50%) !important;
        width: 20px !important;
        color: #6c757d; /* Make arrow color subtle */
    }

    .select2-container--default .select2-selection--single .select2-selection__clear {
        float: none !important;
        line-height: normal !important;
        padding-right: 0.5rem;
        font-size: 1.25rem !important;
        color: #6c757d !important;
        order: 1;
    }

    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #0d6efd !important;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25) !important;
    }

    .select2-container--default .select2-results__option {
        padding: 0.75rem 1.25rem; /* Consistent padding for dropdown options */
        font-size: 1rem;
    }

    .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
        background-color: #0d6efd !important;
        color: #fff !important;
    }

    .select2-dropdown {
        border-radius: 0.75rem !important; /* Match input border-radius */
        border: 1px solid #ced4da !important;
        overflow: hidden; /* Ensure rounded corners are visible */
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15)!important; /* Add shadow to dropdown */
    }

    .select2-search input {
        border-radius: 0.5rem !important;
        border-color: #ced4da !important;
        padding: 0.5rem 1rem !important;
        font-size: 1rem !important;
    }
</style>

<script>
    $(document).ready(function() {
        // Initialize Select2 for all relevant dropdowns
        $('select[name="kode_wilayah"]').select2({
            width: '100%',
            placeholder: "Pilih Kode Wilayah",
            allowClear: true,
        });

        $('select[name="merek_mobil"]').select2({
            width: '100%',
            placeholder: "Pilih Merek Mobil",
            allowClear: true,
        });

        $('select[name="jenis_mobil"]').select2({
            width: '100%',
            placeholder: "Pilih Jenis Mobil",
            allowClear: true,
        });

        // Get references to the input fields and select element for license plate logic
        const nomorPolisiHiddenInput = $('input[name="nomor_polisi"]'); // The hidden input for license plate
        const displayNomorPolisi = $('#display_nomor_polisi'); // The span to display license plate
        const nomorPolisiSelanjutnyaInput = $('input[name="nomor_polisi_selanjutnya"]'); // The suffix input for license plate
        const kodeWilayahSelect = $('select[name="kode_wilayah"]'); // The region code select

        // Function to update the combined license plate number
        function updateNomorPolisi() {
            const kodeWilayah = kodeWilayahSelect.val() || ''; // Get selected region code, default to empty string if null
            const nomorSelanjutnya = nomorPolisiSelanjutnyaInput.val() || ''; // Get suffix, default to empty string if null

            let combinedValue = '';
            if (kodeWilayah && nomorSelanjutnya) {
                combinedValue = kodeWilayah + ' ' + nomorSelanjutnya;
            } else if (kodeWilayah) {
                combinedValue = kodeWilayah + ' ';
            } else if (nomorSelanjutnya) {
                combinedValue = ' ' + nomorSelanjutnya;
            }

            // Log values for debugging
            console.log('--- updateNomorPolisi called ---');
            console.log('Kode Wilayah:', kodeWilayah);
            console.log('Nomor Selanjutnya:', nomorSelanjutnya);
            console.log('Combined Value (sent to hidden input):', combinedValue);
            console.log('Current Hidden Input Value (before update):', nomorPolisiHiddenInput.val());
            console.log('Current Display Span Text (before update):', displayNomorPolisi.text());


            // Update the hidden input and the display span
            nomorPolisiHiddenInput.val(combinedValue);
            displayNomorPolisi.text(combinedValue);

            console.log('New Hidden Input Value (after update):', nomorPolisiHiddenInput.val());
            console.log('New Display Span Text (after update):', displayNomorPolisi.text());
        }

        // Add event listeners
        kodeWilayahSelect.on('change', updateNomorPolisi);
        nomorPolisiSelanjutnyaInput.on('input', updateNomorPolisi);

        // Initial call to set the display on page load (for create/edit mode)
        // This will ensure the combined display is correct on load, especially after validation errors.
        updateNomorPolisi();

        // Optional: Trigger change on Select2 if initial value is set, ensuring Select2's internal state
        // and external display are synchronized from the start.
        if (kodeWilayahSelect.val()) {
            kodeWilayahSelect.trigger('change');
        }
    });
</script>
@endsection
