<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Pembeli;
use App\Models\Penjual;
use App\Models\Mobil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log; // Pastikan ini diimpor!
// use Illuminate\Support\Facades\DB; // Hanya perlu jika Anda benar-benar melakukan debugging DB level rendah

class TransaksiController extends Controller
{
    /**
     * Menampilkan daftar semua transaksi umum.
     * Mengambil data transaksi dengan eager loading untuk relasi 'mobil', 'pembeli', dan 'penjual',
     * mengurutkannya berdasarkan tanggal transaksi terbaru, dan melakukan paginasi.
     */
    public function index()
    {
        $transaksis = Transaksi::with(['mobil', 'pembeli', 'penjual'])
                                ->orderBy('tanggal_transaksi', 'desc')
                                ->orderBy('id', 'desc')
                                ->paginate(10);

        return view('transaksi.index', compact('transaksis'));
    }

    /**
     * Menampilkan daftar transaksi khusus untuk Pembeli.
     * Mengambil data transaksi dengan eager loading untuk relasi 'mobil' dan 'pembeli'.
     */
    public function indexPembeli()
    {
        $transaksiPembeli = Transaksi::with('mobil', 'pembeli')
                                     ->whereNotNull('pembeli_id')
                                     ->orderBy('tanggal_transaksi', 'desc')
                                     ->orderBy('id', 'desc')
                                     ->paginate(10);

        return view('transaksi.pembeli.index', compact('transaksiPembeli'));
    }

    /**
     * Menampilkan formulir untuk membuat transaksi pembeli baru.
     */
    public function createPembeli()
    {
        $mobils = Mobil::all();
        $pembelis = Pembeli::all();

        // Generate kode_transaksi
        $lastTransaksi = Transaksi::latest('id')->first();
        $lastId = $lastTransaksi ? $lastTransaksi->id : 0;
        $kode_transaksi = 'TRX-B-' . date('Ymd') . '-' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);

        return view('transaksi.pembeli.create', compact('mobils', 'pembelis', 'kode_transaksi'));
    }

    /**
     * Menyimpan transaksi pembeli baru ke database.
     */
    public function storePembeli(Request $request)
    {
        $validatedData = $request->validate([
            'mobil_id' => 'required|exists:mobils,id',
            'pembeli_id' => 'required|exists:pembelis,id',
            'tanggal_transaksi' => 'required|date',
            'metode_pembayaran' => 'required|string|max:255',
            'diskon_persen' => 'nullable|numeric|min:0|max:100',
            'total_harga' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string|max:1000',
            'status_pembayaran' => 'required|in:Lunas,Belum Lunas,Menunggu Pembayaran,Dibatalkan',
            // 'kode_transaksi' dikirim dari form dan sudah di-generate di create
            // Validasi unik untuk kode_transaksi yang dikirim dari form
            'kode_transaksi' => 'required|string|unique:transaksis,kode_transaksi',
            'bukti_pembayaran' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:2048',
        ]);

        // Karena kode_transaksi diambil dari form, kita gunakan itu.
        // Jika Anda ingin menggenerasi ulang di sini, Anda bisa mengaktifkan kembali kode di bawah.
        // Untuk konsistensi dengan form create, kita asumsikan kode_transaksi sudah valid dari form.
        $kodeTransaksi = $validatedData['kode_transaksi']; // Ambil dari data yang sudah divalidasi

        // Penanganan unggahan file bukti_pembayaran saat menyimpan
        if ($request->hasFile('bukti_pembayaran')) {
            try {
                $file = $request->file('bukti_pembayaran');
                $extension = $file->getClientOriginalExtension(); // Dapatkan ekstensi asli file

                // Buat nama file baru: bukti_pembayaran_KODETRANSAKSI_timestamp.ext
                $fileName = 'bukti_pembayaran_' . $kodeTransaksi . '_' . time() . '.' . $extension;

                // Simpan file dengan nama khusus ke dalam direktori 'bukti_pembayaran' di public disk
                $path = $file->storeAs('bukti_pembayaran', $fileName, 'public');
                $validatedData['bukti_pembayaran'] = $path; // Update path di validatedData
                Log::info('File bukti_pembayaran berhasil diunggah (store): ' . $path);
            } catch (\Exception $e) {
                Log::error('Gagal mengunggah bukti_pembayaran (store): ' . $e->getMessage());
                return redirect()->back()->withInput()->withErrors(['bukti_pembayaran' => 'Gagal mengunggah file bukti pembayaran.']);
            }
        } else {
            $validatedData['bukti_pembayaran'] = null; // Pastikan null jika tidak ada file
            Log::info('Tidak ada file bukti_pembayaran diunggah (store), nilai akan menjadi NULL.');
        }

        $validatedData['penjual_id'] = null; // Default null untuk transaksi pembeli

        // Debugging: Cek data sebelum disimpan
        Log::debug('Data yang akan dibuat di Transaksi (store): ' . json_encode($validatedData));

        Transaksi::create($validatedData);

        return redirect()->route('transaksi.pembeli.index')->with('success', 'Transaksi pembeli berhasil ditambahkan.');
    }

    /**
     * Menampilkan formulir untuk mengedit transaksi pembeli tertentu.
     *
     * @param  \App\Models\Transaksi  $transaksi
     * @return \Illuminate\View\View
     */
    public function editPembeli(Transaksi $transaksi)
    {
        $transaksi->load('mobil', 'pembeli');
        $mobils = Mobil::all();
        $pembelis = Pembeli::all();

        return view('transaksi.pembeli.edit', compact('transaksi', 'mobils', 'pembelis'));
    }

    /**
     * Memperbarui transaksi pembeli tertentu di database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaksi  $transaksi
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePembeli(Request $request, Transaksi $transaksi)
    {
        // Debugging: Log semua input dari request
        Log::debug('Semua input request untuk updatePembeli: ' . json_encode($request->all()));

        $validatedData = $request->validate([
            'mobil_id' => 'required|exists:mobils,id',
            'pembeli_id' => 'required|exists:pembelis,id',
            'tanggal_transaksi' => 'required|date',
            'metode_pembayaran' => 'required|string|max:255',
            'diskon_persen' => 'nullable|numeric|min:0|max:100',
            'total_harga' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string|max:1000',
            'status_pembayaran' => 'required|in:Lunas,Belum Lunas,Menunggu Pembayaran,Dibatalkan',
            'kode_transaksi' => 'required|string|unique:transaksis,kode_transaksi,' . $transaksi->id,
            'bukti_pembayaran' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:2048',
        ]);

        Log::info('Data yang divalidasi setelah validasi: ' . json_encode($validatedData));

        // Penanganan unggahan file bukti_pembayaran saat update
        if ($request->hasFile('bukti_pembayaran')) {
            try {
                // Hapus file lama jika ada sebelum menyimpan yang baru
                if ($transaksi->bukti_pembayaran && Storage::disk('public')->exists($transaksi->bukti_pembayaran)) {
                    Storage::disk('public')->delete($transaksi->bukti_pembayaran);
                    Log::info('File bukti_pembayaran lama berhasil dihapus (update): ' . $transaksi->bukti_pembayaran);
                }

                $file = $request->file('bukti_pembayaran');
                $extension = $file->getClientOriginalExtension();

                // Buat nama file baru: bukti_pembayaran_KODETRANSAKSI_timestamp.ext
                $fileName = 'bukti_pembayaran_' . $transaksi->kode_transaksi . '_' . time() . '.' . $extension;

                $path = $file->storeAs('bukti_pembayaran', $fileName, 'public');
                $validatedData['bukti_pembayaran'] = $path; // Update path di validatedData
                Log::info('File bukti_pembayaran baru berhasil diunggah (update): ' . $path);
            } catch (\Exception $e) {
                Log::error('Gagal mengunggah bukti_pembayaran saat update: ' . $e->getMessage());
                return redirect()->back()->withInput()->withErrors(['bukti_pembayaran' => 'Gagal mengunggah file bukti pembayaran.']);
            }
        } elseif ($request->boolean('clear_bukti_pembayaran')) { // Opsional: Tambahkan input hidden di form jika ingin bisa menghapus file
            // Logika untuk menghapus bukti_pembayaran jika user secara eksplisit memilih untuk menghapus
            if ($transaksi->bukti_pembayaran && Storage::disk('public')->exists($transaksi->bukti_pembayaran)) {
                Storage::disk('public')->delete($transaksi->bukti_pembayaran);
                Log::info('File bukti_pembayaran dihapus secara eksplisit oleh pengguna: ' . $transaksi->bukti_pembayaran);
            }
            $validatedData['bukti_pembayaran'] = null;
        }
        else {
            // Jika tidak ada file baru diunggah DAN tidak ada permintaan hapus eksplisit,
            // pertahankan nilai 'bukti_pembayaran' yang sudah ada
            $validatedData['bukti_pembayaran'] = $transaksi->bukti_pembayaran;
            Log::info('Tidak ada file bukti_pembayaran baru diunggah, mempertahankan path lama: ' . ($transaksi->bukti_pembayaran ?? 'NULL'));
        }

        // Debugging: Pastikan 'bukti_pembayaran' ada dan memiliki nilai yang benar di $validatedData
        Log::debug('Data final yang akan diupdate untuk Transaksi ID ' . $transaksi->id . ': ' . json_encode($validatedData));

        // Melakukan update data transaksi
        try {
            $transaksi->update($validatedData);
            Log::info('Transaksi ID ' . $transaksi->id . ' berhasil diupdate di database.');
        } catch (\Exception $e) {
            Log::error('Gagal mengupdate transaksi ID ' . $transaksi->id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui transaksi: ' . $e->getMessage());
        }

        return redirect()->route('transaksi.pembeli.index')->with('success', 'Transaksi pembelian berhasil diperbarui!');
    }

    /**
     * Menampilkan daftar transaksi khusus untuk Penjual.
     * Mengambil data transaksi dengan eager loading untuk relasi 'mobil' dan 'penjual'.
     */
    public function indexPenjual()
    {
        $transaksiPenjual = Transaksi::with('mobil', 'penjual')->paginate(10);
        return view('transaksi.penjual.index', compact('transaksiPenjual'));
    }

    /**
     * Menampilkan formulir untuk membuat transaksi penjual baru.
     */
    public function createPenjual()
    {
        $mobils = Mobil::all();
        $penjuals = Penjual::all();

        $lastTransaksi = Transaksi::latest('id')->first();
        $lastId = $lastTransaksi ? $lastTransaksi->id : 0;
        $kode_transaksi = 'TRX-J-' . date('Ymd') . '-' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);

        return view('transaksi.penjual.create', compact('mobils', 'penjuals', 'kode_transaksi'));
    }

    /**
     * Menyimpan transaksi penjual baru ke database.
     */
    public function storePenjual(Request $request)
    {
        $validatedData = $request->validate([
            'mobil_id' => 'required|exists:mobils,id',
            'penjual_id' => 'required|exists:penjuals,id',
            'tanggal_transaksi' => 'required|date',
            'total_harga' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        $lastTransaksi = Transaksi::latest('id')->first();
        $lastId = $lastTransaksi ? $lastTransaksi->id : 0;
        $kodeTransaksi = 'TRX-J-' . date('Ymd') . '-' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);
        $validatedData['kode_transaksi'] = $kodeTransaksi;

        $validatedData['pembeli_id'] = null;
        $validatedData['metode_pembayaran'] = null;
        $validatedData['diskon_persen'] = 0;
        $validatedData['status_pembayaran'] = 'Menunggu Pembayaran'; // Default untuk transaksi penjual
        $validatedData['bukti_pembayaran'] = null; // Tidak ada bukti pembayaran untuk transaksi penjualan by default

        Transaksi::create($validatedData);

        return redirect()->route('transaksi.penjual.index')->with('success', 'Transaksi penjual berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail transaksi tertentu berdasarkan ID.
     * Mengambil data transaksi dengan eager loading dan mengembalikan sebagai response JSON.
     */
    public function show($id)
    {
        $transaksi = Transaksi::with('mobil', 'pembeli', 'penjual')->find($id);

        if ($transaksi) {
            return response()->json([
                'kode_transaksi' => $transaksi->kode_transaksi,
                'tanggal_transaksi' => $transaksi->tanggal_transaksi,
                'metode_pembayaran' => $transaksi->metode_pembayaran,
                'diskon_persen' => $transaksi->diskon_persen,
                'total_harga' => $transaksi->total_harga,
                'keterangan' => $transaksi->keterangan,
                'status_pembayaran' => $transaksi->status_pembayaran,
                // Pastikan path bukti_pembayaran diakses melalui Storage::url() untuk frontend
                'bukti_pembayaran' => $transaksi->bukti_pembayaran ? Storage::url($transaksi->bukti_pembayaran) : null,
                'pembeli' => $transaksi->pembeli,
                'penjual' => $transaksi->penjual,
                'mobil' => $transaksi->mobil,
                'created_at' => $transaksi->created_at, // Penting: 'created_at' sudah disertakan di sini.
            ]);
        }

        return response()->json(['error' => 'Transaksi tidak ditemukan'], 404);
    }
}
