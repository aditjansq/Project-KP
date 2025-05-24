@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Terjadi kesalahan:</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ isset($mobil) ? route('mobil.update', $mobil->id) : route('mobil.store') }}">
    @csrf
    @if(isset($mobil)) @method('PUT') @endif

    <div class="row">
        @php
            $old = fn($name) => old($name, $mobil->$name ?? '');
        @endphp

        <div class="col-md-6 mb-3">
            <label>Tipe Mobil</label>
            <input type="text" name="tipe_mobil" class="form-control" value="{{ $old('tipe_mobil') }}" required>
        </div>
        <div class="col-md-6 mb-3">
            <label>Merek Mobil</label>
            <input type="text" name="merek_mobil" class="form-control" value="{{ $old('merek_mobil') }}" required>
        </div>
        <div class="col-md-4 mb-3">
            <label>Tahun Pembuatan</label>
            <input type="number" name="tahun_pembuatan" class="form-control" value="{{ $old('tahun_pembuatan') }}" required min="1901" max="2155">

        </div>
        <div class="col-md-4 mb-3">
            <label>Warna Mobil</label>
            <input type="text" name="warna_mobil" class="form-control" value="{{ $old('warna_mobil') }}" required>
        </div>
        <div class="col-md-4 mb-3">
            <label>Harga Mobil</label>
            <input type="number" name="harga_mobil" class="form-control" value="{{ $old('harga_mobil') }}" required>
        </div>
        <div class="col-md-4 mb-3">
            <label>Bahan Bakar</label>
            <input type="text" name="bahan_bakar" class="form-control" value="{{ $old('bahan_bakar') }}" required>
        </div>
        <div class="col-md-4 mb-3">
            <label>Nomor Polisi</label>
            <input type="text" name="nomor_polisi" class="form-control" value="{{ $old('nomor_polisi') }}" required>
        </div>
        <div class="col-md-4 mb-3">
            <label>Nomor Rangka</label>
            <input type="text" name="nomor_rangka" class="form-control" value="{{ $old('nomor_rangka') }}" required>
        </div>
        <div class="col-md-4 mb-3">
            <label>Nomor Mesin</label>
            <input type="text" name="nomor_mesin" class="form-control" value="{{ $old('nomor_mesin') }}" required>
        </div>
        <div class="col-md-4 mb-3">
            <label>Nomor BPKB</label>
            <input type="text" name="nomor_bpkb" class="form-control" value="{{ $old('nomor_bpkb') }}" required>
        </div>
        <div class="col-md-4 mb-3">
            <label>Tanggal Masuk</label>
            <input type="date" name="tanggal_masuk" class="form-control" value="{{ $old('tanggal_masuk') }}" required>
        </div>
        <div class="col-md-3 mb-3">
            <label>Status Mobil</label>
            <select name="status_mobil" class="form-control" required>
                <option value="baru" {{ $old('status_mobil') == 'baru' ? 'selected' : '' }}>Baru</option>
                <option value="bekas" {{ $old('status_mobil') == 'bekas' ? 'selected' : '' }}>Bekas</option>
            </select>
        </div>
        <div class="col-md-3 mb-3">
            <label>Stok</label>
            <select name="stok" class="form-control" required>
                <option value="ada" {{ $old('stok') == 'ada' ? 'selected' : '' }}>Ada</option>
                <option value="tidak" {{ $old('stok') == 'tidak' ? 'selected' : '' }}>Tidak</option>
            </select>
        </div>
    </div>

    <button class="btn btn-success">Simpan</button>
    <a href="{{ route('mobil.index') }}" class="btn btn-secondary">Kembali</a>
</form>
