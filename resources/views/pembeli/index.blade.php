    @extends('layouts.app')

    @section('title', 'Data Pembeli')

    @section('content')
    <div class="container-fluid py-4 px-3 px-md-4">
        <div class="row mb-4 align-items-center">
            <div class="col-md-6 mb-3 mb-md-0">
                <h4 class="text-dark fw-bold mb-0">Daftar Data Pembeli</h4>
                <small class="text-secondary">Kelola semua informasi pembeli Anda dengan mudah.</small>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="{{ route('pembeli.create') }}" class="btn btn-primary btn-lg shadow-lg rounded-pill animate__animated animate__fadeInRight">
                    <i class="bi bi-plus-circle me-2"></i> Tambah Pembeli Baru
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
                    <h5 class="card-title mb-0 text-dark fw-bold">Daftar Pembeli</h5>
                    <p class="card-text text-muted">Informasi lengkap mengenai pembeli yang terdaftar.</p>
                </div>
                <div class="header-right d-flex flex-column flex-md-row align-items-md-center gap-3">
                    <input type="text" id="searchInput" class="form-control rounded-pill shadow-sm" placeholder="Cari pembeli..." aria-label="Cari pembeli">

                    {{-- Filter Pekerjaan --}}
                    <select id="pekerjaanFilter" class="form-select rounded-pill shadow-sm">
                        <option value="">Semua Pekerjaan</option>
                        @foreach($pembelis->unique('pekerjaan')->sortBy('pekerjaan') as $pembeli)
                            <option value="{{ $pembeli->pekerjaan }}">{{ $pembeli->pekerjaan }}</option>
                        @endforeach
                    </select>

                    {{-- Filter Tahun Lahir --}}
                    <select id="tahunLahirFilter" class="form-select rounded-pill shadow-sm">
                        <option value="">Semua Tahun Lahir</option>
                        @php
                            $uniqueYears = $pembelis->map(function($p) {
                                return \Carbon\Carbon::parse($p->tanggal_lahir)->year;
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
                    <table class="table table-hover table-striped align-middle mb-0 custom-table" id="pembeliTable">
                        <thead class="bg-gradient-primary text-white text-center align-middle">
                            <tr>
                                <th scope="col" style="width: 100px;">Kode</th>
                                <th scope="col" style="min-width: 180px;">Nama</th>
                                <th scope="col" style="min-width: 130px;">Tanggal Lahir</th>
                                <th scope="col" style="min-width: 160px;">Pekerjaan</th>
                                <th scope="col" style="min-width: 250px;">Alamat</th>
                                <th scope="col" style="min-width: 140px;">No. Telepon</th>
                                <th scope="col" style="width: 180px;">Aksi</th> {{-- Adjusted width for Aksi column (smaller as delete is removed) --}}
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pembelis as $pembeli)
                            <tr class="data-row"
                                data-nama="{{ strtolower($pembeli->nama) }}"
                                data-pekerjaan="{{ strtolower($pembeli->pekerjaan) }}"
                                data-tahunlahir="{{ \Carbon\Carbon::parse($pembeli->tanggal_lahir)->year }}">
                                <td class="text-center fw-bold">{{ $pembeli->kode_pembeli }}</td>
                                <td class="text-break">{{ $pembeli->nama }}</td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($pembeli->tanggal_lahir)->format('d M Y') }}</td>
                                <td class="text-break">{{ $pembeli->pekerjaan }}</td>
                                <td class="text-break">{{ $pembeli->alamat }}</td>
                                <td class="text-break">{{ $pembeli->no_telepon }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center align-items-center btn-group-actions">
                                        <button class="btn btn-sm btn-outline-info me-2 action-btn" data-bs-toggle="modal" data-bs-target="#detailModal"
                                            data-kode="{{ $pembeli->kode_pembeli }}"
                                            data-nama="{{ $pembeli->nama }}"
                                            data-tgllahir="{{ \Carbon\Carbon::parse($pembeli->tanggal_lahir)->format('d M Y') }}"
                                            data-pekerjaan="{{ $pembeli->pekerjaan }}"
                                            data-alamat="{{ $pembeli->alamat }}"
                                            data-notelepon="{{ $pembeli->no_telepon }}"
                                            title="Lihat Detail Pembeli">
                                            <i class="bi bi-info-circle"></i> <span class="d-none d-md-inline">Detail</span>
                                        </button>
                                        <a href="{{ route('pembeli.edit', $pembeli->id) }}" class="btn btn-sm btn-outline-warning action-btn" title="Edit Data Pembeli"> {{-- Removed 'me-2' as it's now the last button --}}
                                            <i class="bi bi-pencil-square"></i> <span class="d-none d-md-inline">Edit</span>
                                        </a>
                                        {{-- The delete button and its modal trigger logic are removed --}}
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
                {{-- Pagination Links --}}
                {{ $pembelis->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal (Entire block removed) --}}

    {{-- Detail Pembeli Modal --}}
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-4 shadow-lg border-0">
                <div class="modal-header bg-info text-white border-0 rounded-top-4 p-3">
                    <h5 class="modal-title fw-bold fs-5" id="detailModalLabel"><i class="bi bi-person-lines-fill me-2"></i> Detail Data Pembeli</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <p class="mb-1 text-muted small">Kode Pembeli:</p>
                            <p class="mb-0 fw-bold" id="detail-kode"></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="mb-1 text-muted small">Nama:</p>
                            <p class="mb-0 fw-bold" id="detail-nama"></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="mb-1 text-muted small">Tanggal Lahir:</p>
                            <p class="mb-0 fw-bold" id="detail-tgllahir"></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="mb-1 text-muted small">Pekerjaan:</p>
                            <p class="mb-0 fw-bold" id="detail-pekerjaan"></p>
                        </div>
                        <div class="col-md-12 mb-3">
                            <p class="mb-1 text-muted small">Alamat:</p>
                            <p class="mb-0 fw-bold" id="detail-alamat"></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="mb-1 text-muted small">No. Telepon:</p>
                            <p class="mb-0 fw-bold" id="detail-notelepon"></p>
                        </div>
                    </div>
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
        .custom-table th:nth-child(1), .custom-table td:nth-child(1) { width: 100px; } /* Kode */
        .custom-table th:nth-child(2), .custom-table td:nth-child(2) { width: 180px; } /* Nama */
        .custom-table th:nth-child(3), .custom-table td:nth-child(3) { width: 130px; } /* Tanggal Lahir */
        .custom-table th:nth-child(4), .custom-table td:nth-child(4) { width: 160px; } /* Pekerjaan */
        .custom-table th:nth-child(5), .custom-table td:nth-child(5) { width: 250px; } /* Alamat */
        .custom-table th:nth-child(6), .custom-table td:nth-child(6) { width: 140px; } /* No. Telepon */
        .custom-table th:nth-child(7), .custom-table td:nth-child(7) { width: 180px; } /* Aksi */


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

        /* Adjust header-right layout for small screens */
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- Modal Logic (Delete) ---
            // This entire block for delete modal is removed.
            // const deleteModal = document.getElementById('deleteModal');
            // if (deleteModal) {
            //     deleteModal.addEventListener('show.bs.modal', function (event) {
            //         const button = event.relatedTarget;
            //         const pembeliId = button.getAttribute('data-id');
            //         const pembeliNama = button.getAttribute('data-nama');
            //         const pembeliKode = button.getAttribute('data-kode');

            //         const modalPembeliKode = document.getElementById('modal-pembeli-kode');
            //         const modalPembeliNama = document.getElementById('modal-pembeli-nama');
            //         const deleteForm = document.getElementById('delete-form');

            //         if (modalPembeliKode) modalPembeliKode.textContent = pembeliKode;
            //         if (modalPembeliNama) modalPembeliNama.textContent = pembeliNama;
            //         if (deleteForm) {
            //             deleteForm.action = `/pembeli/${pembeliId}`;
            //         }
            //     });
            // }

            // --- Modal Logic (Detail) ---
            const detailModal = document.getElementById('detailModal');
            if (detailModal) {
                detailModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget; // Button that triggered the modal

                    const data = {
                        kode: button.getAttribute('data-kode'),
                        nama: button.getAttribute('data-nama'),
                        tgllahir: button.getAttribute('data-tgllahir'),
                        pekerjaan: button.getAttribute('data-pekerjaan'),
                        alamat: button.getAttribute('data-alamat'),
                        notelepon: button.getAttribute('data-notelepon')
                    };

                    document.getElementById('detail-kode').textContent = data.kode;
                    document.getElementById('detail-nama').textContent = data.nama;
                    document.getElementById('detail-tgllahir').textContent = data.tgllahir;
                    document.getElementById('detail-pekerjaan').textContent = data.pekerjaan;
                    document.getElementById('detail-alamat').textContent = data.alamat;
                    document.getElementById('detail-notelepon').textContent = data.notelepon;
                });
            }

            // --- Table Search and Filter Logic ---
            const searchInput = document.getElementById('searchInput');
            const pekerjaanFilter = document.getElementById('pekerjaanFilter');
            const tahunLahirFilter = document.getElementById('tahunLahirFilter');
            const resetFiltersBtn = document.getElementById('resetFilters');

            const pembeliTable = document.getElementById('pembeliTable');
            const tableRows = pembeliTable.querySelectorAll('tbody tr.data-row');
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
                const selectedPekerjaan = pekerjaanFilter.value.toLowerCase().trim();
                const selectedTahunLahir = tahunLahirFilter.value.toLowerCase().trim();

                let foundVisibleRows = false;

                tableRows.forEach(row => {
                    const nama = row.getAttribute('data-nama');
                    const pekerjaan = row.getAttribute('data-pekerjaan');
                    const tahunLahir = row.getAttribute('data-tahunlahir');

                    const matchesSearch = nama.includes(searchTerm) ||
                                        pekerjaan.includes(searchTerm) ||
                                        row.textContent.toLowerCase().includes(searchTerm); // General search for other columns

                    const matchesPekerjaan = selectedPekerjaan === '' || pekerjaan === selectedPekerjaan;
                    const matchesTahunLahir = selectedTahunLahir === '' || tahunLahir === selectedTahunLahir;

                    if (matchesSearch && matchesPekerjaan && matchesTahunLahir) {
                        row.style.display = ''; // Show row
                        foundVisibleRows = true;
                    } else {
                        row.style.display = 'none'; // Hide row
                    }
                });

                // Handle empty state message
                const emptyStateRow = pembeliTable.querySelector('.empty-state');
                if (emptyStateRow) {
                    if (foundVisibleRows) {
                        emptyStateRow.style.display = 'none';
                    } else {
                        emptyStateRow.style.display = '';
                        emptyStateRow.querySelector('.bi-info-circle-fill').style.display = 'block';
                        emptyStateRow.querySelector('p:first-of-type').textContent = 'Tidak ada hasil ditemukan untuk pencarian atau filter Anda.';
                        emptyStateRow.querySelector('p:last-of-type').textContent = 'Coba kata kunci atau filter lain.';
                    }
                } else if (!foundVisibleRows && !pembeliTable.querySelector('.empty-state')) {
                    // If no empty state row exists but no results are found, create one
                    const newEmptyStateRow = document.createElement('tr');
                    newEmptyStateRow.classList.add('empty-state');
                    newEmptyStateRow.innerHTML = `
                        <td colspan="7" class="text-center text-muted py-5">
                            <i class="bi bi-info-circle-fill fs-3 mb-2 d-block"></i>
                            <p class="mb-1">Tidak ada hasil ditemukan untuk pencarian atau filter Anda.</p>
                            <p class="mb-0">Coba kata kunci atau filter lain.</p>
                        </td>
                    `;
                    pembeliTable.querySelector('tbody').appendChild(newEmptyStateRow);
                }
            }

            // Attach event listeners for filtering and searching
            searchInput.addEventListener('keyup', applyFiltersAndSearch);
            pekerjaanFilter.addEventListener('change', applyFiltersAndSearch);
            tahunLahirFilter.addEventListener('change', applyFiltersAndSearch);

            resetFiltersBtn.addEventListener('click', function() {
                searchInput.value = '';
                pekerjaanFilter.value = '';
                tahunLahirFilter.value = '';
                applyFiltersAndSearch();
            });

            // Initial application of filters on page load
            applyFiltersAndSearch();
        });
    </script>
    @endsection
