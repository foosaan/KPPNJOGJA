<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class AdminStaffController extends Controller
{
    private $divisiList = [
        'MSKI',
        'VERA',
        'BANK',
        'PD',
        'UMUM',
    ];

    public function index()
    {
        $staffs = User::where('role', 'staff')->get();
return view('admin.staffs.index', compact('staffs'));

    }

    public function create()
    {
        $divisiList = $this->divisiList;
        return view('admin.staffs.create', compact('divisiList'));
    }

    public function store(Request $request)
    {
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
                Rule::in($this->divisiList),
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
        $divisiList = $this->divisiList;
        return view('admin.staffs.edit', compact('staff', 'divisiList'));
    }

    public function update(Request $request, User $staff)
    {
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
                Rule::in($this->divisiList),
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
