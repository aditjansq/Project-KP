<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mobil;
use App\Models\Penjual;
use App\Models\TransaksiPembelian;
use App\Models\TransaksiPembayaranDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class TransaksiPembelianController extends Controller
{
    /**
     * Menampilkan daftar transaksi pembelian dengan filter.
     */
    public function index(Request $request)
    {
        $query = TransaksiPembelian::with(['mobil', 'penjual', 'user']);

        // Filter berdasarkan pencarian (search)
        if ($request->has('search') && $request->input('search') != '') {
            $searchTerm = strtolower($request->input('search'));
            $query->where(function($q) use ($searchTerm) {
                $q->where(DB::raw('lower(kode_transaksi)'), 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('mobil', function($qMobil) use ($searchTerm) {
                      // Perbaikan: Gunakan CAST(AS CHAR) atau CONCAT() untuk MySQL
                      // Asumsi MySQL, 'TEXT' tidak valid untuk CAST. 'CHAR' atau 'VARCHAR' lebih sesuai.
                      // Atau yang lebih sederhana, CONCAT() akan mengkonversi ke string.
                      $qMobil->where(DB::raw('lower(merek_mobil)'), 'like', '%' . $searchTerm . '%')
                             ->orWhere(DB::raw('lower(tipe_mobil)'), 'like', '%' . $searchTerm . '%')
                             // Mengubah 'cast(tahun_pembuatan as text)' menjadi 'cast(tahun_pembuatan as char)'
                             // Atau bisa juga menggunakan 'CONCAT(tahun_pembuatan)' untuk implicit cast
                             ->orWhere(DB::raw('lower(CAST(tahun_pembuatan AS CHAR))'), 'like', '%' . $searchTerm . '%')
                             ->orWhere(DB::raw('lower(nomor_polisi)'), 'like', '%' . $searchTerm . '%');
                  })
                  ->orWhereHas('penjual', function($qPenjual) use ($searchTerm) {
                      $qPenjual->where(DB::raw('lower(nama)'), 'like', '%' . $searchTerm . '%')
                               ->orWhere(DB::raw('lower(no_telepon)'), 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        // Filter berdasarkan status pembayaran
        if ($request->has('status') && $request->input('status') != '') {
            $status = strtolower($request->input('status'));
            $query->where(DB::raw('lower(status_pembayaran)'), $status);
        }

        // Filter berdasarkan tahun mobil
        if ($request->has('year') && $request->input('year') != '') {
            $year = $request->input('year');
            $query->whereHas('mobil', function($qMobil) use ($year) {
                $qMobil->where('tahun_pembuatan', $year);
            });
        }

        $transaksis = $query->latest()->paginate(1)->appends($request->query()); // appends() untuk mempertahankan filter di pagination

        return view('transaksi_pembelian.index', compact('transaksis'));
    }

    /**
     * Menampilkan formulir untuk membuat transaksi pembelian baru.
     */
    public function create()
    {
        $mobils = Mobil::all();
        $penjuals = Penjual::all();

        // Tanggal sekarang dengan format DDMMYY
        $tanggalSekarang = now()->format('dmy');

        // Dapatkan nomor urut transaksi terakhir secara global
        $lastTransaksi = TransaksiPembelian::where('kode_transaksi', 'like', 'CM-PO-' . $tanggalSekarang . '-%')
                                            ->latest('id')
                                            ->first();

        $urutan = 1; // Default jika belum ada transaksi sama sekali dengan tanggal sekarang
        if ($lastTransaksi) {
            $parts = explode('-', $lastTransaksi->kode_transaksi);
            if (count($parts) > 0 && is_numeric(end($parts))) {
                $lastPart = end($parts);
                $urutan = $lastPart + 1;
            }
        }
        $nomorUrutFormatted = sprintf('%03d', $urutan);
        $kode_transaksi_baru = 'CM-PO-' . $tanggalSekarang . '-' . $nomorUrutFormatted;

        return view('transaksi_pembelian.create', compact('mobils', 'penjuals', 'kode_transaksi_baru'));
    }

    /**
     * Menyimpan transaksi pembelian yang baru dibuat ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi Data
        $request->validate([
            'mobil_id' => 'required|exists:mobils,id',
            'penjual_id' => 'required|exists:penjuals,id',
            'tanggal_transaksi' => 'required|date',
            'harga_beli_mobil_final' => 'required|numeric|min:0',
            'kode_transaksi' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'pembayaran_detail' => 'nullable|array',
            'pembayaran_detail.*.metode_pembayaran' => 'required_with:pembayaran_detail|string|max:50',
            'pembayaran_detail.*.jumlah_pembayaran' => 'required_with:pembayaran_detail|numeric|min:0',
            'pembayaran_detail.*.tanggal_pembayaran' => 'nullable|date',
            'pembayaran_detail.*.keterangan_pembayaran_detail' => 'nullable|string',
            'pembayaran_detail.*.bukti_pembayaran_detail' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048', // Max 2MB
        ]);

        DB::beginTransaction();

        try {
            // Tanggal sekarang dengan format DDMMYY
            $tanggalSekarang = now()->format('dmy');

            // Dapatkan nomor urut transaksi terakhir secara global
            // Pastikan ini hanya mencari transaksi yang kodenya cocok dengan pola CM-PO-DDMMYY
            $lastTransaksi = TransaksiPembelian::where('kode_transaksi', 'like', 'CM-PO-' . $tanggalSekarang . '-%')
                                                ->latest('id')
                                                ->first();

            $urutan = 1; // Default jika belum ada transaksi sama sekali dengan tanggal sekarang
            if ($lastTransaksi) {
                $parts = explode('-', $lastTransaksi->kode_transaksi);
                // Pastikan bagian terakhir adalah angka
                if (count($parts) > 0 && is_numeric(end($parts))) {
                    $lastPart = end($parts);
                    $urutan = $lastPart + 1;
                }
            }
            $nomorUrutFormatted = sprintf('%03d', $urutan);
            $kode_transaksi_baru = 'CM-PO-' . $tanggalSekarang . '-' . $nomorUrutFormatted;

            // 2. Simpan Transaksi Pembelian Utama
            $transaksiPembelian = TransaksiPembelian::create([
                'kode_transaksi' => $kode_transaksi_baru, // Menggunakan kode_transaksi yang baru digenerate
                'tanggal_transaksi' => $request->tanggal_transaksi,
                'mobil_id' => $request->mobil_id,
                'penjual_id' => $request->penjual_id,
                'harga_beli_mobil_final' => $request->harga_beli_mobil_final,
                'status_pembayaran' => 'Belum Dibayar', // Default status awal
                'user_id' => auth()->id(),
                'keterangan' => $request->keterangan,
            ]);

            $totalPembayaranInput = 0;

            // 3. Simpan Detail Pembayaran (Looping)
            if ($request->has('pembayaran_detail') && is_array($request->pembayaran_detail)) {
                foreach ($request->pembayaran_detail as $index => $detail) {
                    $buktiPath = null;
                    if (isset($detail['bukti_pembayaran_detail']) && $detail['bukti_pembayaran_detail'] instanceof \Illuminate\Http\UploadedFile) {
                        $file = $detail['bukti_pembayaran_detail'];
                        $extension = $file->getClientOriginalExtension();
                        $fileName = 'bukti_' . Str::slug($transaksiPembelian->kode_transaksi) . '_' . $index . '_' . time() . '.' . $extension;
                        $directory = 'bukti_pembayaran';

                        $buktiPath = $file->storeAs($directory, $fileName, 'public');
                        $buktiPath = Storage::url($buktiPath); // Dapatkan URL publik
                    }

                    TransaksiPembayaranDetail::create([
                        'transaksi_id' => $transaksiPembelian->id,
                        'metode_pembayaran' => $detail['metode_pembayaran'],
                        'jumlah_pembayaran' => $detail['jumlah_pembayaran'],
                        'tanggal_pembayaran' => $detail['tanggal_pembayaran'] ?? now()->toDateString(),
                        'keterangan_pembayaran_detail' => $detail['keterangan_pembayaran_detail'],
                        'bukti_pembayaran_detail' => $buktiPath,
                    ]);
                    $totalPembayaranInput += $detail['jumlah_pembayaran'];
                }
            }

            // 4. Perbarui Status Pembayaran berdasarkan total pembayaran yang masuk
            if ($totalPembayaranInput >= $transaksiPembelian->harga_beli_mobil_final) {
                $transaksiPembelian->status_pembayaran = 'Lunas';
            } else if ($totalPembayaranInput > 0 && $totalPembayaranInput < $transaksiPembelian->harga_beli_mobil_final) {
                $transaksiPembelian->status_pembayaran = 'Sebagian Dibayar';
            } else {
                $transaksiPembelian->status_pembayaran = 'Belum Dibayar';
            }
            $transaksiPembelian->save();

            DB::commit();

            return redirect()->route('transaksi-pembelian.index')->with('success', 'Transaksi pembelian berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error saving transaction: ' . $e->getMessage() . ' on line ' . $e->getLine() . ' in ' . $e->getFile());
            return back()->with('error', 'Terjadi kesalahan saat menyimpan transaksi: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menampilkan detail transaksi pembelian tertentu.
     */
    public function show(TransaksiPembelian $transaksiPembelian)
    {
        $transaksiPembelian->load('mobil', 'penjual', 'user', 'detailPembayaran');
        return view('transaksi_pembelian.show', compact('transaksiPembelian'));
    }

    /**
     * Menampilkan formulir untuk mengedit transaksi pembelian.
     */
    public function edit(TransaksiPembelian $transaksiPembelian)
    {
        $mobils = Mobil::all();
        $penjuals = Penjual::all();
        $transaksiPembelian->load('detailPembayaran');
        return view('transaksi_pembelian.edit', compact('transaksiPembelian', 'mobils', 'penjuals'));
    }

    /**
     * Memperbarui transaksi pembelian di database.
     */
    public function update(Request $request, TransaksiPembelian $transaksiPembelian)
    {
        $request->validate([
            'mobil_id' => 'required|exists:mobils,id',
            'penjual_id' => 'required|exists:penjuals,id',
            'tanggal_transaksi' => 'required|date',
            'harga_beli_mobil_final' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'pembayaran_detail' => 'nullable|array',
            'pembayaran_detail.*.id' => 'nullable|exists:transaksi_pembayaran_details,id',
            'pembayaran_detail.*.metode_pembayaran' => 'required_with:pembayaran_detail|string|max:50',
            'pembayaran_detail.*.jumlah_pembayaran' => 'required_with:pembayaran_detail|numeric|min:0',
            'pembayaran_detail.*.tanggal_pembayaran' => 'nullable|date',
            'pembayaran_detail.*.keterangan_pembayaran_detail' => 'nullable|string',
            'pembayaran_detail.*.bukti_pembayaran_detail' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'pembayaran_detail.*.existing_bukti_pembayaran_detail' => 'nullable|string', // Untuk menyimpan path bukti yang sudah ada
            'deleted_payment_details' => 'nullable|array',
            'deleted_payment_details.*' => 'exists:transaksi_pembayaran_details,id',
        ]);

        DB::beginTransaction();

        try {
            // Update transaksi pembelian utama
            $transaksiPembelian->update([
                'tanggal_transaksi' => $request->tanggal_transaksi,
                'mobil_id' => $request->mobil_id,
                'penjual_id' => $request->penjual_id,
                'harga_beli_mobil_final' => $request->harga_beli_mobil_final,
                'keterangan' => $request->keterangan,
            ]);

            // Hapus detail pembayaran yang ditandai untuk dihapus
            if ($request->has('deleted_payment_details')) {
                foreach ($request->deleted_payment_details as $detailId) {
                    $detailToDelete = TransaksiPembayaranDetail::find($detailId);
                    if ($detailToDelete) {
                        // Hapus file bukti jika ada
                        if ($detailToDelete->bukti_pembayaran_detail && Storage::exists(str_replace('/storage/', 'public/', $detailToDelete->bukti_pembayaran_detail))) {
                            Storage::delete(str_replace('/storage/', 'public/', $detailToDelete->bukti_pembayaran_detail));
                        }
                        $detailToDelete->delete();
                    }
                }
            }

            $totalPembayaranInput = 0;
            // Sinkronisasi detail pembayaran
            $currentPaymentDetailIds = collect($request->input('pembayaran_detail'))->pluck('id')->filter()->toArray();

            // Proses detail pembayaran yang ada di request
            if ($request->has('pembayaran_detail') && is_array($request->pembayaran_detail)) {
                foreach ($request->pembayaran_detail as $detail) {
                    $buktiPath = $detail['existing_bukti_pembayaran_detail'] ?? null; // Ambil path bukti yang sudah ada

                    if (isset($detail['bukti_pembayaran_detail']) && $detail['bukti_pembayaran_detail'] instanceof \Illuminate\Http\UploadedFile) {
                        $file = $detail['bukti_pembayaran_detail'];
                        $extension = $file->getClientOriginalExtension();
                        $fileName = 'bukti_' . Str::slug($transaksiPembelian->kode_transaksi) . '_' . uniqid() . '.' . $extension; // uniqid untuk nama unik
                        $directory = 'bukti_pembayaran';

                        // Hapus bukti lama jika ada dan berbeda dengan yang baru
                        if ($buktiPath && Storage::exists(str_replace('/storage/', 'public/', $buktiPath))) {
                            Storage::delete(str_replace('/storage/', 'public/', $buktiPath));
                        }

                        $buktiPath = $file->storeAs($directory, $fileName, 'public');
                        $buktiPath = Storage::url($buktiPath);
                    }

                    if (isset($detail['id']) && $detail['id']) {
                        // Update detail yang sudah ada
                        $transaksiPembelian->detailPembayaran()->where('id', $detail['id'])->update([
                            'metode_pembayaran' => $detail['metode_pembayaran'],
                            'jumlah_pembayaran' => $detail['jumlah_pembayaran'],
                            'tanggal_pembayaran' => $detail['tanggal_pembayaran'] ?? null,
                            'keterangan_pembayaran_detail' => $detail['keterangan_pembayaran_detail'],
                            'bukti_pembayaran_detail' => $buktiPath,
                        ]);
                    } else {
                        // Buat detail baru
                        $transaksiPembelian->detailPembayaran()->create([
                            'metode_pembayaran' => $detail['metode_pembayaran'],
                            'jumlah_pembayaran' => $detail['jumlah_pembayaran'],
                            'tanggal_pembayaran' => $detail['tanggal_pembayaran'] ?? null,
                            'keterangan_pembayaran_detail' => $detail['keterangan_pembayaran_detail'],
                            'bukti_pembayaran_detail' => $buktiPath,
                        ]);
                    }
                    $totalPembayaranInput += $detail['jumlah_pembayaran'];
                }
            }


            // Hitung ulang status pembayaran
            $totalPembayaranTerbayar = $transaksiPembelian->detailPembayaran()->sum('jumlah_pembayaran');

            if ($totalPembayaranTerbayar >= $transaksiPembelian->harga_beli_mobil_final) {
                $transaksiPembelian->status_pembayaran = 'Lunas';
            } else if ($totalPembayaranTerbayar > 0) {
                $transaksiPembelian->status_pembayaran = 'Sebagian Dibayar';
            } else {
                $transaksiPembelian->status_pembayaran = 'Belum Dibayar';
            }
            $transaksiPembelian->save();

            DB::commit();

            return redirect()->route('transaksi-pembelian.index')->with('success', 'Transaksi pembelian berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating transaction: ' . $e->getMessage() . ' on line ' . $e->getLine() . ' in ' . $e->getFile());
            return back()->with('error', 'Terjadi kesalahan saat memperbarui transaksi: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menghapus transaksi pembelian.
     */
    public function destroy(TransaksiPembelian $transaksiPembelian)
    {
        DB::beginTransaction();
        try {
            foreach ($transaksiPembelian->detailPembayaran as $detail) {
                if ($detail->bukti_pembayaran_detail && Storage::exists(str_replace('/storage/', 'public/', $detail->bukti_pembayaran_detail))) {
                    Storage::delete(str_replace('/storage/', 'public/', $detail->bukti_pembayaran_detail));
                }
                $detail->delete();
            }

            $transaksiPembelian->delete();

            DB::commit();
            return redirect()->route('transaksi-pembelian.index')->with('success', 'Transaksi pembelian berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error deleting transaction: ' . $e->getMessage() . ' on line ' . $e->getLine() . ' in ' . $e->getFile());
            return back()->with('error', 'Terjadi kesalahan saat menghapus transaksi: ' . $e->getMessage());
        }
    }
}
