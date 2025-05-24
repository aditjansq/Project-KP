@extends('layouts.app')

@section('title', 'Data Mobil')

@section('content')
<h3>Data Mobil</h3>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<a href="{{ route('mobil.create') }}" class="btn btn-primary mb-3">+ Tambah Mobil</a>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Kode</th>
            <th>Tipe</th>
            <th>Merek</th>
            <th>Harga</th>
            <th>Status</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($mobils as $mobil)
        <tr>
            <td>{{ $mobil->kode_mobil }}</td>
            <td>{{ $mobil->tipe_mobil }}</td>
            <td>{{ $mobil->merek_mobil }}</td>
            <td>Rp {{ number_format($mobil->harga_mobil, 0, ',', '.') }}</td>
            <td>{{ ucfirst($mobil->status_mobil) }}</td>
            <td>{{ ucfirst($mobil->stok) }}</td>
            <td>
                <a href="{{ route('mobil.edit', $mobil->id) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('mobil.destroy', $mobil->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
