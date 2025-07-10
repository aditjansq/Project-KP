<?php // Baris ini harus menjadi yang pertama di file

namespace App\Http\Controllers; // Ini harus langsung setelah <?php

use App\Models\User;
use App\Models\Mobil;
use App\Models\Transaksi; // Import model Transaksi
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

        // Hitung total transaksi pembeli (transaksi yang memiliki pembeli_id)
        $totalTransaksiPembeli = Transaksi::whereNotNull('pembeli_id')->count();

        // Hitung total transaksi penjual (transaksi yang memiliki penjual_id)
        $totalTransaksiPenjual = Transaksi::whereNotNull('penjual_id')->count();

        return view('roles.manajer', compact('users', 'totalUsers', 'totalMobil', 'totalTransaksiPembeli', 'totalTransaksiPenjual')); // Kirimkan semua variabel ke view
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
            'password' => 'required|string|min:8|confirmed',
            'job' => ['required', 'string', Rule::in(['admin', 'manajer', 'sales'])],
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
