@extends('layouts.app') {{-- Pastikan ini sesuai dengan nama file layout utama Anda --}}

@section('title', 'Laporan Mobil Terjual') {{-- Judul halaman untuk tab browser --}}

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="h3 mb-4 text-gray-800">Laporan Mobil Terjual</h1>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Data Mobil Terjual</h6>
                </div>
                <div class="card-body">
                    {{--
                        Di sini Anda akan menampilkan data mobil terjual.
                        Data ini biasanya dikirim dari LaporanController::mobilTerjual().
                        Contoh penggunaan variabel $mobilTerjual yang dikirim dari controller:
                    --}}

                    @if (isset($mobilTerjual) && $mobilTerjual->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No.</th>
                                        <th>No. Transaksi</th>
                                        <th>Nama Mobil</th>
                                        <th>Pembeli</th>
                                        <th>Tanggal Jual</th>
                                        <th>Harga Jual</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($mobilTerjual as $index => $transaksi)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $transaksi->nomor_transaksi ?? 'N/A' }}</td>
                                        <td>{{ $transaksi->mobil->nama_mobil ?? 'Mobil Tidak Ditemukan' }}</td>
                                        <td>{{ $transaksi->pembeli->nama ?? 'Pembeli Tidak Ditemukan' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->translatedFormat('d F Y') }}</td>
                                        <td>Rp {{ number_format($transaksi->harga_jual, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="5" class="text-end">Total Penjualan:</th>
                                        <th>Rp {{ number_format($mobilTerjual->sum('harga_jual'), 0, ',', '.') }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info" role="alert">
                            Tidak ada data mobil terjual yang tersedia saat ini.
                        </div>
                    @endif

                    {{--
                        Anda bisa menambahkan form filter berdasarkan tanggal atau kriteria lain di sini.
                        Contoh:
                        <form action="{{ route('laporan.mobil_terjual') }}" method="GET" class="mb-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="start_date">Dari Tanggal:</label>
                                    <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="end_date">Sampai Tanggal:</label>
                                    <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                </div>
                            </div>
                        </form>
                    --}}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    {{-- Anda bisa menambahkan script JS spesifik untuk halaman ini di sini,
         misalnya untuk inisialisasi DataTables jika Anda menggunakannya.
         Contoh:
         <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
         <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap5.min.js"></script>
         <script>
             $(document).ready(function() {
                 $('#dataTable').DataTable();
             });
         </script>
    --}}
@endpush
