<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Tampilkan List User
    public function index()
    {
        $users = User::where('role', '<>', 'admin')->latest()->get();
        return view('pages.pengguna.index', compact('users'));
    }

    // Tampilkan Form Create
    public function create()
    {
        return view('pages.pengguna.create');
    }

    // Simpan Data User Baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:3',
            'status' => 'required|in:active,not_active',
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'status' => $request->status,
        ]);

        return redirect()->route('admin.pengguna.index')->with('success', 'User berhasil ditambahkan!');
    }

    // Tampilkan Form Edit
    public function edit(User $user)
    {
        return view('pages.pengguna.edit', compact('user'));
    }

    // Update Data User
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'status' => 'required|in:active,not_active',
        ]);

        $data = [
            'name' => $request->name,
            'username' => $request->username,
            'status' => $request->status,
        ];

        // Update password cuma kalau diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.pengguna.index')->with('success', 'User berhasil diupdate!');
    }

    // Delete via AJAX
    public function destroy(User $user)
    {
        $user->delete();
        // Return JSON buat ditangkap sama AJAX SweetAlert
        return response()->json([
            'status' => 'success',
            'message' => 'User berhasil dihapus!'
        ]);
    }
}
