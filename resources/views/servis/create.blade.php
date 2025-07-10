@extends('layouts.app')

@section('title', 'Tambah Servis')

@section('content')
<head>
    {{-- Moment.js for date formatting (if needed in client-side JS for date manipulation) --}}
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    {{-- Select2 CSS for enhanced dropdowns --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    {{-- Animate.css for subtle animations --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>
<div class="container-fluid py-4 px-3 px-md-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8 mb-3 mb-md-0">
            <h4 class="text-dark fw-bold mb-0">Tambah Servis Baru</h4>
            <small class="text-secondary">Isi formulir di bawah ini untuk mencatat servis kendaraan baru.</small>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('servis.index') }}" class="btn btn-outline-secondary rounded-pill animate__animated animate__fadeInRight">
                <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar Servis
            </a>
        </div>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger border-0 shadow-sm rounded-3 animate__animated animate__shakeX" role="alert">
        <h6 class="alert-heading fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i> Terjadi Kesalahan Input:</h6>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card border-0 shadow-xl rounded-4 animate__animated animate__fadeInUp">
        <div class="card-body p-lg-5 p-md-4 p-3">
            <form method="POST" action="{{ route('servis.store') }}">
                @csrf
                @php
                    $old = fn($name) => old($name);
                    // Calculate date one month ago for min attribute
                    $oneMonthAgo = date('Y-m-d', strtotime('-1 month'));
                @endphp

                <h5 class="mb-4 fw-bold text-dark border-bottom pb-2">Informasi Servis Utama</h5>
                <div class="row g-3 mb-4">
                    <!-- Kode Servis -->
                    <div class="col-md-6">
                        <label for="kode_servis" class="form-label text-muted">Kode Servis</label>
                        <input type="text" class="form-control form-control-lg bg-light-subtle rounded-pill border-0 shadow-sm" name="kode_servis" id="kode_servis" value="{{ $kode_servis }}" readonly />
                    </div>

                    <!-- Cari Mobil (Select2) -->
                    <div class="col-md-6">
                        <label for="mobil_id" class="form-label text-muted">Pilih Mobil</label>
                        <select class="form-control select2 form-select-lg rounded-pill shadow-sm" name="mobil_id" id="mobil_id" style="width: 100%" required>
                            <option value="">Pilih Mobil (Tahun - Merek - Tipe - No. Polisi)</option>
                            @foreach($mobils as $mobil)
                                <option
                                    value="{{ $mobil->id }}" data-merek="{{ $mobil->merek_mobil }}"
                                    data-tipe="{{ $mobil->tipe_mobil ?? '' }}"
                                    data-nomorpolisi="{{ $mobil->nomor_polisi }}"
                                    data-tahunpembuatan="{{ $mobil->tahun_pembuatan ?? '' }}"
                                    {{ old('mobil_id') == $mobil->id ? 'selected' : '' }}> {{ $mobil->tahun_pembuatan ?? '' }} {{ $mobil->merek_mobil }} {{ $mobil->tipe_mobil ?? '' }} - {{ $mobil->nomor_polisi }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Informasi Mobil (Auto Fill) -->
                    <div class="col-md-6">
                        <label for="merek_mobil" class="form-label text-muted">Informasi Mobil</label>
                        <input type="text" class="form-control form-control-lg bg-light-subtle rounded-pill border-0 shadow-sm" name="merek_mobil" id="merek_mobil" value="{{ $old('merek_mobil') }}" readonly />
                    </div>

                    <!-- Tanggal Servis -->
                    <div class="col-md-6">
                        <label for="tanggal_servis" class="form-label text-muted">Tanggal Servis</label>
                        {{-- Set max attribute to today's date to prevent future dates --}}
                        {{-- Set min attribute to date one month ago to prevent older dates --}}
                        <input type="date" class="form-control form-control-lg rounded-pill shadow-sm" id="tanggal_servis" name="tanggal_servis" value="{{ $old('tanggal_servis', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}" min="{{ $oneMonthAgo }}" required>
                    </div>

                    <!-- Metode Pembayaran -->
                    <div class="col-md-6">
                        <label for="metode_pembayaran" class="form-label text-muted">Metode Pembayaran</label>
                        <select class="form-control form-select-lg rounded-pill shadow-sm" id="metode_pembayaran" name="metode_pembayaran" required>
                            <option value="Transfer Bank" {{ $old('metode_pembayaran') == 'Transfer Bank' ? 'selected' : '' }}>Transfer Bank</option>
                            <option value="Cash" {{ $old('metode_pembayaran') == 'Cash' ? 'selected' : '' }}>Cash</option>
                            {{-- <option value="Kartu Kredit" {{ $old('metode_pembayaran') == 'Kartu Kredit' ? 'selected' : '' }}>Kartu Kredit</option> --}}
                        </select>
                    </div>

                    <!-- Status Servis (BARU DITAMBAHKAN) -->
                    <div class="col-md-6">
                        <label for="status" class="form-label text-muted">Status Servis</label>
                        <select class="form-control form-select-lg rounded-pill shadow-sm" id="status" name="status">
                            <option value="" {{ is_null(old('status')) ? 'selected' : '' }}>Pilih Status</option> {{-- Default null --}}
                            <option value="proses" {{ old('status') == 'proses' ? 'selected' : '' }}>Proses</option>
                            <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="batal" {{ old('status') == 'batal' ? 'selected' : '' }}>Batal</option>
                        </select>
                    </div>
                </div>

                <h5 class="mt-5 mb-4 fw-bold text-dark border-bottom pb-2">Detail Item Servis</h5>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered table-hover align-middle custom-item-table" id="itemsTable">
                        <thead>
                            <tr class="table-light">
                                <th style="width: 25%;">Nama Barang (Servis)</th>
                                <th style="width: 15%;">Kemasan</th>
                                <th style="width: 8%;" class="text-center">Qty</th>
                                <th style="width: 15%;" class="text-end">Harga Satuan</th>
                                <th style="width: 10%;" class="text-center">Diskon (%)</th>
                                <th style="width: 12%;" class="text-end">Nilai Diskon</th>
                                <th style="width: 15%;" class="text-end">Jumlah</th>
                                <th style="width: 50px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(old('item_name'))
                                @foreach(old('item_name') as $key => $itemName)
                                <tr>
                                    <td><input type="text" class="form-control form-control-sm" name="item_name[]" placeholder="Nama Barang" value="{{ $old('item_name.' . $key) }}" required></td>
                                    <td>
                                        <select class="form-control form-control-sm" name="item_package[]" required>
                                            <option value="">Pilih Kemasan</option>
                                            <option value="Pcs" {{ $old('item_package.' . $key) == 'Pcs' ? 'selected' : '' }}>Pcs</option>
                                            <option value="Set" {{ $old('item_package.' . $key) == 'Set' ? 'selected' : '' }}>Set</option>
                                            <option value="Liter" {{ $old('item_package.' . $key) == 'Liter' ? 'selected' : '' }}>Liter</option>
                                            <option value="Botol" {{ $old('item_package.' . $key) == 'Botol' ? 'selected' : '' }}>Botol</option>
                                            <option value="Jasa" {{ $old('item_package.' . $key) == 'Jasa' ? 'selected' : '' }}>Jasa</option>
                                            <option value="Paket" {{ $old('item_package.' . $key) == 'Paket' ? 'selected' : '' }}>Paket</option>
                                            <option value="Unit" {{ $old('item_package.' . $key) == 'Unit' ? 'selected' : '' }}>Unit</option>
                                            <option value="Meter" {{ $old('item_package.' . $key) == 'Meter' ? 'selected' : '' }}>Meter</option>
                                            <option value="Jam" {{ $old('item_package.' . $key) == 'Jam' ? 'selected' : '' }}>Jam</option>
                                        </select>
                                    </td>
                                    <td><input type="number" class="form-control form-control-sm qty" name="item_qty[]" min="1" value="{{ $old('item_qty.' . $key, 1) }}" required></td>
                                    <td><input type="number" class="form-control form-control-sm price" name="item_price[]" min="0" value="{{ $old('item_price.' . $key, 0) }}" required></td>
                                    <td><input type="number" class="form-control form-control-sm discount" name="item_discount[]" min="0" max="100" value="{{ $old('item_discount.' . $key, 0) }}" required></td>
                                    <td><input type="text" class="form-control form-control-sm discount_value" name="item_discount_value[]" value="{{ number_format(floatval(str_replace(',', '.', old('item_discount_value.' . $key, $item->item_discount_value ?? 0))), 0, '', '.') }}" readonly></td>                                    <td><input type="text" class="form-control form-control-sm total" name="item_total[]" value="{{ number_format((float) str_replace(',', '.', $old('item_total.' . $key, 0)), 2, ',', '.') }}" readonly></td>
                                    <td class="text-center"><button type="button" class="btn btn-danger btn-sm remove-item rounded-pill"><i class="bi bi-x"></i></button></td>
                                </tr>
                                @endforeach
                            @else
                            <tr>
                                <td><input type="text" class="form-control form-control-sm" name="item_name[]" placeholder="Nama Barang" required></td>
                                <td>
                                    <select class="form-control form-control-sm" name="item_package[]" required>
                                        <option value="">Pilih Kemasan</option>
                                        <option value="Pcs">Pcs</option>
                                        <option value="Set">Set</option>
                                        <option value="Liter">Liter</option>
                                        <option value="Botol">Botol</option>
                                        <option value="Jasa">Jasa</option>
                                        <option value="Paket">Paket</option>
                                        <option value="Unit">Unit</option>
                                        <option value="Meter">Meter</option>
                                        <option value="Jam">Jam</option>
                                    </select>
                                </td>
                                <td><input type="number" class="form-control form-control-sm qty" name="item_qty[]" min="1" value="1" required></td>
                                <td><input type="number" class="form-control form-control-sm price" name="item_price[]" min="0" value="0" required></td>
                                <td><input type="number" class="form-control form-control-sm discount" name="item_discount[]" min="0" max="100" value="0" required></td>
                                <td><input type="text" class="form-control form-control-sm discount_value" name="item_discount_value[]" value="0.00" readonly></td>
                                <td><input type="text" class="form-control form-control-sm total" name="item_total[]" value="0.00" readonly></td>
                                <td class="text-center"><button type="button" class="btn btn-danger btn-sm remove-item rounded-pill"><i class="bi bi-x"></i></button></td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <button type="button" class="btn btn-outline-primary rounded-pill shadow-sm" id="addItem">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Item
                </button>

                <!-- Total Harga Keseluruhan -->
                <div class="mt-5 text-end">
                    <h5 class="fw-bold text-dark">Total Harga Servis: <span id="totalPrice" class="text-primary fs-3">Rp0,00</span></h5>
                    <small class="text-muted">Total biaya keseluruhan dari semua item servis.</small>
                </div>

                <!-- Button Simpan -->
                <div class="mt-5 d-flex justify-content-end gap-3">
                    <button type="submit" class="btn btn-success btn-lg px-4 rounded-pill shadow-sm animate__animated animate__pulse animate__infinite">
                        <i class="bi bi-save me-2"></i> Simpan Servis
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Add custom styles here for consistency */
    body {
        background-color: #f8f9fa;
        font-family: 'Poppins', sans-serif;
        color: #343a40;
    }

    .container-fluid.py-4 {
        padding-top: 2.5rem !important;
        padding-bottom: 2.5rem !important;
    }

    .form-label {
        font-size: 0.9rem;
        font-weight: 500;
        color: #555;
    }

    .form-control-lg, .form-select-lg {
        padding: 0.75rem 1.25rem;
        border-radius: 0.75rem !important;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        border: 1px solid #dee2e6; /* subtle border */
    }

    .form-control-lg:focus, .form-select-lg:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13,110,253,.25), 0 2px 8px rgba(0,0,0,0.1);
    }

    .bg-light-subtle {
        background-color: #f8f9fa !important;
    }

    /* Card Styling */
    .card {
        border-radius: 1rem !important;
        overflow: hidden;
        box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.09) !important;
    }

    /* Alert Styling */
    .alert-danger {
        background-color: #fef2f2;
        color: #721c24;
        border: 1px solid #f5c6cb;
        border-radius: 0.75rem;
        padding: 1.25rem 1.75rem;
    }
    .alert-danger .alert-heading {
        color: #dc3545;
        font-size: 1.1rem;
    }
    .alert-danger ul {
        padding-left: 25px;
    }
    .alert-danger li {
        margin-bottom: 5px;
    }

    /* Buttons */
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

    .btn-outline-secondary {
        border-color: #6c757d;
        color: #6c757d;
        transition: all 0.3s ease;
    }
    .btn-outline-secondary:hover {
        background-color: #6c757d;
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .btn-success {
        background: linear-gradient(45deg, #28a745, #218838);
        border: none;
        transition: all 0.3s ease;
        font-weight: 600;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 10px rgba(40, 167, 69, 0.2);
    }
    .btn-success:hover {
        background: linear-gradient(45deg, #218838, #28a745);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    }
    .animate__pulse {
        animation-duration: 2s; /* Increase animation duration */
    }

    /* Table for Items */
    .custom-item-table thead th {
        background-color: #f1f3f5;
        font-size: 0.85rem;
        font-weight: 600;
        color: #495057;
        white-space: nowrap;
        vertical-align: middle;
    }
    .custom-item-table tbody td {
        padding: 0.6rem;
        vertical-align: middle;
    }
    .custom-item-table .form-control-sm {
        height: calc(1.8rem + 2px); /* Adjust height for sm controls */
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
        border-radius: 0.4rem;
    }
    .custom-item-table .btn-danger.btn-sm {
        padding: 0.2rem 0.5rem;
        font-size: 0.75rem;
    }
    .custom-item-table tbody tr:hover {
        background-color: #f8f9fa;
    }

    /* Select2 Custom Styling */
    .select2-container--bootstrap-5 .select2-selection {
        border-radius: 0.75rem !important; /* Match form-control-lg */
        height: calc(2.8rem + 2px); /* Match form-control-lg height */
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
        display: flex; /* Use flexbox to vertically align content */
        align-items: center; /* Vertically center selected text */
        border: 1px solid #dee2e6; /* subtle border */
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    .select2-container--bootstrap-5.select2-container--focus .select2-selection,
    .select2-container--bootstrap-5.select2-container--open .select2-selection {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13,110,253,.25), 0 2px 8px rgba(0,0,0,0.1);
    }

    .select2-container--bootstrap-5 .select2-selection__arrow {
        height: 100%; /* Make arrow span full height */
        display: flex;
        align-items: center;
        padding-right: 0.75rem;
    }

    .select2-container--bootstrap-5 .select2-selection__placeholder {
        color: #6c757d;
        line-height: 1.5; /* Align with input text */
    }

    .select2-container--bootstrap-5 .select2-selection__rendered {
        color: #495057;
        line-height: 1.5; /* Align with input text */
        padding-left: 1.25rem; /* Match input padding */
    }

    .select2-container--bootstrap-5 .select2-dropdown {
        border-radius: 0.75rem;
        border: 1px solid #dee2e6;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        z-index: 1056; /* Ensure dropdown appears above other elements */
    }

    .select2-container--bootstrap-5 .select2-results__option {
        padding: 0.75rem 1.25rem;
        font-size: 0.9rem;
    }

    .select2-container--bootstrap-5 .select2-results__option--highlighted.select2-results__option--selectable {
        background-color: #0d6efd;
        color: white;
    }
    .select2-container--bootstrap-5 .select2-results__option--selected {
        background-color: #e9ecef;
        color: #495057;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .btn-lg {
            width: 100%;
            margin-bottom: 1rem;
        }
        .d-flex.justify-content-end.gap-3 {
            flex-direction: column;
            gap: 1rem;
        }
    }
</style>

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Include Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- Include Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Select2 for the mobil_id dropdown
        $('#mobil_id').select2({
            theme: "bootstrap-5", // Use Bootstrap 5 theme for Select2
            placeholder: 'Pilih mobil berdasarkan data mobil yang sudah didaftar...',
            allowClear: true // Option to clear selection
        });

        // Auto-fill Informasi Mobil when a car is selected
        $('#mobil_id').on('change', function() {
            var selectedOption = $(this).find(':selected');
            var tahunPembuatan = selectedOption.data('tahunpembuatan') || ''; // Get tahun_pembuatan
            var merekMobil = selectedOption.data('merek');
            var tipeMobil = selectedOption.data('tipe') || ''; // Get tipe_mobil
            var nomorPolisi = selectedOption.data('nomorpolisi'); // Get nomor_polisi

            // Construct the full string
            var fullMobilInfo = '';
            if (tahunPembuatan) {
                fullMobilInfo += tahunPembuatan + ' ';
            }
            if (merekMobil) {
                fullMobilInfo += merekMobil;
            }
            if (tipeMobil) {
                fullMobilInfo += ' ' + tipeMobil;
            }
            if (nomorPolisi) {
                fullMobilInfo += ' - ' + nomorPolisi;
            }

            $('#merek_mobil').val(fullMobilInfo.trim() || ''); // Set the combined string
        }).trigger('change'); // Trigger on load to populate if old('mobil_id') exists

        // Function to format currency
        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(number);
        }

        // Calculate total for an item row
        function calculateItemTotal(row) {
            const qty = parseFloat(row.querySelector(".qty").value) || 0;
            const price = parseFloat(row.querySelector(".price").value) || 0;
            const discountPercentage = parseFloat(row.querySelector(".discount").value) || 0;

            const subtotal = price * qty;
            const discountValue = (discountPercentage / 100) * subtotal;
            const total = subtotal - discountValue;

            row.querySelector(".discount_value").value = formatRupiah(discountValue); // Format for display
            row.querySelector(".total").value = formatRupiah(total); // Format for display

            return total;
        }

        // Calculate grand total for all items
        function calculateGrandTotal() {
            let grandTotal = 0;
            document.querySelectorAll("#itemsTable tbody tr").forEach(row => {
                const itemTotalString = row.querySelector(".total").value;
                // Remove 'Rp' and thousand separators, then parse
                const rawItemTotal = parseFloat(itemTotalString.replace(/[^0-9,-]+/g, "").replace(",", ".")) || 0;
                grandTotal += rawItemTotal;
            });
            document.getElementById("totalPrice").textContent = formatRupiah(grandTotal);
        }

        // Add new item row
        document.getElementById("addItem").addEventListener("click", function() {
            const newRowHtml = `
                <tr>
                    <td><input type="text" class="form-control form-control-sm" name="item_name[]" placeholder="Nama Barang" required></td>
                    <td>
                        <select class="form-control form-control-sm" name="item_package[]" required>
                            <option value="">Pilih Kemasan</option>
                            <option value="Pcs">Pcs</option>
                            <option value="Set">Set</option>
                            <option value="Liter">Liter</option>
                            <option value="Botol">Botol</option>
                            <option value="Jasa">Jasa</option>
                            <option value="Paket">Paket</option>
                            <option value="Unit">Unit</option>
                            <option value="Meter">Meter</option>
                            <option value="Jam">Jam</option>
                        </select>
                    </td>
                    <td><input type="number" class="form-control form-control-sm qty" name="item_qty[]" min="1" value="1" required></td>
                    <td><input type="number" class="form-control form-control-sm price" name="item_price[]" min="0" value="0" required></td>
                    <td><input type="number" class="form-control form-control-sm discount" name="item_discount[]" min="0" max="100" value="0" required></td>
                    <td><input type="text" class="form-control form-control-sm discount_value" name="item_discount_value[]" value="${formatRupiah(0)}" readonly></td>
                    <td><input type="text" class="form-control form-control-sm total" name="item_total[]" value="${formatRupiah(0)}" readonly></td>
                    <td class="text-center"><button type="button" class="btn btn-danger btn-sm remove-item rounded-pill"><i class="bi bi-x"></i></button></td>
                </tr>
            `;
            document.querySelector("#itemsTable tbody").insertAdjacentHTML('beforeend', newRowHtml);
            calculateGrandTotal(); // Recalculate after adding
        });

        // Event delegation for removing item and recalculating totals
        document.querySelector("#itemsTable").addEventListener("click", function(e) {
            if (e.target.classList.contains("remove-item") || e.target.closest(".remove-item")) {
                const rowToRemove = e.target.closest("tr");
                if (document.querySelectorAll("#itemsTable tbody tr").length > 1) { // Prevent removing last row
                    rowToRemove.remove();
                    calculateGrandTotal();
                } else {
                    alert("Minimal harus ada satu item servis."); // Use a more friendly custom modal if needed
                }
            }
        });

        // Event delegation for input changes in item rows
        document.querySelector("#itemsTable").addEventListener("input", function(e) {
            const target = e.target;
            if (target.classList.contains("qty") || target.classList.contains("price") || target.classList.contains("discount")) {
                const row = target.closest("tr");
                calculateItemTotal(row);
                calculateGrandTotal();
            }
        });

        // Initial calculation on page load (important for old() values)
        document.querySelectorAll("#itemsTable tbody tr").forEach(row => {
            calculateItemTotal(row);
        });
        calculateGrandTotal(); // Ensure grand total is correct on load
    });
</script>
@endsection
