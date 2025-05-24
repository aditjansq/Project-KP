<?php

namespace App\Http\Controllers;

use App\Models\Pembeli;
use Illuminate\Http\Request;

class PembeliController extends Controller
{
    public function index()
    {
        $pembelis = Pembeli::latest()->get();
        return view('pembeli.index', compact('pembelis'));
    }

    public function create()
    {
        return view('pembeli.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'tanggal_lahir' => 'required|date',
            'pekerjaan' => 'required',
            'alamat' => 'required',
            'no_telepon' => 'required',
        ]);

        $last = Pembeli::latest()->first();
        $kode = 'PLB-' . str_pad(optional($last)->id + 1, 4, '0', STR_PAD_LEFT);

        Pembeli::create([
            'kode_pembeli' => $kode,
            'nama' => $request->nama,
            'tanggal_lahir' => $request->tanggal_lahir,
            'pekerjaan' => $request->pekerjaan,
            'alamat' => $request->alamat,
            'no_telepon' => $request->no_telepon,
        ]);

        return redirect()->route('pembeli.index')->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit(Pembeli $pembeli)
    {
        return view('pembeli.edit', compact('pembeli'));
    }

    public function update(Request $request, Pembeli $pembeli)
    {
        $request->validate([
            'nama' => 'required',
            'tanggal_lahir' => 'required|date',
            'pekerjaan' => 'required',
            'alamat' => 'required',
            'no_telepon' => 'required',
        ]);

        $pembeli->update($request->all());

        return redirect()->route('pembeli.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(Pembeli $pembeli)
    {
        $pembeli->delete();
        return redirect()->route('pembeli.index')->with('success', 'Data berhasil dihapus.');
    }
}
