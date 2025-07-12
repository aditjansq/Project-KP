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
        }
        table, th, td {
            border: 1px solid #000; /* Border tabel data utama diubah menjadi hitam */
        }
        th, td {
            padding: 2px 3px; /* Padding sedikit disesuaikan */
            text-align: left;
            /* word-wrap: break-word; */ /* Hapus atau override ini jika ingin mencegah wrapping sama sekali */
            white-space: nowrap; /* Mencegah teks membungkus baris baru */
            overflow: hidden; /* Menyembunyikan teks yang melebihi lebar kolom */
            text-overflow: ellipsis; /* Menambahkan elipsis (...) jika teks terlalu panjang */
            vertical-align: top;
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

        /* Penyesuaian lebar kolom spesifik (opsional, sesuaikan nilai sesuai kebutuhan) */
        table th:nth-child(1), table td:nth-child(1) { width: 3%; } /* No. */
        table th:nth-child(2), table td:nth-child(2) { width: 8%; } /* Kode Trx */
        table th:nth-child(3), table td:nth-child(3) { width: 10%; } /* Merek */
        table th:nth-child(4), table td:nth-child(4) { width: 10%; } /* Type */
        table th:nth-child(5), table td:nth-child(5) { width: 5%; } /* Tahun */
        table th:nth-child(6), table td:nth-child(6) { width: 7%; } /* Warna */
        table th:nth-child(7), table td:nth-child(7) { width: 10%; } /* No. Polisi */
        table th:nth-child(8), table td:nth-child(8) { width: 8%; } /* Tgl Beli */
        table th:nth-child(9), table td:nth-child(9) { width: 11%; text-align: right;} /* Harga Dibeli */
        table th:nth-child(10), table td:nth-child(10) { width: 11%; text-align: right;} /* Total Servis */
        table th:nth-child(11), table td:nth-child(11) { width: 12%; text-align: right;} /* Modal Mobil */

        /* Override untuk footer jika perlu penyesuaian lebar khusus */
        tfoot th:nth-child(1) { width: auto; } /* Untuk colspan */
        tfoot th:nth-child(2) { width: 11%; } /* Harga Dibeli (Total) */
        tfoot th:nth-child(3) { width: 11%; } /* Total Servis (Total) */
        tfoot th:nth-child(4) { width: 12%; } /* Modal Mobil (Total) */
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

    @if ($mobilDibeli->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Kode Trx</th>
                    <th>Merek</th>
                    <th>Type</th>
                    <th>Tahun</th>
                    <th>Warna</th>
                    <th>No. Polisi</th>
                    <th>Tgl Beli</th>
                    <th>Harga Dibeli</th>
                    <th>Total Servis</th>
                    <th>Modal Mobil</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($mobilDibeli as $index => $pembelian)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $pembelian->kode_transaksi ?? 'N/A' }}</td>
                    <td>{{ $pembelian->mobil->merek_mobil ?? 'N/A' }}</td>
                    <td>{{ $pembelian->mobil->tipe_mobil ?? 'N/A' }}</td>
                    <td>{{ $pembelian->mobil->tahun_pembuatan ?? 'N/A' }}</td>
                    <td>{{ $pembelian->mobil->warna_mobil ?? 'N/A' }}</td>
                    <td>{{ $pembelian->mobil->nomor_polisi ?? 'N/A' }}</td>
                    <td>{{ \Carbon\Carbon::parse($pembelian->tanggal_transaksi)->format('d/m/Y') }}</td>
                    <td class="text-end">Rp {{ number_format($pembelian->harga_beli_mobil_final, 0, ',', '.') }}</td>
                    <td class="text-end">
                        Rp {{ number_format($pembelian->mobil->servis->sum('total_harga'), 0, ',', '.') }}
                    </td>
                    <td class="text-end">
                        @php
                            $totalBiayaServis = $pembelian->mobil->servis->sum('total_biaya_keseluruhan');
                            $modalMobil = $pembelian->harga_beli_mobil_final + $totalBiayaServis;
                        @endphp
                        Rp {{ number_format($modalMobil, 0, ',', '.') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <th colspan="8" class="text-end">Total Pembelian:</th>
                    <th class="text-end">Rp {{ number_format($mobilDibeli->sum('harga_beli_mobil_final'), 0, ',', '.') }}</th>
                    <th class="text-end">
                        Rp {{ number_format($mobilDibeli->sum(function($p) { return $p->mobil->servis->sum('total_harga'); }), 0, ',', '.') }}
                    </th>
                    <th class="text-end">
                        @php
                            $totalModalKeseluruhan = $mobilDibeli->sum(function($p) {
                                $totalBiayaServis = $p->mobil->servis->sum('total_biaya_keseluruhan');
                                return $p->harga_beli_mobil_final + $totalBiayaServis;
                            });
                        @endphp
                        Rp {{ number_format($totalModalKeseluruhan, 0, ',', '.') }}
                    </th>
                </tr>
            </tfoot>
        </table>
    @else
        <p class="text-center">Tidak ada data mobil dibeli yang tersedia untuk laporan ini.</p>
    @endif

    <div class="footer-note">
        Dicetak pada: {{ date('d F Y H:i:s') }}
    </div>
</body>
</html>
