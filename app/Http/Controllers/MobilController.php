<?php

namespace App\Http\Controllers;

use App\Models\Mobil;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse; // Tambahkan ini untuk tipe kembalian JsonResponse

class MobilController extends Controller
{
    /**
     * Menampilkan daftar semua mobil dengan paginasi dan filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Mobil::latest(); // Mulai query dengan data terbaru

        // Filter berdasarkan pencarian (search)
        if ($request->has('search') && $request->input('search') != '') {
            $searchTerm = $request->input('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('kode_mobil', 'like', '%' . $searchTerm . '%')
                  ->orWhere('jenis_mobil', 'like', '%' . $searchTerm . '%')
                  ->orWhere('tipe_mobil', 'like', '%' . $searchTerm . '%')
                  ->orWhere('merek_mobil', 'like', '%' . $searchTerm . '%')
                  ->orWhere('nomor_polisi', 'like', '%' . $searchTerm . '%')
                  ->orWhere('warna_mobil', 'like', '%' . $searchTerm . '%')
                  ->orWhere('transmisi', 'like', '%' . $searchTerm . '%')
                  ->orWhere('bahan_bakar', 'like', '%' . $searchTerm . '%')
                  ->orWhere('status_mobil', 'like', '%' . $searchTerm . '%')
                  ->orWhere('ketersediaan', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filter berdasarkan jenis_mobil
        if ($request->has('jenisMobilFilter') && $request->input('jenisMobilFilter') != '') {
            $query->where('jenis_mobil', $request->input('jenisMobilFilter'));
        }

        // Filter berdasarkan merek_mobil
        if ($request->has('merekMobilFilter') && $request->input('merekMobilFilter') != '') {
            $query->where('merek_mobil', $request->input('merekMobilFilter'));
        }

        // Filter berdasarkan transmisi
        if ($request->has('transmisiFilter') && $request->input('transmisiFilter') != '') {
            $query->where('transmisi', $request->input('transmisiFilter'));
        }

        // Filter berdasarkan tahun_pembuatan
        if ($request->has('tahunPembuatanFilter') && $request->input('tahunPembuatanFilter') != '') {
            $query->where('tahun_pembuatan', $request->input('tahunPembuatanFilter'));
        }

        // Filter berdasarkan warna_mobil
        if ($request->has('warnaMobilFilter') && $request->input('warnaMobilFilter') != '') {
            $query->where('warna_mobil', $request->input('warnaMobilFilter'));
        }

        // Filter berdasarkan bahan_bakar
        if ($request->has('bahanBakarFilter') && $request->input('bahanBakarFilter') != '') {
            $query->where('bahan_bakar', $request->input('bahanBakarFilter'));
        }

        // Filter berdasarkan status_mobil
        if ($request->has('statusMobilFilter') && $request->input('statusMobilFilter') != '') {
            $query->where('status_mobil', $request->input('statusMobilFilter'));
        }

        // Filter berdasarkan ketersediaan
        if ($request->has('ketersediaanFilter') && $request->input('ketersediaanFilter') != '') {
            $query->where('ketersediaan', $request->input('ketersediaanFilter'));
        }


        // Menggunakan paginate() untuk memuat data mobil dengan paginasi setelah filter diterapkan
        $mobils = $query->paginate(10); // Misalnya, 10 item per halaman. Anda bisa menyesuaikan angkanya.

        // Menambahkan parameter query ke link paginasi agar filter tetap berlaku saat navigasi halaman
        $mobils->appends($request->query());

        return view('mobil.index', compact('mobils'));
    }

    /**
     * Menampilkan form untuk membuat mobil baru.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Mengembalikan tampilan form pembuatan mobil
        return view('mobil.create');
    }

    /**
     * Menyimpan mobil baru ke database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->merge(['harga_mobil' => str_replace('.', '', $request->input('harga_mobil'))]);

        // Validasi data yang masuk dari form
        $request->validate([
            'jenis_mobil' => 'required|string|max:255',
            'merek_mobil' => 'required|string|max:255',
            'tipe_mobil' => 'required|string|max:255',
            'tahun_pembuatan' => 'required|integer|digits:4',
            'warna_mobil' => 'required|string|max:255',
            'harga_mobil' => 'required|integer|min:0|max:500000000', // Diperbarui
            'bahan_bakar' => 'required|string|max:255',
            'transmisi' => 'required|in:manual,matic',
            'nomor_polisi' => ['required', Rule::unique('mobils', 'nomor_polisi')], // Menggunakan Rule::unique
            'nomor_rangka' => ['required', 'string', 'min:17', Rule::unique('mobils', 'nomor_rangka')], // Menggunakan Rule::unique
            'nomor_mesin' => ['required', 'string', 'min:6', 'max:15', Rule::unique('mobils', 'nomor_mesin')], // Diperbarui dan menggunakan Rule::unique
            'nomor_bpkb' => ['required', 'string', Rule::unique('mobils', 'nomor_bpkb')], // Menggunakan Rule::unique
            'tanggal_masuk' => 'required|date|before_or_equal:today', // Diperbarui
            'status_mobil' => ['required', Rule::in(['baru', 'bekas', 'lunas', 'belum lunas', 'menunggu pembayaran', 'dibatalkan'])], // 'tersedia' dihapus
            'ketersediaan' => ['required', Rule::in(['ada', 'tidak', 'servis', 'terjual'])], // Validasi ketersediaan
            'masa_berlaku_pajak' => 'required|date|after_or_equal:today',
        ], [
            'transmisi.required' => 'Transmisi wajib diisi.',
            'transmisi.in' => 'Pilih salah satu dari opsi transmisi yang tersedia.',
            'nomor_polisi.unique' => 'Nomor Polisi sudah terdaftar.',
            'nomor_rangka.unique' => 'Nomor Rangka sudah terdaftar.',
            'nomor_mesin.unique' => 'Nomor Mesin sudah terdaftar.',
            'nomor_bpkb.unique' => 'Nomor BPKB sudah terdaftar.',
            'jenis_mobil.required' => 'Jenis Mobil wajib diisi.',
            'ketersediaan.in' => 'Pilih salah satu dari opsi yang tersedia untuk Ketersediaan.',
            'harga_mobil.min' => 'Harga mobil minimal Rp 135.000.000.',
            'harga_mobil.max' => 'Harga mobil maksimal Rp 500.000.000.',
            'nomor_mesin.min' => 'Nomor Mesin minimal 6 karakter.',
            'nomor_mesin.max' => 'Nomor Mesin maksimal 15 karakter.',
            'tanggal_masuk.before_or_equal' => 'Tanggal masuk tidak boleh di masa depan.',
        ]);

        // Generate kode mobil otomatis berdasarkan ID mobil terakhir
        $lastMobil = Mobil::latest()->first();
        $newId = $lastMobil ? (int)substr($lastMobil->kode_mobil, 4) + 1 : 1;
        $kodeMobil = 'MBL-' . str_pad($newId, 4, '0', STR_PAD_LEFT);

        // Buat instance mobil baru dan isi datanya
        Mobil::create(array_merge($request->all(), ['kode_mobil' => $kodeMobil]));

        // Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('mobil.index')->with('success', 'Data mobil berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail mobil tertentu.
     * Metode ini akan dipanggil ketika mengakses /mobil/{id}.
     *
     * @param  \App\Models\Mobil  $mobil
     * @return \Illuminate\View\View
     */
    public function show(Mobil $mobil): \Illuminate\View\View
    {
        // Mengembalikan tampilan detail mobil
        return view('mobil.show', compact('mobil'));
    }

    /**
     * Menampilkan form untuk mengedit mobil yang ada.
     *
     * @param  \App\Models\Mobil  $mobil
     * @return \Illuminate\View\View
     */
    public function edit(Mobil $mobil)
    {
        // Memuat semua data mobil jika dropdown di _form.blade memerlukan data ini (disesuaikan)
        $mobils = Mobil::all();
        // Memecah nomor_polisi untuk mengisi dropdown dan input terpisah di form edit
        $parts = explode(' ', $mobil->nomor_polisi, 2);
        $mobil->kode_wilayah = $parts[0] ?? '';
        $mobil->nomor_polisi_selanjutnya = $parts[1] ?? '';

        return view('mobil.edit', compact('mobil', 'mobils'));
    }

    /**
     * Memperbarui data mobil di database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Mobil  $mobil
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Mobil $mobil)
    {
        $request->merge(['harga_mobil' => str_replace('.', '', $request->input('harga_mobil'))]);

        // Validasi data yang masuk untuk pembaruan
        $request->validate([
            'jenis_mobil' => 'required|string|max:255',
            'merek_mobil' => 'required|string|max:255',
            'tipe_mobil' => 'required|string|max:255',
            'tahun_pembuatan' => 'required|integer|digits:4|min:1901|max:2155', // Diperbarui
            'warna_mobil' => 'required|string|max:255',
            'harga_mobil' => 'required|integer|min:0|max:500000000', // Diperbarui
            'bahan_bakar' => 'required|string|max:255',
            'transmisi' => 'required|in:manual,matic',
            'nomor_polisi' => ['required', Rule::unique('mobils', 'nomor_polisi')->ignore($mobil->id)], // Menggunakan Rule::unique
            'nomor_rangka' => ['required', 'string', 'min:17', Rule::unique('mobils', 'nomor_rangka')->ignore($mobil->id)], // Menggunakan Rule::unique
            'nomor_mesin' => ['required', 'string', 'min:6', 'max:15', Rule::unique('mobils', 'nomor_mesin')->ignore($mobil->id)], // Diperbarui dan menggunakan Rule::unique
            'nomor_bpkb' => ['required', 'string', Rule::unique('mobils', 'nomor_bpkb')->ignore($mobil->id)], // Menggunakan Rule::unique
            'tanggal_masuk' => 'required|date|before_or_equal:today', // Diperbarui
            'status_mobil' => ['required', Rule::in(['baru', 'bekas', 'lunas', 'belum lunas', 'menunggu pembayaran', 'dibatalkan'])], // 'tersedia' dihapus
            'ketersediaan' => ['required', Rule::in(['ada', 'tidak', 'servis', 'terjual'])], // Validasi ketersediaan
            'masa_berlaku_pajak' => 'required|date|after_or_equal:today',
        ], [
            'transmisi.required' => 'Transmisi wajib diisi.',
            'transmisi.in' => 'Pilih salah satu dari opsi transmisi yang tersedia.',
            'nomor_polisi.unique' => 'Nomor Polisi sudah terdaftar.',
            'nomor_rangka.unique' => 'Nomor Rangka sudah terdaftar.',
            'nomor_mesin.unique' => 'Nomor Mesin sudah terdaftar.',
            'nomor_bpkb.unique' => 'Nomor BPKB sudah terdaftar.',
            'jenis_mobil.required' => 'Jenis Mobil wajib diisi.',
            'ketersediaan.in' => 'Pilih salah satu dari opsi yang tersedia untuk Ketersediaan.',
            'harga_mobil.min' => 'Harga mobil minimal Rp 135.000.000.',
            'harga_mobil.max' => 'Harga mobil maksimal Rp 500.000.000.',
            'tahun_pembuatan.min' => 'Tahun pembuatan minimal 1901.',
            'tahun_pembuatan.max' => 'Tahun pembuatan maksimal 2155.',
            'nomor_mesin.min' => 'Nomor Mesin minimal 6 karakter.',
            'nomor_mesin.max' => 'Nomor Mesin maksimal 15 karakter.',
            'tanggal_masuk.before_or_equal' => 'Tanggal masuk tidak boleh di masa depan.',
        ]);

        // Perbarui data mobil
        $mobil->update($request->all());

        // Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('mobil.index')->with('success', 'Data mobil berhasil diperbarui.');
    }

    /**
     * Menghapus mobil dari database.
     *
     * @param  \App\Models\Mobil  $mobil
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Mobil $mobil)
    {
        // Hapus data mobil
        $mobil->delete();

        // Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('mobil.index')->with('success', 'Data mobil berhasil dihapus.');
    }
}
