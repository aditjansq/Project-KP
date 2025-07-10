<?php

namespace App\Http\Controllers;

use App\Models\Servis;
use App\Models\Item;
use App\Models\Mobil;
use Illuminate\Http\Request;

class ServisController extends Controller
{
    /**
     * Menampilkan daftar semua servis.
     * Mengambil data servis dengan eager loading untuk relasi 'mobil' dan 'items',
     * mengurutkannya berdasarkan tanggal servis terbaru, dan melakukan paginasi.
     */
    public function index()
    {
        $servis = Servis::with('mobil', 'items')
                        ->orderBy('tanggal_servis', 'desc') // Mengurutkan berdasarkan tanggal_servis terbaru
                        ->orderBy('id', 'desc') // Menambahkan pengurutan berdasarkan ID terbaru sebagai tie-breaker
                        ->paginate(10);

        return view('servis.index', compact('servis'));
    }

    /**
     * Menampilkan formulir untuk membuat servis baru.
     * Mengambil data mobil yang tersedia dan menghasilkan kode servis otomatis.
     */
    public function create()
    {
        $mobils = Mobil::all(); // Mengambil data mobil

        // Logika baru untuk menghasilkan kode servis otomatis yang unik per tahun
        $currentYear = date('Y');
        $prefix = 'SV-' . $currentYear . '-';

        // Cari kode servis terakhir untuk tahun ini
        $lastServis = Servis::where('kode_servis', 'like', $prefix . '%')
                            ->orderBy('kode_servis', 'desc') // Urutkan berdasarkan kode_servis untuk mendapatkan nomor tertinggi
                            ->first();

        $lastNumber = 0;
        if ($lastServis) {
            // Ekstrak bagian angka dari kode servis (misal: dari 'SV-2025-0003' ambil '0003')
            $lastNumber = (int) substr($lastServis->kode_servis, -4);
        }

        $newNumber = $lastNumber + 1;
        $kode_servis = $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT); // Format dengan 4 digit

        return view('servis.create', compact('mobils', 'kode_servis'));
    }

    /**
     * Menyimpan servis baru ke database.
     * Melakukan validasi input, mencari ID mobil, menyimpan data servis,
     * menyimpan item-item terkait, dan menghitung total harga.
     */
    public function store(Request $request)
    {
        // Validasi input servis dan item
        $request->validate([
            'mobil_id' => 'required|exists:mobils,kode_mobil',
            'tanggal_servis' => 'required|date',
            'metode_pembayaran' => 'required|string',
            'status' => 'nullable|in:proses,selesai,batal', // Validasi kolom status baru
            'kode_servis' => 'required|string|unique:servis,kode_servis', // Tambahkan validasi unique
            'item_name.*' => 'required|string',
            'item_package.*' => 'nullable|string',
            'item_qty.*' => 'required|numeric|min:1',
            'item_price.*' => 'required|numeric|min:0',
            'item_discount.*' => 'nullable|numeric|min:0|max:100',
        ]);

        // Cari ID mobil berdasarkan kode mobil
        $mobil = Mobil::where('kode_mobil', $request->mobil_id)->first();

        if (!$mobil) {
            return redirect()->back()->with('error', 'Mobil tidak ditemukan.')->withInput();
        }

        // Menyimpan data servis awal
        $servis = Servis::create([
            'mobil_id' => $mobil->id,
            'kode_servis' => $request->kode_servis,
            'tanggal_servis' => $request->tanggal_servis,
            'metode_pembayaran' => $request->metode_pembayaran,
            'total_harga' => 0, // Menggunakan total_harga
            'status' => $request->status, // Menggunakan kolom 'status' baru dari request
        ]);

        $totalHargaServis = 0; // Menggunakan totalHargaServis

        // Menyimpan item-item terkait dan menghitung total harga
        foreach ($request->item_name as $key => $itemName) {
            $qty = (float)$request->item_qty[$key];
            $price = (float)$request->item_price[$key];
            $discountPercentage = (float)$request->item_discount[$key];

            $itemDiscountValue = ($discountPercentage / 100) * ($price * $qty);
            $itemTotal = ($price * $qty) - $itemDiscountValue;

            // Simpan item ke tabel items
            $servis->items()->create([
                'item_name' => $itemName,
                'item_package' => $request->item_package[$key] ?? null,
                'item_qty' => $qty,
                'item_price' => $price,
                'item_discount' => $discountPercentage,
                'item_discount_value' => $itemDiscountValue,
                'item_total' => $itemTotal,
                'service_date' => $request->tanggal_servis,
                'kode_servis' => $request->kode_servis,
            ]);

            // Menambahkan item total ke total harga servis
            $totalHargaServis += $itemTotal;
        }

        // Update total harga ke tabel servis
        $servis->update(['total_harga' => $totalHargaServis]);

        return redirect()->route('servis.index')->with('success', 'Servis dan item berhasil disimpan.');
    }

    /**
     * Mengambil riwayat servis (dan itemnya) untuk mobil tertentu.
     * Digunakan oleh permintaan AJAX dari create.blade.php
     *
     * @param int $mobilId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getServisHistoryByMobilId($mobilId)
    {
        // Pastikan mobil_id yang diterima valid
        $mobil = Mobil::find($mobilId);

        if (!$mobil) {
            return response()->json(['message' => 'Mobil tidak ditemukan'], 404);
        }

        // Ambil semua servis yang terkait dengan mobil ini, dengan eager loading item-itemnya
        $servisHistory = Servis::where('mobil_id', $mobilId)
                               ->with('items') // Pastikan relasi 'items' ada di model Servis
                               ->orderBy('tanggal_servis', 'desc')
                               ->get();

        return response()->json($servisHistory);
    }

    /**
     * Menampilkan detail servis tertentu berdasarkan ID.
     * Mengambil data servis dengan eager loading dan mengembalikan sebagai response JSON.
     */
    public function show($id)
    {
        // Ambil data servis berdasarkan ID dan sertakan data mobil serta items terkait
        $servis = Servis::with('mobil', 'items')->find($id);

        if ($servis) {
            // Mengembalikan response JSON dengan total_harga sebagai nilai numerik mentah
            // agar bisa diformat di sisi client (JavaScript)
            return response()->json([
                'kode_servis' => $servis->kode_servis,
                'tanggal_servis' => $servis->tanggal_servis,
                'mobil' => [
                    'merek_mobil' => $servis->mobil->merek_mobil ?? 'N/A',
                    'nomor_polisi' => $servis->mobil->nomor_polisi ?? 'N/A',
                ],
                'metode_pembayaran' => $servis->metode_pembayaran,
                'total_harga' => $servis->total_harga, // Mengembalikan sebagai nilai numerik
                'status' => $servis->status, // Menggunakan kolom 'status' baru
                // 'tanggal_selesai_servis' => $servis->tanggal_selesai_servis, // Kolom ini mungkin tidak ada lagi atau belum ditambahkan
                'items' => $servis->items
            ]);
        }

        return response()->json(['error' => 'Servis tidak ditemukan'], 404);
    }

    /**
     * Menampilkan formulir untuk mengedit servis tertentu.
     *
     * @param  \App\Models\Servis  $servis
     * @return \Illuminate\View\View
     */
    public function edit(Servis $servis)
    {
        $mobils = Mobil::all(); // Mengambil semua data mobil untuk dropdown
        // Eager load items jika diperlukan di formulir edit (misal: untuk mengedit item)
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
        // Validasi input servis dan item
        $request->validate([
            'mobil_id' => 'required|exists:mobils,kode_mobil',
            'tanggal_servis' => 'required|date',
            'metode_pembayaran' => 'required|string',
            'status' => 'nullable|in:proses,selesai,batal', // Validasi kolom status baru
            'kode_servis' => 'required|string|unique:servis,kode_servis,' . $servis->id, // Validasi unique, abaikan ID saat ini
            'item_name.*' => 'required|string',
            'item_package.*' => 'nullable|string',
            'item_qty.*' => 'required|numeric|min:1',
            'item_price.*' => 'required|numeric|min:0',
            'item_discount.*' => 'nullable|numeric|min:0|max:100',
        ]);

        // Cari ID mobil berdasarkan kode mobil
        $mobil = Mobil::where('kode_mobil', $request->mobil_id)->first();

        if (!$mobil) {
            return redirect()->back()->with('error', 'Mobil tidak ditemukan.')->withInput();
        }

        // Update data servis utama
        $servis->update([
            'mobil_id' => $mobil->id,
            'kode_servis' => $request->kode_servis, // Kode servis biasanya tidak diubah setelah dibuat
            'tanggal_servis' => $request->tanggal_servis,
            'metode_pembayaran' => $request->metode_pembayaran,
            'status' => $request->status,
        ]);

        $totalHargaServis = 0;

        // Hapus semua item yang ada untuk servis ini, lalu buat ulang dari request
        // Ini adalah cara yang lebih sederhana untuk mengelola relasi many-to-one pada update
        // jika item tidak memiliki ID unik yang dikirimkan kembali dari form edit.
        $servis->items()->delete();

        // Menyimpan (atau membuat ulang) item-item terkait dan menghitung total harga
        foreach ($request->item_name as $key => $itemName) {
            $qty = (float)$request->item_qty[$key];
            $price = (float)$request->item_price[$key];
            $discountPercentage = (float)$request->item_discount[$key];

            $itemDiscountValue = ($discountPercentage / 100) * ($price * $qty);
            $itemTotal = ($price * $qty) - $itemDiscountValue;

            $servis->items()->create([
                'item_name' => $itemName,
                'item_package' => $request->item_package[$key] ?? null,
                'item_qty' => $qty,
                'item_price' => $price,
                'item_discount' => $discountPercentage,
                'item_discount_value' => $itemDiscountValue,
                'item_total' => $itemTotal,
                'service_date' => $request->tanggal_servis,
                'kode_servis' => $request->kode_servis, // Simpan kembali kode servis di item
            ]);

            $totalHargaServis += $itemTotal;
        }

        // Update total harga ke tabel servis setelah semua item diperbarui
        $servis->update(['total_harga' => $totalHargaServis]);

        return redirect()->route('servis.index')->with('success', 'Servis berhasil diperbarui.');
    }
}
