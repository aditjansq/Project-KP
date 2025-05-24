{{-- resources/views/pembeli/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Pembeli')

@section('content')
    <h3>Edit Pembeli</h3>
    <form method="POST" action="{{ route('pembeli.update', $pembeli->id) }}">
        @csrf @method('PUT')
        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control" value="{{ $pembeli->nama }}" required>
        </div>
        <div class="mb-3">
            <label>Tanggal Lahir</label>
            <input type="date" name="tanggal_lahir" class="form-control" value="{{ $pembeli->tanggal_lahir }}" required>
        </div>
        <div class="mb-3">
            <label>Pekerjaan</label>
            <input type="text" name="pekerjaan" class="form-control" value="{{ $pembeli->pekerjaan }}" required>
        </div>
        <div class="mb-3">
            <label>Alamat</label>
            <textarea name="alamat" class="form-control" required>{{ $pembeli->alamat }}</textarea>
        </div>
        <div class="mb-3">
            <label>No Telepon</label>
            <input type="text" name="no_telepon" class="form-control" value="{{ $pembeli->no_telepon }}" required>
        </div>
        <button class="btn btn-primary">Update</button>
    </form>
@endsection
