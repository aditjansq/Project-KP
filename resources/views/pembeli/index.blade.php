{{-- resources/views/pembeli/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Data Pembeli')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Data Pembeli</h3>
        <a href="{{ route('pembeli.create') }}" class="btn btn-primary">+ Tambah Pembeli</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama</th>
                <th>No. Telepon</th>
                <th>Pekerjaan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pembelis as $pembeli)
                <tr>
                    <td>{{ $pembeli->kode_pembeli }}</td>
                    <td>{{ $pembeli->nama }}</td>
                    <td>{{ $pembeli->no_telepon }}</td>
                    <td>{{ $pembeli->pekerjaan }}</td>
                    <td>
                        <a href="{{ route('pembeli.edit', $pembeli) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('pembeli.destroy', $pembeli) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection