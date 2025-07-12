<?php

namespace App\Http\Controllers;

use App\Models\Mobil;
use App\Models\Pembeli;
use App\Models\TransaksiPenjualan;
use App\Models\TransaksiKreditDetail;
use App\Models\TransaksiPenjualanPembayaranDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon; // Pastikan Carbon sudah diimpor

class TransaksiPenjualanController extends Controller
{
    /**
     * Menampilkan daftar transaksi penjualan.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $transaksis = TransaksiPenjualan::with('mobil', 'pembeli', 'pembayaranDetails')->latest()->paginate(10);
        return view('transaksi_penjualan.index', compact('transaksis'));
    }

    /**
     * Menampilkan form untuk membuat transaksi penjualan baru.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $mobils = Mobil::all();
        $pembelis = Pembeli::all();

        // Tanggal sekarang dengan format DDMMYY
        $tanggalSekarang = now()->format('dmy');

        // Dapatkan nomor urut transaksi terakhir secara global
        $lastTransaksi = TransaksiPenjualan::latest('id')->first(); // Ambil transaksi terakhir berdasarkan ID

        $urutan = 1; // Default jika belum ada transaksi sama sekali
        if ($lastTransaksi) {
            // Asumsi format kode_transaksi: CM-PJ-DDMMYY-XXX atau CM-PJ-YYYYMMDD-XXX dari format sebelumnya
            // Kita perlu mengambil bagian terakhir setelah tanda hubung terakhir
            $parts = explode('-', $lastTransaksi->kode_transaksi);
            if (count($parts) > 1) { // Pastikan ada setidaknya satu tanda hubung
                $lastPart = end($parts); // Ambil bagian terakhir
                // Coba konversi bagian terakhir ke integer, jika gagal, anggap 0
                $lastUrutan = (int) $lastPart;
                $urutan = $lastUrutan + 1;
            }
        }

        // Format nomor urut menjadi 3 digit (misal: 001, 010, 100)
        $nomorUrutFormatted = sprintf('%03d', $urutan);

        $kode_transaksi_otomatis = 'CM-PJ-' . $tanggalSekarang . '-' . $nomorUrutFormatted;

        return view('transaksi_penjualan.create', compact('mobils', 'pembelis', 'kode_transaksi_otomatis'));
    }

    /**
     * Menyimpan transaksi penjualan yang baru dibuat ke database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validatedTransaksi = $request->validate([
            'mobil_id' => 'required|exists:mobils,id',
            'pembeli_id' => 'required|exists:pembelis,id',
            'metode_pembayaran' => 'required|in:non_kredit,kredit',
            'harga_negosiasi' => 'required|numeric|min:0',
            'tanggal_transaksi' => 'required|date',
        ]);

        $validatedPembayaran = $request->validate([
            'pembayaran' => 'array',
            'pembayaran.*.metode_pembayaran_detail' => 'required|string|max:255',
            'pembayaran.*.jumlah_pembayaran' => 'required|numeric|min:0',
            'pembayaran.*.tanggal_pembayaran' => 'required|date',
            'pembayaran.*.keterangan_pembayaran_detail' => 'nullable|string|max:1000',
            'pembayaran.*.bukti_pembayaran_detail' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $validatedKredit = [];
        if ($validatedTransaksi['metode_pembayaran'] === 'kredit') {
            $validatedKredit = $request->validate([
                'dp' => 'required|numeric|min:0',
                'tempo' => 'required|integer|min:1',
                'leasing' => 'required|string|max:255',
                'angsuran_per_bulan' => 'required|numeric|min:0',
                'refund' => 'nullable|numeric|min:0', // <--- TAMBAHKAN BARIS INI

            ]);
        }

        $mobil = Mobil::findOrFail($validatedTransaksi['mobil_id']);

        // Tanggal sekarang dengan format DDMMYY
        $tanggalSekarang = now()->format('dmy');

        // Dapatkan nomor urut transaksi terakhir secara global
        $lastTransaksi = TransaksiPenjualan::latest('id')->first();

        $urutan = 1;
        if ($lastTransaksi) {
            $parts = explode('-', $lastTransaksi->kode_transaksi);
            if (count($parts) > 1) {
                $lastPart = end($parts);
                $lastUrutan = (int) $lastPart;
                $urutan = $lastUrutan + 1;
            }
        }
        $nomorUrutFormatted = sprintf('%03d', $urutan);
        $kode_transaksi = 'CM-PJ-' . $tanggalSekarang . '-' . $nomorUrutFormatted;

        $transaksi = TransaksiPenjualan::create([
            'kode_transaksi' => $kode_transaksi,
            'mobil_id' => $validatedTransaksi['mobil_id'],
            'pembeli_id' => $validatedTransaksi['pembeli_id'],
            'metode_pembayaran' => $validatedTransaksi['metode_pembayaran'],
            'total_harga' => $mobil->harga_mobil,
            'harga_negosiasi' => $validatedTransaksi['harga_negosiasi'],
            'tanggal_transaksi' => $validatedTransaksi['tanggal_transaksi'],
            'status' => 'belum lunas',
        ]);

        if ($validatedTransaksi['metode_pembayaran'] === 'kredit') {
            TransaksiKreditDetail::create(array_merge(
                ['transaksi_penjualan_id' => $transaksi->id],
                $validatedKredit
            ));
        }

        $totalPembayaran = 0;
        if (isset($validatedPembayaran['pembayaran'])) {
            foreach ($validatedPembayaran['pembayaran'] as $pembayaranData) {
                $filePath = null;
                if (isset($pembayaranData['bukti_pembayaran_detail']) && $pembayaranData['bukti_pembayaran_detail'] instanceof \Illuminate\Http\UploadedFile) {
                    $fileName = Str::slug($transaksi->kode_transaksi . '-' . Str::random(10)) . '.' . $pembayaranData['bukti_pembayaran_detail']->getClientOriginalExtension();
                    $filePath = $pembayaranData['bukti_pembayaran_detail']->storeAs('public/bukti_pembayaran', $fileName);
                    $filePath = Storage::url($filePath);
                }

                TransaksiPenjualanPembayaranDetail::create([
                    'transaksi_id' => $transaksi->id,
                    'metode_pembayaran_detail' => $pembayaranData['metode_pembayaran_detail'],
                    'jumlah_pembayaran' => $pembayaranData['jumlah_pembayaran'],
                    'tanggal_pembayaran' => $pembayaranData['tanggal_pembayaran'],
                    'keterangan_pembayaran_detail' => $pembayaranData['keterangan_pembayaran_detail'] ?? null,
                    'bukti_pembayaran_detail' => $filePath,
                ]);
                $totalPembayaran += $pembayaranData['jumlah_pembayaran'];
            }
        }

        $this->updateTransaksiStatus($transaksi, $totalPembayaran);

        return redirect()->route('transaksi-penjualan.index')->with('success', 'Transaksi berhasil disimpan.');
    }

    /**
     * Menampilkan detail transaksi penjualan tertentu.
     *
     * @param  \App\Models\TransaksiPenjualan  $transaksi_penjualan
     * @return \Illuminate\View\View
     */
    public function show(TransaksiPenjualan $transaksi_penjualan)
    {
        $transaksi_penjualan->load('mobil', 'pembeli', 'kreditDetail', 'pembayaranDetails');
        return view('transaksi_penjualan.show', compact('transaksi_penjualan'));
    }

    /**
     * Menampilkan form untuk mengedit transaksi penjualan.
     *
     * @param  \App\Models\TransaksiPenjualan  $transaksi_penjualan
     * @return \Illuminate\View\View
     */
    public function edit(TransaksiPenjualan $transaksi_penjualan)
    {
        $mobils = Mobil::all();
        $pembelis = Pembeli::all();
        $transaksi_penjualan->load('kreditDetail', 'pembayaranDetails');

        return view('transaksi_penjualan.edit', compact('transaksi_penjualan', 'mobils', 'pembelis'));
    }

    /**
     * Memperbarui transaksi penjualan yang ada di database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TransaksiPenjualan  $transaksi_penjualan
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, TransaksiPenjualan $transaksi_penjualan)
    {
        $validatedTransaksi = $request->validate([
            'mobil_id' => 'required|exists:mobils,id',
            'pembeli_id' => 'required|exists:pembelis,id',
            'metode_pembayaran' => 'required|in:non_kredit,kredit',
            'harga_negosiasi' => 'required|numeric|min:0',
            'tanggal_transaksi' => 'required|date',
        ]);

        $validatedPembayaran = $request->validate([
            'pembayaran' => 'array',
            'pembayaran.*.id' => 'nullable|exists:transaksi_penjualan_pembayaran_details,id',
            'pembayaran.*.metode_pembayaran_detail' => 'required|string|max:255',
            'pembayaran.*.jumlah_pembayaran' => 'required|numeric|min:0',
            'pembayaran.*.tanggal_pembayaran' => 'required|date',
            'pembayaran.*.keterangan_pembayaran_detail' => 'nullable|string|max:1000',
            'pembayaran.*.bukti_pembayaran_detail' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'pembayaran.*.delete_file_bukti' => 'nullable|boolean',
        ]);

        $validatedKredit = [];
        if ($validatedTransaksi['metode_pembayaran'] === 'kredit') {
            $validatedKredit = $request->validate([
                'dp' => 'required|numeric|min:0',
                'tempo' => 'required|integer|min:1',
                'leasing' => 'required|string|max:255',
                'angsuran_per_bulan' => 'required|numeric|min:0',
                'refund' => 'nullable|numeric|min:0', // <--- TAMBAHKAN BARIS INI

            ]);
        }

        $mobil = Mobil::findOrFail($validatedTransaksi['mobil_id']);

        $transaksi_penjualan->update([
            'mobil_id' => $validatedTransaksi['mobil_id'],
            'pembeli_id' => $validatedTransaksi['pembeli_id'],
            'metode_pembayaran' => $validatedTransaksi['metode_pembayaran'],
            'total_harga' => $mobil->harga_mobil,
            'harga_negosiasi' => $validatedTransaksi['harga_negosiasi'],
            'tanggal_transaksi' => $validatedTransaksi['tanggal_transaksi'],
        ]);

        if ($validatedTransaksi['metode_pembayaran'] === 'kredit') {
            $transaksi_penjualan->kreditDetail()->updateOrCreate(
                ['transaksi_penjualan_id' => $transaksi_penjualan->id],
                $validatedKredit
            );
        } else {
            $transaksi_penjualan->kreditDetail()->delete();
        }

        $totalPembayaran = 0;
        $existingPaymentIds = $transaksi_penjualan->pembayaranDetails->pluck('id')->toArray();
        $submittedPaymentIds = [];

        if (isset($validatedPembayaran['pembayaran'])) {
            foreach ($validatedPembayaran['pembayaran'] as $pembayaranData) {
                $filePath = null;
                $currentPayment = null;

                if (isset($pembayaranData['id'])) {
                    $currentPayment = TransaksiPenjualanPembayaranDetail::find($pembayaranData['id']);
                    $submittedPaymentIds[] = $pembayaranData['id'];
                }

                if ($currentPayment && isset($pembayaranData['delete_file_bukti']) && $pembayaranData['delete_file_bukti']) {
                    if ($currentPayment->bukti_pembayaran_detail && Storage::exists(str_replace('/storage/', 'public/', $currentPayment->bukti_pembayaran_detail))) {
                        Storage::delete(str_replace('/storage/', 'public/', $currentPayment->bukti_pembayaran_detail));
                    }
                    $currentPayment->bukti_pembayaran_detail = null;
                }

                if (isset($pembayaranData['bukti_pembayaran_detail']) && $pembayaranData['bukti_pembayaran_detail'] instanceof \Illuminate\Http\UploadedFile) {
                    if ($currentPayment && $currentPayment->bukti_pembayaran_detail && Storage::exists(str_replace('/storage/', 'public/', $currentPayment->bukti_pembayaran_detail))) {
                        Storage::delete(str_replace('/storage/', 'public/', $currentPayment->bukti_pembayaran_detail));
                    }
                    $fileName = Str::slug($transaksi_penjualan->kode_transaksi . '-' . Str::random(10)) . '.' . $pembayaranData['bukti_pembayaran_detail']->getClientOriginalExtension();
                    $filePath = $pembayaranData['bukti_pembayaran_detail']->storeAs('public/bukti_pembayaran', $fileName);
                    $filePath = Storage::url($filePath);
                } else if ($currentPayment) {
                    $filePath = $currentPayment->bukti_pembayaran_detail;
                }

                $paymentDataToSave = [
                    'metode_pembayaran_detail' => $pembayaranData['metode_pembayaran_detail'],
                    'jumlah_pembayaran' => $pembayaranData['jumlah_pembayaran'],
                    'tanggal_pembayaran' => $pembayaranData['tanggal_pembayaran'],
                    'keterangan_pembayaran_detail' => $pembayaranData['keterangan_pembayaran_detail'] ?? null,
                    'bukti_pembayaran_detail' => $filePath,
                ];

                if ($currentPayment) {
                    $currentPayment->update($paymentDataToSave);
                } else {
                    TransaksiPenjualanPembayaranDetail::create(array_merge(
                        ['transaksi_id' => $transaksi_penjualan->id],
                        $paymentDataToSave
                    ));
                }
                $totalPembayaran += $pembayaranData['jumlah_pembayaran'];
            }
        }

        $paymentsToDelete = array_diff($existingPaymentIds, $submittedPaymentIds);
        foreach ($paymentsToDelete as $paymentId) {
            $payment = TransaksiPenjualanPembayaranDetail::find($paymentId);
            if ($payment) {
                if ($payment->bukti_pembayaran_detail && Storage::exists(str_replace('/storage/', 'public/', $payment->bukti_pembayaran_detail))) {
                    Storage::delete(str_replace('/storage/', 'public/', $payment->bukti_pembayaran_detail));
                }
                $payment->delete();
            }
        }

        $this->updateTransaksiStatus($transaksi_penjualan, $totalPembayaran);

        return redirect()->route('transaksi-penjualan.index')->with('success', 'Transaksi berhasil diperbarui.');
    }

    /**
     * Menghapus transaksi penjualan dari database.
     *
     * @param  \App\Models\TransaksiPenjualan  $transaksi_penjualan
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(TransaksiPenjualan $transaksi_penjualan)
    {
        foreach ($transaksi_penjualan->pembayaranDetails as $detail) {
            if ($detail->bukti_pembayaran_detail && Storage::exists(str_replace('/storage/', 'public/', $detail->bukti_pembayaran_detail))) {
                Storage::delete(str_replace('/storage/', 'public/', $detail->bukti_pembayaran_detail));
            }
        }

        $transaksi_penjualan->pembayaranDetails()->delete();
        $transaksi_penjualan->kreditDetail()->delete();
        $transaksi_penjualan->delete();

        return redirect()->route('transaksi-penjualan.index')->with('success', 'Transaksi berhasil dihapus.');
    }

    /**
     * Fungsi helper untuk mengupdate status transaksi berdasarkan total pembayaran.
     *
     * @param \App\Models\TransaksiPenjualan $transaksi
     * @param float $totalPembayaran
     * @return void
     */
    private function updateTransaksiStatus(TransaksiPenjualan $transaksi, float $totalPembayaran)
    {
        $status = 'belum lunas';
        if ($totalPembayaran >= $transaksi->harga_negosiasi) {
            $status = 'lunas';
        } elseif ($totalPembayaran > 0 && $totalPembayaran < $transaksi->harga_negosiasi) {
            $status = 'dp';
        }

        $transaksi->update(['status' => $status]);
    }
}
