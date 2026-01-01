<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use App\Models\Layanan;
use App\Models\BerkasLayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class LayananGenerikController extends Controller
{
    /**
     * Show create form for a specific divisi
     */
    public function create($slug)
    {
        $divisi = Divisi::where('slug', $slug)->where('is_active', true)->first();

        if (!$divisi) {
            abort(404, 'Divisi tidak ditemukan');
        }

        // Get active layanan for this divisi
        $jenis_layanan = Layanan::where('divisi_id', $divisi->id)
            ->where('is_active', true)
            ->pluck('jenis_layanan')
            ->toArray();

        return view('user.layanan.create', [
            'divisi' => $divisi,
            'jenis_layanan' => $jenis_layanan,
            'userNip' => Auth::user()->nip,
            'noBerkasPreview' => strtoupper($divisi->slug) . '-' . Carbon::now()->format('YmdHis')
        ]);
    }

    /**
     * Store new layanan request
     */
    public function store(Request $request, $slug)
    {
        $divisi = Divisi::where('slug', $slug)->where('is_active', true)->first();

        if (!$divisi) {
            abort(404, 'Divisi tidak ditemukan');
        }

        $request->validate([
            'jenis_layanan' => 'required|string|exists:layanans,jenis_layanan',
            'keterangan' => 'required|string',
            'file_upload' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,zip,rar',
        ], [
            'file_upload.mimes' => 'Format file harus PDF, DOC, DOCX, XLS, XLSX, JPG, PNG, ZIP, atau RAR',
        ]);

        // Generate no_berkas
        $prefix = strtoupper($divisi->slug);
        $today = Carbon::now()->format('Ymd');
        $countToday = BerkasLayanan::whereDate('created_at', Carbon::today())
            ->where('divisi_id', $divisi->id)
            ->count() + 1;
        $noBerkas = $prefix . '-' . $today . '-' . str_pad($countToday, 3, '0', STR_PAD_LEFT);

        // File upload
        $file = $request->file('file_upload');
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $originalName = preg_replace('/^' . $prefix . '-\d{8}-\d{3}-?/i', '', $originalName);
        $cleanName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $originalName);
        $cleanName = Str::limit($cleanName, 80, '');
        $ext = $file->getClientOriginalExtension();
        $newFileName = $noBerkas . '-' . $cleanName . '.' . $ext;
        $filePath = $file->storeAs('uploads/layanan', $newFileName, 'public');

        // Save to BerkasLayanan table with divisi_id
        BerkasLayanan::create([
            'no_berkas' => $noBerkas,
            'divisi_id' => $divisi->id,
            'id_satker' => Auth::user()->nip,
            'jenis_layanan' => $request->jenis_layanan,
            'keterangan' => $request->keterangan,
            'file_path' => $filePath,
            'original_filename' => $file->getClientOriginalName(),
            'user_id' => Auth::id(),
            'status' => 'baru',
        ]);

        return back()->with('success', 'Layanan ' . $divisi->nama . ' berhasil dikirim.');
    }
}
