<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Pembeli;
use App\Models\Penjual;
use App\Models\Mobil;
use App\Models\Servis;
use App\Models\DetailServis; // Import model DetailServis
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon; // Import Carbon for date handling

class TransaksiController extends Controller
{
    /**
     * Menampilkan daftar semua transaksi umum dengan paginasi.
     * Memuat relasi 'mobil', 'pembeli', 'penjual', dan 'servis',
     * mengurutkan berdasarkan tanggal transaksi terbaru, dan memaginasi.
     */
    public function index()
    {
        $transaksis = Transaksi::with(['mobil', 'pembeli', 'penjual', 'servis'])
                               ->orderBy('tanggal_transaksi', 'desc')
                               ->orderBy('id', 'desc')
                               ->paginate(10);

        return view('transaksi.index', compact('transaksis'));
    }

    /**
     * Menampilkan daftar transaksi khusus untuk Pembeli.
     * Memuat relasi 'mobil', 'pembeli', dan 'servis'.
     */
    public function indexPembeli()
    {
        // Modifikasi eager loading: load 'mobil' dan di dalam 'mobil' load 'servis'
        $transaksiPembeli = Transaksi::with(['mobil.servis', 'pembeli'])
                                     ->whereNotNull('pembeli_id')
                                     ->orderBy('tanggal_transaksi', 'desc')
                                     ->orderBy('id', 'desc')
                                     ->paginate(10);

        return view('transaksi.pembeli.index', compact('transaksiPembeli'));
    }

    /**
     * Menampilkan form untuk membuat transaksi pembeli baru.
     */
    public function createPembeli()
    {
        $mobils = Mobil::all();
        $pembelis = Pembeli::all();

        // Membuat kode transaksi untuk Pembeli (TRX-B)
        $currentDate = Carbon::now()->format('Ymd');
        $prefix = 'TRX-B-' . $currentDate . '-';

        // Mencari transaksi terakhir untuk pembeli pada tanggal saat ini
        $lastTransaksiBuyer = Transaksi::where('kode_transaksi', 'like', $prefix . '%')
                                       ->orderBy('kode_transaksi', 'desc')
                                       ->first();

        $lastSequence = 0;
        if ($lastTransaksiBuyer) {
            // Mengekstrak nomor urut (misalnya, '0001' dari 'TRX-B-20250701-0001')
            $lastCode = $lastTransaksiBuyer->kode_transaksi;
            $parts = explode('-', $lastCode);
            if (count($parts) === 4) {
                $lastSequence = (int) $parts[3];
            }
        }

        $newSequence = $lastSequence + 1;
        $kode_transaksi = $prefix . str_pad($newSequence, 4, '0', STR_PAD_LEFT);

        return view('transaksi.pembeli.create', compact('mobils', 'pembelis', 'kode_transaksi'));
    }

    /**
     * Menyimpan transaksi pembeli yang baru dibuat ke penyimpanan.
     */
    public function storePembeli(Request $request)
    {
        $rules = [
            'mobil_id' => 'required|exists:mobils,id',
            'pembeli_id' => 'required|exists:pembelis,id',
            'tanggal_transaksi' => 'required|date',
            'metode_pembayaran' => 'required|string|max:255',
            'tempo_angsuran' => 'nullable|integer|min:1|max:5',
            'total_harga' => 'required|numeric|min:0', // Ini adalah harga mobil dari form
            'keterangan' => 'nullable|string|max:1000',
            'status_pembayaran' => 'required|in:Lunas,Belum Lunas,Menunggu Pembayaran,Dibatalkan',
            'kode_transaksi' => 'required|string|unique:transaksis,kode_transaksi',
            'bukti_pembayaran' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:2048',
            'servis_id' => 'nullable|exists:servis,id',
            'modal' => 'nullable|numeric|min:0', // Ini adalah total biaya servis dari form
            'dp_jumlah' => 'nullable|numeric|min:0',
        ];

        // Validasi kondisional untuk dp_jumlah
        if ($request->input('metode_pembayaran') === 'Kredit') {
            $rules['dp_jumlah'] = 'nullable|numeric|min:0';
        }

        $validatedData = $request->validate($rules);

        $kodeTransaksi = $validatedData['kode_transaksi'];

        // Menangani upload file bukti_pembayaran saat menyimpan
        if ($request->hasFile('bukti_pembayaran')) {
            try {
                $file = $request->file('bukti_pembayaran');
                $extension = $file->getClientOriginalExtension();
                $fileName = 'bukti_pembayaran_' . $kodeTransaksi . '_' . time() . '.' . $extension;
                $path = $file->storeAs('bukti_pembayaran', $fileName, 'public');
                $validatedData['bukti_pembayaran'] = $path;
                Log::info('File bukti_pembayaran berhasil diunggah (store): ' . $path);
            } catch (\Exception $e) {
                Log::error('Gagal mengunggah bukti_pembayaran (store): ' . $e->getMessage());
                return redirect()->back()->withInput()->withErrors(['bukti_pembayaran' => 'Gagal mengunggah file bukti pembayaran.']);
            }
        } else {
            $validatedData['bukti_pembayaran'] = null;
            Log::info('Tidak ada file bukti_pembayaran diunggah (store), nilai akan NULL.');
        }

        $validatedData['penjual_id'] = null; // Default null untuk transaksi pembeli

        // Mengatur dp_jumlah berdasarkan metode_pembayaran
        if ($request->input('metode_pembayaran') === 'Kredit') {
            $validatedData['dp_jumlah'] = $validatedData['dp_jumlah'] ?? 0;
        } else {
            $validatedData['dp_jumlah'] = null; // Mengosongkan dp_jumlah jika bukan Kredit
        }

        // Menyimpan harga mobil asli dan biaya servis asli secara terpisah
        $validatedData['harga_mobil_saat_transaksi'] = $validatedData['total_harga'];
        $validatedData['total_biaya_servis_saat_transaksi'] = $validatedData['modal'];

        // Menghitung total_harga akhir: harga mobil + modal servis
        $validatedData['total_harga'] = $validatedData['harga_mobil_saat_transaksi'] + $validatedData['total_biaya_servis_saat_transaksi'];

        Log::debug('Data yang akan dibuat di Transaksi (store): ' . json_encode($validatedData));

        $transaksi = Transaksi::create($validatedData);

        // --- Logika untuk memperbarui ketersediaan dan status_mobil ---
        if ($transaksi && $transaksi->pembeli_id) { // Memeriksa apakah transaksi dibuat dan itu adalah transaksi pembeli
            $mobil = Mobil::find($transaksi->mobil_id);
            if ($mobil) {
                $newKetersediaan = $mobil->ketersediaan; // Default: pertahankan ketersediaan yang ada
                $newStatusMobil = $mobil->status_mobil; // Default: pertahankan status_mobil yang ada

                switch ($validatedData['status_pembayaran']) {
                    case 'Menunggu Pembayaran':
                        // $newKetersediaan = 'menunggu pembayaran'; // Aligned with MobilController validation
                        $newStatusMobil = 'menunggu pembayaran'; // Aligned with MobilController validation
                        break;
                    default:
                        // Fallback untuk status yang tidak terduga, pertahankan nilai saat ini
                        Log::warning('Status pembayaran tidak dikenal saat store: ' . $validatedData['status_pembayaran'] . '. Ketersediaan dan status_mobil tidak diubah.');
                        break;
                }

                if ($mobil->ketersediaan !== $newKetersediaan) {
                    $mobil->ketersediaan = $newKetersediaan;
                    Log::info('Mobil ID ' . $mobil->id . ' ketersediaan diubah menjadi "' . $newKetersediaan . '" (saat store).');
                }
                if ($mobil->status_mobil !== $newStatusMobil) {
                    $mobil->status_mobil = $newStatusMobil;
                    Log::info('Mobil ID ' . $mobil->id . ' status_mobil diubah menjadi "' . $newStatusMobil . '" (saat store).');
                }
                $mobil->save(); // Pastikan perubahan status mobil disimpan
            }
        }
        // --- Akhir logika baru ---

        return redirect()->route('transaksi.pembeli.index')->with('success', 'Transaksi Pembeli berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit transaksi pembeli yang ditentukan.
     *
     * @param  \App\Models\Transaksi  $transaksi
     * @return \Illuminate\View\View
     */
    public function editPembeli(Transaksi $transaksi)
    {
        $transaksi->load('mobil', 'pembeli', 'servis');
        $mobils = Mobil::all();
        $pembelis = Pembeli::all();

        return view('transaksi.pembeli.edit', compact('transaksi', 'mobils', 'pembelis'));
    }

    /**
     * Memperbarui transaksi pembeli yang ditentukan di penyimpanan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaksi  $transaksi
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePembeli(Request $request, Transaksi $transaksi)
    {
        Log::debug('1. Semua input request untuk updatePembeli: ' . json_encode($request->all()));

        // Menyimpan status pembayaran lama sebelum validasi dan pembaruan
        $oldStatusPembayaran = $transaksi->status_pembayaran;
        $oldMobilId = $transaksi->mobil_id; // Menyimpan juga mobil_id lama jika mobil bisa diganti

        $rules = [
            'mobil_id' => 'required|exists:mobils,id',
            'pembeli_id' => 'required|exists:pembelis,id',
            'tanggal_transaksi' => 'required|date',
            'metode_pembayaran' => 'required|string|max:255',
            'tempo_angsuran' => 'nullable|integer|min:1|max:5',
            'total_harga' => 'required|numeric|min:0', // Ini adalah harga mobil dari form
            'keterangan' => 'nullable|string|max:1000',
            'status_pembayaran' => 'required|in:Lunas,Belum Lunas,Menunggu Pembayaran,Dibatalkan',
            'kode_transaksi' => 'required|string|unique:transaksis,kode_transaksi,' . $transaksi->id,
            'bukti_pembayaran' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:2048',
            'servis_id' => 'nullable|exists:servis,id',
            'modal' => 'nullable|numeric|min:0', // Ini adalah total biaya servis dari form
            'dp_jumlah' => 'nullable|numeric|min:0',
        ];

        // Validasi kondisional untuk dp_jumlah
        if ($request->input('metode_pembayaran') === 'Kredit') {
            $rules['dp_jumlah'] = 'nullable|numeric|min:0';
        }

        $validatedData = $request->validate($rules);

        Log::debug('2. Data yang divalidasi setelah validasi: ' . json_encode($validatedData));

        // Menangani upload file bukti_pembayaran saat memperbarui
        if ($request->hasFile('bukti_pembayaran')) {
            try {
                if ($transaksi->bukti_pembayaran && Storage::disk('public')->exists($transaksi->bukti_pembayaran)) {
                    Storage::disk('public')->delete($transaksi->bukti_pembayaran);
                    Log::info('File bukti_pembayaran lama berhasil dihapus (update): ' . $transaksi->bukti_pembayaran);
                }

                $file = $request->file('bukti_pembayaran');
                $extension = $file->getClientOriginalExtension();
                $fileName = 'bukti_pembayaran_' . $transaksi->kode_transaksi . '_' . time() . '.' . $extension;
                $path = $file->storeAs('bukti_pembayaran', $fileName, 'public');
                $validatedData['bukti_pembayaran'] = $path;
                Log::info('File bukti_pembayaran baru berhasil diunggah (update): ' . $path);
            } catch (\Exception $e) {
                Log::error('Gagal mengunggah bukti_pembayaran selama pembaruan: ' . $e->getMessage());
                return redirect()->back()->withInput()->withErrors(['bukti_pembayaran' => 'Gagal mengunggah file bukti pembayaran.']);
            }
        } elseif ($request->boolean('clear_bukti_pembayaran')) {
            if ($transaksi->bukti_pembayaran && Storage::disk('public')->exists($transaksi->bukti_pembayaran)) {
                Storage::disk('public')->delete($transaksi->bukti_pembayaran);
                Log::info('File bukti_pembayaran secara eksplisit dihapus oleh pengguna: ' . $transaksi->bukti_pembayaran);
            }
            $validatedData['bukti_pembayaran'] = null;
        } else {
            $validatedData['bukti_pembayaran'] = $transaksi->bukti_pembayaran;
            Log::info('Tidak ada file bukti_pembayaran baru diunggah, mempertahankan jalur lama: ' . ($transaksi->bukti_pembayaran ?? 'NULL'));
        }
        Log::debug('3. Data setelah penanganan file: ' . json_encode($validatedData));


        // Mengatur dp_jumlah berdasarkan metode_pembayaran
        if ($request->input('metode_pembayaran') === 'Kredit') {
            $validatedData['dp_jumlah'] = $validatedData['dp_jumlah'] ?? 0;
        } else {
            $validatedData['dp_jumlah'] = null; // Mengosongkan dp_jumlah jika bukan Kredit
        }
        Log::debug('4. Data setelah pengaturan dp_jumlah: ' . json_encode($validatedData));


        // Menyimpan harga mobil asli dan biaya servis asli secara terpisah
        $validatedData['harga_mobil_saat_transaksi'] = $validatedData['total_harga']; // Ambil dari input form
        // $validatedData['total_biaya_servis_saat_transaksi'] = $validatedData['modal']; // Ambil dari input form
        // $validatedData['total_harga'] = $validatedData['harga_mobil_saat_transaksi'] + $validatedData['total_biaya_servis_saat_transaksi'];


        Log::debug('5. Data setelah perhitungan total_harga: ' . json_encode($validatedData));


        try {
            $transaksi->update($validatedData); // Ini adalah baris yang menyimpan ke database
            Log::info('6. Transaksi ID ' . $transaksi->id . ' berhasil diperbarui di database.');

            // --- Logika untuk memperbarui ketersediaan dan status_mobil ---
            // Mengambil mobil yang terkait dengan transaksi
            $mobil = Mobil::find($validatedData['mobil_id']);

            Log::debug('7. Mencari Mobil dengan ID: ' . $validatedData['mobil_id'] . '. Mobil ditemukan: ' . ($mobil ? 'Ya' : 'Tidak'));

            if ($mobil) {
                $newKetersediaan = $mobil->ketersediaan; // Default: pertahankan ketersediaan yang ada
                $newStatusMobil = $mobil->status_mobil; // Default: pertahankan status_mobil yang ada

                switch ($validatedData['status_pembayaran']) {
                    case 'Menunggu Pembayaran':
                        $newKetersediaan = 'ada';             // Aligned with MobilController validation
                        $newStatusMobil = 'menunggu pembayaran'; // Aligned with MobilController validation
                        break;
                    case 'Lunas':
                        $newKetersediaan = 'terjual'; // Aligned with MobilController validation
                        $newStatusMobil = 'lunas'; // Aligned with MobilController validation
                        break;
                    case 'Belum Lunas':
                        $newKetersediaan = 'terjual'; // Aligned with MobilController validation
                        $newStatusMobil = 'belum lunas'; // Aligned with MobilController validation
                        break;
                    case 'Dibatalkan':
                        $newKetersediaan = 'ada'; // Aligned with MobilController validation
                        $newStatusMobil = 'dibatalkan'; // Aligned with MobilController validation
                        break;
                    default:
                        // Fallback untuk status yang tidak terduga, pertahankan nilai saat ini
                        Log::warning('Status pembayaran tidak dikenal saat update: ' . $validatedData['status_pembayaran'] . '. Ketersediaan dan status_mobil tidak diubah.');
                        break;
                }

                Log::debug('8. Status pembayaran baru: ' . $validatedData['status_pembayaran'] .
                          ', Ketersediaan yang ditentukan: ' . $newKetersediaan .
                          ', Status Mobil yang ditentukan: ' . $newStatusMobil .
                          ', Ketersediaan saat ini di DB: ' . $mobil->ketersediaan .
                          ', Status Mobil saat ini di DB: ' . $mobil->status_mobil);

                // Hanya perbarui jika nilai benar-benar berubah untuk menghindari penulisan yang tidak perlu
                if ($mobil->ketersediaan !== $newKetersediaan) {
                    $mobil->ketersediaan = $newKetersediaan;
                    Log::info('9. Mobil ID ' . $mobil->id . ' ketersediaan diubah menjadi "' . $newKetersediaan . '".');
                } else {
                    Log::info('9.1. Ketersediaan mobil ID ' . $mobil->id . ' tidak berubah, tetap "' . $newKetersediaan . '".');
                }

                if ($mobil->status_mobil !== $newStatusMobil) {
                    $mobil->status_mobil = $newStatusMobil;
                    Log::info('10. Mobil ID ' . $mobil->id . ' status_mobil diubah menjadi "' . $newStatusMobil . '".');
                } else {
                    Log::info('10.1. Status_mobil mobil ID ' . $mobil->id . ' tidak berubah, tetap "' . $newStatusMobil . '".');
                }

                // Ini adalah baris kritis yang menyimpan perubahan ke tabel 'mobils'
                $mobil->save();
                Log::info('11. Perubahan Mobil ID ' . $mobil->id . ' berhasil disimpan ke database.');

                // Logika untuk mengembalikan status mobil lama jika mobil_id berubah.
                // if ($oldMobilId !== $validatedData['mobil_id']) {
                //     $oldMobil = Mobil::find($oldMobilId);
                //     if ($oldMobil) {
                //         // Memeriksa apakah mobil lama masih terkait dengan transaksi *aktif* lainnya
                //         $otherActiveTransactions = Transaksi::where('mobil_id', $oldMobilId)
                //                                              ->where('id', '!=', $transaksi->id) // Kecualikan transaksi saat ini
                //                                              ->whereIn('status_pembayaran', ['Lunas', 'Belum Lunas', 'Menunggu Pembayaran'])
                //                                              ->exists();

                //         if (!$otherActiveTransactions) {
                //             // Jika tidak ada transaksi aktif lain, kembalikan status mobil lama
                //             if ($oldMobil->ketersediaan !== 'ada') {
                //                 $oldMobil->ketersediaan = 'ada';
                //                 Log::info('Mobil ID lama ' . $oldMobil->id . ' ketersediaan diubah menjadi "ada" karena transaksi dialihkan ke mobil lain.');
                //             }
                //             // Mengatur status_mobil mobil lama kembali ke "baru" atau "bekas"
                //             if (in_array($oldMobil->status_mobil, ['lunas', 'belum lunas', 'menunggu pembayarann', 'dibatalkan'])) {
                //                 if ($oldMobil->kondisi === 'baru') { // Assuming 'kondisi' field exists in Mobil model
                //                     $oldMobil->status_mobil = 'baru';
                //                 } else {
                //                     $oldMobil->status_mobil = 'bekas';
                //                 }
                //                 Log::info('Mobil ID lama ' . $oldMobil->id . ' status_mobil diubah kembali menjadi "' . $oldMobil->status_mobil . '".');
                //             }
                //             $oldMobil->save();
                //         }
                //     }
                // }
                Log::info('12. Logika mobil lama selesai.');
            } else {
                Log::warning('7.1. Mobil dengan ID ' . $validatedData['mobil_id'] . ' tidak ditemukan saat mencoba memperbarui status.');
            }
            // --- Akhir logika update ketersediaan dan status_mobil ---

        } catch (\Exception $e) {
            Log::error('13. Gagal memperbarui transaksi ID ' . $transaksi->id . ' atau status mobil: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui transaksi: ' . $e->getMessage());
        }

        return redirect()->route('transaksi.pembeli.index')->with('success', 'Transaksi Pembeli berhasil diperbarui!');
    }

    /**
     * Menampilkan daftar transaksi khusus untuk Penjual.
     * Memuat relasi 'mobil', 'penjual', dan 'servis'.
     */
    public function indexPenjual()
    {
        // Mendapatkan 10 transaksi per halaman dengan eager loading relasi yang dibutuhkan
        // Memastikan hanya transaksi dengan penjual_id yang tidak null yang diambil
        $transaksiPenjual = Transaksi::whereNotNull('penjual_id') // FILTER UTAMA
                                    ->with(['mobil', 'penjual', 'user']) // Eager load relasi yang diperlukan
                                    ->orderBy('created_at', 'desc') // Urutkan berdasarkan waktu pembuatan terbaru
                                    ->paginate(10); // Ambil 10 transaksi per halaman

        return view('transaksi.penjual.index', compact('transaksiPenjual'));
    }

    /**
     * Menampilkan form untuk membuat transaksi penjual baru.
     */
    public function createPenjual()
    {
        $mobils = Mobil::all();
        $penjuals = Penjual::all();

        // Membuat kode transaksi untuk Penjual (TRX-J)
        $currentDate = Carbon::now()->format('Ymd');
        $prefix = 'TRX-J-' . $currentDate . '-';

        // Mencari transaksi terakhir untuk penjual pada tanggal saat ini
        $lastTransaksiSeller = Transaksi::where('kode_transaksi', 'like', $prefix . '%')
                                        ->orderBy('kode_transaksi', 'desc')
                                        ->first();

        $lastSequence = 0;
        if ($lastTransaksiSeller) {
            // Mengekstrak nomor urut (misalnya, '0001' dari 'TRX-J-20250701-0001')
            $lastCode = $lastTransaksiSeller->kode_transaksi;
            $parts = explode('-', $lastCode);
            if (count($parts) === 4) {
                $lastSequence = (int) $parts[3];
            }
        }

        $newSequence = $lastSequence + 1;
        $kode_transaksi = $prefix . str_pad($newSequence, 4, '0', STR_PAD_LEFT);

        return view('transaksi.penjual.create', compact('mobils', 'penjuals', 'kode_transaksi'));
    }

    /**
     * Menyimpan transaksi penjual yang baru dibuat ke penyimpanan.
     */
    public function storePenjual(Request $request)
    {
        $rules = [
            'mobil_id' => 'required|exists:mobils,id',
            'penjual_id' => 'required|exists:penjuals,id',
            'tanggal_transaksi' => 'required|date',
            'total_harga' => 'required|numeric|min:0', // Ini adalah harga mobil dari form
            'keterangan' => 'nullable|string|max:1000',
            'kode_transaksi' => 'required|string|unique:transaksis,kode_transaksi',
            'bukti_pembayaran' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:2048', // Bukti pembayaran dari dealer ke penjual
            'servis_id' => 'nullable|exists:servis,id',
            'modal' => 'nullable|numeric|min:0', // Ini adalah total biaya servis dari form
            'dp_jumlah' => 'nullable|numeric|min:0', // Jika dealer membayar kredit ke penjual
            'metode_pembayaran' => 'required|string|max:255', // Metode pembayaran dealer ke penjual
            'status_pembayaran' => 'required|in:Lunas,Belum Lunas,Menunggu Pembayaran,Dibatalkan',
        ];

        // Validasi kondisional untuk dp_jumlah
        if ($request->input('metode_pembayaran') === 'Kredit') {
            $rules['dp_jumlah'] = 'nullable|numeric|min:0';
        }

        $validatedData = $request->validate($rules);

        $kodeTransaksi = $validatedData['kode_transaksi'];

        // Menangani upload file bukti_pembayaran saat menyimpan
        if ($request->hasFile('bukti_pembayaran')) {
            try {
                $file = $request->file('bukti_pembayaran');
                $extension = $file->getClientOriginalExtension();
                $fileName = 'bukti_pembayaran_penjual_' . $kodeTransaksi . '_' . time() . '.' . $extension;
                $path = $file->storeAs('bukti_pembayaran_penjual', $fileName, 'public');
                $validatedData['bukti_pembayaran'] = $path;
                Log::info('File bukti_pembayaran penjual berhasil diunggah (store): ' . $path);
            } catch (\Exception $e) {
                Log::error('Gagal mengunggah bukti_pembayaran penjual (store): ' . $e->getMessage());
                return redirect()->back()->withInput()->withErrors(['bukti_pembayaran' => 'Gagal mengunggah file bukti pembayaran.']);
            }
        } else {
            $validatedData['bukti_pembayaran'] = null;
            Log::info('Tidak ada file bukti_pembayaran penjual diunggah (store), nilai akan NULL.');
        }

        $validatedData['pembeli_id'] = null; // Default null untuk transaksi penjual (dealer membeli dari penjual)

        // Mengatur dp_jumlah berdasarkan metode_pembayaran
        if ($request->input('metode_pembayaran') === 'Kredit') {
            $validatedData['dp_jumlah'] = $validatedData['dp_jumlah'] ?? 0;
        } else {
            $validatedData['dp_jumlah'] = null; // Mengosongkan dp_jumlah jika bukan Kredit
        }

        // Menyimpan harga mobil asli dan biaya servis asli secara terpisah
        $validatedData['harga_mobil_saat_transaksi'] = $validatedData['total_harga'];
        // $validatedData['total_biaya_servis_saat_transaksi'] = $validatedData['modal'];

        // Menghitung total_harga akhir: harga mobil + modal servis (biaya akuisisi)
        // $validatedData['total_harga'] = $validatedData['harga_mobil_saat_transaksi'] + $validatedData['total_biaya_servis_saat_transaksi'];

        Log::debug('Data yang akan dibuat di Transaksi Penjual (store): ' . json_encode($validatedData));

        Transaksi::create($validatedData);

        return redirect()->route('transaksi.penjual.index')->with('success', 'Transaksi Penjual berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit transaksi penjual yang ditentukan.
     *
     * @param  \App\Models\Transaksi  $transaksi
     * @return \Illuminate\View\View
     */
    public function editPenjual(Transaksi $transaksi)
    {
        $transaksi->load('mobil', 'penjual', 'servis'); // Memuat relasi yang diperlukan
        $mobils = Mobil::all();
        $penjuals = Penjual::all();

        return view('transaksi.penjual.edit', compact('transaksi', 'mobils', 'penjuals'));
    }

    /**
     * Memperbarui transaksi penjual yang ditentukan di penyimpanan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaksi  $transaksi
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePenjual(Request $request, Transaksi $transaksi)
    {
        Log::debug('1. Semua input request untuk updatePenjual: ' . json_encode($request->all()));

        $oldMobilId = $transaksi->mobil_id; // Simpan mobil_id lama
        $oldStatusPembayaran = $transaksi->status_pembayaran; // Simpan status pembayaran lama

        $rules = [
            'mobil_id' => 'required|exists:mobils,id',
            'penjual_id' => 'required|exists:penjuals,id',
            'tanggal_transaksi' => 'required|date',
            'metode_pembayaran' => 'required|string|max:255',
            'tempo_angsuran' => 'nullable|integer|min:1|max:5',
            'total_harga' => 'required|numeric|min:0', // Ini adalah harga mobil dari form
            'keterangan' => 'nullable|string|max:1000',
            'status_pembayaran' => 'required|in:Lunas,Belum Lunas,Menunggu Pembayaran,Dibatalkan',
            'kode_transaksi' => 'required|string|unique:transaksis,kode_transaksi,' . $transaksi->id,
            'bukti_pembayaran' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:2048',
            'servis_id' => 'nullable|exists:servis,id',
            'modal' => 'nullable|numeric|min:0', // Ini adalah total biaya servis dari form
            'dp_jumlah' => 'nullable|numeric|min:0',
        ];

        // Validasi kondisional untuk dp_jumlah
        if ($request->input('metode_pembayaran') === 'Kredit') {
            $rules['dp_jumlah'] = 'nullable|numeric|min:0';
        }

        $validatedData = $request->validate($rules);

        Log::info('2. Data yang divalidasi setelah validasi untuk updatePenjual: ' . json_encode($validatedData));

        // Menangani upload file bukti_pembayaran saat memperbarui
        if ($request->hasFile('bukti_pembayaran')) {
            try {
                if ($transaksi->bukti_pembayaran && Storage::disk('public')->exists($transaksi->bukti_pembayaran)) {
                    Storage::disk('public')->delete($transaksi->bukti_pembayaran);
                    Log::info('File bukti_pembayaran penjual lama berhasil dihapus (update): ' . $transaksi->bukti_pembayaran);
                }

                $file = $request->file('bukti_pembayaran');
                $extension = $file->getClientOriginalExtension();
                $fileName = 'bukti_pembayaran_penjual_' . $transaksi->kode_transaksi . '_' . time() . '.' . $extension;
                $path = $file->storeAs('bukti_pembayaran_penjual', $fileName, 'public');
                $validatedData['bukti_pembayaran'] = $path;
                Log::info('File bukti_pembayaran penjual baru berhasil diunggah (update): ' . $path);
            } catch (\Exception $e) {
                Log::error('Gagal mengunggah bukti_pembayaran penjual selama pembaruan: ' . $e->getMessage());
                return redirect()->back()->withInput()->withErrors(['bukti_pembayaran' => 'Gagal mengunggah file bukti pembayaran.']);
            }
        } elseif ($request->boolean('clear_bukti_pembayaran')) {
            if ($transaksi->bukti_pembayaran && Storage::disk('public')->exists($transaksi->bukti_pembayaran)) {
                Storage::disk('public')->delete($transaksi->bukti_pembayaran);
                Log::info('File bukti_pembayaran penjual secara eksplisit dihapus oleh pengguna: ' . $transaksi->bukti_pembayaran);
            }
            $validatedData['bukti_pembayaran'] = null;
        } else {
            $validatedData['bukti_pembayaran'] = $transaksi->bukti_pembayaran;
            Log::info('Tidak ada file bukti_pembayaran penjual baru diunggah, mempertahankan jalur lama: ' . ($transaksi->bukti_pembayaran ?? 'NULL'));
        }
        Log::debug('3. Data setelah penanganan file: ' . json_encode($validatedData));

        // Mengatur dp_jumlah berdasarkan metode_pembayaran
        if ($request->input('metode_pembayaran') === 'Kredit') {
            $validatedData['dp_jumlah'] = $validatedData['dp_jumlah'] ?? 0;
        } else {
            $validatedData['dp_jumlah'] = null; // Mengosongkan dp_jumlah jika bukan Kredit
        }
        Log::debug('4. Data setelah pengaturan dp_jumlah: ' . json_encode($validatedData));

        // Menyimpan harga mobil asli dan biaya servis asli secara terpisah
        $validatedData['harga_mobil_saat_transaksi'] = $validatedData['total_harga'];
        // $validatedData['total_biaya_servis_saat_transaksi'] = $validatedData['modal'];

        // Menghitung total_harga akhir: harga mobil + modal servis (biaya akuisisi)
        // $validatedData['total_harga'] = $validatedData['harga_mobil_saat_transaksi'] + $validatedData['total_biaya_servis_saat_transaksi'];

        Log::debug('5. Data akhir yang akan diperbarui untuk Transaksi Penjual ID ' . $transaksi->id . ': ' . json_encode($validatedData));

        try {
            $transaksi->update($validatedData);
            Log::info('6. Transaksi Penjual ID ' . $transaksi->id . ' berhasil diperbarui di database.');

            // --- Logika untuk memperbarui ketersediaan dan status_mobil ---
            $mobil = Mobil::find($validatedData['mobil_id']);

            Log::debug('7. Mencari Mobil dengan ID: ' . $validatedData['mobil_id'] . '. Mobil ditemukan: ' . ($mobil ? 'Ya' : 'Tidak'));

            if ($mobil) {
                $newKetersediaan = $mobil->ketersediaan; // Default: pertahankan ketersediaan yang ada
                $newStatusMobil = $mobil->status_mobil; // Default: pertahankan status_mobil yang ada

                switch ($validatedData['status_pembayaran']) {
                    case 'Lunas':
                        $newKetersediaan = 'ada';
                        if ($mobil->kondisi === 'baru') {
                            $newStatusMobil = 'baru';
                        } else {
                            $newStatusMobil = 'bekas';
                        }
                        break;
                    case 'Belum Lunas':
                        $newKetersediaan = 'ada';
                        if ($mobil->kondisi === 'baru') {
                            $newStatusMobil = 'baru';
                        } else {
                            $newStatusMobil = 'bekas';
                        }
                        break;
                    case 'Menunggu Pembayaran':
                        $newKetersediaan = 'ada';
                        if ($mobil->kondisi === 'baru') {
                            $newStatusMobil = 'baru';
                        } else {
                            $newStatusMobil = 'bekas';
                        }
                        break;
                    case 'Dibatalkan':
                        $newKetersediaan = 'ada';
                        if ($mobil->kondisi === 'baru') {
                            $newStatusMobil = 'baru';
                        } else {
                            $newStatusMobil = 'bekas';
                        }
                        break;
                    default:
                        Log::warning('Status pembayaran tidak dikenal saat update penjual: ' . $validatedData['status_pembayaran'] . '. Ketersediaan dan status_mobil tidak diubah.');
                        break;
                }

                Log::debug('8. Status pembayaran baru: ' . $validatedData['status_pembayaran'] .
                          ', Ketersediaan yang ditentukan: ' . $newKetersediaan .
                          ', Status Mobil yang ditentukan: ' . $newStatusMobil .
                          ', Ketersediaan saat ini di DB: ' . $mobil->ketersediaan .
                          ', Status Mobil saat ini di DB: ' . $mobil->status_mobil);

                if ($mobil->ketersediaan !== $newKetersediaan) {
                    $mobil->ketersediaan = $newKetersediaan;
                    Log::info('9. Mobil ID ' . $mobil->id . ' ketersediaan diubah menjadi "' . $newKetersediaan . '".');
                } else {
                    Log::info('9.1. Ketersediaan mobil ID ' . $mobil->id . ' tidak berubah, tetap "' . $newKetersediaan . '".');
                }

                if ($mobil->status_mobil !== $newStatusMobil) {
                    $mobil->status_mobil = $newStatusMobil;
                    Log::info('10. Mobil ID ' . $mobil->id . ' status_mobil diubah menjadi "' . $newStatusMobil . '".');
                } else {
                    Log::info('10.1. Status_mobil mobil ID ' . $mobil->id . ' tidak berubah, tetap "' . $newStatusMobil . '".');
                }

                $mobil->save();
                Log::info('11. Perubahan Mobil ID ' . $mobil->id . ' berhasil disimpan ke database.');

                // Logika untuk mengembalikan status mobil lama jika mobil_id berubah (mirip dengan updatePembeli)
                if ($oldMobilId !== $validatedData['mobil_id']) {
                    $oldMobil = Mobil::find($oldMobilId);
                    if ($oldMobil) {
                        // Periksa apakah mobil lama masih terkait dengan transaksi *aktif* lainnya
                        $otherActiveTransactions = Transaksi::where('mobil_id', $oldMobilId)
                                                             ->where('id', '!=', $transaksi->id)
                                                             ->whereIn('status_pembayaran', ['Lunas', 'Belum Lunas', 'Menunggu Pembayaran'])
                                                             ->exists();

                        if (!$otherActiveTransactions) {
                            if ($oldMobil->ketersediaan !== 'ada') {
                                $oldMobil->ketersediaan = 'ada';
                                Log::info('Mobil ID lama ' . $oldMobil->id . ' ketersediaan diubah menjadi "ada" karena transaksi dialihkan ke mobil lain.');
                            }
                            if (in_array($oldMobil->status_mobil, ['lunas', 'belum lunas', 'menunggu pembayaran', 'dibatalkan'])) {
                                if ($oldMobil->kondisi === 'baru') {
                                    $oldMobil->status_mobil = 'baru';
                                } else {
                                    $oldMobil->status_mobil = 'bekas';
                                }
                                Log::info('Mobil ID lama ' . $oldMobil->id . ' status_mobil diubah kembali menjadi "' . $oldMobil->status_mobil . '".');
                            }
                            $oldMobil->save();
                        }
                    }
                }
                Log::info('12. Logika mobil lama selesai.');

            } else {
                Log::warning('7.1. Mobil dengan ID ' . $validatedData['mobil_id'] . ' tidak ditemukan saat mencoba memperbarui status di updatePenjual.');
            }
            // --- Akhir logika update ketersediaan dan status_mobil ---

        } catch (\Exception $e) {
            Log::error('13. Gagal memperbarui transaksi Penjual ID ' . $transaksi->id . ' atau status mobil: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui transaksi: ' . $e->getMessage());
        }

        return redirect()->route('transaksi.penjual.index')->with('success', 'Transaksi Penjual berhasil diperbarui!');
    }


    /**
     * Menampilkan detail transaksi yang ditentukan berdasarkan ID.
     * Memuat relasi dan mengembalikan sebagai respons JSON.
     */
    public function show($id)
    {
        // Memuat 'mobil' dan di dalamnya memuat 'servis'
        $transaksi = Transaksi::with(['mobil.servis', 'pembeli', 'penjual'])->find($id);

        if ($transaksi) {
            $totalServisHarga = 0;
            // Jika ada mobil dan mobil tersebut memiliki servis, jumlahkan total_harga dari semua servis
            // Perhatikan bahwa ini menghitung ulang total servis dari riwayat servis mobil,
            // sedangkan `modal` di transaksi adalah nilai yang disimpan saat transaksi dibuat.
            // Bisa jadi ada perbedaan jika riwayat servis mobil berubah setelah transaksi.
            if ($transaksi->mobil && $transaksi->mobil->servis) {
                foreach ($transaksi->mobil->servis as $servis) {
                    $totalServisHarga += $servis->total_harga ?? 0;
                }
            }

            return response()->json([
                'kode_transaksi' => $transaksi->kode_transaksi,
                'tanggal_transaksi' => $transaksi->tanggal_transaksi,
                'metode_pembayaran' => $transaksi->metode_pembayaran,
                'tempo_angsuran' => $transaksi->tempo_angsuran,
                'dp_jumlah' => $transaksi->dp_jumlah,
                'total_harga' => $transaksi->total_harga, // Ini adalah total_harga yang sudah dihitung (harga mobil + modal servis)
                'harga_mobil_saat_transaksi' => $transaksi->harga_mobil_saat_transaksi, // Harga mobil asli saat transaksi
                'total_biaya_servis_saat_transaksi' => $transaksi->total_biaya_servis_saat_transaksi, // Biaya servis asli saat transaksi
                'keterangan' => $transaksi->keterangan,
                'status_pembayaran' => $transaksi->status_pembayaran,
                'bukti_pembayaran' => $transaksi->bukti_pembayaran ? Storage::url($transaksi->bukti_pembayaran) : null,
                'pembeli' => $transaksi->pembeli,
                'penjual' => $transaksi->penjual,
                'mobil' => $transaksi->mobil,
                // Mengirimkan total servis yang sudah dijumlahkan dari riwayat servis (ini berbeda dari `modal` yang disimpan)
                'servis_total_harga_calculated' => $totalServisHarga,
                'modal' => $transaksi->modal, // Ini adalah nilai 'modal' yang disimpan dalam catatan transaksi (total biaya servis dari form)
                'created_at' => $transaksi->created_at,
            ]);
        }

        return response()->json(['error' => 'Transaksi tidak ditemukan'], 404);
    }

    /**
     * Mengambil riwayat servis untuk ID mobil tertentu.
     * Mengembalikan HTML untuk tampilan dan total biaya servis.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getServisHistory(Request $request)
    {
        $mobilId = $request->query('mobil_id');
        $servisHistory = [];
        $totalBiayaServis = 0;
        $latestServisId = null; // Untuk menyimpan ID servis terbaru

        if ($mobilId) {
            // Mengambil semua riwayat servis untuk mobil ini, mengurutkan dari yang terbaru
            $services = Servis::where('mobil_id', $mobilId)
                               ->orderBy('tanggal_servis', 'desc')
                               ->get();

            if ($services->isNotEmpty()) {
                // Mengambil ID servis terbaru
                $latestServisId = $services->first()->id;

                foreach ($services as $servis) {
                    $detailServisItems = [];
                    // Asumsi ada relasi hasMany DetailServis dengan Servis
                    // dan Servis memiliki relasi ke DetailServis
                    foreach ($servis->detailServis as $detail) {
                        $detailServisItems[] = [
                            'layanan' => $detail->layanan ?? 'N/A',
                            'biaya' => $detail->biaya ?? 0,
                        ];
                        $totalBiayaServis += ($detail->biaya ?? 0);
                    }

                    $servisHistory[] = [
                        'kode_servis' => $servis->kode_servis,
                        'tanggal_servis' => Carbon::parse($servis->tanggal_servis)->format('d M Y'),
                        'keterangan' => $servis->keterangan ?? 'Tidak ada keterangan',
                        'detail_servis' => $detailServisItems,
                    ];
                }
            }
        }

        // Membangun HTML untuk riwayat servis
        $html = '';
        if (empty($servisHistory)) {
            $html = '<p class="text-muted text-center">Tidak ada riwayat servis untuk mobil ini.</p>';
        } else {
            $html .= '<div class="accordion" id="servisHistoryAccordion">';
            foreach ($servisHistory as $index => $history) {
                $collapseId = 'collapseServis' . $index;
                $headingId = 'headingServis' . $index;
                $html .= '<div class="accordion-item mb-2 shadow-sm rounded-3">';
                $html .= '  <h2 class="accordion-header" id="' . $headingId . '">';
                $html .= '    <button class="accordion-button ' . ($index === 0 ? '' : 'collapsed') . '" type="button" data-bs-toggle="collapse" data-bs-target="#' . $collapseId . '" aria-expanded="' . ($index === 0 ? 'true' : 'false') . '" aria-controls="' . $collapseId . '">';
                $html .= '      <strong>Kode Servis: ' . $history['kode_servis'] . '</strong> <span class="ms-3 text-muted">(' . $history['tanggal_servis'] . ')</span>';
                $html .= '    </button>';
                $html .= '  </h2>';
                $html .= '  <div id="' . $collapseId . '" class="accordion-collapse collapse ' . ($index === 0 ? 'show' : '') . '\" aria-labelledby="' . $headingId . '\" data-bs-parent=\"#servisHistoryAccordion\">';
                $html .= '    <div class="accordion-body">';
                $html .= '      <p><strong>Keterangan:</strong> ' . $history['keterangan'] . '</p>';
                $html .= '      <h6>Detail Layanan:</h6>';
                if (!empty($history['detail_servis'])) {
                    $html .= '      <ul class="list-group list-group-flush">';
                    foreach ($history['detail_servis'] as $detail) {
                        $html .= '        <li class="list-group-item d-flex justify-content-between align-items-center">';
                        $html .= '          ' . $detail['layanan'];
                        $html .= '          <span class="fw-bold text-success">' . number_format($detail['biaya'], 0, ',', '.') . '</span>';
                        $html .= '        </li>';
                    }
                    $html .= '      </ul>';
                } else {
                    $html .= '      <p class="text-muted">Tidak ada detail layanan.</p>';
                }
                $html .= '    </div>';
                $html .= '  </div>';
                $html .= '</div>';
            }
            $html .= '</div>';
        }

        return response()->json([
            'html' => $html,
            'total_biaya' => $totalBiayaServis,
            'servis_id' => $latestServisId // Mengirim ID servis terbaru
        ]);
    }
}
