<?php

namespace App\Http\Controllers;

use App\Models\Penjual; // Mengubah dari Pembeli menjadi Penjual
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PenjualController extends Controller
{
    /**
     * Menampilkan daftar penjual.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Mengambil data penjual dengan paginasi
        $penjuals = Penjual::paginate(10);
        return view('penjual.index', compact('penjuals')); // Mengubah rute view
    }

    /**
     * Menampilkan form untuk membuat penjual baru.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Mendapatkan kode penjual terakhir untuk pembuatan kode otomatis
        $lastPenjual = Penjual::latest()->first();
        if ($lastPenjual) {
            $lastKode = $lastPenjual->kode_penjual; // Mengubah dari kode_pembeli
            if (str_starts_with($lastKode, 'PNJ-')) { // Mengubah prefix
                $lastNumber = (int) substr($lastKode, 4);
            } else {
                $lastNumber = 0;
            }
            $newCode = 'PNJ-' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT); // Mengubah prefix
        } else {
            $newCode = 'PNJ-' . str_pad(1, 4, '0', STR_PAD_LEFT); // Mengubah prefix
        }
        return view('penjual.create', compact('newCode')); // Mengubah rute view
    }

    /**
     * Menyimpan data penjual baru.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validasi data yang masuk dari request
        $validatedData = $request->validate([
            'kode_penjual' => 'required|string|unique:penjuals,kode_penjual|max:255', // Mengubah tabel dan kolom
            'nama' => 'required|string|min:3|regex:/^[A-Za-z\s]+$/',
            'tanggal_lahir' => 'required|date|before_or_equal:today',
            'no_telepon' => 'nullable|digits_between:10,15',
            'alamat' => 'required|string|min:4',
            'pekerjaan' => 'nullable|string|min:4|regex:/^[A-Za-z\s]+$/',
            'ktp_pasangan' => 'nullable|file|mimes:jpeg,png,pdf|max:2048', // Hanya ktp_pasangan
        ]);

        // Mengambil semua data kecuali token dan file yang akan diunggah
        $data = $request->except(['_token', 'ktp_pasangan']); // Hanya ktp_pasangan
        $kodePenjual = $request->input('kode_penjual');

        // Jalur penyimpanan dasar relatif terhadap root disk 'public' (storage/app/public)
        $storageFolder = 'documents/penjual'; // Tetap di documents/penjual

        // Tangani unggahan KTP Pasangan
        if ($request->hasFile('ktp_pasangan')) {
            $file = $request->file('ktp_pasangan');
            $fileName = $kodePenjual . '-KTP_PASANGAN-' . Carbon::now()->format('YmdHis') . '.' . $file->getClientOriginalExtension();
            // Simpan file ke dalam 'documents/penjual' di disk 'public'
            $data['ktp_pasangan'] = Storage::disk('public')->putFileAs($storageFolder, $file, $fileName);
        }

        // Membuat entri penjual baru di database
        Penjual::create($data);

        return redirect()->route('penjual.index')->with('success', 'Penjual berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit data penjual.
     *
     * @param  \App\Models\Penjual  $penjual
     * @return \Illuminate\View\View
     */
    public function edit(Penjual $penjual)
    {
        return view('penjual.edit', compact('penjual'));
    }

    /**
     * Memperbarui data penjual.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Penjual  $penjual
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Penjual $penjual)
    {
        // Validasi data yang masuk dari request
        $validatedData = $request->validate([
            'nama' => 'required|string|min:3|regex:/^[A-Za-z\s]+$/',
            'tanggal_lahir' => 'required|date|before_or_equal:today',
            'no_telepon' => 'nullable|digits_between:10,15',
            'alamat' => 'required|string|min:4',
            'pekerjaan' => 'nullable|string|min:4|regex:/^[A-Za-z\s]+$/',
            'ktp_pasangan' => 'nullable|file|mimes:jpeg,png,pdf|max:2048', // Hanya ktp_pasangan
        ]);

        // Mengambil semua data kecuali token, method, dan file yang akan diunggah
        $data = $request->except(['_token', '_method', 'ktp_pasangan']); // Hanya ktp_pasangan
        $kodePenjual = $penjual->kode_penjual;

        // Jalur penyimpanan dasar relatif terhadap root disk 'public' (storage/app/public)
        $storageFolder = 'documents/penjual'; // Tetap di documents/penjual

        // Tangani unggahan KTP Pasangan
        if ($request->hasFile('ktp_pasangan')) {
            // Hapus file lama jika ada
            if ($penjual->ktp_pasangan && Storage::disk('public')->exists($penjual->ktp_pasangan)) {
                Storage::disk('public')->delete($penjual->ktp_pasangan);
            }
            $file = $request->file('ktp_pasangan');
            $fileName = $kodePenjual . '-KTP_PASANGAN-' . Carbon::now()->format('YmdHis') . '.' . $file->getClientOriginalExtension();
            $data['ktp_pasangan'] = Storage::disk('public')->putFileAs($storageFolder, $file, $fileName);
        }

        // Memperbarui entri penjual di database
        $penjual->update($data);

        return redirect()->route('penjual.index')->with('success', 'Penjual berhasil diperbarui.');
    }

    /**
     * Menghapus data penjual.
     *
     * @param  \App\Models\Penjual  $penjual
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Penjual $penjual)
    {
        // Hapus file terkait sebelum menghapus penjual
        if ($penjual->ktp_pasangan && Storage::disk('public')->exists($penjual->ktp_pasangan)) {
            Storage::disk('public')->delete($penjual->ktp_pasangan);
        }

        // Menghapus entri penjual dari database
        $penjual->delete();
        return redirect()->route('penjual.index')->with('success', 'Penjual berhasil dihapus.');
    }
}
