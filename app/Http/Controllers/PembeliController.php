<?php

namespace App\Http\Controllers;

use App\Models\Pembeli;
use Illuminate\Http\Request;

class PembeliController extends Controller
{
    // Menampilkan daftar pembeli
    public function index()
    {
        $pembelis = Pembeli::paginate(10); // Ini akan mengembalikan Paginator
        return view('pembeli.index', compact('pembelis'));
    }

    // Menampilkan form untuk membuat pembeli baru
    public function create()
    {
        // Ambil kode pembeli terakhir yang ada di database
        $lastPembeli = Pembeli::latest()->first();

        // Jika ada pembeli sebelumnya, ambil kode terakhir dan tambahkan angka berikutnya
        if ($lastPembeli) {
            $lastKode = $lastPembeli->kode_pembeli;
            $newCode = 'PLB-' . str_pad((int) substr($lastKode, 4) + 1, 4, '0', STR_PAD_LEFT);
        } else {
            // Jika belum ada pembeli, mulai dari kode PLB-0001
            $newCode = 'PLB-0001';
        }

        // Kirim kode pembeli ke tampilan create
        return view('pembeli.create', compact('newCode'));
    }


    // Menyimpan data pembeli baru
    public function store(Request $request)
    {
        // Validasi data yang dimasukkan
        $validated = $request->validate([
            'nama' => 'required|string|min:3|regex:/^[A-Za-z\s]+$/', // Nama harus minimal 3 huruf dan hanya huruf
            'no_telepon' => 'required|digits_between:10,15', // No Telepon hanya angka dan panjangnya 10 hingga 15
            'alamat' => 'required|string|min:4', // Alamat minimal 4 karakter
            'pekerjaan' => 'required|string|min:4|regex:/^[A-Za-z\s]+$/', // Pekerjaan minimal 4 huruf dan hanya huruf
            'tanggal_lahir' => 'required|date|before:today', // Tanggal Lahir harus sebelum hari ini
        ]);

        // Menyimpan data pembeli
        Pembeli::create([
            'kode_pembeli' => $request->kode_pembeli, // Gunakan kode yang sudah di-generate
            'nama' => $request->nama,
            'no_telepon' => $request->no_telepon,
            'alamat' => $request->alamat,
            'pekerjaan' => $request->pekerjaan,
            'tanggal_lahir' => $request->tanggal_lahir,
        ]);

        return redirect()->route('pembeli.index')->with('success', 'Pembeli berhasil ditambahkan.');
    }

    // Menampilkan form untuk mengedit data pembeli
    public function edit(Pembeli $pembeli)
    {
        return view('pembeli.edit', compact('pembeli'));
    }

    // Memperbarui data pembeli
    public function update(Request $request, Pembeli $pembeli)
    {
        // Validasi data yang dimasukkan
        $validated = $request->validate([
            'nama' => 'required|string|min:3|regex:/^[A-Za-z\s]+$/', // Nama harus minimal 3 huruf dan hanya huruf
            'no_telepon' => 'required|digits_between:10,15', // No Telepon hanya angka dan panjangnya 10 hingga 15
            'alamat' => 'required|string|min:4', // Alamat minimal 4 karakter
            'pekerjaan' => 'required|string|min:4|regex:/^[A-Za-z\s]+$/', // Pekerjaan minimal 4 huruf dan hanya huruf
            'tanggal_lahir' => 'required|date|before:today', // Tanggal Lahir harus sebelum hari ini
        ]);

        // Memperbarui data pembeli
        $pembeli->update([
            'nama' => $request->nama,
            'no_telepon' => $request->no_telepon,
            'alamat' => $request->alamat,
            'pekerjaan' => $request->pekerjaan,
            'tanggal_lahir' => $request->tanggal_lahir, // Menyimpan tanggal lahir yang baru
        ]);

        return redirect()->route('pembeli.index')->with('success', 'Pembeli berhasil diperbarui.');
    }

    // Menghapus data pembeli
    public function destroy(Pembeli $pembeli)
    {
        // Hapus pembeli
        $pembeli->delete();
        return redirect()->route('pembeli.index')->with('success', 'Pembeli berhasil dihapus.');
    }
}
