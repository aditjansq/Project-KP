<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 8pt; /* Ukuran font keseluruhan */
            margin: 20mm; /* Margin halaman */
            color: #000; /* Mengubah warna teks default menjadi hitam */
        }

        /* --- Header (Kop) Section --- */
        .header-container {
            width: 100%;
            text-align: center; /* Memastikan tabel kop berada di tengah */
            margin-bottom: 0; /* Jarak diatur oleh garis di bawahnya */
        }
        .company-header-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 auto; /* Memastikan tabel ini sendiri berada di tengah halaman */
            border: none; /* Hapus border dari tabel kop itu sendiri */
        }
        .company-header-table td {
            padding: 0;
            vertical-align: bottom; /* Konten sel diatur ke bawah, agar mepet ke garis */
            border: none; /* Hapus border dari sel-sel di dalam tabel kop */
        }

        /* Company Info (Name, Tagline, Address, Contact) */
        .company-info-cell {
            width: 100%; /* Lebar kolom informasi perusahaan diatur 100% karena logo dihapus */
            text-align: center; /* Teks informasi perusahaan di tengah */
        }
        .company-info-cell h1 {
            margin: 0;
            font-size: 14pt; /* Ukuran nama perusahaan */
            color: #000;
            line-height: 1.1;
        }
        .company-info-cell h2 {
            margin: 0;
            font-size: 8.5pt; /* Ukuran tagline */
            color: #000;
            font-weight: normal;
            line-height: 1.1;
        }
        .company-info-cell p {
            margin: 0;
            font-size: 7pt; /* Ukuran alamat dan kontak */
            color: #000;
            line-height: 1.1;
        }

        /* --- Garis Pemisah Kop --- */
        .header-border {
            border-bottom: 2px solid #000;
            margin-top: 5px; /* Jarak antara kop dan garis diperkecil */
            margin-bottom: 15px; /* Jarak antara garis dan detail laporan */
            clear: both;
        }

        /* --- Report Details (Di bawah garis) --- */
        .report-details-below-border {
            text-align: center; /* Detail laporan di tengah */
            margin-top: 0; /* Jarak sudah diatur oleh margin-bottom header-border */
            margin-bottom: 20px; /* Jarak ke tabel data utama */
        }
        .report-details-below-border .report-title {
            margin: 0;
            font-size: 11pt; /* Ukuran judul laporan */
            color: #000;
            font-weight: bold;
            line-height: 1.2;
        }
        .report-details-below-border .report-date {
            margin: 0;
            font-size: 8pt; /* Ukuran tanggal laporan */
            color: #000;
            line-height: 1.2;
        }

        /* --- Main Data Table --- */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 6.5pt; /* Sedikit dikecilkan untuk memberikan lebih banyak ruang */
            table-layout: fixed; /* Menambahkan ini untuk lebar kolom yang tetap */
            margin: 0 auto; /* Menambahkan ini untuk memusatkan tabel */
        }
        table, th, td {
            border: 1px solid #000; /* Border tabel data utama diubah menjadi hitam */
            padding: 2px 3px; /* Padding sedikit disesuaikan */
            text-align: left;
            vertical-align: top;
            /* white-space, overflow, text-overflow dihapus dari sini agar teks bisa wrap secara default */
        }
        th {
            background-color: #e0e0e0;
            font-weight: bold;
        }
        .text-end {
            text-align: right;
        }
        .total-row th, .total-row td {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .footer-note {
            margin-top: 30px;
            text-align: right;
            font-size: 6pt;
            color: #000;
        }

        /* Penyesuaian lebar kolom spesifik untuk mobil_terjual_pdf.blade.php */
        /* Total lebar kolom sekarang 100% */
        table th:nth-child(1), table td:nth-child(1) { width: 3%; } /* No. */
        table th:nth-child(2), table td:nth-child(2) { width: 8%; } /* Kode Trx */
        table th:nth-child(3), table td:nth-child(3) { width: 9%; } /* Merek */
        table th:nth-child(4), table td:nth-child(4) { width: 9%; } /* Type */
        table th:nth-child(5), table td:nth-child(5) { width: 5%; } /* Tahun */
        table th:nth-child(6), table td:nth-child(6) { width: 7%; } /* No. Polisi (Dikurangi 1%) */
        table th:nth-child(7), table td:nth-child(7) { width: 12%; } /* Pembeli */

        /* Kolom-kolom ini TIDAK BOLEH membungkus */
        table th:nth-child(8), table td:nth-child(8) { /* Tgl Jual */
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            width: 10%; /* Dilebarkan menjadi 10% */
        }
        table th:nth-child(9), table td:nth-child(9) { /* Modal Servis */
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            width: 13%; /* Tetap 13% */
        }
        table th:nth-child(10), table td:nth-child(10) { /* Harga Jual */
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            width: 13%; /* Tetap 13% */
        }
        table th:nth-child(11), table td:nth-child(11) { /* Profit */
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            width: 11%; /* Dikurangi menjadi 11% */
        }

        /* Override untuk footer jika perlu penyesuaian lebar khusus */
        tfoot th, tfoot td {
            white-space: normal; /* Allow text to wrap for footer cells */
            overflow: visible; /* Ensure content is visible for footer cells */
            text-overflow: clip; /* Prevent ellipsis for footer cells */
        }
        tfoot th:nth-child(1) {
            width: auto; /* Untuk colspan */
            text-align: right; /* Ensure "Total:" is right-aligned */
        }
        /* Menyesuaikan lebar kolom footer agar cocok dengan header tabel */
        tfoot th:nth-child(2) { /* Modal Servis (Total) */
            width: 13%;
            text-align: right;
        }
        tfoot th:nth-child(3) { /* Harga Jual (Total) */
            width: 13%;
            text-align: right;
        }
        tfoot th:nth-child(4) { /* Profit (Total) */
            width: 11%; /* Dikurangi menjadi 11% */
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header-container">
        <table class="company-header-table">
            <tr>
                <td class="company-info-cell">
                    <h1>CENTRA MOBILINDO</h1>
                    <h2>"JUAL BELI MOBIL BARU/BEKAS"</h2>
                    <p>Jl. Residen Abdul Rozak No. 2182 L-N Simpang Celentang - Palembang</p>
                    <p>Telp. 0821 7953 5370 / 0821 7944 8055</p>
                </td>
            </tr>
        </table>
    </div>
    <div class="header-border"></div> {{-- Garis pemisah kop --}}

    <div class="report-details-below-border">
        <h3 class="report-title">{{ $title }}</h3>
        <p class="report-date">Tanggal Laporan: {{ $date }}</p>
    </div>

    @if ($mobilTerjual->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Kode Trx</th>
                    <th>Merek</th>
                    <th>Type</th>
                    <th>Tahun</th>
                    <th>No. Polisi</th>
                    <th>Pembeli</th>
                    <th>Tgl Jual</th>
                    <th>Modal Servis</th>
                    <th>Harga Jual</th>
                    <th>Profit</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($mobilTerjual as $index => $penjualan)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $penjualan->kode_transaksi ?? 'N/A' }}</td>
                    <td>{{ $penjualan->mobil->merek_mobil ?? 'N/A' }}</td>
                    <td>{{ $penjualan->mobil->tipe_mobil ?? 'N/A' }}</td>
                    <td>{{ $penjualan->mobil->tahun_pembuatan ?? 'N/A' }}</td>
                    <td>{{ $penjualan->mobil->nomor_polisi ?? 'N/A' }}</td>
                    <td>{{ $penjualan->pembeli->nama ?? 'Umum' }}</td>
                    <td>{{ \Carbon\Carbon::parse($penjualan->tanggal_transaksi)->format('d/m/Y') }}</td>
                    <td class="text-end">
                        @php
                            $totalBiayaServis = $penjualan->mobil->servis->sum('total_biaya_keseluruhan');
                        @endphp
                        Rp {{ number_format($totalBiayaServis, 0, ',', '.') }}
                    </td>
                    <td class="text-end">Rp {{ number_format($penjualan->harga_negosiasi, 0, ',', '.') }}</td>
                    <td class="text-end">
                        @php
                            $profit = $penjualan->harga_negosiasi - $totalBiayaServis;
                        @endphp
                        Rp {{ number_format($profit, 0, ',', '.') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <th colspan="8" class="text-end">Total:</th>
                    <th class="text-end">
                        @php
                            $totalModalServis = $mobilTerjual->sum(function($item) {
                                return $item->mobil->servis->sum('total_biaya_keseluruhan');
                            });
                        @endphp
                        Rp {{ number_format($totalModalServis, 0, ',', '.') }}
                    </th>
                    <th class="text-end">Rp {{ number_format($mobilTerjual->sum('harga_negosiasi'), 0, ',', '.') }}</th>
                    <th class="text-end">
                        @php
                            $totalProfit = $mobilTerjual->sum(function($item) {
                                $totalBiayaServis = $item->mobil->servis->sum('total_biaya_keseluruhan');
                                return $item->harga_negosiasi - $totalBiayaServis;
                            });
                        @endphp
                        Rp {{ number_format($totalProfit, 0, ',', '.') }}
                    </th>
                </tr>
            </tfoot>
        </table>
    @else
        <p class="text-center">Tidak ada data mobil terjual yang tersedia untuk laporan ini.</p>
    @endif

    <div class="footer-note">
        Dicetak pada: {{ date('d F Y H:i:s') }}
    </div>
</body>
</html>
