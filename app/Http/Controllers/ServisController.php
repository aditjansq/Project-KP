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
     */
    public function index()
    {
        $servis = Servis::with(['mobil', 'items', 'mobil.transaksiPembelian'])
                        ->orderBy('tanggal_servis', 'desc')
                        ->orderBy('id', 'desc')
                        ->paginate(10);

        return view('servis.index', compact('servis'));
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

            // Validasi untuk item-item, pastikan nama field sesuai dengan input di Blade
            'item_name' => 'required|array|min:1',
            'item_name.*' => 'required|string|max:255',
            'item_package' => 'required|array|min:1',
            'item_package.*' => 'required|string',
            'item_qty' => 'required|array|min:1',
            'item_qty.*' => 'required|integer|min:1',
            'item_price' => 'required|array|min:1',
            'item_price.*' => 'required|numeric|min:0',
            'item_discount' => 'required|array|min:1',
            'item_discount.*' => 'required|numeric|min:0|max:100',
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
                $qty = $request->item_qty[$key]; // Qty sudah divalidasi sebagai integer

                // Untuk price, cek apakah nilainya bilangan bulat, jika ya, cast ke integer
                $price = $request->item_price[$key];
                if (is_numeric($price) && fmod($price, 1) == 0) {
                    $price = (int) $price;
                } else {
                    $price = (float) $price;
                }

                // Untuk discountPercentage, cek apakah nilainya bilangan bulat, jika ya, cast ke integer
                $discountPercentage = $request->item_discount[$key];
                if (is_numeric($discountPercentage) && fmod($discountPercentage, 1) == 0) {
                    $discountPercentage = (int) $discountPercentage;
                } else {
                    $discountPercentage = (float) $discountPercentage;
                }

                $subtotal = $price * $qty;
                $discountValue = ($discountPercentage / 100) * $subtotal;
                $itemTotal = $subtotal - $discountValue;

                // Gunakan nama kolom yang sesuai dengan skema tabel 'items' Anda
                $servis->items()->create([
                    'item_name' => $itemName,
                    'item_package' => $request->item_package[$key],
                    'item_qty' => $qty,
                    'item_price' => $price,
                    'item_discount' => $discountPercentage,
                    'item_discount_value' => $discountValue,
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
        // Ini memastikan data tersedia di respons JSON
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
            'item_package' => 'required|array|min:1',
            'item_package.*' => 'required|string',
            'item_qty' => 'required|array|min:1',
            'item_qty.*' => 'required|integer|min:1',
            'item_price' => 'required|array|min:1',
            'item_price.*' => 'required|numeric|min:0',
            'item_discount' => 'required|array|min:1',
            'item_discount.*' => 'required|numeric|min:0|max:100',
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
                $qty = $request->item_qty[$key]; // Qty sudah divalidasi sebagai integer

                // Untuk price, cek apakah nilainya bilangan bulat, jika ya, cast ke integer
                $price = $request->item_price[$key];
                if (is_numeric($price) && fmod($price, 1) == 0) {
                    $price = (int) $price;
                } else {
                    $price = (float) $price;
                }

                // Untuk discountPercentage, cek apakah nilainya bilangan bulat, jika ya, cast ke integer
                $discountPercentage = $request->item_discount[$key];
                if (is_numeric($discountPercentage) && fmod($discountPercentage, 1) == 0) {
                    $discountPercentage = (int) $discountPercentage;
                } else {
                    $discountPercentage = (float) $discountPercentage;
                }

                $subtotal = $price * $qty;
                $discountValue = ($discountPercentage / 100) * $subtotal;
                $itemTotal = $subtotal - $discountValue;

                // Gunakan nama kolom yang sesuai dengan skema tabel 'items' Anda
                $servis->items()->create([
                    'item_name' => $itemName,
                    'item_package' => $request->item_package[$key],
                    'item_qty' => $qty,
                    'item_price' => $price,
                    'item_discount' => $discountPercentage,
                    'item_discount_value' => $discountValue,
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
