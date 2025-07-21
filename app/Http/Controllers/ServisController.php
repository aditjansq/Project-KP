<?php

namespace App\Http\Controllers;

use App\Models\Servis;
use App\Models\Item;
use App\Models\Mobil;
use App\Models\Transaksi;
use App\Models\TransaksiPembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServisController extends Controller
{
    /**
     * Menampilkan daftar semua servis.
     * Mengambil data servis dengan eager loading untuk relasi 'mobil' dan 'items',
     * mengurutkannya berdasarkan tanggal servis terbaru, dan melakukan paginasi.
     * Menerapkan filter dari request URL.
     */
    public function index(Request $request)
    {
        $query = Servis::with(['mobil', 'items', 'mobil.transaksiPembelian']);

        // Filter berdasarkan pencarian umum
        if ($request->has('search') && $request->input('search') != '') {
            $searchTerm = strtolower($request->input('search'));
            $query->where(function ($q) use ($searchTerm) {
                $q->where(DB::raw('lower(kode_servis)'), 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('mobil', function ($qMobil) use ($searchTerm) {
                      $qMobil->where(DB::raw('lower(nomor_polisi)'), 'like', '%' . $searchTerm . '%')
                             ->orWhere(DB::raw('lower(merek_mobil)'), 'like', '%' . $searchTerm . '%')
                             ->orWhere(DB::raw('lower(tipe_mobil)'), 'like', '%' . $searchTerm . '%');
                  })
                  ->orWhere(DB::raw('lower(status)'), 'like', '%' . $searchTerm . '%'); // Tambahkan pencarian status juga
            });
        }

        // Filter berdasarkan status
        if ($request->has('status') && $request->input('status') != '') {
            $status = strtolower($request->input('status'));
            if ($status === 'null') { // Menangani status 'Tidak Ada'
                 $query->whereNull('status');
            } else {
                 $query->where(DB::raw('lower(status)'), $status);
            }
        }

        // Filter berdasarkan tanggal servis (start_date)
        if ($request->has('start_date') && $request->input('start_date') != '') {
            $query->whereDate('tanggal_servis', '>=', $request->input('start_date'));
        }

        // Filter berdasarkan tanggal servis (end_date)
        if ($request->has('end_date') && $request->input('end_date') != '') {
            $query->whereDate('tanggal_servis', '<=', $request->input('end_date'));
        }

        // Filter berdasarkan tahun servis
        if ($request->has('tahun_servis') && $request->input('tahun_servis') != '') {
            $query->whereYear('tanggal_servis', $request->input('tahun_servis'));
        }

        // Filter berdasarkan merek mobil
        if ($request->has('mobil_merek') && $request->input('mobil_merek') != '') {
            $mobilMerek = strtolower($request->input('mobil_merek'));
            $query->whereHas('mobil', function ($qMobil) use ($mobilMerek) {
                $qMobil->where(DB::raw('lower(merek_mobil)'), $mobilMerek);
            });
        }

        // Filter berdasarkan tipe mobil
        if ($request->has('mobil_tipe') && $request->input('mobil_tipe') != '') {
            $mobilTipe = strtolower($request->input('mobil_tipe'));
            $query->whereHas('mobil', function ($qMobil) use ($mobilTipe) {
                $qMobil->where(DB::raw('lower(tipe_mobil)'), $mobilTipe);
            });
        }

        // Filter berdasarkan nomor polisi mobil
        if ($request->has('mobil_nopol') && $request->input('mobil_nopol') != '') {
            $mobilNopol = strtolower($request->input('mobil_nopol'));
            $query->whereHas('mobil', function ($qMobil) use ($mobilNopol) {
                $qMobil->where(DB::raw('lower(nomor_polisi)'), 'like', '%' . $mobilNopol . '%');
            });
        }

        $servis = $query->orderBy('tanggal_servis', 'desc')
                        ->orderBy('id', 'desc')
                        ->paginate(10)
                        ->appends($request->except('page')); // Penting: Pertahankan filter saat paginasi

        // Dapatkan semua merek dan tipe mobil unik untuk filter dropdown
        $allMerek = Mobil::distinct()->pluck('merek_mobil')->filter()->sort()->map(fn($m) => strtolower($m));
        $allTipe = Mobil::distinct()->pluck('tipe_mobil')->filter()->sort()->map(fn($t) => strtolower($t));

        return view('servis.index', compact('servis', 'allMerek', 'allTipe'));
    }

    /**
     * Menampilkan formulir untuk membuat servis baru.
     * Mengambil data mobil yang tersedia dan menghasilkan kode servis otomatis.
     */
    public function create()
    {
        $mobils = Mobil::all();

        $currentYear = date('Y');
        $prefix = 'SV-' . $currentYear . '-';

        $lastServis = Servis::where('kode_servis', 'like', $prefix . '%')
                            ->orderBy('kode_servis', 'desc')
                            ->first();

        $newSequence = 1;
        if ($lastServis) {
            $lastSequence = (int) substr($lastServis->kode_servis, -3);
            $newSequence = $lastSequence + 1;
        }

        $kode_servis = $prefix . str_pad($newSequence, 3, '0', STR_PAD_LEFT);

        return view('servis.create', compact('mobils', 'kode_servis'));
    }

    /**
     * Menyimpan servis baru ke database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'mobil_id' => 'required|exists:mobils,id',
            'tanggal_servis' => 'required|date|before_or_equal:today|after_or_equal:' . date('Y-m-d', strtotime('-1 month')),
            'kode_servis' => 'required|string|unique:servis,kode_servis',
            'metode_pembayaran' => 'required|string|in:Transfer Bank,Cash',
            'status' => 'nullable|string|in:proses,selesai,batal',
            'keterangan' => 'nullable|string',

            // Validasi untuk item-item
            'item_name' => 'required|array|min:1',
            'item_name.*' => 'required|string|max:255',
            'item_qty' => 'required|array|min:1',
            'item_qty.*' => 'required|integer|min:1',
            'item_price' => 'required|array|min:1',
            'item_price.*' => 'required|numeric|min:0',
        ]);

        return DB::transaction(function () use ($request) {
            $totalHargaServis = 0;

            // Buat entri servis utama
            $servis = Servis::create([
                'mobil_id' => $request->mobil_id,
                'tanggal_servis' => $request->tanggal_servis,
                'kode_servis' => $request->kode_servis,
                'metode_pembayaran' => $request->metode_pembayaran,
                'status' => $request->status ?? 'proses',
                'keterangan' => $request->keterangan,
                'total_harga' => 0,
                'total_biaya_keseluruhan' => 0,
            ]);

            // Tambahkan item-item servis
            foreach ($request->item_name as $key => $itemName) {
                $qty = $request->item_qty[$key];

                $price = $request->item_price[$key];
                if (is_numeric($price) && fmod($price, 1) == 0) {
                    $price = (int) $price;
                } else {
                    $price = (float) $price;
                }

                $itemTotal = $price * $qty;

                $servis->items()->create([
                    'item_name' => $itemName,
                    'item_package' => '',
                    'item_qty' => $qty,
                    'item_price' => $price,
                    'item_discount' => 0, // <--- DITAMBAHKAN: Memberikan nilai default 0
                    'item_discount_value' => 0, // <--- DITAMBAHKAN: Memberikan nilai default 0
                    'item_total' => $itemTotal,
                    'service_date' => $request->tanggal_servis,
                ]);
                $totalHargaServis += $itemTotal;
            }

            // Update total harga ke tabel servis setelah semua item ditambahkan
            $servis->update(['total_harga' => $totalHargaServis]);

            // Dapatkan harga_beli_mobil_final dari transaksi pembelian terakhir untuk mobil ini
            $hargaBeliMobilFinal = 0;
            $latestTransaksiPembelian = TransaksiPembelian::where('mobil_id', $servis->mobil_id)
                                                            ->latest()
                                                            ->first();

            if ($latestTransaksiPembelian) {
                $hargaBeliMobilFinal = $latestTransaksiPembelian->harga_beli_mobil_final;
            }

            // Hitung total_biaya_keseluruhan
            $totalBiayaKeseluruhan = $servis->total_harga + $hargaBeliMobilFinal;

            // Update total_biaya_keseluruhan ke tabel servis
            $servis->update(['total_biaya_keseluruhan' => $totalBiayaKeseluruhan]);

            return redirect()->route('servis.index')->with('success', 'Servis berhasil ditambahkan.');
        });
    }

    /**
     * Menampilkan detail servis tertentu.
     *
     * @param  \App\Models\Servis  $servis
     * @return \Illuminate\View\View
     */
    public function show(Servis $servis)
    {
        $servis->load(['mobil', 'items', 'mobil.transaksiPembelian']);

        // Hitung total_harga_mobil_dibeli dari relasi transaksiPembelian yang sudah dimuat
        $totalHargaMobilDibeli = optional($servis->mobil->transaksiPembelian)->sum('harga_beli_mobil_final') ?? 0;

        // Tambahkan atribut ini ke objek servis yang akan di-JSON-kan
        $servis->setAttribute('total_harga_mobil_dibeli', $totalHargaMobilDibeli);

        // Mengembalikan data sebagai JSON
        return response()->json($servis);
    }

    /**
     * Menampilkan formulir untuk mengedit servis tertentu.
     *
     * @param  \App\Models\Servis  $servis
     * @return \Illuminate\View\View
     */
    public function edit(Servis $servis)
    {
        $mobils = Mobil::all();
        $servis->load('items');
        return view('servis.edit', compact('servis', 'mobils'));
    }

    /**
     * Memperbarui servis tertentu di database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Servis  $servis
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Servis $servis)
    {
        $request->validate([
            'mobil_id' => 'required|exists:mobils,id',
            'tanggal_servis' => 'required|date|before_or_equal:today|after_or_equal:' . date('Y-m-d', strtotime('-1 month')),
            'kode_servis' => 'required|string|unique:servis,kode_servis,' . $servis->id,
            'metode_pembayaran' => 'required|string|in:Transfer Bank,Cash',
            'status' => 'nullable|string|in:proses,selesai,batal',
            'keterangan' => 'nullable|string',

            'item_name' => 'required|array|min:1',
            'item_name.*' => 'required|string|max:255',
            'item_qty' => 'required|array|min:1',
            'item_qty.*' => 'required|integer|min:1',
            'item_price' => 'required|array|min:1',
            'item_price.*' => 'required|numeric|min:0',
        ]);

        return DB::transaction(function () use ($request, $servis) {
            // Perbarui data servis utama
            $servis->update([
                'mobil_id' => $request->mobil_id,
                'tanggal_servis' => $request->tanggal_servis,
                'kode_servis' => $request->kode_servis,
                'metode_pembayaran' => $request->metode_pembayaran,
                'status' => $request->status ?? 'proses',
                'keterangan' => $request->keterangan,
            ]);

            // Hapus item-item lama
            $servis->items()->delete();
            $totalHargaServis = 0;

            // Tambahkan item-item baru
            foreach ($request->item_name as $key => $itemName) {
                $qty = $request->item_qty[$key];

                $price = $request->item_price[$key];
                if (is_numeric($price) && fmod($price, 1) == 0) {
                    $price = (int) $price;
                } else {
                    $price = (float) $price;
                }

                $itemTotal = $price * $qty;

                $servis->items()->create([
                    'item_name' => $itemName,
                    'item_package' => '',
                    'item_qty' => $qty,
                    'item_price' => $price,
                    'item_discount' => 0, // <--- DITAMBAHKAN: Memberikan nilai default 0
                    'item_discount_value' => 0, // <--- DITAMBAHKAN: Memberikan nilai default 0
                    'item_total' => $itemTotal,
                    'service_date' => $request->tanggal_servis,
                ]);
                $totalHargaServis += $itemTotal;
            }

            // Update total harga ke tabel servis setelah semua item diperbarui
            $servis->update(['total_harga' => $totalHargaServis]);

            // Dapatkan mobil terkait untuk mengakses transaksi pembelian
            $mobil = Mobil::find($request->mobil_id);

            $hargaBeliMobilFinal = 0;
            if ($mobil) {
                $latestTransaksiPembelian = TransaksiPembelian::where('mobil_id', $mobil->id)
                                                                ->latest()
                                                                ->first();
                if ($latestTransaksiPembelian) {
                    $hargaBeliMobilFinal = $latestTransaksiPembelian->harga_beli_mobil_final;
                }
            }

            // Hitung total_biaya_keseluruhan
            $totalBiayaKeseluruhan = $servis->total_harga + $hargaBeliMobilFinal;

            // Update total_biaya_keseluruhan ke tabel servis
            $servis->update(['total_biaya_keseluruhan' => $totalBiayaKeseluruhan]);

            return redirect()->route('servis.index')->with('success', 'Servis berhasil diperbarui.');
        });
    }

    /**
     * Menghapus servis tertentu dari database.
     *
     * @param  \App\Models\Servis  $servis
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Servis $servis)
    {
        return DB::transaction(function () use ($servis) {
            $servis->items()->delete();
            $servis->delete();

            return redirect()->route('servis.index')->with('success', 'Servis berhasil dihapus.');
        });
    }
}
