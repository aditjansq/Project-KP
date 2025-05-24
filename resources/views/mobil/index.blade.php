@extends('layouts.app')

@section('title', 'Data Mobil')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h4 class="text-dark fw-bold mb-0">Data Mobil</h4>
            <small class="text-muted">Daftar mobil lengkap yang tersedia di sistem.</small>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <a href="{{ route('mobil.create') }}" class="btn btn-primary shadow-sm">
                <i class="bi bi-plus-circle me-2"></i>Tambah Mobil
            </a>
        </div>
    </div>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-bordered table-striped table-nowrap align-middle mb-0">
                    <thead class="table-light text-center align-middle">
                        <tr>
                            <th>Kode</th>
                            <th>Tipe</th>
                            <th>Merek</th>
                            <th>Tahun</th>
                            <th>Warna</th>
                            <th>Harga</th>
                            <th>Bahan Bakar</th>
                            <th>Nomor Polisi</th>
                            <th>No. Rangka</th>
                            <th>No. Mesin</th>
                            <th>No. BPKB</th>
                            <th>Tanggal Masuk</th>
                            <th>Status</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($mobils as $mobil)
                        <tr>
                            <td>{{ $mobil->kode_mobil }}</td>
                            <td>{{ $mobil->tipe_mobil }}</td>
                            <td>{{ $mobil->merek_mobil }}</td>
                            <td>{{ $mobil->tahun_pembuatan }}</td>
                            <td>{{ $mobil->warna_mobil }}</td>
                            <td>Rp {{ number_format($mobil->harga_mobil, 0, ',', '.') }}</td>
                            <td>{{ ucfirst($mobil->bahan_bakar) }}</td>
                            <td>{{ $mobil->nomor_polisi }}</td>
                            <td>{{ $mobil->nomor_rangka }}</td>
                            <td>{{ $mobil->nomor_mesin }}</td>
                            <td>{{ $mobil->nomor_bpkb }}</td>
                            <td>{{ \Carbon\Carbon::parse($mobil->tanggal_masuk)->format('d M Y') }}</td>
                            <td>
                                <span class="badge bg-{{ $mobil->status_mobil === 'tersedia' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($mobil->status_mobil) }}
                                </span>
                            </td>
                            <td>{{ $mobil->stok }}</td>
                            <td class="text-center">
                                <a href="{{ route('mobil.edit', $mobil->id) }}" class="btn btn-sm btn-outline-warning me-1" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('mobil.destroy', $mobil->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
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
                            <td colspan="15" class="text-center text-muted py-4">
                                <i class="bi bi-info-circle me-1"></i> Belum ada data mobil tersedia.
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
