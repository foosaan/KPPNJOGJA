<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use Illuminate\Http\Request;

class KelolaLayananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index(Request $request)
{
    $query = Layanan::query();

    // Filter Type
    if ($request->filled('type')) {
        $query->where('layanan_type', $request->type);
    }

    // Filter Status
    if ($request->filled('status')) {
        $query->where('is_active', (bool) $request->status);
    }

    // Search
    if ($request->filled('search')) {
        $query->where('jenis_layanan', 'like', '%' . $request->search . '%');
    }

    $layanans = $query->latest()->paginate(15);

    // Statistik - Hitung jumlah tipe yang berbeda secara otomatis
    $stats = [
        'total' => Layanan::count(),
        'total_types' => Layanan::distinct('layanan_type')->count('layanan_type'), // Jumlah tipe berbeda
        'active' => Layanan::where('is_active', true)->count(),
        'inactive' => Layanan::where('is_active', false)->count(),
    ];

    return view('admin.layanan.index', compact('layanans', 'stats'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.layanan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'layanan_type' => 'required|string|max:50',
            'jenis_layanan' => 'required|string|max:255|unique:layanans,jenis_layanan',
            'deskripsi' => 'nullable|string|max:150',
        ], [
            'layanan_type.required' => 'Tipe layanan harus dipilih.',
            'jenis_layanan.required' => 'Jenis layanan harus diisi.',
            'jenis_layanan.unique' => 'Jenis layanan sudah digunakan.',
            'deskripsi.max' => 'Deskripsi maksimal 150 karakter.',
        ]);

        // Checkbox -> true jika dicentang, false jika tidak
        $validated['is_active'] = $request->boolean('is_active');

        Layanan::create($validated);

        return redirect()->route('admin.layanan.index')
            ->with('success', 'Layanan berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Layanan $layanan)
    {
        return view('admin.layanan.edit', compact('layanan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Layanan $layanan)
    {
        $validated = $request->validate([
            'layanan_type' => 'required|string|max:100',
            'jenis_layanan' => 'required|string|max:255|unique:layanans,jenis_layanan,' . $layanan->id,
            'deskripsi' => 'nullable|string|max:150',
        ], [
            'layanan_type.required' => 'Tipe layanan harus diisi.',
            'jenis_layanan.required' => 'Jenis layanan harus diisi.',
            'jenis_layanan.unique' => 'Jenis layanan sudah digunakan.',
            'deskripsi.max' => 'Deskripsi maksimal 150 karakter.',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $layanan->update($validated);

        return redirect()->route('admin.layanan.index')
            ->with('success', 'Layanan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Layanan $layanan)
    {
        $layanan->delete();

        return redirect()->route('admin.layanan.index')
            ->with('success', 'Layanan berhasil dihapus!');
    }

    /**
     * Toggle aktif/nonaktif
     */
    public function toggleStatus(Layanan $layanan)
    {
        $layanan->is_active = !$layanan->is_active;
        $layanan->save();

        return back()->with('success', 'Status layanan berhasil diperbarui!');
    }
}
