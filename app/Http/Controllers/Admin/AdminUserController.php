<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    // ==================== CRUD ADMIN ====================
    /**
     * Menampilkan daftar Admin
     */
    public function index()
    {
        $admins = User::where('role', 'admin')->get();
        $adminCount = $admins->count();
        return view('admin.admins.index', compact('admins', 'adminCount'));
    }

    public function create() // Menampilkan form tambah admin
    {
        return view('admin.admins.create');
    }

    /**
     * Menyimpan Admin baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required',
            'nip'      => 'required|numeric|unique:users,nip',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'name'        => $request->name,
            'nip'         => $request->nip,
            'email'       => $request->email,
            'no_hp'       => $request->no_hp ?: '-',
            'jabatan'     => $request->jabatan ?: '-',
            'nama_satker' => $request->nama_satker ?: '-',
            'role'        => 'admin',
            'password'    => Hash::make($request->password),
        ]);

        return redirect()->route('admin.admins.index')->with('success', 'Admin berhasil ditambahkan.');
    }

    public function edit(string $id) // Menampilkan form edit admin
    {
        $admin = User::findOrFail($id);
        return view('admin.admins.edit', compact('admin'));
    }

    /**
     * Update data Admin
     */
    public function update(Request $request, string $id)
    {
        $admin = User::findOrFail($id);

        $request->validate([
            'name'        => 'required',
            'nip'         => 'required|numeric|unique:users,nip,' . $admin->id,
            'email'       => 'required|email|unique:users,email,' . $admin->id,
            'no_hp'       => 'nullable|string',
            'jabatan'     => 'nullable|string',
            'nama_satker' => 'nullable|string',
            'password'    => 'nullable|min:6|confirmed',
        ]);

        $admin->name        = $request->name;
        $admin->nip         = $request->nip;
        $admin->email       = $request->email;
        $admin->no_hp       = $request->no_hp ?: '-';
        $admin->jabatan     = $request->jabatan ?: '-';
        $admin->nama_satker = $request->nama_satker ?: '-';

        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        $admin->save();

        return redirect()->route('admin.admins.index')->with('success', 'Admin berhasil diperbarui.');
    }

    public function destroy(string $id) // Menghapus admin
    {
        $admin = User::findOrFail($id);
        $admin->delete();

        return redirect()->route('admin.admins.index')->with('success', 'Admin berhasil dihapus.');
    }

    // ==================== CRUD USER BIASA ====================
    /**
     * Menampilkan daftar User
     */
    public function indexUser()
    {
        $users = User::where('role', 'user')->get();
        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    /**
     * Menyimpan User baru (Manual create by Admin)
     */
    public function storeUser(Request $request)
    {
        $request->validate([
            'name'     => 'required',
            'nip'      => 'required|numeric|unique:users,nip',
            'email'    => 'nullable|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'name'        => $request->name,
            'nip'         => $request->nip,
            'email'       => $request->email ?: '-',
            'no_hp'       => $request->no_hp ?: '-',
            'jabatan'     => $request->jabatan ?: '-',
            'nama_satker' => $request->nama_satker ?: '-',
            'role'        => 'user',
            'password'    => Hash::make($request->password),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function editUser(string $id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'        => 'required',
            'nip'         => 'required|numeric|unique:users,nip,' . $user->id,
            'email'       => 'nullable|email|unique:users,email,' . $user->id,
            'no_hp'       => 'nullable|string',
            'jabatan'     => 'nullable|string',
            'nama_satker' => 'nullable|string',
            'password'    => 'nullable|min:6|confirmed',
        ]);

        $user->name        = $request->name;
        $user->nip         = $request->nip;
        $user->email       = $request->email ?: '-';
        $user->no_hp       = $request->no_hp ?: '-';
        $user->jabatan     = $request->jabatan ?: '-';
        $user->nama_satker = $request->nama_satker ?: '-';

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroyUser(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }
}
