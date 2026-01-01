<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Divisi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class AdminStaffController extends Controller
{
    public function index()
    {
        $staffs = User::where('role', 'staff')->get();
        return view('admin.staffs.index', compact('staffs'));
    }

    public function create()
    {
        $divisiList = Divisi::where('is_active', true)->orderBy('nama')->pluck('nama')->toArray();
        return view('admin.staffs.create', compact('divisiList'));
    }

    public function store(Request $request)
    {
        $divisiNames = Divisi::where('is_active', true)->pluck('nama')->toArray();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email'),
            ],
            'nip' => [
                'required',
                'string',
                Rule::unique('users', 'nip'),
            ],
            'divisi' => [
                'required',
                Rule::in($divisiNames),
            ],
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'nip' => $validated['nip'],
            'divisi' => $validated['divisi'],
            'role' => 'staff',
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.staffs.index')->with('success', 'Staff berhasil ditambahkan.');
    }

    public function edit(User $staff)
    {
        $divisiList = Divisi::where('is_active', true)->orderBy('nama')->pluck('nama')->toArray();
        return view('admin.staffs.edit', compact('staff', 'divisiList'));
    }

    public function update(Request $request, User $staff)
    {
        $divisiNames = Divisi::where('is_active', true)->pluck('nama')->toArray();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($staff->id),
            ],
            'nip' => [
                'required',
                'string',
                Rule::unique('users', 'nip')->ignore($staff->id),
            ],
            'divisi' => [
                'required',
                Rule::in($divisiNames),
            ],
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $staff->name = $validated['name'];
        $staff->email = $validated['email'];
        $staff->nip = $validated['nip'];
        $staff->divisi = $validated['divisi'];

        if (!empty($validated['password'])) {
            $staff->password = Hash::make($validated['password']);
        }

        $staff->save();

        return redirect()->route('admin.staffs.index')->with('success', 'Staff berhasil diperbarui.');
    }

    public function destroy(User $staff)
    {
        $staff->delete();
        return redirect()->route('admin.staffs.index')->with('success', 'Staff berhasil dihapus.');
    }
}
