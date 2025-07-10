@extends('layouts.app')

@section('title', 'Daftar Transaksi Pembelian Mobil')

@section('content')
<div class="container-fluid py-4 px-3 px-md-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6 mb-3 mb-md-0">
            <h4 class="text-dark fw-bold mb-0">Daftar Pembelian Mobil</h4>
            <small class="text-secondary">Detail transaksi Pembelian Mobil.</small>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('transaksi.penjual.create') }}" class="btn btn-primary btn-lg shadow-lg rounded-pill animate__animated animate__fadeInRight">
                <i class="bi bi-plus-circle me-2"></i> Tambah Transaksi Penjual Baru
            </a>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary rounded-pill animate__animated animate__fadeInRight ms-2">
                <i class="bi bi-arrow-left me-2"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-3 animate__animated animate__fadeInDown" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
    @endif

    <div class="card border-0 shadow-xl rounded-4 animate__animated animate__fadeInUp">
        <div class="card-header bg-white p-4 border-bottom-0 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <div class="header-left mb-3 mb-md-0">
                <h5 class="card-title mb-0 text-dark fw-bold">Detail Transaksi Pembelian Mobil</h5>
                <p class="card-text text-muted">Lihat Detail Transaksi, Angsuran, Edit Pembayaran.</p>
            </div>
            <div class="header-right d-flex flex-column flex-md-row align-items-md-center gap-3">
                <input type="text" id="searchInput" class="form-control rounded-pill shadow-sm" placeholder="Cari transaksi..." aria-label="Cari transaksi">

                @php
                    $today = date('Y-m-d');
                    $oneMonthAgo = date('Y-m-d', strtotime('-1 month'));
                @endphp
                <div class="d-flex flex-column flex-md-row gap-2">
                    <input type="date" id="startDateFilter" class="form-control rounded-pill shadow-sm" title="Dari Tanggal Transaksi" max="{{ $today }}" min="{{ $oneMonthAgo }}">
                    <input type="date" id="endDateFilter" class="form-control rounded-pill shadow-sm" title="Sampai Tanggal Transaksi" max="{{ $today }}" min="{{ $oneMonthAgo }}">
                </div>

                <select id="metodePembayaranFilter" class="form-select rounded-pill shadow-sm">
                    <option value="">Semua Metode Pembayaran</option>
                    <option value="Transfer Bank">Transfer Bank</option>
                    <option value="Cash">Cash</option>
                    <option value="Kredit">Kredit</option>
                </select>

                <select id="statusFilter" class="form-select rounded-pill shadow-sm">
                    <option value="">Semua Status</option>
                    <option value="Lunas">Lunas</option>
                    <option value="Belum Lunas">Belum Lunas</option>
                    <option value="Menunggu Pembayaran">Menunggu Pembayaran</option>
                    <option value="Dibatalkan">Dibatalkan</option>
                </select>

                <button id="resetFilters" class="btn btn-outline-secondary rounded-pill shadow-sm">Reset</button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive" id="tableResponsiveContainer">
                <table class="table table-hover table-striped align-middle mb-0 custom-table" id="transaksiPenjualTable">
                    <thead class="bg-gradient-primary text-white text-center align-middle">
                        <tr>
                            <th scope="col" style="width: 160px;">Kode Transaksi</th>
                            <th scope="col" style="width: 120px;">Tanggal</th>
                            <th scope="col" style="width: 120px;">Jam</th>
                            <th scope="col" style="min-width: 280px;">Informasi Mobil</th>
                            <th scope="col" style="min-width: 180px;">Nama Penjual</th>
                            <th scope="col" style="width: 150px;">Metode Pembayaran</th>
                            <th scope="col" style="width: 150px;">Harga Beli</th>
                            <th scope="col" style="width: 130px;">Status</th>
                            <th scope="col" style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksiPenjual as $transaksi)
                        <tr class="data-row"
                            data-kode="{{ strtolower($transaksi->kode_transaksi) }}"
                            data-tanggal="{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('Y-m-d') }}"
                            data-jam="{{ \Carbon\Carbon::parse($transaksi->created_at)->format('H:i') }}"
                            data-metode="{{ strtolower($transaksi->metode_pembayaran ?? '') }}"
                            data-status="{{ strtolower($transaksi->status_pembayaran ?? '') }}">
                            <td class="text-center fw-bold">{{ $transaksi->kode_transaksi }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d M Y') }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($transaksi->created_at)->format('H:i') }} WIB</td>
                            <td>
                                @php
                                    $mobilDisplay = '';
                                    if ($transaksi->mobil) {
                                        if ($transaksi->mobil->tahun_pembuatan) {
                                            $mobilDisplay .= $transaksi->mobil->tahun_pembuatan . ' ';
                                        }
                                        $mobilDisplay .= $transaksi->mobil->merek_mobil ?? 'N/A';
                                        if ($transaksi->mobil->tipe_mobil) {
                                            $mobilDisplay .= ' ' . $transaksi->mobil->tipe_mobil;
                                        }
                                        $mobilDisplay .= ' - ' . ($transaksi->mobil->nomor_polisi ?? 'N/A');
                                    } else {
                                        $mobilDisplay = 'Tidak Ada Mobil';
                                    }
                                @endphp
                                {{ $mobilDisplay }}
                            </td>
                            <td>{{ $transaksi->penjual->nama ?? 'N/A' }}</td>
                            <td>
                                {{ $transaksi->metode_pembayaran ?? 'N/A' }}
                                @if ($transaksi->metode_pembayaran == 'Kredit' && $transaksi->tempo_angsuran)
                                    <br>
                                    <span class="installment-trigger text-primary text-decoration-underline"
                                          style="cursor: pointer;"
                                          data-bs-toggle="modal"
                                          data-bs-target="#installmentModal"
                                          data-total-harga="{{ $transaksi->total_harga }}"
                                          data-tempo-angsuran="{{ $transaksi->tempo_angsuran }}"
                                          data-kode-transaksi="{{ $transaksi->kode_transaksi }}"
                                          data-biaya-akuisisi="{{ $transaksi->biaya_akuisisi ?? 0 }}"
                                          data-dp-amount="{{ $transaksi->dp_jumlah ?? 0 }}">
                                        {{ $transaksi->tempo_angsuran }} Tahun
                                    </span>
                                @endif
                            </td>
                            <td class="text-end">
                                Rp{{ number_format($transaksi->total_harga, 0, ',', '.') }}
                            </td>
                            <td class="text-center">
                                @php
                                    $badgeClass = '';
                                    $statusText = $transaksi->status_pembayaran ?? 'Tidak Ada';
                                    switch (strtolower($statusText)) {
                                        case 'lunas': $badgeClass = 'bg-success'; break;
                                        case 'belum lunas': $badgeClass = 'bg-warning text-dark'; break;
                                        case 'menunggu pembayaran': $badgeClass = 'bg-info text-dark'; break;
                                        case 'dibatalkan': $badgeClass = 'bg-danger'; break;
                                        default: $badgeClass = 'bg-secondary'; break;
                                    }
                                @endphp
                                <span class="badge {{ $badgeClass }}">
                                    @if(strtolower($statusText) === 'menunggu pembayaran')
                                        Menunggu<br>Pembayaran
                                    @else
                                        {{ ucfirst($statusText) }}
                                    @endif
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-info action-btn" data-bs-toggle="modal" data-bs-target="#detailModal" data-id="{{ $transaksi->id }}" title="Lihat Detail Transaksi">
                                        <i class="bi bi-eye"></i>
                                    </button>

                                    @if(in_array(strtolower(auth()->user()->job ?? ''), ['manajer', 'admin']))
                                    <a href="{{ route('transaksi.penjual.edit', $transaksi->id) }}" class="btn btn-sm btn-outline-warning action-btn" title="Update Pembayaran">
                                        <i class="bi bi-cash"></i>
                                    </a>
                                    {{-- Tombol Delete --}}
                                    <form action="{{ route('transaksi.penjual.destroy', $transaksi->id) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger action-btn" title="Hapus Transaksi" onclick="return confirm('Apakah Anda yakin ingin menghapus transaksi ini secara permanen?');">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-5 empty-state">
                                <i class="bi bi-info-circle-fill fs-3 mb-2 d-block"></i>
                                <p class="mb-1">Tidak ada transaksi pembelian mobil ditemukan.</p>
                                <p class="mb-0">Coba kata kunci atau filter lain.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-light border-top-0 py-3 px-4 d-flex justify-content-between align-items-center rounded-bottom-4 gap-3">
            <small class="text-muted">Data ditampilkan: {{ $transaksiPenjual->count() }} dari {{ $transaksiPenjual->total() }}</small>
            {{ $transaksiPenjual->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

{{-- Detail Transaksi Modal --}}
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 shadow-lg border-0">
            <div class="modal-header bg-info text-white border-0 rounded-top-4 p-3">
                <h5 class="modal-title fw-bold fs-5" id="detailModalLabel"><i class="bi bi-file-text-fill me-2"></i> Detail Data Transaksi Pembelian</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <h6 class="mb-3 fw-bold border-bottom pb-2">
                    <i class="bi bi-card-checklist me-2 text-primary"></i><span class="text-dark">Informasi Transaksi:</span>
                </h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <p class="mb-0 text-muted small">Kode Transaksi:</p>
                            <p class="mb-0 fw-bold fs-6" id="detail-kode"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <p class="mb-0 text-muted small">Tanggal Transaksi:</p>
                            <p class="mb-0 fw-bold fs-6" id="detail-tanggal"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <p class="mb-0 text-muted small">Jam Transaksi:</p>
                            <p class="mb-0 fw-bold fs-6" id="detail-jam"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <p class="mb-0 text-muted small">Metode Pembayaran:</p>
                            <p class="mb-0 fw-bold fs-6" id="detail-metode"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <p class="mb-0 text-muted small">Harga Beli:</p>
                            <p class="mb-0 fw-bold fs-5 text-primary" id="detail-total"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <p class="mb-0 text-muted small">Status Pembayaran:</p>
                            <p class="mb-0 fw-bold fs-6" id="detail-status"></p>
                        </div>
                    </div>
                </div>

                <h6 class="mt-4 mb-3 fw-bold border-bottom pb-2">
                    <i class="bi bi-people-fill me-2 text-primary"></i><span class="text-dark">Detail Pihak Terkait:</span>
                </h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <p class="mb-0 text-muted small">Penjual:</p>
                            <p class="mb-0 fw-bold" id="detail-penjual-nama"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <p class="mb-0 text-muted small">Alamat Penjual:</p>
                            <p class="mb-0 fw-bold" id="detail-penjual-alamat"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <p class="mb-0 text-muted small">Pembeli (Dealer):</p>
                            <p class="mb-0 fw-bold" id="detail-pembeli"></p>
                        </div>
                    </div>
                </div>

                <h6 class="mt-4 mb-3 fw-bold border-bottom pb-2">
                    <i class="bi bi-car-front-fill me-2 text-primary"></i><span class="text-dark">Detail Mobil:</span>
                </h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-12">
                        <p class="mb-1 text-muted small">Informasi Mobil:</p>
                        <p class="mb-0 fw-bold" id="detail-mobil"></p>
                    </div>
                     <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <p class="mb-0 text-muted small">No. Plat:</p>
                            <p class="mb-0 fw-bold" id="detail-mobil-nomorpolisi"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <p class="mb-0 text-muted small">Jenis Mobil:</p>
                            <p class="mb-0 fw-bold" id="detail-mobil-jenis"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <p class="mb-0 text-muted small">Merek:</p>
                            <p class="mb-0 fw-bold" id="detail-mobil-merek"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <p class="mb-0 text-muted small">Model / Tipe:</p>
                            <p class="mb-0 fw-bold" id="detail-mobil-tipe"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <p class="mb-0 text-muted small">Tahun:</p>
                            <p class="mb-0 fw-bold" id="detail-mobil-tahun"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <p class="mb-0 text-muted small">Nomor Rangka:</p>
                            <p class="mb-0 fw-bold" id="detail-mobil-norangka"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <p class="mb-0 text-muted small">Nomor Mesin:</p>
                            <p class="mb-0 fw-bold" id="detail-mobil-nomesin"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0 pb-3">
                <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- Installment Modal (if applicable, based on your backend logic) --}}
<div class="modal fade" id="installmentModal" tabindex="-1" aria-labelledby="installmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow-lg border-0">
            <div class="modal-header bg-primary text-white border-0 rounded-top-4 p-3">
                <h5 class="modal-title fw-bold fs-5" id="installmentModalLabel"><i class="bi bi-calendar-check-fill me-2"></i> Detail Angsuran</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="mb-2"><strong>Kode Transaksi:</strong> <span id="installment-kode-transaksi" class="fw-bold"></span></p>
                <p class="mb-2"><strong>Total Harga:</strong> <span id="installment-total-harga" class="fw-bold text-success"></span></p>
                <p class="mb-2"><strong>Biaya Akuisisi:</strong> <span id="installment-biaya-akuisisi" class="fw-bold text-info"></span></p>
                <p class="mb-2"><strong>Jumlah DP:</strong> <span id="installment-dp-amount" class="fw-bold text-primary"></span></p>
                <p class="mb-3"><strong>Tempo Angsuran:</strong> <span id="installment-tempo-angsuran" class="fw-bold"></span></p>

                <h6 class="mt-4 mb-3 fw-bold border-bottom pb-2">Simulasi Angsuran Bulanan:</h6>
                <p id="installment-monthly-amount" class="fs-4 text-center text-dark fw-bold"></p>
            </div>
            <div class="modal-footer border-0 pt-0 pb-3">
                <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Function to format number to Rupiah
        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(number);
        }

        // Fungsi untuk mengaplikasikan filter dan pencarian
        function applyFiltersAndSearch() {
            const searchText = $('#searchInput').val().toLowerCase();
            const startDate = $('#startDateFilter').val();
            const endDate = $('#endDateFilter').val();
            const metodePembayaranFilter = $('#metodePembayaranFilter').val().toLowerCase();
            const statusFilter = $('#statusFilter').val().toLowerCase();

            let hasVisibleRows = false;
            $('#transaksiPenjualTable tbody tr.data-row').each(function() {
                const row = $(this);
                const kodeTransaksi = row.data('kode');
                const tanggalTransaksi = row.data('tanggal'); // Format YYYY-MM-DD
                const metodePembayaran = row.data('metode');
                const statusPembayaran = row.data('status');
                const mobilInfo = row.find('td:eq(3)').text().toLowerCase(); // Informasi Mobil
                const namaPenjual = row.find('td:eq(4)').text().toLowerCase(); // Nama Penjual

                let isVisible = true;

                // Search filter
                if (searchText && !(
                        kodeTransaksi.includes(searchText) ||
                        mobilInfo.includes(searchText) ||
                        namaPenjual.includes(searchText)
                    )) {
                    isVisible = false;
                }

                // Date filter
                if (startDate && tanggalTransaksi < startDate) {
                    isVisible = false;
                }
                if (endDate && tanggalTransaksi > endDate) {
                    isVisible = false;
                }

                // Metode Pembayaran filter
                if (metodePembayaranFilter && metodePembayaranFilter !== '' && metodePembayaran !== metodePembayaranFilter) {
                    isVisible = false;
                }

                // Status Pembayaran filter
                if (statusFilter && statusFilter !== '' && statusPembayaran !== statusFilter) {
                    isVisible = false;
                }

                if (isVisible) {
                    row.show();
                    hasVisibleRows = true;
                } else {
                    row.hide();
                }
            });

            // Handle empty state row
            const emptyStateRow = $('#transaksiPenjualTable .empty-state');
            if (emptyStateRow.length) {
                if (hasVisibleRows) {
                    emptyStateRow.hide();
                } else {
                    emptyStateRow.find('td').attr('colspan', $('#transaksiPenjualTable th').length);
                    emptyStateRow.show();
                }
            } else if (!hasVisibleRows && $('#transaksiPenjualTable tbody').length) {
                // If no empty state row exists and no rows are visible, create one
                const newEmptyStateRow = document.createElement('tr');
                newEmptyStateRow.classList.add('empty-state');
                newEmptyStateRow.innerHTML = `
                    <td colspan="9" class="text-center text-muted py-5">
                        <i class="bi bi-info-circle-fill fs-3 mb-2 d-block"></i>
                        <p class="mb-1">Tidak ada transaksi pembelian mobil ditemukan.</p>
                        <p class="mb-0">Coba kata kunci atau filter lain.</p>
                    </td>
                `;
                $('#transaksiPenjualTable tbody').append(newEmptyStateRow);
            }
        }

        // Event listeners for filters and search
        $('#searchInput, #startDateFilter, #endDateFilter, #metodePembayaranFilter, #statusFilter').on('change keyup', applyFiltersAndSearch);

        // Reset Filters Button
        $('#resetFilters').on('click', function() {
            $('#searchInput').val('');
            $('#startDateFilter').val('');
            $('#endDateFilter').val('');
            $('#metodePembayaranFilter').val('');
            $('#statusFilter').val('');
            applyFiltersAndSearch();
        });

        // Initial application of filters on page load
        applyFiltersAndSearch();


        // Handle Detail Modal (AJAX Call)
        $('#detailModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var transaksiId = button.data('id'); // Extract info from data-* attributes

            // AJAX call to fetch transaction details
            $.ajax({
                url: `/transaksi/penjual/${transaksiId}`, // This will hit the show method of TransaksiPenjualController
                method: 'GET',
                success: function(response) {
                    // Populate the modal with fetched data
                    $('#detail-kode').text(response.kode_transaksi);
                    $('#detail-tanggal').text(new Date(response.tanggal_transaksi).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }));
                    $('#detail-jam').text(new Date(response.created_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) + ' WIB');
                    $('#detail-metode').text(response.metode_pembayaran || 'N/A');
                    $('#detail-total').text(formatRupiah(response.total_harga));
                    $('#detail-status').text(response.status_pembayaran || 'N/A');

                    // Penjual Details
                    $('#detail-penjual-nama').text(response.penjual ? response.penjual.nama_penjual : 'N/A');
                    $('#detail-penjual-alamat').text(response.penjual ? response.penjual.alamat : 'N/A');
                    $('#detail-pembeli').text(response.user ? response.user.name : 'N/A'); // Assuming 'user' is the dealer/buyer

                    // Mobil Details
                    if (response.mobil) {
                        $('#detail-mobil').text(`${response.mobil.tahun_pembuatan || ''} ${response.mobil.merek_mobil || 'N/A'} ${response.mobil.tipe_mobil || ''}`);
                        $('#detail-mobil-nomorpolisi').text(response.mobil.nomor_polisi || 'N/A');
                        $('#detail-mobil-jenis').text(response.mobil.jenis_mobil || 'N/A');
                        $('#detail-mobil-merek').text(response.mobil.merek_mobil || 'N/A');
                        $('#detail-mobil-tipe').text(response.mobil.tipe_mobil || 'N/A');
                        $('#detail-mobil-tahun').text(response.mobil.tahun_pembuatan || 'N/A');
                        $('#detail-mobil-norangka').text(response.mobil.nomor_rangka || 'N/A');
                        $('#detail-mobil-nomesin').text(response.mobil.nomor_mesin || 'N/A');
                    } else {
                        $('#detail-mobil').text('Tidak Ada Mobil Terkait');
                        $('#detail-mobil-nomorpolisi, #detail-mobil-jenis, #detail-mobil-merek, #detail-mobil-tipe, #detail-mobil-tahun, #detail-mobil-norangka, #detail-mobil-nomesin').text('N/A');
                    }

                    // Handle payment details if you have a separate relationship (e.g., detail_pembayaran)
                    // For now, it's just showing the main method.
                    // If you have `detail_pembayaran` relationship in TransaksiPenjual model, you'd iterate it here.
                },
                error: function(xhr) {
                    console.error('Error fetching transaction details:', xhr.responseText);
                    alert('Gagal memuat detail transaksi. Silakan coba lagi.');
                }
            });
        });

        // Handle Installment Modal (if applicable)
        $('#installmentModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var totalHarga = button.data('total-harga');
            var tempoAngsuran = button.data('tempo-angsuran');
            var kodeTransaksi = button.data('kode-transaksi');
            var biayaAkuisisi = button.data('biaya-akuisisi') || 0;
            var dpAmount = button.data('dp-amount') || 0;

            $('#installment-kode-transaksi').text(kodeTransaksi);
            $('#installment-total-harga').text(formatRupiah(totalHarga));
            $('#installment-biaya-akuisisi').text(formatRupiah(biayaAkuisisi));
            $('#installment-dp-amount').text(formatRupiah(dpAmount));
            $('#installment-tempo-angsuran').text(tempoAngsuran + ' Tahun');

            // Calculate monthly installment
            const sisaPembayaran = totalHarga + biayaAkuisisi - dpAmount;
            if (tempoAngsuran > 0) {
                const monthlyInstallment = sisaPembayaran / (tempoAngsuran * 12);
                $('#installment-monthly-amount').text('Rp' + new Intl.NumberFormat('id-ID', { minimumFractionDigits: 0 }).format(monthlyInstallment) + ' / Bulan');
            } else {
                $('#installment-monthly-amount').text('Tidak Ada Angsuran');
            }
        });

    });
</script>
@endsection
