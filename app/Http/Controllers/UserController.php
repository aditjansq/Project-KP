<?php // Baris ini harus menjadi yang pertama di file

namespace App\Http\Controllers; // Ini harus langsung setelah <?php

use App\Models\User;
use App\Models\Mobil;
use App\Models\TransaksiPembelian; // Import model TransaksiPembelian
use App\Models\TransaksiPenjualan; // Import model TransaksiPenjualan
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(10);
        $totalUsers = User::count();
        $totalMobil = Mobil::count();

        // Menghitung total transaksi pembelian
        $totalTransaksiPembelian = TransaksiPembelian::count();

        // Menghitung total transaksi penjualan
        $totalTransaksiPenjualan = TransaksiPenjualan::count();

        // Menghitung total mobil berdasarkan ketersediaan
        $totalMobilTerjual = Mobil::where('ketersediaan', 'terjual')->count();
        $totalMobilTersedia = Mobil::whereIn('ketersediaan', ['ada', 'servis'])->count(); // Diperbarui untuk menyertakan 'servis'
        $totalMobilServis = Mobil::where('ketersediaan', 'servis')->count();

        return view('roles.manajer', compact(
            'users',
            'totalUsers',
            'totalMobil',
            'totalTransaksiPembelian',
            'totalTransaksiPenjualan', // Variabel ini telah disesuaikan menjadi 'totalTransaksiPenjualan' agar konsisten
            'totalMobilTerjual',
            'totalMobilTersedia',
            'totalMobilServis'
        ));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'job' => ['required', 'string', Rule::in(['admin', 'manajer', 'sales'])],
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'job' => $request->job,
            'is_verified' => true,
        ]);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan!');
    }

    public function show(User $user)
    {
        return response()->json($user);
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'job' => ['required', 'string', Rule::in(['admin', 'manajer', 'sales'])],
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->job = $request->job;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil diperbarui!');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus!');
    }
}
