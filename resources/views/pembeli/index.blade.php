@extends('layouts.app')

@section('title', 'Data Pembeli')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h4 class="text-dark fw-bold mb-0">Data Pembeli</h4>
            <small class="text-muted">Daftar pembeli yang terdaftar di sistem.</small>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <a href="{{ route('pembeli.create') }}" class="btn btn-primary shadow-sm">
                <i class="bi bi-plus-circle me-2"></i>Tambah Pembeli
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body px-0">
            <div class="table-responsive">
                <table class="table table-hover table-bordered table-striped align-middle mb-0" style="min-width: 1200px;">
                    <thead class="table-light text-center align-middle">
                        <tr>
                            <th style="width: 100px;">Kode</th>
                            <th style="min-width: 180px;">Nama</th>
                            <th style="min-width: 130px;">Tanggal Lahir</th>
                            <th style="min-width: 160px;">Pekerjaan</th>
                            <th style="min-width: 250px;">Alamat</th>
                            <th style="min-width: 140px;">No. Telepon</th>
                            <th style="min-width: 100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pembelis as $pembeli)
                        <tr>
                            <td class="text-center">{{ $pembeli->kode_pembeli }}</td>
                            <td class="text-break">{{ $pembeli->nama }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($pembeli->tanggal_lahir)->format('d M Y') }}</td>
                            <td class="text-break">{{ $pembeli->pekerjaan }}</td>
                            <td class="text-break">{{ $pembeli->alamat }}</td>
                            <td class="text-break">{{ $pembeli->no_telepon }}</td>
                            <td class="text-center">
                                <a href="{{ route('pembeli.edit', $pembeli) }}" class="btn btn-sm btn-outline-warning me-1" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('pembeli.destroy', $pembeli) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Hapus">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-info-circle me-1"></i> Belum ada data pembeli tersedia.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
