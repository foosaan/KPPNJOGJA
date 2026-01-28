<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
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

        // Get all active divisions for sidebar
        $divisis = Divisi::where('is_active', true)->get();

        return view('user.layanan.create', [
            'divisi' => $divisi,
            'divisis' => $divisis,
            'jenis_layanan' => $jenis_layanan,
            'userNip' => Auth::user()->nip,
            'noBerkasPreview' => strtoupper($divisi->slug) . '-' . Carbon::now()->format('YmdHis')
        ]);
    }

    /**
     * Store new layanan request
     */
    /**
     * Menyimpan Data Pengajuan Layanan Baru dari User
     * Alur:
     * 1. Validasi input (tipe layanan, keterangan, file).
     * 2. Generate Nomor Berkas otomatis (Format: DIVISI-YYYYMMDD-001).
     * 3. Upload file ke folder server dan rename agar rapi.
     * 4. Simpan data ke database.
     */
    public function store(Request $request, $slug)
    {
        // Cek apakah divisi tujuan valid
        $divisi = Divisi::where('slug', $slug)->where('is_active', true)->first();

        if (!$divisi) {
            abort(404, 'Divisi tidak ditemukan');
        }

        // 1. VALIDASI INPUT
        $request->validate([
            'jenis_layanan' => 'required|string|exists:layanans,jenis_layanan',
            'keterangan' => 'required|string',
            'file_upload' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,zip,rar',
        ], [
            'file_upload.mimes' => 'Format file harus PDF, DOC, DOCX, XLS, XLSX, JPG, PNG, ZIP, atau RAR',
        ]);

        // 2. GENERATE NOMOR BERKAS
        $prefix = strtoupper($divisi->slug);
        $today = Carbon::now()->format('Ymd');
        
        // Hitung jumlah berkas hari ini untuk nomor urut
        $countToday = BerkasLayanan::whereDate('created_at', Carbon::today())
            ->where('divisi_id', $divisi->id)
            ->count() + 1;
            
        // Gabungkan menjadi: NAMADIVISI-20231010-001
        $noBerkas = $prefix . '-' . $today . '-' . str_pad($countToday, 3, '0', STR_PAD_LEFT);

        // 3. PROSES UPLOAD FILE
        $file = $request->file('file_upload');
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        // Bersihkan nama file dari karakter aneh
        $cleanName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $originalName);
        $cleanName = Str::limit($cleanName, 80, '');
        $ext = $file->getClientOriginalExtension();
        
        // Nama file baru: NO_BERKAS + NAMA_ASLI
        $newFileName = $noBerkas . '-' . $cleanName . '.' . $ext;
        $filePath = $file->storeAs('uploads/layanan', $newFileName, 'public');

        // 4. SIMPAN KE DATABASE
        BerkasLayanan::create([
            'no_berkas' => $noBerkas,
            'divisi_id' => $divisi->id,
            'id_satker' => Auth::user()->nip, // NIP Pengirim
            'jenis_layanan' => $request->jenis_layanan,
            'keterangan' => $request->keterangan,
            'file_path' => $filePath,
            'original_filename' => $file->getClientOriginalName(),
            'user_id' => Auth::id(),
            'status' => 'baru', // Status awal selalu 'baru'
        ]);

        return back()->with('success', 'Layanan ' . $divisi->nama . ' berhasil dikirim.');
    }
}
