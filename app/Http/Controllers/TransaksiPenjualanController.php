<?php

namespace App\Http\Controllers;

use App\Models\Mobil;
use App\Models\Pembeli;
use App\Models\TransaksiPenjualan;
use App\Models\TransaksiKreditDetail;
use App\Models\TransaksiPenjualanPembayaranDetail; // Menggunakan model baru untuk detail pembayaran penjualan
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage; // Untuk upload file

class TransaksiPenjualanController extends Controller
{
    /**
     * Menampilkan daftar transaksi penjualan.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Memuat relasi mobil, pembeli, dan pembayaranDetails
        // Gunakan paginate() alih-alih get() untuk mendapatkan instance Paginator
        $transaksis = TransaksiPenjualan::with('mobil', 'pembeli', 'pembayaranDetails')->latest()->paginate(10); // Menampilkan 10 item per halaman
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

        // **INI ADALAH BAGIAN YANG DIREVISI:**
        // Buat kode transaksi unik untuk ditampilkan di formulir
        $tanggalSekarang = now()->format('Ymd'); // Format tanggal YYYYMMDD
        $stringAcak = Str::random(4); // String acak 4 karakter untuk "YANG KE"
        $kode_transaksi_otomatis = 'CM-PJ-' . $tanggalSekarang . '-' . strtoupper($stringAcak);

        // Teruskan variabel ini ke tampilan
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
        // Validasi data transaksi penjualan utama
        $validatedTransaksi = $request->validate([
            'mobil_id' => 'required|exists:mobils,id',
            'pembeli_id' => 'required|exists:pembelis,id',
            'metode_pembayaran' => 'required|in:non_kredit,kredit', // Mengganti 'tunai' menjadi 'non_kredit'
            'harga_negosiasi' => 'required|numeric|min:0',
            'tanggal_transaksi' => 'required|date',
            // 'status' akan dihitung otomatis, jadi tidak perlu divalidasi di sini
        ]);

        // Validasi detail pembayaran
        $validatedPembayaran = $request->validate([
            'pembayaran' => 'array', // Pastikan ini adalah array
            'pembayaran.*.metode_pembayaran_detail' => 'required|string|max:255',
            'pembayaran.*.jumlah_pembayaran' => 'required|numeric|min:0',
            'pembayaran.*.tanggal_pembayaran' => 'required|date',
            'pembayaran.*.keterangan_pembayaran_detail' => 'nullable|string|max:1000',
            'pembayaran.*.bukti_pembayaran_detail' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048', // Menggunakan 'bukti_pembayaran_detail'
        ]);

        // Validasi detail kredit jika metode pembayaran adalah 'kredit'
        $validatedKredit = [];
        if ($validatedTransaksi['metode_pembayaran'] === 'kredit') {
            $validatedKredit = $request->validate([
                'dp' => 'required|numeric|min:0',
                'tempo' => 'required|integer|min:1',
                'leasing' => 'required|string|max:255',
                'angsuran_per_bulan' => 'required|numeric|min:0',
            ]);
        }

        $mobil = Mobil::findOrFail($validatedTransaksi['mobil_id']);

        // Buat kode transaksi unik dengan format CM-PJ-TANGGAL-YANG KE (menggunakan tanggal dan string acak)
        // Ini adalah kode transaksi yang akan disimpan ke database
        $tanggalSekarang = now()->format('Ymd'); // Format tanggal YYYYMMDD
        $stringAcak = Str::random(4); // String acak 4 karakter untuk "YANG KE"
        $kode_transaksi = 'CM-PJ-' . $tanggalSekarang . '-' . strtoupper($stringAcak);

        // Buat transaksi penjualan
        $transaksi = TransaksiPenjualan::create([
            'kode_transaksi' => $kode_transaksi, // Menggunakan $kode_transaksi untuk disimpan
            'mobil_id' => $validatedTransaksi['mobil_id'],
            'pembeli_id' => $validatedTransaksi['pembeli_id'],
            'metode_pembayaran' => $validatedTransaksi['metode_pembayaran'],
            'total_harga' => $mobil->harga_mobil, // Menggunakan harga mobil dari model Mobil
            'harga_negosiasi' => $validatedTransaksi['harga_negosiasi'],
            'tanggal_transaksi' => $validatedTransaksi['tanggal_transaksi'],
            'status' => 'belum lunas', // Status awal, akan diupdate setelah pembayaran diproses
        ]);

        // Simpan detail kredit jika metode pembayaran adalah kredit
        if ($validatedTransaksi['metode_pembayaran'] === 'kredit') {
            TransaksiKreditDetail::create(array_merge(
                ['transaksi_penjualan_id' => $transaksi->id],
                $validatedKredit
            ));
        }

        $totalPembayaran = 0;
        // Simpan detail pembayaran
        if (isset($validatedPembayaran['pembayaran'])) {
            foreach ($validatedPembayaran['pembayaran'] as $pembayaranData) {
                $filePath = null;
                // Upload file bukti pembayaran jika ada
                if (isset($pembayaranData['bukti_pembayaran_detail']) && $pembayaranData['bukti_pembayaran_detail'] instanceof \Illuminate\Http\UploadedFile) {
                    $fileName = Str::slug($transaksi->kode_transaksi . '-' . Str::random(10)) . '.' . $pembayaranData['bukti_pembayaran_detail']->getClientOriginalExtension();
                    $filePath = $pembayaranData['bukti_pembayaran_detail']->storeAs('public/bukti_pembayaran', $fileName);
                    $filePath = Storage::url($filePath); // Dapatkan URL publik
                }

                // Menggunakan model TransaksiPenjualanPembayaranDetail dan 'transaksi_id'
                TransaksiPenjualanPembayaranDetail::create([
                    'transaksi_id' => $transaksi->id, // Menggunakan 'transaksi_id' sebagai foreign key
                    'metode_pembayaran_detail' => $pembayaranData['metode_pembayaran_detail'],
                    'jumlah_pembayaran' => $pembayaranData['jumlah_pembayaran'],
                    'tanggal_pembayaran' => $pembayaranData['tanggal_pembayaran'],
                    'keterangan_pembayaran_detail' => $pembayaranData['keterangan_pembayaran_detail'] ?? null,
                    'bukti_pembayaran_detail' => $filePath, // Menggunakan 'bukti_pembayaran_detail'
                ]);
                $totalPembayaran += $pembayaranData['jumlah_pembayaran'];
            }
        }

        // Hitung dan update status transaksi
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
        // Memuat semua relasi yang diperlukan untuk tampilan detail
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
        // Memuat relasi kreditDetail dan pembayaranDetails (yang sekarang menunjuk ke tabel baru)
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
        // Validasi data transaksi penjualan utama
        $validatedTransaksi = $request->validate([
            'mobil_id' => 'required|exists:mobils,id',
            'pembeli_id' => 'required|exists:pembelis,id',
            'metode_pembayaran' => 'required|in:non_kredit,kredit', // Mengganti 'tunai' menjadi 'non_kredit'
            'harga_negosiasi' => 'required|numeric|min:0',
            'tanggal_transaksi' => 'required|date',
        ]);

        // Validasi detail pembayaran
        $validatedPembayaran = $request->validate([
            'pembayaran' => 'array',
            'pembayaran.*.id' => 'nullable|exists:transaksi_penjualan_pembayaran_details,id', // Diperbarui: nama tabel baru
            'pembayaran.*.metode_pembayaran_detail' => 'required|string|max:255',
            'pembayaran.*.jumlah_pembayaran' => 'required|numeric|min:0',
            'pembayaran.*.tanggal_pembayaran' => 'required|date',
            'pembayaran.*.keterangan_pembayaran_detail' => 'nullable|string|max:1000',
            'pembayaran.*.bukti_pembayaran_detail' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048', // Menggunakan 'bukti_pembayaran_detail'
            'pembayaran.*.delete_file_bukti' => 'nullable|boolean', // Untuk menghapus file bukti yang sudah ada
        ]);

        // Validasi detail kredit jika metode pembayaran adalah 'kredit'
        $validatedKredit = [];
        if ($validatedTransaksi['metode_pembayaran'] === 'kredit') {
            $validatedKredit = $request->validate([
                'dp' => 'required|numeric|min:0',
                'tempo' => 'required|integer|min:1',
                'leasing' => 'required|string|max:255',
                'angsuran_per_bulan' => 'required|numeric|min:0',
            ]);
        }

        $mobil = Mobil::findOrFail($validatedTransaksi['mobil_id']);

        // Update transaksi penjualan utama
        $transaksi_penjualan->update([
            'mobil_id' => $validatedTransaksi['mobil_id'],
            'pembeli_id' => $validatedTransaksi['pembeli_id'],
            'metode_pembayaran' => $validatedTransaksi['metode_pembayaran'],
            'total_harga' => $mobil->harga_mobil,
            'harga_negosiasi' => $validatedTransaksi['harga_negosiasi'],
            'tanggal_transaksi' => $validatedTransaksi['tanggal_transaksi'],
            // Status akan diupdate setelah pembayaran diproses
        ]);

        // Update atau buat detail kredit
        if ($validatedTransaksi['metode_pembayaran'] === 'kredit') {
            $transaksi_penjualan->kreditDetail()->updateOrCreate(
                ['transaksi_penjualan_id' => $transaksi_penjualan->id],
                $validatedKredit
            );
        } else {
            // Jika metode pembayaran berubah menjadi non-kredit, hapus detail kredit
            $transaksi_penjualan->kreditDetail()->delete();
        }

        $totalPembayaran = 0;
        // Mengambil ID pembayaran dari tabel baru
        $existingPaymentIds = $transaksi_penjualan->pembayaranDetails->pluck('id')->toArray();
        $submittedPaymentIds = [];

        // Proses detail pembayaran
        if (isset($validatedPembayaran['pembayaran'])) {
            foreach ($validatedPembayaran['pembayaran'] as $pembayaranData) {
                $filePath = null;
                $currentPayment = null;

                // Menggunakan model baru untuk mencari pembayaran
                if (isset($pembayaranData['id'])) {
                    $currentPayment = TransaksiPenjualanPembayaranDetail::find($pembayaranData['id']);
                    $submittedPaymentIds[] = $pembayaranData['id'];
                }

                // Hapus file bukti yang sudah ada jika diminta
                if ($currentPayment && isset($pembayaranData['delete_file_bukti']) && $pembayaranData['delete_file_bukti']) {
                    if ($currentPayment->bukti_pembayaran_detail && Storage::exists(str_replace('/storage/', 'public/', $currentPayment->bukti_pembayaran_detail))) {
                        Storage::delete(str_replace('/storage/', 'public/', $currentPayment->bukti_pembayaran_detail));
                    }
                    $currentPayment->bukti_pembayaran_detail = null;
                }

                // Upload file bukti pembayaran baru jika ada
                if (isset($pembayaranData['bukti_pembayaran_detail']) && $pembayaranData['bukti_pembayaran_detail'] instanceof \Illuminate\Http\UploadedFile) {
                    // Hapus file lama jika ada sebelum mengupload yang baru
                    if ($currentPayment && $currentPayment->bukti_pembayaran_detail && Storage::exists(str_replace('/storage/', 'public/', $currentPayment->bukti_pembayaran_detail))) {
                        Storage::delete(str_replace('/storage/', 'public/', $currentPayment->bukti_pembayaran_detail));
                    }
                    $fileName = Str::slug($transaksi_penjualan->kode_transaksi . '-' . Str::random(10)) . '.' . $pembayaranData['bukti_pembayaran_detail']->getClientOriginalExtension();
                    $filePath = $pembayaranData['bukti_pembayaran_detail']->storeAs('public/bukti_pembayaran', $fileName);
                    $filePath = Storage::url($filePath);
                } else if ($currentPayment) {
                    // Pertahankan file bukti yang sudah ada jika tidak ada upload baru atau penghapusan
                    $filePath = $currentPayment->bukti_pembayaran_detail;
                }

                $paymentDataToSave = [
                    'metode_pembayaran_detail' => $pembayaranData['metode_pembayaran_detail'],
                    'jumlah_pembayaran' => $pembayaranData['jumlah_pembayaran'],
                    'tanggal_pembayaran' => $pembayaranData['tanggal_pembayaran'],
                    'keterangan_pembayaran_detail' => $pembayaranData['keterangan_pembayaran_detail'] ?? null,
                    'bukti_pembayaran_detail' => $filePath, // Menggunakan 'bukti_pembayaran_detail'
                ];

                if ($currentPayment) {
                    $currentPayment->update($paymentDataToSave);
                } else {
                    // Menggunakan model baru dan 'transaksi_id'
                    TransaksiPenjualanPembayaranDetail::create(array_merge(
                        ['transaksi_id' => $transaksi_penjualan->id], // Menggunakan 'transaksi_id'
                        $paymentDataToSave
                    ));
                }
                $totalPembayaran += $pembayaranData['jumlah_pembayaran'];
            }
        }

        // Hapus pembayaran yang tidak lagi ada di request dari tabel baru
        $paymentsToDelete = array_diff($existingPaymentIds, $submittedPaymentIds);
        foreach ($paymentsToDelete as $paymentId) {
            // Menggunakan model baru untuk menghapus pembayaran
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
        // Hapus semua file bukti pembayaran terkait dari tabel baru
        foreach ($transaksi_penjualan->pembayaranDetails as $detail) {
            if ($detail->bukti_pembayaran_detail && Storage::exists(str_replace('/storage/', 'public/', $detail->bukti_pembayaran_detail))) {
                Storage::delete(str_replace('/storage/', 'public/', $detail->bukti_pembayaran_detail));
            }
        }

        // Hapus semua detail pembayaran terkait dari tabel baru
        $transaksi_penjualan->pembayaranDetails()->delete();

        // Hapus detail kredit jika ada
        $transaksi_penjualan->kreditDetail()->delete();

        // Hapus transaksi penjualan itu sendiri
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
