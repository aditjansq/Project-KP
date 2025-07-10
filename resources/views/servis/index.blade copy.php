@extends('layouts.app')

@section('title', 'Daftar Servis')

@section('content')
<div class="container-fluid py-4 px-3 px-md-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6 mb-3 mb-md-0">
            <h4 class="text-dark fw-bold mb-0">Daftar Data Servis</h4>
            <small class="text-secondary">Kelola semua informasi servis kendaraan Anda dengan mudah.</small>
        </div>
        <div class="col-md-6 text-md-end">
            {{-- Tombol Tambah Servis - Hanya untuk Admin --}}
            @if(strtolower(auth()->user()->job ?? '') === 'admin')
            <a href="{{ route('servis.create') }}" class="btn btn-primary btn-lg shadow-lg rounded-pill animate__animated animate__fadeInRight">
                <i class="bi bi-plus-circle me-2"></i> Tambah Servis Baru
            </a>
            @endif
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
                <h5 class="card-title mb-0 text-dark fw-bold">Daftar Servis</h5>
                <p class="card-text text-muted">Informasi lengkap mengenai histori servis kendaraan yang terdaftar.</p>
            </div>
            <div class="header-right d-flex flex-column flex-md-row align-items-md-center gap-3">
                <input type="text" id="searchInput" class="form-control rounded-pill shadow-sm" placeholder="Cari servis..." aria-label="Cari servis">

                {{-- Filter Tanggal Servis --}}
                @php
                    $today = date('Y-m-d');
                    $oneMonthAgo = date('Y-m-d', strtotime('-1 month'));
                @endphp
                <div class="d-flex flex-column flex-md-row gap-2">
                    <input type="date" id="startDateFilter" class="form-control rounded-pill shadow-sm" title="Dari Tanggal Servis" max="{{ $today }}" min="{{ $oneMonthAgo }}">
                    <input type="date" id="endDateFilter" class="form-control rounded-pill shadow-sm" title="Sampai Tanggal Servis" max="{{ $today }}" min="{{ $oneMonthAgo }}">
                </div>

                {{-- Filter Status Servis --}}
                <select id="statusFilter" class="form-select rounded-pill shadow-sm">
                    <option value="">Semua Status</option>
                    <option value="selesai">Selesai</option>
                    <option value="proses">Proses</option>
                    <option value="batal">Batal</option>
                    <option value="null">Tidak Ada</option> {{-- Menambahkan opsi untuk status NULL --}}
                </select>

                {{-- Filter Tahun Servis (Opsional, bisa dihapus jika filter tanggal lebih disukai) --}}
                <select id="tahunServisFilter" class="form-select rounded-pill shadow-sm">
                    <option value="">Semua Tahun Servis</option>
                    @php
                        // Memastikan $servis adalah instance paginator atau collection sebelum map
                        $uniqueYears = optional($servis)->map(function($s) {
                            return \Carbon\Carbon::parse($s->tanggal_servis)->year;
                        })->unique()->sortDesc();
                    @endphp
                    @foreach($uniqueYears as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>

                <button id="resetFilters" class="btn btn-outline-secondary rounded-pill shadow-sm">Reset</button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive" id="tableResponsiveContainer">
                <table class="table table-hover table-striped align-middle mb-0 custom-table" id="servisTable">
                    <thead class="bg-gradient-primary text-white text-center align-middle">
                        <tr>
                            <th scope="col" style="width: 160px;">Kode Servis</th>
                            <th scope="col" style="min-width: 150px;">Tanggal Servis</th>
                            <th scope="col" style="min-width: 160px;">Informasi Mobil (Tahun, Merek, Tipe, No. Polisi)</th>
                            <th scope="col" style="min-width: 150px;">Metode Pembayaran</th>
                            <th scope="col" style="min-width: 120px;">Total Harga</th>
                            <th scope="col" style="min-width: 120px;">Status</th>
                            <th scope="col" style="width: 180px;">Lihat Detail</th> {{-- Lebarkan kolom ini --}}
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($servis as $item)
                        <tr class="data-row"
                            data-kode="{{ strtolower($item->kode_servis) }}"
                            data-mobil="{{ strtolower($item->mobil->nomor_polisi ?? '') }}"
                            data-status="{{ strtolower($item->status ?? 'null') }}"
                            data-tahunservis="{{ \Carbon\Carbon::parse($item->tanggal_servis)->year }}"
                            data-tanggal="{{ \Carbon\Carbon::parse($item->tanggal_servis)->format('Y-m-d') }}">
                            <td class="text-center fw-bold">{{ $item->kode_servis }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal_servis)->format('d M Y') }}</td>
                            <td class="text-break">
                                @php
                                    $mobilDisplay = '';
                                    if ($item->mobil->tahun_pembuatan) {
                                        $mobilDisplay .= $item->mobil->tahun_pembuatan . ' ';
                                    }
                                    $mobilDisplay .= $item->mobil->merek_mobil ?? 'N/A';
                                    if ($item->mobil->tipe_mobil) {
                                        $mobilDisplay .= ' ' . $item->mobil->tipe_mobil;
                                    }
                                    $mobilDisplay .= ' - ' . ($item->mobil->nomor_polisi ?? 'N/A');
                                @endphp
                                {{ $mobilDisplay }}
                            </td>
                            <td class="text-break">{{ $item->metode_pembayaran }}</td>
                            <td class="text-end">Rp{{ number_format($item->total_harga, 0, ',', '.') }}</td>
                            <td class="text-center">
                                @php
                                    $badgeClass = '';
                                    $statusText = $item->status ?? 'Tidak Ada';
                                    switch (strtolower($item->status ?? 'null')) {
                                        case 'selesai': $badgeClass = 'bg-success'; break;
                                        case 'proses': $badgeClass = 'bg-primary'; break;
                                        case 'batal': $badgeClass = 'bg-danger'; break;
                                        case 'null': $badgeClass = 'bg-secondary'; break;
                                        default: $badgeClass = 'bg-secondary'; break;
                                    }
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ ucfirst($statusText) }}</span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center align-items-center btn-group-actions">
                                    <button class="btn btn-sm btn-outline-info me-2 action-btn" data-bs-toggle="modal" data-bs-target="#detailModal" data-id="{{ $item->id }}" title="Lihat Detail Servis">
                                        <i class="bi bi-info-circle"></i> <span class="d-none d-md-inline">Detail</span>
                                    </button>
                                    {{-- Tombol Edit hanya untuk Admin --}}
                                    @if(strtolower(auth()->user()->job ?? '') === 'admin')
                                    <a href="{{ route('servis.edit', $item->id) }}" class="btn btn-sm btn-outline-warning action-btn" title="Edit Servis">
                                        <i class="bi bi-pencil-square"></i> <span class="d-none d-md-inline">Edit</span>
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5 empty-state"> <i class="bi bi-info-circle-fill fs-3 mb-2 d-block"></i>
                                <p class="mb-1">Tidak ada hasil ditemukan untuk pencarian atau filter Anda.</p>
                                <p class="mb-0">Coba kata kunci atau filter lain.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-light border-top-0 py-3 px-4 d-flex justify-content-between align-items-center rounded-bottom-4 gap-3">
            <small class="text-muted">Data diperbarui terakhir: {{ \Carbon\Carbon::now()->format('d M Y H:i') }} WIB</small>
            {{-- Pagination Links (Pastikan $servis adalah instance Paginator) --}}
            {{ optional($servis)->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

{{-- Detail Servis Modal --}}
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 shadow-lg border-0">
            <div class="modal-header bg-info text-white border-0 rounded-top-4 p-3">
                <h5 class="modal-title fw-bold fs-5" id="detailModalLabel"><i class="bi bi-file-text-fill me-2"></i> Detail Data Servis</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row mb-3">
                    <div class="col-md-6 mb-2">
                        <p class="mb-1 text-muted small">Kode Servis:</p>
                        <p class="mb-0 fw-bold" id="detail-kode"></p>
                    </div>
                    <div class="col-md-6 mb-2">
                        <p class="mb-1 text-muted small">Tanggal Servis:</p>
                        <p class="mb-0 fw-bold" id="detail-tanggal"></p>
                    </div>
                    <div class="col-md-6 mb-2">
                        <p class="mb-1 text-muted small">Mobil:</p>
                        <p class="mb-0 fw-bold" id="detail-mobil"></p>
                    </div>
                    <div class="col-md-6 mb-2">
                        <p class="mb-1 text-muted small">Metode Pembayaran:</p>
                        <p class="mb-0 fw-bold" id="detail-metode"></p>
                    </div>
                    <div class="col-md-6 mb-2">
                        <p class="mb-1 text-muted small">Status:</p>
                        <p class="mb-0 fw-bold" id="detail-status"></p>
                    </div>
                    <div class="col-md-6 mb-2">
                        <p class="mb-1 text-muted small">Total Harga:</p>
                        <p class="mb-0 fw-bold" id="detail-total"></p>
                    </div>
                </div>

                <h6 class="mt-4 mb-2 text-dark fw-bold">Item Servis:</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Barang</th>
                                <th>Kemasan</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Harga Satuan</th>
                                <th class="text-end">Diskon (%)</th>
                                <th class="text-end">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody id="detail-items-body">
                            {{-- Items will be loaded here by JS --}}
                        </tbody>
                    </table>
                </div>
                <p class="text-muted small mt-2">Daftar barang dan layanan yang digunakan dalam servis ini.</p>
            </div>
            <div class="modal-footer border-0 p-3 bg-light rounded-bottom-4">
                <button type="button" class="btn btn-outline-secondary px-4 rounded-pill" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Google Fonts - Poppins */
    /* @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap'); Remove if already in main layout */

    body {
        background-color: #f8f9fa; /* Lighter, more neutral background */
        font-family: 'Poppins', sans-serif; /* Changed to Poppins for consistency */
        color: #343a40;
    }

    .container-fluid.py-4 {
        padding-top: 2.5rem !important;
        padding-bottom: 2.5rem !important;
    }

    /* Primary Button Style */
    .btn-primary {
        background: linear-gradient(45deg, #0d6efd, #0b5ed7);
        border: none;
        transition: all 0.3s ease;
        font-weight: 600;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 10px rgba(13, 110, 253, 0.2);
    }
    .btn-primary:hover {
        background: linear-gradient(45deg, #0b5ed7, #0d6efd);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    }

    /* Card Styling */
    .card {
        border-radius: 1rem !important; /* More rounded corners */
        overflow: hidden;
        box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.09) !important; /* Stronger, softer shadow */
    }

    .card-header {
        background-color: #ffffff;
        border-bottom: 1px solid #e9ecef;
    }

    /* Alert Styling (Success) */
    .alert-success {
        background-color: #e6ffed; /* Lighter green background */
        color: #1a4d2e; /* Darker green text */
        border: 1px solid #28a745; /* Green border */
        padding: 1rem 1.5rem;
        border-radius: 0.75rem;
    }
    .alert-success .alert-heading {
        color: #28a745;
    }
    .alert-success .btn-close {
        font-size: 0.8rem;
    }

    /* Table Styling */
    .custom-table {
        border-collapse: separate;
        border-spacing: 0;
        table-layout: fixed; /* Added to fix column widths */
        width: 100%; /* Ensure table takes full width */
    }

    .custom-table thead th {
        background: linear-gradient(90deg, #495057, #6c757d); /* Darker gradient for header */
        color: #fff;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem; /* Slightly smaller font for header */
        padding: 1rem 0.8rem; /* More padding */
        border-bottom: none; /* Remove default border */
        white-space: nowrap; /* Prevent wrapping in headers */
    }

    .custom-table thead th:first-child {
        border-top-left-radius: 1rem;
    }

    .custom-table thead th:last-child {
        border-top-right-radius: 1rem;
    }

    .custom-table tbody tr {
        transition: all 0.2s ease;
        border-bottom: 1px solid #dee2e6; /* Subtle border between rows */
    }

    .custom-table tbody tr:last-child {
        border-bottom: none;
    }

    .custom-table tbody tr:hover {
        background-color: #f1f3f5; /* More distinct hover */
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.05); /* Lift effect on hover */
        transform: translateY(-1px);
    }

    .custom-table tbody td {
        padding: 0.8rem; /* Consistent padding */
        vertical-align: middle;
        font-size: 0.88rem;
        color: #495057;
        white-space: nowrap; /* Prevent content wrapping in cells */
        overflow: hidden; /* Hide overflowing content in cells */
        text-overflow: ellipsis; /* Add ellipsis for overflowing text */
    }

    /* Specific column width adjustments */
    .custom-table th:nth-child(1), .custom-table td:nth-child(1) { width: 160px; } /* Kode Servis */
    .custom-table th:nth-child(2), .custom-table td:nth-child(2) { width: 150px; } /* Tanggal Servis */
    .custom-table th:nth-child(3), .custom-table td:nth-child(3) { min-width: 160px; } /* Informasi Mobil */
    .custom-table th:nth-child(4), .custom-table td:nth-child(4) { width: 150px; } /* Metode Pembayaran */
    .custom-table th:nth-child(5), .custom-table td:nth-child(5) { width: 120px; } /* Total Harga */
    .custom-table th:nth-child(6), .custom-table td:nth-child(6) { width: 120px; } /* Status */
    .custom-table th:nth-child(7), .custom-table td:nth-child(7) { width: 180px; } /* Lihat Detail */


    .text-muted-header {
        color: rgba(255, 255, 255, 0.7) !important; /* Slightly faded header text for less important columns */
        font-weight: 500 !important;
    }

    /* Action buttons group */
    .btn-group-actions {
        gap: 0.5rem;
    }

    .action-btn {
        font-size: 0.8rem;
        padding: 0.4rem 0.7rem;
        border-radius: 0.5rem;
        transition: all 0.2s ease;
        white-space: nowrap; /* Prevent button text wrapping */
    }

    .action-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    /* Empty state styling */
    .empty-state {
        background-color: #fefefe;
        color: #6c757d;
        font-style: italic;
        padding: 3rem !important;
    }
    .empty-state i {
        color: #adb5bd;
    }

    /* Modal enhancements */
    .modal-header {
        border-bottom: none;
    }
    .modal-content {
        border: none;
    }
    .modal-body p.lead {
        font-size: 1.15rem;
        font-weight: 500;
    }
    .modal-body .alert-warning {
        background-color: #fff8eb; /* Softer warning background */
        border-left: 5px solid #ffc107;
        color: #6a4000;
        align-items: center;
        border-radius: 0.75rem;
    }
    .modal-body .alert-warning i {
        color: #ffc107;
    }
    .modal-footer {
        background-color: #f8f9fa;
        border-top: 1px solid #e9ecef;
        padding-top: 1rem;
        padding-bottom: 1rem;
    }
    .modal-footer .btn {
        font-weight: 500;
    }

    /* Search input styling */
    #searchInput {
        max-width: 300px;
        border: 1px solid #ced4da;
        padding: 0.65rem 1rem;
    }
    #searchInput:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13,110,253,.25);
    }

    /* Filter select styling */
    .card-header .form-select {
        max-width: 200px; /* Limit width of dropdowns */
        border: 1px solid #ced4da;
        padding: 0.65rem 1rem;
        font-size: 0.9rem;
        border-radius: 2rem; /* Pill shape for selects too */
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }
    .card-header .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13,110,253,.25);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .card-header .header-right {
            width: 100%; /* Take full width */
            flex-direction: column; /* Stack filter elements vertically */
            align-items: stretch; /* Stretch items to fill width */
        }
        .card-header .header-right .form-control,
        .card-header .header-right .form-select,
        .card-header .header-right .btn {
            max-width: 100%; /* Make them full width */
            margin-bottom: 0.75rem; /* Add spacing between stacked elements */
        }
        .card-header .header-right .btn:last-child {
            margin-bottom: 0; /* No margin on the last button */
        }
    }
</style>

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Include Bootstrap JS (Bundled with Popper for modals/dropdowns) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Include Moment.js for date formatting in modals -->
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Modal Logic (Detail) ---
        const detailModal = document.getElementById('detailModal');
        if (detailModal) {
            detailModal.addEventListener('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // Tombol yang diklik
                var id = button.data('id'); // ID servis dari data-id tombol

                // Ambil data servis berdasarkan ID
                $.ajax({
                    url: '/servis/' + id, // Route API untuk mengambil detail servis
                    method: 'GET',
                    success: function(data) {
                        // Menampilkan informasi servis utama di modal
                        $('#detail-kode').text(data.kode_servis);
                        $('#detail-tanggal').text(moment(data.tanggal_servis).format('DD MMMM YYYY'));
                        // Menampilkan informasi mobil lengkap di modal
                        let mobilInfoDetail = '';
                        if (data.mobil.tahun_pembuatan) {
                            mobilInfoDetail += data.mobil.tahun_pembuatan + ' ';
                        }
                        mobilInfoDetail += data.mobil.merek_mobil || 'N/A';
                        if (data.mobil.tipe_mobil) {
                            mobilInfoDetail += ' ' + data.mobil.tipe_mobil;
                        }
                        if (data.mobil.nomor_polisi) {
                            mobilInfoDetail += ' - ' + data.mobil.nomor_polisi;
                        }
                        $('#detail-mobil').text(mobilInfoDetail.trim()); // Trim any leading/trailing space

                        $('#detail-metode').text(data.metode_pembayaran);
                        $('#detail-status').text(data.status ? data.status.charAt(0).toUpperCase() + data.status.slice(1) : 'Tidak Ada'); // Menggunakan data.status dan format huruf kapital pertama
                        // Format total_harga menjadi Rupiah
                        $('#detail-total').text('Rp' + parseFloat(data.total_harga).toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 }));

                        // Menampilkan daftar item servis di modal
                        const itemsBody = document.getElementById('detail-items-body');
                        itemsBody.innerHTML = ''; // Bersihkan item sebelumnya
                        let itemList = ''; // Inisialisasi itemList di sini
                        if (data.items && data.items.length > 0) {
                            $.each(data.items, function(index, item) {
                                const hargaSatuanFormatted = parseFloat(item.item_price).toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
                                const jumlahFormatted = parseFloat(item.item_total).toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 });

                                itemList += `
                                    <tr>
                                        <td>${item.item_name}</td>
                                        <td>${item.item_package}</td>
                                        <td class="text-center">${item.item_qty}</td>
                                        <td class="text-end">Rp${hargaSatuanFormatted}</td>
                                        <td class="text-end">${item.item_discount}%</td>
                                        <td class="text-end">Rp${jumlahFormatted}</td>
                                    </tr>
                                `;
                            });
                            itemsBody.innerHTML = itemList;
                        } else {
                            itemsBody.innerHTML = `<tr><td colspan="6" class="text-center text-muted">Tidak ada item servis.</td></tr>`;
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error("AJAX Error:", textStatus, errorThrown);
                        console.error("Response Text:", jqXHR.responseText);
                        alert("Gagal mengambil data detail servis. Silakan cek konsol browser untuk error.");
                    }
                });
            });
        }

        // --- Table Search and Filter Logic ---
        const searchInput = document.getElementById('searchInput');
        const startDateFilter = document.getElementById('startDateFilter');
        const endDateFilter = document.getElementById('endDateFilter');
        const statusFilter = document.getElementById('statusFilter');
        const tahunServisFilter = document.getElementById('tahunServisFilter');
        const resetFiltersBtn = document.getElementById('resetFilters');

        const servisTable = document.getElementById('servisTable');
        const tableRows = servisTable.querySelectorAll('tbody tr.data-row');
        const tableResponsiveContainer = document.getElementById('tableResponsiveContainer');

        const scrollToRight = () => {
            if (tableResponsiveContainer) {
                tableResponsiveContainer.scrollLeft = tableResponsiveContainer.scrollWidth;
            }
        };

        // Scroll to the right on load and resize if table is wider than container
        scrollToRight();
        window.addEventListener('resize', scrollToRight);

        function applyFiltersAndSearch() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            const selectedStatus = statusFilter.value.toLowerCase().trim(); // Status filter value
            const selectedTahunServis = tahunServisFilter.value.toLowerCase().trim();
            const selectedStartDate = startDateFilter.value;
            const selectedEndDate = endDateFilter.value;

            let foundVisibleRows = false;

            tableRows.forEach(row => {
                const kode = row.getAttribute('data-kode');
                const mobil = row.getAttribute('data-mobil');
                const status = row.getAttribute('data-status'); // Get status from data attribute
                const tahunServis = row.getAttribute('data-tahunservis');
                const tanggalServisRow = row.getAttribute('data-tanggal');

                // For search, include row.textContent for more comprehensive matching
                const rowTextContent = row.textContent.toLowerCase();
                const matchesSearch = rowTextContent.includes(searchTerm);

                // Filter by status
                const matchesStatus = selectedStatus === '' || status === selectedStatus;

                const matchesTahunServis = selectedTahunServis === '' || tahunServis === selectedTahunServis;

                // Date range filtering
                let matchesDateRange = true;
                if (selectedStartDate && tanggalServisRow < selectedStartDate) {
                    matchesDateRange = false;
                }
                if (selectedEndDate && tanggalServisRow > selectedEndDate) {
                    matchesDateRange = false;
                }

                if (matchesSearch && matchesStatus && matchesTahunServis && matchesDateRange) {
                    row.style.display = '';
                    foundVisibleRows = true;
                } else {
                    row.style.display = 'none';
                }
            });

            // Handle empty state message
            const emptyStateRow = servisTable.querySelector('.empty-state');
            if (emptyStateRow) {
                if (foundVisibleRows) {
                    emptyStateRow.style.display = 'none';
                } else {
                    emptyStateRow.style.display = '';
                }
            } else if (!foundVisibleRows && servisTable.querySelector('tbody')) {
                const newEmptyStateRow = document.createElement('tr');
                newEmptyStateRow.classList.add('empty-state');
                newEmptyStateRow.innerHTML = `
                    <td colspan="7" class="text-center text-muted py-5">
                        <i class="bi bi-info-circle-fill fs-3 mb-2 d-block"></i>
                        <p class="mb-1">Tidak ada hasil ditemukan untuk pencarian atau filter Anda.</p>
                        <p class="mb-0">Coba kata kunci atau filter lain.</p>
                    </td>
                `;
                servisTable.querySelector('tbody').appendChild(newEmptyStateRow);
            }
        }

        // Attach event listeners for filtering and searching
        searchInput.addEventListener('keyup', applyFiltersAndSearch);
        startDateFilter.addEventListener('change', applyFiltersAndSearch);
        endDateFilter.addEventListener('change', applyFiltersAndSearch);
        statusFilter.addEventListener('change', applyFiltersAndSearch); // Event listener for status filter
        tahunServisFilter.addEventListener('change', applyFiltersAndSearch);

        resetFiltersBtn.addEventListener('click', function() {
            searchInput.value = '';
            startDateFilter.value = '';
            endDateFilter.value = '';
            statusFilter.value = '';
            tahunServisFilter.value = '';
            applyFiltersAndSearch();
        });

        // Initial application of filters on page load
        applyFiltersAndSearch();
    });
</script>
@endsection
