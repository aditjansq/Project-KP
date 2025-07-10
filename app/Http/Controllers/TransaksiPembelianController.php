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
     * Menampilkan daftar transaksi pembelian.
     */
    public function index()
    {
        $transaksis = TransaksiPembelian::with(['mobil', 'penjual', 'user'])->latest()->paginate(10);
        return view('transaksi_pembelian.index', compact('transaksis'));
    }

    /**
     * Menampilkan formulir untuk membuat transaksi pembelian baru.
     */
    public function create()
    {
        $mobils = Mobil::all();
        $penjuals = Penjual::all();

        $currentDate = now();
        $dateFormatted = $currentDate->format('Ymd');

        $countTodayTransactions = TransaksiPembelian::whereDate('created_at', $currentDate->toDateString())->count();
        $sequenceNumber = $countTodayTransactions + 1;

        $kode_transaksi = 'CM-PO-' . $dateFormatted . '-' . str_pad($sequenceNumber, 3, '0', STR_PAD_LEFT);

        return view('transaksi_pembelian.create', compact('mobils', 'penjuals', 'kode_transaksi'));
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
            'kode_transaksi' => 'required|string|max:255|unique:transaksi_pembelians,kode_transaksi',
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
            // 2. Simpan Transaksi Pembelian Utama
            $transaksiPembelian = TransaksiPembelian::create([
                'kode_transaksi' => $request->kode_transaksi,
                'tanggal_transaksi' => $request->tanggal_transaksi,
                'mobil_id' => $request->mobil_id,
                'penjual_id' => $request->penjual_id,
                'harga_beli_mobil_final' => $request->harga_beli_mobil_final,
                'status_pembayaran' => 'Belum Dibayar',
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

            // 4. Perbarui Status Pembayaran
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
     * Menampilkan detail satu transaksi.
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
            'kode_transaksi' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'pembayaran_detail' => 'nullable|array',
            'pembayaran_detail.*.metode_pembayaran' => 'required_with:pembayaran_detail|string|max:50',
            'pembayaran_detail.*.jumlah_pembayaran' => 'required_with:pembayaran_detail|numeric|min:0',
            'pembayaran_detail.*.tanggal_pembayaran' => 'nullable|date',
            'pembayaran_detail.*.keterangan_pembayaran_detail' => 'nullable|string',
            'pembayaran_detail.*.bukti_pembayaran_detail' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'pembayaran_detail.*.id' => 'nullable|exists:transaksi_pembayaran_details,id',
            'pembayaran_detail.*.delete_file_bukti' => 'nullable|boolean',
            'pembayaran_detail.*.existing_bukti_pembayaran_detail' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            // 1. Update Transaksi Pembelian Utama
            $transaksiPembelian->update([
                'mobil_id' => $request->mobil_id,
                'penjual_id' => $request->penjual_id,
                'tanggal_transaksi' => $request->tanggal_transaksi,
                'harga_beli_mobil_final' => $request->harga_beli_mobil_final,
                'keterangan' => $request->keterangan,
            ]);

            // Ambil ID detail pembayaran yang sudah ada di database
            $existingPaymentIds = $transaksiPembelian->detailPembayaran->pluck('id')->toArray();
            $updatedPaymentIds = []; // Untuk melacak ID yang ada di request dan berhasil diproses

            $totalPembayaranInput = 0;

            // 2. Proses detail pembayaran dari request
            if ($request->has('pembayaran_detail') && is_array($request->pembayaran_detail)) {
                foreach ($request->pembayaran_detail as $pembayaranData) {
                    $transaksiPembayaranDetail = null;
                    $buktiPath = $pembayaranData['existing_bukti_pembayaran_detail'] ?? null; // Default ke path yang sudah ada

                    // Jika ada ID pembayaran (berarti ini item yang sudah ada)
                    if (isset($pembayaranData['id']) && $pembayaranData['id']) {
                        $transaksiPembayaranDetail = TransaksiPembayaranDetail::find($pembayaranData['id']);
                        if ($transaksiPembayaranDetail) {
                            $updatedPaymentIds[] = $transaksiPembayaranDetail->id; // Tambahkan ke daftar ID yang diperbarui
                        }
                    }

                    // Handle penghapusan file lama jika checkbox dicentang
                    if (isset($pembayaranData['delete_file_bukti']) && $pembayaranData['delete_file_bukti'] == '1') {
                        if ($transaksiPembayaranDetail && $transaksiPembayaranDetail->bukti_pembayaran_detail && Storage::exists(str_replace('/storage/', 'public/', $transaksiPembayaranDetail->bukti_pembayaran_detail))) {
                            Storage::delete(str_replace('/storage/', 'public/', $transaksiPembayaranDetail->bukti_pembayaran_detail));
                        }
                        $buktiPath = null; // Set path ke null karena file dihapus
                    }

                    // Handle unggahan file baru
                    if (isset($pembayaranData['bukti_pembayaran_detail']) && $pembayaranData['bukti_pembayaran_detail'] instanceof \Illuminate\Http\UploadedFile) {
                        // Hapus file lama jika ada dan merupakan update
                        if ($transaksiPembayaranDetail && $transaksiPembayaranDetail->bukti_pembayaran_detail && Storage::exists(str_replace('/storage/', 'public/', $transaksiPembayaranDetail->bukti_pembayaran_detail))) {
                            Storage::delete(str_replace('/storage/', 'public/', $transaksiPembayaranDetail->bukti_pembayaran_detail));
                        }
                        $file = $pembayaranData['bukti_pembayaran_detail'];
                        $extension = $file->getClientOriginalExtension();
                        $fileName = 'bukti_' . Str::slug($transaksiPembelian->kode_transaksi) . '_' . time() . '.' . $extension;
                        $directory = 'bukti_pembayaran';
                        $buktiPath = $file->storeAs($directory, $fileName, 'public');
                        $buktiPath = Storage::url($buktiPath); // Dapatkan URL publik
                    }
                    // Jika tidak ada file baru diupload DAN tidak ada permintaan hapus, pertahankan file yang sudah ada
                    else if (!isset($pembayaranData['delete_file_bukti']) || $pembayaranData['delete_file_bukti'] != '1') {
                        $buktiPath = $pembayaranData['existing_bukti_pembayaran_detail'] ?? null;
                    }


                    $dataUntukDisimpan = [
                        'metode_pembayaran' => $pembayaranData['metode_pembayaran'],
                        'jumlah_pembayaran' => $pembayaranData['jumlah_pembayaran'],
                        'tanggal_pembayaran' => $pembayaranData['tanggal_pembayaran'] ?? now()->toDateString(),
                        'keterangan_pembayaran_detail' => $pembayaranData['keterangan_pembayaran_detail'] ?? null,
                        'bukti_pembayaran_detail' => $buktiPath,
                    ];

                    if ($transaksiPembayaranDetail) {
                        // Update detail pembayaran yang sudah ada
                        $transaksiPembayaranDetail->update($dataUntukDisimpan);
                    } else {
                        // Buat detail pembayaran baru
                        $transaksiPembelian->detailPembayaran()->create($dataUntukDisimpan);
                    }
                    $totalPembayaranInput += $pembayaranData['jumlah_pembayaran'];
                }
            }

            // 3. Hapus detail pembayaran yang ada di database tetapi tidak ada di request (yang dihapus dari form)
            $pembayaranToDelete = array_diff($existingPaymentIds, $updatedPaymentIds);
            foreach ($pembayaranToDelete as $id) {
                $detail = TransaksiPembayaranDetail::find($id);
                if ($detail) {
                    // Hapus file bukti jika ada sebelum menghapus record
                    if ($detail->bukti_pembayaran_detail && Storage::exists(str_replace('/storage/', 'public/', $detail->bukti_pembayaran_detail))) {
                        Storage::delete(str_replace('/storage/', 'public/', $detail->bukti_pembayaran_detail));
                    }
                    $detail->delete();
                }
            }

            // 4. Perbarui Status Pembayaran
            if ($totalPembayaranInput >= $transaksiPembelian->harga_beli_mobil_final) {
                $transaksiPembelian->status_pembayaran = 'Lunas';
            } else if ($totalPembayaranInput > 0 && $totalPembayaranInput < $transaksiPembelian->harga_beli_mobil_final) {
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
