<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use App\Models\Divisi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KelolaLayananController extends Controller
{
    /**
     * Menampilkan daftar layanan yang tersedia
     * - Mendukung filter berdasarkan Divisi, Status (Aktif/Nonaktif), dan Pencarian nama
     * - Menghitung statistik ringkas untuk ditampilkan di atas tabel
     */
    public function index(Request $request)
    {
        $query = Layanan::with('divisi');

        // Filter Type (now using divisi_id)
        if ($request->filled('divisi_id')) {
            $query->where('divisi_id', $request->divisi_id);
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

        // Statistik
        $stats = [
            'total' => Layanan::count(),
            'total_types' => Divisi::count(),
            'active' => Layanan::where('is_active', true)->count(),
            'inactive' => Layanan::where('is_active', false)->count(),
        ];

        // Load all divisis for dropdown and tabs
        $divisis = Divisi::orderBy('nama')->get();

        return view('admin.layanan.index', compact('layanans', 'stats', 'divisis'));
    }

    /**
     * Menampilkan form tambah layanan baru
     */
    public function create()
    {
        $divisis = Divisi::where('is_active', true)->orderBy('nama')->get();
        return view('admin.layanan.create', compact('divisis'));
    }

    /**
     * Menyimpan layanan baru ke database
     * - Validasi nama layanan agar unik
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'divisi_id' => 'required|exists:divisis,id',
            'jenis_layanan' => 'required|string|max:255|unique:layanans,jenis_layanan',
            'deskripsi' => 'nullable|string|max:150',
        ], [
            'divisi_id.required' => 'Divisi harus dipilih.',
            'divisi_id.exists' => 'Divisi tidak valid.',
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
     * Menampilkan form edit layanan
     */
    public function edit(Layanan $layanan)
    {
        $divisis = Divisi::where('is_active', true)->orderBy('nama')->get();
        return view('admin.layanan.edit', compact('layanan', 'divisis'));
    }

    /**
     * Update data layanan
     */
    public function update(Request $request, Layanan $layanan)
    {
        $validated = $request->validate([
            'divisi_id' => 'required|exists:divisis,id',
            'jenis_layanan' => 'required|string|max:255|unique:layanans,jenis_layanan,' . $layanan->id,
            'deskripsi' => 'nullable|string|max:150',
        ], [
            'divisi_id.required' => 'Divisi harus dipilih.',
            'divisi_id.exists' => 'Divisi tidak valid.',
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
     * Menghapus layanan
     */
    public function destroy(Layanan $layanan)
    {
        $layanan->delete();

        return redirect()->route('admin.layanan.index')
            ->with('success', 'Layanan berhasil dihapus!');
    }

    /**
     * Mengubah status aktif/nonaktif secara cepat (AJAX Toggle)
     * - Mengembalikan response JSON agar halaman tidak perlu reload
     */
    public function toggleStatus(Request $request, Layanan $layanan)
    {
        $layanan->is_active = !$layanan->is_active;
        $layanan->save();

        // Return JSON for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_active' => $layanan->is_active,
                'message' => 'Status layanan berhasil diperbarui!'
            ]);
        }

        return redirect()->route('admin.layanan.index', ['tab' => 'layanan'])
            ->with('success', 'Status layanan berhasil diperbarui!');
    }
}
