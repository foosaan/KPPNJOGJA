<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use Illuminate\Http\Request;

class DivisiController extends Controller
{
    /**
     * Store a newly created divisi
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50|unique:divisis,nama',
            'deskripsi' => 'nullable|string|max:255',
        ], [
            'nama.required' => 'Nama divisi harus diisi.',
            'nama.unique' => 'Nama divisi sudah digunakan.',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        Divisi::create($validated);

        return redirect()->route('admin.layanan.index')
            ->with('success', 'Divisi berhasil ditambahkan!');
    }

    /**
     * Update the specified divisi
     */
    public function update(Request $request, Divisi $divisi)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50|unique:divisis,nama,' . $divisi->id,
            'deskripsi' => 'nullable|string|max:255',
        ], [
            'nama.required' => 'Nama divisi harus diisi.',
            'nama.unique' => 'Nama divisi sudah digunakan.',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $divisi->update($validated);

        return redirect()->route('admin.layanan.index')
            ->with('success', 'Divisi berhasil diperbarui!');
    }

    /**
     * Remove the specified divisi
     */
    public function destroy(Divisi $divisi)
    {
        // Check if divisi has layanans
        if ($divisi->layanans()->count() > 0) {
            return redirect()->route('admin.layanan.index')
                ->with('error', 'Tidak dapat menghapus divisi yang masih memiliki layanan!');
        }

        $divisi->delete();

        return redirect()->route('admin.layanan.index')
            ->with('success', 'Divisi berhasil dihapus!');
    }

    /**
     * Toggle active status with cascade effect on layanans
     * - When OFF: save active layanan IDs, then turn all OFF
     * - When ON: restore only previously active layanans
     */
    public function toggle(Request $request, Divisi $divisi)
    {
        // Toggle divisi status
        $newStatus = !$divisi->is_active;
        $divisi->is_active = $newStatus;
        $divisi->save();

        if (!$newStatus) {
            // Divisi sedang di-OFF-kan
            // Simpan daftar layanan yang aktif sebelum di-OFF-kan
            $activeLayananIds = $divisi->layanans()->where('is_active', true)->pluck('id')->toArray();
            
            // Simpan di cache Laravel
            cache()->put("divisi_{$divisi->id}_active_layanans", $activeLayananIds, now()->addDays(30));
            
            // Nonaktifkan semua layanan di divisi ini
            $divisi->layanans()->update(['is_active' => false]);
            
            $message = 'Divisi dinonaktifkan. Semua layanan di divisi ini juga dinonaktifkan.';
        } else {
            // Divisi sedang di-ON-kan
            // Ambil daftar layanan yang sebelumnya aktif
            $cachedLayananIds = cache()->get("divisi_{$divisi->id}_active_layanans", []);
            
            if (!empty($cachedLayananIds)) {
                // Aktifkan hanya layanan yang sebelumnya aktif
                $divisi->layanans()->whereIn('id', $cachedLayananIds)->update(['is_active' => true]);
                
                // Hapus cache
                cache()->forget("divisi_{$divisi->id}_active_layanans");
                
                $message = 'Divisi diaktifkan. Layanan yang sebelumnya aktif telah diaktifkan kembali.';
            } else {
                $message = 'Divisi diaktifkan.';
            }
        }

        // Return JSON for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_active' => $newStatus,
                'message' => $message
            ]);
        }

        return redirect()->route('admin.layanan.index', ['tab' => 'divisi'])
            ->with('success', $message);
    }
}
