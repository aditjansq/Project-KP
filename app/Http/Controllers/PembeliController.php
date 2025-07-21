<?php

namespace App\Http\Controllers;

use App\Models\Pembeli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PembeliController extends Controller
{
    // Menampilkan daftar pembeli
    public function index(Request $request) // Tambahkan parameter Request $request
    {
        $search = $request->query('search'); // Ambil nilai 'search' dari URL

        $pembelis = Pembeli::query(); // Mulai query Pembeli

        // Terapkan filter pencarian jika ada
        if ($search) {
            $pembelis->where(function($query) use ($search) {
                $query->where('kode_pembeli', 'like', '%' . $search . '%')
                      ->orWhere('nama', 'like', '%' . $search . '%')
                      ->orWhere('no_telepon', 'like', '%' . $search . '%')
                      ->orWhere('alamat', 'like', '%' . $search . '%')
                      ->orWhere('pekerjaan', 'like', '%' . $search . '%')
                      // Jika Anda ingin mencari berdasarkan bagian dari tanggal (misal 'Apr'),
                      // Anda perlu mengonversi tanggal_lahir ke string
                      ->orWhereRaw("DATE_FORMAT(tanggal_lahir, '%d %b %Y') LIKE ?", ['%' . $search . '%']);
            });
        }

        $pembelis = $pembelis->paginate(1); // Lakukan paginasi setelah filter diterapkan

        // Kirim nilai pencarian kembali ke view agar input tetap terisi
        return view('pembeli.index', compact('pembelis', 'search'));
    }

    // Menampilkan form untuk membuat pembeli baru
    public function create()
    {
        $lastPembeli = Pembeli::latest()->first();
        if ($lastPembeli) {
            $lastKode = $lastPembeli->kode_pembeli;
            if (str_starts_with($lastKode, 'PLB-')) {
                $lastNumber = (int) substr($lastKode, 4);
            } else {
                $lastNumber = 0;
            }
            $newCode = 'PLB-' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newCode = 'PLB-' . str_pad(1, 4, '0', STR_PAD_LEFT); // Gunakan 1 bukan 0
        }
        return view('pembeli.create', compact('newCode'));
    }

    // Menyimpan data pembeli baru
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'kode_pembeli' => 'required|string|unique:pembelis,kode_pembeli|max:255',
            'nama' => 'required|string|min:3|regex:/^[A-Za-z\s]+$/',
            'tanggal_lahir' => 'required|date|before_or_equal:today',
            'no_telepon' => 'nullable|digits_between:10,15',
            'alamat' => 'required|string|min:4',
            'pekerjaan' => 'nullable|string|min:4|regex:/^[A-Za-z\s]+$/',
            'ktp_pasangan' => 'nullable|file|mimes:jpeg,png,pdf|max:2048',
            'kartu_keluarga' => 'nullable|file|mimes:jpeg,png,pdf|max:2048',
            'slip_gaji' => 'nullable|file|mimes:jpeg,png,pdf|max:2048',
        ]);

        $data = $request->except(['_token', 'ktp_pasangan', 'kartu_keluarga', 'slip_gaji']);
        $kodePembeli = $request->input('kode_pembeli');

        // Jalur penyimpanan dasar relatif terhadap root disk 'public' (storage/app/public)
        $storageFolder = 'documents/pembeli';

        // Tangani unggahan KTP Pasangan
        if ($request->hasFile('ktp_pasangan')) {
            $file = $request->file('ktp_pasangan');
            $fileName = $kodePembeli . '-KTP_PASANGAN-' . Carbon::now()->format('YmdHis') . '.' . $file->getClientOriginalExtension();
            // Simpan file ke dalam 'documents/pembeli' di disk 'public'
            $data['ktp_pasangan'] = Storage::disk('public')->putFileAs($storageFolder, $file, $fileName);
        }

        // Tangani unggahan Kartu Keluarga
        if ($request->hasFile('kartu_keluarga')) {
            $file = $request->file('kartu_keluarga');
            $fileName = $kodePembeli . '-KARTU_KELUARGA-' . Carbon::now()->format('YmdHis') . '.' . $file->getClientOriginalExtension();
            $data['kartu_keluarga'] = Storage::disk('public')->putFileAs($storageFolder, $file, $fileName);
        }

        // Tangani unggahan Slip Gaji
        if ($request->hasFile('slip_gaji')) {
            $file = $request->file('slip_gaji');
            $fileName = $kodePembeli . '-SLIP_GAJI-' . Carbon::now()->format('YmdHis') . '.' . $file->getClientOriginalExtension();
            $data['slip_gaji'] = Storage::disk('public')->putFileAs($storageFolder, $file, $fileName);
        }

        Pembeli::create($data);

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
        $validatedData = $request->validate([
            'nama' => 'required|string|min:3|regex:/^[A-Za-z\s]+$/',
            'tanggal_lahir' => 'required|date|before_or_equal:today',
            'no_telepon' => 'nullable|digits_between:10,15',
            'alamat' => 'required|string|min:4',
            'pekerjaan' => 'nullable|string|min:4|regex:/^[A-Za-z\s]+$/',
            'ktp_pasangan' => 'nullable|file|mimes:jpeg,png,pdf|max:2048',
            'kartu_keluarga' => 'nullable|file|mimes:jpeg,png,pdf|max:2048',
            'slip_gaji' => 'nullable|file|mimes:jpeg,png,pdf|max:2048',
        ]);

        $data = $request->except(['_token', '_method', 'ktp_pasangan', 'kartu_keluarga', 'slip_gaji']);
        $kodePembeli = $pembeli->kode_pembeli;

        // Jalur penyimpanan dasar relatif terhadap root disk 'public' (storage/app/public)
        $storageFolder = 'documents/pembeli';

        // Tangani unggahan KTP Pasangan
        if ($request->hasFile('ktp_pasangan')) {
            // Hapus file lama jika ada
            if ($pembeli->ktp_pasangan && Storage::disk('public')->exists($pembeli->ktp_pasangan)) {
                Storage::disk('public')->delete($pembeli->ktp_pasangan);
            }
            $file = $request->file('ktp_pasangan');
            $fileName = $kodePembeli . '-KTP_PASANGAN-' . Carbon::now()->format('YmdHis') . '.' . $file->getClientOriginalExtension();
            $data['ktp_pasangan'] = Storage::disk('public')->putFileAs($storageFolder, $file, $fileName);
        }

        // Tangani unggahan Kartu Keluarga
        if ($request->hasFile('kartu_keluarga')) {
            // Hapus file lama jika ada
            if ($pembeli->kartu_keluarga && Storage::disk('public')->exists($pembeli->kartu_keluarga)) {
                Storage::disk('public')->delete($pembeli->kartu_keluarga);
            }
            $file = $request->file('kartu_keluarga');
            $fileName = $kodePembeli . '-KARTU_KELUARGA-' . Carbon::now()->format('YmdHis') . '.' . $file->getClientOriginalExtension();
            $data['kartu_keluarga'] = Storage::disk('public')->putFileAs($storageFolder, $file, $fileName);
        }

        // Tangani unggahan Slip Gaji
        if ($request->hasFile('slip_gaji')) {
            // Hapus file lama jika ada
            if ($pembeli->slip_gaji && Storage::disk('public')->exists($pembeli->slip_gaji)) {
                Storage::disk('public')->delete($pembeli->slip_gaji);
            }
            $file = $request->file('slip_gaji');
            $fileName = $kodePembeli . '-SLIP_GAJI-' . Carbon::now()->format('YmdHis') . '.' . $file->getClientOriginalExtension();
            $data['slip_gaji'] = Storage::disk('public')->putFileAs($storageFolder, $file, $fileName);
        }

        $pembeli->update($data);

        return redirect()->route('pembeli.index')->with('success', 'Pembeli berhasil diperbarui.');
    }

    // Menghapus data pembeli
    public function destroy(Pembeli $pembeli)
    {
        // Hapus file terkait sebelum menghapus pembeli
        if ($pembeli->ktp_pasangan && Storage::disk('public')->exists($pembeli->ktp_pasangan)) {
            Storage::disk('public')->delete($pembeli->ktp_pasangan);
        }
        if ($pembeli->kartu_keluarga && Storage::disk('public')->exists($pembeli->kartu_keluarga)) {
            Storage::disk('public')->delete($pembeli->kartu_keluarga);
        }
        if ($pembeli->slip_gaji && Storage::disk('public')->exists($pembeli->slip_gaji)) {
            Storage::disk('public')->delete($pembeli->slip_gaji);
        }

        $pembeli->delete();
        return redirect()->route('pembeli.index')->with('success', 'Pembeli berhasil dihapus.');
    }
}
