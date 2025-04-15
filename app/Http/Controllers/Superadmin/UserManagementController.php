<?php

namespace App\Http\Controllers\Superadmin;

use App\Notifications\DocumentActionNotification;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $role = $request->input('role');

        $query = User::where('role', '!=', 'superadmin');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        if (!empty($role)) {
            $query->where('role', $role);
        }

        $users = $query->latest()->paginate(10);

        return view('superadmin.users.index', compact('users', 'search', 'role'));
    }

    public function create()
    {
        return view('superadmin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,user',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Notifikasi
        Auth::user()->notify(new DocumentActionNotification('User "' . $user->name . '" berhasil ditambahkan.'));

        return redirect()->route('superadmin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        if ($user->role === 'superadmin') {
            return redirect()->route('superadmin.users.index')->with('error', 'Tidak bisa mengedit Superadmin.');
        }

        return view('superadmin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->role === 'superadmin') {
            return redirect()->route('superadmin.users.index')->with('error', 'Tidak bisa mengedit Superadmin.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,user',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        // Notifikasi
        Auth::user()->notify(new DocumentActionNotification('User "' . $user->name . '" berhasil diperbarui.'));

        return redirect()->route('superadmin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $name = $user->name;
        $user->delete();

        // Notifikasi
        Auth::user()->notify(new DocumentActionNotification('User "' . $name . '" berhasil dihapus.'));

        return redirect()->route('superadmin.users.index')->with('success', 'User berhasil dihapus.');
    }
}
