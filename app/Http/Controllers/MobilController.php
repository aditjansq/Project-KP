<?php

namespace App\Http\Controllers;

use App\Models\Mobil;
use Illuminate\Http\Request;

class MobilController extends Controller
{
    public function index() {
        $mobils = Mobil::latest()->get();
        return view('mobil.index', compact('mobils'));
    }

    public function create() {
        return view('mobil.create');
    }

    public function store(Request $request) {
        $request->validate([
            'tipe_mobil' => 'required',
            'merek_mobil' => 'required',
            'tahun_pembuatan' => 'required|digits:4',
            'warna_mobil' => 'required',
            'harga_mobil' => 'required|numeric',
            'bahan_bakar' => 'required',
            'nomor_polisi' => 'required|unique:mobils',
            'nomor_rangka' => 'required|unique:mobils',
            'nomor_mesin' => 'required|unique:mobils',
            'nomor_bpkb' => 'required|unique:mobils',
            'tanggal_masuk' => 'required|date',
            'status_mobil' => 'required|in:baru,bekas',
            'stok' => 'required|in:ada,tidak',
        ]);

        $last = Mobil::latest()->first();
        $kode = 'MBL-' . str_pad(optional($last)->id + 1, 4, '0', STR_PAD_LEFT);

        Mobil::create(array_merge(
            $request->all(),
            ['kode_mobil' => $kode]
        ));

        return redirect()->route('mobil.index')->with('success', 'Data mobil berhasil ditambahkan.');
    }

    public function edit(Mobil $mobil) {
        return view('mobil.edit', compact('mobil'));
    }

public function update(Request $request, Mobil $mobil)
{
    $request->validate([
        'tipe_mobil' => 'required',
        'merek_mobil' => 'required',
        'tahun_pembuatan' => 'required|digits:4|integer|min:1901|max:2155',
        'warna_mobil' => 'required',
        'harga_mobil' => 'required|numeric',
        'bahan_bakar' => 'required',
        'nomor_polisi' => 'required',
        'nomor_rangka' => 'required',
        'nomor_mesin' => 'required',
        'nomor_bpkb' => 'required',
        'tanggal_masuk' => 'required|date',
        'status_mobil' => 'required|in:baru,bekas',
        'stok' => 'required|in:ada,tidak',
    ]);

    $mobil->update([
        'tipe_mobil' => $request->tipe_mobil,
        'merek_mobil' => $request->merek_mobil,
        'tahun_pembuatan' => intval($request->tahun_pembuatan),
        'warna_mobil' => $request->warna_mobil,
        'harga_mobil' => $request->harga_mobil,
        'bahan_bakar' => $request->bahan_bakar,
        'nomor_polisi' => $request->nomor_polisi,
        'nomor_rangka' => $request->nomor_rangka,
        'nomor_mesin' => $request->nomor_mesin,
        'nomor_bpkb' => $request->nomor_bpkb,
        'tanggal_masuk' => $request->tanggal_masuk,
        'status_mobil' => $request->status_mobil,
        'stok' => $request->stok,
    ]);

    return redirect()->route('mobil.index')->with('success', 'Data mobil berhasil diperbarui.');
}


    public function destroy(Mobil $mobil) {
        $mobil->delete();
        return redirect()->route('mobil.index')->with('success', 'Data mobil berhasil dihapus.');
    }
}
