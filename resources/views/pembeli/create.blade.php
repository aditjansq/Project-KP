{{-- resources/views/pembeli/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Tambah Pembeli')

@section('content')
    <h3>Tambah Pembeli</h3>
    <form method="POST" action="{{ route('pembeli.store') }}">
        @csrf
        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Tanggal Lahir</label>
            <input type="date" name="tanggal_lahir" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Pekerjaan</label>
            <input type="text" name="pekerjaan" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Alamat</label>
            <textarea name="alamat" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
            <label>No Telepon</label>
            <input type="text" name="no_telepon" class="form-control" required>
        </div>
        <button class="btn btn-primary">Simpan</button>
    </form>
@endsection