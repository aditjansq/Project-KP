<?php

namespace App\Http\Controllers;

use App\Models\Penjual; // Mengubah dari Pembeli menjadi Penjual
use Illuminate\Http\Request;

class PenjualController extends Controller
{
    // Menampilkan daftar penjual
    public function index()
    {
        $penjuals = Penjual::paginate(10); // Mengubah dari $pembelis menjadi $penjuals, dan Model Pembeli menjadi Penjual
        return view('penjual.index', compact('penjuals')); // Mengubah dari 'pembeli.index' menjadi 'penjual.index'
    }

    // Menampilkan form untuk membuat penjual baru
    public function create()
    {
        // Ambil kode penjual terakhir yang ada di database
        $lastPenjual = Penjual::latest()->first(); // Mengubah dari Pembeli menjadi Penjual

        // Jika ada penjual sebelumnya, ambil kode terakhir dan tambahkan angka berikutnya
        if ($lastPenjual) {
            $lastKode = $lastPenjual->kode_penjual; // Mengubah dari kode_pembeli menjadi kode_penjual
            $newCode = 'PNJ-' . str_pad((int) substr($lastKode, 4) + 1, 4, '0', STR_PAD_LEFT); // Mengubah dari 'PLB-' menjadi 'PNJ-'
        } else {
            // Jika belum ada penjual, mulai dari kode PNJ-0001
            $newCode = 'PNJ-0001'; // Mengubah dari 'PLB-0001' menjadi 'PNJ-0001'
        }

        // Kirim kode penjual ke tampilan create
        return view('penjual.create', compact('newCode')); // Mengubah dari 'pembeli.create' menjadi 'penjual.create'
    }

    // Menyimpan data penjual baru
    public function store(Request $request)
    {
        // Validasi data yang dimasukkan
        $validated = $request->validate([
            'nama' => 'required|string|min:3|regex:/^[A-Za-z\s]+$/',
            'no_telepon' => 'required|digits_between:10,15',
            'alamat' => 'required|string|min:4',
            'pekerjaan' => 'required|string|min:4|regex:/^[A-Za-z\s]+$/',
            'tanggal_lahir' => 'required|date|before:today',
        ]);

        // Menyimpan data penjual
        Penjual::create([ // Mengubah dari Pembeli menjadi Penjual
            'kode_penjual' => $request->kode_penjual, // Mengubah dari kode_pembeli menjadi kode_penjual
            'nama' => $request->nama,
            'no_telepon' => $request->no_telepon,
            'alamat' => $request->alamat,
            'pekerjaan' => $request->pekerjaan,
            'tanggal_lahir' => $request->tanggal_lahir,
        ]);

        return redirect()->route('penjual.index')->with('success', 'Penjual berhasil ditambahkan.'); // Mengubah rute dan pesan sukses
    }

    // Menampilkan form untuk mengedit data penjual
    public function edit(Penjual $penjual) // Mengubah dari Pembeli $pembeli menjadi Penjual $penjual
    {
        return view('penjual.edit', compact('penjual')); // Mengubah dari 'pembeli.edit' menjadi 'penjual.edit'
    }

    // Memperbarui data penjual
    public function update(Request $request, Penjual $penjual) // Mengubah dari Pembeli $pembeli menjadi Penjual $penjual
    {
        // Validasi data yang dimasukkan
        $validated = $request->validate([
            'nama' => 'required|string|min:3|regex:/^[A-Za-z\s]+$/',
            'no_telepon' => 'required|digits_between:10,15',
            'alamat' => 'required|string|min:4',
            'pekerjaan' => 'required|string|min:4|regex:/^[A-Za-z\s]+$/',
            'tanggal_lahir' => 'required|date|before:today',
        ]);

        // Memperbarui data penjual
        $penjual->update([
            'nama' => $request->nama,
            'no_telepon' => $request->no_telepon,
            'alamat' => $request->alamat,
            'pekerjaan' => $request->pekerjaan,
            'tanggal_lahir' => $request->tanggal_lahir,
        ]);

        return redirect()->route('penjual.index')->with('success', 'Penjual berhasil diperbarui.'); // Mengubah rute dan pesan sukses
    }

    // Menghapus data penjual
    public function destroy(Penjual $penjual) // Mengubah dari Pembeli $pembeli menjadi Penjual $penjual
    {
        // Hapus penjual
        $penjual->delete();
        return redirect()->route('penjual.index')->with('success', 'Penjual berhasil dihapus.'); // Mengubah rute dan pesan sukses
    }
}
