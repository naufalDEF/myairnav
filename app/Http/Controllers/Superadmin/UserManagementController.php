<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    // Menampilkan daftar user
    public function index()
    {
        $users = User::where('role', '!=', 'superadmin')->get(); // Exclude Superadmin
        return view('superadmin.users.index', compact('users'));
    }

    // Menampilkan form tambah user
    public function create()
    {
        return view('superadmin.users.create');
    }

    // Menyimpan user baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,user', // Hanya bisa admin & user biasa
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('superadmin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    // Menampilkan form edit user (Pastikan tidak bisa edit Superadmin)
    public function edit(User $user)
    {
        if ($user->role === 'superadmin') {
            return redirect()->route('superadmin.users.index')->with('error', 'Tidak bisa mengedit Superadmin.');
        }

        return view('superadmin.users.edit', compact('user'));
    }

    // Menyimpan perubahan user ke database
    public function update(Request $request, User $user)
    {
        if ($user->role === 'superadmin') {
            return redirect()->route('superadmin.users.index')->with('error', 'Tidak bisa mengedit Superadmin.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,user', // Hanya admin & user biasa
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        return redirect()->route('superadmin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    // Menghapus user dari database
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('superadmin.users.index')->with('success', 'User berhasil dihapus.');
    }
}
